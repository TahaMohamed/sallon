<?php
namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class UseTransaction
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!in_array($request->method(), ['POST','PUT','PATCH','DELETE'])) {
            return $next($request);
        }

        DB::beginTransaction();
        $response = $next($request);

        if ($response instanceof Response && $response->getStatusCode() >= 500) {
            DB::rollBack();
            return $response;
        }

        DB::commit();

        return $response;
    }
}
