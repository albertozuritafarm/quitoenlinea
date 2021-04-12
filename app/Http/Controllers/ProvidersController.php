<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ProvidersController extends Controller{
    
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('validateUserRoute');
    }
    
    public function index(request $request){
        //Validate if User has view Permit
        $viewPermit = checkViewPermit('59', \Auth::user()->role_id);
        if(!$viewPermit){
            \Session::flash('ValidateUserRoute', 'No tiene acceso al modulo de Proveedores.');
            return view('home');
        }
        
        //Obtain Edit Permission
        $edit = checkExtraPermits('55',\Auth::user()->role_id);
        
        //Obtain Create Permission
        $create = checkExtraPermits('57',\Auth::user()->role_id);
        
        //Obtain Cancel Permission
        $cancel = checkExtraPermits('56',\Auth::user()->role_id);
        
        //Obtain Cancel Permission
        $createBranch = checkExtraPermits('57',\Auth::user()->role_id);
        
        //Store Form Variables in Session
        if ($request->isMethod('post')) {
            session(['providersItems' => $request->items]);
            session(['providersDocument' => $request->document]);
            session(['providersName' => $request->name]);
            session(['providersCity' => $request->city]);
        }
        
        //Form Variables
        $document = session('providersDocument');
        $name = session('providersName');
        $city = session('providersCity');
        
        //Pagination Items
        if (session('providersItems') == null) { $items = 5; } else { $items = session('providersItems'); }
        
        $cities = \App\city::all();
        
        //Providers
        $providers = providers($document, $name, $city, $items);
        
        return view('providers.index',[
            'providers' => $providers,
            'items' => $items,
            'cities' => $cities,
            'edit' => $edit,
            'cancel' => $cancel,
            'create' => $create,
            'createBranch' => $createBranch
        ]);
    }
    function fetch_data(Request $request) {
        if ($request->ajax()) {
            //Obtain Edit Permission
            $edit = checkExtraPermits('55',\Auth::user()->role_id);

            //Obtain Create Permission
            $create = checkExtraPermits('57',\Auth::user()->role_id);

            //Obtain Cancel Permission
            $cancel = checkExtraPermits('56',\Auth::user()->role_id);

            //Obtain Cancel Permission
            $createBranch = checkExtraPermits('65',\Auth::user()->role_id);
        
            //Store Form Variables in Session
            if ($request->isMethod('post')) {
                session(['providersItems' => $request->items]);
            }
            //Form Variables
            $document = session('providersDocument');
            $name = session('providersName');
            $city = session('providersCity');
        
            //Pagination Items
            if (session('providersItems') == null) { $items = 5; } else { $items = session('providersItems'); }

            //Providers
            $providers = providers($document, $name, $city, $items);
            
            return view('pagination.providers', [
                'providers' => $providers,
                'items' => $items,
                'create' => $create,
                'edit' => $edit,
                'cancel' => $cancel,
                'createBranch' => $createBranch
            ]);
        }
    }
    
    public function create(){
        //Obtain Data
        $provinces = \App\province::all();
        $cities = \App\city::all();
        
        return view('providers.create',[
            'provinces' => $provinces,
            'cities' => $cities
        ]);
    }
    
    public function store(request $request){
        //Form variables
        $ruc = $request['ruc']; $address = $request['address']; $province = $request['province'];
        $phone = $request['phone']; $zip = $request['zip']; $name = $request['name'];
        $contact = $request['contact']; $cityId = $request['city']; $mobilePhone = $request['mobile_phone'];
        $email = $request['email']; $errorMsg = false;
        
        //Validate Form Variables
        if (!is_numeric($ruc)) { $errorMsg = true; \Session::flash('errorRuc', 'El RUC debe ser numérico'); } else if (strlen($ruc) != 13) { $errorMsg = true; \Session::flash('errorRuc', 'El RUC debe contener 13 caracteres'); } else { $rucValidate = \App\providers::where('document', '=', $ruc)->get(); if (!$rucValidate->isEmpty()) { $errorMsg = true; \Session::flash('errorRuc', 'El RUC ya se encuentra registrado en el sistema'); } }
        if (!isset($address)) { $errorMsg = true; \Session::flash('errorAddress', 'La dirección no puede estar vacia'); }
        if (!isset($province)) { $errorMsg = true; \Session::flash('errorProvince', 'Debe seleccionar una Provincia'); }
        if (!is_numeric($phone)) { $errorMsg = true;\Session::flash('errorPhone', 'El télefono debe ser numérico'); } else if (strlen($phone) != 9) {$errorMsg = true; \Session::flash('errorPhone', 'El télefono debe contener 9 caracteres'); }
        if(isset($zip)){ if (!is_numeric($zip)) { $errorMsg = true; \Session::flash('errorZip', 'El Código Postal debe ser numérico'); } else if (strlen($zip) != 5) { $errorMsg = true; \Session::flash('errorZip', 'El Código Postal debe tener 5 caracteres'); } }    
        if (!isset($name)) { $errorMsg = true; \Session::flash('errorName', 'El nombre no puede estar vacio'); }
        if (!isset($contact)) { $errorMsg = true; \Session::flash('errorContact', 'El contacto no puede estar vacio'); }
        if (!isset($cityId)) { $errorMsg = true; \Session::flash('errorCity', 'Debe seleccionar una ciudad'); } else { $city = \App\city::find($cityId); $cityName = $city->name; \Session::flash('cityName', $cityName); }
        if (!is_numeric($mobilePhone)) { $errorMsg = true; \Session::flash('errorMobilePhone', 'El télefono celular debe ser numérico'); } else if (strlen($mobilePhone) != 10) { $errorMsg = true; \Session::flash('errorMobilePhone', 'El télefono celular debe contener 10 caracteres'); }
        if(!isset($email)){ $errorMsg = true; \Session::flash('errorEmail', 'El Email no puede estar vacio'); }elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $errorMsg = true; \Session::flash('errorEmail', 'El Email no tiene el formato correcto'); }
        
        if($errorMsg == true){ // Return With Error
            return back()->withInput();
        }else{ // Store Channel
            $provider = new \App\providers();
            $provider->name = $name;
            $provider->document = $ruc;
            $provider->document_id = 2;
            $provider->address = $address;
            $provider->city_id = $cityId;
            $provider->phone = $phone;
            $provider->mobile_phone = $mobilePhone;
            $provider->contact = $contact;
            $provider->email = $email;
            $provider->save();
            \Session::flash('success', 'El proveedor fue creado correctamente');
            return redirect('providers');
        }
    }
     public function resume(request $request){
        $returnTable = '';
        //Providerss Data
        $provider = \App\providers::selectRaw('providers.document, providers.name, providers.address, cit.name as "citName", providers.contact, providers.phone, providers.mobile_phone, providers.email')
                                ->join('cities as cit','cit.id','=','providers.city_id')
                                ->where('providers.id','=',$request['id'])
                                ->get();
        //Providers Branch Data
        $providers_branch = \App\providers_branch::selectRaw('providers_branch.document, providers_branch.name, providers_branch.address, cit.name as "citName", providers_branch.contact, providers_branch.phone, providers_branch.mobile_phone, providers_branch.email')
                                ->join('cities as cit','cit.id','=','providers_branch.city_id')
                                ->where('providers_branch.providers_id','=',$request['id'])
                                ->get();
        $returnTable .= '<h4>Proveedor:</h4>';
        //Return Table Providers
        $returnTable .= '<table class="table table-bordered">
                            <thead>
                              <tr>
                                <th align="center" style="background-color: #848484;color: white;">Documento</th>
                                <th align="center" style="background-color: #848484;color: white;">Nombre</th>
                                <th align="center" style="background-color: #848484;color: white;">Ciudad</th>
                                <th align="center" style="background-color: #848484;color: white;">Télefono</th>
                                <th align="center" style="background-color: #848484;color: white;">Celular</th>
                                <th align="center" style="background-color: #848484;color: white;">Contacto</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td align="center">'.$provider[0]->document.'</td>
                                <td align="center">'.$provider[0]->name.'</td>
                                <td align="center">'.$provider[0]->citName.'</td>
                                <td align="center">'.$provider[0]->phone.'</td>
                                <td align="center">'.$provider[0]->mobile_phone.'</td>
                                <td align="center">'.$provider[0]->contact.'</td>
                              </tr>
                            </tbody>
                          </table>';
        
        $returnTable .= '<h4>Sucursales:</h4>';
        //Return Table Agency
            $returnTable .= '<table id="tableChannelResume2" class="table table-bordered">
                                <thead>
                                  <tr>
                                    <th align="center" style="background-color: #848484;color: white;">Nombre</th>
                                    <th align="center" style="background-color: #848484;color: white;">Ciudad</th>
                                    <th align="center" style="background-color: #848484;color: white;">Télefono</th>
                                    <th align="center" style="background-color: #848484;color: white;">Celular</th>
                                    <th align="center" style="background-color: #848484;color: white;">Contacto</th>
                                  </tr>
                                </thead>
                                <tbody>';
                foreach($providers_branch as $agen){
            $returnTable .='     <tr>
                                    <td align="center">'.$agen->name.'</td>
                                    <td align="center">'.$agen->citName.'</td>
                                    <td align="center">'.$agen->phone.'</td>
                                    <td align="center">'.$agen->mobile_phone.'</td>
                                    <td align="center">'.$agen->contact.'</td>
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
}
