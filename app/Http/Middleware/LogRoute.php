<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRoute
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
        $response = $next($request);

        $log = [
            'HEADER' => array_change_key_case(getallheaders(), CASE_LOWER),
            'URI' => $request->getUri(),
            'METHOD' => $request->getMethod(),
            'REQUEST_BODY' => $request->all(),
            'RESPONSE' => $response->getContent()
        ];

        Log::info(json_encode($log));

        return $response;
    }
}
