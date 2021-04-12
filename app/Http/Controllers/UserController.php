<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use DB;
Use Redirect;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Laravel\Passport\HasApiTokens;
use Illuminate\Pagination\Paginator;

class UserController extends Controller {
    
    use HasApiTokens;
    
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('validateUserRoute');
    }

    
    //Show List Users
    public function index(request $request) {
        //Validate if User has view Permit
        $viewPermit = checkViewPermit('9', \Auth::user()->role_id);
        if(!$viewPermit){
            \Session::flash('ValidateUserRoute', 'No tiene acceso al modulo de Usuarios.');
            return view('home');
        }
        
        //Obtain Edit Permission
        $edit = checkExtraPermits('8',\Auth::user()->role_id);
        
        //Obtain Create Permission
        $create = checkExtraPermits('20',\Auth::user()->role_id);
        
        //Obtain Cancel Permission
        $cancel = checkExtraPermits('9',\Auth::user()->role_id);
        
        //Obtain Channel
        $channelQuery = 'select cha.* from channels cha join agencies agen on agen.channel_id = cha.id where agen.id =  "'.\Auth::user()->agen_id.'"';
        $channel = DB::select($channelQuery);
        
        //Obtain Information
        $roles = DB::select('select * from rols');
        
        $channels = DB::select('select * from channels where canalnegodes is not null and status_id = 1 order by canalnegodes');
        $agencies = DB::select('select * from agencies where channel_id = ?', [request('channel')]);

        //Store Form Variables in Session
        if ($request->isMethod('patch')) {
            session(['usersFirstName' => $request->first_name]);
            session(['usersLastName' => $request->last_name]);
            session(['usersDocument' => $request->document]);
            session(['usersEmail' => $request->email]);
            session(['usersRol' => $request->rol]);
            session(['usersChannel' => $request->channel]);
            session(['usersAgency' => $request->agency]);
            session(['usersItems' => $request->items]);
            $currentPage = 1;
            session(['usersPage' => 1]);
        }else{
            $currentPage = session('usersPage');
        }

        //Pagination Items
        if (session('usersItems') == null) { $items = 10; } else { $items = session('usersItems'); }

        //Form Variables
        $firstName = session('usersFirstName');
        $lastName = session('usersLastName');
        $document = session('usersDocument');
        $email = session('usersEmail');
        $rol = session('usersRol');
        $channelForm = session('usersChannel');
        $agencyForm = session('usersAgency');
        
        //Validate User Role
        if(\Auth::user()->role_id == '3'){ $sqlUser = 'users.role_id not in (1,2)'; }elseif(\Auth::user()->role_id == '2'){ $sqlUser = 'users.role_id not in (1)'; }else{ $sqlUser = '1=1'; }
        
         // Make sure that you call the static method currentPageResolver()
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });
        
        //New Users
        $newUsers = user($firstName, $lastName, $document, $email, $rol, $channelForm, $agencyForm, $items, $sqlUser);

        return view('user.index', [
            'users' => $newUsers,
            'roles' => $roles,
            'channels' => $channels,
            'agencies' => $agencies,
            'items' => $items,
            'edit' => $edit,
            'cancel' => $cancel,
            'create' => $create
        ]);
    }
    
    function fetch_data(request $request){
        if($request->ajax()){   
            //Page
            session(['usersPage' => $request->page]);
                 
        //Validate if User has view Permit
        $viewPermit = checkViewPermit('9', \Auth::user()->role_id);
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
        $edit = checkExtraPermits('8',\Auth::user()->role_id);
        
        //Obtain Create Permission
        $create = checkExtraPermits('20',\Auth::user()->role_id);
        
        //Obtain Cancel Permission
        $cancel = checkExtraPermits('9',\Auth::user()->role_id);
        
            //Pagination Items
        if (session('usersItems') == null) { $items = 10; } else { $items = session('usersItems'); }

            //Form Variables
        $firstName = session('usersFirstName');
        $lastName = session('usersLastName');
        $document = session('usersDocument');
        $email = session('usersEmail');
        $rol = session('usersRol');
        $channelForm = session('usersChannel');
        $agencyForm = session('usersAgency');
        
        //Validate User Role
        if(\Auth::user()->role_id == '3'){ $sqlUser = 'users.role_id not in (1,2)'; }elseif(\Auth::user()->role_id == '2'){ $sqlUser = 'users.role_id not in (1)'; }else{ $sqlUser = '1=1'; }
        
        //New Users
        $newUsers = user($firstName, $lastName, $document, $email, $rol, $channelForm, $agencyForm, $items, $sqlUser);

        return view('pagination.users', [
            'users' => $newUsers,
            'items' => $items,
            'edit' => $edit,
            'cancel' => $cancel,
            'create' => $create
        ]);
        }
    }

    //Create New User
    public function create() {
        //Validate Create Permission
        $edit = checkExtraPermits('20',\Auth::user()->role_id);
        if(!$edit){
            \Session::flash('ValidateUserRoute', 'No tiene acceso a crear Usuarios.');
            return view('home');
        }

        //Obtain Information
        $document = DB::select('select * from documents where id in (1,3)');
        $type = DB::select('select * from type_users');
        
        //Validate if theres a INSPECTOR or AUDITOR MEDICO
        $inspector = \App\User::where('role_id','=',45)->get();
        if(!$inspector->isEmpty()){
            $inspectorQuery = ' AND id not in (45)';
        }else{
            $inspectorQuery = ' ';
        }
        $auditor = \App\User::where('role_id','=',46)->get();
        if(!$auditor->isEmpty()){
            $auditorQuery = ' AND id not in (46)';
        }else{
            $auditorQuery = ' ';
        }
        
        
        $rolQuery = 'select * from rols WHERE 1 = 1 '.$inspectorQuery . $auditorQuery;
        //Validate User Role
        if(\Auth::user()->role_id == '3'){
            $rolQuery .= ' AND id not in (1,2)';
        }elseif(\Auth::user()->role_id == '2'){//
            $rolQuery .= ' AND id not in (1)';
        }
        $rol = DB::select($rolQuery);
        $country = DB::select('select * from countries');
        $channels = DB::select('select * from channels where id not in (4) and canalnegodes is not null and canalnegodes != "" and status_id = 1');
        $agencies = DB::select('select * from agencies');
        $typeSucre  = \App\type_user_sucre::all();
        
        return view('user/create', [
            'documents' => $document,
            'types' => $type,
            'rols' => $rol,
            'countries' => $country,
            'channels' => $channels,
            'agencies' => $agencies,
            'typeSucre' => $typeSucre
        ]);
    }

    //Store New User
    public function store() {
//        return request();
        //Obtain Pass
        $pass = request('password');

        //Validate Email
        $emailQuery = DB::select('select * from users where email = ?', [request('email')]);

        //Validate Document/ID
        $documentQuery = DB::select('select * from users where document = ?',[request('document')]);
        
        $validateId = false;
        //Validate if Documents is Real
        if(request('document_id') == 1){
            if(!validateId(request('document'))){
                $validateId = true;
            }
        }
        
        $letters = string_has_letters(request('password'));
        $numbers = string_has_numbers(request('password'));
        $chars = string_has_special_chars(request('password'));
        $diffChars = string_has_different_chars(request('password'));
            
        
        //Check Response
        if ($emailQuery) {
            $returnArray = [
                "success" => "false",
                "msg" => 'El correo ya se encuentra registado',
                "input" => 'email'
            ];
        }elseif($validateId){
            $returnArray = [
                "success" => "false",
                "msg" => 'El Documento ingresado es incorrecto',
                "input" => 'document'
            ];
        }elseif($documentQuery) {
            $returnArray = [
                "success" => "false",
                "msg" => 'El documento/ID ya se encuentra registrado',
                "input" => 'document'
            ];
        }elseif(request('password') != request('passwordCheck')) {
            $returnArray = [
                "success" => "false",
                "msg" => 'Las contraseñas no coinciden',
                "input" => 'password'
            ];
        }elseif(!$letters){
            $returnArray = [
                "success" => "false",
                "msg" => 'La contraseña no incluye letras',
                "input" => 'password'
            ];
        }elseif(!$numbers){
            $returnArray = [
                "success" => "false",
                "msg" => 'La contraseña no incluye numeros',
                "input" => 'password'
            ];
        }elseif(!$chars){
            $returnArray = [
                "success" => "false",
                "msg" => 'La contraseña no incluye Caracteres Especiales',
                "input" => 'password'
            ];
        }elseif($diffChars){
            $returnArray = [
                "success" => "false",
                "msg" => 'Solo se permiten (./*-_+,)',
                "input" => 'password'
            ];
        }elseif(strlen(request('password')) < 7){
            $returnArray = [
                "success" => "false",
                "msg" => 'La contraseña debe ser mayor a 7 caracteres',
                "input" => 'password'
            ];
        }else{
            $user = new \App\user();
            $user->first_name = request('first_name');
            $user->last_name = request('last_name');
            $user->document = request('document');
            $user->password = Hash::make(request('password'));
            $user->document_id = request('document_id');
            $user->role_id = request('rol');
            $user->email = request('email');
            $user->type_id = request('tipo');
            $user->city_id = 1;
            $user->agen_id = request('agency');
            $user->status_id = 1;
            $user->type_user_sucre_id = request('typeSucre');
            $user->save();
            
//            $BBDD = session('db_name_other');
//            $newUser = new \App\user;
//            $newUser->setConnection($BBDD);
//            $newUser->first_name = request('first_name');
//            $newUser->last_name = request('last_name');
//            $newUser->document = request('document');
//            $newUser->password = Hash::make(request('password'));
//            $newUser->document_id = request('document_id');
//            $newUser->role_id = request('rol');
//            $newUser->email = request('email');
//            $newUser->type_id = request('tipo');
//            $newUser->city_id = 1;
//            $newUser->agen_id = request('agency');
//            $newUser->status_id = 1;
//            $newUser->save();
            
            \Session::flash('userStoreSuccess', 'El usuario fue creado correctamente');
//            
             $returnArray = [
                "success" => "true",
                "msg" => 'Store Successufull'
            ];
        }
//            return redirect('/user');
            return $returnArray;
    }

    //Patch New User
    public function patch() {
        //Validate Email is not in other user
        $query = 'select * from users where email = "'.request('email').'" and id not in ('.request('id').')';
        $validate = DB::select($query);
        if($validate){
            return redirect()->back()->withInput()->withErrors(['El correo ya se encuentra registrado']);
        }else{
            //Validate User Document
            $user = \App\user::find(request('id'));
            $user->first_name = request('first_name');
            $user->last_name = request('last_name');
            $user->document_id = request('document_id');
            $user->document = request('document');
            $user->role_id = request('rol');
            $user->email = request('email');
            $user->type_id = request('tipo');
            $user->agen_id = request('agency');
            $user->save();
            return redirect('/user');
        }
    }

    //Update User
    public function update($id) {
        //Decrypt Data
        $data = Crypt::decrypt($id);


        //Obtain Information
//        $user = DB::select('select * from users where id = ?', [$data]);
        $user = DB::select('Select usr.*, cha.id as "channel", age.id as "agency"
                        from users usr
                        left join agencies age on age.id = usr.agen_id
                        left join channels cha on cha.id = age.channel_id
                        left join status sta on sta.id = usr.status_id
                        left join documents doc on doc.id = usr.document_id where usr.id = ?', [$data]);
//        return $user;
        $document = DB::select('select * from documents');
        $type = DB::select('select * from type_users');
        
        if($user[0]->type_user_sucre_id == 1){
            $rol = DB::select('select * from rols where rol_entity_id = 1');
            $channel = DB::select('select * from channels where canalnegoid in (9)');
        }else{
            $rol = DB::select('select * from rols where rol_entity_id = 2');
            $channel = DB::select('select * from channels where id not in (4,9) and canalnegodes is not null and status_id = 1');
        }
        
        //Validate City
        $city = DB::select('select * from cities where id = ?', [$user[0]->city_id]);
        $agency = DB::select('select * from agencies where channel_id = ?', [$user[0]->channel]);
//        if ($user[0]->city_id != null) {
//            $city = DB::select('select * from cities where id = ?', [$user[0]->city_id]);
//            $channel = DB::select('select * from channels where id not in (4) and city_id = ?', [$user[0]->city_id]);
//        } else {
//            $city = null;
//            $channel = null;
//        }
        //Validate Province
        if ($city != null) {
            $province = DB::select('select * from provinces where id =?', [$city[0]->province_id]);
        } else {
            $province = null;
        }
        $country = DB::select('select * from countries');
        //Validate Agency
//        if ($channel) {
//            $agency = DB::select('select * from agencies where id = ?', [$user[0]->agency]);
//        } else {
//            $agency = null;
//        }
//        return $province;
        $status = DB::select('select * from status');
        $typeSucre = \App\type_user_sucre::all();
        return view('user/update', [
            'user' => $user,
            'documents' => $document,
            'types' => $type,
            'rols' => $rol,
            'countries' => $country,
            'cities' => $city,
            'provinces' => $province,
            'channels' => $channel,
            'agencies' => $agency,
            'status' => $status,
            'typeSucre' => $typeSucre
        ]);
    }

    public function updatePass($id) {
        //Decrypt Data
        $data = Crypt::decrypt($id);
        return view('/user/update_pass', [
            'id' => $data
        ]);
    }

    public function patchPass() {
        if (request('password') != request('passwordCheck')) {
            return Redirect::back()->withInput()->with('Error', 'Las contraseñas no coinciden');
        } else {
//            return request();
            $user = \App\user::find(request('id'));
            $user->password = Hash::make(request('password'));
            $user->save();
            return Redirect::back()->withInput()->with('Success', 'Contraseña cambiada correctamente');
        }
    }

    public function inactiveUser($id) {
        //Decrypt Data
        $data = Crypt::decrypt($id);
        $user = \App\user::find($data);
        if($user->status_id == 1){
            $user->status_id = 2;
            $msg = 'El usuario fue inactivado correctamente';
        }else{
            $user->status_id = 1;
            $msg = 'El usuario fue activado correctamente';
        }
        $user->save();
                //Initiate Inputs Variable
        $inputs = false;
        //Query
        $sql = 'Select usr.*, cha.name as "channel", sta.name as "estado", doc.name as "documento"
                            from users usr
                            left join agencies age on age.id = usr.agen_id
                            left join channels cha on cha.id = age.channel_id
                            left join status sta on sta.id = usr.status_id
                            left join documents doc on doc.id = usr.document_id WHERE usr.status_id = 1';

        //Validate User Input
        if (request('first_name') != null) {
            $sql .= ' AND usr.first_name like "%' . request('first_name') . '%"';
            $inputs = true;
        }
        if (request('last_name') != null) {
            $sql .= ' AND usr.last_name like "%' . request('last_name') . '%"';
            $inputs = true;
        }
        if (request('document') != null) {
            $sql .= ' AND usr.document like "%' . request('document') . '%"';
            $inputs = true;
        }
        if (request('email') != null) {
            $sql .= ' AND usr.email like "%' . request('email') . '%"';
            $inputs = true;
        }
        if (request('rol') != null) {
            $sql .= ' AND usr.role_id = "' . request('rol') . '"';
            $inputs = true;
        }
        if (request('channel') != null) {
            $sql .= ' AND cha.id = "' . request('channel') . '"';
            $inputs = true;
        }
        if (request('agency') != null) {
            $sql .= ' AND age.id = "' . request('agency') . '"';
            $inputs = true;
        }

        $data = array('first_name' => request('first_name'),
            'last_name' => request('last_name'),
            'document' => request('document'),
            'email' => request('email'),
            'rol' => request('rol'),
            'channel' => request('channel'),
            'agency' => request('agency'));

        //Obtain Information
        $users = DB::select($sql);
        $roles = DB::select('select * from rols');
        $channels = DB::select('select * from channels');
        $agencies = DB::select('select * from agencies where channel_id = ?', [request('channel')]);

        return Redirect::back()->withInput()->with('Inactive', $msg);
        
        //Validate Inputs
//        if ($inputs == true) {
//
//            return view('user.index', [
//                'users' => $users,
//                'roles' => $roles,
//                'channels' => $channels,
//                'agencies' => $agencies,
//                'data' => $data
//            ]);
//        } else {
//            return view('user.index', [
//                'users' => $users,
//                'roles' => $roles,
//                'channels' => $channels,
//                'agencies' => $agencies,
//                'data' => $data
//            ]);
//        }
    }
    
    public function passwordChange(){
        return view('user.passwordChange');
    }
    public function passwordUpdate(request $request){
//        return $request;
        //Validate is the same input
        if($request->password == $request->passwordCheck){
            //Validate if it has letter
            $letters = string_has_letters($request->password);
            if($letters){
                //Validate if it has number
                $numbers = string_has_numbers($request->password);
                if($numbers){
                    //Validate if it has special chars
                    $chars = string_has_special_chars($request->password);
                    if($chars){
                        //Validate if it has different special Chars
                        $diff = string_has_different_chars($request->password);
                        if(!$diff){
                            //Validate if it has 7 characters
                            if(strlen($request->password) >= 7){
                                $user = \App\user::find(\Auth::user()->id);
                                $user->password = Hash::make($request->password);
                                $user->save();
                                return Redirect::back()->with('success', 'La contraseña ha sido cambiada satisfactoriamente');
                            }else{
                                return Redirect::back()->with('error', 'La contraseña debe tener al menos 7 caracteres');
                            }
                        }else{
                            return Redirect::back()->with('error', 'Solo se permiten los siguientes caracteres especiales (./*-_+,)');
                        }
                    }else{
                        return Redirect::back()->with('error', 'La contraseña debe tener al menos un carecter especial (./*-_+,)');
                    }
                }else{
                    return Redirect::back()->with('error', 'La contraseña no tiene numeros');
                }
            }else{
                return Redirect::back()->with('error', 'La contraseña no tiene letras');
            }
        }else{
            return Redirect::back()->with('error', 'Las contraseñas no coinciden');
        }
    }
    public function passwordUpdateModal(request $request){
//        return $request;
        //Validate is the same input
        if($request->password == $request->passwordCheck){
            //Validate if it has letter
            $letters = string_has_letters($request->password);
            if($letters){
                //Validate if it has number
                $numbers = string_has_numbers($request->password);
                if($numbers){
                    //Validate if it has special chars
                    $chars = string_has_special_chars($request->password);
                    if($chars){
                        //Validate if it has different special Chars
                        $diff = string_has_different_chars($request->password);
                        if(!$diff){
                            //Validate if it has 7 characters
                            if(strlen($request->password) >= 7){
                                $user = \App\user::find($request->idUser);
                                $user->password = Hash::make($request->password);
                                $user->save();
                                
                                $returnAray = [
                                    "success" => "true",
                                    "msg" => "La contraseña ha sido cambiada satisfactoriamente"
                                ];
                            }else{
                                $returnAray = [
                                    "success" => "false",
                                    "msg" => "La contraseña debe tener al menos 7 caracteres"
                                ];
                            }
                        }else{
                            $returnAray = [
                                "success" => "false",
                                "msg" => "Solo se permiten los siguientes caracteres especiales (./*-_+,)"
                            ];
                        }
                    }else{
                        $returnAray = [
                            "success" => "false",
                            "msg" => "La contraseña debe tener al menos un carecter especial (./*-_+,)"
                        ];
                    }
                }else{
                    $returnAray = [
                        "success" => "false",
                        "msg" => "La contraseña no tiene numeros"
                    ];
                }
            }else{
                $returnAray = [
                    "success" => "false",
                    "msg" => "La contraseña no tiene letras"
                ];
            }
        }else{
            $returnAray = [
                "success" => "false",
                "msg" => "Las contraseñas no coinciden"
            ];
        }
        return $returnAray;
    }
    
    public function typeSucreChange(request $request){
        $returnRol = '<option value="">-- ESCOJA UNA --</option>';
        $returnChannel = '<option value="">-- ESCOJA UNA --</option>';
        if($request['id'] == 1){
            //Validate if Rols already Exists
            $inspectorSearch = \App\User::where('role_id','=','45')->where('status_id','=',1)->get();
            $auditorSearch = \App\User::where('role_id','=','46')->where('status_id','=',1)->get();
            $rols = \App\rols::where('rol_entity_id','=','1')
                            ->when($inspectorSearch != null, function ($rols) use ($inspectorSearch) {
                                return $rols->where('id','!=',45);
                            })
                            ->when($auditorSearch != null, function ($rols) use ($auditorSearch) {
                                return $rols->where('id','!=',46);
                            })
                            ->get();
            foreach($rols as $r){
                $returnRol .= '<option value="'.$r->id.'">'.$r->name.'</option>';
            }
            $channel = \App\channels::where('canalnegoid','=','1')->orderBy('canalnegodes')->get();
            foreach($channel as $c){
                $returnChannel .= '<option value="'.$c->id.'">'.$c->canalnegodes.'</option>';
            }
        }else{
            $rols = \App\rols::where('rol_entity_id','=','2')->get();
            foreach($rols as $r){
                $returnRol .= '<option value="'.$r->id.'">'.$r->name.'</option>';
            }
            $channel = \App\channels::whereNotIn('canalnegoid',[0,9,1])->orderBy('canalnegodes')->get();
            foreach($channel as $c){
                $returnChannel .= '<option value="'.$c->id.'">'.$c->canalnegodes.'</option>';
            }
        }
        
        
        $returnData = [
            'rol' => $returnRol,
            'channel' => $returnChannel
        ];
        
        return $returnData;
    }
    

}
