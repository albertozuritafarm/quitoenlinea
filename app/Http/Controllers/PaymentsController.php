<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Mail\Email;
use Illuminate\Support\Facades\Mail;
use App\Jobs\EmailJobs;
use Illuminate\Support\Carbon;
use Illuminate\Pagination\Paginator;

class PaymentsController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('validateUserRoute');
    }

    public function index(request $request) {
        //Validate if User has view Permit
        $viewPermit = checkViewPermit('15', \Auth::user()->role_id);
        if(!$viewPermit){
            \Session::flash('ValidateUserRoute', 'No tiene acceso al modulo de Cobranzas.');
            return view('home');
        }
        
        //Obtain Edit Permission
        $edit = checkExtraPermits('24',\Auth::user()->role_id);
        
        //Obtain Create Permission
        $create = checkExtraPermits('26',\Auth::user()->role_id);
        
        //Obtain Cancel Permission
        $cancel = checkExtraPermits('25',\Auth::user()->role_id);
        
        //Obtain Channel
        $channelQuery = 'select cha.* from channels cha join agencies agen on agen.channel_id = cha.id where agen.id =  "' . \Auth::user()->agen_id . '"';
        $channel = DB::select($channelQuery);

        //Payment Types
        $payment_types = \App\paymentTypes::where('id','=','2')->get();
//        dd($payment_types);
        //Charge Type
        $charge_types = \App\chargeTypes::where('id','=','1')->get();

        //Obtain Sales Status
        $status = \App\status::find([26, 4, 32, 21, 30]);
        
        //Store Form Variables in Session
        if ($request->isMethod('post')){
            session(['paymentsDocument' => $request->document]);
            session(['paymentsDate' => $request->date]);
            session(['paymentsSaleId' => $request->saleId]);
            session(['paymentsSalesType' => $request->payment_type]);
            session(['paymentsChargesType' => $request->charge_type]);
            session(['paymentsStatus' => $request->status]);
            session(['paymentsItems' => $request->items]);
            $currentPage = 1;
            session(['paymentsPage' => 1]);
        }else{
            $currentPage = session('paymentsPage');
        }

        //Pagination Items
        if(session('paymentsItems') == null){ $items = 10; }else{ $items = session('paymentsItems'); }

        //Form Variables
        $document = session('paymentsDocument');
        $date = session('paymentsDate');
        $saleId = session('paymentsSaleId');
        $salesType = session('paymentsSalesType');
        $chargesType = session('paymentsChargesType');
        $statusForm = session('paymentsStatus');

        //Validate User Role
        $userRol = 'true';
        $userRolQuery = '1=1';

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
                $userSucreQuery = ' pbc.ejecutivo_ss_email = "'.\Auth::user()->email.'"';
            }
        }
        //ROL CANAL
        if($rol->rol_entity_id == 2){
            //ROL TIPO GERENCIA
            if($rol->rol_type_id == 1){
                $userSucre = true;
                $userSucreQuery = 'chan.id = ' . $channel[0]->id;
            }elseif($rol->rol_type_id == 2){
            // ROL TIPO JEFATURA
                $userSucre = true;
                $userSucreQuery = ' agencies.id = "'.\Auth::user()->agen_id.'"';
            }else{
            // ROL TIPO EJECUTIVO
                $userSucre = true;
                $userSucreQuery = ' sal.user_id = "'.\Auth::user()->id.'"';
            }
        }
        
        // Make sure that you call the static method currentPageResolver()
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });
        
        $newCharges = charges($document, $date, $saleId, $salesType, $chargesType, $statusForm, $userRol, $userRolQuery, $items, $userSucre, $userSucreQuery);

        return view('payments.index', [
            'charges' => $newCharges,
            'paymentTypes' => $payment_types,
            'chargeTypes' => $charge_types,
            'status' => $status,
            'items' => $items,
            'edit' => $edit,
            'cancel' => $cancel,
            'create' => $create
        ]);
    }
    
    function fetch_data(request $request){
        if($request->ajax()){
            //Page
            session(['paymentsPage' => $request->page]);
                       
        
        	//Validate if User has view Permit
        $viewPermit = checkViewPermit('15', \Auth::user()->role_id);
        	if(!$viewPermit){
            $error = '<div class="col-md-5 col-md-offset-3">
                        <div class="alert alert-danger">
                <center><strong>
                    No tiene acceso al modulo de Usuarios.

                    </strong></center>
            </div>
                        
        </div>';
            return $error;
        }
            //Obtain Edit Permission
            $edit = checkExtraPermits('24',\Auth::user()->role_id);

            //Obtain Create Permission
            $create = checkExtraPermits('26',\Auth::user()->role_id);

            //Obtain Cancel Permission
            $cancel = checkExtraPermits('25',\Auth::user()->role_id);
        
            //Obtain Channel
            $channelQuery = 'select cha.* from channels cha join agencies agen on agen.channel_id = cha.id where agen.id =  "' . \Auth::user()->agen_id . '"';
            $channel = DB::select($channelQuery);
            
            //Pagination Items
            if(session('paymentsItems') == null){ $items = 10; }else{ $items = session('paymentsItems'); }

            //Form Variables
            $document = session('paymentsDocument');
            $date = session('paymentsDate');
            $saleId = session('paymentsSaleId');
            $salesType = session('paymentsSalesType');
            $chargesType = session('paymentsChargesType');
            $statusForm = session('paymentsStatus');

            //Validate User Role
            $userRol = 'true';
            $userRolQuery = '1=1';

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
                    $userSucreQuery = ' pbc.ejecutivo_ss_email = "' . \Auth::user()->email . '"';
                }
            }
            //ROL CANAL
            if ($rol->rol_entity_id == 2) {
                //ROL TIPO GERENCIA
                if ($rol->rol_type_id == 1) {
                    $userSucre = true;
                    $userSucreQuery = 'chan.id = ' . $channel[0]->id;
                } elseif ($rol->rol_type_id == 2) {
                    // ROL TIPO JEFATURA
                    $userSucre = true;
                    $userSucreQuery = ' agencies.id = "' . \Auth::user()->agen_id . '"';
                } else {
                    // ROL TIPO EJECUTIVO
                    $userSucre = true;
                    $userSucreQuery = ' sal.user_id = "' . \Auth::user()->id . '"';
                }
            }
            $newCharges = charges($document, $date, $saleId, $salesType, $chargesType, $statusForm, $userRol, $userRolQuery, $items, $userSucre, $userSucreQuery);
            
            return view('pagination.charges', [
                'charges' => $newCharges,
                'items' => $items,
                'edit' => $edit,
                'cancel' => $cancel,
                'create' => $create
            ]);
         }
    }

    public function create(request $request) {
        $charge = \App\Charge::find($request['chargeId']);
        //Obtain Sale Data
        $sale = \App\sales::find($charge->sales_id);
        //Obtain Charge Status
        $status = \App\status::find($sale->status_id);
        //Obtain Customer Data
        $customer = \App\customers::find($sale->customer_id); 
        
        //Obtain Documents
        $documents = \App\document::all();
        //Customer Country, Province, City
        $cusCity = \App\city::find($customer->city_id);
        $cusCityList = true;
        $cusProvince = \App\province::find($cusCity->province_id);
        $cusProvinceList = true;
        $cusCountry = \App\country::find($cusProvince->country_id);
        $cusCountryList = true;
        
        $countries = DB::select('select * from countries');
                
//        return $returnData;
        return view('payments.create', [
            'sale' => $sale,
            'charge' => $charge,
            'status' => $status,
            'customer' => $customer,
            'documents' => $documents,
            'cusCity' => $cusCity,
            'cusProvince' => $cusProvince,
            'cusCountry' => $cusCountry,
            'cusProvinceList' => $cusProvinceList,
            'cusCountryList' => $cusCountryList,
            'cusCityList' => $cusCityList,
            'countries' => $countries
        ]);
    }

    public function createGet($id) {
        $charge = \App\Charge::find($id);
        //Obtain Sale Data
        $sale = \App\sales::find($charge->sales_id);
        //Obtain Charge Status
        $status = \App\status::find($sale->status_id);
        //Obtain Customer Data
        $customer = \App\customers::find($sale->customer_id); 
        
        //Obtain Documents
        $documents = \App\document::all();
        //Customer Country, Province, City
        $cusCity = \App\city::find($customer->city_id);
        $cusCityList = true;
        $cusProvince = \App\province::find($cusCity->province_id);
        $cusProvinceList = true;
        $cusCountry = \App\country::find($cusProvince->country_id);
        $cusCountryList = true;
        
        $countries = DB::select('select * from countries');
        
        $products = \App\products::selectRaw('products.productodes as "name", sal.total as "price"')
                                ->join('products_channel as pbc','pbc.product_id','=','products.id')
                                ->join('sales as sal','sal.pbc_id','=','pbc.id')
                                ->where('sal.id','=',$sale->id)
                                ->get();
        
//        return $returnData;
        return view('payments.create', [
            'sale' => $sale,
            'charge' => $charge,
            'status' => $status,
            'customer' => $customer,
            'documents' => $documents,
            'cusCity' => $cusCity,
            'cusProvince' => $cusProvince,
            'cusCountry' => $cusCountry,
            'cusProvinceList' => $cusProvinceList,
            'cusCountryList' => $cusCountryList,
            'cusCityList' => $cusCityList,
            'countries' => $countries,
            'product' => $products[0]
        ]);
    }

    public function storeOLD(request $request) {
//        return $request;
        //Validate Payment Type
        //Obtain Sale data
        $sale = \App\sales::find($request['salId']);

        //Date 
        $now = new \DateTime();

        //Payment
        $payment = new \App\payments();
        $payment->payment_type_id = 1;
        $payment->date = $now;
        $payment->number = $request['number'];
        $payment->value = $sale->total;
        $payment->user_id = Auth::user()->id;
        $payment->save();

        //Charge Query
        $chargeQuery = 'select * from charges where sales_id =' . $sale->id;
        $chargeSelect = DB::select($chargeQuery);

        $charge = \App\Charge::find($chargeSelect[0]->id);
        $charge->sales_id = $sale->id;
        $charge->payments_id = $payment->id;
        $charge->status_id = 9;
        $charge->date = $now;
        $charge->save();

        //Activate SALE
        $result = activateSale($sale->id);

        //Send Welcome Mail
//        Mail::to('coberto@magnusmas.com')->send(new WelcomeEmail($sale->customer_id, $sale->id));
        welcomeMail($sale->customer_id);

        //Obtain Payments
        //        $charge = \App\Charge::all();

        $chargesQuery = 'select 
                        cha.id as "id",
                        cha.sales_id as "salId",
                        cus.document as "document",
                        DATE_FORMAT(cha.date, "%Y-%m-%d") as "date",
                        cha.value as "value",
                        sta.name as "status",
                        typ.name as "type",
                        ctype.name as "typeCharge"    
                        from charges cha
                        join customers cus on cus.id = cha.customers_id
                        join status sta on sta.id = cha.status_id
                        left join payments pay on pay.id = cha.payments_id
                        left join payments_types typ on typ.id = pay.payment_type_id
                        join sales sal on sal.id = cha.sales_id
                        join charges_types ctype on ctype.id = cha.types_id where sal.status_id not in (11,10)';

        $charges = DB::select($chargesQuery);

        return view('payments.index', [
            'charges' => $charges
        ]);
    }

    public function store(request $request) {
        if (($request['option'] == 'cash') || ($request['option'] == 'transfer')) {
            $now = new \DateTime();
            
            //Obtain Charge Data
            $charges = \App\Charge::find($request['chargeId']);

            //Obtain Sale data
            $sale = \App\sales::find($charges->sales_id);

            //Payment Type
            if ($request['option'] == 'transfer') {
                $paymentType = 3;
            } else {
                $paymentType = 1;
            }

            //Payment
            $payment = new \App\payments();
            $payment->payment_type_id = $paymentType;
            $payment->date = $now;
            $payment->number = $request['number'];
            $payment->value = $sale->total;
            $payment->user_id = Auth::user()->id;
            $payment->save();


            //Charge Query
            //                $chargeQuery = 'select * from charges where sales_id =' . $sale->id;
            //                $chargeSelect = \App\Charge::find($request['data']['chargeId']);

            $charge = \App\Charge::find($request['chargeId']);
            $charge->payments_id = $payment->id;
            $charge->status_id = 9;
            $charge->date = $now;
            $charge->save();

            $customerQuery = 'select cus.email as "email" from customers cus
                                        join sales sal on sal.customer_id = cus.id
                                        where sal.id = ' . $sale->id;
            $customer = DB::select($customerQuery);

            //Send Welcome Mail
//                    $job = (new EmailJobs($sale->customer_id, $sale->id, $customer[0]->email));
//                    dispatch($job);
//                    Mail::to('coberto@magnusmas.com')->send(new Email($sale->customer_id, $sale->id));
//                    welcomeMail($sale->customer_id, $sale->id);
            //Activate SALE
            $result = activateSale($sale->id);
            return redirect('payments');
        }
        if (($request['option'] == 'creditCard')) {
            $now = new \DateTime();
            //Obtain Charge Data
            $charges = \App\Charge::find($request['chargeId']);

            //Obtain Sale data
            $sale = \App\sales::find($charges->sales_id);
            $sale->status_id = 21;
            $sale->save();

            //Payment Type
            $paymentType = 2;

            $number = substr($request['number'], -4);
            
            //Payment
            $payment = new \App\payments();
            $payment->payment_type_id = $paymentType;
            $payment->date = $now;
            $payment->number = $number;
            $payment->first_name = $request['first_name'];
            $payment->last_name = $request['last_name'];
            $payment->email = $request['email'];
            $payment->document = $request['document'];
            $payment->cvc = $request['cvc'];
            $payment->month = $request['month'];
            $payment->year = $request['year'];
            $payment->value = $sale->total;
            $payment->user_id = Auth::user()->id;
            $payment->save();

            //Charge Query
            $charge = \App\Charge::find($request['chargeId']);
            $charge->payments_id = $payment->id;
            $charge->status_id = 9;
            $charge->date = $now;
            $charge->save();
            
            \Session::flash('paymentsStore', 'El pago ha sido registrado con exito.');
            
            if(isset($request['redirect'])){
                return 'true';
            }else{
                return redirect('payments');
            }
        }
    }

    public function modal(request $request) {

        //Obtain Charges Data
        $charge = \App\Charge::find($request['data']['id']);

        //Obtain Sales Data
        $sale = \App\sales::find($charge->sales_id);


        //Status
        $status = \App\status::find($sale->status_id);
        if ($charge->types_id == 1) {
            $returnData = '<input type="hidden" id="chargeId" name="chargeId" value="' . $charge->id . '">
                    <div class="col-md-10 col-md-offset-1 border">
                        <h5>Resumen de la Venta</h5>
                        <table id="saleResumeTable" class="table table-bordered">
                            <tbody>
                                <tr style="background-color: #183c6b;color: white;">
                                    <th>Venta</th>
                                    <th>Fecha de Emisión</th>
                                    <th>Fecha Inicio Cobertura</th>
                                    <th>Fecha Fin Cobertura</th>
                                    <th>Estado</th>
                                </tr>
                                <tr>
                                    <td align="center">' . $sale->id . '</td>
                                    <td align="center">' . $sale->emission_date . '</td>
                                    <td align="center">' . $sale->begin_date . '</td>
                                    <td align="center">' . $sale->end_date . '</td>
                                    <td align="center">' . $status->name . '</td>
                                </tr>
                                <tr style="background-color: #183c6b;color: white;">
                                    <th>Subtotal 12%</th>
                                    <th>Subtotal 0%</th>
                                    <th>IVA 12%</th>
                                    <th colspan="2">Total</th>
                                </tr>
                                <tr>
                                    <td align="center">' . $sale->subtotal_12 . '</td>
                                    <td align="center">' . $sale->subtotal_0 . '</td>
                                    <td align="center">' . $sale->tax . '</td>
                                    <td align="center" colspan="2">' . $sale->total . '</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="col-md-12 border">
                            <div class="col-md-12" style="padding-left:5px !important;padding-right: 5px">
                                <div class="col-md-3 wizard_inactivo registerForm">
                                </div>
                                <div id="cash" class="col-md-3 wizard_inactivo registerForm" onclick="cashDivClick()" style="cursor: pointer;">
                                    <span><input id="cashRadioBtn" type="radio" name="option" value="cash"> Caja</span>
                                </div>
                                <div id="datafast" class="col-md-3 wizard_inactivo registerForm" onclick="datafastDivClick()" style="cursor: pointer;">
                                   <input type="radio" id="datafastRadioBtn" name="option" value="datafast"> Boton de Pagos
                                </div>
                                <div class="col-md-3 wizard_inactivo registerForm">
                                </div>
                            </div>
                            <br><br>
                            <div id="cashDiv" class="col-md-12 hidden" style="margin-top:15px">
                                <div id="resultMessage">
                                </div>
                                <div class="form-group">
                                    <label for="number">Ingrese el numero de recibo</label>
                                    <input type="text" class="form-control" id="number" name="number">
                                </div>
                                <div class="col-md-12" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                                    <button type="button" class="btn btn-default" data-dismiss="modal" style="margin-left: -15px;width:75px">Cerrar</button>
                                    <input id="cashBtn" type="submit" style="float:right;margin-right: -15px;padding: 5px;width:75px" class="btn btn-info registerForm" align="right" value="Pagar" onclick="submitPaymentForm()">

                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                        </div>
                    </div>
               ';
        } else {
            $returnData = '<input type="hidden" id="chargeId" name="chargeId" value="' . $charge->id . '">
                    <input type="hidden" id="number" name="number" value="1">
                    <div class="col-md-10 col-md-offset-1 border">
                    <h5>Resumen de la Venta</h5>
                        <table id="saleResumeTable" class="table table-bordered">
                            <tbody>
                                <tr style="background-color: #183c6b;color: white;">
                                    <th>Venta</th>
                                    <th>Fecha de Emisión</th>
                                    <th>Fecha Inicio Cobertura</th>
                                    <th>Fecha Fin Cobertura</th>
                                    <th>Estado</th>
                                </tr>
                                <tr>
                                    <td align="center">' . $sale->id . '</td>
                                    <td align="center">' . $sale->emission_date . '</td>
                                    <td align="center">' . $sale->begin_date . '</td>
                                    <td align="center">' . $sale->end_date . '</td>
                                    <td align="center">' . $status->name . '</td>
                                </tr>
                                <tr style="background-color: #183c6b;color: white;">
                                    <th>Subtotal 12%</th>
                                    <th>Subtotal 0%</th>
                                    <th>IVA 12%</th>
                                    <th colspan="2">Total</th>
                                </tr>
                                <tr>
                                    <td align="center">' . $sale->subtotal_12 . '</td>
                                    <td align="center">' . $sale->subtotal_0 . '</td>
                                    <td align="center">' . $sale->tax . '</td>
                                    <td align="center" colspan="2">' . $sale->total . '</td>
                                </tr>
                            </tbody>
                        </table>';
            if ($charge->motives_id == 3) {
                $returnData .= '<h5>Vehiculos Cancelados</h5>
                                <table id="saleResumeTable" class="table table-bordered">
                            <tbody>
                                <tr style="background-color: #183c6b;color: white;">
                                    <th>Placa</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>Año</th>
                                    <th>Color</th>
                                </tr>';
                //Obtain Vehicules
                $vehiclesQuery = 'select vehi.* from vehicles vehi join vehicles_sales vsal on vsal.vehicule_id = vehi.id and vsal.charges_id = ' . $charge->id;

                $vehicles = DB::select($vehiclesQuery);

                foreach ($vehicles as $vehicle) {
                    $returnData .= '<tr>
                                                    <td align="center">' . $vehicle->plate . '</td>
                                                    <td align="center">' . $vehicle->brand_id . '</td>
                                                    <td align="center">' . $vehicle->model . '</td>
                                                    <td align="center">' . $vehicle->year . '</td>
                                                    <td align="center">' . $vehicle->color . '</td>
                                                </tr>';
                };

                $returnData .= '</tbody>
                        </table>';
            }
            $returnData .= ' <div class="col-md-12 border" style="padding-bottom:10px">
                        <div class="col-md-12">
                            <div class="col-md-4 wizard_inactivo registerForm">
                            </div>
                            <div id="cash" class="col-md-4 wizard_activo registerForm" style="cursor: pointer;">
                                <span><input id="transferRadioBtn" type="radio" name="option" value="transfer" checked> Transferencia</span>
                            </div>
                            <div id="datafast" class="col-md-4 wizard_inactivo registerForm" onclick="datafastDivClick()" style="cursor: pointer;">

                            </div>
                        </div>
                        <br><br>
                        <div id="transferDiv" class="col-md-12" style="margin-top:15px">
                            <div id="resultMessage">
                            </div>
                            <div class="form-group">
                            <center>
                                <p>
                                    Recuerde que una vez realizado el pago de la Nota de Credito, el proceso no podra ser reversado
                                </p>
                                </center>
                            </div>
                            <div class="col-md-12" style="margin-top:5px;padding-top:15px;padding-bottom:15px;">
                                <button type="button" class="btn btn-default" data-dismiss="modal" style="margin-left: -15px;width:75px">Cerrar</button>
                                <input id="cashBtn" type="submit" style="float:right;margin-right: -15px;padding: 5px;width:75px" class="btn btn-info registerForm" align="right" value="Pagar" onclick="validateModalForm()">

                            </div>
                        </div>
                        </div>
                    </div>
               ';
        }


        return $returnData;
    }

    public function modalStore(request $request) {
        if ($request['number'] != null) {
            if (($request['option'] == 'cash') || ($request['option'] == 'transfer')) {
                //Obtain Charge Data
                $charges = \App\Charge::find($request['data']['chargeId']);

                //Obtain Sale data
                $sale = \App\sales::find($charges->sales_id);

                //Payment Type
                if ($request['option'] == 'transfer') {
                    $paymentType = 3;
                } else {
                    $paymentType = 1;
                }

                //Payment
                $payment = new \App\payments();
                $payment->payment_type_id = $paymentType;
                $payment->date = now();
                $payment->number = $request['data']['number'];
                $payment->value = $sale->total;
                $payment->user_id = Auth::user()->id;
                $payment->save();


                //Charge Query
//                $chargeQuery = 'select * from charges where sales_id =' . $sale->id;
//                $chargeSelect = \App\Charge::find($request['data']['chargeId']);

                $charge = \App\Charge::find($request['data']['chargeId']);
                $charge->payments_id = $payment->id;
                $charge->status_id = 9;
                $charge->date = now();
                $charge->save();

                //Send Welcome Mail
                welcomeMail($sale->customer_id);

                //Activate SALE
                $result = activateSale($sale->id);
                $returnData = '<div class="alert alert-success">
                                <center>
                                    <strong>
                                      El pago fue registrado exitosamente.
                                    </strong>
                                </center>
                            </div>';

                $returnArray = [
                    'success' => 'true',
                    'date' => $charge->date,
                    'paymentType' => 'CAJA',
                    'id' => $charge->id,
                    'message' => $returnData
                ];

                return $returnArray;
            }
        } else {
            $returnData = '<div class="alert alert-danger">
                          <center>
                              <strong>
                                  Por favor ingrese un numero valido.
                              </strong>
                          </center>
                        </div>';

            $returnArray = [
                'success' => 'false',
                'message' => $returnData
            ];
            return $returnArray;
        }
    }

    public function modalResume(request $request) {

        //Obtain Charges Data
        $charge = \App\Charge::find($request['data']['id']);

        //Obtain Sales Data
        $sale = \App\sales::find($charge->sales_id);


        //Status
        $status = \App\status::find($sale->status_id);
        if ($charge->types_id == 1) {//FACTURA
            $returnData = '<input type="hidden" id="chargeId" name="chargeId" value="' . $charge->id . '">
                    <div class="col-md-10 col-md-offset-1 border">
                        <h5>Resumen de la Venta</h5>
                        <table id="saleResumeTable" class="table table-bordered">
                            <tbody>
                                <tr style="background-color: #183c6b;color: white;">
                                    <th>Venta</th>
                                    <th>Fecha de Emisión</th>
                                    <th>Fecha Inicio Cobertura</th>
                                    <th>Fecha Fin Cobertura</th>
                                    <th>Estado</th>
                                </tr>
                                <tr>
                                    <td align="center">' . $sale->id . '</td>
                                    <td align="center">' . $sale->emission_date . '</td>
                                    <td align="center">' . $sale->begin_date . '</td>
                                    <td align="center">' . $sale->end_date . '</td>
                                    <td align="center">' . $status->name . '</td>
                                </tr>
                                <tr style="background-color: #183c6b;color: white;">
                                    <th>Subtotal 12%</th>
                                    <th>Subtotal 0%</th>
                                    <th>IVA 12%</th>
                                    <th colspan="2">Total</th>
                                </tr>
                                <tr>
                                    <td align="center">' . $sale->subtotal_12 . '</td>
                                    <td align="center">' . $sale->subtotal_0 . '</td>
                                    <td align="center">' . $sale->tax . '</td>
                                    <td align="center" colspan="2">' . $sale->total . '</td>
                                </tr>
                            </tbody>
                        </table>';
            if ($charge->status_id == 9) {//If its paid
                //Obtain Payment
                $payment = \App\payments::find($charge->payments_id);
                if ($payment->payment_type_id == 1) {//CASH PAYMENT
                    $paymentType = \App\paymentTypes::find($payment->payment_type_id); //PAYMENT TYPE
                    $returnData .= '<h5>Resumen del pago</h5>
                        <table id="saleResumeTable" class="table table-bordered">
                                <tbody>
                                    <tr style="background-color: #183c6b;color: white;">
                                        <th>Numero</th>
                                        <th>Fecha</th>
                                        <th>Valor</th>
                                        <th>Forma de Pago</th>
                                    </tr>
                                    <tr>
                                        <td align="center">' . $payment->number . '</td>
                                        <td align="center">' . date("d-m-Y h:i:s", strtotime($payment->date)) . '</td>
                                        <td align="center">' . $payment->value . '</td>
                                        <td align="center">' . $paymentType->name . '</td>
                                    </tr>
                                </tbody>
                            </table>';
                } else { //DATAFAST PAYMENT
                    $paymentType = \App\paymentTypes::find($payment->payment_type_id); //PAYMENT TYPE
                    $card = \App\cards::find($payment->cards_id);
                    $returnData .= '<h5>Resumen del pago</h5>
                        <table id="saleResumeTable" class="table table-bordered">
                                <tbody>
                                    <tr style="background-color: #183c6b;color: white;">
                                        <th>Numero</th>
                                        <th>Fecha</th>
                                        <th>Valor</th>
                                        <th>Forma de Pago</th>
                                    </tr>
                                    <tr>
                                        <td align="center">' . $payment->number . '</td>
                                        <td align="center">' . date("d-m-Y h:i:s", strtotime($payment->date)) . '</td>
                                        <td align="center">' . $payment->value . '</td>
                                        <td align="center">' . $paymentType->name . '</td>
                                    </tr>
                                </tbody>
                            </table>';
                }
            }
            $returnData .= '<br><br>
                    </div>
                    <div class="col-md-12 hidden" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                                <button type="button" class="btn btn-default" data-dismiss="modal" style="margin-left: -15px;width:75px">Cerrar</button>

                    </div>
               ';
        } else {//NOTA DE CREDITO
            $returnData = '<input type="hidden" id="chargeId" name="chargeId" value="' . $charge->id . '">
                    <input type="hidden" id="number" name="number" value="1">
                    <div class="col-md-10 col-md-offset-1 border">
                    <h5>Resumen de la Venta</h5>
                        <table id="saleResumeTable" class="table table-bordered">
                            <tbody>
                                <tr style="background-color: #183c6b;color: white;">
                                    <th>Venta</th>
                                    <th>Fecha de Emisión</th>
                                    <th>Fecha Inicio Cobertura</th>
                                    <th>Fecha Fin Cobertura</th>
                                    <th>Estado</th>
                                </tr>
                                <tr>
                                    <td align="center">' . $sale->id . '</td>
                                    <td align="center">' . $sale->emission_date . '</td>
                                    <td align="center">' . $sale->begin_date . '</td>
                                    <td align="center">' . $sale->end_date . '</td>
                                    <td align="center">' . $status->name . '</td>
                                </tr>
                                <tr style="background-color: #183c6b;color: white;">
                                    <th>Subtotal 12%</th>
                                    <th>Subtotal 0%</th>
                                    <th>IVA 12%</th>
                                    <th colspan="2">Total</th>
                                </tr>
                                <tr>
                                    <td align="center">' . $sale->subtotal_12 . '</td>
                                    <td align="center">' . $sale->subtotal_0 . '</td>
                                    <td align="center">' . $sale->tax . '</td>
                                    <td align="center" colspan="2">' . $sale->total . '</td>
                                </tr>
                            </tbody>
                        </table>';
            if ($charge->motives_id == 3) {//CANCELACIÓN
                $returnData .= '<h5>Vehiculos Cancelados</h5>
                                <table id="saleResumeTable" class="table table-bordered">
                            <tbody>
                                <tr style="background-color: #183c6b;color: white;">
                                    <th>Placa</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>Año</th>
                                    <th>Color</th>
                                </tr>';
                //Obtain Vehicules
                $vehiclesQuery = 'select vehi.* from vehicles vehi join vehicles_sales vsal on vsal.vehicule_id = vehi.id and vsal.charges_id = ' . $charge->id;

                $vehicles = DB::select($vehiclesQuery);

                foreach ($vehicles as $vehicle) {
                    $returnData .= '<tr>
                                                    <td align="center">' . $vehicle->plate . '</td>
                                                    <td align="center">' . $vehicle->brand_id . '</td>
                                                    <td align="center">' . $vehicle->model . '</td>
                                                    <td align="center">' . $vehicle->year . '</td>
                                                    <td align="center">' . $vehicle->color . '</td>
                                                </tr>';
                };

                $returnData .= '</tbody>
                        </table>';
            }
            if ($charge->status_id == 9) {//If its paid
                //Obtain Payment
                $payment = \App\payments::find($charge->payments_id);
                $paymentType = \App\paymentTypes::find($payment->payment_type_id); //PAYMENT TYPE
                $returnData .= '<h5>Resumen del pago</h5>
                        <table id="saleResumeTable" class="table table-bordered">
                                <tbody>
                                    <tr style="background-color: #183c6b;color: white;">
                                        <th>Numero</th>
                                        <th>Fecha</th>
                                        <th>Valor</th>
                                        <th>Forma de Pago</th>
                                    </tr>
                                    <tr>
                                        <td align="center">' . $payment->number . '</td>
                                        <td align="center">' . $payment->date . '</td>
                                        <td align="center">' . $payment->value . '</td>
                                        <td align="center">' . $paymentType->name . '</td>
                                    </tr>
                                </tbody>
                            </table>';
            }
            $returnData .= ' 
                        <br><br>
                    </div>
                    <div class="col-md-12 hidden" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                                <button type="button" class="btn btn-default" data-dismiss="modal" style="margin-left: -15px;width:75px">Cerrar</button>

                    </div>
                    
               ';
        }


        return $returnData;
    }
    
    public function validateNumber(request $request){
        $numberQuery = 'select id from payments where number = '.$request['data']['number'];
        $number = DB::select($numberQuery);
        if($number){
            $returnArray = [
                "success" => 'false',
                "message" => 'El numero ya se encuentra ingresado'
            ];
        }else{
            $returnArray = [
                "success" => 'true'
            ];
        }
        return $returnArray;    
    }
    
    public function refund($sales){
        $salesId = \decrypt($sales);
        
        //Obtain Sale Data
        $sale = \App\sales::find($salesId);
        //Obtain Charge
        $chargeSearch = \App\Charge::where('sales_id',$sale->id)->get();
        $charge = \App\Charge::find($chargeSearch[0]->id);  
        //Obtain Charge Status
        $status = \App\status::find($sale->status_id);
        //Obtain Customer Data
        $customer = \App\customers::find($sale->customer_id);
        
//        $paymentPrepareCheckout = paymentPrepareCheckout();
        $items = array();
        $product = array( "name" => "test",
                "description" => "test",
                "price" => "$sale->total",
                "quantity" => "1");
        array_push($items,$product);
        
         return view('payments.refund', [
            'checkoutId' => 1,
            'sale' => $sale,
            'charge' => $charge,
            'status' => $status,
            'customer' => $customer
        ]);
    }
    
    public function refundStore(request $request){
        $log = \App\datafast_log::where('id_cart','=',$request['sale_id'])->where('code','=','000.100.112')->where('type','=','PAYMENT')->latest()->first();
        
        //Obtain Payment Detail
        $payment  = \App\Charge::selectRaw('pay.auth_code, pay.reference, pay.transaction_id, pay.custom_parameters, pay.custom_value, pay.shopper_interes, pay.shopper_installments')
                                ->join('payments as pay','pay.id','=','charges.payments_id')
                                ->where('charges.sales_id','=',$request['sale_id'])
                                ->orderBy('charges.id','DESC')
                                ->first();
        
        
//        $result = annulmentDataFast('RF', $log->id_response, $sale->total, 'USD', $log->auth_code, $log->reference, 'XXX', $log->custom_value, $request['card_number'], $request['month'], $request['year'], $payment->shopper_interes, $payment->shopper_installments);
        $result = annulmentDataFast('RF', $log->id_response, '5.00', 'USD', $log->auth_code, $log->reference, 'XXX', $log->custom_value, $request['card_number'], $request['month'], $request['year'], $payment->shopper_interes, $payment->shopper_installments);
        if($result['result']['code'] == '000.100.112'){
            $sale = \App\sales::find($request['sale_id']);
            $sale->status_id = 4;
            $sale->save();
            
            anulacionEndodoSS($sale->id);
        }
        $sale = \App\sales::find($request['sale_id']);
        
        //Update Log
        $logNew = new \App\datafast_log();
        $logNew->id_cart = $sale->id;
        $logNew->order = null;
        $logNew->order_date = new \DateTime();
        $logNew->id_transaction = $request['sale_id'];
        $logNew->lot = $log->lot;
        $logNew->reference = $log->reference;
        $logNew->auth_code = $log->auth_code;
        $logNew->code = $result['result']['code'];
        $logNew->response = $result['result']['description'];
        $logNew->id_response = $result['id'];
        $logNew->custom_value = $result['customParameters']['1000000505_PD100406'];
        $logNew->type = 'REFUND';
        $logNew->save();
        
        //Vaidate Response
        if($result['result']['code'] == '000.100.112'){ $success = 'true'; }else{ $success = 'false'; }
        
        //Validate Response Code
        $responseCode = \App\datafast_response::where('code','=',$log->code)->get();
        if($responseCode->isEmpty()){
            return view('payments.paymentsResult',[
                'success' => $success,
                'code' => $logNew->code,
                'message' => $logNew->response
            ]);
        }else{
            return view('payments.paymentsResult',[
                'success' => $success,
                'code' => $responseCode[0]->code,
                'message' => $responseCode[0]->name
            ]);
        }
        
    }

}
