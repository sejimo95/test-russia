<?php

namespace App\Http\Controllers\Api\V1\Panel;

use App\Http\Controllers\Controller;
use App\Models\Deal;

class DealController extends Controller
{
    public function index() {
        $index = Deal::oldest('created_at');

        $search = request()->search;
        if($search) {
            $index->where('name', 'like', "%$search%");
        }

        return response()->json(['items' => $index->paginate(10)],200);
    }

}
