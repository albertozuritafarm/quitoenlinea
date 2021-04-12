<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Excel;
use File;
use Rap2hpoutre\FastExcel\FastExcel;
use Box\Spout\Writer\Style\Border;
use Box\Spout\Writer\Style\BorderBuilder;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\WriterFactory;
use Session;

class ProvidersBranchController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('validateUserRoute');
    }
    
    public function create(request $request){
        //Variables
        if (isset($request->items)) { $items = $request->items; } else { $items = 5; }
        $providersId = $request['providersId'];

        session(['branchCreateItems' => $items]);
//        session(['agenciesCreateChannel' => $channel]);

        $provinces = \App\province::all();

        //Obtain Agencies
        $providersBranch = providersBranch($items, $providersId);
        
        return view('providersBranch.create', [
            'providersBranch' => $providersBranch,
            'items' => $items,
            'providersId' => $providersId,
            'provinces' => $provinces
        ]);
    }
    
    public function store(request $request){
        $branchValidate = \App\providers_branch::where('name','=',$request['name'])->where('providers_id','=',$request['providersId'])->get();
            
        if($branchValidate->isEmpty()){
            //Store Agency
            $branch = new \App\providers_branch();
            $branch->name = $request['name'];
            $branch->city_id = $request['city'];
            $branch->providers_id = $request['providersId'];
            $branch->address = $request['address'];
            $branch->phone = $request['phone'];
            $branch->mobile_phone = $request['mobile_phone'];
            $branch->contact = $request['contact'];
            $branch->save();

            \Session::flash('editSuccess', 'La agencia fue guardada correctamente.');
            return 'ok';
        }else{
            return 'false';
        }
    }
    
    public function validateUploadExcel(request $request){
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
                        $errorMessage = ["fila" => $excelRow, "columna" => 'NOMBRE', "error" => 'Debe ingresar un NOMBRE']; array_push($errorReturn, $errorMessage); } else { $nameValidate = \App\providers_branch::where('name','=',$data['NOMBRE'])->where('providers_id','=',$request['providersId'])->get(); if (!$nameValidate->isEmpty()) { $errorMessage = ["fila" => $excelRow, "columna" => 'NOMBRE', "error" => 'El NOMBRE ingresado ya se encuentra registrado']; array_push($errorReturn, $errorMessage); }
                    }
                    if(!isset($data['DIRECCIÓN'])) { // DIRECCION
                        $errorMessage = ["fila" => $excelRow, "columna" => 'DIRECCIÓN', "error" => 'Debe ingresar una DIRECCIÓN']; array_push($errorReturn, $errorMessage);
                    }
                    if(!isset($data['CIUDAD'])) { // CIUDAD
                        $errorMessage = ["fila" => $excelRow, "columna" => 'CIUDAD', "error" => 'Debe ingresar una CIUDAD']; array_push($errorReturn, $errorMessage); } else { $city = \App\city::where('name', '=', $data['CIUDAD'])->get(); if ($city->isEmpty()) { $errorMessage = ["fila" => $excelRow, "columna" => 'CIUDAD', "error" => 'La CIUDAD ingresada no se encuentra en el sistema']; array_push($errorReturn, $errorMessage); } else { $city_id = $city[0]->id; }
                    }
                    if(!isset($data['TELEFONO FIJO'])) { // TELEFONO FIJO
                        $errorMessage = ["fila" => $excelRow, "columna" => 'TELEFONO FIJO', "error" => 'Debe ingresar un TELEFONO FIJO']; array_push($errorReturn, $errorMessage); } elseif (!is_numeric($data['TELEFONO FIJO'])) { $errorMessage = ["fila" => $excelRow, "columna" => 'TELEFONO FIJO', "error" => 'El TELEFONO FIJO debe ser numerico']; array_push($errorReturn, $errorMessage); } elseif (strlen($data['TELEFONO FIJO']) != 9) { $errorMessage = ["fila" => $excelRow, "columna" => 'TELEFONO FIJO', "error" => 'El TELEFONO FIJO debe tener 9 caracteres']; array_push($errorReturn, $errorMessage);
                    }
                    if(!isset($data['CONTACTO'])) { // CONTACTO
                        $errorMessage = ["fila" => $excelRow, "columna" => 'CONTACTO', "error" => 'Debe ingresar un CONTACTO']; array_push($errorReturn, $errorMessage);
                    }
                    if(!isset($data['CORREO'])) { // CORREO
                        $errorMessage = ["fila" => $excelRow, "columna" => 'CORREO', "error" => 'Debe ingresar un CORREO']; array_push($errorReturn, $errorMessage); }else{ $email = $data['CORREO']; if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $errorMessage = ["fila" => $excelRow, "columna" => 'CORREO', "error" => 'El CORREO ingresado no tiene el formato correcto']; array_push($errorReturn, $errorMessage); }
                    }
                    if(!isset($data['TELEFONO CELULAR'])) { // TELEFONO CELULAR
                        $errorMessage = ["fila" => $excelRow, "columna" => 'TELEFONO CELULAR', "error" => 'Debe ingresar un TELEFONO CELULAR']; array_push($errorReturn, $errorMessage); } elseif (!is_numeric($data['TELEFONO CELULAR'])) { $errorMessage = ["fila" => $excelRow, "columna" => 'TELEFONO CELULAR', "error" => 'El TELEFONO CELULAR debe ser numerico']; array_push($errorReturn, $errorMessage); } elseif (strlen($data['TELEFONO CELULAR']) != 10) { $errorMessage = ["fila" => $excelRow, "columna" => 'TELEFONO CELULAR', "error" => 'El TELEFONO CELULAR debe tener 10 caracteres']; array_push($errorReturn, $errorMessage);
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
                    $errorLog = (new FastExcel($collet))->headerStyle($style)->export(public_path('uploadError/') . $name);

                    $returnArray = [
                        'success' => 'false',
                        'name' => 'Existe un error con el archivo seleccionado, descargue el archivo <a href="'. getAppRoute() .'/uploadError/'.$name.'" target="blank" title="Descargar Archivo Cargado">AQUI</a>' 
                    ];
                } else {
                    //Save Agencies
                    foreach($collection as $data){
                        
                        $branch = new \App\providers_branch();
                        $branch->name = $data['NOMBRE'];
                        $branch->city_id = $city_id;
                        $branch->providers_id = $request['providersId'];
                        $branch->address = $data['DIRECCIÓN'];
                        $branch->phone = $data['TELEFONO FIJO'];
                        $branch->mobile_phone = $data['TELEFONO CELULAR'];
                        $branch->contact = $data['CONTACTO'];
                        $branch->save();
                    }
                    Session::flash('editSuccess', 'Las sucursales fueron cargadas correctamente');
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
}