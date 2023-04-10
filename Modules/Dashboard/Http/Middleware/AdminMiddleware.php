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
        if (auth()->check() && auth()->user()->hasPermission($request->route()->getName())) {
            return $next($request);
        } else {
            return $this->errorResponse(message: __('auth.forbidden'), code: Response::HTTP_FORBIDDEN);
        }
    }
}
