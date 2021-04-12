<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Carbon;
use DateTime;
use DateInterval;
use DB;

class ApiController extends Controller {

    public $successStatus = 200;/**
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */

    public function login(request $request) {
        return response()->json(['error' => 'Unauthorised'], 401);
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            return response()->json(['success' => $success], $this->successStatus);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    /**
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'email' => 'required|email',
                    'password' => 'required',
                    'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['name'] = $user->name;
        return response()->json(['success' => $success], $this->successStatus);
    }

    public function uploadSales(request $request) {
        $requestJson = json_decode($request->getContent(), true);
        Config::set('database.default', 'mysql3'); //API DATABASE
        if(isset($requestJson['venta'][0]['request_id'])){$requestId = $requestJson['venta'][0]['request_id'];}else{$requestId = null;}
        //Update Log
        $apiLog = new \App\api_log();
        $apiLog->setConnection('mysql3');
        $apiLog->date = new \DateTime();
        $apiLog->input_json = serialize($requestJson);
        $apiLog->request_id = $requestId;
        $apiLog->type = 'UPLOAD';
        $apiLog->save();

        //Obtain Login Data
        $username = $_SERVER['PHP_AUTH_USER'];
        $password = $_SERVER['PHP_AUTH_PW'];
        if (empty($username) || empty($password)) {
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->setConnection('mysql3');
            $api_log->response_json = 'Debe indicar los datos de authenticacion';
            $api_log->response_code = 401;
            $api_log->save();
            return response()->json(['mensaje' => 'Debe indicar los datos de authenticacion'], 401);
        } else {
            Config::set('database.default', 'mysql3'); //API DATABASE
            if (Auth::attempt(['email' => $username, 'password' => $password])) {
                Config::set('database.default', 'mysql'); // DEFAULT DATABASE
                $result = validateUploadSales($request);
                if (!$result) { // Passes Validation
                    Config::set('database.default', 'mysql3'); //API DATABASE
                    $api_log = \App\api_log::find($apiLog->id);
                    $api_log->setConnection('mysql3');
                    $api_log->response_json = 'Venta Procesada Exitosamente';
                    $api_log->response_code = $this->successStatus;
                    $api_log->save();
                    
                    //Process Sale
                    Config::set('database.default', 'mysql'); // DEFAULT DATABASE
                    $user_id = \App\user::where('email','=',Auth::user()->email)->get();
                    if($user_id->isEmpty()){
                        $userId = 11;
                    }else{
                        $userId = $user_id[0]->id;
                    }
                    $result = processApiSale($request, $userId);
                    
                    return response()->json(['mensaje' => 'Venta Procesada Exitosamente'], 200);
                } else {
                    Config::set('database.default', 'mysql3'); //API DATABASE
                    $api_log = \App\api_log::find($apiLog->id);
                    $api_log->setConnection('mysql3');
                    $api_log->response_json = serialize($result);
                    $api_log->response_code = 402;
                    $api_log->save();
                    return response()->json(['mensaje' => $result], 402);
                }
            } else {
                Config::set('database.default', 'mysql3'); //API DATABASE
                $api_log = \App\api_log::find($apiLog->id);
                $api_log->setConnection('mysql3');
                $api_log->response_json = 'Datos invalidos';
                $api_log->response_code = 403;
                $api_log->save();
                return response()->json(['mensaje' => 'Datos invalidos'], 403);
            }
        }
    }
    
    public function cancelSales(request $request){
        $requestJson = json_decode($request->getContent(), true);
        Config::set('database.default', 'mysql3'); //API DATABASE
        if(isset($requestJson['venta'][0]['request_id'])){$requestId = $requestJson['venta'][0]['request_id'];}else{$requestId = null;}
        //Update Log
        $apiLog = new \App\api_log();
        $apiLog->setConnection('mysql3');
        $apiLog->date = new \DateTime();
        $apiLog->input_json = serialize($requestJson);
        $apiLog->request_id = $requestId;
        $apiLog->type = 'CANCEL';
        $apiLog->save();

        //Obtain Login Data
        $username = $_SERVER['PHP_AUTH_USER'];
        $password = $_SERVER['PHP_AUTH_PW'];
        if (empty($username) || empty($password)) {
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->setConnection('mysql3');
            $api_log->response_json = 'Debe indicar los datos de authenticacion';
            $api_log->response_code = 401;
            $api_log->save();
            return response()->json(['mensaje' => 'Debe indicar los datos de authenticacion'], 401);
        } else {
            Config::set('database.default', 'mysql3'); //API DATABASE
            if (Auth::attempt(['email' => $username, 'password' => $password])) {
                Config::set('database.default', 'mysql'); // DEFAULT DATABASE
                $result = validateCancelSales($request);
                if (!$result) { // Passes Validation
                    Config::set('database.default', 'mysql3'); //API DATABASE
                    $api_log = \App\api_log::find($apiLog->id);
                    $api_log->setConnection('mysql3');
                    $api_log->response_json = 'Venta Procesada Exitosamente';
                    $api_log->response_code = $this->successStatus;
                    $api_log->save();
                    
                    //Process Cancel
                    Config::set('database.default', 'mysql'); // DEFAULT DATABASE
                    $user_id = \App\user::where('email','=',Auth::user()->email)->get();
                    $result = processApiCancel($request, $user_id);
                    
                    return response()->json(['mensaje' => 'La venta fue cancelada exitosamente'], 200);
                } else {
                    Config::set('database.default', 'mysql3'); //API DATABASE
                    $api_log = \App\api_log::find($apiLog->id);
                    $api_log->setConnection('mysql3');
                    $api_log->response_json = serialize($result);
                    $api_log->response_code = 402;
                    $api_log->save();
                    return response()->json(['mensaje' => $result], 402);
                }
            } else {
                Config::set('database.default', 'mysql3'); //API DATABASE
                $api_log = \App\api_log::find($apiLog->id);
                $api_log->setConnection('mysql3');
                $api_log->response_json = 'Datos invalidos';
                $api_log->response_code = 403;
                $api_log->save();
                return response()->json(['mensaje' => 'Datos invalidos'], 403);
            }
        }
    }

    public function storeUploadSales(request $request) {
        return response()->json(['mensaje' => $request], 402);
        //Update Log
        $apiLog = new \App\api_log();
        $apiLog->date = new \DateTime();
        $apiLog->input_json = $request;
        $apiLog->save();

        //Obtain Login Data
        $username = $_SERVER['PHP_AUTH_USER'];
        $password = $_SERVER['PHP_AUTH_PW'];
        if (empty($username) || empty($password)) {
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->response_json = 'Datos invalidos';
            $api_log->response_code = 401;
            $api_log->save();
            return response()->json(['mensaje' => 'Debe indicar los datos de authenticacion'], 401);
        } else {
            if (Auth::attempt(['email' => $username, 'password' => $password])) {
                $result = validateUploadSales($request);
                if (!$result) { // Passes Validation
                    $api_log = \App\api_log::find($apiLog->id);
                    $api_log->response_json = 'Venta Procesa Exitosamente';
                    $api_log->response_code = 200;
                    $api_log->save();
                    return response()->json(['mensaje' => 'Venta Procesa Exitosamente'], $this->successStatus);
                } else {
                    $api_log = \App\api_log::find($apiLog->id);
                    $api_log->response_json = 'Datos invalidos';
                    $api_log->response_code = 401;
                    $api_log->save();
                    return response()->json(['mensaje' => $result], 401);
                }
            } else {
                $api_log = \App\api_log::find($apiLog->id);
                $api_log->response_json = 'Datos invalidos';
                $api_log->response_code = 401;
                $api_log->save();
                return response()->json(['mensaje' => 'Datos invalidos'], 401);
            }
        }
    }
    
    public function changeStatusIpla(request $request){
        $requestJson = json_decode($request->getContent(), true);
        
        //Update Log
        $apiLog = new \App\api_log();
        $apiLog->date = new \DateTime();
        $apiLog->input_json = serialize($requestJson);
        $apiLog->save();

        //Obtain Login Data
        $username = $_SERVER['PHP_AUTH_USER'];
        $password = $_SERVER['PHP_AUTH_PW'];
        if (empty($username) || empty($password)) {
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->response_json = 'Debe indicar los datos de authenticacion';
            $api_log->response_code = 401;
            $api_log->save();
            return response()->json(['mensaje' => 'Debe indicar los datos de authenticacion', 'codigo' => '401'], 200);
        } else {
            if (Auth::attempt(['first_name' => $username, 'password' => $password])) {
                $result = changeStatusIpla($requestJson);
                if (!$result) { // Passes Validation
                    
                    // Update Sales Status
                    $saleFind = \App\sales::where('codigo_solicitud_ipla','=',$requestJson['codigosolicitud'])->get();
                    if(!$saleFind->isEmpty()){
                        if($requestJson['indicador'] == 1){
                            $sale = \App\sales::find($saleFind[0]->id);
                            $sale->status_id = 20;
                            $sale->save();
                            
                            $customer = \App\customers::find($sale->customer_id);
                            $user = User::find($sale->user_id);
                            
                            \App\Jobs\infoListsUnblockadeUserEmailJobs::dispatch($sale->id, $user->email, $customer->document);
                        }
                        if($requestJson['indicador'] == 2){
                            $sale = \App\sales::find($saleFind[0]->id);
                            $sale->status_id = 35;
                            $sale->save();
                            
                            $customer = \App\customers::find($sale->customer_id);
                            $user = User::find($sale->user_id);
                            
                            \App\Jobs\infoListsBlockadeUserEmailJobs::dispatch($sale->id, $user->email, $customer->document);
                        }
                        
                        $api_log = \App\api_log::find($apiLog->id);
                        $api_log->response_json = 'Solicitud recibida exitosamente';
                        $api_log->response_code = $this->successStatus;
                        $api_log->save();     
                        return response()->json(['mensaje' => 'Solicitud recibida exitosamente', 'codigo' => '200'], 200);
                    }else{
                        $api_log = \App\api_log::find($apiLog->id);
                        $api_log->response_json = 'No se encontro una solicitud con ese codigo';
                        $api_log->response_code = 404;
                        $api_log->save();     
                        return response()->json(['mensaje' => 'No se encontro una solicitud con ese codigo', 'codigo' => '404'], 200);
                    }
                    
                } else {
                    $api_log = \App\api_log::find($apiLog->id);
                    $api_log->response_json = serialize($result);
                    $api_log->response_code = 402;
                    $api_log->save();
                    return response()->json(['mensaje' => $result, 'codigo' => '402'], 200);
                }
            } else {
                $api_log = \App\api_log::find($apiLog->id);
                $api_log->response_json = 'Datos invalidos';
                $api_log->response_code = 403;
                $api_log->save();
                return response()->json(['mensaje' => 'Datos invalidos', 'codigo' => '403'], 200);
            }
        }
    }


    /**
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */
    public function details() {
        $user = Auth::user();
        return response()->json(['success' => $user], $this->successStatus);
    }

    public function sftpResponse(request $request){
        $requestJson = json_decode($request->getContent(), true);
        
        //Update Log
        $apiLog = new \App\api_log();
        $apiLog->date = new \DateTime();
        $apiLog->input_json = serialize($requestJson);
        $apiLog->save();

        //Obtain Login Data
        $username = $_SERVER['PHP_AUTH_USER'];
        $password = $_SERVER['PHP_AUTH_PW'];
        if (empty($username) || empty($password)) {
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->response_json = 'Debe indicar los datos de authenticacion';
            $api_log->response_code = 401;
            $api_log->save();
            return response()->json(['mensaje' => 'Debe indicar los datos de authenticacion', 'codigo' => '401'], 200);
        } else {
            if (Auth::attempt(['first_name' => $username, 'password' => $password])) {
                $result = sftpResponse($requestJson['respuesta']);
                if (!$result) { // Passes Validation
                    $arraySaleId = array();
                    foreach($requestJson['respuesta'][0]['archivos'] as $response){
                        $estado = $response['estado'];
                        if($estado == 2){
                            array_push($arraySaleId, $response);
                        }else{
                           //Store Url Data
                            $sale = (explode("_",$requestJson['respuesta'][0]['numoperacion']));
                            if($sale[1] == '2'){ //VINCULATION FORM
                                $vinculationSearch = \App\vinculation_form::where('sales_id','=',$sale[0])->get();
                                $file = (explode("_",$response['nombrearchivo']));
                                if($file[1] == 'DocumentApplicant'){
                                    \Storage::disk('s3')->delete($vinculationSearch[0]->picture_document_aplicant);
                                    $vinculation = \App\vinculation_form::find($vinculationSearch[0]->id);
                                    $vinculation->picture_document_applicant = $response['url'];
                                    $vinculation->save();
                                }
                                if($file[1] == 'DocumentSpouse'){
                                     \Storage::disk('s3')->delete($vinculationSearch[0]->picture_document_spouse);
                                    $vinculation = \App\vinculation_form::find($vinculationSearch[0]->id);
                                    $vinculation->picture_document_spouse = $response['url'];
                                    $vinculation->save();
                                }
                                if($file[1] == 'VotingBallotApplicant'){
                                     \Storage::disk('s3')->delete($vinculationSearch[0]->picture_voting_ballot);
                                    $vinculation = \App\vinculation_form::find($vinculationSearch[0]->id);
                                    $vinculation->picture_voting_ballot = $response['url'];
                                    $vinculation->save();
                                }
                                if($file[1] == 'VotingBallotSpouse'){
                                     \Storage::disk('s3')->delete($vinculationSearch[0]->picture_voting_ballot_spouse);
                                    $vinculation = \App\vinculation_form::find($vinculationSearch[0]->id);
                                    $vinculation->picture_voting_ballot_spouse = $response['url'];
                                    $vinculation->save();
                                }
                                if($file[1] == 'Service'){
                                     \Storage::disk('s3')->delete($vinculationSearch[0]->picture_service);
                                    $vinculation = \App\vinculation_form::find($vinculationSearch[0]->id);
                                    $vinculation->picture_service = $response['url'];
                                    $vinculation->save();
                                }
                                if($file[1] == 'Sri'){
                                     \Storage::disk('s3')->delete($vinculationSearch[0]->picture_sri);
                                    $vinculation = \App\vinculation_form::find($vinculationSearch[0]->id);
                                    $vinculation->picture_sri = $response['url'];
                                    $vinculation->save();
                                }
                            }else{ //SALES - EMIT
                                $salesSearch = \App\sales::where('sales_id','=',$sale[0])->get();
                                $file = (explode("_",$response['nombrearchivo']));
                                if($file[1] == 'Factura'){
                                    \Storage::disk('s3')->delete($salesSearch[0]->picture_factura);
                                    $sales = \App\sales::find($salesSearch[0]->id);
                                    $sales->picture_document_applicant = $response['url'];
                                    $sales->save();
                                }
                                if($file[1] == 'Cactura'){
                                    \Storage::disk('s3')->delete($salesSearch[0]->picture_carta);
                                    $sales = \App\sales::find($salesSearch[0]->id);
                                    $sales->picture_document_applicant = $response['url'];
                                    $sales->save();
                                }
                            }
                        }
                    }
                    //REPROCESAR LA IMAGEN
                    \App\Jobs\ftpSucreForwardFilesJobs::dispatch($arraySaleId);
                    
                    $api_log = \App\api_log::find($apiLog->id);
                    $api_log->response_json = 'Solicitud recibida exitosamente';
                    $api_log->response_code = $this->successStatus;
                    $api_log->save(); 

                    return response()->json(['mensaje' => 'Solicitud recibida exitosamente', 'codigo' => '200'], 200);
                    
                } else {
                    $api_log = \App\api_log::find($apiLog->id);
                    $api_log->response_json = serialize($result);
                    $api_log->response_code = 402;
                    $api_log->save();
                    return response()->json(['mensaje' => $result, 'codigo' => '402'], 200);
                }
            } else {
                $api_log = \App\api_log::find($apiLog->id);
                $api_log->response_json = 'Datos invalidos';
                $api_log->response_code = 403;
                $api_log->save();
                return response()->json(['mensaje' => 'Datos invalidos', 'codigo' => '403'], 200);
            }
        }
    }
    
    public function viamaticaResponse(request $request){
        $requestJson = json_decode($request->getContent(), true);
        
        //Update Log
        $apiLog = new \App\api_log();
        $apiLog->date = new \DateTime();
        $apiLog->input_json = serialize($requestJson);
        $apiLog->save();
        
//        return $requestJson['respuesta'][0];

        //Obtain Login Data
        $username = $_SERVER['PHP_AUTH_USER'];
        $password = $_SERVER['PHP_AUTH_PW'];
        if (empty($username) || empty($password)) {
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->response_json = 'Debe indicar los datos de authenticacion';
            $api_log->response_code = 401;
            $api_log->save();
            return response()->json(['mensaje' => 'Debe indicar los datos de authenticacion', 'codigo' => '401'], 200);
        } else {
            if (Auth::attempt(['first_name' => $username, 'password' => $password])) {
                $result = viamaticaResponse($requestJson['respuesta'][0]);
                
                if (!$result) { // Passes Validation
                    
                    // Update Sales Status
                    $vinFormFind = \App\vinculation_form::selectRaw('vinculation_form.id as "vinId", sal.id as "salesId"')
                                                        ->join('sales as sal','sal.id','=','vinculation_form.sales_id')
                                                        ->where('sal.viamatica_id','=',$requestJson['respuesta'][0]['idDocMigrado'])
                                                        ->orWhere('vinculation_form.viamatica_id','=',$requestJson['respuesta'][0]['idDocMigrado'])
                                                        ->get();

                    if(!$vinFormFind->isEmpty()){
//                        \App\Jobs\vinculaClientesJob::dispatch($vinFormFind[0]->salesId);
                        
                        $sale = $vinFormFind[0]->salesId;
                        $userEmail = \App\User::selectRaw('users.email')->join('sales','sales.user_id','=','users.id')->where('sales.id','=',$sale) ->get();
                        
                        //VINCULATION FORM
                        $vinculationSearch = \App\vinculation_form::where('viamatica_id','=',$requestJson['respuesta'][0]['idDocMigrado'])->get();
                        if(!$vinculationSearch->isEmpty()){
                            //Send vinculation complete email
                            \App\Jobs\VinculationCompleteEmailJobs::dispatch($sale, $userEmail[0]->email);
                            
                            //Update Vinculation Form Status
                            $vinForm = \App\vinculation_form::find($vinFormFind[0]->vinId);
                            $vinForm->status_id = 1;
                            $vinForm->url = $requestJson['respuesta'][0]['url'];
                            $vinForm->save();

                            //Update Sale STATUS
                            $sale = \App\sales::find($vinFormFind[0]->salesId);
                            $sale->status_id = 25;
                            $sale->save();
                        }
                        
                        //SALE
                        $saleSearch = \App\sales::where('viamatica_id','=',$requestJson['respuesta'][0]['idDocMigrado'])->get();
                        if(!$saleSearch->isEmpty()){
                            //Update Sale STATUS
                            $sale = \App\sales::find($vinFormFind[0]->salesId);
                            $sale->status_id = 22;
                            $sale->url_viamatica = $requestJson['respuesta'][0]['url'];
                            $sale->save();
        
                            $user = \App\User::find($sale->user_id);
                            $customer = \App\customers::find($sale->customer_id);

                            \App\Jobs\insuranceRequestSignedEmailJobs::dispatch($sale->id,$user->email,$customer->document);
                        }
                        
                        $api_log = \App\api_log::find($apiLog->id);
                        $api_log->response_json = 'Solicitud recibida exitosamente';
                        $api_log->response_code = $this->successStatus;
                        $api_log->save();     
                        return response()->json(['mensaje' => 'Solicitud recibida exitosamente', 'codigo' => '200'], 200);
                    }else{
                        $api_log = \App\api_log::find($apiLog->id);
                        $api_log->response_json = 'No se encontro una solicitud con ese codigo';
                        $api_log->response_code = 404;
                        $api_log->save();     
                        return response()->json(['mensaje' => 'No se encontro una solicitud con ese idDocMigrado', 'codigo' => '404'], 200);
                    }
                    
                } else {
                    $api_log = \App\api_log::find($apiLog->id);
                    $api_log->response_json = serialize($result);
                    $api_log->response_code = 402;
                    $api_log->save();
                    return response()->json(['mensaje' => $result, 'codigo' => '402'], 200);
                }
            } else {
                $api_log = \App\api_log::find($apiLog->id);
                $api_log->response_json = 'Datos invalidos';
                $api_log->response_code = 403;
                $api_log->save();
                return response()->json(['mensaje' => 'Datos invalidos', 'codigo' => '403'], 200);
            }
        }
    }
    
    public function ssEmisionResponse(request $request){  
        $requestJson = json_decode($request->getContent(), true);
        
        //Update Log
        $apiLog = new \App\api_log();
        $apiLog->date = new \DateTime();
        $apiLog->input_json = serialize($requestJson);
        $apiLog->save();

        //Obtain Login Data
        $username = $_SERVER['PHP_AUTH_USER'];
        $password = $_SERVER['PHP_AUTH_PW'];
        if (empty($username) || empty($password)) {
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->response_json = 'Debe indicar los datos de authenticacion';
            $api_log->response_code = 401;
            $api_log->save();
            return response()->json(['mensaje' => 'Debe indicar los datos de authenticacion', 'codigo' => '401'], 200);
        } else {
            if (Auth::attempt(['first_name' => $username, 'password' => $password])) {
                $result = emisionResponse($requestJson);
                
                if (!$result) { // Passes Validation
                    
                    // Update Sales Status
                    $saleFind = \App\sales::where('id','=',$requestJson['codtransaccion'])->get();
                    if(!$saleFind->isEmpty()){                        
                        //EJECUTAR JOB DE DEVOLUCION POLIZA EMITIDA
                        \App\Jobs\polizaEmitidaJobs::dispatch($requestJson['codtransaccion']);
                        
                        $api_log = \App\api_log::find($apiLog->id);
                        $api_log->response_json = 'Solicitud recibida exitosamente';
                        $api_log->response_code = $this->successStatus;
                        $api_log->save();     
                        return response()->json(['mensaje' => 'Solicitud recibida exitosamente', 'codigo' => '200'], 200);
                    }else{
                        $api_log = \App\api_log::find($apiLog->id);
                        $api_log->response_json = 'No se encontro una solicitud con ese codigo';
                        $api_log->response_code = 404;
                        $api_log->save();     
                        return response()->json(['mensaje' => 'No se encontro una solicitud con ese codtransaccion', 'codigo' => '404'], 200);
                    }
                    
                } else {
                    $api_log = \App\api_log::find($apiLog->id);
                    $api_log->response_json = serialize($result);
                    $api_log->response_code = 402;
                    $api_log->save();
                    return response()->json(['mensaje' => $result, 'codigo' => '402'], 200);
                }
            } else {
                $api_log = \App\api_log::find($apiLog->id);
                $api_log->response_json = 'Datos invalidos';
                $api_log->response_code = 403;
                $api_log->save();
                return response()->json(['mensaje' => 'Datos invalidos', 'codigo' => '403'], 200);
            }
        }
    }
    
    ///////////////////////////
    ///////// WS API //////////
    ///////////////////////////
    
    public function tokenApp(request $request){           
        //Update Log
        $apiLog = new \App\api_log();
        $apiLog->date = new \DateTime();
        $apiLog->input_json = $request;
        $apiLog->save();
        
        //Validate ""grant_type": "client_credentials"
        if(!isset($request['grant_type'])){
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->response_json = 'Error en el request';
            $api_log->response_code = 401;
            $api_log->save();
            
            return response()->json(['mensaje' => 'Error en el request', 'codigo' => '401'], 200);
        }else{
            if($request['grant_type'] != 'client_credentials'){
                $api_log = \App\api_log::find($apiLog->id);
                $api_log->response_json = 'Error en el request';
                $api_log->response_code = 401;
                $api_log->save();

                return response()->json(['mensaje' => 'Error en el request', 'codigo' => '401'], 200);
            }
        }
        
        //Obtain Login Data
        $username = $_SERVER['PHP_AUTH_USER'];
        $password = $_SERVER['PHP_AUTH_PW'];
        if (empty($username) || empty($password)) {
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->response_json = 'Debe indicar los datos de authenticacion';
            $api_log->response_code = 402;
            $api_log->save();
            return response()->json(['mensaje' => 'Debe indicar los datos de authenticacion', 'codigo' => '402'], 200);
        } else {
            if (Auth::attempt(['first_name' => $username, 'password' => $password])) {
                //Generate Token
                $tokenPre = $username . '-' . Carbon::now()->addMinutes(15);
                $token = encrypt($tokenPre);
                
                //Validate if token is still valid
                $tokenSearch = \App\token_app::latest()->get();
                $start_date = new DateTime($tokenSearch[0]->created_at);
                $time = new DateTime();
                $since_start = $start_date->diff(new DateTime());
                
                //If is less than 15 is still valid
                if($since_start->y == 0 && $since_start->m == 0 && $since_start->d == 0 && $since_start->h == 0 && $since_start->i < 15) {
                    //Update Api Log
                    $api_log = \App\api_log::find($apiLog->id);
                    $api_log->response_json = 'Token creado';
                    $api_log->response_code = 200;
                    $api_log->save();
                    
                    return response()->json(['mensaje' => 'Token creado', 'token' => $tokenSearch[0]->token, 'codigo' => '200'], 200);
                }
                
                //Update Api Log
                $api_log = \App\api_log::find($apiLog->id);
                $api_log->response_json = 'Token creado';
                $api_log->response_code = 200;
                $api_log->save();

                //Update Token Log
                $tokenApp = new \App\token_app();
                $tokenApp->token = $token;
                $tokenApp->save();
                
                return response()->json(['mensaje' => 'Token creado', 'token' => $token, 'codigo' => '200'], 200);
            } else {
                $api_log = \App\api_log::find($apiLog->id);
                $api_log->response_json = 'Datos invalidos';
                $api_log->response_code = 403;
                $api_log->save();
                return response()->json(['mensaje' => 'Datos invalidos', 'codigo' => '403'], 200);
            }
        }
    }
    
    function validateBearerToken($bearer, $token){
        $returnMessage = null;
        
        //Check it says bearer
        if($bearer != 'Bearer'){
            $returnMessage = 'Error de Authenticacion';
            return $returnMessage;    
        }
        
        //Check if token is valid
        if($token == null){
            $returnMessage = 'Error del Token';
            return $returnMessage;    
        }else{
            $tokenSearch = \App\token_app::where('token','=',$token)->get();
            if($tokenSearch->isEmpty()){
                $returnMessage = 'Token Invalido';
                return $returnMessage;    
            }
            $start_date = new DateTime($tokenSearch[0]->created_at);
            $since_start = $start_date->diff(new DateTime());

            //If is less than 15 is still valid
            if($since_start->y == 0 && $since_start->m == 0 && $since_start->d == 0 && $since_start->h == 0 && $since_start->i < 15) {
                return $returnMessage;
            }else{
                $returnMessage = 'Token Caducado';
                return $returnMessage;    
            }
        }
        
        return $returnMessage;        
    }
    
    function validateLoginApp($json){
        $returnArray = array();
        //Validate User
        if (isset($json['user'])) { if ($json['user'] == '') { $response = ["user" => "Debe indicar un usuario"]; array_push($returnArray, $response); } } else { $response = ["user" => "Debe indicar un usuario"]; array_push($returnArray, $response); }
        //Validate Pass
        if (isset($json['pass'])) { if ($json['pass'] == '') { $response = ["pass" => "Debe indicar un pass"]; array_push($returnArray, $response); } } else { $response = ["pass" => "Debe indicar un pass"]; array_push($returnArray, $response); }
        
        return $returnArray;
    }
    
    function validateCustomerApp($json) {
        $returnArray = array();
        //Validate User
        if (isset($json['document'])) { if ($json['document'] == '') { $response = ["document" => "Debe indicar un document"]; array_push($returnArray, $response); } else { if (!validateId($json['document'])) { $response = ["document" => "Document invalido"]; array_push($returnArray, $response); } } } else { $response = ["document" => "Debe indicar un document"]; array_push($returnArray, $response); }

        return $returnArray;
    }
    
    function validateVehiclesApp($json) {
        $returnArray = array();
        //Validate Plate
        if (isset($json['plate'])) { if ($json['plate'] == '') { $response = ["plate" => "Debe indicar un plate"]; array_push($returnArray, $response); } } else { $response = ["plate" => "Debe indicar un plate"]; array_push($returnArray, $response); }
        //Validate Status
        if (isset($json['status'])) { if ($json['status'] == '') { $response = ["status" => "Debe indicar un status"]; array_push($returnArray, $response); } else if (($json['status'] == 'Nuevo') || ($json['status'] == 'Usado')) { }else{ $response = ["status" => "Status incorrecto"]; array_push($returnArray, $response); } } else { $response = ["status" => "Debe indicar un status"]; array_push($returnArray, $response); }

        return $returnArray;
    }
    function validateProductsVehiclesApp($json) {
        $returnArray = array();
        //Validate Data
        if (isset($json['userId'])) { if ($json['userId'] == '') { $response = ["userId" => "Debe indicar un userId"]; array_push($returnArray, $response); } else { $userSearch = User::where('id', '=', $json['userId'])->get(); if ($userSearch->isEmpty()) { $response = ["userId" => "userId invalido"]; array_push($returnArray, $response); } } } else { $response = ["userId" => "Debe indicar un userId"]; array_push($returnArray, $response); }
        if (isset($json['vehicles'])) {
            $i = 0;
            foreach($json['vehicles'] as $vehi){
                if(isset($vehi['value'])){ if($vehi['value'] == '' || !is_numeric($vehi['value'])){ $response = ["value-".$i => "Debe indicar un value"]; array_push($returnArray, $response); } }else{ $response = ["value-".$i => "Debe indicar un value"]; array_push($returnArray, $response); }
                if(isset($vehi['accValue'])){ if($vehi['accValue'] == '' || !is_numeric($vehi['accValue'])){ $response = ["accValue-".$i => "Debe indicar un accValue"]; array_push($returnArray, $response); } }else{ $response = ["accValue-".$i => "Debe indicar un accValue"]; array_push($returnArray, $response); }
                if(isset($vehi['vehiType'])){ if($vehi['vehiType'] == ''){ $response = ["vehiType-".$i => "Debe indicar un vehiType"]; array_push($returnArray, $response); } }else{ $response = ["vehiType-".$i => "Debe indicar un vehiType"]; array_push($returnArray, $response); }
                $i++;
            }
        } else {
            $response = ["vehicles" => "Debe indicar un vehicles"];
            array_push($returnArray, $response);
        }

        return $returnArray;
    }
    
    function validateProductsR4App($json) {
        $returnArray = array();
        //Validate Data
        if (isset($json['userId'])) { if ($json['userId'] == '') { $response = ["userId" => "Debe indicar un userId"]; array_push($returnArray, $response); } else { $userSearch = User::where('id', '=', $json['userId'])->get(); if ($userSearch->isEmpty()) { $response = ["userId" => "userId invalido"]; array_push($returnArray, $response); } } } else { $response = ["userId" => "Debe indicar un userId"]; array_push($returnArray, $response); }
        if (isset($json['rubro'])) { if ($json['rubro'] == '') { $response = ["rubro" => "Debe indicar un rubro"]; array_push($returnArray, $response); } else { $rubroSearch = \App\products_rubros::where('description','=',$json['rubro'])->get(); if($rubroSearch->isEmpty()){ $response = ["rubro" => "Rubro invalido"]; array_push($returnArray, $response); } } } else { $response = ["rubro" => "Debe indicar un rubro"]; array_push($returnArray, $response); }
        if (isset($json['value'])) { if ($json['value'] == '' || !is_numeric($json['value'])) { $response = ["value" => "Debe indicar un value"]; array_push($returnArray, $response); } } else { $response = ["value" => "Debe indicar un value"]; array_push($returnArray, $response); }

        return $returnArray;
    }
    
    function validateRubrosR4App($json) {
        $returnArray = array();
        //Validate Data
        if (isset($json['userId'])) { if ($json['userId'] == '') { $response = ["userId" => "Debe indicar un userId"]; array_push($returnArray, $response); } else { $userSearch = User::where('id', '=', $json['userId'])->get(); if ($userSearch->isEmpty()) { $response = ["userId" => "userId invalido"]; array_push($returnArray, $response); } } } else { $response = ["userId" => "Debe indicar un userId"]; array_push($returnArray, $response); }

        return $returnArray;
    }
    
    function validateSalesApp($json) {
        $returnArray = array();
        //Validate Data
        if (isset($json['userId'])) { if ($json['userId'] == '') { $response = ["userId" => "Debe indicar un userId"]; array_push($returnArray, $response); } else { $userSearch = User::where('id', '=', $json['userId'])->get(); if ($userSearch->isEmpty()) { $response = ["userId" => "userId invalido"]; array_push($returnArray, $response); } } } else { $response = ["userId" => "Debe indicar un userId"]; array_push($returnArray, $response); }
        if (!isset($json['firstName'])) { $response = ["firstName" => "Debe indicar un firstName"]; array_push($returnArray, $response); }
        if (!isset($json['lastName'])) { $response = ["lastName" => "Debe indicar un lastName"]; array_push($returnArray, $response); }
        if (!isset($json['document'])) { $response = ["document" => "Debe indicar un document"]; array_push($returnArray, $response); }
        if (!isset($json['plate'])) { $response = ["plate" => "Debe indicar un plate"]; array_push($returnArray, $response); }
        if (!isset($json['email'])) { $response = ["email" => "Debe indicar un email"]; array_push($returnArray, $response); }
        if (!isset($json['saleId'])) { $response = ["saleId" => "Debe indicar un saleId"]; array_push($returnArray, $response); }
        
        return $returnArray;
    }
    
    function validateInspections($json) {
        $returnArray = array();
        //Validate Data
        if (isset($json['codigosolicitud'])) {
            $codigoSolicitud = explode("-",$json['codigosolicitud']);
            if(isset($codigoSolicitud[0]) && isset($codigoSolicitud[1])){
                $saleSearch = \App\sales::where('id','=',$codigoSolicitud[0])->get();
                $vehiSearch = \App\vehicles::where('place','=',$codigoSolicitud[1])->get();
                $vehiSalesSearch = \App\vehicles_sales::where('vehicule_id','=',$vehiSearch[0]->id)
                                                        ->where('sales_id','=',$saleSearch[0]->id)
                                                        ->get();
                if($saleSearch->isEmpty()){
                    $response = ["codigosolicitud" => "El codigosolicitud enviado es incorrecto"];
                    array_push($returnArray, $response);
                }else if($vehiSearch->isEmpty()){
                    $response = ["codigosolicitud" => "El codigosolicitud enviado es incorrecto"];
                    array_push($returnArray, $response);
                }else if($vehiSalesSearch->isEmpty()){
                    $response = ["codigosolicitud" => "El codigosolicitud enviado es incorrecto"];
                    array_push($returnArray, $response);
                }
            }else{
                $response = ["codigosolicitud" => "El codigosolicitud enviado es incorrecto"];
                array_push($returnArray, $response);
            }
        } else {
            $response = ["codigosolicitud" => "Debe indicar un codigosolicitud"];
            array_push($returnArray, $response);
        }
        if (isset($json['estado'])) {
            $array = array("1", "3", "4", "5");
            if (!in_array($json['estado'], $array)){
                $response = ["estado" => "El estado indicado es incorrecto"];
                array_push($returnArray, $response);
            }
        }else{
            $response = ["estado" => "Debe indicar un estado"];
            array_push($returnArray, $response);
        }

        return $returnArray;
    }
    
    function validateLinksApp($json) {
        $returnArray = array();
        //Validate Data
        if (isset($json['saleId'])) { if ($json['saleId'] == '') { $response = ["saleId" => "Debe indicar un saleId"]; array_push($returnArray, $response); } else { $saleSearch = \App\sales::where('id','=',$json['saleId'])->get(); if ($saleSearch->isEmpty()) { $response = ["saleId" => "saleId invalido"]; array_push($returnArray, $response); } } } else { $response = ["saleId" => "Debe indicar un saleId"]; array_push($returnArray, $response); }
        if (isset($json['type'])) { if ($json['type'] == '') { $response = ["type" => "Debe indicar un type"]; array_push($returnArray, $response); } else { $links = array('Vinculación','Beneficiarios','Solicitud','Pago'); if (!in_array($json['type'], $links)) { $response = ["type" => "Type invalido"]; array_push($returnArray, $response); } } } else { $response = ["type" => "Debe indicar un type"]; array_push($returnArray, $response); }

        return $returnArray;
    }
    
    function validateProductsR2App($json) {
        $returnArray = array();
        //Validate Data
        if (isset($json['userId'])) { if ($json['userId'] == '') { $response = ["userId" => "Debe indicar un userId"]; array_push($returnArray, $response); } else { $userSearch = User::where('id', '=', $json['userId'])->get(); if ($userSearch->isEmpty()) { $response = ["userId" => "userId invalido"]; array_push($returnArray, $response); } } } else { $response = ["userId" => "Debe indicar un userId"]; array_push($returnArray, $response); }
        if (isset($json['branch'])) { if ($json['branch'] == '') { $response = ["branch" => "Debe indicar un branch"]; array_push($returnArray, $response); } else { if($json['branch'] != 'R2' && $json['branch'] != 'R3'){ $response = ["branch" => "Branch invalido"]; array_push($returnArray, $response); } } } else { $response = ["branch" => "Debe indicar un branch"]; array_push($returnArray, $response); }

        return $returnArray;
    }

    function is_json($string,$return_data = false) {
        $data = json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE) ? ($return_data ? $data : TRUE) : FALSE;
    }
    
    public function loginApp(request $request){  
        $requestJson = json_decode($request->getContent(), true);
        
        //Update Log
        $apiLog = new \App\api_log();
        $apiLog->date = new \DateTime();
        $apiLog->input_json = serialize($requestJson);
        $apiLog->save();
        
        //Validate Tokens
        $beaerToken = explode(" ", $_SERVER['HTTP_AUTHORIZATION']);
        $validateToken = $this->validateBearerToken($beaerToken[0], $beaerToken[1]);
        if($validateToken != null){
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->response_json = $validateToken;
            $api_log->response_code = 401;
            $api_log->save();
//            return response()->json(['mensaje' => $validateToken, 'codigo' => '401'], 200);
        }
        
        //Validate JSON
        $json = json_decode($request->getContent(), true);
        if($this->is_json($request->getContent())){
            $validateJson = $this->validateLoginApp(json_decode($request->getContent(), true));
            if($validateJson != null){
                $api_log = \App\api_log::find($apiLog->id);
                $api_log->response_json = serialize($validateJson);
                $api_log->response_code = 402;
                $api_log->save();
                return response()->json(['mensaje' => $validateJson, 'codigo' => '402'], 200);
            }
        }else{
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->response_json = 'JSON Invalido';
            $api_log->response_code = 402;
            $api_log->save();
            return response()->json(['mensaje' => 'JSON Invalido', 'codigo' => '402'], 200);
        }
        
        //Obtain Login Data
        if (Auth::attempt(['email' => $json['user'], 'password' => $json['pass']])) {
            //Update Api Log
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->response_json = 'Login Correcto';
            $api_log->response_code = 200;
            $api_log->save();

            return response()->json(['mensaje' => 'Login Correcto', 'codigo' => '200'], 200);
        } else {
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->response_json = 'Datos invalidos';
            $api_log->response_code = 403;
            $api_log->save();
            return response()->json(['mensaje' => 'Datos invalidos', 'codigo' => '403'], 200);
        }
    }
    
    public function customerApp(request $request){  
        $requestJson = json_decode($request->getContent(), true);
        
        //Update Log
        $apiLog = new \App\api_log();
        $apiLog->date = new \DateTime();
        $apiLog->input_json = serialize($requestJson);
        $apiLog->save();
        
        //Validate Tokens
        $beaerToken = explode(" ", $_SERVER['HTTP_AUTHORIZATION']);
        $validateToken = $this->validateBearerToken($beaerToken[0], $beaerToken[1]);
        if($validateToken != null){
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->response_json = $validateToken;
            $api_log->response_code = 401;
            $api_log->save();
//            return response()->json(['mensaje' => $validateToken, 'codigo' => '401'], 200);
        }
        
        //Validate JSON
        $json = json_decode($request->getContent(), true);
        if($this->is_json($request->getContent())){
            $validateJson = $this->validateCustomerApp(json_decode($request->getContent(), true));
            if($validateJson != null){
                $api_log = \App\api_log::find($apiLog->id);
                $api_log->response_json = serialize($validateJson);
                $api_log->response_code = 402;
                $api_log->save();
                return response()->json(['mensaje' => $validateJson, 'codigo' => '402'], 200);
            }
        }else{
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->response_json = 'JSON Invalido';
            $api_log->response_code = 402;
            $api_log->save();
            return response()->json(['mensaje' => 'JSON Invalido', 'codigo' => '402'], 200);
        }
        
        //Obtain Customer Data from SS
        $customerSS = \App\Http\Controllers\CustomerController::documentAutoFill($json['document']);
        $api_log = \App\api_log::find($apiLog->id);
        $api_log->response_json = serialize($customerSS);
        $api_log->response_code = 200;
        $api_log->save();
        return response()->json(['mensaje' => $customerSS, 'codigo' => '200'], 200);
    }
    
    public function vehiclesApp(request $request){  
        $requestJson = json_decode($request->getContent(), true);
        
        //Update Log
        $apiLog = new \App\api_log();
        $apiLog->date = new \DateTime();
        $apiLog->input_json = serialize($requestJson);
        $apiLog->save();
        
        //Validate Tokens
        $beaerToken = explode(" ", $_SERVER['HTTP_AUTHORIZATION']);
        $validateToken = $this->validateBearerToken($beaerToken[0], $beaerToken[1]);
        if($validateToken != null){
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->response_json = $validateToken;
            $api_log->response_code = 401;
            $api_log->save();
//            return response()->json(['mensaje' => $validateToken, 'codigo' => '401'], 200);
        }
        
        //Validate JSON
        $json = json_decode($request->getContent(), true);
        if($this->is_json($request->getContent())){
            $validateJson = $this->validateVehiclesApp(json_decode($request->getContent(), true));
            if($validateJson != null){
                $api_log = \App\api_log::find($apiLog->id);
                $api_log->response_json = serialize($validateJson);
                $api_log->response_code = 402;
                $api_log->save();
                return response()->json(['mensaje' => $validateJson, 'codigo' => '402'], 200);
            }
        }else{
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->response_json = 'JSON Invalido';
            $api_log->response_code = 402;
            $api_log->save();
            return response()->json(['mensaje' => 'JSON Invalido', 'codigo' => '402'], 200);
        }
        
        //Obtain Customer Data from SS
        $vehicleSS = vehicleSearchApp($json['plate'], $json['status']);
        return $vehicleSS;
        $api_log = \App\api_log::find($apiLog->id);
        $api_log->response_json = serialize($customerSS);
        $api_log->response_code = 200;
        $api_log->save();
        return response()->json(['mensaje' => $customerSS, 'codigo' => '200'], 200);
    }
    
    public function productsR1App(request $request){  
        $requestJson = json_decode($request->getContent(), true);
        
        //Update Log
        $apiLog = new \App\api_log();
        $apiLog->date = new \DateTime();
        $apiLog->input_json = serialize($requestJson);
        $apiLog->save();
        
        //Validate Tokens
        $beaerToken = explode(" ", $_SERVER['HTTP_AUTHORIZATION']);
        $validateToken = $this->validateBearerToken($beaerToken[0], $beaerToken[1]);
        if($validateToken != null){
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->response_json = $validateToken;
            $api_log->response_code = 401;
            $api_log->save();
//            return response()->json(['mensaje' => $validateToken, 'codigo' => '401'], 200);
        }
        
        //Validate JSON
        $json = json_decode($request->getContent(), true);
        if($this->is_json($request->getContent())){
            $validateJson = $this->validateProductsVehiclesApp(json_decode($request->getContent(), true));
            if($validateJson != null){
                $api_log = \App\api_log::find($apiLog->id);
                $api_log->response_json = serialize($validateJson);
                $api_log->response_code = 402;
                $api_log->save();
                return response()->json(['mensaje' => $validateJson, 'codigo' => '402'], 200);
            }
        }else{
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->response_json = 'JSON Invalido';
            $api_log->response_code = 402;
            $api_log->save();
            return response()->json(['mensaje' => 'JSON Invalido', 'codigo' => '402'], 200);
        }
        
        //Obtain Customer Data from SS
        $vehicleSS = productsVehiclesApp($json);
        if(isset($vehicleSS[0]['productError'])){
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->response_json = serialize($vehicleSS);
            $api_log->response_code = 403;
            $api_log->save();
            return response()->json(['mensaje' => $vehicleSS, 'codigo' => '403'], 200);
        }else{
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->response_json = serialize($vehicleSS);
            $api_log->response_code = 200;
            $api_log->save();
            return response()->json(['mensaje' => $vehicleSS, 'codigo' => '200'], 200);
        }
    }
    
    public function productsR2App(request $request){  
        $requestJson = json_decode($request->getContent(), true);
        
        //Update Log
        $apiLog = new \App\api_log();
        $apiLog->date = new \DateTime();
        $apiLog->input_json = serialize($requestJson);
        $apiLog->save();
        
        //Validate Tokens
        $beaerToken = explode(" ", $_SERVER['HTTP_AUTHORIZATION']);
        $validateToken = $this->validateBearerToken($beaerToken[0], $beaerToken[1]);
        if($validateToken != null){
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->response_json = $validateToken;
            $api_log->response_code = 401;
            $api_log->save();
//            return response()->json(['mensaje' => $validateToken, 'codigo' => '401'], 200);
        }
        
        //Validate JSON
        $json = json_decode($request->getContent(), true);
        if($this->is_json($request->getContent())){
            $validateJson = $this->validateProductsR2App(json_decode($request->getContent(), true));
            if($validateJson != null){
                $api_log = \App\api_log::find($apiLog->id);
                $api_log->response_json = serialize($validateJson);
                $api_log->response_code = 402;
                $api_log->save();
                return response()->json(['mensaje' => $validateJson, 'codigo' => '402'], 200);
            }
        }else{
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->response_json = 'JSON Invalido';
            $api_log->response_code = 402;
            $api_log->save();
            return response()->json(['mensaje' => 'JSON Invalido', 'codigo' => '402'], 200);
        }
        
        //Obtain Customer Data from SS
        $r2SS = r2CheckPrice($json);
        
        if(isset($r2SS[0]['productError'])){
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->response_json = serialize($r2SS);
            $api_log->response_code = 403;
            $api_log->save();
            return response()->json(['mensaje' => $r2SS, 'codigo' => '403'], 200);
        }else{
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->response_json = serialize($r2SS);
            $api_log->response_code = 200;
            $api_log->save();
            return response()->json(['mensaje' => $r2SS, 'codigo' => '200'], 200);
        }
    }
    
    public function productsR4App(request $request){  
        $requestJson = json_decode($request->getContent(), true);
        
        //Update Log
        $apiLog = new \App\api_log();
        $apiLog->date = new \DateTime();
        $apiLog->input_json = serialize($requestJson);
        $apiLog->save();
        
        //Validate Tokens
        $beaerToken = explode(" ", $_SERVER['HTTP_AUTHORIZATION']);
        $validateToken = $this->validateBearerToken($beaerToken[0], $beaerToken[1]);
        if($validateToken != null){
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->response_json = $validateToken;
            $api_log->response_code = 401;
            $api_log->save();
//            return response()->json(['mensaje' => $validateToken, 'codigo' => '401'], 200);
        }
        
        //Validate JSON
        $json = json_decode($request->getContent(), true);
        if($this->is_json($request->getContent())){
            $validateJson = $this->validateProductsR4App(json_decode($request->getContent(), true));
            if($validateJson != null){
                $api_log = \App\api_log::find($apiLog->id);
                $api_log->response_json = serialize($validateJson);
                $api_log->response_code = 402;
                $api_log->save();
                return response()->json(['mensaje' => $validateJson, 'codigo' => '402'], 200);
            }
        }else{
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->response_json = 'JSON Invalido';
            $api_log->response_code = 402;
            $api_log->save();
            return response()->json(['mensaje' => 'JSON Invalido', 'codigo' => '402'], 200);
        }
        
        //Obtain Customer Data from SS
        $r4SS = r4CheckPrice($json);
        
        if(isset($r4SS[0]['productError'])){
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->response_json = serialize($r4SS);
            $api_log->response_code = 403;
            $api_log->save();
            return response()->json(['mensaje' => $r4SS, 'codigo' => '403'], 200);
        }else{
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->response_json = serialize($r4SS);
            $api_log->response_code = 200;
            $api_log->save();
            return response()->json(['mensaje' => $r4SS, 'codigo' => '200'], 200);
        }
    }
    
    public function rubrosR4App(request $request){  
        $requestJson = json_decode($request->getContent(), true);
        
        //Update Log
        $apiLog = new \App\api_log();
        $apiLog->date = new \DateTime();
        $apiLog->input_json = serialize($requestJson);
        $apiLog->save();
        
        //Validate Tokens
        $beaerToken = explode(" ", $_SERVER['HTTP_AUTHORIZATION']);
        $validateToken = $this->validateBearerToken($beaerToken[0], $beaerToken[1]);
        if($validateToken != null){
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->response_json = $validateToken;
            $api_log->response_code = 401;
            $api_log->save();
//            return response()->json(['mensaje' => $validateToken, 'codigo' => '401'], 200);
        }
        
        //Validate JSON
        $json = json_decode($request->getContent(), true);
        if($this->is_json($request->getContent())){
            $validateJson = $this->validateRubrosR4App(json_decode($request->getContent(), true));
            if($validateJson != null){
                $api_log = \App\api_log::find($apiLog->id);
                $api_log->response_json = serialize($validateJson);
                $api_log->response_code = 402;
                $api_log->save();
                return response()->json(['mensaje' => $validateJson, 'codigo' => '402'], 200);
            }
        }else{
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->response_json = 'JSON Invalido';
            $api_log->response_code = 402;
            $api_log->save();
            return response()->json(['mensaje' => 'JSON Invalido', 'codigo' => '402'], 200);
        }
        
        //Obtain Customer Data from SS
        $rubrosR4SS = r4CheckRubros($json['userId']);
        
        if(empty($rubrosR4SS)){
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->response_json = 'El usuario no tiene rubros asociados';
            $api_log->response_code = 403;
            $api_log->save();
            return response()->json(['mensaje' => 'El usuario no tiene rubros asociados', 'codigo' => '403'], 200);
        }else{
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->response_json = serialize($rubrosR4SS);
            $api_log->response_code = 200;
            $api_log->save();
            return response()->json(['mensaje' => $rubrosR4SS, 'codigo' => '200'], 200);
        }
    }
    
    public function linksApp(request $request){  
        $requestJson = json_decode($request->getContent(), true);
        
        //Update Log
        $apiLog = new \App\api_log();
        $apiLog->date = new \DateTime();
        $apiLog->input_json = serialize($requestJson);
        $apiLog->save();
        
        //Validate Tokens
        $beaerToken = explode(" ", $_SERVER['HTTP_AUTHORIZATION']);
        $validateToken = $this->validateBearerToken($beaerToken[0], $beaerToken[1]);
        if($validateToken != null){
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->response_json = $validateToken;
            $api_log->response_code = 401;
            $api_log->save();
//            return response()->json(['mensaje' => $validateToken, 'codigo' => '401'], 200);
        }
        
        //Validate JSON
        $json = json_decode($request->getContent(), true);
        if($this->is_json($request->getContent())){
            $validateJson = $this->validateLinksApp(json_decode($request->getContent(), true));
            if($validateJson != null){
                $api_log = \App\api_log::find($apiLog->id);
                $api_log->response_json = serialize($validateJson);
                $api_log->response_code = 402;
                $api_log->save();
                return response()->json(['mensaje' => $validateJson, 'codigo' => '402'], 200);
            }
        }else{
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->response_json = 'JSON Invalido';
            $api_log->response_code = 402;
            $api_log->save();
            return response()->json(['mensaje' => 'JSON Invalido', 'codigo' => '402'], 200);
        }
        
        //Validate and send corresponding email
        $errorMessage = null;
        switch ($json['type']) {
            case "Vinculación":
                $sale = \App\sales::find($json['saleId']);
                $customer = \App\customers::find($sale->customer_id);
                \App\Jobs\VinculationSendLinkEmailJobs::dispatchNow($sale->id, $customer->email, $customer->document);
                break;
            case "Beneficiarios":
                $sale = \App\sales::find($json['saleId']);
                $customer = \App\customers::find($sale->customer_id);
                \App\Jobs\beneficiariesRequestSendLinkEmailJobs::dispatchNow($sale->id, $customer->email, $customer->document);
                break;
            case "Solicitud":
                $sale = \App\sales::find($json['saleId']);
                $customer = \App\customers::find($sale->customer_id);
                \App\Jobs\insuranceRequestSendLinkEmailJobs::dispatchNow($sale->id, $customer->email, $customer->document);
                break;
            case "Pago":
                $sale = \App\sales::find($json['saleId']);
                $customer = \App\customers::find($sale->customer_id);
                \App\Jobs\paymentSendLinkUserEmailJobs::dispatchNow($sale->id, $customer->email, $customer->document);
                break;
            default:
                $errorMessage = 'No se pudo enviar el link.';
        }
        
        if($errorMessage != null){
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->response_json = $errorMessage;
            $api_log->response_code = 403;
            $api_log->save();
            return response()->json(['mensaje' => $errorMessage, 'codigo' => '403'], 200);
        }else{
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->response_json = 'Link enviado correctamente';
            $api_log->response_code = 200;
            $api_log->save();
            return response()->json(['mensaje' => 'Link enviado correctamente', 'codigo' => '200'], 200);
        }
    }
    
    public function salesApp(request $request){  
        $requestJson = json_decode($request->getContent(), true);
        
        //Update Log
        $apiLog = new \App\api_log();
        $apiLog->date = new \DateTime();
        $apiLog->input_json = serialize($requestJson);
        $apiLog->save();
        
        //Validate Tokens
        $beaerToken = explode(" ", $_SERVER['HTTP_AUTHORIZATION']);
        $validateToken = $this->validateBearerToken($beaerToken[0], $beaerToken[1]);
        if($validateToken != null){
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->response_json = $validateToken;
            $api_log->response_code = 401;
            $api_log->save();
//            return response()->json(['mensaje' => $validateToken, 'codigo' => '401'], 200);
        }
        
        //Validate JSON
        $json = json_decode($request->getContent(), true);
        if($this->is_json($request->getContent())){
            $validateJson = $this->validateSalesApp(json_decode($request->getContent(), true));
            if($validateJson != null){
                $api_log = \App\api_log::find($apiLog->id);
                $api_log->response_json = serialize($validateJson);
                $api_log->response_code = 402;
                $api_log->save();
                return response()->json(['mensaje' => $validateJson, 'codigo' => '402'], 200);
            }
        }else{
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->response_json = 'JSON Invalido';
            $api_log->response_code = 402;
            $api_log->save();
            return response()->json(['mensaje' => 'JSON Invalido', 'codigo' => '402'], 200);
        }
        
        //Data for sales list
        $user = User::find($json['userId']);
        
        //Validate User Role
        $userRol = null;
        $userQueryForm = '';
        
        //Validate User Type Sucre
        $rol = \App\rols::find($user->role_id);
        
        //Obtain Channel
        $channelQuery = 'select cha.* from channels cha join agencies agen on agen.channel_id = cha.id where agen.id =  "' . $user->agen_id . '"';
        $channel = DB::select($channelQuery);
        
        //ROL SEGUROS SUCRE
        if ($rol->rol_entity_id == 1) {
            //ROL TIPO GERENCIA/EJECUTIVO
            if($rol->rol_type_id == 1 || $rol->rol_type_id == 2){
                $userSucre = null;
                $userSucreQuery = '';
            }else{
            // ROL TIPO EJECUTIVO
                $userSucre = true;
                $userSucreQuery = ' products_channel.ejecutivo_ss_email = "'.$user->email.'"';
            }
        }
        //ROL CANAL
        if($rol->rol_entity_id == 2){
            //ROL TIPO GERENCIA
            if($rol->rol_type_id == 1){
                $userSucre = true;
                $userSucreQuery = 'channels.id = ' . $channel[0]->id;
            }elseif($rol->rol_type_id == 2){
            // ROL TIPO JEFATURA
                $userSucre = true;
                $userSucreQuery = ' agencies.id = "'.$user->agen_id.'"';
            }else{
            // ROL TIPO EJECUTIVO
                $userSucre = true;
                $userSucreQuery = ' sales.user_id = "'.$user->id.'"';
            }
        }
        
        //Obtain Sales List (Only shows last 10 sales)
        $sales = individual($json['saleId'], $json['firstName'] . ' ' .$json['lastName'], $json['document'], $json['plate'], null, null, $json['saleId'], null, null, $userRol, $userQueryForm, null, 10, $userSucre, $userSucreQuery);        
        
        $returnData = array();
        foreach($sales as $sal) {
            array_push($returnData, $sal);
        }
//        return json_encode($returnData, JSON_UNESCAPED_SLASHES);
        if(empty($returnData)){
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->response_json = 'No se encontraron ventas';
            $api_log->response_code = 403;
            $api_log->save();
            return response()->json(['mensaje' => 'No se encontraron ventas', 'codigo' => '403'], 200);
        }else{
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->response_json = serialize($returnData);
            $api_log->response_code = 200;
            $api_log->save();
            
            return response()->json(['mensaje' => $returnData, 'codigo' => '200'], 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
        }
    }
    
    public function inspections(request $request){  
        $requestJson = json_decode($request->getContent(), true);
        
        //Update Log
        $apiLog = new \App\api_log();
        $apiLog->date = new \DateTime();
        $apiLog->input_json = serialize($requestJson);
        $apiLog->save();
        
        //Validate Tokens
        $beaerToken = explode(" ", $_SERVER['HTTP_AUTHORIZATION']);
        $validateToken = $this->validateBearerToken($beaerToken[0], $beaerToken[1]);
        if($validateToken != null){
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->response_json = $validateToken;
            $api_log->response_code = 401;
            $api_log->save();
//            return response()->json(['mensaje' => $validateToken, 'codigo' => '401'], 200);
        }
        
        //Validate JSON
        $json = json_decode($request->getContent(), true);
        if($this->is_json($request->getContent())){
            $validateJson = $this->validateInspections(json_decode($request->getContent(), true));
            if($validateJson != null){
                $api_log = \App\api_log::find($apiLog->id);
                $api_log->response_json = serialize($validateJson);
                $api_log->response_code = 402;
                $api_log->save();
                return response()->json(['mensaje' => $validateJson, 'codigo' => '402'], 200);
            }else{
                //UPDATE SALES STATUS AND INSPECTION STATUS
                if($json['estado'] == 3){
                    
                }
                
                $api_log = \App\api_log::find($apiLog->id);
                $api_log->response_json = serialize($returnData);
                $api_log->response_code = 200;
                $api_log->save();

                return response()->json(['mensaje' => $returnData, 'codigo' => '200'], 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
            }
        }else{
            $api_log = \App\api_log::find($apiLog->id);
            $api_log->response_json = 'JSON Invalido';
            $api_log->response_code = 402;
            $api_log->save();
            return response()->json(['mensaje' => 'JSON Invalido', 'codigo' => '402'], 200);
        }
    }
}