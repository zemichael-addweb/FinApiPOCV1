<?php

namespace App\Http\Controllers;

use App\Models\FinAPIAccessToken;
use App\Services\FinAPIService;
use App\Services\HelperServices;
use Exception;
use FinAPI\Client\Api\AuthorizationApi;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class FinApiController extends Controller
{
    public function getAccessToken()
    {        
        $accessToken = FinAPIAccessToken::getAccessToken();
        return response()->json($accessToken);

        // $finApiServerUrl = config('finApi.finApiServerUrl');

        // try {
        //     $response = HelperServices::makeCurlHttpRequest($finApiServerUrl . '/api/v2/oauth/token', 'POST', [
        //         'grant_type' => $grantType,
        //         'client_id' => $clientId,
        //         'client_secret' => $clientSecret,
        //     ]);

        //     if(!$response) {
        //         return response()->json(['error' => 'No response from server'], 500);
        //     }

        //     return response()->json(['data' => $response], status: isset($response->status)  ? $response->status : 500);
        // } catch (\Throwable $th) {
        //     Log::error($th->getMessage());
        //     return response()->json(['error' => $th->getMessage()], 500);
        // }
    }
}
