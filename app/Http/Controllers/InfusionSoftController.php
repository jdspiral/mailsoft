<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use App\Http\Requests;
use Infusionsoft\Infusionsoft;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Token;
use Infusionsoft\InfusionsoftException;

class InfusionSoftController extends Controller
{
    /**
     * Count the unsynced contact between Mailchimp and Infusionsoft
     *
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function countUnSyncedContacts() {

        if (!Auth::user()->token) {
            return view('dashboard.index');
        }

        else {
            $infusionsoft = new Infusionsoft(array(
                'clientId' => getenv('INFUSIONSOFT_CLIENT_ID'),
                'clientSecret' => getenv('INFUSIONSOFT_CLIENT_SECRET'),
                'redirectUri' => getenv('INFUSIONSOFT_REDIRECT_URI'),
            ));

            // Get list of subscribers from requested list
            // * Need to add in list dynamically *
            $client = new Client();

            $response = $client->get('https://us8.api.mailchimp.com/3.0/lists/09e3b22872/members', [
                'auth' => [
                    'somestring', 'cffe5df69579c623d7323d5cfbc5a037'
                ]
            ]);

            // Get decoded list of subscribers to loop through
            $contacts = $response->getBody()->getContents();
            $contacts = json_decode($contacts);

            // Set authorized user to a variable for ease of use
            $user = Auth::user();

            // Set the token if we have it saved in the database
            if ($user->token) {
                Token::retrieve_tokens_in_database($user);
                $infusionsoft->setToken(Token::retrieve_tokens_in_database($user));
            }

            // Get the total of unsynced contacts from Mailchimp
            // This will allow us to determine the number of unsynced
            // Contacts between Mailchimp and Infusionsoft
            $count = $contacts->total_items;
            try {
                foreach ($contacts->members as $contact) {
                    $contactExists = $infusionsoft->contacts()->findByEmail($contact->email_address, ['Id']);
                    // If the contact exists, remove them from the total
                    if ($contactExists) {
                        $count--;
                    }
                }
            } catch (InfusionsoftException $e) {

                // Refresh our access token since we've thrown a token expired exception
                $infusionsoft->refreshAccessToken();
                // We also have to save the new token, since it's now been refreshed.
                // We serialize the token to ensure the entire PHP object is saved
                // and not accidentally converted to a string
                $user->token = serialize($infusionsoft->getToken());

                $user->save();
                // Retrieve the list of contacts again now that we have a new token
                foreach ($contacts as $contact) {
                    $contactExists = $infusionsoft->contacts()->findByEmail($contact->email_address, ['Id']);
                    if ($contactExists) {
                        $count--;
                    }
                }
            }

            // Return the number of unsynced contacts
            return view('dashboard.index')->with('count', $count);
        }
    }

    /**
     * Get the subscribers from Mailchimp
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getSubscribersFromMC() {
        $infusionsoft = new Infusionsoft(array(
            'clientId' => getenv('INFUSIONSOFT_CLIENT_ID'),
            'clientSecret' => getenv('INFUSIONSOFT_CLIENT_SECRET'),
            'redirectUri' => getenv('INFUSIONSOFT_REDIRECT_URI'),
        ));

        $client = new Client();

        $response = $client->get('https://us8.api.mailchimp.com/3.0/lists/09e3b22872/members', [
            'auth' => [
                'somestring', 'cffe5df69579c623d7323d5cfbc5a037'
            ]
        ]);

        $contacts = $response->getBody()->getContents();
        $contacts = json_decode($contacts);

        // Set authorized user to a variable for ease of use
        $user = Auth::user();
        // Set the token if we have it saved in the database
        if ($user->token) {
            Token::retrieve_tokens_in_database($user);
            $infusionsoft->setToken(Token::retrieve_tokens_in_database($user));
        }
        try {
            // For each contact we have, check to see if their email already exist
            // If not, we add them into Infusionsoft
            foreach ($contacts->members as $contact) {
                $contactExists = $infusionsoft->contacts()->findByEmail($contact->email_address, ['Id']);
                if ($contactExists) {

                } else {
                    $infusionsoft->contacts()->add(['FirstName' => $contact->merge_fields->FNAME, 'LastName' => $contact->merge_fields->LNAME, 'Email' => $contact->email_address]);
                }
            }
        } catch (InfusionsoftException $e) {

            // Refresh our access token since we've thrown a token expired exception
            $infusionsoft->refreshAccessToken();

            // We also have to save the new token, since it's now been refreshed.
            // We serialize the token to ensure the entire PHP object is saved
            // and not accidentally converted to a string
            $user->token = serialize($infusionsoft->getToken());

            $user->save();
            // Loop through the contacts again now that we have a new token
            foreach($contacts->members as $contact) {
                $contactExists = $infusionsoft->contacts()->findByEmail($contact->email_address, ['Id']);
                if ($contactExists) {

                } else {
                    $infusionsoft->contacts()->add(['FirstName' => $contact->merge_fields->FNAME, 'LastName' => $contact->merge_fields->LNAME, 'Email' => $contact->email_address]);
                }
            }
        }

    return redirect('/dashboard');
    }
}
