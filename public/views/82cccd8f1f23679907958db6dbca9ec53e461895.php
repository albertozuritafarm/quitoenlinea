

<?php $__env->startSection('content'); ?>
<script src="<?php echo e(assets('js/registerCustom.js')); ?>"></script>
<script src="<?php echo e(assets('js/sales/createRemote.js')); ?>"></script>
<link href="<?php echo e(assets('css/sales/create.css')); ?>" rel="stylesheet" type="text/css"/>

<style>
    .form-group{
        margin-top:25px !important;
        margin-bottom: 25px !important;
    }
    .frmSearch {border: 1px solid #a8d4b1;background-color: #c6f7d0;margin: 2px 0px;padding:40px;border-radius:4px;}
    #customer-list{float:left;list-style:none;margin-top:-3px;padding:0;width:290px;position: absolute;z-index:9999;}
    #customer-list li{padding: 10px; background: #f0f0f0; border-bottom: #bbb9b9 1px solid;}
    #customer-list li:hover{background:#ece3d2;cursor: pointer;}
    #search-box{padding: 10px;border: #a8d4b1 1px solid;border-radius:4px;}
    .error{border:1px solid red}
        .modal-header {
    border-bottom: 0 none;
}

.modal-footer {
    border-top: 0 none;
}
</style>
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
<div class="container" style="margin-top:15px; font-size:14px !important">
    <!--<div class="row justify-content-center border" style="margin-left:20%;">-->
    <div class="col-md-10 border" style="padding: 15px;margin-left: 5%">
        <div class="row">
            <div class="col-xs-12 registerForm" style="margin:12px;">
                <center>
                    <h4 style="font-weight:bold">Registro de Nueva Venta</h4>
                    <!--<h5>Datos del Cliente.</h5>-->
                </center>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-3 wizard_inicial" style="padding-left:0px !important"><div id="firstStepWizard" class="wizard_activo registerForm">Producto</div></div>
            <div class="col-xs-12 col-md-2 wizard_inicial" style="padding-left:0px !important"><div id="secondStepWizard" class="wizard_inactivo registerForm">Cliente</div></div>
            <div class="col-xs-12 col-md-2 wizard_medio" style="padding-left:0px !important"><div id="thirdStepWizard" class="wizard_inactivo registerForm">Vehiculos</div></div>
            <div class="col-xs-12 col-md-2 wizard_medio" style="padding-left:0px !important"><div id="fourthStepWizard" class="wizard_inactivo registerForm">Resumen</div></div>
            <div class="col-xs-12 col-md-3 wizard_final" style="padding-right: 0px !important"><div id="fifthStepWizard" class="wizard_inactivo registerForm">Validacion</div></div>
        </div>
        <form name="salesForm" method="POST" action="/user" id="salesForm">
            
            <div id="firstStep" class="col-md-12" style="margin-top:10px">
                <div class="col-md-12 border" style="margin-top:10px">
                    <div id="productAlert" class="alert alert-danger hidden">
                        <strong>¡Alerta!</strong> Debe seleccionar un producto
                    </div>
                    <div id="tableDiv" class="col-md-12" >
                        <table id="tableUsers" class="table table-striped row-border table-responsive hover stripe" style="margin-left:-14px;width:100%">
                            <thead>
                                <tr>
                                    <th align="center">Seleccionar</th>
                                    <th align="center">Producto</th>
                                    <th align="center">Valor Producto</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td align="center"><input onclick="myButton_onclick()" type="checkbox" id="productCheckBox<?php echo e($product->id); ?>" name="productCheckBox" class="check" value="<?php echo e($product->id); ?>"></td>
                                    <td align="center"><a href="#" data-toggle="modal" data-target="#myModal<?php echo e($product->id); ?>"><?php echo e($product->name); ?></a></td>
                                    <td align="center">USD <?php echo e($product->total_price); ?></td>
                                </tr>
                                <!-- Modal -->
                            <div id="myModal<?php echo e($product->id); ?>" class="modal fade" role="dialog">
                                <div class="modal-dialog modal-lg">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                                                    <div class="modal-header">    
                                                                    </div>
                                        <div class="modal-body" >
                                            <label style="list-style-type:disc;" for="first_name">Nombre del Producto:</label>
                                            <h4><?php echo e($product->name); ?></h4>
                                            <label style="list-style-type:disc;" for="first_name">Segmento:</label>
                                            <h4><?php echo e($product->segment); ?></h4>
                                            <label style="list-style-type:disc;" for="first_name">Detalle:</label>
                                            <h4><?php echo e($product->detail); ?></h4>
                                            <label style="list-style-type:disc;" for="first_name">CONDICIONES Y BENEFICIOS ADICIONALES:</label>
                                            <h4><?php echo nl2br(e($product->conditions)); ?> </h4>
                                            <label style="list-style-type:disc;" for="first_name">CONDICIONES Y BENEFICIOS ADICIONALES:</label>
                                            <h4><?php echo nl2br(e($product->exclutions)); ?> </h4>

                                        </div>
                                                                    <div class="modal-footer">
                                                                    </div>
                                    </div>

                                </div>
                            </div>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div> 
                <div class="col-md-12" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                    <div class="col-md-1">
                        <a class="btn btn-default registerForm" align="right" href="/sales" style="margin-left: -30px;"> Cancelar </a>
                    </div>
                    <div class="col-md-1 col-md-offset-8">
                        <a class="btn btn-default registerForm" align="right" href="/sales" style="margin-left: 30px;background-color: #444;color:white"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                    </div>
                    <div class="col-md-1 col-md-offset-1">
                        <a id="firstStepBtnNext" class="btn btn-info registerForm" align="right" href="#" style="float:right;margin-right: -30px;padding: 5px"> Siguiente <span class="glyphicon glyphicon-step-forward"></span></a>
                    </div>
                </div>
            </div>
            <div id="secondStep" class="col-md-12 hidden" style="margin-top:20px">
                <div  class="col-md-12 border" style="margin-top:20px">
                    <div id="customerAlert" class="alert alert-danger hidden">
                        <center><strong>¡Alerta!</strong> Revise los campos </center>
                    </div>
                    <?php echo e(csrf_field()); ?>

                    <div class="col-md-6">
                        <button id="btnModalSecondStep" type="button" class="btn btn-info btn-lg hidden" data-toggle="modal" data-target="#secondStepModal">Open Modal</button>
                        <!-- Modal Contents -->
                        <!-- Modal -->
                        <div id="secondStepModal" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Modal Header</h4>
                                    </div>
                                    <div class="modal-body">
                                        <p>Some text in the modal.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="form-group form-inline">
                            <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Cédula</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                            <!--<input type="text" class="form-control registerForm" name="document" id="document" placeholder="Cédula" value="<?php echo e(old('document')); ?>" required="required">-->
                            <div class="form-inline">
                               <input autocomplete="off" type="text" class="form-control registerForm" name="document" id="document" placeholder="Cédula" required="required"tabindex="1" style="width:90%">
                                <button type="button" class="btn btn-info" id="documentBtn" onclick="documentBtn()"><span class="glyphicon glyphicon-search"></span></button>
                                <div id="suggesstion-box"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="first_name"> Nombre(s)</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                            <input type="text" class="form-control registerForm" name="first_name" id="first_name" placeholder="Nombre" value="<?php echo e(old('first_name')); ?>" required="required" tabindex="3" disabled="disabled">
                        </div>
                        <div class="form-group">
                            <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="city"> Celular</label> <span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span><label class="own"><span class="glyphicon glyphicon-info-sign" style="color:#0099ff"><span class="own1" style="float:left">La contraseña debe tener: <br> 1) Un Numero <br> 2) Una Letra <br> 3) Un caracter Especial <br> 4) Debe tener al menos 7 caracteres</p></span></span></label>
                            <input type="text" class="form-control registerForm" name="mobile_phone" id="mobile_phone" placeholder="Nombre" value="<?php echo e(old('mobile_phone')); ?>" required tabindex="5">
                        </div>
                        <div class="form-group">
                            <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="city"> Direccion</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                            <input type="text" class="form-control registerForm" name="address" id="address" placeholder="Nombre" required tabindex="7">
                        </div>

                        <div class="form-group">
                            <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="country"> Pais</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                            <select name="country" id="country" class="form-control registerForm" required tabindex="9">
                                <option selected="true" disabled="disabled" value="0">--Escoja Una---</option>
                                <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($country->id); ?>"><?php echo e($country->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="city"> Ciudad</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                            <select name="city" class="form-control registerForm" id="city" required tabindex="11">
                                <option id="citySelect" selected="true" disabled="disabled" value="0">--Escoja Una---</option>
                            </select>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document_id"> Tipo Documento</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                            <select id="document_id" name="document_id" class="form-control registerForm" value="<?php echo e(old('document_id')); ?>" required tabindex="2" disabled="disabled">
                                <option selected="true" value="0">--Escoja Una---</option>
                                <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($document->id); ?>"><?php echo e($document->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="last_name"> Apellido(s)</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                            <input type="text" class="form-control registerForm" name="last_name" id="last_name" placeholder="Apellido" value="<?php echo e(old('last_name')); ?>" required tabindex="4" disabled="disabled">
                        </div>
                        <div class="form-group">
                            <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Teléfono</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span><label class="own"><span class="glyphicon glyphicon-info-sign" style="color:#0099ff"><span class="own1" style="float:left">La contraseña debe tener: <br> 1) Un Numero <br> 2) Una Letra <br> 3) Un caracter Especial <br> 4) Debe tener al menos 7 caracteres</p></span></span></label>
                            <input type="text" class="form-control registerForm" name="phone" id="phone" placeholder="Cédula" value="<?php echo e(old('phone')); ?>" required tabindex="6">
                        </div>

                        <div class="form-group">
                            <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="correo"> Correo</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                            <input type="email" class="form-control registerForm" name="email" id="email" placeholder="Correo" value="<?php echo e(old('email')); ?>" required tabindex="8">
                            <p id="emailError" style="color:red;font-weight: bold"></p>    
<!--<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>-->
                            <?php if($errors->any()): ?>
                            <span style="color:red;font-weight:bold"><?php echo e($errors->first()); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="province"> Canton</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                            <select name="province" class="form-control registerForm" id="province" required tabindex="10">
                                <option id="provinceSelect" selected="true" disabled="disabled" value="0">--Escoja Una---</option>
                            </select>
                        </div>

                    </div>
                </div>
                <!--                <div class="col-md-12" style="margin-top:5px;padding-top:15px;">
                                    <a id="secondStepBtnBack" class="btn btn-default registerForm" align="left" href="#" style="margin-left: -15px"> Anterior </a>
                                    <a id="secondStepBtnNext" class="btn btn-info registerForm" align="right" href="#" style="float:right;margin-right: -15px;padding: 5px"> Siguiente </a>
                                </div>-->
                <div class="col-md-12" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                    <div class="col-md-1">
                        <a class="btn btn-default registerForm" align="right" href="/sales" style="margin-left: -30px;"> Cancelar </a>
                    </div>
                    <div class="col-md-1 col-md-offset-8">
                        <a id="secondStepBtnBack" class="btn btn-default registerForm" align="right" href="#" style="margin-left: 25px;background-color: #444;color:white"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                    </div>
                    <div class="col-md-1 col-md-offset-1">
                        <a id="secondStepBtnNext" class="btn btn-info registerForm" align="right" href="#" style="float:right;margin-right: -25px;padding: 5px"> Siguiente <span class="glyphicon glyphicon-step-forward"></span></a>
                    </div>
                </div>
            </div>
            <div id="thirdStep" class="col-md-12 hidden" style="margin-top:20px">
                <div class="col-md-12 border">
                    <div id="thirdStepAlert" class="alert alert-danger hidden">
                        <strong>¡Alerta!</strong> Debe ingresar un vehiculo.
                    </div>
                    <div id="plateAlert" class="alert alert-danger hidden">
                        <strong>¡Alerta!</strong> El vehiculo ya posee una venta individual.
                    </div>
                    <div id="plateAlert2" class="alert alert-danger hidden">
                        <strong>¡Alerta!</strong> Debe ingresar una placa valida.
                    </div>
                    <div id="plateAlert3" class="alert alert-danger hidden">
                        <center>Ingrese una placa valida.</center>
                    </div>
                    <div id="plateAlert4" class="alert alert-danger hidden">
                        <center><strong>Por favor ingrese una placa.</strong></center>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="last_name"> Placa</label> <label class="own"><span class="glyphicon glyphicon-info-sign" style="color:#0099ff"><span class="own1" style="float:left">* La placa debe tener 7 Caracteres: <br> -PCQ1111 <br> Nota: Si la placa solo tiene 6 caracteres debe agregar un cero de la siguiente manera: <br> -Placa: PCQ0111</p></span></span></label>
                            <div class="form-inline">
                                <input type="text" class="form-control registerForm" name="plate" id="plate" tabindex=1 placeholder="Placa" value="<?php echo e(old('plate')); ?>" maxlength="7" required style="width:90%">
                                <button type="button" class="btn btn-info" id="plateBtn"><span class="glyphicon glyphicon-search"></span></button>
                            </div>
                        </div>
                        <div class="form-group">
                            <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="last_name"> Modelo</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                            <input type="text" class="form-control registerForm" name="model" id="model" tabindex=3 placeholder="Modelo" value="<?php echo e(old('model')); ?>" required disabled="disabled">
                        </div>
                        <div class="form-group">
                            <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="last_name"> Color</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                            <input type="text" class="form-control registerForm" name="color" id="color" tabindex=5 placeholder="Color" value="<?php echo e(old('color')); ?>" required disabled="disabled">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="province"> Marca</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                            <select name="brand" class="form-control registerForm" id="brand" tabindex=2 required disabled="disabled">
                                <option id="brandSelect" selected="true" disabled="disabled" value="0">--Escoja Una---</option>
                                <?php $__currentLoopData = $vehiclesBrands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicleBrand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option id="brandSelect" value="<?php echo e($vehicleBrand->name); ?>"><?php echo e($vehicleBrand->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="last_name"> Año</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                            <input type="number" class="form-control registerForm yearVehicle" name="year" id="year" tabindex=4 placeholder="Año" value="<?php echo e(old('year')); ?>" max="2020" min="1" required disabled="disabled">
                            <p id="yearVehicleError" style="color:red;font-weight: bold"></p>
                        </div>
                        <div class="form-group">
                            <a id="btnVehicles" class="btn btn-success registerForm" align="right" href="#" style="float:right;margin-right: 0px;padding: 5px;margin-top: 25px;width:100px"><span class="glyphicon glyphicon-plus"></span>Agregar </a>
                        </div>

                    </div>
                    <div class="col-md-10 col-md-offset-1">
                        <table id="vehiclesTable" class="table table-striped table-bordered" style="border-collapse: separate !important">
                            <thead>
                                <tr>
                                    <!--<th>N°</th>-->
                                    <th style="background-color:#b3b0b0">Placa</th>
                                    <th style="background-color:#b3b0b0">Marca</th>
                                    <th style="background-color:#b3b0b0">Modelo</th>
                                    <th style="background-color:#b3b0b0">Año</th>
                                    <th style="background-color:#b3b0b0">Color</th>
                                    <th style="background-color:#b3b0b0">Quitar</th>
                                </tr>
                            </thead>
                            <tbody id="vehiclesBodyTable">
                            </tbody>
                        </table>
                    </div>
                    <!--                <div class="col-md-12" style="margin-top:5px;padding-top:15px;">
                                        <a id="thirdStepBtnBack" class="btn btn-default registerForm" align="left" href="#" style="margin-left: -15px"> Anterior </a>
                                        <a id="thirdStepBtnNext" class="btn btn-info registerForm" align="right" href="#" style="float:right;margin-right: -15px;padding: 5px"> Siguiente </a>
                                    </div>-->
                </div>
                <div class="col-md-12" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                    <div class="col-md-1">
                        <a class="btn btn-default registerForm" align="right" href="/sales" style="margin-left: -30px;"> Cancelar </a>
                    </div>
                    <div class="col-md-1 col-md-offset-8">
                        <a id="thirdStepBtnBack" class="btn btn-default registerForm" align="right" href="#" style="margin-left: 25px;background-color: #444;color:white"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                    </div>
                    <div class="col-md-1 col-md-offset-1">
                        <a id="thirdStepBtnNext" class="btn btn-info registerForm" align="right" href="#" style="float:right;margin-right: -25px;padding: 5px"> Siguiente <span class="glyphicon glyphicon-step-forward"></span></a>
                    </div>
                </div>
            </div>
            <div id="fourthStep" class="col-md-12 hidden" style="margin-top:20px">
                <div class="col-md-12 border">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="registerForm" for="documentResume"> Identificación</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                            <input type="text" class="form-control registerForm" name="documentResume" id="documentResume" placeholder="Placa" value="<?php echo e(old('documentResume')); ?>" disabled="disabled">
                        </div>
                        <div class="form-group">
                            <label class="registerForm" for="mobile_phoneResume"> Teléfono Movil</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                            <input type="text" class="form-control registerForm" name="mobile_phoneResume" id="mobile_phoneResume" placeholder="Modelo" value="<?php echo e(old('model')); ?>" disabled="disabled">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="registerForm" for="customerResume"> Cliente</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                            <input type="text" class="form-control registerForm" name="customerResume" id="customerResume" placeholder="Modelo" value="<?php echo e(old('model')); ?>" disabled="disabled">
                        </div>
                        <div class="form-group">
                            <label class="registerForm" for="emailResume"> Email</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                            <input type="email" class="form-control registerForm" name="emailResume" id="emailResume" placeholder="Año" value="<?php echo e(old('year')); ?>" disabled="disabled">
                        </div>                    
                    </div>
                    <div class="col-md-8 col-md-offset-2">
                        <table id="vehiclesTableResume" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style="background-color:#b3b0b0">Item</th>
                                    <th style="background-color:#b3b0b0">Vehiculo</th>
                                    <th style="background-color:#b3b0b0">Precio Unitario</th>
                                    <th style="background-color:#b3b0b0">Descuento</th>
                                    <th style="background-color:#b3b0b0;width:100px;">Precio Total</th>
                                </tr>
                            </thead>
                            <tbody id="vehiclesTableBodyResume">
                            </tbody>
                        </table>
                    </div>
                    <div id="taxTableResume" class="col-md-4 col-md-offset-6">
                        <table class="table table-striped table-bordered">
                            <tbody id="taxTableBodyResume">
                                <tr>
                                    <td style="background-color:#b3b0b0">
                                        Subtotal 12
                                    </td>
                                    <td align="right" id="sub12" style="width:100px">
                                        0
                                    </td>
                                </tr>
                                <tr>
                                    <td style="background-color:#b3b0b0">
                                        Subtota 0
                                    </td>
                                    <td align="right" id="sub0">
                                        0
                                    </td>
                                </tr>
                                <tr>
                                    <td style="background-color:#b3b0b0">
                                        Otros Descuentos
                                    </td>
                                    <td align="right" id="otherDiscount">
                                        0
                                    </td>
                                </tr>
                                <tr>
                                    <td style="background-color:#b3b0b0">
                                        IVA 12%
                                    </td>
                                    <td align="right" id="tax12">
                                        0
                                    </td>
                                </tr>
                                <tr>
                                    <td style="background-color:#b3b0b0">
                                        Total
                                    </td>
                                    <td align="right" id="total">
                                        0
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="benefitsTable" class="col-md-4 col-md-offset-4">
                        <table class="table table-striped table-bordered">
                            <tbody>
                                <tr id="benefitsTableBody">
                                    <td align="center" style="background-color:#b3b0b0;font-weight: bold">
                                        Beneficios Adicionales
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!--                <div class="col-md-12" style="margin-top:5px;padding-top:15px;">
                                        <a id="thirdStepBtnBack" class="btn btn-default registerForm" align="left" href="#" style="margin-left: -15px"> Anterior </a>
                                        <a id="thirdStepBtnNext" class="btn btn-info registerForm" align="right" href="#" style="float:right;margin-right: -15px;padding: 5px"> Siguiente </a>
                                    </div>-->
                </div>
                <div class="col-md-12" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                    <div class="col-md-1">
                        <a class="btn btn-default registerForm" align="right" href="/sales" style="margin-left: -30px;"> Cancelar </a>
                    </div>
                    <div class="col-md-1 col-md-offset-8">
                        <a id="fourthStepBtnBack" class="btn btn-default registerForm" align="right" href="#" style="margin-left: 25px;background-color: #444;color:white"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                    </div>
                    <div class="col-md-1 col-md-offset-1">
                        <a id="fourthStepBtnNext" class="btn btn-info registerForm" align="right" href="#" style="float:right;margin-right: -25px;padding: 5px"> Siguiente <span class="glyphicon glyphicon-step-forward"></span></a>
                    </div>
                </div>
                <div class="col-md-12" style="margin-top:5px;padding-top:15px;">
                    <a class="btn btn-default registerForm hidden" align="left" href="/user" style="margin-left: -15px"> Cancelar </a>
                    <input type="submit" style="float:right;margin-right: -15px;padding: 5px" class="btn btn-info registerForm hidden" align="right" value="Guardar">

                </div>
            </div>
            <div id="fifthStep" class="col-md-12 hidden" style="margin-top:20px">
                <div class="col-md-12 border">
<!--                    <div class="col-md-6 col-md-offset-3">
                        <div class="form-group">
                            <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="last_name"> Codigo</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                            <input type="number" class="form-control registerForm" name="code" id="code" placeholder="Codigo" value="<?php echo e(old('code')); ?>" max="2020" min="1" required>
                        </div>
                    </div>
                    <div class="col-md-6 col-md-offset-3">
                        <div class="form-group" style="padding-bottom:5px">
                            <a id="resendCodeBtn" class="btn btn-success " align="right" href="#" style="float:right;margin-top: -25px"> Re-enviar Codigo</a>
                            <input id="fifthStepProductId" type="text" class="hidden" value="">
                            <input id="fifthStepValidationCode" type="text" class="hidden" value="1234">
                        </div>
                    </div>-->
                    <div id="resultMessage">
                    </div>
                    <span class="col-md-12">
                        <div id="validationCode">
                            <input type="hidden" name="salId" id="salId" value="">
                        </div>
                        <div class="form-group">
                            <label for="code">Ingrese el codigo</label>
                            <input type="text" class="form-control" name="code" id="code" placeholder="Ingrese el codigo"><br>
                            <button id="resendCodeBtn" type="submit" class="btn btn-success" style="float:right;margin-bottom: 10px" onclick="resendCode()">Reenviar Codigo</button>
                        </div>
                    </span>
                </div>
                <div class="col-md-12" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                    <div class="col-md-1">
                        <a class="btn btn-default registerForm" align="right" href="/sales" style="margin-left: -30px;"> Cancelar </a>
                    </div>
                    <div class="col-md-1 col-md-offset-8">
                        <!--<a id="fifthStepBtnBack" class="btn btn-default registerForm" align="right" href="#" style="margin-left: 25px;background-color: #444;color:white"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>-->
                    </div>
                    <div class="col-md-1 col-md-offset-1">
                        <a id="fifthStepBtnNext" class="btn btn-info registerForm" align="right" href="#" onclick="validateCode()" style="float:right;margin-right: -25px;padding: 5px;width:80px"> Validar <span class="glyphicon glyphicon-ok"></span></a>
                    </div>
                </div>
                </form>
                <!-- Trigger the modal with a button -->
                <button id="confirmModal" type="button" class="btn btn-info btn-lg hidden" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#myModal">Open Modal</button>
                
                <!-- Modal -->
                <div id="myModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Su registro a sido completado satisfactoriamente</h4>
                                </div>
                                <div class="modal-body">
                                    <center>
                                        <p id="modalText" style="font-weight: bold;font-size: 16px"></p><br>
                                        <p style="font-weight: bold;font-size: 16px">¿Desea ir a la pasarela de cobro?.</p><br>
                                    </center>
                                </div>
                                <div class="modal-footer">
                                    <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
                                    <a href="/sales" type="button" class="btn btn-default" style="float:left">NO</a>
                                    <form action="/payments/create" method="POST">
                                        <?php echo e(csrf_field()); ?>

                                        <input type="hidden" id="chargeId" name="chargeId" value="">
                                        <input type="submit" class="btn btn-info" style="float:right" value="SI">
                                    </form>
                                </div>
                            </div>

                    </div>
                </div>
            </div>

        
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views\sales\createRemote.blade.php ENDPATH**/ ?>