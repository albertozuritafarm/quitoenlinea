<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
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
            $BBDD = session('db_name');
            Config::set('database.default', $BBDD);
            Auth::attempt(array('email'=>session('email'), 'password'=>session('password') ), true);
            return route('salesIndex');
        }
    }
}
