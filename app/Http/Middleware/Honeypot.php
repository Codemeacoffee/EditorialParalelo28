<?php

namespace Paralelo28\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;

class Honeypot
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
        $HPInput = htmlspecialchars($request['HPInput']);

        if($HPInput != null || strlen($HPInput) > 0) return Redirect::back();
        else return $next($request);
    }
}
