<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Ixudra\Curl\Facades\Curl;

class HelperServices{

    public static function makeHttpRequest($url, $method, $data) {
        $client = new \GuzzleHttp\Client();
        $response = $client->request($method, $url, $data)->getBody();
        return $response;
    }

    public static function makeCurlHttpRequest($url, $method, $data) {
        try {
            $request = Curl::to($url)
                ->withHeaders(array('Content-type:application/json;charset=UTF-8', 'Accept:application/json', 'Context-length:0'))
                ->withData($data)
                ->asJson();

            switch($method) {
                case 'GET':
                    return $request->get();
                case 'POST':
                    return $request->post();
                case 'PUT':
                    return $request->put();
                case 'DELETE':
                    return $request->delete();
            }

            throw new \Exception('Method not supported');
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th->getMessage());
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}    


