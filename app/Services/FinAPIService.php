<?php

namespace App\Services;

use App\Models\User;
use App\Models\FinapiPayment;
use Exception;
use GuzzleHttp\Client;
use stdClass;
use Illuminate\Support\Facades\Hash;
use App\Models\FinapiPaymentRecipient;
use App\Models\FinapiBankConnection;
use App\Models\FinapiUser;
use App\Services\LoggerService;
use Carbon\Carbon;
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

    public static function getOAuthToken($grantType, $username = null, $password = null, $refreshToken = null)
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

        if ($grantType === 'refresh_token') {
            $formParams['refresh_token'] = $refreshToken;
        }

        $response = $client->post($url, [
            'headers' => [
                'X-Request-Id' => $requestId,
            ],
            'form_params' => $formParams,
        ]);

        $responseCode = $response->getStatusCode();
        $responseBody = $response->getBody()->getContents();

        LoggerService::logFinapiRequest($url, ['X-Request-Id' =>  $requestId], $formParams, $responseCode, $responseBody, $requestId);

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

        LoggerService::logFinapiRequest($url, ['X-Request-Id' =>  $requestId], $userDetails, $responseCode, $responseBody, $requestId);

        if ($responseCode == 201) {
            return json_decode($responseBody);
        }

        dump('No or invalid Fin User Response!');

        return null;
    }

    public static function getAccessToken($type, $email = null, $password = null, $username = null) {
        try {
            if ($type === 'client') {
                return FinAPIService::getOAuthToken(config('finApi.grant_type.client_credentials'));
            }

            if ($type === 'user') {

                $finApiUser = FinapiUser::where('email', $email)
                ->orWhere('username', $username)
                ->first();

                if(!$finApiUser) {
                    $user = auth()->user();
                    $email = $email ?? ($user ? $user->email : 'email@localhost.de');
                    $password = $password ?? Str::random();

                    // Check if the FinAPI user already exists
                    $finApiUser = $user
                        ? FinapiUser::where('user_id', $user->id)->first()
                        : FinapiUser::where('email', $email)->first();
                }

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



                            if($finApiUser->user_id === null) {
                                $newUser = new User();
                                $newUser->name = $email;
                                $newUser->email = $email;
                                $newUser->password = Hash::make($password);
                                $newUser->save();

                                $finApiUser->user_id = $newUser->id;
                            }

                            $finApiUser->save();
                        }
                    }
                }


                $tokenExpired = true;

                if($finApiUser && $finApiUser->expire_at !== null){
                    $tokenExpired = Carbon::now()->gt($finApiUser->expire_at);
                }


                if ($tokenExpired) {
                    $now = Carbon::now();
                    $expiresAt = Carbon::parse($finApiUser->expire_at);
                    $finApiUserAccessToken = null;

                    if (!isset($finApiUser->refresh_token)) {
                        $finApiUserAccessToken = FinAPIService::getOAuthToken('password', $finApiUser->username, $finApiUser->password);
                    } elseif ($now->lt($expiresAt)) {
                        return $finApiUser;
                    }
                    // else {
                    //     $finApiUserAccessToken = FinAPIService::getOAuthToken('refresh_token', null, null, $finApiUser->refresh_token);
                    // }

                    if (!isset($finApiUserAccessToken->access_token)) {
                        $finApiUserAccessToken = FinAPIService::getOAuthToken('password', $finApiUser->username, $finApiUser->password);
                    }

                    if ($finApiUserAccessToken && isset($finApiUserAccessToken->access_token)) {
                        $finApiUser->access_token = $finApiUserAccessToken->access_token;
                        $finApiUser->expire_at = now()->addSeconds($finApiUserAccessToken->expires_in);
                        $finApiUser->refresh_token = $finApiUserAccessToken->refresh_token;

                        $finApiUser->save();
                        return $finApiUser;
                    }
                }

                return $finApiUser;

            }

            return response()->json(['error' => 'Type needs to either be "client" or "user"'], 400);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return null;
    }

    public static function getBankConnectionForm($userAccessToken, $paymentDetails)
    {
        $url = 'https://webform-sandbox.finapi.io//api/webForms/bankConnectionImport';
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

        LoggerService::logFinapiRequest($url, ['X-Request-Id' =>  $requestId], $paymentDetails, $responseCode, $responseBody, $requestId);

        if ($responseCode == 201) {
            return json_decode($responseBody);
        }

        dump('No or invalid Form!');
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

        LoggerService::logFinapiRequest($url, ['X-Request-Id' =>  $requestId], $paymentDetails, $responseCode, $responseBody, $requestId);

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

        LoggerService::logFinapiRequest($url, ['X-Request-Id' =>  $requestId], ['finapi_form_id',$formId], $responseCode, $responseBody, $requestId);

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

        LoggerService::logFinapiRequest($url, ['X-Request-Id' =>  $requestId], ['ids' => $paymentId], $responseCode, $responseBody, $requestId);

        if ($responseCode == 200) {
            $fetchedPayments = json_decode($responseBody);
            if (isset($fetchedPayments->payments) && count($fetchedPayments->payments) > 0) {
                self::updateFinapiPaymentDetail($fetchedPayments->payments);
            }
            return $fetchedPayments;
        }

        dump('No or invalid payment!');
        return null;
    }

    public static function updateFinapiPaymentDetail($fetchedPayments){
        foreach ($fetchedPayments as $fetchedPayment) {
            $finApiPayment = FinapiPayment::where('finapi_id', $fetchedPayment->id)->first();
            if ($finApiPayment) {
                $finApiPayment->status = $fetchedPayment->status;
                $finApiPayment->status_v2 = $fetchedPayment->statusV2;
                $finApiPayment->save();
            }
        }
    }

    public static function buildPaymentDetails($amount, $currencyCode, $finapiUserId = null, $confirmationNumber = null)
    {
        $finApiPaymentRecipient = FinapiPaymentRecipient::first();

        if (!$finApiPaymentRecipient) {
            return null;
        }

        $purpose = $confirmationNumber ? "shopify_confirmation_number:$confirmationNumber" : "shopify_confirmation_number:no_confirmation_number";

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
            "purpose" => $purpose
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

        LoggerService::logFinapiRequest($url, ['X-Request-Id' => $requestId], ['body' => $body], $responseCode, $responseBody,$requestId);

        if ($responseCode == 200) {
            return json_decode($responseBody);
        }

        dump('No or invalid payment!');
        return null;
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

        LoggerService::logFinapiRequest($url, ['X-Request-Id' =>  $requestId], ['body'=> $data], $responseCode, $responseBody, $requestId);

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

    public static function getImportBankConnectionform($userAccessToken, $bankConnectionDetails)
    {
        // https://docs.finapi.io/#post-/api/webForms/createBankConnection

        $url = "https://webform-sandbox.finapi.io/api/webForms/bankConnectionImport";
        $requestId = self::createUUID();
        $client = new Client();

        $response = $client->post($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $userAccessToken,
                'X-Request-Id' => $requestId,
            ],
            'json' => $bankConnectionDetails
        ]);

        $responseCode = $response->getStatusCode();
        $responseBody = $response->getBody()->getContents();

        LoggerService::logFinapiRequest($url, ['X-Request-Id' =>  $requestId], ['body'=> $bankConnectionDetails], $responseCode, $responseBody, $requestId);

        if ($responseCode == 201) {
            return json_decode($responseBody);
        }

        dump('No or invalid Form!');
        return null;
    }

    public static function buildBankConnectionDetails(Request $request)
    {
        // ! NOTHING IS REQUIRED FOR NOW : WILL NEED TO READ MORE FOR VALIDATION CHECK
        // CHECK REQUEST SCHEMA
        // https://docs.finapi.io/#post-/api/webForms/bankConnectionImport

        $rules = [
            'bank_id' => 'nullable|integer',
            'bank_search' => 'nullable|string',
            'bank_connection_name' => 'nullable|string|max:64',
            'skip_balances_download' => 'nullable|boolean',
            'skip_positions_download' => 'nullable|boolean',
            'load_owner_data' => 'nullable|boolean',
            'max_days_for_download' => 'nullable|integer|max:3650',
            'account_types' => 'nullable|array',
            'allowed_interfaces' => 'nullable|array',
            'callbacks_finalised' => 'nullable|url|max:2048',
            'profile_d' => 'nullable|string|size:36',
            'redirect_url' => 'nullable|url|max:2048',
            'allow_test_bank' => 'nullable|boolean'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()->all()], 400);
        }

        $body = [
            // 'bank' => [
            //     'id' => $request->input('bank_id', null),
            //     'search' => $request->input('bank_search', null)
            // ],
            'bankConnectionName' => $request->input('bank_connection_name', 'My bank connection'),
            // 'skipBalancesDownload' => $request->input('skip_balances_download', false),
            // 'skipPositionsDownload' => $request->input('skip_positions_download', false),
            // 'loadOwnerData' => $request->input('load_owner_data', false),
            'maxDaysForDownload' => $request->input('max_days_for_download', 0),
            // 'accountTypes' => $request->input('account_types', [
            //     'CHECKING', 'SAVINGS', 'CREDIT_CARD', 'SECURITY', 'MEMBERSHIP', 'LOAN', 'BAUSPAREN'
            // ]),
            'allowedInterfaces' => $request->input('allowed_interfaces', ['XS2A', 'FINTS_SERVER', 'WEB_SCRAPER']),
            // 'callbacks' => [
            //     'finalised' => $request->input('callbacks.finalised', 'https://dev.finapi.io/callback?state=42')
            // ],
            // 'profileId' => $request->input('profileId', null),
            // 'redirectUrl' => $request->input('redirectUrl', 'https://finapi.io/callback'),
            'allowTestBank' => $request->input('allowTestBank', true)
        ];

        return $body;
    }

    public static function fetchTransactions($userAccessToken, $filters)
    {
        // https://docs.finapi.io/#get-/api/v2/transactions/-id-

        $url = "https://sandbox.finapi.io/api/v2/transactions";
        $requestId = self::createUUID();
        $client = new Client();

        $response = $client->get($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $userAccessToken,
                'X-Request-Id' => $requestId,
            ],
            'query' => $filters
        ]);

        $responseCode = $response->getStatusCode();
        $responseBody = $response->getBody()->getContents();


        LoggerService::logFinapiRequest($url, ['X-Request-Id' =>  $requestId], ['query'=> $filters], $responseCode, $responseBody, $requestId);

        if ($responseCode == 200) {
            return json_decode($responseBody);
        }

        dump('No or invalid Transactions!');
        return null;
    }

    public static function buildTransactionFilters(Request $request)
    {
        // https://docs.finapi.io/#get-/api/v2/transactions/-id-

        $rules = [
            'ids' => 'nullable|array|max:1000',
            'ids.*' => 'integer',
            'view' => 'nullable|in:bankView,userView',
            'confirmationNumber' => 'nullable|string|max:255',
            'search' => 'nullable|string|max:255',
            'counterpart' => 'nullable|string|max:255',
            'purpose' => 'nullable|string|max:255',
            'currency' => 'nullable|in:AED,AFN,ALL,AMD,ANG,AOA,ARS,AUD,AWG,AZN,BAM,BBD,BDT,BGN,BHD,BIF,BMD,BND,BOB,BOV,BRL,BSD,BTN,BWP,BYN,BZD,CAD,CDF,CHE,CHF,CHN,CHW,CLF,CLP,CNY,COP,COU,CRC,CUC,CUP,CVE,CZK,DJF,DKK,DOP,DZD,EGP,ERN,ETB,EUR,FJD,FKP,GBP,GEL,GGP,GHS,GIP,GMD,GNF,GTQ,GYD,HKD,HNL,HRK,HTG,HUF,IDR,ILS,IMP,INR,IQD,IRR,ISK,JEP,JMD,JOD,JPY,KES,KGS,KHR,KID,KMF,KPW,KRW,KWD,KYD,KZT,LAK,LBP,LKR,LRD,LSL,LYD,MAD,MDL,MGA,MKD,MMK,MNT,MOP,MRU,MUR,MVR,MWK,MXN,MXV,MYR,MZN,NAD,NGN,NIO,NIS,NOK,NPR,NTD,NZD,OMR,PAB,PEN,PGK,PHP,PKR,PLN,PRB,PYG,QAR,RMB,RON,RSD,RUB,RWF,SAR,SBD,SCR,SDG,SEK,SGD,SHP,SLL,SLS,SOS,SRD,SSP,STN,SVC,SYP,SZL,THB,TJS,TMT,TND,TOP,TRY,TTD,TVD,TWD,TZS,UAH,UGX,USD,USN,UYI,UYU,UYW,UZS,VEF,VES,VND,VUV,WST,XAF,XAG,XAU,XBA,XBB,XBC,XBD,XCD,XDR,XOF,XPD,XPF,XPT,XSU,XTS,XUA,XXX,YER,ZAR,ZMW,ZWB,ZWL',
            'accountIds' => 'nullable',
            'accountIds.*' => 'integer',
            'minBankBookingDate' => 'nullable|date_format:Y-m-d',
            'maxBankBookingDate' => 'nullable|date_format:Y-m-d|after_or_equal:minBankBookingDate',
            'minFinapiBookingDate' => 'nullable|date_format:Y-m-d',
            'maxFinapiBookingDate' => 'nullable|date_format:Y-m-d|after_or_equal:minFinapiBookingDate',
            'minAmount' => 'nullable|numeric',
            'maxAmount' => 'nullable|numeric|gte:minAmount',
            'direction' => 'nullable|in:all,income,spending',
            'labelIds' => 'nullable|array',
            'labelIds.*' => 'integer',
            'categoryIds' => 'nullable|array',
            'categoryIds.*' => 'integer',
            'includeChildCategories' => 'nullable|boolean',
            'isNew' => 'nullable|boolean',
            'isPotentialDuplicate' => 'nullable|boolean',
            'isAdjustingEntry' => 'nullable|boolean',
            'minImportDate' => 'nullable|date_format:Y-m-d',
            'maxImportDate' => 'nullable|date_format:Y-m-d|after_or_equal:minImportDate',
            'page' => 'nullable|integer|min:1',
            'perPage' => 'nullable|integer|min:1|max:500',
            'order' => 'nullable|array',
            'order.*' => 'string|in:id,parentId,accountId,valueDate,bankBookingDate,finapiBookingDate,amount,purpose,counterpartName,counterpartAccountNumber,counterpartIban,counterpartBlz,counterpartBic,asc,desc',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()->all()], 400);
        }

        $purposeFilter = $request->input('confirmationNumber') ? $request->input('confirmationNumber') : $request->input('purpose');

        $accountIds = null;
        if($request->get('accountIds')){
            $account = FinapiBankConnection::where('id', $request->get('accountIds'))->first();
            $data = json_decode($account->data, true);
            if(isset($data['accountIds']) && count($data['accountIds']) > 0) {
                $accountIds = implode(', ', $data['accountIds']);
            }
        }

        $filters = [
            'ids' => $request->input('ids') ? join(', ', $request->input('ids')) : null,
            'view' => $request->input('view', 'userView'),
            'confirmationNumber' => $request->input('confirmationNumber'),
            'search' => $request->input('search'),
            'counterpart' => $request->input('counterpart'),
            'purpose' => $purposeFilter,
            'currency' => $request->input('currency'),
            'accountIds' => $accountIds,
            'minBankBookingDate' => $request->input('minBankBookingDate'),
            'maxBankBookingDate' => $request->input('maxBankBookingDate'),
            'minFinapiBookingDate' => $request->input('minFinapiBookingDate'),
            'maxFinapiBookingDate' => $request->input('maxFinapiBookingDate'),
            'minAmount' => $request->input('minAmount'),
            'maxAmount' => $request->input('maxAmount'),
            'direction' => $request->input('direction', 'all'),
            'labelIds' => $request->input('labelIds'),
            'categoryIds' => $request->input('categoryIds'),
            'includeChildCategories' => $request->input('includeChildCategories', true),
            'isNew' => $request->input('isNew'),
            'isPotentialDuplicate' => $request->input('isPotentialDuplicate'),
            'isAdjustingEntry' => $request->input('isAdjustingEntry'),
            'minImportDate' => $request->input('minImportDate'),
            'maxImportDate' => $request->input('maxImportDate'),
            'page' => $request->input('page', 1),
            'perPage' => $request->input('perPage', 20),
            'order' => 'finapiBookingDate,desc',
        ];

        $filters = array_filter($filters, function ($value) {
            return !is_null($value) && $value !== '';
        });

        return $filters;
    }

    public static function fetchBankConnections($userAccessToken, $filters=null)
    {
        // https://docs.finapi.io/#get-/api/v2/bankConnections/-id-

        $url = "https://sandbox.finapi.io/api/v2/bankConnections";
        $requestId = self::createUUID();
        $client = new Client();

        $response = $client->get($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $userAccessToken,
                'X-Request-Id' => $requestId,
            ],
            'query' => $filters
        ]);

        $responseCode = $response->getStatusCode();
        $responseBody = $response->getBody()->getContents();

        LoggerService::logFinapiRequest($url, ['X-Request-Id' =>  $requestId], ['query'=> $filters], $responseCode, $responseBody, $requestId);

        if ($responseCode == 200) {
            return json_decode($responseBody);
        }

        dump('No or invalid Transactions!');
        return null;
    }

    public static function fetchWebform($userAccessToken, $id)
    {
        https://docs.finapi.io/#get-/api/webForms/-id-

        if(!$id){
            dump('No or invalid Form ID!');
            return null;
        }

        $url = "https://webform-sandbox.finapi.io/api/webForms/$id";
        $requestId = self::createUUID();
        $client = new Client();

        $response = $client->get($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $userAccessToken,
                'X-Request-Id' => $requestId,
            ],
        ]);

        $responseCode = $response->getStatusCode();
        $responseBody = $response->getBody()->getContents();

        LoggerService::logFinapiRequest($url, ['X-Request-Id' =>  $requestId], ['query'=> ['id' => $id]], $responseCode, $responseBody, $requestId);

        if ($responseCode == 200) {
            return json_decode($responseBody);
        }

        dump('No or invalid Forms!');
        return null;
    }

    public static function fetchWebforms($userAccessToken, $filters=null)
    {
        // https://docs.finapi.io/#get-/api/webForms

        $url = "https://webform-sandbox.finapi.io/api/webForms";
        $requestId = self::createUUID();
        $client = new Client();

        $response = $client->get($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $userAccessToken,
                'X-Request-Id' => $requestId,
            ],
            'query' => $filters
        ]);

        $responseCode = $response->getStatusCode();
        $responseBody = $response->getBody()->getContents();

        LoggerService::logFinapiRequest($url, ['X-Request-Id' =>  $requestId], ['query'=> $filters], $responseCode, $responseBody, $requestId);

        if ($responseCode == 200) {
            return json_decode($responseBody);
        }

        dump('No or invalid Forms!');
        return null;
    }
}
