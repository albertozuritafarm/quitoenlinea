<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class CityController extends Controller {

    public function getCity($id) {
//        return $id;
        $cities = DB::select('select * from cities where province_id = ?', [$id]);

//        $provinces = \App\province::all();

        return json_encode($cities);
    }
    
    public function getCityByCountry($id) {
//        return $id;
        $cities = DB::select('select cit.* from cities cit join provinces prov on prov.id = cit.province_id  where prov.country_id = ? order by cit.name', [$id] );

//        $provinces = \App\province::all();

        return json_encode($cities);
    }

    public function getByCountry(request $request) {
        $cities = \App\city::selectRaw('cities.name, cities.id')
                            ->join('provinces','provinces.id','=','cities.province_id')
                            ->join('countries','countries.id','=','provinces.country_id')
                            ->where('countries.id','=',$request['data']['id'])
                            ->orderBy('cities.name')
                            ->get();
        $returnData = '<option>--Escoja Una--</option>';
        foreach($cities as $cit){
            $returnData .= '<option value="'.$cit->id.'">'.$cit->name.'</option>';
        }
        return $returnData;
    }

}
