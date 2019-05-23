<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Modules\Billing\Entities\Customer;
use Modules\User\Entities\Company;
use Modules\User\Entities\User;
use JWTAuth;

class PlanMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->isMethod('post')) {
            $user = JWTAuth::parseToken()->authenticate();
            $customerCount = Customer::where('company_id', $user->company_id)->count();
            $userCount = User::where('company_id', $user->company_id)->count();

            $company = Company::find($user->company_id);

            if (str_contains($request->url(), 'customer') && !($customerCount <= $company->pricing_plan->customer_limit))
                return response()->json(['message' => sprintf('Customer limit exceeded')], 403);

            if (str_contains($request->url(), 'user') && !($userCount <= $company->pricing_plan->user_limit))
                return response()->json(['message' => sprintf('User limit exceeded')], 403);
        }
        return $next($request);
    }
}
