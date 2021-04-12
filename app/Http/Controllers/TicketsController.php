<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Jobs\TicketsEmailJobs;
use App\Jobs\TicketsCommentEmailJobs;

class TicketsController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('validateUserRoute');
    }

    public function index(request $request) {

        //Obtain Edit Permission
        $edit = checkExtraPermits('55', \Auth::user()->role_id);

        //Obtain Create Permission
        $create = checkExtraPermits('57', \Auth::user()->role_id);

        //Obtain Cancel Permission
        $cancel = checkExtraPermits('56', \Auth::user()->role_id);

        //Obtain Cancel Permission
        $createBranch = checkExtraPermits('57', \Auth::user()->role_id);

        //Store Form Variables in Session
        if ($request->isMethod('post')) {
            session(['ticketsItems' => $request->items]);
            session(['ticketsNumber' => $request->number]);
            session(['ticketsFirstName' => $request->firstName]);
            session(['ticketsLastName' => $request->lastName]);
            session(['ticketsStatus' => $request->status]);
            session(['ticketsBeginDate' => $request->beginDate]);
            session(['ticketsEndDate' => $request->endDate]);
            session(['ticketsMenu' => $request->menu]);
            session(['ticketsTicketType' => $request->ticketType]);
        }

        //Form Variables
        $number = session('ticketsNumber');
        $firstName = session('ticketsFirstName');
        $lastName = session('ticketsLastName');
        $statusForm = session('ticketsStatus');
        $beginDate = session('ticketsBeginDate');
        $endDate = session('ticketsEndDate');
        $menuForm = session('ticketsMenu');
        $ticketTypeForm = session('ticketsTicketType');

        //Pagination Items
        if (session('ticketsItems') == null) { $items = 10; } else { $items = session('ticketsItems'); }

        $cities = \App\city::all();
        $status = \App\status::find([3, 4, 6, 20]);
        $menus = \App\menu::whereIn('id', [1, 9, 10, 12, 13, 14, 15, 35, 53, 54, 57, 58, 59, 60])->whereNotIn('id', [8, 16])->orderBy('name')->get();
        $ticketType = \App\ticket_type::all();

        //Validate User
        if(\Auth::user()->type_ticket_access == 1){ $adminUser = true; }else{ $adminUser = false; }
        
        //Providers
        $tickets = tickets($number, $firstName, $lastName, $statusForm, $beginDate, $endDate, $menuForm, $ticketTypeForm, $adminUser, $items);

        return view('tickets.index', [
            'tickets' => $tickets,
            'items' => $items,
            'cities' => $cities,
            'edit' => $edit,
            'cancel' => $cancel,
            'create' => $create,
            'createBranch' => $createBranch,
            'status' => $status,
            'menus' => $menus,
            'ticketType' => $ticketType
        ]);
    }

    function fetch_data(request $request) {
        if ($request->ajax()) {
            //Obtain Edit Permission
            $edit = checkExtraPermits('55', \Auth::user()->role_id);

            //Obtain Create Permission
            $create = checkExtraPermits('57', \Auth::user()->role_id);

            //Obtain Cancel Permission
            $cancel = checkExtraPermits('56', \Auth::user()->role_id);

            //Obtain Cancel Permission
            $createBranch = checkExtraPermits('57', \Auth::user()->role_id);

            //Store Form Variables in Session
            if ($request->isMethod('post')) {
                session(['ticketsItems' => $request->items]);
                session(['ticketsNumber' => $request->number]);
                session(['ticketsFirstName' => $request->firstName]);
                session(['ticketsLastName' => $request->lastName]);
                session(['ticketsStatus' => $request->status]);
                session(['ticketsBeginDate' => $request->beginDate]);
                session(['ticketsEndDate' => $request->endDate]);
                session(['ticketsMenu' => $request->menu]);
                session(['ticketsTicketType' => $request->ticketType]);
            }

            //Form Variables
            $number = session('ticketsNumber');
            $firstName = session('ticketsFirstName');
            $lastName = session('ticketsLastName');
            $statusForm = session('ticketsStatus');
            $beginDate = session('ticketsBeginDate');
            $endDate = session('ticketsEndDate');
            $menuForm = session('ticketsMenu');
            $ticketTypeForm = session('ticketsTicketType');
            
            //Validate User
            if(\Auth::user()->type_ticket_access == 1){ $adminUser = true; }else{ $adminUser = false; }

            //Pagination Items
            if (session('ticketsItems') == null) { $items = 10; } else { $items = session('ticketsItems'); }

            $cities = \App\city::all();
            $status = \App\status::find([3, 4, 6, 20]);
            $menus = \App\menu::whereIn('id', [1, 9, 10, 12, 13, 14, 15, 35, 53, 54, 57, 58, 59, 60])->whereNotIn('id', [8, 16])->orderBy('name')->get();
            $ticketType = \App\ticket_type::all();

            //Providers
            $tickets = tickets($number, $firstName, $lastName, $statusForm, $beginDate, $endDate, $menuForm, $ticketTypeForm, $adminUser, $items);

            return view('pagination.tickets', [
                'tickets' => $tickets,
                'items' => $items,
                'cities' => $cities,
                'edit' => $edit,
                'cancel' => $cancel,
                'create' => $create,
                'createBranch' => $createBranch,
                'status' => $status,
                'menus' => $menus,
                'ticketType' => $ticketType
            ]);
        }
    }

    public function create() {
        //Data
        $ticketType = \App\ticket_type::all();
        $menu = \App\menu::whereIn('id', [1, 9, 10, 12, 13, 14, 15, 35, 53, 54, 57, 58, 59, 60])->whereNotIn('id', [8, 16])->orderBy('name')->get();
        return view('tickets.create', [
            'ticketType' => $ticketType,
            'menu' => $menu
        ]);
    }

    public function ticketTypeDetail(request $request) {
        $ticketTypeDetail = \App\ticket_type_detail::where('ticket_type_id', '=', $request['id'])->get();

        $returnData = '<option value="">--Escoja Una--</option>';
        foreach ($ticketTypeDetail as $typ) {
            $returnData .= '<option value="' . $typ->id . '">' . $typ->name . '</option>';
        }
        return $returnData;
    }

    public function store(request $request) {
        //DateTime
        $now = new \DateTime();
        
        $picture1 = null;
        $picture2 = null;

        //Store Ticket
        $ticket = new \App\ticket();
        $ticket->ticket_type_detail_id = $request['ticketTypeDetail'];
        $ticket->request_user_id = \Auth::user()->id;
        $ticket->assign_user_id = 11;
        $ticket->begin_date = $now;
        $ticket->status_id = 23;
        $ticket->menu_id = $request['menuSelect'];
        $ticket->title = $request['title'];
        $ticket->save();
        
        if(isset($request['file'])){
            $validation = Validator::make($request->all(), [
                        'file' => 'required|max:10048'
            ]);

            if ($validation->passes()) {
                //Vehicle Folder
                $ticketFolder = public_path('tickets/').$ticket->id.'/';
                //Create Vehicle Folder
                if (!file_exists($ticketFolder)) {
                    mkdir($ticketFolder, 0777, true);
                }

                $image = $request->file('file');
                $new_name = $image->getClientOriginalName();
                $image->move(public_path('tickets/'.$ticket->id), $new_name);
                
                $picture1 = getAppRoute() . '/tickets/' .$ticket->id . '/'. $new_name;     
                $picture2 = $image->getClientOriginalName();
            }else{
                $deletedRows = \App\ticket::where('id','=',$ticket->id)->delete();
                return 'false';
            }
        }
                
        $ticketDetail = new \App\ticket_detail();
        $ticketDetail->description = $request['description'];
        $ticketDetail->request_user_id = \Auth::user()->id;
        $ticketDetail->assign_user_id = 11;
        $ticketDetail->date = $now;
        $ticketDetail->status_id = 23;
        $ticketDetail->ticket_id = $ticket->id;
        $ticketDetail->picture1 = $picture1;
        $ticketDetail->picture2 = $picture2;
        $ticketDetail->save();
        
        $user = \App\User::find(11);
        
        //Mail
        $job = (new TicketsEmailJobs($ticket->id, $user->email));
        dispatch($job);
    }

    function upload(Request $request) {
        $validation = Validator::make($request->all(), [
                    'select_file'.$request['side'] => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validation->passes()) {
            //Random Temp Name
            $name = generateRandomString();

            //Ticket Folder
            $vehiFolder = public_path('images/tickets/') . $name . '/';
            //Create Vehicle Folder
            if (!file_exists($vehiFolder)) {
                mkdir($vehiFolder, 0777, true);
            }

            $image = $request->file('select_file'.$request['side']);
            $new_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/tickets/' . $name), $new_name);

            //Activate SALE
            return response()->json([
                        'message' => 'Image Upload Successfully',
                        'uploaded_image' => '<a href="' . '/images/tickets/' . $name . '/' . $new_name . '" target="_blank"><img src="' . '/images/tickets/' . $name . '/' . $new_name . '" class="img-thumbnail" width="300" height="20" /></a>',
                        'class_name' => 'alert-success',
                        'Success' => 'true',
                        'name' => $name,
                        'newName' => $new_name
            ]);
        } else {
            return response()->json([
                        'message' => 'Debe subir la imagen en un formato valido',
                        'uploaded_image' => '',
                        'class_name' => 'alert-danger',
                        'Success' => 'false'
            ]);
        }
    }

    function delete(Request $request) {

        return response()->json([
                    'message' => 'Image Upload Successfully',
                    'uploaded_image' => '',
                    'class_name' => 'alert-success',
                    'Success' => 'true'
        ]);
    }

    public function detail($ticketID) {
        $ticketDetail = ticketsDetail($ticketID);
        $ticket = \App\ticket::find($ticketID);
        $menu = \App\menu::find($ticket->menu_id);
        $ticketTypeDetail = \App\ticket_type_detail::find($ticket->ticket_type_detail_id);
        $ticketType = \App\ticket_type::find($ticketTypeDetail->ticket_type_id);
        $ticketStatus = \App\status::find($ticket->status_id);
        
        return view('tickets.detail',[
            'ticketDetail' => $ticketDetail,
            'ticketsId' => $ticketID,
            'ticketStatus' => $ticketStatus,
            'ticket' => $ticket,
            'menu' => $menu,
            'ticketTypeDetail' => $ticketTypeDetail,
            'ticketType' => $ticketType
        ]);
    }
    
    public function downloadPicture($picture, $id){
        $ticketDetail = \App\ticket_detail::find($id);
        if($picture == 1){
            if (file_exists(public_path($ticketDetail->picture1))) {
                return response()->download(public_path($ticketDetail->picture1));
            } else {
                echo '<span style="color:red;font-weight:bold">El archivo no se encuentra disponible en el servidor</span>';
            }
        }
        if($picture == 2){
            if (file_exists(public_path($ticketDetail->picture2))) {
                return response()->download(public_path($ticketDetail->picture2));
            } else {
                echo '<span style="color:red;font-weight:bold">El archivo no se encuentra disponible en el servidor</span>';
            }
        }
        if($picture == 3){
            if (file_exists(public_path($ticketDetail->picture3))) {
                return response()->download(public_path($ticketDetail->picture3));
            } else {
                echo '<span style="color:red;font-weight:bold">El archivo no se encuentra disponible en el servidor</span>';
            }
        }
    }
    
    public function storeComment(request $request){
        //DateTime
        $now = new \DateTime();
        
        $picture1 = null;
        $picture2 = null;
        
        $ticket = \App\ticket::find($request['ticketsId']);
        
        //Ticket Status
        if(\Auth::user()->id == $ticket->assign_user_id){ $status = 21; }else{ $status = 22; }
        if($request['closeTicket'] == 'close'){ $status = 20; }
        
        if(isset($request['file'])){
            $validation = Validator::make($request->all(), [
                        'file' => 'required|max:10048'
            ]);

            if ($validation->passes()) {
                //Vehicle Folder
                $ticketFolder = public_path('tickets/').$ticket->id.'/';
                //Create Vehicle Folder
                if (!file_exists($ticketFolder)) {
                    mkdir($ticketFolder, 0777, true);
                }

                $image = $request->file('file');
                $new_name = $image->getClientOriginalName();
                $image->move(public_path('tickets/'.$ticket->id), $new_name);
                
                $picture1 = getAppRoute() . '/tickets/' .$ticket->id . '/'. $new_name;     
                $picture2 = $image->getClientOriginalName();
            }else{
                $deletedRows = \App\ticket::where('id','=',$ticket->id)->delete();
                return 'false';
            }
        }
        
        
        $ticketDetail = new \App\ticket_detail();
        $ticketDetail->description = $request['description'];
        $ticketDetail->request_user_id = \Auth::user()->id;
        $ticketDetail->assign_user_id = 11;
        $ticketDetail->date = $now;
        $ticketDetail->status_id = $status;
        $ticketDetail->ticket_id = $request['ticketsId'];
        $ticketDetail->picture1 = $picture1;
        $ticketDetail->picture2 = $picture2;
        $ticketDetail->save();
        
        $ticketUpdate = \App\ticket::find($request['ticketsId']);
        $ticketUpdate->status_id = $status;
        $ticketUpdate->save();
        
        $user = \App\User::find($ticket->request_user_id);
        
        //Mail
        $job = (new TicketsCommentEmailJobs($ticketDetail->id, $user->email, $ticket->id));
        dispatch($job);
    }

}
