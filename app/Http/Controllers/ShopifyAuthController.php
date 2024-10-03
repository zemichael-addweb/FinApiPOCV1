<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class ShopifyAuthController extends Controller
{
    function verifyShopifyRequest($request)
    {
        $params = $request->all();
        
        $hmac = Arr::pull($params, 'hmac');

        if(!$hmac) {
            return false;
        }
    
        ksort($params);
    
        $queryString = http_build_query($params, '', '&', PHP_QUERY_RFC3986);

        $generatedHmac = hash_hmac('sha256', $queryString, config('shopify.client_secret'));
    
        return hash_equals($generatedHmac, $hmac);
    }

    protected function generateAuthUrl($shop)
    {
        $shopifyConfig = config('shopify');
        $scopes = $shopifyConfig['scopes'];
        $redirectUri = $shopifyConfig['auth_callback_url'];
        $clientId = $shopifyConfig['client_id'];
    
        $nonce = bin2hex(random_bytes(16));
        session(['shopify_nonce' => $nonce]);
    
        $query = http_build_query([
            'client_id' => $clientId,
            'scope' => $scopes,
            'redirect_uri' => $redirectUri,
            'state' => $nonce,
            'grant_options[]' => 'per-user', 
        ]);
    
        return "https://{$shop}.myshopify.com/admin/oauth/authorize?" . $query;
    }
    
    public function install(Request $request)
    {
        if (!$this->verifyShopifyRequest($request)) {
            return response()->json(['error' => 'Invalid request'], 403);
        }
    
        $shop = config('shopify.test_shop_name');
    
        $authUrl = $this->generateAuthUrl($shop);
        $nonce = session('shopify_nonce');

        $requestContentType = 'application/json';

        return redirect()->away($authUrl)
            ->withCookie(cookie()->forever('nonce', $nonce));
    }
    
    // Verifies the HMAC provided by Shopify
    protected function verifyHmac(array $params, $hmac)
    {
        $clientSecret = config('shopify.client_secret');

        unset($params['hmac']);
        ksort($params);

        $queryString = urldecode(http_build_query($params));

        $calculatedHmac = hash_hmac('sha256', $queryString, $clientSecret);

        return hash_equals($calculatedHmac, $hmac);
    }

    // Validates the shop parameter
    protected function isValidShopDomain($shop)
    {
        return preg_match('/^[a-zA-Z0-9][a-zA-Z0-9\-]*\.myshopify\.com$/', $shop);
    }


    public function callback(Request $request)
    {
        $shop = $request->get('shop');
        $code = $request->get('code');
        $state = $request->get('state');
        $hmac = $request->get('hmac');
        
        $storedNonce = session('shopify_nonce');
        if ($storedNonce !== $state) {
            return response()->json(['error' => 'Invalid nonce'], 403);
        }
    
        if (!$this->verifyHmac($request->all(), $hmac)) {
            return response()->json(['error' => 'Invalid HMAC'], 403);
        }
    
        if (!$this->isValidShopDomain($shop)) {
            return response()->json(['error' => 'Invalid shop domain'], 403);
        }
    
        $response = Http::post("https://{$shop}/admin/oauth/access_token", [
            'client_id' => config('shopify.client_id'),
            'client_secret' => config('shopify.client_secret'),
            'code' => $code
        ]);
    
        if ($response->successful()) {
            $accessToken = $response->json('access_token');
        } else {
            return response()->json(['error' => 'Unable to retrieve access token'], 500);
        }
    }
    
}
