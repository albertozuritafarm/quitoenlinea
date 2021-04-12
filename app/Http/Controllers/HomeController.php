<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(request $request)
    {
        if(\Auth::user()->role_id == 1 || \Auth::user()->role_id == 2 || \Auth::user()->role_id == 3 || \Auth::user()->role_id == 4 || \Auth::user()->role_id == 6){
            return redirect('sales/product/select');
        }else{
            return redirect('sales/product/select');
        }
    }
}
