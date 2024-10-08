<?php

use App\Http\Controllers\DepositController;
use App\Http\Controllers\FinapiPaymentRecipientController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopifyAuthController;
use App\Http\Controllers\ShopifyController;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\VerifyShopifyRequest;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (request()->has('hmac')) {
        return redirect()->route('shopify.auth.install', request()->all());
    }
    return view('home');
})->name('home');

Route::group(['middleware'=>['auth', 'verified']], function () {
    Route::get('/dashboard', function () { return view('dashboard');})->name('dashboard');

    Route::resource('/profile', ProfileController::class);
    Route::resource( '/orders', OrderController::class);
    Route::resource( '/payments', PaymentController::class);
    Route::resource( '/deposits', DepositController::class);
    Route::get( '/settings', function (){ return view('home'); });
});

require __DIR__.'/auth.php';

Route::prefix('admin')
    ->middleware(EnsureUserIsAdmin::class)
    ->group(function () {
        Route::resource('/finapi-payment-recipient', FinapiPaymentRecipientController::class);
});


Route::prefix('shopify')
    // ->middleware(EnsureUserIsAdmin::class)
    ->group(function () {
        Route::prefix('order')
        // ->middleware(EnsureUserIsAdmin::class)
        ->group(function () {
            Route::get('get-order-by-id', [OrderController::class, 'getShopifyOrderById'])->name('shopify.order.getOrderById');
            Route::get('get-order-by-name', [OrderController::class, 'getShopifyOrderByName'])->name('shopify.order.getOrderByName');
            Route::get('get-order-by-confirmation-number', [OrderController::class, 'getShopifyOrderByConfirmationNumber'])->name('shopify.order.getOrderByConfirmationNumber');
            Route::post('update-order-status', [OrderController::class, 'updateShopifyPaymentStatus'])->name('shopify.order.updateStatus');
            Route::post('mark-order-paid', [OrderController::class, 'markShopifyOrderAsPaid'])->name('shopify.order.markAsPaid');
            Route::post('void-transaction', [OrderController::class, 'voidShopifyTransaction'])->name('shopify.order.voidTransaction');
        });
    Route::prefix('payment')
        // ->middleware(VerifyShopifyRequest::class)
        ->group(function () {
            Route::post('redirect-to-payment-form', [PaymentController::class, 'redirectToFinAPIPaymentForm'])->name('shopify.payment.redirect-to-fin');
    });
    Route::prefix('auth')
        ->middleware(VerifyShopifyRequest::class)
        ->group(function () {
            Route::get('install', [ShopifyAuthController::class, 'install'])->name('shopify.auth.install');
            Route::get('callback', [ShopifyAuthController::class, 'callback'])->name('shopify.auth.callback');
    });
});