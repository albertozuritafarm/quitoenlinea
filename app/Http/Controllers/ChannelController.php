<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Mail;
use App\Mail\QuotationR1Email;
class ChannelController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    
    public function index(request $request){
        //$result = uploadFilesSftpSS(2);
    //    return $result;
//        $result = new QuotationR1Email('1444', '1757612005');
//        return $result;   
//        $data = encrypt('1757612005');
//        return $data;
//        $result = tokenSS();
//        $result = customerSS('R', '1790010937001');
//        $result = customerSS('C', '1757612005');
//        $result = vehicleSS('PDF2489');
//        $result = vehicleSS('PDF4715');
//        $result = vehicleSS('PCP6456');
//        $result = vehicleSS('T02107880');
//        $result = vehicleSS('G01508951');
//        $result = vehicleSS('PCT9921');
//        $result = vehicleSS('PAC6183');
//        $result = vehicleSS('PCY8893');
//        $result = vehicleSS('PCB4221');
//       $result = productChannelSS();
//        $result = agenteSS();
//        $result = valorAseguradoSS('1756363667');
//        return $result;
//        dd($resultListaObservados);
//        $sftpPagosLog = \App\sftp_pagos_log::count();
//        $sftpPagosLog = \DB::table('sftp_pagos_log')->orderBy('created_at','asc')->get();
//        $result = calculoPrimaSS('189', '01/05/2020', '01/05/2021', '5', 'AUTOMÓVIL', '20000');
//        $result = calculoPrimaR2('149', '27/07/2020', '27/07/2021');
//        $result = calculoPrimaSS('238', '01/05/2020', '01/05/2021', '14', 'FURGONETA', '10000');
//        $result = calculoPrimaSS('238', '01/05/2020', '01/05/2021', '14', 'TAXI', '10000');
//        $result = calculoPrimaR4('266', '79', '70000.00', '01/05/2020', '01/05/2021');
//        $result = tarifarioVehiculoSS();
//        $result = devolucionPOlizaEmitidaSS('1234', '7');
//       $result = vinculaClienteeSS(1512);
//       return $result;
//        $result = montoAseguradoSS('1757612005');
//           $result = contratanteSS('0100998269');
//        $result = sftpPaymentsReceiveSS();
//           return $result;
//           dd($result['infocontratante']['clientedesde']);
           //return $result;
//        $result = uploadFiles(2);
//        $version = 1 + $sftpPagosLog;
//        return $sftpPagosLog;
//        if($result['error'][0]['code'] == 003){
//            return 'esta bien';
//        }else{
//            return 'esta mal';
//        }
//        $result = carteraVencidaSS('1757612005');
//        $result = vinculaClienteeSS(1443);
//        return $result;
//        die();
//        dd($result);
//        \App\Jobs\listaObservadosyCarteraJobs::dispatch(189,113, 1462,'carlos.oberto88@gmail.com');
//        return 'hola';
//        $email = new \App\Mail\QuotationR2Email(1319, 1757612005);
//        Mail::to('carlos.oberto88@gmail.com')->send($email);
//        dd('hola');
//        $result = devolucionPOlizaEmitidaSS(1592, 7);
//        $result = sftpPaymentsSS();
//       $result = documentosPolizaSS(1936);
//       $result = emisionSS(1476);
//       return $result;
//        $result = costoSeguroSS(40, 246.98);
//        return $result;
        //Check if the vehicle has other sales.
//                $otherVehi = \App\vehicles::selectRaw('vehicles.plate')
//                                ->join('vehicles_sales as vsal','vsal.vehicule_id','=','vehicles.id')
//                                ->where('vsal.sales_id','=',1400)
//                                ->get();
//                foreach($otherVehi as $vehi){
//                    $otherSale = \App\vehicles::selectRaw('vsal.sales_id')
//                                ->join('vehicles_sales as vsal','vsal.vehicule_id','=','vehicles.id')
//                                ->join('vehicles as vehi','vehi.id','=','vsal.vehicule_id')
//                                ->where('vsal.sales_id','!=',1400)
//                                ->where('vehi.plate','=',$vehi->plate)
//                                ->get();
//                    foreach($otherSale as $sal){
//                        $sales = \App\sales::find($sal->sales_id);
//                        $sales->status_id = 4;
//                        $sales->save();
//                    }
//                }
//                dd('hola');
//$job = (new \App\Jobs\inspectionAceptedR1UserEmailJobs(1470 , 'carlos.oberto88@gmail.com', 1757612005, 96));
//                    dispatch($job);
//                    dd('hola');
        
        
//        $bienAsegurado = \App\properties::selectRaw('properties.main_street, properties.secondary_street, properties.number, properties.office_department, cit.name, rub.description, proRub.value')
//                                                    ->join('properties_rubros as proRub','proRub.property_id','=','properties.id')
//                                                    ->join('cities as cit','cit.id','=','properties.city_id')
//                                                    ->join('products_rubros as rub','rub.cod','=','proRub.rubros_cod')
//                                                    ->where('properties.sales_id','=','1476')
//                                                    ->get();
//        return $bienAsegurado[0];
//        DB::enableQueryLog(); 
//        $products = \App\products::selectRaw('DISTINCT(products.productoid), products.id, products.productodes as "name"')->where('ramoid', '=', 5)->groupBy('products.productoid')->get();
//        dd(DB::getQueryLog());
        
//        $result = sftpPaymentsSS();
//        return $result;
        
        //Validate if User has view Permit
        $viewPermit = checkViewPermit('58', \Auth::user()->role_id);
        if(!$viewPermit){
            \Session::flash('ValidateUserRoute', 'No tiene acceso al modulo de Canales.');
            return view('home');
        }
        
        //Obtain Edit Permission
        $edit = checkExtraPermits('47',\Auth::user()->role_id);
        
        //Obtain Create Permission
        $create = checkExtraPermits('49',\Auth::user()->role_id);
        
        //Obtain Cancel Permission
        $cancel = checkExtraPermits('48',\Auth::user()->role_id);
        
        //Create Agency
        $createAgency = checkExtraPermits('49',\Auth::user()->role_id);
        
        //Obtain Channel
        $channelQuery = 'select cha.* from channels cha join agencies agen on agen.channel_id = cha.id where agen.id =  "' . \Auth::user()->agen_id . '"';
        $channel = DB::select($channelQuery);
        
        //Validate User Role
        if (\Auth::user()->role_id == 4) { $userRol = true; $userQueryForm = 'channels.id = "'.$channel[0]->id.'"'; }else{ $userRol = false; $userQueryForm = ''; }
        
        //Store Form Variables in Session
        if ($request->isMethod('post')) {
            session(['channelsItems' => $request->items]);
            session(['channelsId' => $request->id]);
            session(['channelsName' => $request->name]);
            $currentPage = 1;
            session(['channelsPage' => 1]);
        }else{
            $currentPage = session('channelsPage');
        }
        
        //Form Variables
        $id = session('channelsId');
        $name = session('channelsName');
        
        //Pagination Items
        if (session('channelsItems') == null) { $items = 10; } else { $items = session('channelsItems'); }
        
         // Make sure that you call the static method currentPageResolver()
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });
        
        //Obtain Data
        $channels = channels($id, $name, $items,$userRol,$userQueryForm);
        $cities = \App\city::all();
        
        return view('channels.index',[
            'items' => $items,
            'channels' => $channels,
            'cities' => $cities,
            'edit' => $edit,
            'cancel' => $cancel,
            'create' => $create,
            'createAgency' => $createAgency
        ]);
    }
    function fetch_data(Request $request) {
        if ($request->ajax()) { 
            //Page
            session(['channelsPage' => $request->page]);
                       
            //Obtain Edit Permission
            $edit = checkExtraPermits('47',\Auth::user()->role_id);

            //Obtain Create Permission
            $create = checkExtraPermits('49',\Auth::user()->role_id);

            //Obtain Cancel Permission
            $cancel = checkExtraPermits('48',\Auth::user()->role_id);

            //Create Agency
            $createAgency = checkExtraPermits('49',\Auth::user()->role_id);
            
            //Pagination Items
            if (session('channelsItems') == null) { $items = 10; } else { $items = session('channelsItems'); }
            
            //Form Variables
            $document = session('channelsDocument');
            $name = session('channelsName');
            $city = session('channelsCity');
            
            //Obtain Channel
            $channelQuery = 'select cha.* from channels cha join agencies agen on agen.channel_id = cha.id where agen.id =  "' . \Auth::user()->agen_id . '"';
            $channel = DB::select($channelQuery);

            //Validate User Role
            if (\Auth::user()->role_id == 4) { $userRol = true; $userQueryForm = 'channels.id = "'.$channel[0]->id.'"'; }else{ $userRol = false; $userQueryForm = ''; }
            
            $channels = channels($document, $name, $city, $items,$userRol,$userQueryForm);
            
            return view('pagination.channels', [
                'channels' => $channels,
                'items' => $items,
                'edit' => $edit,
                'cancel' => $cancel,
                'create' => $create,
                'createAgency' => $createAgency
            ]);
        }
    }
    
    public function create(){
        //Obtain Data
        $provinces = \App\province::all();
        $cities = \App\city::all();
        $channelTypes = \App\channel_types::all();
        $sucreUsers = \App\User::where('type_user_sucre_id','=','1')->get();
        
        return view('channels.create',[
            'provinces' => $provinces,
            'cities' => $cities,
            'channelTypes' => $channelTypes,
            'sucreUsers' => $sucreUsers
        ]);
    }
    
    public function store(request $request){
        //Form variables
        $ruc = $request['ruc']; $address = $request['address']; $province = $request['province'];
        $phone = $request['phone']; $zip = $request['zip']; $name = $request['name'];
        $contact = $request['contact']; $cityId = $request['city']; $mobilePhone = $request['mobile_phone']; $type = $request['type'];
        $sucreUser = $request['sucreUser'];
        $errorMsg = false;
        
        //Validate Form Variables
        if (!is_numeric($ruc)) { $errorMsg = true; \Session::flash('errorRuc', 'El RUC debe ser numérico'); } else if (strlen($ruc) != 13) { $errorMsg = true; \Session::flash('errorRuc', 'El RUC debe contener 13 caracteres'); }else{ $rucValidate = \App\channels::where('document','=',$ruc)->get(); if(!$rucValidate->isEmpty()){ $errorMsg = true; \Session::flash('errorRuc', 'El RUC ya se encuentra registrado en el sistema'); } }
        if (!isset($address)) { $errorMsg = true; \Session::flash('errorAddress', 'La dirección no puede estar vacia'); }
        if (!isset($province)) { $errorMsg = true; \Session::flash('errorProvince', 'Debe seleccionar una Provincia'); }
        if (!is_numeric($phone)) { $errorMsg = true;\Session::flash('errorPhone', 'El télefono debe ser numérico'); } else if (strlen($phone) != 9) {$errorMsg = true; \Session::flash('errorPhone', 'El télefono debe contener 9 caracteres'); }
        if(isset($zip)){ if (!is_numeric($zip)) { $errorMsg = true; \Session::flash('errorZip', 'El Código Postal debe ser numérico'); } else if (strlen($zip) != 5) { $errorMsg = true; \Session::flash('errorZip', 'El Código Postal debe tener 5 caracteres'); } }    
        if (!isset($name)) { $errorMsg = true; \Session::flash('errorName', 'El nombre no puede estar vacio'); }
        if (!isset($contact)) { $errorMsg = true; \Session::flash('errorContact', 'El contacto no puede estar vacio'); }
        if (!isset($cityId)) { $errorMsg = true; \Session::flash('errorCity', 'Debe seleccionar una ciudad'); } else { $city = \App\city::find($cityId); $cityName = $city->name; \Session::flash('cityName', $cityName); }
        if (!is_numeric($mobilePhone)) { $errorMsg = true; \Session::flash('errorMobilePhone', 'El télefono celular debe ser numérico'); } else if (strlen($mobilePhone) != 10) { $errorMsg = true; \Session::flash('errorMobilePhone', 'El télefono celular debe contener 10 caracteres'); }
        if(!isset($type)){ $errorMsg = true; \Session::flash('errorType', 'Debe indicar un Tipo'); }
        if(!isset($sucreUser)){
            $errorMsg = true; \Session::flash('errorSucreUser', 'Debe indicar un Usuario');
        }
        
        if($errorMsg == true){ // Return With Error
            return back()->withInput();
        }else{ // Store Channel
            $channel = new \App\channels();
            $channel->document = $ruc;
            $channel->document_id = 2;
            $channel->address = $address;
            $channel->phone = $phone;
            $channel->zip_code = $zip;
            $channel->contact = $contact;
            $channel->city_id = $cityId;
            $channel->mobile_phone = $mobilePhone;
            $channel->name = $name;
            $channel->channel_type_id = $type;
            $channel->user_sucre_id = $sucreUser;
            $channel->save();
            
            \Session::flash('success', 'El canal fue creado correctamente');
            return redirect('channel');
        }
    }
    
    public function resume(request $request){
        $returnTable = '';
        //Channel Data
        $channel = \App\channels::where('channels.id','=',$request['id'])
                                ->get();
        //Agencies Data
//        $agencies = \App\Agency::where('agencies.channel_id','=',$request['id'])
//                                ->get();
        $agencies = \App\product_channel::selectRaw('DISTINCT(agente.agenteid) as "agenteId", agente.agentedes as "agenteName"')
                                        ->join('agent_ss as agente','agente.id','=','products_channel.agency_ss_id')
                                        ->where('products_channel.channel_id','=',$request['id'])
                                        ->get();
        

        $returnTable .= '<h4>Canal:</h4>';
        //Return Table Channel
        $returnTable .= '<table class="table table-bordered">
                            <thead>
                              <tr>
                                <th align="center" style="background-color: #183c6b;color: white;">ID</th>
                                <th align="center" style="background-color: #183c6b;color: white;">Descripcion</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td align="center">'.$channel[0]->canalnegoid.'</td>
                                <td align="center">'.$channel[0]->canalnegodes.'</td>
                              </tr>
                            </tbody>
                          </table>';
        
        $returnTable .= '<h4>Agencia:</h4>';
        //Return Table Agency
            $returnTable .= '<table id="tableChannelResume2" class="table table-bordered">
                                <thead>
                                  <tr>
                                    <th align="center" style="background-color: #183c6b;color: white;">ID</th>
                                    <th align="center" style="background-color: #183c6b;color: white;">Agente</th>
                                  </tr>
                                </thead>
                                <tbody>';
                foreach($agencies as $agen){
                    $returnTable .='     <tr>
                                            <td align="center">'.$agen->agenteId.'</td>
                                            <td align="center">'.$agen->agenteName.'</td>
                                          </tr>';
                    
                    
                }
            $returnTable .='</tbody>
                          </table>';
            $returnTable .= '<script> $("#tableChannelResume2").DataTable({
                    "searching": false,
                    "pagination": false,
                    "info": false,
                    "ordering": false,
                    "language": {
                        "sProcessing": "Procesando...",
                        "sLengthMenu": "Mostrar   _MENU_   registros",
                        "sZeroRecords": "No se encontraron resultados",
                        "sEmptyTable": "Ningún dato disponible en esta tabla",
                        "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                        "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                        "sInfoPostFix": "",
                        "sSearch": "Buscar:",
                        "sUrl": "",
                        "sInfoThousands": ",",
                        "sLoadingRecords": "Cargando...",
                        "oPaginate": {
                            "sFirst": "Primero",
                            "sLast": "Último",
                            "sNext": "Siguiente",
                            "sPrevious": "Anterior"
                        },
                        "oAria": {
                            "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                        }
                    }
                });</script>';
            
        return $returnTable;
    }
    
    public function edit(request $request){
        //Obtain Data
        $channel = \App\channels::find($request['channelEditId']);
        $cityId = \App\city::find($channel->city_id);
        $provinceId = \App\province::find($cityId->province_id);
        $countryId = \App\country::find($provinceId->country_id);
        $cities = \App\city::where('province_id','=',$provinceId->id)->orderBy('name','ASC')->get();
        $provinces = \App\province::where('country_id','=',$countryId->id)->orderBy('name','ASC')->get();
 
        return view('channels.edit',[
            'provinces' => $provinces,
            'cities' => $cities,
            'channel' => $channel,
            'cityId' => $cityId->id,
            'provinceId' => $provinceId->id,
            'countryId' => $countryId->id,
            'cities' => $cities,
            'provinces' => $provinces
        ]);
    }
    
    public function editValidate(request $request){
        //Form variables
        $ruc = $request['ruc']; $address = $request['address']; $province = $request['province'];
        $phone = $request['phone']; $zip = $request['zip']; $name = $request['name'];
        $contact = $request['contact']; $cityId = $request['city']; $mobilePhone = $request['mobile_phone'];
        $errorMsg = false;
        
        //Validate Form Variables
        if (!is_numeric($ruc)) { $errorMsg = true; \Session::flash('errorRuc', 'El RUC debe ser numérico'); } else if (strlen($ruc) != 13) { $errorMsg = true; \Session::flash('errorRuc', 'El RUC debe contener 13 caracteres'); } else { $rucValidate = \App\channels::where('document', '=', $ruc)->where('id','!=',$request['channelId'])->get(); if (!$rucValidate->isEmpty()) { $errorMsg = true; \Session::flash('errorRuc', 'El RUC ya se encuentra registrado en el sistema'); } }
        if (!isset($address)) { $errorMsg = true; \Session::flash('errorAddress', 'La dirección no puede estar vacia'); }
        if (!isset($province)) { $errorMsg = true; \Session::flash('errorProvince', 'Debe seleccionar una Provincia'); }
        if (!is_numeric($phone)) { $errorMsg = true;\Session::flash('errorPhone', 'El télefono debe ser numérico'); } else if (strlen($phone) != 9) {$errorMsg = true; \Session::flash('errorPhone', 'El télefono debe contener 9 caracteres'); }
        if(isset($zip)){ if (!is_numeric($zip)) { $errorMsg = true; \Session::flash('errorZip', 'El Código Postal debe ser numérico'); } else if (strlen($zip) != 5) { $errorMsg = true; \Session::flash('errorZip', 'El Código Postal debe tener 5 caracteres'); } }    
        if (!isset($name)) { $errorMsg = true; \Session::flash('errorName', 'El nombre no puede estar vacio'); }
        if (!isset($contact)) { $errorMsg = true; \Session::flash('errorContact', 'El contacto no puede estar vacio'); }
        if (!isset($cityId)) { $errorMsg = true; \Session::flash('errorCity', 'Debe seleccionar una ciudad'); } else { $city = \App\city::find($cityId); $cityName = $city->name; \Session::flash('cityName', $cityName); }
        if (!is_numeric($mobilePhone)) { $errorMsg = true; \Session::flash('errorMobilePhone', 'El télefono celular debe ser numérico'); } else if (strlen($mobilePhone) != 10) { $errorMsg = true; \Session::flash('errorMobilePhone', 'El télefono celular debe contener 10 caracteres'); }
        
        if($errorMsg == true){ // Return With Error
            return 'error';
        }else{ // Store Channel
            $channel = \App\channels::find($request['channelId']);
            $channel->document = $ruc;
            $channel->document_id = 2;
            $channel->address = $address;
            $channel->phone = $phone;
            $channel->zip_code = $zip;
            $channel->contact = $contact;
            $channel->city_id = $cityId;
            $channel->mobile_phone = $mobilePhone;
            $channel->name = $name;
            $channel->save();
            
            \Session::flash('success', 'El canal fue creado correctamente');
            return 'success';
        }
    }
    
    public function productChannel($id){
        $channelId = decrypt($id);
        
        $channel = \App\channels::find($channelId);
        $products = \App\products::all();
        
        //Selected Products
        $selectedProducts = \App\products::selectRaw('products.id, products.name')
                                        ->join('products_channel as pbc','pbc.product_id','=','products.id')
                                        ->where('pbc.channel_id','=',$channel->id)
                                        ->get();
        $arraySelectedProducts = array();
        foreach($selectedProducts as $pro){
            $arraySelectedProducts = [$pro->id];
        }
        //UnSelected Products
        $unSelectedProducts = \App\products::selectRaw('products.id, products.name')
                                        ->whereNotIn('products.id',$arraySelectedProducts)
                                        ->get();
        
        return view('channels.product',[
            'channel' => $channel,
            'products' => $products,
            'selectedProducts' => $selectedProducts,
            'unSelectedProducts' => $unSelectedProducts
        ]);
    }
    
    public function productChannelStore(request $request){
        $productChannel = \App\product_channel::where('channel_id','=',$request['channelId'])->delete();
        if(!empty($request['selectData'])){
            foreach($request['selectData'] as $pro){
                $newProductChannel = new \App\product_channel();
                $newProductChannel->channel_id = $request['channelId'];
                $newProductChannel->product_id = $pro['productId'];
                $newProductChannel->user_id = \Auth::user()->id;
                $newProductChannel->status_id = 1;
                $newProductChannel->save();
            }
        }
        \Session::flash('editSuccess', 'Los productos fueron asignados');
    }
    
    public function productChannelSS(request $request){
        set_time_limit(60000);
        $delete = \App\products_agency::truncate();
        $delete = \App\coverage::truncate();
        $delete = \App\products_rubros::truncate();
        $result = productChannelSS();
        $arrayChannels = array();
        $arrayAgents = array();
        $arrayAgencies = array();
        $arrayProducts = array();
        $arrayRubros = array();
        foreach($result['canalproducto'] as $res){
            //Store Agencia SS
            $agenciaSSFind = \App\agencia_ss::where('agenciaid','=',$res['agenciaid'])->get();
            if($agenciaSSFind->isEmpty()){
                $agenciaSS = new \App\agencia_ss();
                $agenciaSS->agenciades = $res['agenciades'];
                $agenciaSS->agenciaid = $res['agenciaid'];
                $agenciaSS->save();
                $agenciaSSId = $agenciaSS->id;
            }else{
                $agenciaSS = \App\agencia_ss::find($agenciaSSFind[0]->id);
                $agenciaSS->agenciades = $res['agenciades'];
                $agenciaSS->agenciaid = $res['agenciaid'];
                $agenciaSS->save();
                $agenciaSSId = $agenciaSSFind[0]->id;
            }
            //Array Agents
            array_push($arrayAgents,$res['agenteid']);
            //Store Agente SS
            $agentSSFind = \App\agent_ss::where('agenteid','=',$res['agenteid'])->get();
            if($agentSSFind->isEmpty()){
                $agentSS = new \App\agent_ss();
                $agentSS->agentedes = $res['agentedes'];
                $agentSS->agenteid = $res['agenteid'];
                $agentSS->status_id = 1;
                $agentSS->save();
                $agenteSSId = $agentSS->id;
            }else{
                $agentSS = \App\agent_ss::find($agentSSFind[0]->id);
                $agentSS->agentedes = $res['agentedes'];
                $agentSS->agenteid = $res['agenteid'];
                $agentSS->status_id = 1;
                $agentSS->save();
                $agenteSSId = $agentSSFind[0]->id;
            }
            //Array Canales
            array_push($arrayChannels,$res['canalnegoid']);
            //Store Channel
            $channelFind = \App\channels::where('canalnegoid','=',$res['canalnegoid'])->get();
            if($channelFind->isEmpty()){
                $channel = new \App\channels();
                $channel->canalnegodes = $res['canalnegodes'];
                $channel->canalnegoid = $res['canalnegoid'];
                $channel->status_id = 1;
                $channel->save();
                $channelId = $channel->id;
            }else{
                $channel = \App\channels::find($channelFind[0]->id);
                $channel->canalnegodes = $res['canalnegodes'];
                $channel->canalnegoid = $res['canalnegoid'];
                $channel->status_id = 1;
                $channel->save();
                $channelId = $channelFind[0]->id;
            }
            //Array Agencias
            array_push($arrayAgencies,$res['puntoventaid']);
            //Store Agencia (Punto de Venta)
            $agencieFind = \App\Agency::where('puntodeventaid','=',$res['puntoventaid'])->get();
            if($agencieFind->isEmpty()){
                $agency = new \App\Agency();
                $agency->puntodeventades = $res['puntoventades'];
                $agency->puntodeventaid = $res['puntoventaid'];
                $agency->channel_id = $channelId;
                $agency->save();
                $agencyId = $agency->id;
            }else{
                $agency = \App\Agency::find($agencieFind[0]->id);
                $agency->puntodeventades = $res['puntoventades'];
                $agency->puntodeventaid = $res['puntoventaid'];
                $agency->channel_id = $channelId;
                $agency->save();
                $agencyId = $agency->id;
            }
            //Array Products
            array_push($arrayProducts,$res['canalplanid']);
            //Store Product
            $productFind = \App\products::where('canalplanid','=',$res['canalplanid'])->get();
            if($productFind->isEmpty()){
                $product = new \App\products();
                $product->productodes = $res['productodes'];
                $product->productoid = $res['productoid'];
                $product->ramodes = $res['ramodes'];
                $product->ramoid = $res['ramoid'];
                $product->agency_id = $agencyId;
                $product->canalplanid = $res['canalplanid'];
                $product->status_id = 1;
                $product->save();
                $productId = $product->id;
            }else{
                $product = \App\products::find($productFind[0]->id);
                $product->productodes = $res['productodes'];
                $product->productoid = $res['productoid'];
                $product->ramodes = $res['ramodes'];
                $product->ramoid = $res['ramoid'];
                $product->agency_id = $agencyId;
                $product->canalplanid = $res['canalplanid'];
                $product->status_id = 1;
                $product->save();
                $productId = $productFind[0]->id;
            }
            $proAgency = new \App\products_agency();
            $proAgency->product_id = $productId;
            $proAgency->agency_id = $agencyId;
            $proAgency->status_id = 1;
            $proAgency->save();
            
            //Delete Coverage Data
            $result = \App\coverage::where('product_id','=',$productId)->delete();
            //Store Coverage (Coberturas)
            \App\Jobs\coverageStoreJob::dispatch($res['coberturas'], $productId);
            if(!empty($res['rubros'])){
                foreach($res['rubros'] as $rubros){
                    $rubroFind = \App\products_rubros::where('cod','=',$rubros['codigo'])->where('product_id','=',$productId)->get();
                    if($rubroFind->isEmpty()){
                        //Array Rubros
                        array_push($arrayRubros,$rubros['codigo']);
                        $rubro = new \App\products_rubros();
                        $rubro->cod = $rubros['codigo'];
                        $rubro->description = $rubros['descripcion'];
                        $rubro->indicator = $rubros['indicador'];
                        $rubro->max_value = $rubros['montomaximo'];
                        $rubro->min_value = $rubros['montominimo'];
                        $rubro->value = $rubros['valorasegurado'];
                        $rubro->status_id = 1;
                        $rubro->product_id = $productId;
                        $rubro->save();
                    }
                }
            }
            //Product Chanel
            $productChannel = \App\product_channel::where('canal_plan_id','=',$res['canalplanid'])->get();
            if($productChannel->isEmpty()){
                $pro = new \App\product_channel();
                $pro->channel_id = $channelId;
                $pro->product_id = $productId;
                $pro->status_id = 1;
                $pro->agency_id = $agencyId;
                $pro->canal_plan_id = $res['canalplanid'];
                $pro->agency_ss_id = $agenciaSSId;
                $pro->agent_ss = $agenteSSId;
                $pro->ejecutivo_ss = $res['atributos']['1']['valor'];
                $pro->ejecutivo_ss_email = $res['atributos']['0']['valor'];
                $pro->save();
            }else{
                $pro = \App\product_channel::find($productChannel[0]->id);
                $pro->channel_id = $channelId;
                $pro->product_id = $productId;
                $pro->status_id = 1;
                $pro->agency_id = $agencyId;
                $pro->canal_plan_id = $res['canalplanid'];
                $pro->agency_ss_id = $agenciaSSId;
                $pro->agent_ss = $agenteSSId;
                $pro->ejecutivo_ss = $res['atributos']['1']['valor'];
                $pro->ejecutivo_ss_email = $res['atributos']['0']['valor'];
                $pro->save();
                
            }
        }
        //Validate Products
        $products = \App\product_channel::whereNotIn('canal_plan_id',$arrayProducts)->get();
        foreach($products as $p){
           $pro = \App\product_channel::find($p->id);
           $pro->status_id = 2;
           $pro->save();
        }
        //Validate Rubros
//        $rubros = \App\products_rubros::whereNotIn('cod', $arrayRubros)->get();
//        foreach($rubros as $ru){
//            $rubroSearch = \App\products_rubros::where('cod','=',$ru->cod)->get();
//            $rubro = \App\products_rubros::find($rubroSearch[0]->id);
//            $rubro->status_id = 2;
//            $rubro->save();
//        }
        //Validate Agents
        $agents = \App\agent_ss::whereNotIn('agenteid',$arrayAgents)->get();
        foreach($agents as $c){
           $agent = \App\agent_ss::find($c->id);
           $agent->status_id = 2;
           $agent->save();
        }
        //Validate Channels
        $channels = \App\channels::whereNotIn('canalnegoid',$arrayChannels)->get();
        foreach($channels as $c){
           $channel = \App\channels::find($c->id);
           $channel->status_id = 2;
           $channel->save();
           $agencies = \App\Agency::where('channel_id','=',$c->id)->get();
           foreach($agencies as $a){
               $user = \App\User::where('agen_id','=',$a->id)->whereNotIn('type_user_sucre_id',[1])->get();
               foreach($user as $u){
                   $usr = \App\User::find($u->id);
                   $usr->status_id = 2;
                   $usr->save();
               }
           }
        }
        //Validate Agency
        $agency = \App\Agency::whereNotIn('puntodeventaid', $arrayAgencies)->get();
        foreach($agency as $a){
           $user = \App\User::where('agen_id','=',$a->id)->whereNotIn('type_user_sucre_id',[1])->get();
           foreach($user as $u){
               $usr = \App\User::find($u->id);
               $usr->status_id = 2;
               $usr->save();
           }
        }
    }

    public function getAgentChannel($agentId){
        $channelQuery = 'SELECT distinct(c.id), c.canalnegodes FROM agent_ss gs
                        INNER JOIN products_channel pc ON pc.agent_ss = gs.id
                        INNER JOIN channels c ON c.id = pc.channel_id 
                        WHERE gs.id ='. $agentId .' and c.status_id = 1';        
        $channel = DB::select($channelQuery);

        return json_encode($channel);
    }
    
    public function getEjecutivoChannel($agencyId,$channelId){
        $ejecutivoQuery = 'SELECT DISTINCT(pc.ejecutivo_ss), pc.id  FROM products_channel pc 
                        INNER JOIN channels c ON c.id = pc.channel_id 
                        WHERE pc.channel_id ='. $channelId .' and pc.status_id = 1 and pc.agency_id = ' .$agencyId . ' group by pc.ejecutivo_ss';  
        $ejecutivo = DB::select($ejecutivoQuery);

        return json_encode($ejecutivo);
    }
}
