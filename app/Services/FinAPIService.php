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
use FinAPI\Client\Api\PaymentsApi;
use FinAPI\Client\Configuration;
use FinAPI\Client\Model\CreateMoneyTransferParams;
use FinAPI\Client\Model\Currency;
use FinAPI\Client\Model\ISO3166Alpha2Codes;
use FinAPI\Client\Model\MoneyTransferOrderParams;
use FinAPI\Client\Model\MoneyTransferOrderParamsCounterpartAddress;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
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

    public static function createDirectDebit($userAccessToken, $request)
    {
        $url = 'https://sandbox.finapi.io/api/v2/payments/directDebits';
        $requestId = self::createUUID();

        $body = self::buildDirectDepositDetails($request);

        if (!$body) {
            return response()->json('Error building direct deposit details', 500);
        }

        if (isset($body->success) && $body->success == false) {
            return response()->json($body->message, 400);
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

    private static function buildDirectDepositDetails(Request $request)
    {
        // Required fields validation
        $requiredFields = [
            'accountId', 'directDebitType', 'sequenceType', 'directDebits', 'executionDate'
        ];

        foreach ($requiredFields as $field) {
            if (!$request->has($field)) {
                $error = new stdClass();
                $error->success = false;
                $error->message = "Missing required field: {$field}";
                return $error;
            }
        }

        // Direct Debit fields validation
        $directDebitFields = ['counterpartName', 'counterpartIban', 'amount', 'mandateId', 'mandateDate', 'creditorId'];
        $directDebit = $request->input('directDebits')[0];

        foreach ($directDebitFields as $field) {
            if (empty($directDebit[$field])) {
                $error = new stdClass();
                $error->success = false;
                $error->message = "Missing required field in directDebits: {$field}";
                return $error;
            }
        }

        $body = new stdClass();
        $body->singleBooking = $request->input('singleBooking', false);
        $body->msgId = $request->input('msgId');
        $body->accountId = $request->input('accountId');
        $body->directDebitType = $request->input('directDebitType');
        $body->sequenceType = $request->input('sequenceType');
        $body->executionDate = $request->input('executionDate');
        $body->directDebits = [
            [
                'counterpartName' => $directDebit['counterpartName'],
                'counterpartIban' => $directDebit['counterpartIban'],
                'counterpartBic' => $directDebit['counterpartBic'] ?? null,
                'amount' => $directDebit['amount'],
                'purpose' => $directDebit['purpose'] ?? null,
                'sepaPurposeCode' => $directDebit['sepaPurposeCode'] ?? null,
                'endToEndId' => $directDebit['endToEndId'] ?? null,
                'mandateId' => $directDebit['mandateId'],
                'mandateDate' => $directDebit['mandateDate'],
                'creditorId' => $directDebit['creditorId'],
            ]
        ];

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
