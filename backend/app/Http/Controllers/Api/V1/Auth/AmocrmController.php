<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Tymon\JWTAuth\Facades\JWTAuth;

class AmocrmController extends Controller
{

    private function clientSecret() {
        return ($_SERVER['SERVER_NAME'] == '127.0.0.1') ? 'lKNfq2sZwCzTSGyyTTmDgyd3jmGoW8v6CDlHy7vH6piTMVPy3UWjUke57fvH1HmD' : 'gnFL4IxTw1mLFG7TNmyHRVNHwAyzKzUVhqzjUAZBeYlDyj9Y6PdAE3YHXHH5oXAe';
    }

    public function callbackAmocrm() {

        // if faild
        $error = request()->error;
        if (isset($error)) {
            return response()->json(['message' => 'access denied'],401);
        }

        $referer = request()->referer;
        $client_id = request()->client_id;
        $code = request()->code;

        $response = Http::post("https://$referer/oauth2/access_token", [
            'client_id' => $client_id,
            'client_secret' => $this->clientSecret(),
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => url('api/v1/auth/callback/amocrm'),
        ]);

        // if faild
        if ($response->status() != 200) {
            return response()->json(['Incorrect data was transmitted. Call support.'],401);
        }

        $data = $response->object();
        setAmocrmAccessAndRefreshToken($data->access_token, $data->refresh_token);

        // authJwt
        $token = $this->authJwt();
        return view('callbackAmocrm', compact('token'));
    }

    // authJwt
    public function authJwt() {
        $user = User::find(1);
        return JWTAuth::fromUser($user);
    }

}
