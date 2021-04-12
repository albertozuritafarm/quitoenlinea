<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Config;

class validateUserRoute
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
        $BBDD = session('db_name');
        Config::set('database.default', $BBDD);
        return $next($request);
//        DB::table('scheduling_temp_details')->where('user_id', '=', \Auth::user()->id)->delete();
    
        //Validate POST
        if ($request->isMethod('post') || $request->isMethod('patch')) {
            return $next($request);
        }else{
            //Obtain URL
            $url = $request->path();
            $validateData =  substr_count($url,"/");
            if($validateData > 1){
                    //ALLOW BECAUSE CANT VALIDATE
                    return $next($request);
            }else{
                $newUrl = $url;
            }
            //Obtain user Menu
            $query = 'select * 
                    from menu men
                    join menu_rol mrol on mrol.menu_id = men.id
                    where men.url = "'.$newUrl.'" and mrol.rol_id IN ("'.\Auth::user()->role_id.'","0")';
    
            $result = DB::select($query);
            if ($result) {
                return $next($request);
            } else {
                return redirect()->route('home')->with('ValidateUserRoute', 'Usted no posee permisos para acceder al modulo');
            }
        }
    }
}
