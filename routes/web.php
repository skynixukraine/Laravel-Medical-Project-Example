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

// those are only used for local development to have a parent page with iframes.
// In production those pages are setup in Wordpress
if (App::Environment() != "production") {
    Route::view('website-example-ohn', 'website-example-ohn');
    Route::view('website-example-ita', 'website-example-ita');
    Route::view('website-example-sna', 'website-example-sna');
    Route::view('creditcard', 'creditcard');
    Route::view('sofort', 'sofort');
}

// iframe embedded pages
Route::view('case-submit-ohn', 'case-submit-ohn');
Route::view('case-submit-ita', 'case-submit-ita');
Route::view('case-submit-sna', 'case-submit-sna');
Route::view('case-search-ohn', 'case-search-ohn');
Route::view('case-search-ita', 'case-search-ita');
Route::view('case-search-sna', 'case-search-sna');

// aerzteportal
Route::domain(config('app.MIX_PORTAL_DOMAIN'))->group(function () {
    Route::view('/', 'portal')->name('portal');
});

Route::get('login', 'PageController@fakelogin')->name('login'); // this is only needed because laravel wants to redirect to "login" if an unauthorized request was made
Route::get('healthcheck', 'PageController@healthcheck');
