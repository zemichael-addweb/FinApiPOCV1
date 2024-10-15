<?php

use App\Http\Controllers\FinApiController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group(['prefix' => 'v1'], function () {

    // Route for getting access token
    Route::post('/get-access-token', [FinApiController::class, 'getAccessToken']);

    // Shopify routes
    Route::prefix('shopify')->group(function () {

        // Shopify order-related routes
        Route::prefix('order')->group(function () {
            Route::get('get-order-by-id', [OrderController::class, 'getShopifyOrderById'])
                ->name('api.shopify.order.getOrderById');
            Route::get('get-order-by-confirmation-number', [OrderController::class, 'getShopifyOrderByConfirmationNumber'])
                ->name('api.shopify.order.getOrderByConfirmationNumber');
        });

        // Shopify payment-related routes
        Route::prefix('payment')->group(function () {
            Route::post('make-direct-debit', [PaymentController::class, 'makeDirectDebit'])
                ->name('api.shopify.payment.makeDirectDebit');

            Route::post('make-direct-debit-with-webform', [PaymentController::class, 'makeDirectDebitWithWebform'])->name('api.shopify.payment.make-direct-debit-with-webform');
        });
    });
});
