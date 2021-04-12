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
use App\Mail\paymentSendLinkUserEmail;
use App\Mail\beneficiariesRequestSendLinkEmail;
use App\Mail\insuranceRequestSendLinkEmail;
use Illuminate\Support\Facades\Storage;

class SalesController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('validateUserRoute');
    }

    public function index(request $request) {
        $viewPermit = checkViewPermit('1', \Auth::user()->role_id);
        if (!$viewPermit) {
            \Session::flash('ValidateUserRoute', 'No tiene acceso al modulo de ventas Individual.');
            return view('home');
        }
        
        //Obtain Create Permission
        $create = checkExtraPermits('19', \Auth::user()->role_id);

        //Obtain Edit Permission
        $edit = checkExtraPermits('3', \Auth::user()->role_id);


        //Obtain Cancel Permission
        $cancel = checkExtraPermits('5', \Auth::user()->role_id);

        //Obtain Channel
        $channelQuery = 'select cha.* from channels cha join agencies agen on agen.channel_id = cha.id where agen.id =  "' . \Auth::user()->agen_id . '"';
        $channel = DB::select($channelQuery);

        //Obtain Sales Status
        $status = \App\status::find([4, 9, 10, 12, 20, 21, 22, 23,24,25, 26,26, 28,29, 30, 31, 32, 33, 34, 35]);

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
            session(['salesPolicyNumber' => $request->policy_number]);
            session(['salesCustomer' => $request->customer]);
            session(['salesDocument' => $request->document]);
            session(['salesPlate' => $request->plate]);
            session(['salesDateFrom' => $request->dateFrom]);
            session(['salesDateUntil' => $request->dateUntil]);
            session(['salesSaleId' => $request->saleId]);
            session(['salesAdviser' => $request->adviser]);
            session(['salesStatus' => $request->status]);
            session(['salesItems' => $request->items]);
            session(['salesMovements' => $request->movement]);
            $currentPage = 1;
            session(['salesPage' => 1]);
        } else {
            $currentPage = session('salesPage');
        }

        //Pagination Items
        if (session('salesItems') == null) {
            $items = 10;
        } else {
            $items = session('salesItems');
        }

        //Form Variables
        $policyNumber = session('salesPolicyNumber');
        $customer = session('salesCustomer');
        $document = session('salesDocument');
        $plate = session('salesPlate');
        $dateFrom = session('salesDateFrom');
        $dateUntil = session('salesDateUntil');
        $saleId = session('salesSaleId');
        $adviser = session('salesAdviser');
        $statusForm = session('salesStatus');
        $salesMovementsForm = session('salesMovements');

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
        $newSales = individual($policyNumber, $customer, $document, $plate, $dateFrom, $dateUntil, $saleId, $adviser, $statusForm, $userRol, $userQueryForm, $salesMovementsForm, $items, $userSucre, $userSucreQuery);
        
        $countries = \App\country::all();
        $documents = \App\document::all();
        
        return view('sales.index', [
            'sales' => $newSales,
            'status' => $status,
            'users' => $users,
            'items' => $items,
            'edit' => $edit,
            'cancel' => $cancel,
            'create' => $create,
            'salesMovements' => $salesMovements,
            'countries' => $countries,
            'documents' => $documents
        ]);
    }

    function fetch_data(Request $request) {
        if ($request->ajax()) {
            //Page
            session(['salesPage' => $request->page]);

            //Obtain Edit Permission
            $edit = checkExtraPermits('3', \Auth::user()->role_id);

            //Obtain Create Permission
            $create = checkExtraPermits('19', \Auth::user()->role_id);

            //Obtain Cancel Permission
            $cancel = checkExtraPermits('5', \Auth::user()->role_id);

            //Obtain Channel
            $channelQuery = 'select cha.* from channels cha join agencies agen on agen.channel_id = cha.id where agen.id =  "' . \Auth::user()->agen_id . '"';
            $channel = DB::select($channelQuery);


            //Pagination Items
            if (session('salesItems') == null) {
                $items = 10;
            } else {
                $items = session('salesItems');
            }

            //Form Variables
            $firstName = session('salesFirstName');
            $lastName = session('salesLastName');
            $document = session('salesDocument');
            $plate = session('salesPlate');
            $date = session('salesDate');
            $email = session('salesEmail');
            $saleId = session('salesSaleId');
            $adviser = session('salesAdviser');
            $statusForm = session('salesStatus');
            $salesMovementsForm = session('salesMovements');

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
            $newSales = individual($firstName, $lastName, $document, $plate, $date, $email, $saleId, $adviser, $statusForm, $userRol, $userQueryForm, $salesMovementsForm, $items, $userSucre, $userSucreQuery);

            return view('pagination.individual', [
                'sales' => $newSales,
                'items' => $items,
                'edit' => $edit,
                'cancel' => $cancel,
                'create' => $create
            ]);
        }
    }

    public function productSelect() {
        return view('sales.productSelect');
    }

    public function createR1() {
        //Validate Create Permission
        $create = checkExtraPermits('19', \Auth::user()->role_id);
        if (!$create) {
            \Session::flash('ValidateUserRoute', 'No tiene acceso a crear nuevas cotizaciones.');
            return view('home');
        }
        
        //Obtain Create Permission

        $customer = new \App\customers();

        //Customer Country, Province, City
        $cusCity = new \App\city();
        $cusCityList = false;
        $cusProvince = new \App\province();
        $cusProvinceList = false;
        $cusCountry = new \App\country();
        $cusCountryList = false;

        $disabled = null;
        $vehiBodyTable = '';

        //Obtain Channel
        $channelQuery = 'select cha.* from channels cha join agencies agen on agen.channel_id = cha.id where agen.id =  "' . \Auth::user()->agen_id . '"';
        $channel = DB::select($channelQuery);

        //Product Data
        $products = DB::select('select pCha.id, pro.name, pro.price, pro.total_price, pro.segment, pro.detail, pro.conditions, pro.exclutions from products pro join products_channel pCha on pCha.product_id = pro.id where pro.status_id = "1" and pCha.channel_id = "' . $channel[0]->id . '" and pro.product_type in ("INDIVIDUAL","AMBOS")');
//        return $products;
        $documents = DB::select('select * from documents where id in (1,3)');
        $countries = DB::select('select * from countries where id = 1');
        $vehiclesBrands = DB::select('select * from vehicles_brands where status_id = 1 order by name');
        $vehiclesTypes = \App\vehicles_type::all();
        $vehiClass = \App\vehicle_class::find([14, 1, 5, 7]);

        $sale_movement = 1;

        return view('sales.R1.create', [
            'products' => $products,
            'documents' => $documents,
            'countries' => $countries,
            'vehiclesBrands' => $vehiclesBrands,
            'customer' => $customer,
            'disabled' => $disabled,
            'cusCity' => $cusCity,
            'cusProvince' => $cusProvince,
            'cusCountry' => $cusCountry,
            'cusCityList' => $cusCityList,
            'cusProvinceList' => $cusProvinceList,
            'vehiBodyTable' => $vehiBodyTable,
            'sale_movement' => $sale_movement,
            'sale_id' => null,
            'vehiclesType' => $vehiclesTypes,
            'vehiClass' => $vehiClass
        ]);
    }

    function assetView($id, $data) {
        $insuredID = session('salesCreateLifeInsuredId');
        $customerID = session('salesCreateLifeCustomerId');
        if ($insuredID == null) {
            $customer = \App\customers::find($customerID);
        } else {
            $customer = \App\insured::find($insuredID);
        }

        //Variable to Return
        $sale_movement = 1;
        $sale_id = null;
        $insuranceBranch = 2;

        //Validate Insurance Branch
        if ($id == 2) {
            $countries = DB::select('select * from countries');
            $documents = DB::select('select * from documents where id in (1,3)');
            //Customer Country, Province, City
            $cusCity = \App\city::find($customer->city_id);
            $cusProvince = \App\province::find($cusCity->province_id);
            $cusCountry = \App\country::find($cusProvince->country_id);
            return view('sales.R2.insured', [
                'sale_movement' => $sale_movement,
                'sale_id' => $sale_id,
                'insuranceBranch' => $insuranceBranch,
                'customer' => $customer,
                'countries' => $countries,
                'documents' => $documents,
                'cusCountry' => $cusCountry,
                'cusCity' => $cusCity,
                'cusProvince' => $cusProvince,
                'customerId' => $customer->id,
                'insuredId' => null
            ]);
        }
    }

    public function productView() {
        $insuranceBranch = 2;
        return view('sales.products', [
            'sale_movement' => 1,
            'sale_id' => null,
            'insurance_branch' => $insuranceBranch
        ]);
    }

    public function showData(request $request) {
        //Product Data
        $products = DB::select('select * from products where status_id = "1"');

        //Obtain Channel
        $channelQuery = 'select cha.* from channels cha join agencies agen on agen.channel_id = cha.id where agen.id =  "' . \Auth::user()->agen_id . '"';
        $channel = DB::select($channelQuery);

//        return $channelQuery;
        $sql = 'select distinct sal.id as "salesId",
                    concat(cus.first_name," ",cus.last_name) as "customer",
                    cus.document as "document",
                    sal.total as "total",
                    sal.emission_date as "date",
                    sta.name as "status",
                    sal.status_id as "status_id",
                    sta.name as "statuSal",
                    IF(sal.allow_cancel_date is not null, IF(NOW()<=sal.allow_cancel_date, "NO", "YES"),"NO") as "allow_cancel"
                    
                    from sales sal
                    join customers cus on cus.id = sal.customer_id
                    join status sta on sta.id = sal.status_id
                    join vehicles_sales vsal on vsal.sales_id = sal.id
                    join vehicles vehi on vehi.id = vsal.vehicule_id
                    join users usr on usr.id = sal.user_id
                    JOIN agencies agen ON agen.id = usr.agen_id
                    JOIN products_channel pbc on pbc.id = sal.pbc_id
                    JOIN channels cha ON cha.id = pbc.channel_id
                    where sal.sales_type_id in (1,3)';

        //Validate User Role
        if (\Auth::user()->role_id == 1 || \Auth::user()->role_id == 2 || \Auth::user()->role_id == 3) {
            //NADA
        } elseif (\Auth::user()->role_id == '6') {//
            $sql .= ' AND sal.user_id = ' . \Auth::user()->id;
        } else {
            $sql .= ' AND cha.id = ' . $channel[0]->id;
        }

        //Options SQL Variable
        $sql2 = '';
        $where = false;

        //Validate Filter Form
        //First Name
        if ($request->first_name != null) {
            $sql2 .= ' AND cus.first_name like "%' . $request->first_name . '%"';
        }
        //Last Name
        if ($request->last_name != null) {
            $sql2 .= ' AND cus.last_name like "%' . $request->last_name . '%"';
        }
        //Document
        if ($request->document != null) {
            $sql2 .= ' AND cus.document like "%' . $request->document . '%"';
        }
        //Plate
        if ($request->plate != null) {
            $sql2 .= ' AND vehi.plate like "%' . $request->plate . '%"';
        }
        //Date
        if ($request->date != null) {
            $sql2 .= ' AND sal.emission_date like "%' . $request->date . '%"';
        }
        //Email
        if ($request->email != null) {
            $sql2 .= ' AND cus.email like "%' . $request->email . '%"';
        }
        //Email
        if ($request->saleId != null) {
            true;
            $sql2 .= ' AND sal.id like "%' . $request->saleId . '%"';
        }
        //Asesor
        if ($request->adviser != 0) {
            $sql2 .= ' AND usr.id = "' . $request->adviser . '"';
        }
        //Status
        if ($request->status != 0) {
            $sql2 .= ' AND sal.status_id = "' . $request->status . '"';
        }

        //Validate 
        //Order by
        $sql2 .= ' order by sal.emission_date DESC';
        $sql .= $sql2;

        //Sales Data
        $sales = DB::select($sql);

        $id = '0';
        foreach ($sales as $sale) {
            $id .= ',' . $sale->salesId;
        }

        //Obtain Sales Status
        $status = \App\status::find([1, 10, 3, 5, 7, 6, 4, 11]);

        //Obtain Users
        if (\Auth::user()->role_id == '6') {
            $userQuery = 'select * from users where role_id in (6)';
        } elseif (\Auth::user()->role_id == '4') {
            $userQuery = 'select usr.* from users  usr join agencies agen on agen.id = usr.agen_id join channels chan on chan.id = agen.channel_id where usr.role_id in (1,6,3,4,2) and chan.id = ' . $channel[0]->id;
        } else {
            $userQuery = 'select * from users where role_id in (1,6,3,4,2)';
        }
        $users = DB::select($userQuery);

        //Data Array
//        $data = array('first_name' => $request->first_name,
//            'last_name' => $request->last_name,
//            'document' => $request->document,
//            'plate' => $request->plate,
//            'date' => $request->date,
//            'email' => $request->email,
//            'saleId' => $request->saleId,
//            'adviser' => $request->adviser,
//            'status' => $request->status);
//
//        return view('sales.showData', [
//            'data' => $data,
//            'sales' => $sales,
//            'status' => $status,
//            'users' => $users
//        ]);
        return $sales;
    }

    public function getPosts() {
        return \DataTables::of(User::query())->make(true);
    }

    public function createRemote() {
        //Validate Create Permission
        $edit = checkExtraPermits('19', \Auth::user()->role_id);
        if (!$edit) {
            \Session::flash('ValidateUserRoute', 'No tiene acceso a crear ventas Individuales.');
            return view('home');
        }
        //Obtain Channel
        $channelQuery = 'select cha.* from channels cha join agencies agen on agen.channel_id = cha.id where agen.id =  "' . \Auth::user()->agen_id . '"';
        $channel = DB::select($channelQuery);

        //Product Data
        $products = DB::select('select pCha.id, pro.name, pro.price, pro.total_price, pro.segment, pro.detail, pro.conditions, pro.exclutions from products pro join products_channel pCha on pCha.product_id = pro.id where pro.status_id = "1" and pCha.channel_id = "' . $channel[0]->id . '" and pro.product_type = "INDIVIDUAL"');
//        return $products;
        $documents = DB::select('select * from documents where id in (1,3)');
        $countries = DB::select('select * from countries');
        $vehiclesBrands = DB::select('select * from vehicles_brands where status_id = 1 order by name');
        return view('sales.createRemote', [
            'products' => $products,
            'documents' => $documents,
            'countries' => $countries,
            'vehiclesBrands' => $vehiclesBrands
        ]);
    }

    public function resume(request $request) {
//        return $request;
        //Obtain Product Channel
        $productSql = 'select * from products_channel where id = ' . $request['data']['product'];
        $productChannel = DB::select($productSql);
        //Obtain ProductId
        $productId = $productChannel[0]->product_id;
        //Generate SQL
        $productSql = 'select * from products where id = ' . $productId;
        //Obtain Product Data
        $product = DB::select($productSql);

        //Obtain Product Tax
        $taxSql = 'SELECT tax.percentage FROM 
                    products_tax ptax 
                    join tax tax on tax.id = ptax.tax_id
                    where ptax.products_id = ' . $productId;
        $tax = DB::select($taxSql);

        //Obtain First Vehicle
        $vehicles = $request['data']['vehicles'];

        //Declare Vehicle Count Variable
        $vehicleCount = 1;

        //Obtain Channel
        $channelQuery = 'select cha.* 
                from channels  cha
                join agencies age on age.channel_id = cha.id
                where age.id = ' . \Auth::user()->agen_id;

        $channel = DB::select($channelQuery);

        $arrayResponse = array();

        $data = '';
        $vehicltesTableArray = array();
        $taxTableArray = array();
        $sub12Price = 0;
        $sub0Price = 0;

        foreach ($vehicles as $vehicle) {
            $data .= '<tr>';
            $data .= '<td align="center">' . $vehicleCount . '</td>';
            $data .= '<td align="center">' . $vehicle['model'] . '</td>';
            $data .= '<td align="center">' . $product[0]->price . '</td>';


            if ($vehicleCount == 1) {
                $data .= '<td align="center">0</td>';
                $data .= '<td align="right">' . $product[0]->price . '</td>';
                if ($tax[0]->percentage > 0) {
                    $sub12Price += $product[0]->price;
                } else {
                    $sub0Price += $product[0]->price;
                }
            } else {
                $discountSql = 'select dis.percentage
                                from discounts dis
                                join products_discount prd on prd.discounts_id = dis.id
                                where prd.products_id = ' . $productId . ' and dis.vechicles_count = ' . $vehicleCount;
                $discount = DB::select($discountSql);
//                return $discount;

                $discountPrice = round(($product[0]->price - (($discount[0]->percentage * $product[0]->price) / 100)), 2);
                if ($tax[0]->percentage > 0) {
                    $sub12Price += $discountPrice;
                } else {
                    $sub0Price += $discountPrice;
                }
                $data .= '<td align="center">' . $discount[0]->percentage . '</td>';
                $data .= '<td align="right">' . $discountPrice . '</td>';
            }
            $data .= '</tr>';
            $vehicleCount++;
        }


        //Obtain Extra Benefits(Other Discounts)
        $benefitsQuery = 'select * from benefits where channels_id = "' . $channel[0]->id . '" and status_id = 1 and discount = "NO"';
        $benefits = DB::select($benefitsQuery);
        $benefitPercentage = 0;

        $benefitsTable = '<table class="table table-bordered">
                            <thead>
                              <tr>
                                <th style="background-color:#b3b0b0">Beneficios</th>
                              </tr>
                            </thead>
                            <tbody>';

        foreach ($benefits as $benefit) {
            $benefitPercentage += $benefit->discount_percentage;
            $benefitsTable .= '<tr>
                                <td align="center">' . $benefit->name . '</td>
                              </tr>';
        }

        $benefitsTable .= '</tbody>
                          </table>';
        $benefitsQuery2 = 'select * from benefits where channels_id = "' . $channel[0]->id . '" and status_id = 1 and discount = "YES" ';
        $benefits2 = DB::select($benefitsQuery2);
        foreach ($benefits2 as $benefit) {
            $benefitPercentage += $benefit->discount_percentage;
        }
        //Array Response 0 Vehicles Table
        array_push($vehicltesTableArray, $data);
        array_push($arrayResponse, $vehicltesTableArray);

        //Calculate Other Discounts
        $otherDiscount = (($sub12Price * $benefitPercentage) / 100);

        $subTotal = (($sub12Price + $sub0Price) - $otherDiscount);

        //Calculate IVA
        $iva = (($tax[0]->percentage * $subTotal) / 100);

        //Calculate Total
        $total = $iva + $subTotal + $sub0Price;


        //Array Response 1 Tax Table
        array_push($arrayResponse, round($sub12Price, 2));
        array_push($arrayResponse, round($sub0Price, 2));
        array_push($arrayResponse, round($otherDiscount, 2));
        array_push($arrayResponse, round($iva, 2));
        array_push($arrayResponse, round($total, 2));
        array_push($arrayResponse, $benefitsTable);

        return $arrayResponse;
    }

    public function resumeNew(request $request) {
        //Variables 
        $value = $request['data']['total'];
        //Vehicle Table Resume
        $vehicleTable = '<tr align="center">
                            <td>' . $request['data']['name'] . '</td>
                            <td>$' . $value . '</td>
                        </tr>';
        $arrayResponse = array();
        array_push($arrayResponse, $vehicleTable);

        //Check Product Tax
        $productTax = \App\products::selectRaw('tax.percentage')
                ->join('products_tax', 'products_tax.products_id', '=', 'products.id')
                ->join('tax', 'tax.id', '=', 'products_tax.tax_id')
                ->where('products.id', '=', $request['data']['product'])
                ->get();
        if ($productTax->isEmpty()) {
            $percentage = 0;
        } else {
            $percentage = $productTax[0]->percentage;
        }

        //Tax Table Resume
        $superBancos = round((($value * 3.5) / 100), 2);
        $seguCampesino = round((($value * 0.5) / 100), 2);
        $deEmision = 0.50;
        $subTotal = round(($superBancos + $seguCampesino + $deEmision + $value), 2);
        $tax = round((($value * $percentage) / 100), 2);
        $total = $subTotal + $tax;

        $taxTable = '<tr align="center">
                        <th style="background-color:#b3b0b0;">S. de Bancos (3.5%)</th>
                        <td style="width:40%">$' . $superBancos . '</td>
                     </tr>
                     <tr align="center">
                        <th style="background-color:#b3b0b0;">S. Campesino (0.5%)</th>
                        <td style="width:40%">$' . $seguCampesino . '</td>
                     </tr>
                     <tr align="center">
                        <th style="background-color:#b3b0b0;">Derechos de Emisión</th>
                        <td style="width:40%">$' . $deEmision . '</td>
                     </tr>
                     <tr align="center">
                        <th style="background-color:#b3b0b0;">Subtotal</th>
                        <td style="width:40%">$' . $subTotal . '</td>
                     </tr>
                     <tr align="center">
                        <th style="background-color:#b3b0b0;">IVA</th>
                        <td style="width:40%">$' . $tax . '</td>
                     </tr>
                     <tr align="center">
                        <th style="background-color:#b3b0b0;">Total</th>
                        <td style="width:40%">$' . $total . '</td>
                     </tr>';

        array_push($arrayResponse, $taxTable);

        //Customer Variable
        $customer = \App\customers::find(session('salesCreateLifeCustomerId'));

        array_push($arrayResponse, $customer);
        return $arrayResponse;
    }
    
    public function resumeNewSS(request $request) {
//      return $request;
        $prima = 0;
        $contribucion = 0;
        $sCam = 0;
        $derEmision = 0;
        $subSinIva = 0;
        $subConIva = 0;
        $iva = 0;
        $total = 0;
        foreach ($request['data']['vehicles'] as $vehi) {
                $vehiValue = 0;
                $vehiValue += str_replace(',','',$vehi['vehiValue']);

                $accValue = 0;
                //Sum acc Values
                if (isset($request['data']['vehiAccData'])) {
                    foreach ($request['data']['vehiAccData'] as $ac) {
                        if ($ac['plate'] == $vehi['plate']) {
                            $accValue += $ac['value'];
                        }
                    }
                    $value = $vehiValue + $accValue;
                } else {
                    $value = $vehiValue;
                }
                //Obtain Prima Neta
                $result = calculoPrimaSS($request['data']['canalPlanId'], $request['data']['today'], $request['data']['oneYear'], $request['data']['agenciaSS'], $vehi['type'], $value);
                
                $prima += $result['rubrofactura']['primaneta'];
        }
        //Obtain total cost/price
        $costoSeguro = costoSeguroSS(7, $prima);
        foreach ($costoSeguro['rubrofactura']['rubros'] as $a) {
            if ($a['rubro'] == 'CONTRIBUCIÓN') {
                $contribucion += $a['valor'];
            }
            if ($a['rubro'] == 'S. SOCIAL CAMPESINO') {
                $sCam += $a['valor'];
            }
            if ($a['rubro'] == 'DERECHO DE EMISION') {
                $derEmision += $a['valor'];
            }
            if ($a['rubro'] == 'SUBTOTAL TARIFA IVA' && $a['simbolo'] == '0.00%') {
                $subSinIva += $a['valor'];
            }
            if ($a['rubro'] == 'SUBTOTAL TARIFA IVA' && $a['simbolo'] == '12.00%') {
                $subConIva += $a['valor'];
            }
            if ($a['rubro'] == 'I.V.A') {
                $iva += $a['valor'];
            }
            if ($a['rubro'] == 'TOTAL') {
                $total += $a['valor'];
            }
        }
        
        $prima = number_format((float)$prima, 2, '.', '');
        $contribucion = number_format((float)$contribucion, 2, '.', '');
        $sCam = number_format((float)$sCam, 2, '.', '');
        $derEmision = number_format((float)$derEmision, 2, '.', '');
        $subSinIva = number_format((float)$subSinIva, 2, '.', '');
        $subConIva = number_format((float)$subConIva, 2, '.', '');
        $iva = number_format((float)$iva, 2, '.', '');
        $total = number_format((float)$total, 2, '.', '');
//        $rate = number_format((float)$rate, 2, '.', '');
//        number_format((float)$number, 2, '.', '');
        $product = \App\products::find($request['data']['proId']);
        
        //Vehicle Table Resume
        $vehicleTable = '<tr align="center">
                            <td>' . $product->productodes . '</td>
                            <td style="text-align:right">$' . $prima . '</td>
                        </tr>';
        $arrayResponse = array();
        array_push($arrayResponse, $vehicleTable);

        $taxTable = '<tr align="center">
                        <th style="text-align:right;">S. de Compañias (3.5%)</th>
                        <td style="width:40%;text-align:right">$' . $contribucion . '</td>
                     </tr>
                     <input type="hidden" id="sCompania" name="sCompania" value="'.$contribucion.'">
                     <tr align="center">
                        <th style="text-align:right;">S. Campesino (0.5%)</th>
                        <td style="width:40%;text-align:right">$' . $sCam . '</td>
                     </tr>
                     <input type="hidden" id="sCampesino" name="sCampesino" value="'.$sCam.'">
                     <tr align="center">
                        <th style="text-align:right;">Derechos de Emisión</th>
                        <td style="width:40%;text-align:right">$' . $derEmision . '</td>
                     </tr>
                     <input type="hidden" id="dEmision" name="dEmision" value="'.$derEmision.'">
                     <tr align="center">
                        <th style="text-align:right;">Subtotal 12%</th>
                        <td style="width:40%;text-align:right">$' . $subConIva . '</td>
                     </tr>
                     <input type="hidden" id="subtotal12" name="subtotal12" value="'.$subConIva.'">
                     <tr align="center">
                        <th style="text-align:right;">Subtotal 0%</th>
                        <td style="width:40%;text-align:right">$' . $subSinIva . '</td>
                     </tr>
                     <input type="hidden" id="subtotal0" name="subtotal0" value="'.$subSinIva.'">
                     <tr align="center">
                        <th style="text-align:right;">IVA</th>
                        <td style="width:40%;text-align:right">$' . $iva . '</td>
                     </tr>
                     <input type="hidden" id="tax" name="tax" value="'.$iva.'">
                     <tr align="center">
                        <th style="background-color:#b3b0b0;text-align:right;">Total</th>
                        <td style="background-color:#b3b0b0;width:40%;text-align:right">$' . $total . '</td>
                     </tr>
                     <input type="hidden" id="total" name="total" value="'.$total.'">
                     <input type="hidden" id="rate" name="rate" value="'.$result['rubrofactura']['tasa'].'">';

        array_push($arrayResponse, $taxTable);

        //Customer Variable
        $customer = \App\customers::find(session('salesCreateLifeCustomerId'));

        array_push($arrayResponse, $customer);
        return $arrayResponse;
    }

    public function store(request $request) {
        set_time_limit(120);
//        Save or Update Customer
        $customerSql = 'select * from customers where document = "' . $request['data']['customer']['document'] . '"';
        $customer = DB::select($customerSql);

        //Validate Customer Save or Update
        if ($customer) {
            $customerUpdate = \App\customers::find($customer[0]->id);
//            $customerUpdate->first_name = $request['data']['customer']['firstName'];
//            $customerUpdate->last_name = $request['data']['customer']['lastName'];
//            $customerUpdate->document = $request['data']['customer']['document'];
//            $customerUpdate->document_id = $request['data']['customer']['documentId'];
            $customerUpdate->last_name = $request['data']['customer']['lastName'];
            $customerUpdate->second_last_name = $request['data']['customer']['secondLastName'];
            $customerUpdate->address = $request['data']['customer']['address'];
            $customerUpdate->city_id = $request['data']['customer']['city'];
            $customerUpdate->phone = $request['data']['customer']['phone'];
            $customerUpdate->mobile_phone = $request['data']['customer']['mobilePhone'];
            $customerUpdate->email = $request['data']['customer']['email'];
            $customerUpdate->birthdate = $request['data']['customer']['birthdate'];
            $customerUpdate->save();
            $customerId = $customerUpdate->id;
            $customerPhone = substr($customerUpdate->mobile_phone, 1);
            $customerEmail = $customerUpdate->email;
            $customerDocument = $customerUpdate->document;
            $customerSearch = \App\customers::find($customerUpdate->id);
        } else {
            $customerNew = new \App\customers();
            $customerNew->first_name = $request['data']['customer']['firstName'];
            $customerNew->second_name = $request['data']['customer']['secondName'];
            $customerNew->last_name = $request['data']['customer']['lastName'];
            $customerNew->second_last_name = $request['data']['customer']['secondLastName'];
            $customerNew->document = $request['data']['customer']['document'];
            $customerNew->document_id = $request['data']['customer']['documentId'];
            $customerNew->address = $request['data']['customer']['address'];
            $customerNew->city_id = $request['data']['customer']['city'];
            $customerNew->phone = $request['data']['customer']['phone'];
            $customerNew->mobile_phone = $request['data']['customer']['mobilePhone'];
            $customerNew->email = $request['data']['customer']['email'];
            $customerNew->birthdate = $request['data']['customer']['birthdate'];
            $customerNew->status_id = 1;
            $customerNew->save();
            $customerId = $customerNew->id;
            $customerPhone = substr($customerNew->mobile_phone, 1);
            $customerEmail = $customerNew->email;
            $customerDocument = $customerNew->document;
            $customerSearch = \App\customers::find($customerNew->id);
        }

        //Obtain Product Data
//        $productSql = 'select * from products_channel where product_id = ' . $request['data']['product'];
//        $productSql = 'select * from products_channel where product_id = ' . $request['data']['product'];
//        $productChannel = DB::select($productSql);
//       return $productChannel;
        //Random Code
//        $randomCode = rand(1000, 9999);
        
        $productChannel = \App\product_channel::where('canal_plan_id','=',$request['data']['product'])->get();
        $randomCode = '1234';

        //Validate if its Individual or Remote
        if (isset($request['data']['salesType'])) {
            $salesType = $request['data']['salesType'];
        } else {
            $salesType = 1;
        }

        //DateTime
        $now = new \DateTime();

        //Send Quotation Email Check Box
        if ($request['data']['sendQuotation'] == 'true') {
            $chkBoxSendQuotation = true;
        } else {
            $chkBoxSendQuotation = false;
        }

        //Store Sale
        $salesNew = new \App\sales();
        $salesNew->pbc_id = $productChannel[0]->id;
        $salesNew->user_id = \Auth::user()->id;
        $salesNew->customer_id = $customerId;
        $salesNew->status_id = 36;
        $salesNew->emission_date = $now;
        $salesNew->token_date_send = $now;
        $salesNew->subtotal_12 = $request['data']['subtotal12'];
        $salesNew->subtotal_0 = $request['data']['subtotal0'];
        $salesNew->other_discount = 0;
        $salesNew->seguro_campesino = $request['data']['sCampesino'];
        $salesNew->super_bancos = $request['data']['sCompania'];
        $salesNew->derecho_emision = $request['data']['dEmision'];
        $salesNew->tax = $request['data']['tax'];
        $salesNew->total = $request['data']['total'];
        $salesNew->token = $randomCode;
        $salesNew->sales_type_id = $salesType;
        $salesNew->agen_id = \Auth::user()->agen_id;
        $salesNew->cus_mobile_phone = $request['data']['customer']['mobilePhone'];
        $salesNew->cus_phone = $request['data']['customer']['phone'];
        $salesNew->cus_address = $request['data']['customer']['address'];
        $salesNew->cus_email = $request['data']['customer']['email'];
        $salesNew->cus_city = $request['data']['customer']['city'];
        $salesNew->sales_movements_id = $request['data']['saleMovement'];
        $salesNew->sales_id = $request['data']['saleId'];
        $salesNew->chkBoxSendQuotation = $chkBoxSendQuotation;
        $salesNew->save();
                
        //Consulta Lista Observados y Cartera Vencida SS
        \App\Jobs\listaObservadosyCarteraJobs::dispatch($request['data']['product'], $customerId, $salesNew->id, \Auth::user()->email);

        if (isset($request['data']['saleId'])) {
            $salesOld = \App\sales::find($request['data']['saleId']);
            $salesOld->has_been_renewed = 1;
            $salesOld->has_been_renewed_date = $now;
            $salesOld->save();
        }


        //Vinculation_form
        $vinculation = new \App\vinculation_form();
        $vinculation->customer_id = $customerId;
        $vinculation->sales_id = $salesNew->id;
        $vinculation->status_id = 6;
        $vinculation->main_road = $request['data']['customer']['address'];
        $vinculation->city_id = $request['data']['customer']['city'];
        $vinculation->phone = $request['data']['customer']['phone'];
        $vinculation->mobile_phone = $request['data']['customer']['mobilePhone'];
        $vinculation->email = $request['data']['customer']['email'];
        $vinculation->birth_date = $request['data']['customer']['birthdate'];
        $vinculation->save();

        

        //Store Charge
//        $charge = new \App\Charge();
//        $charge->sales_id = $salesNew->id;
//        $charge->customers_id = $customerId;
//        $charge->status_id = 8;
//        $charge->value = $salesNew->total;
//        $charge->types_id = 1;
//        $charge->motives_id = 1;
//        $charge->save();
        //Send SMS
        if ($salesNew->sales_type_id == 1) { // INDIVIDUAL SALE - SEND SMS
//        sendSMS($customerPhone, $randomCode, $salesNew->id);
        } else { // REMOTE SALE - SEND LINK
            sendLinkSMS($customerPhone, $randomCode, $salesNew->id);
        }
        //Store vehicles
        $vehicleCount = 1;
        $primaTotal = 0;
        $insuredValue = 0;
        foreach ($request['data']['vehicles'] as $vehicle) {

            //Obtain Brand
            $brandSql = 'select * from vehicles_brands where name = "' . $vehicle['brand'] . '"';
            $brand = DB::select($brandSql);

            //If vehicle Existe Update else Store
            $vehicleSql = 'select * from vehicles where plate = "' . $vehicle['plate'] . '"';
            $vehicleSearch = DB::select($vehicleSql);
            
            //New or User Vehicle
            if($vehicle['newVehicle'] == 'Nuevo'){ $newVehicle = 1; }else{ $newVehicle = 0; }
            
            $vehiTypeSearch = \App\vehicles_type::where('name','=',$request['vehiType'])->get();
            if($vehiTypeSearch->isEmpty()){
                $vehiType = '1';
            }else{
                $vehiType = $vehiTypeSearch[0]->id;
            }
            
            //Vehicle Class
            $result = vehicleSS($vehicle['plate']);
            $vehiClassSearch = \App\vehicle_class::where('name','like','%'.$vehicle['type'].'%')->get();
            if($vehiClassSearch->isEmpty()){
                $vehiClass = 1;
            }else{
                $vehiClass = $vehiClassSearch[0]->id;
            }
            $AgencySS = \App\agencia_ss::find($productChannel[0]->agency_ss_id);
            
            
            $today = date('d-m-Y');
            $oneYear = date('d-m-Y', strtotime('+1 years'));
            
            //Obtain Prima Neta
            $resultPrimaNeta = calculoPrimaSS($productChannel[0]->canal_plan_id, $today, $oneYear, $AgencySS->agenciaid, $vehicle['type'], str_replace(',','',$vehicle['vehiValue']));
            $rate = $request['data']['rate'];
            if ($vehicleSearch) {
                //Update Vehicule
                $vehicleUpdate = \App\vehicles::find($vehicleSearch[0]->id);
                $vehicleUpdate->plate = $vehicle['plate'];
                $vehicleUpdate->brand_id = $brand[0]->id;
                $vehicleUpdate->model = $vehicle['model'];
                $vehicleUpdate->year = $vehicle['year'];
                $vehicleUpdate->color = $result['datosvehiculo']['color'];
                $vehicleUpdate->new_vehicle = $newVehicle;
                $vehicleUpdate->chassis = $vehicle['chassis'];
                $vehicleUpdate->matricula = $vehicle['matricula'];
                $vehicleUpdate->ramv = $vehicle['ramv'];
                $vehicleUpdate->vehicles_type_id = $vehiType;
                $vehicleUpdate->vehicles_class_id = $vehiClass;
                $vehicleUpdate->aniofiscal = $result['datosvehiculo']['aniofiscal'];
                $vehicleUpdate->cantonmatriculacion = $result['datosvehiculo']['cantonmatriculacion'];
                $vehicleUpdate->capacidad = $result['datosvehiculo']['capacidad'];
                $vehicleUpdate->cargautil = $result['datosvehiculo']['cargautil'];
                $vehicleUpdate->cilindraje = $result['datosvehiculo']['cilindraje'];
                $vehicleUpdate->clase = $result['datosvehiculo']['clase'];
                $vehicleUpdate->codent = $result['datosvehiculo']['codent'];
                $vehicleUpdate->codigocan2 = $result['datosvehiculo']['codigocan2'];
                $vehicleUpdate->codigoeda = $result['datosvehiculo']['codigoeda'];
                $vehicleUpdate->codigosub = $result['datosvehiculo']['codigosub'];
                $vehicleUpdate->combustible = $result['datosvehiculo']['combustible'];
                $vehicleUpdate->estadoexoneracion = $result['datosvehiculo']['estadoexoneracion'];
                $vehicleUpdate->fchutil = $result['datosvehiculo']['fchutil'];
                $vehicleUpdate->fechacaducidad = $result['datosvehiculo']['fechacaducidad'];
                $vehicleUpdate->fechacompra = $result['datosvehiculo']['fechacompra'];
                $vehicleUpdate->marca = $result['datosvehiculo']['marca'];
                $vehicleUpdate->pais = $result['datosvehiculo']['pais'];
                $vehicleUpdate->precioventa = $result['datosvehiculo']['precioventa'];
                $vehicleUpdate->propietario = $result['datosvehiculo']['propietario'];
                $vehicleUpdate->propietarioide = $result['datosvehiculo']['propietarioide'];
                $vehicleUpdate->subclase = $result['datosvehiculo']['subclase'];
                $vehicleUpdate->tipo = $result['datosvehiculo']['tipo'];
                $vehicleUpdate->valoravaluo = $result['datosvehiculo']['valoravaluo'];
                $vehicleUpdate->valorimpuesto = $result['datosvehiculo']['valorimpuesto'];
                $vehicleUpdate->yearult = $result['datosvehiculo']['yearult'];
                $vehicleUpdate->motor = $result['datosvehiculo']['motor'];
                $vehicleUpdate->save();
                $vehiclePlate = $vehicleUpdate->plate;
                $vehicleId = $vehicleUpdate->id;
            } else {
                //Store Vehicle
                $vehiclesNew = new \App\vehicles();
                $vehiclesNew->plate = $vehicle['plate'];
                $vehiclesNew->ramv = $vehicle['ramv'];
                $vehiclesNew->brand_id = $brand[0]->id;
                $vehiclesNew->model = $vehicle['model'];
                $vehiclesNew->year = $vehicle['year'];
                $vehiclesNew->color = $result['datosvehiculo']['color'];
                $vehiclesNew->new_vehicle = $newVehicle;
                $vehiclesNew->chassis = $vehicle['chassis'];
                $vehiclesNew->matricula = $vehicle['matricula'];
                $vehiclesNew->status_vehicle_on_new_sale = $newVehicle;
                $vehiclesNew->vehicles_type_id = $vehiType;
                $vehiclesNew->vehicles_class_id = $vehiClass;
                $vehiclesNew->aniofiscal = $result['datosvehiculo']['aniofiscal'];
                $vehiclesNew->cantonmatriculacion = $result['datosvehiculo']['cantonmatriculacion'];
                $vehiclesNew->capacidad = $result['datosvehiculo']['capacidad'];
                $vehiclesNew->cargautil = $result['datosvehiculo']['cargautil'];
                $vehiclesNew->cilindraje = $result['datosvehiculo']['cilindraje'];
                $vehiclesNew->clase = $result['datosvehiculo']['clase'];
                $vehiclesNew->codent = $result['datosvehiculo']['codent'];
                $vehiclesNew->codigocan2 = $result['datosvehiculo']['codigocan2'];
                $vehiclesNew->codigoeda = $result['datosvehiculo']['codigoeda'];
                $vehiclesNew->codigosub = $result['datosvehiculo']['codigosub'];
                $vehiclesNew->combustible = $result['datosvehiculo']['combustible'];
                $vehiclesNew->estadoexoneracion = $result['datosvehiculo']['estadoexoneracion'];
                $vehiclesNew->fchutil = $result['datosvehiculo']['fchutil'];
                $vehiclesNew->fechacaducidad = $result['datosvehiculo']['fechacaducidad'];
                $vehiclesNew->fechacompra = $result['datosvehiculo']['fechacompra'];
                $vehiclesNew->marca = $result['datosvehiculo']['marca'];
                $vehiclesNew->pais = $result['datosvehiculo']['pais'];
                $vehiclesNew->precioventa = $result['datosvehiculo']['precioventa'];
                $vehiclesNew->propietario = $result['datosvehiculo']['propietario'];
                $vehiclesNew->propietarioide = $result['datosvehiculo']['propietarioide'];
                $vehiclesNew->subclase = $result['datosvehiculo']['subclase'];
                $vehiclesNew->tipo = $result['datosvehiculo']['tipo'];
                $vehiclesNew->valoravaluo = $result['datosvehiculo']['valoravaluo'];
                $vehiclesNew->valorimpuesto = $result['datosvehiculo']['valorimpuesto'];
                $vehiclesNew->yearult = $result['datosvehiculo']['yearult'];
                $vehiclesNew->save();
                $vehiclePlate = $vehiclesNew->plate;
                $vehicleId = $vehiclesNew->id;
            }

            //Store Vehicles Sales
            $vehiclesSalesNew = new \App\vehicles_sales();
            $vehiclesSalesNew->sales_id = $salesNew->id;
            $vehiclesSalesNew->vehicule_id = $vehicleId;
            $vehiclesSalesNew->status_id = 1;
            $vehiclesSalesNew->insured_value = str_replace(',','',$vehicle['vehiValue']);
            $vehiclesSalesNew->reference_value = $vehicle['vehiPrice'];
            $vehiclesSalesNew->rate = $resultPrimaNeta['rubrofactura']['tasa'];
            $vehiclesSalesNew->prima_value = $resultPrimaNeta['rubrofactura']['primaneta'];
            $vehiclesSalesNew->indprifija = $resultPrimaNeta['rubrofactura']['indprifija'];
            $vehiclesSalesNew->save();
            
            $primaTotal += $resultPrimaNeta['rubrofactura']['primaneta'];
            $insuredValue += str_replace(',','',$vehicle['vehiValue']);
           
            $accValue = 0;
            
            if(isset($request['data']['vehiAcc'])){
                foreach ($request['data']['vehiAcc'] as $acc) {
                    if($acc['plate'] == $vehiclePlate){
                        $accValue += str_replace(',','',$acc['value']); 
                        
                        $vehiAcc = new \App\vehiclesAccesories();
                        $vehiAcc->vehicles_sales_id = $vehiclesSalesNew->id;
                        $vehiAcc->name = $acc['description'];
                        $vehiAcc->value = str_replace(',','',$acc['value']);
                        $vehiAcc->rate = $request['data']['rate'];
                        $vehiAcc->save();
                    }
                }
                $vehiclesSalesUpdate = \App\vehicles_sales::find($vehiclesSalesNew->id);
                $vehiclesSalesUpdate->acc_value = $accValue;
                $vehiclesSalesUpdate->save();
            }

            //Obtain Channel
            $channelQuery = 'select cha.* 
                from channels  cha
                join agencies age on age.channel_id = cha.id
                where age.id = ' . \Auth::user()->agen_id;

            $channel = DB::select($channelQuery);

            //Obtain Extra Benefits
            $benefitsQuery = 'select * from benefits where channels_id = "' . $channel[0]->id . '" and status_id = 1 ';
            $benefits = DB::select($benefitsQuery);

            //DateTime
            $now = new \DateTime();

            //Beneits Array
            foreach ($benefits as $benefit) {
                //Store Benefits Vehicles Sales
                $benefitsNew = new \App\benefits_vehicles_sales();
                $benefitsNew->benefits_id = $benefit->id;
                $benefitsNew->vsal_id = $vehiclesSalesNew->id;
                $benefitsNew->date = $now;
                $benefitsNew->save();
            }


            //Obtain Vehicle 1 Price
            $product = \App\products::find($productChannel[0]->product_id);

            //Obtain Benefits Discount
            $benefitsQuery2 = 'select * from benefits where channels_id = "' . $channel[0]->id . '" and status_id = 1 and discount = "YES" ';
            $benefits2 = DB::select($benefitsQuery2);
            $discountPercentage = 0;
            foreach ($benefits2 as $bene2) {
                $discountPercentage += $bene2->discount_percentage;
            }
            //Obta
            //Store Vehicles Sales Prices
            $vehicleSalesPrices = new \App\vehicles_sales_prices();
            $vehicleSalesPrices->sales_id = $salesNew->id;
//            if ($vehicleCount == 1) {
            if (1 == 1) {
                $vehicleSalesPrices->price = round(($product['price'] - (($product['price'] * $discountPercentage) / 100)), 2);
            } else {
                //Obtain vehicle x price
                $discountSql = 'select distinct dis.* 
                                from discounts dis 
                                join products_discount pdis on pdis.discounts_id = discounts_id
                                where pdis.products_id = "' . $product['id'] . '" and dis.vechicles_count = "' . $vehicleCount . '"';
                $discount = DB::select($discountSql);
                $discountPrice = ($product['price'] - (($product['price'] * $discount[0]->percentage) / 100));
                $vehicleSalesPrices->price = round(($discountPrice - (($discountPrice * $discountPercentage) / 100)), 2);
            }
            $vehicleSalesPrices->status_id = 1;
            $vehicleSalesPrices->save();

            $vehicleCount++;
        }
        
        //Updata Prima Total
        $salesUpdate = \App\sales::find($salesNew->id);
        $salesUpdate->rate = $rate;
        $salesUpdate->prima_total = $primaTotal;
        $salesUpdate->insured_value = $insuredValue;
        $salesUpdate->save();
        
        if ($chkBoxSendQuotation == true) {
            $job = (new \App\Jobs\QuotationR1EmailJobs($salesNew->id, $customerEmail, $customerDocument));
            dispatch($job);
        }
        
//        listaObservadosYCarteraFunction($salesNew->id, $customerId, \Auth::user()->email, $request['data']['product']);

        $returnArray = array();
        $returnArray = [
            'productId' => $salesNew->id,
            'validationCode' => $salesNew->token,
            'salId' => $salesNew->id
        ];
        return $returnArray;
    }

    public function storeNew(request $request) {
//        return $request;
        //Obtain Product Data
        $productSql = 'select * from products_channel where product_id = ' . $request['data']['product'];
        $productChannel = DB::select($productSql);

        //Sale Prices Variables
        $sBancos = str_replace("$", "", $request['data']['pricesTable'][0]['sBancos']);
        $sCampes = str_replace("$", "", $request['data']['pricesTable'][0]['sCampes']);
        $dEmisio = str_replace("$", "", $request['data']['pricesTable'][0]['dEmisio']);
        $subTotal = str_replace("$", "", $request['data']['pricesTable'][0]['subTotal']);
        $tax = str_replace("$", "", $request['data']['pricesTable'][0]['tax']);
        $total = str_replace("$", "", $request['data']['pricesTable'][0]['total']);

        //DateTime
        $now = new \DateTime();

        //Send Quotation Email Check Box
        if ($request['data']['sendQuotation'] == 'true') {
            $chkBoxSendQuotation = true;
        } else {
            $chkBoxSendQuotation = false;
        }

        //Validate if its Individual or Remote
        if (isset($request['data']['salesType'])) {
            $salesType = $request['data']['salesType'];
        } else {
            $salesType = 1;
        }

        $randomCode = '1234';

        $customerId = session('salesCreateLifeCustomerId');
        $customer = \App\customers::find($customerId);
        $insuredId = session('salesCreateLifeInsuredId');

        //Store Sale
        $salesNew = new \App\sales();
        $salesNew->pbc_id = $productChannel[0]->id;
        $salesNew->user_id = \Auth::user()->id;
        $salesNew->customer_id = $customerId;
        $salesNew->status_id = 20;
        $salesNew->emission_date = $now;
        $salesNew->token_date_send = $now;
        $salesNew->subtotal_12 = $subTotal;
        $salesNew->subtotal_0 = 0;
        $salesNew->other_discount = 0;
        $salesNew->seguro_campesino = $sCampes;
        $salesNew->super_bancos = $sBancos;
        $salesNew->derecho_emision = $dEmisio;
        $salesNew->tax = $tax;
        $salesNew->total = $total;
        $salesNew->token = $randomCode;
        $salesNew->sales_type_id = $salesType;
        $salesNew->agen_id = \Auth::user()->agen_id;
        $salesNew->cus_mobile_phone = $customer->mobile_phone;
        $salesNew->cus_phone = $customer->phone;
        $salesNew->cus_address = $customer->address;
        $salesNew->cus_email = $customer->email;
        $salesNew->cus_city = $customer->city_id;
        $salesNew->sales_movements_id = $request['data']['saleMovement'];
        $salesNew->sales_id = $request['data']['saleId'];
        $salesNew->chkBoxSendQuotation = $chkBoxSendQuotation;
        $salesNew->insured_id = $insuredId;
        $salesNew->insurance_branch_id = $request['data']['insuranceBranch'];
        $salesNew->save();

        if (isset($request['data']['saleId'])) {
            $salesOld = \App\sales::find($request['data']['saleId']);
            $salesOld->has_been_renewed = 1;
            $salesOld->has_been_renewed_date = $now;
            $salesOld->save();
        }


        //Vinculation_form
        $vinculation = new \App\vinculation_form();
        $vinculation->customer_id = $customerId;
        $vinculation->sales_id = $salesNew->id;
        $vinculation->status_id = 6;
        $vinculation->save();

        if ($chkBoxSendQuotation == true) {
            $job = (new \App\Jobs\QuotationEmailJobs($salesNew->id, $customer->email, $customer->document));
            dispatch($job);
        }

        $returnArray = array();
        $returnArray = [
            'productId' => $salesNew->id,
            'validationCode' => $salesNew->token,
            'salId' => $salesNew->id
        ];
        return $returnArray;
    }

    public function cancel($id) {
        //Validate Cancel Permission
        $edit = checkExtraPermits('5', \Auth::user()->role_id);
        if (!$edit) {
            \Session::flash('ValidateUserRoute', 'No tiene acceso a cancelar ventas Individuales.');
            return view('home');
        }

        //Decrypt Data
        $data = Crypt::decrypt($id);

        $sql = 'select vehi.*, deta.id as "sche", brand.name as "brand"
                from vehicles vehi
                join vehicles_sales vsal on vsal.vehicule_id = vehi.id
                join vehicles_brands brand on brand.id = vehi.brand_id
                 LEFT JOIN scheduling sche ON sche.vehicles_sales_id = vsal.id
                left join scheduling_details deta on deta.scheduling_id = sche.id and deta.status_id = 17  
                where vsal.status_id = 1 and  vsal.sales_id = ' . $data;

        //Cancel Motive 
        $cancelMotives = \App\cancel_motives::all();


        $vehicles = DB::select($sql);

        return view('sales.cancel', [
            'vehicles' => $vehicles,
            'sale' => $data,
            'cancelMotives' => $cancelMotives
        ]);
    }

    public function annulment(request $request) {
        //Error Array
        $errorArray = array();

        //Response Array
        $responseArray = array();

        //Success Array
        $successArray = array();

        foreach ($request['data']['sales'] as $saleId) {
            //Validate if Sales has a cancellation
            $cancelSql = 'select vsal.* from sales sal
                            join vehicles_sales vsal on vsal.sales_id = sal.id
                            where sal.id = "' . $saleId . '" 
                            and sal.status_id = "3"
                            and vsal.status_id = 2';

            $cancel = DB::select($cancelSql);
            //Validate if Sales has a Scheduling
            $scheQuery = 'select sche.* 
                            from scheduling sche 
                            join scheduling_details deta on deta.scheduling_id = sche.id
                            join vehicles_sales vsal on vsal.id = sche.vehicles_sales_id
                            where vsal.sales_id = "' . $saleId . '" and deta.status_id = 17';
            $sche = DB::select($scheQuery);

            if ($cancel || $sche) {
                $saleError = [
                    'id' => $saleId
                ];
                array_push($errorArray, $saleId);
            } else {
                $saleSql = 'select sal.* from sales sal
                            join charges cha on cha.sales_id = sal.id
                            where sal.id = "' . $saleId . '" 
                            and sal.status_id = "3" and cha.status_id = 9
                            union
                            select sal.* from sales sal
                            join charges cha on cha.sales_id = sal.id
                            where sal.id = "' . $saleId . '"  
                            and cha.status_id = 9
                            and sal.emission_date BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW() and sal.status_id not in (5)';

                $sale = DB::select($saleSql);

                if ($sale) {
                    $saleUpdate = \App\sales::find($sale[0]->id);
                    $saleUpdate->status_id = 5;
                    $saleUpdate->save();

                    //Store Charge
                    $charge = new \App\Charge();
                    $charge->sales_id = $saleUpdate->id;
                    $charge->customers_id = $saleUpdate->customer_id;
                    $charge->status_id = 8;
                    $charge->value = $saleUpdate->total;
                    $charge->types_id = 2;
                    $charge->motives_id = 2;
                    $charge->save();

                    $saleSuccess = [
                        'id' => $saleId
                    ];

                    //Cancel Pending Scheduling
                    $scheSql = 'select deta.* 
                            from scheduling sche 
                            join scheduling_details deta on deta.scheduling_id = sche.id
                            join vehicles_sales vsal on vsal.id = sche.vehicles_sales_id
                            where vsal.sales_id = "' . $saleId . '" and deta.status_id not in (17)';
                    $Sche = DB::select($scheSql);

                    if (count($Sche) > 0) {
                        foreach ($Sche as $sche) {
                            $sdeta = \App\schedulingDetails::find($sche->id);
                            $sdeta->status_id = 4;
                            $sdeta->cancel_motive_id = 11;
                            $sdeta->cancel_user_id = \Auth::user()->id;
                            $sdeta->cancel_date = now();
                            $sdeta->save();
                        }
                    }
                    array_push($successArray, $saleSuccess);
                } else {
                    $saleError = [
                        'id' => $saleId
                    ];
                    array_push($errorArray, $saleId);
                }
            }
        }
        $responseArray = [
            'error' => $errorArray,
            'success' => $successArray
        ];
        return $responseArray;
    }

    public function renew(request $request) {
//        return $request;
        //Error Array
        $errorArray = array();

        //Response Array
        $responseArray = array();

        //Success Array
        $successArray = array();

        foreach ($request['data']['sales'] as $saleId) {
            //Validate if Sales can be renewed
            $renewSql = 'select * from sales 
                        where id = "' . $saleId . '"
                        and end_date BETWEEN DATE_SUB(NOW(), INTERVAL 5 DAY) AND NOW() 
                        union
                        select * from sales
                        where id = "' . $saleId . '"
                        and status_id =1';

            $renew = DB::select($renewSql);

            if (!$renew) {
                $saleError = [
                    'id' => $saleId
                ];
                array_push($errorArray, $saleId);
                \Session::flash('errorMessage', 'La(s) venta(s) no pudieron ser Renovada(s).');
            } else {
                //Random Code
                $randomCode = rand(100000, 999999);

                //RENEW PROCESS
                //OLD SALE DATA
                $oldSale = \App\sales::find($saleId);
                $oldSale->status_id = 7;
                $oldSale->save();

                //Calculate Date
                $date = $oldSale->end_date;
                if ($date) {
                    $newYear = date('Y-m-d h:i:s', strtotime($date . ' + 1 year'));
                } else {
                    $newYear = $date;
                }

                //NEW SALE
                $salesNew = new \App\sales();
                $salesNew->sales_id = $oldSale->id;
                $salesNew->pbc_id = $oldSale->pbc_id;
                $salesNew->user_id = \Auth::user()->id;
                $salesNew->customer_id = $oldSale->customer_id;
                $salesNew->status_id = 6;
                $salesNew->emission_date = now();
                $salesNew->subtotal_12 = $oldSale->subtotal_12;
                $salesNew->subtotal_0 = $oldSale->subtotal_0;
                $salesNew->other_discount = $oldSale->other_discount;
                $salesNew->tax = $oldSale->tax;
                $salesNew->total = $oldSale->total;
                $salesNew->begin_date = $date;
                $salesNew->end_date = $newYear;
                $salesNew->sales_type_id = 1;
                $salesNew->agen_id = $oldSale->agen_id;
                $salesNew->save();

                //Store Charge
                $charge = new \App\Charge();
                $charge->sales_id = $salesNew->id;
                $charge->customers_id = $oldSale->customer_id;
                $charge->status_id = 8;
                $charge->value = $salesNew->total;
                $charge->types_id = 1;
                $charge->motives_id = 1;
                $charge->save();

                //NEW VEHICLES SALES
                $vsalQuery = 'select * from vehicles_sales where sales_id = ' . $oldSale->id;

                $oldVSales = DB::select($vsalQuery);

                foreach ($oldVSales as $oldVSale) {
                    $newVSal = new \App\vehicles_sales();
                    $newVSal->vehicule_id = $oldVSale->vehicule_id;
                    $newVSal->sales_id = $salesNew->id;
                    $newVSal->status_id = 1;
                    $newVSal->save();
                }

                //NEW VEHICLES SALES PRICES
                $vsalPricesQuery = 'select * from vehicles_sales_prices where sales_id = ' . $oldSale->id;

                $oldVSalesPrices = DB::select($vsalPricesQuery);

                foreach ($oldVSalesPrices as $oldVSalPrice) {
                    $newVSalPrice = new \App\vehicles_sales_prices();
                    $newVSalPrice->price = $oldVSalPrice->price;
                    $newVSalPrice->sales_id = $salesNew->id;
                    $newVSalPrice->status_id = 1;
                    $newVSalPrice->save();
                }

                //Obtain Customer Phone
                $customerQuery = 'select cus.mobile_phone 
                                    from customers cus
                                    join sales sal on sal.customer_id = cus.id
                                    where sal.id = ' . $salesNew->id;

                $mobilePhone = DB::select($customerQuery);

                $customerPhone = substr($mobilePhone[0]->mobile_phone, 1);

                //SEND SMS
                sendSMS($customerPhone, $randomCode, $salesNew->id);
                \Session::flash('successMessage', 'La(s) venta(s) fueron Renovada(s) correctamente.');
            }
        }
        $responseArray = [
            'error' => $errorArray,
            'success' => $successArray
        ];
//        return redirect('sales')->with('cancelMessage', 'La(s) venta(s) fueron anulada(s) correctamente.');
        return $responseArray;
    }

    public function vehiclesCancel(request $request) {
        //Validate it has a Vehicle Selected
        if (!$request['vehicleId']) {
            return Redirect::back()->with('errorMessage', 'Debe Seleccionar un Vehículo');
        }

        //Vehicles id Responso Array
        $responseArray = Array();

        //Vehicles Array
        $vehiclesArray = Array();

        //Sales Array
        $salesArray = Array();

        //Obtain Sale Data
        $sale = \App\sales::find($request['saleId']);

        //Store Charge
        $charge = new \App\Charge();
        $charge->sales_id = $sale->id;
        $charge->customers_id = $sale->customer_id;
        $charge->status_id = 8;
        $charge->types_id = 2;
        $charge->motives_id = 3;
        $charge->save();

        //Vehicles Sales Query
        $cnValue = 0;
        foreach ($request['vehicleId'] as $vehicle) {

            //Determine Credit Note Value
            $vehiSalQuery = 'select * from vehicles_sales where sales_id = "' . $request['saleId'] . '" and vehicule_id = "' . $vehicle . '"';
            $vehiSale = DB::select($vehiSalQuery);

            $vehicule = \App\vehicles_sales::find($vehiSale[0]->id);
            $vehicule->status_id = 2;
            $vehicule->cancel_date = now();
            $vehicule->charges_id = $charge->id;
            $vehicule->cancel_motive_id = $request['cancelMotive'];
            $vehicule->save();

            $saleSuccess = [
                'id' => $vehicle
            ];

            array_push($vehiclesArray, $saleSuccess);

            //Refund Money Vehicules Sales Prices (Lowest Amount)
            $priceQuery = 'select * from 
                            vehicles_sales_prices where sales_id = "' . $request['saleId'] . '"
                            and price = (select min(price) from vehicles_sales_prices where sales_id = "' . $request['saleId'] . '" and status_id = 1)';
            $price = DB::select($priceQuery);
//            return $price;
            $priceUpdate = \App\vehicles_sales_prices::find($price[0]->id);
            $priceUpdate->status_id = 2;
            $priceUpdate->cancel_date = now();
            $priceUpdate->save();

            //Calculate Date            
            $now = Carbon::now();
            $end = Carbon::parse($sale->end_date);

            $diff_in_days = $now->diffInDays($end);
            $diff_in_days = $diff_in_days + 2;

            $daysResult = $diff_in_days / 365;

            $valueWithOutTax = round(($priceUpdate->price * $daysResult), 2);
//            echo '<pre>'.print_r($valueWithOutTax).'</pre>';die();
            $tax = (($valueWithOutTax * 12) / 100);
            $valueWithTax = $valueWithOutTax + $tax;

            $cnValue += round($valueWithTax, 2);

            //Validate ir it has a Pending scheduling
            $scheSql = 'select deta.id from scheduling_details deta
                        join scheduling sche on sche.id = deta.scheduling_id
                        where sche.vehicles_sales_id = ' . $vehiSale[0]->id . ' and deta.status_id = 3';
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

        $updateCharge = \App\Charge::find($charge->id);
        $updateCharge->value = $cnValue;
        $updateCharge->save();



        //Validate if Sales has Vehicles Active
        $saleQuery = 'select * from vehicles_sales where sales_id = "' . $request['saleId'] . '" and status_id = 1';

        $saleValidate = DB::select($saleQuery);

        if ($saleValidate) {
            $saleSuccess = [
                'id' => '0'
            ];

            array_push($salesArray, $saleSuccess);
        } else {
            $sale = \App\sales::find($request['saleId']);
            $sale->status_id = 4;
            $sale->save();

            $saleSuccess = [
                'id' => '1'
            ];

            array_push($salesArray, $saleSuccess);
        }
        //Response Array
        $responseArray = [
            'vehicles' => $vehiclesArray,
            'sales' => $salesArray
        ];

//        return $responseArray;
        return redirect('sales')->with('cancelMessage', 'El(Los) vehiculo(s) fue(ron) cancelado(s) correctamente');
    }

    public function pdf($id) {
        setlocale(LC_MONETARY, 'en_US');
        //Decrypt Data
        $data = Crypt::decrypt($id);
        
        $product = \App\sales::selectRaw('pro.ramoid')
                                        ->join('products_channel as pbc','pbc.id','=','sales.pbc_id')
                                        ->join('products as pro','pro.id','=','pbc.product_id')
                                        ->where('sales.id','=',$data)
                                        ->get();
//        dd($product[0]->ramoid);
        $sale = \App\sales::find($data);
        //Check if PDF is in BBDD with base64
        if($sale->pdf_quotation != null){
            $pdf = base64_decode($sale->pdf_quotation);
            file_put_contents(public_path('quotation.pdf'), $pdf);
      
            return response()->file(public_path('quotation.pdf'));
        }else{
            switch ($product[0]->ramoid) {
                case 7: // VEHICLES //R1
                    $customerQuery = 'select concat(cus.first_name," ",IFNULL(cus.second_name, "")," ",cus.last_name," ",cus.second_last_name) as "Cliente", concat(cus.first_name," ",IFNULL(cus.second_name, "")) as "names", concat(cus.last_name," ",cus.second_last_name) as "lastNames", pro.ramodes as "ramo", cus.document as "cusDocument", DATE_FORMAT(sal.emission_date, "%Y") as "year", sal.id as "id", pro.productodes as "proName", DATE_FORMAT(sal.emission_date, "%d/%m/%Y") as "date", cus.email as "email", cus.mobile_phone as "mobile", cus.phone as "phone", sal.agen_id as "agen_id", CONCAT(users.first_name," ",users.last_name) as "user", ch.name as "channel_name", doc.name as "typeDocDes", cus.birthdate as "cusBirthdate", pro.id as "idProduct", ci.name as "city"
                                            from customers cus
                                            join sales sal on sal.customer_id = cus.id
                                            join products_channel pbc on pbc.id = sal.pbc_id
                                            join products pro on pro.id = pbc.product_id
                                            join users on users. id = sal.user_id
                                            join documents doc on cus.document_id = doc.id
                                            join channels ch on ch.id = pbc.channel_id
                                            join  cities ci on ci.id = sal.cus_city
                                            where sal.id = "' . $data . '"';
                    $customer = DB::select($customerQuery);
                    $carQuery = 'SELECT  vh.plate as "plate", vhb.name as "brand", vh.model as "model", vh.year as "year", vhsal.reference_value as "refValue", vhsal.insured_value as "ins_value", vhsal.prima_value as "prima_value", vhsal.acc_value as "acc_value"
                                FROM vehicles_sales vhsal
                                join vehicles vh on vhsal.vehicule_id = vh.id
                                join vehicles_brands vhb on vhb.id = vh.brand_id
                                where vhsal.sales_id = "' . $data . '"';

                    $car = DB::select($carQuery);

                    //Obtain Coverages
                    $coverageQuery = 'SELECT cob.coberturades as "cobPrincipales", cob.valorasegurado as "valorAsegurado" , cob.tipocoberturades as "cobtipocoberturades"
                                            FROM coverage cob
                                            join products pro on pro.id = cob.product_id
                                            where pro.id =  ' . $customer[0]->idProduct . '
                                            and cob.tipocoberturades not in ("02. FORMA PAGO", "01. CARTA", "03. FIN CONDICIONES PARTICULARES", "07. FORMA PAGO", "08. FIN CONDICIONES PARTICULARES")
                                            GROUP BY cob.tipocoberturades
                                            ORDER BY cob.tipocoberturades';
                    $coverage = DB::select($coverageQuery);

                    $sales = \App\sales::find($data);
                    //Validate ID Length
                    if (strlen($customer[0]->id) == 3) {
                        $id = '00' . $customer[0]->id;
                    } else if (strlen($customer[0]->id) == 4) {
                        $id = '0' . $customer[0]->id;
                    } else {
                        $id = $customer[0]->id;
                    }

                    $returnBenefits = false;

                    $pdf = PDF::loadView('sales.R1.pdf_quotation', ['customer' => strtoupper($customer[0]->Cliente),
                                'id' => $id,
                                'benefits' => $returnBenefits,
                                'customer' => $customer,
                                'car' => $car,
                                'sales' => $sales,
                                'coverage' => $coverage
                    ]);
                    break;
                case 1: //VIDA
                    $customerQuery = 'select concat(cus.first_name," ",IFNULL(cus.second_name, "")," ",cus.last_name," ",cus.second_last_name) as "Cliente", concat(cus.first_name," ",IFNULL(cus.second_name, "")) as "names", concat(cus.last_name," ",cus.second_last_name) as "lastNames", pro.ramodes as "ramo", cus.document as "cusDocument", DATE_FORMAT(sal.emission_date, "%Y") as "year", sal.id as "id", pro.productodes as "proName", DATE_FORMAT(sal.emission_date, "%d/%m/%Y") as "date", cus.email as "email", cus.mobile_phone as "mobile", cus.phone as "phone", sal.agen_id as "agen_id", CONCAT(users.first_name," ",users.last_name) as "user", ch.name as "channel_name", doc.name as "typeDocDes", cus.birthdate as "cusBirthdate", pro.id as "idProduct", ci.name as "city"
                                            from customers cus
                                            join sales sal on sal.customer_id = cus.id
                                            join products_channel pbc on pbc.id = sal.pbc_id
                                            join products pro on pro.id = pbc.product_id
                                            join users on users. id = sal.user_id
                                            join documents doc on cus.document_id = doc.id
                                            join channels ch on ch.id = pbc.channel_id
                                            join  cities ci on ci.id = sal.cus_city
                                            where sal.id = "' . $data . '"';
                    $customer = DB::select($customerQuery);

                    $sales = \App\sales::find($data);

                    $productName = \App\sales::selectRaw('products.name, products.productodes, products.price, cus.document')
                            ->join('products_channel as pbc', 'pbc.id', 'sales.pbc_id')
                            ->join('products', 'products.id', '=', 'pbc.product_id')
                            ->join('customers as cus', 'cus.id', '=', 'sales.customer_id')
                            ->where('sales.id', '=', $data)
                            ->get();

                    $coverageQuery = 'SELECT cob.coberturades as "cobPrincipales", cob.valorasegurado as "valorAsegurado" , cob.tipocoberturades as "cobtipocoberturades", cob.product_id
                                            FROM coverage cob
                                            join products pro on pro.id = cob.product_id
                                            where pro.id =  ' . $customer[0]->idProduct . '
                                            and cob.tipocoberturades not in ("02. FORMA PAGO", "01. CARTA", "03. FIN CONDICIONES PARTICULARES", "07. FORMA PAGO", "08. FIN CONDICIONES PARTICULARES")
                                            GROUP BY cob.tipocoberturades
                                            ORDER BY cob.tipocoberturades';
                    $coverage = DB::select($coverageQuery);

                    $cov = getCoverageDetails($coverage[0]->cobtipocoberturades, $customer[0]->idProduct);
    //                return $cov;
                    //Validate ID Length
                    if (strlen($customer[0]->id) == 3) {
                        $id = '00' . $customer[0]->id;
                    } else if (strlen($customer[0]->id) == 4) {
                        $id = '0' . $customer[0]->id;
                    } else {
                        $id = $customer[0]->id;
                    }

                    $returnBenefits = false;

                    $pdf = PDF::loadView('sales.R2.pdf_quotation', ['customer' => strtoupper($customer[0]->Cliente),
                                'id' => $id,
                                'benefits' => $returnBenefits,
                                'customer' => $customer,
                                'sales' => $sales,
                                'coverage' => $coverage
                    ]);
                    break;
                case 2: //VIDA
                    $customerQuery = 'select concat(cus.first_name," ",IFNULL(cus.second_name, "")," ",cus.last_name," ",cus.second_last_name) as "Cliente", concat(cus.first_name," ",IFNULL(cus.second_name, "")) as "names", concat(cus.last_name," ",cus.second_last_name) as "lastNames", pro.ramodes as "ramo", cus.document as "cusDocument", DATE_FORMAT(sal.emission_date, "%Y") as "year", sal.id as "id", pro.productodes as "proName", DATE_FORMAT(sal.emission_date, "%d/%m/%Y") as "date", cus.email as "email", cus.mobile_phone as "mobile", cus.phone as "phone", sal.agen_id as "agen_id", CONCAT(users.first_name," ",users.last_name) as "user", ch.name as "channel_name", doc.name as "typeDocDes", cus.birthdate as "cusBirthdate", pro.id as "idProduct", ci.name as "city"
                                            from customers cus
                                            join sales sal on sal.customer_id = cus.id
                                            join products_channel pbc on pbc.id = sal.pbc_id
                                            join products pro on pro.id = pbc.product_id
                                            join users on users. id = sal.user_id
                                            join documents doc on cus.document_id = doc.id
                                            join channels ch on ch.id = pbc.channel_id
                                            join  cities ci on ci.id = sal.cus_city
                                            where sal.id = "' . $data . '"';
                    $customer = DB::select($customerQuery);

                    $sales = \App\sales::find($data);

                    $productName = \App\sales::selectRaw('products.name, products.productodes, products.price, cus.document')
                            ->join('products_channel as pbc', 'pbc.id', 'sales.pbc_id')
                            ->join('products', 'products.id', '=', 'pbc.product_id')
                            ->join('customers as cus', 'cus.id', '=', 'sales.customer_id')
                            ->where('sales.id', '=', $data)
                            ->get();

                    $coverageQuery = 'SELECT cob.coberturades as "cobPrincipales", cob.valorasegurado as "valorAsegurado" , cob.tipocoberturades as "cobtipocoberturades"
                                            FROM coverage cob
                                            join products pro on pro.id = cob.product_id
                                            where pro.id =  ' . $customer[0]->idProduct . '
                                            and cob.tipocoberturades not in ("02. FORMA PAGO", "01. CARTA", "03. FIN CONDICIONES PARTICULARES", "07. FORMA PAGO", "08. FIN CONDICIONES PARTICULARES")
                                            GROUP BY cob.tipocoberturades
                                            ORDER BY cob.tipocoberturades';
                    $coverage = DB::select($coverageQuery);
                    //Validate ID Length
                    if (strlen($customer[0]->id) == 3) {
                        $id = '00' . $customer[0]->id;
                    } else if (strlen($customer[0]->id) == 4) {
                        $id = '0' . $customer[0]->id;
                    } else {
                        $id = $customer[0]->id;
                    }

                    $returnBenefits = false;

                    $pdf = PDF::loadView('sales.R2.pdf_quotation', ['customer' => strtoupper($customer[0]->Cliente),
                                'id' => $id,
                                'benefits' => $returnBenefits,
                                'customer' => $customer,
                                'sales' => $sales,
                                //'coverage' => $coverage
                    ]);
                    break;
                case 4: //ACCIDENTES PERSONALES
                  $customerQuery = 'select concat(cus.first_name," ",IFNULL(cus.second_name, "")," ",cus.last_name," ",cus.second_last_name) as "Cliente", concat(cus.first_name," ",IFNULL(cus.second_name, "")) as "names", concat(cus.last_name," ",cus.second_last_name) as "lastNames", pro.ramodes as "ramo", cus.document as "cusDocument", DATE_FORMAT(sal.emission_date, "%Y") as "year", sal.id as "id", pro.productodes as "proName", DATE_FORMAT(sal.emission_date, "%d/%m/%Y") as "date", cus.email as "email", cus.mobile_phone as "mobile", cus.phone as "phone", sal.agen_id as "agen_id", CONCAT(users.first_name," ",users.last_name) as "user", ch.name as "channel_name", doc.name as "typeDocDes", cus.birthdate as "cusBirthdate", pro.id as "idProduct", ci.name as "city"
                                            from customers cus
                                            join sales sal on sal.customer_id = cus.id
                                            join products_channel pbc on pbc.id = sal.pbc_id
                                            join products pro on pro.id = pbc.product_id
                                            join users on users. id = sal.user_id
                                            join documents doc on cus.document_id = doc.id
                                            join channels ch on ch.id = pbc.channel_id
                                            join  cities ci on ci.id = sal.cus_city
                                            where sal.id = "' . $data . '"';
                    $customer = DB::select($customerQuery);

                    $sales = \App\sales::find($data);

                    $productName = \App\sales::selectRaw('products.name, products.productodes, products.price, cus.document')
                            ->join('products_channel as pbc', 'pbc.id', 'sales.pbc_id')
                            ->join('products', 'products.id', '=', 'pbc.product_id')
                            ->join('customers as cus', 'cus.id', '=', 'sales.customer_id')
                            ->where('sales.id', '=', $data)
                            ->get();

                    $coverageQuery = 'SELECT cob.coberturades as "cobPrincipales", cob.valorasegurado as "valorAsegurado" , cob.tipocoberturades as "cobtipocoberturades"
                                            FROM coverage cob
                                            join products pro on pro.id = cob.product_id
                                            where pro.id =  ' . $customer[0]->idProduct . '
                                            and cob.tipocoberturades not in ("02. FORMA PAGO", "01. CARTA", "03. FIN CONDICIONES PARTICULARES", "07. FORMA PAGO", "08. FIN CONDICIONES PARTICULARES")
                                            GROUP BY cob.tipocoberturades
                                            ORDER BY cob.tipocoberturades';
                    $coverage = DB::select($coverageQuery);
                    //Validate ID Length
                    if (strlen($customer[0]->id) == 3) {
                        $id = '00' . $customer[0]->id;
                    } else if (strlen($customer[0]->id) == 4) {
                        $id = '0' . $customer[0]->id;
                    } else {
                        $id = $customer[0]->id;
                    }

                    $returnBenefits = false;

                    $pdf = PDF::loadView('sales.R3.pdf_quotation', ['customer' => strtoupper($customer[0]->Cliente),
                                'id' => $id,
                                'benefits' => $returnBenefits,
                                'customer' => $customer,
                                'sales' => $sales,
                                'coverage' => $coverage
                    ]);
                    break;
                case 5: //INCENDIO
                    $customerQuery = 'select concat(cus.first_name," ",IFNULL(cus.second_name, "")," ",cus.last_name," ",cus.second_last_name) as "Cliente", concat(cus.first_name," ",IFNULL(cus.second_name, "")) as "names", concat(cus.last_name," ",cus.second_last_name) as "lastNames", pro.ramodes as "ramo", cus.document as "cusDocument", DATE_FORMAT(sal.emission_date, "%Y") as "year", sal.id as "id", pro.productodes as "proName", DATE_FORMAT(sal.emission_date, "%d/%m/%Y") as "date", cus.email as "email", cus.mobile_phone as "mobile", cus.phone as "phone", sal.agen_id as "agen_id", CONCAT(users.first_name," ",users.last_name) as "user", ch.name as "channel_name", doc.name as "typeDocDes", cus.birthdate as "cusBirthdate", pro.id as "idProduct", ci.name as "city"
                                            from customers cus
                                            join sales sal on sal.customer_id = cus.id
                                            join products_channel pbc on pbc.id = sal.pbc_id
                                            join products pro on pro.id = pbc.product_id
                                            join users on users. id = sal.user_id
                                            join documents doc on cus.document_id = doc.id
                                            join channels ch on ch.id = pbc.channel_id
                                            join  cities ci on ci.id = sal.cus_city
                                            where sal.id = "' . $data . '"';
                    $customer = DB::select($customerQuery);

                    $bienAsegurado = \App\properties::selectRaw(' DISTINCT(properties.id), properties.main_street, properties.secondary_street, properties.number, properties.office_department, cit.name, rub.description, proRub.value')
                                                        ->join('properties_rubros as proRub','proRub.property_id','=','properties.id')
                                                        ->join('cities as cit','cit.id','=','properties.city_id')
                                                        ->join('products_rubros as rub','rub.cod','=','proRuc.rubros_cod')
                                                        ->where('properties.sales_id','=',$data)
                                                        ->get();

                    $sales = \App\sales::find($data);

                    $coverageQuery = 'SELECT cob.coberturades as "cobPrincipales", cob.valorasegurado as "valorAsegurado" , cob.tipocoberturades as "cobtipocoberturades"
                                            FROM coverage cob
                                            join products pro on pro.id = cob.product_id
                                            where pro.id =  ' . $customer[0]->idProduct . '
                                            and cob.tipocoberturades not in ("02. FORMA PAGO", "01. CARTA", "03. FIN CONDICIONES PARTICULARES", "07. FORMA PAGO", "08. FIN CONDICIONES PARTICULARES")
                                            GROUP BY cob.tipocoberturades
                                            ORDER BY cob.tipocoberturades';
                    $coverage = DB::select($coverageQuery);

                    $productName = \App\sales::selectRaw('products.name, products.productodes, products.price, cus.document')
                            ->join('products_channel as pbc', 'pbc.id', 'sales.pbc_id')
                            ->join('products', 'products.id', '=', 'pbc.product_id')
                            ->join('customers as cus', 'cus.id', '=', 'sales.customer_id')
                            ->where('sales.id', '=', $data)
                            ->get();
                    //Validate ID Length
                    if (strlen($customer[0]->id) == 3) {
                        $id = '00' . $customer[0]->id;
                    } else if (strlen($customer[0]->id) == 4) {
                        $id = '0' . $customer[0]->id;
                    } else {
                        $id = $customer[0]->id;
                    }

                    $returnBenefits = false;

                    $pdf = PDF::loadView('sales.R4.pdf_quotation', ['customer' => $customer,
                                'id' => $id,
                                'proName' => $customer[0]->proName,
                                'benefits' => $returnBenefits,
                                'cusDocument' => $customer[0]->cusDocument,
                                'date' => $customer[0]->date,
                                'sales' => $sales, 
                                'bienAsegurado' => $bienAsegurado,
                                'coverage' => $coverage,
                                'year' => $customer[0]->year]);
                    break;
                case 40: //INCENDIO
                    $customerQuery = 'select concat(cus.first_name," ",IFNULL(cus.second_name, "")," ",cus.last_name," ",cus.second_last_name) as "Cliente", concat(cus.first_name," ",IFNULL(cus.second_name, "")) as "names", concat(cus.last_name," ",cus.second_last_name) as "lastNames", pro.ramodes as "ramo", cus.document as "cusDocument", DATE_FORMAT(sal.emission_date, "%Y") as "year", sal.id as "id", pro.productodes as "proName", DATE_FORMAT(sal.emission_date, "%d/%m/%Y") as "date", cus.email as "email", cus.mobile_phone as "mobile", cus.phone as "phone", sal.agen_id as "agen_id", CONCAT(users.first_name," ",users.last_name) as "user", ch.name as "channel_name", doc.name as "typeDocDes", cus.birthdate as "cusBirthdate", pro.id as "idProduct", ci.name as "city"
                                            from customers cus
                                            join sales sal on sal.customer_id = cus.id
                                            join products_channel pbc on pbc.id = sal.pbc_id
                                            join products pro on pro.id = pbc.product_id
                                            join users on users. id = sal.user_id
                                            join documents doc on cus.document_id = doc.id
                                            join channels ch on ch.id = pbc.channel_id
                                            join  cities ci on ci.id = sal.cus_city
                                            where sal.id = "' . $data . '"';
                    $customer = DB::select($customerQuery);

                    $bienAsegurado = \App\properties::selectRaw(' DISTINCT(properties.id), properties.main_street, properties.secondary_street, properties.number, properties.office_department, cit.name, rub.description, proRub.value')
                                                        ->join('properties_rubros as proRub','proRub.property_id','=','properties.id')
                                                        ->join('cities as cit','cit.id','=','properties.city_id')
                                                        ->join('products_rubros as rub','rub.cod','=','proRub.rubros_cod')
                                                        ->where('properties.sales_id','=',$data)
                                                        ->get();
    //                dd($bienAsegurado);

                    $sales = \App\sales::find($data);

                    $coverageQuery = 'SELECT cob.coberturades as "cobPrincipales", cob.valorasegurado as "valorAsegurado" , cob.tipocoberturades as "cobtipocoberturades"
                                            FROM coverage cob
                                            join products pro on pro.id = cob.product_id
                                            where pro.id =  ' . $customer[0]->idProduct . '
                                            and cob.tipocoberturades not in ("02. FORMA PAGO", "01. CARTA", "03. FIN CONDICIONES PARTICULARES", "07. FORMA PAGO", "08. FIN CONDICIONES PARTICULARES")
                                            GROUP BY cob.tipocoberturades
                                            ORDER BY cob.tipocoberturades';
                    $coverage = DB::select($coverageQuery);

                    $productName = \App\sales::selectRaw('products.name, products.productodes, products.price, cus.document')
                            ->join('products_channel as pbc', 'pbc.id', 'sales.pbc_id')
                            ->join('products', 'products.id', '=', 'pbc.product_id')
                            ->join('customers as cus', 'cus.id', '=', 'sales.customer_id')
                            ->where('sales.id', '=', $data)
                            ->get();
                    //Validate ID Length
                    if (strlen($customer[0]->id) == 3) {
                        $id = '00' . $customer[0]->id;
                    } else if (strlen($customer[0]->id) == 4) {
                        $id = '0' . $customer[0]->id;
                    } else {
                        $id = $customer[0]->id;
                    }

                    $returnBenefits = false;


                    $pdf = PDF::loadView('sales.R4.pdf_quotation', ['customer' => $customer,
                                'id' => $id,
                                'proName' => $customer[0]->proName,
                                'benefits' => $returnBenefits,
                                'cusDocument' => $customer[0]->cusDocument,
                                'date' => $customer[0]->date,
                                'sales' => $sales,
                                'bienAsegurado' => $bienAsegurado,
                                'coverage' => $coverage,
                                'year' => $customer[0]->year]);
                    break;
                default:
    //                code to be executed if n is different from all labels;
            }

            return $pdf->stream('magnus.pdf');
        
            
        }
    }

    public function salesActivate(request $request) {
//        return $request;
        //Obtain Sale Verfication Code
        $sale = \App\sales::find($request['salId']);

        if ($sale->token == $request['code']) {
            //Update Sale
            $sale->status_id = 3;
            $sale->save();

            $returnData = '<div class="alert alert-success">
                            <center>
                               Ha validado Correctamente la venta.
                            </center>
                           </div>';
            $success = 'true';
        } else {
            $returnData = '<div class="alert alert-danger">
                            <center>
                               El codigo ingresado no coincide con nuestros registros.
                            </center>
                           </div>';
            $success = 'false';
        }

//        $returnData = '<input type="hidden" id="code" name="code" value="'.$sale->token.'">';

        $responseArray = [
            'data' => $returnData,
            'success' => $success,
            'id' => $request['salId']
        ];

        return $responseArray;
    }

    public function salesResendCode(request $request) {
        //Obtain Sale Data
        $sale = \App\sales::find($request['salId']);

        //Obtain Customer Phone
        $customerQuery = 'select cus.mobile_phone 
                            from customers cus
                            join sales sal on sal.customer_id = cus.id
                            where sal.id = ' . $sale->id;

        $mobilePhone = DB::select($customerQuery);

        $customerPhone = substr($mobilePhone[0]->mobile_phone, 1);

        //Random Code
        $randomCode = rand(1000, 999999);

        //Send SMS
        $smsReponse = sendSMS($customerPhone, $randomCode, $sale->id);

        $sale->token = $randomCode;
        $sale->status_id = 3;
        $sale->save();

        if ($smsReponse == 'success') {
            $returnMessage = '<div class="alert alert-success">
                            <center>
                               Se envio un nuevo codigo al tlf ' . $customerPhone . '
                            </center>
                        </div>';
        } else {
            $returnMessage = '<div class="alert alert-danger">
                                <center>
                                    No se pudo enviar el SMS, por favor intente nuevamente.
                                </center>
                              </div>';
        }

        return $returnMessage;
    }

    public function delete(request $request) {
        //Change Sale Status
        $sale = \App\sales::find($request['id']);
        $sale->status_id = 11;
        $sale->save();

        \Session::flash('deleteMessage', 'La venta fue borrada correctamente');

        return $response = 'true';
    }

    public function validateDocument(request $request) {
//        return $request['data']['document'];
        if (!validateId($request['data']['document'])) {
            return 'invalid';
        } else {
            return 'valid';
        }
    }

    public function uploadSales(request $request) {
        return $request;
    }

    public function emit($saleId, $customerId, $form) {
        try {
            $sale = decrypt($saleId);
        } catch (DecryptException $e) {
            \Session::flash('ValidateUserRoute', 'Ha ocurrido un error.');
            return view('home');
        } try {
            $customer = decrypt($customerId);
        } catch (DecryptException $e) {
            \Session::flash('ValidateUserRoute', 'Ha ocurrido un error.');
            return view('home');
        }

        $sales = \App\sales::find($sale);
        
        //PENDIENTE EMISION  - EMITIDA
        if($sales->status_id == 26 || $sales->status_id == 21 || $sales->status_id == 9 || $sales->status_id == 32){
           
            \Session::flash('successMessage', 'La venta ya se se encuentra pagada.');
            return back()->withInput();
        }
        
        if($sales->status_id == 31){
            \Session::flash('errorMessage', ' El proceso de venta no puede continuar, para información adicional por favor contactar a tu ejecutivo comercial.');
            
            return back()->withInput();
        }

        $productName = \App\sales::selectRaw('products.name, products.price, cus.document, products.productodes, products.ramoid')
                ->join('products_channel as pbc', 'pbc.id', 'sales.pbc_id')
                ->join('products', 'products.id', '=', 'pbc.product_id')
                ->join('customers as cus', 'cus.id', '=', 'sales.customer_id')
                ->where('sales.id', '=', $sale)
                ->get();

        $vehiTable = '<tr align="center">
                        <td colspan="2">' . $productName[0]->productodes . '</td>
                        <td style="text-align:right;">$' . $sales->prima_total . '</td>
                    </tr>';
        $taxTable = '<tr style="text-align:center">
                    	<th style="width:50%; background-color: white; border: 0;"></th>
                    	<th style="width:50%; background-color:#b3b0b0;; text-align:right">S. de Bancos (3.5%)</th>
                        <td style="text-align:right;">$' . $sales->super_bancos . '</td>
                    </tr>
                    <tr style="text-align:center">
                    	<th style="width:50%;background-color: white;border: 0;"></th>
                        <th style="width:50%; background-color:#b3b0b0; text-align:right">S. Campesino (0.5%)</th>
                        <td style="text-align:right;">$' . $sales->seguro_campesino . '</td>
                    </tr>
                    <tr style="text-align:center">
                    	<th style="width:50%;background-color: white;border: 0;"></th>
                        <th style="width:50%; background-color:#b3b0b0; text-align:right">D. de Emisión</th>
                        <td style="text-align:right;">$' . $sales->derecho_emision . '</td>
                    </tr>
                    <tr style="text-align:center">
                    	<th style="width:50%;background-color: white;border: 0;"></th>
                        <th style="width:50%; background-color:#b3b0b0; text-align:right">Subtotal 12%</th>
                        <td style="text-align:right;">$' . $sales->subtotal_12 . '</td>
                    </tr>
                    <tr style="text-align:center">
                    	<th style="width:50%;background-color: white;border: 0;"></th>    
                        <th style="width:50%; background-color:#b3b0b0; text-align:right">Subtotal 0%</th>
                        <td style="text-align:right;">$' . $sales->subtotal_0 . '</td>
                    </tr>
                    <tr style="text-align:center">
                    	<th style="width:50%;background-color: white;border: 0;"></th>   
                        <th style="background-color:#b3b0b0; text-align:right">Iva</th>
                        <td style="text-align:right;">$' . $sales->tax . '</td>
                    </tr>
                    <tr style="text-align:center">
                    	<th style="width:50%;background-color: white;border: 0;"></th>    
                        <th style="width:50%; background-color:#b3b0b0; text-align:right">Total</th>
                        <td style="text-align:right;">$' . $sales->total . '</td>
                    </tr>';

        //Tax Table Resume
        $superBancos = $sales->super_bancos;
        $seguCampesino = $sales->seguro_campesino;
        $deEmision = $sales->derecho_emision;
        $subTotal = $sales->subtotal_12;
        $tax = $sales->tax;
        $total = $sales->total;

//        $taxTable = '<tr align="center">
//                        <td>$' . $superBancos . '</td>
//                        <td>$' . $seguCampesino . '</td>
//                        <td>$' . $deEmision . '</td>
//                        <td>$' . $subTotal . '</td>
//                        <td>$' . $tax . '</td>
//                        <td>$' . $total . '</td>
//                    </tr>';

        $document = encrypt($productName[0]->document);
        $saleId = encrypt($sale);
        if (in_array($productName[0]->ramoid, array(7, 5, 40))) { // VEHICULOS E INCENDIO
            $insuranceBranch = 1;
        } else {
            $insuranceBranch = 2; // VIDA Y AP
        }

        if ($sales->status_id == 12) {
            $check = 'checked="checked"';
        } else {
            $check = null;
        }

        if ($form == 0) {
            return view('sales.emit', [
                'taxTable' => $taxTable,
                'vehiTable' => $vehiTable,
                'document' => $document,
                'saleId' => $saleId,
                'customer' => $customer,
                'check' => $check,
                'insuranceBranch' => $insuranceBranch
            ]);
        } else {
            return view('sales.emitForm', [
                'taxTable' => $taxTable,
                'vehiTable' => $vehiTable,
                'document' => $document,
                'saleId' => $saleId,
                'customer' => $customer,
                'check' => 'checked="checked"',
                'insuranceBranch' => $insuranceBranch
            ]);
        }
    }

    public function emitR1Upload(request $request) {
//        return $request;
        $saleId = decrypt($request['saleId']);
        
        if($request['insuranceBranch'] == 1){
            $validation = Validator::make($request->all(), [
                        'select_file'.$request['type'] => 'required|mimes:pdf,jpg,jpeg,png|max:10240'
            ]);

            if ($validation->passes()) {
                //Vehicle Folder
                $vehiFolder = public_path('images/sales/' . $saleId . '/');
                //Create Vehicle Folder
                if (!file_exists($vehiFolder)) {
                    mkdir($vehiFolder, 0777, true);
                }

                $image = $request->file('select_file'.$request['type']);
                $new_name = rand() . '_'.$request['type'].'.' . $image->getClientOriginalExtension();
                
                $name = 'Images/Emit/'.$saleId.'/'.$new_name;
                $path = Storage::disk('s3')->put($name, file_get_contents($image));

                $url = Storage::disk('s3')->url($name);
                
                if($request['type'] == 'Factura'){
                    $sales = \App\sales::find($saleId);
                    $sales->picture_factura = $url;
                    $sales->save();
                    $picture = $sales->picture_factura;
                }
                if($request['type'] == 'Carta'){
                    $sales = \App\sales::find($saleId);
                    $sales->picture_carta = $url;
                    $sales->save();
                    $picture = $sales->picture_carta;
                }

//                $vinculationSearch = \App\vinculation_form::where('sales_id', '=', $saleId)->get();
//                $vinculation = \App\vinculation_form::find($vinculationSearch[0]->id);
//                $vinculation->picture_matricula = getAppRoute() . '/images/sales/r1/' . $saleId . '/' . $new_name;
//                $vinculation->save();
//                $picture = $vinculation->picture_matricula;


                return response()->json([
                            'message' => 'Image Upload Successfully',
                            'uploaded_image' => '<a href="' . $url . '" target="_blank"><img src="/images/pdf.png" class="img-thumbnail" width="50" /></a>',
                            'class_name' => 'alert-success',
                            'Success' => 'true'
                ]);
            } else {
                return response()->json([
                            'message' => 'Debe subir la imagen en un formato valido',
                            'uploaded_image' => '',
                            'class_name' => 'alert-danger',
                            'vSalId' => $request->vSalId,
                            'Success' => 'false'
                ]);
            }
        }
        if($request['insuranceBranch'] == 2){
            $validation = Validator::make($request->all(), [
                        'select_file' => 'required|image|mimes:jpeg,png,jpg,pdf|max:10048'
            ]);

            if ($validation->passes()) {
                //Vehicle Folder
                $vehiFolder = public_path('images/sales/' . $saleId . '/');
                //Create Vehicle Folder
                if (!file_exists($vehiFolder)) {
                    mkdir($vehiFolder, 0777, true);
                }

                $image = $request->file('select_file');
                $new_name = rand() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images/sales/' . $saleId), $new_name);

                $vinculationSearch = \App\vinculation_form::where('sales_id', '=', $saleId)->get();
                $vinculation = \App\vinculation_form::find($vinculationSearch[0]->id);
                $vinculation->picture_matricula = getAppRoute() . '/images/sales/' . $saleId . '/' . $new_name;
                $vinculation->save();
                $picture = $vinculation->picture_matricula;


                return response()->json([
                            'message' => 'Image Upload Successfully',
                            'uploaded_image' => '<a href="' . $picture . '" target="_blank"><img src="' . $picture . '" class="img-thumbnail" width="300" /></a>',
                            'class_name' => 'alert-success',
                            'Success' => 'true'
                ]);
            } else {
                return response()->json([
                            'message' => 'Debe subir la imagen en un formato valido',
                            'uploaded_image' => '',
                            'class_name' => 'alert-danger',
                            'vSalId' => $request->vSalId,
                            'Success' => 'false'
                ]);
            }
            
        }

    }
    
    function validateEmitR1(request $request){
        //Return Variables
        $returnData = '';
        
        //Obtain Vehicles
        $vehi = \App\vehicles::selectRaw('vehicles.*')
                                ->join('vehicles_sales as vsal','vsal.vehicule_id','=','vehicles.id')
                                ->where('vsal.sales_id','=', decrypt($request['saleId'])    )
                                ->get();
        
        //Validate Vehicles
        foreach($vehi as $v){
            $validate = false;
            if($v->chassis == null){ $validate = true; }
            if($v->matricula == null){ $validate = true; }
            if($v->year == null){ $validate = true; }
            if($v->color == null){ $validate = true; }
            if($v->capacidad == null){ $validate = true; }
            if($v->tonelaje == null){ $validate = true; }
            if($v->cilindraje == null){ $validate = true; }
            if($v->pais == null){ $validate = true; }
            if($v->disp_seguridad == null){ $validate = true; }
            if($validate == true){
                $returnData .= 'Debe validar los datos del vehiculo '.$v->plate;
            }
        }
        
        if($returnData == ''){
            return 'success';
        }else{
            return $returnData;
        }
    }
    
    public function emitR1($saleId, $insuranceBranch){
        //Customer Document
        $customer = \App\customers::selectRaw('customers.document')
                        ->join('sales', 'sales.customer_id', '=', 'customers.id')
                        ->where('sales.id', '=', decrypt($saleId))->get();
        $document = encrypt($customer[0]->document);
        
        $pro = \App\products::selectRaw('products.ramoid')
                                        ->join('products_channel as pbc','pbc.product_id','=','products.id')
                                        ->join('sales as sal','sal.pbc_id','=','pbc.id')
                                        ->where('sal.id','=',decrypt($saleId))
                                        ->get();
        
            //Dates
            $sales = \App\sales::find(decrypt($saleId));
            $customerData = \App\customers::find($sales->customer_id); 
            
            
            $newVehicle = 'false';
            
            //See if Vehi are new
            $vehi = \App\vehicles::selectRaw('vehicles.new_vehicle')->join('vehicles_sales','vehicles_sales.vehicule_id','=','vehicles.id')->where('vehicles_sales.sales_id','=',decrypt($saleId))->get();
            foreach($vehi as $v){
                if($v->new_vehicle == 1){
                    $newVehicle = 'true';
                }
            }
            
            $inspection = \App\inspection::where('sales_id','=',$sales->id)->get();
            
            $nreVehicle = 'true';
            //Dates
            if($newVehicle == 'false'){
                $beginDate = Carbon::createFromFormat('Y-m-d', $inspection[0]->date_status)->toDateString();
                $dt = Carbon::create($beginDate);
                $endDateFull = $dt->addYear();
                $endDate = $endDateFull->format('Y-m-d');
                $disabled = 'disabled="disabled"';
            }else{
                $beginDate = null;
                $endDate = null;
                $disabled = '';
            }

            return view('sales.R1.emit', [
                'saleId' => $saleId,
                'document' => $document,
                'beginDate' => $beginDate,
                'endDate' => $endDate,
                'customer' => $customerData,
                'disabled' => $disabled,
                'insuranceBranch' => $insuranceBranch,
                'insuredValue' => $sales->insured_value,
                'newVehicle' => $newVehicle
            ]);
    }

    public function emitBranch($saleId, $insuranceBranch) {
        //Customer Document
        $customer = \App\customers::selectRaw('customers.document')
                        ->join('sales', 'sales.customer_id', '=', 'customers.id')
                        ->where('sales.id', '=', decrypt($saleId))->get();
        $document = encrypt($customer[0]->document);
        
        $pro = \App\products::selectRaw('products.ramoid')
                                        ->join('products_channel as pbc','pbc.product_id','=','products.id')
                                        ->join('sales as sal','sal.pbc_id','=','pbc.id')
                                        ->where('sal.id','=',decrypt($saleId))
                                        ->get();

        //Validate Branch
        if ($pro[0]->ramoid == 7) {  //VEHICLES
            //Dates
            $sales = \App\sales::find(decrypt($saleId));
            $customerData = \App\customers::find($sales->customer_id); 
            
            
            $newVehicle = 'false';
            
            //See if Vehi are new
            $vehi = \App\vehicles::selectRaw('vehicles.new_vehicle')->join('vehicles_sales','vehicles_sales.vehicule_id','=','vehicles.id')->where('vehicles_sales.sales_id','=',decrypt($saleId))->get();
            foreach($vehi as $v){
                if($v->new_vehicle == 1){
                    $newVehicle = 'true';
                }
            }
            
            $inspection = \App\inspection::where('sales_id','=',$sales->id)->get();
            
//            $nreVehicle = 'true';
            //Dates
            if($newVehicle == 'false'){
                $beginDate = Carbon::createFromFormat('Y-m-d', $inspection[0]->date_status)->toDateString();
                $dt = Carbon::create($beginDate);
                $endDateFull = $dt->addYear();
                $endDate = $endDateFull->format('Y-m-d');
                $disabled = 'disabled="disabled"';
                $vehiForm = '';
            }else{
                $beginDate = null;
                $endDate = null;
                $disabled = '';
                $vehiController = new VehiclesController();
                $vehiForm  = $vehiController->vehiTable(decrypt($saleId));
            }
            
            if($newVehicle == 'true'){
                return view('sales.R1.emitVehicleNew', [
                    'saleId' => $saleId,
                    'document' => $document,
                    'beginDate' => $beginDate,
                    'endDate' => $endDate,
                    'customer' => $customerData,
                    'disabled' => $disabled,
                    'insuranceBranch' => $insuranceBranch,
                    'insuredValue' => $sales->insured_value,
                    'newVehicle' => $newVehicle,
                    'vehiForm' => $vehiForm,
                    'minDate' => date('Y-m-d', strtotime("-30 days")),
                    'maxDate' => date('Y-m-d', strtotime("+30 days"))
                ]);
            }else{
                return view('sales.R1.emit', [
                    'saleId' => $saleId,
                    'document' => $document,
                    'beginDate' => $beginDate,
                    'endDate' => $endDate,
                    'customer' => $customerData,
                    'disabled' => $disabled,
                    'insuranceBranch' => $insuranceBranch,
                    'insuredValue' => $sales->insured_value,
                    'newVehicle' => $newVehicle,
                    'vehiForm' => $vehiForm
                ]);
            }
            
            
        }
        if ($pro[0]->ramoid == 1 || $pro[0]->ramoid == 2 || $pro[0]->ramoid == 4) { // LIFE AND AP
            $documents = \App\document::all();
            //Beneficiaries
            $beneficiaries = \App\beneficiary::selectRaw('beneficiary.*')
                    ->where('beneficiary.sales_id', '=', decrypt($saleId))
                    ->get();
            if (!($beneficiaries->isEmpty())) {
                $tableData = '';
                foreach ($beneficiaries as $bene) {
                    $relationship = \App\benefitiary_relationship::find($bene->relationship_id);
                    $tableData .= '<tr>';
                    $tableData .= '<td>' . $bene->document . '</td>';
                    $tableData .= '<td>' . $bene->first_name . '</td>';
                    $tableData .= '<td>' . $bene->second_name . '</td>';
                    $tableData .= '<td>' . $bene->last_name . '</td>';
                    $tableData .= '<td>' . $bene->second_last_name . '</td>';
                    $tableData .= '<td>' . $bene->porcentage . '</td>';
                    $tableData .= '<td>' . $relationship->name . '</td>';
                    $tableData .= '</tr>';
                }
            } else {
                $tableData = null;
            }
            $sales = \App\sales::find(decrypt($saleId));
            //Now Date
            $now = date("Y-m-d", strtotime($sales->begin_date));  
            $next = date("Y-m-d", strtotime($sales->end_date));  

            return view('sales.R2.emit', [
                'saleId' => $saleId,
                'document' => encrypt($customer[0]->document),
                'documentForm' => $document,
                'documents' => $documents,
                'tableData' => $tableData,
                'insuranceBranch' => $insuranceBranch,
                'now' => $now,
                'next' => $next
            ]);
        }
        if ($pro[0]->ramoid == 5 || $pro[0]->ramoid == 40) { // FIRE
            //Dates
            $sales = \App\sales::find(decrypt($saleId));
            $customerData = \App\customers::find($sales->customer_id); 
            
            
            $newVehicle = 'r4';
            
            //See if Vehi are new
//            $vehi = \App\vehicles::selectRaw('vehicles.new_vehicle')->join('vehicles_sales','vehicles_sales.vehicule_id','=','vehicles.id')->where('vehicles_sales.sales_id','=',decrypt($saleId))->get();
//            foreach($vehi as $v){
//                if($v->new_vehicle == 1){
//                    $newVehicle = 'true';
//                }
//            }
            
            //Dates
//            if($newVehicle == 'false'){
//                $beginDate = date('Y-m-d', strtotime($sales->emission_date));
//                $endDate = date('Y-m-d', strtotime($sales->end_date));
//            }else{
//                $beginDate = null;
//                $endDate = null;
//            }
//            $now = date('Y-m-d');
//            $next = date('Y-m-d', strtotime($now. ' + 365 days'));
            
            $now = date("Y-m-d", strtotime($sales->begin_date));  
            $next = date("Y-m-d", strtotime($sales->end_date));  
//            dd($now);
             //Renovate Variables
            $disabled = '';
            
            return view('sales.R4.emit', [
                'saleId' => $saleId,
                'document' => $document,
                'beginDate' => $now,
                'endDate' => $next,
                'customer' => $customerData,
                'disabled' => $disabled,
                'insuranceBranch' => $insuranceBranch,
                'insuredValue' => $sales->insured_value,
                'newVehicle' => $newVehicle
            ]);
        }
        
    }

    public function emitR1Delete(request $request) {
        $saleId = decrypt($request['data']['saleId']);
        if($request['data']['type'] == 'Factura'){
            $sales = \App\sales::find($saleId);
            $sales->picture_factura = null;
            $sales->save();
        }
        if($request['data']['type'] == 'Carta'){
            $sales = \App\sales::find($saleId);
            $sales->picture_carta = null;
            $sales->save();
        }
    }

    public function emitR1Store(request $request) {
//        return $request;
        $saleId = decrypt($request['saleId']);
        if ($request['insuranceBranch'] == 2) {
            //Delete all Beneficiaries
            $delBene = \App\beneficiary::where('sales_id', '=', $saleId)->forceDelete();
            //Beneficiaries
            if (isset($request['TableData'])) {
                foreach ($request['TableData'] as $cus) {
                    $customer = \App\customers::where('document', '=', $cus['document'])->get();
                    if ($customer->isEmpty()) {
                        $document_id = \App\document::where('name', '=', $cus['type'])->get();
                        $customerNew = new \App\customers();
                        $customerNew->document = $cus['document'];
                        $customerNew->document_id = $document_id[0]->id;
                        $customerNew->first_name = $cus['first_name'];
                        $customerNew->last_name = $cus['last_name'];
                        $customerNew->save();
                        $customerId = $customerNew->id;
                    } else {
                        $customerId = $customer[0]->id;
                    }
                    $beneficiary = new \App\beneficiary();
                    $beneficiary->customer_id = $customerId;
                    $beneficiary->sales_id = $saleId;
                    $beneficiary->porcentage = $cus['porcentage'];
                    $beneficiary->save();
                }
            }

            $sale = \App\sales::find($saleId);
            $sale->status_id = 21;
            $sale->save();

            //Store Charge
//            $charge = new \App\Charge();
//            $charge->sales_id = $sale->id;
//            $charge->customers_id = $sale->customer_id;
//            $charge->status_id = 8;
//            $charge->value = $sale->total;
//            $charge->types_id = 1;
//            $charge->motives_id = 1;
//            $charge->save();
            \Session::flash('successMessage', 'Se ha enviado la Poliza a Sucre.');
            return $sale->id;
        }
        if ($request['insuranceBranch'] == 1) {
            $sale = \App\sales::find($saleId);
            $sale->begin_date = $request['beginDate'];
            $sale->end_date = $request['endDate'];
            $sale->status_id = 12;
            if(isset($request['newVehicleData'])){
                $sale->endorsement = $request['newVehicleData']['endosoSelect'];
                $sale->endorsement_document = $request['newVehicleData']['documentEndoso'];
                $sale->business_name_endorsement = $request['newVehicleData']['businessName'];
                $sale->trade_name_endorsement = $request['newVehicleData']['tradename'];
                $sale->amount_endorsement = str_replace(',','',$request['newVehicleData']['endorsementAmount']);
            }
            $sale->save();
            
            $customer = \App\customers::find($sale->customer_id);

            //Store Charge
            $charge = new \App\Charge();
            $charge->sales_id = $sale->id;
            $charge->customers_id = $sale->customer_id;
            $charge->status_id = 8;
            $charge->value = $sale->total;
            $charge->types_id = 1;
            $charge->motives_id = 1;
            $charge->save();
            
            //SEND EMAIL PAYMENT LINK//
            \App\Jobs\paymentSendLinkUserEmailJobs::dispatch($sale->id,$customer->email,$customer->document);

            return $charge->id;
        }
    }
    public function emitR4Store(request $request) {
//        return $request;
        $saleId = decrypt($request['saleId']);

            $sale = \App\sales::find($saleId);
            $sale->endorsement = $request['newVehicleData']['endosoSelect'];
            $sale->endorsement_document = $request['newVehicleData']['documentEndoso'];
            $sale->business_name_endorsement = $request['newVehicleData']['businessName'];
            $sale->trade_name_endorsement = $request['newVehicleData']['tradename'];
            $sale->amount_endorsement = str_replace(',','',$request['newVehicleData']['endorsementAmount']);
            $sale->status_id = 12;
            $sale->save();

            \Session::flash('successMessage', 'Se ha enviado el link de pago al cliente.');
            
            $customer = \App\customers::find($sale->customer_id);

            //Store Charge
            $charge = new \App\Charge();
            $charge->sales_id = $sale->id;
            $charge->customers_id = $sale->customer_id;
            $charge->status_id = 8;
            $charge->value = $sale->total;
            $charge->types_id = 1;
            $charge->motives_id = 1;
            $charge->save();
            
            //SEND EMAIL PAYMENT LINK//
            \App\Jobs\paymentSendLinkUserEmailJobs::dispatch($sale->id,$customer->email,$customer->document);

    }
    public function emitR2Store(request $request) {
//        return $request;
        $saleId = decrypt($request['saleId']);

            $sale = \App\sales::find($saleId);
            $sale->begin_date = $request['beginDate'];
            $sale->end_date = $request['endDate'];
            $sale->status_id = 12;
            $sale->save();

            \Session::flash('successMessage', 'Se ha enviado el link de pago al cliente.');
            
            $customer = \App\customers::find($sale->customer_id);

            //Store Charge
            $charge = new \App\Charge();
            $charge->sales_id = $sale->id;
            $charge->customers_id = $sale->customer_id;
            $charge->status_id = 8;
            $charge->value = $sale->total;
            $charge->types_id = 1;
            $charge->motives_id = 1;
            $charge->save();
            
            //SEND EMAIL PAYMENT LINK//
            \App\Jobs\paymentSendLinkUserEmailJobs::dispatch($sale->id,$customer->email,$customer->document);

    }

    public function encrypt(request $request) {
        $saleId = encrypt($request['saleId'][0]);
        return $saleId;
    }

    public function paymentsCreate(request $request) {
        try {
            $saleId = decrypt($request['sales']);
            
        } catch (DecryptException $ex) {
            return 'Solicite un nuevo enlace';
        }
        //Obtain Sale Data
        $sale = \App\sales::find($saleId);
        if($sale->status_id == 9){
            return view('sales.paymentsResult',[
                'success' => 'false',
                'code' => '888',
                'message' => 'La poliza ya se encuentra pagada'
            ]);
        }
        //Obtain Charge Data
        $chargeValidate = \App\Charge::where('sales_id',$sale->id)->get();
        if($chargeValidate->isEmpty()){
            $charge = new \App\Charge();
            $charge->sales_id = $sale->id;
            $charge->customers_id = $sale->customer_id;
            $charge->types_id = 1;
            $charge->motives_id = 1;
            $charge->status_id = 8;
            $charge->date = new \DateTime();
            $charge->value = $sale->total;
            $charge->save();
        }else{
            $charge = \App\Charge::find($chargeValidate[0]->id);
        }
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
        
        $pbc = \App\product_channel::find($sale->pbc_id);
        $product = \App\products::find($pbc->product_id);

        return view('sales.paymentsCreate', [
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
            'product' => $product
        ]);
    }

    public function paymentsStore(request $request) {
        return $request;
    }

    public function paymentsPay(request $request) {
        //Obtain Sale Data
        $sale = \App\sales::find($request['chargeId']);
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
        $paymentPrepareCheckout = paymentPrepareCheckoutProduction($items, $sale->total, $sale->tax, $sale->subtotal_12, $sale->subtotal_0, $customer->email, $customer->first_name, $customer->second_name, $customer->last_name, $customer->document, $sale->id, '192.168.100.22', 'finger', $customer->mobile_phone, $customer->address, 'EC', $customer->address, 'EC');
//        $paymentPrepareCheckout = annulmentDataFast('RF', '8ac7a4a0712bc2c201712c3a0aaa327e', '1.12', 'USD', '043464', '001877', '1000000505_PD100406', '0081003007010391000401200000000001205100817913101052012000000000000053012000000000100', '4540633170010000', '04', '22');
//        dd($paymentPrepareCheckout);
        return view('sales.paymentsCreate2', [
            'checkoutId' => $paymentPrepareCheckout->id,
            'sale' => $sale,
            'charge' => $charge,
            'status' => $status,
            'customer' => $customer
        ]);
    }
    
    public function endDate(request $request){
        $beginDate = $request['beginDate'];
        $newYear = date('Y-m-d', strtotime($beginDate . ' + 365 days'));
        return $newYear;
    }
    
    public function emitStoreUpload(request $request){
         $saleId = decrypt($request['saleId']);

        $validation = Validator::make($request->all(), [
                    'select_file' => 'required|image|mimes:jpeg,png,jpg,gif|max:10048'
        ]);

        if ($validation->passes()) {
            //Vehicle Folder
            $vehiFolder = public_path('images/sales/' . $saleId . '/');
            //Create Vehicle Folder
            if (!file_exists($vehiFolder)) {
                mkdir($vehiFolder, 0777, true);
            }

            $image = $request->file('select_file');
            $new_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/sales/' . $saleId), $new_name);

            $vinculationSearch = \App\vinculation_form::where('sales_id', '=', $saleId)->get();
            $vinculation = \App\vinculation_form::find($vinculationSearch[0]->id);
            $vinculation->picture_matricula = getAppRoute() . '/images/sales/' . $saleId . '/' . $new_name;
            $vinculation->save();
            $picture = $vinculation->picture_matricula;


            return response()->json([
                        'message' => 'Image Upload Successfully',
                        'uploaded_image' => '<a href="' . $picture . '" target="_blank"><img src="' . $picture . '" class="img-thumbnail" width="300" /></a>',
                        'class_name' => 'alert-success',
                        'Success' => 'true'
            ]);
        } else {
            return response()->json([
                        'message' => 'Debe subir la imagen en un formato valido',
                        'uploaded_image' => '',
                        'class_name' => 'alert-danger',
                        'vSalId' => $request->vSalId,
                        'Success' => 'false'
            ]);
        }
    }
    
    public function vinculationForm($id){
         $saleId = decrypt($id);
         $sales = \App\sales::find($saleId);
         $vinculation = \App\vinculation_form::where('sales_id','=',$saleId)->get();
         $customer = \App\customers::find($sales->customer_id);
         $countries = \App\country::all();
         $documents = \App\document::all();
         return view('sales.vinculationForm',[
             'sales' => $sales,
             'customer' => $customer,
             'countries' => $countries,
             'vinculation' => $vinculation[0],
             'documents' => $documents
         ]);
    }
    
    public function paymentsPayResult(request $request){
        $pageWasRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';

        if($pageWasRefreshed ) {
            return view('sales.paymentsResult',[
                'success' => 'false',
                'code' => '999',
                'message' => 'Debe intentar el pago nuevamente'
            ]);
        } 
        $response = processPaymentDataFast($request['id']);
//        dd($response);
        //Variables
        $merchantTransactionId = (explode("_",$response['merchantTransactionId'])); 
        $ReferenceNbr = (explode("_",$response['resultDetails']['ReferenceNbr'])); 
        $lot = $ReferenceNbr[0];
        $reference_number = $ReferenceNbr[1];
        if(isset($response['resultDetails']['AuthCode'])){ $authCode = $response['resultDetails']['AuthCode']; }else{ $authCode = "null"; }
        
        //Update DataFast Log
        $log = new \App\datafast_log();
        $log->id_cart = $merchantTransactionId[1];
        $log->order = $response['resultDetails']['OrderId'];
        $log->order_date = new \DateTime();
        $log->id_transaction = $response['merchantTransactionId'];
        $log->lot = $lot;
        $log->reference = $reference_number;
        $log->auth_code = $authCode;
        $log->code = $response['result']['code'];
        $log->response = $response['result']['description'];
        $log->id_response = $response['id'];
        $log->custom_value = $response['customParameters']['1000000505_PD100406'];
        $log->type = 'PAYMENT';
        $log->save();
        
        //Vaidate Response
        if($response['result']['code'] == '000.100.112'){ $success = 'true'; }else{ $success = 'false'; }
        
        if($success == 'true'){
            //Update Sale
            $sale = \App\sales::find($merchantTransactionId[1]);
            $sale->status_id = 9;
            $sale->save();
            
            //Validate Installmets  
            if(isset($response['recurring']['numberOfInstallments'])){
                $installments = $response['recurring']['numberOfInstallments'];
            }else{
                $installments = null;
            }
            
            //Validate givenName and middleName
            if(isset($response['middleName'])){
                $firstName = $response['customer']['givenName'].' '.$response['customer']['middleName'];
            }else{
                $firstName = $response['customer']['givenName'];
            }
            
            //Update Payment
            $payment = new \App\payments();
            $payment->payment_type_id = 2;
            $payment->date = new \DateTime();
            $payment->number = null;
            $payment->first_name = $firstName;
            $payment->last_name = $response['customer']['surname'];
            $payment->email = $response['customer']['email'];
            $payment->document = $response['customer']['identificationDocId'];
            $payment->cvc =  null;
            $payment->month = null;
            $payment->year = null;
            $payment->value = $response['amount'];
            $payment->user_id = null;
            $payment->auth_code = $log->auth_code;
            $payment->reference = $log->reference;
            $payment->transaction_id = $log->id_transaction;
            $payment->custom_parameters = '1000000505_PD100406';
            $payment->custom_value = $log->custom_value;
            $payment->shopper_interes = '0';
            $payment->shopper_installments = $installments;
            $payment->id_response = $log->id_response; 
            $payment->save();
            
            //Update Charge
            $chargeSearch = \App\Charge::where('sales_id','=',$sale->id)->get();
            $charge = \App\Charge::find($chargeSearch[0]->id);
            $charge->payments_id = $payment->id;
            $charge->status_id = 9;
            $charge->date = new \DateTime();
            $charge->save();
            
            //EMISION
            emisionSS($sale->id);
        }        
        
        return view('sales.paymentsResult',[
            'success' => $success,
            'code' => $log->code,
            'message' => $log->response
        ]);
    }
    
    public function vehiCheckPrice(request $request) {
//        return $request;
        $agency = \App\Agency::find(\Auth::user()->agen_id);

        $returnData = '';
        $date = new \DateTime();
        $today = $date->format('d-m-Y');
        $oneYear = date('d-m-Y', strtotime('+1 years'));
        $products = \App\product_channel::selectRaw('products_channel.*, pro.productodes')
                ->join('products as pro', 'pro.id', '=', 'products_channel.product_id')
                ->where('products_channel.agency_id', '=', $agency->id)
 //               ->where('products_channel.channel_id', '=', $agency->channel_id)
                ->where('pro.ramoid', '=', '7')
                ->where('products_channel.status_id', '=', '1')
                ->get();

        if ($products->isEmpty()) {
            $returnData .= '<div id="customerAlert" class="alert alert-warning registerForm titleDivBorderTop" style="margin-top:5px;border-radius:0px !important;">
                            <center><strong>¡Alerta!</strong> Por favor tome contacto con Seguros Sucre para poder recibir una tarifa preferencial para el Vehículo </center>
                            </div>';
            return $returnData;
        } else {
            foreach ($products as $pro) {
                $agencySS = \App\agencia_ss::find($pro->agency_ss_id);
                $prima = 0;
                $contribucion = 0;
                $sCam = 0;
                $derEmision = 0;
                $subSinIva = 0;
                $subConIva = 0;
                $iva = 0;
                $total = 0;
                foreach ($request['vehicleData'] as $vehi) {
                    $vehiValue = 0;
                    $vehiValue += str_replace(',','',$vehi['vehiValue']);

                    $accValue = 0;
                    if (isset($request['vehiAccData'])) {
                        foreach ($request['vehiAccData'] as $ac) {
                            if ($ac['plate'] == $vehi['plate']) {
                                $accValue += str_replace(',','',$ac['value']);
                            }
                        }
                        $value = $vehiValue + $accValue;
                    } else {
                        $value = $vehiValue;
                    }
                    //Consulta Prima por cada vehiculo
                    $result = calculoPrimaSS($pro->canal_plan_id, $today, $oneYear, $agencySS->agenciaid, $vehi['type'], $value);
                    
                    //Validate if result is valid
                    if ($result['error'][0]['code'] != '000') {
                        continue;
                    } else {
                        $prima += $result['rubrofactura']['primaneta'];
                    }
                }
                //Validate if prima is 0.00
                if($prima == '0'){
                    continue;
                }
                $coverage = \App\coverage::where('product_id', '=', $pro->product_id)->where('plandetindvis', '=', 'S')->where('coberturades', '!=', NULL)->skip(0)->take(4)->get();

                $agent = \App\agent_ss::find($pro->agent_ss);

                $returnData .= '<div class="pricing-table">
                                        <h3 class="pricing-title">' . $pro->productodes . '</h3>
                                        <div class="price">$' . $prima . '<sup>/ año</sup></div>
                                        <!-- Lista de Caracteristicas / Propiedades -->
                                        <table>';
                foreach ($coverage as $cov) {

                    $returnData .= '<tr>
                                                                <td align="left" width="60%">' . $cov->coberturades . '</td>
                                                                <td align="right" width="40%">$' . $cov->valorasegurado . '</td>
                                                            </tr>';
                }
                $returnData .= '</table>
                                        <!-- Contratar / Comprar -->
                                        <div class="table-buy">
                                            <a href="#" onclick="openProductModal(' . $pro->product_id . ', ' . $prima . ')" style="font-size:14px">Ver Condiciones</a>
                                            <a id="productModalBtn" href="#" data-toggle="modal" data-target="#productModal"></a>
                                            <br><br>
                                            <a href="#" class="pricing-action" onclick="fourthStepBtnNext(\'' . $pro->canal_plan_id . '\', \'' . $today . '\',\'' . $oneYear . '\',\'' . $agencySS->agenciaid . '\',\'AUTOMÓVIL\',\'' . $value . '\', \'' . $pro->product_id . '\')">Seleccionar</a>
                                        </div>
                                    </div>';
            }
        }
        if($returnData == ''){
            $returnData .= '<div id="customerAlert" class="alert alert-warning registerForm titleDivBorderTop" style="margin-top:5px;border-radius:0px !important;">
                <center><strong>¡Alerta!</strong> Por favor tome contacto con Seguros Sucre para poder recibir una tarifa preferencial para el Vehículo </center>
                </div>';
            return $returnData;
        }else{
            return $returnData;
        }
    }

    public function vehiCheckConditions(request $request){
        $returnData = '';
        $product = \App\products::find($request['productId']);
        if($product->ramodes == 'MULTIRIESGO'){
            $ramo = 'CASA HABITACION';
        }else{
            $ramo = $product->ramodes;
        }
        //MODAL HEADER
        $returnData .= '<div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <!--<h4 class="modal-title">Condiciones y Beneficios</h4>-->
                                            <div class="col-md-12 border" style="font-weight:bold;font-size: 40px;box-shadow: 1px 1px 1px #999;background: #104272; color:white;">
                                                <div id="modalVehicleBasic" class="col-md-4" style="text-align:center; font-size: 22px; height:70px; border-right: 1px solid black;">
                                                    '.$ramo.'
                                                </div>
                                                <div id="modalProductBasic" class="col-md-4" style="text-align:center;font-size: 22px;height:70px; border-right: 1px solid black;">
                                                    '.$product->productodes.'
                                                </div>
                                                <div id="modalPriceBasic" class="col-md-4" style="text-align:center;font-size: 22px;height:70px;">
                                                    $'.$request['prima'].' anuales
                                                </div>
                                            </div>
                                        </div>';
        ///COVERAGE
        $coverage = \App\coverage::where('product_id','=',$request['productId'])->where('plandetindvis','=','S')->get();
        if (count($coverage) > 0) {
            $returnData .= '<div class="modal-body" style="color:#104272;">
                                <div class="col-md-12" style="background: #ddd">';
            foreach($coverage as $cov){
                $valorasegurado = $cov->valorasegurado == 0.00 ? $valorasegurado = '<span class="glyphicon glyphicon-ok" style="color:green;font-size:10px"></span>' :$valorasegurado = '$ ' . $cov->valorasegurado;

 

                $returnData .= $cov->texto;
            }
            $returnData .= ' </div>';
        }
        $returnData .= '
                    </div>
                    </div>
                    <div class="modal-footer">
                        </div>';
        return $returnData;
    }

    public function insuranceApplication() {
        return view('sales.R2.insuranceApplication');
    
    }

    public function declarationBeneficiaries() {
        return view('sales.R3.declarationBeneficiaries');
    
    }

    public function R3create() {
        session(['salesCreateLifeInsuredId' => null]);
        session(['saleCreateAsset' => 'Asegurado']);
        session(['salesCreateLifeCustomerId' => null]);

        //Validate Create Permission
        $create = checkExtraPermits('19', \Auth::user()->role_id);
        if (!$create) {
            \Session::flash('ValidateUserRoute', 'No tiene acceso a crear nuevas cotizaciones.');
            return view('home');
        }

        $customer = new \App\customers();

        //Customer Country, Province, City, Occupatrion_ap
        $cusCity = new \App\city();
        $cusCityList = false;
        $cusProvince = new \App\province();
        $cusProvinceList = false;
        $cusCountry = new \App\country();
        $cusCountryList = false;

        $disabled = null;

        //Obtain Channel
        $channelQuery = 'select cha.* from channels cha join agencies agen on agen.channel_id = cha.id where agen.id =  "' . \Auth::user()->agen_id . '"';
        $channel = DB::select($channelQuery);

        //Product Data
        $products = DB::select('select pCha.id, pro.name, pro.price, pro.total_price, pro.segment, pro.detail, pro.conditions, pro.exclutions from products pro join products_channel pCha on pCha.product_id = pro.id where pro.status_id = "1" and pCha.channel_id = "' . $channel[0]->id . '" and pro.product_type in ("INDIVIDUAL","AMBOS")');
        $documents = DB::select('select * from documents where id in (1,3)');
        $countries = DB::select('select * from countries where id = 1');
        $occupation_ap = DB::select('select * from occupation_ap');
        $gender = \App\gender::find([1, 2]);

        $sale_movement = 1;

        $insuranceBranch = 2;
        
        return view('sales.R3.create', [
            'products' => $products,
            'documents' => $documents,
            'countries' => $countries,
            'customer' => $customer,
            'occupation_ap' => $occupation_ap,
            'disabled' => $disabled,
            'cusCity' => $cusCity,
            'cusProvince' => $cusProvince,
            'cusCountry' => $cusCountry,
            'cusCityList' => $cusCityList,
            'cusProvinceList' => $cusProvinceList,
            'sale_movement' => $sale_movement,
            'sale_id' => null,
            'gender' => $gender,
            'insuranceBranch' => $insuranceBranch,
            'customerId' => null,
            'insuredId' => null
        ]);
    
    }

    public function R3createPost(request $request) {
        //Validate Create Permission
        $edit = checkExtraPermits('19', \Auth::user()->role_id);
        if (!$edit) {
            \Session::flash('ValidateUserRoute', 'No tiene acceso a crear ventas Individuales.');
            return view('home');
        }
//        $customerID = session(['salesFirstName' => $request->first_name]);
        $customerID = session('salesCreateLifeCustomerId');
        $insuredID = session('salesCreateLifeInsuredId');
        $customer = \App\customers::find($customerID);

        //Customer Country, Province, City
        $cusCity = \App\city::find($customer->city_id);
        $cusCityList = true;
        $cusProvince = \App\province::find($cusCity->province_id);
        $cusProvinceList = true;
        $cusCountry = \App\country::find($cusProvince->country_id);
        $cusCountryList = true;

        $disabled = null;
        $vehiBodyTable = '';

        //Obtain Channel
        $channelQuery = 'select cha.* from channels cha join agencies agen on agen.channel_id = cha.id where agen.id =  "' . \Auth::user()->agen_id . '"';
        $channel = DB::select($channelQuery);

        //Product Data
        $products = DB::select('select pCha.id, pro.name, pro.price, pro.total_price, pro.segment, pro.detail, pro.conditions, pro.exclutions from products pro join products_channel pCha on pCha.product_id = pro.id where pro.status_id = "1" and pCha.channel_id = "' . $channel[0]->id . '" and pro.product_type in ("INDIVIDUAL","AMBOS")');
        $documents = DB::select('select * from documents where id in (1,3)');
        $countries = DB::select('select * from countries');
        $gender = \App\gender::find([1, 2]);

        $sale_movement = 1;

        $insuranceBranch = 2;

        return view('sales.customer', [
            'products' => $products,
            'documents' => $documents,
            'countries' => $countries,
            'customer' => $customer,
            'disabled' => $disabled,
            'cusCity' => $cusCity,
            'cusProvince' => $cusProvince,
            'cusCountry' => $cusCountry,
            'cusCityList' => $cusCityList,
            'cusProvinceList' => $cusProvinceList,
            'vehiBodyTable' => $vehiBodyTable,
            'sale_movement' => $sale_movement,
            'sale_id' => null,
            'gender' => $gender,
            'insuranceBranch' => $insuranceBranch,
            'customerId' => $customerID,
            'insuredId' => $insuredID
        ]);
    }

    public function R3CheckPrice(request $request) {
        $agency = \App\Agency::find(\Auth::user()->agen_id);
        
        $returnData = '';
        $date = new \DateTime();
        $today = $date->format('d-m-Y');
        $oneYear = date('d-m-Y', strtotime('+1 years'));
        
        $products = \App\product_channel::selectRaw('products_channel.*, pro.productodes')
                                            ->join('products as pro','pro.id','=','products_channel.product_id')
                                            ->where('products_channel.agency_id','=',$agency->id)
//                                            ->where('products_channel.channel_id', '=', $agency->channel_id)
                                            ->whereIn('pro.ramoid',array(4))
                                            ->get();

        if($products->isEmpty()){
            $returnData .= '<div id="customerAlert" class="alert alert-warning registerForm titleDivBorderTop" style="margin-top:5px;border-radius:0px !important;">
                            <center><strong>¡Alerta!</strong> Por favor tome contacto con Seguros Sucre para poder recibir una tarifa preferencial para el Vehículo </center>
                            </div>';
            return $returnData;
            
        }else{
            foreach ($products as $pro) {
                $agencySS = \App\agencia_ss::find($pro->agency_ss_id);
                $prima = 0;
                $contribucion = 0;
                $sCam = 0;
                $derEmision = 0;
                $subSinIva = 0;
                $subConIva = 0;
                $iva = 0;
                $total = 0;


                //Consulta Prima por cada asegurado para accidente personal
                $result = calculoPrimaR2($pro->canal_plan_id, $today, $oneYear);
                
                if ($result['error'][0]['code'] != '000') {
//                    $returnData .= '<div id="customerAlert" class="alert alert-warning registerForm titleDivBorderTop" style="margin-top:5px;border-radius:0px !important;">
//                                    <center><strong>¡Alerta!</strong> 123 Por favor tome contacto con Seguros Sucre para poder recibir una tarifa preferencial para vida </center>
//                                    </div>';
//                    return $returnData;
                }else{
                    foreach ($result['rubrofactura']['rubros'] as $a) {
                        if ($a['rubro'] == 'PRIMA NETA') {
                            $prima += $a['valor'];
                        }
                        if ($a['rubro'] == 'CONTRIBUCIÓN') {
                            $contribucion += $a['valor'];
                        }
                        if ($a['rubro'] == 'S. SOCIAL CAMPESINO') {
                            $sCam += $a['valor'];
                        }
                        if ($a['rubro'] == 'DERECHO DE EMISION') {
                            $derEmision += $a['valor'];
                        }
                        if ($a['rubro'] == 'SUBTOTAL TARIFA IVA' && $a['simbolo'] == '0.00%') {
                            $subSinIva += $a['valor'];
                        }
                        if ($a['rubro'] == 'SUBTOTAL TARIFA IVA' && $a['simbolo'] == '12.00%') {
                            $subConIva += $a['valor'];
                        }
                        if ($a['rubro'] == 'I.V.A') {
                            $iva += $a['valor'];
                        }
                        if ($a['rubro'] == 'TOTAL') {
                            $total += $a['valor'];
                        }
                    }

        //$prima = 1000;
        //$contribucion = 1000;
        //$sCam = 1000;
        //$derEmision = 1000;
        //$subSinIva = 1000;
        //$subConIva = 1000;
        //$iva = 1000;
        //$total = 1000;

                    $coverage = \App\coverage::where('product_id', '=', $pro->product_id)->where('plandetindvis', '=', 'S')->where('coberturades', '!=', NULL)->skip(0)->take(4)->get();

                    $agent = \App\agent_ss::find($pro->agent_ss_id);

                    $returnData .= '<div class="pricing-table">
                                    <h3 class="pricing-title">' . $pro->productodes . '</h3>
                                    <div class="price">$' . $prima . '<sup>/ año</sup></div>
                                    <!-- Lista de Caracteristicas / Propiedades -->
                                    <table>';
                    foreach ($coverage as $cov) {

                        $valorasegurado = $cov->valorasegurado == 0.00 ? $valorasegurado = '<span class="glyphicon glyphicon-ok" style="color:green;font-size:10px"></span>' :$valorasegurado = '$ ' . $cov->valorasegurado;

                        $returnData .= '<tr>
                                                            <td align="left" width="60%">' . $cov->coberturades . '</td>
                                                            <td align="right" width="40%">' . $valorasegurado . '</td>
                                                        </tr>';
                    }
                    $returnData .= '</table>
                                    <!-- Contratar / Comprar -->
                                    <div class="table-buy">
                                        <a href="#" onclick="openProductModal(' . $pro->product_id . ', ' . $prima . ')" style="font-size:14px">Ver Condiciones</a>
                                        <a id="productModalBtn" href="#" data-toggle="modal" data-target="#productModal"></a>
                                        <br><br>
                                        <a href="#" class="pricing-action" onclick="thirdStepBtnNext(\'' . $pro->canal_plan_id . '\', \'' . $today . '\',\'' . $oneYear . '\',\'' . $agencySS->agenciaid . '\',\'VIDA\', \'' . $prima . '\', \'' . $contribucion . '\', \'' . $sCam . '\', \'' . $derEmision . '\', \'' . $subSinIva . '\', \'' . $subConIva . '\', \'' . $iva . '\',\'' . $total . '\',\'' . $pro->product_id . '\')">Seleccionar</a>
                                    </div>
                                </div>';
                    
                }


            }
            
        }
        
        return $returnData;
    }

    public function R3CheckConditions(request $request){
        $returnData = '';
        $product = \App\products::find($request['productId']);
        //MODAL HEADER
        $returnData .= '<div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <!--<h4 class="modal-title">Condiciones y Beneficios</h4>-->
                                            <div class="col-md-12 border" style="font-weight:bold;font-size: 40px;box-shadow: 1px 1px 1px #999;background: #104272; color:white;">
                                                <div id="modalVehicleBasic" class="col-md-4" style="text-align:center; font-size: 22px; height:70px; border-right: 1px solid black;">
                                                    '.$product->ramodes.'
                                                </div>
                                                <div id="modalProductBasic" class="col-md-4" style="text-align:center;font-size: 22px;height:70px; border-right: 1px solid black;">
                                                    '.$product->productodes.'
                                                </div>
                                                <div id="modalPriceBasic" class="col-md-4" style="text-align:center;font-size: 22px;height:70px;">
                                                    $'.$request['prima'].' anuales
                                                </div>
                                            </div>
                                        </div>';
        //COVERAGE
        $coverage = \App\coverage::where('product_id','=',$request['productId'])->where('plandetindvis','=','S')->get();
        if (count($coverage) > 0) {
            $returnData .= '<div class="modal-body" style="color:#104272;">
                                <div class="col-md-12" style="background: #ddd">';
            foreach($coverage as $cov){
                $valorasegurado = $cov->valorasegurado == 0.00 ? $valorasegurado = '<span class="glyphicon glyphicon-ok" style="color:green;font-size:10px"></span>' :$valorasegurado = '$ ' . $cov->valorasegurado;

 

                $returnData .= $cov->texto;
            }
            $returnData .= ' </div>';
        }
        $returnData .= '
                    </div>
                    </div>
                    <div class="modal-footer">
                        </div>';
        return $returnData;
    }

    public function R3ResumeNewSS(request $request) {
        //return $request;
        $prima = number_format((float)$request['data']['prima'], 2, '.', '');
        $contribucion = number_format((float)$request['data']['contribucion'], 2, '.', '');
        $sCam = number_format((float)$request['data']['sCam'], 2, '.', '');
        $derEmision = number_format((float)$request['data']['derEmision'], 2, '.', '');
        $subSinIva = number_format((float)$request['data']['subSinIva'], 2, '.', '');
        $subConIva = number_format((float)$request['data']['subConIva'], 2, '.', '');
        $iva = number_format((float)$request['data']['iva'], 2, '.', '');
        $total = number_format((float)$request['data']['total'], 2, '.', '');
//        number_format((float)$number, 2, '.', '');
    
        $product = \App\products::find($request['data']['proId']);

        //Vehicle Table Resume
        $R2Table = '<tr align="center">
                            <td>' . $product->productodes . '</td>
                            <td style="text-align:right">$' . $prima . '</td>
                        </tr>';
        $arrayResponse = array();
        array_push($arrayResponse, $R2Table);

        $taxTable = '<tr align="center">
                        <th style="text-align:right;">S. de Compañias (3.5%)</th>
                        <td style="width:40%;text-align:right">$' . $contribucion . '</td>
                        </tr>
                        <input type="hidden" id="sBancos" name="sBancos" value="'.$contribucion.'">
                        <tr align="center">
                        <th style="text-align:right;">S. Campesino (0.5%)</th>
                        <td style="width:40%;text-align:right">$' . $sCam . '</td>
                        </tr>
                        <input type="hidden" id="sCampesino" name="sCampesino" value="'.$sCam.'">
                        <tr align="center">
                        <th style="text-align:right;">Derechos de Emisión</th>
                        <td style="width:40%;text-align:right">$' . $derEmision . '</td>
                        </tr>
                        <input type="hidden" id="dEmision" name="dEmision" value="'.$derEmision.'">
                        <tr align="center">
                        <th style="text-align:right;">Subtotal 12%</th>
                        <td style="width:40%;text-align:right">$' . $subConIva . '</td>
                        </tr>
                        <input type="hidden" id="subtotal12" name="subtotal12" value="'.$subConIva.'">
                        <tr align="center">
                        <th style="text-align:right;">Subtotal 0%</th>
                        <td style="width:40%;text-align:right">$' . $subSinIva . '</td>
                        </tr>
                        <input type="hidden" id="subtotal0" name="subtotal0" value="'.$subSinIva.'">
                        <tr align="center">
                        <th style="text-align:right;">IVA</th>
                        <td style="width:40%;text-align:right">$' . $iva . '</td>
                        </tr>
                        <input type="hidden" id="tax" name="tax" value="'.$iva.'">
                        <tr align="center">
                        <th style="background-color:#b3b0b0;text-align:right;">Total</th>
                        <td style="background-color:#b3b0b0;width:40%;text-align:right">$' . $total . '</td>
                        </tr>
                        <input type="hidden" id="total" name="total" value="'.$total.'">
                        <input type="hidden" id="rate" name="rate" value="15">';

        array_push($arrayResponse, $taxTable);
        
        //Customer Variable
        $customer = \App\customers::find(session('salesCreateLifeCustomerId'));
        $customer = 35;
        array_push($arrayResponse, $customer);
        return $arrayResponse;
    }

    public function R3Store(request $request) {
        set_time_limit(120);
//        return $request;
        //Save or Update Customer
        $customerSql = 'select * from customers where document = "' . $request['data']['customer']['document'] . '"';
        $customer = DB::select($customerSql);

        //Validate Customer Save or Update
        if ($customer) {
            $customerUpdate = \App\customers::find($customer[0]->id);
//            $customerUpdate->first_name = $request['data']['customer']['firstName'];
//            $customerUpdate->last_name = $request['data']['customer']['lastName'];
//            $customerUpdate->document = $request['data']['customer']['document'];
//            $customerUpdate->document_id = $request['data']['customer']['documentId'];
            $customerUpdate->last_name = $request['data']['customer']['lastName'];
            $customerUpdate->second_last_name = $request['data']['customer']['secondLastName'];
            $customerUpdate->address = $request['data']['customer']['address'];
            $customerUpdate->city_id = $request['data']['customer']['city'];
            $customerUpdate->phone = $request['data']['customer']['phone'];
            $customerUpdate->mobile_phone = $request['data']['customer']['mobilePhone'];
            $customerUpdate->email = $request['data']['customer']['email'];
            $customerUpdate->birthdate = $request['data']['customer']['birthdate'];
            $customerUpdate->save();
            $customerId = $customerUpdate->id;
            $customerPhone = substr($customerUpdate->mobile_phone, 1);
            $customerEmail = $customerUpdate->email;
            $customerDocument = $customerUpdate->document;
            $customerSearch = \App\customers::find($customerUpdate->id);
        } else {
            $customerNew = new \App\customers();
            $customerNew->first_name = $request['data']['customer']['firstName'];
            $customerNew->second_name = $request['data']['customer']['secondName'];
            $customerNew->last_name = $request['data']['customer']['lastName'];
            $customerNew->second_last_name = $request['data']['customer']['secondLastName'];
            $customerNew->document = $request['data']['customer']['document'];
            $customerNew->document_id = $request['data']['customer']['documentId'];
            $customerNew->address = $request['data']['customer']['address'];
            $customerNew->city_id = $request['data']['customer']['city'];
            $customerNew->phone = $request['data']['customer']['phone'];
            $customerNew->mobile_phone = $request['data']['customer']['mobilePhone'];
            $customerNew->email = $request['data']['customer']['email'];
            $customerNew->birthdate = $request['data']['customer']['birthdate'];
            $customerNew->status_id = 1;
            $customerNew->save();
            $customerId = $customerNew->id;
            $customerPhone = substr($customerNew->mobile_phone, 1);
            $customerEmail = $customerNew->email;
            $customerDocument = $customerNew->document;
            $customerSearch = \App\customers::find($customerNew->id);
        }
        
        $productChannel = \App\product_channel::where('canal_plan_id','=',$request['data']['product'])->get();
        
        $coverage = \App\coverage::where('coberturades','=','MUERTE ACCIDENTAL')->where('product_id','=',$productChannel[0]->product_id)->get();
        if($coverage->isEmpty()){
            $coverageValorAsegurado = 0.00;
        }else{
            $coverageValorAsegurado = $coverage[0]->valorasegurado;
        }

        //DateTime
        $now = new \DateTime();

        //Send Quotation Email Check Box
        if ($request['data']['sendQuotation'] == 'true') {
            $chkBoxSendQuotation = true;
        } else {
            $chkBoxSendQuotation = false;
        }
        
        $result = calculoPrimaR2($productChannel[0]->canal_plan_id, '01/05/2020', '01/05/2021');

        //Store Sale
        $salesNew = new \App\sales();
        $salesNew->pbc_id = $productChannel[0]->id;
        $salesNew->user_id = \Auth::user()->id;
        $salesNew->customer_id = $customerId;
        $salesNew->status_id = 36;
        $salesNew->emission_date = $now;
        $salesNew->token_date_send = $now;
        $salesNew->subtotal_0 = $request['data']['subtotal0'];
        $salesNew->subtotal_12 = $request['data']['subtotal12'];
        $salesNew->other_discount = 0;
        $salesNew->seguro_campesino = $request['data']['sCampesino'];
        $salesNew->super_bancos = $request['data']['sBanco'];
        $salesNew->derecho_emision = $request['data']['dEmision'];
        $salesNew->tax = $request['data']['tax'];
        $salesNew->total = $request['data']['total'];
        $salesNew->agen_id = \Auth::user()->agen_id;
        $salesNew->cus_mobile_phone = $request['data']['customer']['mobilePhone'];
        $salesNew->cus_phone = $request['data']['customer']['phone'];
        $salesNew->cus_address = $request['data']['customer']['address'];
        $salesNew->cus_email = $request['data']['customer']['email'];
        $salesNew->cus_city = $request['data']['customer']['city'];
        $salesNew->sales_movements_id = $request['data']['saleMovement'];
        $salesNew->sales_id = $request['data']['saleId'];
        $salesNew->sales_type_id = 1;
        $salesNew->chkBoxSendQuotation = $chkBoxSendQuotation;
        $salesNew->rate = $result['rubrofactura']['tasa'];
        $salesNew->prima_total = $result['rubrofactura']['rubros'][0]['valor'];
        $salesNew->insured_value =  $coverageValorAsegurado;
        $salesNew->customer_occupation_id = $request['data']['occupation'];
        $salesNew->save();
                
        \App\Jobs\listaObservadosyCarteraJobs::dispatch($request['data']['product'], $customerId, $salesNew->id, \Auth::user()->email);
        
        if (isset($request['data']['saleId'])) {
            $salesOld = \App\sales::find($request['data']['saleId']);
            $salesOld->has_been_renewed = 1;
            $salesOld->has_been_renewed_date = $now;
            $salesOld->save(); 
        }

        //Vinculation_form
        $vinculation = new \App\vinculation_form();
        $vinculation->customer_id = $customerId;
        $vinculation->sales_id = $salesNew->id;
        $vinculation->status_id = 6;
        $vinculation->main_road = $request['data']['customer']['address'];
        $vinculation->city_id = $request['data']['customer']['city'];
        $vinculation->phone = $request['data']['customer']['phone'];
        $vinculation->mobile_phone = $request['data']['customer']['mobilePhone'];
        $vinculation->email = $request['data']['customer']['email'];
        $vinculation->birth_date = $request['data']['customer']['birthdate'];
        $vinculation->save(); 

        if ($chkBoxSendQuotation == true) {
            $job = (new \App\Jobs\QuotationR3EmailJobs($salesNew->id, $customerEmail, $customerDocument));
            dispatch($job);
        }

        //Store Charge
//        $charge = new \App\Charge();
//        $charge->sales_id = $salesNew->id;
//        $charge->customers_id = $customerId;
//        $charge->status_id = 8;
//        $charge->value = $salesNew->total;
//        $charge->types_id = 1;
//        $charge->motives_id = 1;
//        $charge->save();
        //Send SMS
        if ($salesNew->sales_type_id == 1) { // INDIVIDUAL SALE - SEND SMS
//        sendSMS($customerPhone, $randomCode, $salesNew->id);
        } else { // REMOTE SALE - SEND LINK
            //sendLinkSMS($customerPhone, $randomCode, $salesNew->id);
        }
        //Store R2

        //Consulta Lista Observados y Cartera Vencida SS
        
//        listaObservadosYCarteraFunction($salesNew->id, $customerId, \Auth::user()->email, $request['data']['product']);

        $returnArray = array();
        $returnArray = [
            'productId' => $salesNew->id,
            'validationCode' => $salesNew->token,
            'salId' => $salesNew->id
        ];

        return $returnArray;
    }


    public function R4create() {
        session(['salesCreateLifeInsuredId' => null]);
        session(['saleCreateAsset' => 'Asegurado']);
        session(['salesCreateLifeCustomerId' => null]);

        //Validate Create Permission
        $create = checkExtraPermits('19', \Auth::user()->role_id);
        if (!$create) {
            \Session::flash('ValidateUserRoute', 'No tiene acceso a crear nuevas cotizaciones.');
            return view('home');
        }

        $customer = new \App\customers();

        //Customer Country, Province, City, Occupation_ap
        $cusCity = new \App\city();
        $cusCityList = false;
        $cusProvince = new \App\province();
        $cusProvinceList = false;
        $cusCountry = new \App\country();
        $cusCountryList = false;
        $cities = array();

        $disabled = null;
        $rubrosBodyTable = '';

        //Obtain Channel
        $channelQuery = 'select cha.* from channels cha join agencies agen on agen.channel_id = cha.id where agen.id =  "' . \Auth::user()->agen_id . '"';
        $channel = DB::select($channelQuery);

        //Product Data
        $products = DB::select('select pCha.id, pro.name, pro.price, pro.total_price, pro.segment, pro.detail, pro.conditions, pro.exclutions from products pro join products_channel pCha on pCha.product_id = pro.id where pro.status_id = "1" and pCha.channel_id = "' . $channel[0]->id . '" and pro.product_type in ("INDIVIDUAL","AMBOS")');
        $documents = DB::select('select * from documents where id in (1,3)');
        $countries = DB::select('select * from countries where id = 1');
        $rubros = DB::select('select DISTINCT(pr.cod), pr.description, pr.value, pr.max_value, pr.min_value from products_rubros pr inner join products p on p.id = pr.product_id where pr.status_id = 1 AND pr.value = 0 AND p.agency_id ="' . \Auth::user()->agen_id . '"');
        $provinces = DB::select('select name, id from provinces');

        $gender = \App\gender::find([1, 2]);

        $sale_movement = 1;

        $insuranceBranch = 2;
        
        return view('sales.R4.create', [
            'products' => $products,
            'documents' => $documents,
            'countries' => $countries,
            'provinces' => $provinces,
            'cities' => $cities,
            'customer' => $customer,
            'rubros' => $rubros,
            'disabled' => $disabled,
            'cusCity' => $cusCity,
            'cusProvince' => $cusProvince,
            'cusCountry' => $cusCountry,
            'cusCityList' => $cusCityList,
            'cusProvinceList' => $cusProvinceList,
            'rubrosBodyTable' => $rubrosBodyTable,
            'sale_movement' => $sale_movement,
            'sale_id' => null,
            'gender' => $gender,
            'insuranceBranch' => $insuranceBranch,
            'customerId' => null,
            'insuredId' => null
        ]);
    }
    
    public function R4createPost(request $request) {
        //Validate Create Permission
        $edit = checkExtraPermits('19', \Auth::user()->role_id);
        if (!$edit) {
            \Session::flash('ValidateUserRoute', 'No tiene acceso a crear ventas Individuales.');
            return view('home');
        }
//        $customerID = session(['salesFirstName' => $request->first_name]);
        $customerID = session('salesCreateLifeCustomerId');
        $insuredID = session('salesCreateLifeInsuredId');
        $customer = \App\customers::find($customerID);

        //Customer Country, Province, City
        $cusCity = \App\city::find($customer->city_id);
        $cusCityList = true;
        $cusProvince = \App\province::find($cusCity->province_id);
        $cusProvinceList = true;
        $cusCountry = \App\country::find($cusProvince->country_id);
        $cusCountryList = true;

        $disabled = null;
        $vehiBodyTable = '';

        //Obtain Channel
        $channelQuery = 'select cha.* from channels cha join agencies agen on agen.channel_id = cha.id where agen.id =  "' . \Auth::user()->agen_id . '"';
        $channel = DB::select($channelQuery);

        //Product Data
        $products = DB::select('select pCha.id, pro.name, pro.price, pro.total_price, pro.segment, pro.detail, pro.conditions, pro.exclutions from products pro join products_channel pCha on pCha.product_id = pro.id where pro.status_id = "1" and pCha.channel_id = "' . $channel[0]->id . '" and pro.product_type in ("INDIVIDUAL","AMBOS")');
        $documents = DB::select('select * from documents where id in (1,3)');
        $countries = DB::select('select * from countries');
        $gender = \App\gender::find([1, 2]);

        $sale_movement = 1;

        $insuranceBranch = 2;

        return view('sales.customer', [
            'products' => $products,
            'documents' => $documents,
            'countries' => $countries,
            'customer' => $customer,
            'disabled' => $disabled,
            'cusCity' => $cusCity,
            'cusProvince' => $cusProvince,
            'cusCountry' => $cusCountry,
            'cusCityList' => $cusCityList,
            'cusProvinceList' => $cusProvinceList,
            'vehiBodyTable' => $vehiBodyTable,
            'sale_movement' => $sale_movement,
            'sale_id' => null,
            'gender' => $gender,
            'insuranceBranch' => $insuranceBranch,
            'customerId' => $customerID,
            'insuredId' => $insuredID
        ]);
    }

    public function R4CheckPrice(request $request) {
        set_time_limit(300);
//        return $request;
        $agency = \App\Agency::find(\Auth::user()->agen_id);

        $returnData = '';
        $date = new \DateTime();
        $today = $date->format('d-m-Y');
        $oneYear = date('d-m-Y', strtotime('+1 years'));

        $products = \App\product_channel::selectRaw('products_channel.*, pro.productodes')
                ->join('products as pro', 'pro.id', '=', 'products_channel.product_id')
                ->where('products_channel.status_id', '=', '1')
                ->where('products_channel.agency_id', '=', $agency->id)
//                                            ->where('products_channel.channel_id', '=', $agency->channel_id)
                ->where('products_channel.status_id', '=', '1')
                ->whereIn('pro.ramoid', ['40', '5'])
                ->get();
//        dd($products);
        if ($products->isEmpty()) {
            $returnData .= '<div id="customerAlert" class="alert alert-warning registerForm titleDivBorderTop" style="margin-top:5px;border-radius:0px !important;">
                            <center><strong>¡Alerta!</strong> Por favor tome contacto con Seguros Sucre para poder recibir una tarifa preferencial para incendio </center>
                            </div>';
            return $returnData;
        } else {
            foreach ($products as $pro) {
                $productsChannel = \App\product_channel::where('canal_plan_id', '=', $pro->canal_plan_id)->get();
                $agencySS = \App\agencia_ss::find($productsChannel[0]->agency_ss_id);
                $prima = 0;
                $contribucion = 0;
                $sCam = 0;
                $derEmision = 0;
                $subSinIva = 0;
                $subConIva = 0;
                $iva = 0;
                $total = 0;

                //Consultar la Cobertura Principal con el valor asegurado enviado por el usuario. 
                $rubroCod = \App\products_rubros::where('description', '=', $request['nameRubro'])->where('product_id', '=', $pro->product_id)->get();

                //Consulta Prima por cada asegurado para accidente personal
                $result = calculoPrimaR4($pro->canal_plan_id, $rubroCod[0]->cod, str_replace(',','',$request['assuredValue']), $today, $oneYear);

                //Obtener la prima
                foreach ($result['rubrofactura']['rubros'] as $a) {
                    if ($a['rubro'] == 'PRIMA NETA') {
                        $prima += $a['valor'];
                    }
                }
                
                //Consulta Prima por los rubros adicionales
                $rubroAdd = \App\products_rubros::where('cod', '!=', $rubroCod[0]->cod)->where('product_id', '=', $pro->product_id)->get();
                
                //Sumar las primas
                foreach($rubroAdd as $r){
                    $result = calculoPrimaR4($pro->canal_plan_id, $r->cod, $r->value, $today, $oneYear);
                    
                    foreach ($result['rubrofactura']['rubros'] as $a) {
                        if ($a['rubro'] == 'PRIMA NETA') {
                            $prima += $a['valor'];
                        }
                    }
                }
                
                //Consultar las coberturas que sumen valor de prima
                $coverages = \App\coverage::where('plandetindspri','=','S')->where('product_id','=',$pro->product_id)->get();
                
                //Sumar las primas
                foreach ($coverages as $c) {
                    $prima += $c->plandetprima;
                }
                
//                $costoSeguro = c
                
                $rate = $result['rubrofactura']['tasa'];

                $coverage = \App\coverage::where('product_id', '=', $pro->product_id)->where('plandetindvis', '=', 'S')->where('coberturades', '!=', NULL)->skip(0)->take(4)->get();

                $agent = \App\agent_ss::find($productsChannel[0]->agent_ss);

                $returnData .= '<div class="pricing-table">
                                <h3 class="pricing-title">' . $pro->productodes . '</h3>
                                <div class="price">$' . $prima . '<sup>/ año</sup></div>
                                <!-- Lista de Caracteristicas / Propiedades -->
                                <table>';
                foreach ($coverage as $cov) {

                    $valorasegurado = $cov->valorasegurado == 0.00 ? $valorasegurado = '<span class="glyphicon glyphicon-ok" style="color:green;font-size:10px"></span>' : $valorasegurado = '$ ' . $cov->valorasegurado;

                    $returnData .= '<tr>
                                        <td align="left" width="60%">' . $cov->coberturades . '</td>
                                        <td align="right" width="40%">' . $valorasegurado . '</td>
                                    </tr>';
                }

                $returnData .= '</table>
                                <!-- Contratar / Comprar -->
                                <div class="table-buy">
                                    <a href="#" onclick="openProductModal(' . $pro->product_id . ', ' . $prima . ')" style="font-size:14px">Ver Condiciones</a>
                                    <a id="productModalBtn" href="#" data-toggle="modal" data-target="#productModal"></a>
                                    <br><br>
                                    <a href="#" class="pricing-action" onclick="thirdStepBtnNext(\'' . $pro->canal_plan_id . '\', \'' . $today . '\',\'' . $oneYear . '\',\'' . $agencySS->agenciaid . '\',\'VIDA\', \'' . $prima . '\', \'' . $contribucion . '\', \'' . $sCam . '\', \'' . $derEmision . '\', \'' . $subSinIva . '\', \'' . $subConIva . '\', \'' . $iva . '\',\'' . $total . '\', \'' . $rate . '\', \'' . $pro->product_id . '\')">Seleccionar</a>
                                </div>
                            </div>';
            }
        }

        return $returnData;
    }

    public function R4ResumeNewSS(request $request) {
        //return $request;
        
        //Obtain new Values
        $result = costoSeguroSS(40, $request['data']['prima']);
        $primaResult = 0;
        $contribucionResult = 0;
        $sCamResult = 0;
        $derEmisionResult = 0;
        $subSinIvaResult = 0;
        $subConIvaResult = 0;
        $ivaResult = 0;
        $totalResult = 0;
        foreach ($result['rubrofactura']['rubros'] as $a) {
            if ($a['rubro'] == 'PRIMA NETA') {
                $primaResult += $a['valor'];
            }
            if ($a['rubro'] == 'CONTRIBUCIÓN') {
                $contribucionResult += $a['valor'];
            }
            if ($a['rubro'] == 'S. SOCIAL CAMPESINO') {
                $sCamResult += $a['valor'];
            }
            if ($a['rubro'] == 'DERECHO DE EMISION') {
                $derEmisionResult += $a['valor'];
            }
            if ($a['rubro'] == 'SUBTOTAL TARIFA IVA' && $a['simbolo'] == '0.00%') {
                $subSinIvaResult += $a['valor'];
            }
            if ($a['rubro'] == 'SUBTOTAL TARIFA IVA' && $a['simbolo'] == '12.00%') {
                $subConIvaResult += $a['valor'];
            }
            if ($a['rubro'] == 'I.V.A') {
                $ivaResult += $a['valor'];
            }
            if ($a['rubro'] == 'TOTAL') {
                $totalResult += $a['valor'];
            }
        }
        
        $prima = number_format((float)$primaResult, 2, '.', '');
        $contribucion = number_format((float)$contribucionResult, 2, '.', '');
        $sCam = number_format((float)$sCamResult, 2, '.', '');
        $derEmision = number_format((float)$derEmisionResult, 2, '.', '');
        $subSinIva = number_format((float)$subSinIvaResult, 2, '.', '');
        $subConIva = number_format((float)$subConIvaResult, 2, '.', '');
        $iva = number_format((float)$ivaResult, 2, '.', '');
        $total = number_format((float)$totalResult, 2, '.', '');
        $rate = number_format((float)$request['data']['rate'], 2, '.', '');
//        number_format((float)$number, 2, '.', '');
    
        $product = \App\products::find($request['data']['proId']);

        //Incendio Table Resume
        $R4Table = '<tr align="center">
                            <td>' . $product->productodes . '</td>
                            <td style="text-align:right">$' . $prima . '</td>
                        </tr>';
        $arrayResponse = array();
        array_push($arrayResponse, $R4Table);

        $taxTable = '<tr align="center">
                        <th style="text-align:right;">S. de Compañias (3.5%)</th>
                        <td style="width:40%;text-align:right">$' . $contribucion . '</td>
                        </tr>
                        <input type="hidden" id="sBancos" name="sBancos" value="'.$contribucion.'">
                        <tr align="center">
                        <th style="text-align:right;">S. Campesino (0.5%)</th>
                        <td style="width:40%;text-align:right">$' . $sCam . '</td>
                        </tr>
                        <input type="hidden" id="sCampesino" name="sCampesino" value="'.$sCam.'">
                        <tr align="center">
                        <th style="text-align:right;">Derechos de Emisión</th>
                        <td style="width:40%;text-align:right">$' . $derEmision . '</td>
                        </tr>
                        <input type="hidden" id="dEmision" name="dEmision" value="'.$derEmision.'">
                        <tr align="center">
                        <th style="text-align:right;">Subtotal 12%</th>
                        <td style="width:40%;text-align:right">$' . $subConIva . '</td>
                        </tr>
                        <input type="hidden" id="subtotal12" name="subtotal12" value="'.$subConIva.'">
                        <tr align="center">
                        <th style="text-align:right;">Subtotal 0%</th>
                        <td style="width:40%;text-align:right">$' . $subSinIva . '</td>
                        </tr>
                        <input type="hidden" id="subtotal0" name="subtotal0" value="'.$subSinIva.'">
                        <tr align="center">
                        <th style="text-align:right;">IVA</th>
                        <td style="width:40%;text-align:right">$' . $iva . '</td>
                        </tr>
                        <input type="hidden" id="tax" name="tax" value="'.$iva.'">
                        <tr align="center">
                        <th style="background-color:#b3b0b0;text-align:right;">Total</th>
                        <td style="background-color:#b3b0b0;width:40%;text-align:right">$' . $total . '</td>
                        </tr>
                        <input type="hidden" id="total" name="total" value="'.$total.'">
                        <input type="hidden" id="rate" name="rate" value="'.$rate.'">
                        <input type="hidden" id="prima" name="prima" value="'.$prima.'">';


        array_push($arrayResponse, $taxTable);
        
        //Customer Variable
        $customer = \App\customers::find(session('salesCreateLifeCustomerId'));
        $customer = 35;
        array_push($arrayResponse, $customer);
        return $arrayResponse;
    }


    public function R4Store(request $request) {
        set_time_limit(300);
        //Save or Update Customer
        $customerSql = 'select * from customers where document = "' . $request['data']['customer']['document'] . '"';
        $customer = DB::select($customerSql);

        $rubrosSql = 'select cod from products_rubros where description = "' . $request['data']['property']['rubroName'] . '"';
        $rubrosCod = DB::select($rubrosSql);

        //Validate Customer Save or Update
        if ($customer) {
            $customerUpdate = \App\customers::find($customer[0]->id);
//            $customerUpdate->first_name = $request['data']['customer']['firstName'];
//            $customerUpdate->last_name = $request['data']['customer']['lastName'];
//            $customerUpdate->document = $request['data']['customer']['document'];
//            $customerUpdate->document_id = $request['data']['customer']['documentId'];
            $customerUpdate->last_name = $request['data']['customer']['lastName'];
            $customerUpdate->second_last_name = $request['data']['customer']['secondLastName'];
            $customerUpdate->address = $request['data']['customer']['address'];
            $customerUpdate->city_id = $request['data']['customer']['city'];
            $customerUpdate->phone = $request['data']['customer']['phone'];
            $customerUpdate->mobile_phone = $request['data']['customer']['mobilePhone'];
            $customerUpdate->email = $request['data']['customer']['email'];
            $customerUpdate->birthdate = $request['data']['customer']['birthdate'];
            $customerUpdate->save();
            $customerId = $customerUpdate->id;
            $customerPhone = substr($customerUpdate->mobile_phone, 1);
            $customerEmail = $customerUpdate->email;
            $customerDocument = $customerUpdate->document;
            $customerSearch = \App\customers::find($customerUpdate->id);
        } else {
            $customerNew = new \App\customers();
            $customerNew->first_name = $request['data']['customer']['firstName'];
            $customerNew->second_name = $request['data']['customer']['secondName'];
            $customerNew->last_name = $request['data']['customer']['lastName'];
            $customerNew->second_last_name = $request['data']['customer']['secondLastName'];
            $customerNew->document = $request['data']['customer']['document'];
            $customerNew->document_id = $request['data']['customer']['documentId'];
            $customerNew->address = $request['data']['customer']['address'];
            $customerNew->city_id = $request['data']['customer']['city'];
            $customerNew->phone = $request['data']['customer']['phone'];
            $customerNew->mobile_phone = $request['data']['customer']['mobilePhone'];
            $customerNew->email = $request['data']['customer']['email'];
            $customerNew->birthdate = $request['data']['customer']['birthdate'];
            $customerNew->status_id = 1;
            $customerNew->save();
            $customerId = $customerNew->id;
            $customerPhone = substr($customerNew->mobile_phone, 1);
            $customerEmail = $customerNew->email;
            $customerDocument = $customerNew->document;
            $customerSearch = \App\customers::find($customerNew->id);
        }
        
        $productChannel = \App\product_channel::where('canal_plan_id','=',$request['data']['product'])->get();

        //Sale Prices Variables
        $sBancos = str_replace("$", "", $request['data']['sBancos']);
        $sCampes = str_replace("$", "", $request['data']['sCampesino']);
        $dEmisio = str_replace("$", "", $request['data']['dEmision']);
        $subTotal = str_replace("$", "", $request['data']['subtotal12']);
        $tax = str_replace("$", "", $request['data']['tax']);
        $total = str_replace("$", "", $request['data']['total']);
        $rate = str_replace("$", "", $request['data']['rate']);

        //DateTime
        $now = new \DateTime();

        //Send Quotation Email Check Box
        if ($request['data']['sendQuotation'] == 'true') {
            $chkBoxSendQuotation = true;
        } else {
            $chkBoxSendQuotation = false;
        }
        
        //Store Sale
        $salesNew = new \App\sales();
        $salesNew->pbc_id = $productChannel[0]->id;
        $salesNew->user_id = \Auth::user()->id;
        $salesNew->customer_id = $customerId;
        $salesNew->status_id = 36;
        $salesNew->emission_date = $now;
        $salesNew->token_date_send = $now;
        $salesNew->subtotal_12 = $request['data']['subtotal12'];
        $salesNew->subtotal_0 = $request['data']['subtotal0'];
        $salesNew->other_discount = 0;
        $salesNew->seguro_campesino = $request['data']['sCampesino'];
        $salesNew->super_bancos = $request['data']['sBancos'];
        $salesNew->derecho_emision = $request['data']['dEmision'];
        $salesNew->tax = $request['data']['tax'];
        $salesNew->total = $request['data']['total'];
        $salesNew->agen_id = \Auth::user()->agen_id;
        $salesNew->cus_mobile_phone = $request['data']['customer']['mobilePhone'];
        $salesNew->cus_phone = $request['data']['customer']['phone'];
        $salesNew->cus_address = $request['data']['customer']['address'];
        $salesNew->cus_email = $request['data']['customer']['email'];
        $salesNew->cus_city = $request['data']['customer']['city'];
        $salesNew->sales_movements_id = $request['data']['saleMovement'];
        $salesNew->sales_id = $request['data']['saleId'];
        $salesNew->sales_type_id = 1;
        $salesNew->chkBoxSendQuotation = $chkBoxSendQuotation;
        $salesNew->prima_total = $request['data']['property']['rubroPrima'];
        $salesNew->insured_value = str_replace(',','',$request['data']['property']['rubroValue']);
        $salesNew->save();
                
        \App\Jobs\listaObservadosyCarteraJobs::dispatch($request['data']['product'], $customerId, $salesNew->id, \Auth::user()->email);
        
        if (isset($request['data']['saleId'])) {
            $salesOld = \App\sales::find($request['data']['saleId']);
            $salesOld->has_been_renewed = 1;
            $salesOld->has_been_renewed_date = $now;
            $salesOld->save(); 
        }

        //Vinculation_form
        $vinculation = new \App\vinculation_form();
        $vinculation->customer_id = $customerId;
        $vinculation->sales_id = $salesNew->id;
        $vinculation->status_id = 6;
        $vinculation->main_road = $request['data']['customer']['address'];
        $vinculation->city_id = $request['data']['customer']['city'];
        $vinculation->phone = $request['data']['customer']['phone'];
        $vinculation->mobile_phone = $request['data']['customer']['mobilePhone'];
        $vinculation->email = $request['data']['customer']['email'];
        $vinculation->birth_date = $request['data']['customer']['birthdate'];
        $vinculation->save(); 

        //Store Charge
//        $charge = new \App\Charge();
//        $charge->sales_id = $salesNew->id;
//        $charge->customers_id = $customerId;
//        $charge->status_id = 8;
//        $charge->value = $salesNew->total;
//        $charge->types_id = 1;
//        $charge->motives_id = 1;
//        $charge->save();
        //Send SMS
        if ($salesNew->sales_type_id == 1) { // INDIVIDUAL SALE - SEND SMS
//        sendSMS($customerPhone, $randomCode, $salesNew->id);
        } else { // REMOTE SALE - SEND LINK
            //sendLinkSMS($customerPhone, $randomCode, $salesNew->id);
        }

        //Store R4
        $propertiesNew = new \App\properties();
        $propertiesNew->sales_id = $salesNew->id;
        $propertiesNew->status_id = 1;
        $propertiesNew->main_street = $request['data']['property']['principal_street'];
        $propertiesNew->secondary_street = $request['data']['property']['secondary_street'];
        $propertiesNew->number = $request['data']['property']['number'];
        $propertiesNew->office_department = $request['data']['property']['aparment'];
        $propertiesNew->city_id = $request['data']['property']['cities'];
        $propertiesNew->save(); 
                
        //Consulta Prima por rubro principal
        $resultPri = calculoPrimaR4($productChannel[0]->canal_plan_id, $rubrosCod[0]->cod, str_replace(',','',$request['data']['property']['rubroValue']), '01/05/2020', '01/05/2021');
        foreach ($resultPri['rubrofactura']['rubros'] as $a) {
            if ($a['rubro'] == 'PRIMA NETA') {
                $primaRubro = $a['valor'];
            }
        }
        
        $rubrosNew = new \App\properties_rubros();
        $rubrosNew->property_id = $propertiesNew->id;
        $rubrosNew->value = str_replace(',','',$request['data']['property']['rubroValue']);
        $rubrosNew->rate = $resultPri['rubrofactura']['tasa'];
        $rubrosNew->prima_value = $primaRubro;
        $rubrosNew->rubros_cod = $rubrosCod[0]->cod;
        $rubrosNew->save();
        
        //Consulta Prima por los rubros adicionales
        $rubroAdd = \App\products_rubros::where('cod', '!=', $rubrosCod[0]->cod)->where('product_id', '=', $productChannel[0]->product_id)->get();

        //Sumar las primas
        foreach($rubroAdd as $r){
            $result = calculoPrimaR4($productChannel[0]->canal_plan_id, $r->cod, $r->value, '01/05/2020', '01/05/2021');

            if($result['error'][0]['code'] == '000'){
                foreach ($result['rubrofactura']['rubros'] as $a) {
                    if ($a['rubro'] == 'PRIMA NETA') {
                        $primaRubro = $a['valor'];
                    }
                }
            }else{
                $primaRubro = '0.00';
            }
            $rubrosNew = new \App\properties_rubros();
            $rubrosNew->property_id = $propertiesNew->id;
            $rubrosNew->value = $result['rubrofactura']['valorasegurado'];
            $rubrosNew->rate = $result['rubrofactura']['tasa'];
            $rubrosNew->prima_value = $primaRubro;
            $rubrosNew->rubros_cod = $r->cod;
            $rubrosNew->save();
        }

        if ($chkBoxSendQuotation == true) { 
            \App\Jobs\QuotationR4EmailJobs::dispatch($salesNew->id, $customerEmail, $customerDocument);
        }
        
        //Consulta Lista Observados y Cartera Vencida SS
        
//        listaObservadosYCarteraFunction($salesNew->id, $customerId, \Auth::user()->email, $request['data']['product']);

        $returnArray = array();
        $returnArray = [
            'productId' => $salesNew->id,
            'validationCode' => $salesNew->token,
            'salId' => $salesNew->id    
        ];

        return $returnArray;
    }

    public function quotation(){
        $salesNew = \App\sales::find(1302);
        $customer = \App\customers::find($salesNew->customer_id);
        $job = (new \App\Jobs\QuotationEmailJobs($salesNew->id, $customer->email, $customer->document));
        dispatch($job);
    }

    public function quotationMail(){
        return view('emails.quotation');
    }

    /*********************** */
    /****  R2 CONTROLLER  ****/
    /*********************** */

    public function R2create() {
        session(['salesCreateLifeInsuredId' => null]);
        session(['saleCreateAsset' => 'Asegurado']);
        session(['salesCreateLifeCustomerId' => null]);

        //Validate Create Permission
        $create = checkExtraPermits('19', \Auth::user()->role_id);
        if (!$create) {
            \Session::flash('ValidateUserRoute', 'No tiene acceso a crear nuevas cotizaciones.');
            return view('home');
        }

        $customer = new \App\customers();

        //Customer Country, Province, City
        $cusCity = new \App\city();
        $cusCityList = false;
        $cusProvince = new \App\province();
        $cusProvinceList = false;
        $cusCountry = new \App\country();
        $cusCountryList = false;

        $disabled = null;

        //Obtain Channel
        $channelQuery = 'select cha.* from channels cha join agencies agen on agen.channel_id = cha.id where agen.id =  "' . \Auth::user()->agen_id . '"';
        $channel = DB::select($channelQuery);

        //Product Data
        $products = DB::select('select pCha.id, pro.name, pro.price, pro.total_price, pro.segment, pro.detail, pro.conditions, pro.exclutions from products pro join products_channel pCha on pCha.product_id = pro.id where pro.status_id = "1" and pCha.channel_id = "' . $channel[0]->id . '" and pro.product_type in ("INDIVIDUAL","AMBOS")');
        $documents = DB::select('select * from documents where id in (1,3)');
        $countries = DB::select('select * from countries where id = 1');
        $gender = \App\gender::find([1, 2]);

        $sale_movement = 1;

        $insuranceBranch = 2;

        return view('sales.R2.create', [
            'products' => $products,
            'documents' => $documents,
            'countries' => $countries,
            'customer' => $customer,
            'disabled' => $disabled,
            'cusCity' => $cusCity,
            'cusProvince' => $cusProvince,
            'cusCountry' => $cusCountry,
            'cusCityList' => $cusCityList,
            'cusProvinceList' => $cusProvinceList,
            'sale_movement' => $sale_movement,
            'sale_id' => null,
            'gender' => $gender,
            'insuranceBranch' => $insuranceBranch,
            'customerId' => null,
            'insuredId' => null
        ]);
    }

    public function R2createPost(request $request) {
        //Validate Create Permission
        $edit = checkExtraPermits('19', \Auth::user()->role_id);
        if (!$edit) {
            \Session::flash('ValidateUserRoute', 'No tiene acceso a crear ventas Individuales.');
            return view('home');
        }
//        $customerID = session(['salesFirstName' => $request->first_name]);
        $customerID = session('salesCreateLifeCustomerId');
        $insuredID = session('salesCreateLifeInsuredId');
        $customer = \App\customers::find($customerID);

        //Customer Country, Province, City
        $cusCity = \App\city::find($customer->city_id);
        $cusCityList = true;
        $cusProvince = \App\province::find($cusCity->province_id);
        $cusProvinceList = true;
        $cusCountry = \App\country::find($cusProvince->country_id);
        $cusCountryList = true;

        $disabled = null;
        $vehiBodyTable = '';

        //Obtain Channel
        $channelQuery = 'select cha.* from channels cha join agencies agen on agen.channel_id = cha.id where agen.id =  "' . \Auth::user()->agen_id . '"';
        $channel = DB::select($channelQuery);

        //Product Data
        $products = DB::select('select pCha.id, pro.name, pro.price, pro.total_price, pro.segment, pro.detail, pro.conditions, pro.exclutions from products pro join products_channel pCha on pCha.product_id = pro.id where pro.status_id = "1" and pCha.channel_id = "' . $channel[0]->id . '" and pro.product_type in ("INDIVIDUAL","AMBOS")');
        $documents = DB::select('select * from documents where id in (1,3)');
        $countries = DB::select('select * from countries');
        $gender = \App\gender::find([1, 2]);

        $sale_movement = 1;

        $insuranceBranch = 2;

        return view('sales.customer', [
            'products' => $products,
            'documents' => $documents,
            'countries' => $countries,
            'customer' => $customer,
            'disabled' => $disabled,
            'cusCity' => $cusCity,
            'cusProvince' => $cusProvince,
            'cusCountry' => $cusCountry,
            'cusCityList' => $cusCityList,
            'cusProvinceList' => $cusProvinceList,
            'vehiBodyTable' => $vehiBodyTable,
            'sale_movement' => $sale_movement,
            'sale_id' => null,
            'gender' => $gender,
            'insuranceBranch' => $insuranceBranch,
            'customerId' => $customerID,
            'insuredId' => $insuredID
        ]);
    }

    public function R2CheckPrice(request $request) {
//        $agency = \App\Agency::find(\Auth::user()->agen_id);
//        $products = \App\products::where('agency_id','=', $agency->id)->get();
//
//        $returnData = '';
//        $date = new \DateTime();
//        $today = $date->format('d-m-Y');
//        $oneYear = date('d-m-Y', strtotime('+1 years'));
        $agency = \App\Agency::find(\Auth::user()->agen_id);
        $count_products = 0;

        $returnData = '';
        $date = new \DateTime();
        $today = $date->format('d-m-Y');
        $oneYear = date('d-m-Y', strtotime('+1 years'));
        $products = \App\product_channel::selectRaw('products_channel.*, pro.productodes')
                                            ->join('products as pro','pro.id','=','products_channel.product_id')
                                            ->where('products_channel.agency_id','=',$agency->id)
//                                            ->where('products_channel.channel_id', '=', $agency->channel_id)
                                            ->whereIn('pro.ramoid',array(1,2))
                                            ->get();
        if($products->isEmpty()){
            $returnData .= '<div id="customerAlert" class="alert alert-warning registerForm titleDivBorderTop" style="margin-top:5px;border-radius:0px !important;">
                            <center><strong>¡Alerta!</strong> Por favor tome contacto con Seguros Sucre para poder recibir una tarifa preferencial para el Vehículo </center>
                            </div>';
            return $returnData;
            
        }else{
            foreach ($products as $pro) {
                $productsChannel = \App\product_channel::where('canal_plan_id', '=', $pro->canal_plan_id)->get();
                $agencySS = \App\agencia_ss::find($productsChannel[0]->agency_ss_id);
                $prima = 0;
                $contribucion = 0;
                $sCam = 0;
                $derEmision = 0;
                $subSinIva = 0;
                $subConIva = 0;
                $iva = 0;
                $total = 0;

                //Consulta Prima por cada vehiculo
                $result = calculoPrimaR2($pro->canal_plan_id, $today, $oneYear);

                if ($result['error'][0]['code'] != '000') {
//                    $returnData .=  '<div id="customerAlert" class="alert alert-warning registerForm titleDivBorderTop" style="margin-top:5px;border-radius:0px !important;">
//                                    <center><strong>¡Alerta!</strong> Por favor tome contacto con Seguros Sucre para poder recibir una tarifa preferencial para vida </center>
//                                    </div>';
                   
                }else{
                    foreach ($result['rubrofactura']['rubros'] as $a) {
                        if ($a['rubro'] == 'PRIMA NETA') {
                            $prima += $a['valor'];
                        }
                        if ($a['rubro'] == 'CONTRIBUCIÓN') {
                            $contribucion += $a['valor'];
                        }
                        if ($a['rubro'] == 'S. SOCIAL CAMPESINO') {
                            $sCam += $a['valor'];
                        }
                        if ($a['rubro'] == 'DERECHO DE EMISION') {
                            $derEmision += $a['valor'];
                        }
                        if ($a['rubro'] == 'SUBTOTAL TARIFA IVA' && $a['simbolo'] == '0.00%') {
                            $subSinIva += $a['valor'];
                        }
                        if ($a['rubro'] == 'SUBTOTAL TARIFA IVA' && $a['simbolo'] == '12.00%') {
                            $subConIva += $a['valor'];
                        }
                        if ($a['rubro'] == 'I.V.A') {
                            $iva += $a['valor'];
                        }
                        if ($a['rubro'] == 'TOTAL') {
                            $total += $a['valor'];
                        }
                        $count_products++;
                    }

                    $coverage = \App\coverage::where('product_id', '=', $pro->product_id)->where('plandetindvis', '=', 'S')->where('coberturades', '!=', NULL)->skip(0)->take(4)->get();
                    $agent = \App\agent_ss::find($productsChannel[0]->agent_ss);

                    $returnData .= '<div class="pricing-table">
                                    <h3 class="pricing-title">' . $pro->productodes . '</h3>
                                    <div class="price">$' . $prima . '<sup>/ año</sup></div>
                                    <!-- Lista de Caracteristicas / Propiedades -->
                                    <table>';
                    foreach ($coverage as $cov) {

                        $valorasegurado = $cov->valorasegurado == 0.00 ? $valorasegurado = '<span class="glyphicon glyphicon-ok" style="color:green;font-size:10px"></span>' :$valorasegurado = '$ ' . $cov->valorasegurado;

                        $returnData .= '<tr>
                                            <td align="left" width="60%">' . $cov->coberturades . '</td>
                                            <td align="right" width="40%">' . $valorasegurado . '</td>
                                        </tr>';
                    }
                    $returnData .= '</table>
                                    <!-- Contratar / Comprar -->
                                    <div class="table-buy">
                                        <a href="#" onclick="openProductModal(' . $pro->product_id . ', ' . $prima . ')" style="font-size:14px">Ver Condiciones</a>
                                        <a id="productModalBtn" href="#" data-toggle="modal" data-target="#productModal"></a>
                                        <br><br>
                                        <a href="#" class="pricing-action" onclick="secondStepBtnNext(\'' . $pro->canal_plan_id . '\', \'' . $today . '\',\'' . $oneYear . '\',\'' . $agencySS->agenciaid . '\',\'VIDA\', \'' . $prima . '\', \'' . $contribucion . '\', \'' . $sCam . '\', \'' . $derEmision . '\', \'' . $subSinIva . '\', \'' . $subConIva . '\', \'' . $iva . '\',\'' . $total . '\',\'' . $pro->product_id . '\')">Seleccionar</a>
                                    </div>
                                </div>';
                    
                }

            }
            
        }
        
        return $returnData;
    }

    public function R2CheckConditions(request $request){
        $returnData = '';
        $product = \App\products::find($request['productId']);
        
        //MODAL HEADER
        $returnData .= '<div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <!--<h4 class="modal-title">Condiciones y Beneficios</h4>-->
                                            <div class="col-md-12 border" style="font-weight:bold;font-size: 40px;box-shadow: 1px 1px 1px #999;background: #104272; color:white;">
                                                <div id="modalVehicleBasic" class="col-md-4" style="text-align:center; font-size: 22px; height:70px; border-right: 1px solid white;">
                                                    '.$product->ramodes.'
                                                </div>
                                                <div id="modalProductBasic" class="col-md-4" style="text-align:center;font-size: 22px;height:70px; border-right: 1px solid white;">
                                                    '.$product->productodes.'
                                                </div>
                                                <div id="modalPriceBasic" class="col-md-4" style="text-align:center;font-size: 22px;height:70px;">
                                                    $'.$request['prima'].' anuales
                                                </div>
                                            </div>
                                        </div>';
        //COVERAGE
        $coverage = \App\coverage::where('product_id','=',$request['productId'])->where('plandetindvis','=','S')->get();
        if (count($coverage) > 0) {
            $returnData .= '<div class="modal-body" style="color:#104272;">
                                <div class="col-md-12" style="background: #ddd">';
            foreach($coverage as $cov){
                $valorasegurado = $cov->valorasegurado == 0.00 ? $valorasegurado = '<span class="glyphicon glyphicon-ok" style="color:green;font-size:10px"></span>' :$valorasegurado = '$ ' . $cov->valorasegurado;

 

                $returnData .= $cov->texto;
            }
            $returnData .= ' </div>';
        }
        $returnData .= '
                    </div>
                    </div>
                    <div class="modal-footer">
                        </div>';
        return $returnData;
    }

    public function R2ResumeNewSS(request $request) {
//        return $request;
        $prima = number_format((float)$request['data']['prima'], 2, '.', '');
        $contribucion = number_format((float)$request['data']['contribucion'], 2, '.', '');
        $sCam = number_format((float)$request['data']['sCam'], 2, '.', '');
        $derEmision = number_format((float)$request['data']['derEmision'], 2, '.', '');
        $subSinIva = number_format((float)$request['data']['subSinIva'], 2, '.', '');
        $subConIva = number_format((float)$request['data']['subConIva'], 2, '.', '');
        $iva = number_format((float)$request['data']['iva'], 2, '.', '');
        $total = number_format((float)$request['data']['total'], 2, '.', '');
//        number_format((float)$number, 2, '.', '');
    
        $product = \App\products::find($request['data']['proId']);

        //Vehicle Table Resume
        $R2Table = '<tr align="center">
                            <td>' . $product->productodes . '</td>
                            <td style="text-align:right">$' . $prima . '</td>
                        </tr>';
        $arrayResponse = array();
        array_push($arrayResponse, $R2Table);

        $taxTable = '<tr align="center">
                        <th style="text-align:right;">S. de Compañias (3.5%)</th>
                        <td style="width:40%;text-align:right">$' . $contribucion . '</td>
                        </tr>
                        <input type="hidden" id="sBancos" name="sBancos" value="'.$contribucion.'">
                        <tr align="center">
                        <th style="text-align:right;">S. Campesino (0.5%)</th>
                        <td style="width:40%;text-align:right">$' . $sCam . '</td>
                        </tr>
                        <input type="hidden" id="sCampesino" name="sCampesino" value="'.$sCam.'">
                        <tr align="center">
                        <th style="text-align:right;">Derechos de Emisión</th>
                        <td style="width:40%;text-align:right">$' . $derEmision . '</td>
                        </tr>
                        <input type="hidden" id="dEmision" name="dEmision" value="'.$derEmision.'">
                        <tr align="center">
                        <th style="text-align:right;">Subtotal 12%</th>
                        <td style="width:40%;text-align:right">$'.$subConIva.'</td>
                        <input type="hidden" id="subtotal12" name="subtotal12" value="'.$subConIva.'">
                        </tr>
                        <tr align="center">
                        <th style="text-align:right;">Subtotal 0%</th>
                        <td style="width:40%;text-align:right">$' . $subSinIva . '</td>
                        </tr>
                        <input type="hidden" id="subtotal0" name="subtotal0" value="'.$subSinIva.'">
                        <tr align="center">
                        <th style="text-align:right;">IVA</th>
                        <td style="width:40%;text-align:right">$' . $iva . '</td>
                        </tr>
                        <input type="hidden" id="tax" name="tax" value="'.$iva.'">
                        <tr align="center">
                        <th style="background-color:#b3b0b0; text-align:right;">Total</th>
                        <td style="background-color:#b3b0b0;width:40%;text-align:right">$' . $total . '</td>
                        </tr>
                        <input type="hidden" id="total" name="total" value="'.$total.'">
                        <input type="hidden" id="rate" name="rate" value="15">';

        array_push($arrayResponse, $taxTable);
        
        //Customer Variable
        $customer = \App\customers::find(session('salesCreateLifeCustomerId'));
        $customer = 35;
        array_push($arrayResponse, $customer);
        return $arrayResponse;
    }

    public function R2Store(request $request) {
        set_time_limit(120);
        //Save or Update Customer
        $customerSql = 'select * from customers where document = "' . $request['data']['customer']['document'] . '"';
        $customer = DB::select($customerSql);

        //Validate Customer Save or Update
        if ($customer) {
            $customerUpdate = \App\customers::find($customer[0]->id);
//            $customerUpdate->first_name = $request['data']['customer']['firstName'];
//            $customerUpdate->last_name = $request['data']['customer']['lastName'];
//            $customerUpdate->document = $request['data']['customer']['document'];
//            $customerUpdate->document_id = $request['data']['customer']['documentId'];
            $customerUpdate->last_name = $request['data']['customer']['lastName'];
            $customerUpdate->second_last_name = $request['data']['customer']['secondLastName'];
            $customerUpdate->address = $request['data']['customer']['address'];
            $customerUpdate->city_id = $request['data']['customer']['city'];
            $customerUpdate->phone = $request['data']['customer']['phone'];
            $customerUpdate->mobile_phone = $request['data']['customer']['mobilePhone'];
            $customerUpdate->email = $request['data']['customer']['email'];
            $customerUpdate->birthdate = $request['data']['customer']['birthdate'];
            $customerUpdate->save();
            $customerId = $customerUpdate->id;
            $customerPhone = substr($customerUpdate->mobile_phone, 1);
            $customerEmail = $customerUpdate->email;
            $customerDocument = $customerUpdate->document;
            $customerSearch = \App\customers::find($customerUpdate->id);
        } else {
            $customerNew = new \App\customers();
            $customerNew->first_name = $request['data']['customer']['firstName'];
            $customerNew->second_name = $request['data']['customer']['secondName'];
            $customerNew->last_name = $request['data']['customer']['lastName'];
            $customerNew->second_last_name = $request['data']['customer']['secondLastName'];
            $customerNew->document = $request['data']['customer']['document'];
            $customerNew->document_id = $request['data']['customer']['documentId'];
            $customerNew->address = $request['data']['customer']['address'];
            $customerNew->city_id = $request['data']['customer']['city'];
            $customerNew->phone = $request['data']['customer']['phone'];
            $customerNew->mobile_phone = $request['data']['customer']['mobilePhone'];
            $customerNew->email = $request['data']['customer']['email'];
            $customerNew->birthdate = $request['data']['customer']['birthdate'];
            $customerNew->status_id = 1;
            $customerNew->save();
            $customerId = $customerNew->id;
            $customerPhone = substr($customerNew->mobile_phone, 1);
            $customerEmail = $customerNew->email;
            $customerDocument = $customerNew->document;
            $customerSearch = \App\customers::find($customerNew->id);
        }
        
        $productChannel = \App\product_channel::where('canal_plan_id','=',$request['data']['product'])->get();

        //DateTime
        $now = new \DateTime();

        //Send Quotation Email Check Box
        if ($request['data']['sendQuotation'] == 'true') {
            $chkBoxSendQuotation = true;
        } else {
            $chkBoxSendQuotation = false;
        }
        
        //Calculo Prima
        $date = new \DateTime();
        $today = $date->format('d-m-Y');
        $oneYear = date('d-m-Y', strtotime('+1 years'));
        $result = calculoPrimaR2($productChannel[0]->canal_plan_id, $today, $oneYear);
        
        $coverage = \App\coverage::where('coberturades','=','MUERTE')->where('product_id','=',$productChannel[0]->product_id)->get();
        if($coverage->isEmpty()){
            $coverageValorAsegurado = 0.00;
        }else{
            $coverageValorAsegurado = $coverage[0]->valorasegurado;
        }
        //Store Sale
        $salesNew = new \App\sales();
        $salesNew->pbc_id = $productChannel[0]->id;
        $salesNew->user_id = \Auth::user()->id;
        $salesNew->customer_id = $customerId;
        $salesNew->status_id = 36;
        $salesNew->emission_date = $now;
        $salesNew->token_date_send = $now;
        $salesNew->subtotal_0 = $request['data']['subtotal0'];
        $salesNew->subtotal_12 = $request['data']['subtotal12'];
        $salesNew->other_discount = 0;
        $salesNew->seguro_campesino = $request['data']['sCampesino'];
        $salesNew->super_bancos = $request['data']['sBanco'];
        $salesNew->derecho_emision = $request['data']['dEmision'];
        $salesNew->tax = $request['data']['tax'];
        $salesNew->total = $request['data']['total'];
        $salesNew->agen_id = \Auth::user()->agen_id;
        $salesNew->cus_mobile_phone = $request['data']['customer']['mobilePhone'];
        $salesNew->cus_phone = $request['data']['customer']['phone'];
        $salesNew->cus_address = $request['data']['customer']['address'];
        $salesNew->cus_email = $request['data']['customer']['email'];
        $salesNew->cus_city = $request['data']['customer']['city'];
        $salesNew->sales_movements_id = $request['data']['saleMovement'];
        $salesNew->sales_id = $request['data']['saleId'];
        $salesNew->sales_type_id = 1;
        $salesNew->chkBoxSendQuotation = $chkBoxSendQuotation;
        $salesNew->rate = $result['rubrofactura']['tasa'];
        $salesNew->prima_total = $result['rubrofactura']['rubros'][0]['valor'];
        $salesNew->insured_value = $coverageValorAsegurado;
        $salesNew->save();
                
        \App\Jobs\listaObservadosyCarteraJobs::dispatch($request['data']['product'], $customerId, $salesNew->id, \Auth::user()->email);
        
        if (isset($request['data']['saleId'])) {
            $salesOld = \App\sales::find($request['data']['saleId']);
            $salesOld->has_been_renewed = 1;
            $salesOld->has_been_renewed_date = $now;
            $salesOld->save(); 
        }

        //Vinculation_form
        $vinculation = new \App\vinculation_form();
        $vinculation->customer_id = $customerId;
        $vinculation->sales_id = $salesNew->id;
        $vinculation->status_id = 6;
        $vinculation->main_road = $request['data']['customer']['address'];
        $vinculation->city_id = $request['data']['customer']['city'];
        $vinculation->phone = $request['data']['customer']['phone'];
        $vinculation->mobile_phone = $request['data']['customer']['mobilePhone'];
        $vinculation->email = $request['data']['customer']['email'];
        $vinculation->birth_date = $request['data']['customer']['birthdate'];
        $vinculation->save(); 

        if ($chkBoxSendQuotation == true) {
            $job = (new \App\Jobs\QuotationR2EmailJobs($salesNew->id, $customerEmail, $customerDocument));
            dispatch($job);
        }

        //Store Charge
//        $charge = new \App\Charge();
//        $charge->sales_id = $salesNew->id;
//        $charge->customers_id = $customerId;
//        $charge->status_id = 8;
//        $charge->value = $salesNew->total;
//        $charge->types_id = 1;
//        $charge->motives_id = 1;
//        $charge->save();
        //Send SMS
        if ($salesNew->sales_type_id == 1) { // INDIVIDUAL SALE - SEND SMS
//        sendSMS($customerPhone, $randomCode, $salesNew->id);
        } else { // REMOTE SALE - SEND LINK
            //sendLinkSMS($customerPhone, $randomCode, $salesNew->id);
        }
        //Store R2
        
//        listaObservadosYCarteraFunction($salesNew->id, $customerId, \Auth::user()->email, $request['data']['product']);

        $returnArray = array();
        $returnArray = [
            'productId' => $salesNew->id,
            'validationCode' => $salesNew->token,
            'salId' => $salesNew->id
        ];

        return $returnArray;
    }
    
    public function checkDocumentEndoso(request $request){
        $result = customerSS('R',$request['document']);
        return $result;
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
        
//        $insuranceApplication 
        
        return view('sales.R2.insuranceApplication',[
            'customer' => $customer,
            'sales' => $sale,
            'cityUser' => $cityUser,
            'provinceUser' => $provinceUser,
            'countryUser' => $countryUser,
            'documentUser' => $documentUser,
            'agentSS' => $agentSS
        ]);
    }

    public function R2beneficiariesRequestSendLink() {
        return view('sales.R2.beneficiariesRequestSendLink');
    }

    public function pdfA() {
        $pdf = PDF::loadView('sales.R2.pdf_insuranceApplication');
        return $pdf->stream('magnus.pdf');
    }
    
    public function validateListaObservadosyCartera(request $request){
        $sales = \App\sales::find($request['saleId']);
        if($sales->status_id == 24 || $sales->status_id == 31 || $sales->status_id == 32 || $sales->status_id == 33){
            \Session::flash('errorMessage', ' El proceso de venta no puede continuar, para información adicional por favor contactar a tu ejecutivo comercial.');

            return 'true';
        }else{
            return 'false';
        }
    }

    public function showDocuments($id){
        echo file_get_contents('http://172.16.101.87:9002/integrador/integradocs/fedoc/fexmlautorizado/FAC0990064474001017900000077323Aut.xml');
//        echo "<iframe src=\"https://filesmagnusmas.s3.amazonaws.com/Images/Vinculation/1618/965094851_Sri.pdf\" width=\"100%\" style=\"height:100%\"></iframe>";
    }
}



