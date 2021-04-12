<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;
use Excel;

class insertExcel extends Controller
{
    public function insert(){
        set_time_limit(0);

        ini_set('max_execution_time', 60000);

        ini_set('memory_limit', '-1');
        echo now();

        echo 'empieza';

        echo '<br>';         

        $collection = (new FastExcel)->configureCsv(';', '#', ''. PHP_EOL, 'gbk')->import(public_path('SolicituddeReporteMAGNUS.xlsx'));

            foreach($collection as $c){
                
                $agencySSExists=\App\agencia_ss::where('agenciaid','=',$c['AGENCIAID'])->latest()->first();
                if($agencySSExists){
                //    Si existe
                  $agencySSId=$agencySSExists->id;
                }else{
                    $agencySS = new \App\agencia_ss();
                    $agencySS->agenciaid=$c['AGENCIAID'];
                    $agencySS->agenciades=$c['AGENCIADES'];
                    $agencySS->save();
                    $agencySSId=$agencySS->id;
                }

                $agentExists=\App\agent_ss::where('agenteid','=',$c['AGENTEID'])->latest()->first();
                if($agentExists){
                   //    Si existe
                   $agentId=$agentExists->id;
                }else{
                    $agent=new \App\agent_ss();
                    $agent->agenteid=$c['AGENTEID'];
                    $agent->agentedes=$c['AGENTEDES'];
                    $agent->save();
                    $agentId=$agent->id;
                }
                
                $channelExists=\App\channels::where('canalnegoid','=',$c['CANALNEGOCIOID'])->latest()->first();
                if($channelExists){
                // Si existe
                $channelId =$channelExists->id;
                }else{
                    $channel=new \App\channels();
                    $channel->canalnegoid=$c['CANALNEGOCIOID'];
                    $channel->canalnegodes=$c['CANALNEGOCIODES'];
                    $channel->save();
                    $channelId = $channel->id;
                }

                $agencyExists=\App\Agency::where('puntodeventaid','=',$c['PUNTOVENTAID'])->where('channel_id','=',$channelId)->latest()->first();
                if($agencyExists){
                // Si exite
                   $agencyId=$agencyExists->id;
                }else{
                   $agency=new \App\Agency();
                   $agency->puntodeventaid=$c['PUNTOVENTAID'];
                   $agency->puntodeventades=$c['PUNTOVENTADESC'];
                   $agency->city_id=1;
                   $agency->channel_id=$channelId;
                   $agency->save();
                   $agencyId=$agency->id;
                }
               
                $productExists=\App\products::where('productoid','=',$c['CANALPRODUCTOID'])->where('ramoid','=',$c['RAMOID'])->where('agency_id','=',$agencyId)->where('canalplanid','=',$c['CANALPLANID'])->latest()->first();
                if($productExists){
                // Si exite
                    // $productUpdate=\App\products::find($productExists->id);
                    // $productUpdate->segment=$c['RAMODES'];
                    // $productUpdate->save();
                    $productId=$productExists->id;
                    $canalPlanId=$productExists->canalplanid;
                }else{
                   $product=new \App\products();
                   $product->productoid=$c['CANALPRODUCTOID'];
                   $product->productodes=$c['PLANCABDES'];
                   $product->ramoid=$c['RAMOID'];
                   $product->ramodes=$c['RAMODES'];
                   $product->agency_id=$agencyId;
                   $product->canalplanid=$c['CANALPLANID'];
                   $product->segment=$c['RAMODES'];
                   $product->save();
                   $productId=$product->id;
                   $canalPlanId=$product->canalplanid;
                }

                $productChannelExists=\App\product_channel::where('product_id','=',$productId)->where('channel_id','=',$channelId)->where('agency_id','=',$agencyId)->where('agency_ss_id','=',$agencySSId)->where('agent_ss','=',$agentId)->where('canal_plan_id','=',$canalPlanId)->latest()->first();
                if($productChannelExists){
                     // Si exite
                    $productChannelUpdate=\App\product_channel::find($productChannelExists->id);
                    $productChannelUpdate->ejecutivo_ss=$c['Ejecutivo Comercial Seguros Sucre'];
                    $productChannelUpdate->ejecutivo_ss_email=$c['CORREO'];
                    $productChannelUpdate->save();
                }else{
                    $productChannel=new \App\product_channel();
                    $productChannel->channel_id=$channelId;
                    $productChannel->product_id=$productId;
                    $productChannel->status_id=1;
                    $productChannel->agency_id=$agencyId;
                    $productChannel->agency_ss_id=$agencySSId;
                    $productChannel->agent_ss=$agentId;
                    $productChannel->canal_plan_id=$canalPlanId;
                    $productChannel->ejecutivo_ss=$c['Ejecutivo Comercial Seguros Sucre'];
                    $productChannel->ejecutivo_ss_email=$c['CORREO'];
                    $productChannel->save();
                }

        echo now();

        echo 'termina de hacer los insert';

        echo '<br>';

        }
    }
}
