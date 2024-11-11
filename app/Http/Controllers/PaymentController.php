<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\FinapiPayment;
use App\Models\FinapiUser;
use App\Services\LoggerService;
use App\Services\FinAPIService;
use Exception;
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
        $deposit = auth()->user() ? Deposit::where('user_id', auth()->user()->id)->first(): null;
        $pageTitle = 'create-payment';
        return view('payment.payment-create',  ['pageTitle'=>$pageTitle, 'deposit'=>$deposit]);
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

    public function getPayments()
    {
        $payments = FinapiPayment::with('user','finapiUser','form')->get();

        return response()->json($payments);
    }

    public function getPayment(Request $request)
    {
        $id = $request->id;
        if($id) {
            $payment = FinapiPayment::with('user','finapiUser','form')->where('finapi_id', $id)->first();
            return response()->json($payment);
        }
        $payments = FinapiPayment::with('user','finapiUser','form')->get();

        return response()->json($payments);
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

        $email = $request->input('email');
        $username = $request->input('username');

        $finApiUserAccessToken = FinAPIService::getAccessToken('user', $email);

        if($finApiUserAccessToken instanceof JsonResponse){
            return $finApiUserAccessToken;
        }

        $finapiUser = FinapiUser::where('access_token', $finApiUserAccessToken->access_token)->first();

        $paymentDetails = FinAPIService::buildPaymentDetails(
            $amount,
            $currency,
            $finapiUser->username,
            $confirmationNumber
        );

        if(!$paymentDetails){
            return response()->json(['error' => 'Payment details could not be built. Plese contact system admin.'], 500);
        }

        try{
            $finApiStandalonePaymentForm = FinAPIService::getStandalonePaymentForm($finApiUserAccessToken->access_token, $paymentDetails);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        if($finApiStandalonePaymentForm) {
            $formData = [
                'finapi_user_id' => $finapiUser->id,
                'finapi_id' => $finApiStandalonePaymentForm->id,
                'form_purpose' => 'PAYMENT',
                'form_url' => $finApiStandalonePaymentForm->url,
                'expire_time' => $finApiStandalonePaymentForm->expiresAt,
                'type' => $finApiStandalonePaymentForm->type,
                'status' => $finApiStandalonePaymentForm->status,
                'order_conf_number' => $confirmationNumber,
                'error_code' => isset($finApiStandalonePaymentForm->payload->errorCode) ? $finApiStandalonePaymentForm->payload->errorCode : null,
                'error_message' => isset($finApiStandalonePaymentForm->payload->errorMessage) ? $finApiStandalonePaymentForm->payload->errorCode : null,
            ];
            LoggerService::logFinapiForm($formData);

            return response()->json($finApiStandalonePaymentForm);
        }
    }
}
