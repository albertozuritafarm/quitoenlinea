<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Validator;
use DB;
use Barryvdh\DomPDF\Facade as PDF;
use Gallib\ShortUrl\Facades\ShortUrl;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class PnBeneficiaryVinculationController extends Controller
{
    public function pdf(){
                set_time_limit(300);
                $customer = \App\customers::find(113);
                $sales = \App\sales::find(1389);
                $vinculation = \App\vinculation_form::find(355);
                $birthCountry = \App\country::find($vinculation->nationality_id);
                $birthCity = \App\city::find($vinculation->birth_place);
                $city = \App\city::find($vinculation->city_id);
                $province = \App\province::find($city->province_id);
                $country = \App\country::find($province->country_id);
                $broker = \App\sales::selectRaw('agent_ss.agentedes, agent_ss.agentedes as "canalnegodes", pbc.ejecutivo_ss as "ejecutivo", IF(channels.canalnegoid = 1, "DIRECTO", "BROKER") as "canal", CONCAT(usr.first_name," ",usr.last_name) as "ejecutivo_ss", agen.puntodeventades, pro.ramodes, sales.insured_value')
                                        ->join('agencies as agen','agen.id','=','sales.agen_id')
                                        ->join('channels','channels.id','=','agen.channel_id')
                                        ->join('products_channel as pbc','pbc.id','=','sales.pbc_id')
                                        ->join('products as pro','pro.id','=','pbc.product_id')
                                        ->join('agent_ss','agent_ss.id','=','pbc.agent_ss')
                                        ->join('users as usr','usr.id','=','sales.user_id')
                                        ->where('sales.id','=',$sales->id)
                                        ->get();
                
                $ecoActivity=\App\economic_activity::find($vinculation->economic_activity_id);
                $occupation=\App\economic_ocupation::find($vinculation->occupation);
                
        //        if($vinculation->viamatica_date == null){
                    $viamaticaDate = date('d-m-Y');
                $pdf = PDF::loadView('pnBeneficiaryVinculation.pdf',[
                            'customer' => $customer,
                            'sales' => $sales,
                            'vinculation' => $vinculation,
                            'ecoActivity' => $ecoActivity,
                            'occupation' => $occupation,
                            'birthCountry' => $birthCountry,
                            'birthCity' => $birthCity,
                            'city' => $city,
                            'province' => $province,
                            'country' => $country,
                            'broker' => $broker[0],
                            'viamaticaDate' => $viamaticaDate
                ]);
                return $pdf->stream('vinculacion.pdf');
                // $output = $pdf->output();
                // file_put_contents(public_path('vinculacion.pdf'), $output);
                
                // $b64Doc = chunk_split(base64_encode(file_get_contents(public_path('vinculacion.pdf'))));
        //        return $b64Doc;
        
                // $result = viamaticaSendPdf($customer->document, $customer->first_name, $customer->mobile_phone, $sales->id, $b64Doc, $customer->email, $customer->phone, $vinculation->id);
                
                // $vinculationUpdate = \App\vinculation_form::find(213);
                // $vinculationUpdate->viamatica_id = $result['data'][0];
                // $vinculationUpdate->save();
                
                return $result;
            }
}
