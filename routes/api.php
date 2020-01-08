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

// for logged-in doctors
Route::middleware(['auth:api'])->group(function () {
    Route::get('user/profile',               'UserController@profile');
    Route::put('user/password',              'UserController@updatePassword');
    Route::post('user/photo',                'UserController@updatePhoto');
    Route::put('user',                       'UserController@update');
    Route::put('password',                   'UserController@updatePassword');
    Route::get('logout',                     'UserController@logout');
    Route::get('submissions/open',           'SubmissionController@indexOpen'); // open submissions
    Route::get('submissions/answered-by-me', 'SubmissionController@indexAnsweredByMe'); // answered by me
    Route::get('submission/{id}/assign',     'SubmissionController@assign');
    Route::get('submission/{id}/release',    'SubmissionController@release');
    Route::post('submission/{id}/answer',    'SubmissionController@answer');
    Route::post('submission/{id}/question',  'SubmissionController@question'); // Rückfrage
    Route::get('submission/{id}',            'SubmissionController@showById');
    Route::get('user/stats',                 'SubmissionController@statsByUser');
});

// without Authentication
Route::get('pricingtable',                                       'SubmissionController@getPricingTable');
Route::get('submissions/{submission}/photo/{image_id}/{width?}', 'SubmissionController@showPhoto')->name('submissions.showPhoto'); // for clients
Route::get('submission/{id}/photo/{image_id}/{width?}',          'SubmissionController@showPhotoBySubmissionId'); // for doctors (users)
Route::post('submission/{submission}/evaluate',                  'SubmissionController@evaluate'); // for clients
Route::post('submission/{submission}/question/{question}/answer','SubmissionController@answerQuestion'); // Rückfrage beantworten
Route::get('submissions/{submission}',                           'SubmissionController@show')->name('submissions.show');
Route::post('submissions/photoupload',                           'SubmissionController@uploadPhoto')->name('submissions.uploadPhoto');
Route::delete('submissions/photoupload/{identifier?}',           'SubmissionController@fakeUploadPhotoDelete');
Route::post('submissions',                                       'SubmissionController@store')->name('submissions.store');
Route::post('stripe/createCheckoutSession',                      'StripeController@createCheckoutSession');
Route::post('stripe/payment',                                    'StripeController@creditcardPayment');
Route::get('stripe/authorizesofort',                             'StripeController@authorizeSofort');       // returns view or json
Route::get('stripe/checkcreditcardstate',                        'StripeController@checkcreditcardstate')->name('checkcreditcardstate');  // returns view or json
Route::get('stripe/app-checkout',                                'StripeController@appCheckout');  // creditcard payment for apps
Route::post('stripe/webhook',                                    'StripeController@webhook');

Route::prefix('v1')->group(function () {
    Route::post('register', 'API\V1\DoctorController@register')->name('doctors.register');
    Route::post('login', 'API\V1\DoctorController@login')->name('doctors.login');
    Route::post('send-reset-link', 'API\V1\DoctorController@sendResetLinkEmail');
    Route::patch('update-password', 'API\V1\DoctorController@updatePassword');
    Route::get('regions', 'API\V1\RegionController@index')->name('regions.index');
    Route::get('specializations', 'API\V1\SpecializationController@index')->name('specializations.index');
    Route::get('languages', 'API\V1\LanguageController@index')->name('languages.index');
    Route::get('doctors', 'API\V1\DoctorController@index')->name('doctors.index');
    Route::get('verify/{id}', 'API\V1\DoctorController@verify')->name('verification.verify');
    Route::get('resend/{id}', 'API\V1\DoctorController@resend')->name('verification.resend');
    Route::get('messages/first', 'API\V1\MessageController@first')->name('messages.first');
    Route::get('messages/{message}', 'API\V1\MessageController@show')->name('messages.show');
    Route::post('enquires', 'API\V1\EnquireController@create')->name('enquire.create');
});

Route::prefix('v1')->middleware(['auth:api'])->group(function () {
    Route::patch('logout', 'API\V1\DoctorController@logout')->name('doctors.logout');
    Route::get('doctors/{doctor}', 'API\V1\DoctorController@show')->middleware('can:view,doctor');
    Route::patch('doctors/{doctor}', 'API\V1\DoctorController@update')->middleware('can:update,doctor');
    Route::patch('doctors/{doctor}/activate', 'API\V1\DoctorController@activate')->middleware('can:activate,doctor');
    Route::patch('doctors/{doctor}/close', 'API\V1\DoctorController@close')->middleware('can:close,doctor');
    Route::get('enquires', 'API\V1\EnquireController@index')->name('enquires.index');
    Route::get('enquires/{enquire}', 'API\V1\EnquireController@show')->middleware('can:view,enquire');
});