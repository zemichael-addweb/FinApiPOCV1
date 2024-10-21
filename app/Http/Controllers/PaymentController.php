<?php

namespace App\Http\Controllers;

use App\Models\FinAPIAccessToken;
use App\Models\FinapiPayment;
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
use Illuminate\Http\JsonResponse;
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
        $finapiPayments = FinapiPayment::all()->map(function ($payment) {
            return collect($payment)->mapWithKeys(function ($value, $key) {
                return [Str::camel($key) => $value];
            });
        });

        $pageTitle = 'view-payment';
        return view('payment.payment-index', compact('finapiPayments', 'pageTitle'));
    }

    public function create()
    {
        $pageTitle = 'create-payment';
        return view('payment.payment-create',  ['pageTitle'=>$pageTitle]);
    }
    public function createDirectDebit()
    {
        $pageTitle = 'create-direct-debit-payment';
        return view('payment.payment-create-direct-debit',  ['pageTitle'=>$pageTitle]);
    }

    public function store(Request $request)
    {
        return '404 Not Found';
    }

    public function show($id)
    {
        if($id=="create-direct-debit"){
            return $this->createDirectDebit();
        }

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

    public function getFinapiPayment($id = null)
    {
        if(!$id){
            $id=request()->id;
        }
        $finapiPayment = FinapiPayment::where('finapi_id', $id)->first();
        if(!$finapiPayment){
            return response()->json(['error' => 'Payment not found'], 404);
        }
        try{
            $finApiUserAccessToken = FinAPIService::getAccessToken('user');

            if($finApiUserAccessToken instanceof JsonResponse){
                return $finApiUserAccessToken;
            }

            $finapiPayment = FinAPIService::getPaymentDetails($finApiUserAccessToken->access_token, $finapiPayment->finapi_id);

            return response()->json($finapiPayment);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json($finapiPayment);
    }

    public function makeDirectDebitWithApi(Request $request)
    {
        try{
            $finApiUserAccessToken = FinAPIService::getAccessToken('user');

            if($finApiUserAccessToken instanceof JsonResponse){
                return $finApiUserAccessToken;
            }

            $finApiDirectDebit = FinAPIService::createDirectDebitWithAPi($finApiUserAccessToken->access_token, $request);

            return response()->json($finApiDirectDebit);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function makeDirectDebitWithWebform(Request $request)
    {
        try{
            $finApiUserAccessToken = FinAPIService::getAccessToken('user');

            if($finApiUserAccessToken instanceof JsonResponse){
                return $finApiUserAccessToken;
            }

            $finApiDirectDebit = FinAPIService::createDirectDebitWithWebform($finApiUserAccessToken->access_token, $request);

            return response()->json($finApiDirectDebit);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
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

        $finApiUserAccessToken = FinAPIService::getAccessToken('user');

        if($finApiUserAccessToken instanceof JsonResponse){
            return $finApiUserAccessToken;
        }

        $finApiUser = FinapiUser::where('access_token', $finApiUserAccessToken->access_token)->first();

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
            return response()->json(['error' => $e->getMessage()], 500);
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
            FinApiLoggerService::logFinapiForm($formData, $payment->id);

            return response()->json($finApiStandalonePaymentForm);
        }
    }
}
