<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Infusionsoft\Infusionsoft;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Token;


class InfusionSoftController extends Controller
{

    public function getSubscribersFromMC() {
        $infusionsoft = new Infusionsoft(array(
            'clientId' => 'at3xrpwexxc5zjt9jamrsdem',//getenv('INFUSIONSOFT_CLIENT_ID'),
            'clientSecret' => 'RH9nrfQAnE',//getenv('INFUSIONSOFT_CLIENT_SECRET'),
            'redirectUri' => 'http://stripe.app/admin/callback',//getenv('INFUSIONSOFT_REDIRECT_URI'),
        ));

        $client = new Client();
        $response = $client->get('https://us8.api.mailchimp.com/3.0/lists/09e3b22872/members', [
            'auth' => [
                'somestring', 'cffe5df69579c623d7323d5cfbc5a037'
            ]
        ]);

        $contacts = $response->getBody()->getContents();
        $contacts = json_decode($contacts);

        //echo '<pre>';
        $user = Auth::user();
        //$token = $user->token;
        //print_r($token);
        // Set the token if we have it in storage (in this case, a session)
        if ($user->token) {
            $token = Token::retrieve_tokens_in_database($user);
            $infusionsoft->setToken(Token::retrieve_tokens_in_database($user));
           // print_r($token);
            if($token->endOfLife < (time() - 3600)) {
                Token::update_tokens_in_database($user->token, $user);
               // print_r($token);
            }
//            try {
//                Token::update_tokens_in_database($user->token, $user);
//            }
//            catch (InfusionsoftTokenExpiredException $e) {
//                Token::update_tokens_in_database($user->token, $user);
//            }
            }

            //$infusionsoft->setToken(unserialize(Auth::user()->token));


        try {
            echo '<pre>';
            foreach ($contacts as $contact) {
                foreach ($contact as $data) {
                    $contactExists = $infusionsoft->contacts()->findByEmail($data->email_address, ['Id']);
                    if ($contactExists) {
                    } else {
                        $infusionsoft->contacts()->add(['FirstName' => $data->merge_fields->FNAME, 'LastName' => $data->merge_fields->LNAME, 'Email' => $data->email_address]);
                    }
                }
           }
        } catch (InfusionsoftTokenExpiredException $e) {

            // Refresh our access token since we've thrown a token expired exception
            $infusionsoft->refreshAccessToken();

            // We also have to save the new token, since it's now been refreshed.
            // We serialize the token to ensure the entire PHP object is saved
            // and not accidentally converted to a string
            Auth::user()->token = serialize($infusionsoft->getToken());

            Auth::user()->save();
            // Retrieve the list of contacts again now that we have a new token
            foreach($contacts as $contact) {
                foreach ($contact as $data) {
                    print_r($data);
                    $contactExists = $infusionsoft->contacts()->findByEmail($data->email_address, ['Id']);
                    if ($contactExists) {

                    } else {
                        $infusionsoft->contacts()->add(['FirstName' => $data->merge_fields->FNAME, 'LastName' => $data->merge_fields->LNAME, 'Email' => $data->email_address]);
                    }
                }
            }

        }

    return redirect('/dashboard');

    }

    public function countUnSyncedContacts() {
        $infusionsoft = new Infusionsoft(array(
            'clientId' => 'at3xrpwexxc5zjt9jamrsdem',//getenv('INFUSIONSOFT_CLIENT_ID'),
            'clientSecret' => 'RH9nrfQAnE',//getenv('INFUSIONSOFT_CLIENT_SECRET'),
            'redirectUri' => 'http://stripe.app/admin/callback',//getenv('INFUSIONSOFT_REDIRECT_URI'),
        ));

        $client = new Client();
        $response = $client->get('https://us8.api.mailchimp.com/3.0/lists/09e3b22872/members', [
            'auth' => [
                'somestring', 'cffe5df69579c623d7323d5cfbc5a037'
            ]
        ]);

        $contacts = $response->getBody()->getContents();
        $contacts = json_decode($contacts);

        $user = Auth::user();
        // Set the token if we have it in storage (in this case, a session)
        if ($user->token) {
            $token = Token::retrieve_tokens_in_database($user);
            $infusionsoft->setToken(Token::retrieve_tokens_in_database($user));
            if($token->endOfLife < (time() - 3600)) {
                Token::update_tokens_in_database($user->token, $user);
            }
        }
        $count = 1;
        try {
            foreach ($contacts as $contact) {
                foreach ($contact as $data) {
                    $contactExists = $infusionsoft->contacts()->findByEmail($data->email_address, ['Id']);
                    if ($contactExists) {
                        $count--;
                    } else {
                        $count++;
                    }
                }
            }
        } catch (InfusionsoftTokenExpiredException $e) {

            // Refresh our access token since we've thrown a token expired exception
            $infusionsoft->refreshAccessToken();
            // We also have to save the new token, since it's now been refreshed.
            // We serialize the token to ensure the entire PHP object is saved
            // and not accidentally converted to a string
            Auth::user()->token = serialize($infusionsoft->getToken());

            Auth::user()->save();
            // Retrieve the list of contacts again now that we have a new token
            foreach($contacts as $contact) {
                foreach ($contact as $data) {
                    $contactExists = $infusionsoft->contacts()->findByEmail($data->email_address, ['Id']);
                    if ($contactExists) {
                        $count--;
                    } else {
                        $count++;
                    }
                }
            }
        }

        return view('dashboard.index')->with('count', $count);
    }
}
