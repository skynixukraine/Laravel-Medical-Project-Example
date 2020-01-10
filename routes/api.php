<?php

use Illuminate\Support\Facades\Route;

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

Route::prefix('v1')->group(function () {
    Route::post('register', 'API\V1\Doctor\Register')->name('doctors.register');
    Route::post('login', 'API\V1\Doctor\Register\Login')->name('doctors.login');
    Route::post('send-reset-link', 'API\V1\Doctor\SendResetLinkEmail');
    Route::patch('update-password', 'API\V1\Doctor\UpdatePassword');
    Route::get('regions', 'API\V1\RegionController@index')->name('regions.index');
    Route::get('specializations', 'API\V1\SpecializationController@index')->name('specializations.index');
    Route::get('languages', 'API\V1\LanguageController@index')->name('languages.index');
    Route::get('doctors', 'API\V1\Doctor\Doctors')->name('doctors.index');
    Route::get('verify/{id}', 'API\V1\Doctor\VerifyEmail')->name('verification.verify');
    Route::get('resend/{id}', 'API\V1\Doctor\ResendVerificationEmail')->name('verification.resend');
    Route::get('messages/first', 'API\V1\MessageController@first')->name('messages.first');
    Route::get('messages/{message}', 'API\V1\MessageController@show')->name('messages.show');
    Route::post('enquires', 'API\V1\EnquireController@create')->name('enquire.create');
});

Route::prefix('v1')->middleware(['auth:api'])->group(function () {
    Route::patch('logout', 'API\V1\Doctor\Logout')->name('doctors.logout');
    Route::get('doctors/{doctor}', 'API\V1\Doctor\Show')->middleware('can:view,doctor');
    Route::patch('doctors/{doctor}', 'API\V1\Doctor\Update')->middleware('can:update,doctor');
    Route::patch('doctors/{doctor}/activate', 'API\V1\Doctor\Activate')->middleware('can:activate,doctor');
    Route::patch('doctors/{doctor}/close', 'API\V1\Doctor\Close')->middleware('can:close,doctor');
    Route::get('doctors/{doctor}/stripe-connect', 'API\V1\Doctor\StripeConnect')->middleware('can:stripe-connect,doctor');
    Route::patch('doctors/{doctor}/stripe-token', 'API\V1\Doctor\StripeToken')->middleware('can:stripe-token,doctor');
    Route::get('doctors/{doctor}/billings', 'API\V1\Doctor\Billings');
    Route::get('enquires', 'API\V1\EnquireController@index')->name('enquires.index');
    Route::get('enquires/{enquire}', 'API\V1\EnquireController@show')->middleware('can:view,enquire');
});