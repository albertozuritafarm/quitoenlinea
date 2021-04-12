

<?php $__env->startSection('content'); ?>
<script src="<?php echo e(assets('js/registerCustom.js')); ?>"></script>
<script src="<?php echo e(assets('js/massivesVinculation/create.js')); ?>"></script>

<link href="<?php echo e(assets('css/sales/create.css')); ?>" rel="stylesheet" type="text/css"/>


<style>
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
    .form-group {
        height:70px;
    }
/*    #vehiclesTable {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }*/
</style>
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
<div class="container-fluid" style="font-size:14px !important;padding-bottom: 15px;">
    <!--<div class="row justify-content-center border" style="margin-left:20%;">-->

    <div class="col-md-10 col-md-offset-1 border">
        <div class="row">
            <div class="col-xs-12 registerForm" style="margin:12px;">
                <center>
                    <h4 style="font-weight:bold">Registro de Nueva Vinculación</h4>
                </center>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-3 wizard_inicial"><div class="wizard_inactivo registerForm" style="margin-left:-10px"></div></div>
            <div class="col-xs-12 col-md-1 wizard_medio"><div class="wizard_inactivo registerForm"></div></div>
            <div class="col-xs-12 col-md-4 wizard_medio"><div class="wizard_activo registerForm">Vinculación</div></div>
            <div class="col-xs-12 col-md-1 wizard_medio"><div class="wizard_inactivo registerForm"></div></div>
            <div class="col-xs-12 col-md-3 wizard_final" ><div class="wizard_inactivo registerForm" style="margin-right:-10px;"></div></div>
        </div>
        <div class="col-md-12">
            <form name="salesForm" method="POST" action="/user" id="salesForm">
                <input type="hidden" name="sale_movement" id="sale_movement" value="<?php echo e($sale_movement); ?>">
                <div id="firstStep" class="col-md-12">
                    <div class="col-md-12" style="margin-top:5px;margin-bottom: 5px;padding-top:15px;">
                        <div class="row" style="float:left">
                            <a class="btn btn-default registerForm" align="right" href="<?php echo e(asset('/massivesVinculation')); ?>"> Cancelar </a>
                        </div>
                        <div class="row" style="float:right">
                            <button  class="btn btn-info registerForm" align="right" style="padding: 5px" onclick=BtnSave()> Guardar</button>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;">
                        <div class="wizard_activo registerForm titleDivBorderTop" onclick="fadeToggle('resumeDiv')">
                            <span class="titleLink">Datos del Cliente</span>
                            <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                        </div>
                        <div id="customerAlert" class="alert alert-danger registerForm titleDivBorderTop hidden" style="margin-top:5px;border-radius:0px !important;">
                            <center><strong>¡Alerta!</strong> Revise los campos </center>
                        </div>
                        <?php echo e(csrf_field()); ?>

                        <div class="col-md-12" style="margin-top: 25px;">
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
                            <form class="row g-3 needs-validation" novalidate >
                                <div class="form-row">
                                   <div class="form-group col-md-6">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Cédula</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <!--<input type="text" class="form-control registerForm" name="document" id="document" placeholder="Cédula" value="<?php echo e(old('document')); ?>" required="required">-->
                                        <div class="input-group">
                                            <input autocomplete="off" type="text" class="form-control registerForm" name="document" id="document" value="<?php echo e($customer->document); ?>" <?php if($disabled): ?> disabled="disabled" <?php else: ?> <?php endif; ?> placeholder="Cedula" required="required"tabindex="1" style="width:99%">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-info" onclick="documentBtn()" <?php if($disabled): ?> disabled="disabled" <?php else: ?> <?php endif; ?> ><span class="glyphicon glyphicon-search"></span></button>
                                            </span>
                                        </div>
                                        <label id="document_validation" class="hidden"style="color:#F31212;font-size: 12px;">Debe ingresar la Cedula</label>
                                        <div id="suggesstion-box"></div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document_id"> Tipo Documento</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <select id="document_id" name="document_id" class="form-control registerForm" value="<?php echo e(old('document_id')); ?>" required tabindex="2" disabled="disabled">
                                            <option selected="true" value="0">--Escoja Una---</option>
                                            <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option <?php if($document->id == $customer->document_id): ?> selected="true" <?php else: ?> <?php endif; ?> value="<?php echo e($document->id); ?>"><?php echo e($document->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <label id="document_id_validation" class="hidden"style="color:#F31212;font-size: 12px;">Debe ingresar un tipo de documento</label>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="first_name"> Primer Nombre</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" class="form-control registerForm" name="first_name" id="first_name" placeholder="Primer Nombre" value="<?php echo e($customer->first_name); ?>" required="required" required tabindex="3" disabled="disabled" maxlength="30">
                                        <label id="first_name_validation" class="hidden"style="color:#F31212;font-size: 12px;">Debe ingresar el Primer Nombre</label>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" style="list-style-type:disc;" for="second_name"> Segundo Nombre</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" class="form-control registerForm" name="second_name" id="second_name" placeholder="Segundo Nombre" value="<?php echo e($customer->second_name); ?>" required="required" required tabindex="4" disabled="disabled" maxlength="30"> 
                                        <label id="second_name_validation" class="hidden"style="color:#FFFFFF;font-size: 12px;">Segundo Nombre</label>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="last_name"> Primer Apellido</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" class="form-control registerForm" name="last_name" id="last_name" placeholder="Primer Apellido" value="<?php echo e($customer->last_name); ?>" required tabindex="5" disabled="disabled" maxlength="30">
                                        <label id="last_name_validation" class="hidden"style="color:#F31212;font-size: 12px;">Debe ingresar el Primer Apellido</label>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="second_last_name"> Segundo Apellido</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" class="form-control registerForm" name="second_last_name" id="second_last_name" placeholder="Segundo Apellido" value="<?php echo e($customer->second_last_name); ?>" required tabindex="6" disabled="disabled" maxlength="30">
                                        <label id="second_last_name_validation" class="hidden"style="color:#F31212;font-size: 12px;">Debe ingresar el Segundo Apellido</label>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <i class="fa fa-asterisk" aria-hidden="true" style="color: #1BBFDD;"></i>
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="mobile_phone"> Celular</label> <span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span><label class="own"><span class="glyphicon glyphicon-info-sign" style="color:#0099ff"><span class="own1" style="float:left">El celular debe tener 10 caracteres</span></span></label>
                                        <input type="text" class="form-control registerForm" name="mobile_phone" id="mobile_phone" placeholder="Celular" value="<?php echo e($customer->mobile_phone); ?>" required tabindex="7"  maxlength="10">
                                        <label id="mobile_phone_validation" class="hidden" style="color:#F31212;font-size: 12px;">Debe ingresar el Celular</label>
                                        <label id="mobile_phone_validation_length" class="hidden" style="color:#F31212;font-size: 12px;">El número de celular debe tener 10 dígitos</label>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="correo"> Correo</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="email" class="form-control registerForm" name="email" id="email" placeholder="Correo" value="<?php echo e($customer->email); ?>" required tabindex="8" maxlength="100">
                                        <label id="email_validation" class="hidden" style="color:#F31212;font-size: 12px;">Debe ingresar el Correo</label>
                                        <p id="emailError" style="color:red;font-weight: bold; display: none;"></p>    
                                        <!--<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>-->
                                        <?php if($errors->any()): ?>
                                        <span style="color:red;font-weight:bold"><?php echo e($errors->first()); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="branch"> Ramo</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <select name="branch" id="branch" class="form-control registerForm" required tabindex="9">
                                            <option selected="true" value="0">--Escoja Una---</option>
                                            <?php $__currentLoopData = $branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($c->ramodes); ?>"><?php echo e($c->ramodes); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                           <!--                                            <option  value="Vehiculo">Vehículo</option>
                                            <option  value="Vida">Vida</option>
                                            <option  value="Casa Habitacion">Casa Habitación</option>
                                            <option  value="Accidentes Personales">Accidentes Personales</option>-->
                                        </select>
                                        <label id="branch_validation" class="hidden"style="color:#F31212;font-size: 12px;">Debe seleccionar un Ramo</label>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="emissionType"> Tipo Emisión</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <select name="emissionType" id="emissionType" class="form-control registerForm" required tabindex="10">
                                            <option selected="true" value="0">--Escoja Una---</option>
                                            <?php $__currentLoopData = $emissionTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emissionType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($emissionType->id); ?>"><?php echo e($emissionType->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <label id="emissionType_validation" class="hidden"style="color:#F31212;font-size: 12px;">Debe seleccionar un Tipo de Emisión </label>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="product"> Producto</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <select name="product" id="product" class="form-control registerForm" required tabindex="10">
                                            <option selected="true" value="0">--Escoja Una---</option>
                                        </select>
                                        <label id="product_validation" class="hidden"style="color:#F31212;font-size: 12px;">Debe seleccionar un Producto </label>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="insuredValue"> Valor Asegurado</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text2" class="form-control registerForm" name="insuredValue" id="insuredValue" required tabindex="11" placeholder="Valor Asegurado" value="" required maxlength="15" onchange="currencyFormat(this.id)">
                                        <label id="insuredValue_validation" class="hidden"style="color:#F31212;font-size: 12px;">Debe ingresar el Valor Asegurado</label>
                                    </div>
                                    
                                    <div class="form-group col-md-6">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="netPremium">Prima Neta</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text2" class="form-control registerForm" name="netPremium" id="netPremium" required tabindex="12" placeholder="Prima Neta" value="" required maxlength="15" onchange="currencyFormat(this.id)">
                                        <label id="netPremium_validation" class="hidden"style="color:#F31212;font-size: 12px;">Debe ingresar el valor Prima Neta</label>
                                    </div>
                                </div>
                            </form>
                    </div>
                </div>
                <div class="col-md-12" style="padding-bottom:15px">
                                    <div class="row" style="float:left">
                                         <a class="btn btn-default registerForm" align="right" href="<?php echo e(asset('/massivesVinculation')); ?>" tabindex="16"> Cancelar </a>
                                    </div>
                                    <div class="row" style="float:right">
                                         <button  class="btn btn-info registerForm" align="right" style="padding: 5px" onclick=BtnSave() tabindex="17"> Guardar</button>
                                    </div>
                               </div>
                        </div>
            </form>
    </div>
</div>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\magnussucre\resources\views/massivesVinculation/create.blade.php ENDPATH**/ ?>