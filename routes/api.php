<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('enquires')->group(function () {
        Route::post('', 'Enquire\Create')->name('enquire.create');
        Route::get('{enquire}/send-sms', 'Enquire\SendSMS');
        Route::post('{enquire}/verify-sms', 'Enquire\VerifySMS');
        Route::post('validate-hash', 'Enquire\ValidateHash');
        Route::get('{enquire}/conclusion-status', 'Enquire\ConclusionStatus');
        Route::get('{enquire}/download-conclusion', 'Enquire\DownloadConclusion');
        Route::get('verify-email', 'Enquire\VerifyEmail')->name('enquire.verify-email');
        Route::get('payment-methods', 'Enquire\PaymentMethods')->name('enquire.payment-methods');
        Route::patch('{enquire}/charge', 'Enquire\Charge')->name('enquire.charge');
    });

    Route::prefix('doctors')->group(function () {
        Route::post('register', 'Doctor\Register')->name('doctors.register');
        Route::post('login', 'Doctor\Login')->name('doctors.login');
        Route::post('send-reset-password-link', 'Doctor\SendResetPasswordLink')->name('doctors.send-reset-password-link');
        Route::patch('reset-password', 'Doctor\ResetPassword')->name('doctors.reset-password');
        Route::get('', 'Doctor\Index')->name('doctors.index');
        Route::get('verify-email', 'Doctor\VerifyEmail')->name('doctors.verify-email');
        Route::post('send-email-verification-link', 'Doctor\SendEmailVerificationLink')->name('doctors.send-email-verification-link');
        Route::post('change-email', 'Doctor\ChangeEmail');
    });

    Route::middleware(['auth:api'])->group(function () {
        Route::prefix('doctors')->group(function () {
            Route::patch('logout', 'Doctor\Logout')->name('doctors.logout');
            Route::get('{doctor}', 'Doctor\Show')->middleware('can:view,doctor');
            Route::patch('{doctor}', 'Doctor\Update')->middleware(['can:update,doctor']);
            Route::patch('{doctor}/request-activation', 'Doctor\RequestActivation')->middleware('can:request-activation,doctor');
            Route::patch('{doctor}/close', 'Doctor\Close')->middleware('can:close,doctor');
            Route::patch('{doctor}/activate', 'Doctor\Activate')->middleware('can:activate,doctor');
            Route::patch('{doctor}/deactivate', 'Doctor\Deactivate')->middleware('can:deactivate,doctor');
            Route::get('{doctor}/stripe-connect', 'Doctor\StripeConnect')->middleware('can:stripe-connect,doctor');
            Route::patch('{doctor}/stripe-token', 'Doctor\StripeToken')->middleware('can:stripe-token,doctor');
            Route::get('{doctor}/billings', 'Doctor\Billings')->name('doctors.billings');
            Route::get('{doctor}/enquires', 'Doctor\Enquires')->name('doctors.enquires');
            Route::post('send-change-email-request-link', 'Doctor\SendChangeEmailRequestLink');
            Route::delete('{doctor}/delete', 'Doctor\Delete')->middleware('can:delete,doctor');
        });

        Route::prefix('enquires')->group(function () {
            Route::get('{enquire}', 'Enquire\Show')->middleware('can:view,enquire');
            Route::patch('{enquire}/update-conclusion', 'Enquire\UpdateConclusion')->middleware('can:update-conclusion,enquire');
            Route::patch('{enquire}/close', 'Enquire\Close')->middleware('can:close,enquire');
            Route::post('{enquire}/add-message', 'Enquire\AddMessage')->middleware('can:add-message,enquire');
            Route::get('{enquire}/messages', 'Enquire\Messages')->middleware('can:messages,enquire');
        });
    });

    Route::get('regions', 'RegionController@index')->name('regions.index');
    Route::get('doctor-titles', 'DoctorTitleController@index')->name('doctor-titles.index');
    Route::get('specializations', 'SpecializationController@index')->name('specializations.index');
    Route::get('languages', 'LanguageController@index')->name('languages.index');
    Route::get('messages/first', 'MessageController@first')->name('messages.first');
    Route::get('messages/{message}', 'MessageController@show')->name('messages.show');
    Route::post('contact', 'ContactController@index')->name('contacts.index');
});