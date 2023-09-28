<?php

namespace App\Http\Controllers\Api\V1\Panel;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Panel\LogResource;
use App\Models\Log;

class LogController extends Controller
{
    public function index() {
        $index = Log::latest('id');
        return LogResource::collection($index->paginate(10));
    }

}
