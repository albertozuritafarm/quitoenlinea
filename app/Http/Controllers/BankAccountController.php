<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Validator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;

class bankAccountController extends Controller
{
    public function index(request $request){
        //Obtain Channel
        $channelQuery = 'select cha.* from channels cha join agencies agen on agen.channel_id = cha.id where agen.id =  "' . \Auth::user()->agen_id . '"';
        $channel = DB::select($channelQuery);

        //Obtain Payments
        $accountsQuery = 'select
                         ban.id as "id",
                         concat(cus.first_name," ",cus.last_name) as "customer",
                         cus.document as "document_number",
                         doc.name as "document",
                         sta.name as "status",
                         DATE_FORMAT(ban.created_at, "%d-%m-%Y") as "date"
                         from bank_accounts ban
                         join customers cus on cus.id = ban.customer_id
                         join status sta on sta.id = ban.status_id
                         join documents doc on doc.id = cus.document_id where ban.status_id not in (11)';

        //VALIDATE FILTERS
        if ($request['banId'] != null) {//DOCUMENT
            $accountsQuery .= ' and ban.id = "' . $request['banId'] . '"';
        }
        if ($request['beginDate'] != null) {//DATE   
            $accountsQuery .= ' and DATE_FORMAT(ban.created_at,"%Y-%m-%d") BETWEEN "' . $request['beginDate'] . '" and "'.$request['endDate'].'"';
        }
        if ($request['document'] != null) {//SALID
            $accountsQuery .= ' and cus.document = "' . $request['document'] . '"';
        }
        if ($request['status'] != 0) {//PAYMENT TYPE
            $accountsQuery .= ' and sta.id = "' . $request['status'] . '"';
        }
        $accountsQuery .= ' ORDER BY id DESC';
        $accounts = DB::select($accountsQuery);
        
        //Status
        $status = \App\status::find([6,10,18,19]);
        
        //Filter Data Array
        $data = array('banId' => $request['banId'],
            'beginDate' => $request['beginDate'],
            'endDate' => $request['endDate'],
            'saleId' => $request['saleId'],
            'document' => $request['document'],
            'status' => $request['status'],
            'charge_type' => $request['charge_type']);

        return view('account.index', [
            'accounts' => $accounts,
            'data' => $data,
            'status' => $status
        ]);
    }
    
    public function create(){
        //Obtain Channel
        $channelQuery = 'select cha.* from channels cha join agencies agen on agen.channel_id = cha.id where agen.id =  "'.\Auth::user()->agen_id.'"';
        $channel = DB::select($channelQuery);
        
        //Product Data
        $products = DB::select('select pCha.id, pro.name, pro.price, pro.total_price, pro.segment, pro.detail, pro.conditions, pro.exclutions from products pro join products_channel pCha on pCha.product_id = pro.id where pro.status_id = "1" and pCha.channel_id = "'.$channel[0]->id.'" and pro.product_type = "INDIVIDUAL"');
        $documents = DB::select('select * from documents where id in (1,2,3,4)');
        $countries = DB::select('select * from countries');
        $genders = DB::select('select * from gender');
        $nacionalities = DB::select('select * from nacionality');
        $civilStates = DB::select('select * from civil_state');
        $correspondences = DB::select('select * from correspondence');
        $relationships = DB::select('select * from relationships');
        $birthCountry = \App\country::all();
        $incomeSource = \App\income_source::all();
        $economicActivity = \App\economic_activity::all();
        $assets = \App\bank_accounts_assets::all();
        $passives = \App\bank_accounts_passives::all();
        
        return view('account.create', [
            'products' => $products,
            'documents' => $documents,
            'countries' => $countries,
            'genders' => $genders,
            'nacionalities' => $nacionalities,
            'civilStates' => $civilStates,
            'correspondences' => $correspondences,
            'relationships' => $relationships,
            'birthCountry' => $birthCountry,
            'incomeSource' => $incomeSource,
            'economicActivity' => $economicActivity,
            'assets' => $assets,
            'passives' => $passives
        ]);
    }
    public function store(request $request){
        $returnArray = array();
        //INSERT OR UPDATE CUSTOMER
        $query = 'select id from customers where document = "'.$request['data']['document'].'"';
        $customer = DB::select($query);
        if ( !empty ( $customer ) ) {
            $customerUpdate = \App\customers::find($customer[0]->id);
            $customerUpdate->birthdate = $request['data']['birthdate'];
            $customerUpdate->gender_id = $request['data']['gender'];
            $customerUpdate->profession = $request['data']['profession'];
            $customerUpdate->nacionality_id = $request['data']['nacionality'];
            $customerUpdate->civil_status_id = $request['data']['civil_state'];
            $customerUpdate->activity = $request['data']['activity'];
            $customerUpdate->address = $request['data']['address'];
            $customerUpdate->work_address = $request['data']['work_address'];
            $customerUpdate->work_address = $request['data']['work_address'];
            $customerUpdate->mobile_phone = $request['data']['mobile_phone'];
            $customerUpdate->mobile_phone = $request['data']['mobile_phone'];
            $customerUpdate->city_id = $request['data']['city'];
            $customerUpdate->correspondence_id = $request['data']['correspondence'];
            $customerUpdate->phone = $request['data']['phone'];
            $customerUpdate->email = $request['data']['email'];
            try{
                $customerUpdate->save();
            } catch(\Exception $e){
                $returnArray = [
                    'success' => 'false',
                    'msg' => 'Hubo un error actualizando los datos del cliente'
                ];
                return $returnArray;
            }
            $customerId = $customerUpdate->id;
            $customerPhone = $customerUpdate->mobile_phone;
            $customerName = $customerUpdate->first_name. ' '.$customerUpdate->last_name;
            $customerEmail = $customerUpdate->email;
            
        }else{
            $customerNew = new \App\customers();
            $customerNew->document_id = $request['data']['document_id'];
            $customerNew->document = $request['data']['document'];
            $customerNew->first_name = $request['data']['first_name'];
            $customerNew->last_name = $request['data']['last_name'];
            $customerNew->birthdate = $request['data']['birthdate'];
            $customerNew->gender_id = $request['data']['gender'];
            $customerNew->profession = $request['data']['profession'];
            $customerNew->nacionality_id = $request['data']['nacionality'];
            $customerNew->civil_status_id = $request['data']['civil_state'];
            $customerNew->activity = $request['data']['activity'];
            $customerNew->address = $request['data']['address'];
            $customerNew->work_address = $request['data']['work_address'];
            $customerNew->work_address = $request['data']['work_address'];
            $customerNew->mobile_phone = $request['data']['mobile_phone'];
            $customerNew->mobile_phone = $request['data']['mobile_phone'];
            $customerNew->city_id = $request['data']['city'];
            $customerNew->correspondence_id = $request['data']['correspondence'];
            $customerNew->phone = $request['data']['phone'];
            $customerNew->email = $request['data']['email'];
            $customerNew->status_id = 1;
            try{
                $customerNew->save();
            } catch(\Exception $e){
                $returnArray = [
                    'success' => 'false',
                    'msg' => 'Hubo un error guardando al nuevo cliente'
                ];
                return $returnArray;
            }
            $customerId = $customerNew->id;
            $customerPhone = $customerNew->mobile_phone;
            $customerName = $customerNew->first_name. ' '.$customerNew->last_name;
            $customerEmail = $customerNew->email;
        }
        //INSERT OR UPDATE REPRESENTATIVE
        if($request['data']['document_representative']){
            $query = 'select id from representatives where document = "'.$request['data']['document_representative'].'"';
            $representative = DB::select($query);
            if($representative){
                $representativeUpdate = \App\representative::find($representative[0]->id);
                $representativeUpdate->birthdate = $request['data']['birthdate_representative'];
                $representativeUpdate->nacionality_id = $request['data']['nationality_representative'];
                $representativeUpdate->relationship_id = $request['data']['relationship_representative'];
                $representativeUpdate->gender_id = $request['data']['gender_representative'];
                try{
                    $representativeUpdate->save();
                } catch (\Exception $ex) {
                    $returnArray = [
                        'success' => 'false',
                        'msg' => 'Hubo un error actualizando los datos del representante/conyuge'
                    ];
                    return $returnArray;
                }
                $representativeId = $representativeUpdate->id;
            }else{
                $representativeNew = new \App\representative();
                $representativeNew->document_id = $request['data']['document_id_representative'];
                $representativeNew->document = $request['data']['document_representative'];
                $representativeNew->first_name = $request['data']['first_name_representative'];
                $representativeNew->last_name = $request['data']['last_name_representative'];
                $representativeNew->birthdate = $request['data']['birthdate_representative'];
                $representativeNew->nacionality_id = $request['data']['nationality_representative'];
                $representativeNew->relationship_id = $request['data']['relationship_representative'];
                $representativeNew->gender_id = $request['data']['gender_representative'];
                try{
                    $representativeNew->save();
                } catch (\Exception $ex) {
                    $returnArray = [
                        'success' => 'false',
                        'msg' => 'Hubo un error guardando los datos del representante'
                    ];
                    return $returnArray;
                }
                $representativeId = $representativeNew->id;
            }
        }else{
            $representativeId = null;
        }
        //Create New Folders
        $dest = public_path('images/accounts/'.$request['data']['document'].'/Front/');
        if (!file_exists($dest)) { mkdir($dest, 0777, true); }
        $dest = public_path('images/accounts/'.$request['data']['document'].'/Back/');
        if (!file_exists($dest)) { mkdir($dest, 0777, true); }
        $dest = public_path('images/accounts/'.$request['data']['document'].'/Local/');
        if (!file_exists($dest)) { mkdir($dest, 0777, true); }
        
        //FRONT Move Temp Files to Permanent Location
        try{
            $source = public_path('images/temp/accounts/'.$request['data']['document'].'/Front/').$request['data']['pictureNameFront'];
            $dest = public_path('images/accounts/'.$request['data']['document'].'/Front/').$request['data']['pictureNameFront'];
            $moveFront = File::move($source, $dest);
        } catch (\Exception $ex) {
            $returnArray = [
                'success' => 'false',
                'msg' => 'Hubo un problema guardando la imagen frontal de la cedula'
            ];
            return $returnArray;
        }
        //BACK Move Temp Files to Permanent Location
        try{
            $source = public_path('images/temp/accounts/'.$request['data']['document'].'/Back/').$request['data']['pictureNameBack'];
            $dest = public_path('images/accounts/'.$request['data']['document'].'/Back/').$request['data']['pictureNameBack'];
            $moveBack = File::move($source, $dest);
        } catch (\Exception $ex) {
            $returnArray = [
                    'success' => 'false',
                    'msg' => 'Hubo un error guardando la imagen trasera de la cedula'
                ];
            return $returnArray;
        }
        //LOCAL Move Temp Files to Permanent Location
        try{
            $source = public_path('images/temp/accounts/'.$request['data']['document'].'/Local/').$request['data']['pictureNameLocal'];
            $dest = public_path('images/accounts/'.$request['data']['document'].'/Local/').$request['data']['pictureNameLocal'];
            $moveLocal = File::move($source, $dest);
        } catch (Exception $ex) {
            $returnArray = [
                    'success' => 'false',
                    'msg' => 'Hubo un error guardando la imgen frontal del local'
                ];
            return $returnArray;
        }
        //Sens SMS
        $randomCode = rand(100000, 999999);
                
        //Send Mail
//        sendAccountSMS($customerPhone, $randomCode);
        
        $now = new \DateTime();
        
        //Store new Bank Accounts
        $account = new \App\bank_account();
        $account->customer_id = $customerId;
        $account->lon = $request['data']['GLLng'];
        $account->lat = $request['data']['GLLat'];
        $account->lat = $request['data']['GLLat'];
        $account->picture_front = '/images/accounts/'.$request['data']['document'].'/Front/'.$request['data']['pictureNameFront'];
        $account->status_id = 10;
        $account->civil_status_id = $request['data']['civil_state'];
        $account->profession = $request['data']['profession'];
        $account->activity = $request['data']['activity'];
        $account->address = $request['data']['address'];
        $account->work_address = $request['data']['work_address'];
        $account->phone = $request['data']['phone'];
        $account->mobile_phone = $request['data']['mobile_phone'];
        $account->city_id = $request['data']['city'];
        $account->email = $request['data']['email'];
        $account->correspondence_id = $request['data']['correspondence'];
        $account->representative_id = $representativeId;
        $account->representative_birthdate = $request['data']['birthdate_representative'];
        $account->representative_nacionality_id = $request['data']['nationality_representative'];
        $account->representative_relationship_id = $request['data']['relationship_representative'];
        $account->representative_gender_id = $request['data']['gender_representative'];
        $account->picture_back = '/images/accounts/'.$request['data']['document'].'/Back/'.$request['data']['pictureNameBack'];
        $account->picture_local = '/images/accounts/'.$request['data']['document'].'/Local/'.$request['data']['pictureNameLocal'];
        $account->code = $randomCode;
        $account->date_code_send = $now;
        try{
            $account->save();
        } catch (\Exception $ex) {
            $returnArray = [
                    'success' => 'false',
                    'msg' => 'Hubo un error guardando su solicitud'
                ];
            return $returnArray;
        }
        
        //Store Credit
        $credit = new \App\credit();
        $credit->id_customer = $customerId;
        $credit->id_bank = '1';
        $credit->amount = '1500000';
        $credit->status_id = 1;
        $credit->save();
        $credit = new \App\credit();
        $credit->id_customer = $customerId;
        $credit->id_bank = '2';
        $credit->amount = '1500000';
        $credit->status_id = 1;
        $credit->save();
        
        $credit = new \App\credit();
        $credit->id_customer = $customerId;
        $credit->id_bank = '2';
        $credit->amount = '1500000';
        $credit->status_id = 1;
        $credit->save();
        $credit = new \App\credit();
        $credit->id_customer = $customerId;
        $credit->id_bank = '2';
        $credit->amount = '1500000';
        $credit->status_id = 1;
        $credit->save();
        
        //Send Mail
        $job = (new \App\Jobs\newAccountCodeEmailJobs($customerName, $randomCode, $customerEmail));
        dispatch($job);
        
        //Send Mail
        $job = (new \App\Jobs\newAccountJobs($customerId, 'emeza@tat.com.ec'));
        dispatch($job);
        
        //Send Mail
        $job = (new \App\Jobs\newAccountJobs($customerId, 'jbenalcazar@agp-corporacion.com'));
        dispatch($job);
        
        if(empty($returnArray)){
            \Session::flash('successAccount', 'La solicitud fue creada correctamente');
            $returnArray = [
                    'success' => 'true',
                    'msg' => '',
                    'accountId' => $account->id
                ];
            return $returnArray;
        }
    }
    
    public function storePicture(request $request){
        $validation = Validator::make($request->all(), [
                    'select_file'.$request['side'] => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        if ($validation->passes()) {            
            //Temp Folder
            $frontFolder = '/images/temp/accounts/'.$request['document'].'/'.$request['side'].'/';
            //Create Temp Folder
            if (!file_exists($frontFolder)) {
                mkdir($frontFolder, 0777, true);
            }
            
            $image = $request->file('select_file'.$request['side']);
            $new_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/temp/accounts/'.$request['document'].'/'.$request['side'].'/'), $new_name);

            return response()->json([
                        'message' => 'Image Upload Successfully',
                        'uploaded_image' => '<a href="' . $frontFolder . $new_name . '" target="_blank"><img src="' . $frontFolder . $new_name . '" class="img-thumbnail" width="300" /></a>',
                        'class_name' => 'alert-success',
                        'vSalId' => $request->vSalId,
                        'Success' => 'true',
                        'Active' => 'false',
                        'pictureName' => $new_name
            ]);
        } else {
            return response()->json([
                        'message' => 'Debe subir la imagen en un formato valido',
                        'uploaded_image' => '',
                        'side' => $request['side'],
                        'class_name' => 'alert-danger',
                        'vSalId' => $request->vSalId,
                        'Success' => 'false'
            ]);
        }
    }
    
    public function deletePicture(request $request){
        $side = $request['data']['side'];
        $document = $request['data']['document'];
        $file = new Filesystem;
        $route = 'images/temp/accounts/'.$document.'/'.$side;
        $file->cleanDirectory($route);
    }
    
    public function approve(request $request){
        $account = \App\bank_account::find($request['id']);
        $account->status_id = 18;
        $account->save();
        
        \Session::flash('successAccount', 'La solicitud fue aprobada correctamente');
        return redirect()->route('accountIndex');
    }
    public function deny(request $request){
        $account = \App\bank_account::find($request['id']);
        $account->status_id = 19;
        $account->save();
        
        \Session::flash('successAccount', 'La solicitud fue rechazada correctamente');
        return redirect()->route('accountIndex');
    }
    public function delete(request $request){
        foreach($request['data']['accounts'] as $acc){
            $account = \App\bank_account::find($acc);
            $account->status_id = 11;
            $account->save();
        }
        \Session::flash('successAccount', 'La(s) solicitud(es) fue(ron) eliminada(s) correctamente');
    }
    public function sendCode(request $request){
        $randomCode = rand(100000, 999999);
        $now = new \DateTime();
        $account = \App\bank_account::find($request['data']['accountId']);
        $account->code = $randomCode;
        $account->date_code_send = $now;
        $account->save();
        
        $customer = \App\customers::find($account->customer_id);
        $name = $customer->first_name. ' ' .$customer->last_name;
        //Send Mail
        $job = (new \App\Jobs\newAccountCodeEmailJobs($name, $randomCode, $customer->email));
        dispatch($job);
        
//        sendAccountSMS($account->mobile_phone, $randomCode);
    }
    public function validateCode(request $request){
        $now = new \DateTime();
        $account = \App\bank_account::find($request['data']['accountId']);
        if($account->code == $request['data']['code']){
            $account->validate_code_send = $now;
            $account->status_id = 6;
            $account->save();
            $returnArray = [
                    'success' => 'true',
                    'msg' => ''
                ];
        }else{
            $returnArray = [
                    'success' => 'false',
                    'msg' => 'El codigo ingresado es incorrecto'
                ];
        }
        return $returnArray;
    }
}