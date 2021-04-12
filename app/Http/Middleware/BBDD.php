<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class BBDD
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
//    public function handle($request, Closure $next)
    public function handle($request, Closure $next)
    {   
        if (Auth::check()) {
            $BBDD = session('db_name');
            Config::set('database.default', $BBDD);
        }

        return $next($request);
    }
}