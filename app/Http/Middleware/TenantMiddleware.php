<?php

namespace App\Http\Middleware;
use HipsterJazzbo\Landlord\Facades\Landlord;
use Tymon\JWTAuth\Middleware\BaseMiddleware;
use Closure;
use JWTAuth;

class TenantMiddleware extends BaseMiddleware
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
        $user = JWTAuth::parseToken()->authenticate();
        Landlord::addTenant('company_id', $user->company_id);
        return $next($request);
    }
}
