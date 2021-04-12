<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use DB;
use Illuminate\Pagination\Paginator;

class CustomerController extends Controller {

    public function __construct() {
        $this->middleware('auth');
//        $this->middleware('validateUserRoute');
    }

    //Validate Customer Document
    public function documentCheck($id) {
//        return $id;
        $sql = 'select cus.first_name, cus.last_name, cus.address, cus.document, cus.document_id, cus.phone, cus.mobile_phone, cou.id as "country", prov.id as "province_id", prov.name as "province_name", cit.id as "city", cit.name as "city_name", cus.email
                 from customers cus
                 join cities cit on cit.id = cus.city_id
                join provinces prov on prov.id = cit.province_id
                join countries cou on cou.id = prov.country_id
                 where cus.document like "%' . $id . '%" ORDER BY first_name LIMIT 6';
//        return $sql;
        $customers = DB::select($sql);
//        return $customers;
        $data = '<ul id="customer-list" class="border">';
        $tempData = '';
        foreach ($customers as $customer) {
            $tempData .= '<li onClick="selectDocument(' . $customer->document . ')">' . $customer->first_name . ' ' . $customer->last_name . ' - ' . $customer->document . '</li>';
        }
        $data .= $tempData;
        $data .= '</ul>';
        return $data;
    }

    //Validate Customer Document
    public static function documentAutoFill($id) {
        ///////////////////////////////////////////////////////
        //***************** NEW CODE ******************////////
        ///////////////////////////////////////////////////////

        $validateId = validateId($id); if($validateId == true){ $typeDoc = 'C'; $documentId = 1; }else{ $typeDoc = 'P'; $documentId = 2; }
        $data = customerSS($typeDoc, $id);
        
        //return $data['infocliente']['cedula'][0]['celulares'];
        $string = explode(",", $data['infocliente']['cedula'][0]['celulares']);
        $mobilePhone = $string[0];
        
        $string = explode(",", $data['infocliente']['cedula'][0]['telefonos']);
        $phone = $string[0];

        $birthdate = str_replace("/", "-", $data['infocliente']['cedula'][0]['fechanacimiento']);
        $date = date_create($birthdate);
        $date = date_format($date,"Y-m-d");
    
        if ($data['error'][0]['code'] === '000') {
            $data = array('first_name' => $data['infocliente']['cedula'][0]['nombre1'],
                'last_name' => $data['infocliente']['cedula'][0]['apellido1'],
                'document' => $data['infocliente']['cedula'][0]['cedula'],
                'email' => $data['infocliente']['cedula'][0]['email'],
                'phone' => str_replace('-','',$phone),
                'mobile_phone' => str_replace('-','',$mobilePhone),
                'address' => $data['infocliente']['cedula'][0]['direcciones'],
                'province_id' => null,
                'province_name' => null,
                'city_id' => null,
                'city_name' => null,
                'country' => '0',
                'province' => '0',
                'document_id' => $documentId,
                'birthdate' => $data['infocliente']['cedula'][0]['fechanacimiento'],
                'nacionality' => null,
                'gender' => null,
                'civil_state' => null,
                'profession' => null,
                'activity' => null,
                'work_address' => null,
                'correspondence' => null,
                'birthdate' => $date,
                'second_name' => $data['infocliente']['cedula'][0]['nombre2'],
                'second_last_name' => $data['infocliente']['cedula'][0]['apellido2'],
                'success' => 'true');
        } else {
                $data = array('first_name' => '',
                    'last_name' => '',
                    'second_name' => '',
                    'second_last_name' => '',
                    'document' => $id,
                    'email' => '',
                    'city' => '',
                    'document_id' => '0',
                    'success' => 'false');
        }
        
        return $data;
        
        ///////////////////////////////////////////////////////
        //***************** NEW CODE ******************////////
        ///////////////////////////////////////////////////////
        
        ///////////////////////////////////////////////////////
        //***************** OLD CODE ******************////////
        ///////////////////////////////////////////////////////
//        $sql = 'select cus.first_name, cus.last_name, cus.address, cus.document, cus.document_id, cus.phone, cus.mobile_phone, cou.id as "country", prov.id as "province_id", prov.name as "province_name", cit.id as "city", cit.name as "city_name", cus.email
//        $sql = 'select cus.*, cou.id as "country", prov.id as "province_id", prov.name as "province_name", cit.id as "city", cit.name as "city_name", cus.email
//                    from customers cus
//                    join cities cit on cit.id = cus.city_id
//                    join provinces prov on prov.id = cit.province_id
//                    join countries cou on cou.id = prov.country_id
//                    where cus.document = "' . $id . '"';
//
//        $customer = DB::select($sql);
//
//        $sqlInsured = 'select cus.*, cou.id as "country", prov.id as "province_id", prov.name as "province_name", cit.id as "city", cit.name as "city_name", cus.email
//                    from insured cus
//                    join cities cit on cit.id = cus.city_id
//                    join provinces prov on prov.id = cit.province_id
//                    join countries cou on cou.id = prov.country_id
//                    where cus.document = "' . $id . '"';
//
//        if ($customer) {
//            $data = array('first_name' => $customer[0]->first_name,
//                'last_name' => $customer[0]->last_name,
//                'document' => $customer[0]->document,
//                'email' => $customer[0]->email,
//                'city' => $customer[0]->city,
//                'country' => $customer[0]->country,
//                'phone' => $customer[0]->phone,
//                'mobile_phone' => $customer[0]->mobile_phone,
//                'address' => $customer[0]->address,
//                'province_id' => $customer[0]->province_id,
//                'province' => $customer[0]->province_id,
//                'province_name' => $customer[0]->province_name,
//                'city_id' => $customer[0]->city,
//                'city_name' => $customer[0]->city_name,
//                'document_id' => $customer[0]->document_id,
//                'birthdate' => $customer[0]->birthdate,
//                'nacionality' => $customer[0]->nacionality_id,
//                'gender' => $customer[0]->gender_id,
//                'civil_state' => $customer[0]->civil_status_id,
//                'profession' => $customer[0]->profession,
//                'activity' => $customer[0]->activity,
//                'work_address' => $customer[0]->work_address,
//                'correspondence' => $customer[0]->correspondence_id,
//                'second_name' => $customer[0]->second_name,
//                'second_last_name' => $customer[0]->second_last_name,
//                'success' => 'true');
//        } else {
//                $data = array('first_name' => '',
//                    'last_name' => '',
//                    'second_name' => '',
//                    'second_last_name' => '',
//                    'document' => $id,
//                    'email' => '',
//                    'city' => '',
//                    'document_id' => '0',
//                    'success' => 'false');
//            
//        }
//        return $data;
        ///////////////////////////////////////////////////////
        //***************** OLD CODE ******************////////
        ///////////////////////////////////////////////////////
    }
    
    public static function documentCompanyAutoFill($id){
        $data = customerSS('R', $id);
//        return $data;
        if ($data['error'][0]['code'] === '000') {
            $data = array('razon_social' => $data['infocliente']['ruc'][0]['razonsocial'],
                'nombrefantasia' => $data['infocliente']['ruc'][0]['nombrefantasia'],
                'cedularepresentantelegal' => $data['infocliente']['ruc'][0]['cedularepresentantelegal'],
                'success' => 'true');
        } else {
                $data = array('first_name' => '',
                    'last_name' => '',
                    'second_name' => '',
                    'second_last_name' => '',
                    'document' => $id,
                    'email' => '',
                    'city' => '',
                    'document_id' => '0',
                    'message' => $data['error'][0]['message'],
                    'success' => 'false');
        }
        
        return $data;
    }
    
    public function insuredDocumentAutoFill($id) {
//        $sql = 'select cus.first_name, cus.last_name, cus.address, cus.document, cus.document_id, cus.phone, cus.mobile_phone, cou.id as "country", prov.id as "province_id", prov.name as "province_name", cit.id as "city", cit.name as "city_name", cus.email
        $sql = 'select cus.*, cou.id as "country", prov.id as "province_id", prov.name as "province_name", cit.id as "city", cit.name as "city_name", cus.email
                    from customers cus
                    join cities cit on cit.id = cus.city_id
                    join provinces prov on prov.id = cit.province_id
                    join countries cou on cou.id = prov.country_id
                    where cus.document = "' . $id . '"';

        $customer = DB::select($sql);

        $sqlInsured = 'select cus.*, cou.id as "country", prov.id as "province_id", prov.name as "province_name", cit.id as "city", cit.name as "city_name", cus.email
                    from insured cus
                    join cities cit on cit.id = cus.city_id
                    join provinces prov on prov.id = cit.province_id
                    join countries cou on cou.id = prov.country_id
                    where cus.document = "' . $id . '"';

        if ($customer) {
            $data = array('first_name_insured' => $customer[0]->first_name,
                'last_name_insured' => $customer[0]->last_name,
                'document_insured' => $customer[0]->document,
                'email_insured' => $customer[0]->email,
                'city_insured' => $customer[0]->city,
                'country_insured' => $customer[0]->country,
                'phone_insured' => $customer[0]->phone,
                'mobile_phone_insured' => $customer[0]->mobile_phone,
                'address_insured' => $customer[0]->address,
                'province_id_insured' => $customer[0]->province_id,
                'province_insured' => $customer[0]->province_id,
                'province_name_insured' => $customer[0]->province_name,
                'city_id_insured' => $customer[0]->city,
                'city_name_insured' => $customer[0]->city_name,
                'document_id_insured' => $customer[0]->document_id,
                'birthdate_insured' => $customer[0]->birthdate,
                'nacionality_insured' => $customer[0]->nacionality_id,
                'gender_insured' => $customer[0]->gender_id,
                'civil_state_insured' => $customer[0]->civil_status_id,
                'profession_insured' => $customer[0]->profession,
                'activity_insured' => $customer[0]->activity,
                'work_address_insured' => $customer[0]->work_address,
                'correspondence_insured' => $customer[0]->correspondence_id,
                'second_name_insured' => $customer[0]->second_name,
                'second_last_name_insured' => $customer[0]->second_last_name,
                'success' => 'true');
        } else {
            $customer = DB::select($sqlInsured);
            if ($customer) {
                $data = array('first_name_insured' => $customer[0]->first_name,
                    'last_name_insured' => $customer[0]->last_name,
                    'document_insured' => $customer[0]->document,
                    'email_insured' => $customer[0]->email,
                    'city_insured' => $customer[0]->city,
                    'country_insured' => $customer[0]->country,
                    'phone_insured' => $customer[0]->phone,
                    'mobile_phone_insured' => $customer[0]->mobile_phone,
                    'address_insured' => $customer[0]->address,
                    'province_id_insured' => $customer[0]->province_id,
                    'province_insured' => $customer[0]->province_id,
                    'province_name_insured' => $customer[0]->province_name,
                    'city_id_insured' => $customer[0]->city,
                    'city_name_insured' => $customer[0]->city_name,
                    'document_id_insured' => $customer[0]->document_id,
                    'birthdate_insured' => $customer[0]->birthdate,
                    'nacionality_insured' => $customer[0]->nacionality_id,
                    'gender_insured' => $customer[0]->gender_id,
                    'civil_state_insured' => $customer[0]->civil_status_id,
                    'profession_insured' => $customer[0]->profession,
                    'activity_insured' => $customer[0]->activity,
                    'work_address_insured' => $customer[0]->work_address,
                    'correspondence_insured' => $customer[0]->correspondence_id,
                    'second_name_insured' => $customer[0]->second_name,
                    'second_last_name_insured' => $customer[0]->second_last_name,
                    'success' => 'true');
            } else {
                $data = array('first_name' => '',
                    'last_name' => '',
                    'second_name' => '',
                    'second_last_name' => '',
                    'document' => $id,
                    'email' => '',
                    'city' => '',
                    'document_id' => '0',
                    'success' => 'false');
            }
        }
        return $data;
    }

    //Validate Representative Document
    public function documentRepresentativeAutoFill($id) {
        $sql = 'select rep.*
                    from representatives rep
                    where rep.document = "' . $id . '"';

        $representative = DB::select($sql);
        if ($representative) {
            $data = array('first_name_representative' => $representative[0]->first_name,
                'last_name_representative' => $representative[0]->last_name,
                'document_representative' => $representative[0]->document,
                'document_id_representative' => $representative[0]->document_id,
                'birthdate_representative' => $representative[0]->birthdate,
                'nationality_representative' => $representative[0]->nacionality_id,
                'relationship_representative' => $representative[0]->relationship_id,
                'relationship_representative' => $representative[0]->relationship_id,
                'gender_representative' => $representative[0]->gender_id,
                'success' => 'true');
        } else {
            $data = array('first_name' => '',
                'last_name' => '',
                'document' => $id,
                'email' => '',
                'city' => '',
                'document_id' => '0',
                'success' => 'false');
        }
        return $data;
    }

    public function index(request $request) {
        //Validate if User has view Permit
        $viewPermit = checkViewPermit('60', \Auth::user()->role_id);
        if (!$viewPermit) {
            \Session::flash('ValidateUserRoute', 'No tiene acceso al modulo de ventas Individual.');
            return view('home');
        }

        //Obtain Edit Permission
        $edit = checkExtraPermits('51', \Auth::user()->role_id);

        //Obtain Create Permission
        $create = checkExtraPermits('53', \Auth::user()->role_id);

        //Obtain Cancel Permission
        $cancel = checkExtraPermits('52', \Auth::user()->role_id);

        //Store Form Variables in Session
        if ($request->isMethod('post')) {
            session(['customerItems' => $request->items]);
            session(['customerDocument' => $request->document]);
            session(['customerFirstName' => $request->first_name]);
            session(['customerLastName' => $request->last_name]);
            session(['customerCity' => $request->city]);
            $currentPage = 1;
            session(['customerPage' => 1]);
        } else {
            $currentPage = session('customerPage');
        }

        $cities = \App\city::all();

        //Pagination Items
        if (session('customerItems') == null) {
            $items = 10;
        } else {
            $items = session('customerItems');
        }

        //Form Variables
        $document = session('customerDocument');
        $firstName = session('customerFirstName');
        $lastName = session('customerLastName');
        $city = session('customerCity');

        // Make sure that you call the static method currentPageResolver()
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });

        //Customers
        $customers = customers($document, $lastName, $firstName, $city, $items);


        return view('customer.index', [
            'customers' => $customers,
            'items' => $items,
            'cities' => $cities,
            'edit' => $edit,
            'cancel' => $cancel,
            'create' => $create
        ]);
    }

    function fetch_data(Request $request) {
        if ($request->ajax()) {
            //Page
            session(['customerPage' => $request->page]);

            //Obtain Edit Permission
            $edit = checkExtraPermits('51', \Auth::user()->role_id);

            //Obtain Create Permission
            $create = checkExtraPermits('53', \Auth::user()->role_id);

            //Obtain Cancel Permission
            $cancel = checkExtraPermits('52', \Auth::user()->role_id);

            //Pagination Items
            if (session('customerItems') == null) {
                $items = 10;
            } else {
                $items = session('customerItems');
            }

            //Form Variables
            $document = session('customerDocument');
            $firstName = session('customerFirstName');
            $lastName = session('customerLastName');
            $city = session('customerCity');

            //Customers
            $customers = customers($document, $lastName, $firstName, $city, $items);

            return view('pagination.customer', [
                'customers' => $customers,
                'items' => $items,
                'edit' => $edit,
                'cancel' => $cancel,
                'create' => $create
            ]);
        }
    }

    public function resume(request $request) {
        $returnTable = '';
        //Providerss Data
        $customer = \App\customers::selectRaw('customers.document, customers.first_name, customers.last_name, customers.address, customers.phone, customers.mobile_phone, cit.name as "citName", doc.name as "docName", customers.email')
                ->join('cities as cit', 'cit.id', '=', 'customers.city_id')
                ->join('documents as doc', 'doc.id', '=', 'customers.document_id')
                ->where('customers.id', '=', $request['id'])
                ->get();
        //Sales Data
        $sales = \App\sales::selectRaw('sales.id, DATE_FORMAT(sales.emission_date,"%d-%m-%Y") as "emission_date", DATE_FORMAT(sales.begin_date,"%d-%m-%Y") as "begin_date", DATE_FORMAT(sales.end_date,"%d-%m-%Y") as "end_date", chan.name as "chaName", sta.name as "staName",stype.name as "stypeName"')
                ->join('status as sta', 'sta.id', '=', 'sales.status_id')
                ->join('agencies as agen', 'agen.id', '=', 'sales.agen_id')
                ->join('channels as chan', 'chan.id', '=', 'agen.channel_id')
                ->join('sales_type as stype', 'stype.id', '=', 'sales.sales_type_id')
                ->where('sales.customer_id', '=', $request['id'])
                ->get();
        $returnTable .= '<h4>Cliente:</h4>';
        //Return Table Providers
        $returnTable .= '<table class="table table-bordered">
                            <thead>
                              <tr>
                                <th align="center" style="background-color: #183c6b;color: white;">Documento</th>
                                <th align="center" style="background-color: #183c6b;color: white;">Tipo Documento</th>
                                <th align="center" style="background-color: #183c6b;color: white;">Nombres</th>
                                <th align="center" style="background-color: #183c6b;color: white;">Dirección</th>
                                <th align="center" style="background-color: #183c6b;color: white;">Télefono</th>
                                <th align="center" style="background-color: #183c6b;color: white;">Celular</th>
                                <th align="center" style="background-color: #183c6b;color: white;">Correo</th>
                                <th align="center" style="background-color: #183c6b;color: white;">Ciudad</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td align="center">' . $customer[0]->document . '</td>
                                <td align="center">' . $customer[0]->docName . '</td>
                                <td align="center">' . $customer[0]->first_name . ' ' . $customer[0]->last_name . '</td>
                                <td align="center">' . $customer[0]->address . '</td>
                                <td align="center">' . $customer[0]->phone . '</td>
                                <td align="center">' . $customer[0]->mobile_phone . '</td>
                                <td align="center">' . $customer[0]->email . '</td>
                                <td align="center">' . $customer[0]->citName . '</td>
                              </tr>
                            </tbody>
                          </table>';

        $returnTable .= '<h4>Ventas:</h4>';
        //Return Table Agency
        $returnTable .= '<table id="tableChannelResume2" class="table table-bordered">
                                <thead>
                                  <tr>
                                    <th align="center" style="background-color: #183c6b;color: white;">N° Venta</th>
                                    <th align="center" style="background-color: #183c6b;color: white;">Fecha Emisión</th>
                                    <th align="center" style="background-color: #183c6b;color: white;">Fecha Inicio</th>
                                    <th align="center" style="background-color: #183c6b;color: white;">Fecha Fin</th>
                                    <th align="center" style="background-color: #183c6b;color: white;">Canal</th>
                                    <th align="center" style="background-color: #183c6b;color: white;">Estado</th>
                                    <th align="center" style="background-color: #183c6b;color: white;">Tipo</th>
                                  </tr>
                                </thead>
                                <tbody>';
        foreach ($sales as $sal) {
            $returnTable .= '     <tr>
                                    <td align="center">' . $sal->id . '</td>
                                    <td align="center">' . $sal->emission_date . '</td>
                                    <td align="center">' . $sal->begin_date . '</td>
                                    <td align="center">' . $sal->end_date . '</td>
                                    <td align="center">' . $sal->chaName . '</td>
                                    <td align="center">' . $sal->staName . '</td>
                                    <td align="center">' . $sal->stypeName . '</td>
                                  </tr>';
        }
        $returnTable .= '</tbody>
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

    public function create(request $request) {
        //Obtain Data
        $countries = \App\country::all();
        $documents = \App\document::all();
        return view('customer.create', [
            'countries' => $countries,
            'documents' => $documents
        ]);
    }

    public function store(request $request) {
        //Form variables
        $document = $request['document'];
        $document_id = $request['document_id'];
        $first_name = $request['first_name'];
        $last_name = $request['last_name'];
        $mobile_phone = $request['mobile_phone'];
        $address = $request['address'];
        $cityId = $request['city'];
        $provinceId = $request['province'];
        $phone = $request['phone'];
        $email = $request['email'];
        $errorMsg = false;

        //Validate Form Variables
        //Mobile Phone
        if (!is_numeric($mobile_phone)) { $errorMsg = true; \Session::flash('errorMobilePhone', 'El télefono celular debe ser numérico'); } else if (strlen($mobile_phone) != 10) { $errorMsg = true; \Session::flash('errorMobilePhone', 'El télefono celular debe contener 10 caracteres'); }
        //Phone
        if (!is_numeric($phone)) { $errorMsg = true; \Session::flash('errorPhone', 'El télefono debe ser numérico'); } else if (strlen($phone) != 9) { $errorMsg = true; \Session::flash('errorPhone', 'El télefono debe contener 9 caracteres'); }
        //Address
        if (!isset($address)) { $errorMsg = true; \Session::flash('errorAddress', 'La dirección no puede estar vacia'); }
        //Email
        if (!isset($email)) { $errorMsg = true; \Session::flash('errorEmail', 'El Email no puede estar vacio'); } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $errorMsg = true; \Session::flash('errorEmail', 'El Email no tiene el formato correcto'); }
        //Province
        if (!isset($provinceId)) { $errorMsg = true; \Session::flash('errorProvince', 'Debe seleccionar una provincia'); } else { $province = \App\province::find($provinceId); $provinceName = $province->name; \Session::flash('provinceName', $provinceName); }
        //City
        if (!isset($cityId)) { $errorMsg = true; \Session::flash('errorCity', 'Debe seleccionar una ciudad'); } else { $city = \App\city::find($cityId); $cityName = $city->name; \Session::flash('cityName', $cityName); }

        //Customer Exists?
        $customerValidate = \App\customers::where('document', '=', $document)->get();
        if ($customerValidate->isEmpty()) {
            if ($document_id == '1') {
                $documentValidate = validateId($document);
                if (!$documentValidate) {
                    $errorMsg = true;
                    \Session::flash('errorDocument', 'El documento ingresado es invalido');
                }
            } else if ($document_id == '2') {
                if (!is_numeric($document)) {
                    $errorMsg = true;
                    \Session::flash('errorDocument', 'El documento ingresado debe ser numerico');
                } else if (strlen($document) != 13) {
                    $errorMsg = true;
                    \Session::flash('errorDocument', 'El documento ingresado debe contener 13 digitos');
                }
            }
            if (!isset($first_name)) {
                $errorMsg = true;
                \Session::flash('errorFirstName', 'El nombre no puede estar vacio');
            }
            if (!isset($last_name)) {
                $errorMsg = true;
                \Session::flash('errorLastName', 'El apellido no puede estar vacio');
            }
        }
        if ($errorMsg == true) {
            return back()->withInput($request->all());
        } else {
            $customerValidate = \App\customers::where('document', '=', $document)->get();
            if ($customerValidate->isEmpty()) {
                $customerNew = new \App\customers();
                $customerNew->document_id = $document_id;
                $customerNew->document = $document;
                $customerNew->first_name = $first_name;
                $customerNew->last_name = $last_name;
                $customerNew->address = $address;
                $customerNew->city_id = $cityId;
                $customerNew->phone = $phone;
                $customerNew->mobile_phone = $mobile_phone;
                $customerNew->email = $email;
                $customerNew->status_id = 1;
                $customerNew->save();
            } else {
                $customerUpdate = \App\customers::find($customerValidate[0]->id);
                $customerUpdate->address = $address;
                $customerUpdate->city_id = $cityId;
                $customerUpdate->phone = $phone;
                $customerUpdate->mobile_phone = $mobile_phone;
                $customerUpdate->email = $email;
                $customerUpdate->status_id = 1;
                $customerUpdate->save();
            }
            return redirect('customer');
        }
    }
    
    public function storeData(request $request){
        $customerSql = 'select * from customers where document = "' . $request['document'] . '"';
        $customer = DB::select($customerSql);

        //Validate Customer Save or Update
        if ($customer) {
            $customerUpdate = \App\customers::find($customer[0]->id);
            $customerUpdate->address = $request['address'];
            $customerUpdate->city_id = $request['city'];
            $customerUpdate->phone = $request['phone'];
            $customerUpdate->mobile_phone = $request['mobile_phone'];
            $customerUpdate->email = $request['email'];
            $customerUpdate->save();
            $customerId = $customerUpdate->id;
            session(['salesCreateLifeCustomerId' => $customerUpdate->id]);
        } else {
            $customerNew = new \App\customers();
            $customerNew->first_name = $request['first_name'];
            $customerNew->last_name = $request['last_name'];
            $customerNew->document = $request['document'];
            $customerNew->document_id = $request['document_id'];
            $customerNew->address = $request['address'];
            $customerNew->city_id = $request['city'];
            $customerNew->phone = $request['phone'];
            $customerNew->mobile_phone = $request['mobile_phone'];
            $customerNew->email = $request['email'];
            $customerNew->status_id = 1;
            $customerNew->save();
            $customerId = $customerNew->id;
            session(['salesCreateLifeCustomerId' => $customerNew->id]);
        }
        
        $customer = \App\customers::find($customerId);
        
        $insured = \App\insured::where('document','=',$customer->document)->get();
        if($insured->isEmpty()){
            $insuredNew = new \App\insured();
            $insuredNew->first_name = $customer->first_name;
            $insuredNew->last_name = $customer->last_name;
            $insuredNew->document = $customer->document;
            $insuredNew->document_id = $customer->document_id;
            $insuredNew->address = $customer->address;
            $insuredNew->city_id = $customer->city_id;
            $insuredNew->phone = $customer->phone;
            $insuredNew->mobile_phone = $customer->mobile_phone;
            $insuredNew->email = $customer->email;
            $insuredNew->status_id = 1;
            $insuredNew->save();
        }else{
            $insuredUpdate = \App\insured::find($insured[0]->id);
            $insuredUpdate->address = $customer->address;
            $insuredUpdate->city_id = $customer->city_id;
            $insuredUpdate->phone = $customer->phone;
            $insuredUpdate->mobile_phone = $customer->mobile_phone;
            $insuredUpdate->email = $customer->email;
            $insuredUpdate->save();
        }
//        $view = app('App\Http\Controllers\SalesController')->assetView($request['insurance_branch'],$customerId);
        return $customerId;
    }
    
    public function storeInsuredData(request $request){        
        $insured = \App\insured::where('document','=',$request['document_insured'])->get();
        $customer = \App\customers::where('document','=',$request['document_insured'])->get();
        
        if($insured->isEmpty()){
            if($customer->isEmpty()){
                $insuredNew = new \App\insured();
                $insuredNew->first_name = $request['first_name_insured'];
                $insuredNew->last_name = $request['last_name_insured'];
                $insuredNew->document = $request['document_insured'];
                $insuredNew->document_id = $request['document_id_insured'];
                $insuredNew->address = $request['address_insured'];
                $insuredNew->city_id = $request['city_insured'];
                $insuredNew->phone = $request['phone_insured'];
                $insuredNew->mobile_phone = $request['mobile_phone_insured'];
                $insuredNew->email = $request['email_insured'];
                $insuredNew->status_id = 1;
                $insuredNew->save();
                $insuredId = $insuredNew->id;
            session(['salesCreateLifeInsuredId' => $insuredNew->id]);
            }else{
                $insuredNew = new \App\insured();
                $insuredNew->first_name = $customer[0]->first_name;
                $insuredNew->last_name = $customer[0]->last_name;
                $insuredNew->document = $customer[0]->document;
                $insuredNew->document_id = $customer[0]->document_id;
                $insuredNew->address = $customer[0]->address;
                $insuredNew->city_id = $customer[0]->city_id;
                $insuredNew->phone = $customer[0]->phone;
                $insuredNew->mobile_phone = $customer[0]->mobile_phone;
                $insuredNew->email = $customer[0]->email;
                $insuredNew->status_id = 1;
                $insuredNew->save();
                $insuredId = $insuredNew->id;
                session(['salesCreateLifeInsuredId' => $insuredNew->id]);
            }
        }else{
            $insuredUpdate = \App\insured::find($insured[0]->id);
            $insuredUpdate->address = $request['address_insured'];
            $insuredUpdate->city_id = $request['city_insured'];
            $insuredUpdate->phone = $request['phone_insured'];
            $insuredUpdate->mobile_phone = $request['mobile_phone_insured'];
            $insuredUpdate->email = $request['email_insured'];
            $insuredUpdate->save();
            $insuredId = $insuredUpdate->id;
            session(['salesCreateLifeInsuredId' => $insuredUpdate->id]);
        }
        
        return $insuredId;
    }

    public function edit(request $request) {
        //Obtain Data
        $customer = \App\customers::find($request['customerId']);
        $countries = \App\country::all();
        $documents = \App\document::all();
        $cityId = \App\city::find($customer->city_id);
        $provinceId = \App\province::find($cityId->province_id);
        $countryId = \App\country::find($provinceId->country_id);
        $cities = \App\city::where('province_id', '=', $provinceId->id)->orderBy('name', 'ASC')->get();
        $provinces = \App\province::where('country_id', '=', $countryId->id)->orderBy('name', 'ASC')->get();

        return view('customer.edit', [
            'countries' => $countries,
            'documents' => $documents,
            'customer' => $customer,
            'cityId' => $cityId->id,
            'provinceId' => $provinceId->id,
            'countryId' => $countryId->id,
            'cities' => $cities,
            'provinces' => $provinces
        ]);
    }

    public function editValidate(request $request) {
        //Form variables
        $document = $request['document'];
        $document_id = $request['document_id'];
        $first_name = $request['first_name'];
        $last_name = $request['last_name'];
        $mobile_phone = $request['mobile_phone'];
        $address = $request['address'];
        $cityId = $request['city'];
        $countryId = $request['country'];
        $provinceId = $request['province'];
        $phone = $request['phone'];
        $email = $request['email'];
        $errorMsg = false;

        //Validate Form Variables
        //Mobile Phone
        if (!is_numeric($mobile_phone)) { $errorMsg = true; \Session::flash('errorMobilePhone', 'El télefono celular debe ser numérico'); } else if (strlen($mobile_phone) != 10) { $errorMsg = true; \Session::flash('errorMobilePhone', 'El télefono celular debe contener 10 caracteres'); }
        //Phone
        if (!is_numeric($phone)) { $errorMsg = true; \Session::flash('errorPhone', 'El télefono debe ser numérico'); } else if (strlen($phone) != 9) { $errorMsg = true; \Session::flash('errorPhone', 'El télefono debe contener 9 caracteres'); }
        //Address
        if (!isset($address)) { $errorMsg = true; \Session::flash('errorAddress', 'La dirección no puede estar vacia'); }
        //Email
        if (!isset($email)) { $errorMsg = true; \Session::flash('errorEmail', 'El Email no puede estar vacio'); } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $errorMsg = true; \Session::flash('errorEmail', 'El Email no tiene el formato correcto'); }
        //Country
        if (!isset($countryId)) { $errorMsg = true; \Session::flash('errorCountry', 'Debe seleccionar un pais'); } else { $country = \App\country::find($countryId); $countryName = $country->name; \Session::flash('countryName', $countryName); }
        //Province
        if (!isset($provinceId)) { $errorMsg = true; \Session::flash('errorProvince', 'Debe seleccionar una provincia'); } else { $province = \App\province::find($provinceId); $provinceName = $province->name; \Session::flash('provinceName', $provinceName); }
        //City
        if (!isset($cityId)) { $errorMsg = true; \Session::flash('errorCity', 'Debe seleccionar una ciudad'); } else { $city = \App\city::find($cityId); $cityName = $city->name; \Session::flash('cityName', $cityName); }

        //Customer Exists?
        $customerValidate = \App\customers::where('document', '=', $document)->get();
        if ($customerValidate->isEmpty()) {
            if ($document_id == '1') {
                $documentValidate = validateId($document);
                if (!$documentValidate) {
                    $errorMsg = true;
                    \Session::flash('errorDocument', 'El documento ingresado es invalido');
                }
            } else if ($document_id == '2') {
                if (!is_numeric($document)) {
                    $errorMsg = true;
                    \Session::flash('errorDocument', 'El documento ingresado debe ser numerico');
                } else if (strlen($document) != 13) {
                    $errorMsg = true;
                    \Session::flash('errorDocument', 'El documento ingresado debe contener 13 digitos');
                }
            }
            if (!isset($first_name)) {
                $errorMsg = true;
                \Session::flash('errorFirstName', 'El nombre no puede estar vacio');
            }
            if (!isset($last_name)) {
                $errorMsg = true;
                \Session::flash('errorLastName', 'El apellido no puede estar vacio');
            }
        }
        if ($errorMsg == true) {
            return 'error';
        } else {
            $customerValidate = \App\customers::where('document', '=', $document)->get();
            if ($customerValidate->isEmpty()) {
                $customerNew = new \App\customers();
                $customerNew->document_id = $document_id;
                $customerNew->document = $document;
                $customerNew->first_name = $first_name;
                $customerNew->last_name = $last_name;
                $customerNew->address = $address;
                $customerNew->city_id = $cityId;
                $customerNew->phone = $phone;
                $customerNew->mobile_phone = $mobile_phone;
                $customerNew->email = $email;
                $customerNew->status_id = 1;
                $customerNew->save();
            } else {
                $customerUpdate = \App\customers::find($customerValidate[0]->id);
                $customerUpdate->address = $address;
                $customerUpdate->city_id = $cityId;
                $customerUpdate->phone = $phone;
                $customerUpdate->mobile_phone = $mobile_phone;
                $customerUpdate->email = $email;
                $customerUpdate->status_id = 1;
                $customerUpdate->save();
            }
            return 'success';
        }
    }
    
    public function obtainDataSale(request $request){
        //Obtain Customer ID
        $sale = \App\sales::find($request['saleId']);
        
        //Obtain Customer Data
        $customer = \App\customers::find($sale->customer_id);
        $document = \App\document::find($customer->document_id);
        $countries = \App\country::all();
        
        //Customer Form
        
        $returnArray = [
            'customer' => $customer,
            'document' => $document,
            'countries' => $countries
        ];
        
        return $returnArray;
    }

}
