<?php

use App\Http\Controllers\FinApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group(['prefix' => 'v1'], function () {
    Route::get('/get-access-token', [FinApiController::class, 'getAccessToken']);
});