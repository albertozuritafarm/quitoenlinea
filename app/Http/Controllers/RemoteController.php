<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
Use Redirect;
use Illuminate\Support\Carbon;

class RemoteController extends Controller {

    public function index() {
        return view('remote.index');
    }
    
    public function indexVehicles() {
        return view('remote.index_vehicles');
    }

    public function remotePayment(request $request) {
        $query = 'SELECT sal.id, sal.token_date_send FROM sales sal JOIN customers cus on cus.id = sal.customer_id WHERE sal.token = "' . $request['code'] . '" AND cus.document = "' . $request['document'] . '" AND sal.sales_type_id = 3 and sal.status_id = 2';
        $data = DB::select($query);

        if ($data) { // DATA EXISTS
            //Calculate Diff in Minutes
            $tokenDate = Carbon::parse($data[0]->token_date_send);
            $now = new \DateTime();

            $diff_in_minutes = $tokenDate->diffInMinutes($now);

            if ($diff_in_minutes > 30) { // EXPIRED CODE
                \Session::flash('errorMsg', 'El codigo ingresado se encuentra caducado. Por favor solicite un nuevo codigo <a href="#" onclick="resendCode('.$data[0]->id.')">Enlace</a>');
                return Redirect::back()->withInput();
            } else { // OK
                $query = 'SELECT id from charges WHERE sales_id = ' . $data[0]->id;
                $data = DB::select($query);
                //Obtain Charge
                $charge = \App\Charge::find($data[0]->id);
                //Obtain Sale Data
                $sale = \App\sales::find($charge->sales_id);
                //Obtain Charge Status
                $status = \App\status::find($sale->status_id);
                
                //Update Sale
                $sale->token_date_validate = $now;
                $sale->save();

                return view('remote.payment_create', [
                    'sale' => $sale,
                    'charge' => $charge,
                    'status' => $status
                ]);
            }
        } else { // INVALID CODE
            \Session::flash('errorMsg', 'El codigo ingresado es invalido. Por favor revise la información ingresada');
            return Redirect::back()->withInput();
        }
    }

    public function storePayment(request $request) {
        //Obtain Charge Data
        $charges = \App\Charge::find($request['chargeId']);
        
        //Validate if its Payed
        if($charges->status_id == 9){
            return view('remote.validate', [
                    'sale' => $charges->sales_id
            ]);
        }

        //Obtain Sale data
        $sale = \App\sales::find($charges->sales_id);

        //Payment Type
        if ($request['option'] == 'transfer') {
            $paymentType = 3;
        } else {
            $paymentType = 1;
        }

        //Payment
        $payment = new \App\payments();
        $payment->payment_type_id = $paymentType;
        $payment->date = now();
        $payment->number = $request['number'];
        $payment->value = $sale->total;
        $payment->save();

        $charge = \App\Charge::find($request['chargeId']);
        $charge->payments_id = $payment->id;
        $charge->status_id = 9;
        $charge->date = now();
        $charge->save();

        $customerQuery = 'select cus.email as "email" from customers cus
                            join sales sal on sal.customer_id = cus.id
                            where sal.id = ' . $sale->id;
        $customer = DB::select($customerQuery);

        //Send Welcome Mail
        welcomeMailRemote($sale->customer_id, $sale->id);

        //Activate SALE
        $result = activateSale($sale->id);
        
        return view('remote.validate', [
                    'sale' => $sale->id
                ]);
    }
    
    public function vehiPictures(request $request){
        $id = $request['salId'];
        $vehiclesQuery = 'select vsal.*,
                    vehi.plate,
                    vehi.model,
                    vehi.brand_id,
                    vehi.color,
                    vehi.year,
                    IF(vsal.date_picture_front>= now() - INTERVAL 1 DAY, "YES", "NO") as "pictureFront",
                    IF(vsal.date_picture_back>= now() - INTERVAL 1 DAY, "YES", "NO") as "pictureBack",
                    IF(vsal.date_picture_left>= now() - INTERVAL 1 DAY, "YES", "NO") as "pictureLeft",
                    IF(vsal.date_picture_right>= now() - INTERVAL 1 DAY, "YES", "NO") as "pictureRight",
                    IF(vsal.date_picture_roof>= now() - INTERVAL 1 DAY, "YES", "NO") as "pictureRoof",
                    IF(cha.date <= now() - INTERVAL 5 DAY, "NO", "NO") as "PAID"
                    from vehicles_sales vsal 
                    join vehicles vehi on vehi.id = vsal.vehicule_id 
                    left join charges cha on cha.sales_id = vsal.sales_id and cha.types_id = 1
                    where vsal.sales_id in (' .$id. ')';


        $vehicles = DB::select($vehiclesQuery);

        $vehiDropDown = '<div class="form-group" style="padding:15px">
                          <label for="sel1">Placa:</label>
                          <select class="form-control" id="plate" name="plate">';
        foreach($vehicles as $vehicle){
            $vehiDropDown .= '<option value="'.$vehicle->plate.'">'.$vehicle->plate.'</option>';
        }
        $vehiDropDown .= '</select>
                        </div>
                              <input type="hidden" id="salId" name="salId" value="'.$id.'">';

        $returnData = '
            <div class="scrollme">
                <table class="table table-bordered table-responsive table-responsive-stack scrollme">
                    <tr style="background-color: #807f7f;color: white;">
                    </tr>
                    <tr>';
        
        $returnData .= '<th style="background-color: #807f7f;color: white;">Frente</th><td>
                                        <form method="post" id="upload_formFront'.$vehicles[0]->id.'" name="upload_formFront'.$vehicles[0]->id.'" enctype="multipart/form-data" onsubmit="uploadPictureForm(\'upload_formFront\'+'.$vehicles[0]->id.'+\',Front\'">
                                          <input type="hidden" name="_token" value="'.$request->_token.'">
                                            <input type="hidden" name="vSalId" value="'.$vehicles[0]->id.'" />
                                            <div class="alert" id="messageFront'.$vehicles[0]->id.'" style="display: none"></div>
                                            <center>
                                                <div style="width:100px !important;padding: 0" class="inside" id="fileNameFront'.$vehicles[0]->id.'"></div>
                                                <div class="inputWrapper">';
                                                if($vehicles[0]->picture_front == null){
                                                    $returnData .= '<span id="uploaded_imageFront'.$vehicles[0]->id.'"></span>';
                                                } else {
                                                    $returnData .= '<span id="uploaded_imageFront'.$vehicles[0]->id.'"><a href="' . $vehicles[0]->picture_front . '" target="_blank"><img src="' . $vehicles[0]->picture_front . '" class="img-thumbnail" width="300" /></a></span>';
                                                }
                                    if($vehicles[0]->PAID == 'NO'){
                                        if($vehicles[0]->picture_front == null){
                                        $returnData .='<center>
                                                            <img src="images/mas.png" alt="Girl in a jacket" style="width:20px;height:20px;margin-bottom: -100px;">
                                                        </center>
                                                        ';
                                           $returnData .= '<input class="fileInput" type="file" name="select_fileFront" onchange="fileNameFunction(\''.$vehicles[0]->id.'\',\'Front\')" id="select_fileFront'.$vehicles[0]->id.'" />';
                                        }
                                    }             
                                    $returnData .= '
                                                </div>
                                            </center>
                                            <center>
                                                <!--<input type="submit" name="upload" id="uploadFront'.$vehicles[0]->id.'" class="btn btn-primary visible" value="Subir Foto">-->';
                                                if($vehicles[0]->PAID == 'NO'){
                                                    if($vehicles[0]->picture_front == null){
                                                        $returnData .= '<button type="submit" name="upload" id="uploadFront'.$vehicles[0]->id.'" class="btn btn-primary visible" onclick="uploadPictureForm(\'upload_formFront\'+'.$vehicles[0]->id.',\'Front\')"><span class="glyphicon glyphicon-upload"></span>&ensp;Subir Foto</button>';
                                                        $returnData .= '<a class="hidden" id="deletePictureFront'.$vehicles[0]->id.'" href="#" onclick="deletePictureForm(\''.$vehicles[0]->id.'\',\'Front\')"><img src="/images/menos.png" style="width:20px;height:20px"></a>';
                                                    }else{
                                                        if($vehicles[0]->pictureFront == 'YES'){
                                                            $returnData .= '<button type="submit" name="upload" id="uploadFront'.$vehicles[0]->id.'" class="btn btn-primary hidden" onclick="uploadPictureForm(\'upload_formFront\'+'.$vehicles[0]->id.',\'Front\')"><span class="glyphicon glyphicon-upload"></span>&ensp;Subir Foto</button>';
                                                            $returnData .= '<a class="hidden" id="deletePictureFront'.$vehicles[0]->id.'" href="#" onclick="deletePictureForm(\''.$vehicles[0]->id.'\',\'Front\')"><img src="/images/menos.png" style="width:20px;height:20px"></a>';   
                                                        }
                                                    }
                                                }
                              $returnData .='  
                    
                                            </center>
                                        </form>

                                    </td>';
        $returnData .= '<th style="background-color: #807f7f;color: white;">Techo</th><td>
                                        <form method="post" id="upload_formRoof'.$vehicles[0]->id.'" name="upload_formRoof'.$vehicles[0]->id.'" enctype="multipart/form-data" onsubmit="uploadPictureForm(\'upload_formRoof\'+'.$vehicles[0]->id.'+\',Roof\'">
                                          <input type="hidden" name="_token" value="'.$request->_token.'">
                                            <input type="hidden" name="vSalId" value="'.$vehicles[0]->id.'" />
                                            <div class="alert" id="messageRoof'.$vehicles[0]->id.'" style="display: none"></div>
                                            <center>
                                                <div style="width:100px !important;padding: 0" class="inside" id="fileNameRoof'.$vehicles[0]->id.'"></div>
                                                <div class="inputWrapper">';
                                                if($vehicles[0]->picture_roof == null){
                                                    $returnData .= '<span id="uploaded_imageRoof'.$vehicles[0]->id.'"></span>';
                                                } else {
                                                    $returnData .= '<span id="uploaded_imageRoof'.$vehicles[0]->id.'"><a href="' . $vehicles[0]->picture_roof . '" target="_blank"><img src="' . $vehicles[0]->picture_roof . '" class="img-thumbnail" width="300" /></a></span>';
                                                }
                                    if($vehicles[0]->PAID == 'NO'){
                                        if($vehicles[0]->picture_roof == null){
                                        $returnData .='<center>
                                                            <img src="images/mas.png" alt="Girl in a jacket" style="width:20px;height:20px;margin-bottom: -100px;">
                                                        </center>
                                                        ';
                                           $returnData .= '<input class="fileInput" type="file" name="select_fileRoof" onchange="fileNameFunction(\''.$vehicles[0]->id.'\',\'Roof\')" id="select_fileRoof'.$vehicles[0]->id.'" />';
                                        }
                                    }           
                                    $returnData .= '
                                                </div>
                                            </center>
                                            <center>
                                                <!--<input type="submit" name="upload" id="uploadRoof'.$vehicles[0]->id.'" class="btn btn-primary visible" value="Subir Foto">-->';
                                                if($vehicles[0]->PAID == 'NO'){
                                                    if($vehicles[0]->picture_roof == null){
                                                        $returnData .= '<button type="submit" name="upload" id="uploadRoof'.$vehicles[0]->id.'" class="btn btn-primary visible" onclick="uploadPictureForm(\'upload_formRoof\'+'.$vehicles[0]->id.',\'Roof\')"><span class="glyphicon glyphicon-upload"></span>&ensp;Subir Foto</button>';
                                                        $returnData .= '<a class="hidden" id="deletePictureRoof'.$vehicles[0]->id.'" href="#" onclick="deletePictureForm(\''.$vehicles[0]->id.'\',\'Roof\')"><img src="/images/menos.png" style="width:20px;height:20px"></a>';
                                                    }else{
                                                        $returnData .= '<button type="submit" name="upload" id="uploadRoof'.$vehicles[0]->id.'" class="btn btn-primary hidden" onclick="uploadPictureForm(\'upload_formRoof\'+'.$vehicles[0]->id.',\'Roof\')"><span class="glyphicon glyphicon-upload"></span>&ensp;Subir Foto</button>';
                                                        
                                                        $returnData .= '<a class="hidden" id="deletePictureRoof'.$vehicles[0]->id.'" href="#" onclick="deletePictureForm(\''.$vehicles[0]->id.'\',\'Roof\')"><img src="/images/menos.png" style="width:20px;height:20px"></a>';
                                                        

                                                    }
                                                }
                              $returnData .='  
                    
                                            </center>
                                        </form>

                                    </td>';
        $returnData .= '<th style="background-color: #807f7f;color: white;">Izquierda</th><td>
                                       <form method="post" id="upload_formLeft'.$vehicles[0]->id.'" name="upload_formLeft'.$vehicles[0]->id.'" enctype="multipart/form-data" onsubmit="uploadPictureForm(\'upload_formLeft\'+'.$vehicles[0]->id.'+\',Left\'">
                                          <input type="hidden" name="_token" value="'.$request->_token.'">
                                            <input type="hidden" name="vSalId" value="'.$vehicles[0]->id.'" />
                                            <div class="alert" id="messageLeft'.$vehicles[0]->id.'" style="display: none"></div>
                                            <center>
                                                <div style="width:100px !important;padding: 0" class="inside" id="fileNameLeft'.$vehicles[0]->id.'"></div>
                                                <div class="inputWrapper">';
                                                if($vehicles[0]->picture_left == null){
                                                    $returnData .= '<span id="uploaded_imageLeft'.$vehicles[0]->id.'"></span>';
                                                } else {
                                                    $returnData .= '<span id="uploaded_imageLeft'.$vehicles[0]->id.'"><a href="' . $vehicles[0]->picture_left . '" target="_blank"><img src="' . $vehicles[0]->picture_left . '" class="img-thumbnail" width="300" /></a></span>';
                                                }
                                    if($vehicles[0]->PAID == 'NO'){
                                        if($vehicles[0]->picture_left == null){
                                        $returnData .='<center>
                                                            <img src="images/mas.png" alt="Girl in a jacket" style="width:20px;height:20px;margin-bottom: -100px;">
                                                        </center>
                                                        ';
                                           $returnData .= '<input class="fileInput" type="file" name="select_fileLeft" onchange="fileNameFunction(\''.$vehicles[0]->id.'\',\'Left\')" id="select_fileLeft'.$vehicles[0]->id.'" />';
                                        }
                                    }            
                                    $returnData .= '
                                                </div>
                                            </center>
                                            <center>
                                                <!--<input type="submit" name="upload" id="uploadLeft'.$vehicles[0]->id.'" class="btn btn-primary visible" value="Subir Foto">-->';
                                                if($vehicles[0]->PAID == 'NO'){
                                                    If($vehicles[0]->picture_left == null){
                                                        $returnData .= '<button type="submit" name="upload" id="uploadLeft'.$vehicles[0]->id.'" class="btn btn-primary visible" onclick="uploadPictureForm(\'upload_formLeft\'+'.$vehicles[0]->id.',\'Left\')"><span class="glyphicon glyphicon-upload"></span>&ensp;Subir Foto</button>';
                                                        $returnData .= '<a class="hidden" id="deletePictureLeft'.$vehicles[0]->id.'" href="#" onclick="deletePictureForm(\''.$vehicles[0]->id.'\',\'Left\')"><img src="/images/menos.png" style="width:20px;height:20px"></a>';
                                                    }else{
                                                        $returnData .= '<button type="submit" name="upload" id="uploadLeft'.$vehicles[0]->id.'" class="btn btn-primary hidden" onclick="uploadPictureForm(\'upload_formLeft\'+'.$vehicles[0]->id.',\'Left\')"><span class="glyphicon glyphicon-upload"></span>&ensp;Subir Foto</button>';
                                                        $returnData .= '<a class="hidden" id="deletePictureLeft'.$vehicles[0]->id.'" href="#" onclick="deletePictureForm(\''.$vehicles[0]->id.'\',\'Left\')"><img src="/images/menos.png" style="width:20px;height:20px"></a>';
                                                    }
                                                }
                              $returnData .='  
                    
                                            </center>
                                        </form>

                                    </td>';
        $returnData .= '<th style="background-color: #807f7f;color: white;">Derecha</th><td>
                                        <form method="post" id="upload_formRight'.$vehicles[0]->id.'" name="upload_formRight'.$vehicles[0]->id.'" enctype="multipart/form-data" onsubmit="uploadPictureForm(\'upload_formRight\'+'.$vehicles[0]->id.'+\',Right\'">
                                          <input type="hidden" name="_token" value="'.$request->_token.'">
                                            <input type="hidden" name="vSalId" value="'.$vehicles[0]->id.'" />
                                            <div class="alert" id="messageRight'.$vehicles[0]->id.'" style="display: none"></div>
                                            <center>
                                                <div style="width:100px !important;padding: 0" class="inside" id="fileNameRight'.$vehicles[0]->id.'"></div>
                                                <div class="inputWrapper">';
                                                if($vehicles[0]->picture_right == null){
                                                    $returnData .= '<span id="uploaded_imageRight'.$vehicles[0]->id.'"></span>';
                                                } else {
                                                    $returnData .= '<span id="uploaded_imageRight'.$vehicle->id.'"><a href="' . $vehicles[0]->picture_right . '" target="_blank"><img src="' . $vehicles[0]->picture_right . '" class="img-thumbnail" width="300" /></a></span>';
                                                }
                                    if($vehicles[0]->PAID == 'NO'){
                                        if($vehicles[0]->picture_right == null){
                                        $returnData .='<center>
                                                            <img src="images/mas.png" alt="Girl in a jacket" style="width:20px;height:20px;margin-bottom: -100px;">
                                                        </center>
                                                        ';
                                           $returnData .= '<input class="fileInput" type="file" name="select_fileRight" onchange="fileNameFunction(\''.$vehicles[0]->id.'\',\'Right\')" id="select_fileRight'.$vehicles[0]->id.'" />';
                                        }
                                    }           
                                    $returnData .= '
                                                </div>
                                            </center>
                                            <center>
                                                <!--<input type="submit" name="upload" id="uploadRight'.$vehicles[0]->id.'" class="btn btn-primary visible" value="Subir Foto">-->';
                                                if($vehicles[0]->PAID == 'NO'){
                                                    if($vehicles[0]->picture_right == null){
                                                        $returnData .= '<button type="submit" name="upload" id="uploadRight'.$vehicles[0]->id.'" class="btn btn-primary visible" onclick="uploadPictureForm(\'upload_formRight\'+'.$vehicles[0]->id.',\'Right\')"><span class="glyphicon glyphicon-upload"></span>&ensp;Subir Foto</button>';
                                                        $returnData .= '<a class="hidden" id="deletePictureRight'.$vehicles[0]->id.'" href="#" onclick="deletePictureForm(\''.$vehicles[0]->id.'\',\'Right\')"><img src="/images/menos.png" style="width:20px;height:20px"></a>';
                                                    }else{
                                                        $returnData .= '<button type="submit" name="upload" id="uploadRight'.$vehicles[0]->id.'" class="btn btn-primary hidden" onclick="uploadPictureForm(\'upload_formRight\'+'.$vehicles[0]->id.',\'Right\')"><span class="glyphicon glyphicon-upload"></span>&ensp;Subir Foto</button>';
                                                        $returnData .= '<a class="hidden" id="deletePictureRight'.$vehicles[0]->id.'" href="#" onclick="deletePictureForm(\''.$vehicles[0]->id.'\',\'Right\')"><img src="/images/menos.png" style="width:20px;height:20px"></a>';

                                                    }
                                                }
                              $returnData .='  
                    
                                            </center>
                                        </form>

                                    </td>';
        $returnData .= '<th style="background-color: #807f7f;color: white;">Atras</th><td>
                                        <form method="post" id="upload_formBack'.$vehicles[0]->id.'" name="upload_formBack'.$vehicles[0]->id.'" enctype="multipart/form-data" onsubmit="uploadPictureForm(\'upload_formBack\'+'.$vehicles[0]->id.'+\',Back\'">
                                          <input type="hidden" name="_token" value="'.$request->_token.'">
                                            <input type="hidden" name="vSalId" value="'.$vehicles[0]->id.'" />
                                            <div class="alert" id="messageBack'.$vehicles[0]->id.'" style="display: none"></div>
                                            <center>
                                                <div style="width:100px !important;padding: 0" class="inside" id="fileNameBack'.$vehicles[0]->id.'"></div>
                                                <div class="inputWrapper">';
                                                if($vehicles[0]->picture_back == null){
                                                    $returnData .= '<span id="uploaded_imageBack'.$vehicles[0]->id.'"></span>';
                                                } else {
                                                    $returnData .= '<span id="uploaded_imageBack'.$vehicles[0]->id.'"><a href="' . $vehicles[0]->picture_back . '" target="_blank"><img src="' . $vehicles[0]->picture_back . '" class="img-thumbnail" width="300" /></a></span>';
                                                }
                                    if($vehicles[0]->PAID == 'NO'){
                                        if($vehicles[0]->picture_back == null){
                                        $returnData .='<center>
                                                            <img src="images/mas.png" alt="Girl in a jacket" style="width:20px;height:20px;margin-bottom: -100px;">
                                                        </center>
                                                        ';
                                           $returnData .= '<input class="fileInput" type="file" name="select_fileBack" onchange="fileNameFunction(\''.$vehicles[0]->id.'\',\'Back\')" id="select_fileBack'.$vehicles[0]->id.'" />';
                                        }
                                    }           
                                    $returnData .= '
                                                </div>
                                            </center>
                                            <center>
                                                <!--<input type="submit" name="upload" id="uploadBack'.$vehicles[0]->id.'" class="btn btn-primary visible" value="Subir Foto">-->';
                                                if($vehicles[0]->PAID == 'NO'){
                                                    if($vehicles[0]->picture_back == null){
                                                        $returnData .= '<button type="submit" name="upload" id="uploadBack'.$vehicles[0]->id.'" class="btn btn-primary visible" onclick="uploadPictureForm(\'upload_formBack\'+'.$vehicles[0]->id.',\'Back\')"><span class="glyphicon glyphicon-upload"></span>&ensp;Subir Foto</button>';
                                                        $returnData .= '<a class="hidden" id="deletePictureBack'.$vehicles[0]->id.'" href="#" onclick="deletePictureForm(\''.$vehicles[0]->id.'\',\'Back\')"><img src="/images/menos.png" style="width:20px;height:20px"></a>';
                                                    }else{
                                                        $returnData .= '<button type="submit" name="upload" id="uploadBack'.$vehicles[0]->id.'" class="btn btn-primary hidden" onclick="uploadPictureForm(\'upload_formBack\'+'.$vehicles[0]->id.',\'Back\')"><span class="glyphicon glyphicon-upload"></span>&ensp;Subir Foto</button>';
                                                        $returnData .= '<a class="hidden" id="deletePictureBack'.$vehicles[0]->id.'" href="#" onclick="deletePictureForm(\''.$vehicles[0]->id.'\',\'Back\')"><img src="/images/menos.png" style="width:20px;height:20px"></a>';

                                                    }
                                                }
                              $returnData .='  
                    
                                            </center>
                                        </form>

                                    </td></tr>';
        
        $returnData .= '</table>';
        return view('remote.vehi_pictures', [
                    'returnData' => $returnData,
                    'vehiDropDown' => $vehiDropDown
                ]);
    }
    
    public function sendVehiLink(request $request){
        $randomCode = rand(100000, 999999);
        
        $query = 'SELECT cus.mobile_phone from customers cus join sales sal on sal.customer_id = cus.id WHERE sal.id = "'.$request['salId'].'" ';
        $cus = DB::select($query);
        
        //Now
        $now = new \DateTime();
        
        //Update Sale
        $sale = \App\sales::find($request['salId']);
        $sale->token_vehicle = $randomCode;
        $sale->token_vehicle_date_send = $now;
        $sale->save();
        
        sendVehiLinkSMS($cus[0]->mobile_phone, $randomCode, $request['salId']);
        
        \Session::flash('vehiLinkMsg', 'Se envio un SMS con el link');
        return view('remote.index_vehicles');
    }
    
    public function remoteVehicles(request $request){
        //Produccion
//        $query = 'SELECT sal.id, sal.token_vehicle_date_send FROM sales sal JOIN customers cus on cus.id = sal.customer_id WHERE sal.token_vehicle = "' . $request['code'] . '" AND cus.document = "' . $request['document'] . '" AND sal.sales_type_id = 3 and sal.status_id = 13';
        //Localhost
        $query = 'SELECT sal.id, sal.token_vehicle_date_send FROM sales sal JOIN customers cus on cus.id = sal.customer_id WHERE sal.token_vehicle = "' . $request['code'] . '" AND cus.document = "' . $request['document'] . '" AND sal.sales_type_id = 3';
        $data = DB::select($query);
        
        if ($data) { // DATA EXISTS
            //Calculate Diff in Minutes
            $tokenDate = Carbon::parse($data[0]->token_vehicle_date_send);
            $now = new \DateTime();

            $diff_in_minutes = $tokenDate->diffInMinutes($now);

            if ($diff_in_minutes > 30) { // EXPIRED CODE
                \Session::flash('errorMsg', 'El codigo ingresado se encuentra caducado. Por favor solicite un nuevo codigo  <a href="#" onclick="resendCode('.$data[0]->id.')">Enlace</a>');
                return Redirect::back()->withInput();
            } else { // OK
                //Update Sale Data
                $sale = \App\sales::find($data[0]->id);
                $sale->token_vehicle_date_validate = $now;
                $sale->save();
                
                return view('remote.vehicle_redirect', [
                    'sale' => $sale->id,
                ]);
            }
        } else { // INVALID CODE
            \Session::flash('errorMsg', 'El codigo ingresado es invalido. Por favor revise la información ingresada');
            return Redirect::back()->withInput();
        }
    }
    
    public function loadVehiPictures(request $request){
        $id = $request['salId'];
        $plate = $request['plate'];
        //Obtain Vehicle Data
        $vehiclesQuery = 'select vsal.*,
                    vehi.plate,
                    vehi.model,
                    vehi.brand_id,
                    vehi.color,
                    vehi.year,
                    IF(vsal.date_picture_front>= now() - INTERVAL 1 DAY, "YES", "NO") as "pictureFront",
                    IF(vsal.date_picture_back>= now() - INTERVAL 1 DAY, "YES", "NO") as "pictureBack",
                    IF(vsal.date_picture_left>= now() - INTERVAL 1 DAY, "YES", "NO") as "pictureLeft",
                    IF(vsal.date_picture_right>= now() - INTERVAL 1 DAY, "YES", "NO") as "pictureRight",
                    IF(vsal.date_picture_roof>= now() - INTERVAL 1 DAY, "YES", "NO") as "pictureRoof",
                    IF(cha.date <= now() - INTERVAL 5 DAY, "NO", "NO") as "PAID"
                    from vehicles_sales vsal 
                    join vehicles vehi on vehi.id = vsal.vehicule_id 
                    left join charges cha on cha.sales_id = vsal.sales_id and cha.types_id = 1
                    where vsal.sales_id in (' .$id. ') and vehi.plate = "'.$plate.'"';


        $vehicles = DB::select($vehiclesQuery);
        
        $returnData = '
            <div class="scrollme">
                <table class="table table-bordered table-responsive table-responsive-stack scrollme">
                    <tr style="background-color: #807f7f;color: white;">
                    </tr>
                    <tr>';
                        
        $returnData .= '<th style="background-color: #807f7f;color: white;">Frente</th><td>
                                        <form method="post" id="upload_formFront'.$vehicles[0]->id.'" name="upload_formFront'.$vehicles[0]->id.'" enctype="multipart/form-data" onsubmit="uploadPictureForm(\'upload_formFront\'+'.$vehicles[0]->id.'+\',Front\'">
                                          <input type="hidden" name="_token" value="'.$request->_token.'">
                                            <input type="hidden" name="vSalId" value="'.$vehicles[0]->id.'" />
                                            <div class="alert" id="messageFront'.$vehicles[0]->id.'" style="display: none"></div>
                                            <center>
                                                <div style="width:100px !important;padding: 0" class="inside" id="fileNameFront'.$vehicles[0]->id.'"></div>
                                                <div class="inputWrapper">';
                                                if($vehicles[0]->picture_front == null){
                                                    $returnData .= '<span id="uploaded_imageFront'.$vehicles[0]->id.'"></span>';
                                                } else {
                                                    $returnData .= '<span id="uploaded_imageFront'.$vehicles[0]->id.'"><a href="' . $vehicles[0]->picture_front . '" target="_blank"><img src="' . $vehicles[0]->picture_front . '" class="img-thumbnail" width="300" /></a></span>';
                                                }
                                    if($vehicles[0]->PAID == 'NO'){
                                        if($vehicles[0]->picture_front == null){
                                        $returnData .='<center>
                                                            <img src="images/mas.png" alt="Girl in a jacket" style="width:20px;height:20px;margin-bottom: -100px;">
                                                        </center>
                                                        ';
                                           $returnData .= '<input class="fileInput" type="file" name="select_fileFront" onchange="fileNameFunction(\''.$vehicles[0]->id.'\',\'Front\')" id="select_fileFront'.$vehicles[0]->id.'" />';
                                        }
                                    }                
                                    $returnData .= '
                                                </div>
                                            </center>
                                            <center>
                                                <!--<input type="submit" name="upload" id="uploadFront'.$vehicles[0]->id.'" class="btn btn-primary visible" value="Subir Foto">-->';
                                                if($vehicles[0]->PAID == 'NO'){
                                                    if($vehicles[0]->picture_front == null){
                                                        $returnData .= '<button type="submit" name="upload" id="uploadFront'.$vehicles[0]->id.'" class="btn btn-primary visible" onclick="uploadPictureForm(\'upload_formFront\'+'.$vehicles[0]->id.',\'Front\')"><span class="glyphicon glyphicon-upload"></span>&ensp;Subir Foto</button>';
                                                        $returnData .= '<a class="hidden" id="deletePictureFront'.$vehicles[0]->id.'" href="#" onclick="deletePictureForm(\''.$vehicles[0]->id.'\',\'Front\')"><img src="/images/menos.png" style="width:20px;height:20px"></a>';
                                                    }else{
                                                        if($vehicles[0]->pictureFront == 'YES'){
                                                            $returnData .= '<button type="submit" name="upload" id="uploadFront'.$vehicles[0]->id.'" class="btn btn-primary hidden" onclick="uploadPictureForm(\'upload_formFront\'+'.$vehicles[0]->id.',\'Front\')"><span class="glyphicon glyphicon-upload"></span>&ensp;Subir Foto</button>';
                                                            $returnData .= '<a class="hidden" id="deletePictureFront'.$vehicles[0]->id.'" href="#" onclick="deletePictureForm(\''.$vehicles[0]->id.'\',\'Front\')"><img src="/images/menos.png" style="width:20px;height:20px"></a>';
                                                        }

                                                    }
                                                }
                              $returnData .='  
                    
                                            </center>
                                        </form>

                                    </td>';
        $returnData .= '<th style="background-color: #807f7f;color: white;">Techo</th><td>
                                        <form method="post" id="upload_formRoof'.$vehicles[0]->id.'" name="upload_formRoof'.$vehicles[0]->id.'" enctype="multipart/form-data" onsubmit="uploadPictureForm(\'upload_formRoof\'+'.$vehicles[0]->id.'+\',Roof\'">
                                          <input type="hidden" name="_token" value="'.$request->_token.'">
                                            <input type="hidden" name="vSalId" value="'.$vehicles[0]->id.'" />
                                            <div class="alert" id="messageRoof'.$vehicles[0]->id.'" style="display: none"></div>
                                            <center>
                                                <div style="width:100px !important;padding: 0" class="inside" id="fileNameRoof'.$vehicles[0]->id.'"></div>
                                                <div class="inputWrapper">';
                                                if($vehicles[0]->picture_roof == null){
                                                    $returnData .= '<span id="uploaded_imageRoof'.$vehicles[0]->id.'"></span>';
                                                } else {
                                                    $returnData .= '<span id="uploaded_imageRoof'.$vehicles[0]->id.'"><a href="' . $vehicles[0]->picture_roof . '" target="_blank"><img src="' . $vehicles[0]->picture_roof . '" class="img-thumbnail" width="300" /></a></span>';
                                                }
                                    if($vehicles[0]->PAID == 'NO'){
                                        if($vehicles[0]->picture_roof == null){
                                        $returnData .='<center>
                                                            <img src="images/mas.png" alt="Girl in a jacket" style="width:20px;height:20px;margin-bottom: -100px;">
                                                        </center>
                                                        ';
                                           $returnData .= '<input class="fileInput" type="file" name="select_fileRoof" onchange="fileNameFunction(\''.$vehicles[0]->id.'\',\'Roof\')" id="select_fileRoof'.$vehicles[0]->id.'" />';
                                        }
                                    }            
                                    $returnData .= '
                                                </div>
                                            </center>
                                            <center>
                                                <!--<input type="submit" name="upload" id="uploadRoof'.$vehicles[0]->id.'" class="btn btn-primary visible" value="Subir Foto">-->';
                                                if($vehicles[0]->PAID == 'NO'){
                                                    if($vehicles[0]->picture_roof == null){
                                                        $returnData .= '<button type="submit" name="upload" id="uploadRoof'.$vehicles[0]->id.'" class="btn btn-primary visible" onclick="uploadPictureForm(\'upload_formRoof\'+'.$vehicles[0]->id.',\'Roof\')"><span class="glyphicon glyphicon-upload"></span>&ensp;Subir Foto</button>';
                                                        $returnData .= '<a class="hidden" id="deletePictureRoof'.$vehicles[0]->id.'" href="#" onclick="deletePictureForm(\''.$vehicles[0]->id.'\',\'Roof\')"><img src="/images/menos.png" style="width:20px;height:20px"></a>';
                                                    }else{
                                                        $returnData .= '<button type="submit" name="upload" id="uploadRoof'.$vehicles[0]->id.'" class="btn btn-primary hidden" onclick="uploadPictureForm(\'upload_formRoof\'+'.$vehicles[0]->id.',\'Roof\')"><span class="glyphicon glyphicon-upload"></span>&ensp;Subir Foto</button>';
                                                        
                                                        $returnData .= '<a class="hidden" id="deletePictureRoof'.$vehicles[0]->id.'" href="#" onclick="deletePictureForm(\''.$vehicles[0]->id.'\',\'Roof\')"><img src="/images/menos.png" style="width:20px;height:20px"></a>';
                                                        

                                                    }
                                                }
                              $returnData .='  
                    
                                            </center>
                                        </form>

                                    </td>';
        $returnData .= '<th style="background-color: #807f7f;color: white;">Izquierda</th><td>
                                       <form method="post" id="upload_formLeft'.$vehicles[0]->id.'" name="upload_formLeft'.$vehicles[0]->id.'" enctype="multipart/form-data" onsubmit="uploadPictureForm(\'upload_formLeft\'+'.$vehicles[0]->id.'+\',Left\'">
                                          <input type="hidden" name="_token" value="'.$request->_token.'">
                                            <input type="hidden" name="vSalId" value="'.$vehicles[0]->id.'" />
                                            <div class="alert" id="messageLeft'.$vehicles[0]->id.'" style="display: none"></div>
                                            <center>
                                                <div style="width:100px !important;padding: 0" class="inside" id="fileNameLeft'.$vehicles[0]->id.'"></div>
                                                <div class="inputWrapper">';
                                                if($vehicles[0]->picture_left == null){
                                                    $returnData .= '<span id="uploaded_imageLeft'.$vehicles[0]->id.'"></span>';
                                                } else {
                                                    $returnData .= '<span id="uploaded_imageLeft'.$vehicles[0]->id.'"><a href="' . $vehicles[0]->picture_left . '" target="_blank"><img src="' . $vehicles[0]->picture_left . '" class="img-thumbnail" width="300" /></a></span>';
                                                }
                                    if($vehicles[0]->PAID == 'NO'){
                                        if($vehicles[0]->picture_left == null){
                                        $returnData .='<center>
                                                            <img src="images/mas.png" alt="Girl in a jacket" style="width:20px;height:20px;margin-bottom: -100px;">
                                                        </center>
                                                        ';
                                           $returnData .= '<input class="fileInput" type="file" name="select_fileLeft" onchange="fileNameFunction(\''.$vehicles[0]->id.'\',\'Left\')" id="select_fileLeft'.$vehicles[0]->id.'" />';
                                        }
                                    }             
                                    $returnData .= '
                                                </div>
                                            </center>
                                            <center>
                                                <!--<input type="submit" name="upload" id="uploadLeft'.$vehicles[0]->id.'" class="btn btn-primary visible" value="Subir Foto">-->';
                                                if($vehicles[0]->PAID == 'NO'){
                                                    If($vehicles[0]->picture_left == null){
                                                        $returnData .= '<button type="submit" name="upload" id="uploadLeft'.$vehicles[0]->id.'" class="btn btn-primary visible" onclick="uploadPictureForm(\'upload_formLeft\'+'.$vehicles[0]->id.',\'Left\')"><span class="glyphicon glyphicon-upload"></span>&ensp;Subir Foto</button>';
                                                        $returnData .= '<a class="hidden" id="deletePictureLeft'.$vehicles[0]->id.'" href="#" onclick="deletePictureForm(\''.$vehicles[0]->id.'\',\'Left\')"><img src="/images/menos.png" style="width:20px;height:20px"></a>';
                                                    }else{
                                                        $returnData .= '<button type="submit" name="upload" id="uploadLeft'.$vehicles[0]->id.'" class="btn btn-primary hidden" onclick="uploadPictureForm(\'upload_formLeft\'+'.$vehicles[0]->id.',\'Left\')"><span class="glyphicon glyphicon-upload"></span>&ensp;Subir Foto</button>';
                                                        $returnData .= '<a class="hidden" id="deletePictureLeft'.$vehicles[0]->id.'" href="#" onclick="deletePictureForm(\''.$vehicles[0]->id.'\',\'Left\')"><img src="/images/menos.png" style="width:20px;height:20px"></a>';

                                                    }
                                                }
                              $returnData .='  
                    
                                            </center>
                                        </form>

                                    </td>';
        $returnData .= '<th style="background-color: #807f7f;color: white;">Derecha</th><td>
                                        <form method="post" id="upload_formRight'.$vehicles[0]->id.'" name="upload_formRight'.$vehicles[0]->id.'" enctype="multipart/form-data" onsubmit="uploadPictureForm(\'upload_formRight\'+'.$vehicles[0]->id.'+\',Right\'">
                                          <input type="hidden" name="_token" value="'.$request->_token.'">
                                            <input type="hidden" name="vSalId" value="'.$vehicles[0]->id.'" />
                                            <div class="alert" id="messageRight'.$vehicles[0]->id.'" style="display: none"></div>
                                            <center>
                                                <div style="width:100px !important;padding: 0" class="inside" id="fileNameRight'.$vehicles[0]->id.'"></div>
                                                <div class="inputWrapper">';
                                                if($vehicles[0]->picture_right == null){
                                                    $returnData .= '<span id="uploaded_imageRight'.$vehicles[0]->id.'"></span>';
                                                } else {
                                                    $returnData .= '<span id="uploaded_imageRight'.$vehicles[0]->id.'"><a href="' . $vehicles[0]->picture_right . '" target="_blank"><img src="' . $vehicles[0]->picture_right . '" class="img-thumbnail" width="300" /></a></span>';
                                                }
                                    if($vehicles[0]->PAID == 'NO'){
                                        if($vehicles[0]->picture_right == null){
                                        $returnData .='<center>
                                                            <img src="images/mas.png" alt="Girl in a jacket" style="width:20px;height:20px;margin-bottom: -100px;">
                                                        </center>
                                                        ';
                                           $returnData .= '<input class="fileInput" type="file" name="select_fileRight" onchange="fileNameFunction(\''.$vehicles[0]->id.'\',\'Right\')" id="select_fileRight'.$vehicles[0]->id.'" />';
                                        }
                                    }            
                                    $returnData .= '
                                                </div>
                                            </center>
                                            <center>
                                                <!--<input type="submit" name="upload" id="uploadRight'.$vehicles[0]->id.'" class="btn btn-primary visible" value="Subir Foto">-->';
                                                if($vehicles[0]->PAID == 'NO'){
                                                    if($vehicles[0]->picture_right == null){
                                                        $returnData .= '<button type="submit" name="upload" id="uploadRight'.$vehicles[0]->id.'" class="btn btn-primary visible" onclick="uploadPictureForm(\'upload_formRight\'+'.$vehicles[0]->id.',\'Right\')"><span class="glyphicon glyphicon-upload"></span>&ensp;Subir Foto</button>';
                                                        $returnData .= '<a class="hidden" id="deletePictureRight'.$vehicles[0]->id.'" href="#" onclick="deletePictureForm(\''.$vehicles[0]->id.'\',\'Right\')"><img src="/images/menos.png" style="width:20px;height:20px"></a>';
                                                    }else{
                                                        $returnData .= '<button type="submit" name="upload" id="uploadRight'.$vehicles[0]->id.'" class="btn btn-primary hidden" onclick="uploadPictureForm(\'upload_formRight\'+'.$vehicles[0]->id.',\'Right\')"><span class="glyphicon glyphicon-upload"></span>&ensp;Subir Foto</button>';
                                                        $returnData .= '<a class="hidden" id="deletePictureRight'.$vehicles[0]->id.'" href="#" onclick="deletePictureForm(\''.$vehicles[0]->id.'\',\'Right\')"><img src="/images/menos.png" style="width:20px;height:20px"></a>';

                                                    }
                                                }
                              $returnData .='  
                    
                                            </center>
                                        </form>

                                    </td>';
        $returnData .= '<th style="background-color: #807f7f;color: white;">Atras</th><td>
                                        <form method="post" id="upload_formBack'.$vehicles[0]->id.'" name="upload_formBack'.$vehicles[0]->id.'" enctype="multipart/form-data" onsubmit="uploadPictureForm(\'upload_formBack\'+'.$vehicles[0]->id.'+\',Back\'">
                                          <input type="hidden" name="_token" value="'.$request->_token.'">
                                            <input type="hidden" name="vSalId" value="'.$vehicles[0]->id.'" />
                                            <div class="alert" id="messageBack'.$vehicles[0]->id.'" style="display: none"></div>
                                            <center>
                                                <div style="width:100px !important;padding: 0" class="inside" id="fileNameBack'.$vehicles[0]->id.'"></div>
                                                <div class="inputWrapper">';
                                                if($vehicles[0]->picture_back == null){
                                                    $returnData .= '<span id="uploaded_imageBack'.$vehicles[0]->id.'"></span>';
                                                } else {
                                                    $returnData .= '<span id="uploaded_imageBack'.$vehicles[0]->id.'"><a href="' . $vehicles[0]->picture_back . '" target="_blank"><img src="' . $vehicles[0]->picture_back . '" class="img-thumbnail" width="300" /></a></span>';
                                                }
                                    if($vehicles[0]->PAID == 'NO'){
                                        if($vehicles[0]->picture_back == null){
                                        $returnData .='<center>
                                                            <img src="images/mas.png" alt="Girl in a jacket" style="width:20px;height:20px;margin-bottom: -100px;">
                                                        </center>
                                                        ';
                                           $returnData .= '<input class="fileInput" type="file" name="select_fileBack" onchange="fileNameFunction(\''.$vehicles[0]->id.'\',\'Back\')" id="select_fileBack'.$vehicles[0]->id.'" />';
                                        }
                                    }           
                                    $returnData .= '
                                                </div>
                                            </center>
                                            <center>
                                                <!--<input type="submit" name="upload" id="uploadBack'.$vehicles[0]->id.'" class="btn btn-primary visible" value="Subir Foto">-->';
                                                if($vehicles[0]->PAID == 'NO'){
                                                    if($vehicles[0]->picture_back == null){
                                                        $returnData .= '<button type="submit" name="upload" id="uploadBack'.$vehicles[0]->id.'" class="btn btn-primary visible" onclick="uploadPictureForm(\'upload_formBack\'+'.$vehicles[0]->id.',\'Back\')"><span class="glyphicon glyphicon-upload"></span>&ensp;Subir Foto</button>';
                                                        $returnData .= '<a class="hidden" id="deletePictureBack'.$vehicles[0]->id.'" href="#" onclick="deletePictureForm(\''.$vehicles[0]->id.'\',\'Back\')"><img src="/images/menos.png" style="width:20px;height:20px"></a>';
                                                    }else{
                                                        $returnData .= '<button type="submit" name="upload" id="uploadBack'.$vehicles[0]->id.'" class="btn btn-primary hidden" onclick="uploadPictureForm(\'upload_formBack\'+'.$vehicles[0]->id.',\'Back\')"><span class="glyphicon glyphicon-upload"></span>&ensp;Subir Foto</button>';
                                                        $returnData .= '<a class="hidden" id="deletePictureBack'.$vehicles[0]->id.'" href="#" onclick="deletePictureForm(\''.$vehicles[0]->id.'\',\'Back\')"><img src="/images/menos.png" style="width:20px;height:20px"></a>';
                                                    }
                                                }
                              $returnData .='  
                    
                                            </center>
                                        </form>

                                    </td></tr>';
        
        $returnData .= '</table>';
        $returnArray = [
            'returnData' => $returnData
        ];
        return $returnArray;
    }
    public function resendCodePayment(request $request){
        $randomCode = rand(100000, 999999);
        
        $query = 'SELECT cus.mobile_phone from customers cus join sales sal on sal.customer_id = cus.id WHERE sal.id = "'.$request['salId'].'" ';
        $cus = DB::select($query);
        
        //Now
        $now = new \DateTime();
        
        //Update Sale
        $sale = \App\sales::find($request['salId']);
        $sale->token = $randomCode;
        $sale->token_date_send = $now;
        $sale->save();
        
        sendLinkSMS($cus[0]->mobile_phone, $randomCode, $request['salId']);
        
        \Session::flash('vehiLinkMsg', 'Se envio un SMS con el link');
        return view('remote.index');
    }
    public function resendCodeVehicles(request $request){
        $randomCode = rand(100000, 999999);
        
        $query = 'SELECT cus.mobile_phone from customers cus join sales sal on sal.customer_id = cus.id WHERE sal.id = "'.$request['salId'].'" ';
        $cus = DB::select($query);
        
        //Now
        $now = new \DateTime();
        
        //Update Sale
        $sale = \App\sales::find($request['salId']);
        $sale->token_vehicle = $randomCode;
        $sale->token_vehicle_date_send = $now;
        $sale->save();
        
        sendVehiLinkSMS($cus[0]->mobile_phone, $randomCode, $request['salId']);
        
        \Session::flash('vehiLinkMsg', 'Se envio un SMS con el link');
        return view('remote.index_vehicles');
    }
}
