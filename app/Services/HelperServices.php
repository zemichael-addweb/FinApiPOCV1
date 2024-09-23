<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Ixudra\Curl\Facades\Curl;

class HelperServices{

    public static function makeHttpRequest($url, $method, $data) {
        $client = new \GuzzleHttp\Client();
        $data = [
            'headers' => [
                'Content-Type' => 'application/json',
                'accept' => 'application/json',
            
            ],
            'data' => $data,
        ];
        $response = $client->request($method, $url, $data)->getBody();
        return $response;
    }

    public static function makeCurlHttpRequest($url, $method, $data) {
        try {
            $response = Curl::to($url)
                ->withHeaders(array('Content-type:application/json, accept:application/json'))
                ->withData($data)
                ->asJson();

            Log::info('Response',['res'=> $response]);

            if($method == 'GET') {
                $response = $response->get();
            } elseif($method == 'POST'){
                $response = $response->post();
            } elseif($method == 'PUT'){
                $response = $response->put();
            } elseif($method == 'DELETE'){
                $response = $response->delete();
            }

            return $response;
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th->getMessage());
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}    


