<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class RubrosController extends Controller
{
    public function getValueRubro($cod) {
        $value = 0;
        $res = DB::table('products_rubros')->where('cod', $cod)->value('value');
        if ($res > 0) {
            $value = $res; 
        }
        return $value;
    }
}