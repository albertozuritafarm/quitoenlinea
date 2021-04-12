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


class RolController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('validateUserRoute');
    }
    
    public function index(request $request){
        //Obtain Edit Permission
        $edit = DB::table('menu_action_rol')->where('menu_action','=','16')->where('rol_id','=',\Auth::user()->role_id)->get();
        if($edit->isEmpty()){ $edit = false; }else{ $edit = true; }
                
        //Obtain Cancel Permission
        $cancel = DB::table('menu_action_rol')->where('menu_action','=','17')->where('rol_id','=',\Auth::user()->role_id)->get();
        if($cancel->isEmpty()){ $cancel = false; }else{ $cancel = true; }
        
        //Form Variables
        if ($request->isMethod('post')){
            session(['rolItems' => $request->items]);
        }
                
        $rols = \App\rols::selectRaw('rols.id as "rolId",
                                     rols.name as "rolName",
                                     count(usr.id) as "totalUsr"')
                                     ->leftJoin('users as usr','usr.role_id','=','rols.id')
                                     ->groupBy('rols.id')
                            ->get();
        
        return view('rols.index',[
                "rols" => $rols,
                "edit" => $edit
            ]);
    }
    
    public function create(){
        //Obtain Modules    
        $modules = \App\modules::all();
        
        //Obtain Menu
        $menuMain = \App\menu::selectRaw('id, name, parent_id, sub_menu')->where('parent_id','=','0')->whereNotIn('name',['Inicio','Salir'])->orderBy('order')->get();
        $menuSecondary = \App\menu::selectRaw('id, name, parent_id, sub_menu')->whereNotIn('parent_id',[0])->get();
        $menuSecondaryArray = array();
        foreach($menuSecondary as $second){
            array_push($menuSecondaryArray,$second->id);
        }
        $menuThird = \App\menu::selectRaw('id, name, parent_id, sub_menu')->whereIn('parent_id',$menuSecondaryArray)->get();
        
        $rolType = \App\role_type::all();
        $rolEntity = \App\role_entity::all();
        
        return view('rols.create',[
                "modules" => $modules,
                "menuMain" => $menuMain,
                "menuSecondary" => $menuSecondary,
                "menuThird" => $menuThird,
                "rolType" => $rolType,
                "rolEntity" => $rolEntity
            ]);
    }
    public function edit(request $request){
        //Obtain Modules    
        $modules = \App\modules::all();

        //Obtain Menu
        $menuMain = \App\menu::selectRaw('menu.id, menu.name, menu.parent_id, menu.sub_menu, mrol.id as "checkedView",
                                        mActRolEdit.id as "checkedEdit",
                                        mActRolCancel.id as "checkedCancel",
                                        mActRolCreate.id as "checkedCreate"')
                            ->leftJoin('menu_rol as mrol', function($join) use ($request)
                                     {
                                         $join->on('mrol.menu_id', '=', 'menu.id')
                                                 ->where('mrol.rol_id', '=', $request['rolId']);
                                     })
                            ->leftJoin('menu_action as mActEdit', function($join)
                                     {
                                         $join->on('mActEdit.menu_id', '=', 'menu.id')
                                                 ->where('mActEdit.action','=','edit');
                                     })
                            ->leftJoin('menu_action_rol as mActRolEdit', function($join) use ($request)
                                     {
                                         $join->on('mActRolEdit.menu_action', '=', 'mActEdit.id')
                                                 ->where('mActRolEdit.rol_id', '=', $request['rolId']);
                                     })
                            ->leftJoin('menu_action as mActCancel', function($join)
                                     {
                                         $join->on('mActCancel.menu_id', '=', 'menu.id')
                                                 ->where('mActCancel.action','=','cancel');
                                     })
                            ->leftJoin('menu_action_rol as mActRolCancel', function($join) use ($request)
                                     {
                                         $join->on('mActRolCancel.menu_action', '=', 'mActCancel.id')
                                                 ->where('mActRolCancel.rol_id', '=', $request['rolId']);
                                     })
                            ->leftJoin('menu_action as mActCreate', function($join)
                                     {
                                         $join->on('mActCreate.menu_id', '=', 'menu.id')
                                                 ->where('mActCreate.action','=','create');
                                     })
                            ->leftJoin('menu_action_rol as mActRolCreate', function($join) use ($request)
                                     {
                                         $join->on('mActRolCreate.menu_action', '=', 'mActCreate.id')
                                                 ->where('mActRolCreate.rol_id', '=', $request['rolId']);
                                     })
                            ->where('menu.parent_id','=','0')
                            ->whereNotIn('menu.name',['Inicio','Salir'])
                            ->groupBy('menu.id')
                            ->orderBy('menu.order')->get();

        $menuSecondary = \App\menu::selectRaw('menu.id, menu.name, menu.parent_id, menu.sub_menu, mrol.id as "checkedView",
                                       mActRolEdit.id as "checkedEdit",
                                       mActRolCancel.id as "checkedCancel",
                                       mActRolCreate.id as "checkedCreate"')
                            ->leftJoin('menu_rol as mrol', function($join) use ($request)
                                     {
                                         $join->on('mrol.menu_id', '=', 'menu.id')
                                                 ->where('mrol.rol_id', '=', $request['rolId']);
                                     })
                            ->leftJoin('menu_action as mActEdit', function($join)
                                     {
                                         $join->on('mActEdit.menu_id', '=', 'menu.id')
                                                 ->where('mActEdit.action','=','edit');
                                     })
                            ->leftJoin('menu_action_rol as mActRolEdit', function($join) use ($request)
                                     {
                                         $join->on('mActRolEdit.menu_action', '=', 'mActEdit.id')
                                                 ->where('mActRolEdit.rol_id', '=', $request['rolId']);
                                     })
                            ->leftJoin('menu_action as mActCancel', function($join)
                                     {
                                         $join->on('mActCancel.menu_id', '=', 'menu.id')
                                                 ->where('mActCancel.action','=','cancel');
                                     })
                            ->leftJoin('menu_action_rol as mActRolCancel', function($join) use ($request)
                                     {
                                         $join->on('mActRolCancel.menu_action', '=', 'mActCancel.id')
                                                 ->where('mActRolCancel.rol_id', '=', $request['rolId']);
                                     })
                            ->leftJoin('menu_action as mActCreate', function($join)
                                     {
                                         $join->on('mActCreate.menu_id', '=', 'menu.id')
                                                 ->where('mActCreate.action','=','create');
                                     })
                            ->leftJoin('menu_action_rol as mActRolCreate', function($join) use ($request)
                                     {
                                         $join->on('mActRolCreate.menu_action', '=', 'mActCreate.id')
                                                 ->where('mActRolCreate.rol_id', '=', $request['rolId']);
                                     })
                            ->whereNotIn('menu.parent_id',['0'])
                            ->whereNotIn('menu.name',['Inicio','Salir'])
                            ->groupBy('menu.id')
                            ->orderBy('menu.order')->get();

        $menuSecondaryArray = array();
        foreach($menuSecondary as $second){
            array_push($menuSecondaryArray,$second->id);
        }
        $menuThird = \App\menu::selectRaw('menu.id, menu.name, menu.parent_id, menu.sub_menu, mrol.id as "checkedView",
                                       mActRolEdit.id as "checkedEdit",
                                       mActRolCancel.id as "checkedCancel",
                                       mActRolCreate.id as "checkedCreate"')
                            ->leftJoin('menu_rol as mrol', function($join) use ($request)
                                     {
                                         $join->on('mrol.menu_id', '=', 'menu.id')
                                                 ->where('mrol.rol_id', '=', $request['rolId']);
                                     })
                            ->leftJoin('menu_action as mActEdit', function($join)
                                     {
                                         $join->on('mActEdit.menu_id', '=', 'menu.id')
                                                 ->where('mActEdit.action','=','edit');
                                     })
                            ->leftJoin('menu_action_rol as mActRolEdit', function($join) use ($request)
                                     {
                                         $join->on('mActRolEdit.menu_action', '=', 'mActEdit.id')
                                                 ->where('mActRolEdit.rol_id', '=', $request['rolId']);
                                     })
                            ->leftJoin('menu_action as mActCancel', function($join)
                                     {
                                         $join->on('mActCancel.menu_id', '=', 'menu.id')
                                                 ->where('mActCancel.action','=','cancel');
                                     })
                            ->leftJoin('menu_action_rol as mActRolCancel', function($join) use ($request)
                                     {
                                         $join->on('mActRolCancel.menu_action', '=', 'mActCancel.id')
                                                 ->where('mActRolCancel.rol_id', '=', $request['rolId']);
                                     })
                            ->leftJoin('menu_action as mActCreate', function($join)
                                     {
                                         $join->on('mActCreate.menu_id', '=', 'menu.id')
                                                 ->where('mActCreate.action','=','create');
                                     })
                            ->leftJoin('menu_action_rol as mActRolCreate', function($join) use ($request)
                                     {
                                         $join->on('mActRolCreate.menu_action', '=', 'mActCreate.id')
                                                 ->where('mActRolCreate.rol_id', '=', $request['rolId']);
                                     })
                           ->whereIn('parent_id',$menuSecondaryArray)
                            ->groupBy('menu.id')
                            ->orderBy('menu.order')->get();
                                     
        //ROl
        $rol = \App\rols::find($request['rolId']);
        $rolType = \App\role_type::all();
        $rolEntity = \App\role_entity::all();

        return view('rols.edit',[
                "modules" => $modules,
                "menuMain" => $menuMain,
                "menuSecondary" => $menuSecondary,
                "menuThird" => $menuThird,
                "rol" => $rol,
                "rolType" => $rolType,
                "rolEntity" => $rolEntity
            ]);
    }
    public function store(request $request){
        $chk = array();
        foreach($request['selected'] as $selected){
            array_push($chk,explode("_",$selected));
        }

        //Store Rol
        $rol = new \App\rols();
        $rol->name = $request['nameRol'];
        $rol->rol_type_id = $request['rolType'];
        $rol->rol_entity_id = $request['rolEntity'];
        $rol->save();
        
        //Home Menu and Permit
        $menuRolHome = new \App\menuRol();
        $menuRolHome->menu_id = 8;
        $menuRolHome->rol_id = $rol->id;
        $menuRolHome->save();
        
//        $menuRolExit = new \App\menuRol();
//        $menuRolExit->menu_id = 16;
//        $menuRolExit->rol_id = $rol->id;
//        $menuRolExit->save();
        
        //Validate if is VIEW
        foreach($chk as $ch){
           if($ch[1] == 'view'){
               $menuRol = new \App\menuRol();
               $menuRol->menu_id = $ch[0];
               $menuRol->rol_id = $rol->id;
               $menuRol->save();
           }
           //Obtain Menu Action ID
           $menuAction = \App\menuAction::where('menu_id','=',$ch[0])->where('action','=',$ch[1])->get();

           if(!$menuAction->isEmpty()){
               //Store Menu Action Rol
               $menuActionRol = new \App\menuActionRol();
               $menuActionRol->menu_action = $menuAction[0]->id;
               $menuActionRol->rol_id = $rol->id;
               $menuActionRol->save();
           }
        }
        \Session::flash('editSuccess', 'El rol fue creado correctamente.');
    }
    
    public function update(request $request){
//        return $request;
        $chk = array();
        foreach($request['selected'] as $selected){
            array_push($chk,explode("_",$selected));
        }
  
        //Obtain Rols
        $rolUpdate = \App\rols::find($request['idRol']);
        $rolUpdate->name = $request['nameRol'];
        $rolUpdate->rol_type_id = $request['rolType'];
        $rolUpdate->rol_entity_id = $request['rolEntity'];
        $rolUpdate->save();
        
        //Delete Old Permits Menu Rol
        $delMenuRol = \App\menuRol::where('rol_id',$rolUpdate->id)->forceDelete();
        
        //Delete Old Permits Menu Rol
        $delMenuActionRol = \App\menuActionRol::where('rol_id',$rolUpdate->id)->forceDelete();
        
        
        //Home Menu and Permit
        $menuRolHome = new \App\menuRol();
        $menuRolHome->menu_id = 8;
        $menuRolHome->rol_id = $rolUpdate->id;
        $menuRolHome->save();
        
//        $menuRolExit = new \App\menuRol();
//        $menuRolExit->menu_id = 16;
//        $menuRolExit->rol_id = $rolUpdate->id;
//        $menuRolExit->save();
        
        //Validate if is VIEW
        foreach($chk as $ch){
           if($ch[1] == 'view'){
               $menuRol = new \App\menuRol();
               $menuRol->menu_id = $ch[0];
               $menuRol->rol_id = $rolUpdate->id;
               $menuRol->save();
           }
           //Obtain Menu Action ID
           $menuAction = \App\menuAction::where('menu_id','=',$ch[0])->where('action','=',$ch[1])->get();

           if(!$menuAction->isEmpty()){
               //Store Menu Action Rol
               $menuActionRol = new \App\menuActionRol();
               $menuActionRol->menu_action = $menuAction[0]->id;
               $menuActionRol->rol_id = $rolUpdate->id;
               $menuActionRol->save();
           }
        }
        \Session::flash('editSuccess', 'El rol fue creado correctamente.');
    }
}
