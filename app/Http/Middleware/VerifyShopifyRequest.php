<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\ShopifyAuthController;

class VerifyShopifyRequest
{
    public function handle(Request $request, Closure $next)
    {
        $shopifyAuthController = new ShopifyAuthController();

        if (!$shopifyAuthController->verifyShopifyRequest($request)) {
            return response()->json(['error' => 'Invalid Shopify request'], 403);
        }

        return $next($request);
    }
}
