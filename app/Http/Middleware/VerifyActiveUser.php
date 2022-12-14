<?php

namespace App\Http\Middleware;

use Closure;

class VerifyActiveUser
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
        if (auth()->user()->status !== 'active') {
            auth()->logout();
            return redirect('/');
        }
        return $next($request);
    }
}
