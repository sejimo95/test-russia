<?php

namespace App\Http\Controllers\Api\V1\Webhooks;

use App\Http\Controllers\Controller;

class WebhookController extends Controller
{

    public function index() {
        // leads
        if (request()->leads) {
            $lead = new DealController;
            $lead->index(request()->leads);
        }

        return 'OK';
    }

}
