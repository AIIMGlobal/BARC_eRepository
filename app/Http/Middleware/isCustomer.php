<?php

namespace App\Http\Middleware;

use Closure;

class isCustomer
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
        if( !empty(auth()->user()) && auth()->user()->user_type == 3 ) {
            return $next($request);
        }
        return redirect()->route('customer.login')->with('error',"You don't have customer access.");
    }
}
