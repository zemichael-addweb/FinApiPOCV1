<?php

namespace App\Http\Controllers;

use App\Models\FinapiBankConnection;
use App\Models\FinapiTransaction;
use App\Models\ShopifyOrder;
use App\Services\FinAPIService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        try {
            $transactions = json_decode($this->getTransactions($request)->getContent());
            $bankConnections = FinapiBankConnection::all();
            return view('transaction.transaction-index', ['transactions' => $transactions, 'bankConnections' => $bankConnections]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to fetch transactions. Plese contact system admin.'], 500);
        }
    }

    public function refreshTransactions(Request $request)
    {
        $transactions = json_decode($this->getTransactions($request)->getContent());
        return response()->json($transactions);
    }

    public function show(Request $request, $id)
    {
        switch($id){
            case 'import-bank-connection':
                return $this->importBankConnection();
            case 'get-transactions':
                return $this->getTransactions($request);
            default :
                $request->merge(['ids' => [$id]]);
                $transactions = json_decode($this->getTransactions($request)->getContent());
                if(isset($transactions->transactions[0]))
                    return response()->json($transactions->transactions[0]);
                else{
                    return response()->json(['error' => 'Error fetching or Transaction not found.'], 404);
                }
        }
    }

    public function create()
    {
         return '404, Not found';
    }

    public function store(Request $request)
    {
        return '404, Not found';
    }

    public function edit($id)
    {
        return '404, Not found';
    }

    public function update(Request $request, $id)
    {
        return '404, Not found';
    }

    public function importBankConnection()
    {
        return view('bank.import-bank-connection');
    }

    public function getTransactions(Request $request)
    {
        $filters = FinAPIService::buildTransactionFilters($request);

        if (!$filters) {
            return response()->json(['error' => 'Failed to build transaction filters. Plese contact system admin.'], 500);
        }

        if($filters instanceof JsonResponse){
            return $filters;
        }

        $finApiUserAccessToken = FinAPIService::getAccessToken('user');

        if($finApiUserAccessToken instanceof JsonResponse){
            return $finApiUserAccessToken;
        }

        try {
            $transactions = FinAPIService::fetchTransactions($finApiUserAccessToken->access_token, $filters);

            foreach($transactions->transactions as $transaction){
                if(isset($transaction->purpose) && strpos($transaction->purpose, 'shopify_confirmation_number') !== false){
                    $shopify_confirmation_number = explode(':', $transaction->purpose)[1];

                    if($shopify_confirmation_number != 'no_confirmation_number') {
                        $shopify_order = ShopifyOrder::where('confirmation_number', $shopify_confirmation_number)->first();
                    }
                }

                $savedTransaction = FinapiTransaction::where('finapi_id', $transaction->id)->first();

                if(!$savedTransaction){

                    $savedTransaction = new FinapiTransaction([
                        'finapi_id' => $transaction->id,
                        // 'finapi_user_id' => $transaction->userId,
                        // 'finapi_form_id' => $transaction->formId,
                        // 'finapi_payment_id' => $transaction->paymentId,
                        'shopify_order_id' => isset($shopify_order) && $shopify_order ? $shopify_order->id : null,
                        'account_id' => $transaction->accountId,
                        'value_date' => $transaction->valueDate,
                        'bank_booking_date' => $transaction->bankBookingDate,
                        'amount' => $transaction->amount,
                        'currency' => $transaction->currency,
                        'purpose' => $transaction->purpose ?? null,
                        'counterpart_name' => $transaction->counterpartName ?? null,
                        'type' => $transaction->type ?? null,
                        'shopify_confirmation_number' => isset($shopify_confirmation_number) ? $shopify_confirmation_number : null,
                        'data' => json_encode($transaction)
                    ]);
                    $savedTransaction->save();
                } else {
                    $savedTransaction->shopify_order_id = isset($shopify_order) ? $shopify_order->id : null;
                    $savedTransaction->data = json_encode($transaction);
                    $savedTransaction->save();
                }
            }

        } catch (Exception $e) {
            dd($e->getMessage(), $e->getLine(), $e->getFile());
            return response()->json(['error' => $e->getMessage()], 500);
        }

        if ($transactions) {
            return response()->json($transactions);
        }

        return response()->json(['message' => 'No transactions found'], 404);
    }

    public function getBankConnections(Request $request)
    {
        $finApiUserAccessToken = FinAPIService::getAccessToken('user');

        if($finApiUserAccessToken instanceof JsonResponse){
            return $finApiUserAccessToken;
        }

        try {
            $bankConnections = FinAPIService::fetchBankConnections($finApiUserAccessToken->access_token);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        if ($bankConnections) {
            return response()->json($bankConnections);
        }

        return response()->json(['message' => 'No Bank Connections found. Please add one.'], 404);
    }
}
