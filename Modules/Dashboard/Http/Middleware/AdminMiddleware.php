<?php

namespace Modules\Dashboard\Http\Middleware;

use App\Models\User;
use App\Traits\ApiResponse;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    use ApiResponse;
    public function handle($request, \Closure $next)
    {
        if (auth()->check() && auth()->user()->user_type == User::SUPERADMIN) {
            return $next($request);
        } elseif (auth()->check() && auth()->user()->user_type == User::ADMIN) {
            if (auth()->user()->hasPermissions($request->route()->getName())) {
                return $next($request);
            } else {
                return $this->errorResponse(message: __('auth.forbidden'), code: Response::HTTP_FORBIDDEN);
            }
        }
        return $this->errorResponse(message: __('auth.failed'),code: Response::HTTP_UNAUTHORIZED);
    }
}
