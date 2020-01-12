<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('doctors')->group(function () {
        Route::post('register', 'Doctor\Register')->name('doctors.register');
        Route::post('login', 'Doctor\Login')->name('doctors.login');
        Route::post('send-reset-password-link', 'Doctor\SendResetPasswordLink')->name('doctors.send-reset-password-link');
        Route::patch('reset-password', 'Doctor\ResetPassword')->name('doctors.reset-password');
        Route::get('', 'Doctor\Index')->name('doctors.index');
        Route::get('verify-email', 'Doctor\VerifyEmail')->name('doctors.verify-email');
        Route::post('send-email-verification-link', 'Doctor\SendEmailVerificationLink')->name('doctors.send-email-verification-link');
    });

    Route::middleware(['auth:api'])->group(function () {
        Route::prefix('doctors')->group(function () {
            Route::patch('logout', 'Doctor\Logout')->name('doctors.logout');
            Route::get('{doctor}', 'Doctor\Show')->middleware('can:view,doctor');
            Route::patch('{doctor}', 'Doctor\Update')->middleware('can:update,doctor');
            Route::patch('{doctor}/request-activation', 'Doctor\RequestActivation')->middleware('can:activate,doctor');
            Route::patch('{doctor}/close', 'Doctor\Close')->middleware('can:close,doctor');
            Route::get('{doctor}/stripe-connect', 'Doctor\StripeConnect')->middleware('can:stripe-connect,doctor');
            Route::patch('{doctor}/stripe-token', 'Doctor\StripeToken')->middleware('can:stripe-token,doctor');
            Route::get('{doctor}/billings', 'Doctor\Billings')->name('doctors.billings');
            Route::get('{doctor}/enquires', 'Doctor\Enquires')->name('doctors.enquires');
        });

        Route::get('enquires/{enquire}', 'Enquire\Show')->middleware('can:view,enquire');
    });

    Route::get('regions', 'RegionController@index')->name('regions.index');
    Route::get('specializations', 'SpecializationController@index')->name('specializations.index');
    Route::get('languages', 'LanguageController@index')->name('languages.index');
    Route::get('messages/first', 'MessageController@first')->name('messages.first');
    Route::get('messages/{message}', 'MessageController@show')->name('messages.show');
    Route::post('enquires', 'Enquire\Create')->name('enquire.create');
});