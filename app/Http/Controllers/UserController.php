<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Infusionsoft\Infusionsoft;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
//    public function index()
//    {
//        return Auth::user();
//        $users = User::all();
//        return view('pages.index')->with('users', $users);
//    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
//    protected function create()
//    {
//        return view('auth.register');
//    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
//    public function store(Request $request)
//    {
//        $input = $request->all();
//        User::create($input);
//        return redirect('pages.index');
//    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //Auth::user()->request->session()->get('code');
        // Setup a new Infusionsoft SDK object
        $infusionsoft = new Infusionsoft(array(
            'clientId' => 'at3xrpwexxc5zjt9jamrsdem',//getenv('INFUSIONSOFT_CLIENT_ID'),
            'clientSecret' => 'RH9nrfQAnE',//getenv('INFUSIONSOFT_CLIENT_SECRET'),
            'redirectUri' => 'http://stripe.app/admin/callback',//getenv('INFUSIONSOFT_REDIRECT_URI'),
        ));

        // If the serialized token is already available in the session storage,
        // we tell the SDK to use that token for subsequent requests, rather
        // than try and retrieve another one.
        if (Auth::user()->token) {
            $infusionsoft->setToken(unserialize(Auth::user()->token));
        }


        // If we are returning from Infusionsoft we need to exchange the code
        // for an access token.
        if ($request->get('code') and !$infusionsoft->getToken()) {

            $infusionsoft->requestAccessToken($request->get('code'));

        }

        // NOTE: there's some magic in the step above - the Infusionsoft SDK has
        // not only requested an access token, but also set the token in the current
        // Infusionsoft object, so there's no need for you to do it.

        if ($infusionsoft->getToken()) {
           // echo 'hello';
            // Save the serialized token to the current session for subsequent requests
            // NOTE: this can be saved in your database - make sure to serialize the
            // entire token for easy future access
//            $token = $infusionsoft->getToken();
//            Auth::user()->access_token = $token->accessToken;
//            Auth::user()->endOfLife = $token->endOfLife;
            Auth::user()->token = serialize($infusionsoft->getToken());

            Auth::user()->save();

            //$request->session()->put('token', serialize($infusionsoft->getToken()));

            // Now redirect the user to a page that performs some Infusionsoft actions
            return redirect('home');
        }

        // something didn't work, so let's go back to the beginning
        return redirect()->to('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
