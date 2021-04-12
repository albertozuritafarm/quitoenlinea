

<?php $__env->startSection('content'); ?>
<script src="<?php echo e(assets('js/registerCustom.js')); ?>"></script>
<script src="<?php echo e(assets('js/vinculation/create.js')); ?>"></script>
<link href="<?php echo e(assets('css/sales/create.css')); ?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo e(assets('css/sales/index.css')); ?>" rel="stylesheet" type="text/css"/>
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<?php if($secondary_email == null): ?>
<script>
$(document).ready(function () {
var div = document.getElementById('emailSecondaryForm');
$(div).fadeOut();
});</script>
<?php endif; ?>
<?php if($economic_activity != 6): ?>
<script>
    $(document).ready(function () {
    var div = document.getElementById('otherEconomicActivityDiv');
    $(div).fadeOut();
    });</script>
<?php endif; ?>
<?php if($other_monthly_income == null): ?>
<script>
    $(document).ready(function () {
    var div = document.getElementById('otherIncomeDiv');
    $(div).fadeOut();
    });</script>
<?php endif; ?>
<?php if($spouse_document == null): ?>
<script>
    $(document).ready(function () {
    var div = document.getElementById('spouseFullDiv');
    $(div).fadeOut();
    });</script>
<?php endif; ?>
<?php if($customer->document_id != 3): ?>
<script>
    $(document).ready(function () {
    var div = document.getElementById('passportFullDiv');
    $(div).fadeOut();
    });</script>
<?php endif; ?>
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
    }.titleDiv {
        cursor: pointer;
    }
</style>
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
<div class="container" style="margin-top:15px; font-size:14px !important">
    <!--<div class="row justify-content-center border" style="margin-left:20%;">-->
    <div class="col-md-10 col-md-offset-1 border" style="padding: 15px;">
        <div class="row">
            <div class="col-xs-12 registerForm" style="margin:12px;">
                <center>
                    <h4 style="font-weight:bold">Formulario de Vinculación</h4>
                    <!--<h5>Datos del Cliente.</h5>-->
                </center>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-2 wizard_inicial" style="padding-left:0px !important"><div id="firstStepWizard" class="wizard_activo registerForm">Información</div></div>
            <div class="col-xs-12 col-md-3 wizard_inicial" style="padding-left:0px !important"><div id="secondStepWizard" class="wizard_inactivo registerForm">Actividad Economica</div></div>
            <div class="col-xs-12 col-md-2 wizard_medio" style="padding-left:0px !important"><div id="thirdStepWizard" class="wizard_inactivo registerForm">Declaración</div></div>
            <div class="col-xs-12 col-md-2 wizard_medio" style="padding-left:0px !important"><div id="fourthStepWizard" class="wizard_inactivo registerForm">Documentación</div></div>
            <div class="col-xs-12 col-md-3 wizard_final" style="padding-right: 0px !important"><div id="fifthStepWizard" class="wizard_inactivo registerForm">Firma</div></div>
        </div>

        <div id="firstStep" class="col-md-12" style="margin-top:10px">
            <form id="firstStepForm" name="firstStepForm" method="POST" action="<?php echo e(asset('/user')); ?>" id="salesForm">
                <?php echo e(csrf_field()); ?>

                <input type="hidden" id="documentId" name="documentId" value="<?php echo e($customer->id); ?>">
                <input type="hidden" id="saleId" name="saleId" value="<?php echo e($sales_id); ?>">
                <div id="productAlert" class="alert alert-danger hidden">
                    <strong>¡Alerta!</strong> Debe seleccionar un producto
                </div>
                <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;margin-bottom: 5px">
                    <div class="wizard_activo registerForm titleDiv" onclick="fadeToggle('personalDiv')">
                        <a href="#" class="titleLink">Datos Persona Natural</a>
                        <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                    </div>
                    <div id="personalDiv" class="col-md-12">
                        <?php if($customer == false): ?>
                        <input type="hidden" id="customerCheck" value="0">
                        <?php else: ?>
                        <input type="hidden" id="customerCheck" value="1">
                        <?php endif; ?>
                        <div class="col-md-6">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="first_name"> Primer Nombre </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" id="first_name" name="first_name" class="form-control registerForm" required tabindex="1" placeholder="Nombre(s)" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="<?php echo e($customer->first_name); ?>">
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="first_name"> Segundo Nombre </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" id="second_name" name="second_name" class="form-control registerForm" required tabindex="3" placeholder="Nombre(s)" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="<?php echo e($customer->second_name); ?>">
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document_id"> Tipo Documento</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <select id="document_id" name="document_id" class="form-control registerForm" required tabindex="5" onchange="removeInputRedFocus(this.id)" <?php if($customer != false): ?> disabled="disabled" <?php endif; ?>>
                                    <option selected="true" value="" disabled="disabled">--Escoja Una---</option>
                                    <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($customer != false): ?>
                                    <?php if($customer->document_id == $doc->id): ?>
                                    <option value="<?php echo e($doc->id); ?>" selected="true"><?php echo e($doc->name); ?></option>
                                    <?php else: ?>
                                    <?php endif; ?>
                                    <?php else: ?>
                                    <option value="<?php echo e($doc->id); ?>"><?php echo e($doc->name); ?></option>
                                    <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="birth_city"> Lugar de Nacimiento</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <select id="birth_city" name="birth_city" class="form-control registerForm" required onchange="removeInputRedFocus(this.id)" <?php if($birth_place != null): ?> disabled="disabled" <?php endif; ?> tabindex="7">
                                    <option value="">--Escoja Una---</option>
                                    <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option <?php if($cit->id == $birth_place): ?> selected <?php endif; ?> value="<?php echo e($cit->id); ?>"><?php echo e($cit->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <input type="hidden" id="birth_place" name="birth_place" value="<?php echo e($birth_place); ?>">
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="nationality"> Nacionalidad</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <select id="nationality" name="nationality" class="form-control registerForm" required onchange="removeInputRedFocus(this.id)" <?php if($nationality_id != null): ?> disabled="disabled" <?php endif; ?> tabindex="9">
                                    <option value="">--Escoja Una---</option>
                                    <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cou): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option <?php if($cou->id == $nationality_id): ?> selected="true" <?php endif; ?> value="<?php echo e($cou->id); ?>"><?php echo e($cou->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="main_road"> Calle Principal</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="string" id="main_road" name="main_road" class="form-control registerForm" required tabindex="11" placeholder="Calle Principal" maxlength="110" onchange="removeInputRedFocus(this.id)" value="<?php echo e($main_road); ?>" <?php echo e($disable_status); ?>>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="country"> Pais</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <select id="country" name="country" class="form-control registerForm" required onchange="removeInputRedFocus(this.id)" tabindex="13">
                                    <option value="">--Escoja Una---</option>
                                    <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cou): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($cou->id); ?>"><?php echo e($cou->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document_id"> Cantón</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <select id="city" name="city" class="form-control registerForm" required tabindex="15" onchange="removeInputRedFocus(this.id)" <?php echo e($disable_status); ?>>
                                    <option value="">--Escoja Una---</option>
                                    <?php if($addressCities != null): ?>
                                    <?php $__currentLoopData = $addressCities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option <?php if($cit->id == $city_id): ?> selected <?php endif; ?> value="<?php echo e($cit->id); ?>"><?php echo e($cit->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="sector"> N°</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" id="number" name="number" class="form-control registerForm" required tabindex="17" placeholder="N°" maxlength="10" onchange="removeInputRedFocus(this.id)" value="<?php echo e($address_number); ?>" <?php echo e($disable_status); ?>>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="municipality"> Parroquia </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <select id="municipality" name="municipality" class="form-control registerForm" required tabindex="19" onchange="removeInputRedFocus(this.id)" <?php echo e($disable_status); ?>>
                                    <option selected="true" value="">--Escoja Una---</option>
                                    <option value="">Conocoto</option>
                                    <option value="">Calderon</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="mobile_phone">Teléfono Celular</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" id="mobile_phone" name="mobile_phone" class="form-control registerForm" required tabindex="21" placeholder="Teléfono Celular" onchange="removeInputRedFocus(this.id)" value="<?php echo e($mobile_phone); ?>" <?php echo e($disable_status); ?>>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document_id"> Apellidos Completos</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" id="last_name" name="last_name" class="form-control registerForm" required tabindex="2" placeholder="Apellido(s)" onchange="removeInputRedFocus(this.id)" <?php if($customer != false): ?> disabled="disabled" value="<?php echo e($customer->last_name); ?>" <?php endif; ?>>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document_id"> Apellidos Completos</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" id="second_last_name" name="second_last_name" class="form-control registerForm" required tabindex="4" placeholder="Apellido(s)" onchange="removeInputRedFocus(this.id)" <?php if($customer != false): ?> disabled="disabled" value="<?php echo e($customer->last_name); ?>" <?php endif; ?>>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document_id"> Número de Identificación</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input id="documentForm" type="text" id="document" name="document" class="form-control registerForm" required tabindex="6" placeholder="Número de Identificación" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="<?php echo e($customer->document); ?>">
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="birth_date"> Fecha de Nacimiento</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="date" id="birth_date" name="birth_date" class="form-control registerForm" style="line-height: 15px !important" onchange="removeInputRedFocus(this.id)" value="<?php echo e($birth_date); ?>" <?php if($birth_date != null): ?> readonly="readonly" <?php endif; ?> tabindex="8">
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="civilState">Estado Civil</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <select id="civilState" name="civilState" class="form-control registerForm" required tabindex="10" onchange="removeInputRedFocus(this.id)" <?php echo e($disable_status); ?>>
                                    <option value="">--Escoja Una---</option>
                                    <?php $__currentLoopData = $civilStates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option <?php if($sta->id == $civil_state): ?> selected <?php endif; ?> value="<?php echo e($sta->id); ?>"><?php echo e($sta->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="main_road"> Calle Transversal</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" id="secondary_road" name="secondary_road" class="form-control registerForm" required tabindex="12" placeholder="Calle Transversal"  maxlength="110" onchange="removeInputRedFocus(this.id)" value="<?php echo e($secondary_road); ?>" <?php echo e($disable_status); ?>>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document_id"> Provincia  </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <select id="province" name="province" class="form-control registerForm" required tabindex="14" onchange="removeInputRedFocus(this.id)" <?php echo e($disable_status); ?>>
                                    <option selected="true" value="" disabled="disabled">--Escoja Una---</option>
                                    <?php $__currentLoopData = $provinces; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option <?php if($prov->id == $province_id): ?> selected <?php endif; ?> value="<?php echo e($prov->id); ?>"><?php echo e($prov->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="sector"> Sector</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" id="sector" name="sector" class="form-control registerForm" required tabindex="16" placeholder="Sector" maxlength="10" onchange="removeInputRedFocus(this.id)" value="<?php echo e($sector); ?>" <?php echo e($disable_status); ?>>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="phone">Teléfono</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" id="phone" name="phone" class="form-control registerForm" required tabindex="18" placeholder="Teléfono" onchange="removeInputRedFocus(this.id)" value="<?php echo e($phone); ?>" <?php echo e($disable_status); ?>>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="email"> Correo</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="email" id="email" name="email" class="form-control registerForm" required tabindex="20" placeholder="Correo" onchange="removeInputRedFocus(this.id)" value="<?php echo e($email); ?>" <?php echo e($disable_status); ?>>
                                <p id="emailError" style="color:red;font-weight: bold"></p>    
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div id="passportFullDiv" class="col-xs-12 col-md-12 border" style="padding-left:0px !important;margin-bottom: 5px">
                    <div class="wizard_activo registerForm titleDiv" onclick="fadeToggle('passportDiv')">
                        <a href="#" class="titleLink">Pasaporte</a>
                        <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                    </div>
                    <div id="passportDiv" class="col-md-12">
                        <div class="col-md-12" style="margin-bottom: -25px">
                            <div class="col-md-6 form-group" style="margin-left:-15px">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="passportNumber">Número de Pasaporte</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" id="passportNumber" name="passportNumber" class="form-control registerForm" required tabindex="20" placeholder="Número de Pasaporte" onchange="removeInputRedFocus(this.id)" <?php echo e($disable_status); ?>>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="passportBeginDate"> Fecha de Emisión</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="date" id="passportBeginDate" name="passportBeginDate" class="form-control registerForm" required tabindex="21"  style="line-height: 15px !important;width:96%" onchange="removeInputRedFocus(this.id)" <?php echo e($disable_status); ?>>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="passportEndDate"> Fecha de Caducidad</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="date" id="passportEndDate" name="passportEndDate" class="form-control registerForm" required tabindex="22"  style="line-height: 15px !important" onchange="removeInputRedFocus(this.id)" <?php echo e($disable_status); ?>>
                            </div>
                        </div>
                        <div class="col-md-12" style="margin-top: -25px">
                            <div id=""  class="col-md-6 form-group" style="margin-left:-15px">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="migratoryState">  Estado migratorio o Código de VISA:</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <select id="migratoryState" name="migratoryState" class="form-control registerForm" required tabindex="23" onchange="removeInputRedFocus(this.id)" <?php echo e($disable_status); ?>>
                                    <option selected="true" value="">--Escoja Una---</option>
                                    <?php $__currentLoopData = $migratoryStates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($sta->id); ?>"><?php echo e($sta->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>   
                            </div>
                            <div class="col-md-6 form-group" style="float:right; margin-right: -15px;width:52%">                                
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="passportEntryDate"> Fecha de Ingreso al país</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="date" id="passportEntryDate" name="passportEntryDate" class="form-control registerForm" required tabindex="24"  style="line-height: 15px !important;width:96%" onchange="removeInputRedFocus(this.id)" <?php echo e($disable_status); ?>>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="spouseFullDiv" class="col-xs-12 col-md-12 border" style="padding-left:0px !important;margin-bottom: 5px">
                    <div class="wizard_activo registerForm titleDiv" onclick="fadeToggle('spouseDiv')">
                        <a href="#" class="titleLink">Datos del Conyuge o Conviviente</a>
                        <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                    </div>
                    <div id="spouseDiv" class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="passportBeginDate"> Documento de Identidad Conyuge</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" id="spouseDocument" name="spouseDocument" class="form-control registerForm" required tabindex="25"  style="line-height: 15px !important;width:96%" value="<?php echo e($spouse_document); ?>" placeholder="Documento de Identidad Conyuge" onchange="removeInputRedFocus(this.id)" <?php echo e($disable_status); ?>>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="nationality_spouse"> Nacionalidad</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <select id="nationality_spouse" name="nationality_spouse" class="form-control registerForm" required tabindex="27" onchange="removeInputRedFocus(this.id)" <?php if($nationality_id != null): ?> disabled="disabled" <?php endif; ?>>
                                    <option value="">--Escoja Una---</option>
                                    <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cou): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option <?php if($cou->id == $nationality_id): ?> selected="true" <?php endif; ?> value="<?php echo e($cou->id); ?>"><?php echo e($cou->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="birth_city_spouse"> Lugar de Nacimiento</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <select id="birth_city_spouse" name="birth_city_spouse" class="form-control registerForm" required tabindex="29" onchange="removeInputRedFocus(this.id)">
                                    <option value="">--Escoja Una---</option>
                                    <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option <?php if($cit->id == $birth_place): ?> selected <?php endif; ?> value="<?php echo e($cit->id); ?>"><?php echo e($cit->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <input type="hidden" id="birth_place" name="birth_place" value="<?php echo e($birth_place); ?>">
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="company_name_spouse"> Nombre de la Empresa</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" id="company_name_spouse" name="company_name_spouse" class="form-control registerForm" required tabindex="31"  style="line-height: 15px !important" value="" placeholder="Nombre de la Empresa" onchange="removeInputRedFocus(this.id)" <?php echo e($disable_status); ?>>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="phone_spouse"> Teléfono</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" id="phone_spouse" name="phone_spouse" class="form-control registerForm" required tabindex="33"  style="line-height: 15px !important" value="" placeholder="Teléfono" onchange="removeInputRedFocus(this.id)" <?php echo e($disable_status); ?>>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="email_spouse"> Email</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" id="email_spouse" name="email_spouse" class="form-control registerForm" required tabindex="35"  style="line-height: 15px !important" value="" placeholder="Email" onchange="removeInputRedFocus(this.id)" <?php echo e($disable_status); ?>>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="spouse_document_id"> Tipo Documento</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <select id="spouse_document_id" name="spouse_document_id" class="form-control registerForm" required tabindex="26" onchange="removeInputRedFocus(this.id)">
                                    <option selected="true" value="" disabled="disabled">--Escoja Una---</option>
                                    <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($doc->id); ?>"><?php echo e($doc->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="passportEndDate"> Nombres y Apellidos del Conyuge</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" id="spouseName" name="spouseName" class="form-control registerForm" required tabindex="28"  style="line-height: 15px !important" value="<?php echo e($spouse_name); ?>" placeholder="Nombre del Conyuge" onchange="removeInputRedFocus(this.id)" <?php echo e($disable_status); ?>>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="birth_date_spouse"> Fecha de Nacimiento</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="date" id="birth_date_spouse" name="birth_date_spouse" class="form-control registerForm" style="line-height: 15px !important" tabindex="30" onchange="removeInputRedFocus(this.id)" value="" <?php if($birth_date != null): ?> readonly="readonly" <?php endif; ?>>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="economic_activity_spouse"> Profesión o Actividad Económica</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" id="economic_activity_spouse" name="economic_activity_spouse" class="form-control registerForm" required tabindex="32"  style="line-height: 15px !important" value="" placeholder="Profesión o Actividad Económica" onchange="removeInputRedFocus(this.id)" <?php echo e($disable_status); ?>>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="annual_income_spouse"> Ingresos Anuales</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" id="annual_income_spouse" name="annual_income_spouse" class="form-control registerForm" required tabindex="34"  style="line-height: 15px !important" value="" placeholder="Ingresos Anuales" onchange="removeInputRedFocus(this.id)" <?php echo e($disable_status); ?>>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="beneficiaryFullDiv" class="col-xs-12 col-md-12 border" style="padding-left:0px !important;margin-bottom: 5px">
                    <div class="wizard_activo registerForm titleDiv" onclick="fadeToggle('beneficiaryDiv')">
                        <a href="#" class="titleLink">Vinculos Existentes entre el Contratante y Beneficiario</a>
                        <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                    </div>
                    <div id="beneficiaryDiv" class="col-md-12">
                        <div class="col-md-12">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="is_beneficiary"> ¿Es usted el beneficiario de la póliza?</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span><br>
                                <input id="is_beneficiary" name="is_beneficiary" type="checkbox" checked="checked" data-toggle="toggle" tabindex="35" data-on="Si" data-off="No" onchange="isBeneficiaryChange(this)">
                            </div>
                        </div>
                        <span id="beneficiaryDataDiv" style="margin-top:-25px;">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="beneficiaryName"> Nombres Completos o Razón Social</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text2" id="beneficiaryName" name="beneficiaryName" checked class="form-control registerForm" required tabindex="36"  style="line-height: 15px !important" value="" placeholder="Nombres Completos o Razón Social" onchange="removeInputRedFocus(this.id)" <?php echo e($disable_status); ?>>
                                </div>
                            </div>
                            <div class="col-md-6" style="margin-top:-25px">
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="beneficiary_document_id"> Tipo Documento</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select id="beneficiary_document_id" name="beneficiary_document_id" class="form-control registerForm" required tabindex="38" onchange="removeInputRedFocus(this.id)">
                                        <option selected="true" value="" disabled="disabled">--Escoja Una---</option>
                                        <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($doc->id); ?>"><?php echo e($doc->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="beneficiary_nationality"> Nacionalidad</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select id="beneficiary_nationality" name="beneficiary_nationality" class="form-control registerForm" required tabindex="40" onchange="removeInputRedFocus(this.id)">
                                        <option value="">--Escoja Una---</option>
                                        <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cou): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($cou->id); ?>"><?php echo e($cou->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="beneficiary_phone"> Teléfono</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text2" id="beneficiary_phone" name="beneficiary_phone" class="form-control registerForm" required tabindex="42"  style="line-height: 15px !important" value="" placeholder="Teléfono" onchange="removeInputRedFocus(this.id)" <?php echo e($disable_status); ?>>
                                </div>
                            </div>
                            <div class="col-md-6" style="margin-top:-25px">
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="beneficiary_document"> Documento de Identidad</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text2" id="beneficiary_document" name="beneficiary_document" class="form-control registerForm" required tabindex="39"  style="line-height: 15px !important" value="" placeholder="Documento" onchange="removeInputRedFocus(this.id)" <?php echo e($disable_status); ?>>
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="beneficiary_address"> Dirección de Domicilio</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text2" id="beneficiary_address" name="beneficiary_address" class="form-control registerForm" required tabindex="41"  style="line-height: 15px !important" value="" placeholder="Dirección de Domicilio" onchange="removeInputRedFocus(this.id)" <?php echo e($disable_status); ?>>
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="beneficiary_relationship"> Relación</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text2" id="beneficiary_relationship" name="beneficiary_relationship" class="form-control registerForm" required tabindex="43"  style="line-height: 15px !important" value="" placeholder="Relación" onchange="removeInputRedFocus(this.id)" <?php echo e($disable_status); ?>>
                                </div>
                            </div>
                            <div class="col-md-12" style="text-align: justify;">
                                * Cuando en la póliza de seguro de vida o de accidentes personales con la cobertura de muerte, los asegurados hubiesen designado como beneficiarios a sus parientes hasta el cuarto grado de consanguinidad o segundo grado de afinidad, o a su cónyuge o conviviente en unión de hecho, no se requerirá la información de tales beneficiarios. Si fuesen otras personas las designadas como beneficiarios, la documentación referente a estos deberá ser presentada, obligatoriamente, mediante formulario de vinculación de clientes. 
                            </div>
                        </span>
                    </div>
                </div>
                <div class="col-md-12" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                    <div class="row" style="float:left">
                        <!--<a class="btn btn-default registerForm" align="right" href="<?php echo e(asset('/sales')); ?>" style="margin-left: -30px;"> Cancelar </a>-->
                    </div>
                    <div class="row" style="float:right">
                        <a id="firstStepBtnNext" class="btn btn-info registerForm" align="right" href="#"> Siguiente <span class="glyphicon glyphicon-step-forward"></span></a>
                    </div>
                </div>
            </form>
        </div>
        <div id="secondStep" class="col-md-12 hidden" style="margin-top:20px">
            <form id="secondStepForm" name="secondStepForm" method="POST" action="<?php echo e(asset('/user')); ?>" id="salesForm">
                <?php echo e(csrf_field()); ?>

                <input type="hidden" id="documentId" name="documentId" value="<?php echo e($customer->id); ?>">
                <input type="hidden" id="saleId" name="saleId" value="<?php echo e($sales_id); ?>">
                <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;margin-bottom: 5px">
                    <div class="wizard_activo registerForm titleDiv" onclick="fadeToggle('occupationDiv')">
                        <a href="#" class="titleLink">Datos Actividad Económica/Ocupación/Negocio</a>
                        <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                    </div>
                    <div id="occupationDiv" class="col-md-12">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document_id"> Tipo de Empleo</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select id="economic_activity" name="economic_activity" class="form-control registerForm" required tabindex="1" onchange="removeInputRedFocus(this.id)" <?php echo e($disable_status); ?>>
                                        <option value="">--Escoja Una---</option>
                                        <?php $__currentLoopData = $economicActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eco): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option <?php if($economic_activity == $eco->id): ?> selected="true" <?php endif; ?> value="<?php echo e($eco->id); ?>"><?php echo e($eco->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div id="otherEconomicActivityDiv" class="col-md-6">
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="sector"> Especifique</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text2" id="other_economic_activity" name="other_economic_activity" class="form-control registerForm" required tabindex="2" placeholder="Especifique" onchange="removeInputRedFocus(this.id)" value="<?php echo e($other_economic_activity); ?>" <?php echo e($disable_status); ?>>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="occupation"> Ocupación o Actividad Económica</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select id="occupation" name="occupation" class="form-control registerForm" required tabindex="3" onchange="removeInputRedFocus(this.id)" <?php echo e($disable_status); ?>>
                                        <option value="">--Escoja Una---</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="sector"> Cargo que desempeña</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text2" id="position" name="position" class="form-control registerForm" required tabindex="5" placeholder="Cargo que desempeña" onchange="removeInputRedFocus(this.id)" value="" <?php echo e($disable_status); ?>>
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="sector"> Dirección del Trabajo</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text2" id="company_address" name="company_address" class="form-control registerForm" required tabindex="7" placeholder="Dirección del Trabajo" onchange="removeInputRedFocus(this.id)" value="" <?php echo e($disable_status); ?>>
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="charge_address"> Dirección de Cobro</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select id="charge_address" name="charge_address" class="form-control registerForm" required tabindex="9" onchange="removeInputRedFocus(this.id)" <?php echo e($disable_status); ?>>
                                        <option value="">--Escoja Una---</option>
                                        <option value="">Domicilio</option>
                                        <option value="">Lugar de Trabajo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="sector"> Nombre de la Empresa</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text2" id="company_name" name="company_name" class="form-control registerForm" required tabindex="4" placeholder="Nombre de la Empresa" onchange="removeInputRedFocus(this.id)" value="" <?php echo e($disable_status); ?>>
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="sector"> Correo Electrónico</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="email" id="company_email" name="company_email" class="form-control registerForm" required tabindex="6" placeholder="Correo Electrónico" onchange="removeInputRedFocus(this.id)" value="" <?php echo e($disable_status); ?>>
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="sector"> Teléfono</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="email" id="company_phone" name="company_phone" class="form-control registerForm" required tabindex="8" placeholder="Teléfono" onchange="removeInputRedFocus(this.id)" value="" <?php echo e($disable_status); ?>>
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="sector"> Otro</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text2" id="other_economic_activity" name="other_economic_activity" class="form-control registerForm" required tabindex="10" placeholder="Otro" onchange="removeInputRedFocus(this.id)" value="<?php echo e($other_economic_activity); ?>" <?php echo e($disable_status); ?>>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;margin-bottom: 5px">
                    <div class="wizard_activo registerForm titleDiv" onclick="fadeToggle('financingDiv')">
                        <a href="#" class="titleLink">Situación Financiera</a>
                        <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                    </div>
                    <div id="financingDiv" class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="annual_income"> Ingresos Anuales</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text2" id="annual_income" name="annual_income" class="form-control registerForm" required tabindex="11" placeholder="Otros Ingresos Anuales" onchange="removeInputRedFocus(this.id)" value="<?php echo e($monthly_income); ?>" <?php echo e($disable_status); ?>>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="main_road"> Total Ingresos</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text2" id="total_annual_income" name="total_annual_income" class="form-control registerForm" required tabindex="13" placeholder="Total Ingresos" onchange="removeInputRedFocus(this.id)" value="<?php echo e($monthly_outcome); ?>" disabled>
                            </div>
                            <!--                            <div class="form-group">
                                                            <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="main_road"> Total Pasivos</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                                            <input type="text2" id="total_pasives" name="total_pasives" class="form-control registerForm" required tabindex="2" placeholder="Total Pasivos" onchange="removeInputRedFocus(this.id)" value="<?php echo e($total_pasives); ?>" <?php echo e($disable_status); ?>>
                                                        </div>-->
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="other_annual_income"> Otros Ingresos Anuales</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text2" id="other_annual_income" name="other_annual_income" class="form-control registerForm" required tabindex="12" placeholder="Otros Ingresos Anuales" onchange="removeInputRedFocus(this.id)" value="<?php echo e($total_actives); ?>" <?php echo e($disable_status); ?>>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="description_other_income"> Descripción de otros Ingresos</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text2" id="description_other_income" name="description_other_income" class="form-control registerForm" required tabindex="14" placeholder="Descripción de otros Ingresos" onchange="removeInputRedFocus(this.id)" value="<?php echo e($total_actives); ?>" <?php echo e($disable_status); ?>>
                            </div>
                            <!--                            <div class="form-group">
                                                            <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="optradio"> ¿Posee ingresos diferentes a la actividad principal?</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span><br>
                                                            <label class="radio-inline"><input type="radio" name="optradio2" value="yes" <?php if($other_monthly_income != null): ?> checked <?php endif; ?> <?php echo e($disable_status); ?>>Si</label>
                                                            <label class="radio-inline"><input type="radio" name="optradio2" value="no"<?php if($other_monthly_income == null): ?> checked <?php endif; ?> <?php echo e($disable_status); ?>>No</label>
                                                            <input type="hidden" id="extraIncomeDiv" name="extraIncomeDiv" value="no">
                                                        </div>-->
                        </div>
                        <div id="otherIncomeDiv" class="col-md-12" style="margin-top:-25px">
                            <div class="form-group" style="width:48%;float:left;">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="main_road"> Ingresos diferentes de la actividad principal</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text2" id="other_monthly_income" name="other_monthly_income" class="form-control registerForm" required tabindex="15" placeholder="Ingresos diferentes de la actividad principal" onchange="removeInputRedFocus(this.id)" value="<?php echo e($other_monthly_income); ?>" <?php echo e($disable_status); ?>>
                            </div>
                            <div class="form-group"  style="width:48%;float:right;">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="main_road"> Fuente de los otros Ingresos</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text2" id="other_monthly_income_source" name="other_monthly_income_source" class="form-control registerForm" required tabindex="16" placeholder="Fuente de los otros Ingresos" onchange="removeInputRedFocus(this.id)" value="<?php echo e($other_monthly_income_source); ?>" <?php echo e($disable_status); ?>>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;margin-bottom: 5px">
                    <div class="wizard_activo registerForm titleDiv" onclick="fadeToggle('financindSecondDiv')">
                        <a href="#" class="titleLink">Activos / Pasivos</a>
                        <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                    </div>
                    <div id="financindSecondDiv" class="col-md-12">
                        <div class="col-md-12" style="margin-top:15px">
                            * Aplica a contratos cuya suma asegurada sea superior a USD 50.000
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="total_assets"> Total de Activos USD</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text2" id="total_assets" name="total_assets" class="form-control registerForm" required tabindex="17" placeholder="Total Activos USD" onchange="removeInputRedFocus(this.id)" value="<?php echo e($monthly_income); ?>" <?php echo e($disable_status); ?>>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="total_assets_pasives"> Total Patrimonio (A-P)</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text2" id="total_assets_pasives" name="total_assets_pasives" class="form-control registerForm" required tabindex="19" placeholder="Total Patrimonio (A-P)" onchange="removeInputRedFocus(this.id)" value="<?php echo e($monthly_income); ?>" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="total_passives"> Total de Pasivos USD</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text2" id="total_passives" name="total_passives" class="form-control registerForm" required tabindex="18" placeholder="Total de Pasivos USD" onchange="removeInputRedFocus(this.id)" value="<?php echo e($total_actives); ?>" <?php echo e($disable_status); ?>>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;margin-bottom: 5px">
                    <div class="wizard_activo registerForm titleDiv" onclick="fadeToggle('financindThirdDiv')">
                        <a href="#" class="titleLink">Referencias</a>
                        <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                    </div>
                    <div id="financindThirdDiv" class="col-md-12">
                        <div class="col-md-12" style="margin-top:15px">
                            * Aplica a contratos cuya suma asegurada sea superior a USD 200.000
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <h4>Referencias Personales</h4>
                            </div>
                        </div>
                        <div class="col-md-4" style="margin-top:-25px;">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="personal_reference_name"> Nombre</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text2" id="personal_reference_name" name="personal_reference_name" class="form-control registerForm" required tabindex="20" placeholder="Nombre" onchange="removeInputRedFocus(this.id)" value="" <?php echo e($disable_status); ?>>
                            </div>
                        </div>
                        <div class="col-md-4" style="margin-top:-25px;">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="personal_reference_relationship"> Parentesco</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text2" id="personal_reference_relationship" name="personal_reference_relationship" class="form-control registerForm" required tabindex="21" placeholder="Parentesco" onchange="removeInputRedFocus(this.id)" value="" <?php echo e($disable_status); ?>>
                            </div>
                        </div>
                        <div class="col-md-4" style="margin-top:-25px;">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="personal_reference_phone"> Teléfono</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text2" id="personal_reference_phone" name="personal_reference_phone" class="form-control registerForm" required tabindex="22" placeholder="Teléfono" onchange="removeInputRedFocus(this.id)" value="" <?php echo e($disable_status); ?>>
                            </div>
                        </div>
                        <div class="col-md-12" style="margin-top:-25px;">
                            <div class="form-group">
                                <h4>Referencias Comerciales</h4>
                            </div>
                        </div>
                        <div class="col-md-4" style="margin-top:-25px;">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="commercial_reference_name"> Entidad</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text2" id="commercial_reference_name" name="commercial_reference_name" class="form-control registerForm" required tabindex="23" placeholder="Entidad" onchange="removeInputRedFocus(this.id)" value="" <?php echo e($disable_status); ?>>
                            </div>
                        </div>
                        <div class="col-md-4" style="margin-top:-25px;">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="commercial_reference_relationship"> Monto</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text2" id="commercial_reference_relationship" name="commercial_reference_relationship" class="form-control registerForm" required tabindex="24" placeholder="Monto" onchange="removeInputRedFocus(this.id)" value="" <?php echo e($disable_status); ?>>
                            </div>
                        </div>
                        <div class="col-md-4" style="margin-top:-25px;">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="commercial_reference_phone"> Teléfono</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text2" id="commercial_reference_phone" name="commercial_reference_phone" class="form-control registerForm" required tabindex="25" placeholder="Teléfono" onchange="removeInputRedFocus(this.id)" value="" <?php echo e($disable_status); ?>>
                            </div>
                        </div>
                        <div class="col-md-12" style="margin-top:-25px;">
                            <div class="form-group">
                                <h4>Referencias Bancos/Tarjetas</h4>
                            </div>
                        </div>
                        <div class="col-md-6" style="margin-top:-25px;">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="commercial_reference_bank_name"> Institución Financiera</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text2" id="commercial_reference_bank_name" name="commercial_reference_bank_name" class="form-control registerForm" required tabindex="26" placeholder="Instirución Financiera" onchange="removeInputRedFocus(this.id)" value="" <?php echo e($disable_status); ?>>
                            </div>
                        </div>
                        <div class="col-md-6" style="margin-top:-25px;">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="commercial_reference_product"> Producto</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text2" id="commercial_reference_product" name="commercial_reference_product" class="form-control registerForm" required tabindex="27" placeholder="Producto" onchange="removeInputRedFocus(this.id)" value="" <?php echo e($disable_status); ?>>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                    <div class="row" style="float:left">
                        <!--<a class="btn btn-default registerForm" align="right" href="<?php echo e(asset('/sales')); ?>" style="margin-left: -30px;"> Cancelar </a>-->
                    </div>
                    <div class="row" style="float:right">
                        <a id="secondStepBtnBack" class="btn btn-back registerForm" align="right" href="#"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                        <a id="secondStepBtnNext" class="btn btn-info registerForm" align="right" href="#"> Siguiente <span class="glyphicon glyphicon-step-forward"></span></a>
                    </div>
                </div>
            </form>
        </div>
        <div id="thirdStep" class="col-md-12 hidden" style="margin-top:20px">
            <form id="thirdStepForm" name="thirdStepForm" method="POST" action="<?php echo e(asset('/user')); ?>" id="salesForm">
                <?php echo e(csrf_field()); ?>

                <input type="hidden" id="documentId" name="documentId" value="<?php echo e($customer->id); ?>">
                <input type="hidden" id="saleId" name="saleId" value="<?php echo e($sales_id); ?>">
                <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;margin-bottom: 5px">
                    <div class="wizard_activo registerForm titleDiv" onclick="fadeToggle('pepDeclarationDiv')">
                        <a href="#" class="titleLink">Declaración y Autorización</a>
                        <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                    </div>
                    <div id="pepDeclarationDiv" class="col-md-12">
                        <div class="col-md-12" style="margin-top:15px;text-align: justify; font-size: 14px;margin-bottom: 10px;">
                            <div class="col-md-12" style="margin-top:-25px;">
                                <div class="form-group">
                                    <h4>Declaración</h4>
                                </div>
                            </div>
                            <hr>
                            <div class="col-md-12" style="margin-bottom:15px;">
                                Declaro que la información contenida en este formulario, así como toda la documentación presentada, es verdadera, completa y proporciona la información de modo confiable y actualizada. Además, declaro conocer y aceptar que es mi obligación como cliente actualizar anualmente estos datos, así como el comunicar y documentar de manera inmediata a la compañía cualquier cambio en la información que hubiere proporcionado. Durante la vigencia de la relación con Seguros Sucre S.A., me comprometo a proveer de la documentación e información que me sea solicitada.
                            </div>
                            <hr>
                            <div class="col-md-12" style="margin-bottom:15px;">
                                El asegurado declara expresamente que el seguro aquí convenido ampara bienes de procedencia lícita, no ligados con actividades de narcotráfico, lavado de dinero o cualquier otra actividad tipificada en la Ley Orgánica de Prevención, Detección y Erradicación del Delito de Lavado de Activos y del Financiamiento de Delitos. Igualmente, la prima a pagar por este concepto tiene origen lícito y ninguna relación con las actividades mencionadas anteriormente. Eximo a Seguros Sucre S.A. de toda responsabilidad, inclusive respecto a terceros, si esta declaración fuese falsa o errónea. En caso de que se inicien investigaciones sobre mi persona, relacionadas con las actividades antes señaladas o de producirse transacciones inusuales o injustificadas, Seguros Sucre S.A., podrá proporcionar a las autoridades competentes toda la información que tenga sobre las mismas o que le sea requerida. En tal sentido renuncio a presentar en contra de Seguros Sucre S.A., sus funcionarios o empleados, cualquier reclamo o acción legal, judicial, extrajudicial, administrativa, civil penal o arbitral en la eventualidad de producirse tales hechos.
                            </div>  
                            <hr>
                            <div class="col-md-12" style="margin-bottom:15px;">
                                Declaración sobre la condición de Persona Expuesta Políticamente PEP (Persona que desempeña o ha desempeñado funciones públicas en el país o en el exterior). Informo que he leído la Lista Mínima de Cargos Públicos a ser considerados "Personas Expuestas Políticamente" y declaro bajo juramento que <label class="radio-inline" style="padding-left:5px;padding-right: 5px;">Si <input type="radio" name="optradio3" value="yes" <?php if($person_exposed == 'yes'): ?> checked <?php endif; ?> style="margin-left:5px;margin-top: 0px;"></label> <label class="radio-inline" style="padding-left:5px; padding-right:15px;">No <input type="radio" name="optradio3" value="no" <?php if($person_exposed == 'no'): ?> checked <?php endif; ?> style="margin-left:5px;margin-top: 0px;"></label><br> me encuentro ejerciendo uno de los cargos incluidos en la lista o lo ejercí hace un año atrás. En el caso de que la respuesta sea positiva, indicar: Cargo/Función/Jerarquía:  <input type="text2" id="pep_client" name="pep_client" class="form-control registerForm" required tabindex="2" placeholder="Cargo/Función/Jerarquía" onchange="removeInputRedFocus(this.id)" value="" <?php echo e($disable_status); ?>>
                                Nota: La presente declaración no constituye una autoincriminación de ninguna clase, ni conlleva ninguna responsabilidad administrativa, civil o penal.
                            </div>
                            <hr>
                            <div class="col-md-12" style="margin-top:-25px;">
                                <div class="form-group">
                                    <h4>Autorización</h4>
                                </div>
                            </div>  
                            <hr>
                            <div class="col-md-12" style="margin-bottom:15px;">
                                Siendo conocedor de las disposiciones legales, autorizo expresamente en forma libre, voluntaria e irrevocable a Seguros Sucre S. A., a realizar el análisis y las verificaciones que considere necesarias para corroborar la licitud de fondos y bienes comprendidos en el contrato de seguro e informar a las autoridades competentes si fuera el caso; además autorizo expresa, voluntaria e irrevocablemente a todas las personas naturales o jurídicas de derecho público o privado a facilitar a Seguros Sucre S.A. toda la información que ésta les requiera  y revisar los buró de crédito sobre mi información de riesgos crediticios. 
                            </div> 
                        </div>
                    </div>
                    <input type="hidden" id="exposedPersonInput" name="exposedPersonInput" value="<?php echo e($person_exposed); ?>">
                </div>
                <div class="col-md-12" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                    <div class="row" style="float:left">
                        <!--<a class="btn btn-default registerForm" align="right" href="<?php echo e(asset('/sales')); ?>" style="margin-left: -30px;"> Cancelar </a>-->
                    </div>
                    <div class="row" style="float:right">
                        <a id="thirdStepBtnBack" class="btn btn-back registerForm" align="right" href="#"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                        <a id="thirdStepBtnNext" class="btn btn-info registerForm" align="right" href="#"> Siguiente <span class="glyphicon glyphicon-step-forward"></span></a>
                    </div>
                </div>
            </form>
        </div>
        <div id="fourthStep" class="col-md-12 hidden" style="margin-top:20px">
            <!--<form id="fourthStepForm" name="fourthtepForm" method="POST" action="<?php echo e(asset('/user')); ?>" id="salesForm">-->
            <?php echo e(csrf_field()); ?>

            <input type="hidden" id="documentId" name="documentId" value="<?php echo e($customer->id); ?>">
            <input type="hidden" id="saleId" name="saleId" value="<?php echo e($sales_id); ?>">
            <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;margin-bottom: 5px">
                <div class="wizard_activo registerForm titleDiv" onclick="fadeToggle('picturesDivpicturesDiv')">
                    <a href="#" class="titleLink">Documentos Requeridos - Persona Natural</a>
                    <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                </div>
                <div id="picturesDiv" class="col-md-12">
                    <div class="col-md-12">
                        <div class="col-md-6 form-group">
                            <label>Fecha de Caducidad</label>
                            <input type="date" class="form-control" style="line-height:14px;">
                            <br>
                            <form method="post" id="upload_formDocumentApplicant" name="upload_formDocumentApplicant" enctype="multipart/form-data" onsubmit="uploadPictureForm('upload_formDocumentApplicant'">
                                <?php echo e(csrf_field()); ?>                                
                                <input type="hidden" id="documentId" name="documentId" value="<?php echo e($customer->id); ?>">
                                <input type="hidden" id="saleId" name="saleId" value="<?php echo e($sales_id); ?>">
                                <input type="hidden" id="uploadType" name="uploadType" value="DocumentApplicant">
                                <input type="hidden" id="uploadedFileDocumentApplicant" name="uploadedFileDocumentApplicant" value="<?php echo e($picture_document_applicant); ?>">
                                <center>
                                    <label>Copia del documento de identidad del contratante</label>
                                    <div class="alert" id="messageDocumentApplicant" style="display: none"></div>
                                    <div style="width:100px !important;padding: 0" class="inside" id="fileNameDocumentApplicant"></div>
                                    <div class="inputWrapper"><span id="uploaded_imageDocumentApplicant"><?php echo $picture_document_applicant; ?></span>
                                        <center>
                                            <img src="<?php echo e(asset('images/mas.png')); ?>" alt="Girl in a jacket" style="width:20px;height:20px;margin-bottom: -100px;">
                                        </center>
                                        <input class="fileInput" type="file" name="select_fileDocumentApplicant" onchange="fileNameFunction('DocumentApplicant')" id="select_fileDocumentApplicant">
                                    </div>
                                </center>
                                <center>
                                    <button type="submit" name="upload" id="uploadDocumentApplicant" class="btn btn-primary <?php if($picture_document_applicant == null): ?> visible <?php else: ?> hidden <?php endif; ?>" onclick="uploadPictureForm('DocumentApplicant')">
                                        <span class="glyphicon glyphicon-upload"></span> Subir Foto
                                    </button>
                                    <a class="<?php if($picture_document_applicant == null): ?> hidden <?php else: ?> visible <?php endif; ?>" id="deletePictureDocumentApplicant" href="#" onclick="deletePictureForm('DocumentApplicant','<?php echo e($customer->id); ?>','<?php echo e($sales_id); ?>')">
                                        <img src="<?php echo e(asset('/images/menos.png')); ?>" style="width:20px;height:20px">
                                    </a>  
                                </center>
                            </form>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Fecha de Caducidad</label>
                            <input type="date" class="form-control" style="line-height:14px;">
                            <br>
                            <form method="post" id="upload_formDocumentSpouse" name="upload_formDocumentSpouse" enctype="multipart/form-data" onsubmit="uploadPictureForm('upload_formDocumentSpouse'">
                                <?php echo e(csrf_field()); ?>                                
                                <input type="hidden" id="documentId" name="documentId" value="<?php echo e($customer->id); ?>">
                                <input type="hidden" id="saleId" name="saleId" value="<?php echo e($sales_id); ?>">
                                <input type="hidden" id="uploadType" name="uploadType" value="DocumentSpouse">
                                <input type="hidden" id="uploadedFileDocumentSpouse" name="uploadedFileDocumentSpouse" value="<?php echo e($picture_document_spouse); ?>">
                                <center>
                                    <label>Copia del documento de identidad del cónyuge o conviviente legal del contratante</label>
                                    <div class="alert" id="messageDocumentSpouse" style="display: none"></div>
                                    <div style="width:100px !important;padding: 0" class="inside" id="fileNameDocumentSpouse"></div>
                                    <div class="inputWrapper"><span id="uploaded_imageDocumentSpouse"><?php echo $picture_document_spouse; ?></span>
                                        <center>
                                            <img src="<?php echo e(asset('images/mas.png')); ?>" alt="Girl in a jacket" style="width:20px;height:20px;margin-bottom: -100px;">
                                        </center>
                                        <input class="fileInput" type="file" name="select_fileDocumentSpouse" onchange="fileNameFunction('DocumentSpouse')" id="select_fileDocumentSpouse">
                                    </div>
                                </center>
                                <center>
                                    <button type="submit" name="upload" id="uploadDocumentSpouse" class="btn btn-primary <?php if($picture_document_spouse == null): ?> visible <?php else: ?> hidden <?php endif; ?>" onclick="uploadPictureForm('DocumentSpouse')">
                                        <span class="glyphicon glyphicon-upload"></span> Subir Foto
                                    </button>
                                    <a class="<?php if($picture_document_spouse == null): ?> hidden <?php else: ?> visible <?php endif; ?>" id="deletePictureDocumentSpouse" href="#" onclick="deletePictureForm('DocumentSpouse','<?php echo e($customer->id); ?>','<?php echo e($sales_id); ?>')">
                                        <img src="<?php echo e(asset('/images/menos.png')); ?>" style="width:20px;height:20px">
                                    </a>  
                                </center>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-6 form-group">
                            <form method="post" id="upload_formService" name="upload_formService" enctype="multipart/form-data" onsubmit="uploadPictureForm('upload_formService'">
                                <?php echo e(csrf_field()); ?>                                
                                <input type="hidden" id="documentId" name="documentId" value="<?php echo e($customer->id); ?>">
                                <input type="hidden" id="saleId" name="saleId" value="<?php echo e($sales_id); ?>">
                                <input type="hidden" id="uploadType" name="uploadType" value="Service">
                                <input type="hidden" id="uploadedFilePictureService" name="uploadedFilePictureService" value="<?php echo e($picture_service); ?>">
                                <center>
                                    <label>Copia de una planilla de servicios basicos</label>
                                    <div class="alert" id="messageService" style="display: none"></div>
                                    <div style="width:100px !important;padding: 0" class="inside" id="fileNameService"></div>
                                    <div class="inputWrapper"><span id="uploaded_imageService"><?php echo $picture_service; ?></span>
                                        <center>
                                            <img src="<?php echo e(asset('images/mas.png')); ?>" alt="Girl in a jacket" style="width:20px;height:20px;margin-bottom: -100px;">
                                        </center>
                                        <input class="fileInput" type="file" name="select_fileService" onchange="fileNameFunction('Service')" id="select_fileService">
                                    </div>
                                </center>
                                <center>
                                    <button type="submit" name="upload" id="uploadService" class="btn btn-primary <?php if($picture_service == null): ?> visible <?php else: ?> hidden <?php endif; ?>" onclick="uploadPictureForm('Service')">
                                        <span class="glyphicon glyphicon-upload"></span> Subir Foto
                                    </button>
                                    <a class="<?php if($picture_service == null): ?> hidden <?php else: ?> visible <?php endif; ?>" id="deletePictureService" href="#" onclick="deletePictureForm('Service','<?php echo e($customer->id); ?>','<?php echo e($sales_id); ?>')">
                                        <img src="<?php echo e(asset('/images/menos.png')); ?>" style="width:20px;height:20px">
                                    </a>  
                                </center>
                            </form>
                        </div>
                        <div class="col-md-6 form-group">
                            <form method="post" id="upload_formService" name="upload_formService" enctype="multipart/form-data" onsubmit="uploadPictureForm('upload_formService'">
                                <?php echo e(csrf_field()); ?>                                
                                <input type="hidden" id="documentId" name="documentId" value="<?php echo e($customer->id); ?>">
                                <input type="hidden" id="saleId" name="saleId" value="<?php echo e($sales_id); ?>">
                                <input type="hidden" id="uploadType" name="uploadType" value="Service">
                                <input type="hidden" id="uploadedFilePictureService" name="uploadedFilePictureService" value="<?php echo e($picture_service); ?>">
                                <center>
                                    <label>Confirmación del pago del impuesto a la renta del año inmediato anterior o constancia de la información publicada por el Servicio de Rentas Internas (SRI) a través de la página web, de ser aplicable.</label>
                                    <div class="alert" id="messageService" style="display: none"></div>
                                    <div style="width:100px !important;padding: 0" class="inside" id="fileNameService"></div>
                                    <div class="inputWrapper"><span id="uploaded_imageService"><?php echo $picture_service; ?></span>
                                        <center>
                                            <img src="<?php echo e(asset('images/mas.png')); ?>" alt="Girl in a jacket" style="width:20px;height:20px;margin-bottom: -100px;">
                                        </center>
                                        <input class="fileInput" type="file" name="select_fileService" onchange="fileNameFunction('Service')" id="select_fileService">
                                    </div>
                                </center>
                                <center>
                                    <button type="submit" name="upload" id="uploadService" class="btn btn-primary <?php if($picture_service == null): ?> visible <?php else: ?> hidden <?php endif; ?>" onclick="uploadPictureForm('Service')">
                                        <span class="glyphicon glyphicon-upload"></span> Subir Foto
                                    </button>
                                    <a class="<?php if($picture_service == null): ?> hidden <?php else: ?> visible <?php endif; ?>" id="deletePictureService" href="#" onclick="deletePictureForm('Service','<?php echo e($customer->id); ?>','<?php echo e($sales_id); ?>')">
                                        <img src="<?php echo e(asset('/images/menos.png')); ?>" style="width:20px;height:20px">
                                    </a>  
                                </center>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="col-md-12" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                <div class="row" style="float:left">
                    <!--<a class="btn btn-default registerForm" align="right" href="<?php echo e(asset('/sales')); ?>" style="margin-left: -30px;"> Cancelar </a>-->
                </div>
                <div class="row" style="float:right">
                    <a id="fourthStepBtnBack" class="btn btn-back registerForm" align="right" href="#"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                    <a id="fourthStepBtnNext" class="btn btn-info registerForm" align="right" href="#"> Siguiente <span class="glyphicon glyphicon-step-forward"></span></a>
                </div>
            </div>
            <!--</form>-->
        </div>
        <div id="fifthStep" class="col-md-12 hidden" style="margin-top:20px">
            <div class="col-xs-12 col-md-12 border">
                <div id="fifthStepAlert" class="alert hidden">
                    <center> <strong>Se ha completado el Formulario de Vinculación, su asesor de venta pronto se pondra en contacto con usted.</strong></center>
                </div>
                <div class="checkbox">
                    <!--<label><input type="checkbox" id="fifthStepChk" name="fifthStepChk" value="">Certifico que los datos ingresados son verdaderos.</label>-->
                </div>
                <div class="col-md-12" style="margin-top:-25px;">
                    <div class="form-group">
                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="tokenCode"> Token</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                        <input type="text2" id="tokenCode" name="tokenCode" class="form-control registerForm" required tabindex="2" placeholder="Token" onchange="removeInputRedFocus(this.id)" value="">
                    </div>
                </div>
            </div>
            <div class="col-md-12" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                <div class="row" style="float:left">
                    <!--<a class="btn btn-default registerForm" align="right" href="<?php echo e(asset('/sales')); ?>" style="margin-left: -30px;"> Cancelar </a>-->
                </div>
                <div class="row" style="float:right">
                    <a id="fifthStepBtnBack" class="btn btn-back registerForm" align="right" href="#"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                    <a id="fifthStepBtnNext" class="btn btn-info registerForm" align="right" href="#"> Validar </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.remote_app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views\vinculation\create.blade.php ENDPATH**/ ?>