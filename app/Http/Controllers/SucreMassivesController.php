<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SucreMassivesController extends Controller
{
    public function validateExcel(){
        \App\SucreMassives::storeExcel();
    }
    public function validateExcelBiess(){
        \App\SucreMassives::storeExcelBiess();
//        \App\Jobs\SucreMassivesBiessJobs::dispatchNow();
    }
    public function validateExcelPacifico(){
        \App\SucreMassives::storeExcelPacifico();
    }
    
    public function updateAgency(request $request){
//        return $request;
        $returnData = '<option value="">--Escoja Una--</option>';
        $massives = \App\SucreMassives::selectRaw('agency_sponsor')
                                        ->where('channel_sponsor','=',$request['data']['name'])
                                        ->groupBy('agency_sponsor')
                                        ->get();
        
        foreach($massives as $m){
            $returnData .= '<option value="'.$m->agency_sponsor.'">'.$m->agency_sponsor.'</option>';
        }
        return $returnData;
    }
    
    public function updateCity(request $request){
//        return $request;
        $returnData = '<option value="">--Escoja Una--</option>';
        $massives = \App\SucreMassives::selectRaw('city_customer')
                                        ->where('province_customer','=',$request['data']['name'])
                                        ->groupBy('city_customer')
                                        ->get();
        
        foreach($massives as $m){
            $returnData .= '<option value="'.$m->city_customer.'">'.$m->city_customer.'</option>';
        }
        return $returnData;
    }
}
