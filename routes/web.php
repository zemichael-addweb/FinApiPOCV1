<?php

use App\Http\Controllers\DepositController;
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
        Route::get('/checking', function(){return  'You are an admin';})->name('checking');
});


Route::prefix('shopify')
    // ->middleware(EnsureUserIsAdmin::class)
    ->group(function () {
        Route::post('order/update-order-status', [OrderController::class, 'updateShopifyPaymentStatus'])->name('shopify.order.updateStatus');
        Route::post('order/mark-order-paid', [OrderController::class, 'markShopifyOrderAsPaid'])->name('shopify.order.markAsPaid');
        Route::post('order/void-transaction', [OrderController::class, 'voidShopifyTransaction'])->name('shopify.order.voidTransaction');
        Route::prefix('auth')
            ->middleware(VerifyShopifyRequest::class)
            ->group(function () {
                Route::get('install', [ShopifyAuthController::class, 'install'])->name('shopify.auth.install');
                Route::get('callback', [ShopifyAuthController::class, 'callback'])->name('shopify.auth.callback');
        });
});