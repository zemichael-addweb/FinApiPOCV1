<?php

namespace App\Http\Controllers;

use App\Models\FinapiUser;
use App\Services\FinApiLoggerService;
use App\Services\FinAPIService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function index()
    {
        return view('bank.bank-index');
    }

    public function show($id)
    {
        if($id == 'import-bank-connection'){
            return $this->importBankConnection();
        }
        return '404, Not found';
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

    public function redirectToImportBankConnectionForm(Request $request){
        $finApiUserAccessToken = FinAPIService::getAccessToken('user');

        if($finApiUserAccessToken instanceof JsonResponse){
            return $finApiUserAccessToken;
        }

        $finApiUser = FinapiUser::where('access_token', $finApiUserAccessToken->access_token)->first();

        $bankConnectionDetails = FinAPIService::buildBankConnectionDetails($request);

        if (!$bankConnectionDetails) {
            return response()->json(['error' => 'Import Bank Connection details could not be built. Plese contact system admin.'], 500);
        }

        if($bankConnectionDetails instanceof JsonResponse){
            return $bankConnectionDetails;
        }

        try{
            $finApiStandalonePaymentForm = FinAPIService::getImportBankConnectionform($finApiUserAccessToken->access_token, $bankConnectionDetails);
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
            ];

            FinApiLoggerService::logFinapiForm($formData);

            return response()->json($finApiStandalonePaymentForm);
        }
    }
}
