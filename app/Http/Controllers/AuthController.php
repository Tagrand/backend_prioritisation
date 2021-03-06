<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\BadResponseException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $http = new Client();

        try {
            $response = $http->post(config('services.passport.login_endpoint'), [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => config('services.passport.client_id'),
                    'client_secret' =>config('services.passport.client_token'),
                    'username' => $request->username,
                    'password' => $request->password,
                ],
            ]);

            return $response->getBody();
        } catch (BadResponseException $e) {
            if ($e->getCode() === 400) {

                return response()->json("Invalid Request. Please enter a username or a password.", $e->getCode());
            } else if ($e->getCode() === 401) {

                return response()->json('Your credentials are incorrect.', $e->getCode());
            }

                return response()->json('Something went wrong on the server', $e->getCode());
        }
   }
}
