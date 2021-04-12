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

class DataFastController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('validateUserRoute');
    }
    public function index(request $request){
        //Store Form Variables in Session
        if ($request->isMethod('post')) {
            session(['datafastIdCart' => $request->id_cart]);
            session(['datafastOrder' => $request->order]);
            session(['datafastLot' => $request->lot]);
            session(['datafastReference' => $request->reference]);
            session(['datafastBeginDate' => $request->beginDate]);
            session(['datafastEndDate' => $request->endDate]);
            session(['datafastAuthCode' => $request->auth_code]);
            session(['datafastItems' => $request->items]);
            $currentPage = 1;
            session(['datafastPage' => 1]);
        } else {
            $currentPage = session('datafastPage');
        }

        //Pagination Items
        if (session('datafastItems') == null) {
            $items = 10;
        } else {
            $items = session('datafastItems');
        }

        //Form Variables
        $idCart = session('datafastIdCart');
        $order = session('datafastOrder');
        $lot = session('datafastLot');
        $reference = session('datafastReference');
        $beginDate = session('datafastBeginDate');
        $endDate = session('datafastEndDate');
        $authCode = session('datafastAuthCode');
        
        // Make sure that you call the static method currentPageResolver()
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });
        
//        if(\Auth::user()->role_id == 6){ // EJECUTIVO CANAL
//            $userRol = true;
//            $userQuery = ' sal.user_id in ('.\Auth::user()->id.')';
//        }else{
//            $userRol = false;
//            $userQuery = '';
//        }
        //Obtain Channel
        $channelQuery = 'select cha.* from channels cha join agencies agen on agen.channel_id = cha.id where agen.id =  "' . \Auth::user()->agen_id . '"';
        $channel = DB::select($channelQuery);
        
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
                $userSucreQuery = ' agen.id = "'.\Auth::user()->agen_id.'"';
            }else{
                // ROL TIPO EJECUTIVO
                $userSucre = true;
                $userSucreQuery = ' sal.user_id = "'.\Auth::user()->id.'"';
            }
        }
        
        $datafast = datafast($idCart, $order, $lot, $reference, $beginDate, $endDate, $authCode, $items, $userSucre, $userSucreQuery);

        return view('datafast.index',[
            'datafast' => $datafast,
            'items' => $items
        ]);     
    }
    function fetch_data(Request $request) {
        if ($request->ajax()) {
            //Page
            session(['datafastPage' => $request->page]);

            //Pagination Items
            if (session('datafastItems') == null) {
                $items = 10;
            } else {
                $items = session('datafastItems');
            }

            //Form Variables
            $idCart = session('datafastIdCart');
            $order = session('datafastOrder');
            $lot = session('datafastLot');
            $reference = session('datafastReference');
            $beginDate = session('datafastBeginDate');
            $endDate = session('datafastEndDate');
            $authCode = session('datafastAuthCode');
            
            //Obtain Channel
        $channelQuery = 'select cha.* from channels cha join agencies agen on agen.channel_id = cha.id where agen.id =  "' . \Auth::user()->agen_id . '"';
        $channel = DB::select($channelQuery);
        
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
                $userSucreQuery = ' agen.id = "'.\Auth::user()->agen_id.'"';
            }else{
                // ROL TIPO EJECUTIVO
                $userSucre = true;
                $userSucreQuery = ' sal.user_id = "'.\Auth::user()->id.'"';
            }
        }

            $datafast = datafast($idCart, $order, $lot, $reference, $beginDate, $endDate, $authCode, $items, $userSucre, $userSucreQuery);

            return view('pagination.datafast',[
                'datafast' => $datafast,
                'items' => $items
            ]);     
        }
    }
}
