<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
Use Redirect;
use Barryvdh\DomPDF\Facade as PDF;
use Mail;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use DataTables;
use Illuminate\Pagination\Paginator;
use Validator;
use Illuminate\Contracts\Encryption\DecryptException;
use Underscore\Parse;
use ArieTimmerman\Laravel\URLShortener\URLShortener;
use App\Mail\PaymentSendLinkUserEmail;

class InsuranceApplicationController extends Controller
{
    public function __construct() {
    }
    
    public function R2insuranceApplication($id) {
        //Decrypt Sale
        try{
            $saleId = Crypt::decrypt($id);
        } catch (DecryptException  $ex) {
            return 'Por favor solicite un nuevo link';
        }
        $sale = \App\sales::find($saleId);
        $customer = \App\customers::find($sale->customer_id);
        
        $cityUser = \App\city::find($customer->city_id);
        $provinceUser = \App\province::find($cityUser->province_id);
        $countryUser = \App\country::find($provinceUser->country_id);
        $documentUser = \App\document::find($customer->document_id);
        
        $agentSS = \App\agent_ss::selectRaw('agent_ss.agentedes')
                                    ->join('products_channel as pbc','pbc.agent_ss','=','agent_ss.id')
                                    ->join('sales as sal','sal.pbc_id','=','pbc.id')
                                    ->where('sal.id','=',$saleId)
                                    ->get();

        $insuranceApplication = \App\insurance_application::where('sales_id','=',$saleId)->get();
        if($insuranceApplication->isEmpty()){
            $disabled = '';
            $weight = '';
            $stature = '';
        }else{
            if($insuranceApplication[0]->status_id == 6){
                $disabled = '';
            }else{
                $disabled = 'disabled="disabled"';
            }
            $weight = $insuranceApplication[0]->weight;
            $stature = $insuranceApplication[0]->stature;
        }
        
        //Beneficiarys  
        $beneficiarys = \App\beneficiary::where('sales_id','=',$saleId)->get();
        if($beneficiarys->isEmpty()){
            $beneTable = '';
        }else {
            $beneTable = '';
            foreach ($beneficiarys as $bene) {
                $relationsip = \App\benefitiary_relationship::find($bene->relationship_id);
                $beneTable .= '<tr>
                                    <td>' . $bene->first_name . '</td>
                                    <td>' . $bene->second_name . '</td>
                                    <td>' . $bene->last_name . '</td>
                                    <td>' . $bene->second_last_name . '</td>
                                    <td>' . $bene->porcentage . '</td>
                                    <td>' . $relationsip->name . '</td>';
                if(!$insuranceApplication->isEmpty()){
                    if ($insuranceApplication[0]->status_id == 6) {
                        $beneTable .= '<td><button type="submit" class="btn btn-link" onClick="Javacsript:editRow(\'' . $bene->first_name . '\',\'' . $bene->second_name . '\',\'' . $bene->last_name . '\',\'' . $bene->second_last_name . '\',\'' . $bene->porcentage . '\',\'' . $relationsip->name . '\',this)"><span class="glyphicon glyphicon-pencil" style="color:green;font-size:18px"></span></button><button type="submit" class="btn btn-link" onClick="Javacsript:deleteRow(this)"><span class="glyphicon glyphicon-remove" style="color:red;font-size:18px"></span></button></td>';
                    } else {
                        $beneTable .= '<td></td>';
                    }
                }else{
                    $beneTable .= '<td></td>';
                }
                $beneTable .= '</tr>';
            }
        }
        
        //Insurance Questions
        $insuranceApp = \App\insurance_application_answers::where('sales_id','=',$saleId)->get();
        if($insuranceApp->isEmpty()){
            $app = null;
        }else{
            $app = $insuranceApp[0];
        }
        return view('sales.R2.insuranceApplication',[
            'customer' => $customer,
            'sales' => $sale,
            'cityUser' => $cityUser,
            'provinceUser' => $provinceUser,
            'countryUser' => $countryUser,
            'documentUser' => $documentUser,
            'agentSS' => $agentSS,
            'disabled' => $disabled,
            'weight' => $weight,
            'stature' => $stature,
            'beneTable' => $beneTable,
            'insuranceApp' => $app
        ]);
    }
    
    public function R3declarationBeneficiareies($id) {
        //Decrypt Sale
//        $saleId = encrypt(1270);
//        return $saleId;
        try{
            $saleId = Crypt::decrypt($id);
        } catch (DecryptException  $ex) {
            return 'Por favor solicite un nuevo link';
        } 
        
        $sale = \App\sales::find($saleId);
          
        //Beneficiarys  
        $beneficiarys = \App\beneficiary::where('sales_id','=',$saleId)->get();
        if($beneficiarys->isEmpty()){
            $disabled = '';
            $beneTable = '';
        }else {
            $beneTable = '';
            $disabled = 'disabled="disabled"';
            foreach ($beneficiarys as $bene) {
                $relationsip = \App\benefitiary_relationship::find($bene->relationship_id);
                $beneTable .= '<tr>
                                    <td>' . $bene->first_name . '</td>
                                    <td>' . $bene->second_name . '</td>
                                    <td>' . $bene->last_name . '</td>
                                    <td>' . $bene->second_last_name . '</td>
                                    <td>' . $bene->porcentage . '</td>
                                    <td>' . $relationsip->name . '</td>';

                    $beneTable .= '<td></td>';
                
                $beneTable .= '</tr>';
            }
        }
        return view('sales.R3.declarationBeneficiaries',[
            'sale' => $sale,
            'disabled' => $disabled,
            'beneTable' => $beneTable
        ]);
    }  

    public function firstStepStore(request $request){
        $appSearch = \App\insurance_application::where('sales_id','=',$request['salId'])->get();
        if($appSearch->isEmpty()){
            $app = new \App\insurance_application();
            $app->sales_id = $request['salId'];
            $app->weight = $request['weight'];
            $app->stature = $request['stature'];
            $app->status_id = 6;
            $app->save();
        }else{
           if($appSearch[0]->status_id == 6){
               $app = \App\insurance_application::find($appSearch[0]->id);
                $app->weight = $request['weight'];
                $app->stature = $request['stature'];
                $app->status_id = 6;
                $app->save();
           } 
        }
    }
    
    public function r3FirstStepStore(request $request){
        $beneficiary = \App\beneficiary::where('sales_id','=',$request['salId'])->delete();
        foreach($request['tableData'] as $dat){

            $relationship = \App\benefitiary_relationship::where('name','like','%'.$dat['relationship'].'%')->get();

            $ben = new \App\beneficiary();
            $ben->sales_id = $request['salId'];
            $ben->porcentage = $dat['porcentaje'];
            $ben->first_name = $dat['firstName'];
            $ben->second_name = $dat['secondName'];
            $ben->last_name = $dat['lastName'];
            $ben->second_last_name = $dat['secondLastName'];
            $ben->relationship_id = $relationship[0]->id;
            $ben->save();
        }
        $inspectionSearch = \App\inspection::where('sales_id','=',$request['salId'])->get();
        $inspection = \App\inspection::find($inspectionSearch[0]->id);
        $inspection->status_id = 18;
        $inspection->save();
        
        $sale = \App\sales::find($request['salId']);
        $sale->status_id = 27;
        $sale->begin_date = new \DateTime();
        $sale->end_date = date('Y-m-d h:i:s', strtotime('+1 year'));
        $sale->save();
        
        $user = \App\User::find($sale->user_id);
        $customer = \App\customers::find($sale->customer_id);
        
        \App\Jobs\insuranceRequestSignedEmailJobs::dispatch($sale->id,$user->email,$customer->document);
        
        \Session::flash('R3Success', 'Los beneficiarios han sido guardados correctamente.');
    }
    
    public function secondStepStore(request $request){
        $appSearch = \App\insurance_application::where('sales_id','=',$request['salId'])->get();
        if($appSearch[0]->status_id == 6){
            $beneficiary = \App\beneficiary::where('sales_id','=',$request['salId'])->delete();
            foreach($request['tableData'] as $dat){
                
                $relationship = \App\benefitiary_relationship::where('name','like','%'.$dat['relationship'].'%')->get();
               
                $ben = new \App\beneficiary();
                $ben->sales_id = $request['salId'];
                $ben->porcentage = $dat['porcentaje'];
                $ben->first_name = $dat['firstName'];
                $ben->second_name = $dat['secondName'];
                $ben->last_name = $dat['lastName'];
                $ben->second_last_name = $dat['secondLastName'];
                $ben->relationship_id = $relationship[0]->id;
                $ben->save();
            }
        }
    }
    
    public function thirdStepStore(request $request) {
        $appSearch = \App\insurance_application::where('sales_id', '=', $request['salId'])->get();
        if ($appSearch[0]->status_id == 6) {
            
            $appSearch = \App\insurance_application_answers::where('sales_id','=',$request['salId'])->get();
            if($appSearch->isEmpty()){
                $app = new \App\insurance_application_answers();
            }else{
                $app = \App\insurance_application_answers::find($appSearch[0]->id);
            }
            $app->sales_id = $request['salId'];
            $app->insuranceRecord1 = $request['insuranceData']['insuranceRecord1'];
            $app->insuranceRecord1_detail = $request['insuranceData']['textAreaiR1'];
            $app->insuranceRecord2 = $request['insuranceData']['insuranceRecord2'];
            $app->insuranceRecord2_detail = $request['insuranceData']['textAreaiR2'];
            $app->insuranceRecord3 = $request['insuranceData']['insuranceRecord3'];
            $app->insuranceRecord3_detail = $request['insuranceData']['textAreaiR3'];
            $app->insuranceRecord4 = $request['insuranceData']['insuranceRecord4'];
            $app->insuranceRecord4_detail = $request['insuranceData']['textAreaiR4'];
            $app->insuranceRecord5 = $request['insuranceData']['insuranceRecord5'];
            $app->insuranceRecord5_detail = $request['insuranceData']['textAreaiR5'];
            $app->insuranceRecord6 = $request['insuranceData']['insuranceRecord6'];
            $app->insuranceRecord6_detail = $request['insuranceData']['textAreaiR6'];
            $app->insuranceRecord7 = $request['insuranceData']['insuranceRecord7'];
            $app->insuranceRecord7_detail = $request['insuranceData']['textAreaiR7'];
            $app->save();
        }
    }
    
    public function fourthStepStore(request $request){
        $appSearch = \App\insurance_application::where('sales_id', '=', $request['salId'])->get();
        if ($appSearch[0]->status_id == 6) {
            $appSearch = \App\insurance_application_answers::where('sales_id','=',$request['salId'])->get();
            if($appSearch->isEmpty()){
                $app = new \App\insurance_application_answers();
            }else{
                $app = \App\insurance_application_answers::find($appSearch[0]->id);
            }
            $app->medicalHistory1 = $request['clinicalData']['medicalHistory1'];
            $app->diagnosis1 = $request['clinicalData']['diagnosis1'];
            $app->treatmentDate1 = $request['clinicalData']['treatmentDate1'];
            $app->duration1 = $request['clinicalData']['duration1'];
            $app->hospital1 = $request['clinicalData']['hospital1'];
            $app->medicalHistory2 = $request['clinicalData']['medicalHistory2'];
            $app->diagnosis2 = $request['clinicalData']['diagnosis2'];
            $app->treatmentDate2 = $request['clinicalData']['treatmentDate2'];
            $app->duration2 = $request['clinicalData']['duration2'];
            $app->hospital2 = $request['clinicalData']['hospital2'];
            $app->medicalHistory3 = $request['clinicalData']['medicalHistory3'];
            $app->diagnosis3 = $request['clinicalData']['diagnosis3'];
            $app->treatmentDate3 = $request['clinicalData']['treatmentDate3'];
            $app->duration3 = $request['clinicalData']['duration3'];
            $app->hospital3 = $request['clinicalData']['hospital3'];
            $app->medicalHistory4 = $request['clinicalData']['medicalHistory4'];
            $app->diagnosis4 = $request['clinicalData']['diagnosis4'];
            $app->treatmentDate4 = $request['clinicalData']['treatmentDate4'];
            $app->duration4 = $request['clinicalData']['duration4'];
            $app->hospital4 = $request['clinicalData']['hospital4'];
            $app->medicalHistory5 = $request['clinicalData']['medicalHistory5'];
            $app->diagnosis5 = $request['clinicalData']['diagnosis5'];
            $app->treatmentDate5 = $request['clinicalData']['treatmentDate5'];
            $app->duration5 = $request['clinicalData']['duration5'];
            $app->hospital5 = $request['clinicalData']['hospital5'];
            $app->medicalHistory6 = $request['clinicalData']['medicalHistory6'];
            $app->diagnosis6 = $request['clinicalData']['diagnosis6'];
            $app->treatmentDate6 = $request['clinicalData']['treatmentDate6'];
            $app->duration6 = $request['clinicalData']['duration6'];
            $app->hospital6 = $request['clinicalData']['hospital6'];
            $app->medicalHistory7 = $request['clinicalData']['medicalHistory7'];
            $app->diagnosis7 = $request['clinicalData']['diagnosis7'];
            $app->treatmentDate7 = $request['clinicalData']['treatmentDate7'];
            $app->duration7 = $request['clinicalData']['duration7'];
            $app->hospital7 = $request['clinicalData']['hospital7'];
            $app->medicalHistory8 = $request['clinicalData']['medicalHistory8'];
            $app->diagnosis8 = $request['clinicalData']['diagnosis8'];
            $app->treatmentDate8 = $request['clinicalData']['treatmentDate8'];
            $app->duration8 = $request['clinicalData']['duration8'];
            $app->hospital8 = $request['clinicalData']['hospital8'];
            $app->save();
        }
    }
    
    public function fifthStepStore(request $request){
        $sale = \App\sales::find($request['salId']);
        $sale->begin_date = date('Y-m-d', strtotime('now'));
        $sale->end_date = date('Y-m-d', strtotime('+1 years'));
        $sale->save();
        
        $customer = \App\customers::find($sale->customer_id);
        $city = \App\city::find($customer->city_id);
        $app = \App\insurance_application::where('sales_id','=',$sale->id)->get();
        $answers = \App\insurance_application_answers::where('sales_id','=',$sale->id)->get();
        $beneficariys = \App\beneficiary::selectRaw('CONCAT(beneficiary.first_name," ", beneficiary.second_name," ",beneficiary.last_name," ",beneficiary.second_last_name) as "cusName", beneficiary.porcentage as "benPor", rela.name as "benRela"')
                                                    ->join('beneficiary_relationship as rela','rela.id','=','beneficiary.relationship_id')
                                                    ->where('sales_id','=',$sale->id)
                                                    ->get();
        $agentSS = \App\agent_ss::selectRaw('agent_ss.agentedes')
                                    ->join('products_channel as pbc','pbc.agent_ss','=','agent_ss.id')
                                    ->join('sales as sal','sal.pbc_id','=','pbc.id')
                                    ->where('sal.id','=',$sale->id)
                                    ->get();
        
        $covMuerte = \App\coverage::selectRaw('coverage.valorasegurado')
                                ->join('products as pro','pro.id','=','coverage.product_id')
                                ->join('products_channel as pbc','pbc.product_id','=','pro.id')
                                ->where('coverage.coberturades','like','%MUERTE%')
                                ->where('pbc.id','=',$sale->pbc_id)
                                ->get();
        if($covMuerte->isEmpty()){
            $muerte = 'N/A';
        }else{
            $muerte = $covMuerte[0]->valorasegurado;
        }
        
        $covEnfer = \App\coverage::selectRaw('coverage.valorasegurado')
                                ->join('products as pro','pro.id','=','coverage.product_id')
                                ->join('products_channel as pbc','pbc.product_id','=','pro.id')
                                ->where('coverage.coberturades','like','%ENFERMEDADES GRAVES%')
                                ->where('pbc.id','=',$sale->pbc_id)
                                ->get();
        
        if($covEnfer->isEmpty()){
            $enfer = 'N/A';
        }else{
            $enfer = $covEnfer[0]->valorasegurado;
        }
        
        
        
        $pdf = PDF::loadView('sales.R2.pdf_insuranceApplication',[
            'sale' => $sale,
            'customer' => $customer,
            'app' => $app[0],
            'answers' => $answers[0],
            'beneficiarys' => $beneficariys,
            'agentSS' => $agentSS[0],
            'city' => $city,
            'muerte' => $muerte,
            'enfer' => $enfer
        ]);
        
        $output = $pdf->output();
        file_put_contents(public_path('formulario.pdf'), $output);
        
        $b64Doc = chunk_split(base64_encode(file_get_contents(public_path('formulario.pdf'))));
//        return $b64Doc;

        $sales = \App\sales::find($request['salId']);
        $customer = \App\customers::find($sales->customer_id);
        
        $result = viamaticaSendPdf($customer->document, $customer->first_name, $customer->mobile_phone, $sales->id.'R2IA', $b64Doc, $customer->email, $customer->phone, $request['salId'], 2, 'Solicitud de Aseguramiento', 'Formulario de Vida');
        
        $sales->viamatica_id = $result['data'][0];
        $sales->save();
        
        return $result;
    }
    
    public function pdf(){
        $sale = \App\sales::find(1351);
        $sale->begin_date = date('Y-m-d', strtotime('now'));
        $sale->end_date = date('Y-m-d', strtotime('+1 years'));
        $sale->save();
        
        $customer = \App\customers::find($sale->customer_id);
        $city = \App\city::find($customer->city_id);
        $app = \App\insurance_application::where('sales_id','=',$sale->id)->get();
        $answers = \App\insurance_application_answers::where('sales_id','=',$sale->id)->get();
        $beneficariys = \App\beneficiary::selectRaw('CONCAT(beneficiary.first_name," ", beneficiary.second_name," ",beneficiary.last_name," ",beneficiary.second_last_name) as "cusName", beneficiary.porcentage as "benPor", rela.name as "benRela"')
                                                    ->join('beneficiary_relationship as rela','rela.id','=','beneficiary.relationship_id')->get();
        $agentSS = \App\agent_ss::selectRaw('agent_ss.agentedes')
                                    ->join('products_channel as pbc','pbc.agent_ss','=','agent_ss.id')
                                    ->join('sales as sal','sal.pbc_id','=','pbc.id')
                                    ->where('sal.id','=',$sale->id)
                                    ->get();
        
        $covMuerte = \App\coverage::selectRaw('coverage.valorasegurado')
                                ->join('products as pro','pro.id','=','coverage.product_id')
                                ->join('products_channel as pbc','pbc.product_id','=','pro.id')
                                ->where('coverage.coberturades','like','%MUERTE%')
                                ->where('pbc.id','=',$sale->pbc_id)
                                ->get();
        if($covMuerte->isEmpty()){
            $muerte = 'N/A';
        }else{
            $muerte = $covMuerte[0]->valorasegurado;
        }
        
        $covEnfer = \App\coverage::selectRaw('coverage.valorasegurado')
                                ->join('products as pro','pro.id','=','coverage.product_id')
                                ->join('products_channel as pbc','pbc.product_id','=','pro.id')
                                ->where('coverage.coberturades','like','%ENFERMEDADES%')
                                ->where('pbc.id','=',$sale->pbc_id)
                                ->get();
        
        if($covEnfer->isEmpty()){
            $enfer = 'N/A';
        }else{
            $enfer = $covEnfer[0]->valorasegurado;
        }
        
        $pdf = PDF::loadView('sales.R2.pdf_insuranceApplication',[
            'sale' => $sale,
            'customer' => $customer,
            'app' => $app[0],
            'answers' => $answers[0],
            'beneficiarys' => $beneficariys,
            'agentSS' => $agentSS[0],
            'city' => $city,
            'muerte' => $muerte, 
            'enfer' => $enfer
        ]);
        return $pdf->stream('formulario.pdf');
    }
    

}
