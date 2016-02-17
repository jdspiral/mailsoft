<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{

    protected $table = 'tokens';

    /**
     * This function will update the tokens in the database if they have expired and need to be refreshed
     *
     * @param $accessToken
     */
    public static function update_tokens_in_database($serialToken, $user)
    {

        $infusionsoft = new Infusionsoft(array(
            'clientId'     => getenv('INFUSIONSOFT_CLIENT_ID'),
            'clientSecret' => getenv('INFUSIONSOFT_CLIENT_SECRET'),
            'redirectUri'  => getenv('INFUSIONSOFT_REDIRECT_URI'),
        ));

        //Set the token from the database
        $tokenObj = unserialize($serialToken);


        //Refresh the tokens from Infusionsoft
        $infusionsoft->setToken($tokenObj);
        $tokenData = $infusionsoft->refreshAccessToken();

        //Set Variable for serialized token to save to db
        $newToken = $infusionsoft->getToken();
        $token = serialize($newToken);

        $user->access_token     = $token;
        $user->token_expiration = $newToken->endOfLife;
        $user->save();

        return $user->access_token;
    }

    /**
     * Retrieve the access token from the database to use in following API calls
     *
     */
    public static function retrieve_tokens_in_database($user = '')
    {

        if(!$user) {
            $user = User::findOrFail(Auth::user()->id);
        }
        else {
            $user = User::findOrFail($user);
        }


        try {

            // $user = User::findOrFail(Auth::user()->id);

            //Set the token from the database
            $token = unserialize($user->access_token);

            if($token) {
                return $token;
            }
        }
        catch(\Infusionsoft\TokenExpiredException $e) {

            //Set the buffer time to 10 minutes and refresh the tokens if within 10 minutes of expiring
            // $bufferTime = time() - 600;

            $oldToken = Infusionsoft::setToken($token);

            $tokenData = Infusionsoft::refreshAccessToken();

            //Set Variable for serialized token to save to db
            $newToken = Infusionsoft::getToken();
            $token = serialize($newToken);

            $user->access_token     = $token;
            $user->token_expiration = $newToken->endOfLife;
            $user->save();

            if($tokenData) {
                return $tokenData;
            }
            else {
                return false;
            }

        }

        return Infusionsoft::getToken();

    }

}