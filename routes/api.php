<?php

use App\Http\Controllers\FinApiController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group(['prefix' => 'v1'], function () {
    Route::get('/get-access-token', [FinApiController::class, 'getAccessToken']);
    Route::prefix('shopify')
    // ->middleware(EnsureUserIsAdmin::class)
    ->group(function () {
        Route::prefix('order')
        // ->middleware(EnsureUserIsAdmin::class)
        ->group(function () {
            Route::get('get-order-by-id', [OrderController::class, 'getShopifyOrderById'])->name('shopify.order.getOrderById');
            Route::get('get-order-by-confirmation-number', [OrderController::class, 'getShopifyOrderByConfirmationNumber'])->name('shopify.order.getOrderByConfirmationNumber');
        }); 
    });
});