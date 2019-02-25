<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Tymon\JWTAuth\Providers\Auth\Illuminate;

class AuthIndividual
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
        if(!((new Illuminate(\Auth::Guard('individual'))))){
            return response()->json([
                'error' => "unauthorize",
            ], 401);
        } 

        return $next($request);
    }
}
