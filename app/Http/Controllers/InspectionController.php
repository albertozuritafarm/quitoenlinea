<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Pagination\Paginator;
use Validator;
use App\Mail\inspectionRequestR1UserEmail;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

class InspectionController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('validateUserRoute');
    }

    public function create(request $request) {
        //Validate if Inspection already created
        $validateInspection = \App\inspection::selectRaw('inspection.sales_id')->where('inspection.sales_id','=',$request['saleId'])->get();
        if(!$validateInspection->isEmpty()){
           \Session::flash('errorMessage', 'La venta ya fue enviada a Inspeccionar.');

            return 'observado'; 
        }
        
        $returnArray = array();
        //Validate Lista Observados y Cartera
        $sales = \App\sales::find($request['saleId']);
        if($sales->status_id == 24 || $sales->status_id == 31){
            \Session::flash('errorMessage', ' El proceso de venta no puede continuar, para información adicional por favor contactar a tu ejecutivo comercial.');

            return 'observado';
        }
        $now = new \DateTime();

        $product = \App\products::selectRaw('products.ramoid')
                                    ->join('products_channel as pbc','pbc.product_Id','=','products.id')
                                    ->join('sales as sal','sal.pbc_id','=','pbc.id')
                                    ->where('sal.id','=',$request['saleId'])
                                    ->get();
        $ramoId = $product[0]->ramoid;
        $saleFind = \App\sales::find($request['saleId']);

        $sale = \App\sales::find($request['saleId']);

        $customer = \App\customers::find($sale->customer_id);

        switch ($ramoId) {
                case 7: // VEHICLE
                        $vehiSales = \App\vehicles_sales::where('sales_id','=',$request['saleId'])->get();
                        foreach($vehiSales as $vehi){
                            //New Inspection
                            $inspection = new \App\inspection();
                            $inspection->sales_id = $request['saleId'];
                            $inspection->status_id = 22;
                            $inspection->date_created = $now;
                            $inspection->vehicles_sales_id = $vehi->id;
                            $inspection->save();
                            
                            //INSPECTOR USER
                            $inspector = \App\User::where('role_id','=','45')->where('status_id','=',1)->get();
                                                        
                            //SEND EMAIL TO INSPECTOR
                            $job = (new \App\Jobs\inspectionRequestR1UserEmailJobs($request['saleId'],$inspector[0]->email,$customer->document,$inspection->id));
                            dispatch($job);
                        
                            \Session::flash('inspectionCreate', 'Se le ha notificado al Inspector que debe realizar la inspección de la venta ' . $request['saleId']);
                        
                        }
                break;
                case 5: // FIRE
                        //New Inspection
                        $inspection = new \App\inspection();
                        $inspection->sales_id = $request['saleId'];
                        $inspection->status_id = 22;
                        $inspection->date_created = $now;
                        $inspection->save();
                        
                        //INSPECTOR USER
                        $inspector = \App\User::where('role_id','=','45')->where('status_id','=',1)->get();
                        
                        //SEND EMAIL TO INSPECTOR
                        $job = (new \App\Jobs\inspectionRequestR4UserEmailJobs($request['saleId'],$inspector[0]->email,$customer->document,$inspection->id));
                        dispatch($job);
                        
                        \Session::flash('inspectionCreate', 'Se le ha notificado al Inspector que debe realizar la inspección de la venta ' . $request['saleId']);
                        
                break;
                case 40: // FIRE
                        //New Inspection
                        $inspection = new \App\inspection();
                        $inspection->sales_id = $request['saleId'];
                        $inspection->status_id = 22;
                        $inspection->date_created = $now;
                        $inspection->save();
                        
                        //INSPECTOR USER
                        $inspector = \App\User::where('role_id','=','45')->where('status_id','=',1)->get();
                        
                        //SEND EMAIL TO INSPECTOR
                        $job = (new \App\Jobs\inspectionRequestR4UserEmailJobs($request['saleId'],$inspector[0]->email,$customer->document,$inspection->id));
                        dispatch($job);
                        
                        \Session::flash('inspectionCreate', 'Se le ha notificado al Inspector que debe realizar la inspección de la venta ' . $request['saleId']);
                        
                break;
                case 1: // LIFE
                        //New Inspection
                        $inspection = new \App\inspection();
                        $inspection->sales_id = $request['saleId'];
                        $inspection->status_id = 22;
                        $inspection->date_created = $now;
                        $inspection->save();
                        
                        //IAUDITOR USER
                        $auditor = \App\User::where('role_id','=','46')->where('status_id','=',1)->get();
                        
                        \App\Jobs\insuranceRequestSendLinkEmailJobs::dispatch($request['saleId'],$customer->email,$customer->document);

                        //SEND MAIL TO AUDITOR MEDICO
                        $job = (new \App\Jobs\inspectionRequestR2UserEmailJobs($request['saleId'],$auditor[0]->email,$customer->document,$inspection->id));
                        dispatch($job);
                        
                        \Session::flash('inspectionCreate', 'Se ha notificado al Cliente que debe realizar la Solicitud de Aseguramiento de la Venta  ' . $request['saleId']);
                        
                break;
                case 2: // LIFE
                        //New Inspection
                        $inspection = new \App\inspection();
                        $inspection->sales_id = $request['saleId'];
                        $inspection->status_id = 22;
                        $inspection->date_created = $now;
                        $inspection->save();
                        
                        //IAUDITOR USER
                        $auditor = \App\User::where('role_id','=','46')->where('status_id','=',1)->get();
                        
                        \App\Jobs\insuranceRequestSendLinkEmailJobs::dispatch($request['saleId'],$customer->email,$customer->document);

                        //SEND MAIL TO AUDITOR MEDICO
                        $job = (new \App\Jobs\inspectionRequestR2UserEmailJobs($request['saleId'],$auditor[0]->email,$customer->document,$inspection->id));
                        dispatch($job);
                        
                        \Session::flash('inspectionCreate', 'Se ha notificado al Cliente que debe realizar la Solicitud de Aseguramiento de la Venta  ' . $request['saleId']);
                        
                break;
                case 4: // AP
                        //New Inspection
                        $inspection = new \App\inspection();
                        $inspection->sales_id = $request['saleId'];
                        $inspection->status_id = 22;
                        $inspection->date_created = $now;
                        $inspection->save();
                        
                        
                        //IAUDITOR USER
                        $auditor = \App\User::where('role_id','=','46')->where('status_id','=',1)->get();
                        
//                        \App\Jobs\beneficiariesRequestSendLinkEmailJobs::dispatch($request['saleId'],$customer->email,$customer->document);
                        
                        $email = new \App\Mail\beneficiariesRequestSendLinkEmail($request['saleId'], $customer->email, $customer->document);
                        Mail::to($customer->email)->send($email);
                        
                        //SEND EMAIL TO INSPECTOR
                        $job = (new \App\Jobs\inspectionRequestR2UserEmailJobs($request['saleId'],$auditor[0]->email,$customer->document,$inspection->id));
                        dispatch($job);
                        
                        \Session::flash('inspectionCreate', 'Se ha notificado al Cliente que debe realizar la Solicitud de Aseguramiento de la Venta ' . $request['saleId']);
                        
                break;
            }

        //Update Sale
        $sale = \App\sales::find($request['saleId']);
        $sale->status_id = 22;
        $sale->save();

        return $request['saleId'];
    }

    public function index(request $request) {
        $viewPermit = checkViewPermit('61', \Auth::user()->role_id);
        if (!$viewPermit) {
            \Session::flash('ValidateUserRoute', 'No tiene acceso al modulo de Inspección.');
            return view('home');
        }
        
        //Obtain Edit Permission
        $edit = checkExtraPermits('67', \Auth::user()->role_id);
        
        //Store Form Variables in Session
        if ($request->isMethod('post')) {
            session(['inspectionItems' => $request->items]);
            session(['inspectionSalesId' => $request->salesId]);
            session(['inspectionStatus' => $request->status]);
            $currentPage = 1;
            session(['inspectionPage' => 1]);
        } else {
            $currentPage = session('inspectionPage');
        }

        $status = \App\status::find([22, 27, 28]);

        //Pagination Items
        if (session('inspectionItems') == null) { $items = 10; } else { $items = session('inspectionItems'); }

        //Form Variables
        $salesId = session('inspectionSalesId');
        $statusForm = session('inspectionStatus');

        // Make sure that you call the static method currentPageResolver()
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });
        
        //Validate User Type Sucre
        $rol = \App\rols::find(\Auth::user()->role_id);
        
        //Validate User Type
        if(\Auth::user()->role_id == 45){ // VEHICLE
            $userRol = true;
            $userQuery = ' products.ramoid in (7,5,40)';
        }elseif(\Auth::user()->role_id == 46){ // LIFE
            $userRol = true;
            $userQuery = ' products.ramoid in (1,2,4)';
        } else{
            $userRol = false;
            $userQuery = '';
        }
        
        //Customers
        $inspections = inspections($salesId, $statusForm, $items, $userRol, $userQuery);
        
//        $edit = true;
        return view('inspection.index', [
            'inspections' => $inspections,
            'items' => $items,
            'status' => $status,
            'edit' => $edit
        ]);
    }

    function fetch_data(Request $request) {
        if ($request->ajax()) {
            //Page
            session(['inspectionPage' => $request->page]);

            //Obtain Edit Permission
            $edit = checkExtraPermits('67', \Auth::user()->role_id);

            //Obtain Create Permission
            $create = checkExtraPermits('53', \Auth::user()->role_id);

            //Obtain Cancel Permission
            $cancel = checkExtraPermits('52', \Auth::user()->role_id);

            //Pagination Items
            if (session('inspectionItems') == null) { $items = 10; } else { $items = session('inspectionItems'); }

            //Form Variables
            $salesId = session('inspectionSalesId');
            $statusForm = session('inspectionStatus');
            
            //Validate User Type
            if(\Auth::user()->role_id == 45){ // VEHICLE
                $userRol = true;
                $userQuery = ' products.ramoid in (7,5,40)';
            }elseif(\Auth::user()->role_id == 46){ // LIFE
                $userRol = true;
                $userQuery = ' products.ramoid in (1,2,4)';
            } else{
                $userRol = false;
                $userQuery = '';
            }

            //Customers
            $inspections = inspections($salesId, $statusForm, $items, $userRol, $userQuery);

//            $edit = true;
            return view('pagination.inspection', [
                'inspections' => $inspections,
                'items' => $items,
                'edit' => $edit,
                'cancel' => $cancel,
                'create' => $create
            ]);
        }
    }
    
    public function upload(request $request){
        $validation = Validator::make($request->all(), [
                    'fileConfirm' => "required|mimes:pdf|max:5048"
        ]);
        
        if ($validation->passes()) {
            //Inspection
            $inspection = \App\inspection::find($request['confirmId']);
            
            //Vehicle Folder
            $vehiFolder = public_path('images/inspection/').$inspection->id.'/';
            //Create Vehicle Folder
            if (!file_exists($vehiFolder)) {
                mkdir($vehiFolder, 0777, true);
            }
            
            $image = $request->file('fileConfirm');
            $new_name = rand() . '.' . $image->getClientOriginalExtension();
            
            $name = 'Images/Inspection/'.$inspection->sales_id.'/'.$new_name;
            $path = Storage::disk('s3')->put($name, file_get_contents($image));
            
            $url = Storage::disk('s3')->url($name);
            
            $inspection->file = $url;
            $now = new \DateTime();
            $inspection->date_file = $now;
            $inspection->Save();
            
            \Session::flash('inspectionUpload', 'El archivo fue subido de manera exitosa.');
            
            return response()->json([
                        'message' => 'Image Upload Successfully',
                        'uploaded_image' => '<a href="'.$inspection->file.'" target="_blank"><img src="' . $inspection->file . '" class="img-thumbnail" width="300" /></a>',
                        'class_name' => 'alert-success',
                        'vSalId' => $request->confirmId,
                        'Success' => 'true'
            ]);
        } else {
            return response()->json([
                        'message' => 'El documento debe ser en formato PDF y pesar un maximo de 5mb.',
                        'uploaded_image' => '',
                        'class_name' => 'alert-danger',
                        'vSalId' => $request->confirmId,
                        'Success' => 'false'
            ]);
        }
    }
    
    public function confirm(request $request){
        $inspection = \App\inspection::find($request['inspectionId']);
        if($inspection->file == null){
            $returnData = [
                'success' => 'false',
                'msg' => 'Debe subir el archivo de inspeccion.'
            ];
            return $returnData;
        }
        $ramo = findRamo($inspection->sales_id);
        $sales = \App\sales::find($inspection->sales_id);
        if($ramo == 1 || $ramo == 2){
            if($sales->viamatica_id == null){
                $returnData = [
                    'success' => 'false',
                    'msg' => 'El usuario no ha firmado el formulario.'
                ];
                return $returnData;
            }
        }
        $inspection->date_status = $request['date'];
        $inspection->status_id = $request['status'];
        $inspection->save();
        
        $date = date_create($request['date']);
        $beginDate = date_format($date, 'Y-m-d H:i:s');
        $converted = \DateTime::createFromFormat("Y-m-d H:i:s", $beginDate); 
        $endDate = $converted->add(new \DateInterval("P1Y"));
        
        if($ramo == 7){
            //Find if it has other inspections pending
            $inspectionSearch = \App\inspection::where('sales_id','=',$inspection->sales_id)
                                                ->where('date_status','=',null)
                                                ->get();
            if(!$inspectionSearch->isEmpty()){
                //IT HAS INSPECTIONS PENDINGS
                \Session::flash('inspectionUpload', 'La inspeccion fue actualizada de manera exitosa.');
        
                $returnData = [
                        'success' => 'true',
                        'msg' => ''
                    ];
                return $returnData;
            }else {
                //ALL INSPECTIONS ARE DONE
                $inspectionSearch = \App\inspection::selectRaw('inspection.status_id')->where('sales_id', '=', $inspection->sales_id)->get();
                foreach ($inspectionSearch as $ins) {
                    if ($ins->status_id == 28) {
                        $sale = \App\sales::find($inspection->sales_id);
                        $sale->status_id = 28;
                        $sale->begin_date = $beginDate;
                        $sale->end_date = $endDate;
                        $sale->save();
                        $customer = \App\customers::find($sale->customer_id);
                        
                        $user = \App\User::find($sale->user_id);
                        
                        $job = (new \App\Jobs\inspectionRejectedR1UserEmailJobs($sale->id ,$user->email,$customer->document, $inspection->id));
                        dispatch($job);
                        
                        //IT HAS INSPECTIONS PENDINGS
                        \Session::flash('inspectionUpload', 'La inspeccion fue actualizada de manera exitosa.');

                        $returnData = [
                                'success' => 'true',
                                'msg' => ''
                            ];
                        
                        return $returnData;
                        
                        break;
                    }
                }
            }
        }

        $sale = \App\sales::find($inspection->sales_id);
        $sale->status_id = $request['status'];
        $sale->begin_date = $beginDate;
        $sale->end_date = $endDate;
        $sale->save();
        $customer = \App\customers::find($sale->customer_id);
                        
        $user = \App\User::find($sale->user_id);

        $inspections =  \App\inspection::selectRaw('products.ramoid as "ramoId"')
        ->join('sales as sal', 'sal.id', '=', 'inspection.sales_id')
        ->join('products_channel', 'products_channel.id', '=', 'sal.pbc_id')
        ->join('products', 'products.id', '=', 'products_channel.product_id')
        ->where('sal.id','=',$sale->id)
        ->get();
        $ramoId = $inspections[0]->ramoId;

        switch ($ramoId) {
            case 7: // VEHICLE
                if  ($request['status'] == 27) {   // SEND EMAIL TO USER  INSPECTION ACCETED
                    $job = (new \App\Jobs\inspectionAceptedR1UserEmailJobs($sale->id ,$user->email,$customer->document, $inspection->id));
                    dispatch($job);
                } elseif  ($request['status'] == 28)  {     // SEND EMAIL TO USER  INSPECTION REJECTED
                    $job = (new \App\Jobs\inspectionRejectedR1UserEmailJobs($sale->id ,$user->email,$customer->document, $inspection->id));
                    dispatch($job);
                }  
            break;
            case 1: // LIFE
                if  ($request['status'] == 27) {   // SEND EMAIL TO USER  INSPECTION ACCETED
                    $job = (new \App\Jobs\inspectionAceptedR2UserEmailJobs($sale->id ,$user->email,$customer->document, $inspection->id));
                    dispatch($job);
                } elseif  ($request['status'] == 28)  {     // SEND EMAIL TO USER  INSPECTION REJECTED
                    $job = (new \App\Jobs\inspectionRejectedR2UserEmailJobs($sale->id ,$user->email,$customer->document, $inspection->id));
                    dispatch($job);
                }  
            break;
            case 2: // LIFE
                if  ($request['status'] == 27) {   // SEND EMAIL TO USER  INSPECTION ACCETED
                    $job = (new \App\Jobs\inspectionAceptedR2UserEmailJobs($sale->id ,$user->email,$customer->document, $inspection->id));
                    dispatch($job);
                } elseif  ($request['status'] == 28)  {     // SEND EMAIL TO USER  INSPECTION REJECTED
                    $job = (new \App\Jobs\inspectionRejectedR2UserEmailJobs($sale->id ,$user->email,$customer->document, $inspection->id));
                    dispatch($job);
                }  
            break;
            case 5: // FIRE
                if  ($request['status'] == 27) {   // SEND EMAIL TO USER  INSPECTION ACCETED
                    $job = (new \App\Jobs\inspectionAceptedR4UserEmailJobs($sale->id ,$user->email,$customer->document, $inspection->id));
                    dispatch($job);
                } elseif  ($request['status'] == 28)  {     // SEND EMAIL TO USER  INSPECTION REJECTED
                    $job = (new \App\Jobs\inspectionRejectedR4UserEmailJobs($sale->id ,$user->email,$customer->document, $inspection->id));
                    dispatch($job);
                }  
            break;
            case 40: // FIRE
                if  ($request['status'] == 27) {   // SEND EMAIL TO USER  INSPECTION ACCETED
                    $job = (new \App\Jobs\inspectionAceptedR4UserEmailJobs($sale->id ,$user->email,$customer->document, $inspection->id));
                    dispatch($job);
                } elseif  ($request['status'] == 28)  {     // SEND EMAIL TO USER  INSPECTION REJECTED
                    $job = (new \App\Jobs\inspectionRejectedR4UserEmailJobs($sale->id ,$user->email,$customer->document, $inspection->id));
                    dispatch($job);
                }  
            break;
            case 4: // AP
                if  ($request['status'] == 27) {   // SEND EMAIL TO USER  INSPECTION ACCETED
                    $job = (new \App\Jobs\inspectionAceptedR3UserEmailJobs($sale->id ,$user->email,$customer->document, $inspection->id));
                    dispatch($job);
                } elseif  ($request['status'] == 28)  {     // SEND EMAIL TO USER  INSPECTION REJECTED
                    $job = (new \App\Jobs\inspectionRejectedR3UserEmailJobs($sale->id ,$user->email,$customer->document, $inspection->id));
                    dispatch($job);
                }  
            break;
        }

        \Session::flash('inspectionUpload', 'La inspeccion fue actualizada de manera exitosa.');
        
        $returnData = [
                'success' => 'true',
                'msg' => ''
            ];
        return $returnData;
        
    }
    
    public function vehiForm(request $request){
        $vehi = \App\vehicles::selectRaw('vehicles.*, vsal.id as "vehiSalId", vsal.inspector_updated')
                                ->join('vehicles_sales as vsal','vsal.vehicule_id','=','vehicles.id')
                                ->join('inspection as ins','ins.vehicles_sales_id','=','vsal.id')
                                ->where('ins.id','=',$request['data']['id'])
                                ->get();
        
        $vehiBrand = \App\vehiclesBrands::find($vehi[0]->brand_id);
        $vehiClass = \App\vehicle_class::where('id','=',$vehi[0]->vehicles_class_id)->get();
        $vehiType = \App\vehicles_type::where('id','=',$vehi[0]->vehicles_type_id)->get();
        $vehiCountrySearch= \App\country::where('id','=',$vehi[0]->pais)->get();
        if($vehiCountrySearch->isEmpty()){
            $vehiCountry = null;
        }else{
            $vehiCountry = $vehiCountrySearch[0]->id;
        }
        $countries = \App\country::all();
        
        if($vehi[0]->inspector_updated == null){
            $disabled = '';
        }else{
            $disabled = 'disabled="disabled"';
        }
        
        if($vehi[0]->disp_seguridad == 1){
            $selectedVehiSecu1 = 'selected="true"';
            $selectedVehiSecu2 = '';
        }else if($vehi[0]->disp_seguridad == 2){
            $selectedVehiSecu1 = '';
            $selectedVehiSecu2 = 'selected="true"';
        }else{
            $selectedVehiSecu1 = '';
            $selectedVehiSecu2 = '';
        }
        $returnData = '<div class="modal-body">
            <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;">
                    <div class="wizard_activo registerForm titleDivBorderTop">
                        <span class="titleLink">Datos del Vehículo</span>
                        <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                    </div>
                    <div class="col-md-12" style="padding-top:15px;padding-bottom: 15px;">
                        <form method="POST" id="formConfirmVehicle"  style="margin-top: 25px;">
                        '.@csrf_field().'
                        <input type="hidden" id="vehiId" name="vehiId" value="'.$vehi[0]->id.'">
                        <input type="hidden" id="inspectionId" name="inspectionId" value="'.$request['data']['id'].'">
                        <input type="hidden" id="vehiSalId" name="vehiSalId" value="'.$vehi[0]->vehiSalId.'">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label id="vehicleDocument" class="registerForm" for="last_name"> RAMV</label> 
                                    <input type="text" class="form-control registerForm" name="ramv" id="ramv" tabindex="1" placeholder="RAMV" value="'.$vehi[0]->ramv.'" maxlength="7" required disabled="disabled">
                                </div>
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label id="vehicleDocument" class="registerForm" for="last_name"> Placa</label> 
                                    <input type="text" class="form-control registerForm" name="plate" id="plate" tabindex="2" placeholder="Placa" value="'.$vehi[0]->plate.'" maxlength="7" required disabled="disabled">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="last_name"> Modelo</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="model" id="model" tabindex="3" placeholder="Modelo" value="'.$vehi[0]->model.'" required disabled="disabled">
                                </div>
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="province"> Marca</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="brand" id="brand" tabindex="4" placeholder="brand" value="'.$vehiBrand->name.'" required disabled="disabled">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="use"> Uso</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select name="vehiType" class="form-control registerForm" id="vehiType" tabindex="7" required disabled="disabled">
                                        <option id="typeSelect" disabled="disabled" value="0">--Escoja Una---</option>
                                        <option id="typeSelect" selected="true" value="'.$vehiType[0]->id.'">'.$vehiType[0]->name.'</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="vehicleClass"> Tipo</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select name="vehicleClass" class="form-control registerForm" id="vehicleClass" tabindex="8" required disabled="disabled">
                                        <option id="vehicleClass" value="0">--Escoja Una---</option>
                                        <option id="vehicleClass" selected="true" value="'.$vehiClass[0]->id.'">'.$vehiClass[0]->name.'</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="registration"> Motor</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="registration" id="registration" tabindex="9" placeholder="Motor" value="'.$vehi[0]->matricula.'" required '.$disabled.'>
                                </div>
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="chassis"> Chasis</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="chassis" id="chassis" tabindex="10" placeholder="Chasis" value="'.$vehi[0]->chassis.'" required '.$disabled.'>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="last_name"> Año</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm yearVehicle" name="year" id="year" tabindex="11" placeholder="Año" value="'.$vehi[0]->year.'" max="2020" min="1" required '.$disabled.'>
                                    
                                </div>
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="last_name"> Color</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="color" id="color" tabindex="12" placeholder="Color" value="'.$vehi[0]->color.'" required '.$disabled.'>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="npassengers"> N° Pasajeros</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="npassengers" id="npassengers" tabindex="13" placeholder="Número de pasajeros" value="'.$vehi[0]->capacidad.'" required '.$disabled.'>
                                </div>
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="tonnage"> Tonelaje</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="tonnage" id="tonnage" tabindex="14" placeholder="Tonelaje" value="'.$vehi[0]->tonelaje.'" required '.$disabled.'>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="vehicleCylinder"> Cilindraje</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="vehicleCylinder" id="vehicleCylinder" tabindex="15" placeholder="Cilindraje" value="'.$vehi[0]->cilindraje.'" required '.$disabled.'>
                                </div>
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="countryOrigin"> País de Origen</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select name="country" class="form-control registerForm" id="country" tabindex="16" required '.$disabled.'>
                                        <option value="">--Escoja Una---</option>';
                                    if ($vehiCountry == null) {
                                         foreach($countries as $cou){
                                            $returnData .= '<option value="'.$cou->id.'">'.$cou->name.'</option>';
                                         }
                                    }else{
                                         foreach($countries as $cou){
                                             if($cou->id == $vehiCountry){
                                                $returnData .= '<option selected="true" value="'.$cou->id.'">'.$cou->name.'</option>';
                                             }else{
                                                $returnData .= '<option value="'.$cou->id.'">'.$cou->name.'</option>';
                                             }
                                         }
                                    }
                       $returnData .='</select>                               
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="vehicleSecurity"> Dispositivo de Seguridad</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select name="vehicleSecurity" class="form-control registerForm" id="vehicleSecurity" tabindex="17" required '.$disabled.'>
                                        <option value="">--Escoja Una---</option>
                                        <option value="1" '.$selectedVehiSecu1.'>SI</option>
                                        <option value="2" '.$selectedVehiSecu1.'>NO</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                </div>
                <div class="modal-footer">
                    <button id="modalCancelCloseBtn" type="button" class="btn btn-default registerForm" data-dismiss="modal" style="float:left">Cerrar</button>
                    <button type="submit" class="btn btn-info registerForm" style="float:right" onclick="modalConfirmVehiBtn()" '.$disabled.'>Confirmar</button>
                </div>';
        
        return $returnData;
    }
    
    public function vehiUpdate(request $request){
        $vehi = \App\vehicles::find($request['vehiId']);
        $vehi->matricula = $request['registration']; 
        $vehi->chassis = $request['chassis']; 
        $vehi->year = $request['year']; 
        $vehi->color = $request['color']; 
        $vehi->capacidad = $request['npassengers']; 
        $vehi->tonelaje = $request['tonnage']; 
        $vehi->cilindraje = $request['vehicleCylinder']; 
        $vehi->pais = $request['country']; 
        $vehi->pais = $request['country']; 
        $vehi->disp_seguridad = $request['vehicleSecurity']; 
        $vehi->save();
        
        $vsal = \App\vehicles_sales::find($request['vehiSalId']);
        $vsal->inspector_updated = new \DateTime();
        $vsal->save();
        
        $inspection = \App\inspection::find($request['inspectionId']);
        $inspection->inspector_updated = new \DateTime();
        $inspection->save();
        
        return 'true';
    }

    public function dateInspection($id) {

        $provinces = DB::select('select DATE_FORMAT(created_at , "%Y-%m-%d") as created_at from inspection where id = ?', [$id]);
        return $provinces;

    }
    
    public function pdfR2($salId){
        $sale = \App\sales::find($salId);
        
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
            'enfer' => $muerte
        ]);
        
        return $pdf->stream('formulario.pdf');
    }

}
