<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class CantonController extends Controller
{
    public function getCantones($id) {
        $cantones = DB::select('select * from canton where province_id = ?', [$id]);
        return json_encode($cantones);
    }
}