

<?php $__env->startSection('content'); ?>
<script src="<?php echo e(assets('js/registerCustom.js')); ?>"></script>
<script src="<?php echo e(assets('js/account/create.js')); ?>"></script>
<link href="<?php echo e(assets('css/sales/create.css')); ?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo e(assets('css/sales/index.css')); ?>" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCeMH-_P38-MIn5g635MFt6gGQIoNhDbjI&libraries=geometry,drawing,places" async defer></script>
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
    <div class="col-md-8 col-md-offset-2 border">
        <div class="row">
            <div class="col-xs-12 registerForm" style="margin:12px;">
                <center>
                    <h4 style="font-weight:bold">Apertura de Cuenta</h4>
                </center>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-3 wizard_inicial"><div id="firstStepWizard" class="wizard_activo registerForm">Cliente</div></div>
            <div class="col-md-3 col-sm-3 wizard_medio"><div id="secondStepWizard" class="wizard_inactivo registerForm">Geolocalización</div></div>
            <div class="col-md-3 col-sm-3 wizard_medio"><div id="thirdStepWizard" class="wizard_inactivo registerForm">Anexos</div></div>
            <div class="col-md-3 col-sm-3 wizard_final"><div id="fourthStepWizard" class="wizard_inactivo registerForm">Validación</div></div>
        </div>
        <?php if( Session::has('excelError') ): ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <ul class="list-group">
                <?php $__currentLoopData = $errors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error['msg']); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul> 
        </div>
        <?php endif; ?>
        <!--<form action="<?php echo e(route('accountStore')); ?>" method="POST" enctype="multipart/form-data" id="uploadForm">-->
        <?php echo e(csrf_field()); ?>

        <div id="firstStep">
            <div class="" style="margin-top:20px">
                <div id="customerForm"  class="col-md-12">
                    <div id="customerAlert" class="alert alert-danger hidden">
                        <center><strong>¡Alerta!</strong> Revise los campos </center>
                    </div>
                    <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;margin-bottom: 5px">
                        <div class="wizard_activo registerForm titleDiv" onclick="fadeToggle('personalDiv')">
                            <a href="#" class="titleLink">Información del Cliente</a>
                            <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                        </div>
                        <div id="personalDiv" class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group form-inline">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Cédula</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <div class="form-inline">
                                        <input autocomplete="off" type="text" class="form-control registerForm" name="document" id="document" placeholder="Cédula" required="required"tabindex="1" style="width:85%">
                                        <button type="button" class="btn btn-info" id="documentBtn" onclick="documentBtn()" tabindex="2"><span class="glyphicon glyphicon-search"></span></button>
                                        <div id="suggesstion-box"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="first_name"> Primer Nombre</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="first_name" id="first_name" placeholder="Primer Nombre" value="" required="required" tabindex="4" disabled="disabled" onchange="removeInputRedFocus(this.id)">
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="last_name"> Primer Apellido</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="last_name" id="last_name" placeholder="Primer Apellido" value="" required tabindex="5" disabled="disabled" onchange="removeInputRedFocus(this.id)">
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Pais de Nacimiento</label> <span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select class="form-control registerForm" id="birth_country" name="birth_country" tabindex="7"  onchange="removeInputRedFocus(this.id)">
                                        <option value="">--Escoja Una--</option>
                                        <?php $__currentLoopData = $birthCountry; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cou): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($cou->id); ?>"><?php echo e($cou->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="first_name"> Fecha de Nacimiento</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input class="form-control registerForm" type="date" name="birthdate" id="birthdate" style="line-height:14px" tabindex="6"  onchange="removeInputRedFocus(this.id)">
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="first_name"> Genero</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select class="form-control registerForm" id="gender" name="gender" tabindex="8"  onchange="removeInputRedFocus(this.id)">
                                        <option value="">--Escoja Una--</option>
                                        <?php $__currentLoopData = $genders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gender): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($gender->id); ?>"><?php echo e($gender->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="city"> Profesión</label> <span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="profession" id="profession" placeholder="Profesión" value="" required tabindex="10" onchange="removeInputRedFocus(this.id)">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document_id"> Tipo Documento</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select id="document_id" name="document_id" class="form-control registerForm" value="" required tabindex="3" disabled="disabled" onchange="removeInputRedFocus(this.id)">
                                        <option selected="true" value="0">--Escoja Una---</option>
                                        <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($document->id); ?>"><?php echo e($document->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="first_name"> Segundo Nombre</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="second_name" id="second_name" placeholder="Segundo Nombre" value="" required="required" tabindex="4" disabled="disabled" onchange="removeInputRedFocus(this.id)">
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="last_name"> Segundo Apellido</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="second_last_name" id="second_last_name" placeholder="Segundo Apellido" value="" required tabindex="5" disabled="disabled" onchange="removeInputRedFocus(this.id)">
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Ciudad de Nacimiento</label> <span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select class="form-control registerForm" id="birth_city" name="birth_city" tabindex="7"  onchange="removeInputRedFocus(this.id)">
                                        <option>--Escoja Una--</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Nacionalidad </label> <span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select class="form-control registerForm" id="nacionality" name="nacionality" tabindex="9"  onchange="removeInputRedFocus(this.id)">
                                        <option value="">--Escoja Una--</option>
                                        <?php $__currentLoopData = $nacionalities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nac): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($nac->id); ?>"><?php echo e($nac->name); ?></option>                                    
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Estado Civil </label> <span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select class="form-control registerForm" id="civil_state" name="civil_state" tabindex="11"  onchange="removeInputRedFocus(this.id)">
                                        <option value="">--Escoja Una--</option>
                                        <?php $__currentLoopData = $civilStates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($state->id); ?>"><?php echo e($state->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Actividad que Desarrolla </label> <span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="activity" id="activity" placeholder="Actividad que desarrolla" value="" required tabindex="13" onchange="removeInputRedFocus(this.id)">
                                </div>
                            </div>
                            <div class="col-md-6" style="margin-top:-15px">
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="city"> Celular</label> <span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span><label class="own"><span class="glyphicon glyphicon-info-sign" style="color:#0099ff"><span class="own1" style="float:left">El telefono debe contener 10 caracteres</span></span></label>
                                    <input type="text" class="form-control registerForm" name="mobile_phone" id="mobile_phone" placeholder="Celular" value="<?php echo e(old('mobile_phone')); ?>" required tabindex="14" onchange="removeInputRedFocus(this.id)">
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="country"> Pais</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select name="country" id="country" class="form-control registerForm" required tabindex="16" onchange="removeInputRedFocus(this.id)">
                                        <option selected="true" value="0">--Escoja Una---</option>
                                        <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($country->id); ?>"><?php echo e($country->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="city"> Ciudad</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select name="city" class="form-control registerForm" id="city" required tabindex="18"  onchange="removeInputRedFocus(this.id)">
                                        <option id="citySelect" selected="true" disabled="disabled" value="0">--Escoja Una---</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="city"> Envio de Correspondencia</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select name="correspondence" class="form-control registerForm" id="correspondence" required tabindex="20" onchange="removeInputRedFocus(this.id)">
                                        <option selected="true" disabled="disabled" value="0">--Escoja Una---</option>
                                        <?php $__currentLoopData = $correspondences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($cor->id); ?>"><?php echo e($cor->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6" style="margin-top:-15px">
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Teléfono </label> <span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span><label class="own"><span class="glyphicon glyphicon-info-sign" style="color:#0099ff"><span class="own1" style="float:left">El telefono debe contener 9 caracteres</span></span></label>
                                    <input type="text" class="form-control registerForm" name="phone" id="phone" placeholder="Teléfono" value="<?php echo e(old('phone')); ?>" required tabindex="15" onchange="removeInputRedFocus(this.id)">
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="province"> Canton</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select name="province" class="form-control registerForm" id="province" required tabindex="17" onchange="removeInputRedFocus(this.id)">
                                        <option id="provinceSelect" selected="true" disabled="disabled" value="0">--Escoja Una---</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="correo"> Correo</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="email" class="form-control registerForm" name="email" id="email" placeholder="Correo" value="<?php echo e(old('email')); ?>" required tabindex="19" onchange="removeInputRedFocus(this.id)">
                                    <p id="emailError" style="color:red;font-weight: bold"></p>    
                                    <?php if($errors->any()): ?>
                                    <span style="color:red;font-weight:bold"><?php echo e($errors->first()); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-12" style="margin-top:-15px">
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="city"> Direccion Domicilio</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="address" id="address" placeholder="Direccion Domicilio" required tabindex="15" onchange="removeInputRedFocus(this.id)">
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="city"> Direccion Trabajo</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="work_address" id="work_address" placeholder="Direccion Trabajo" required tabindex="17" onchange="removeInputRedFocus(this.id)">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id=""  class="col-md-12">
                    <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;margin-bottom: 5px">
                        <div class="wizard_activo registerForm titleDiv" onclick="fadeToggle('representativeForm')">
                            <a href="#" class="titleLink">Datos del Conyuge/Representante</a>
                            <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                        </div>
                        <div id="representativeForm" class="col-md-12">
                            <div id="representative_error" class="alert alert-danger hidden" align="center">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group form-inline">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Cédula</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <div class="form-inline">
                                            <input autocomplete="off" type="text" class="form-control registerForm" name="document_representative" id="document_representative" placeholder="Cédula" required="required" tabindex="21" style="width:85%">
                                            <button type="button" class="btn btn-info" id="documentRepresentativeBtn" onclick="documentRepresentativeBtn()" tabindex="22"><span class="glyphicon glyphicon-search"></span></button>
                                            <div id="suggesstion-box"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="first_name"> Nombre(s)</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" class="form-control registerForm" name="first_name_representative" id="first_name_representative" placeholder="Nombre" value="<?php echo e(old('first_name')); ?>" required="required" tabindex="24" disabled="disabled" onchange="removeInputRedFocus(this.id)">
                                    </div>
                                    <div class="form-group">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="first_name"> Fecha de Nacimiento</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input class="form-control registerForm" type="date" name="birthdate_representative" id="birthdate_representative" style="line-height:14px" onchange="removeInputRedFocus(this.id)" tabindex="26">
                                    </div>
                                    <div class="form-group">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document_id"> Relacion con el Titular</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <select id="relationship_representative" name="relationship_representative" class="form-control registerForm" value="<?php echo e(old('document_id')); ?>" required tabindex="28" onchange="removeInputRedFocus(this.id)" >
                                            <option selected="true" value="">--Escoja Una---</option>
                                            <?php $__currentLoopData = $relationships; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($rel->id); ?>"><?php echo e($rel->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document_id"> Tipo Documento</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <select id="document_id_representative" name="document_id_representative" class="form-control registerForm" value="<?php echo e(old('document_id')); ?>" required tabindex="23" disabled="disabled" onchange="removeInputRedFocus(this.id)">
                                            <option selected="true" value="">--Escoja Una---</option>
                                            <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($document->id); ?>"><?php echo e($document->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="last_name"> Apellido(s)</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" class="form-control registerForm" name="last_name_representative" id="last_name_representative" placeholder="Apellido" value="<?php echo e(old('last_name')); ?>" required tabindex="25" disabled="disabled" onchange="removeInputRedFocus(this.id)">
                                    </div>
                                    <div class="form-group">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Nacionalidad </label> <span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span><label class="own"><span class="glyphicon glyphicon-info-sign" style="color:#0099ff"><span class="own1" style="float:left">La contraseña debe tener: <br> 1) Un Numero <br> 2) Una Letra <br> 3) Un caracter Especial <br> 4) Debe tener al menos 7 caracteres</p></span></span></label>
                                        <select class="form-control registerForm" id="nationality_representative" name="nationality_representative" onchange="removeInputRedFocus(this.id)" tabindex="27">
                                            <option value="">--Escoja Una--</option>
                                            <?php $__currentLoopData = $nacionalities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nac): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($nac->id); ?>"><?php echo e($nac->name); ?></option>                                    
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="first_name"> Genero</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <select class="form-control registerForm" id="gender_representative" name="gender_representative" onchange="removeInputRedFocus(this.id)" tabindex="29">
                                            <option value="">--Escoja Una--</option>
                                            <?php $__currentLoopData = $genders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gender): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($gender->id); ?>"><?php echo e($gender->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id=""  class="col-md-12">
                    <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;margin-bottom: 5px">
                        <div class="wizard_activo registerForm titleDiv" onclick="fadeToggle('economicInformationForm')">
                            <a href="#" class="titleLink">Información Financiera</a>
                            <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                        </div>
                        <div id="economicInformationForm" class="col-md-12">
                            <div id="representative_error" class="alert alert-danger hidden" align="center">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document_id"> Actividad Economica Principal</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <select id="relationship_representative" name="relationship_representative" class="form-control registerForm" required tabindex="28" onchange="removeInputRedFocus(this.id)" >
                                            <option selected="true" value="">--Escoja Una---</option>
                                            <?php $__currentLoopData = $economicActivity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eco): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($eco->id); ?>"><?php echo e($eco->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div> 
                                    <div class="form-group">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Fuente de Ingresos</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <textarea class="form-control" rows="5" id="" placeholder="Fuente de Ingresos"></textarea>
                                    </div> 
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document_id"> Fuente de Ingresos</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <select id="document_id_representative" name="document_id_representative" class="form-control registerForm" required tabindex="23" onchange="removeInputRedFocus(this.id)">
                                            <option selected="true" value="">--Escoja Una---</option>
                                            <?php $__currentLoopData = $incomeSource; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($inc->id); ?>"><?php echo e($inc->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Descripción de Gastos Generales</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <textarea class="form-control" rows="5" id="" placeholder="Descripción de Gastos Generales"></textarea>
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id=""  class="col-md-12">
                    <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;margin-bottom: 5px">
                        <div class="wizard_activo registerForm titleDiv" onclick="fadeToggle('assetsInformationForm')">
                            <a href="#" class="titleLink">Descripción de Activos</a>
                            <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                        </div>
                        <div id="assetsInformationForm" class="col-md-12">
                            <div id="representative_error" class="alert alert-danger hidden" align="center">
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="button" value="Agregar" onclick="addAssets()">
                                        <input type="button" value="Remover" onclick="removeAssets()">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document_id"> Actividad Economica Principal</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <select id="assetId_1" name="assetId" class="form-control registerForm" required tabindex="28" onchange="removeInputRedFocus(this.id)" >
                                            <option selected="true" value="">--Escoja Una---</option>
                                            <?php $__currentLoopData = $assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ass): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($ass->id); ?>"><?php echo e($ass->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div id="assetIdDiv">
                                        
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                      <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Valor</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                      <input type="text" class="form-control" id="" name="assetValue" placeholder="Valor">
                                    </div>
                                    <div id="assetValueDiv">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id=""  class="col-md-12">
                    <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;margin-bottom: 5px">
                        <div class="wizard_activo registerForm titleDiv" onclick="fadeToggle('passivesInformationForm')">
                            <a href="#" class="titleLink">Descripción de Pasivos</a>
                            <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                        </div>
                        <div id="passivesInformationForm" class="col-md-12">
                            <div id="representative_error" class="alert alert-danger hidden" align="center">
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="button" value="Agregar" onclick="addPassives()">
                                        <input type="button" value="Remover" onclick="removePassives()">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document_id"> Actividad Economica Principal</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <select id="assetId_1" name="passiveId" class="form-control registerForm" required tabindex="28" onchange="removeInputRedFocus(this.id)" >
                                            <option selected="true" value="">--Escoja Una---</option>
                                            <?php $__currentLoopData = $passives; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($pas->id); ?>"><?php echo e($pas->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div id="passiveIdDiv">
                                        
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                      <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Valor</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                      <input type="text" class="form-control" id="usr" placeholder="Valor">
                                    </div>
                                    <div id="passiveValueDiv">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                <a class="btn btn-default registerForm" align="left" href="<?php echo e(asset('/account')); ?>" style="margin-left:-15px" tabindex="30"> Cancelar </a>
                <input id="firstStepBtnNext" type="button" style="float:right;padding: 5px;width:75px;margin-right: -15px" class="btn btn-info registerForm" align="right" value="Siguiente" tabindex="31">
                <!--<input id="" type="submit" style="float:right;padding: 5px;width:75px;margin-right: -15px" class="btn btn-info registerForm" align="right" value="Siguiente">-->
            </div>
        </div>
        <div id ="secondStep" class="hidden">
            <div class="col-md-12 border" style="margin-top:20px">
                <div class="col-md-12">
                    <div id="DIVMapa" style="border:1px solid #DDD; border-radius:6px; margin:auto 25px; height:300px;"></div>
                </div>
                <input type="hidden" id="GLLng" name="GLLng">
                <input type="hidden" id="GLLat" name="GLLat">
            </div>
            <div class="col-md-12" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                <input id="secondStepBtnBack" type="button" style="float:left;padding: 5px;width:75px;margin-left: -15px" class="btn btn-default registerForm" align="right" value="Anterior">
                <input id="secondStepBtnNext" type="button" style="float:right;padding: 5px;width:75px;margin-right: -15px" class="btn btn-info registerForm" align="right" value="Siguiente">
            </div>
        </div>
        <div id="thirdStep" class="hidden">
            <div class="col-md-12 border" style="margin-top:20px">
                <div class="col-md-4 border">
                    <form method="post" id="upload_formFront" name="upload_formFront" enctype="multipart/form-data" onsubmit="uploadPictureForm('upload_formFront', 'Front')">
                        <?php echo e(csrf_field()); ?>

                        <center><h5>Cédula Frente</h5></center>
                        <input type="hidden" name="document" id="customerDocumentFront" value="" />
                        <input type="hidden" name="side" id="side" value="Front" />
                        <input type="hidden" name="pictureNameFront" id="pictureNameFront" value="" />
                        <div class="alert hidden" id="messageFront" style="font-weight: bold;color:red">Debe seleccionar una Imagen</div>
                        <center>
                            <div style="width:100px !important;padding: 0" class="inside" id="fileNameFront"></div>
                            <span id="uploaded_imageFront"></span>
                            <div class="inputWrapper">
                                <img id="imageMasFront" src="/images/mas.png" alt="Girl in a jacket" style="width:20px;height:20px;margin-bottom: -100px;">
                                <input class="fileInput" type="file" name="select_fileFront" onchange="fileNameFunction('Front')" id="select_fileFront">
                            </div>
                            <a class="hidden" id="deletePictureFront" href="#" onclick="deletePictureForm('Front')"><img src="/images/menos.png" style="width:20px;height:20px"></a>
                            <button type="submit" name="upload" id="uploadFront" class="btn btn-primary" onclick="uploadPictureForm('upload_formFront', 'Front')"><span class="glyphicon glyphicon-upload"></span>&ensp;Subir Foto</button>
                        </center>
                    </form>
                </div>
                <div class="col-md-4 border">
                    <form method="post" id="upload_formBack" name="upload_formBack" enctype="multipart/form-data" onsubmit="uploadPictureForm('upload_formBack', 'Back')">
                        <?php echo e(csrf_field()); ?>

                        <center><h5>Cédula Reverso</h5></center>
                        <input type="hidden" name="document" id="customerDocumentBack" value="" />
                        <input type="hidden" name="side" id="side" value="Back" />
                        <input type="hidden" name="pictureNameBack" id="pictureNameBack" value="" />
                        <div class="alert hidden" id="messageBack" style="font-weight: bold;color:red">Debe seleccionar una Imagens</div>
                        <center>
                            <div style="width:100px !important;padding: 0" class="inside" id="fileNameBack"></div>
                            <span id="uploaded_imageBack"></span>
                            <div class="inputWrapper">
                                <img id="imageMasBack" src="/images/mas.png" alt="Girl in a jacket" style="width:20px;height:20px;margin-bottom: -100px;">
                                <input class="fileInput" type="file" name="select_fileBack" onchange="fileNameFunction('Back')" id="select_fileBack">
                            </div>
                            <a class="hidden" id="deletePictureBack" href="#" onclick="deletePictureForm('Back')"><img src="/images/menos.png" style="width:20px;height:20px"></a>
                            <button type="submit" name="upload" id="uploadBack" class="btn btn-primary" onclick="uploadPictureForm('upload_formBack', 'Back')"><span class="glyphicon glyphicon-upload"></span>&ensp;Subir Foto</button>
                        </center>
                    </form>
                </div>
                <div class="col-md-4 border">
                    <form method="post" id="upload_formLocal" name="upload_formLocal" enctype="multipart/form-data" onsubmit="uploadPictureForm('upload_formLocal', 'front')">
                        <?php echo e(csrf_field()); ?>

                        <center><h5>Local Frente</h5></center>
                        <input type="hidden" name="document" id="customerDocumentLocal" value="" />
                        <input type="hidden" name="side" id="side" value="Local" />
                        <input type="hidden" name="pictureNameLocal" id="pictureNameLocal" value="" />
                        <div class="alert hidden" id="messageLocal" style="font-weight: bold;color:red">Debe seleccionar una Imagen</div>
                        <center>
                            <div style="width:100px !important;padding: 0" class="inside" id="fileNameLocal"></div>
                            <span id="uploaded_imageLocal"></span>
                            <div class="inputWrapper">
                                <img id="imageMasLocal" src="/images/mas.png" alt="Girl in a jacket" style="width:20px;height:20px;margin-bottom: -100px;">
                                <input class="fileInput" type="file" name="select_fileLocal" onchange="fileNameFunction('Local')" id="select_fileLocal">
                            </div>
                            <a class="hidden" id="deletePictureLocal" href="#" onclick="deletePictureForm('Local')"><img src="/images/menos.png" style="width:20px;height:20px"></a>
                            <button type="submit" name="upload" id="uploadLocal" class="btn btn-primary" onclick="uploadPictureForm('upload_formLocal', 'Local')"><span class="glyphicon glyphicon-upload"></span>&ensp;Subir Foto</button>
                        </center>
                    </form>
                </div>
            </div>
            <div class="col-md-12" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                <input id="thirdStepBtnBack" type="button" style="float:left;padding: 5px;width:75px;margin-left: -15px" class="btn btn-default registerForm" align="right" value="Anterior">
                <input id="thirdStepBtnNext" type="button" style="float:right;padding: 5px;width:75px;margin-right: -15px" class="btn btn-info registerForm" align="right" value="Siguiente">
            </div>
        </div>
        <div id="fourthStep" class="hidden">
            <div class="col-md-12 border" style="margin-top:20px">
                <span class="">
                    <div id="resultMessage" class="">
                    </div>
                    <div id="validationCode">
                        <input type="hidden" name="accountId" id="accountId" value="">
                    </div>
                    <div class="form-group">
                        <label for="code">Ingrese el codigo</label>
                        <input type="text" class="form-control" name="code" id="code" placeholder="Ingrese el codigo"><br>
                        <button id="resendCodeBtn" type="submit" class="btn btn-success" style="float:right;margin-bottom: 10px" onclick="resendCode()">Reenviar Codigo</button>
                    </div>
                </span>
            </div>
            <div class="col-md-12" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                <input id="fourthStepBtnBack" type="button" style="float:left;padding: 5px;width:75px;margin-left: -15px" class="btn btn-default registerForm" align="right" value="Cancelar">
                <input id="fourthStepBtnNext" type="button" style="float:right;padding: 5px;width:75px;margin-right: -15px" class="btn btn-info registerForm" align="right" value="Validar">
            </div>
        </div>
        <!--</form>-->
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views\account\create.blade.php ENDPATH**/ ?>