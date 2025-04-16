<?php

namespace App\Http\Middleware;

use Closure;

class isAdmin
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
        // dd(auth()->user());
        if( !empty(auth()->user()) && ( auth()->user()->user_type == 1 || auth()->user()->user_type == 2) ) {
            return $next($request);
        }
        return redirect('home')->with('error',"You don't have admin access.");
    }
}
