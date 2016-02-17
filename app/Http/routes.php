<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use Illuminate\Http\Request;
use GuzzleHttp\Client;
//use Infusionsoft\Infusionsoft;

//Route::get('/', 'UserController@index');


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

//Route::get('/admin/callback', 'UserController@update');

Route::group(['middleware' => ['web']], function () {

    Route::auth();

    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/dashboard', 'InfusionsoftController@countUnSyncedContacts');

    Route::get('/contacts', 'InfusionsoftController@getSubscribersFromMC');
    Route::get('/admin/callback', 'UserController@update');

    Route::get('/admin/register', function() {

        $infusionsoft = new Infusionsoft\Infusionsoft(array(
            'clientId' => 'at3xrpwexxc5zjt9jamrsdem',//getenv('INFUSIONSOFT_CLIENT_ID'),
            'clientSecret' => 'RH9nrfQAnE',//getenv('INFUSIONSOFT_CLIENT_SECRET'),
            'redirectUri' => 'http://stripe.app/admin/callback',//getenv('INFUSIONSOFT_REDIRECT_URI'),
        ));

        echo '<a href="' . $infusionsoft->getAuthorizationUrl() . '">Click here to connect to Infusionsoft</a>';
    });
});
