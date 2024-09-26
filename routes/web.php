<?php

use App\Http\Controllers\DepositController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
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