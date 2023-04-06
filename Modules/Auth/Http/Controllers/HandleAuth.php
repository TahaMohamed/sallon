<?php

namespace Modules\Auth\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

trait HandleAuth
{
    use ApiResponse;
    protected function getCredentials($request): array
    {
        return [
            $request->login_key => $request->username,
            'password' => $request->password
        ];
    }

    protected function checkIfUserBanned($user)
    {
        throw_if($user?->isBanned(), new HttpResponseException(
            $this->errorResponse(message: __('auth.banned',['reason' => $user->reason]),code: Response::HTTP_FORBIDDEN)
        ));
    }
}
