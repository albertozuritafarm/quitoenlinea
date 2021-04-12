<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Excel;
use File;
use Rap2hpoutre\FastExcel\FastExcel;
use Box\Spout\Writer\Style\Border;
use Box\Spout\Writer\Style\BorderBuilder;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\WriterFactory;
use Session;

class AgencyController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function getAgency($id) {
//        return $id;
        $agencies = DB::select('select id, puntodeventades as "name" from agencies where channel_id = ?', [$id]);
//        $provinces = \App\province::all();

        return json_encode($agencies);
    }

    public function create(request $request) {
        //Variables
        if (isset($request->items)) { $items = $request->items; } else { $items = 10; }
        $channel = $request['channelId'];

        session(['agenciesCreateItems' => $items]);
        session(['agenciesCreateChannel' => $channel]);

        //Obtain Edit Permission
        $edit = checkExtraPermits('47',\Auth::user()->role_id);
        
        $provinces = \App\province::all();

        //Obtain Agencies
        $agencies = agencies($items, $channel);
        
        return view('agencies.create', [
            'agencies' => $agencies,
            'items' => $items,
            'channelId' => $channel,
            'provinces' => $provinces,
            'edit' => $edit
        ]);
    }

    public function index() {
        //Obtain Agencies
        $agencies = agencies($items, $channel);
    }

    function fetch_data(Request $request) {
        if ($request->ajax()) {
            //Pagination Items
            if (session('agenciesCreateItems') == null) { $items = 10; } else { $items = session('agenciesCreateItems'); }
            $channelId = session('agenciesCreateChannel');

            $agencies = agencies($items, $channelId);

            return view('pagination.agencies', [
                'agencies' => $agencies,
                'items' => $items,
                'channelId' => $channelId
            ]);
        }
    }

    public function store(request $request) {
        if($request['id'] == 1){ //NEW AGENCY
            $agencyValidate = \App\Agency::where('name','=',$request['name'])->where('channel_id','=',$request['channelId'])->get();

            if($agencyValidate->isEmpty()){
                //Store Agency
                $agency = new \App\Agency();
                $agency->name = $request['name'];
                $agency->city_id = $request['city'];
                $agency->channel_id = $request['channelId'];
                $agency->address = $request['address'];
                $agency->zip_code = $request['zip'];
                $agency->phone = $request['phone'];
                $agency->mobile_phone = $request['mobile_phone'];
                $agency->contact = $request['contact'];
                $agency->save();

                \Session::flash('editSuccess', 'La agencia fue guardada correctamente.');
                return 'ok';
            }else{
                return 'false';
            }
        }else{ //UPDATE AGENCY
            $agency = \App\Agency::find($request['agencyId']);
//            $agency->name = $request['name'];
            $agency->city_id = $request['city'];
            $agency->channel_id = $request['channelId'];
            $agency->address = $request['address'];
            $agency->zip_code = $request['zip'];
            $agency->phone = $request['phone'];
            $agency->mobile_phone = $request['mobile_phone'];
            $agency->contact = $request['contact'];
            $agency->save();
            
            \Session::flash('editSuccess', 'La agencia fue actualizada correctamente.');
            return 'ok';
        }
    }

    public function validateUploadExcel(request $request) {
//        $this->validate($request, array(
//            'file' => 'required'
//        ));

        if ($request->hasFile('file')) {
            $extension = File::extension($request->file->getClientOriginalName());
            if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {

                $path = $request->file->getRealPath();
                $collection = (new FastExcel)->import($path);

                $errorReturn = array();
                $excelRow = 1;
                //Turn data in JSON
                foreach ($collection as $data) {
                    $excelRow ++;
                    
                    if(!isset($data['NOMBRE'])) { // NOMBRE 
                        $errorMessage = ["fila" => $excelRow, "columna" => 'NOMBRE', "error" => 'Debe ingresar un NOMBRE']; array_push($errorReturn, $errorMessage); }else{ $nameValidate = \App\Agency::where('name','=',$data['NOMBRE'])->where('channel_id','=',$request['channelId'])->get(); if(!$nameValidate->isEmpty()){ $errorMessage = ["fila" => $excelRow, "columna" => 'NOMBRE', "error" => 'El NOMBRE ingresado ya se encuentra registrado']; array_push($errorReturn, $errorMessage); }
                    }
                    if(!isset($data['DIRECCIÓN'])){ // DIRECCION
                        $errorMessage = [ "fila" => $excelRow, "columna" => 'DIRECCIÓN', "error" => 'Debe ingresar una DIRECCIÓN' ]; array_push($errorReturn, $errorMessage);
                    }
                    if(!isset($data['CIUDAD'])){ // CIUDAD
                        $errorMessage = [ "fila" => $excelRow, "columna" => 'CIUDAD', "error" => 'Debe ingresar una CIUDAD' ]; array_push($errorReturn, $errorMessage); }else{ $city = \App\city::where('name','=',$data['CIUDAD'])->get(); if($city->isEmpty()){ $errorMessage = [ "fila" => $excelRow, "columna" => 'CIUDAD', "error" => 'La CIUDAD ingresada no se encuentra en el sistema' ]; array_push($errorReturn, $errorMessage); }else{ $city_id = $city[0]->id; }
                    }
                    if(!isset($data['TELEFONO FIJO'])){ // TELEFONO FIJO
                        $errorMessage = [ "fila" => $excelRow, "columna" => 'TELEFONO FIJO', "error" => 'Debe ingresar un TELEFONO FIJO' ]; array_push($errorReturn, $errorMessage);
                    }elseif(!is_numeric($data['TELEFONO FIJO'])){
                        $errorMessage = [ "fila" => $excelRow, "columna" => 'TELEFONO FIJO', "error" => 'El TELEFONO FIJO debe ser numerico' ]; array_push($errorReturn, $errorMessage);
                    }elseif(strlen($data['TELEFONO FIJO']) != 9){
                        $errorMessage = [ "fila" => $excelRow, "columna" => 'TELEFONO FIJO', "error" => 'El TELEFONO FIJO debe tener 9 caracteres' ]; array_push($errorReturn, $errorMessage);
                    }
                    if(!isset($data['CONTACTO'])){ // CONTACTO
                        $errorMessage = [ "fila" => $excelRow, "columna" => 'CONTACTO', "error" => 'Debe ingresar un CONTACTO' ]; array_push($errorReturn, $errorMessage);
                    }
                    if(!isset($data['CODIGO POSTAL'])) { // CODIGO POSTAL
                        if (!is_numeric($data['CODIGO POSTAL'])) { $errorMessage = ["fila" => $excelRow, "columna" => 'CODIGO POSTAL', "error" => 'El CODIGO POSTAL debe ser numerico']; array_push($errorReturn, $errorMessage); } elseif (strlen($data['CODIGO POSTAL']) != 5) { $errorMessage = ["fila" => $excelRow, "columna" => 'CODIGO POSTAL', "error" => 'El CODIGO POSTAL debe tener 5 caracteres']; array_push($errorReturn, $errorMessage); }
                    }
                    if(!isset($data['TELEFONO CELULAR'])){ // TELEFONO CELULAR
                        $errorMessage = [ "fila" => $excelRow, "columna" => 'TELEFONO CELULAR', "error" => 'Debe ingresar un TELEFONO CELULAR' ]; array_push($errorReturn, $errorMessage);
                    }elseif(!is_numeric($data['TELEFONO CELULAR'])){
                        $errorMessage = [ "fila" => $excelRow, "columna" => 'TELEFONO CELULAR', "error" => 'El TELEFONO CELULAR debe ser numerico' ]; array_push($errorReturn, $errorMessage);
                    }elseif(strlen($data['TELEFONO CELULAR']) != 10){
                        $errorMessage = [ "fila" => $excelRow, "columna" => 'TELEFONO CELULAR', "error" => 'El TELEFONO CELULAR debe tener 10 caracteres' ]; array_push($errorReturn, $errorMessage);
                    }
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
                    $errorLog = (new FastExcel($collet))->headerStyle($style)->export(public_páth('uploadError/') . $name);

                    $returnArray = [
                        'success' => 'false',
                        'name' => 'Existe un error con el archivo seleccionado, descargue el archivo <a href="/uploadError/'.$name.'" target="blank" title="Descargar Archivo Cargado">AQUI</a>' 
                    ];
                } else {
                    //Save Agencies
                    foreach($collection as $data){
                        if($data['CODIGO POSTAL'] == ''){ $zip = null; }else{ $zip = $data['CODIGO POSTAL']; }
                        
                        $agency = new \App\Agency();
                        $agency->name = $data['NOMBRE'];
                        $agency->city_id = $city_id;
                        $agency->channel_id = $request['channelId'];
                        $agency->address = $data['DIRECCIÓN'];
                        $agency->zip_code = $zip;
                        $agency->phone = $data['TELEFONO FIJO'];
                        $agency->mobile_phone = $data['TELEFONO CELULAR'];
                        $agency->contact = $data['CONTACTO'];
                        $agency->save();
                    }
                    Session::flash('editSuccess', 'Las agencias fueron cargadas correctamente');
                    $returnArray = [
                        'success' => 'true'
                    ];
                }
                return $returnArray;
            } else {
                $returnArray = [
                    'success' => 'false',
                    'name' => 'Debe ingresar un archivo tipo Excel (xls, xlsx)'
                ];
                return $returnArray;
            }
        } else {
            $returnArray = [
                'success' => 'false',
                'name' => 'Debe incluir in archivo'
            ];
            return $returnArray;
        }
    }
    
    public function downloadFormat(){
        return response()->download(public_path('formato_agencias.xlsx'));
    }
    
    public function edit(request $request){
        $agency = \App\Agency::find($request['id']);
        $cityId = \App\city::find($agency->city_id);
        $provinceId = \App\province::find($cityId->province_id);
        $countryId = \App\country::find($provinceId->country_id);
        $cities = \App\city::where('province_id','=',$provinceId->id)->orderBy('name','ASC')->get();
        $provinces = \App\province::where('country_id','=',$countryId->id)->orderBy('name','ASC')->get();

        $returnData = '';
        $returnData .= '
                <div id="">
                    <input type="hidden" id="agencyId" name="agencyId" value="'.$agency->id.'">
                    
                    <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;">
                        <div class="wizard_activo registerForm titleDivBorderTop">
                            <span class="titleLink">Datos de la Agencia</span>
                            <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                        </div>
                        <div class="col-md-6" style="margin-top:25px;">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="name">Nombre:</label>
                                <input type="text" class="form-control" name="name2" id="name2" placeholder="Nombre" value="'.$agency->name.'"  maxlength="13" tabindex="2" required disabled="disabled">
                                <p id="nameError2" style="color:red;font-weight: bold"></p>
                           </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="name">Provincia:</label>
                                <select class="form-control" id="province2" name="province2" tabindex="3" required>
                                    <option value="">-- Escoja Una --</option>';
                                foreach($provinces as $prov){
                                    if($prov->id == $provinceId->id){
                                        $returnData .= '<option selected="true" value="'.$prov->id.'">'.$prov->name.'</option>';
                                    }else{
                                        $returnData .= '<option value="'.$prov->id.'">'.$prov->name.'</option>';
                                    }
                                }    
            $returnData .= '    </select>
                                <p id="provinceError2" style="color:red;font-weight: bold"></p>
                            </div> 
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="name">Teléfono Fijo:</label>
                                <input type="text" class="form-control" name="phone2" id="phone2" placeholder="Teléfono Fijo" value="'.$agency->phone.'" tabindex="5" required>
                                <p id="phoneError2" style="color:red;font-weight: bold"></p>
                           </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="name">Contacto:</label>
                                <input type="text" class="form-control" name="contact2" id="contact2" placeholder="Contacto" value="'.$agency->contact.'" tabindex="6" required>
                                <p id="contactError2" style="color:red;font-weight: bold"></p>
                           </div>
                        </div>
                        <div class="col-md-6" style="margin-top:25px;">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="name">Dirección:</label>
                                <input type="text" class="form-control" name="address2" id="address2" placeholder="Dirección" value="'.$agency->address.'" tabindex="1" required>
                                <p id="addressError2" style="color:red;font-weight: bold"></p>
                            </div> 
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="name">Ciudad:</label>
                                <select class="form-control" id="city2" name="city2" tabindex="4" required>
                                    <option value="">-- Escoja Una --</option>';
                                    foreach($cities as $cit){
                                        if($cit->id == $cityId->id){
                                            $returnData .= '<option selected="true" value="'.$cit->id.'">'.$cit->name.'</option>';
                                        }else{
                                            $returnData .= '<option value="'.$cit->id.'">'.$cit->name.'</option>';
                                        }
                                    }
                $returnData .= '</select>
                                <p id="cityError2" style="color:red;font-weight: bold"></p>
                            </div> 
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="name">Teléfono Celular:</label>
                                <input type="text" class="form-control" name="mobile_phone2" id="mobile_phone2" placeholder="Teléfono Celular" value="'.$agency->mobile_phone.'" tabindex="8" required>
                                <p id="mobilePhoneError2" style="color:red;font-weight: bold"></p>
                            </div>
                            <div class="form-group">
                                <label class="registerForm" style="list-style-type:disc;" for="name">Código Postal:</label>
                                <input type="text" class="form-control" name="zip2" id="zip2" placeholder="Código Postal" value="'.$agency->zip_code.'" tabindex="7" required>
                                <p id="zipError2" style="color:red;font-weight: bold"></p>
                           </div>
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                        <div class="col-md-1">
                            <a class="btn btn-default registerForm" align="right" href="#" data-dismiss="modal" style="margin-left: -30px;"> Cancelar </a>
                        </div>
                        <div class="col-md-1 col-md-offset-10">
                            <button id="btnEditStoreAgency" type="button" class="btn btn-info registerForm" align="right" style="float:right;margin-right: -30px;padding: 5px;width:80px" onclick="addIndividual(2)"> Guardar </button>
                        </div>
                    </div>
                </div>';
                $returnData .= '<script>    //Obtain Cities
    $(\'select[name="province2"]\').on(\'change\', function(){
        var provinceId = $(this).val();
        var url = ROUTE + \'/city/get/\'+provinceId;
        if(provinceId) {
            $.ajax({
                url: url,
                type:"GET",
                dataType:"json",
                success:function(data) {
                    var sel = $("#city2");
                    sel.empty();
                    sel.append(\'<option selected="true" disabled="true" value="">--Escoja Una--</option>\');
                    for (var i=0; i<data.length; i++) {
                      sel.append(\'<option value="\' + data[i].id + \'">\' + data[i].name + \'</option>\');
                    }
                  },
                complete: function(){
//                    $(\'#loader\').css("visibility", "hidden");
                }
            });
        } else {
            $(\'select[name="provinc2e"]\').empty();
        }
    });</script>';
                
                return $returnData;
    }
}
