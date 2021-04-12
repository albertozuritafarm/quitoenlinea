<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use DB;

class productsController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function getRamos(){
        $ramosQuery = 'SELECT DISTINCT(ramoid), ramodes FROM products';        
        $ramos = DB::select($ramosQuery);
        return json_encode($ramos);
    }

    public function getProducts($id){
       $products = \App\products::selectRaw('DISTINCT(products.productoid), products.id, products.productodes as "name"')
                                           ->join('products_channel as pbc','pbc.product_id','=','products.id')
                                           ->where('products.ramoid', '=', $id)
                                           ->where('pbc.status_id','=','1')
                                           ->groupBy('products.productoid')
                                           ->get();
       return json_encode($products);
    }

}