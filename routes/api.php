<?php

use App\Http\Controllers\Api\V1\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

Route::group(['middleware' => 'jwt'], function () {
    Route::get('countries', 'App\Http\Controllers\Api\V1\Controllers\CurrencyConverterController@getCountriesMoney');
    Route::post('currency-convert', 'App\Http\Controllers\Api\V1\Controllers\CurrencyConverterController@currencyConvert');
});
