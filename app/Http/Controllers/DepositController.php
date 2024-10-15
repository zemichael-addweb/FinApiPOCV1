<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use stdClass;


class DepositController extends Controller
{
    // resource controller
    public function index()
    {
        // TODO get all deposits of this user and display them
        return view('deposit.deposit-index');
    }

    public function create()
    {
        return view('deposit.deposit-create');
    }

    public function store(Request $request)
    {
        return 'store';
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

        $user = auth()->user();

        $accessToken = FinAPIService::getOAuthToken(config('finApi.grant_type.client_credentials'));

        if ($accessToken) {
            // get finApiUser
            $finApiUser = FinapiUser::where('user_id', $user->id)->first();

            if($finApiUser) {
                $userDetails = [
                    'id' => $finApiUser->id,
                    'password' => $finApiUser->password,
                    'email' => $finApiUser->email,
                    'phone' => '+49 99 999999-999',
                    'isAutoUpdateEnabled' => true
                ];


            } else {
                $userDetails = [
                    'id' => Str::random(),
                    'password' => 'hellopassword',
                    'email' => 'email@localhost.de',
                    'phone' => '+49 99 999999-999',
                    'isAutoUpdateEnabled' => true
                ];

                $finApiUser = FinAPIService::createFinApiUser($accessToken->access_token, $userDetails);

                // {"id":"lOCOne5IisOKWzUN","password":"hellopassword","email":"email@localhost.de","phone":"+49 99 999999-999","isAutoUpdateEnabled":true}

                $finApiUserDetails = new FinapiUser([
                    'user_id' => auth()->user() ? auth()->user()->id : null,
                    'username' => auth()->user() ? auth()->user()->name : 'NO_USERNAME', // !
                    'password' => $finApiUser->password,
                    'email' => $finApiUser->email,
                ]);
            }

            if ($finApiUser) {

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
                        FinApiLoggerService::logFinapiForm($formData, $payment->id);

                        return response()->json($finApiStandalonePaymentForm);
                    }
                }
            }
        }
    }
}
