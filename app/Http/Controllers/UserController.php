<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Infusionsoft\Infusionsoft;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

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
        $infusionsoft = new Infusionsoft(array(
            'clientId' => getenv('INFUSIONSOFT_CLIENT_ID'),
            'clientSecret' => getenv('INFUSIONSOFT_CLIENT_SECRET'),
            'redirectUri' => getenv('INFUSIONSOFT_REDIRECT_URI'),
        ));

        // If the serialized token is already available in the database,
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

        if ($infusionsoft->getToken()) {
            // Save the serialized token to the database for ease of
            // use in subsequent requests
            Auth::user()->token = serialize($infusionsoft->getToken());

            Auth::user()->save();

            // Redirect the user back to the dashboard
            return redirect('/dashboard');

        }

        // Something didn't work, so let's go back to the beginning
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
