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
        $pageTitle = 'view-payment';
        return view('payment.payment-index', ['pageTitle'=>$pageTitle]); // ! may list payments
    }

    public function create()
    {
        $pageTitle = 'create-payment';
        return view('payment.payment-create',  ['pageTitle'=>$pageTitle]);
    }

    public function store(Request $request)
    {
        return '404 Not Found';
    }

    public function show($id)
    {
        return '404 Not Found';
    }

    public function edit($id)
    {
        return '404 Not Found';
    }

    public function update(Request $request, $id)
    {
        return '404 Not Found';
    }

    public function destroy($id)
    {
        return '404 Not Found';
    }

    public function fromCallback(Request $request)
    {
        dd($request->all());
        return '404 Not Found';
    }

    public function redirectToFinAPIPaymentForm(Request $request){
        $amount = $request->input('amount');
        $currency = $request->input('currency');
        $confirmationNumber = $request->input('confirmationNumber');
        try {
            $accessToken = FinAPIService::getOAuthToken(config('finApi.grant_type.client_credentials'));
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }

        if ($accessToken) {
            $email = $request->input('email');
            $password = $request->input('password');

            $user = auth()->user();

            $finApiUser = $user ? FinapiUser::where('user_id', $user->id)->first() : FinapiUser::where('email', $email)->first();

            if(!$finApiUser){
                try{
                    $fetchedFinApiUser = FinAPIService::createFinApiUser($accessToken->access_token, [
                        'id' => Str::random(),
                        'password' => $password,
                        'email' => $email,
                        // 'phone' => '+49 99 999999-999',
                        'isAutoUpdateEnabled' => true
                    ]);
                    // {"id":"lOCOne5IisOKWzUN","password":"hellopassword","email":"email@localhost.de","phone":"+49 99 999999-999","isAutoUpdateEnabled":true}

                    if($fetchedFinApiUser){
                        $finApiUser = new FinapiUser([
                            'user_id' => $user ? $user->id : null,
                            'username' => $fetchedFinApiUser->id,
                            'password' => $fetchedFinApiUser->password,
                            'email' => $fetchedFinApiUser->email,
                        ]);

                        $finApiUser->save();
                    }
                } catch (Exception $e) {
                    return response()->json(['error' => $e->getMessage()], $e->getCode());
                }
            }

            if ($finApiUser) {
                try{
                    $finApiUserAccessToken = FinAPIService::getOAuthToken('password', $finApiUser->username, $finApiUser->password);
                } catch (Exception $e) {
                    return response()->json(['error' => $e->getMessage()], $e->getCode());
                }
                // {"access_token":"k3mvEvxNC4...","token_type":"bearer","refresh_token":"9Ld_45TcIO...","expires_in":3599,"scope":"all"}

                if ($finApiUserAccessToken) {

                    $finApiUser->access_token = $finApiUserAccessToken->access_token;
                    $finApiUser->expire_at = now()->addSeconds($finApiUserAccessToken->expires_in);
                    $finApiUser->refresh_token = $finApiUserAccessToken->refresh_token;

                    $finApiUser->save();

                    $payment = new Payment([
                        'finapi_user_id' => $finApiUser->id,
                        'order_ref_number' => $confirmationNumber,
                        'amount' => $amount,
                        'currency' => $currency,
                        'type' => 'ORDER', // TODO and this
                        'status' => 'PENDING',
                    ]);

                    $payment->save();

                    $paymentDetails = FinAPIService::buildPaymentDetails(
                        $payment->amount,
                        $payment->currency,
                        $finApiUser->username,
                    );

                    if(!$paymentDetails){
                        return response()->json(['error' => 'Payment details could not be built. Plese contact system admin.'], 500);
                    }

                    try{
                        $finApiStandalonePaymentForm = FinAPIService::getStandalonePaymentForm($finApiUserAccessToken->access_token, $paymentDetails);
                        // {"id":"eb54ab34-3e61-4060-b12b-beecbc52a76c","url":"https://webform-sandbox.finapi.io/wf/eb54ab34-3e61-4060-b12b-beecbc52a76c","createdAt":"2024-10-04T13:48:59.194+0000","expiresAt":"2024-10-04T14:08:59.194+0000","type":"STANDALONE_PAYMENT","status":"NOT_YET_OPENED","payload":{}}
                    } catch (Exception $e) {
                        return response()->json(['error' => $e->getMessage()], $e->getCode());
                    }

                    if($finApiStandalonePaymentForm) {
                        $formData = [
                            'finapi_user_id' => $finApiUser->id,
                            'form_id' => $finApiStandalonePaymentForm->id,
                            'form_url' => $finApiStandalonePaymentForm->url,
                            'expire_time' => $finApiStandalonePaymentForm->expiresAt,
                            'type' => $finApiStandalonePaymentForm->type,
                            'standing_order_id' => $confirmationNumber
                        ];
                        FinApiLoggerService::logPaymentForm($payment->id, $formData);

                        return response()->json($finApiStandalonePaymentForm);
                    }
                }
            }
        }
    }
}
