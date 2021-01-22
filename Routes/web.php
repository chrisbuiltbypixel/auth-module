<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::group(['prefix' => 'auth'], function () {
    // Authentication Routes...
    Route::post('login', 'UserDashboard\LoginController@login');
    Route::post('logout', 'UserDashboard\LoginController@logout');

    // // Password Reset Routes...
    // Route::post('password/reset', 'UserDashboard\ResetPasswordController@reset');
    // Route::get('password/reset/{token}', 'UserDashboard\ResetPasswordController@showResetForm');

    // // Registration Routes...
    // Route::post('register/{token?}', 'UserDashboard\RegisterController@store');

    // // Email Verification...
    // Route::get('email-verification/{token}', 'UserDashboard\VerificationController@verifyEmailAddress');
});
