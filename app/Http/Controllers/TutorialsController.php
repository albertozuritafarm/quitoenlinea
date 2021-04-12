<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TutorialsController extends Controller{

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('validateUserRoute');
    }
    
    public function index(){
        return view('tutorials.index');
        
    }
    
}
