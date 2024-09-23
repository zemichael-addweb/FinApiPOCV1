<?php

namespace App\Http\Controllers;

use App\Services\HelperServices;
use Illuminate\Support\Facades\Log;

class FinApiController extends Controller
{
    public function index()
    {
        return response()->json(['apiKeys' => config('finApi.default')]);
    }

    public function getAccessToken()
    {
        $grantType = config('finApi.grant_type.client_credentials');
        $clientId = config('finApi.default.clientId');
        $clientSecret = config('finApi.default.clientSecret');
        $finApiServerUrl = config('finApi.finApiServerUrl');

        try {
            $response = HelperServices::makeCurlHttpRequest($finApiServerUrl . '/api/v2/oauth/token/', 'POST', [
                'grant_type' => $grantType,
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
            ]);

            if($response && isset($response->status)) {
                return response()->json(['data' => $response], $response->status);
            }

            return response()->json(['data' => $response]);

            // Log::info('Error getting access token', ['response' => $response]);
           

            // return response()->json(['error' => 'Error getting access token'], 500);

        } catch (\Throwable $th) {
            Log::info($th->getMessage());
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

}