<?php

// Home route
Route::get('/', function() {
    return view('home');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {

    Route::auth();

    Route::get('/dashboard', 'InfusionsoftController@countUnSyncedContacts');

    Route::get('/dashboard/infusionsoft/callback', 'UserController@update');

    Route::get('/dashboard/mailchimp/callback', 'MailchimpRegisterController@callback');

    Route::get('/dashboard/mailchimp/getaccesstoken', 'MailchimpRegisterController@getaccesstoken');

    Route::get('/contacts', 'InfusionsoftController@getSubscribersFromMC');
});
