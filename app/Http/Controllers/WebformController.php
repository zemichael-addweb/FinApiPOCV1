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

class WebformController extends Controller
{
    public function index(Request $request){
        $webforms = json_decode($this->getWebforms($request)->getContent());
        return view('webforms.webforms-index', compact('webforms'));
    }

    public function show($id) {
        $finApiUserAccessToken = FinAPIService::getAccessToken('user');

        if($finApiUserAccessToken instanceof JsonResponse){
            return $finApiUserAccessToken;
        }

        $webform = FinAPIService::fetchWebform($finApiUserAccessToken->access_token, $id);

        return response()->json($webform);
    }

    public function getWebforms(Request $request)
    {
        $finApiUserAccessToken = FinAPIService::getAccessToken('user');

        if($finApiUserAccessToken instanceof JsonResponse){
            return $finApiUserAccessToken;
        }

        $filters = array_filter([
            'page' => $request->page,
            'perPage' => $request->perPage,
            'order' => $request->order,
        ], function ($filterItem){
            return $filterItem !== null;
        });

        try {
            $bankConnections = FinAPIService::fetchWebforms($finApiUserAccessToken->access_token, $filters);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        if ($bankConnections) {
            return response()->json($bankConnections);
        }

        return response()->json(['message' => 'No Bank Connections found. Please add one.'], 404);
    }
}
