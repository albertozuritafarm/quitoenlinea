

<?php $__env->startSection('content'); ?>
<script src="<?php echo e(assets('js/registerCustom.js')); ?>"></script>
<script src="<?php echo e(assets('js/vinculation/pj/beneficiarios_juridica.js')); ?>"></script>
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
<?php if($civil_state == 2 || $civil_state == 5): ?>
<script>
    $(document).ready(function () {
    var div = document.getElementById('spouseFullDiv');
    $(div).fadeIn();
    var div = document.getElementById('formDocumentSpouse');
    $(div).fadeIn();
    });</script>
<?php else: ?>
<script>
    $(document).ready(function () {
    var div = document.getElementById('spouseFullDiv');
    $(div).fadeOut();
    var div = document.getElementById('formDocumentSpouse');
    $(div).fadeOut();
    });</script>
<?php endif; ?>

<?php if($beneficiaryName == null): ?>
<script>
    $(document).ready(function () {
    var div = document.getElementById('beneficiaryDataDiv');
    $(div).fadeOut();
    div = document.getElementById('representanteDiv');
    $(div).fadeOut();
    div = document.getElementById('conyugueDiv');
    $(div).fadeOut();
    
    });
</script>
<?php endif; ?>
<style>
    .form-row{
        margin-top: 15px;
        margin-bottom: 15px;
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
    .glyphicon.glyphicon-asterisk{
        font-size:12px;
    }
    #economic_activity{
        width:89%;
    }
    td#tipo_empresa{
        font-size:12px;
        color:#003b71;
        padding:0px;
        background:white;
    }
    .move-left {
        width: auto;
        box-shadow: none;
        
    }
    #referenceTable td {
        border: 0.5px ridge rgb(204, 204, 204);
        text-align: center;
        font-size :10px !important;
        background:white;
    }
    #referenceTable th {
        border: 0.5px ridge rgb(204, 204, 204);
        text-align: center;
        
    }
    #referenceTable input{
        font-size :10px !important;
    }
    
    .row{
        margin-right:0px;
        margin-left:0px;

    }
   
  
    @media  only screen and (max-width: 600px) {
        .row{
            margin-top: 10px;
            margin-bottom: 0px;
        }
        .wizardActivo{
            height:55px;
        }
        #economic_activity{
            width:80%;
        }
    }
</style>
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
<div class="container" style="margin-top:15px; font-size:14px !important">
    <!--<div class="row justify-content-center border" style="margin-left:20%;">-->
    <div class="col-xs-12 col-md-10 col-md-offset-1 border">
        <div class="form-row">
            <div class="col-xs-12 registerForm" style="margin:12px;">
                <center>
                    <h4 style="font-weight:bold">Formulario de Vinculación</h4>
                    <!--<h5>Datos del Cliente.</h5>-->
                </center>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-3 wizard_inicial" style="padding-left:0px !important"><div id="firstStepWizard" class="wizard_activo registerForm">Información</div></div>
            <div class="col-xs-12 col-md-3 wizard_medio" style="padding-left:0px !important"><div id="secondStepWizard" class="wizard_inactivo registerForm">Actividad Económica</div></div>
            <div class="col-xs-12 col-md-3 wizard_medio" style="padding-left:0px !important"><div id="thirdStepWizard" class="wizard_inactivo registerForm">Declaración</div></div>
            <div class="col-xs-12 col-md-3 wizard_final" style="padding-right:0px !important"><div id="fourthStepWizard" class="wizard_inactivo registerForm">Documentación</div></div>
        </div>

        <div id="firstStep" class="" style="margin-top:30px">
            <form id="firstStepForm" name="firstStepForm" method="POST" action="<?php echo e(asset('/user')); ?>" id="salesForm">
                <?php echo e(csrf_field()); ?>

                <input type="hidden" id="documentId" name="documentId" value="">
                <input type="hidden" id="saleId" name="saleId" value="">
                <div id="productAlert" class="alert alert-danger hidden">
                    <strong>¡Alerta!</strong> Debe seleccionar un producto
                </div>
                <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;margin-bottom: 5px;margin-top:30px;">
                    <div class="wizard_activo registerForm titleDiv" onclick="fadeToggle('personalDiv')">
                        <a href="#" class="titleLink">Datos de la empresa (Beneficiario Final):</a>
                        <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                    </div>
                    <div id="personalDiv" class="col-md-12">
                        <?php if($customer == false): ?>
                        <input type="hidden" id="customerCheck" value="0">
                        <?php else: ?>
                        <input type="hidden" id="customerCheck" value="1">
                        <?php endif; ?>
                     <div class="">
                            <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="first_name"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>Nombre </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" id="first_name" name="first_name" class="form-control registerForm" required tabindex="1" placeholder="Nombre(s)" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="" maxlength="30">
                                    </div>
                                    <div class="form-group col-md-6">
                                    <label class="registerForm" for="spouse_document_id"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>Fecha de Constitución: </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="date" id="fecha_constitucion" name="fecha_constitucion" class="form-control registerForm" required tabindex="5" placeholder="fecha de constitución" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="" maxlength="30">
                                        <!--<label class="registerForm" for="document_id"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Tipo Documento</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <select id="document_id" name="document_id" class="form-control registerForm" required tabindex="5" onchange="removeInputRedFocus(this.id)" <?php if($customer != false): ?> disabled="disabled" <?php endif; ?> >
                                            <option value="">--Escoja Una---</option>
                                            <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($customer != false): ?>
                                            <?php if($customer->document_id == $doc->id): ?>
                                            <option value="<?php echo e($doc->id); ?>" selected="selected"><?php echo e($doc->name); ?></option>
                                            <?php else: ?>
                                            <?php endif; ?>
                                            <?php else: ?>
                                            <option value="<?php echo e($doc->id); ?>"><?php echo e($doc->name); ?></option>
                                            <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select> -->
                                    </div>
                                   
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="ruc"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>Ruc N°</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input  type="text" id="ruc" name="ruc" class="form-control registerForm" required tabindex="6" placeholder="Número de Identificación" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="">
                                    </div>
                                    <div class="form-group col-md-6">
                                    <label class="registerForm" for="objeto_social"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>Objeto Social:  </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" id="objeto_social" name="objeto_social" class="form-control registerForm" required tabindex="7" placeholder="fecha de constitución" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="" maxlength="30">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <div class="form-group col-md-6">
                                    <label class="registerForm" for="spouse_document_id"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>Ingresos brutos anuales de la compañía:  </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" id="ingresos_brutos" name="ingresos_brutos" class="form-control registerForm" required tabindex="9" placeholder="fecha de constitución" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="" maxlength="30">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="actividad_economica"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>Actividad Económica: </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <select id="economic_activity" name="economic_activity" class="form-control registerForm" required tabindex="1" onchange="removeInputRedFocus(this.id)" disabled="disabled" style="float:left;">
                                            <option value="">--Escoja Una---</option>
                                            <?php $__currentLoopData = $economicActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eco): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option <?php if($economic_activity == $eco->id): ?> selected="true" <?php else: ?> <?php endif; ?> value="<?php echo e($eco->id); ?>"><?php echo e($eco->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <button id="btnModalSearch" type="button" class="btn btn-info" <?php if($disable_status): ?> disabled="disabled" <?php else: ?> <?php endif; ?> data-toggle="modal" data-target="#myModal" style="float:right"><span class="glyphicon glyphicon-search"></span></button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="nationality"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> País de Nacimiento</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <?php if($nationality_id == null ): ?>
                                            <select id="nationality" name="nationality" class="form-control registerForm" required onchange="removeInputRedFocus(this.id)" tabindex="16" <?php echo e($disable_status); ?>>
                                        <?php else: ?>
                                            <input type="hidden" id="nationality" name="nationality" value="<?php echo e($nationality_id); ?>">
                                            <select class="form-control registerForm" required onchange="removeInputRedFocus(this.id)" disabled="disabled" tabindex="17">
                                        <?php endif; ?>
                                            <option value="">--Escoja Una--</option>
                                            <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cou): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option <?php if($cou->id == $nationality_id): ?> selected="true" <?php endif; ?> value="<?php echo e($cou->id); ?>"><?php echo e($cou->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="actividad_economica"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>Su empresa es: </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <table><tr>
                                        <td id="tipo_empresa">Sociedad Anónima</td><td id="tipo_empresa"> <input  type="checkbox"   id="sociedad_anonima" name="sociedad_anonima" value="Sociedad Anónima" class="form-control move-left" required tabindex="10" placeholder="Sociedad Anónima" disabled="disabled" onchange="removeInputRedFocus(this.id)" value=""></td><td></td><td></td><td></td><td></td>
                                        </tr> 
                                        <tr>
                                        <td id="tipo_empresa">Cía. Limitada</td><td id="tipo_empresa"> <input  type="checkbox"   id="cia_limitada" name="cia_limitada" value="Compania Limitada" class="form-control move-left" required tabindex="11" placeholder="Compania Limitada" disabled="disabled" onchange="removeInputRedFocus(this.id)" value=""></td><td id="tipo_empresa"></td><td id="tipo_empresa"></td><td id="tipo_empresa"></td><td id="tipo_empresa"></td>
                                        </tr>
                                        <tr>
                                        <td id="tipo_empresa">Sociedad de Hecho</td><td id="tipo_empresa"> <input  type="checkbox"   id="sociedad_hecho" name="sociedad_hecho" value="Sociedad Hecho" class="form-control move-left" required tabindex="12" placeholder="Sociedad Hecho" disabled="disabled" onchange="removeInputRedFocus(this.id)" value=""></td><td></td><td></td><td></td><td></td>
                                        </tr>
                                        <tr>
                                        <td id="tipo_empresa">Pública</td><td id="tipo_empresa"> <input  type="checkbox"   id="publica" name="publica" value="Empresa Pública" class="form-control move-left" required tabindex="13" placeholder="Empresa Pública" disabled="disabled" onchange="removeInputRedFocus(this.id)" value=""></td><td id="tipo_empresa"></td><td id="tipo_empresa"></td><td id="tipo_empresa"></td><td id="tipo_empresa"></td>
                                        </tr>
                                        <tr>
                                        <td id="tipo_empresa">Privada</td><td id="tipo_empresa"> <input  type="checkbox"   id="privada" name="privada" value="Empresa Privada" class="form-control move-left" required tabindex="14" placeholder="Empresa Privada" disabled="disabled" onchange="removeInputRedFocus(this.id)" value=""></td><td></td><td></td><td></td><td></td>
                                        </tr>
                                        <tr>
                                        <td id="tipo_empresa">ONG's</td><td id="tipo_empresa"> <input  type="checkbox"   id="ong" name="ong" value="ONG's" class="form-control move-left" required tabindex="15" placeholder="ONG" disabled="disabled" onchange="removeInputRedFocus(this.id)" value=""></td><td id="tipo_empresa"></td><td id="tipo_empresa"></td><td id="tipo_empresa"></td><td id="tipo_empresa"></td><td id="tipo_empresa"></td>
                                        </tr></table>

                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="birth_date"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Fecha de Nacimiento</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="date" id="birth_date" name="birth_date" class="form-control registerForm" style="line-height: 15px !important" onchange="removeInputRedFocus(this.id)" value="<?php echo e($birth_date); ?>" <?php if($birth_date != null): ?> readonly="readonly" <?php endif; ?> tabindex="9" <?php echo e($disable_status); ?>>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="birth_city"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Ciudad de Nacimiento </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <?php if($birth_place == null): ?>
                                            <select id="birth_city" name="birth_city" class="form-control registerForm" required onchange="removeInputRedFocus(this.id)" tabindex="18" <?php echo e($disable_status); ?>>
                                        <?php else: ?>
                                            <input type="hidden" id="birth_city" name="birth_city" value="<?php echo e($birth_place); ?>">
                                            <select class="form-control registerForm" required onchange="removeInputRedFocus(this.id)" disabled="disabled" tabindex="19">
                                        <?php endif; ?>
                                            <option value="">--Escoja Una---</option>
                                            <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option <?php if($cit->id == $birth_place): ?> selected="true" <?php endif; ?> value="<?php echo e($cit->id); ?>"><?php echo e($cit->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <input type="hidden" id="birth_place" name="birth_place" value="<?php echo e($birth_place); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                <div class="form-group col-md-6">
                                        <label class="registerForm" for="country"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> País</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <select id="country" name="country" class="form-control registerForm" required onchange="removeInputRedFocus(this.id)" tabindex="11" <?php echo e($disable_status); ?>>
                                            <option value="">--Escoja Una---</option>
                                            <?php $__currentLoopData = $countriesResidence; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cou): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option <?php if($cou->id == $country_id): ?> selected="true" <?php endif; ?> value="<?php echo e($cou->id); ?>"><?php echo e($cou->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="civilState"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Estado Civil</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <select id="civilState" name="civilState" class="form-control registerForm" required tabindex="10" onchange="removeInputRedFocus(this.id)" <?php echo e($disable_status); ?>>
                                            <option value="">--Escoja Una---</option>
                                            <?php $__currentLoopData = $civilStates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option <?php if($sta->id == $civil_state): ?> selected <?php endif; ?> value="<?php echo e($sta->id); ?>"><?php echo e($sta->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                <div class="form-group col-md-6">
                                        <label class="registerForm" for="document_id"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Cantón</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <select id="canton" name="canton" class="form-control registerForm" required tabindex="13" onchange="removeInputRedFocus(this.id)" <?php echo e($disable_status); ?>>
                                            <option value="">--Escoja Una---</option>
                                            <?php if($addressCities != null): ?>
                                            <?php $__currentLoopData = $addressCities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option <?php if($cit->id == $city_id): ?> selected <?php endif; ?> value="<?php echo e($cit->id); ?>"><?php echo e($cit->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php else: ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="document_id"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Provincia  </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <select id="province" name="province" class="form-control registerForm" required tabindex="12" onchange="removeInputRedFocus(this.id)" <?php echo e($disable_status); ?>>
                                            <option selected="true" value="" disabled="disabled">--Escoja Una---</option>
                                            <?php $__currentLoopData = $provinces; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option <?php if($prov->id == $province_id): ?> selected <?php endif; ?> value="<?php echo e($prov->id); ?>"><?php echo e($prov->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                           
                            <div class="form-row">
                                <div class="form-group">
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="numero_calle"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> N°</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" id="numero_calle" name="numero_calle" class="form-control registerForm" required tabindex="15" placeholder="N°" maxlength="10" onchange="removeInputRedFocus(this.id)" value="<?php echo e($address_number); ?>" <?php echo e($disable_status); ?> maxlength="30">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="parroquia"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Parroquia</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" id="parroquia" name="parroquia" class="form-control registerForm" required tabindex="15" placeholder="N°" maxlength="10" onchange="removeInputRedFocus(this.id)" value="<?php echo e($address_number); ?>" <?php echo e($disable_status); ?> maxlength="30">
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                <div class="form-group col-md-6">
                                        <label class="registerForm" for="sector"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Sector</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" id="sector" name="sector" class="form-control registerForm" required tabindex="17" placeholder="Sector" maxlength="20" onchange="removeInputRedFocus(this.id)" value="" <?php echo e($disable_status); ?> maxlength="30">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="calle_principal"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Calle Principal</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text2" id="calle_principal" name="calle_principal" class="form-control registerForm" required tabindex="14" placeholder="Calle Principal" onchange="removeInputRedFocus(this.id)" value="<?php echo e($main_road); ?>" <?php echo e($disable_status); ?> maxlength="90">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="calle_transversal"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Calle Transversal</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" id="calle_transversal" name="calle_transversal" class="form-control registerForm" required tabindex="16" placeholder="Calle Transversal" onchange="removeInputRedFocus(this.id)" value="" <?php echo e($disable_status); ?> maxlength="50">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="phones"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Teléfonos</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" id="phones" name="phones" class="form-control registerForm" required tabindex="19" placeholder="Teléfonos" onchange="removeInputRedFocus(this.id)" value="<?php echo e($mobile_phone); ?>" <?php echo e($disable_status); ?> maxlength="10">
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="website"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Website</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" id="website" name="website" class="form-control registerForm" required tabindex="22" placeholder="Website" onchange="removeInputRedFocus(this.id)" value="" <?php echo e($disable_status); ?> maxlength="9">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="email"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Email</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="email" id="email" name="email" class="form-control registerForm" required tabindex="18" placeholder="Correo" onchange="removeInputRedFocus(this.id)" value="" <?php echo e($disable_status); ?> maxlength="100">
                                        <p id="emailError" style="color:red;font-weight: bold"></p>  
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                <div class="form-group col-md-6">
                                        <label class="registerForm" for="fax"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>Fax</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" id="fax" name="fax" class="form-control registerForm" required tabindex="20" placeholder="Teléfono" onchange="removeInputRedFocus(this.id)" value="" <?php echo e($disable_status); ?> maxlength="9">
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
              
                
                <div id="spouseFullDiv" class="col-xs-12 col-md-12 border" style="padding-left:0px !important;margin-bottom: 5px">
                    <div class="wizard_activo registerForm titleDiv " onclick="fadeToggle('spouseDiv')">
                        <a href="#" class="titleLink">Datos del Cónyuge o Conviviente</a>
                        <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                    </div>
                    <div id="spouseDiv" class="col-md-12">
                        <div class="">
                            <div class="form-row">
                                <div class="form-group">
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="passportBeginDate"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Documento de Identidad Cónyuge</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" id="spouseDocument" name="spouseDocument" class="form-control registerForm" required tabindex="25"  style="line-height: 15px !important;width:96%" value="<?php echo e($spouse_document); ?>" placeholder="Documento de Identidad Cónyuge" onchange="removeInputRedFocus(this.id)" <?php echo e($disable_status); ?>>
                                    </div>
                                    <!--<div class="form-group col-md-6">
                                        <label class="registerForm" for="spouse_document_id"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Tipo Documento</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <select id="spouse_document_id" name="spouse_document_id" class="form-control registerForm" required tabindex="26" onchange="removeInputRedFocus(this.id),validateDocumentSpouse()" <?php echo e($disable_status); ?>>
                                            <option selected="true" value="">--Escoja Una---</option>
                                            <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option <?php if($doc->id == $spouse_document_id): ?> selected="true" <?php endif; ?> value="<?php echo e($doc->id); ?>"><?php echo e($doc->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>-->
                                   
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="passportEndDate"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Nombre(s) del Cónyuge</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" id="spouseFirstName" name="spouseFirstName" class="form-control registerForm" required tabindex="27"  style="line-height: 15px !important" value="<?php echo e($spouse_name); ?>" placeholder="Nombre del Cónyuge" onchange="removeInputRedFocus(this.id)" <?php echo e($disable_status); ?> maxlength="60">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="passportEndDate"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Apellido(s) del Cónyuge </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" id="spouseLastName" name="spouseLastName" class="form-control registerForm" required tabindex="28"  style="line-height: 15px !important" value="<?php echo e($spouse_last_name); ?>" placeholder="Apellido(s) del Cónyuge" onchange="removeInputRedFocus(this.id)" value="<?php echo e($spouse_last_name); ?>" <?php echo e($disable_status); ?> maxlength="60">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <br>
                
                <div id="beneficiaryFullDiv" class="col-xs-12 col-md-12 border" style="padding-left:0px !important;">
                    <div class="wizard_activo registerForm titleDiv" onclick="fadeToggle('representanteDiv')">
                        <a href="#" class="titleLink">Datos del Representante Legal o Apoderado:</a>
                        <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                    </div>
                    <div id="representanteDiv" class="col-md-12">
                    
                    <div class="form-row">
                                <div class="form-group">
                                    <div class="form-group col-md-6">
                                    <label class="registerForm" for="representante_apellidos"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>Apellidos </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" id="representante_apellidos" name="representante_apellidos" class="form-control registerForm" required tabindex="5" placeholder="apellidos" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="" maxlength="30">
                                      
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="representante_nombres"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>Nombres</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" id="representante_nombres" name="representante_nombres" class="form-control registerForm" required tabindex="6" placeholder="nombres" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <div class="form-group col-md-6">
                                    <label class="registerForm" for="lugar_nacimiento"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>Lugar de nacimiento</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" id="representante_lugar_nacimiento" name="representante_lugar_nacimiento" class="form-control registerForm" required tabindex="7" placeholder="Lugar de nacimiento" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="" maxlength="30">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="fecha_nacimiento"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>Fecha de nacimiento </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input  type="text" id="representante_fecha_nacimiento" name="representante_fecha_nacimiento" class="form-control registerForm" required tabindex="8" placeholder="fecha de nacimiento" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="actividad_economica"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>Sexo: </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <table><tr>
                                        <td id="tipo_empresa">M</td><td id="tipo_empresa"> <input  type="checkbox"   id="masculino" name="masculino" value="masculino" class="form-control move-left" required tabindex="10" placeholder="masculino" disabled="disabled" onchange="removeInputRedFocus(this.id)" value=""></td><td></td><td></td>
                                        </tr> 
                                        <tr>
                                        <td id="tipo_empresa">F</td><td id="tipo_empresa"> <input  type="checkbox"   id="femenino" name="femenino" value="femenino" class="form-control move-left" required tabindex="11" placeholder="Compania Limitada" disabled="disabled" onchange="removeInputRedFocus(this.id)" value=""></td><td id="tipo_empresa"></td><td id="tipo_empresa"></td>
                                        </tr>
                                      </table>

                                    </div>
                                    <div class="form-group col-md-6">
                                    <label class="registerForm" for="representante_cedula_pasaporte"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>Cédula /Pasaporte No.:   </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" id="representante_cedula_pasaporte" name="representante_cedula_pasaporte" class="form-control registerForm" required tabindex="9" placeholder="cedula/pasaporte" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="" maxlength="30">
                                    </div>
                                   
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                <div class="form-group col-md-6">
                                    <label class="registerForm" for="representante_nacionalidad"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>Nacionalidad:   </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" id="representante_nacionalidad" name="representante_nacionalidad" class="form-control registerForm" required tabindex="9" placeholder="representante_nacionalidad" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="" maxlength="30">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="actividad_economica"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>PEP </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <table><tr>
                                        <td id="tipo_empresa">SI</td><td id="tipo_empresa"> <input  type="checkbox"   id="SI" name="SI" value="SI" class="form-control move-left" required tabindex="10" placeholder="SI/NO" disabled="disabled" onchange="removeInputRedFocus(this.id)" value=""></td><td></td><td></td>
                                        </tr> 
                                        <tr>
                                        <td id="tipo_empresa">NO</td><td id="tipo_empresa"> <input  type="checkbox"   id="NO" name="NO" value="NO" class="form-control move-left" required tabindex="11" placeholder="SI/NO" disabled="disabled" onchange="removeInputRedFocus(this.id)" value=""></td><td id="tipo_empresa"></td><td id="tipo_empresa"></td>
                                        </tr>
                                      </table>

                                    </div>
                                    
                                   
                                </div>
                                <div class="form-row">
                                <div class="form-group">
                                    <div class="form-group col-md-6">
                                    <label class="registerForm" for="representante_fecha_nombramiento"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>Fecha Nombramiento: </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" id="representante_fecha_nombramiento" name="representante_fecha_nombramiento" class="form-control registerForm" required tabindex="7" placeholder="fecha de nombramiento" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="" maxlength="30">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="representante_profesion"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>Profesión:  </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" id="representante_profesion" name="representante_profesion" class="form-control registerForm" required tabindex="8" placeholder="profesión" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="">
                                    </div>
                                </div>
                            </div>
                            </div>
                           
                            <div class="form-row">
                                <div class="form-group">
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="country"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> País</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <select id="representante_pais" name="representante_pais" class="form-control registerForm" required onchange="removeInputRedFocus(this.id)" tabindex="11" <?php echo e($disable_status); ?>>
                                            <option value="">--Escoja Una---</option>
                                            <?php $__currentLoopData = $countriesResidence; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cou): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option <?php if($cou->id == $country_id): ?> selected="true" <?php endif; ?> value="<?php echo e($cou->id); ?>"><?php echo e($cou->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="representante_provincia"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Provincia  </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <select id="representante_provincia" name="representante_provincia" class="form-control registerForm" required tabindex="12" onchange="removeInputRedFocus(this.id)" <?php echo e($disable_status); ?>>
                                            <option selected="true" value="" disabled="disabled">--Escoja Una---</option>
                                            <?php $__currentLoopData = $provinces; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option <?php if($prov->id == $province_id): ?> selected <?php endif; ?> value="<?php echo e($prov->id); ?>"><?php echo e($prov->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                           
                            <div class="form-row">
                                <div class="form-group">
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="document_id"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Cantón</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <select id="representante_canton" name="representante_canton" class="form-control registerForm" required tabindex="13" onchange="removeInputRedFocus(this.id)" <?php echo e($disable_status); ?>>
                                            <option value="">--Escoja Una---</option>
                                            <?php if($addressCities != null): ?>
                                            <?php $__currentLoopData = $addressCities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option <?php if($cit->id == $city_id): ?> selected <?php endif; ?> value="<?php echo e($cit->id); ?>"><?php echo e($cit->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php else: ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="representante_parroquia"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Parroquia</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" id="representante_parroquia" name="representante_parroquia" class="form-control registerForm" required tabindex="15" placeholder="representante_parroquia" maxlength="10" onchange="removeInputRedFocus(this.id)" value="<?php echo e($address_number); ?>" <?php echo e($disable_status); ?> maxlength="30">
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                <div class="form-group col-md-6">
                                        <label class="registerForm" for="representante_calle_principal"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Calle Principal</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text2" id="representante_calle_principal" name="representante_calle_principal" class="form-control registerForm" required tabindex="14" placeholder="Calle Principal" onchange="removeInputRedFocus(this.id)" value="<?php echo e($main_road); ?>" <?php echo e($disable_status); ?> maxlength="90">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="representante_numero_calle"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> No</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" id="representante_numero_calle" name="representante_numero_calle" class="form-control registerForm" required tabindex="16" placeholder="No" onchange="removeInputRedFocus(this.id)" value="" <?php echo e($disable_status); ?> maxlength="50">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                <div class="form-group col-md-6">
                                        <label class="registerForm" for="representante_transversal"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Transversal</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text2" id="representante_transversal" name="representante_transversal" class="form-control registerForm" required tabindex="14" placeholder="transversal" onchange="removeInputRedFocus(this.id)" value="<?php echo e($main_road); ?>" <?php echo e($disable_status); ?> maxlength="90">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="representante_sector"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Sector </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="email" id="representante_sector" name="representante_sector" class="form-control registerForm" required tabindex="18" placeholder="sector" onchange="removeInputRedFocus(this.id)" value="" <?php echo e($disable_status); ?> maxlength="100">
                                        <p id="emailError" style="color:red;font-weight: bold"></p>  
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                <div class="form-group col-md-6">
                                        <label class="registerForm" for="representante_cargo_actual"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Cargo Actual: </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text2" id="representante_cargo_actual" name="representante_cargo_actual" class="form-control registerForm" required tabindex="14" placeholder="cargo_actual" onchange="removeInputRedFocus(this.id)" value="<?php echo e($main_road); ?>" <?php echo e($disable_status); ?> maxlength="90">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="representante_email"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>Correo Electrónico: </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="email" id="representante_email" name="representante_email" class="form-control registerForm" required tabindex="18" placeholder="email" onchange="removeInputRedFocus(this.id)" value="" <?php echo e($disable_status); ?> maxlength="100">
                                        <p id="emailError" style="color:red;font-weight: bold"></p>  
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                <div class="form-group col-md-6">
                                        <label class="registerForm" for="representante_telefono"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>Teléfono Residencial: </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text2" id="representante_telefono" name="representante_telefono" class="form-control registerForm" required tabindex="14" placeholder="telefono_residencial" onchange="removeInputRedFocus(this.id)" value="<?php echo e($main_road); ?>" <?php echo e($disable_status); ?> maxlength="90">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="celular"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>Celular No.: </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="email" id="representante_celular" name="representante_celular" class="form-control registerForm" required tabindex="18" placeholder="celular" onchange="removeInputRedFocus(this.id)" value="" <?php echo e($disable_status); ?> maxlength="100">
                                        
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                <div class="form-group col-md-6">
                                        <label class="registerForm" for="fax"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>Fax No.:  </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text2" id="representante_fax" name="representante_fax" class="form-control registerForm" required tabindex="14" placeholder="fax" onchange="removeInputRedFocus(this.id)" value="<?php echo e($main_road); ?>" <?php echo e($disable_status); ?> maxlength="90">
                                    </div>
                                    
                                </div>
                            </div>
                           

    
                           
                    </div>
                </div>
                <hr>
                <br>

                
                <div id="beneficiaryFullDiv" class="col-xs-12 col-md-12 border" style="padding-left:0px !important;">
                    <div class="wizard_activo registerForm titleDiv" onclick="fadeToggle('conyugueDiv')">
                        <a href="#" class="titleLink">Datos del Cónyuge o Conviviente del Representante Legal o Apoderado (si aplica):</a>
                        <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                    </div>
                    <div id="conyugueDiv" class="col-md-12">
                    
                    <div class="form-row">
                                <div class="form-group">
                                    <div class="form-group col-md-6">
                                    <label class="registerForm" for="conyugue_apellidos"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>Apellidos </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" id="conyugue_apellidos" name="conyugue_apellidos" class="form-control registerForm" required tabindex="5" placeholder="apellidos" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="" maxlength="30">
                                      
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="conyugue_nombres"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>Nombres</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input  type="text" id="conyugue_nombres" name="conyugue_nombres" class="form-control registerForm" required tabindex="6" placeholder="nombres" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <div class="form-group col-md-6">
                                    <label class="registerForm" for="lugar_nacimiento"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>Cédula /Pasaporte No.: </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" id="conyugue_cedula" name="conyugue_cedula" class="form-control registerForm" required tabindex="7" placeholder="conyugue_cedula" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="" maxlength="30">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="fecha_nacimiento"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>Nacionalidad </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" id="conyugue_nacionalidad" name="conyugue_nacionalidad" class="form-control registerForm" required tabindex="8" placeholder="conyugue_nacionalidad" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="">
                                    </div>
                                </div>
                            </div>
                            
                    </div>
                </div>
                
                <hr>
                
                
                <div class="col-xs-12 col-md-12" style="padding-left:0px !important;">
                    <div class="form-row">
                        <div class="form-group">
                            <div class="form-group">
                            </div>
                            <div class="form-group" style="float:right">
                                <a id="firstStepBtnNext" class="btn btn-info registerForm" align="right" tabindex="36" href="#"> Siguiente <span class="glyphicon glyphicon-step-forward"></span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div id="secondStep" class="hidden" style="margin-top:30px">
            <form id="secondStepForm" name="secondStepForm" method="POST" action="<?php echo e(asset('/user')); ?>" id="salesForm">
                <?php echo e(csrf_field()); ?>

                <input type="hidden" id="documentId" name="documentId" value="  ">
                <input type="hidden" id="saleId" name="saleId" value="<?php echo e($sales_id); ?>">
                
                <hr>
                <div class="col-md-12" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                    <div class="form-row" style="float:left">
                        <!--<a class="btn btn-default registerForm" align="right" href="<?php echo e(asset('/sales')); ?>" style="margin-left: -30px;"> Cancelar </a>-->
                        <div id="beneficiaryFullDiv" class="col-xs-12 col-md-12 border" style="padding-left:0px !important;">
                    <div class="wizard_activo registerForm titleDiv" onclick="fadeToggle('beneficiaryDiv')">
                        <a href="#" class="titleLink">Historial de reclamaciones e indemnizaciones de los últimos 2 años (superiores a USD. 10.000)</a>
                        <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                    </div>
                    <div id="beneficiaryDiv" class="col-md-12">
                        <div class="">
                            <div class="form-row">
                                <div class="form-group">
                                    <div class="form-group col-md-12">
                                        <label class="registerForm" for="is_beneficiary"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> ¿Ha recibido indemnizaciones superiores a USD. 10.000, respecto de cualquier asegurador en los últimos dos años?  </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span><br>
                                        <input id="is_beneficiary" name="is_beneficiary" class="is_beneficiaryCheckBox" type="checkbox" <?php if($beneficiaryName == null): ?> checked="checked" <?php endif; ?> data-toggle="toggle" tabindex="35" data-on="No" data-off="Si" onchange="isBeneficiaryChange(this)" <?php echo e($disable_status); ?>>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <span id="beneficiaryDataDiv" style="margin-top:-25px;">
                            <div class="">
                                <div class="form-row">
                                    <div class="form-group">
                                        <div class="form-group col-md-12">
                                            <span>Si la respuesta es SI, detalle la siguiente información:</span>
                                            <br>
                                            
                                            <div class="form-group">
                                            <table id="referenceTable" class="indemnizaciones">
                        <tr>
                            <th style="width:33%" colspan="3">Nombre Companía de Seguros</th>
</th>
                            <th style="width:33%" colspan="3">Fecha de Indemnización</th>
                            <th style="width:33%" colspan="3">Descripción del bien indemnizado</th>
                            <th style="width:33%" colspan="3">Valor de la indemnización</th>
                        </tr>
                        <tr>
                        <td style="width:33%" colspan="3"><input type="text" id="nombre_compania_seguros_1" name="nombre_compania_seguros_1" class="form-control registerForm" required tabindex="7" placeholder="Nombre Companía de Seguros" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="  " maxlength="30"></td>
                        <td style="width:33%" colspan="3"><input type="text" id="fecha_indeminizacion_1" name="fecha_indeminizacion_1" class="form-control registerForm" required tabindex="7" placeholder="Fecha de Indemnización" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="  " maxlength="30"></td>
                        <td style="width:33%" colspan="3"><input type="text" id="descripcion_bien_1" name="descripcion_bien_1" class="form-control registerForm" required tabindex="7" placeholder="Descripción del bien indemnizado" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="  " maxlength="30"></td>
                        <td style="width:33%" colspan="3"><input type="text" id="valor_indeminizacion_1" name="valor_indeminizacion_1" class="form-control registerForm" required tabindex="7" placeholder="Valor de la indemnización" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="  " maxlength="30"></td>
                          
                        </tr>
                        <tr>
                        <td style="width:33%" colspan="3"><input type="text" id="nombre_compania_seguros_2" name="nombre_compania_seguros_2" class="form-control registerForm" required tabindex="7" placeholder="Nombre Companía de Seguros" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="  " maxlength="30"></td>
                        <td style="width:33%" colspan="3"><input type="text" id="fecha_indeminizacion_2" name="fecha_indeminizacion_2" class="form-control registerForm" required tabindex="7" placeholder="Fecha de Indemnización" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="  " maxlength="30"></td>
                        <td style="width:33%" colspan="3"><input type="text" id="descripcion_bien_2" name="descripcion_bien_2" class="form-control registerForm" required tabindex="7" placeholder="Descripción del bien indemnizado" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="  " maxlength="30"></td>
                        <td style="width:33%" colspan="3"><input type="text" id="valor_indeminizacion_2" name="valor_indeminizacion_2" class="form-control registerForm" required tabindex="7" placeholder="Valor de la indemnización" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="  " maxlength="30"></td>
                      
                        </tr>
                        <tr>
                        <td style="width:33%" colspan="3"><input type="text" id="nombre_compania_seguros_3" name="nombre_compania_seguros_3" class="form-control registerForm" required tabindex="7" placeholder="Nombre Companía de Seguros" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="  " maxlength="30"></td>
                        <td style="width:33%" colspan="3"><input type="text" id="fecha_indeminizacion_3" name="fecha_indeminizacion_3" class="form-control registerForm" required tabindex="7" placeholder="Fecha de Indemnización" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="  " maxlength="30"></td>
                        <td style="width:33%" colspan="3"><input type="text" id="descripcion_bien_3" name="descripcion_bien_3" class="form-control registerForm" required tabindex="7" placeholder="Descripción del bien indemnizado" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="  " maxlength="30"></td>
                        <td style="width:33%" colspan="3"><input type="text" id="valor_indeminizacion_3" name="valor_indeminizacion_3" class="form-control registerForm" required tabindex="7" placeholder="Valor de la indemnización" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="  " maxlength="30"></td>
                          
                        </tr>
                        <tr>
                        <td style="width:33%" colspan="3"><input type="text" id="nombre_compania_seguros_4" name="nombre_compania_seguros_4" class="form-control registerForm" required tabindex="7" placeholder="Nombre Companía de Seguros" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="  " maxlength="30"></td>
                        <td style="width:33%" colspan="3"><input type="text" id="fecha_indeminizacion_4" name="fecha_indeminizacion_4" class="form-control registerForm" required tabindex="7" placeholder="Fecha de Indemnización" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="  " maxlength="30"></td>
                        <td style="width:33%" colspan="3"><input type="text" id="descripcion_bien_4" name="descripcion_bien_4" class="form-control registerForm" required tabindex="7" placeholder="Descripción del bien indemnizado" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="  " maxlength="30"></td>
                        <td style="width:33%" colspan="3"><input type="text" id="valor_indeminizacion_4" name="valor_indeminizacion_4" class="form-control registerForm" required tabindex="7" placeholder="Valor de la indemnización" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="  " maxlength="30"></td>

                        </tr>
                    </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               
                              
                               
                            </div>
                            <div class="col-md-12" style="text-align: justify;">
                                * Cuando en la póliza de seguro de vida o de accidentes personales con la cobertura de muerte, los asegurados hubiesen designado como beneficiarios a sus parientes hasta el cuarto grado de consanguinidad o segundo grado de afinidad, o a su cónyuge o conviviente en unión de hecho, no se requerirá la información de tales beneficiarios. Si fuesen otras personas las designadas como beneficiarios, la documentación referente a estos deberá ser presentada, obligatoriamente, mediante formulario de vinculación de clientes. 
                            </div>
                        </span>
                    </div>
                </div>
                <div class="col-xs-12 col-md-12" style="padding-left:0px !important;">
                    <div class="form-row">
                        <div class="form-group">
                            <div class="form-group">
                            </div>
                            <div class="form-group" style="float:right">
                                
                            </div>
                        </div>
                    </div>
                </div>
                    </div>
                    <div class="form-row" style="float:right">
                        <a id="secondStepBtnBack" class="btn btn-back registerForm" align="right" href="#"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                        <a id="secondStepBtnNext" class="btn btn-info registerForm" align="right" href="#"> Siguiente <span class="glyphicon glyphicon-step-forward"></span></a>
                    </div>
                </div>
            </form>
        </div>
        <div id="thirdStep" class="hidden" style="margin-top:30px">
            <form id="thirdStepForm" name="thirdStepForm" method="POST" action="<?php echo e(asset('/user')); ?>" id="salesForm">
                <?php echo e(csrf_field()); ?>

                <input type="hidden" id="documentId" name="documentId" value="  ">
                <input type="hidden" id="saleId" name="saleId" value="<?php echo e($sales_id); ?>">
                <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;margin-bottom: 5px;margin-top:30px;">
                    <div class="wizard_activo registerForm titleDiv" onclick="fadeToggle('pepDeclarationDiv')">
                        <a href="#" class="titleLink">Declaración y Autorización</a>
                        <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                    </div>
                    <div id="pepDeclarationDiv" class="col-md-12">
                        <div class="" style="margin-top:15px;text-align: justify; font-size: 14px;margin-bottom: 10px;margin-top:40px;">
                            <div class="col-md-12" style="margin-top:25px;">
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
                            <?php if($person_exposed == null): ?>
                                <div class="col-md-12" style="margin-bottom:15px;">
                                    Declaración sobre la condición de Persona Expuesta Políticamente PEP (Persona que desempeña o ha desempeñado funciones públicas en el país o en el exterior). Informo que he leído la Lista Mínima de Cargos Públicos a ser considerados "Personas Expuestas Políticamente" y declaro bajo juramento que <label class="radio-inline" style="padding-left:5px;padding-right: 5px;">Si <input type="radio" name="optradio3" value="yes" style="margin-left:5px;margin-top: 0px;" <?php echo e($disable_status); ?>></label> <label class="radio-inline" style="padding-left:5px; padding-right:15px;">No <input type="radio" name="optradio3" value="no" checked style="margin-left:5px;margin-top: 0px;" <?php echo e($disable_status); ?>></label><br> me encuentro ejerciendo uno de los cargos incluidos en la lista o lo ejercí hace un año atrás. En el caso de que la respuesta sea positiva, indicar: Cargo/Función/Jerarquía:  <input type="text2" id="pep_client" name="pep_client" class="form-control registerForm" required tabindex="2" placeholder="Cargo/Función/Jerarquía" onchange="removeInputRedFocus(this.id)" value="" <?php echo e($disable_status); ?>>
                                    Nota: La presente declaración no constituye una autoincriminación de ninguna clase, ni conlleva ninguna responsabilidad administrativa, civil o penal.
                                </div>
                            <?php else: ?>
                                <div class="col-md-12" style="margin-bottom:15px;">
                                    Declaración sobre la condición de Persona Expuesta Políticamente PEP (Persona que desempeña o ha desempeñado funciones públicas en el país o en el exterior). Informo que he leído la Lista Mínima de Cargos Públicos a ser considerados "Personas Expuestas Políticamente" y declaro bajo juramento que <label class="radio-inline" style="padding-left:5px;padding-right: 5px;">Si <input type="radio" name="optradio3" value="yes" <?php if($person_exposed == 'yes'): ?> checked <?php endif; ?> style="margin-left:5px;margin-top: 0px;"<?php echo e($disable_status); ?>></label> <label class="radio-inline" style="padding-left:5px; padding-right:15px;" <?php echo e($disable_status); ?>>No <input type="radio" name="optradio3" value="no" <?php if($person_exposed == 'no'): ?> checked <?php endif; ?> style="margin-left:5px;margin-top: 0px;"></label><br> me encuentro ejerciendo uno de los cargos incluidos en la lista o lo ejercí hace un año atrás. En el caso de que la respuesta sea positiva, indicar: Cargo/Función/Jerarquía:  <input type="text2" id="pep_client" name="pep_client" class="form-control registerForm" required tabindex="2" placeholder="Cargo/Función/Jerarquía" onchange="removeInputRedFocus(this.id)" value="<?php echo e($pep_client); ?>" <?php echo e($disable_status); ?>>
                                    Nota: La presente declaración no constituye una autoincriminación de ninguna clase, ni conlleva ninguna responsabilidad administrativa, civil o penal.
                                </div>
                            <?php endif; ?>
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
                    <div class="form-row" style="float:left">
                        <!--<a class="btn btn-default registerForm" align="right" href="<?php echo e(asset('/sales')); ?>" style="margin-left: -30px;"> Cancelar </a>-->
                    </div>
                    <div class="form-row" style="float:right">
                        <a id="thirdStepBtnBack" class="btn btn-back registerForm" align="right" href="#"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                        <a id="thirdStepBtnNext" class="btn btn-info registerForm" align="right" href="#"> Siguiente <span class="glyphicon glyphicon-step-forward"></span></a>
                    </div>
                </div>
            </form>
        </div>
        <div id="fourthStep" class="hidden" style="margin-top:30px">
            <!--<form id="fourthStepForm" name="fourthtepForm" method="POST" action="<?php echo e(asset('/user')); ?>" id="salesForm">-->
            <?php echo e(csrf_field()); ?>

            <input type="hidden" id="documentId" name="documentId" value="  ">
            <input type="hidden" id="saleId" name="saleId" value="<?php echo e($sales_id); ?>">
            <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;margin-bottom: 5px;margin-top:30px;">
                <div class="wizard_activo registerForm titleDiv" onclick="fadeToggle('picturesDivpicturesDiv')">
                    <a href="#" class="titleLink">Documentos Requeridos - Persona Natural</a>
                    <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                </div>
                <div id="picturesDiv" class="col-md-12" style="margin-top:25px;">
                    <div class="col-md-6" style="margin: 5px 0;">
                        <form class="col-md-12 border" method="post" id="upload_formDocumentApplicant" name="upload_formDocumentApplicant" enctype="multipart/form-data" style="min-height:350px !important">
                            <?php echo e(csrf_field()); ?>    
                            <center><label class="registerForm">Documento de Identidad</label></center>
                            <input type="hidden" id="documentId" name="documentId" value="  ">
                            <input type="hidden" id="saleId" name="saleId" value="<?php echo e($sales_id); ?>">
                            <input type="hidden" id="uploadType" name="uploadType" value="DocumentApplicant">
                            <input type="hidden" id="uploadedFileDocumentApplicant" name="uploadedFileDocumentApplicant" value="<?php echo e($picture_document_applicant); ?>">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm">Fecha de Caducidad</label>
                                <input type="date" id="DocumentApplicantDate" name="DocumentApplicantDate" class="form-control" style="line-height:14px;" tabindex="1" required="required" value="<?php echo e($document_applicant_date); ?>" <?php echo e($disable_status); ?>>
                            </div>
                            <div class="form-group" style="text-align: center;">
                                <label class="registerForm" style="word-wrap: break-word;">Copia del documento de identidad del contratante</label>
                            </div>
                            <div class="alert" id="messageDocumentApplicant" style="display: none"></div>
                            <center>
                                <div style="width:100px !important;padding: 0" class="inside" id="fileNameDocumentApplicant"></div>
                                <div class="inputWrapper"><span id="uploaded_imageDocumentApplicant"><?php echo $picture_document_applicant; ?></span>
                                    <center>
                                        <img src="<?php echo e(asset('images/mas.png')); ?>" alt="Girl in a jacket" style="width:20px;height:20px;margin-bottom: -100px;">
                                    </center>
                                    <input class="fileInput" type="file" name="select_fileDocumentApplicant" tabindex="2" onchange="fileNameFunction('DocumentApplicant')" id="select_fileDocumentApplicant" <?php if($disable_status != null): ?> style="display:none" <?php endif; ?>>
                                </div>
                            </center>
                            <center>
                                <button type="submit" name="upload_file" id="uploadDocumentApplicant" class="btn btn-primary <?php if($picture_document_applicant == null): ?> visible <?php else: ?> hidden <?php endif; ?>" tabindex="2" onclick="uploadPictureForm('DocumentApplicant')">
                                    <span class="glyphicon glyphicon-upload"></span> Subir Foto
                                </button>
                                <a class="<?php if($picture_document_applicant == null): ?> hidden <?php else: ?> visible <?php endif; ?>" id="deletePictureDocumentApplicant" href="#" onclick="deletePictureForm('DocumentApplicant','  ','<?php echo e($sales_id); ?>')">
                                    <?php if($disable_status == null): ?>
                                        <img src="<?php echo e(asset('/images/menos.png')); ?>" style="width:20px;height:20px">
                                    <?php endif; ?>                                          </a>  
                            </center>
                        </form>
                    </div>
                    <div class="col-md-6" id="formDocumentSpouse" style="margin: 5px 0;">
                        <form class="col-md-12 border" method="post" id="upload_formDocumentSpouse" name="upload_formDocumentSpouse" enctype="multipart/form-data" onsubmit="uploadPictureForm('upload_formDocumentSpouse'" style="min-height:350px !important">
                            <?php echo e(csrf_field()); ?>  
                            <center><label class="registerForm">Documento de Identidad del Cónyuge</label></center>
                            <input type="hidden" id="documentId" name="documentId" value="  ">
                            <input type="hidden" id="saleId" name="saleId" value="<?php echo e($sales_id); ?>">
                            <input type="hidden" id="uploadType" name="uploadType" value="DocumentSpouse">
                            <input type="hidden" id="uploadedFileDocumentSpouse" name="uploadedFileDocumentSpouse" value="<?php echo e($picture_document_spouse); ?>">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm">Fecha de Caducidad</label>
                                <input type="date" id="DocumentSpouseDate" name="DocumentSpouseDate" class="form-control" style="line-height:14px;" tabindex="6" value="<?php echo e($document_spouse_date); ?>" <?php echo e($disable_status); ?>>
                            </div>    
                            <div class="form-group" style="text-align: center;">
                                <label class="registerForm">Copia del documento de identidad del cónyuge o conviviente legal del contratante</label>
                            </div>
                                <div class="alert" id="messageDocumentSpouse" style="display: none"></div>
                            <center>
                                <div style="width:100px !important;padding: 0" class="inside" id="fileNameDocumentSpouse"></div>
                                <div class="inputWrapper"><span id="uploaded_imageDocumentSpouse"><?php echo $picture_document_spouse; ?></span>
                                    <center>
                                        <img src="<?php echo e(asset('images/mas.png')); ?>" alt="Girl in a jacket" style="width:20px;height:20px;margin-bottom: -100px;">
                                    </center>
                                    <input class="fileInput" type="file" name="select_fileDocumentSpouse" tabindex="7" onchange="fileNameFunction('DocumentSpouse')" id="select_fileDocumentSpouse" <?php if($disable_status != null): ?> style="display:none" <?php endif; ?>>
                                </div>
                            </center>
                            <center>
                                <button type="submit" name="upload" id="uploadDocumentSpouse" class="btn btn-primary <?php if($picture_document_spouse == null): ?> visible <?php else: ?> hidden <?php endif; ?>" tabindex="8" onclick="uploadPictureForm('DocumentSpouse')">
                                    <span class="glyphicon glyphicon-upload"></span> Subir Foto
                                </button>
                                <a class="<?php if($picture_document_spouse == null): ?> hidden <?php else: ?> visible <?php endif; ?>" id="deletePictureDocumentSpouse" href="#" onclick="deletePictureForm('DocumentSpouse','  ','<?php echo e($sales_id); ?>')">
                                    <?php if($disable_status == null): ?>
                                        <img src="<?php echo e(asset('/images/menos.png')); ?>" style="width:20px;height:20px">
                                    <?php endif; ?>                                          </a>  
                            </center>
                        </form>
                    </div>
                    <div class="col-md-6" style="margin: 5px 0;">
                        <form class="col-md-12 border" method="post" id="upload_formService" name="upload_formService" enctype="multipart/form-data" onsubmit="uploadPictureForm('upload_formService'" style="min-height:350px !important">
                            <?php echo e(csrf_field()); ?>    
                            <center><label class="registerForm">Planillas Servicios Básicos</label></center>                            
                            <input type="hidden" id="documentId" name="documentId" value="  ">
                            <input type="hidden" id="saleId" name="saleId" value="<?php echo e($sales_id); ?>">
                            <input type="hidden" id="uploadType" name="uploadType" value="Service">
                            <input type="hidden" id="uploadedFileService" name="uploadedFileService" value="<?php echo e($picture_service); ?>">
                                <div class="form-group" style="padding-top:85px;text-align: center;">
                                    <label class="registerForm" style="padding-bottom: 5%;">Copia de una planilla de servicios basicos</label>
                                </div>
                                <div style="width:100px !important;padding: 0" class="inside" id="fileNameService"></div>
                            <div class="alert" id="messageService" style="display: none"></div>
                            <center>
                                <div class="inputWrapper"><span id="uploaded_imageService"><?php echo $picture_service; ?></span>
                                    <center>
                                        <img src="<?php echo e(asset('images/mas.png')); ?>" alt="Girl in a jacket" style="width:20px;height:20px;margin-bottom: -100px;">
                                    </center>
                                    <input class="fileInput" type="file" name="select_fileService" tabindex="4" onchange="fileNameFunction('Service')" id="select_fileService" <?php if($disable_status != null): ?> style="display:none" <?php endif; ?>>
                                </div>
                            </center>
                            <center>
                                <button type="submit" name="upload" id="uploadService" class="btn btn-primary <?php if($picture_service == null): ?> visible <?php else: ?> hidden <?php endif; ?>" tabindex="5" onclick="uploadPictureForm('Service')">
                                    <span class="glyphicon glyphicon-upload"></span> Subir Foto
                                </button>
                                <a class="<?php if($picture_service == null): ?> hidden <?php else: ?> visible <?php endif; ?>" id="deletePictureService" href="#" onclick="deletePictureForm('Service','  ','<?php echo e($sales_id); ?>')">
                                    <?php if($disable_status == null): ?>
                                        <img src="<?php echo e(asset('/images/menos.png')); ?>" style="width:20px;height:20px">
                                    <?php endif; ?>                                        
                                </a>  
                            </center>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;margin-bottom: 5px">
                <div class="wizard_activo registerForm titleDiv" onclick="fadeToggle('pepDeclarationDiv')">
                    <a href="#" class="titleLink">Términos y Condiciones</a>
                    <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                </div>
                <div id="pepDeclarationDiv" class="col-md-12">
                    <div class="col-md-12" style="margin-top:15px;text-align: justify; font-size: 14px;margin-bottom: 10px;">
                        <div class="col-md-12" style="margin-bottom:15px;">
                            <div id="termChkAlert" class="alert alert-danger hidden">
                                <strong>Alerta!</strong> Por favor acepte los términos y condiciones.
                            </div>
                            <input type="checkbox" id="termChk" name="termChk" <?php if($checked != null): ?> checked disabled="disabled" <?php endif; ?>> He leído y acepto las condiciones generales de venta. <a href="<?php echo e(asset('/Condiciones_Generales_De_Venta.pdf')); ?>" target="_blank">Condiciones generales de venta</a>
                        </div>
                        <hr>  
                    </div>
                </div>
            </div>
            <hr>
            <div class="col-md-12" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                <div class="form-row" style="float:left">
                    <!--<a class="btn btn-default registerForm" align="right" href="<?php echo e(asset('/sales')); ?>" style="margin-left: -30px;"> Cancelar </a>-->
                </div>
                <div class="form-row" style="float:right">
                    <a id="fourthStepBtnBack" class="btn btn-back registerForm" align="right" href="#"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                    <?php if($disable_status == null): ?>
                        <a id="fourthStepBtnNext" class="btn btn-info registerForm" align="right" href="#"> Siguiente <span class="glyphicon glyphicon-step-forward"></span></a>
                    <?php endif; ?>
                </div>
            </div>
            <!--</form>-->
        </div>
        <div id="fifthStep" class="hidden" style="margin-top:30px">
            <div class="col-xs-12 col-md-12 border" style="margin-top:30px">
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
                <div class="form-row" style="float:left">
                    <!--<a class="btn btn-default registerForm" align="right" href="<?php echo e(asset('/sales')); ?>" style="margin-left: -30px;"> Cancelar </a>-->
                </div>
                <div class="form-row" style="float:right">
                    <a id="fifthStepBtnBack" class="btn btn-back registerForm" align="right" href="#"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                    <a id="fifthStepBtnNext" class="btn btn-info registerForm" align="right" href="#"> Validar </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Trigger the modal with a button -->
<button type="button" class="btn btn-info btn-lg hidden" data-toggle="modal" data-target="#myModal">Open Modal</button>
<!-- Modal Actividades Economicas -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Activades Economicas</h4>
            </div>
            <div class="modal-body">
                
                <form id="modalForm">
                    <div class="form-row">
                        <div class="col-md-12 input-group">
                            <label class="registerForm"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span> Busqueda de Actividad Economica</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                            <input type="text2" id="searchEconomicActivity" name="searchEconomicActivity" class="form-control registerForm" required  placeholder="Busqueda de Actividad Economica" onchange="removeInputRedFocus(this.id)" <?php echo e($disable_status); ?> maxlength="15" style="margin-right:5px;">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-info" <?php if($disable_status): ?> disabled="disabled" <?php else: ?> <?php endif; ?> onclick="economicActivitySearch()" style="margin-top:22px"><span class="glyphicon glyphicon-search"></span></button>
                            </span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-12 input-group">
                            <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm"> Actividad Economica</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                            <select id="economic_activity_search" name="economic_activity_search" class="form-control registerForm" required onchange="removeInputRedFocus(this.id)">
                                <option value="">--Escoja Una--</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="closeModal" type="button" class="btn btn-default registerForm" data-dismiss="modal" style="float:left">Cerrar</button>
                <button type="button" class="btn btn-info registerForm" style="float:right" onclick="selectEconomicActivity()">Seleccionar</button>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.remote_app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\magnussucre\resources\views/vinculation/pj/beneficiaryPerson/create.blade.php ENDPATH**/ ?>