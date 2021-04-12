<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ProvinceController extends Controller
{
    public function getProvince($id) {
//        return $id;
          $provinces = DB::select('select * from provinces where country_id = ?', [$id]);

//        $provinces = \App\province::all();
        
        return json_encode($provinces);

    }
}
