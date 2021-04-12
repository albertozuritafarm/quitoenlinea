<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class VehiclesSales extends Controller {

    function index() {
        return view('ajax_upload');
    }

    function actionFront(Request $request) {
//        return $request;
//        $vehi = DB::select('select * from vehicles_sales where sales_id = ?',[$request->vSalId]);
        $validation = Validator::make($request->all(), [
                    'select_fileFront' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validation->passes()) {
            //Vehicule Sale
            $vSal = \App\vehicles_sales::find($request->vSalId);

            //Vehicule
            $vehicle = \App\vehicles::find($vSal->vehicule_id);

            //Vehicle Folder
            $vehiFolder = public_path('images/Vehicules/') . $vehicle->plate . '/';
            //Create Vehicle Folder
            if (!file_exists($vehiFolder)) {
                mkdir($vehiFolder, 0777, true);
            }

            $image = $request->file('select_fileFront');
            $new_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/Vehicules/' . $vehicle->plate), $new_name);

//            $vSal->picture_front = $vehiFolder . $new_name;
            $vSal->picture_front = getAppRoute() . '/images/Vehicules/' . $vehicle->plate . '/' . $new_name;
            $now = new \DateTime();
            $vSal->date_picture_front = $now;
            $vSal->Save();

            //Activate SALE
            $result = activateSale($vSal->sales_id);
//            return $result;
            return response()->json([
                        'message' => 'Image Upload Successfully',
                        'uploaded_image' => '<a href="' . $vSal->picture_front . '" target="_blank"><img src="' . $vSal->picture_front . '" class="img-thumbnail" width="300" /></a>',
                        'class_name' => 'alert-success',
                        'vSalId' => $request->vSalId,
                        'Success' => 'true',
                        'Active' => $result,
                        'salesId' => $vSal->sales_id
            ]);
        } else {
            return response()->json([
//       'message'   => $validation->errors()->all(),
                        'message' => 'Debe subir la imagen en un formato valido',
                        'uploaded_image' => '',
                        'class_name' => 'alert-danger',
                        'vSalId' => $request->vSalId,
                        'Success' => 'false'
            ]);
        }
    }

    function deleteLeft(Request $request) {

        $vSal = \App\vehicles_sales::find($request['data']['id']);
        $vSal->picture_left = null;
        $now = new \DateTime();
        $vSal->date_picture_left = $now;
        $vSal->Save();

        //UPDATE SALE STATUS
        changeSaleStatus($vSal->sales_id, 13);

        return response()->json([
                    'message' => 'Image Upload Successfully',
                    'uploaded_image' => '>',
                    'class_name' => 'alert-success',
                    'vSalId' => $vSal->sales_id,
                    'Success' => 'true'
        ]);
    }

    function deleteFront(Request $request) {


//        $vSalQuery = DB::select('select * from vehicu');

        $vSal = \App\vehicles_sales::find($request['data']['id']);
        $vSal->picture_front = null;
        $now = new \DateTime();
        $vSal->date_picture_front = $now;
        $vSal->Save();

        //UPDATE SALE STATUS
        changeSaleStatus($vSal->sales_id, 13);

        return response()->json([
                    'message' => 'Image Upload Successfully',
                    'uploaded_image' => '>',
                    'class_name' => 'alert-success',
                    'vSalId' => $vSal->sales_id,
                    'Success' => 'true'
        ]);
    }

    function deleteRight(Request $request) {

        $vSal = \App\vehicles_sales::find($request['data']['id']);
        $vSal->picture_right = null;
        $now = new \DateTime();
        $vSal->date_picture_right = $now;
        $vSal->Save();

        //UPDATE SALE STATUS
        changeSaleStatus($vSal->sales_id, 13);

        return response()->json([
                    'message' => 'Image Upload Successfully',
                    'uploaded_image' => '>',
                    'class_name' => 'alert-success',
                    'vSalId' => $vSal->sales_id,
                    'Success' => 'true'
        ]);
    }

    function deleteBack(Request $request) {
//        return $request;
        $vSal = \App\vehicles_sales::find($request['data']['id']);
        $vSal->picture_back = null;
        $now = new \DateTime();
        $vSal->date_picture_back = $now;
        $vSal->Save();

        //UPDATE SALE STATUS
        changeSaleStatus($vSal->sales_id, 13);

        return response()->json([
                    'message' => 'Image Upload Successfully',
                    'uploaded_image' => '>',
                    'class_name' => 'alert-success',
                    'vSalId' => $vSal->sales_id,
                    'Success' => 'true'
        ]);
    }

    function deleteRoof(Request $request) {

        $vSal = \App\vehicles_sales::find($request['data']['id']);
        $vSal->picture_roof = null;
        $now = new \DateTime();
        $vSal->date_picture_roof = $now;
        $vSal->Save();

        //UPDATE SALE STATUS
        changeSaleStatus($vSal->sales_id, 13);

        return response()->json([
                    'message' => 'Image Upload Successfully',
                    'uploaded_image' => '1234',
                    'class_name' => 'alert-success',
                    'vSalId' => $vSal->sales_id,
                    'Success' => 'true'
        ]);
    }

    function actionBack(Request $request) {
//        return $request;
        $validation = Validator::make($request->all(), [
                    'select_fileBack' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        if ($validation->passes()) {
            //Vehicule Sale
            $vSal = \App\vehicles_sales::find($request->vSalId);

            //Vehicule
            $vehicle = \App\vehicles::find($vSal->vehicule_id);

            //Vehicle Folder
            $vehiFolder = public_path('images/Vehicules/') . $vehicle->plate . '/';
            //Create Vehicle Folder
            if (!file_exists($vehiFolder)) {
                mkdir($vehiFolder, 0777, true);
            }

            $image = $request->file('select_fileBack');
            $new_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/Vehicules/' . $vehicle->plate), $new_name);

            $vSal->picture_back = getAppRoute() . '/images/Vehicules/' . $vehicle->plate . '/' . $new_name;
            $now = new \DateTime();
            $vSal->date_picture_back = $now;
            $vSal->Save();

            //Activate SALE
            $result = activateSale($vSal->sales_id);

            return response()->json([
                        'message' => 'Image Upload Successfully',
                        'uploaded_image' => '<a href="' . $vSal->picture_back . '" target="_blank"><img src="' . $vSal->picture_back . '" class="img-thumbnail" width="300" /></a>',
                        'class_name' => 'alert-success',
                        'vSalId' => $request->vSalId,
                        'Success' => 'true',
                        'Active' => $result,
                        'salesId' => $vSal->sales_id
            ]);
        } else {
            return response()->json([
                        'message' => 'Debe subir la imagen en un formato valido',
                        'uploaded_image' => '',
                        'class_name' => 'alert-danger',
                        'vSalId' => $request->vSalId,
                        'Success' => 'false'
            ]);
        }
    }

    function actionRight(Request $request) {
//        return $request;
        $validation = Validator::make($request->all(), [
                    'select_fileRight' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        if ($validation->passes()) {
            //Vehicule Sale
            $vSal = \App\vehicles_sales::find($request->vSalId);

            //Vehicule
            $vehicle = \App\vehicles::find($vSal->vehicule_id);

            //Vehicle Folder
            $vehiFolder = public_path('images/Vehicules/') . $vehicle->plate . '/';
            //Create Vehicle Folder
            if (!file_exists($vehiFolder)) {
                mkdir($vehiFolder, 0777, true);
            }

            $image = $request->file('select_fileRight');
            $new_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/Vehicules/' . $vehicle->plate), $new_name);

            $vSal->picture_right = getAppRoute() . '/images/Vehicules/' . $vehicle->plate . '/' . $new_name;
            $now = new \DateTime();
            $vSal->date_picture_right = $now;
            $vSal->Save();

            //Activate SALE
            $result = activateSale($vSal->sales_id);

            return response()->json([
                        'message' => 'Debe subir la imagen en un formato valido',
                        'uploaded_image' => '<a href="' . $vSal->picture_right . '" target="_blank"><img src="' . $vSal->picture_right . '" class="img-thumbnail" width="300" /></a>',
                        'class_name' => 'alert-success',
                        'vSalId' => $request->vSalId,
                        'Success' => 'true',
                        'Active' => $result,
                        'salesId' => $vSal->sales_id
            ]);
        } else {
            return response()->json([
                        'message' => $validation->errors()->all(),
                        'uploaded_image' => '',
                        'class_name' => 'alert-danger',
                        'vSalId' => $request->vSalId,
                        'Success' => 'false'
            ]);
        }
    }

    function actionLeft(Request $request) {
//        return $request;
        $validation = Validator::make($request->all(), [
                    'select_fileLeft' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        if ($validation->passes()) {
            //Vehicule Sale
            $vSal = \App\vehicles_sales::find($request->vSalId);

            //Vehicule
            $vehicle = \App\vehicles::find($vSal->vehicule_id);

            //Vehicle Folder
            $vehiFolder = public_path('images/Vehicules/') . $vehicle->plate . '/';
            //Create Vehicle Folder
            if (!file_exists($vehiFolder)) {
                mkdir($vehiFolder, 0777, true);
            }

            $image = $request->file('select_fileLeft');
            $new_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/Vehicules/' . $vehicle->plate), $new_name);

            $vSal->picture_left = getAppRoute() . '/images/Vehicules/' . $vehicle->plate . '/' . $new_name;
            $now = new \DateTime();
            $vSal->date_picture_left = $now;
            $vSal->Save();

            //Activate SALE
            $result = activateSale($vSal->sales_id);

            return response()->json([
                        'message' => 'Image Upload Successfully',
                        'uploaded_image' => '<a href="' . $vSal->picture_left . '" target="_blank"><img src="' . $vSal->picture_left . '" class="img-thumbnail" width="300" /></a>',
                        'class_name' => 'alert-success',
                        'vSalId' => $request->vSalId,
                        'Success' => 'true',
                        'Active' => $result,
                        'salesId' => $vSal->sales_id
            ]);
        } else {
            return response()->json([
                        'message' => 'Debe subir la imagen en un formato valido',
                        'uploaded_image' => '',
                        'class_name' => 'alert-danger',
                        'vSalId' => $request->vSalId,
                        'Success' => 'false'
            ]);
        }
    }

    function actionRoof(Request $request) {
//        return $request;
        $validation = Validator::make($request->all(), [
                    'select_fileRoof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        if ($validation->passes()) {
            //Vehicule Sale
            $vSal = \App\vehicles_sales::find($request->vSalId);

            //Vehicule
            $vehicle = \App\vehicles::find($vSal->vehicule_id);

            //Vehicle Folder
            $vehiFolder = public_path('images/Vehicules/') . $vehicle->plate . '/';
            //Create Vehicle Folder
            if (!file_exists($vehiFolder)) {
                mkdir($vehiFolder, 0777, true);
            }

            $image = $request->file('select_fileRoof');
            $new_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/Vehicules/' . $vehicle->plate), $new_name);

            $vSal->picture_roof = getAppRoute() . '/images/Vehicules/' . $vehicle->plate . '/' . $new_name;
            $now = new \DateTime();
            $vSal->date_picture_roof = $now;
            $vSal->Save();

            //Activate SALE
            $result = activateSale($vSal->sales_id);

            return response()->json([
                        'message' => 'Image Upload Successfully',
                        'uploaded_image' => '<a href="' . $vSal->picture_roof . '" target="_blank"><img src="' . $vSal->picture_roof . '" class="img-thumbnail" width="300" /></a>',
                        'class_name' => 'alert-success',
                        'vSalId' => $request->vSalId,
                        'Success' => 'true',
                        'Active' => $result,
                        'salesId' => $vSal->sales_id
            ]);
        } else {
            return response()->json([
                        'message' => 'Debe subir la imagen en un formato valido',
                        'uploaded_image' => '',
                        'class_name' => 'alert-danger',
                        'vSalId' => $request->vSalId,
                        'Success' => 'false'
            ]);
        }
    }

    function modalPictures(request $request) {
//        return $request;
        $id = $request['data']['id'];
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
                    where vsal.sales_id in (' . $id . ')';
//        return $vehiclesQuery;

        $vehicles = DB::select($vehiclesQuery);
//        return $vehicles;
        $returnData = '<br><br>
                <table class="table table-bordered">
                    <tr style="background-color: #807f7f;color: white;">
                        <th>Placa</th>
                        <th>Frente</th>
                        <th>Techo</th>
                        <th>Izquierda</th>
                        <th>Derecha</th>
                        <th>Atras</th>
                    </tr>
                    <tr>';
        foreach ($vehicles as $vehicle) {

            $returnData .= '<td>
                            <center>
                                <br><br><br><span style="margin-top:-50px">' . $vehicle->plate . '</span>
                            </center>
                        </td>';

            $returnData .= '<td>
                                        <form method="post" id="upload_formFront' . $vehicle->id . '" name="upload_formFront' . $vehicle->id . '" enctype="multipart/form-data" onsubmit="uploadPictureForm(\'upload_formFront\'+' . $vehicle->id . '+\',Front\'">
                                          <input type="hidden" name="_token" value="' . $request->_token . '">
                                            <input type="hidden" name="vSalId" value="' . $vehicle->id . '" />
                                            <div class="alert" id="messageFront' . $vehicle->id . '" style="display: none"></div>
                                            <center>
                                                <div style="width:100px !important;padding: 0" class="inside" id="fileNameFront' . $vehicle->id . '"></div>
                                                <div class="inputWrapper">';
            if ($vehicle->picture_front == null) {
                $returnData .= '<span id="uploaded_imageFront' . $vehicle->id . '"></span>';
            } else {
                $returnData .= '<span id="uploaded_imageFront' . $vehicle->id . '"><a href="' . $vehicle->picture_front . '" target="_blank"><img src="' . $vehicle->picture_front . '" class="img-thumbnail" width="300" /></a></span>';
            }
            if ($vehicle->PAID == 'NO') {
                $returnData .= '<center>
                                                            <img src="images/mas.png" alt="Girl in a jacket" style="width:20px;height:20px;margin-bottom: -100px;">
                                                        </center>
                                                        <input class="fileInput" type="file" name="select_fileFront" onchange="fileNameFunction(\'' . $vehicle->id . '\',\'Front\')" id="select_fileFront' . $vehicle->id . '" />';
            }
            $returnData .= '
                                                </div>
                                            </center>
                                            <center>
                                                <!--<input type="submit" name="upload" id="uploadFront' . $vehicle->id . '" class="btn btn-primary visible" value="Subir Foto">-->';
            if ($vehicle->PAID == 'NO') {
                if ($vehicle->picture_front == null) {
                    $returnData .= '<button type="submit" name="upload" id="uploadFront' . $vehicle->id . '" class="btn btn-primary visible" onclick="uploadPictureForm(\'upload_formFront\'+' . $vehicle->id . ',\'Front\')"><span class="glyphicon glyphicon-upload"></span>&ensp;Subir Foto</button>';
                    $returnData .= '<a class="hidden" id="deletePictureFront' . $vehicle->id . '" href="#" onclick="deletePictureForm(\'' . $vehicle->id . '\',\'Front\')"><img src="images/menos.png" style="width:20px;height:20px"></a>';
                } else {
                    if ($vehicle->pictureFront == 'YES') {
                        $returnData .= '<button type="submit" name="upload" id="uploadFront' . $vehicle->id . '" class="btn btn-primary hidden" onclick="uploadPictureForm(\'upload_formFront\'+' . $vehicle->id . ',\'Front\')"><span class="glyphicon glyphicon-upload"></span>&ensp;Subir Foto</button>';
                        $returnData .= '<a class="visible" id="deletePictureFront' . $vehicle->id . '" href="#" onclick="deletePictureForm(\'' . $vehicle->id . '\',\'Front\')"><img src="images/menos.png" style="width:20px;height:20px"></a>';
                    }
                }
            }
            $returnData .= '  
                    
                                            </center>
                                        </form>

                                    </td>';
            $returnData .= '<td>
                                        <form method="post" id="upload_formRoof' . $vehicle->id . '" name="upload_formRoof' . $vehicle->id . '" enctype="multipart/form-data" onsubmit="uploadPictureForm(\'upload_formRoof\'+' . $vehicle->id . '+\',Roof\'">
                                          <input type="hidden" name="_token" value="' . $request->_token . '">
                                            <input type="hidden" name="vSalId" value="' . $vehicle->id . '" />
                                            <div class="alert" id="messageRoof' . $vehicle->id . '" style="display: none"></div>
                                            <center>
                                                <div style="width:100px !important;padding: 0" class="inside" id="fileNameRoof' . $vehicle->id . '"></div>
                                                <div class="inputWrapper">';
            if ($vehicle->picture_roof == null) {
                $returnData .= '<span id="uploaded_imageRoof' . $vehicle->id . '"></span>';
            } else {
                $returnData .= '<span id="uploaded_imageRoof' . $vehicle->id . '"><a href="' . $vehicle->picture_roof . '" target="_blank"><img src="' . $vehicle->picture_roof . '" class="img-thumbnail" width="300" /></a></span>';
            }
            if ($vehicle->PAID == 'NO') {
                $returnData .= '<center>
                                                            <img src="images/mas.png" alt="Girl in a jacket" style="width:20px;height:20px;margin-bottom: -100px;">
                                                        </center>
                                                        <input class="fileInput" type="file" name="select_fileRoof" onchange="fileNameFunction(\'' . $vehicle->id . '\',\'Roof\')" id="select_fileRoof' . $vehicle->id . '" />';
            }
            $returnData .= '
                                                </div>
                                            </center>
                                            <center>
                                                <!--<input type="submit" name="upload" id="uploadRoof' . $vehicle->id . '" class="btn btn-primary visible" value="Subir Foto">-->';
            if ($vehicle->PAID == 'NO') {
                if ($vehicle->picture_roof == null) {
                    $returnData .= '<button type="submit" name="upload" id="uploadRoof' . $vehicle->id . '" class="btn btn-primary visible" onclick="uploadPictureForm(\'upload_formRoof\'+' . $vehicle->id . ',\'Roof\')"><span class="glyphicon glyphicon-upload"></span>&ensp;Subir Foto</button>';
                    $returnData .= '<a class="hidden" id="deletePictureRoof' . $vehicle->id . '" href="#" onclick="deletePictureForm(\'' . $vehicle->id . '\',\'Roof\')"><img src="images/menos.png" style="width:20px;height:20px"></a>';
                } else {
                    $returnData .= '<button type="submit" name="upload" id="uploadRoof' . $vehicle->id . '" class="btn btn-primary hidden" onclick="uploadPictureForm(\'upload_formRoof\'+' . $vehicle->id . ',\'Roof\')"><span class="glyphicon glyphicon-upload"></span>&ensp;Subir Foto</button>';

                    $returnData .= '<a class="visible" id="deletePictureRoof' . $vehicle->id . '" href="#" onclick="deletePictureForm(\'' . $vehicle->id . '\',\'Roof\')"><img src="images/menos.png" style="width:20px;height:20px"></a>';
                }
            }
            $returnData .= '  
                    
                                            </center>
                                        </form>

                                    </td>';
            $returnData .= '<td>
                                       <form method="post" id="upload_formLeft' . $vehicle->id . '" name="upload_formLeft' . $vehicle->id . '" enctype="multipart/form-data" onsubmit="uploadPictureForm(\'upload_formLeft\'+' . $vehicle->id . '+\',Left\'">
                                          <input type="hidden" name="_token" value="' . $request->_token . '">
                                            <input type="hidden" name="vSalId" value="' . $vehicle->id . '" />
                                            <div class="alert" id="messageLeft' . $vehicle->id . '" style="display: none"></div>
                                            <center>
                                                <div style="width:100px !important;padding: 0" class="inside" id="fileNameLeft' . $vehicle->id . '"></div>
                                                <div class="inputWrapper">';
            if ($vehicle->picture_left == null) {
                $returnData .= '<span id="uploaded_imageLeft' . $vehicle->id . '"></span>';
            } else {
                $returnData .= '<span id="uploaded_imageLeft' . $vehicle->id . '"><a href="' . $vehicle->picture_left . '" target="_blank"><img src="' . $vehicle->picture_left . '" class="img-thumbnail" width="300" /></a></span>';
            }
            if ($vehicle->PAID == 'NO') {
                $returnData .= '<center>
                                                            <img src="images/mas.png" alt="Girl in a jacket" style="width:20px;height:20px;margin-bottom: -100px;">
                                                        </center>
                                                        <input class="fileInput" type="file" name="select_fileLeft" onchange="fileNameFunction(\'' . $vehicle->id . '\',\'Left\')" id="select_fileLeft' . $vehicle->id . '" />';
            }
            $returnData .= '
                                                </div>
                                            </center>
                                            <center>
                                                <!--<input type="submit" name="upload" id="uploadLeft' . $vehicle->id . '" class="btn btn-primary visible" value="Subir Foto">-->';
            if ($vehicle->PAID == 'NO') {
                If ($vehicle->picture_left == null) {
                    $returnData .= '<button type="submit" name="upload" id="uploadLeft' . $vehicle->id . '" class="btn btn-primary visible" onclick="uploadPictureForm(\'upload_formLeft\'+' . $vehicle->id . ',\'Left\')"><span class="glyphicon glyphicon-upload"></span>&ensp;Subir Foto</button>';
                    $returnData .= '<a class="hidden" id="deletePictureLeft' . $vehicle->id . '" href="#" onclick="deletePictureForm(\'' . $vehicle->id . '\',\'Left\')"><img src="images/menos.png" style="width:20px;height:20px"></a>';
                } else {
                    $returnData .= '<button type="submit" name="upload" id="uploadLeft' . $vehicle->id . '" class="btn btn-primary hidden" onclick="uploadPictureForm(\'upload_formLeft\'+' . $vehicle->id . ',\'Left\')"><span class="glyphicon glyphicon-upload"></span>&ensp;Subir Foto</button>';
                    $returnData .= '<a class="visible" id="deletePictureLeft' . $vehicle->id . '" href="#" onclick="deletePictureForm(\'' . $vehicle->id . '\',\'Left\')"><img src="images/menos.png" style="width:20px;height:20px"></a>';
                }
            }
            $returnData .= '  
                    
                                            </center>
                                        </form>

                                    </td>';
            $returnData .= '<td>
                                        <form method="post" id="upload_formRight' . $vehicle->id . '" name="upload_formRight' . $vehicle->id . '" enctype="multipart/form-data" onsubmit="uploadPictureForm(\'upload_formRight\'+' . $vehicle->id . '+\',Right\'">
                                          <input type="hidden" name="_token" value="' . $request->_token . '">
                                            <input type="hidden" name="vSalId" value="' . $vehicle->id . '" />
                                            <div class="alert" id="messageRight' . $vehicle->id . '" style="display: none"></div>
                                            <center>
                                                <div style="width:100px !important;padding: 0" class="inside" id="fileNameRight' . $vehicle->id . '"></div>
                                                <div class="inputWrapper">';
            if ($vehicle->picture_right == null) {
                $returnData .= '<span id="uploaded_imageRight' . $vehicle->id . '"></span>';
            } else {
                $returnData .= '<span id="uploaded_imageRight' . $vehicle->id . '"><a href="' . $vehicle->picture_right . '" target="_blank"><img src="' . $vehicle->picture_right . '" class="img-thumbnail" width="300" /></a></span>';
            }
            if ($vehicle->PAID == 'NO') {
                $returnData .= '<center>
                                                            <img src="images/mas.png" alt="Girl in a jacket" style="width:20px;height:20px;margin-bottom: -100px;">
                                                        </center>
                                                        <input class="fileInput" type="file" name="select_fileRight" onchange="fileNameFunction(\'' . $vehicle->id . '\',\'Right\')" id="select_fileRight' . $vehicle->id . '" />';
            }
            $returnData .= '
                                                </div>
                                            </center>
                                            <center>
                                                <!--<input type="submit" name="upload" id="uploadRight' . $vehicle->id . '" class="btn btn-primary visible" value="Subir Foto">-->';
            if ($vehicle->PAID == 'NO') {
                if ($vehicle->picture_right == null) {
                    $returnData .= '<button type="submit" name="upload" id="uploadRight' . $vehicle->id . '" class="btn btn-primary visible" onclick="uploadPictureForm(\'upload_formRight\'+' . $vehicle->id . ',\'Right\')"><span class="glyphicon glyphicon-upload"></span>&ensp;Subir Foto</button>';
                    $returnData .= '<a class="hidden" id="deletePictureRight' . $vehicle->id . '" href="#" onclick="deletePictureForm(\'' . $vehicle->id . '\',\'Right\')"><img src="images/menos.png" style="width:20px;height:20px"></a>';
                } else {
                    $returnData .= '<button type="submit" name="upload" id="uploadRight' . $vehicle->id . '" class="btn btn-primary hidden" onclick="uploadPictureForm(\'upload_formRight\'+' . $vehicle->id . ',\'Right\')"><span class="glyphicon glyphicon-upload"></span>&ensp;Subir Foto</button>';
                    $returnData .= '<a class="visible" id="deletePictureRight' . $vehicle->id . '" href="#" onclick="deletePictureForm(\'' . $vehicle->id . '\',\'Right\')"><img src="images/menos.png" style="width:20px;height:20px"></a>';
                }
            }
            $returnData .= '  
                    
                                            </center>
                                        </form>

                                    </td>';
            $returnData .= '<td>
                                        <form method="post" id="upload_formBack' . $vehicle->id . '" name="upload_formBack' . $vehicle->id . '" enctype="multipart/form-data" onsubmit="uploadPictureForm(\'upload_formBack\'+' . $vehicle->id . '+\',Back\'">
                                          <input type="hidden" name="_token" value="' . $request->_token . '">
                                            <input type="hidden" name="vSalId" value="' . $vehicle->id . '" />
                                            <div class="alert" id="messageBack' . $vehicle->id . '" style="display: none"></div>
                                            <center>
                                                <div style="width:100px !important;padding: 0" class="inside" id="fileNameBack' . $vehicle->id . '"></div>
                                                <div class="inputWrapper">';
            if ($vehicle->picture_back == null) {
                $returnData .= '<span id="uploaded_imageBack' . $vehicle->id . '"></span>';
            } else {
                $returnData .= '<span id="uploaded_imageBack' . $vehicle->id . '"><a href="' . $vehicle->picture_back . '" target="_blank"><img src="' . $vehicle->picture_back . '" class="img-thumbnail" width="300" /></a></span>';
            }
            if ($vehicle->PAID == 'NO') {
                $returnData .= '<center>
                                                            <img src="images/mas.png" alt="Girl in a jacket" style="width:20px;height:20px;margin-bottom: -100px;">
                                                        </center>
                                                        <input class="fileInput" type="file" name="select_fileBack" onchange="fileNameFunction(\'' . $vehicle->id . '\',\'Back\')" id="select_fileBack' . $vehicle->id . '" />';
            }
            $returnData .= '
                                                </div>
                                            </center>
                                            <center>
                                                <!--<input type="submit" name="upload" id="uploadBack' . $vehicle->id . '" class="btn btn-primary visible" value="Subir Foto">-->';
            if ($vehicle->PAID == 'NO') {
                if ($vehicle->picture_back == null) {
                    $returnData .= '<button type="submit" name="upload" id="uploadBack' . $vehicle->id . '" class="btn btn-primary visible" onclick="uploadPictureForm(\'upload_formBack\'+' . $vehicle->id . ',\'Back\')"><span class="glyphicon glyphicon-upload"></span>&ensp;Subir Foto</button>';
                    $returnData .= '<a class="hidden" id="deletePictureBack' . $vehicle->id . '" href="#" onclick="deletePictureForm(\'' . $vehicle->id . '\',\'Back\')"><img src="images/menos.png" style="width:20px;height:20px"></a>';
                } else {
                    $returnData .= '<button type="submit" name="upload" id="uploadBack' . $vehicle->id . '" class="btn btn-primary hidden" onclick="uploadPictureForm(\'upload_formBack\'+' . $vehicle->id . ',\'Back\')"><span class="glyphicon glyphicon-upload"></span>&ensp;Subir Foto</button>';
                    $returnData .= '<a class="visible" id="deletePictureBack' . $vehicle->id . '" href="#" onclick="deletePictureForm(\'' . $vehicle->id . '\',\'Back\')"><img src="images/menos.png" style="width:20px;height:20px"></a>';
                }
            }
            $returnData .= '  
                    
                                            </center>
                                        </form>

                                    </td></tr>';
        }
        return $returnData;
    }

    function modalResume(request $request) {
        //Obtain Sale Data
        $sale = \App\sales::find($request['data']['id']);
        //Obtain Customer Data
        $customer = \App\customers::find($sale->customer_id);

        $returnTable = '';

        //Obtain Sale Status
        $status = \App\status::find($sale->status_id);

        //Customer Resume Table
        $returnTable .= '<h4>Datos del Cliente:</h4>
                        <table id="customerResumeTable" class="table table-bordered">
                                    <tbody>
                                        <tr style="background-color: #183c6b;color: white;">
                                            <th>Cliente</th>
                                            <th>Documento</th>
                                            <th>Correo</th>
                                        </tr>
                                        <tr>
                                            <td align="center">' . $customer->first_name . ' ' . $customer->last_name . '</td>
                                            <td align="center">' . $customer->document . '</td>
                                            <td align="center">' . $sale->cus_email . '</td>
                                        </tr>
                                        <tr style="background-color: #183c6b;color: white;">
                                            <th>Telefono</th>
                                            <th>Celular</th>
                                            <th>Direccion</th>
                                        </tr>
                                        <tr>
                                            <td align="center">' . $sale->cus_phone . '</td>
                                            <td align="center">' . $sale->cus_mobile_phone . '</td>
                                            <td align="center">' . $sale->cus_address . '</td>
                                        </tr>
                                    </tbody>
                            </table>';
        //Policy Resume Table
        $returnTable .= '<h4>Datos de la Venta:</h4>
            <table id="saleResumeTable" class="table table-bordered">
                                    <tbody>
                                        <tr style="background-color: #183c6b;color: white;">
                                            <th>Poliza</th>
                                            <th>Cotizacion</th>
                                            <th>Fecha de Emisión</th>
                                            <th>Fecha Inicio Cobertura</th>
                                            <th>Fecha Fin Cobertura</th>
                                            <th>Estado</th>
                                        </tr>
                                        <tr>
                                            <td align="center">' . $sale->poliza . '</td>
                                            <td align="center">' . $sale->id . '</td>
                                            <td align="center">' . date_format(date_create($sale->emission_date),'d-m-Y') . '</td>
                                            <td align="center">' . date_format(date_create($sale->begin_date),'d-m-Y') . '</td>
                                            <td align="center">' . date_format(date_create($sale->end_date),'d-m-Y') . '</td>
                                            <td align="center">' . $status->name . '</td>
                                        </tr>
                                    </tbody>
                                </table>';
        
        //Obtain Product Segment
        $product = \App\products::selectRaw('products.ramoid')
                ->join('products_channel as pbc', 'pbc.product_id', '=', 'products.id')
                ->join('sales', 'sales.pbc_id', '=', 'pbc.id')
                ->where('sales.id', '=', $sale->id)
                ->get();
        
        //Obtain URLS
        $urls = \App\sales::selectRaw('sales.url_condiciones, sales.url_endoso, sales.url_factura, sales.url_caratula, sales.url_viamatica, vin.url')
                            ->join('vinculation_form as vin','vin.sales_id','=','sales.id')
                            ->where('sales.id','=',$sale->id)
                            ->get();
        //Url Condiciones
        if($urls[0]->url_condiciones == null){ $urlCondiciones = ''; }else{ $urlCondiciones = '<a href="'.$urls[0]->url_condiciones.'" target="_blank" data-toggle="tooltip" title="ver PDF"> <img src="/images/pdf.png" height="20" width="20" style="margin-left:-10px"> </a>'; }
        //Url Endoso
        if($urls[0]->url_endoso == null){ $urlEndoso = ''; }else{ $urlEndoso = '<a href="'.$urls[0]->url_endoso.'" target="_blank" data-toggle="tooltip" title="ver PDF"> <img src="/images/pdf.png" height="30" width="30" style="padding:5px"> </a>'; }
        //Url Factura
        if($urls[0]->url_factura == null){ $urlFactura = ''; }else{ $urlFactura = '<a href="'.$urls[0]->url_factura.'" target="_blank" data-toggle="tooltip" title="ver PDF"> <img src="/images/pdf.png" height="20" width="20" style="margin-left:-10px"> </a>'; }
        //Url Caratula
        if($urls[0]->url_caratula == null){ $urlCaratula = ''; }else{ $urlCaratula = '<a href="'.$urls[0]->url_caratula.'" target="_blank" data-toggle="tooltip" title="ver PDF"> <img src="/images/pdf.png" height="20" width="20" style="margin-left:-10px"> </a>'; }
        //Url Viamatica Sales
        if($urls[0]->url_viamatica == null){ $urlViamatica = ''; }else{ $urlViamatica = '<a href="'.$urls[0]->url_viamatica.'" target="_blank" data-toggle="tooltip" title="ver PDF"> <img src="/images/pdf.png" height="20" width="20" style="margin-left:-10px"> </a>'; }
        //Url Viamatica Vinculation
        if($urls[0]->url == null){ $urlVinculation = ''; }else{ $urlVinculation = '<a href="'.$urls[0]->url.'" target="_blank" data-toggle="tooltip" title="ver PDF"> <img src="/images/pdf.png" height="20" width="20" style="margin-left:-10px"> </a><br><a href="'. asset('').'vinculation/create?document='.\Crypt::encrypt($customer->document).'&sales='.\Crypt::encrypt($sale->id).'&broker=1" target="_blank">Ver formulario</a>'; }
        
        // URL TABLE
        if ($product[0]->ramoid == 2 || $product[0]->ramoid == 1) { // VIDA
            $returnTable .= '<h4>Documentos:</h4>
            <table id="saleResumeTable" class="table table-bordered">
                                    <tbody>
                                        <tr style="background-color: #183c6b;color: white;">
                                            <th>PDF Vinculacion</th>
                                            <th>PDF Vida</th>
                                            <th>Caratula</th>
                                            <th>Cond. Particulares</th>
                                            <th>Factura</th>
                                            <th>Endoso</th>
                                        </tr>
                                        <tr>
                                            <td align="center">'.$urlVinculation.'</td>
                                            <td align="center">'.$urlViamatica.'</td>
                                            <td align="center">'.$urlCaratula.'</td>
                                            <td align="center">'.$urlCondiciones.'</td>
                                            <td align="center">'.$urlFactura.'</td>
                                            <td align="center">'.$urlEndoso.'</td>
                                        </tr>
                                    </tbody>
                                </table>';
        }else{
            $returnTable .= '<h4>Documentos:</h4>
            <table id="saleResumeTable" class="table table-bordered">
                                    <tbody>
                                        <tr style="background-color: #183c6b;color: white;">
                                            <th>PDF Vinculacion</th>
                                            <th>Caratula</th>
                                            <th>Cond. Particulares</th>
                                            <th>Factura</th>
                                            <th>Endoso</th>
                                        </tr>
                                        <tr>
                                            <td align="center">'.$urlVinculation.'</td>
                                            <td align="center">'.$urlCaratula.'</td>
                                            <td align="center">'.$urlCondiciones.'</td>
                                            <td align="center">'.$urlFactura.'</td>
                                            <td align="center">'.$urlEndoso.'</td>
                                        </tr>
                                    </tbody>
                                </table>';
        }
        
        //Sale Resume Table
        $returnTable .= '<h4>Datos de la Poliza:</h4>
            <table class="table table-bordered table-condensed" style="width:30% !important">
                                    <tbody>
                                        <tr>
                                            <th style="background-color: #183c6b;color: white; text-align:right">Prima</th>
                                            <th>'. asCurrency($sale->prima_total) .'</th>
                                        </tr>
                                        <tr>
                                            <th style="background-color: #183c6b;color: white; text-align:right">Subtotal 12%</th>
                                            <th>'.$sale->subtotal_12.'</th>
                                        </tr>
                                        <tr>
                                            <th style="background-color: #183c6b;color: white; text-align:right">Subtotal 0%</th>
                                            <th>'.$sale->subtotal_0.'</th>
                                        </tr>
                                        <tr>
                                            <th style="background-color: #183c6b;color: white; text-align:right">Impuesto</th>
                                            <th>'.$sale->tax.'</th>
                                        </tr>
                                        <tr>
                                            <th style="background-color: #183c6b;color: white; text-align:right">Total</th>
                                            <th>'.$sale->total.'</th>
                                        </tr>
                                    </tbody>
                                </table>';

        
        //VIDA Y ACCIDENTES PERSONALES//
        if ($product[0]->ramoid == 4 || $product[0]->ramoid == 1 || $product[0]->ramoid == 2) {
            //Obtain Insured
            $beneficiarys = \App\beneficiary::selectRaw('beneficiary.*, rela.name')
                                            ->join('beneficiary_relationship as rela','rela.id','=','beneficiary.relationship_id')
                                            ->where('sales_id','=',$sale->id)->get();
            $returnTable .= '<h4>Datos de Beneficiarios:</h4>
                        <table id="customerResumeTable" class="table table-bordered">
                                    <tbody>
                                        <tr style="background-color: #183c6b;color: white;">
                                            <th>Primer Nombre</th>
                                            <th>Segundo Nombre</th>
                                            <th>Primer Apellido</th>
                                            <th>Segundo Apellido</th>
                                            <th>Porcentaje</th>
                                            <th>Relacion</th>
                                        </tr>';
            foreach($beneficiarys as $bene){
                $returnTable .= '<tr>
                                    <td align="center">' . $bene->first_name . '</td>
                                    <td align="center">' . $bene->second_name . '</td>
                                    <td align="center">' . $bene->last_name . '</td>
                                    <td align="center">' . $bene->second_last_name . '</td>
                                    <td align="center">' . $bene->porcentage . '</td>
                                    <td align="center">' . $bene->name . '</td>
                                </tr>';
            }
            $returnTable .= '</tbody>
                            </table>';
        } 
        // VEHICULOS //
        if($product[0]->ramoid == 7){
            //Obtain Vehicles Data
            $vehiclesQuery = 'select vehi.*, typ.name as "type", vsal.insured_value as "value" from vehicles vehi
                                left join vehicles_type typ on typ.id = vehi.vehicles_type_id
                                join vehicles_sales vsal on vsal.vehicule_id = vehi.id
                                where vsal.sales_id = ' . $sale->id;

            $vehicles = DB::select($vehiclesQuery);

            //Vehicules Resume Table
            $returnTable .= '<h4>Vehiculos:</h4>
                            <table id="vehicleTableResume" class="table table-bordered">
                                        <tbody>
                                            <tr style="background-color: #183c6b;color: white;">
                                                <th>Placa</th>
                                                <th>Marca</th>
                                                <th>Modelo</th>
                                                <th>Año</th>
                                                <th>Color</th>
                                                <th>Chasis</th>
                                                <th>Motor</th>
                                                <th>Uso</th>
                                                <th>V. Aseg.</th>
                                            </tr>';
            foreach ($vehicles as $vehicle) {
                //Obtain Vehicle Brand
                $brand = \App\vehiclesBrands::find($vehicle->brand_id);
                $returnTable .= '
                                            <tr>
                                                <td align="center">' . $vehicle->plate . '</td>
                                                <td align="center">' . $brand->name . '</td>
                                                <td align="center">' . $vehicle->model . '</td>
                                                <td align="center">' . $vehicle->year . '</td>
                                                <td align="center">' . $vehicle->color . '</td>
                                                <td align="center">' . $vehicle->chassis . '</td>
                                                <td align="center">' . $vehicle->matricula . '</td>
                                                <td align="center">' . $vehicle->type . '</td>
                                                <td align="center">' . asCurrency($vehicle->value) . '</td>
                                            </tr>
                                        ';
            }
            $returnTable .= '</tbody>
                        </table>';
            //Accesories Resume Table
            //Obtain Vehicles Data
            $vehiAcc = \App\vehiclesAccesories::selectRaw('vehicles_accesories.name, vehicles_accesories.value, vehicles.plate, vehicles.ramv')
                    ->join('vehicles_sales', 'vehicles_sales.id', '=', 'vehicles_accesories.vehicles_sales_id')
                    ->join('vehicles','vehicles.id','=','vehicles_sales.vehicule_id')
                    ->where('vehicles_sales.sales_id', '=', $sale->id)
                    ->get();
            $returnTable .= '<h4>Accesorios:</h4>
                            <table id="vehicleTableResume" class="table table-bordered">
                                        <tbody>
                                            <tr style="background-color: #183c6b;color: white;">
                                                <th>RAMV</th>
                                                <th>Plate</th>
                                                <th>Descripción</th>
                                                <th>Valor</th>
                                            </tr>';
            foreach ($vehiAcc as $acc) {
                //Obtain Vehicle Brand
                $brand = \App\vehiclesBrands::find($vehicle->brand_id);
                $returnTable .= '
                                            <tr>
                                                <td align="center">' . $acc->ramv . '</td>
                                                <td align="center">' . $acc->plate . '</td>
                                                <td align="center">' . $acc->name . '</td>
                                                <td align="center">' . $acc->value . '</td>
                                            </tr>
                                        ';
            }
            $returnTable .= '</tbody>
                                </table>';
        }
        
       // INCENDIO //
        if($product[0]->ramoid == 5 || $product[0]->ramoid == 40){
            //Obtain Properties Data
            $properties = \App\properties::selectRaw('properties.*, cit.name')
                                            ->join('cities as cit','cit.id','=','properties.city_id')
                                            ->where('properties.sales_id','=',$sale->id)
                                            ->get();

            //Properties Resume Table
            $returnTable .= '<h4>Propiedad:</h4>
                            <table id="vehicleTableResume" class="table table-bordered">
                                        <tbody>
                                            <tr style="background-color: #183c6b;color: white;">
                                                <th>Calle Principal</th>
                                                <th>Calle Secundaria</th>
                                                <th>Numero</th>
                                                <th>Oficina/Departamento</th>
                                                <th>Canton</th>
                                            </tr>';
            foreach ($properties as $pro) {
                $returnTable .= '
                                            <tr>
                                                <td align="center">' . $pro->main_street . '</td>
                                                <td align="center">' . $pro->secondary_street . '</td>
                                                <td align="center">' . $pro->number . '</td>
                                                <td align="center">' . $pro->office_department . '</td>
                                                <td align="center">' . $pro->name . '</td>
                                            </tr>
                                        ';
            }
            $returnTable .= '</tbody>
                        </table>';
            //Accesories Resume Table
            //Obtain Vehicles Data
            $rubros = \App\properties_rubros::selectRaw('DISTINCT(properties.id),rub.description, rub.cod, properties_rubros.value')
                    ->join('properties', 'properties.id','=','properties_rubros.property_id')
                    ->join('products_rubros as rub','rub.cod','=','properties_rubros.rubros_cod')
                    ->join('sales','sales.id','=','properties.sales_id')
                    ->where('sales.id','=',$sale->id)
                    ->get();
            $returnTable .= '<h4>Rubros:</h4>
                            <table id="vehicleTableResume" class="table table-bordered">
                                        <tbody>
                                            <tr style="background-color: #183c6b;color: white;">
                                                <th>Rubro</th>
                                                <th>Codigo</th>
                                                <th>Valor</th>
                                            </tr>';
            foreach ($rubros as $rub) {
                //Obtain Vehicle Brand
                $returnTable .= '
                                            <tr>
                                                <td align="center">' . $rub->description . '</td>
                                                <td align="center">' . $rub->cod . '</td>
                                                <td align="center">' . asCurrency($rub->value) . '</td>
                                            </tr>
                                        ';
            }
            $returnTable .= '</tbody>
                                </table>';
            
        }


        return $returnTable;
    }
    function modalResumeMassives(request $request) {
        //Obtain Sale Data
        $sale = \App\sales::find($request['data']['id']);
        $companyId=$sale->company_id;
        //Obtain Customer Data
        if($sale->customer_id != null){
            $customer = \App\customers::find($sale->customer_id);
        }else{
            $customer = \App\customerLegalRepresentative::find($sale->customer_legal_representative_id);  
        }

        //Vinculation
        $vinculation = \App\vinculation_form::where('sales_id','=',$request['data']['id'])->get();
        if($vinculation->isEmpty()){
            $address = '';
            $phone = '';
        }else{
            $address = $vinculation[0]->address_zone;
            $phone = $vinculation[0]->phone;
        }
        
        $returnTable = '';

        //Obtain Sale Status
        $status = \App\status::find($sale->status_id);

        //Customer Resume Table
        $returnTable .= '<h4>Datos del Cliente:</h4>
                        <table id="customerResumeTable" class="table table-bordered">
                                    <tbody>
                                        <tr style="background-color: #183c6b;color: white;">
                                            <th>Cliente</th>
                                            <th>Documento</th>
                                            <th>Correo</th>
                                        </tr>
                                        <tr>
                                            <td align="center">' . $customer->first_name . ' ' . $customer->last_name . '</td>
                                            <td align="center">' . $customer->document . '</td>
                                            <td align="center">' . $customer->email . '</td>
                                        </tr>
                                        <tr style="background-color: #183c6b;color: white;">
                                            <th>Teléfono</th>
                                            <th>Celular</th>
                                            <th>Dirección</th>
                                        </tr>
                                        <tr>
                                            <td align="center">' . $phone . '</td>
                                            <td align="center">' . $customer->mobile_phone . '</td>
                                            <td align="center">' . $address . '</td>
                                        </tr>
                                    </tbody>
                            </table>';
        //Policy Resume Table
        $returnTable .= '<h4>Resumen de la Vinculación:</h4>
            <table id="saleResumeTable" class="table table-bordered">
                                    <tbody>
                                        <tr style="background-color: #183c6b;color: white;">

                                            <th>Vinculación</th>
                                            <th>Fecha de Emisión</th>
                                            <th>Fecha Inicio Cobertura</th>
                                            <th>Fecha Fin Cobertura</th>
                                            <th>Estado</th>
                                        </tr>
                                        <tr>
                                            <td align="center">' . $sale->id . '</td>
                                            <td align="center">' . date_format(date_create($sale->emission_date),'d-m-Y') . '</td>
                                            <td align="center">' . date_format(date_create($sale->begin_date),'d-m-Y') . '</td>
                                            <td align="center">' . date_format(date_create($sale->end_date),'d-m-Y') . '</td>
                                            <td align="center">' . $status->name . '</td>
                                        </tr>
                                    </tbody>
                                </table>';
        
        //Obtain Product Segment
        $product = \App\products::selectRaw('products.ramoid,products.ramodes,products.productodes')
                ->join('products_channel as pbc', 'pbc.product_id', '=', 'products.id')
                ->join('sales', 'sales.pbc_id', '=', 'pbc.id')
                ->where('sales.id', '=', $sale->id)
                ->get();
        
        //Obtain URLS
        $urls = \App\sales::selectRaw('sales.url_condiciones, sales.url_endoso, sales.url_factura, sales.url_caratula, sales.url_viamatica, vin.url')
                            ->join('vinculation_form as vin','vin.sales_id','=','sales.id')
                            ->where('sales.id','=',$sale->id)
                            ->get();
        //Url Condiciones
        if($urls[0]->url_condiciones == null){ $urlCondiciones = ''; }else{ $urlCondiciones = '<a href="'.$urls[0]->url_condiciones.'" target="_blank" data-toggle="tooltip" title="ver PDF"> <img src="/images/pdf.png" height="20" width="20" style="margin-left:-10px"> </a>'; }
        //Url Endoso
        if($urls[0]->url_endoso == null){ $urlEndoso = ''; }else{ $urlEndoso = '<a href="'.$urls[0]->url_endoso.'" target="_blank" data-toggle="tooltip" title="ver PDF"> <img src="/images/pdf.png" height="30" width="30" style="padding:5px"> </a>'; }
        //Url Factura
        if($urls[0]->url_factura == null){ $urlFactura = ''; }else{ $urlFactura = '<a href="'.$urls[0]->url_factura.'" target="_blank" data-toggle="tooltip" title="ver PDF"> <img src="/images/pdf.png" height="20" width="20" style="margin-left:-10px"> </a>'; }
        //Url Caratula
        if($urls[0]->url_caratula == null){ $urlCaratula = ''; }else{ $urlCaratula = '<a href="'.$urls[0]->url_caratula.'" target="_blank" data-toggle="tooltip" title="ver PDF"> <img src="/images/pdf.png" height="20" width="20" style="margin-left:-10px"> </a>'; }
        //Url Viamatica Sales
        if($urls[0]->url_viamatica == null){ $urlViamatica = ''; }else{ $urlViamatica = '<a href="'.$urls[0]->url_viamatica.'" target="_blank" data-toggle="tooltip" title="ver PDF"> <img src="/images/pdf.png" height="20" width="20" style="margin-left:-10px"> </a>'; }
        //Url Viamatica Vinculation
        if($companyId!=null){
           $company= \App\companys::find($companyId);
           if($urls[0]->url == null){ $urlVinculation = ''; }else{ $urlVinculation = '<a href="'.$urls[0]->url.'" target="_blank" data-toggle="tooltip" title="ver PDF"> <img src="/images/pdf.png" height="20" width="20" style="margin-left:-10px"> </a><br><a href="'. asset('').'legalPersonVinculation/create?document='.\Crypt::encrypt($customer->document).'&sales='.\Crypt::encrypt($sale->id).'&companys='.\Crypt::encrypt($company->document).'&broker=1" target="_blank">Ver formulario</a>'; }
        
        }else{
             if($urls[0]->url == null){ $urlVinculation = ''; }else{ $urlVinculation = '<a href="'.$urls[0]->url.'" target="_blank" data-toggle="tooltip" title="ver PDF"> <img src="/images/pdf.png" height="20" width="20" style="margin-left:-10px"> </a><br><a href="'. asset('').'vinculation/create?document='.\Crypt::encrypt($customer->document).'&sales='.\Crypt::encrypt($sale->id).'&broker=1" target="_blank">Ver formulario</a>'; }
        }
        // URL TABLE
        if ($product[0]->ramoid == 2 || $product[0]->ramoid == 1 && 1 == 2) { // VIDA
            $returnTable .= '<h4>Documentos:</h4>
            <table id="saleResumeTable" class="table table-bordered">
                                    <tbody>
                                        <tr style="background-color: #183c6b;color: white;">
                                            <th>PDF Vinculación</th>
                                            <th>PDF Vida</th>
                                            <th>Caratula</th>
                                            <th>Cond. Particulares</th>
                                            <th>Factura</th>
                                            <th>Endoso</th>
                                        </tr>
                                        <tr>
                                            <td align="center">'.$urlVinculation.'</td>
                                            <td align="center">'.$urlViamatica.'</td>
                                            <td align="center">'.$urlCaratula.'</td>
                                            <td align="center">'.$urlCondiciones.'</td>
                                            <td align="center">'.$urlFactura.'</td>
                                            <td align="center">'.$urlEndoso.'</td>
                                        </tr>
                                    </tbody>
                                </table>';
        }else{
            $returnTable .= '<h4>Documentos:</h4>
            <table id="saleResumeTable" class="table table-bordered">
                                    <tbody>
                                        <tr style="background-color: #183c6b;color: white;">
                                            <th>PDF Vinculación</th>
                                        </tr>
                                        <tr>
                                            <td align="center">'.$urlVinculation.'</td>
                                        </tr>
                                    </tbody>
                                </table>';
        }
        
        //Sale Resume Table
        $returnTable .= '<h4>Datos de la Póliza:</h4>
        <table  class="table table-bordered">
                                    <tbody>
                                        <tr style="background-color: #183c6b;color: white;">
                                            <th>Prima Neta</th>
                                            <th>Valor Asegurado</th>
                                            <th>Ramo</th>
                                            <th>Producto</th>
                                        </tr>
                                        <tr>
                                            <th align="center">'.asCurrency($sale->net_premium) .'</th>
                                            <th align="center">'.asCurrency($sale->insured_value).'</th>
                                            <th align="center">'.$product[0]->ramodes.'</th>
                                            <th align="center">'.$product[0]->productodes.'</th>
                                        </tr>
                                    </tbody>
                                </table>';

        return $returnTable;
    }

    function massiveStore(request $request) {
        //Data
        $plate = $request['data']['TableData'][0]['plate'];
        $brand = $request['data']['TableData'][0]['brand'];
        $model = $request['data']['TableData'][0]['model'];
        $year = $request['data']['TableData'][0]['year'];
        $color = $request['data']['TableData'][0]['color'];
        $saleId = $request['data']['modalSalId'];

        // Validate Vehicle
        $vehicle = \App\vehicles::where('plate', '=', $plate)->get();
        if ($vehicle->isEmpty()) {
            //Obtain Brand
            $brandId = \App\vehiclesBrands::where('name', '=', $brand)->get();

            $vehicleNew = new \App\vehicles();
            $vehicleNew->plate = $plate;
            $vehicleNew->brand_id = $brandId[0]->id;
            $vehicleNew->model = $model;
            $vehicleNew->year = $year;
            $vehicleNew->color = $color;
            $vehicleNew->save();
            $vehicleId = $vehicleNew->id;
        } else {
            $vehicleId = $vehicle[0]->id;
        }

        // Save Vehicle Sales
        $vehiSales = new \App\vehicles_sales();
        $vehiSales->vehicule_id = $vehicleId;
        $vehiSales->sales_id = $saleId;
        $vehiSales->status_id = 1;
        $vehiSales->save();

        //Obtain Promotion Sale
        $promo = \App\sales::where('sales_id', '=', $saleId)->get();

        if (!$promo->isEmpty()) {
            //Store Vehicle Promotion Sale
            $vehiSalesPromo = new \App\vehicles_sales();
            $vehiSalesPromo->vehicule_id = $vehicleId;
            $vehiSalesPromo->sales_id = $promo[0]->id;
            $vehiSalesPromo->status_id = 1;
            $vehiSalesPromo->save();
        }

        //Activate Sales
        activeSaleDiners($saleId);

        \Session::flash('Success', 'El vehiculo fue agregado correctamente.');
        return 'ok';
    }

}
