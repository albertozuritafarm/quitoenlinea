<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class BenefitsController extends Controller
{
    //Authenticaction
      public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('validateUserRoute');
    }
    
    public function index(request $request){       
            //Validate if User has view Permit
            $viewPermit = checkViewPermit('13', \Auth::user()->role_id);
            if(!$viewPermit){
                \Session::flash('ValidateUserRoute', 'No tiene acceso al modulo de ventas Individual.');
                return view('home');
            }
             
            //Obtain Channel
            $channelQuery = 'select cha.* from channels cha join agencies agen on agen.channel_id = cha.id where agen.id =  "'.\Auth::user()->agen_id.'"';
            $channel = DB::select($channelQuery);
              
            //Cancel Motive
            $cancelMotives = \App\cancel_motives::where('table','BENEFITS')->get();
            
            //Channels
            $channelsQuery = 'select cha.id, IF(cha.name = "Todos", "Magnus", cha.name) as "name" from channels cha';
            $channels = DB::select($channelsQuery);
            
            //Status
            $status = \App\status::find([1, 4]);
        
            //Obtain Edit Permission
            $edit = checkExtraPermits('43',\Auth::user()->role_id);

            //Obtain Create Permission
            $create = checkExtraPermits('44',\Auth::user()->role_id);

            //Obtain Cancel Permission
            $cancel = checkExtraPermits('45',\Auth::user()->role_id);
                    
            //Store Form Variables in Session
            if ($request->isMethod('post')){
                session(['benefitsChannel' => $request->channel]);
                session(['benefitsBeginDate' => $request->beginDate]);
                session(['benefitsEndDate' => $request->endDate]);
                session(['benefitsStatus' => $request->status]);
                session(['benefitsItems' => $request->items]);
            }

            //Pagination Items
            if(session('benefitsItems') == null){ $items = 10; }else{ $items = session('benefitsItems'); }

            //Form Variables
            $channelForm = session('benefitsChannel');
            $beginDate = session('benefitsBeginDate');
            $endDate = session('benefitsEndDate');
            $statusForm = session('benefitsFirstName');
            
            //Validate User Role
            if(\Auth::user()->role_id == 1 || \Auth::user()->role_id == 2 || \Auth::user()->role_id == 3){ $userRol = null; $channelQueryForm = '1=1'; }else{ $userRol = 'true'; $channelQueryForm = ' channels.id = "'.$channel[0]->id.'"'; }
            
            $newBenefits = benefitsPagination($channelForm, $beginDate, $endDate, $statusForm, $items, $userRol, $channelQueryForm);
            
            return view('benefits.index',[
                "benefits" => $newBenefits,
                "cancelMotives" => $cancelMotives,
                "channels" => $channels,
                "status" => $status,
                "edit" => $edit,
                "cancel" => $cancel,
                "create" => $create,
                "items" => $items
            ]);
    }
    public function indexSecondary(request $request){     
            //Validate if User has view Permit
            $viewPermit = checkViewPermit('14', \Auth::user()->role_id);
            if(!$viewPermit){
                \Session::flash('ValidateUserRoute', 'No tiene acceso al modulo de ventas Individual.');
                return view('home');
            }
            
            //Obtain Channel
            $channelQuery = 'select cha.* from channels cha join agencies agen on agen.channel_id = cha.id where agen.id =  "'.\Auth::user()->agen_id.'"';
            $channel = DB::select($channelQuery);
            
            //Obtain Edit Permission
            $edit = checkExtraPermits('27',\Auth::user()->role_id);

            //Obtain Create Permission
            $create = checkExtraPermits('29',\Auth::user()->role_id);

            //Obtain Cancel Permission
            $cancel = checkExtraPermits('28',\Auth::user()->role_id);
        
            //Store Form Variables in Session
            if ($request->isMethod('post')){
                session(['benefitsSecondaryPlate' => $request->plate]);
                session(['benefitsSecondaryBeginDate' => $request->beginDate]);
                session(['benefitsSecondaryEndDate' => $request->endDate]);
                session(['benefitsSecondaryFirstName' => $request->first_name]);
                session(['benefitsSecondaryLastName' => $request->last_name]);
                session(['benefitsSecondaryDocument' => $request->document]);
                session(['benefitsSecondaryItems' => $request->items]);
            }

            //Pagination Items
            if(session('benefitsSecondaryItems') == null){ $items = 10; }else{ $items = session('benefitsSecondaryItems'); }

            //Form Variables
            $plate = session('benefitsSecondaryPlate');
            $beginDate = session('benefitsSecondaryBeginDate');
            $endDate = session('benefitsSecondaryEndDate');
            $firstName = session('benefitsSecondaryFirstName');
            $lastName = session('benefitsSecondaryLastName');
            $document = session('benefitsSecondaryDocument');
            
            //Validate User Role
            $userRol = 'true';
            if(\Auth::user()->role_id == 1 || \Auth::user()->role_id == 2 || \Auth::user()->role_id == 3){ $channelQueryForm = '1=1'; }elseif(\Auth::user()->role_id == 6){ $channelQueryForm = 'usr.id = '.\Auth::user()->id; }else{ $channelQueryForm = 'cha.id = "'.$channel[0]->id.'"'; }
           
            $newBenefits = benefisSecondaryPagination($plate, $beginDate, $endDate, $firstName, $lastName, $document, $items, $userRol, $channelQueryForm);
            
            return view('benefits.index_secondary',[
                "benefits" => $newBenefits,
                "edit" => $edit,
                "cancel" => $cancel,
                "create" => $create,
                "items" => $items
            ]);
    }
    
    function fetch_data(Request $request)
    {
     if($request->ajax())
     {
             
            //Validate if User has view Permit
            $viewPermit = checkViewPermit('13', \Auth::user()->role_id);
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
            $edit = checkExtraPermits('43',\Auth::user()->role_id);

            //Obtain Create Permission
            $create = checkExtraPermits('44',\Auth::user()->role_id);

            //Obtain Cancel Permission
            $cancel = checkExtraPermits('45',\Auth::user()->role_id);
        
            //Pagination Items
            if(session('benefitsItems') == null){ $items = 10; }else{ $items = session('benefitsItems'); }

            //Form Variables
            $channelForm = session('benefitsChannel');
            $beginDate = session('benefitsBeginDate');
            $endDate = session('benefitsEndDate');
            $statusForm = session('benefitsFirstName');
            
            //Validate User Role
            if(\Auth::user()->role_id == 1 || \Auth::user()->role_id == 2 || \Auth::user()->role_id == 3){ $userRol = null; $channelQueryForm = '1=1'; }else{ $userRol = 'true'; $channelQueryForm = ' channels.id = "'.$channel[0]->id.'"'; }
            
            $benefits = benefitsPagination($channelForm, $beginDate, $endDate, $statusForm, $items, $userRol, $channelQueryForm);
            return view('pagination.benefits',[
                "benefits" => $benefits,
                "edit" => $edit,
                "cancel" => $cancel,
                "create" => $create,
                "items" => $items
            ]);
     }
    }
    function fetch_data_secondary(Request $request)
    {
     if($request->ajax())
     {
            //Validate if User has view Permit
            $viewPermit = checkViewPermit('14', \Auth::user()->role_id);
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
            $edit = checkExtraPermits('27',\Auth::user()->role_id);

            //Obtain Create Permission
            $create = checkExtraPermits('29',\Auth::user()->role_id);

            //Obtain Cancel Permission
            $cancel = checkExtraPermits('28',\Auth::user()->role_id);
        
            //Pagination Items
            if(session('benefitsItems') == null){ $items = 10; }else{ $items = session('benefitsItems'); }

            //Form Variables
            $channelForm = session('benefitsChannel');
            $beginDate = session('benefitsBeginDate');
            $endDate = session('benefitsEndDate');
            $statusForm = session('benefitsFirstName');
            
            //Validate User Role
            if(\Auth::user()->role_id == 1 || \Auth::user()->role_id == 2 || \Auth::user()->role_id == 3){ $userRol = null; $channelQueryForm = '1=1'; }else{ $userRol = 'true'; $channelQueryForm = ' channels.id = "'.$channel[0]->id.'"'; }
            
            $benefits = benefitsPagination($channelForm, $beginDate, $endDate, $statusForm, $items, $userRol, $channelQueryForm);
            return view('pagination.benefits',[
                "benefits" => $benefits,
                "edit" => $edit,
                "cancel" => $cancel,
                "create" => $create,
                "items" => $items
            ]);
//            return view('pagination.benefits', 
//                    compact('benefits'),
//                    compact('cancel'),
//                    compact('edit'),
//                    compact('items')
//                    )->render();
     }
    }
    
    public function create(){
        //Channels
//        $channels = DB::table('channels')
//                    ->whereNotIn ('id', [1])
//                    ->get();
        $channels = \App\channels::all();
        
        return view('benefits.create',[
            "channels" => $channels
        ]);
    }
    
    public function store(request $request){
//        return $request;
        //Validate Percentage
        if($request['percentage']){
            $percentage = $request['percentage'];
        }else{
            $percentage = 0;
        }
        
        //Begin Date
        $beginDate = Carbon::parse($request['beginDate']);
        
        //End Date
        $endDate = Carbon::parse($request['endDate']);
        
        //NOW
        $now = Carbon::now();
        //VALIDATE STATUS
        if($beginDate > $now){
            $status = 2;
        }else{
            $status = 1;
        }
        
        //Check if Benefits is for All
        if($request['channel'] == '0'){
            //Obtain Channels
            $channels = \App\channels::all();
            
            foreach($channels as $channel){
                $benefits = new \App\benefits();
                $benefits->name = $request['name'];
                $benefits->status_id = $status;
                $benefits->code = $request['code'];
                $benefits->discount = $request['discount'];
                $benefits->discount_percentage = $percentage;
                $benefits->begin_date = $beginDate;
                $benefits->end_date = $endDate;
                $benefits->uses = $request['uses'];
                $benefits->channels_id = $channel['id'];
                $benefits->save();
            }
            
        }else{
                $benefits = new \App\benefits();
                $benefits->name = $request['name'];
                $benefits->status_id = $status;
                $benefits->code = $request['code'];
                $benefits->discount = $request['discount'];
                $benefits->discount_percentage = $percentage;
                $benefits->begin_date = $beginDate;
                $benefits->end_date = $endDate;
                $benefits->uses = $request['uses'];
                $benefits->channels_id = $request['channel'];
                $benefits->save();
        }
        
        
        //Benefits
        $benefitsQuery = 'select ben.*, IF(cha.name = "Todos", "Magnus", cha.name) as "channel", sta.name as "status"
                        from benefits ben 
                        join channels cha on cha.id = ben.channels_id
                        join status sta on sta.id = ben.status_id';
        $benefits = DB::select($benefitsQuery);
        
        //Return Filter Data
            $data = [
                "channel" => $request['channel'],
                "beginDate" => $request['beginDate'],
                "endDate" => $request['endDate'],
                "status" => $request['status']
            ];

            //Cancel Motive
            $cancelMotives = \App\cancel_motives::all();
            
            //Channels
            $channelsQuery = 'select cha.id, IF(cha.name = "Todos", "Magnus", cha.name) as "name" from channels cha';
            $channels = DB::select($channelsQuery);
            
            //Status
            $status = \App\status::find([1, 4]);
            
            //Edit Permission
            $editQuery = 'select mAct.* from 
                        menu_action mAct
                         join menu_action_rol mActRol on mActRol.menu_action = mAct.id
                         where mAct.action ="edit" and mAct.module = "benefits" and rol_id =  "'.\Auth::user()->role_id.'"';
            $edit = DB::select($editQuery);
            
            if($edit){
                $edit = "true";
            }else{
                $edit = "false";
            }
            
            //Cancel Permission
            $editQuery = 'select mAct.* from 
                        menu_action mAct
                         join menu_action_rol mActRol on mActRol.menu_action = mAct.id
                         where mAct.action ="cancel" and mAct.module = "benefits" and rol_id =  "'.\Auth::user()->role_id.'"';
            $cancel = DB::select($editQuery);
            
            if($cancel){
                $cancel = "true";
            }else{
                $cancel = "false";
            }
            
            return view('benefits.index',[
                "benefits" => $benefits,
                "cancelMotives" => $cancelMotives,
                "channels" => $channels,
                "data" => $data,
                "status" => $status,
                "edit" => $edit,
                "cancel" => $cancel
            ]);
    }
    
    public function editModal(request $request){
        //Obtain Beneftis
        $benefit = \App\benefits::find($request['data']['id']);
        
        //Carbon Dates
        $beginDate = Carbon::parse($benefit->begin_date);
        $endDate = Carbon::parse($benefit->end_date);
        
        $returnArray = [
            "beginDate" => $beginDate->format('d M Y'),
            "endDate" => $endDate->format('d M Y'),
            "uses" => $benefit->uses,
            "benefitsId" => $benefit->id
        ];
        
        return $returnArray;
    }
    
    public function editStore(request $request){
        //Format Dates Carbon
        $beginDate = Carbon::parse($request['data']['beginDate']);
        $endDate = Carbon::parse($request['data']['endDate']);
        
        $benefit = \App\benefits::find($request['data']['id']);
        $benefit->begin_date = $beginDate;
        $benefit->end_date = $endDate;
        $benefit->uses = $request['data']['uses'];
        if($benefit->save()){
            \Session::flash('editSuccess', 'El beneficio fue modificado correctamente.');
            $returnArray = [
                "success" => "true"
            ];
        }else{
            $returnArray = [
                "succes" => "false"
            ];
        }
        
        return $returnArray;
    }
    
    public function cancelStore(request $request){
        //Date
        $now = new \DateTime();
        
        //Obtain Benefit
        $benefit = \App\benefits::find($request['data']['benefitsIdCancel']);
        $benefit->cancel_motive_id = $request['data']['cancelMotive'];
        $benefit->cancel_date = $now;
        $benefit->cancel_user_id = \Auth::user()->id;
        $benefit->status_id = 4;
        if($benefit->save()){
            \Session::flash('cancelSuccess', 'El beneficio fue cancelado correctamente.');
            $returnArray = [
                "success" => "true"
            ];
        }else{
            $returnArray = [
                "success" => "false"
            ];
        }
        return $returnArray;
        
    }
    
    public function scheduleModal(request $request){
        //Validate if Sales is Active and Benefit is Active
        $query = 'select vehi.plate as "plate",
                sal.id as "salId",
                ben.code as "code",
                ben.name as "name",
                IF(cha.name = "Todos", "Magnus", cha.name) as "channel",
                ben.begin_date as "beginDate",
                ben.end_date as "endDate",
                bvsal.id as "bVSalId", 
                ben.uses as "uses"
                from benefits ben
                join benefits_vehicles_sales bvsal on bvsal.benefits_id = ben.id
                join vehicles_sales vsal on vsal.id = bvsal.vsal_id
                join vehicles vehi on vehi.id = vsal.vehicule_id
                join sales sal on sal.id = vsal.sales_id
                join channels cha on cha.id = ben.channels_id
                where vsal.status_id = 1 and ben.status_id not in (4) and sal.status_id = 1 and ben.discount = "NO" and vehi.plate = "'.$request['data']['plateModal'].'"';
        
        $benefitsData = DB::select($query);
        
        
        
        if($benefitsData){
            $benefitsTable = '<table align="center" class="table table-bordered">
                                <thead>
                                  <tr>
                                    <th align="center">Placa</th>
                                    <th align="center">Venta</th>
                                    <th align="center">Codigo</th>
                                    <th align="center">Beneficio</th>
                                    <th align="center">Canal</th>
                                    <th align="center">Vigencia Desde</th>
                                    <th align="center">Vigencia Hasta</th>
                                    <th align="center">Accciones</th>
                                  </tr>
                                </thead>
                                <tbody>';

            foreach($benefitsData as $benefit){
                $benefitsTable .= '<tr>
                                    <td align="center">'.$benefit->plate.'</td>
                                    <td align="center">'.$benefit->salId.'</td>
                                    <td align="center">'.$benefit->code.'</td>
                                    <td align="center">'.$benefit->name.'</td>
                                    <td align="center">'.$benefit->channel.'</td>
                                    <td align="center">'.$benefit->beginDate.'</td>
                                    <td align="center">'.$benefit->endDate.'</td>';
                
                //Validate if it has Benefits Available
                $query = 'select count(uses.id) as "uses"
                         from benefits_vehicles_sales_uses uses
                         where benefits_vsal_id = "'.$benefit->bVSalId.'"';
                
                $uses = DB::select($query);
                
                //Uses Left
                $usesLeft = $benefit->uses - $uses[0]->uses;
                
                if($usesLeft > 0){
                    $benefitsTable .='  <td align="center"><input type="checkbox" name="check" class="check" value="'.$benefit->bVSalId.'" onclick="checkValidate()"></td>
                                      </tr>';
                }else{
                    $benefitsTable .= '<td></td>';
                }
                
            }

            $benefitsTable .= ' </tbody>
                              </table>';
            
            $returnArray = [
                "success" => "true",
                "data" => $benefitsTable
            ];
            
        }else{
            $returnArray = [
                "success" => "false"
            ];
                    
        }
        
        
        return $returnArray;
    }
    
    public function scheduleStore(request $request){
//        return $request;
        $now = new \DateTime();
        foreach($request['data']['bVsalId'] as $use){
            $benefitUse = new \App\benefits_vehicles_sales_uses;
            $benefitUse->benefits_vsal_id = $use;
            $benefitUse->date = $now;
            $benefitUse->user_id = \Auth::user()->id;
            $benefitUse->save();
        }
        $returnArray = [
                "success" => "true"
            ];
        \Session::flash('successScheduleStore', 'La(s) venta(s) fueron Renovada(s) correctamente.');
        return $returnArray;
    }
}
