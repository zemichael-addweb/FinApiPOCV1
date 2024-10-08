<?php

namespace App\Http\Controllers;

use App\Models\FinAPIAccessToken;
use App\Models\FinapiPaymentRecipient;
use App\Models\FinapiUser;
use App\Models\Payment;
use App\Services\FinApiLoggerService;
use App\Services\FinAPIService;
use App\Services\OpenApiEnumModelService;
use Exception;
use FinAPI\Client\Api\PaymentsApi;
use FinAPI\Client\Configuration;
use FinAPI\Client\Model\CreateMoneyTransferParams;
use FinAPI\Client\Model\Currency;
use FinAPI\Client\Model\ISO3166Alpha2Codes;
use FinAPI\Client\Model\MoneyTransferOrderParams;
use FinAPI\Client\Model\MoneyTransferOrderParamsCounterpartAddress;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use stdClass;

class PaymentController extends Controller
{
    private $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('finApi.finApiServerUrl');
    }

    public function createUUID()
    {
        return (string) Str::uuid();
    }

    // resource controller
    public function index()
    {
        return view('payment.payment-index');
    }

    public function create()
    {
        return view('payment.payment-create');
    }

    public function store(Request $request)
    {
        $structuredRemittanceInformation = $request->input('structured_remittance_information');
        $accessToken = FinAPIAccessToken::getAccessToken()->access_token;

        $config = Configuration::getDefaultConfiguration()->setAccessToken($accessToken);

        Log::info('Config', ['conf' => $config]);

        $apiInstance = new PaymentsApi(
            new Client(),
            $config
        );

        $money_transfer_params = [];

        foreach ($request->input('counterpart_name') as $key => $counterpartName) {
            $money_transfer_params[] = new MoneyTransferOrderParams([
                'counterpart_name' => $request->input('counterpart_name')[$key],
                'counterpart_iban' => $request->input('counterpart_iban')[$key],
                'counterpart_bic' => $request->input('counterpart_bic')[$key],
                'counterpart_bank_name' => $request->input('counterpart_bank_name')[$key],
                'amount' => $request->input('amount')[$key],
                'currency' => OpenApiEnumModelService::getEnumValue(Currency::class, $request->input('currency')[$key], Currency::USD),
                'purpose' => $request->input('purpose')[$key],
                'sepa_purpose_code' => $request->input('sepa_purpose_code')[$key],
                'counterpart_address' => new MoneyTransferOrderParamsCounterpartAddress([
                    'street' => $request->input('counterpart_address.street')[$key],
                    'postCode' => $request->input('counterpart_address.post_code')[$key],
                    'city' => $request->input('counterpart_address.city')[$key],
                    'houseNumber' => $request->input('counterpart_address.house_number')[$key],
                    'country' => OpenApiEnumModelService::getEnumValue(ISO3166Alpha2Codes::class, $request->input('counterpart_address.country')[$key], ISO3166Alpha2Codes::DE)
                ]),
                'end_to_end_id' => $request->input('end_to_end_id')[$key],
                'structured_remittance_information' => [$structuredRemittanceInformation]
            ]);
        }

        $create_money_transfer_params = new CreateMoneyTransferParams([
            'account_id' => $request->input('account_id'),
            'iban' => $request->input('iban'),
            'bank_id' => $request->input('bank_id'),
            'execution_date' => $request->input('execution_date'),
            'money_transfers' => $money_transfer_params, // ! check here
            'instant_payment' => $request->input('instant_payment'),
            'single_booking' => $request->input('single_booking'),
            'msg_id' => $request->input('msg_id')
        ]);

        Log::info('Body', ['bod' => $create_money_transfer_params]);
        // dd($request->all(), $config, FinAPIAccessToken::getAccessToken()->access_token, $create_money_transfer_params);

        $x_request_id = null;
        try {
            $result = $apiInstance->createMoneyTransfer($create_money_transfer_params, $x_request_id);
            print_r($result);
        } catch (Exception $e) {
            echo 'Exception when calling PaymentsApi->createDirectDebit: ', $e->getMessage(), PHP_EOL;
        }
    }

    public function show($id)
    {
        return 'show';
    }

    public function edit($id)
    {
        return 'edit';
    }

    public function update(Request $request, $id)
    {
        return 'update';
    }

    public function destroy($id)
    {
        return 'destroy';
    }

    public function redirectToFinAPIPaymentForm(Request $request){
        $amount = $request->input('amount');
        $currency = $request->input('currency');

        $accessToken = FinAPIService::getOAuthToken(config('finApi.grant_type.client_credentials'));

        if ($accessToken) {
            $userDetails = [
                'id' => Str::random(),
                'password' => 'hellopassword',
                'email' => 'email@localhost.de',
                'phone' => '+49 99 999999-999',
                'isAutoUpdateEnabled' => true
            ];

            $finApiUser = FinAPIService::createFinApiUser($accessToken->access_token, $userDetails);

            if ($finApiUser) {

                 // {"id":"lOCOne5IisOKWzUN","password":"hellopassword","email":"email@localhost.de","phone":"+49 99 999999-999","isAutoUpdateEnabled":true}

                $finApiUserDetails = new FinapiUser([
                        'user_id' => auth()->user() ? auth()->user()->id : null,
                        'username' => auth()->user() ? auth()->user()->name : 'NO_USERNAME', // !
                        'password' => $finApiUser->password,
                        'email' => $finApiUser->email,
                ]);

                $finApiUserAccessToken = FinAPIService::getOAuthToken('password', $finApiUser->id, $finApiUser->password);

                if ($finApiUserAccessToken) {
                    // {"access_token":"k3mvEvxNC4GYTzrtBU7KZE2o3uj2d05jOWkc8CfvOW9mZlG8ZUAR8RM8TahbaXRxUhASV4R0gHkmj8ApLA8RgiZkAG1GHMYgV9FIY9MrUaX3G2OgwCdnRZGpZtdF9Oc2","token_type":"bearer","refresh_token":"9Ld_45TcIOcx3w1oJjdRcqenRn_spharcIF2lPu8E8KEZuUIac69SaHC8YXdUwfcxtV2CFaAGbbgknpmvkWL6sC6ltA2dxeukNhLex4HcBMalSkXclWFO_Rfb0Xym0Ok","expires_in":3599,"scope":"all"}

                    $finApiUserDetails->access_token = $finApiUserAccessToken->access_token;
                    $finApiUserDetails->expire_at = now()->addSeconds($finApiUserAccessToken->expires_in);
                    $finApiUserDetails->refresh_token = $finApiUserAccessToken->refresh_token;

                    $finApiUserDetails->save();

                    $payment = new Payment([
                        'finapi_user_id' => $finApiUserDetails->id,
                        'order_ref_number' => '123456', // TODO get this from shopify
                        'amount' => $amount,
                        'currency' => $currency, 
                        'type' => 'ORDER', // TODO and this
                        'status' => 'PENDING',
                    ]);

                    $payment->save();

                    $paymentDetails = FinAPIService::buildPaymentDetails(
                        $payment->amount,
                        $payment->currency
                    );

                    $finApiStandalonePaymentForm = FinAPIService::getStandalonePaymentForm($finApiUserAccessToken->access_token, $paymentDetails);

                    // dump($finApiStandalonePaymentForm);
                    // {"id":"eb54ab34-3e61-4060-b12b-beecbc52a76c","url":"https://webform-sandbox.finapi.io/wf/eb54ab34-3e61-4060-b12b-beecbc52a76c","createdAt":"2024-10-04T13:48:59.194+0000","expiresAt":"2024-10-04T14:08:59.194+0000","type":"STANDALONE_PAYMENT","status":"NOT_YET_OPENED","payload":{}}
                    
                    if($finApiStandalonePaymentForm) {
                        $formData = [
                            'form_id' => $finApiStandalonePaymentForm->id,
                            'form_url' => $finApiStandalonePaymentForm->url,
                            'expire_time' => $finApiStandalonePaymentForm->expiresAt,
                            'type' => $finApiStandalonePaymentForm->type,
                        ];
                        FinApiLoggerService::logPaymentForm($payment->id, $formData);

                        return response()->json($finApiStandalonePaymentForm);
                    }
                }
            }
        }
    }
}
