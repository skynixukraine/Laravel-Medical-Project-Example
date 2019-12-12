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
Route::post('register',                                          'API\DoctorController@register');
Route::post('login',                                              'API\DoctorController@login');
Route::post('send-reset-link',                                   'API\DoctorController@sendResetLinkEmail');
Route::patch('update-password',                                  'API\DoctorController@updatePassword')->name('password.update');

//Route::middleware('auth:api')->get('/submission/open', function (Request $request) {
//    return "hello ";
//});
