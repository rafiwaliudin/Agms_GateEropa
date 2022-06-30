<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if (strtolower(Auth::user()->roles->pluck('name')->first()) == 'administrator') {
                return redirect('/');
            } elseif (strtolower(Auth::user()->roles->pluck('name')->first()) == 'user') {
                return redirect('/app/index');
            }
        }

        return $next($request);
    }
}
