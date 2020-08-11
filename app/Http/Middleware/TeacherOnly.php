<?php

namespace App\Http\Middleware;

use Closure;

class TeacherOnly
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
        // Check is the requester is administrator
        if($request->user('api')->role == '1') {
            return $next($request);
        }
        return \App\Actions\SendResponse::forbidden();
    }
}
