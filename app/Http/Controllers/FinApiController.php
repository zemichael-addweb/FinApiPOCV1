<?php

namespace App\Http\Controllers;

use App\Models\FinAPIAccessToken;
use App\Services\FinAPIService;
use App\Services\HelperServices;
use Exception;
use FinAPI\Client\Api\AuthorizationApi;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class FinApiController extends Controller
{
    public function getAccessToken(Request $request)
    {
        $type = $request->type;
        $email = $request->email;
        $password = $request->password;

        return response()->json(FinAPIService::getAccessToken($type, $email, $password));
    }
}
