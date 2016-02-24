<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;


class MailchimpRegisterController extends Controller
{

    /**
     * Exchange our code for an access token and save results to database
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function callback(Request $request)
    {
        // Get the code from the request
        $code = $request->get('code');

        $client = new Client();

        // Post a request to get the token
        $request = $client->request('POST', 'https://login.mailchimp.com/oauth2/token', [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'client_id' => getenv('MAILCHIMP_CLIENT_ID'),
                'client_secret' => getenv('MAILCHIMP_CLIENT_SECRET'),
                'redirect_uri' => 'http://mailsoft.app/dashboard/mailchimp/callback',
                'code' => $code
            ]
        ]);

        $token = $request->getBody()->getContents();

        $token = json_decode($token);

        // Make GET request sending access token in the header
        $response = $client->request('GET', 'https://login.mailchimp.com/oauth2/metadata',[
            'headers' => [
                'Authorization' => 'OAuth '.$token->access_token
            ]
        ]);

        // Save the response in database so user doesn't have to register
        // application again
        if($response->getBody()->getContents()) {
            Auth::user()->mailchimp = serialize( $response->getBody()->getContents());
            Auth::user()->save();
        }

        return redirect('/dashboard');

    }

}