<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::group(['prefix' => 'auth'], function () {
    // Authentication Routes...
    Route::post('login', '\Modules\Auth\Http\Controllers\Api\LoginController@login');
    Route::post('logout', '\Modules\Auth\Http\Controllers\Api\LoginController@logout');

    // Password Reset Routes...
    Route::post('password/email', 'Modules\Auth\Http\Controllers\Api\ForgotPasswordController@sendResetLinkEmail');
    Route::get('password/reset', 'Modules\Auth\Http\Controllers\Api\ForgotPasswordController@showLinkRequestForm');
    Route::post('password/reset', 'Modules\Auth\Http\Controllers\Api\ResetPasswordController@reset');
    Route::get('password/reset/{token}', 'Modules\Auth\Http\Controllers\Api\ResetPasswordController@showResetForm');
});
