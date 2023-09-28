<?php

namespace App\Http\Controllers\Api\V1\Panel;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Panel\ContactResource;
use App\Models\Deal;

class DealController extends Controller
{
    public function index() {
        $index = Deal::withCount('contacts')->oldest('created_at');

        $search = request()->search;
        if($search) {
            $index->where('name', 'like', "%$search%");
        }

        return ContactResource::collection($index->paginate(10));
    }

}
