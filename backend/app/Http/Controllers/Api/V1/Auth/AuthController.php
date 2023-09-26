<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use http\Env\Response;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'isLogin']]);
    }

    // is-login
    public function isLogin() {
        if (auth()->check()) {
            return response()->json(['user' => auth()->user()], 200);
        }
        return response()->json(['message' => 'unauthorized!', 'user' => null], 422);
    }

    // logout
    public function logout()
    {
        auth()->logout();
        return response()->json(['success' => true], 200);
    }

    // login
    public function login()
    {
        // validate
        $data = json_decode(request()->data, true);
        $validator = Validator::make($data, [
            'email' => 'required|string|email|max:191|min:6',
            'password' => 'required|string|min:6|max:191',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        // check pass & email
        if (!$token = auth()->attempt(['email' => $data['email'], 'password' => $data['password']])) {
            return response()->json(['message' => 'The password or email is incorrect'], 422);
        }

        return response()->json(['user' => auth()->user(), 'token_type' => 'bearer', 'token' => $token], 200);
    }

}
