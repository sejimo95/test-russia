<?php

use App\Models\AmocrmRefreshToken;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

function setAmocrmAccessAndRefreshToken($access_token, $refresh_token) {
    //access_token
    $content = ['value' => $access_token, 'time' => date('Y/m/d',time())];
    Storage::put('amocrm/access_token.json', json_encode($content));

    //refresh_token
    $store = new AmocrmRefreshToken;
    $store->token = $refresh_token;
    $store->save();
}

function getAmocrmAccessToken() {
    $file = Storage::get('amocrm/access_token.json');
    $content = json_decode($file);
    if(isset($content->value)) {
        return $content->value;
    }
    return '';
}

function SendRequestToAmocrm($requestType, $path, $parameters = []){
    $url = 'https://sejijon.amocrm.ru/' . $path;
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . getAmocrmAccessToken(),
    ])->$requestType("$url", $parameters);

    // Unauthorized
    if ($response->status() == 401) {
        // logout
        auth()->logout();
        return ['status' => 401];
    }

    return ['result' => $response->object(), 'status' => $response->status(), 'message' => $response->body()];
}


?>
