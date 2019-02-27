<?php

namespace App\Http\Middleware;

use Tymon\JWTAuth\Providers\Auth\Illuminate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Closure;


class AuthUser
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
        if(!( (new Illuminate(\Auth::Guard('company')))->user() || (new Illuminate(\Auth::Guard('individual')))->user() )){
            return response()->json([
                'error' => "unauthorize",
            ], 401);
        } 

        return $next($request);
    }
}
