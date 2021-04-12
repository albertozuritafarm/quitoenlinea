

<?php $__env->startSection('content'); ?>
<script src="<?php echo e(assets('js/registerCustom.js')); ?>"></script>
<link href="<?php echo e(assets('css/sales/create.css')); ?>" rel="stylesheet" type="text/css"/>

<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
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
<div id="customerStep" class="container-fluid" style="font-size:14px !important;padding-bottom: 15px;">

    <script src="<?php echo e(assets('js/sales/customer.js')); ?>"></script>
    <!--<div class="row justify-content-center border" style="margin-left:20%;">-->

    <div class="col-md-8 col-md-offset-2 border">
        <div class="row">
            <div class="col-xs-12 registerForm" style="margin:12px;">
                <center>
                    <h4 style="font-weight:bold">Registro de Nueva Venta</h4>
                </center>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-3 wizard_inicial"><div style="margin-left:-10px" id="zeroStepWizard" class="wizard_inactivo registerForm">Ramo</div></div>
            <div class="col-xs-12 col-md-2 wizard_medio"><div id="firstStepWizard" class="wizard_activo registerForm">Cliente</div></div>
            <div class="col-xs-12 col-md-2 wizard_medio"><div id="secondStepWizard" class="wizard_inactivo registerForm">Asegurado</div></div>
            <div class="col-xs-12 col-md-2 wizard_medio"><div id="thirdStepWizard" class="wizard_inactivo registerForm">Producto</div></div>
            <div class="col-xs-12 col-md-3 wizard_final"><div style="margin-right:-10px;" id="fourthStepWizard" class="wizard_inactivo registerForm">Resumen</div></div>
        </div>
        <div class="col-md-12">
            <div class="col-md-12">
                <div class="col-md-12" style="margin-top:5px;margin-bottom: 5px;padding-top:15px;">
                    <div class="row" style="float:left">
                        <a class="btn btn-default registerForm" align="right" href="<?php echo e(asset('/sales')); ?>"> Cancelar </a>
                    </div>
                    <div class="row" style="float:right">
                        <a id="secondStepBtnBackTop" class="btn btn-default registerForm" align="right" <?php if($disabled == null): ?> href="<?php echo e(asset('/sales/product/select')); ?>" <?php else: ?> href="#" disabled="disabled" <?php endif; ?> style="background-color: #444;color:white"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                        <a id="secondStepBtnNextTop" class="btn btn-info registerForm" align="right" href="#" style="padding: 5px" onclick="firstStepBtnNext()"> Siguiente <span class="glyphicon glyphicon-step-forward"></span></a>
                    </div>
                </div>
                <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;">
                    <div class="wizard_activo registerForm titleDivBorderTop">
                        <span class="titleLink">Datos del Cliente</span>
                        <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                    </div>
                    <div id="customerAlert" class="alert alert-danger hidden registerForm titleDivBorderTop" style="margin-top:5px; border-radius: 0px !important">
                        <center><strong>¡Alerta!</strong> Revise los campos </center>
                    </div>
                    <?php echo e(csrf_field()); ?>

                    <form id="customerForm" method="POST" action="/user">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="sale_movement" id="sale_movement" value="<?php echo e($sale_movement); ?>">
                        <input type="hidden" name="sale_id" id="sale_id" value="<?php echo e($sale_id); ?>">
                        <input type="hidden" name="insurance_branch" id="insurance_branch" value="<?php echo e($insuranceBranch); ?>">
                        <input type="hidden" name="customer_id" id="customer_id" value="<?php echo e($customerId); ?>">
                        <input type="hidden" name="insured_id" id="insured_id" value="<?php echo e($insuredId); ?>">
                        <div class="col-md-6">
                            <div class="form-group form-inline">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Cédula</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <!--<input type="text" class="form-control registerForm" name="document" id="document" placeholder="Cédula" value="<?php echo e(old('document')); ?>" required="required">-->
                                <div class="form-inline">
                                    <input autocomplete="off" type="text" class="form-control registerForm" name="document" id="document" value="<?php echo e($customer->document); ?>" <?php if($disabled): ?> disabled="disabled" <?php else: ?> <?php endif; ?> placeholder="Cédula" required="required"tabindex="1" style="width:89%">
                                    <button type="button" class="btn btn-info" onclick="documentBtn();" <?php if($disabled): ?> disabled="disabled" <?php else: ?> <?php endif; ?> style="width:10%"><span class="glyphicon glyphicon-search"></span></button>
                                    <div id="suggesstion-box"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="first_name"> Nombre(s)</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" class="form-control registerForm" name="first_name" id="first_name" placeholder="Nombre" value="<?php echo e($customer->first_name); ?>" required="required" tabindex="3" disabled="disabled">
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="city"> Celular</label> <span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span><label class="own"><span class="glyphicon glyphicon-info-sign" style="color:#0099ff"><span class="own1" style="float:left">El celular debe tener 10 caracteres</span></span></label>
                                <input type="text" class="form-control registerForm" name="mobile_phone" id="mobile_phone" placeholder="Nombre" value="<?php echo e($customer->mobile_phone); ?>" required tabindex="5">
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="city"> Direccion</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" class="form-control registerForm" name="address" id="address" placeholder="Nombre" required tabindex="7" value="<?php echo e($customer->address); ?>">
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="country"> Pais</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <select name="country" id="country" class="form-control registerForm" required tabindex="9">
                                    <option selected="true" value="0">--Escoja Una---</option>
                                    <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option <?php if($country->id == $cusCountry->id): ?> selected="true" <?php else: ?> <?php endif; ?> value="<?php echo e($country->id); ?>"><?php echo e($country->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="city"> Ciudad</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <select name="city" class="form-control registerForm" id="city" required tabindex="11">
                                    <?php if($cusCityList): ?>
                                    <option id="citySelect" selected="true" value="<?php echo e($cusCity->id); ?>"><?php echo e($cusCity->name); ?></option>
                                    <?php else: ?>
                                    <option id="citySelect" selected="true" disabled="disabled" value="0">--Escoja Una---</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document_id"> Tipo Documento</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <select id="document_id" name="document_id" class="form-control registerForm" value="<?php echo e(old('document_id')); ?>" required tabindex="2" disabled="disabled">
                                    <option selected="true" value="0">--Escoja Una---</option>
                                    <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option <?php if($document->id == $customer->document_id): ?> selected="true" <?php else: ?> <?php endif; ?> value="<?php echo e($document->id); ?>"><?php echo e($document->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="last_name"> Apellido(s)</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" class="form-control registerForm" name="last_name" id="last_name" placeholder="Apellido" value="<?php echo e($customer->last_name); ?>" required tabindex="4" disabled="disabled">
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Teléfono</label>&ensp;<span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span><label class="own"><span class="glyphicon glyphicon-info-sign" style="color:#0099ff"><span class="own1" style="float:left">El telefono debe tener 9 caracteres</span></span></label>
                                <input type="text" class="form-control registerForm" name="phone" id="phone" placeholder="Cédula" value="<?php echo e($customer->phone); ?>" required tabindex="6">
                            </div>

                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="correo"> Correo</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="email" class="form-control registerForm" name="email" id="email" placeholder="Correo" value="<?php echo e($customer->email); ?>" required tabindex="8">
                                <p id="emailError" style="color:red;font-weight: bold"></p>    
                                <?php if($errors->any()): ?>
                                <span style="color:red;font-weight:bold"><?php echo e($errors->first()); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="province"> Canton</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <select name="province" class="form-control registerForm" id="province" required tabindex="10">
                                    <?php if($cusProvinceList): ?>
                                    <option id="provinceSelect" selected="true" value="<?php echo e($cusProvince->id); ?>"><?php echo e($cusProvince->name); ?></option>
                                    <?php else: ?>
                                    <option id="provinceSelect" selected="true" disabled="disabled" value="0">--Escoja Una---</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-12" style="padding-bottom:15px">
                    <div class="row" style="float:left">
                        <a class="btn btn-default registerForm" align="right" href="<?php echo e(asset('/sales')); ?>""> Cancelar </a>
                    </div>
                    <div class="row" style="float:right">
                        <a class="btn btn-default registerForm" align="right" <?php if($disabled == null): ?> href="<?php echo e(asset('/sales/product/select')); ?>" <?php else: ?> href="#" disabled="disabled" <?php endif; ?> style="background-color: #444;color:white"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                        <a class="btn btn-info registerForm" align="right" href="#" style="padding: 5px" onclick="firstStepBtnNext()"> Siguiente <span class="glyphicon glyphicon-step-forward"></span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views\sales\customer.blade.php ENDPATH**/ ?>