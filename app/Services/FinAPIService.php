<?php

namespace App\Services;

use App\Models\FinAPIAccessToken;
use App\Services\HelperServices;
use Exception;
use FinAPI\Client\Api\AuthorizationApi;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use stdClass;
use App\Models\FinapiPaymentRecipient;
use App\Models\FinapiUser;
use App\Models\Payment;
use App\Services\FinApiLoggerService;
use App\Services\OpenApiEnumModelService;
use Carbon\Carbon;
use FinAPI\Client\Api\PaymentsApi;
use FinAPI\Client\Configuration;
use FinAPI\Client\Model\CreateMoneyTransferParams;
use FinAPI\Client\Model\Currency;
use FinAPI\Client\Model\ISO3166Alpha2Codes;
use FinAPI\Client\Model\MoneyTransferOrderParams;
use FinAPI\Client\Model\MoneyTransferOrderParamsCounterpartAddress;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FinAPIService {

    public function __construct()
    {
        //
    }

    public static function getBaseUrl()
    {
        return config('finApi.finApiServerUrl');
    }

    public static function createUUID()
    {
        return (string) Str::uuid();
    }

    public static function getGrantType() {return config('finApi.grant_type.client_credentials');}
    public static function getClientId(){return config('finApi.default.clientId');}
    public static function getClientSecret(){return  config('finApi.default.clientSecret');}

    public static function getOAuthToken($grantType, $username = null, $password = null)
    {
        $baseUrl = self::getBaseUrl();
        $url = "$baseUrl/api/v2/oauth/token";
        $requestId = self::createUUID();

        $client = new Client();

        $formParams = [
            'grant_type' => $grantType,
            'client_id' => self::getClientId(),
            'client_secret' => self::getClientSecret(),
        ];

        if ($grantType === 'password') {
            $formParams['username'] = $username;
            $formParams['password'] = $password;
        }

        $response = $client->post($url, [
            'headers' => [
                'X-Request-Id' => $requestId,
            ],
            'form_params' => $formParams,
        ]);

        $responseCode = $response->getStatusCode();
        $responseBody = $response->getBody()->getContents();

        FinApiLoggerService::logFinapiRequest($url, ['X-Request-Id' =>  $requestId], $formParams, $responseCode, $responseBody, $requestId);

        if ($responseCode == 200) {
            return json_decode($responseBody);
        }

        dump('No or invalid Access Token!');
        return null;
    }

    public static function createFinApiUser($accessToken, $userDetails)
    {
        $baseUrl =  self::getBaseUrl();
        $url = "$baseUrl/api/v2/users";
        $requestId = self::createUUID();

        $client = new Client();

        $response = $client->post($url, [
            'headers' => [
                'X-Request-Id' => $requestId,
                'Authorization' => 'Bearer ' . $accessToken,
            ],
            'json' => $userDetails,
        ]);

        $responseCode = $response->getStatusCode();
        $responseBody = $response->getBody()->getContents();

        FinApiLoggerService::logFinapiRequest($url, ['X-Request-Id' =>  $requestId], $userDetails, $responseCode, $responseBody, $requestId);

        if ($responseCode == 201) {
            return json_decode($responseBody);
        }

        dump('No or invalid Fin User Response!');

        return null;
    }

    public static function getAccessToken($type, $email = null, $password = null) {
        try {
                if ($type === 'client') {
                    return FinAPIService::getOAuthToken(config('finApi.grant_type.client_credentials'));
                }

                if ($type === 'user') {
                    $user = auth()->user();
                    $email = $email ?? ($user ? $user->email : 'email@localhost.de');
                    $password = $password ?? 'hellopassword';

                    // Check if the FinAPI user already exists
                    $finApiUser = $user
                        ? FinapiUser::where('user_id', $user->id)->first()
                        : FinapiUser::where('email', $email)->first();

                    // If the FinAPI user doesn't exist, create a new one
                    if (!$finApiUser) {
                        $accessToken = FinAPIService::getOAuthToken(config('finApi.grant_type.client_credentials'));

                        if ($accessToken) {
                            $fetchedFinApiUser = FinAPIService::createFinApiUser($accessToken->access_token, [
                                'id' => Str::random(),
                                'password' => $password,
                                'email' => $email,
                                'isAutoUpdateEnabled' => true
                            ]);

                            if ($fetchedFinApiUser) {
                                $finApiUser = new FinapiUser([
                                    'user_id' => $user ? $user->id : null,
                                    'username' => $fetchedFinApiUser->id,
                                    'password' => $fetchedFinApiUser->password,
                                    'email' => $email,
                                ]);

                                $finApiUser->save();
                            }
                        }
                    }

                    // Once we have a valid FinAPI user, get their OAuth token
                    if ($finApiUser) {
                        $finApiUserAccessToken = FinAPIService::getOAuthToken('password', $finApiUser->username, $finApiUser->password);

                        if ($finApiUserAccessToken) {
                            $finApiUser->access_token = $finApiUserAccessToken->access_token;
                            $finApiUser->expire_at = now()->addSeconds($finApiUserAccessToken->expires_in);
                            $finApiUser->refresh_token = $finApiUserAccessToken->refresh_token;

                            $finApiUser->save();

                            return $finApiUserAccessToken;
                        }
                    }
                }

                return response()->json(['error' => 'Type needs to either be "client" or "user"'], 400);
            } catch (Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }

            return null;
    }

    public static function getStandalonePaymentForm($userAccessToken, $paymentDetails)
    {
        $url = 'https://webform-sandbox.finapi.io/api/webForms/standalonePayment';
        $requestId = self::createUUID();
        $client = new Client();

        $response = $client->post($url, [
            'headers' => [
                'X-Request-Id' => $requestId,
                'Authorization' => 'Bearer ' . $userAccessToken,
            ],
            'json' => $paymentDetails,
        ]);

        $responseCode = $response->getStatusCode();
        $responseBody = $response->getBody()->getContents();

        FinApiLoggerService::logFinapiRequest($url, ['X-Request-Id' =>  $requestId], $paymentDetails, $responseCode, $responseBody, $requestId);

        if ($responseCode == 201) {
            return json_decode($responseBody);
        }

        dump('No or invalid Form!');
        return null;
    }

    public static function getFromDetails($userAccessToken, $formId)
    {
        $url = "https://webform-sandbox.finapi.io/api/webForms/{$formId}";
        $requestId = self::createUUID();
        $client = new Client();

        $response = $client->get($url, [
            'headers' => [
                'X-Request-Id' => $requestId,
                'Authorization' => 'Bearer ' . $userAccessToken,
            ],
        ]);

        $responseCode = $response->getStatusCode();
        $responseBody = $response->getBody()->getContents();

        FinApiLoggerService::logFinapiRequest($url, ['X-Request-Id' =>  $requestId], ['form_id',$formId], $responseCode, $responseBody, $requestId);

        if ($responseCode == 200) {
            return json_decode($responseBody);
        }

        dump('No or invalid Form!');
        return null;
    }

    public static function getPaymentDetails($userAccessToken, $paymentId)
    {
        $url = "https://sandbox.finapi.io/api/v2/payments";
        $requestId = self::createUUID();
        $client = new Client();

        $response = $client->get($url, [
            'headers' => [
                'X-Request-Id' => $requestId,
                'Authorization' => 'Bearer ' . $userAccessToken,
            ],
            'query' => ['ids' => $paymentId]
        ]);

        $responseCode = $response->getStatusCode();
        $responseBody = $response->getBody()->getContents();

        FinApiLoggerService::logFinapiRequest($url, ['X-Request-Id' =>  $requestId], ['ids' => $paymentId], $responseCode, $responseBody, $requestId);

        if ($responseCode == 200) {
            return json_decode($responseBody);
        }

        dump('No or invalid payment!');
        return null;
    }

    public static function createDirectDebitWithApi($userAccessToken, $request)
    {
        $url = 'https://sandbox.finapi.io/api/v2/payments/directDebits';
        $requestId = self::createUUID();

        $body = self::buildDirectDepositWithApiDetails($request);

        if (!$body) {
            return response()->json('Error building direct deposit details', 500);
        }

        if($body instanceof JsonResponse){
            return $body;
        }

        $client = new Client();
        $response = $client->post($url, [
            'headers' => [
                'X-Request-Id' => $requestId,
                'Authorization' => 'Bearer ' . $userAccessToken,
                'Authorization' => 'Bearer ' . $userAccessToken,
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode($body),
        ]);

        $responseCode = $response->getStatusCode();
        $responseBody = $response->getBody()->getContents();

        FinApiLoggerService::logFinapiRequest($url, ['X-Request-Id' => $requestId], ['body' => $body], $responseCode, $responseBody,$requestId);

        if ($responseCode == 200) {
            return json_decode($responseBody);
        }

        dump('No or invalid payment!');
        return null;
    }

    public static function createDirectDebitWithWebform($userAccessToken, $request)
    {
        // https://docs.finapi.io/#post-/api/webForms/directDebitWithAccountId

        $url = "https://webform-sandbox.finapi.io/api/webForms/directDebitWithAccountId";
        $requestId = self::createUUID();
        $client = new Client();

        $body = self::buildDirectDebitWithWebformDetails($request);

        if (!$body) {
            return response()->json('Error building direct deposit details', 500);
        }

        if($body instanceof JsonResponse){
            return $body;
        }

        $response = $client->post($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $userAccessToken,
                'X-Request-Id' => $requestId,
                'Content-Type' => 'application/json',
            ],
            'json' => $body
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        $responseCode = $response->getStatusCode();
        $responseBody = $response->getBody()->getContents();

        FinApiLoggerService::logFinapiRequest($url, ['X-Request-Id' =>  $requestId], ['body'=> $data], $responseCode, $responseBody, $requestId);

        if ($responseCode == 201) {
            return json_decode($responseBody);
        }

        dump('No or invalid Form!');
        return null;
    }

    private static function buildDirectDebitWithWebformDetails(Request $request)
    {
        $rules = [
            'payer_name' => 'required',
            'iban' => 'required',
            'amount' => 'required|numeric',
            'purpose' => 'required',
            'execution_date' => 'required|date|after_or_equal:today',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()->all()], 400);
        }

        $body = [
            "orders" => [
                [
                    "payer" => [
                        "name" => $request->payer_name,
                        "iban" => $request->iban,
                        // "bic" => $request->input('bic', null),
                        // "address" => $request->input('payer_address', '221b Baker St, London NW1 6XE'),
                        // "country" => $request->input('payer_country', 'DE'),
                    ],
                    "amount" => [
                        "value" => $request->amount,
                        "currency" => $request->input('currency', 'EUR')
                    ],
                    "purpose" => $request->purpose,
                    // "sepaPurposeCode" => $request->input('sepa_purpose_code', 'SALA'),
                    // "endToEndId" => $request->input('end_to_end_id', 'endToEndId')
                    "mandateId" => 1, // Mandate ID that this direct debit order is based on.
                    "mandateDate" => "2024-01-01", // Date of the mandate that this direct debit order is based on, in the format YYYY-MM-DD
                    // ! https://documentation.finapi.io/access/finapi-test-bank-for-redirect-approach
                    "creditorId" => $request->input('creditor_id', 'DE85533700080333333300') //Creditor ID of the source account's holder
                ]
            ],
            "executionDate" => $request->input('execution_date', Carbon::now()->addDays(1)->format('Y-m-d')),
            // "batchBookingPreferred" => $request->input('batch_booking_preferred', true),
            // "batchBookingId" => $request->input('batch_booking_id', 'batch-payment-' . date('Y-m-d')),
            // "profileId" => $request->input('profile_id', null),
            // "redirectUrl" => $request->input('redirect_url', 'https://finapi.io/callback'),
            // "callbacks" => [
            //     "finalised" => $request->input('finalised_callback', 'https://yourdomain.com/callback?state=42')
            // ],
            "receiver" => [
                // ! https://documentation.finapi.io/access/finapi-test-bank-for-redirect-approach
                "accountId" => $request->input('account_id', 280002)
            ],
            "directDebitType" => $request->input('direct_debit_type', 'B2B'),
            "sequenceType" => $request->input('sequence_type', 'OOFF')
        ];

        return $body;
    }

    private static function buildDirectDepositWithApiDetails(Request $request)
    {
        $rules = [
            'accountId' => 'required',
            'directDebitType' => 'required',
            'sequenceType' => 'required',
            'directDebits' => 'required|array|min:1',
            'directDebits.*.counterpartName' => 'required',
            'directDebits.*.counterpartIban' => 'required',
            'directDebits.*.amount' => 'required|numeric',
            'directDebits.*.mandateId' => 'required',
            'directDebits.*.mandateDate' => 'required|date',
            'directDebits.*.creditorId' => 'required',
            'executionDate' => 'required|date|after_or_equal:today'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()->all()], 400);
        }

        $directDebits = $request->input('directDebits');

        $directDebits = array_map(function($directDebit) {
            return [
                'counterpartName' => $directDebit['counterpartName'],
                'counterpartIban' => $directDebit['counterpartIban'],
                'counterpartBic' => $directDebit['counterpartBic'] ?? null,
                'amount' => $directDebit['amount'],
                'purpose' => $directDebit['purpose'] ?? null,
                'sepaPurposeCode' => $directDebit['sepaPurposeCode'] ?? null,
                'endToEndId' => $directDebit['endToEndId'] ?? null,
                'mandateId' => $directDebit['mandateId'],
                'mandateDate' => $directDebit['mandateDate'],
                'creditorId' => $directDebit['creditorId']
            ];
        }, $directDebits);

        $body = new stdClass();
        $body->singleBooking = $request->input('singleBooking', false);
        $body->msgId = $request->input('msgId');
        $body->accountId = $request->input('accountId');
        $body->directDebitType = $request->input('directDebitType');
        $body->sequenceType = $request->input('sequenceType');
        $body->executionDate = $request->input('executionDate');
        $body->directDebits = $directDebits;

        return $body;
    }

    public static function buildPaymentDetails($amount, $currencyCode, $finapiUserId = null)
    {
        $finApiPaymentRecipient = FinapiPaymentRecipient::first();

        if (!$finApiPaymentRecipient) {
            return null;
        }

        $order = [
            "recipient" => [
                "name" => $finApiPaymentRecipient->name,
                "iban" => $finApiPaymentRecipient->iban,
                // "bic" => $finApiPaymentRecipient->bic,
                // "bankName" => $finApiPaymentRecipient->bankName,
                // "address" => [
                //     "street" => $finApiPaymentRecipient->street,
                //     "houseNumber" => $finApiPaymentRecipient->houseNumber,
                //     "postCode" => $finApiPaymentRecipient->postCode,
                //     "city" => $finApiPaymentRecipient->city,
                //     "country" => $finApiPaymentRecipient->country,
                // ],
                // "structuredRemittanceInformation" => [
                //     "VS:12345",
                //     "KS:12345",
                //     "SS:12345"
                // ],
            ],
            "amount" => [
                "value" => $amount,
                "currency" => $currencyCode,
            ],
            "purpose" => $finapiUserId ? "Payment for FinAPI User: $finapiUserId" : "Payment for FinAPI User",
            // "sepaPurposeCode" => "SALA",
            // "endToEndId" => "endToEndId"
        ];

        return [
            "orders" => [
                $order
            ],
            // "profileId" => "a2c9fc3b-1777-403c-8b2f-1ce4d90157a2",
            // "redirectUrl" => "https://terd/callback",
            // "callbacks" => [
            //     "finalised" => "https://terd/callback?state=42"
            // ],
            // "sender" => [
            //     "iban" => "DE77533700080111111100"
            // ],
            "instantPayment" => false,
            "allowTestBank" => true
        ];
    }
}
