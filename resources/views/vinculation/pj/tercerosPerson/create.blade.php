@extends('layouts.remote_app')

@section('content')
<script src="{{ assets('js/registerCustom.js') }}"></script>
<script src="{{ assets('js/vinculation/pj/terceros_juridica.js') }}"></script>
<link href="{{ assets('css/sales/create.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{ assets('css/sales/index.css')}}" rel="stylesheet" type="text/css"/>
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
@if($secondary_email == null)
<script>
$(document).ready(function () {
var div = document.getElementById('emailSecondaryForm');
$(div).fadeOut();
});</script>
@endif
@if($economic_activity != 6)
<script>
    $(document).ready(function () {
    var div = document.getElementById('otherEconomicActivityDiv');
    $(div).fadeOut();
    });</script>
@endif
@if($other_monthly_income == null)
<script>
    $(document).ready(function () {
    var div = document.getElementById('otherIncomeDiv');
    $(div).fadeOut();
    });</script>
@endif
@if($civil_state == 2 || $civil_state == 5)
<script>
    $(document).ready(function () {
    var div = document.getElementById('spouseFullDiv');
    $(div).fadeIn();
    var div = document.getElementById('formDocumentSpouse');
    $(div).fadeIn();
    });</script>
@else
<script>
    $(document).ready(function () {
    var div = document.getElementById('spouseFullDiv');
    $(div).fadeOut();
    var div = document.getElementById('formDocumentSpouse');
    $(div).fadeOut();
    });</script>
@endif

@if($beneficiaryName == null)
<script>
    $(document).ready(function () {
    var div = document.getElementById('beneficiaryDataDiv');
    $(div).fadeOut();
    div = document.getElementById('representanteDiv');
    $(div).fadeOut();
    div = document.getElementById('conyugueDiv');
    $(div).fadeOut();
    div = document.getElementById('tercerosDiv');
    $(div).fadeOut();
    div = document.getElementById('picturesDiv');
    $(div).fadeOut();
    
   
    
    });
</script>
@endif
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
    h4#declaracion{
        margin-top:30px !important;
    }

    .col-sm-4#firma,#firma_representante,#firma_representante_cedula,#firma_responsable_comercial, #fecha_firma_responsable_comercial{
        text-align:center !important;
    }
   
  
    @media only screen and (max-width: 600px) {
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
<meta name="csrf-token" content="{{ csrf_token() }}" />
<div class="container" style="margin-top:15px; font-size:14px !important">
    <!--<div class="row justify-content-center border" style="margin-left:20%;">-->
    <div class="col-xs-12 col-md-10 col-md-offset-1 border">
        <div class="form-row">
            <div class="col-xs-12 registerForm" style="margin:12px;">
                <center>
                    <h4 style="font-weight:bold">FORMULARIO DE VINCULACIÓN A TERCEROS <br>PERSONA JURÍDICA</h4>
                    <hr>
                    <h5>LA ENTREGA DE LA INFORMACIÓN Y DOCUMENTACIÓN SOLICITADA ES OBLIGATORIA</h5>
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
            <form id="firstStepForm" name="firstStepForm" method="POST" action="{{asset('/user')}}" id="salesForm">
                {{ csrf_field() }}
              
                <div id="productAlert" class="alert alert-danger hidden">
                    <strong>¡Alerta!</strong> Debe seleccionar un producto
                </div>
                <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;margin-bottom: 5px;margin-top:30px;">
                    <div class="wizard_activo registerForm titleDiv" onclick="fadeToggle('personalDiv')">
                        <a href="#" class="titleLink">DATOS DE LA COMPAÑÍA</a>
                        <span style="float:left;margin-left:60%;margin-top: 4px;">Fecha: ___/___/___/</span>
                        <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                    </div>
                    <div id="personalDiv" class="col-md-12">
                        @if($customer == false)
                        <input type="hidden" id="customerCheck" value="0">
                        @else
                        <input type="hidden" id="customerCheck" value="1">
                        @endif
                     <div class="">
                            <div class="form-row">
                                   
                                    <div class="form-group col-md-6">
                                    <label class="registerForm" for="spouse_document_id"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>Fecha de Constitución: </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="date" id="fecha_constitucion" name="fecha_constitucion" class="form-control registerForm" required tabindex="5" placeholder="fecha de constitución" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="" maxlength="30">
                                        
                                    </div>
                                   
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="ruc"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>Ruc N°</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input  type="text" id="ruc" name="ruc" class="form-control registerForm" required tabindex="6" placeholder="Número de Identificación" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="">
                                    </div>
                                    <div class="form-group col-md-6">
                                    <label class="registerForm" for="razon_social"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>Razón Social:  </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" id="razon_social" name="razon_social" class="form-control registerForm" required tabindex="7" placeholder="razon_social" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="" maxlength="30">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">

                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="actividad_economica"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>Actividad Económica: </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <select id="economic_activity" name="economic_activity" class="form-control registerForm" required tabindex="1" onchange="removeInputRedFocus(this.id)" disabled="disabled" style="float:left;">
                                            <option value="">--Escoja Una---</option>
                                            @foreach($economicActivities as $eco)
                                            <option @if($economic_activity == $eco->id) selected="true" @else @endif value="{{$eco->id}}">{{$eco->name}}</option>
                                            @endforeach
                                        </select>
                                        <button id="btnModalSearch" type="button" class="btn btn-info" @if($disable_status) disabled="disabled" @else @endif data-toggle="modal" data-target="#myModal" style="float:right"><span class="glyphicon glyphicon-search"></span></button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                   <!-- <div class="form-group col-md-6">
                                        <label class="registerForm" for="nationality"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> País de Nacimiento</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        @if($nationality_id == null )
                                            <select id="nationality" name="nationality" class="form-control registerForm" required onchange="removeInputRedFocus(this.id)" tabindex="16" {{$disable_status}}>
                                        @else+6
                                            <input type="hidden" id="nationality" name="nationality" value="{{$nationality_id}}">
                                            <select class="form-control registerForm" required onchange="removeInputRedFocus(this.id)" disabled="disabled" tabindex="17">
                                        @endif
                                            <option value="">--Escoja Una--</option>
                                            @foreach($countries as $cou)
                                            <option @if($cou->id == $nationality_id) selected="true" @endif value="{{$cou->id}}">{{$cou->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>-->
                                  
                                </div>
                            </div>
                           <!-- <div class="form-row">
                                <div class="form-group">
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="birth_date"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Fecha de Nacimiento</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="date" id="birth_date" name="birth_date" class="form-control registerForm" style="line-height: 15px !important" onchange="removeInputRedFocus(this.id)" value="{{$birth_date}}" @if($birth_date != null) readonly="readonly" @endif tabindex="9" {{$disable_status}}>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="birth_city"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Ciudad de Nacimiento </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        @if($birth_place == null)
                                            <select id="birth_city" name="birth_city" class="form-control registerForm" required onchange="removeInputRedFocus(this.id)" tabindex="18" {{$disable_status}}>
                                        @else
                                            <input type="hidden" id="birth_city" name="birth_city" value="{{$birth_place}}">
                                            <select class="form-control registerForm" required onchange="removeInputRedFocus(this.id)" disabled="disabled" tabindex="19">
                                        @endif
                                            <option value="">--Escoja Una---</option>
                                            @foreach($cities as $cit)
                                            <option @if($cit->id == $birth_place) selected="true" @endif value="{{$cit->id}}">{{$cit->name}}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" id="birth_place" name="birth_place" value="{{$birth_place}}">
                                    </div>
                                </div>
                            </div>-->
                            <div class="form-row">
                                <div class="form-group">
                                <div class="form-group col-md-6">
                                        <label class="registerForm" for="country"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> País</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <select id="country" name="country" class="form-control registerForm" required onchange="removeInputRedFocus(this.id)" tabindex="11" {{$disable_status}}>
                                            <option value="">--Escoja Una---</option>
                                            @foreach($countriesResidence as $cou)
                                            <option @if($cou->id == $country_id) selected="true" @endif value="{{$cou->id}}">{{$cou->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                   <!-- <div class="form-group col-md-6">
                                        <label class="registerForm" for="civilState"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Estado Civil</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <select id="civilState" name="civilState" class="form-control registerForm" required tabindex="10" onchange="removeInputRedFocus(this.id)" {{$disable_status}}>
                                            <option value="">--Escoja Una---</option>
                                            @foreach($civilStates as $sta)
                                            <option @if($sta->id == $civil_state) selected @endif value="{{$sta->id}}">{{$sta->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>-->
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                <div class="form-group col-md-6">
                                        <label class="registerForm" for="document_id"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Cantón</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <select id="canton" name="canton" class="form-control registerForm" required tabindex="13" onchange="removeInputRedFocus(this.id)" {{$disable_status}}>
                                            <option value="">--Escoja Una---</option>
                                            @if($addressCities != null)
                                            @foreach($addressCities as $cit)
                                            <option @if($cit->id == $city_id) selected @endif value="{{$cit->id}}">{{$cit->name}}</option>
                                            @endforeach
                                            @else
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="document_id"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Provincia  </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <select id="province" name="province" class="form-control registerForm" required tabindex="12" onchange="removeInputRedFocus(this.id)" {{$disable_status}}>
                                            <option selected="true" value="" disabled="disabled">--Escoja Una---</option>
                                            @foreach($provinces as $prov)
                                            <option @if($prov->id == $province_id) selected @endif value="{{$prov->id}}">{{$prov->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                           
                            <div class="form-row">
                                <div class="form-group">
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="numero_calle"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> N°</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" id="numero_calle" name="numero_calle" class="form-control registerForm" required tabindex="15" placeholder="N°" maxlength="10" onchange="removeInputRedFocus(this.id)" value="{{$address_number}}" {{$disable_status}} maxlength="30">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="parroquia"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Parroquia</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" id="parroquia" name="parroquia" class="form-control registerForm" required tabindex="15" placeholder="N°" maxlength="10" onchange="removeInputRedFocus(this.id)" value="{{$address_number}}" {{$disable_status}} maxlength="30">
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                <div class="form-group col-md-6">
                                        <label class="registerForm" for="sector"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Sector</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" id="sector" name="sector" class="form-control registerForm" required tabindex="17" placeholder="Sector" maxlength="20" onchange="removeInputRedFocus(this.id)" value="" {{$disable_status}} maxlength="30">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="calle_principal"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Calle Principal</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text2" id="calle_principal" name="calle_principal" class="form-control registerForm" required tabindex="14" placeholder="Calle Principal" onchange="removeInputRedFocus(this.id)" value="{{$main_road}}" {{$disable_status}} maxlength="90">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="calle_transversal"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Calle Transversal</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" id="calle_transversal" name="calle_transversal" class="form-control registerForm" required tabindex="16" placeholder="Calle Transversal" onchange="removeInputRedFocus(this.id)" value="" {{$disable_status}} maxlength="50">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="phones"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Teléfonos</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" id="phones" name="phones" class="form-control registerForm" required tabindex="19" placeholder="Teléfonos" onchange="removeInputRedFocus(this.id)" value="{{$mobile_phone}}" {{$disable_status}} maxlength="10">
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="celular"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Celular</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" id="celular" name="celular" class="form-control registerForm" required tabindex="22" placeholder="celular" onchange="removeInputRedFocus(this.id)" value="" {{$disable_status}} maxlength="9">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="email"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Email</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="email" id="email" name="email" class="form-control registerForm" required tabindex="18" placeholder="Correo" onchange="removeInputRedFocus(this.id)" value="" {{$disable_status}} maxlength="100">
                                        <p id="emailError" style="color:red;font-weight: bold"></p>  
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
              
                
                <!--div id="passportFullDiv" class="col-xs-12 col-md-12 border" style="padding-left:0px !important;margin-bottom: 5px">
                    <div class="wizard_activo registerForm titleDiv" onclick="fadeToggle('passportDiv')">
                        <a href="#" class="titleLink">Pasaporte</a>
                        <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                    </div>
                    <div id="passportDiv" class="col-md-12">
                        <div class="col-md-12" style="margin-bottom: -25px">
                            <div class="form-group col-md-6 form-group" style="margin-left:-15px">
                                <label class="registerForm" for="passportNumber"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Número de Pasaporte</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" id="passportNumber" name="passportNumber" class="form-control registerForm" required tabindex="20" placeholder="Número de Pasaporte" onchange="removeInputRedFocus(this.id)" {{$disable_status}}>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="form-group">
                                <label class="registerForm" for="passportBeginDate"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Fecha de Emisión</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="date" id="passportBeginDate" name="passportBeginDate" class="form-control registerForm" required tabindex="21"  style="line-height: 15px !important;width:96%" onchange="removeInputRedFocus(this.id)" {{$disable_status}}>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="form-group">
                                <label class="registerForm" for="passportEndDate"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Fecha de Caducidad</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="date" id="passportEndDate" name="passportEndDate" class="form-control registerForm" required tabindex="22"  style="line-height: 15px !important" onchange="removeInputRedFocus(this.id)" {{$disable_status}}>
                            </div>
                        </div>
                        <div class="col-md-12" style="margin-top: -25px">
                            <div id=""  class="form-group col-md-6 form-group" style="margin-left:-15px">
                                <label class="registerForm" for="migratoryState"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>  Estado migratorio o Código de VISA:</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <select id="migratoryState" name="migratoryState" class="form-control registerForm" required tabindex="23" onchange="removeInputRedFocus(this.id)" {{$disable_status}}>
                                    <option selected="true" value="">--Escoja Una---</option>
                                    @foreach($migratoryStates as $sta)
                                    <option value="{{$sta->id}}">{{$sta->name}}</option>
                                    @endforeach
                                </select>   
                            </div>
                            <div class="form-group col-md-6 form-group" style="float:right; margin-right: -15px;width:52%">                                
                                <label class="registerForm" for="passportEntryDate"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Fecha de Ingreso al país</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="date" id="passportEntryDate" name="passportEntryDate" class="form-control registerForm" required tabindex="24"  style="line-height: 15px !important;width:96%" onchange="removeInputRedFocus(this.id)" {{$disable_status}}>
                            </div>
                        </div>
                    </div>
                </div>-->
              <!--  <div id="spouseFullDiv" class="col-xs-12 col-md-12 border" style="padding-left:0px !important;margin-bottom: 5px">
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
                                        <input type="text" id="spouseDocument" name="spouseDocument" class="form-control registerForm" required tabindex="25"  style="line-height: 15px !important;width:96%" value="{{$spouse_document}}" placeholder="Documento de Identidad Cónyuge" onchange="removeInputRedFocus(this.id)" {{$disable_status}}>
                                    </div>
                                   
                                   
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="passportEndDate"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Nombre(s) del Cónyuge</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" id="spouseFirstName" name="spouseFirstName" class="form-control registerForm" required tabindex="27"  style="line-height: 15px !important" value="{{$spouse_name}}" placeholder="Nombre del Cónyuge" onchange="removeInputRedFocus(this.id)" {{$disable_status}} maxlength="60">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="passportEndDate"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Apellido(s) del Cónyuge </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" id="spouseLastName" name="spouseLastName" class="form-control registerForm" required tabindex="28"  style="line-height: 15px !important" value="{{$spouse_last_name}}" placeholder="Apellido(s) del Cónyuge" onchange="removeInputRedFocus(this.id)" value="{{$spouse_last_name}}" {{$disable_status}} maxlength="60">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>-->
                
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
                                        <input  type="date" id="representante_fecha_nacimiento" name="representante_fecha_nacimiento" class="form-control registerForm" required tabindex="8" placeholder="fecha de nacimiento" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    --<div class="form-group col-md-6">
                                        <label class="registerForm" for="actividad_economica"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>Cédula /Pasaporte: </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <table><tr>
                                        <td id="tipo_empresa">Cédula</td><td id="tipo_empresa"> <input  type="checkbox"   id="cedula" name="cedula" value="cedula" class="form-control move-left" required tabindex="10" placeholder="cedula" disabled="disabled" onchange="removeInputRedFocus(this.id)" value=""></td><td></td><td></td>
                                        </tr> 
                                        <tr>
                                        <td id="tipo_empresa">Pasaporte</td><td id="tipo_empresa"> <input  type="checkbox"   id="pasaporte" name="pasaporte" value="pasaporte" class="form-control move-left" required tabindex="11" placeholder="pasaporte" disabled="disabled" onchange="removeInputRedFocus(this.id)" value=""></td><td id="tipo_empresa"></td><td id="tipo_empresa"></td>
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
                                        <label class="registerForm" for="actividad_economica"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>Estado Civil: </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <table><tr>
                                        <td id="tipo_empresa">Soltero/a:</td><td id="tipo_empresa"> <input  type="checkbox"   id="soltero" name="soltero" value="Sociedad Anónima" class="form-control move-left" required tabindex="10" placeholder="soltero" disabled="disabled" onchange="removeInputRedFocus(this.id)" value=""></td><td></td><td></td><td></td><td></td>
                                        </tr> 
                                        <tr>
                                        <td id="tipo_empresa">Unión de Hecho:</td><td id="tipo_empresa"> <input  type="checkbox"   id="union_de_hecho" name="union_de_hecho" value="union_de_hecho" class="form-control move-left" required tabindex="11" placeholder="Compania Limitada" disabled="disabled" onchange="removeInputRedFocus(this.id)" value=" -"></td><td id="tipo_empresa"></td><td id="tipo_empresa"></td><td id="tipo_empresa"></td><td id="tipo_empresa"></td>
                                        </tr>
                                        <tr>
                                        <td id="tipo_empresa">Casado/a:</td><td id="tipo_empresa"> <input  type="checkbox"   id="casado" name="casado" value="Sociedad Hecho" class="form-control move-left" required tabindex="12" placeholder="casado" disabled="disabled" onchange="removeInputRedFocus(this.id)" value=""></td><td></td><td></td><td></td><td></td>
                                        </tr>
                                        <tr>
                                        <td id="tipo_empresa">Divorciado/a:</td><td id="tipo_empresa"> <input  type="checkbox"   id="divorciado" name="divorciado" value="Empresa Pública" class="form-control move-left" required tabindex="13" placeholder="divorciado" disabled="disabled" onchange="removeInputRedFocus(this.id)" value=""></td><td id="tipo_empresa"></td><td id="tipo_empresa"></td><td id="tipo_empresa"></td><td id="tipo_empresa"></td>
                                        </tr>
                                        <tr>
                                        <td id="tipo_empresa">Viudo/a:</td><td id="tipo_empresa"> <input  type="checkbox"   id="viudo" name="viudo" value="Empresa Privada" class="form-control move-left" required tabindex="14" placeholder="viudo" disabled="disabled" onchange="removeInputRedFocus(this.id)" value=""></td><td></td><td></td><td></td><td></td>
                                        </tr>
                                        </table>

                                </div>
                                    
                                   
                            </div>
                                <!--<div class="form-row">
                                <div class="form-group">
                                    <div class="form-group col-md-6">
                                    <label class="registerForm" for="representante_fecha_nombramiento"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>Fecha Nombramiento: </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    </div>
                                    
                                </div>
                            </div>-->
                            </div>
                           
                            <div class="form-row">
                                <div class="form-group">
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="country"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> País</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <select id="representante_pais" name="representante_pais" class="form-control registerForm" required onchange="removeInputRedFocus(this.id)" tabindex="11" {{$disable_status}}>
                                            <option value="">--Escoja Una---</option>
                                            @foreach($countriesResidence as $cou)
                                            <option @if($cou->id == $country_id) selected="true" @endif value="{{$cou->id}}">{{$cou->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="representante_provincia"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Provincia  </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <select id="representante_provincia" name="representante_provincia" class="form-control registerForm" required tabindex="12" onchange="removeInputRedFocus(this.id)" {{$disable_status}}>
                                            <option selected="true" value="" disabled="disabled">--Escoja Una---</option>
                                            @foreach($provinces as $prov)
                                            <option @if($prov->id == $province_id) selected @endif value="{{$prov->id}}">{{$prov->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                           
                            <div class="form-row">
                                <div class="form-group">
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="document_id"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Cantón</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <select id="representante_canton" name="representante_canton" class="form-control registerForm" required tabindex="13" onchange="removeInputRedFocus(this.id)" {{$disable_status}}>
                                            <option value="">--Escoja Una---</option>
                                            @if($addressCities != null)
                                            @foreach($addressCities as $cit)
                                            <option @if($cit->id == $city_id) selected @endif value="{{$cit->id}}">{{$cit->name}}</option>
                                            @endforeach
                                            @else
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="representante_parroquia"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Parroquia</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" id="representante_parroquia" name="representante_parroquia" class="form-control registerForm" required tabindex="15" placeholder="representante_parroquia" maxlength="10" onchange="removeInputRedFocus(this.id)" value="{{$address_number}}" {{$disable_status}} maxlength="30">
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                <div class="form-group col-md-6">
                                        <label class="registerForm" for="representante_calle_principal"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Calle Principal</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text2" id="representante_calle_principal" name="representante_calle_principal" class="form-control registerForm" required tabindex="14" placeholder="Calle Principal" onchange="removeInputRedFocus(this.id)" value="{{$main_road}}" {{$disable_status}} maxlength="90">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="representante_numero_calle"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> No</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" id="representante_numero_calle" name="representante_numero_calle" class="form-control registerForm" required tabindex="16" placeholder="No" onchange="removeInputRedFocus(this.id)" value="" {{$disable_status}} maxlength="50">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                <!--<div class="form-group col-md-6">
                                        <label class="registerForm" for="representante_transversal"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Transversal</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text2" id="representante_transversal" name="representante_transversal" class="form-control registerForm" required tabindex="14" placeholder="transversal" onchange="removeInputRedFocus(this.id)" value="{{$main_road}}" {{$disable_status}} maxlength="90">
                                </div>-->
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="representante_sector"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Sector </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="email" id="representante_sector" name="representante_sector" class="form-control registerForm" required tabindex="18" placeholder="sector" onchange="removeInputRedFocus(this.id)" value="" {{$disable_status}} maxlength="100">
                                        <p id="emailError" style="color:red;font-weight: bold"></p>  
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                <!--<div class="form-group col-md-6">
                                        <label class="registerForm" for="representante_cargo_actual"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> Cargo Actual: </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text2" id="representante_cargo_actual" name="representante_cargo_actual" class="form-control registerForm" required tabindex="14" placeholder="cargo_actual" onchange="removeInputRedFocus(this.id)" value="{{$main_road}}" {{$disable_status}} maxlength="90">
                                </div>-->
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="representante_email"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>Email: </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="email" id="representante_email" name="representante_email" class="form-control registerForm" required tabindex="18" placeholder="email" onchange="removeInputRedFocus(this.id)" value="" {{$disable_status}} maxlength="100">
                                        <p id="emailError" style="color:red;font-weight: bold"></p>  
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                <div class="form-group col-md-6">
                                        <label class="registerForm" for="representante_telefono"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>Teléfono Residencial: </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text2" id="representante_telefono" name="representante_telefono" class="form-control registerForm" required tabindex="14" placeholder="telefono_residencial" onchange="removeInputRedFocus(this.id)" value="{{$main_road}}" {{$disable_status}} maxlength="90">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" for="celular"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>Celular No.: </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="email" id="representante_celular" name="representante_celular" class="form-control registerForm" required tabindex="18" placeholder="celular" onchange="removeInputRedFocus(this.id)" value="" {{$disable_status}} maxlength="100">
                                        
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                               
                                    
                                </div>
                            </div>
                           

    
                           
                    </div>
                </div>
                <hr>
                <br>

                
               <!-- <div id="beneficiaryFullDiv" class="col-xs-12 col-md-12 border" style="padding-left:0px !important;">
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
                </div> -->
                
             
                
                
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
            <form id="secondStepForm" name="secondStepForm" method="POST" action="{{asset('/user')}}" id="salesForm">
                {{ csrf_field() }}
                <input type="hidden" id="documentId" name="documentId" value="">
                <input type="hidden" id="saleId" name="saleId" value="{{$sales_id}}">
                <div id="beneficiaryFullDiv" class="col-xs-12 col-md-12 border" style="padding-left:0px !important;">
                    <div class="wizard_activo registerForm titleDiv" onclick="fadeToggle('tercerosDiv')">
                        <a href="#" class="titleLink">SITUACIÓN FINANCIERA</a>
                        <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                    </div>
                    <div id="tercerosDiv" class="col-md-12">
                        <div class="">
                            
                        </div>
                        <span id="tercerosDataDiv" style="margin-top:-25px;">
                            <div class="">
                                <div class="form-row">
                                    <div class="form-group">
                                        <div class="form-group col-md-12">
                                           
                                            <br>
                                            
                                            <div class="form-group">
                                            <div class="form-group col-md-6">
                                                <label class="registerForm" for="ingresos_brutos_terceros"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>Ingresos brutos anuales declarados en el año anterior:  </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                                <input type="text" id="ingresos_brutos_terceros" name="ingresos_brutos_terceros" class="form-control registerForm" required tabindex="5" placeholder="ingresos_brutos" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="" maxlength="30">
                                      
                                            </div>
                                         
                                            
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               
                              
                               
                            </div>
                            <div class="col-md-12" style="text-align: justify;">
                            </div>
                        </span>
                    </div>
                </div>
        
            
              
                
                            <div class="form-row" style="float:right">
                                <a id="secondStepBtnBack" class="btn btn-back registerForm" align="right" href="#"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                                <a id="secondStepBtnNext" class="btn btn-info registerForm" align="right" href="#"> Siguiente <span class="glyphicon glyphicon-step-forward"></span></a>
                            </div>
               
           
               
            </form>
        </div>
        <div id="thirdStep" class="hidden" style="margin-top:30px">
            <form id="thirdStepForm" name="thirdStepForm" method="POST" action="{{asset('/user')}}" id="salesForm">
                {{ csrf_field() }}
                <input type="hidden" id="documentId" name="documentId" value="">
                <input type="hidden" id="saleId" name="saleId" value="{{$sales_id}}">
                <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;margin-bottom: 5px;margin-top:30px;">
                    <div class="wizard_activo registerForm titleDiv" onclick="fadeToggle('pepDeclarationDiv')">
                        <a href="#" class="titleLink">Declaración y Autorización</a>
                        <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                    </div>
                    <div id="pepDeclarationDiv" class="col-md-12">
                        <div class="" style="text-align: justify; font-size: 14px;margin-bottom: 10px;">
                            <div class="col-md-12" style="margin-top:10px;">
                                <div class="form-group">
                                    <h4 id="declaracion" class="declaracion">Declaración</h4>
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
                            @if($person_exposed == null)
                                <div class="col-md-12" style="margin-bottom:15px;">
                                    Declaración sobre la condición de Persona Expuesta Políticamente PEP (Persona que desempeña o ha desempeñado funciones públicas en el país o en el exterior). Informo que he leído la Lista Mínima de Cargos Públicos a ser considerados "Personas Expuestas Políticamente" y declaro bajo juramento que <label class="radio-inline" style="padding-left:5px;padding-right: 5px;">Si <input type="radio" name="optradio3" value="yes" style="margin-left:5px;margin-top: 0px;" {{$disable_status}}></label> <label class="radio-inline" style="padding-left:5px; padding-right:15px;">No <input type="radio" name="optradio3" value="no" checked style="margin-left:5px;margin-top: 0px;" {{$disable_status}}></label><br> me encuentro ejerciendo uno de los cargos incluidos en la lista o lo ejercí hace un año atrás. En el caso de que la respuesta sea positiva, indicar: Cargo/Función/Jerarquía:  <input type="text2" id="pep_client" name="pep_client" class="form-control registerForm" required tabindex="2" placeholder="Cargo/Función/Jerarquía" onchange="removeInputRedFocus(this.id)" value="" {{$disable_status}}>
                                    Nota: La presente declaración no constituye una autoincriminación de ninguna clase, ni conlleva ninguna responsabilidad administrativa, civil o penal.
                                </div>
                            @else
                                <div class="col-md-12" style="margin-bottom:15px;">
                                    Declaración sobre la condición de Persona Expuesta Políticamente PEP (Persona que desempeña o ha desempeñado funciones públicas en el país o en el exterior). Informo que he leído la Lista Mínima de Cargos Públicos a ser considerados "Personas Expuestas Políticamente" y declaro bajo juramento que <label class="radio-inline" style="padding-left:5px;padding-right: 5px;">Si <input type="radio" name="optradio3" value="yes" @if($person_exposed == 'yes') checked @endif style="margin-left:5px;margin-top: 0px;"{{$disable_status}}></label> <label class="radio-inline" style="padding-left:5px; padding-right:15px;" {{$disable_status}}>No <input type="radio" name="optradio3" value="no" @if($person_exposed == 'no') checked @endif style="margin-left:5px;margin-top: 0px;"></label><br> me encuentro ejerciendo uno de los cargos incluidos en la lista o lo ejercí hace un año atrás. En el caso de que la respuesta sea positiva, indicar: Cargo/Función/Jerarquía:  <input type="text2" id="pep_client" name="pep_client" class="form-control registerForm" required tabindex="2" placeholder="Cargo/Función/Jerarquía" onchange="removeInputRedFocus(this.id)" value="{{$pep_client}}" {{$disable_status}}>
                                    Nota: La presente declaración no constituye una autoincriminación de ninguna clase, ni conlleva ninguna responsabilidad administrativa, civil o penal.
                                </div>
                            @endif
                            <hr>
                            <br>
                            <div class="col-md-12" style="margin-top:-25px;">
                                <div class="form-group">
                                    <h4 id="declaracion">Autorización</h4>
                                </div>
                            </div>  
                            <hr>
                            <div class="col-md-12" style="margin-bottom:15px;">
                                Siendo conocedor de las disposiciones legales, autorizo expresamente en forma libre, voluntaria e irrevocable a Seguros Sucre S. A., a realizar el análisis y las verificaciones que considere necesarias para corroborar la licitud de fondos y bienes comprendidos en el contrato de seguro e informar a las autoridades competentes si fuera el caso; además autorizo expresa, voluntaria e irrevocablemente a todas las personas naturales o jurídicas de derecho público o privado a facilitar a Seguros Sucre S.A. toda la información que ésta les requiera  y revisar los buró de crédito sobre mi información de riesgos crediticios. 
                            </div>

                          

                            <div class="row">
                                
                                    <div class="col-sm-4">
                                       
                                    </div>
                                    <div  id="firma" class="col-sm-4">
                                        <input type="text" id="firma_representante" name="firma_representante" class="form-control registerForm" required tabindex="18" placeholder="firma_representante" onchange="removeInputRedFocus(this.id)" value="" {{$disable_status}} maxlength="100">
                                        
                                    </div>
                                    <div class="col-sm-4">
                                        
                                        
                                    </div>
                                
                            </div>
                            <div class="row">
                                
                                    <div class="col-sm-4">
                                        
                                    </div>
                                    <div id="firma" class="col-sm-4">
                                        <label class="registerForm" for="firma_representante"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>Firma </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" id="firma_representante_cedula" name="firma_representante_cedula" class="form-control registerForm" required tabindex="18" placeholder="firma_representante_cedula" onchange="removeInputRedFocus(this.id)" value="" {{$disable_status}} maxlength="100">
                                        
                                    </div>
                                    <div class="col-sm-4">
                                        
                                        
                                    </div>
                                
                            </div>
                            <div class="row">
                                
                                    <div class="col-sm-4">
                                        
                                    </div>
                                    <div  id="firma" class="col-sm-4">
                                        <label class="registerForm" for="celular"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>C.I </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        
                                    </div>
                                    <div class="col-sm-4">
                                        
                                        
                                    </div>
                                
                            </div>

                            

                           

                           

                            
                            
                            
                        </div>
                        
                   

                    <div id="sucreExclusivoDiv" class="col-md-12">
                        <div class="" style="text-align: justify; font-size: 14px;margin-bottom: 10px;">
                            <div class="col-md-12" style="margin-top:10px;">
                                <div class="form-group">
                                    <h4 id="declaracion" class="declaracion">USO EXCLUSIVO DE SEGUROS SUCRE S.A.</h4>
                                    <h5>Datos de la Relación Comercial:</h5>
                                </div>
                            </div>
                            <hr>
                            <div class="col-md-12" style="margin-bottom:15px;">
                                <div class="form-group col-md-6">
                                        <table><tr>
                                        <td id="tipo_empresa"> Nueva:</td><td id="tipo_empresa"> <input  type="checkbox"   id="datos_nueva" name="datos_nueva" value="datos_nueva" class="form-control move-left" required tabindex="10" placeholder="datos_nueva" disabled="disabled" onchange="removeInputRedFocus(this.id)" value=" "></td><td></td><td></td><td></td><td></td>
                                        </tr> 
                                        <tr>
                                        <td id="tipo_empresa">Renovación:</td><td id="tipo_empresa"> <input  type="checkbox"   id="datos_renovacion" name="datos_renovacion" value="udatos_renovacion" class="form-control move-left" required tabindex="11" placeholder="datos_renovacion" disabled="disabled" onchange="removeInputRedFocus(this.id)" value=" -"></td><td id="tipo_empresa"></td><td id="tipo_empresa"></td><td id="tipo_empresa"></td><td id="tipo_empresa"></td>
                                        </tr>
                                        <tr>
                                        <td id="tipo_empresa">Ramo:</td><td id="tipo_empresa"> <input  type="checkbox"   id="datos_ramo" name="datos_ramo" value="datos_ramo" class="form-control move-left" required tabindex="12" placeholder="datos_ramo" disabled="disabled" onchange="removeInputRedFocus(this.id)" value=""></td><td></td><td></td><td></td><td></td>
                                        </tr>
                                        <tr>
                                        <td id="tipo_empresa">Suma Asegurada</td><td id="tipo_empresa"> <input  type="checkbox"   id="datos_suma_asegurada" name="datos_suma_asegurada" value="datos_suma_asegurada" class="form-control move-left" required tabindex="13" placeholder="datos_suma_asegurada" disabled="disabled" onchange="removeInputRedFocus(this.id)" value=" -"></td><td id="tipo_empresa"></td><td id="tipo_empresa"></td><td id="tipo_empresa"></td><td id="tipo_empresa"></td>
                                        </tr>
                                        <tr>
                                        <td id="tipo_empresa">Canal de Vinculación:</td><td id="tipo_empresa"> <input  type="checkbox"   id="datos_canal_vinculacion" name="datos_canal_vinculacion" value="datos_canal_vinculacion" class="form-control move-left" required tabindex="14" placeholder="datos_canal_vinculacion" disabled="disabled" onchange="removeInputRedFocus(this.id)" value=" -"></td><td></td><td></td><td></td><td></td>
                                        </tr>
                                        </table>

                                </div>

                            </div>
                            <hr>
                            <div class="col-md-12" style="margin-bottom:15px;">
                            </div>  
                            
                      
                            <br>
                            <div class="col-md-12" style="margin-top:-25px;">
                                <div class="form-group">
                                    <h4 id="declaracion">Nombre y firma del Ejecutivo que verifica la documentación e información:</h4>
                                </div>
                            </div>
                            <br>  

                            <div class="col-md-12">
                                <div class="form-group">
                                <label class="registerForm" for="representante_telefono"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>Nombres Completos: </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" id="nombres_ejecutivo" name="nombres_ejecutivo" class="form-control registerForm" required tabindex="14" placeholder="nombres_ejecutivo" onchange="removeInputRedFocus(this.id)" value="{{$main_road}}" {{$disable_status}} maxlength="90">
                                </div>
                            </div>
                            <hr>
                            <div class="col-md-12" style="margin-bottom:15px;">
                            Confirmo que he revisado la razonabilidad de la información proporcionada por el pagador y declaro que he verificado la documentación e información solicitada de acuerdo a lo establecido en la política "Conozca su Cliente" y he analizado la información respecto a la actividad económica e ingresos, los cuales concuerdan con los productos solicitados.                            </div>

                          

                            <div class="row">
                                
                                    <div class="col-sm-4">
                                       
                                    </div>
                                    <div  id="firma" class="col-sm-4">
                                        <input type="text" id="firma_responsable_comercial" name="firma_responsable_comercial" class="form-control registerForm" required tabindex="18" placeholder="firma_responsable_comercial" onchange="removeInputRedFocus(this.id)" value="" {{$disable_status}} maxlength="100">
                                        
                                    </div>
                                    <div class="col-sm-4">
                                        
                                        
                                    </div>
                                
                            </div>
                            <div class="row">
                                
                                    <div class="col-sm-4">
                                        
                                    </div>
                                    <div id="firma" class="col-sm-4">
                                        <label class="registerForm" for="firma_responsable_comercial"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>Firma del Responsable Comercial  </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" id="fecha_firma_responsable_comercial" name="fecha_firma_responsable_comercial" class="form-control registerForm" required tabindex="18" placeholder="fecha" onchange="removeInputRedFocus(this.id)" value="" {{$disable_status}} maxlength="100">
                                        
                                    </div>
                                    <div class="col-sm-4">
                                        
                                        
                                    </div>
                                
                            </div>
                            <div class="row">
                                
                                    <div class="col-sm-4">
                                        
                                    </div>
                                    <div  id="firma" class="col-sm-4">
                                        <label class="registerForm" for="fecha"><span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span>Fecha </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        
                                    </div>
                                    <div class="col-sm-4">
                                        
                                        
                                    </div>
                                
                            </div>

                            

                           
</div>
                           

                            
                            
                            
                        </div>
                        
                    </div>
                    <input type="hidden" id="exposedPersonInput" name="exposedPersonInput" value="{{$person_exposed}}">
                </div>
                <div class="col-md-12" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                    <div class="form-row" style="float:left">
                        <!--<a class="btn btn-default registerForm" align="right" href="{{asset('/sales')}}" style="margin-left: -30px;"> Cancelar </a>-->
                    </div>
                    <div class="form-row" style="float:right">
                        <a id="thirdStepBtnBack" class="btn btn-back registerForm" align="right" href="#"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                        <a id="thirdStepBtnNext" class="btn btn-info registerForm" align="right" href="#"> Siguiente <span class="glyphicon glyphicon-step-forward"></span></a>
                    </div>
                </div>
            </form>
        </div>
        <div id="fourthStep" class="hidden" style="margin-top:30px">
            <!--<form id="fourthStepForm" name="fourthtepForm" method="POST" action="{{asset('/user')}}" id="salesForm">-->
            {{ csrf_field() }}
            <input type="hidden" id="documentId" name="documentId" value="">
            <input type="hidden" id="saleId" name="saleId" value="{{$sales_id}}">
            <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;margin-bottom: 5px;margin-top:30px;">
                <div class="wizard_activo registerForm titleDiv" onclick="fadeToggle('picturesDiv')">
                    <a href="#" class="titleLink">DOCUMENTOS REQUERIDOS - PERSONA JURÍDICA</a>
                    <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                </div>
                <div id="picturesDiv" class="col-md-12" style="margin-top:25px;">
                    <div class="col-md-6" style="margin: 5px 0;">
                            {{ csrf_field() }}    
                            <center><label class="registerForm">DOCUMENTOS REQUERIDOS - PERSONA JURÍDICA</label></center>
                            <input type="hidden" id="documentId" name="documentId" value="">
                            <input type="hidden" id="saleId" name="saleId" value="{{$sales_id}}">
                            
                            <div class="form-group">
                                <ul>
                                    <li>Copia del registro único de contribuyentes (RUC) o número análogo.</li>
                                    <li>Copia del documento de identificación del representante legal o apoderado.</li>
                                    <li>Copia certificada del nombramiento del representante legal o apoderado.</li>
                                </ul>
                            </div>
                            
                            
                            
                    </div>
                    
                    
            </div>
          
            <hr>
            <div class="col-md-12" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                <div class="form-row" style="float:left">
                    <!--<a class="btn btn-default registerForm" align="right" href="{{asset('/sales')}}" style="margin-left: -30px;"> Cancelar </a>-->
                </div>
                <div class="form-row" style="float:right">
                    <a id="fourthStepBtnBack" class="btn btn-back registerForm" align="right" href="#"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                    @if($disable_status == null)
                        <a id="fourthStepBtnNext" class="btn btn-info registerForm" align="right" href="#"> Siguiente <span class="glyphicon glyphicon-step-forward"></span></a>
                    @endif
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
                    <!--<a class="btn btn-default registerForm" align="right" href="{{asset('/sales')}}" style="margin-left: -30px;"> Cancelar </a>-->
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
                            <input type="text2" id="searchEconomicActivity" name="searchEconomicActivity" class="form-control registerForm" required  placeholder="Busqueda de Actividad Economica" onchange="removeInputRedFocus(this.id)" {{$disable_status}} maxlength="15" style="margin-right:5px;">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-info" @if($disable_status) disabled="disabled" @else @endif onclick="economicActivitySearch()" style="margin-top:22px"><span class="glyphicon glyphicon-search"></span></button>
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
@endsection
