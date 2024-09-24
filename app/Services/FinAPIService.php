<?php

namespace App\Services;

use App\Models\FinAPIAccessToken;
use App\Services\HelperServices;
use Exception;
use FinAPI\Client\Api\AuthorizationApi;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use stdClass;

class FinAPIService {
    public function __construct() {
        //
    }

    public static function getGrantType() {return config('finApi.grant_type.client_credentials');}
    public static function getClientId(){return config('finApi.default.clientId');}
    public static function getClientSecret(){return config('finApi.default.clientSecret');}

    
    public static function authenticate() {
        $apiInstance = new AuthorizationApi(new Client());
        $grant_type = self::getGrantType();
        $client_id = self::getClientId();
        $client_secret = self::getClientSecret();
        $x_request_id = null;
        $refresh_token = null;
        $username = null;
        $password = null;
        
        try {
            $result = $apiInstance->getToken($grant_type, $client_id, $client_secret, $x_request_id, $refresh_token, $username, $password);

            if($result && $result->getAccessToken()) {
                $accessToken = new FinAPIAccessToken([
                        'access_token' => $result->getAccessToken(),
                        'token_type' => $result->getTokenType(),
                        'scope' => $result->getScope(),
                        'expires_in' => $result->getExpiresIn(),
                        'refresh_token' => $result->getRefreshToken()
                ]);

                $accessToken->save();
                Log::info('Token Updated Successfully', ['data'=>$result]);
                return $accessToken;
            }

            Log::info('Error Updating Token', ['data' => $result]);
            return  $result;
        } catch (Exception $e) {
            Log::info('Error Response', ['res',$e->getMessage(), 'status'=>$e->getCode(), 'file'=>$e->getFile(), 'line'=>$e->getLine()]);
            $error = new stdClass();
            $error->error = $e->getMessage();
            $error->statusCode = $e->getCode();
            return $error;
        }
    }
}