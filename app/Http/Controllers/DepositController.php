<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\DepositTransaction;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\FinapiPayment;
use App\Models\FinapiPaymentRecipient;
use App\Models\FinapiUser;
use App\Services\DepositServices;
use App\Services\LoggerService;
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
        $pageTitle = 'view-deposits';

        $user = User::where('id', auth()->user()->id)->first();

        if($user->role == 'admin'){
            $finapiDeposits = FinapiPayment::where('purpose', 'DEPOSIT')
            ->get()->map(function ($payment) {
                return collect($payment)->mapWithKeys(function ($value, $key) {
                    return [Str::camel($key) => $value];
                });
            });
            $deposits = Deposit::all();
            $depositTrsansactions = $deposits && count($deposits) > 0 ? DepositTransaction::all() : null;

            return view('admin.deposit.deposit-index', compact('finapiDeposits','deposits','depositTrsansactions', 'pageTitle'));
        } else {
            $finapiDeposits = FinapiPayment::where('purpose', 'DEPOSIT')->where('user_id', $user->id)
            ->get()->map(function ($payment) {
                return collect($payment)->mapWithKeys(function ($value, $key) {
                    return [Str::camel($key) => $value];
                });
            });
            $deposit = Deposit::where('user_id', $user->id)->first();
            $depositTrsansactions = $deposit ? DepositTransaction::where('deposit_id', $deposit->id)->get(): null;

            return view('deposit.deposit-index', compact('finapiDeposits','deposit','depositTrsansactions', 'pageTitle'));
        }
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

        try{
            $finApiUserAccessToken = FinAPIService::getAccessToken('user', $user->email);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
            return;
        }

        if(isset($finApiUserAccessToken->access_token)){
            $accessToken = $finApiUserAccessToken->access_token;
        } else {
            return response()->json(['error' => $e->getMessage()], 500);
            return;
        }

        $finApiUserDetails = FinapiUser::where('access_token', $accessToken)->first();

        $paymentDetails = FinAPIService::buildPaymentDetails(
            $amount,
            $currency
        );

        $finApiStandalonePaymentForm = FinAPIService::getStandalonePaymentForm($finApiUserAccessToken->access_token, $paymentDetails);

        if($finApiStandalonePaymentForm) {
            $formData = [
                'finapi_user_id' => $finApiUserDetails->id,
                'purpose' => 'DEPOSIT',
                'finapi_id' => $finApiStandalonePaymentForm->id,
                'form_url' => $finApiStandalonePaymentForm->url,
                'expire_time' => $finApiStandalonePaymentForm->expiresAt,
                'type' => $finApiStandalonePaymentForm->type,
            ];


            $form = LoggerService::logFinapiForm($formData);

            return response()->json($finApiStandalonePaymentForm);
        }
    }

    public function getDeposit(Request $request){
        $id = $request->input('id');
        $deposit = $id ? Deposit::where('id', $id)->first() : Deposit::where('user_id', auth()->user()->id)->first();
        return response()->json($deposit);
    }

    public function getDeposits(Request $request){
        $id = $request->input('id');
        if($id) {
            $deposit = Deposit::where('user_id', $id)->first();
            return response()->json($deposit);
        }

        $deposits = Deposit::all();
        return response()->json($deposits);
    }

    public function makePaymentFromDeposit(Request $request){
        $amount = $request->input('amount');
        $currency = $request->input('currency');
        $emil = $request->input('email');

        $user = auth()->user();

        $userDeposit = Deposit::where('user_id', $user->id)->first();

        if(!$userDeposit) {
            return response()->json(['success' => false,'error' => 'No deposits found'], 400);
        }

        if($userDeposit->remaining_balance < $amount) {
            return response()->json(['success' => false,'error' => 'Insufficient balance'], 400);
        }

        $userDeposit = DepositServices::withdrawDeposit($amount, $user->id);

        return response()->json(['success' => true, 'message' => 'Payment successful']);
    }
}
