<?php

namespace App\Http\Middleware;

use Closure;

class RestMiddleware
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
        if(isset(request()->key) && request()->key == env('SESS_KEY')) {
            return $next($request);
        }
        return response()->json(['error' => true, 'message' => 'You do not have access to this resource']);
    }
}
