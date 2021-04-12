<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class VehiclesController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
        
//        $this->middleware('validateUserRoute');
    }
    public function tempJson(Request $request){
        $response = array(
          'status' => 'success',
          'plate' => $request->plate,
          'model' => $request->model,
          'document' => $request->document,
      );
      return response()->json($response); 
    }
    
    public static function checkPlate(request $request) {
         $queryValidate = 'select vehi.*
                        from vehicles vehi
                        join vehicles_sales vsal on vsal.vehicule_id = vehi.id
                        join sales sal on sal.id = vsal.sales_id
                        where sal.sales_type_id = 1 and sal.status_id not in(3, 5, 7, 11, 4, 28, 20, 35, 21, 23) and vehi.plate = "'.$request['data']['plate'].'"';
        
            $validate = DB::select($queryValidate);
            
            if($validate){ //IT HAS A SALE
                //DATA
                $vehicleArray = [
                'brand' => '0',
                'year' => '',
                'color' => '',
                'model' => '',
                'type' => '',
                'class' => '',
                'validate' => 'true'
            ];
            }else{
                $data = vehicleSS($request['data']['plate']);
                
                //Validate Error
                if($data['error'][0]['code'] == '006'){
                    $vehicleArray = [
                        'brand' => '0',
                        'year' => '',
                        'color' => '',
                        'model' => '',
                        'type' => '',
                        'class' => '',
                        'price' => '0',
                        'validate' => 'antDown',
                        'message' => ''
                    ];
                    return $vehicleArray;
                }
                
                //Validate Year
                if($request['data']['newVehicle'] == 'Nuevo'){
                    $year = date('Y');
                    $nextYear = $year + 1;
                    if($data['datosvehiculo']['anio'] == $year || $data['datosvehiculo']['anio'] == $nextYear){
                    }else{
                        $vehicleArray = [
                            'brand' => '0',
                            'year' => '',
                            'color' => '',
                            'model' => '',
                            'type' => '',
                            'plate' => $request['data']['plate'],
                            'class' => '',
                            'validate' => 'error',
                            'message' => '<center>Para un vehiculo nuevo, el año del vehiculo debe ser '.$year.' o '.$nextYear.'</center>'
                        ];
                        return $vehicleArray;
                    }
                }
                
                //Validate Vehicle Price
                $arr = explode(' ', trim($data['datosvehiculo']['modelo'] . ' '));
                $model = $arr[0];

                //Find by Model
                $price = \App\vehiclesPrices::where('description', '=', $data['datosvehiculo']['modelo'])->get();
                if ($price->isEmpty()) {
                    //Find by Like Model
                    $price = \App\vehiclesPrices::where('brand', 'LIKE', '%' . $data['datosvehiculo']['marca'] . '%')
                            ->where('model', 'LIKE', '%' . $model . '%')
                            ->where('year', '=', $data['datosvehiculo']['anio'])
                            ->get();
                }
                if ($price->isEmpty()) {
                    $priceReturn = null;
                    $validate = 'priceError';
                    $message = '<center>No es posible obtener un precio referencial para el vehiculo ' . $data['datosvehiculo']['modelo'] . '.<br>Por favor contacte con Seguros Sucre</center>';
                } else {
                    $priceReturn = $price[0]->price;
                    $validate = 'false';
                    $message = 'null';
                }
                $classSearch = \App\vehicle_class::where('name','=',$data['datosvehiculo']['clase'])->get();
                if($classSearch->isEmpty()){
                    $class = '';
                }else{
                    $class = $classSearch[0]->name;
                }
                //Validate Year
                $year = date('Y');
                $yearDiff = $year - $data['datosvehiculo']['anio'];
                if($yearDiff > 12){
                    $validate = 'error';
                    $message = '<center>El vehiculo no puede ser mayor de 12 años.</center>';
                }
                //DATA
                $vehicleArray = [
                    'brand' => $data['datosvehiculo']['marca'],
                    'year' => $data['datosvehiculo']['anio'],
                    'color' => $data['datosvehiculo']['color'],
                    'model' => $data['datosvehiculo']['modelo'],
                    'type' => $data['datosvehiculo']['tipo'],
                    'plate' => $data['datosvehiculo']['placa'],
                    'ramv' => $data['datosvehiculo']['camv'],
                    'chasis' => $data['datosvehiculo']['chasis'],
                    'motor' => $data['datosvehiculo']['motor'],
                    'class' => $class,
                    'price' => $priceReturn,
                    'validate' => $validate,
                    'message' => $message
                ];
            }
        return $vehicleArray;
    }

    public function checkPlateOLD(request $request){
        // VALIDATE PLATE EXISTS IN BBDD
        $query = 'select * from vehicles where plate = "'.$request['data']['plate'].'"';
        $vehi = DB::select($query);
       
        
        if($vehi){
            //VALIDATE IF HAS INDIVIDUAL SALE
            $queryValidate = 'select vehi.*
                        from vehicles vehi
                        join vehicles_sales vsal on vsal.vehicule_id = vehi.id
                        join sales sal on sal.id = vsal.sales_id
                        where sal.sales_type_id = 1 and sal.status_id not in(3,5,7,11) and vehi.plate = "'.$request['data']['plate'].'"';
        
            $validate = DB::select($queryValidate);
            
            if($validate){ //IT HAS A SALE
                //DATA
                $vehicleArray = [
                'brand' => '0',
                'year' => '',
                'color' => '',
                'model' => '',
                'type' => '',
                'validate' => 'true'
            ];
            }else{
                //OBTAIN BRAND ID
                $query = 'select * from vehicles_brands where id = "'.$vehi[0]->brand_id.'"';
                $brand_id = DB::select($query);
                $vehiType = \App\vehicles_type::find($vehi[0]->vehicles_type_id);
                
                //Validate Vehicle Price
                $arr = explode(' ',trim($vehi[0]->model.' '));
                $model = $arr[0];
                
                //Find by Model
                $price = \App\vehiclesPrices::where('description','=',$vehi[0]->model)->get();
                if($price->isEmpty()){
                    //Find by Like Model
                    $price = \App\vehiclesPrices::where('brand','LIKE','%'.$brand_id[0]->name.'%')
                                                ->where('model','LIKE','%'.$model.'%')
                                                ->where('year','=',$vehi[0]->year)
                                                ->get();
                }
                if($price->isEmpty()){
                    $priceReturn = null;
                    $validate = 'priceError';
                    $message = '<center>No es posible obtener un precio referencial para el vehiculo ' . $vehi[0]->model . '.<br>Por favor contacte con Seguros Sucre</center>';
                }else{
                    $priceReturn  = $price[0]->price;
                    $validate = 'false';
                    $message = 'null';
                }
                //DATA
                $vehicleArray = [
                    'brand' => $brand_id[0]->name,
                    'year' => $vehi[0]->year,
                    'color' => $vehi[0]->color,
                    'model' => $vehi[0]->model,
                    'type' => $vehiType->name,
                    'plate' => $vehi[0]->plate,
                    'price' => $priceReturn,
                    'validate' => $validate,
                    'message' => $message
                ];
            }
        }else{
            $validatePlate = validatePlateANT($request['data']['plate']);
//            return $validatePlate;
            if($validatePlate['success'] == 'true'){
                //OBTAIN BRAND ID
                $query = 'select * from vehicles_brands where name = "'.$validatePlate['marca'].'"';
                $brand_id = DB::select($query);
                //Validate Vehicle Type
                $vehiType = \App\vehicles_type::where('name','like','"%'.$validatePlate['servicio'].'%"')->get();
                if($vehiType->isEmpty()){
                    $servicio = 'USO PARTICULAR';
                    $vehiTypeId = 1;
                }else{
                    $servicio = $validatePlate['servicio'];
                    $vehiTypeId = $vehiType[0]->id;
                }
                //Validate Vehicle Class
                $vehiClass = \App\vehicle_class::where('name','like','"%'.$validatePlate['clase'].'%"')->get();
                if($vehiClass->isEmpty()){
                    $vehiClassNew = new \App\vehicle_class();
                    $vehiClassNew->name = $validatePlate['clase'];
                    $vehiClassNew->save();
                    $vehiClassId = $vehiClassNew->id;
                }else{
                    $vehiClassId = $vehiClass[0]->id;
                }
                
                //Store New Vehicle for future use
                $vehiclesNew = new \App\vehicles();
                $vehiclesNew->plate = $validatePlate['placa'];
                $vehiclesNew->ramv = null;
                $vehiclesNew->brand_id = $brand_id[0]->id;
                $vehiclesNew->model = $validatePlate['modelo'];
                $vehiclesNew->year = $validatePlate['anioFabricacion'];
                $vehiclesNew->color = $validatePlate['color'];
                $vehiclesNew->new_vehicle = 0;
                $vehiclesNew->chassis = null;
                $vehiclesNew->matricula = null;
                $vehiclesNew->vehicles_type_id = $vehiTypeId;
                $vehiclesNew->status_vehicle_on_new_sale = 0;
                $vehiclesNew->save();
                
                
                //Validate Vehicle Price
                $arr = explode(' ',trim($validatePlate['modelo'].' '));
                $model = $arr[0];
                
                //Find by Model
                $price = \App\vehiclesPrices::where('description','=',$validatePlate['modelo'])->get();
                if($price->isEmpty()){
                    //Find by Like Model
                    $price = \App\vehiclesPrices::where('brand','LIKE','%'.$brand_id[0]->name.'%')
                                                ->where('model','LIKE','%'.$model.'%')
                                                ->where('year','=',$validatePlate['anioFabricacion'])
                                                ->get();
                }
                if($price->isEmpty()){
                    $priceReturn = null;
                    $validate = 'priceError';
                    $message = '<center>No es posible obtener un precio referencial para el vehiculo ' . $validatePlate['modelo'] . '.<br>Por favor contacte con Seguros Sucre</center>';
                }else{
                    $priceReturn  = $price[0]->price;
                    $validate = 'false';
                    $message = 'null';
                }
                //DATA
                $vehicleArray = [
                    'brand' => $brand_id[0]->name,
                    'year' => $validatePlate['anioFabricacion'],
                    'color' => $validatePlate['color'],
                    'model' => $validatePlate['modelo'],
                    'plate' => $validatePlate['placa'],
                    'price' => $priceReturn,
                    'type' => $servicio,
                    'message' => $message,
                    'validate' => $validate
                    
                ];
            }else if($validatePlate['success'] == 'antDown'){
                $vehicleArray = [
                'brand' => '0',
                'year' => '',
                'color' => '',
                'model' => '',
                'type' => '',
                'message' => $validatePlate['Error'],
                'validate' => 'antDown'
            ];
            }else{
                $vehicleArray = [
                'brand' => '0',
                'year' => '',
                'color' => '',
                'model' => '',
                'type' => '',
                'message' => $validatePlate['Error'],
                'validate' => 'error'
            ];
            }
        }
        return $vehicleArray;
        
//        $result = substr($request['data']['plate'], 0, 3);
//        if(string_has_other_than_letters($result)){
//            return $vehicleArray = [
//                        'brand' => '0',
//                        'year' => '',
//                        'color' => '',
//                        'model' => '',
//                        'validate' => 'plate'
//                    ];
//        }
//        if (strlen($request['data']['plate']) == 6){
//            $result = substr($request['data']['plate'], -3);
//        }else{
//            $result = substr($request['data']['plate'], -4);
//        }
//        if(string_has_other_than_numbers($result)){
//            return $vehicleArray = [
//                        'brand' => '0',
//                        'year' => '',
//                        'color' => '',
//                        'model' => '',
//                        'validate' => 'plate'
//                    ];
//        }
    }
    
    public function vehiPriceModal(request $request){
        //Validate Price
        $arr = explode(' ',trim($request['model'].' '));
        $model = $arr[0];

        //Find by Like Model
        $price = \App\vehiclesPrices::where('brand','LIKE','%'.$request['brand'].'%')
                                    ->where('model','LIKE','%'.$model.'%')
                                                ->where('year','=',$request['year'])
                                    ->get();
        $returnData = '<option>-- Seleccione Uno --</option>';
        foreach($price as $pr){
            $returnData .= '<option value="'.$pr->price.'">'.$pr->description.' - '.$pr->price.'</option>';
        }
        return $returnData;
    }
    
    function vehiForm($id){
        $vehi = \App\vehicles::selectRaw('vehicles.*, vsal.id as "vehiSalId", vsal.inspector_updated')
                                ->join('vehicles_sales as vsal','vsal.vehicule_id','=','vehicles.id')
                                ->where('vsal.sales_id','=',$id)
                                ->get();
        
        $vehiBrand = \App\vehiclesBrands::find($vehi[0]->brand_id);
        $vehiClass = \App\vehicle_class::where('id','=',$vehi[0]->vehicles_class_id)->get();
        $vehiType = \App\vehicles_type::where('id','=',$vehi[0]->vehicles_type_id)->get();
        $vehiCountrySearch= \App\country::where('id','=',$vehi[0]->pais)->get();
        if($vehiCountrySearch->isEmpty()){
            $vehiCountry = null;
        }else{
            $vehiCountry = $vehiCountrySearch[0]->id;
        }
        $countries = \App\country::all();
        
        if($vehi[0]->inspector_updated == null){
            $disabled = '';
        }else{
            $disabled = 'disabled="disabled"';
        }
        
        if($vehi[0]->disp_seguridad == 1){
            $selectedVehiSecu1 = 'selected="true"';
            $selectedVehiSecu2 = '';
        }else if($vehi[0]->disp_seguridad == 2){
            $selectedVehiSecu1 = '';
            $selectedVehiSecu2 = 'selected="true"';
        }else{
            $selectedVehiSecu1 = '';
            $selectedVehiSecu2 = '';
        }
        $returnData = '
                        <form method="POST" id="formConfirmVehicle"  style="margin-top: 25px;">
                        '.@csrf_field().'
                        <input type="hidden" id="vehiId" name="vehiId" value="'.$vehi[0]->id.'">
                        <input type="hidden" id="inspectionId" name="inspectionId" value="'.$id.'">
                        <input type="hidden" id="vehiSalId" name="vehiSalId" value="'.$vehi[0]->vehiSalId.'">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label id="vehicleDocument" class="registerForm" for="last_name"> RAMV</label> 
                                    <input type="text" class="form-control registerForm" name="ramv" id="ramv" tabindex="1" placeholder="RAMV" value="'.$vehi[0]->ramv.'" maxlength="7" required disabled="disabled">
                                </div>
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label id="vehicleDocument" class="registerForm" for="last_name"> Placa</label> 
                                    <input type="text" class="form-control registerForm" name="plate" id="plate" tabindex="2" placeholder="Placa" value="'.$vehi[0]->plate.'" maxlength="7" required disabled="disabled">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="last_name"> Modelo</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="model" id="model" tabindex="3" placeholder="Modelo" value="'.$vehi[0]->model.'" required disabled="disabled">
                                </div>
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="province"> Marca</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="brand" id="brand" tabindex="4" placeholder="brand" value="'.$vehiBrand->name.'" required disabled="disabled">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="use"> Uso</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select name="vehiType" class="form-control registerForm" id="vehiType" tabindex="7" required disabled="disabled">
                                        <option id="typeSelect" disabled="disabled" value="0">--Escoja Una---</option>
                                        <option id="typeSelect" selected="true" value="'.$vehiType[0]->id.'">'.$vehiType[0]->name.'</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="vehicleClass"> Tipo</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select name="vehicleClass" class="form-control registerForm" id="vehicleClass" tabindex="8" required disabled="disabled">
                                        <option id="vehicleClass" value="0">--Escoja Una---</option>
                                        <option id="vehicleClass" selected="true" value="'.$vehiClass[0]->id.'">'.$vehiClass[0]->name.'</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="registration"> Motor</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="registration" id="registration" tabindex="9" placeholder="Motor" value="'.$vehi[0]->matricula.'" required '.$disabled.'>
                                </div>
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="chassis"> Chasis</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="chassis" id="chassis" tabindex="10" placeholder="Chasis" value="'.$vehi[0]->chassis.'" required '.$disabled.'>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="last_name"> Año</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm yearVehicle" name="year" id="year" tabindex="5" placeholder="Año" value="'.$vehi[0]->year.'" max="2020" min="1" required '.$disabled.'>
                                    
                                </div>
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="last_name"> Color</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="color" id="color" tabindex="6" placeholder="Color" value="'.$vehi[0]->color.'" required '.$disabled.'>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="npassengers"> N° Pasajeros</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="npassengers" id="npassengers" tabindex="11" placeholder="Número de pasajeros" value="'.$vehi[0]->capacidad.'" required '.$disabled.'>
                                </div>
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="tonnage"> Tonelaje</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="tonnage" id="tonnage" tabindex="12" placeholder="Tonelaje" value="'.$vehi[0]->tonelaje.'" required '.$disabled.'>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="vehicleCylinder"> Cilindraje</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="vehicleCylinder" id="vehicleCylinder" tabindex="13" placeholder="Cilindraje" value="'.$vehi[0]->cilindraje.'" required '.$disabled.'>
                                </div>
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="countryOrigin"> País de Origen</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select name="country" class="form-control registerForm" id="country" tabindex="8" required '.$disabled.'>
                                        <option value="">--Escoja Una---</option>';
                                    if ($vehiCountry == null) {
                                         foreach($countries as $cou){
                                            $returnData .= '<option value="'.$cou->id.'">'.$cou->name.'</option>';
                                         }
                                    }else{
                                         foreach($countries as $cou){
                                             if($cou->id == $vehiCountry){
                                                $returnData .= '<option selected="true" value="'.$cou->id.'">'.$cou->name.'</option>';
                                             }else{
                                                $returnData .= '<option value="'.$cou->id.'">'.$cou->name.'</option>';
                                             }
                                         }
                                    }
                       $returnData .='</select>                               
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="vehicleSecurity"> Dispositivo de Seguridad</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select name="vehicleSecurity" class="form-control registerForm" id="vehicleSecurity" tabindex="8" required '.$disabled.'>
                                        <option value="">--Escoja Una---</option>
                                        <option value="1" '.$selectedVehiSecu1.'>SI</option>
                                        <option value="2" '.$selectedVehiSecu1.'>NO</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    ';
        
        return $returnData;
    }
    
    function vehiTable($id){
        $vehi = \App\vehicles::selectRaw('vehicles.*, vsal.insured_value, vsal.id as "vehiSalId", vsal.inspector_updated')
                                ->join('vehicles_sales as vsal','vsal.vehicule_id','=','vehicles.id')
                                ->where('vsal.sales_id','=',$id)
                                ->get();
        $returnData = '<table id="vehiclesTable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="background-color:#b3b0b0">Placa</th>
                                            <th style="background-color:#b3b0b0">Modelo</th>
                                            <th style="background-color:#b3b0b0">Marca</th>
                                            <th style="background-color:#b3b0b0">Val. Asegurado</th>
                                            <th style="background-color:#b3b0b0;margin-right: -15px;">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody id="vehiclesBodyTable" style="word-break: break-all">';
        foreach($vehi as $v){
            $brand = \App\vehiclesBrands::find($v->brand_id);
            $returnData .= '<tr>
                                <td>'.$v->plate.'</td>
                                <td>'.$v->model.'</td>
                                <td>'.$brand->name.'</td>
                                <td>'.number_format($v->insured_value, 2, '.', ',').'</td>
                                <td><a href="#" onclick="vehiModal('.$v->id.')"><span class="glyphicon glyphicon-pencil" style="color:green"></span></a></td>
                            </tr>';
        }
                                        
        $returnData .= '</tbody>
                        </table>';
        
        return $returnData;
    }
    
    function emitEditModal(request $request){
        $vehi = \App\vehicles::selectRaw('vehicles.*, vsal.id as "vehiSalId", vsal.inspector_updated')
                                ->join('vehicles_sales as vsal','vsal.vehicule_id','=','vehicles.id')
                                ->where('vehicles.id','=',$request['id'])
                                ->get();
        
        $vehiBrand = \App\vehiclesBrands::find($vehi[0]->brand_id);
        $vehiClass = \App\vehicle_class::where('id','=',$vehi[0]->vehicles_class_id)->get();
        $vehiType = \App\vehicles_type::where('id','=',$vehi[0]->vehicles_type_id)->get();
        $vehiCountrySearch= \App\country::where('id','=',$vehi[0]->pais)->get();
        if($vehiCountrySearch->isEmpty()){
            $vehiCountry = null;
        }else{
            $vehiCountry = $vehiCountrySearch[0]->id;
        }
        $countries = \App\country::all();
        
        $disabled = '';
        
        if($vehi[0]->disp_seguridad == 1){
            $selectedVehiSecu1 = 'selected="true"';
            $selectedVehiSecu2 = '';
        }else if($vehi[0]->disp_seguridad == 2){
            $selectedVehiSecu1 = '';
            $selectedVehiSecu2 = 'selected="true"';
        }else{
            $selectedVehiSecu1 = '';
            $selectedVehiSecu2 = '';
        }
        $returnData = '<div class="modal-body">
            <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;">
                    <div class="wizard_activo registerForm titleDivBorderTop">
                        <span class="titleLink">Datos del Vehículo</span>
                        <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                    </div>
                    <div class="col-md-12" style="padding-top:15px;padding-bottom: 15px;">
                        <form method="POST" id="formConfirmVehicle"  style="margin-top: 25px;">
                        '.@csrf_field().'
                        <input type="hidden" id="vehiId" name="vehiId" value="'.$vehi[0]->id.'">
                        <input type="hidden" id="vehiSalId" name="vehiSalId" value="'.$vehi[0]->vehiSalId.'">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label id="vehicleDocument" class="registerForm" for="last_name"> RAMV</label> 
                                    <input type="text" class="form-control registerForm" name="ramv" id="ramv" tabindex="1" placeholder="RAMV" value="'.$vehi[0]->ramv.'" maxlength="7" required disabled="disabled">
                                </div>
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label id="vehicleDocument" class="registerForm" for="last_name"> Placa</label> 
                                    <input type="text" class="form-control registerForm" name="plate" id="plate" tabindex="2" placeholder="Placa" value="'.$vehi[0]->plate.'" maxlength="7" required disabled="disabled">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="last_name"> Modelo</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="model" id="model" tabindex="3" placeholder="Modelo" value="'.$vehi[0]->model.'" required disabled="disabled">
                                </div>
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="province"> Marca</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="brand" id="brand" tabindex="4" placeholder="brand" value="'.$vehiBrand->name.'" required disabled="disabled">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="use"> Uso</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select name="vehiType" class="form-control registerForm" id="vehiType" tabindex="7" required disabled="disabled">
                                        <option id="typeSelect" disabled="disabled" value="0">--Escoja Una---</option>
                                        <option id="typeSelect" selected="true" value="'.$vehiType[0]->id.'">'.$vehiType[0]->name.'</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="vehicleClass"> Tipo</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select name="vehicleClass" class="form-control registerForm" id="vehicleClass" tabindex="8" required disabled="disabled">
                                        <option id="vehicleClass" value="0">--Escoja Una---</option>
                                        <option id="vehicleClass" selected="true" value="'.$vehiClass[0]->id.'">'.$vehiClass[0]->name.'</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="registration"> Motor</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="registration" id="registration" tabindex="9" placeholder="Motor" value="'.$vehi[0]->matricula.'" required '.$disabled.'>
                                </div>
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="chassis"> Chasis</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="chassis" id="chassis" tabindex="10" placeholder="Chasis" value="'.$vehi[0]->chassis.'" required '.$disabled.'>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="last_name"> Año</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm yearVehicle" name="year" id="year" tabindex="11" placeholder="Año" value="'.$vehi[0]->year.'" max="2020" min="1" required '.$disabled.'>
                                    
                                </div>
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="last_name"> Color</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="color" id="color" tabindex="12" placeholder="Color" value="'.$vehi[0]->color.'" required '.$disabled.'>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="npassengers"> N° Pasajeros</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="npassengers" id="npassengers" tabindex="13" placeholder="Número de pasajeros" value="'.$vehi[0]->capacidad.'" required '.$disabled.'>
                                </div>
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="tonnage"> Tonelaje</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="tonnage" id="tonnage" tabindex="14" placeholder="Tonelaje" value="'.$vehi[0]->tonelaje.'" required '.$disabled.'>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="vehicleCylinder"> Cilindraje</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="vehicleCylinder" id="vehicleCylinder" tabindex="15" placeholder="Cilindraje" value="'.$vehi[0]->cilindraje.'" required '.$disabled.'>
                                </div>
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="countryOrigin"> País de Origen</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select name="country" class="form-control registerForm" id="country" tabindex="16" required '.$disabled.'>
                                        <option value="">--Escoja Una---</option>';
                                    if ($vehiCountry == null) {
                                         foreach($countries as $cou){
                                            $returnData .= '<option value="'.$cou->id.'">'.$cou->name.'</option>';
                                         }
                                    }else{
                                         foreach($countries as $cou){
                                             if($cou->id == $vehiCountry){
                                                $returnData .= '<option selected="true" value="'.$cou->id.'">'.$cou->name.'</option>';
                                             }else{
                                                $returnData .= '<option value="'.$cou->id.'">'.$cou->name.'</option>';
                                             }
                                         }
                                    }
                       $returnData .='</select>                               
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="vehicleSecurity"> Dispositivo de Seguridad</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select name="vehicleSecurity" class="form-control registerForm" id="vehicleSecurity" tabindex="17" required '.$disabled.'>
                                        <option value="">--Escoja Una---</option>
                                        <option value="1" '.$selectedVehiSecu1.'>SI</option>
                                        <option value="2" '.$selectedVehiSecu1.'>NO</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                </div>
                <div class="modal-footer">
                    <button id="modalCancelCloseBtn" type="button" class="btn btn-default registerForm" data-dismiss="modal" style="float:left">Cerrar</button>
                    <button type="submit" class="btn btn-info registerForm" style="float:right" onclick="modalConfirmVehiBtn()" '.$disabled.'>Confirmar</button>
                </div>';
        
        return $returnData;
    }
    
    public function vehiUpdate(request $request){
        $vehi = \App\vehicles::find($request['vehiId']);
        $vehi->matricula = $request['registration']; 
        $vehi->chassis = $request['chassis']; 
        $vehi->year = $request['year']; 
        $vehi->color = $request['color']; 
        $vehi->capacidad = $request['npassengers']; 
        $vehi->tonelaje = $request['tonnage']; 
        $vehi->cilindraje = $request['vehicleCylinder']; 
        $vehi->pais = $request['country']; 
        $vehi->pais = $request['country']; 
        $vehi->disp_seguridad = $request['vehicleSecurity']; 
        $vehi->save();
        
        $vsal = \App\vehicles_sales::find($request['vehiSalId']);
        $vsal->inspector_updated = new \DateTime();
        $vsal->save();
        
        return 'true';
    }
}
