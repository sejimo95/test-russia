<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
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
            return ['success' => true, 'user' => auth()->user()];
        }
        return ['success' => false, 'user' => null,'isLogout' => false];
    }

    // logout
    public function logout()
    {
        auth()->logout();
        return response()->json(['success' => true]);
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
            return ['success' => false,'message' => $validator->errors()->first()];
        }

        // check pass & email
        if (!$token = auth()->attempt(['email' => $data['email'], 'password' => $data['password']])) {
            return ['success' => false,'message' => 'Unauthorized'];
        }

        return [
            'success' => true,
            'user' => auth()->user(),
            'token_type' => 'bearer',
            'token' => $token
        ];
    }

}
