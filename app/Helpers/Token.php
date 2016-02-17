<?php
namespace App\Helpers;

use Infusionsoft\Infusionsoft;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;



class Token {

    /**
     * This function will update the tokens in the database if they have expired and need to be refreshed
     *
     * @param $accessToken
     * */

    public static function update_tokens_in_database($serialToken, User $user)
    {
        //$infusionsoft = new Infusionsoft();
        //$infusionsoft = new Infusionsoft();
        //$infusionsoft = new Infusionsoft;
        $infusionsoft = new Infusionsoft(array(
            'clientId' => 'at3xrpwexxc5zjt9jamrsdem',//getenv('INFUSIONSOFT_CLIENT_ID'),
            'clientSecret' => 'RH9nrfQAnE',//getenv('INFUSIONSOFT_CLIENT_SECRET'),
            'redirectUri' => 'http://stripe.app/admin/callback',//getenv('INFUSIONSOFT_REDIRECT_URI'),
        ));
        //Set the token from the database
        $tokenObj = unserialize($serialToken);


        //Refresh the tokens from Infusionsoft
        $infusionsoft->setToken($tokenObj);
        $infusionsoft->refreshAccessToken();

        //Set Variable for serialized token to save to db
        $newToken = $infusionsoft->getToken();
        $token = serialize($newToken);

        //$user->access_token = $token;
        //$user->token_expiration = $newToken->endOfLife;
        $user->token = $token;
        $user->save();

        return $user->token;
    }

/**
* Retrieve the access token from the database to use in following API calls
*
*/
public static function retrieve_tokens_in_database(User $user)
    {
        //$infusionsoft = new Infusionsoft;
        $infusionsoft = new Infusionsoft(array(
            'clientId' => 'at3xrpwexxc5zjt9jamrsdem',//getenv('INFUSIONSOFT_CLIENT_ID'),
            'clientSecret' => 'RH9nrfQAnE',//getenv('INFUSIONSOFT_CLIENT_SECRET'),
            'redirectUri' => 'http://stripe.app/admin/callback',//getenv('INFUSIONSOFT_REDIRECT_URI'),
        ));


    try {

        // $user = User::findOrFail(Auth::user()->id);
        $token = $user->token;
        //Set the token from the database
        $token = unserialize($token);
        //$infusionsoft->setToken($token);
        if($token) {
            return $token;
        }
    }
    catch(\Infusionsoft\TokenExpiredException $e) {

        //Set the buffer time to 10 minutes and refresh the tokens if within 10 minutes of expiring
        // $bufferTime = time() - 600;

        $infusionsoft->setToken($user->token);

        $tokenData = $infusionsoft->refreshAccessToken();

        //Set Variable for serialized token to save to db
        $newToken = $infusionsoft->getToken();
        $token = serialize($newToken);

        $user->token = $token;
        //$user->access_token     = $token;
        //$user->token_expiration = $newToken->endOfLife;
        $user->save();

        if($tokenData) {
            return $tokenData;
        }
        else {
            return false;
        }

    }

    return $infusionsoft->getToken();

    }

}