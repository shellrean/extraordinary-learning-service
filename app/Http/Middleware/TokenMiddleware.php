<?php

namespace App\Http\Middleware;

use Closure;
use App\Actions\SendResponse;

class TokenMiddleware
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
        if(isset($request->token)) {
            $token = \App\Token::where('token', $request->token)->first();
            if($token) {
                return $next($request);
            }
        }
        return SendResponse::forbidden('You do not have access to this resource');
    }
}
