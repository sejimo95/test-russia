<?php

namespace App\Http\Controllers\Api\V1\Webhooks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WebhookController extends Controller
{

    public function index() {
        Storage::put('amocrm/webhok.json', json_encode(request()->all()));

        $this->checkApi();

        return 'OK';
    }

    public function checkApi() {
        // leads
        if (request()->leads) {
            $lead = new DealController;
            $lead->index(request()->leads);
        }
    }

}
