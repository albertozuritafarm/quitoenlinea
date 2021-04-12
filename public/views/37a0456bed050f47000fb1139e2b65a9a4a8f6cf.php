

<?php $__env->startSection('content'); ?>
<!--<div class="se-pre-con"></div>-->
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
<link href="<?php echo e(assets('css/sales/productSelect.css')); ?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo e(assets('css/sales/index.css')); ?>" rel="stylesheet" type="text/css"/>
<script src="<?php echo e(assets('js/sales/vinculationForm.js')); ?>"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://kit.fontawesome.com/fd8222181b.js" crossorigin="anonymous"></script>
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

<div id="customerStep" class="container-fluid" style="font-size:14px !important;padding-bottom: 15px;">

    <div class="col-md-8 col-md-offset-2 border">
        <div class="row">
            <div class="col-xs-12 registerForm" style="margin:12px;">
                <center>
                    <h4 style="font-weight:bold">Datos del Cliente</h4>
                </center>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-4 wizard_inicial"><div style="margin-left:-10px" class="wizard_inactivo registerForm"></div></div>
            <div class="col-xs-12 col-md-4 wizard_medio"><div class="wizard_activo registerForm">Cliente</div></div>
            <div class="col-xs-12 col-md-4 wizard_final"><div style="margin-right:-10px;" class="wizard_inactivo registerForm"></div></div>
        </div>
        <div class="col-md-12">
            <div class="col-md-12">
                <div class="col-md-12" style="margin-top:5px;padding-top:15px;">
                    <div class="row" style="float:left">
                        <a class="btn btn-default registerForm" align="right" href="<?php echo e(asset('/sales')); ?>""> Cancelar </a>
                    </div>
                    <div class="row" style="float:right">
                        <a class="btn btn-info registerForm" align="right" href="#" style="padding: 5px" onclick="validateVinculationForm()"> Guardar </a>
                    </div>
                </div>
                <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;">
                    <div class="wizard_activo registerForm titleDivBorderTop" onclick="fadeToggle('clientDiv')">
                        <a href="#" class="titleLink">Datos del Cliente</a>
                        <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                    </div>
                    <div id="clientDiv" class="col-md-12" style="padding-top: 25px;">
                        <input type="hidden" id="customerId" name="customerId" value="<?php echo e($customer->id); ?>">
                        <input type="hidden" id="saleId" name="saleId" value="<?php echo e($sales->id); ?>">
                        <div class="col-md-6">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Cédula</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input autocomplete="off" type="text" class="form-control registerForm" name="document" id="document" placeholder="Cédula" required="required"tabindex="1" readonly="readonly" value="<?php echo e($customer->document); ?>">                                    
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="first_name"> Nombre(s)</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" class="form-control registerForm" name="first_name" id="first_name" placeholder="Nombre" required="required" tabindex="3" disabled="disabled" value="<?php echo e($customer->first_name); ?>">
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="city"> Celular</label> <span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span><label class="own"><span class="glyphicon glyphicon-info-sign" style="color:#0099ff"><span class="own1" style="float:left">El celular debe tener 10 caracteres</span></span></label>
                                <input type="text" class="form-control registerForm" name="mobile_phone" id="mobile_phone" placeholder="Nombre" required tabindex="5" value="<?php echo e($customer->mobile_phone); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document_id"> Tipo Documento</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <select id="document_id" name="document_id" class="form-control registerForm" value="<?php echo e(old('document_id')); ?>" required tabindex="2" disabled="disabled">
                                    <option value="0">--Escoja Una---</option>
                                    <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option <?php if($doc->id == $customer->document_id): ?> selected="true" <?php else: ?> <?php endif; ?> value="<?php echo e($doc->id); ?>"><?php echo e($doc->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="last_name"> Apellido(s)</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" class="form-control registerForm" name="last_name" id="last_name" placeholder="Apellido" required tabindex="4" disabled="disabled" value="<?php echo e($customer->last_name); ?>">
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="correo"> Correo</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="email" class="form-control registerForm" name="email" id="email" placeholder="Correo" required tabindex="8" value="<?php echo e($customer->email); ?>">
                                <p id="emailError" style="color:red;font-weight: bold"></p>   
                            </div>
                        </div>
                        <div class="col-md-12" style="padding-bottom:15px">
                            <div class="row" style="float:left">
                            </div>
                            <div class="row" style="float:right;margin-right: 0px;">
                                <!--<a class="btn btn-success registerForm" align="right" href="#" style="padding: 5px"> Actualizar </a>-->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;margin-bottom: 5px;margin-top:25px;">
                    <div class="wizard_activo registerForm titleDivBorderTop" onclick="fadeToggle('vinculationsFormsDiv')">
                        <a href="#" class="titleLink">Formulario de Vinculación</a>
                        <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                    </div>
                    <div id="vinculationsFormsDiv" class="col-md-12" style="padding-top: 25px;">
                        <table class="table table-striped table-bordered" style="text-align: center;">
                            <thead class="tableCustomHeader">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th>Estado</th>
                                    <th>Ver Formulario</th>
                                    <th>Reenviar</th>
                                    <th>Validación</th>
                                </tr>
                            </thead>
                            <tbody id="vinculationTableBodyResume">
                                <tr>
                                    <td style="text-transform: uppercase;"><?php echo e($customer->first_name); ?> <?php echo e($customer->second_name); ?> <?php echo e($customer->last_name); ?> <?php echo e($customer->second_last_name); ?></td>
                                    <td>Cliente</td>
                                    <td>Pendiente</td>
                                    <?php if($vinculation->status_id == 1): ?>
                                        <td><a href="<?php echo e(asset('')); ?>vinculation/create?document=<?php echo e(\Crypt::encrypt($customer->document)); ?>&sales=<?php echo e(\Crypt::encrypt($sales->id)); ?>&broker=1" target="_blank" align="right"> Ver Formulario </a></td>
                                    <?php else: ?>
                                        <td></td>
                                    <?php endif; ?>
                                    <td><a onclick="sendVinculationFormLink('<?php echo e($sales->id); ?>')"  href="#"> Enviar Link </a></td>
                                    <td><input name="formValidate" id="formValidate" type="checkbox" data-toggle="toggle" data-on="Validado" data-off="No Validado"data-onstyle="success"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="payerFullDiv" class="col-xs-12 col-md-12 border" style="padding-left:0px !important;margin-top:20px;">
                    <div class="wizard_activo registerForm titleDivBorderTop" onclick="fadeToggle('payerDiv')">
                        <a href="#" class="titleLink">Datos del Pagador</a>
                        <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                    </div>
                    <div id="payerDiv" class="col-md-12" style="padding-top: 25px;">
                        <div class="col-md-6">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Cédula</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <!--<input type="text" class="form-control registerForm" name="document" id="document" placeholder="Cédula" value="<?php echo e(old('document')); ?>" required="required">-->
                                <div class="form-inline">
                                    <input autocomplete="off" type="text" class="form-control registerForm" name="document" id="document" placeholder="Cédula" required="required"tabindex="13" style="width:88%">
                                    <button type="button" class="btn btn-info" onclick="documentBtn()" style="width:10%"><span class="glyphicon glyphicon-search"></span></button>
                                    <div id="suggesstion-box"></div>
                                </div>                                        </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="first_name"> Nombre(s)</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" class="form-control registerForm" name="first_name" id="first_name" placeholder="Nombre" required="required" tabindex="15">
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="city"> Celular</label> <span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span><label class="own"><span class="glyphicon glyphicon-info-sign" style="color:#0099ff"><span class="own1" style="float:left">El celular debe tener 10 caracteres</span></span></label>
                                <input type="text" class="form-control registerForm" name="mobile_phone" id="mobile_phone" placeholder="Nombre" required tabindex="17">
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="city"> Direccion</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" class="form-control registerForm" name="address" id="address" placeholder="Nombre" required tabindex="19">
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="country"> Pais</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <select name="country" id="country" class="form-control registerForm" required tabindex="21">
                                    <option selected="true" value="0">--Escoja Una---</option>
                                    <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cou): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($cou->id); ?>"><?php echo e($cou->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="city"> Ciudad</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <select name="city" class="form-control registerForm" id="city" required tabindex="23">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document_id"> Tipo Documento</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <select id="document_id" name="document_id" class="form-control registerForm" value="<?php echo e(old('document_id')); ?>" required tabindex="14">
                                    <option selected="true" value="0">--Escoja Una---</option>
                                    <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($doc->id); ?>"><?php echo e($doc->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="last_name"> Apellido(s)</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" class="form-control registerForm" name="last_name" id="last_name" placeholder="Apellido" value="" required tabindex="16">
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Teléfono</label>&ensp;<span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span><label class="own"><span class="glyphicon glyphicon-info-sign" style="color:#0099ff"><span class="own1" style="float:left">El telefono debe tener 9 caracteres</span></span></label>
                                <input type="text" class="form-control registerForm" name="phone" id="phone" placeholder="Cédula" value="" required tabindex="18">
                            </div>

                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="correo"> Correo</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="email" class="form-control registerForm" name="email" id="email" placeholder="Correo" value="" required tabindex="20">
                                <p id="emailError" style="color:red;font-weight: bold"></p>   
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="province"> Canton</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <select name="province" class="form-control registerForm" id="province" required tabindex="22">
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12" style="padding-bottom:15px">
                    <div class="row" style="float:left">
                        <a class="btn btn-default registerForm" align="right" href="<?php echo e(asset('/sales')); ?>""> Cancelar </a>
                    </div>
                    <div class="row" style="float:right">
                        <a class="btn btn-info registerForm" align="right" href="#" style="padding: 5px" onclick="validateVinculationForm()"> Guardar </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--</div>-->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views\sales\vinculationForm.blade.php ENDPATH**/ ?>