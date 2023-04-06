<?php

namespace Modules\Auth\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Auth\Transformers\LoginResource;

class LoginController extends AuthController
{
    use HandleAuth;
    public function login(LoginRequest $request)
    {
        $user = User::query()->firstWhere($request->login_key, $request->username);
        $this->checkIfUserBanned($user);
        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->errorResponse(message: __('auth.failed'), code: 401);
        }
//        TODO:: Activate this in production
        // $user->tokens()->delete();
        $user->_token = $user->createToken('RasidERP')->plainTextToken;
        return $this->successResponse(data: LoginResource::make($user), message: __('auth.success_login'));
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        Auth::guard('sanctum')->forgetUser();
        return $this->successResponse(message: __('auth.logout'));
    }
}
