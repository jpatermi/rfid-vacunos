<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

//use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate //extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->header('Authorization')) {
            if (User::where('api_token', $request->header('Authorization'))->first()){
                return $next($request);
            } else {
                return response()->json(['error' => 'No autorizado. Por favor autentícate primero (token).'], 401);
            }
        } else {
            return response()->json(['error' => 'No autorizado. Por favor autentícate primero (header).'], 401);
        }
    }
}
