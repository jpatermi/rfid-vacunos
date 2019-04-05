<?php

namespace App\Http\Middleware;

use Closure;

class IsJson
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
        if($request->isJson()) {
            return $next($request);
        }
        return response()->json(['Error' => 'No Autorizado por no ser un Json'], 401);
    }
}
