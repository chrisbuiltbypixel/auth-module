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
    Route::post('login', 'Api\LoginController@login');
    Route::post('logout', 'Api\LoginController@logout');

    // Password Reset Routes...
    // Route::post('password/email', 'Api\ForgotPasswordController@sendResetLinkEmail');
    // Route::get('password/reset', 'Api\ForgotPasswordController@showLinkRequestForm');
    // Route::post('password/reset', 'Api\ResetPasswordController@reset');
    // Route::get('password/reset/{token}', 'Api\ResetPasswordController@showResetForm');
});
