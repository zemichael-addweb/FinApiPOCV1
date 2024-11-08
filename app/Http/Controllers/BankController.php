<?php

namespace App\Http\Controllers;

use App\Models\FinapiBankConnection;
use App\Models\FinapiUser;
use App\Services\LoggerService;
use App\Services\FinAPIService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BankController extends Controller
{
    public function index()
    {
        $bankConnections = $this->getBankConnections();
        return view('bank.bank-index', compact('bankConnections'));
    }

    public function show(Request $request, $id)
    {
        switch($id){
            case 'import-bank-connection':
                return $this->importBankConnection();
            case 'get-bank-connections':
                return $this->getBankConnections($request);
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

    public function redirectToImportBankConnectionForm(Request $request)
    {
        $bankConnectionDetails = FinAPIService::buildBankConnectionDetails($request);

        if (!$bankConnectionDetails) {
            return response()->json(['error' => 'Import Bank Connection details could not be built. Plese contact system admin.'], 500);
        }

        if($bankConnectionDetails instanceof JsonResponse){
            return $bankConnectionDetails;
        }

        $finApiUserAccessToken = FinAPIService::getAccessToken('user');

        if($finApiUserAccessToken instanceof JsonResponse){
            return $finApiUserAccessToken;
        }

        $finApiUser = FinapiUser::where('access_token', $finApiUserAccessToken->access_token)->first();

        try{
            $finApiStandalonePaymentForm = FinAPIService::getImportBankConnectionform($finApiUserAccessToken->access_token, $bankConnectionDetails);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        if($finApiStandalonePaymentForm) {
            $formData = [
                'finapi_user_id' => $finApiUser->id,
                'form_purpose' => 'BANK_CONNECTION',
                'finapi_id' => $finApiStandalonePaymentForm->id,
                'form_url' => $finApiStandalonePaymentForm->url,
                'expire_time' => $finApiStandalonePaymentForm->expiresAt,
                'type' => $finApiStandalonePaymentForm->type,
            ];

            LoggerService::logFinapiForm($formData);

            return response()->json($finApiStandalonePaymentForm);
        }
    }

    public function getBankConnections(Request $request = null)
    {
        $finApiUserAccessToken = FinAPIService::getAccessToken('user');

        if ($finApiUserAccessToken instanceof \Illuminate\Http\JsonResponse) {
            return $finApiUserAccessToken;
        }

        try {
            $bankConnections = FinAPIService::fetchBankConnections($finApiUserAccessToken->access_token);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        if (!$bankConnections) {
            return response()->json(['message' => 'No bank connections found.'], 404);
        }

        foreach ($bankConnections->connections as $connection) {
            $finapiBankConnection = FinapiBankConnection::where('finapi_id', $connection->id)->first();

            if($finapiBankConnection) {
                $finapiBankConnection->finapi_id = $connection->id;
                $finapiBankConnection->finapi_user_id = $connection->user_id ?? null;
                $finapiBankConnection->finapi_form_id = $connection->form_id ?? null;
                $finapiBankConnection->bank_name = $connection->bank->name ?? null;
                $finapiBankConnection->blz = $connection->bank->blz ?? null;
                $finapiBankConnection->bank_group = $connection->bank->group ?? null;
                $finapiBankConnection->data = json_encode($connection);

                $finapiBankConnection->save();
            } else {
                FinapiBankConnection::create([
                    'finapi_id' => $connection->id,
                    'finapi_user_id' => $connection->user_id ?? null,
                    'finapi_form_id' => $connection->form_id ?? null,
                    'bank_name' => $connection->bank->name ?? null,
                    'blz' => $connection->bank->blz ?? null,
                    'bank_group' => $connection->bank->group ?? null,
                    'data' => json_encode($connection),
                ]);
            }
        }

        return $bankConnections;
    }
}
