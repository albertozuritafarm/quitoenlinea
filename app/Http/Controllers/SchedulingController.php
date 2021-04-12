<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use File;
use Illuminate\Support\Facades\Session;
//use Validator;
use Illuminate\Support\Facades\Validator;

class SchedulingController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        
//        $this->middleware('validateUserRoute');
    }

    public function create() {
        //Validate Create Permission
        $edit = checkExtraPermits('22',\Auth::user()->role_id);
        if(!$edit){
            \Session::flash('ValidateUserRoute', 'No tiene acceso a crear nuevos Agendamientos.');
            return view('home');
        }
        
        //Delete Temp Data
        DB::table('scheduling_temp_details')->where('user_id', \Auth::user()->id)->delete();
        return view('scheduling.create');
    }

    public function index(request $request) {
        //Validate if User has view Permit
        $viewPermit = checkViewPermit('11', \Auth::user()->role_id);
        if(!$viewPermit){
            \Session::flash('ValidateUserRoute', 'No tiene acceso al modulo de Agendamiento.');
            return view('home');
        }
        
        //Obtain Edit Permission
        $edit = checkExtraPermits('12',\Auth::user()->role_id);
        
        //Obtain Create Permission
        $create = checkExtraPermits('22',\Auth::user()->role_id);
        
        //Obtain Cancel Permission
        $cancel = checkExtraPermits('14',\Auth::user()->role_id);
        
        //Delete Temp Data
        DB::table('scheduling_temp_details')->where('user_id', \Auth::user()->id)->delete();
        
        //Obtain Channel
        $channelQuery = 'select cha.* from channels cha join agencies agen on agen.channel_id = cha.id where agen.id =  "'.\Auth::user()->agen_id.'"';
        $channelForm = DB::select($channelQuery);
        
        //Obtain Sales Status
        $status = \App\status::find([16,17,3,4]);
        
        //Store Form Variables in Session
        if ($request->isMethod('post')){
            session(['schedulingPlate' => $request->plate]);
            session(['schedulingBeginDate' => $request->beginDate]);
            session(['schedulingFirstName' => $request->first_name]);
            session(['schedulingLastName' => $request->last_name]);
            session(['schedulingDocument' => $request->document]);
            session(['schedulingEndDate' => $request->endDate]);
            session(['schedulingStatus' => $request->status]);
            session(['schedulingItems' => $request->items]);
        }
        
        //Pagination Items
        if(session('schedulingItems') == null){ $items = 10; }else{ $items = session('schedulingItems'); }
        
        //Form Variables
        $plate = session('schedulingPlate');
        $beginDate = session('schedulingBeginDate');
        $endDate = session('schedulingEndDate');
        $firstName = session('schedulingFirstName');
        $lastName = session('schedulingLastName');
        $document = session('schedulingDocument');
        $statusForm = session('schedulingStatus');
        
        //Validate User
        if(\Auth::user()->role_id == 1 || \Auth::user()->role_id == 2){ $userRol = null; }else{ $userRol = true; }
        
        //NEW SHCEDULING
        $newScheduling = scheduling($plate, $beginDate, $endDate, $firstName, $lastName, $document, $statusForm, $items, $userRol, $channelForm);

        return view('scheduling.index', [
            'newSchedules' => $newScheduling,
            "status" => $status,
            "edit" => $edit,
            "cancel" => $cancel,
            "create" => $create,
            "items" => $items
        ]);
    }
    
    function fetch_data(request $request){
        if ($request->ajax()) {  
            
            //Obtain Channel
        $channelQuery = 'select cha.* from channels cha join agencies agen on agen.channel_id = cha.id where agen.id =  "'.\Auth::user()->agen_id.'"';
        $channelForm = DB::select($channelQuery);
        
            //Pagination Items
        if(session('schedulingItems') == null){ $items = 10; }else{ $items = session('schedulingItems'); }
        
        //Form Variables
        $plate = session('schedulingPlate');
        $beginDate = session('schedulingBeginDate');
        $endDate = session('schedulingEndDate');
        $firstName = session('schedulingFirstName');
        $lastName = session('schedulingLastName');
        $document = session('schedulingDocument');
        $statusForm = session('schedulingStatus');
        
        //Obtain Edit Permission
        $edit = checkExtraPermits('12',\Auth::user()->role_id);
        
        //Obtain Create Permission
        $create = checkExtraPermits('22',\Auth::user()->role_id);
        
        //Obtain Cancel Permission
        $cancel = checkExtraPermits('14',\Auth::user()->role_id);
        
        //Validate User
        if(\Auth::user()->role_id == 1 || \Auth::user()->role_id == 2){ $userRol = null; }else{ $userRol = true; }
        
         //NEW SHCEDULING
        $newScheduling = scheduling($plate, $beginDate, $endDate, $firstName, $lastName, $document, $statusForm, $items, $userRol, $channelForm);
        return view('pagination.scheduling', [
            'newSchedules' => $newScheduling,
            "edit" => $edit,
            "cancel" => $cancel,
            "create" => $create,
            "items" => $items
        ]);
        }
    }

    public function validatePlate(request $request) {
        //Validate if plate has existing Sale
        $query = 'select sal.id, vehi.color, vehi.year, concat(brand.name," - ", vehi.model) as "brand"
                from vehicles_sales vsal
                join vehicles vehi on vehi.id = vehicule_id
                join vehicles_brands brand on brand.id = vehi.brand_id
                join sales sal on sal.id = vsal.sales_id
                where vehi.plate = "' . $request['data']['plate'] . '" and vsal.status_id = 1 and sal.status_id = 1 order by sal.end_date';
        $sales = DB::select($query);
        
        if ($sales != null) {
            //Generate Sales Table
            $returnTable = '<table id="salesTable" class="table table-bordered">
                                <thead>
                                  <tr>
                                    <th>Venta</th>
                                    <th>Producto</th>
                                    <th>Inicio</th>
                                    <th>Fin</th>
                                    <th>Servicios Disponibles</th>
                                    <th>Seleccione</th>
                                  </tr>
                                </thead>
                                <tbody>';

            foreach ($sales as $sale) {
                $queryTable = 'select pro.name,  DATE_FORMAT(sal.begin_date, "%d-%m-%Y")  as "begin_date",
                                DATE_FORMAT(sal.end_date, "%d-%m-%Y") as "end_date", pro.services
                                from sales sal
                                join products_channel pbc on pbc.id = sal.pbc_id
                                join products pro on pro.id = pbc.product_id
                                where sal.id in (' . $sale->id . ')';
                $table = DB::select($queryTable);

                $returnTable .= '<tr>
                                <td align="center">' . $sale->id . '</td>
                                <td align="center">' . $table[0]->name . '</td>
                                <td align="center">' . $table[0]->begin_date . '</td>
                                <td align="center">' . $table[0]->end_date . '</td>';

                //Obtain services available
                $query2 = 'select count(sdeta.id) as "count"
                        from scheduling_details sdeta
                        join scheduling sche on sche.id = sdeta.scheduling_id
                        join vehicles_sales vsal on vsal.id = sche.vehicles_sales_id
                        join sales sal on sal.id = vsal.sales_id
                        join vehicles vehi on vehi.id = vsal.vehicule_id
                        where vehi.plate = "' . $request['data']['plate'] . '" and sdeta.status_id in (3,17) and sal.id = "' . $sale->id . '"';

                $tableData = DB::select($query2);

                //Services Available
                $services = $table[0]->services - $tableData[0]->count;

                $returnTable .= '<td align="center">' . $services . '</td>';
                if ($services > 0) {
//                    $returnTable .= '<td align="center"><a href="#" onclick="selectSales(' . $sale->id . ')"><span class="glyphicon glyphicon-ok"></span></a></td></tr>';
                    $returnTable .= '<td align="center"><input type="radio" name="optradio" onclick="selectSales(' . $sale->id . ')"></td></tr>';
                } else {
                    $returnTable .= '<td align="center"></td></tr>';
//                    $returnTable .= '<td align="center"></td></tr>';
                }
            }
            $returnTable .= '  </tbody>
                              </table>';
            $returnArray = [
                "success" => "true",
                "table" => $returnTable,
                "color" => $sales[0]->color,
                "year" => $sales[0]->year,
                "brand" => $sales[0]->brand
            ];
        } else {
            $returnArray = [
                "success" => "false"
            ];
        }
        return $returnArray;
    }

    public function createFill(request $request) {
//        return $request;
        $query = 'select vehi.color as "color",
                concat(brand.name," - ", vehi.model) as "brand",
                vehi.year as "year",
                cus.document as "document",
                cus.last_name as "last_name",
                cus.first_name as "first_name",
                sal.cus_mobile_phone as "mobile_phone",
                sal.cus_email as "email", 
                if(sal.sales_type_id = 2, cha2.name,  cha.name) as "channel",
                pro.services as "services",
                DATE_FORMAT(sal.begin_date, "%d-%m-%Y")  as "begin_date",
                DATE_FORMAT(sal.end_date, "%d-%m-%Y") as "end_date"
                from sales sal
                join customers cus on cus.id = sal.customer_id
                join vehicles_sales vsal on vsal.sales_id = sal.id
                join vehicles vehi on vehi.id = vsal.vehicule_id 
                join vehicles_brands brand on brand.id = vehi.brand_id
                join users user on user.id = sal.user_id
                join agencies agen on agen.id = user.agen_id
                join channels cha on cha.id = agen.channel_id
                left join massives_sales msal on msal.sales_id = sal.id
                left join massives mass on mass.id = msal.massives_id
                left join agencies agen2 on agen2.id = mass.agencies_id
                left join channels cha2 on cha2.id = agen2.channel_id
                join products_channel pbc on pbc.id = sal.pbc_id
                join products pro on pro.id = pbc.product_id
                where sal.id = "' . $request['data']['sales'] . '" and vehi.plate = "' . $request['data']['plate'] . '"';

        $data = DB::select($query);

        if ($data != null) {

            //Obtain services available
            $query2 = 'select count(sdeta.id) as "count"
                        from scheduling_details sdeta
                        join scheduling sche on sche.id = sdeta.scheduling_id
                        join vehicles_sales vsal on vsal.id = sche.vehicles_sales_id
                        join sales sal on sal.id = vsal.sales_id
                        join vehicles vehi on vehi.id = vsal.vehicule_id
                        where vehi.plate = "' . $request['data']['plate'] . '" and sdeta.status_id in (3,17) and sal.id = "' . $request['data']['sales'] . '"';

            $tableData = DB::select($query2);

            //Services Available
            $services = $data[0]->services - $tableData[0]->count;

            if ($services > 0) {
                $success = 'true';
            } else {
                $success = 'false';
            }

            $table = '<table class="table table-bordered">
                        <thead>
                          <tr>
                            <th>Desde</th>
                            <th>Hasta</th>
                            <th>Servicios Disponibles</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td align="center">' . $data[0]->begin_date . '</td>
                            <td align="center">' . $data[0]->end_date . '</td>
                            <td align="center">' . $services . '</td>
                          </tr>
                        </tbody>
                      </table>';

            $returnArray = [
                "success" => $success,
                "data" => $data[0],
                "table" => $table
            ];
        } else {
            $returnArray = [
                "success" => "false"
            ];
        }
        return $returnArray;
    }

    public function validateDamage(request $request) {
        //Obtain Damage
        $damageRequest = $request['data']['damage'];
        $damage = \App\damage::where('name', $damageRequest)->first();

        //Validate Location
        if ($request['data']['location'] == 'Taller') {
            //Turn Time into Hours and Minutes
            $hour = date('H:i', mktime(0, $damage->work_shop_time));
        } else {
            //Turn Time into Hours and Minutes
            $hour = date('H:i', mktime(0, $damage->home_time));
        }

        //Return
        return $hour;
    }

    public function validateDateTime(request $request) {
//        return $request;
        //Obtain Damage
        $damageRequest = $request['data']['damage'];
        $damage = \App\damage::where('name', $damageRequest)->first();

        //Validate Location
        if ($request['data']['location'] == 'Taller') {
            //Turn Time into Hours and Minutes
            $minutes = $damage->work_shop_time;
        } else {
            //Turn Time into Hours and Minutes
            $minutes = $damage->home_time;
        }

        //Validate Begin Hour
        $beginDate = Carbon::parse($request['data']['dateTime']);
        $date = strtotime($beginDate);
        $hourMinutes = date('Hi', $date);

        if ($hourMinutes < '0830') {
            $returnArray = [
                "success" => "false"
            ];
            return $returnArray;
        }

        //Validate End Date
        $endDate = Carbon::parse($request['data']['dateTime']);
        $newEndDate = $endDate->addMinutes($minutes);
        $date = strtotime($newEndDate);
        $hourMinutes = date('Hi', $date);

        if ($hourMinutes > '1730') {
            $returnArray = [
                "success" => "false"
            ];
            return $returnArray;
        }
        
        //Validate if date is between sales dates
       $query = 'SELECT id
                    FROM sales 
                    WHERE id = '.$request['data']['sale'].'
                    AND DATE_FORMAT("'.$beginDate.'","%Y-%m-%d") >= begin_date
                    AND DATE_FORMAT("'.$beginDate.'","%Y-%m-%d") <= end_date';
//        return $query;die();
        $dateTime = DB::select($query);
        
        if (!$dateTime) {
            $returnArray = [
                "success" => "false"
            ];
            return $returnArray;
        }

        $query = 'select distinct(sdeta.id) 
                from scheduling_details sdeta 
                where "'.$beginDate.'" >= sdeta.begin_date and  "'.$beginDate.'" <= sdeta.end_date and status_id = 3
                union 
                select distinct(sdeta.id) 
                from scheduling_details sdeta 
                where "'.$endDate.'" >= sdeta.begin_date and  "'.$endDate.'" <= sdeta.end_date  and status_id = 3
                union
                select distinct(sdetaTemp.id) 
                from scheduling_temp_details sdetaTemp 
                where "'.$beginDate.'" >= sdetaTemp.begin_date and  "'.$beginDate.'" <= sdetaTemp.end_date 
                union 
                select distinct(sdetaTemp.id) 
                from scheduling_temp_details sdetaTemp 
                where "'.$endDate.'" >= sdetaTemp.begin_date and  "'.$endDate.'" <= sdetaTemp.end_date';
      
        $dateTime = DB::select($query);

        if ($dateTime != null) {
            $returnArray = [
                "success" => "false"
            ];
        } else {
            //Store in Scheduling Temp Details
            $tempDetails = new \App\SchedulingTempDetails();
            $tempDetails->user_id = \Auth::user()->id;
            $tempDetails->paint = null;
            $tempDetails->service_location_id = null;
            $tempDetails->address = $request['data']['location'];
            $tempDetails->damage_type_id = null;
            $tempDetails->estimated_time = $minutes;
            $tempDetails->begin_date = $beginDate;
            $tempDetails->end_date = $newEndDate;
            $tempDetails->save();
            $returnArray = [
                "id" => $tempDetails->id,
                "success" => "true"
            ];
        }
        return $returnArray;
    }

    public function store(request $request) {
//        return $request;
        //Obtain vSalId
        $vSalIdQuery = 'select vsal.id
                        from vehicles_sales vsal
                        join vehicles vehi on vehi.id = vsal.vehicule_id
                        where vehi.plate = "' . $request['plate'] . '" and vsal.sales_id = "' . $request['sale'] . '"';

        $vSalId = DB::select($vSalIdQuery);

        //Store Scheduling
        $scheduling = new \App\scheduling();
        $scheduling->vehicles_sales_id = $vSalId[0]->id;
        $scheduling->user_id = \Auth::user()->id;
        $scheduling->date = now();
        $scheduling->status_id = 1;
        $scheduling->save();

        //Foreach
        foreach ($request['tableData'] as $service) {
            //Service Locatio
            $serviceLocationQuery = 'select * from service_location where name = "' . $service['location'] . '"';
            $serviceLocation = DB::select($serviceLocationQuery);

            //Damage
            $damageQuery = 'select * from damage_type where name = "' . $service['damage'] . '"';
            $damage = DB::select($damageQuery);

            //Validate Location
            if ($service['location'] == 'Taller') {
                //Turn Time into Hours and Minutes
                $minutes = $damage[0]->work_shop_time;
            } else {
                //Turn Time into Hours and Minutes
                $minutes = $damage[0]->home_time;
            }

            $beginDate = Carbon::parse($service['dateTime']);

            $endDate = Carbon::parse($service['dateTime']);
            $endDate->addMinutes($minutes);

            //EndDate
            //Store Scheduling Details
            $details = new \App\schedulingDetails();
            $details->scheduling_id = $scheduling->id;
            $details->paint = $service['paint'];
            $details->service_location_id = $serviceLocation[0]->id;
            $details->address = $service['address'];
            $details->damage_type_id = $damage[0]->id;
            $details->estimated_time = $minutes;
            $details->begin_date = $beginDate;
            $details->end_date = $endDate;
            $details->status_id = 3;
            $details->user_id = \Auth::user()->id;
            $details->save();
        }
        \Session::flash('storeMessage', ' El agendamiento '.$scheduling->id.' fue creado de manera exitosa.');
        return 'success';
    }

    public function calendar(request $request) {
        $query = 'select deta.id, deta.address, deta.begin_date, deta.end_date
                    from scheduling_details deta where status_id = 3
                    ';

        $scheDetails = DB::select($query);

        foreach ($scheDetails as $detail) {
            $data[] = array(
                'id' => $detail->id,
                'title' => $detail->address,
                'start' => $detail->begin_date,
                'end' => $detail->end_date,
                'color' => "red"
            );
        }
        $queryTemp = 'select * 
                    from scheduling_temp_details temp
                    ';

        $scheDetailsTemp = DB::select($queryTemp);

        foreach ($scheDetailsTemp as $detailTemp) {
            if ($detailTemp->user_id == \Auth::user()->id) {
                $dataTemp = array(
                    'id' => $detailTemp->id,
                    'title' => $detailTemp->address,
                    'start' => $detailTemp->begin_date,
                    'end' => $detailTemp->end_date,
                    'color' => "green",
                    "editable" => true,
                    "durationEditable" => false,
                    "overlap" => false
                );
            } else {
                $dataTemp = array(
                    'id' => $detailTemp->id,
                    'title' => $detailTemp->address,
                    'start' => $detailTemp->begin_date,
                    'end' => $detailTemp->end_date,
                    'color' => "red"
                );
            }
            array_push($data, $dataTemp);
        }
        return $data;
    }
    
    public function calendarReschedule($id) {
        $query = 'select deta.id, deta.address, deta.begin_date, deta.end_date
                    from scheduling_details deta where status_id = 3
                    ';

        $scheDetails = DB::select($query);

        foreach ($scheDetails as $detail) {
            if($id == $detail->id){
                $data[] = array(
                    'id' => $detail->id,
                    'title' => $detail->address,
                    'start' => $detail->begin_date,
                    'end' => $detail->end_date,
                    'color' => "green",
                    "editable" => true,
                    "durationEditable" => false,
                    "overlap" => false
                );
            }else{
                $data[] = array(
                    'id' => $detail->id,
                    'title' => $detail->address,
                    'start' => $detail->begin_date,
                    'end' => $detail->end_date,
                    'color' => "red"
                );
            }
        }
        $queryTemp = 'select * 
                    from scheduling_temp_details temp
                    ';

        $scheDetailsTemp = DB::select($queryTemp);

        foreach ($scheDetailsTemp as $detailTemp) {
            if ($detailTemp->user_id == \Auth::user()->id) {
                $dataTemp = array(
                    'id' => $detailTemp->id,
                    'title' => $detailTemp->address,
                    'start' => $detailTemp->begin_date,
                    'end' => $detailTemp->end_date,
                );
            } else {
                $dataTemp = array(
                    'id' => $detailTemp->id,
                    'title' => $detailTemp->address,
                    'start' => $detailTemp->begin_date,
                    'end' => $detailTemp->end_date,
                    'color' => "red"
                );
            }
            array_push($data, $dataTemp);
        }
//        return json_encode($data);
        return $data;
    }

    public function deleteTemp() {
        DB::table('scheduling_temp_details')->where('user_id', \Auth::user()->id)->delete();
    }

    function modalResume(request $request) {
        //Obtain Data
        $query = 'select DATE_FORMAT(sche.date, "%d-%m-%Y") as "date",
                    vehi.plate as "plate",
                    vehi.color as "color",
                    concat(vbrand.name," - ",vehi.model) as "brand",
                    vehi.year as "year",
                    cus.document as "document",
                    cus.last_name as "last_name",
                    cus.first_name as "first_name",
                    sal.cus_mobile_phone as "phone",
                    deta.paint as "paint",
                    sloca.name as "location",
                    deta.address as "address",
                    DATE_FORMAT(deta.begin_date, "%d-%m-%Y %k:%i") as "begin_date",
                    dama.name as "damage",
                    deta.estimated_time as "time",
                    sta.name as "status"
                    from scheduling_details deta
                    left join scheduling sche on sche.id = deta.scheduling_id
                    left join vehicles_sales vsal on vsal.id = sche.vehicles_sales_id
                    left join vehicles vehi on vehi.id = vehicule_id
                    left join vehicles_brands vbrand on vbrand.id = vehi.brand_id
                    left join sales sal on sal.id = vsal.sales_id
                    left join customers cus on cus.id = sal.customer_id
                    left join service_location sloca on sloca.id = deta.service_location_id
                    left join damage_type dama on dama.id = deta.damage_type_id
                    left join status sta on sta.id = deta.status_id
                    where deta.id = "' . $request['data']['id'] . '"';

        $data = DB::select($query);

        $returnTable = '';

        //Resume Table
        $returnTable .= '<h4>Resumen del Agendamiento:</h4>
                        <table id="schedulingResumeTable" class="table table-bordered">
                                    <tbody>
                                        <tr style="background-color: #848484;color: white;">
                                            <th>Fecha Agendamiento</th>
                                            <th>Placa</th>
                                            <th>Color</th>
                                            <th>Marca-Modelo</th>
                                            <th>Año</th>
                                            <th>Documento</th>
                                            <th>Apellidos</th>
                                            <th>Nombres</th>
                                        </tr>
                                        <tr>
                                            <td align="center">' . $data[0]->date . '</td>
                                            <td align="center">' . $data[0]->plate . '</td>
                                            <td align="center">' . $data[0]->color . '</td>
                                            <td align="center">' . $data[0]->brand . '</td>
                                            <td align="center">' . $data[0]->year . '</td>
                                            <td align="center">' . $data[0]->document . '</td>
                                            <td align="center">' . $data[0]->last_name . '</td>
                                            <td align="center">' . $data[0]->first_name . '</td>
                                        </tr>
                                        <tr style="background-color: #848484;color: white;">
                                            <th>Telefono</th>
                                            <th>Pintura</th>
                                            <th>Lugar Servicio</th>
                                            <th>Direccion</th>
                                            <th>Fecha/Hora</th>
                                            <th>Tipo Golpe</th>
                                            <th>Tiempo Estimado</th>
                                            <th>Estado</th>
                                        </tr>
                                        <tr>
                                            <td align="center">' . $data[0]->phone . '</td>
                                            <td align="center">' . $data[0]->paint . '</td>
                                            <td align="center">' . $data[0]->location . '</td>
                                            <td align="center">' . $data[0]->address . '</td>
                                            <td align="center">' . $data[0]->begin_date . '</td>
                                            <td align="center">' . $data[0]->damage . '</td>
                                            <td align="center">' . date('H:i', mktime(0, $data[0]->time)) . '</td>
                                            <td align="center">' . $data[0]->status . '</td>
                                        </tr>
                                    </tbody>
                            </table>';

        return $returnTable;
    }

    public function confirm(request $request) {
        $ext = 'false';
        $fileExt = $request->file('fileConfirm')->getClientOriginalExtension();
        
        if(in_array($fileExt,array('zip','rar'))){
            $ext = 'true';
        }else{
            $ext = 'false';
        }
        
        $validation = Validator::make($request->all(), [
                    'fileConfirm' => 'required|max:1000048'
        ]);
        
        if ($ext) {
         
            //Folder
            $folder = '/files/scheduling/'.$request->confirmId.'/';
            
            //Create Folder
            if (!file_exists($folder)) {
                mkdir($folder, 0775, true);
            }
            
            $image = $request->file('fileConfirm');
            $new_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('files/scheduling/'.$request->confirmId), $new_name);
            
            $now = Carbon::now();
            $deta = \App\schedulingDetails::find($request->confirmId);
            $deta->status_id = '17';
            $deta->confirm_date = $now;
            $deta->confirm_user_id = \Auth::user()->id;
            $deta->confirm_file = $folder . $new_name;
            $deta->save();
            
            \Session::flash('confirmSuccess', 'El agendamiento fue confirmado de manera exitosa');
            
            $returnArray = [
                "success" => "true",
                "message" => "El agendamiento fue confirmado correctamente"
            ];
            
        } else {
            $returnArray = [
                "success" => "false",
                "message" => "El archivo debe ser un archivo comprimido"
            ];
        }
        return $returnArray;
        
    }

    public function modalCancel(request $request) {
        $cancels = \App\cancel_motives::where('table','SCHEDULING')->get();
        $return = ' <div class="form-group">
                      <input id="schedulingDetailId" name="cancelId" type="hidden" value="' . $request['id'] . '">
                      <label for="sel1">Motivo de la Cancelación:</label>
                      <select id="cancelMotive" class="form-control" id="sel1">
                      <option value="">--Escoja Una--</option>';
        foreach ($cancels as $cancel) {
            $return .= '<option value="' . $cancel->id . '">' . $cancel->name . '</option>';
        }
        $return .= '</select>
                    </div> ';
        $returnArray = [
            "success" => "true",
            "data" => $return
        ];
        return $returnArray;
    }

    public function storeCancel(request $request) {
        $now = Carbon::now();
        $deta = \App\schedulingDetails::find($request['schedulingDetailId']);
        $deta->cancel_motive_id = $request['cancelMotive'];
        $deta->cancel_date = $now;
        $deta->cancel_user_id = \Auth::user()->id;
        $deta->status_id = 4;
        $deta->save();

        $returnArray = [
            "success" => "true"
        ];
        return $returnArray;
    }

    public function rescheduleValidate(request $request) {
//        return $request;
//        return $request['beginDate'];
        //Obtain Dates
        $beginDate = Carbon::parse($request['beginDate']);
//        $beginDate = Carbon::createFromFormat('Y-m-d\TH:i:s.0000000 P', $request['beginDate']);
//        return $beginDate;
        $deta = \App\schedulingDetails::find($request['id']);
      
        $endDate = Carbon::parse($request['beginDate']);
        $endDate->addMinutes($deta->estimated_time);

        //Validate Begin Hour
        $date = strtotime($beginDate);
        $hourMinutes = date('Hi', $date);

        if ($hourMinutes < '0830') {
            $returnArray = [
                "success" => "false"
            ];
            return $returnArray;
        }

        //Validate End Date
        $date = strtotime($endDate);
        $hourMinutes = date('Hi', $date);

        if ($hourMinutes > '1730') {
            $returnArray = [
                "success" => "false"
            ];
            return $returnArray;
        }
        
        //Validate Dates
        $query = 'select distinct(sdeta.id) 
                    from scheduling_details sdeta 
                    where "'.$beginDate.'" > sdeta.begin_date 
                    and "'.$beginDate.'" < sdeta.end_date
                    and sdeta.status_id in (3) and sdeta.id not in ('.$request['id'].')
                    union
                    select distinct(sdetaTemp.id) 
                    from scheduling_temp_details sdetaTemp 
                    where "'.$beginDate.'" > sdetaTemp.begin_date 
                    and "'.$beginDate.'" < sdetaTemp.end_date
                    and sdetaTemp.status_id in (3)
                    union
                    select distinct(sdeta.id) 
                    from scheduling_details sdeta 
                    where "'.$endDate.'" > sdeta.begin_date 
                    and "'.$endDate.'" < sdeta.end_date
                    and sdeta.status_id in (3) and sdeta.id not in ('.$request['id'].')
                    union
                    select distinct(sdetaTemp.id) 
                    from scheduling_temp_details sdetaTemp 
                    where "'.$endDate.'" > sdetaTemp.begin_date 
                    and "'.$endDate.'" < sdetaTemp.end_date
                    and sdetaTemp.status_id in (3)';
//return $query;
        $dateTime = DB::select($query);

        if ($dateTime) {
            $returnArray = [
                "success" => "false"
            ];
        } else {
            //Upload OLD Deta
            $deta->status_id = 16;
            $deta->save();
            
             //Store Scheduling Details
            $newDeta = new \App\schedulingDetails();
            $newDeta->scheduling_id = $deta->scheduling_id;
            $newDeta->paint = $deta->paint;
            $newDeta->service_location_id = $deta->service_location_id;
            $newDeta->address = $deta->address;
            $newDeta->damage_type_id = $deta->damage_type_id;
            $newDeta->estimated_time = $deta->estimated_time;
            $newDeta->begin_date = $beginDate;
            $newDeta->end_date = $endDate;
            $newDeta->status_id = 3;
            $newDeta->user_id = \Auth::user()->id;
            $newDeta->save();
            $returnArray = [
                "success" => "true"
            ];
            \Session::flash('rescheduleSuccess', 'El reagendamiento fue reagendado de manera exitosa');
        }
        return $returnArray;
    }
    
    public function calendarTableDelete(request $request){
        DB::table('scheduling_temp_details')->where('id', $request->id)->delete();
    }

}
