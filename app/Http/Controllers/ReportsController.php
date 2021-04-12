<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\CoVehicleExport;
use App\Exports\CoVidaApExport;
use App\Exports\TeVehicleExport;
use App\Exports\TeVidaApExport;
use App\Exports\walletReport;
use App\Exports\benefitsUseReports;
use App\Exports\benefitsDetailReports;
use App\Exports\customersReports;
use App\Exports\cancelMotivesReports;
use App\Exports\vehiclesReport;
use App\Exports\schedulingReports;
use App\Exports\schedulingCancelMotivesReports;
use App\Exports\schedulingDetailReports;
use App\Exports\schedulingTimeReports;
use App\Exports\massivesDetailReports;
use App\Exports\massivesGlobalReports;
use Maatwebsite\Excel\Facades\Excel;
use DB;

class ReportsController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('validateUserRoute');
    }
    
    public function CovehiclesReportIndex(){
        //Validate if User has view Permit
        $viewPermit = checkViewPermit('36', \Auth::user()->role_id);
        if(!$viewPermit){
            \Session::flash('ValidateUserRoute', 'No tiene acceso al Reporte.');
            return view('home');
        }

        //Obtain Agent
        $agents = \App\agent_ss::where('agentedes','!=',NULL)->where('agentedes','!=','')->where('status_id','=','1')->get();
       
        //Obtain Agencys sucre
        $agencyss = \App\agencia_ss::where('agenciades','!=',NULL)->where('agenciades','!=','')->get();

        //Obtain provinces
        $provincies = \App\province::all();

        //Obtain payments types
        $paymentsTypes = \App\paymentTypes::where('id', 4)->get();

        //Obtain vechicle brands
        $vehicleBrands = \App\vehiclesBrands::all();

        //Obtain ramo status
        $status = \App\status::find([4, 9, 10, 12, 20, 21, 22, 23,24,25, 26,26, 28,29, 30, 31, 32, 33, 34, 35]);

        //Obtain producto
        $producutsQuery = 'SELECT DISTINCT(productoid) as id, productodes FROM products where ramoid in (7) and status_id in (1)';        
        $products = DB::select($producutsQuery);
        
        //Obtain ramos
        $ramosQuery = 'SELECT DISTINCT(ramoid) as id, ramodes as name FROM products';        
        $ramos = DB::select($ramosQuery);

        return view('reports.CovehiclesIndex', [
            "agents" => $agents,
            "agencyss" => $agencyss,
            "provincies" => $provincies,
            "paymentsTypes" => $paymentsTypes,
            "vehicleBrands" => $vehicleBrands,
            "status" => $status,
            "ramos" => $ramos,
            "products" => $products
        ]);
    }
    
    public function CovehiclesReport(request $request){
        $sql = '';
        $sqlSale = '';

        //Validate Country
        if($request['agent'] != null){
            $sql .= ' and gs.id = "'.$request['agent'].'"';
        }
        if($request['channel'] != null){
            $sql .= ' and c.id = "'.$request['channel'].'"';
        }
        if($request['agency'] != null){
            $sql .= ' and a.id = "'.$request['agency'].'"';
        }
        if($request['ejecutivo_ss'] != null){
            $sql .= ' and gs.ejecutivo_ss = "'.$request['ejecutivo_ss'].'"';
        }
        if($request['beginDate'] != null){
            $sql .= ' and DATE_FORMAT(s.emission_date, "%Y-%m-%d") BETWEEN "'.$request['beginDate'].'" AND "'.$request['endDate'].'"';
        }        
        if($request['type_policy'] != null){
            $sql .= '';
        }
        if($request['ramo'] != null){
            $sql .= ' and p2.ramoid = "'.$request['ramo'].'"';
        }
        if($request['product'] != null){
            $sql .= ' and p2.productoid = "'.$request['product'].'"';
        }
        if($request['state'] != null){
            $sql .= ' and s.status_id = "'.$request['state'].'"';
        }
        if($request['sale_id'] != null){
            $sql .= ' and s.id = "'.$request['sale_id'].'"';
        }
        if($request['province'] != null){
            $sql .= ' and pro.id = "'.$request['province'].'"';
        }
        if($request['city'] != null){
            $sql .= ' and ci.id = "'.$request['city'].'"';
        }
        if($request['paymenttype'] != null){
            $sql .= ' and pt.id = "'.$request['paymenttype'].'"';
        }
        if($request['brand'] != null){
            $sql .= ' and vb.id = "'.$request['brand'].'"';
        }

        return Excel::download(new CoVehicleExport($sql), 'Vehículos.xlsx');
    }

    public function CovdapReportIndex(){
        //Validate if User has view Permit
        $viewPermit = checkViewPermit('36', \Auth::user()->role_id);
        if(!$viewPermit){
            \Session::flash('ValidateUserRoute', 'No tiene acceso al Reporte.');
            return view('home');
        }

        //Obtain Agent
        $agents = \App\agent_ss::where('agentedes','!=',NULL)->where('agentedes','!=','')->where('status_id','=','1')->get();
       
        //Obtain Agencys sucre
        $agencyss = \App\agencia_ss::where('agenciades','!=',NULL)->where('agenciades','!=','')->get();

        //Obtain provinces
        $provincies = \App\province::all();

        //Obtain payments types
        $paymentsTypes = \App\paymentTypes::where('id', 4)->get();

        //Obtain ramo status
        $status = \App\status::find([4, 9, 10, 12, 20, 21, 22, 23,24,25, 26,26, 28,29, 30, 31, 32, 33, 34, 35]);

        //Obtain ramos
        $ramosQuery = 'SELECT DISTINCT(ramoid) as id, ramodes as name FROM products WHERE ramoid IN (1,4,40)';        
        $ramos = DB::select($ramosQuery);

        return view('reports.CovdapIndex', [
            "agents" => $agents,
            "agencyss" => $agencyss,
            "provincies" => $provincies,
            "paymentsTypes" => $paymentsTypes,
            "status" => $status,
            "ramos" => $ramos
        ]);
    }
    
    public function CovdapReport(request $request){
        $sql = '';
        
        //Validate Country
        if($request['agent'] != null){
            $sql .= ' and gs.id = "'.$request['agent'].'"';
        }
        if($request['channel'] != null){
            $sql .= ' and c.id = "'.$request['channel'].'"';
        }
        if($request['agency'] != null){
            $sql .= ' and a.id = "'.$request['agency'].'"';
        }
        if($request['ejecutivo_ss'] != null){
            $sql .= ' and gs.ejecutivo_ss = "'.$request['ejecutivo_ss'].'"';
        }
        if($request['beginDate'] != null){
            $sql .= ' and DATE_FORMAT(s.emission_date, "%Y-%m-%d") BETWEEN "'.$request['beginDate'].'" AND "'.$request['endDate'].'"';
        }       
        if($request['type_policy'] != null){
            $sql .= '';
        }
        if($request['ramov'] != null){
            $sql .= ' and p2.ramoid = "'.$request['ramov'].'"';
        }
        if($request['state'] != null){
            $sql .= '  and s.status_id =  "'.$request['state'].'"';
        }
        if($request['sale_id'] != null){
            $sql .= ' and s.id = "'.$request['sale_id'].'"';
        }
        if($request['province'] != null){
            $sql .= ' and pro.province_id = "'.$request['province'].'"';
        }
        if($request['city'] != null){
            $sql .= ' and ci.id = "'.$request['city'].'"';
        }
        if($request['paymenttype'] != null){
            $sql .= ' and pt.id = "'.$request['paymenttype'].'"';
        }
        if($request['brand'] != null){
            $sql .= ' and vb.id = "'.$request['brand'].'"';
        }

        return Excel::download(new CoVidaApExport($sql), 'Vida_AP.xlsx');
    }

       
    public function TevehiclesReportIndex(){
        //Validate if User has view Permit
        $viewPermit = checkViewPermit('36', \Auth::user()->role_id);
        if(!$viewPermit){
            \Session::flash('ValidateUserRoute', 'No tiene acceso al Reporte.');
            return view('home');
        }

        //Obtain Agent
        $agents = \App\agent_ss::where('agentedes','!=',NULL)->where('agentedes','!=','')->where('status_id','=','1')->get();
       
        //Obtain Agencys sucre
        $agencyss = \App\agencia_ss::where('agenciades','!=',NULL)->where('agenciades','!=','')->get();

        //Obtain provinces
        $provincies = \App\province::all();

        //Obtain payments types
        $paymentsTypes = \App\paymentTypes::where('id', 4)->get();

        //Obtain vechicle brands
        $vehicleBrands = \App\vehiclesBrands::all();

        //Obtain ramo status
        $status = \App\status::find([4, 9, 10, 12, 20, 21, 22, 23,24,25, 26,26, 28,29, 30, 31, 32, 33, 34, 35]);

        //Obtain producto
        $products = \App\products::selectRaw('DISTINCT(products.productoid), products.id, products.productodes as "name"')
                                           ->join('products_channel as pbc','pbc.product_id','=','products.id')
                                            ->where('products.ramoid', 7)
                                           ->where('pbc.status_id','=','1')
                                            ->groupBy('products.productoid')
                                            ->get();
//        dd($products);
        //Obtain uso vehiculos
        $uses = \App\vehicles_type::all();

        //Obtain ramos
        $ramosQuery = 'SELECT DISTINCT(ramoid) as id, ramodes as name FROM products';        
        $ramos = DB::select($ramosQuery);

        return view('reports.TevehiclesIndex', [
            "agents" => $agents,
            "agencyss" => $agencyss,
            "provincies" => $provincies,
            "paymentsTypes" => $paymentsTypes,
            "vehicleBrands" => $vehicleBrands,
            "status" => $status,
            "ramos" => $ramos,
            "products" => $products,
            "uses" => $uses
        ]);
    }
    
    public function TevehiclesReport(request $request){
        $sql = '';

        //Validate Country
        if($request['agent'] != null){
            $sql .= ' and gs.id = "'.$request['agent'].'"';
        }
        if($request['channel'] != null){
            $sql .= ' and c.id = "'.$request['channel'].'"';
        }
        if($request['agency'] != null){
            $sql .= ' and a.id = "'.$request['agency'].'"';
        }
        if($request['ejecutivo_ss'] != null){
            $sql .= ' and gs.ejecutivo_ss = "'.$request['ejecutivo_ss'].'"';
        }
        if($request['beginDate'] != null){
            $sql .= ' and DATE_FORMAT(s.emission_date, "%Y-%m-%d") BETWEEN "'.$request['beginDate'].'" AND "'.$request['endDate'].'"';
        }         
        if($request['type_policy'] != null){
            $sql .= '';
        }
        if($request['ramo'] != null){
            $sql .= ' and p2.ramoid = "'.$request['ramo'].'"';
        }
        if($request['product'] != null){
            $sql .= ' and p2.productoid = "'.$request['product'].'"';
        }
        if($request['state'] != null){
            $sql .= ' and s.status_id = "'.$request['state'].'"';
        }
        if($request['sale_id'] != null){
            $sql .= ' and s.id = "'.$request['sale_id'].'"';
        }
        if($request['province'] != null){
            $sql .= ' and pro.province_id = "'.$request['province'].'"';
        }
        if($request['city'] != null){
            $sql .= ' and ci.id = "'.$request['city'].'"';
        }
        if($request['paymenttype'] != null){
            $sql .= ' and pt.id = "'.$request['paymenttype'].'"';
        }
        if($request['brand'] != null){
            $sql .= ' and vb.id = "'.$request['brand'].'"';
        }
        if($request['uses'] != null){
            $sql .= ' and vt.id = "'.$request['uses'].'"';
        }

        return Excel::download(new TeVehicleExport($sql), 'Vehículos.xlsx');
    }

    public function TevdapReportIndex(){
        //Validate if User has view Permit
        $viewPermit = checkViewPermit('36', \Auth::user()->role_id);
        if(!$viewPermit){
            \Session::flash('ValidateUserRoute', 'No tiene acceso al Reporte.');
            return view('home');
        }

        //Obtain Agent
        $agents = \App\agent_ss::where('agentedes','!=',NULL)->where('agentedes','!=','')->where('status_id','=','1')->get();
       
        //Obtain Agencys sucre
        $agencyss = \App\agencia_ss::where('agenciades','!=',NULL)->where('agenciades','!=','')->get();

        //Obtain provinces
        $provincies = \App\province::all();

        //Obtain payments types
        $paymentsTypes = \App\paymentTypes::where('id', 4)->get();
        
        //Obtain producto
        $products = \App\products::selectRaw('DISTINCT(products.productoid), products.id, products.productodes as "name"')->where('ramoid', '!=', 7)->groupBy('products.productoid')->get();

        //Obtain ramo status
        $status = \App\status::find([4, 9, 10, 12, 20, 21, 22, 23,24,25, 26,26, 28,29, 30, 31, 32, 33, 34, 35]);

        //Obtain ramos
        $ramosQuery = 'SELECT DISTINCT(ramoid) as id, ramodes as name FROM products WHERE ramoid IN (1,4,40)';        
        $ramos = DB::select($ramosQuery);

        return view('reports.TevdapIndex', [
            "agents" => $agents,
            "agencyss" => $agencyss,
            "provincies" => $provincies,
            "paymentsTypes" => $paymentsTypes,
            "status" => $status,
            "products" => $products,
            "ramos" => $ramos
        ]);
    }
    
    public function TevdapReport(request $request){
        $sql = '';
        
        //Validate Country
        if($request['agent'] != null){
            $sql .= ' and gs.id = "'.$request['agent'].'"';
        }
        if($request['channel'] != null){
            $sql .= ' and c.id = "'.$request['channel'].'"';
        }
        if($request['agency'] != null){
            $sql .= ' and a.id = "'.$request['agency'].'"';
        }
        if($request['ejecutivo_ss'] != null){
            $sql .= ' and gs.ejecutivo_ss = "'.$request['ejecutivo_ss'].'"';
        }
        if($request['beginDate'] != null){
            $sql .= ' and DATE_FORMAT(s.emission_date, "%Y-%m-%d") BETWEEN "'.$request['beginDate'].'" AND "'.$request['endDate'].'"';
        }      
        if($request['type_policy'] != null){
            $sql .= ' and c.id = "'.$request['type_policy'].'"';
        }
        if($request['ramo'] != null){
            $sql .= ' and p2.ramoid = "'.$request['ramo'].'"';
        }
        if($request['state'] != null){
            $sql .= ' and st.id = "'.$request['state'].'"';
        }
        if($request['sale_id'] != null){
            $sql .= ' and s.id = "'.$request['sale_id'].'"';
        }
        if($request['province'] != null){
            $sql .= ' and c.province_id = "'.$request['province'].'"';
        }
        if($request['city'] != null){
            $sql .= ' and c.id = "'.$request['city'].'"';
        }
        if($request['paymenttype'] != null){
            $sql .= ' and pt.id = "'.$request['paymenttype'].'"';
        }
        if($request['brand'] != null){
            $sql .= ' and vb.id = "'.$request['brand'].'"';
        }

        return Excel::download(new TeVidaApExport($sql), 'Vida_AP.xlsx');
    }

    public function walletReportIndex(){
        //Validate if User has view Permit
        $viewPermit = checkViewPermit('36', \Auth::user()->role_id);
        if(!$viewPermit){
            \Session::flash('ValidateUserRoute', 'No tiene acceso al Reporte.');
            return view('home');
        }

        //Obtain Agent
        $agents = \App\agent_ss::where('agentedes','!=',NULL)->where('agentedes','!=','')->where('status_id','=','1')->get();
       
        //Obtain Agencys sucre
        $agencyss = \App\agencia_ss::where('agenciades','!=',NULL)->where('agenciades','!=','')->get();

        //Obtain provinces
        $provincies = \App\province::all();

        //Obtain payments types
        $paymentsTypes = \App\paymentTypes::where('id', 4)->get();

        //Obtain ramo status
        $status = \App\status::find([4, 9, 10, 12, 20, 21, 22, 23,24,25, 26,26, 28,29, 30, 31, 32, 33, 34, 35]);

        //Obtain ramos
        $ramosQuery = 'SELECT DISTINCT(ramoid) as id, ramodes as name FROM products WHERE ramoid IN (1,4,7, 40)';        
        $ramos = DB::select($ramosQuery);

        return view('reports.walletIndex', [
            "agents" => $agents,
            "agencyss" => $agencyss,
            "provincies" => $provincies,
            "paymentsTypes" => $paymentsTypes,
            "status" => $status,
            "ramos" => $ramos
        ]);
    }
    
    public function walletReport(request $request){
        $sql = '';
        
        //Validate Country
        if($request['agent'] != null){
            $sql .= ' and gs.id = "'.$request['agent'].'"';
        }
        if($request['channel'] != null){
            $sql .= ' and c.id = "'.$request['channel'].'"';
        }
        if($request['agency'] != null){
            $sql .= ' and a.id = "'.$request['agency'].'"';
        }
        if($request['ejecutivo_ss'] != null){
            $sql .= ' and gs.ejecutivo_ss = "'.$request['ejecutivo_ss'].'"';
        }
        if($request['beginDate'] != null){
            $sql .= ' and DATE_FORMAT(s.emission_date, "%Y-%m-%d") BETWEEN "'.$request['beginDate'].'" AND "'.$request['endDate'].'"';
        }        
        if($request['type_policy'] != null){
            $sql .= '';
        }
        if($request['ramov'] != null){
            $sql .= ' and p2.ramoid = "'.$request['ramov'].'"';
        }
        if($request['state'] != null){
            $sql .= ' and s.status_id = "'.$request['state'].'"';
        }
        if($request['sale_id'] != null){
            $sql .= ' and s.id = "'.$request['sale_id'].'"';
        }
        if($request['province'] != null){
            $sql .= ' and pro.province_id =  "'.$request['province'].'"';
        }
        if($request['city'] != null){
            $sql .= 'and ci.id = "'.$request['city'].'"';
        }
        if($request['paymenttype'] != null){
            $sql .= ' and pt.id = "'.$request['paymenttype'].'"';
        }
        if($request['brand'] != null){
            $sql .= ' and vb.id = "'.$request['brand'].'"';
        }

        return Excel::download(new walletReport($sql), 'Cartera.xlsx');
    }

    public function timeIndex(){
        //Validate if User has view Permit
        $viewPermit = checkViewPermit('37', \Auth::user()->role_id);
        if(!$viewPermit){
            \Session::flash('ValidateUserRoute', 'No tiene acceso al Reporte.');
            return view('home');
        }
        
        //Obtain Country
        $country = \App\country::all();
        
        //Obtain Channel
        $channel = \App\channels::all();
        
        //Obtain Users
        $userQuery = 'select * from users where role_id = "6"';
        $users = DB::select($userQuery);
        
        //Obtain Sale Type
        $saleType = \App\sales_type::all();
        
        //Obtain Sales Status
        $status = \App\status::find([1,10,3,5,7,6,2,4,11]);
       
        return view('reports.time', [
            "countries" => $country,
            "channels" => $channel,
            "saleTypes" => $saleType,
            "status" => $status,
            "users" => $users
        ]);
    }
    
    public function timeReports(request $request){
       $sql = '';
        $sqlSale = '';
        //Validate Country
        if($request['country'] != null){
            $sql .= ' and cou.id = "'.$request['country'].'"';
        }
        //Validate Province
        if($request['province'] != null){
            $sql .= ' and pro.id = "'.$request['province'].'"';
        }
        //Validate City
        if($request['city'] != null){
            $sql .= ' and cit.id = "'.$request['city'].'"';
        }
        //Validate Begin Date
        if($request['beginDate'] != null){
            $sql .= ' and sal.emission_date between "'.$request['beginDate'].'" and "'.$request['endDate'].'"';
        }
        //Validate Channel
        if($request['channel'] != null){
            $sql .= ' and chan.id = "'.$request['channel'].'"';
        }
        //Validate Agency
        if($request['agency'] != null){
            $sql .= ' and agen.id = "'.$request['agency'].'"';
        }
        return Excel::download(new timeReports($sql), 'Tiempos_Ventas.xlsx');
    }
    
    public function cancelMotivesIndex(){
        //Validate if User has view Permit
        $viewPermit = checkViewPermit('38', \Auth::user()->role_id);
        if(!$viewPermit){
            \Session::flash('ValidateUserRoute', 'No tiene acceso al Reporte.');
            return view('home');
        }
        
        //Obtain Country
        $country = \App\country::all();
        
        //Obtain Channel
        $channel = \App\channels::all();
        
        //Obtain Users
        $userQuery = 'select * from users where role_id = "6"';
        $users = DB::select($userQuery);
        
        //Obtain Sale Type
        $saleType = \App\sales_type::all();
        
        //Obtain Sales Status
        $status = \App\status::find([1,10,3,5,7,6,2,4,11]);
       
        return view('reports.cancelMotives', [
            "countries" => $country,
            "channels" => $channel,
            "saleTypes" => $saleType,
            "status" => $status,
            "users" => $users
        ]);
    }
    public function cancelMotivesReports(request $request){
       $sql = '';
        $sqlSale = '';
        //Validate Country
        if($request['country'] != null){
            $sql .= ' and cou.id = "'.$request['country'].'"';
        }
        //Validate Province
        if($request['province'] != null){
            $sql .= ' and pro.id = "'.$request['province'].'"';
        }
        //Validate City
        if($request['city'] != null){
            $sql .= ' and cit.id = "'.$request['city'].'"';
        }
        //Validate Begin Date
        if($request['beginDate'] != null){
            $sql .= ' and sal.emission_date between "'.$request['beginDate'].'" and "'.$request['endDate'].'"';
        }
        //Validate Channel
        if($request['channel'] != null){
            $sql .= ' and chan.id = "'.$request['channel'].'"';
        }
        //Validate Agency
        if($request['agency'] != null){
            $sql .= ' and agen.id = "'.$request['agency'].'"';
        }
        return Excel::download(new cancelMotivesReports($sql), 'Cancelaciones_Ventas.xlsx');
    }
    public function individualSalesIndex(){
        //Validate if User has view Permit
        $viewPermit = checkViewPermit('39', \Auth::user()->role_id);
        if(!$viewPermit){
            \Session::flash('ValidateUserRoute', 'No tiene acceso al Reporte.');
            return view('home');
        }
        
        //Obtain Country
        $country = \App\country::all();
        
        //Obtain Channel
        $channel = \App\channels::all();
        
        //Obtain Users
        $userQuery = 'select * from users where role_id = "6"';
        $users = DB::select($userQuery);
        
        //Obtain Sale Type
        $saleType = \App\sales_type::all();
        
        //Obtain Sales Status
        $status = \App\status::find([1,10,3,5,7,6,2,4,11]);
       
        return view('reports.individualSales', [
            "countries" => $country,
            "channels" => $channel,
            "saleTypes" => $saleType,
            "status" => $status,
            "users" => $users
        ]);
    }
    public function individualSalesReports(request $request){
        $sql = '';
        //Validate Country
        if($request['country'] != null){
            $sql .= ' and cou.id = "'.$request['country'].'"';
        }
        //Validate Province
        if($request['province'] != null){
            $sql .= ' and pro.id = "'.$request['province'].'"';
        }
        //Validate City
        if($request['city'] != null){
            $sql .= ' and cit.id = "'.$request['city'].'"';
        }
        //Validate Begin Date
        if($request['beginDate'] != null){
            $sql .= ' and sal.emission_date between "'.$request['beginDate'].'" and "'.$request['endDate'].'"';
        }
        //Validate Channel
        if($request['channel'] != null){
            $sql .= ' and chan.id = "'.$request['channel'].'"';
        }
        //Validate Agency
        if($request['agency'] != null){
            $sql .= ' and agen.id = "'.$request['agency'].'"';
        }
        //Validate salId
        if($request['salId'] != null){
            $sql .= ' and sal.id = "'.$request['salId'].'"';
        }
        //Validate Adviser
        if($request['adviser'] != null){
            $sql .= ' and usr.id = "'.$request['adviser'].'"';
        }
        //Validate Status
        if($request['status'] != null){
            $sql .= ' and sta.id = "'.$request['status'].'"';
        }
        return Excel::download(new individualSalesReports($sql), 'Venta_Detallado.xlsx');
    }
    public function schedulingIndex(){
        //Validate if User has view Permit
        $viewPermit = checkViewPermit('42', \Auth::user()->role_id);
        if(!$viewPermit){
            \Session::flash('ValidateUserRoute', 'No tiene acceso al Reporte.');
            return view('home');
        }
        
        //Obtain Country
        $country = \App\country::all();
        
        //Obtain Channel
        $channel = \App\channels::all();
        
        //Obtain Users
        $userQuery = 'SELECT * FROM users where role_id in (SELECT rol_id FROM menu_rol where menu_id = 14) ';
        $users = DB::select($userQuery);
        
        //Obtain Sale Type
        $saleType = \App\sales_type::all();
        
        //Obtain Sales Status
        $status = \App\status::find([16,17,3,4]);
        
        //Obtain Sales Types
        $sTypes = \App\sales_type::all();
        
        //Obtain Locations 
        $locationsQuery = 'select * from service_location';
        $location = DB::select($locationsQuery);
        
        //Obtain Damage
        $damages = \App\damage::all();
       
        return view('reports.scheduling', [
            "countries" => $country,
            "channels" => $channel,
            "saleTypes" => $saleType,
            "status" => $status,
            "users" => $users,
            "sTypes" => $sTypes,
            "locations" => $location,
            "damages" => $damages
        ]);
    }
    
    public function schedulingReports(request $request){
        $sql = '';
        //Validate Country
        if($request['country'] != null){
            $sql .= ' and cou.id = "'.$request['country'].'"';
        }
        //Validate Province
        if($request['province'] != null){
            $sql .= ' and pro.id = "'.$request['province'].'"';
        }
        //Validate City
        if($request['city'] != null){
            $sql .= ' and cit.id = "'.$request['city'].'"';
        }
        //Validate Begin Date
        if($request['beginDate'] != null){
            $sql .= ' and deta.begin_date > "'.$request['beginDate'].'" and deta.end_date < "'.$request['endDate'].'"';
        }
        //Validate Channel
        if($request['channel'] != null){
            $sql .= ' and chan.id = "'.$request['channel'].'"';
        }
        //Validate Agency
        if($request['agency'] != null){
            $sql .= ' and agen.id = "'.$request['agency'].'"';
        }
        //Validate salId
        if($request['salId'] != null){
            $sql .= ' and sal.id = "'.$request['salId'].'"';
        }
        //Validate Adviser
        if($request['adviser'] != null){
            $sql .= ' and usr.id = "'.$request['adviser'].'"';
        }
        //Validate Status
        if($request['status'] != null){
            $sql .= ' and sta.id = "'.$request['status'].'"';
        }
        //Validate Sale Type
        if($request['sType'] != null){
            $sql .= ' and sType.id = "'.$request['sType'].'"';
        }
        //Validate Location
        if($request['location'] != null){
            $sql .= ' and loca.id = "'.$request['location'].'"';
        }
        //Validate Paint
        if($request['paint'] != null){
            $sql .= ' and deta.paint = "'.$request['paint'].'"';
        }
        //Validate Damage
        if($request['damage'] != null){
            $sql .= ' and dType.id = "'.$request['damage'].'"';
        }
        return Excel::download(new schedulingReports($sql), 'agendamiento.xlsx');
    }
    public function schedulingCancelMotivesIndex(){
        //Validate if User has view Permit
        $viewPermit = checkViewPermit('43', \Auth::user()->role_id);
        if(!$viewPermit){
            \Session::flash('ValidateUserRoute', 'No tiene acceso al Reporte.');
            return view('home');
        }
        
        //Obtain Country
        $country = \App\country::all();
        
        //Obtain Channel
        $channel = \App\channels::all();
        
        //Obtain Users
         $userQuery = 'SELECT * FROM users where role_id in (SELECT rol_id FROM menu_rol where menu_id = 14) ';
        $users = DB::select($userQuery);
        
        //Obtain Sale Type
        $saleType = \App\sales_type::all();
        
        //Obtain Sales Status
        $status = \App\status::find([16,17,3,4]);
       
        return view('reports.schedulingCancelMotives', [
            "countries" => $country,
            "channels" => $channel,
            "saleTypes" => $saleType,
            "status" => $status,
            "users" => $users
        ]);
    }
    public function schedulingCancelMotivesReports(request $request){
       $sql = '';
        $sqlSale = '';
        //Validate Country
        if($request['country'] != null){
            $sql .= ' and cou.id = "'.$request['country'].'"';
        }
        //Validate Province
        if($request['province'] != null){
            $sql .= ' and pro.id = "'.$request['province'].'"';
        }
        //Validate City
        if($request['city'] != null){
            $sql .= ' and cit.id = "'.$request['city'].'"';
        }
        //Validate Begin Date
        if($request['beginDate'] != null){
            $sql .= ' and deta.begin_date > "'.$request['beginDate'].'" and deta.end_date < "'.$request['endDate'].'"';
        }
        //Validate Channel
        if($request['channel'] != null){
            $sql .= ' and chan.id = "'.$request['channel'].'"';
        }
        //Validate Agency
        if($request['agency'] != null){
            $sql .= ' and agen.id = "'.$request['agency'].'"';
        }
        return Excel::download(new schedulingCancelMotivesReports($sql), 'cancelacion.xlsx');
    }
    public function schedulingTimeIndex(){
        //Validate if User has view Permit
        $viewPermit = checkViewPermit('44', \Auth::user()->role_id);
        if(!$viewPermit){
            \Session::flash('ValidateUserRoute', 'No tiene acceso al Reporte.');
            return view('home');
        }
        
        //Obtain Country
        $country = \App\country::all();
        
        //Obtain Channel
        $channel = \App\channels::all();
        
        //Obtain Users
        $userQuery = 'select * from users where role_id = "6"';
        $users = DB::select($userQuery);
        
        //Obtain Sale Type
        $saleType = \App\sales_type::all();
        
        //Obtain Sales Status
       $status = \App\status::find([16,17,3,4]);
       
        return view('reports.schedulingTime', [
            "countries" => $country,
            "channels" => $channel,
            "saleTypes" => $saleType,
            "status" => $status,
            "users" => $users
        ]);
    }
    public function schedulingTimeReports(request $request){
       $sql = '';
        $sqlSale = '';
        //Validate Country
        if($request['country'] != null){
            $sql .= ' and cou.id = "'.$request['country'].'"';
        }
        //Validate Province
        if($request['province'] != null){
            $sql .= ' and pro.id = "'.$request['province'].'"';
        }
        //Validate City
        if($request['city'] != null){
            $sql .= ' and cit.id = "'.$request['city'].'"';
        }
        //Validate Begin Date
        if($request['beginDate'] != null){
            $sql .= ' and deta.begin_date > "'.$request['beginDate'].'" and deta.end_date < "'.$request['endDate'].'"';
        }
        //Validate Channel
        if($request['channel'] != null){
            $sql .= ' and chan.id = "'.$request['channel'].'"';
        }
        //Validate Agency
        if($request['agency'] != null){
            $sql .= ' and agen.id = "'.$request['agency'].'"';
        }
        return Excel::download(new schedulingTimeReports($sql), 'cancelacion.xlsx');
    }
    public function schedulingDetailIndex(){
        //Validate if User has view Permit
        $viewPermit = checkViewPermit('45', \Auth::user()->role_id);
        if(!$viewPermit){
            \Session::flash('ValidateUserRoute', 'No tiene acceso al Reporte.');
            return view('home');
        }
        
        //Obtain Country
        $country = \App\country::all();
        
        //Obtain Channel
        $channel = \App\channels::all();
        
        //Obtain Users
         $userQuery = 'SELECT * FROM users where role_id in (SELECT rol_id FROM menu_rol where menu_id = 14) ';
        $users = DB::select($userQuery);
        
        //Obtain Sale Type
        $saleType = \App\sales_type::all();
        
        //Obtain Sales Status
        $status = \App\status::find([16,17,3,4]);
        
        //Obtain Sales Types
        $sTypes = \App\sales_type::all();
        
        //Obtain Locations 
        $locationsQuery = 'select * from service_location';
        $location = DB::select($locationsQuery);
        
        //Obtain Damage
        $damages = \App\damage::all();
       
        return view('reports.schedulingDetail', [
            "countries" => $country,
            "channels" => $channel,
            "saleTypes" => $saleType,
            "status" => $status,
            "users" => $users,
            "sTypes" => $sTypes,
            "locations" => $location,
            "damages" => $damages
        ]);
    }
    
    public function schedulingDetailReports(request $request){
        $sql = '';
        //Validate Country
        if($request['country'] != null){
            $sql .= ' and cou.id = "'.$request['country'].'"';
        }
        //Validate Province
        if($request['province'] != null){
            $sql .= ' and pro.id = "'.$request['province'].'"';
        }
        //Validate City
        if($request['city'] != null){
            $sql .= ' and cit.id = "'.$request['city'].'"';
        }
        //Validate Begin Date
        if($request['beginDate'] != null){
            $sql .= ' and deta.begin_date > "'.$request['beginDate'].'" and deta.end_date < "'.$request['endDate'].'"';
        }
        //Validate Channel
        if($request['channel'] != null){
            $sql .= ' and chan.id = "'.$request['channel'].'"';
        }
        //Validate Agency
        if($request['agency'] != null){
            $sql .= ' and agen.id = "'.$request['agency'].'"';
        }
        //Validate salId
        if($request['salId'] != null){
            $sql .= ' and sal.id = "'.$request['salId'].'"';
        }
        //Validate Adviser
        if($request['adviser'] != null){
            $sql .= ' and usr.id = "'.$request['adviser'].'"';
        }
        //Validate Status
        if($request['status'] != null){
            $sql .= ' and sta.id = "'.$request['status'].'"';
        }
        //Validate Sale Type
        if($request['sType'] != null){
            $sql .= ' and sType.id = "'.$request['sType'].'"';
        }
        //Validate Location
        if($request['location'] != null){
            $sql .= ' and loca.id = "'.$request['location'].'"';
        }
        //Validate Paint
        if($request['paint'] != null){
            $sql .= ' and deta.paint = "'.$request['paint'].'"';
        }
        //Validate Damage
        if($request['damage'] != null){
            $sql .= ' and dType.id = "'.$request['damage'].'"';
        }
        return Excel::download(new schedulingDetailReports($sql), 'agendamiento.xlsx');
    }
    public function benefitsIndex(){
        //Validate if User has view Permit
        $viewPermit = checkViewPermit('47', \Auth::user()->role_id);
        if(!$viewPermit){
            \Session::flash('ValidateUserRoute', 'No tiene acceso al Reporte.');
            return view('home');
        }
        
          //Obtain Country
        $country = \App\country::all();
        
        //Obtain Channel
        $channel = \App\channels::all();
        
        //Obtain Users
         $userQuery = 'SELECT * FROM users where role_id in (SELECT rol_id FROM menu_rol where menu_id = 14) ';
        $users = DB::select($userQuery);
        
        //Obtain Sale Type
        $saleType = \App\sales_type::all();
        
        //Obtain Sales Status
        $status = \App\status::find([1,10,3,5,7,6,2,4,11]);
       
        return view('reports.benefits', [
            "countries" => $country,
            "channels" => $channel,
            "saleTypes" => $saleType,
            "status" => $status,
            "users" => $users
        ]);
    }
    
    public function benefitsReports(request $request){
       $sql = '';
        $sqlSale = '';
        //Validate Country
        if($request['country'] != null){
            $sql .= ' and cou.id = "'.$request['country'].'"';
        }
        //Validate Province
        if($request['province'] != null){
            $sql .= ' and pro.id = "'.$request['province'].'"';
        }
        //Validate City
        if($request['city'] != null){
            $sql .= ' and cit.id = "'.$request['city'].'"';
        }
        //Validate Begin Date
        if($request['beginDate'] != null){
            $sql .= ' and benVSalUse.date > "'.$request['beginDate'].'" and benVSalUse.date < "'.$request['endDate'].'"';
        }
        //Validate Channel
        if($request['channel'] != null){
            $sql .= ' and chan.id = "'.$request['channel'].'"';
        }
        //Validate Agency
        if($request['agency'] != null){
            $sql .= ' and agen.id = "'.$request['agency'].'"';
        }
        return Excel::download(new benefitsReports($sql), 'Beneficios_Global.xlsx');
    }
    public function benefitsUseIndex(){
        //Validate if User has view Permit
        $viewPermit = checkViewPermit('48', \Auth::user()->role_id);
        if(!$viewPermit){
            \Session::flash('ValidateUserRoute', 'No tiene acceso al Reporte.');
            return view('home');
        }
        
          //Obtain Country
        $country = \App\country::all();
        
        //Obtain Channel
        $channel = \App\channels::all();
        
        //Obtain Users
        $userQuery = 'select * from users where role_id = "6"';
        $users = DB::select($userQuery);
        
        //Obtain Sale Type
        $saleType = \App\sales_type::all();
        
        //Obtain Sales Status
        $status = \App\status::find([1,10,3,5,7,6,2,4,11]);
       
        return view('reports.benefitsUse', [
            "countries" => $country,
            "channels" => $channel,
            "saleTypes" => $saleType,
            "status" => $status,
            "users" => $users
        ]);
    }
    
    public function benefitsUseReports(request $request){
       $sql = '';
        $sqlSale = '';
        //Validate Country
        if($request['country'] != null){
            $sql .= ' and cou.id = "'.$request['country'].'"';
        }
        //Validate Province
        if($request['province'] != null){
            $sql .= ' and pro.id = "'.$request['province'].'"';
        }
        //Validate City
        if($request['city'] != null){
            $sql .= ' and cit.id = "'.$request['city'].'"';
        }
        //Validate Begin Date
        if($request['beginDate'] != null){
            $sql .= ' and benVSalUse.date > "'.$request['beginDate'].'" and benVSalUse.date < "'.$request['endDate'].'"';
        }
        //Validate Channel
        if($request['channel'] != null){
            $sql .= ' and chan.id = "'.$request['channel'].'"';
        }
        //Validate Agency
        if($request['agency'] != null){
            $sql .= ' and agen.id = "'.$request['agency'].'"';
        }
        return Excel::download(new benefitsUseReports($sql), 'Beneficios_uso.xlsx');
    }
    public function benefitsDetailIndex(){
        //Validate if User has view Permit
        $viewPermit = checkViewPermit('49', \Auth::user()->role_id);
        if(!$viewPermit){
            \Session::flash('ValidateUserRoute', 'No tiene acceso al Reporte.');
            return view('home');
        }
        
          //Obtain Country
        $country = \App\country::all();
        
        //Obtain Channel
        $channel = \App\channels::all();
        
        //Obtain Users
        $userQuery = 'select * from users where role_id = "6"';
        $users = DB::select($userQuery);
        
        //Obtain Sale Type
        $saleType = \App\sales_type::all();
        
        //Obtain Sales Status
        $status = \App\status::find([1,10,3,5,7,6,2,4,11]);
       
        return view('reports.benefitsDetail', [
            "countries" => $country,
            "channels" => $channel,
            "saleTypes" => $saleType,
            "status" => $status,
            "users" => $users
        ]);
    }
    
    public function benefitsDetailReports(request $request){
       $sql = '';
        $sqlSale = '';
        //Validate Country
        if($request['country'] != null){
            $sql .= ' and cou.id = "'.$request['country'].'"';
        }
        //Validate Province
        if($request['province'] != null){
            $sql .= ' and pro.id = "'.$request['province'].'"';
        }
        //Validate City
        if($request['city'] != null){
            $sql .= ' and cit.id = "'.$request['city'].'"';
        }
        //Validate Begin Date
        if($request['beginDate'] != null){
            $sql .= ' and ben.begin_date >  "'.$request['beginDate'].'" and ben.end_date < "'.$request['endDate'].'"';
        }
        //Validate Channel
        if($request['channel'] != null){
            $sql .= ' and chan.id = "'.$request['channel'].'"';
        }
        //Validate Agency
        if($request['agency'] != null){
            $sql .= ' and agen.id = "'.$request['agency'].'"';
        }
        return Excel::download(new benefitsDetailReports($sql), 'Beneficios_Detalle.xlsx');
    }
    public function customersIndex(){
        //Validate if User has view Permit
        $viewPermit = checkViewPermit('51', \Auth::user()->role_id);
        if(!$viewPermit){
            \Session::flash('ValidateUserRoute', 'No tiene acceso al Reporte.');
            return view('home');
        }
        
          //Obtain Country
        $country = \App\country::all();
        
        //Obtain Channel
        $channel = \App\channels::all();
        
        //Obtain Users
        $userQuery = 'select * from users where role_id = "6"';
        $users = DB::select($userQuery);
        
        //Obtain Sale Type
        $saleType = \App\sales_type::all();
        
        //Obtain Sales Status
        $status = \App\status::find([1,10,3,5,7,6,2,4,11]);
       
        return view('reports.customers', [
            "countries" => $country,
            "channels" => $channel,
            "saleTypes" => $saleType,
            "status" => $status,
            "users" => $users
        ]);
    }
    
    public function customersReports(request $request){
       $sql = '';
        $sqlSale = '';
        //Validate Country
        if($request['country'] != null){
            $sql .= ' and cou.id = "'.$request['country'].'"';
        }
        //Validate Province
        if($request['province'] != null){
            $sql .= ' and pro.id = "'.$request['province'].'"';
        }
        //Validate City
        if($request['city'] != null){
            $sql .= ' and cit.id = "'.$request['city'].'"';
        }
        //Validate Begin Date
        if($request['beginDate'] != null){
            $sql .= ' and sal.emission_date > "'.$request['beginDate'].'" and sal.emission_date < "'.$request['endDate'].'"';
        }
        //Validate Channel
        if($request['channel'] != null){
            $sql .= ' and chan.id = "'.$request['channel'].'"';
        }
        //Validate Agency
        if($request['agency'] != null){
            $sql .= ' and agen.id = "'.$request['agency'].'"';
        }
        //Validate Users
        if($request['adviser'] != null){
            $sql .= ' and usr.id = "'.$request['adviser'].'"';
        }
        return Excel::download(new customersReports($sql), 'clientes.xlsx');
    }
    
    public function massivesDetailIndex(){
        $channel = \App\SucreMassives::selectRaw('channel_sponsor')->groupBy('channel_sponsor')->get();
        $province = \App\SucreMassives::selectRaw('province_customer')->groupBy('province_customer')->get();
        $advisor  = \App\SucreMassives::selectRaw('advisor_sponsor')->groupBy('advisor_sponsor')->get();

        return view('reports.massivesDetail', [
            "channels" => $channel,
            "province" => $province,
            "advisor" => $advisor
        ]);
    }
    
    public function massivesDetailReports(request $request){
    	set_time_limit(0);
        ini_set('max_execution_time', 60000);
        ini_set('memory_limit', '-1');
        return Excel::download(new massivesDetailReports($request['channelReport'], $request['agency'], $request['beginDate'], $request['endDate'], $request['provinceReport'], $request['city'], $request['policy_number'], $request['advisor']), 'detallado_masivos.xlsx');
    }
    
    public function massivesGlobalIndex(){
        $channel = \App\SucreMassives::selectRaw('channel_sponsor')->groupBy('channel_sponsor')->get();
        $province = \App\SucreMassives::selectRaw('province_customer')->groupBy('province_customer')->get();
        $advisor  = \App\SucreMassives::selectRaw('advisor_sponsor')->groupBy('advisor_sponsor')->get();
        
        return view('reports.massivesGlobal', [
            "channels" => $channel,
            "province" => $province,
            "advisor" => $advisor
        ]);
    }
    
    public function massivesGlobalReports(request $request){
    	set_time_limit(0);
        ini_set('max_execution_time', 60000);
        ini_set('memory_limit', '-1');
        return Excel::download(new massivesGlobalReports($request['channelReport'], $request['agency'], $request['beginDate'], $request['endDate'], $request['provinceReport'], $request['city'], $request['policy_number'], $request['advisor']), 'detallado_masivos.xlsx');
    }
}
