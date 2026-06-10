<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthMobile
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('mobile_token')) {
            return redirect()->route('mobile.login');
        }

        return $next($request);
    }
}
