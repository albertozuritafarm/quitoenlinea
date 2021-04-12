<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Excel;
use File;
use Session;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;
use Carbon\Carbon;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Style\Border;
use Box\Spout\Writer\Style\BorderBuilder;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\WriterFactory;
use App\Jobs\EmailJobs;
use Illuminate\Pagination\Paginator;

class MassiveController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('validateUserRoute');
    }

    public function index(request $request) {
        //Validate if User has view Permit
        $viewPermit = checkViewPermit('10', \Auth::user()->role_id);
        if(!$viewPermit){
            \Session::flash('ValidateUserRoute', 'No tiene acceso al modulo de ventas Masivas.');
            return view('home');
        }
        
        //Obtain Edit Permission
        $edit = checkExtraPermits('10',\Auth::user()->role_id);
        
        //Obtain Create Permission
        $create = checkExtraPermits('21',\Auth::user()->role_id);
        
        //Obtain Cancel Permission
        $cancel = checkExtraPermits('11',\Auth::user()->role_id);
        
        //Obtain Channel
        $channelQuery = 'select cha.* from channels cha join agencies agen on agen.channel_id = cha.id where agen.id =  "' . \Auth::user()->agen_id . '"';
        $channel = DB::select($channelQuery);

        //CHANNELS
        if(\Auth::user()->role_id == 4){
            $channelSql = 'select chan.* from channels chan join agencies agen on agen.channel_id = chan.id where agen.id = '.\Auth::user()->agen_id;
            $channels = DB::select($channelSql);
        }else{
            $channels = \App\channels::all();
        }
        
        //Store Form Variables in Session
        if ($request->isMethod('post')) {
            session(['massiveFirstViewChannel' => $request->channel]);
            session(['massiveFirstViewBeginDate' => $request->beginDate]);
            session(['massiveFirstViewEndDate' => $request->endDate]);
            session(['massiveFirstViewType' => $request->type]);
            session(['massiveFirstViewStatus' => $request->statusMassive]);
            session(['massiveFirstViewStatusPayment' => $request->statusPayment]);
            session(['massiveFirstViewItems' => $request->items]);
            $currentPage = 1;
            session(['massivePage' => 1]);
        }else{
            $currentPage = session('massivePage');
        }

        //Pagination Items
        if (session('massiveFirstViewItems') == null) { $items = 10; } else { $items = session('massiveFirstViewItems'); }

        //Form Variables
        $channelForm = session('massiveFirstViewChannel');
        $beginDate = session('massiveFirstViewBeginDate');
        $endDate = session('massiveFirstViewEndDate');
        $type = session('massiveFirstViewType');
        $statusMassiveForm = session('massiveFirstViewStatus');
        $statusPayment = session('massiveFirstViewStatusPayment');

        if (\Auth::user()->role_id == 1 || \Auth::user()->role_id == 2) { $userRol = null; } else { $userRol = false; }

        // Make sure that you call the static method currentPageResolver()
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });
        
        $newMassives = massivesFirstView($channelForm, $beginDate, $endDate, $type, $statusMassiveForm, $statusPayment, $items, $userRol, $channel);

        //STATUS MASSIVE
        $statusMassive = \App\status::find([1, 14, 15]);

        //STATUS CHARGE
        $statusCharge = \App\status::find([12, 9, 15]);

        //TYPE
        $types = \App\massive_types::all();

        //Store Main View
        session(['massiveIndex' => '/massive']);
        
        return view('massive.index', [
            "massives" => $newMassives,
            "channels" => $channels,
            "statusMassive" => $statusMassive,
            "statusCharge" => $statusCharge,
            "massiveTypes" => $types,
            "items" => $items,
            "edit" => $edit,
            "cancel" => $cancel,
            "create" => $create
        ]);
    }
    
    function fetch_data(request $request) {
        if ($request->ajax()) {
            //Page
            session(['massivePage' => $request->page]);
            
            //Obtain Edit Permission
            $edit = checkExtraPermits('10',\Auth::user()->role_id);

            //Obtain Create Permission
            $create = checkExtraPermits('21',\Auth::user()->role_id);

            //Obtain Cancel Permission
            $cancel = checkExtraPermits('11',\Auth::user()->role_id);
        
            //Obtain Channel
            $channelQuery = 'select cha.* from channels cha join agencies agen on agen.channel_id = cha.id where agen.id =  "' . \Auth::user()->agen_id . '"';
            $channel = DB::select($channelQuery);
            
            //Pagination Items
            if (session('massiveItems') == null) {
                $items = 10;
            } else {
                $items = session('massiveItems');
            }

            //Form Variables
            $channelForm = session('massiveFirstViewChannel');
            $beginDate = session('massiveFirstViewBeginDate');
            $endDate = session('massiveFirstViewEndDate');
            $type = session('massiveFirstViewType');
            $statusMassiveForm = session('massiveFirstViewStatus');
            $statusPayment = session('massiveFirstViewStatusPayment');

            if (\Auth::user()->role_id == 1 || \Auth::user()->role_id == 2) {
                $userRol = null;
            } else {
                $userRol = false;
            }

        $newMassives = massivesFirstView($channelForm, $beginDate, $endDate, $type, $statusMassiveForm, $statusPayment, $items, $userRol, $channel);

            return view('pagination.massives', [
                "massives" => $newMassives,
                "items" => $items,
                "edit" => $edit,
                "cancel" => $cancel,
                "create" => $create
            ]);
        }
    }
    public function indexSecondary(request $request) {
        //Validate if User has view Permit
        $viewPermit = checkViewPermit('10', \Auth::user()->role_id);
        if(!$viewPermit){
            \Session::flash('ValidateUserRoute', 'No tiene acceso al modulo de ventas Masivas.');
            return view('home');
        }
        
        //Obtain Edit Permission
        $edit = checkExtraPermits('10',\Auth::user()->role_id);
        
        //Obtain Create Permission
        $create = checkExtraPermits('21',\Auth::user()->role_id);
        
        //Obtain Cancel Permission
        $cancel = checkExtraPermits('11',\Auth::user()->role_id);
        
        //Obtain Channel
        $channelQuery = 'select cha.* from channels cha join agencies agen on agen.channel_id = cha.id where agen.id =  "' . \Auth::user()->agen_id . '"';
        $channel = DB::select($channelQuery);

        //CHANNELS
        if (\Auth::user()->role_id == 4) {
            $channelSql = 'select chan.* from channels chan join agencies agen on agen.channel_id = chan.id where agen.id = ' . \Auth::user()->agen_id;
            $channels = DB::select($channelSql);
        } else {
            $channels = \App\channels::all();
        }

        //Store Form Variables in Session
        if ($request->isMethod('post')) {
            session(['massiveSecondaryChannel' => $request->channel]);
            session(['massiveSecondaryBeginDate' => $request->beginDate]);
            session(['massiveSecondaryEndDate' => $request->endDate]);
            session(['massiveSecondaryPlateSearch' => $request->plateSearch]);
            session(['massiveSecondaryType' => $request->type]);
            session(['massiveSecondaryStatus' => $request->statusMassive]);
            session(['massiveSecondaryStatusPayment' => $request->statusPayment]);
            session(['massiveSecondarySaleId' => $request->saleId]);
            session(['massiveSecondaryMassId' => $request->massId]);
            session(['massiveSecondaryCusName' => $request->cusName]);
            session(['massiveSecondaryCusDoc' => $request->cusDoc]);
            session(['massiveSecondaryItems' => $request->items]);
            $currentPage = 1;
            session(['massiveSecondaryPage' => 1]);
        }else{
            $currentPage = session('massiveSecondaryPage');
        }

        //Pagination Items
        if (session('massiveSecondaryItems') == null) {
            $items = 10;
        } else {
            $items = session('massiveSecondaryItems');
        }

        //Form Variables
        $channelForm = session('massiveSecondaryChannel');
        $beginDate = session('massiveSecondaryBeginDate');
        $endDate = session('massiveSecondaryEndDate');
        $plate = session('massiveSecondaryPlateSearch');
        $type = session('massiveSecondaryType');
        $statusMassiveForm = session('massiveSecondaryStatus');
        $statusPayment = session('massiveStatusSecondaryPayment');
        $sale = session('massiveSecondarySaleId');
        $massive = session('massiveSecondaryMassId');
        $cusName = session('massiveSecondaryCusName');
        $cusDoc = session('massiveSecondaryCusDoc');

        if (\Auth::user()->role_id == 1 || \Auth::user()->role_id == 2) {
            $userRol = null;
        } else {
            $userRol = false;
        }

        // Make sure that you call the static method currentPageResolver()
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });
        
        $newMassives = massivesSecondary($channelForm, $beginDate, $endDate, $type, $statusMassiveForm, $statusPayment, $plate, $sale, $items, $userRol, $channel, $massive, $cusName, $cusDoc);

        //STATUS MASSIVE
        $statusMassive = \App\status::find([16,17,3,4]);

        //STATUS CHARGE
        $statusCharge = \App\status::find([12, 9, 15]);

        //TYPE
        $types = \App\massive_types::all();

        //Store Main View
        session(['massiveIndex' => '/massive/secondary']);
        
        return view('massive.indexSecondary', [
            "massives" => $newMassives,
            "channels" => $channels,
            "statusMassive" => $statusMassive,
            "statusCharge" => $statusCharge,
            "massiveTypes" => $types,
            "items" => $items,
            "edit" => $edit,
            "cancel" => $cancel,
            "create" => $create
        ]);
    }
    
    function fetch_dataSecondary(request $request) {
        if ($request->ajax()) {
            //Page
            session(['massiveSecondaryPage' => $request->page]);
            
            //Obtain Edit Permission
            $edit = checkExtraPermits('10',\Auth::user()->role_id);

            //Obtain Create Permission
            $create = checkExtraPermits('21',\Auth::user()->role_id);

            //Obtain Cancel Permission
            $cancel = checkExtraPermits('11',\Auth::user()->role_id);
        
            //Obtain Channel
            $channelQuery = 'select cha.* from channels cha join agencies agen on agen.channel_id = cha.id where agen.id =  "' . \Auth::user()->agen_id . '"';
            $channel = DB::select($channelQuery);
            
            //Pagination Items
            if (session('massiveSecondaryItems') == null) {
                $items = 10;
            } else {
                $items = session('massiveSecondaryItems');
            }

            //Form Variables
            $channelForm = session('massiveSecondaryChannel');
            $beginDate = session('massiveSecondaryBeginDate');
            $endDate = session('massiveSecondaryEndDate');
            $type = session('massiveSecondaryType');
            $statusMassiveForm = session('massiveSecondaryStatus');
            $statusPayment = session('massiveSecondaryStatusPayment');
            $plate = session('massiveSecondaryPlateSearch');
            $sale = session('massiveSecondarySaleId');
            $massive = session('massiveSecondaryMassId');
            $cusName = session('massiveSecondaryCusName');
            $cusDoc = session('massiveSecondaryCusDoc');

            if (\Auth::user()->role_id == 1 || \Auth::user()->role_id == 2) {
                $userRol = null;
            } else {
                $userRol = false;
            }

            $newMassives = massivesSecondary($channelForm, $beginDate, $endDate, $type, $statusMassiveForm, $statusPayment, $plate, $sale, $items, $userRol, $channel, $massive, $cusName, $cusDoc);

            return view('pagination.massivesSecondary', [
                "massives" => $newMassives,
                "items" => $items,
                "edit" => $edit,
                "cancel" => $cancel,
                "create" => $create
            ]);
        }
    }

    public function create() {
        //Validate Create Permission
        $edit = checkExtraPermits('21',\Auth::user()->role_id);
        if(!$edit){
            \Session::flash('ValidateUserRoute', 'No tiene acceso a crear ventas Masivas.');
            return view('home');
        }
        
        //PRODUCTS
        //Obtain Channel
        $channelQuery = 'select cha.* from channels cha join agencies agen on agen.channel_id = cha.id where agen.id =  "' . \Auth::user()->agen_id . '"';
        $channel = DB::select($channelQuery);

        //Product Data
//        $products = DB::select('select pro.* from products pro join products_channel pCha on pCha.product_id = pro.id where pro.status_id = "1" and pCha.channel_id = "'.$channel[0]->id.'"');
        $products = DB::select('select DISTINCT pro.id as "PROID", pro.* from products pro join products_channel pCha on pCha.product_id = pro.id where pro.status_id = "1" and pro.product_type in ("MASIVO","AMBOS")');
//        $products = \App\products::all();
        //Channels 
        $channels = \App\channels::find([4]);

        return view('massive.create', [
            "products" => $products,
            "channels" => $channels
        ]);
    }

    public function store(request $request) {
//        return $request;
        //validate the xls file
        $this->validate($request, array(
            'file' => 'required'
        ));

        if ($request->hasFile('file')) {
            $extension = File::extension($request->file->getClientOriginalName());
            if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {

                $path = $request->file->getRealPath();
                $collection = (new FastExcel)->import($path);

                $errorReturn = array();
                $errorResponse = array();
                $saleMassive = array();
                $subtotalMassive = 0;


                //Turn data in JSON
                foreach ($collection as $data) {

                    //INITIATE ERROR VARIABLE
                    $error = false;

                    //OBTAIN BRAND
                    $brand = \App\vehiclesBrands::where('name', $data['MARCA'])->first();

                    //VALIDATE BRAND
                    if ($brand) {
                        //NADA
                    } else {
                        $error = true;
                        $errorMessage = [
                            "placa" => $data['PLACA'],
                            "error" => 'La marca ' . $data['MARCA'] . ' no se encuentra en nustra base de datos'
                        ];
                        array_push($errorReturn, $errorMessage);
                    }

                    //VALIDATE PLATE
                    if (strlen($data['PLACA']) == 7 || strlen($data['PLACA']) == 9 || strlen($data['PLACA']) == 11) {
                        //NADA
                    } else {
                        $error = true;
                        $errorMessage = [
                            "placa" => $data['PLACA'],
                            "error" => 'La placa ' . $data['PLACA'] . ' no tiene el formato correcto'
                        ];
                        array_push($errorReturn, $errorMessage);
                    }

                    //VALIDATE CITY
                    //OBTAIN CITY
                    $city = \App\city::where('name', $data['CIUDAD'])->first();
                    if ($city) {
                        //NADA
                    } else {
                        $error = true;
                        $errorMessage = [
                            "placa" => $data['PLACA'],
                            "error" => 'La ciudad ' . $data['CIUDAD'] . ' no se encuentra registrada en nuestra base de datos'
                        ];
                        array_push($errorReturn, $errorMessage);
                    }
                    //VALIDATE MOBILE PHONE
                    if ($data['TELÉFONO MÓVIL'] == '') {
                        $error = true;
                        $errorMessage = [
                            "placa" => $data['PLACA'],
                            "error" => 'El Telefono Movil no puede estar vacio.'
                        ];
                        array_push($errorReturn, $errorMessage);
                    } else if (strlen($data['TELÉFONO MÓVIL']) == 10) {
                        if (string_has_letters($data['TELÉFONO MÓVIL'])) {
                            $error = true;
                            $errorMessage = [
                                "placa" => $data['PLACA'],
                                "error" => 'El Telefono Movil solo puede contener numeros.'
                            ];
                            array_push($errorReturn, $errorMessage);
                        }
                    } else {
                        $error = true;
                        $errorMessage = [
                            "placa" => $data['PLACA'],
                            "error" => 'El telefono movil ' . $data['TELÉFONO MÓVIL'] . ' no cumple con los requisitos, debe ser 10 digitos'
                        ];
                        array_push($errorReturn, $errorMessage);
                    }
                    //VALIDATE EMAIL
                    $email = $data['EMAIL'];
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $error = true;
                        $errorMessage = [
                            "placa" => $data['PLACA'],
                            "error" => 'El email ' . $data['TELÉFONO MÓVIL'] . ' no es un email valido'
                        ];
                        array_push($errorReturn, $errorMessage);
                    }
                    //VALIDATE ID LENGTH
                    if ($data['ID'] == '') {
                        $error = true;
                        $errorMessage = [
                            "placa" => $data['PLACA'],
                            "error" => 'El ID no puede estar vacio.'
                        ];
                        array_push($errorReturn, $errorMessage);
                    } else if (strlen(trim($data['ID'])) == 10) {
                        if (!is_numeric($data['ID'])) {
                            $error = true;
                            $errorMessage = [
                                "placa" => $data['PLACA'],
                                "error" => 'La cedula ingresada es incorrecta1' . $data['ID'] . '.'
                            ];
                            array_push($errorReturn, $errorMessage);
                        }
                        $documentType = 1;
                    } else {
                        $error = true;
                        $errorMessage = [
                            "placa" => $data['PLACA'],
                            "error" => 'La cedula ingresada es incorrecta ' . $data['ID'] . '.'
                        ];
                        array_push($errorReturn, $errorMessage);
                        $documentType = 3;
                    }
                    //Validate Country
                    if ($data['PAIS'] == '') {
                        $error = true;
                        $errorMessage = [
                            "placa" => $data['PLACA'],
                            "error" => 'El pais no puede estar vacio.'
                        ];
                    }
                    //Validate Province
                    if ($data['PROVINCIA'] == '') {
                        $error = true;
                        $errorMessage = [
                            "placa" => $data['PLACA'],
                            "error" => 'La provincia no puede estar vacia.'
                        ];
                    }
                    //Validate Last Name
                    if ($data['APELLIDOS'] == '') {
                        $error = true;
                        $errorMessage = [
                            "placa" => $data['PLACA'],
                            "error" => 'El apellido no puede estar vacio.'
                        ];
                    }
                    //Validate First Name
                    if ($data['NOMBRES'] == '') {
                        $error = true;
                        $errorMessage = [
                            "placa" => $data['PLACA'],
                            "error" => 'El nombre no puede estar vacio.'
                        ];
                    }
                    //Validate Home Phone Number
                    if ($data['TELÉFONO FIJO'] == '') {
                        $error = true;
                        $errorMessage = [
                            "placa" => $data['PLACA'],
                            "error" => 'El Telefono Fijo no puede estar vacio.'
                        ];
                        array_push($errorReturn, $errorMessage);
                    } else if (strlen($data['TELÉFONO FIJO']) == 9) {
                        if (string_has_letters($data['TELÉFONO FIJO'])) {
                            $error = true;
                            $errorMessage = [
                                "placa" => $data['PLACA'],
                                "error" => 'El Telefono Fijo solo puede contener numeros.'
                            ];
                            array_push($errorReturn, $errorMessage);
                        }
                    } else {
                        $error = true;
                        $errorMessage = [
                            "placa" => $data['PLACA'],
                            "error" => 'El telefono Fijo ' . $data['TELÉFONO FIJO'] . ' no cumple con los requisitos, debe ser 9 digitos'
                        ];
                        array_push($errorReturn, $errorMessage);
                    }
                    //Validate Color
                    if ($data['COLOR'] == '') {
                        $error = true;
                        $errorMessage = [
                            "placa" => $data['PLACA'],
                            "error" => 'El color no puede estar vacio.'
                        ];
                    }
                    //Validate Model
                    if ($data['MODELO'] == '') {
                        $error = true;
                        $errorMessage = [
                            "placa" => $data['PLACA'],
                            "error" => 'El modelo no puede estar vacio.'
                        ];
                    }
                    //Validate Year
                    if ($data['AÑO'] == '') {
                        $error = true;
                        $errorMessage = [
                            "placa" => $data['PLACA'],
                            "error" => 'El año no puede estar vacio.'
                        ];
                    }
                    //Validate if ID Exists
                    foreach ($saleMassive as $massive) {
                        if ($massive['data']['customer']['document'] == $data['ID']) {
                            $error = true;
                            $errorMessage = [
                                "placa" => $data['PLACA'],
                                "error" => 'El documento ' . $data['ID'] . ' se encuentra repetido.'
                            ];
                        }
                    }
                    if ($error == false) {
                        $vehicles = [
                            "plate" => $data['PLACA'],
                            "brand" => $brand->name,
                            "model" => $data['MODELO'],
                            "year" => $data['AÑO'],
                            "color" => $data['COLOR']
                        ];
                        $vehiclesArray = array();
                        array_push($vehiclesArray, $vehicles);

                        //OBTAIN COUNTRY
                        $country = \App\country::where('name', $data['PAIS'])->first();

                        //OBTAIN PROVINCE
                        $province = \App\province::where('name', $data['PROVINCIA'])->first();


                        //CUSTOMER JSON
                        $customer = [
                            "document" => $data['ID'],
                            "documentId" => $documentType,
                            "firstName" => $data['NOMBRES'],
                            "lastName" => $data['APELLIDOS'],
                            "phone" => $data['TELÉFONO FIJO'],
                            "mobilePhone" => $data['TELÉFONO MÓVIL'],
                            "address" => $data['DIRECCIÓN'],
                            "email" => $data['EMAIL'],
                            "country" => $country->id,
                            "province" => $province->id,
                            "city" => $city->id
                        ];

                        //CALCULATE PRICES
                        $value = abs($request['value']);
                        $taxPrice = round((($value * 12) / 100), 2);
                        $totalPrice = round($value + $taxPrice, 2);

                        $subtotal = [
                            "value" => $value
                        ];
                        $discount = [
                            "value" => 0
                        ];
                        $otherDiscount = [
                            "value" => 0
                        ];
                        $tax = [
                            "value" => $taxPrice
                        ];
                        $total = [
                            "value" => $totalPrice
                        ];

                        //SUBTOTAL
                        $subtotalMassive += abs($request['value']);

                        $prices = array();
                        array_push($prices, $subtotal);
                        array_push($prices, $discount);
                        array_push($prices, $otherDiscount);
                        array_push($prices, $tax);
                        array_push($prices, $total);

                        $beginDate = Carbon::parse($data['VIGENCIA DESDE']);
                        $endDate = Carbon::parse($data['VIGENCIA HASTA']);


                        $data = [
                            "product" => $request['product'],
                            "channel" => $request['channel'],
                            "beginDate" => $beginDate,
                            "endDate" => $endDate,
                            "vehicles" => $vehiclesArray,
                            "customer" => $customer,
                            "pricesTable" => $prices
                        ];
                        $json = [
                            "data" => $data
                        ];
                        array_push($saleMassive, $json);
                    }
                }

                //VALIDATE IF ERRRORS EXIST
                if ($errorReturn) {
                    //Save Massive Failed
                    //Massive TAX
                    $tax = (($subtotalMassive * 12) / 100);

                    //Total
                    $total = $subtotalMassive + $tax;

                    //Create Massive Sale
                    $massive = new \App\massives();
                    $massive->upload_date = now();
                    $massive->upload_user = \Auth::user()->id;
                    $massive->subtotal = $subtotalMassive;
                    $massive->tax = $tax;
                    $massive->total = $total;
                    $massive->agencies_id = $request['agency'];
                    $massive->status_massive_id = 14;
                    $massive->status_charge_id = 12;
                    $massive->massive_type_id = 1;
                    $massive->save();

                    //Massive Folder
                    $massiveFolder = '/massives/' . $massive->id . '/';
                    //Create Massive Folder
                    if (!file_exists($massiveFolder)) {
                        mkdir($massiveFolder, 0777, true);
                    }

                    //Move Uploaded File
                    $fileUpload = $request->file;
                    $new_name = rand() . '.' . $fileUpload->getClientOriginalExtension();
                    $fileUpload->move(public_path($massiveFolder), $new_name);


                    $collet = collect($errorReturn);

                    $border = (new BorderBuilder())
                            ->setBorderBottom(Color::BLACK, Border::WIDTH_MEDIUM, Border::STYLE_SOLID)
                            ->setBorderTop(Color::BLACK, Border::WIDTH_MEDIUM, Border::STYLE_SOLID)
                            ->setBorderLeft(Color::BLACK, Border::WIDTH_MEDIUM, Border::STYLE_SOLID)
                            ->setBorderRight(Color::BLACK, Border::WIDTH_MEDIUM, Border::STYLE_SOLID)
                            ->build();

                    $style = (new StyleBuilder())
                            ->setFontBold()
                            ->setFontSize(12)
                            ->setShouldWrapText(true)
                            ->setBorder($border)
                            ->setBackgroundColor(Color::YELLOW)
                            ->build();

                    $errorLog = (new FastExcel($collet))->headerStyle($style)->download('massives/' . $massive->id . '/error.xlsx');
                    $errorLogSave = (new FastExcel($collet))->headerStyle($style)->export('massives/' . $massive->id . '/error.xlsx');

                    //save Error File
                    //Update Files
                    $updateMassive = \App\massives::find($massive->id);
                    $updateMassive->upload_file = 'massives/' . $massive->id . '/' . $new_name;
                    $updateMassive->error_file = 'massives/' . $massive->id . '/error.xlsx';
                    $updateMassive->save();

                    return $errorLog;
                } else {
                    //Save Massive Success
                    //Massive TAX
                    $tax = round((($subtotalMassive * 12) / 100), 2);

                    //Total
                    $total = round($subtotalMassive + $tax, 2);

                    //Create Massive Sale
                    $massive = new \App\massives();
                    $massive->upload_date = now();
                    $massive->upload_user = \Auth::user()->id;
                    $massive->subtotal = $subtotalMassive;
                    $massive->tax = $tax;
                    $massive->total = $total;
                    $massive->agencies_id = $request['agency'];
                    $massive->status_massive_id = 1;
                    $massive->status_charge_id = 12;
                    $massive->massive_type_id = 1;
                    $massive->save();

                    //Massive Folder
                    $massiveFolder = public_path('/massives/') . $massive->id . '/';
                    //Create Massive Folder
//                    $oldmask = umask(0);
                    if (!file_exists($massiveFolder)) {
                        mkdir($massiveFolder, 0777, true);
                    }
//                    umask($oldmask);
                    //Move Uploaded File
                    $fileUpload = $request->file;
                    $new_name = rand() . '.' . $fileUpload->getClientOriginalExtension();
                    $fileUpload->move($massiveFolder, $new_name);

                    //Update Files
                    $updateMassive = \App\massives::find($massive->id);
                    $updateMassive->upload_file = 'massives/' . $massive->id . '/' . $new_name;
                    $updateMassive->save();

                    $result = saleStoreMassive($saleMassive, $massive->id);

                    Session::flash('storeSuccess', 'El archivo fue cargado correctamente');
//                    return back();
                    $query = 'select mass.id as "id",  mass.total as "total", count(vsal.id) as "cantidad", chan.name as "canal",
                    staMass.name as "estadoMasivo",
                    staChar.name as "estadoCobro",
                    mtype.name as "tipo",
                    DATE_FORMAT(mass.upload_date, "%d-%m-%Y") as "fecha" 
                    from massives mass
                    left join massives_sales msal on msal.massives_id = mass.id
                    left join sales sal on sal.id = msal.sales_id
                    left join vehicles_sales vsal on vsal.sales_id = sal.id
                    left join agencies agen on agen.id = mass.agencies_id
                    left join channels chan on chan.id = agen.channel_id
                    join status staMass on staMass.id = mass.status_massive_id
                    join status staChar on staChar.id = mass.status_charge_id
                    join massive_type mtype on mtype.id = mass.massive_type_id
                    where mass.upload_file is not null group by mass.id, mass.total, chan.name, staMass.name, staChar.name, mtype.name, fecha order by mass.id';

                    $massive = DB::select($query);

                    //CHANNELS
                    $channels = \App\channels::all();

                    //STATUS MASSIVE
                    $statusMassive = \App\status::find([1, 14, 15]);

                    //STATUS CHARGE
                    $statusCharge = \App\status::find([12, 9, 15]);

                    //TYPE
                    $types = \App\massive_types::all();
                    return redirect('massive');
                    //Data Array
                    $data = array('channel' => $request->channel,
                        'beginDate' => $request->beginDate,
                        'endDate' => $request->endDate,
                        'type' => $request->type,
                        'statusMassive' => $request->statusMassive,
                        'statusPayment' => $request->statusPayment);

                    return view('massive.index', [
                        "massives" => $massive,
                        "channels" => $channels,
                        "statusMassive" => $statusMassive,
                        "statusCharge" => $statusCharge,
                        "massiveTypes" => $types,
                        "data" => $data
                    ]);
                }
            } else {
                Session::flash('error', 'El archivo es del tipo ' . $extension . ' !! Por favor seleccione un archivo tipo xls!!');
                return back();
            }
        }
    }

    public function downloadUploadFile($id) {
//        return $id;
        $massive = \App\massives::find($id);
        //return response()->download(public_path('uploadError/' . $id));
        if (file_exists(public_path($massive->upload_file))) {
            return response()->download(public_path($massive->upload_file));
        } else {
            echo '<span style="color:red;font-weight:bold">El archivo no se encuentra disponible en el servidor</span>';
        }
    }

    public function downloadErrorFile($id) {
//        return $id;
        return response()->download(public_path('' . $id));
    }

    public function downloadUploadFormatFile() {
        return response()->download(public_path('massives/formato_cargo.xlsx'));
    }

    public function downloadCancelFormatFile() {
        return response()->download(public_path('massives/formato_cancel.xlsx'));
    }

    public function payment(request $request) {
        $massive = \App\massives::find($request['id']);
        $massive->status_charge_id = 9;
        $massive->payment_date = now();
        $massive->payment_user = \Auth::user()->id;
        if ($massive->save()) {
            \Session::flash('Success', 'El masivo fue pagado correctamente.');
            $result = 'true';
        } else {
            $result = 'false';
        }
        return $result;
    }

    public function cancel() {
        //Validate Create Permission
        $edit = checkExtraPermits('11',\Auth::user()->role_id);
        if(!$edit){
            \Session::flash('ValidateUserRoute', 'No tiene acceso a cancelar ventas Masivas.');
            return view('home');
        }
        
        //PRODUCTS
        $products = \App\products::all();
        //Channels 
        $channels = \App\channels::all();

        return view('massive.cancel', [
            "products" => $products,
            "channels" => $channels
        ]);
    }

    public function storeCancel(request $request) {
//        return $request;
        //validate the xls file
        $this->validate($request, array(
            'file' => 'required'
        ));

        if ($request->hasFile('file')) {
            $extension = File::extension($request->file->getClientOriginalName());
            if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {

                $path = $request->file->getRealPath();
                $collection = (new FastExcel)->import($path);

                $errorReturn = array();
                $successArray = array();
                $error = false;
                $subTotal = 0;
                $tax = 0;
                $total = 0;
                //Turn data in JSON
                foreach ($collection as $data) {
                    //VALIDATE PLATE    
                    $vehicle = \App\vehicles::where('plate', 'LIKE', '%' . $data['PLACA'] . '%')->first();

                    if ($vehicle) {
                        //NADA
                    } else {
                        $error = true;
                        $errorMessage = [
                            "placa" => $data['PLACA'],
                            "error" => 'la Placa ' . $data['PLACA'] . ' no se encuentra registrada en nuestra base de datos'
                        ];
                        array_push($errorReturn, $errorMessage);
                    }
                    //VALIDATE VEHICLE IS A MASSIVE SALE AND BELONGS TO AGENCY
                    $query = 'select distinct mass.id as "id",
                        sal.subtotal_12 as "subtotal_12",
                        sal.tax as "tax",
                        sal.total as "total",
                        sal.id as "salId",
                        vsal.id as "vSalId"
                        from massives mass
                            join massives_sales msal on msal.massives_id = mass.id
                            join sales sal on sal.id = msal.sales_id
                            join vehicles_sales vsal on vsal.sales_id = sal.id
                            join vehicles vehi on vehi.id = vsal.vehicule_id
                            join agencies agen on agen.id = mass.agencies_id
                            join channels chan on chan.id = agen.channel_id
                            where vehi.plate = "' . $data['PLACA'] . '" and chan.id = "' . $request['channel'] . '"';
                    $select = DB::select($query);
                    if ($select) {
                        //NADA
                    } else {
                        $error = true;
                        $errorMessage = [
                            "placa" => $data['PLACA'],
                            "error" => 'la Placa ' . $data['PLACA'] . ' no tiene una venta masiva para este Canal'
                        ];
                        array_push($errorReturn, $errorMessage);
                    }
                    if ($error == false) {
                        //VALUES
                        $subTotal += $select[0]->subtotal_12;
                        $tax += $select[0]->tax;
                        $total += $select[0]->total;

                        //PREPARE CANCEL ARRAY
                        $vehi = [
                            "plate" => $data['PLACA'],
                            "vSalId" => $select[0]->vSalId,
                            "salId" => $select[0]->salId
                        ];
                        array_push($successArray, $vehi);
                    }
                }
                if ($errorReturn) {
                    //Save Massive Failed
                    //Create Massive Sale
                    $massive = new \App\massives();
                    $massive->upload_date = now();
                    $massive->upload_user = \Auth::user()->id;
                    $massive->subtotal = 'NA';
                    $massive->tax = 'NA';
                    $massive->total = 'NA';
                    $massive->agencies_id = $request['agency'];
                    $massive->status_massive_id = 14;
                    $massive->status_charge_id = 15;
                    $massive->massive_type_id = 2;
                    $massive->save();

                    //Massive Folder
                    $massiveFolder = '/massives/' . $massive->id . '/';
                    //Create Massive Folder
                    if (!file_exists($massiveFolder)) {
                        mkdir($massiveFolder, 0777, true);
                    }

                    //Move Uploaded File
                    $fileUpload = $request->file;
                    $new_name = rand() . '.' . $fileUpload->getClientOriginalExtension();
                    $fileUpload->move(public_path($massiveFolder), $new_name);


                    $collet = collect($errorReturn);

                    $border = (new BorderBuilder())
                            ->setBorderBottom(Color::BLACK, Border::WIDTH_MEDIUM, Border::STYLE_SOLID)
                            ->setBorderTop(Color::BLACK, Border::WIDTH_MEDIUM, Border::STYLE_SOLID)
                            ->setBorderLeft(Color::BLACK, Border::WIDTH_MEDIUM, Border::STYLE_SOLID)
                            ->setBorderRight(Color::BLACK, Border::WIDTH_MEDIUM, Border::STYLE_SOLID)
                            ->build();

                    $style = (new StyleBuilder())
                            ->setFontBold()
                            ->setFontSize(12)
                            ->setShouldWrapText(true)
                            ->setBorder($border)
                            ->setBackgroundColor(Color::YELLOW)
                            ->build();

                    $errorLog = (new FastExcel($collet))->headerStyle($style)->download('massives/' . $massive->id . '/CancelError.xlsx');
                    $errorLogSave = (new FastExcel($collet))->headerStyle($style)->export('massives/' . $massive->id . '/CancelError.xlsx');

                    //save Error File
                    //Update Files
                    $updateMassive = \App\massives::find($massive->id);
                    $updateMassive->upload_file = 'massives/' . $massive->id . '/' . $new_name;
                    $updateMassive->error_file = 'massives/' . $massive->id . '/CancelError.xlsx';
                    $updateMassive->save();

                    return $errorLog;
                } else {
                    //Save Massive Success
                    //Create Massive Sale
                    $massive = new \App\massives();
                    $massive->upload_date = now();
                    $massive->upload_user = \Auth::user()->id;
                    $massive->subtotal = $subTotal;
                    $massive->tax = $tax;
                    $massive->total = $total;
                    $massive->agencies_id = $request['agency'];
                    $massive->status_massive_id = 15;
                    $massive->status_charge_id = 15;
                    $massive->massive_type_id = 2;
                    $massive->save();

                    //Massive Folder
                    $massiveFolder = '/massives/' . $massive->id . '/';
                    //Create Massive Folder
                    if (!file_exists($massiveFolder)) {
                        mkdir($massiveFolder, 0777, true);
                    }

                    //Move Uploaded File
                    $fileUpload = $request->file;
                    $new_name = rand() . '.' . $fileUpload->getClientOriginalExtension();
                    $fileUpload->move(public_path($massiveFolder), $new_name);

                    //Update Files
                    $updateMassive = \App\massives::find($massive->id);
                    $updateMassive->upload_file = 'massives/' . $massive->id . '/' . $new_name;
                    $updateMassive->save();

                    foreach ($successArray as $success) {
                        //Cancel Vehicles Sales
                        $vSal = \App\vehicles_sales::find($success['vSalId']);
                        $vSal->status_id = 2;
                        $vSal->save();

                        //Cancel Sales
                        $sales = \App\sales::find($success['salId']);
                        $sales->status_id = 4;
                        $sales->save();

                        //Massive Sales
                        $massiveSales = new \App\massives_sales();
                        $massiveSales->sales_id = $success['salId'];
                        $massiveSales->massives_id = $massive->id;
                        $massiveSales->save();

                        //Validate ir it has a Pending scheduling
                        $scheSql = 'select deta.id from scheduling_details deta
                                    join scheduling sche on sche.id = deta.scheduling_id
                                    where sche.vehicles_sales_id = ' . $vSal->id . ' and deta.status_id = 3';
                        $sche = DB::select($scheSql);

                        if ($sche) {
                            $deta = \App\schedulingDetails::find($sche[0]->id);
                            $deta->status_id = 4;
                            $deta->cancel_motive_id = 12;
                            $deta->cancel_date = now();
                            $deta->cancel_user_id = \Auth::user()->id;
                            $deta->save();
                        }
                    }
                    //Inactive Vehicles Sales

                    Session::flash('cancelSuccess', 'El archivo fue cargado correctamente');
//                    return back();
                    $query = 'select mass.id as "id",  mass.total as "total", count(vsal.id) as "cantidad", chan.name as "canal",
                    staMass.name as "estadoMasivo",
                    staChar.name as "estadoCobro",
                    mtype.name as "tipo",
                    DATE_FORMAT(mass.upload_date, "%d-%m-%Y") as "fecha" 
                    from massives mass
                    left join massives_sales msal on msal.massives_id = mass.id
                    left join sales sal on sal.id = msal.sales_id
                    left join vehicles_sales vsal on vsal.sales_id = sal.id
                    left join agencies agen on agen.id = mass.agencies_id
                    left join channels chan on chan.id = agen.channel_id
                    join status staMass on staMass.id = mass.status_massive_id
                    join status staChar on staChar.id = mass.status_charge_id
                    join massive_type mtype on mtype.id = mass.massive_type_id
                    where mass.upload_file is not null group by mass.id, mass.total, chan.name, staMass.name, staChar.name, mtype.name, fecha order by mass.id';

                    $massive = DB::select($query);

                    //CHANNELS
                    $channels = \App\channels::all();

                    //STATUS MASSIVE
                    $statusMassive = \App\status::find([1, 14, 15]);

                    //STATUS CHARGE
                    $statusCharge = \App\status::find([12, 9, 15]);

                    //TYPE
                    $types = \App\massive_types::all();

                    //Data Array
                    $data = array('channel' => $request->channel,
                        'beginDate' => $request->beginDate,
                        'endDate' => $request->endDate,
                        'type' => $request->type,
                        'statusMassive' => $request->statusMassive,
                        'statusPayment' => $request->statusPayment);

                    return view('massive.index', [
                        "massives" => $massive,
                        "channels" => $channels,
                        "statusMassive" => $statusMassive,
                        "statusCharge" => $statusCharge,
                        "massiveTypes" => $types,
                        "data" => $data
                    ]);
                }
            }
        }
    }

    public function validateUploadExcel(request $request) {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 6000);
        ini_set('max_input_time', 600);
        return $request;
        //validate the xls file
        $this->validate($request, array(
            'file' => 'required'
        ));

        if ($request->hasFile('file')) {
            $extension = File::extension($request->file->getClientOriginalName());
            if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {

                $path = $request->file->getRealPath();
                $collection = (new FastExcel)->import($path);

                $errorReturn = array();
                $errorResponse = array();
                $saleMassive = array();
                $idMassive = array();
                $plateMassive = array();
                $subtotalMassive = 0;
                $idError = false;
                $plateError = false;

//                return $collection;
                
                //Turn data in JSON
                foreach ($collection as $data) {
                    //Test Insert to BBDD
                    $test = new \App\test();
                    $test->country = $data['PAIS'];
                    $test->province = $data['PROVINCIA'];
                    $test->city = $data['CIUDAD'];
                    $test->document = $data['ID'];
                    $test->last_name = $data['APELLIDOS'];
                    $test->first_name = $data['NOMBRES'];
                    $test->address = $data['DIRECCIÓN'];
                    $test->phone = $data['TELÉFONO FIJO'];
                    $test->mobile_phone = $data['TELÉFONO MÓVIL'];
                    $test->email = $data['EMAIL'];
                    $test->plate = $data['PLACA'];
                    $test->color = $data['COLOR'];
                    $test->brand = $data['MARCA'];
                    $test->model = $data['MODELO'];
                    $test->year = $data['AÑO'];
                    $test->begin_date = $data['VIGENCIA DESDE'];
                    $test->end_date = $data['VIGENCIA HASTA'];
                    $test->save();
                    
                    //INITIATE ERROR VARIABLE
//                    $error = false;

                    //OBTAIN BRAND
//                    $brand = \App\vehiclesBrands::where('name', $data['MARCA'])->get();
                    
                    //VALIDATE BRAND
//                    if ($brand->isEmpty()) { $error = true; $errorMessage = [ "placa" => $data['PLACA'], "error" => 'La marca ' . $data['MARCA'] . ' no se encuentra en nustra base de datos' ]; array_push($errorReturn, $errorMessage); }

                    //VALIDATE PLATE
//                    if (strlen($data['PLACA']) == 7) { $plate = $data['PLACA']; if (!string_has_letters($plate[0]) || !string_has_letters($plate[1]) || !string_has_letters($plate[2])) { $error = true; $errorMessage = [ "placa" => $data['PLACA'], "error" => 'La placa ' . $data['PLACA'] . ' no tiene el formato correcto (ABC1234)' ]; array_push($errorReturn, $errorMessage); } else if (!string_has_numbers($plate[3]) || !string_has_numbers($plate[4]) || !string_has_numbers($plate[5]) || !string_has_numbers($plate[6])) { $error = true; $errorMessage = [ "placa" => $data['PLACA'], "error" => 'La placa ' . $data['PLACA'] . ' no tiene el formato correcto (ABC1234)' ]; array_push($errorReturn, $errorMessage); } } else { $error = true; $errorMessage = [ "placa" => $data['PLACA'], "error" => 'La placa ' . $data['PLACA'] . ' no tiene el formato correcto (ABC1234)' ]; array_push($errorReturn, $errorMessage); }

                    //VALIDATE CITY
//                    $city = \App\city::where('name', $data['CIUDAD'])->get(); if ($city->isEmpty()) { $error = true; $errorMessage = [ "placa" => $data['PLACA'], "error" => 'La ciudad ' . $data['CIUDAD'] . ' no se encuentra registrada en nuestra base de datos' ]; array_push($errorReturn, $errorMessage); }
                    
                    //VALIDATE MOBILE PHONE
//                    if ($data['TELÉFONO MÓVIL'] == '') { $error = true; $errorMessage = [ "placa" => $data['PLACA'], "error" => 'El Telefono Movil no puede estar vacio.' ]; array_push($errorReturn, $errorMessage); } else if (strlen($data['TELÉFONO MÓVIL']) == 10) { if (string_has_letters($data['TELÉFONO MÓVIL'])) { $error = true; $errorMessage = [ "placa" => $data['PLACA'], "error" => 'El Telefono Movil solo puede contener numeros.' ]; array_push($errorReturn, $errorMessage); } } else { $error = true; $errorMessage = [ "placa" => $data['PLACA'], "error" => 'El telefono movil ' . $data['TELÉFONO MÓVIL'] . ' no cumple con los requisitos, debe ser 10 digitos' ]; array_push($errorReturn, $errorMessage); }
                   
                    //VALIDATE EMAIL
//                    $email = $data['EMAIL']; $return = filter_var($email, FILTER_VALIDATE_EMAIL); if ($return == false) { $error = true; $errorMessage = [ "placa" => $data['PLACA'], "error" => 'El email ' . $data['EMAIL'] . ' no es un email valido' ]; array_push($errorReturn, $errorMessage); }
                    
                    //Validate ID
//                    if ($data['ID'] == '') { $error = true; $errorMessage = [ "placa" => $data['PLACA'], "error" => 'El ID no puede estar vacio.' ]; array_push($errorReturn, $errorMessage); } else if (strlen(trim($data['ID'])) == 10) { if (!is_numeric($data['ID'])) { $error = true; $errorMessage = [ "placa" => $data['PLACA'], "error" => 'La cedula ingresada es incorrecta1' . $data['ID'] . '.' ]; array_push($errorReturn, $errorMessage); } $documentType = 1; } else { $error = true; $errorMessage = [ "placa" => $data['PLACA'], "error" => 'La cedula ingresada es incorrecta ' . $data['ID'] . '.' ]; array_push($errorReturn, $errorMessage); $documentType = 3; }

                    //Validate Country
//                    if ($data['PAIS'] == '') { $error = true; $errorMessage = [ "placa" => $data['PLACA'], "error" => 'El pais no puede estar vacio.' ]; array_push($errorReturn, $errorMessage); }

                    //Validate Province
//                    if ($data['PROVINCIA'] == '') { $error = true; $errorMessage = [ "placa" => $data['PLACA'], "error" => 'La provincia no puede estar vacia.' ]; array_push($errorReturn, $errorMessage); }
                    
                    //Validate Last Name
//                    if ($data['APELLIDOS'] == '') { $error = true; $errorMessage = [ "placa" => $data['PLACA'], "error" => 'El apellido no puede estar vacio.' ]; array_push($errorReturn, $errorMessage); }
                    
                    //Validate First Name
//                    if ($data['NOMBRES'] == '') { $error = true; $errorMessage = [ "placa" => $data['PLACA'], "error" => 'El nombre no puede estar vacio.' ]; array_push($errorReturn, $errorMessage); }
                    
                    //Validate Home Phone Number
//                    if ($data['TELÉFONO FIJO'] == '') { $error = true; $errorMessage = [ "placa" => $data['PLACA'], "error" => 'El Telefono Fijo no puede estar vacio.' ]; array_push($errorReturn, $errorMessage); } else if (strlen($data['TELÉFONO FIJO']) == 9) { if (string_has_letters($data['TELÉFONO FIJO'])) { $error = true; $errorMessage = [ "placa" => $data['PLACA'], "error" => 'El Telefono Fijo solo puede contener numeros.' ]; array_push($errorReturn, $errorMessage); } } else { $error = true; $errorMessage = [ "placa" => $data['PLACA'], "error" => 'El telefono Fijo ' . $data['TELÉFONO FIJO'] . ' no cumple con los requisitos, debe ser 9 digitos' ]; array_push($errorReturn, $errorMessage); }
                    
                    //Validate Color
//                    if ($data['COLOR'] == '') { $error = true; $errorMessage = [ "placa" => $data['PLACA'], "error" => 'El color no puede estar vacio.' ]; array_push($errorReturn, $errorMessage); }
                    
                    //Validate Model
//                    if ($data['MODELO'] == '') { $error = true; $errorMessage = [ "placa" => $data['PLACA'], "error" => 'El modelo no puede estar vacio.' ]; array_push($errorReturn, $errorMessage); }
                    
                    //Validate Year
//                    if ($data['AÑO'] == '') { $error = true; $errorMessage = [ "placa" => $data['PLACA'], "error" => 'El año no puede estar vacio.' ]; array_push($errorReturn, $errorMessage); }
                    
                    //Validate Begin Date
//                    if ($data['VIGENCIA DESDE'] == '') { $error = true; $errorMessage = [ "placa" => $data['PLACA'], "error" => 'La fecha de Vigencia Desde no puede estar vacia.' ]; array_push($errorReturn, $errorMessage); } elseif (is_string($data['VIGENCIA DESDE'])) { $error = true; $errorMessage = [ "placa" => $data['PLACA'], "error" => 'La fecha de Vigencia Desde no tiene el formato correcto.' ]; array_push($errorReturn, $errorMessage); }
                    
                    //Validate End Date
//                    if ($data['VIGENCIA HASTA'] == '') { $error = true; $errorMessage = [ "placa" => $data['PLACA'], "error" => 'La fecha de Vigencia Hasta no puede estar vacia.' ]; array_push($errorReturn, $errorMessage); } elseif (is_string($data['VIGENCIA HASTA'])) { $error = true; $errorMessage = [ "placa" => $data['PLACA'], "error" => 'La fecha de Vigencia Hasta no tiene el formato correcto.' ]; array_push($errorReturn, $errorMessage); }
                    
                    //Validate if ID Exists
//                    if ($idError == false) { foreach ($idMassive as $massive) { if ($massive == $data['ID']) { $error = true; $errorMessage = [ "placa" => $data['PLACA'], "error" => 'El documento ' . $data['ID'] . ' se encuentra repetido.' ]; array_push($errorReturn, $errorMessage); $idError = true; } } }
                    
                    //Validate if PLATE Exists
//                    if ($plateError == false) { foreach ($plateMassive as $massive) { if ($massive == $data['PLACA']) { $error = true; $errorMessage = [ "placa" => $data['PLACA'], "error" => 'La Placa ' . $data['PLACA'] . ' se encuentra repetida.' ]; array_push($errorReturn, $errorMessage); $plateError = true; } } }

                    //Store ID
                    array_push($idMassive, $data['ID']);

                    //Store PLATE
                    array_push($plateMassive, $data['PLACA']);
                }
                //VALIDATE ERROR
                if ($errorReturn) {
                    //Generate Error Excel
                    $collet = collect($errorReturn);

                    $border = (new BorderBuilder())
                            ->setBorderBottom(Color::BLACK, Border::WIDTH_MEDIUM, Border::STYLE_SOLID)
                            ->setBorderTop(Color::BLACK, Border::WIDTH_MEDIUM, Border::STYLE_SOLID)
                            ->setBorderLeft(Color::BLACK, Border::WIDTH_MEDIUM, Border::STYLE_SOLID)
                            ->setBorderRight(Color::BLACK, Border::WIDTH_MEDIUM, Border::STYLE_SOLID)
                            ->build();

                    $style = (new StyleBuilder())
                            ->setFontBold()
                            ->setFontSize(12)
                            ->setShouldWrapText(true)
                            ->setBorder($border)
                            ->setBackgroundColor(Color::YELLOW)
                            ->build();

                    $name = rand() . 'uploadError.xlsx';
                    $errorLog = (new FastExcel($collet))->headerStyle($style)->export(public_path('' . $name)); 

                    $returnArray = [
                        'success' => 'false',
                        'name' => '' . $name
                    ];
                } else {
                    $returnArray = [
                        'success' => 'true'
                    ];
                }
                return $returnArray;
            }
        }
    }

    public function validateCancelExcel(request $request) {
//        return $request;
        //validate the xls file
        $this->validate($request, array(
            'file' => 'required'
        ));

        if ($request->hasFile('file')) {
            $extension = File::extension($request->file->getClientOriginalName());
            if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {

                $path = $request->file->getRealPath();
                $collection = (new FastExcel)->import($path);

                $errorReturn = array();
                $successArray = array();
                $error = false;
                $subTotal = 0;
                $tax = 0;
                $total = 0;
                //Turn data in JSON
                if (empty($collection[0])) {
                    $error = true;
                    $errorMessage = [
                        "placa" => '',
                        "error" => 'Debe ingresar una placa'
                    ];
                    array_push($errorReturn, $errorMessage);
                } else {
                    foreach ($collection as $data) {
                        if (empty($data)) {
                            $error = true;
                            $errorMessage = [
                                "placa" => $data['PLACA'],
                                "error" => 'Debe ingresar una placa'
                            ];
                            array_push($errorReturn, $errorMessage);
                        } else {
                            //VALIDATE PLATE    
                            $vehicle = \App\vehicles::where('plate', 'LIKE', '%' . $data['PLACA'] . '%')->first();

                            if ($vehicle) {
                                //NADA
                            } else {
                                $error = true;
                                $errorMessage = [
                                    "placa" => $data['PLACA'],
                                    "error" => 'la Placa ' . $data['PLACA'] . ' no se encuentra registrada en nuestra base de datos'
                                ];
                                array_push($errorReturn, $errorMessage);
                            }
                            //VALIDATE VEHICLE IS A MASSIVE SALE AND BELONGS TO AGENCY
                            $query = 'select distinct mass.id as "id",
                            sal.subtotal_12 as "subtotal_12",
                            sal.tax as "tax",
                            sal.total as "total",
                            sal.id as "salId",
                            vsal.id as "vSalId"
                            from massives mass
                                join massives_sales msal on msal.massives_id = mass.id
                                join sales sal on sal.id = msal.sales_id
                                join vehicles_sales vsal on vsal.sales_id = sal.id
                                join vehicles vehi on vehi.id = vsal.vehicule_id
                                join agencies agen on agen.id = mass.agencies_id
                                join channels chan on chan.id = agen.channel_id
                                where vehi.plate = "' . $data['PLACA'] . '" and chan.id = "' . $request['channel'] . '"';
                            $select = DB::select($query);
//                            print_r($select[0]->vSalId);die();
                            if ($select) {
                                //Validate ir it has a Confirm scheduling
                                $scheSql = 'select deta.id from scheduling_details deta
                                            join scheduling sche on sche.id = deta.scheduling_id
                                            where sche.vehicles_sales_id = ' . $select[0]->vSalId . ' and deta.status_id = 17';
                                $sche = DB::select($scheSql);

                                if ($sche) {
                                    $error = true;
                                    $errorMessage = [
                                        "placa" => $data['PLACA'],
                                        "error" => 'la Placa ' . $data['PLACA'] . ' tiene un agendamiento confirmado'
                                    ];
                                    array_push($errorReturn, $errorMessage);
                                }
                            } else {
                                $error = true;
                                $errorMessage = [
                                    "placa" => $data['PLACA'],
                                    "error" => 'la Placa ' . $data['PLACA'] . ' no tiene una venta masiva para este Canal'
                                ];
                                array_push($errorReturn, $errorMessage);
                            }
                        }
                    }
                }
                if ($errorReturn) {
                    //Generate Error Excel
                    $collet = collect($errorReturn);

                    $border = (new BorderBuilder())
                            ->setBorderBottom(Color::BLACK, Border::WIDTH_MEDIUM, Border::STYLE_SOLID)
                            ->setBorderTop(Color::BLACK, Border::WIDTH_MEDIUM, Border::STYLE_SOLID)
                            ->setBorderLeft(Color::BLACK, Border::WIDTH_MEDIUM, Border::STYLE_SOLID)
                            ->setBorderRight(Color::BLACK, Border::WIDTH_MEDIUM, Border::STYLE_SOLID)
                            ->build();

                    $style = (new StyleBuilder())
                            ->setFontBold()
                            ->setFontSize(12)
                            ->setShouldWrapText(true)
                            ->setBorder($border)
                            ->setBackgroundColor(Color::YELLOW)
                            ->build();

                    $name = rand() . 'cancelError.xlsx';
                    $errorLog = (new FastExcel($collet))->headerStyle($style)->export('uploadError/' . $name);

                    $returnArray = [
                        'success' => 'false',
                        'name' => '' . $name
                    ];
                } else {
                    $returnArray = [
                        'success' => 'true'
                    ];
                }
                return $returnArray;
            }
        }
    }

    public function vehiculeModal(request $request) {
        //Vehicles Brands
        $brands = \App\vehiclesBrands::where('status_id', '=', 1)->get();


        $data = '<div id="thirdStep" class="col-md-12" style="margin-top:20px">
                    <div class="col-md-12 border">
                        <div id="thirdStepAlert" class="alert alert-danger hidden">
                            <strong>¡Alerta!</strong> Debe ingresar un vehiculo.
                        </div>
                        <div id="plateAlert" class="alert alert-danger hidden">
                            <strong>¡Alerta!</strong> El vehiculo ya posee una venta individual.
                        </div>
                        <div id="plateAlert2" class="alert alert-danger hidden">
                            <strong>¡Alerta!</strong> Debe ingresar una placa valida.
                        </div>
                        <div id="plateAlert3" class="alert alert-danger hidden">
                            <center>Ingrese una placa valida.</center>
                        </div>
                        <div id="plateAlert4" class="alert alert-danger hidden">
                            <center><strong>Por favor ingrese una placa.</strong></center>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="last_name"> Placa</label> <label class="own"><span class="glyphicon glyphicon-info-sign" style="color:#0099ff"><span class="own1" style="float:left">* La placa debe tener 7 Caracteres: <br> -PCQ1111 <br> Nota: Si la placa solo tiene 6 caracteres debe agregar un cero de la siguiente manera: <br> -Placa: PCQ0111</p></span></span></label>
                                <div class="form-inline">
                                    <input type="text" class="form-control registerForm" name="plate" id="plate" tabindex=1 placeholder="Placa" value="" maxlength="7" required style="width:80%">
                                    <button type="button" class="btn btn-info" id="plateBtn" onclick="plateBtn()"><span class="glyphicon glyphicon-search"></span></button>
                                </div>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="last_name"> Modelo</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" class="form-control registerForm" name="model" id="model" tabindex=3 placeholder="Modelo" value="" required disabled="disabled">
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="last_name"> Color</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" class="form-control registerForm" name="color" id="color" tabindex=5 placeholder="Color" value="" required disabled="disabled">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="province"> Marca</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <select name="brand" class="form-control registerForm" id="brand" tabindex=2 required disabled="disabled">
                                    <option id="brandSelect" selected="true" disabled="disabled" value="0">--Escoja Una---</option>
                                    ';
        foreach ($brands as $bra) {
            $data .= '<option id="brandSelect" value="' . $bra->name . '">' . $bra->name . '</option>';
        }
        $data .= '
                                </select>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="last_name"> Año</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="number" class="form-control registerForm yearVehicle" name="year" id="year" tabindex=4 placeholder="Año" value="" max="2020" min="1" required disabled="disabled" onchange="yearChange(this)">
                                <p id="yearVehicleError" style="color:red;font-weight: bold"></p>
                            </div>
                            <div class="form-group">
                                <a id="btnVehicles" class="btn btn-success registerForm" align="right" href="#" style="float:right;margin-right: 0px;padding: 5px;margin-top: 25px;width:100px" onclick="btnVehicles()"><span class="glyphicon glyphicon-plus"></span>Agregar </a>
                            </div>

                        </div>
                        <div class="col-md-10 col-md-offset-1">
                            <table id="vehiclesTable" class="table table-striped table-bordered" style="border-collapse: separate !important">
                                <thead>
                                    <tr>
                                        <!--<th>N°</th>-->
                                        <th style="background-color:#b3b0b0">Placa</th>
                                        <th style="background-color:#b3b0b0">Marca</th>
                                        <th style="background-color:#b3b0b0">Modelo</th>
                                        <th style="background-color:#b3b0b0">Año</th>
                                        <th style="background-color:#b3b0b0">Color</th>
                                        <th style="background-color:#b3b0b0">Quitar</th>
                                    </tr>
                                </thead>
                                <tbody id="vehiclesBodyTable">
                                </tbody>
                            </table>
                        </div>
                        <!--                <div class="col-md-12" style="margin-top:5px;padding-top:15px;">
                                            <a id="thirdStepBtnBack" class="btn btn-default registerForm" align="left" href="#" style="margin-left: -15px"> Anterior </a>
                                            <a id="thirdStepBtnNext" class="btn btn-info registerForm" align="right" href="#" style="float:right;margin-right: -15px;padding: 5px"> Siguiente </a>
                                        </div>-->
                    </div>
                    <div class="col-md-12" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                            <a class="btn btn-default registerForm" align="right" href="#" style="float:left;margin-left:-15px" data-dismiss="modal"> Cancelar </a>
                            <a id="modalVehiSave" class="btn btn-info registerForm" align="right" href="#" style="float:right;margin-right: -15px" onclick="modalVehiSave()"> Guardar </a>
                    </div>
                </div>';
        return $data;
    }

    public function cancelAjax(request $request) {
        //Process Massives Cancel
        $sales = $request['data']['ids'];
        foreach ($sales as $sal) {
            //Cancel Sales
            $sales = \App\sales::find($sal);
            $sales->status_id = 4;
            $sales->save();

            //Obtain Vehicle Sales
            $vsal = \App\vehicles_sales::where('sales_id', '=', $sal)->get();
            if (!$vsal->isEmpty()) {
                //Cancel Vehicles Sales
                $vSal = \App\vehicles_sales::find($vsal[0]->id);
                $vSal->status_id = 2;
                $vSal->save();

                //Validate ir it has a Pending scheduling
                $scheSql = 'select deta.id from scheduling_details deta
                                        join scheduling sche on sche.id = deta.scheduling_id
                                        where sche.vehicles_sales_id = ' . $vSal->id . ' and deta.status_id = 3';
                $sche = DB::select($scheSql);

                if ($sche) {
                    $deta = \App\schedulingDetails::find($sche[0]->id);
                    $deta->status_id = 4;
                    $deta->cancel_motive_id = 12;
                    $deta->cancel_date = now();
                    $deta->cancel_user_id = \Auth::user()->id;
                    $deta->save();
                }
            }
        }

        Session::flash('cancelSuccess', 'Los Masivos fueron cancelados correctamente');
    }
    
}
