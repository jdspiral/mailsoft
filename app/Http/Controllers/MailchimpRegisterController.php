<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use GuzzleHttp\Client;
use Infusionsoft\Infusionsoft;
use App\Helpers\Token;


class MailchimpRegisterController extends Controller
{

    public function getLists(Request $request)
    {
        $client = new Client();
        $response = $client->get('https://us8.api.mailchimp.com/3.0/lists/09e3b22872/members', [
            'auth' => [
                'somestring', 'cffe5df69579c623d7323d5cfbc5a037'
            ]
        ]);
    //    echo '<pre>';
    //    echo $response->getBody();
    //    echo '</pre>';

        $contacts = $response->getBody()->getContents();
        echo '<pre>';
        $contacts = json_decode($contacts);
        print_r($contacts);
        echo '</pre>';

        $infusionsoft = new Infusionsoft(array(
            'clientId' => getenv('INFUSIONSOFT_CLIENT_ID'),
            'clientSecret' => getenv('INFUSIONSOFT_CLIENT_SECRET'),
            'redirectUri' => getenv('INFUSIONSOFT_REDIRECT_URI'),
        ));
        // Set the token if we have it in storage (in this case, a session)
        $infusionsoft->setToken(unserialize($request->session()->get('token')));


        try {
            $contact = $infusionsoft->contacts()->findByEmail($contacts->members[0]->email_address, ['Id']);
            if ($contact) {

            } else {
                $infusionsoft->contacts()->add(['FirstName' => $contacts->members[0]->merge_fields->FNAME, 'LastName' => $contacts->members[0]->merge_fields->LNAME, 'Email' => $contacts->members[0]->email_address]);
            }
        } catch (InfusionsoftTokenExpiredException $e) {
            // Refresh our access token since we've thrown a token expired exception
            $infusionsoft->refreshAccessToken();

            // We also have to save the new token, since it's now been refreshed.
            // We serialize the token to ensure the entire PHP object is saved
            // and not accidentally converted to a string
            $request->session()->put('token', serialize($infusionsoft->getToken()));

            // Retrieve the list of contacts again now that we have a new token
            $contact = $infusionsoft->contacts()->findByEmail($contacts->members[0]->email_address, ['Id']);
            if ($contact) {

            } else {
                $infusionsoft->contacts->add(['FirstName' => $contacts->members[0]->merge_fields->FNAME, 'LastName' => $contacts->members[0]->merge_fields->LNAME, 'Email' => $contacts->members[0]->email_address]);
            }


        }
    }

    public function registerIS()
    {

        $infusionsoft = new Infusionsoft(array(
            'clientId' => getenv('INFUSIONSOFT_CLIENT_ID'),
            'clientSecret' => getenv('INFUSIONSOFT_CLIENT_SECRET'),
            'redirectUri' => getenv('INFUSIONSOFT_REDIRECT_URI'),
        ));


        echo '<a href="' . $infusionsoft->getAuthorizationUrl() . '">Click here to connect to Infusionsoft';
    }

    public function getToken(Request $request)
    {
        $infusionsoft = new Infusionsoft(array(
            'clientId' => getenv('INFUSIONSOFT_CLIENT_ID'),
            'clientSecret' => getenv('INFUSIONSOFT_CLIENT_SECRET'),
            'redirectUri' => getenv('INFUSIONSOFT_REDIRECT_URI'),
        ));

        if ($request->session()->has('token')) {
            $infusionsoft->setToken(unserialize($request->session()->get('token')));
        }

        // If we are returning from Infusionsoft we need to exchange the code
        // for an access token.
        if ($request->session()->has('code') and !$infusionsoft->getToken()) {
            $infusionsoft->requestAccessToken($request->session()->get('code'));
        }

        // NOTE: there's some magic in the step above - the Infusionsoft SDK has
        // not only requested an access token, but also set the token in the current
        // Infusionsoft object, so there's no need for you to do it.

        if ($infusionsoft->getToken()) {
            // Save the serialized token to the current session for subsequent requests
            // NOTE: this can be saved in your database - make sure to serialize the
            // entire token for easy future access
            //$request->session()->put('token', serialize($infusionsoft->getToken()));
           // return redirect()->to('/mc');
        }
       // return redirect()->to('/');
    }

//    public function getContacts(Request $request)
//    {

//        $infusionsoft = new Infusionsoft(array(
//            'clientId' => getenv('INFUSIONSOFT_CLIENT_ID'),
//            'clientSecret' => getenv('INFUSIONSOFT_CLIENT_SECRET'),
//            'redirectUri' => getenv('INFUSIONSOFT_REDIRECT_URI'),
//        ));
//        // Set the token if we have it in storage (in this case, a session)
//        $infusionsoft->setToken(unserialize($request->session()->get('token')));
//
//
//        try {
//            $contact = $infusionsoft->contacts()->findByEmail($contacts->members[0]->email_address, ['Id']);
//            if ($contact) {
//                echo 'yo';
//            } else {
//                $infusionsoft->contacts->add(['FirstName' => $contacts->members[0]->merge_fields->FNAME, 'LastName' => $contacts->members[0]->merge_fields->LNAME, 'Email' => $contacts->members[0]->email_address]);
//            }
//        } catch (InfusionsoftTokenExpiredException $e) {
//            // Refresh our access token since we've thrown a token expired exception
//            $infusionsoft->refreshAccessToken();
//
//            // We also have to save the new token, since it's now been refreshed.
//            // We serialize the token to ensure the entire PHP object is saved
//            // and not accidentally converted to a string
//            $request->session()->put('token', serialize($infusionsoft->getToken()));
//
//            // Retrieve the list of contacts again now that we have a new token
//            $contact = $infusionsoft->contacts()->findByEmail($contacts->members[0]->email_address, ['Id']);
//            if ($contact) {
//                echo 'yoyo';
//            } else {
//                $infusionsoft->contacts->add(['FirstName' => $contacts->members[0]->merge_fields->FNAME, 'LastName' => $contacts->members[0]->merge_fields->LNAME, 'Email' => $contacts->members[0]->email_address]);
//            }
//
//
//        }

        //return $contacts;

        //$infusionsoft->refreshAccessToken();


//    }
}


