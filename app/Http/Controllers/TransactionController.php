<?php

namespace App\Http\Controllers;

use App\Services\FinAPIService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $transactions = json_decode($this->getTransactions($request)->getContent());
        $bankConnections = json_decode($this->getBankConnections($request)->getContent());

        return view('transaction.transaction-index', ['transactions' => $transactions, 'bankConnections' => $bankConnections]);
    }

    public function show(Request $request, $id)
    {
        switch($id){
            case 'import-bank-connection':
                return $this->importBankConnection();
            case 'transactions':
                return $this->transactions($request);
            case 'get-transactions':
                return $this->getTransactions($request);
            default :
                return '404, Not found';
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
        } catch (Exception $e) {
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
