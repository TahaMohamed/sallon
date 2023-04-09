<?php

namespace Modules\Vendor\Http\Middleware;

use App\Models\User;
use App\Traits\ApiResponse;
use Symfony\Component\HttpFoundation\Response;

class VendorMiddleware
{
    use ApiResponse;
    public function handle($request, \Closure $next)
    {
        if (auth()->check() && auth()->user()->user_type == User::VENDOR) {
            return $next($request);
        }
        return $this->errorResponse(message: __('auth.failed'),code: Response::HTTP_UNAUTHORIZED);
    }
}
