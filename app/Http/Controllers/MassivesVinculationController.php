<?php

namespace App\Http\Controllers;

use App\rc;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
Use Redirect;
use Barryvdh\DomPDF\Facade as PDF;
use Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Carbon;
use DataTables;
use Illuminate\Pagination\Paginator;
use Validator;
use Illuminate\Contracts\Encryption\DecryptException;
use Underscore\Parse;
use ArieTimmerman\Laravel\URLShortener\URLShortener;
use App\Mail\paymentSendLinkUserEmail;
use App\Mail\beneficiariesRequestSendLinkEmail;
use App\Mail\insuranceRequestSendLinkEmail;
use Illuminate\Support\Facades\Storage;
use Rap2hpoutre\FastExcel\FastExcel;

class MassivesVinculationController extends Controller{
public function __construct() {
    $this->middleware('auth');
    $this->middleware('validateUserRoute');
}
public function index(request $request) {
//    $collection = (new FastExcel)->import(public_path('ramos.xlsx'));
//    foreach($collection as $c){
//        $pro = new \App\products();
//        $pro->status_id = 2;
//        $pro->segment = $c['RAMODES'];
//        $pro->productodes = $c['RAMODES'];
//        $pro->productoid = 325;
//        $pro->ramodes = $c['RAMODES'];
//        $pro->ramoid = $c['RAMOID'];
//        $pro->agency_id = 32;
//        $pro->canalplanid = 1;
//        $pro->save();
//        
//        $pbc = new \App\product_channel();
//        $pbc->channel_id = 19;
//        $pbc->product_id = $pro->id;
//        $pbc->status_id = 2;
//        $pbc->agency_id = 32;
//        $pbc->canal_plan_id = 1;
//        $pbc->agency_ss_id = 1;
//        $pbc->agent_ss = 1;
//        $pbc->ejecutivo_ss = 'DIEGO ERAZO';
//        $pbc->ejecutivo_ss_email = 'diego.erazo@segurossucre.fin.ec';
//        $pbc->save();
//    }
    //Update Sale status to ID 37
  \App\sales::where('sales_type_id',7)
            ->whereIn('status_id',[22])
            ->update(['status_id' => 25]);
  \App\sales::where('sales_type_id',7)
            ->whereIn('status_id',[20,36])
            ->update(['status_id' => 34]);
  
    //Update Sale status to ID 34
    \App\sales::where('sales_type_id',7)
            ->whereIn('status_id',[20,36])
            ->update(['status_id' => 34]);
  
    $viewPermit = checkViewPermit('68', \Auth::user()->role_id);
    if (!$viewPermit) {
        \Session::flash('ValidateUserRoute', 'No tiene acceso al modulo de vinculación Individual.');
        return view('home');
    }

    //Obtain Create Permission
    $create = checkExtraPermits('70', \Auth::user()->role_id);

    //Obtain Edit Permission
    $edit = checkExtraPermits('72', \Auth::user()->role_id);


    //Obtain Cancel Permission
    $cancel = checkExtraPermits('73', \Auth::user()->role_id);

    //Obtain Sales Status
    $status = \App\status::find([23, 31, 37]);

    //Obtain Sales Movements
    $salesMovements = \App\sales_movements::all();

    //Obtain Users
    if (\Auth::user()->role_id == '6') {
        $userQuery = 'select * from users where role_id in (6)';
    } elseif (\Auth::user()->role_id == '4') {
        $userQuery = 'select usr.* from users  usr join agencies agen on agen.id = usr.agen_id join channels chan on chan.id = agen.channel_id where usr.role_id in (1,6,3,4,2) and chan.id = ' . $channel[0]->id;
    } else {
        $userQuery = 'select * from users where role_id in (1,6,3,4,2)';
    }
    $users = DB::select($userQuery);

    //Store Form Variables in Session
    if ($request->isMethod('post')) {
        session(['massivesVinculationPolicyNumber' => $request->policy_number]);
        session(['massivesVinculationCustomer' => $request->customer]);
        session(['massivesVinculationDocument' => $request->document]);
        session(['massivesVinculationPlate' => $request->plate]);
        session(['massivesVinculationDateFrom' => $request->dateFrom]);
        session(['massivesVinculationDateUntil' => $request->dateUntil]);
        session(['massivesVinculationUpdateDateFrom' => $request->updateDateFrom]);
        session(['massivesVinculationUpdateDateUntil' => $request->updateDateUntil]);
        session(['massivesVinculationSaleId' => $request->saleId]);
        session(['massivesVinculationAdviser' => $request->adviser]);
        session(['massivesVinculationChannel' => $request->channel]);
        session(['massivesVinculationNameBusiness' => $request->nameBusiness]);
        session(['massivesVinculationStatus' => $request->status]);
        session(['massivesVinculationItems' => $request->items]);
        session(['massivesVinculationMovements' => $request->movement]);
        $currentPage = 1;
        session(['massivesVinculationMovementsPage' => 1]);
    } else {
        $currentPage = session('massivesVinculationMovementsPage');
    }

    //Pagination Items
    if (session('massivesVinculationItems') == null) {
        $items = 10;
    } else {
        $items = session('massivesVinculationItems');
    }

    //Form Variables
    $customer = session('massivesVinculationCustomer');
    $document = session('massivesVinculationDocument');
    $plate = session('massivesVinculationPlate');
    $dateFrom = session('massivesVinculationDateFrom');
    $dateUntil = session('massivesVinculationDateUntil');
    $updateDateFrom = session('massivesVinculationUpdateDateFrom');
    $updateDateUntil = session('massivesVinculationUpdateDateUntil');
    $saleId = session('massivesVinculationSaleId');
    $adviser = session('massivesVinculationAdviser');
    $statusForm = session('massivesVinculationStatus');
    $channelForm=session('massivesVinculationChannel');
    $nameBusiness=session('massivesVinculationNameBusiness');
    $salesMovementsForm = session('massivesVinculationMovements');

    //Validate User Role
    $userRol = null;
    $userQueryForm = '';

    //Validate User Type Sucre
    $rol = \App\rols::find(\Auth::user()->role_id);
    
    //ROL SEGUROS SUCRE
    if ($rol->rol_entity_id == 1) {
        //ROL TIPO GERENCIA/EJECUTIVO
        if($rol->rol_type_id == 1 || $rol->rol_type_id == 2){
            $userSucre = null;
            $userSucreQuery = '';
        }else{
        // ROL TIPO EJECUTIVO
            $userSucre = true;
            $userSucreQuery = ' products_channel.ejecutivo_ss_email = "'.\Auth::user()->email.'"';
        }
    }
    //ROL CANAL
    if($rol->rol_entity_id == 2){
        //ROL TIPO GERENCIA
        if($rol->rol_type_id == 1){
            $userSucre = true;
            $userSucreQuery = 'channels.id = ' . $channel[0]->id;
        }elseif($rol->rol_type_id == 2){
        // ROL TIPO JEFATURA
            $userSucre = true;
            $userSucreQuery = ' agencies.id = "'.\Auth::user()->agen_id.'"';
        }else{
        // ROL TIPO EJECUTIVO
            $userSucre = true;
            $userSucreQuery = ' sales.user_id = "'.\Auth::user()->id.'"';
        }
    }

    // Make sure that you call the static method currentPageResolver()
    Paginator::currentPageResolver(function () use ($currentPage) {
        return $currentPage;
    });

    //New Sales
    $newSales = massivesVinculation( $customer, $document, $plate, $dateFrom, $dateUntil, $updateDateFrom, $updateDateUntil,$saleId, $adviser, $statusForm, $userRol, $userQueryForm, $salesMovementsForm, $items, $userSucre, $userSucreQuery,$channelForm,$nameBusiness);
    
    $countries = \App\country::all();
    $documents = \App\document::all();
    $channels = \App\channels::where('canalnegodes','!=','')->where('canalnegodes','!=',null)->orderBy('canalnegodes','asc')->get();
    $agent = \App\agent_ss::where('agentedes','!=','')->where('agentedes','!=',null)->orderBY('agentedes','asc')->get();
    
    return view('massivesVinculation.index', [
        'sales' => $newSales,
        'status' => $status,
        'users' => $users,
        'items' => $items,
        'edit' => $edit,
        'cancel' => $cancel,
        'create' => $create,
        'salesMovements' => $salesMovements,
        'countries' => $countries,
        'documents' => $documents,
        'channel' => $channels,
        'agent' => $agent
    ]);
}
public function vinculationForm($id){
    $saleId = decrypt($id);
    $sales = \App\sales::find($saleId);
    $vinculation = \App\vinculation_form::where('sales_id','=',$saleId)->get();
    $customer = \App\customers::find($sales->customer_id);
    $countries = \App\country::all();
    $documents = \App\document::all();
    return view('massivesVinculation.massivesVinculationForm',[
        'sales' => $sales,
        'customer' => $customer,
        'countries' => $countries,
        'vinculation' => $vinculation[0],
        'documents' => $documents
    ]);
}

public function legalPersonVinculationForm($id){

    $saleId = decrypt($id);
    $sales = \App\sales::find($saleId);
    $vinculation = \App\vinculation_form::where('sales_id','=',$saleId)->get();
    $legalRepresentative= \App\customerLegalRepresentative::find($sales->customer_legal_representative_id);
    $company=\App\companys::find($sales->company_id);
    $countries = \App\country::all();
    $documents = \App\document::all();
    return view('massivesVinculation.legalPerson.legalPersonForm',[
        'sales' => $sales,
        'company' => $company,
        'legalRepresentative' => $legalRepresentative,
        'countries' => $countries,
        'vinculation' => $vinculation[0],
        'documents' => $documents
    ]);
}
public function validateListaObservados(request $request){
    $sales = \App\sales::find($request['saleId']);
    if($sales->status_id == 24 || $sales->status_id == 31 || $sales->status_id == 32 || $sales->status_id == 33){
        \Session::flash('errorMessage', ' El proceso de venta no puede continuar, para información adicional por favor contactar a tu ejecutivo comercial.');

        return 'true';
    }else{
        return 'false';
    }
}
function fetch_data(Request $request) {
    if ($request->ajax()) {
        //Page
        session(['massivesVinculationMovementsPage' => $request->page]);

        //Obtain Edit Permission
        $edit = checkExtraPermits('72', \Auth::user()->role_id);

        //Obtain Create Permission
        $create = checkExtraPermits('70', \Auth::user()->role_id);

        //Obtain Cancel Permission
        $cancel = checkExtraPermits('73', \Auth::user()->role_id);

        //Obtain Channel
        $channelQuery = 'select cha.* from channels cha join agencies agen on agen.channel_id = cha.id where agen.id =  "' . \Auth::user()->agen_id . '"';
        $channel = DB::select($channelQuery);


        //Pagination Items
        if (session('massivesVinculationMovementsItems') == null) {
            $items = 10;
        } else {
            $items = session('massivesVinculationMovementsItems');
        }

        //Form Variables
        $customer = session('massivesVinculationCustomer');
        $document = session('massivesVinculationDocument');
        $plate = session('massivesVinculationPlate');
        $dateFrom = session('massivesVinculationDateFrom');
        $dateUntil = session('massivesVinculationDateUntil');
        $updateDateFrom = session('massivesVinculationUpdateDateFrom');
        $updateDateUntil = session('massivesVinculationUpdateDateUntil');
        $saleId = session('massivesVinculationSaleId');
        $adviser = session('massivesVinculationAdviser');
        $statusForm = session('massivesVinculationStatus');
        $channelForm=session('massivesVinculationChannel');
        $nameBusiness=session('massivesVinculationNameBusiness');
        $salesMovementsForm = session('massivesVinculationMovements');

        $userRol = null;
        $userQueryForm = '';
        //Validate User Type Sucre
        $rol = \App\rols::find(\Auth::user()->role_id);

        //ROL SEGUROS SUCRE
        if ($rol->rol_entity_id == 1) {
            //ROL TIPO GERENCIA/EJECUTIVO
            if ($rol->rol_type_id == 1 || $rol->rol_type_id == 2) {
                $userSucre = null;
                $userSucreQuery = '';
            } else {
                // ROL TIPO EJECUTIVO
                $userSucre = true;
                $userSucreQuery = ' products_channel.ejecutivo_ss_email = "' . \Auth::user()->email . '"';
            }
        }
        //ROL CANAL
        if ($rol->rol_entity_id == 2) {
            //ROL TIPO GERENCIA
            if ($rol->rol_type_id == 1) {
                $userSucre = true;
                $userSucreQuery = 'channels.id = ' . $channel[0]->id;
            } elseif ($rol->rol_type_id == 2) {
                // ROL TIPO JEFATURA
                $userSucre = true;
                $userSucreQuery = ' agencies.id = "' . \Auth::user()->agen_id . '"';
            } else {
                // ROL TIPO EJECUTIVO
                $userSucre = true;
                $userSucreQuery = ' sales.user_id = "' . \Auth::user()->id . '"';
            }
        }
        //New Sales
        $newSales = massivesVinculation($customer, $document, $plate, $dateFrom, $dateUntil, $updateDateFrom, $updateDateUntil, $saleId, $adviser, $statusForm, $userRol, $userQueryForm, $salesMovementsForm, $items, $userSucre, $userSucreQuery,$channelForm,$nameBusiness);
    
        return view('pagination.massivesVinculation', [
            'sales' => $newSales,
            'items' => $items,
            'edit' => $edit,
            'cancel' => $cancel,
            'create' => $create
        ]);
    }
}

public function create() {
    //Validate Create Permission
    $create = checkExtraPermits('70', \Auth::user()->role_id);
    if (!$create) {
        \Session::flash('ValidateUserRoute', 'No tiene acceso a crear nuevas vinculaciones.');
        return view('home');
    }
    
    //Obtain Create Permission
    $agency_id=\Auth::user()->agen_id;
    $channel=\App\Agency::find($agency_id);
    $customer = new \App\customers();
    $disabled = null;
    $documents = DB::select('select * from documents where id in (1,3)');
    $emissionTypes = DB::select('select * from emission_type where id in (1,2)');
    $sale_movement = 1;
    $branch = \App\product_channel::selectRaw('products.ramodes')                         
                            ->join('products', 'products.id', '=', 'products_channel.product_id')
                            ->where('products_channel.agency_id', '=',$agency_id)
                            ->where('products_channel.channel_id', '=',$channel->channel_id)
                            ->groupBy('products.ramodes')
                            ->get();

    return view('massivesVinculation.create', [
        'documents' => $documents,
        'customer' => $customer,
        'disabled' => $disabled,
        'emissionTypes'=>$emissionTypes,
        'sale_movement' => $sale_movement,
        'sale_id' => null,
        'branch' => $branch
    ]);
}
public function createLegalPerson() {
    //Validate Create Permission
    $create = checkExtraPermits('70', \Auth::user()->role_id);
    if (!$create) {
        \Session::flash('ValidateUserRoute', 'No tiene acceso a crear nuevas cotizaciones.');
        return view('home');
    }
    
    //Obtain Create Permission
    $agency_id=\Auth::user()->agen_id;
    $channel=\App\Agency::find($agency_id);
    $customer = new \App\customers();
    $disabled = null;
    $documents = DB::select('select * from documents where id in (1,3)');
    $emissionTypes = DB::select('select * from emission_type where id in (1,2)');
    $sale_movement = 1;
     $branch = \App\product_channel::selectRaw('products.ramodes')                         
                            ->join('products', 'products.id', '=', 'products_channel.product_id')
                            ->where('products_channel.agency_id', '=',$agency_id)
                            ->where('products_channel.channel_id', '=',$channel->channel_id)
                            ->groupBy('products.ramodes')
                            ->get();

    return view('massivesVinculation.legalPerson.create', [
        'documents' => $documents,
        'customer' => $customer,
        'disabled' => $disabled,
        'emissionTypes'=>$emissionTypes,
        'sale_movement' => $sale_movement,
        'sale_id' => null,
        'branch' => $branch
    ]);
}



public function store(request $request) {
    set_time_limit(120);
    //Save or Update Customer
    $customerSql = 'select * from customers where document = "'. $request['data']['documentNumber'] . '"';
    $customer = DB::select($customerSql);

    //Validate Customer Save or Update
    if ($customer) {
        $customerUpdate = \App\customers::find($customer[0]->id);
        $customerUpdate->last_name = $request['data']['last_name'];
        $customerUpdate->second_last_name = $request['data']['second_last_name'];
        $customerUpdate->mobile_phone = $request['data']['mobile_phone'];
        $customerUpdate->email = $request['data']['email'];
        $customerUpdate->save();
        $customerId = $customerUpdate->id;
        $customerPhone = substr($customerUpdate->mobile_phone, 1);
        $customerEmail = $customerUpdate->email;
        $customerDocument = $customerUpdate->document;
        $customerSearch = \App\customers::find($customerUpdate->id);
    } else {
        $customerNew = new \App\customers();
        $customerNew->first_name = $request['data']['first_name'];
        $customerNew->second_name = $request['data']['second_name'];
        $customerNew->last_name = $request['data']['last_name'];
        $customerNew->second_last_name = $request['data']['second_last_name'];
        $customerNew->document = $request['data']['documentNumber'];
        $customerNew->document_id = $request['data']['document_id'];
        $customerNew->mobile_phone = $request['data']['mobile_phone'];
        $customerNew->email = $request['data']['email'];
        $customerNew->status_id = 1;
        $customerNew->save();
        $customerId = $customerNew->id;
        $customerPhone = substr($customerNew->mobile_phone, 1);
        $customerEmail = $customerNew->email;
        $customerDocument = $customerNew->document;
        $customerSearch = \App\customers::find($customerNew->id);
    }
       //Form variables
       $now = new \DateTime();
       $branch = $request['data']['branch'];
       $emissionType = $request['data']['emissionType'];
       $insuredValue = $request['data']['insuredValue'];
       $netPremium = $request['data']['netPremium'];
       $product=$request['data']['product'];
       //Products by Channel
//       $pbc = DB::table('products_channel')->latest('created_at')->first();
       
       //GET PBD ID BY RAMO
       $agency_id=\Auth::user()->agen_id;
       $pbc = \App\product_channel::where('products_channel.product_id','=',$product)
                                    ->where('products_channel.agency_id','=',$agency_id)
                                    ->get();        

       //Store new SALES
       $massivesVinculationNew= new \App\sales();
       $massivesVinculationNew->agen_id = $agency_id;
       $massivesVinculationNew->user_id = \Auth::user()->id;
       $massivesVinculationNew->pbc_id = $pbc[0]->id;
       $massivesVinculationNew->customer_id = $customerId;
       $massivesVinculationNew->status_id = 23;
       $massivesVinculationNew->emission_date = $now;
       $massivesVinculationNew->sales_type_id = 7;
       $massivesVinculationNew->branch = $branch;
       $massivesVinculationNew->emission_type_id = $emissionType;
       $massivesVinculationNew->insured_value = str_replace(',','',$insuredValue);
       $massivesVinculationNew->net_premium = str_replace(',','',$netPremium);
       $massivesVinculationNew->total = str_replace(',','',$netPremium);
       $massivesVinculationNew->insured_value = str_replace(',','',$insuredValue);
       $massivesVinculationNew->save();
       
        //Vinculation_form
        $vinculation = new \App\vinculation_form();
        $vinculation->customer_id = $customerId;
        $vinculation->sales_id = $massivesVinculationNew->id;
        $vinculation->status_id = 6;
        $vinculation->mobile_phone = $request['data']['mobile_phone'];
        $vinculation->email = $request['data']['email'];
        $vinculation->save();
        
        \Session::flash('SendEmailMessage', '  Se envío el link del Formulario a su correo');
        //Send Link Vinculation Form Email
        $job = (new \App\Jobs\VinculationSendLinkEmailJobs($massivesVinculationNew->id, $customerEmail, $customerDocument));
        dispatch($job);
}

public function storeLegalPerson(request $request) {
    set_time_limit(120);
    //Save or Update Company
    $companySql = 'select * from companys where document = "'. $request['data']['documentCompany'] . '"';
    $company = DB::select($companySql);
    
       $businessName = $request['data']['business_name'];
       $tradename = $request['data']['tradename'];
    //Validate Customer Save or Update
    if ($company) {
        $companyUpdate = \App\companys::find($company[0]->id);
        $companyUpdate->business_name  = $request['data']['business_name'];
        $companyUpdate->tradename = $request['data']['tradename'];
        $companyUpdate->save();
        $companyId = $companyUpdate->id;
        $companyDocument = $companyUpdate->document;
    } else {
        $companyNew = new \App\companys();
        $companyNew->document_id = $request['data']['documentCompanyid'];
        $companyNew->document= $request['data']['documentCompany'];
        $companyNew->business_name =str_replace('.','',$businessName);
        $companyNew->tradename = str_replace('.','',$tradename );
        $companyNew->save();
        $companyId = $companyNew->id;
        $companyDocument=$companyNew->document;
    }
    //Save or Update Legal Representative
    $legalRepresentativeSql = 'select * from customer_legal_representative where document = "'. $request['data']['documentNumber'] . '"';
    $legalRepresentative = DB::select($legalRepresentativeSql);
    if($legalRepresentative){
        $legalRepresentativeUpdate = \App\customerLegalRepresentative::find($legalRepresentative[0]->id);
        $legalRepresentativeUpdate->second_name=$request['data']['second_name'];
        $legalRepresentativeUpdate->second_last_name=$request['data']['second_last_name'];
        $legalRepresentativeUpdate->mobile_phone=$request['data']['mobile_phone'];
        $legalRepresentativeUpdate->email=$request['data']['email'];
        $legalRepresentativeUpdate->save();
        $legalRepresentativeId = $legalRepresentativeUpdate->id;
        $legalRepresentativePhone = substr($legalRepresentativeUpdate->mobile_phone, 1);
        $legalRepresentativeEmail = $legalRepresentativeUpdate->email;
        $legalRepresentativeDocument = $legalRepresentativeUpdate->document;
    }else{
        $legalRepresentativeNew= new \App\customerLegalRepresentative();
        $legalRepresentativeNew->document=$request['data']['documentNumber'];
        $legalRepresentativeNew->document_id=$request['data']['document_id'];
        $legalRepresentativeNew->first_name=$request['data']['first_name'];
        $legalRepresentativeNew->second_name=$request['data']['second_name'];
        $legalRepresentativeNew->last_name=$request['data']['last_name'];
        $legalRepresentativeNew->second_last_name=$request['data']['second_last_name'];
        $legalRepresentativeNew->mobile_phone=$request['data']['mobile_phone'];
        $legalRepresentativeNew->email=$request['data']['email'];
        $legalRepresentativeNew->save();
        $legalRepresentativeId = $legalRepresentativeNew->id;        
        $legalRepresentativePhone = substr($legalRepresentativeNew->mobile_phone, 1);
        $legalRepresentativeEmail = $legalRepresentativeNew->email;
        $legalRepresentativeDocument = $legalRepresentativeNew->document;
    }
       //Form variables
       $now = new \DateTime();
       $branch = $request['data']['branch'];
       $emissionType = $request['data']['emissionType'];
       $insuredValue = $request['data']['insuredValue'];
       $netPremium = $request['data']['netPremium'];
       $product=$request['data']['product'];
       //Products by Channel
//       $pbc = DB::table('products_channel')->latest('created_at')->first();
       
       //GET PBD ID BY RAMO
       $agency_id=\Auth::user()->agen_id;
       $pbc = \App\product_channel::where('products_channel.product_id','=',$product)
                                    ->where('products_channel.agency_id','=',$agency_id)
                                    ->get();   

       //Store new SALES
       $massivesVinculationLegalPersonNew= new \App\sales();
       $massivesVinculationLegalPersonNew->agen_id = $agency_id;
       $massivesVinculationLegalPersonNew->user_id = \Auth::user()->id;
       $massivesVinculationLegalPersonNew->company_id = $companyId;
       $massivesVinculationLegalPersonNew->customer_legal_representative_id= $legalRepresentativeId;
       $massivesVinculationLegalPersonNew->pbc_id = $pbc[0]->id;
       $massivesVinculationLegalPersonNew->status_id = 23;
       $massivesVinculationLegalPersonNew->emission_date = $now;
       $massivesVinculationLegalPersonNew->sales_type_id = 7;
       $massivesVinculationLegalPersonNew->branch = $branch;
       $massivesVinculationLegalPersonNew->emission_type_id = $emissionType;
       $massivesVinculationLegalPersonNew->insured_value = str_replace(',','',$insuredValue);
       $massivesVinculationLegalPersonNew->net_premium = str_replace(',','',$netPremium);
       $massivesVinculationLegalPersonNew->total = str_replace(',','',$netPremium);
       $massivesVinculationLegalPersonNew->insured_value = str_replace(',','',$insuredValue);
       $massivesVinculationLegalPersonNew->save();
       
        //Vinculation_form
        $vinculationLegalPerson = new \App\vinculation_form();
        $vinculationLegalPerson->company_id = $companyId;
        $vinculationLegalPerson->customer_legal_representative_id = $legalRepresentativeId;
        $vinculationLegalPerson->sales_id = $massivesVinculationLegalPersonNew->id;
        $vinculationLegalPerson->status_id = 6;
        $vinculationLegalPerson->mobile_phone = $request['data']['mobile_phone'];
        $vinculationLegalPerson->email = $request['data']['email'];
        $vinculationLegalPerson->save();
        
        \Session::flash('SendEmailMessage', '  Se envío el link del Formulario a su correo');
        //Send Link Vinculation Form Email
       $job = (new \App\Jobs\VinculationLegalPersonSendLinkEmailJobs($massivesVinculationLegalPersonNew->id, $legalRepresentativeEmail, $legalRepresentativeDocument,$companyDocument));
       dispatch($job);
}

public function validateDocument(request $request) {
    //        return $request['data']['document'];
            if (!validateId($request['data']['document'])) {
                return 'invalid';
            } else {
                return 'valid';
            }
        }

public function selectProduct(request $request){
    $agency_id=\Auth::user()->agen_id; 
    $channel=\App\Agency::find($agency_id);
    // DB::enableQueryLog();   
    $search = \App\products::selectRaw('products.productodes,products.id')
                                    ->join('products_channel','products.id','=','products_channel.product_id')
                                    ->where('products.ramodes','=',$request['branch'])
                                    ->where('products.segment','=',$request['branch'])
                                    ->where('products_channel.agency_id','=',$agency_id)
                                    ->where('products_channel.channel_id', '=',$channel->channel_id)
                                    ->groupBy('products.productodes')
                                    ->get();   
// dd(DB::getQueryLog());                                
    $response = '<option value="">--Escoja Una--</option>';
    foreach($search as $s){
        if($s->productodes!=null){
          $response .= '<option value="'.$s->id.'">'.$s->productodes.'</option>';
        }
       }
         return $response;
    } 
}
