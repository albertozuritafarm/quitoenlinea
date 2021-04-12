@extends('layouts.remote_app')

@section('content')
<script src="{{ assets('js/registerCustom.js') }}"></script>
<script src="{{ assets('js/vinculation/pj/v5.js') }}"></script>
<script src="{{ assets('js/customer/create.js') }}"></script>
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
@if($spouse_document == null)
<script>
    $(document).ready(function () {
    var div = document.getElementById('spouseFullDiv');
    $(div).fadeOut();
    });</script>
@endif
@if($customer->document_id != 3)
<script>
    $(document).ready(function () {
    var div = document.getElementById('passportFullDiv');
    $(div).fadeOut();
    });</script>
@endif
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
<meta name="csrf-token" content="{{ csrf_token() }}" />
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
            <form id="firstStepForm" name="firstStepForm" method="POST" action="{{asset('/user')}}" id="salesForm">
                {{ csrf_field() }}
                <input type="hidden" id="documentId" name="documentId" value="{{$customer->id}}">
                <input type="hidden" id="saleId" name="saleId" value="{{$sales_id}}">
                <div id="productAlert" class="alert alert-danger hidden">
                    <strong>¡Alerta!</strong> Debe seleccionar un producto
                </div>
                <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;margin-bottom: 5px">
                    <div class="wizard_activo registerForm titleDiv" onclick="fadeToggle('personalDiv')">
                        <a href="#" class="titleLink">Datos de la Compañia</a>
                        <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                    </div>
                    <div id="personalDiv" class="col-md-12">
                        @if($customer == false)
                        <input type="hidden" id="customerCheck" value="0">
                        @else
                        <input type="hidden" id="customerCheck" value="1">
                        @endif
                        <div class="col-md-6">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="business_name"> Razón Social</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" id="business_name" name="business_name" class="form-control registerForm" required tabindex="2" placeholder="Razón Social" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="{{$customer->first_name}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document_id"> RUC</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input id="documentForm" type="text" id="documentCompany" name="documentCompany" class="form-control registerForm" required tabindex="2" placeholder="Número de Identificación" disabled="disabled" onchange="removeInputRedFocus(this.id)" value="{{$customer->document}}">
                            </div>
                        </div>
                        <div class="col-md-12" style="margin-top:-25px;">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="occupation"> Actividad Economica</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <select id="occupation" name="occupation" class="form-control registerForm" required onchange="removeInputRedFocus(this.id)" @if($nationality_id != null) disabled="disabled" @endif>
                                    <option value="">--Escoja Una---</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6" style="margin-top:-25px;">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="countryConstitutionCompany"> Pais de Constitution</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <select id="countryConstitutionCompany" name="countryConstitutionCompany" class="form-control registerForm" required onchange="removeInputRedFocus(this.id)">
                                    <option value="">--Escoja Una---</option>
                                    @foreach($countries as $cou)
                                    <option value="{{$cou->id}}">{{$cou->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="main_road"> Dirección/Calle Principal</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" id="main_roadCompany" name="main_roadCompany" class="form-control registerForm" required tabindex="2" placeholder="Calle Principal" onchange="removeInputRedFocus(this.id)" value="{{$main_road}}" {{$disable_status}}>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="sector"> N°</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" id="numberCompany" name="numberCompany" class="form-control registerForm" required tabindex="2" placeholder="N°" onchange="removeInputRedFocus(this.id)" value="{{$address_number}}" {{$disable_status}}>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="country"> Pais</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <select id="countryCompany" name="countryCompany" class="form-control registerForm" required onchange="removeInputRedFocus(this.id)">
                                    <option value="">--Escoja Una---</option>
                                    @foreach($countries as $cou)
                                    <option value="{{$cou->id}}">{{$cou->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document_id"> Cantón</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <select id="cityCompany" name="cityCompany" class="form-control registerForm" required tabindex="2" onchange="removeInputRedFocus(this.id)" {{$disable_status}}>
                                    <option value="">--Escoja Una---</option>
                                    @if($addressCities != null)
                                    @foreach($addressCities as $cit)
                                    <option @if($cit->id == $city_id) selected @endif value="{{$cit->id}}">{{$cit->name}}</option>
                                    @endforeach
                                    @else
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="mobile_phone"> Celular</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" id="mobile_phoneCompany" name="mobile_phoneCompany" class="form-control registerForm" required tabindex="2" placeholder="Celular" onchange="removeInputRedFocus(this.id)" value="{{$mobile_phone}}" {{$disable_status}}>
                            </div>
                        </div>
                        <div class="col-md-6" style="margin-top:-25px;">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="constitution_date"> Fecha de Constitución</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="date" id="constitution_dateCompany" name="constitution_dateCompany" class="form-control registerForm" style="line-height: 15px !important" onchange="removeInputRedFocus(this.id)" value="{{$birth_date}}" @if($birth_date != null) readonly="readonly" @endif>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="main_road"> Transversal</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" id="secondary_roadCompany" name="secondary_roadCompany" class="form-control registerForm" required tabindex="2" placeholder="Calle Transversal" onchange="removeInputRedFocus(this.id)" value="{{$secondary_road}}" {{$disable_status}}>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="sector"> Sector</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" id="sectorCompany" name="sectorCompany" class="form-control registerForm" required tabindex="2" placeholder="Sector" onchange="removeInputRedFocus(this.id)" value="{{$sector}}" {{$disable_status}}>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document_id"> Provincia  </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <select id="provinceCompany" name="provinceCompany" class="form-control registerForm" required tabindex="2" onchange="removeInputRedFocus(this.id)" {{$disable_status}}>
                                    <option selected="true" value="" disabled="disabled">--Escoja Una---</option>
                                    @foreach($provinces as $prov)
                                    <option @if($prov->id == $province_id) selected @endif value="{{$prov->id}}">{{$prov->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="email"> Email</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="email" id="emailCompany" name="emailCompany" class="form-control registerForm" required tabindex="2" placeholder="Correo" onchange="removeInputRedFocus(this.id)" value="{{$email}}" {{$disable_status}}>
                                <p id="emailError" style="color:red;font-weight: bold"></p>    
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="phone">Teléfono</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" id="phoneCompany" name="phoneCompany" class="form-control registerForm" required tabindex="2" placeholder="Teléfono" onchange="removeInputRedFocus(this.id)" value="{{$phone}}" {{$disable_status}}>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div id="legalRepresentativeFullDiv" class="col-xs-12 col-md-12 border" style="padding-left:0px !important;margin-bottom: 5px">
                    <div class="wizard_activo registerForm titleDiv" onclick="fadeToggle('legalRepresentativeDiv')">
                        <a href="#" class="titleLink">Datos del Representante Legal</a>
                        <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                    </div>
                    <div id="legalRepresentativeDiv" class="col-md-12">
                        <div id="customerForm">
                            <div class="col-md-6">
                                <div class="form-group form-inline">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Cédula</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <!--<input type="text" class="form-control registerForm" name="document" id="document" placeholder="Cédula" value="{{ old('document') }}" required="required">-->
                                    <div class="form-inline">
                                        <input autocomplete="off" type="text" class="form-control registerForm" name="document" id="document" placeholder="Cédula" required="required"tabindex="1" style="width:88%" value="{{old('document')}}" onkeyup="removeErrorMsg('Document')" onchange="removeInputRedFocus(this.id)">
                                        <button type="button" class="btn btn-info" id="documentBtn" onclick="documentBtn()"><span class="glyphicon glyphicon-search"></span></button>
                                        @if(session('errorDocument')) <p id="errorMsgDocument" style="color:red;font-weight: bold">{{session('errorDocument')}}</p> @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="first_name"> Primer Nombre</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="first_name" id="first_name" placeholder="Primer Nombre" value="{{ old('first_name') }}" required="required" tabindex="3" onkeyup="removeErrorMsg('FirstName')" onchange="removeInputRedFocus(this.id)">
                                    @if(session('errorFirstName')) <p id="errorMsgFirstName" style="color:red;font-weight: bold">{{session('errorFirstName')}}</p> @endif
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="second_name"> Segundo Nombre</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="second_name" id="second_name" placeholder="Segundo Nombre" value="{{ old('second_name') }}" required="required" tabindex="3" onkeyup="removeErrorMsg('FirstName')" onchange="removeInputRedFocus(this.id)">
                                    @if(session('errorSecondName')) <p id="errorMsgSecondName" style="color:red;font-weight: bold">{{session('errorSecondName')}}</p> @endif
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="birth_date"> Fecha de Nacimiento</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="date" id="birth_date" name="birth_date" class="form-control registerForm" style="line-height: 15px !important" onchange="removeInputRedFocus(this.id)" value="{{$birth_date}}" @if($birth_date != null) readonly="readonly" @endif tabindex="8" onchange="removeInputRedFocus(this.id)">
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="birthProvince"> Canton de Nacimiento</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select name="birthProvince" class="form-control registerForm" id="birthProvince" required tabindex="10" onchange="removeInputRedFocus(this.id)">

                                        @if(old('province'))
                                        <option id="provinceSelect"  value="{{old('province')}}">{{session('provinceName')}}</option>
                                        @else
                                        <option id="provinceSelect" selected="true" disabled="disabled" value="">--Escoja Una---</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="city"> Celular</label> <span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span><label class="own"><span class="glyphicon glyphicon-info-sign" style="color:#0099ff"><span class="own1" style="float:left">El celular debe contener 10 caracteres</span></span></label>
                                    <input type="text" class="form-control registerForm" name="mobile_phone" id="mobile_phone" placeholder="Celular" value="{{ old('mobile_phone') }}" required tabindex="5" onkeyup="removeErrorMsg('MobilePhone')" onchange="removeInputRedFocus(this.id)">
                                    @if(session('errorMobilePhone')) <p id="errorMsgMobilePhone" style="color:red;font-weight: bold">{{session('errorMobilePhone')}}</p> @endif
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="city"> Direccion</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="address" id="address" placeholder="Direccion" required tabindex="7" value="{{old('address')}}" onkeyup="removeErrorMsg('Address')" onchange="removeInputRedFocus(this.id)">
                                    @if(session('errorAddress')) <p id="errorMsgAddress" style="color:red;font-weight: bold">{{session('errorAddress')}}</p> @endif
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="country"> Pais</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select name="country" id="country" class="form-control registerForm" required tabindex="9" onchange="removeInputRedFocus(this.id)">
                                        <option value="">--Escoja Una---</option>
                                        @foreach($countries as $country)
                                        <option @if($country->id == old('country')) selected="true" @else @endif value="{{$country->id}}">{{$country->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="city"> Ciudad</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select name="city" class="form-control registerForm" id="city" required tabindex="11" onchange="removeInputRedFocus(this.id)">
                                        @if(old('city'))
                                        <option id="citySelect"  value="{{old('city')}}">{{session('cityName')}}</option>
                                        @else
                                        <option id="citySelect" selected="true" disabled="disabled" value="">--Escoja Una---</option>
                                        @endif
                                    </select>
                                    @if(session('errorCity')) <p id="errorMsgCity" style="color:red;font-weight: bold">{{session('errorCity')}}</p> @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document_id"> Tipo Documento</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select id="document_id" name="document_id" class="form-control registerForm" value="{{old('document_id')}}" required tabindex="2" onchange="removeInputRedFocus(this.id)">
                                        <option value="">--Escoja Una---</option>
                                        @foreach($documents as $document)
                                        <option @if($document->id == old('document_id')) selected="true" @else @endif value="{{$document->id}}">{{$document->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="last_name"> Primer Apellido</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="last_name" id="last_name" placeholder="Primer Apellido" value="{{ old('last_name') }}" required tabindex="4" onkeyup="removeErrorMsg('LastName')" onchange="removeInputRedFocus(this.id)">
                                    @if(session('errorLastName')) <p id="errorMsglastName" style="color:red;font-weight: bold">{{session('errorLastName')}}</p> @endif
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="second_last_name"> Segundo Apellido</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="second_last_name" id="second_last_name" placeholder="Segundo Apellido" value="{{ old('last_name') }}" required tabindex="4" onkeyup="removeErrorMsg('LastName')" onchange="removeInputRedFocus(this.id)">
                                    @if(session('errorSecondLastName')) <p id="errorMsgSecondlastName" style="color:red;font-weight: bold">{{session('errorSecondLastName')}}</p> @endif
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="country"> Pais de Nacimiento</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select name="birthCountry" id="birthCountry" class="form-control registerForm" required tabindex="9" onchange="removeInputRedFocus(this.id)">
                                        <option value="">--Escoja Una---</option>
                                        @foreach($countries as $country)
                                        <option @if($country->id == old('country')) selected="true" @else @endif value="{{$country->id}}">{{$country->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="birthCity"> Ciudad de Nacimiento</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select name="birthCity" class="form-control registerForm" id="birthCity" required tabindex="11" onchange="removeInputRedFocus(this.id)">
                                        @if(old('city'))
                                        <option id="citySelect"  value="{{old('city')}}">{{session('cityName')}}</option>
                                        @else
                                        <option id="citySelect" selected="true" disabled="disabled" value="">--Escoja Una---</option>
                                        @endif
                                    </select>
                                    @if(session('errorCity')) <p id="errorMsgCity" style="color:red;font-weight: bold">{{session('errorCity')}}</p> @endif
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Teléfono</label> <span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span><label class="own"><span class="glyphicon glyphicon-info-sign" style="color:#0099ff"><span class="own1" style="float:left">El telefono debe tener 9 caracteres</span></span></label>
                                    <input type="text" class="form-control registerForm" name="phone" id="phone" placeholder="Teléfono" value="{{ old('phone') }}" required tabindex="6" onkeyup="removeErrorMsg('Phone')" onchange="removeInputRedFocus(this.id)">
                                    @if(session('errorPhone')) <p id="errorMsgPhone" style="color:red;font-weight: bold">{{session('errorPhone')}}</p> @endif
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="correo"> Correo</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="email" class="form-control registerForm" name="email" id="email" placeholder="Correo" value="{{old('email')}}" required tabindex="8" onkeyup="removeErrorMsg('Email')" onchange="removeInputRedFocus(this.id)">
                                    <p id="emailError" style="color:red;font-weight: bold"></p>    
                                    @if($errors->any())
                                    <span style="color:red;font-weight:bold">{{$errors->first()}}</span>
                                    @endif
                                    @if(session('errorEmail')) <p id="errorMsgEmail" style="color:red;font-weight: bold">{{session('errorEmail')}}</p> @endif
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="province"> Canton</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select name="province" class="form-control registerForm" id="province" required tabindex="10" onchange="removeInputRedFocus(this.id)">

                                        @if(old('province'))
                                        <option id="provinceSelect"  value="{{old('province')}}">{{session('provinceName')}}</option>
                                        @else
                                        <option id="provinceSelect" selected="true" disabled="disabled" value="">--Escoja Una---</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="civilState">Estado Civil</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select id="civilState" name="civilState" class="form-control registerForm" required tabindex="10" onchange="removeInputRedFocus(this.id)" {{$disable_status}}>
                                        <option value="">--Escoja Una---</option>
                                        @foreach($civilStates as $sta)
                                        <option @if($sta->id == $civil_state) selected @endif value="{{$sta->id}}">{{$sta->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
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
                                <input type="text" id="spouseDocument" name="spouseDocument" class="form-control registerForm" required tabindex="25"  style="line-height: 15px !important;width:96%" value="{{$spouse_document}}" placeholder="Documento de Identidad Conyuge" onchange="removeInputRedFocus(this.id)" {{$disable_status}}>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="passportEndDate"> Nombre(s) del Conyuge</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" id="spouseFirstName" name="spouseFirstName" class="form-control registerForm" required tabindex="28"  style="line-height: 15px !important" value="{{$spouse_name}}" placeholder="Nombre del Conyuge" onchange="removeInputRedFocus(this.id)" {{$disable_status}}>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="spouse_document_id"> Tipo Documento</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <select id="spouse_document_id" name="spouse_document_id" class="form-control registerForm" required tabindex="26" onchange="removeInputRedFocus(this.id),validateDocumentSpouse()" {{$disable_status}}>
                                    <option selected="true" value="">--Escoja Una---</option>
                                    @foreach($documents as $doc)
                                    <option @if($doc->id == $spouse_document_id) selected="true" @endif value="{{$doc->id}}">{{$doc->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="passportEndDate"> Apellido(s) del Conyuge </label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" id="spouseLastName" name="spouseLastName" class="form-control registerForm" required tabindex="28"  style="line-height: 15px !important" value="{{$spouse_last_name}}" placeholder="Apellido(s) del Conyuge" onchange="removeInputRedFocus(this.id)" value="{{$spouse_last_name}}" {{$disable_status}}>
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
                                <input id="is_beneficiary" name="is_beneficiary" type="checkbox" checked="checked" data-toggle="toggle" data-on="Si" data-off="No" onchange="isBeneficiaryChange(this)">
                            </div>
                        </div>
                        <span id="beneficiaryDataDiv" style="margin-top:-25px;">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="beneficiaryName"> Nombres Completos o Razón Social</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text2" id="beneficiaryName" name="beneficiaryName" checked class="form-control registerForm" required tabindex="2"  style="line-height: 15px !important" value="" placeholder="Nombres Completos o Razón Social" onchange="removeInputRedFocus(this.id)" {{$disable_status}}>
                                </div>
                            </div>
                            <div class="col-md-6" style="margin-top:-25px">
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="beneficiary_document_id"> Tipo Documento</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select id="beneficiary_document_id" name="beneficiary_document_id" class="form-control registerForm" required tabindex="2" onchange="removeInputRedFocus(this.id)">
                                        <option selected="true" value="" disabled="disabled">--Escoja Una---</option>
                                        @foreach($documents as $doc)
                                        <option value="{{$doc->id}}">{{$doc->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="beneficiary_nationality"> Nacionalidad</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select id="beneficiary_nationality" name="beneficiary_nationality" class="form-control registerForm" required onchange="removeInputRedFocus(this.id)">
                                        <option value="">--Escoja Una---</option>
                                        @foreach($countries as $cou)
                                        <option value="{{$cou->id}}">{{$cou->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="beneficiary_phone"> Teléfono</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text2" id="beneficiary_phone" name="beneficiary_phone" class="form-control registerForm" required tabindex="2"  style="line-height: 15px !important" value="" placeholder="Teléfono" onchange="removeInputRedFocus(this.id)" {{$disable_status}}>
                                </div>
                            </div>
                            <div class="col-md-6" style="margin-top:-25px">
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="beneficiary_document"> Documento de Identidad</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text2" id="beneficiary_document" name="beneficiary_document" class="form-control registerForm" required tabindex="2"  style="line-height: 15px !important" value="" placeholder="Documento" onchange="removeInputRedFocus(this.id)" {{$disable_status}}>
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="beneficiary_address"> Dirección de Domicilio</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text2" id="beneficiary_address" name="beneficiary_address" class="form-control registerForm" required tabindex="2"  style="line-height: 15px !important" value="" placeholder="Dirección de Domicilio" onchange="removeInputRedFocus(this.id)" {{$disable_status}}>
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="beneficiary_relationship"> Relación</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text2" id="beneficiary_relationship" name="beneficiary_relationship" class="form-control registerForm" required tabindex="2"  style="line-height: 15px !important" value="" placeholder="Relación" onchange="removeInputRedFocus(this.id)" {{$disable_status}}>
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
                        <!--<a class="btn btn-default registerForm" align="right" href="{{asset('/sales')}}" style="margin-left: -30px;"> Cancelar </a>-->
                    </div>
                    <div class="row"style="float:right">
                        <!--<a class="btn btn-default registerForm" align="right" href="{{asset('/sales')}}" style="margin-left: 30px;background-color: #444;color:white"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>-->
                        <a id="firstStepBtnNext" class="btn btn-info registerForm" align="right" href="#"> Siguiente <span class="glyphicon glyphicon-step-forward"></span></a>
                    </div>
                </div>
            </form>
        </div>
        <div id="secondStep" class="col-md-12 hidden" style="margin-top:20px">
            <form id="secondStepForm" name="secondStepForm" method="POST" action="{{asset('/user')}}" id="salesForm">
                {{ csrf_field() }}
                <input type="hidden" id="documentId" name="documentId" value="{{$customer->id}}">
                <input type="hidden" id="saleId" name="saleId" value="{{$sales_id}}">
                <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;margin-bottom: 5px">
                    <div class="wizard_activo registerForm titleDiv" onclick="fadeToggle('occupationDiv')">
                        <a href="#" class="titleLink">Situación Financiera</a>
                        <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                    </div>
                    <div id="occupationDiv" class="col-md-12">
                        <div class="col-md-12">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="annual_income"> Ingresos brutos anuales declarados en el año anterior</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text2" id="annual_income" name="annual_income" class="form-control registerForm" required tabindex="2"  style="line-height: 15px !important" value="" placeholder="Ingresos brutos anuales declarados en el año anterior" onchange="removeInputRedFocus(this.id)" {{$disable_status}}>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="col-md-12" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                    <div class="row" style="float:left">
                        <!--<a class="btn btn-default registerForm" align="right" href="{{asset('/sales')}}" style="margin-left: -30px;"> Cancelar </a>-->
                    </div>
                    <div class="row" style="float:right">
                        <a id="secondStepBtnBack" class="btn btn-back registerForm" align="right" href="#"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                        <a id="secondStepBtnNext" class="btn btn-info registerForm" align="right" href="#"> Siguiente <span class="glyphicon glyphicon-step-forward"></span></a>
                    </div>
                </div>
            </form>
        </div>
        <div id="thirdStep" class="col-md-12 hidden" style="margin-top:20px">
            <form id="thirdStepForm" name="thirdStepForm" method="POST" action="{{asset('/user')}}" id="salesForm">
                {{ csrf_field() }}
                <input type="hidden" id="documentId" name="documentId" value="{{$customer->id}}">
                <input type="hidden" id="saleId" name="saleId" value="{{$sales_id}}">
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
                                El asegurado declara expresamente que el seguro aquí convenido ampara bienes de procedencia lícita, no ligados con actividades de narcotráfico, lavado de dinero o cualquier otra actividad tipificada en la Ley Orgánica de Prevención, Detección y Erradicación del Delito de Lavado de Activos y del Financiamiento de Delitos. Igualmente, la prima a pagar por este concepto tiene origen lícito y ninguna relación con las actividades mencionadas anteriormente. Eximo a Seguros Sucre S.A. de toda responsabilidad, inclusive respecto a terceros, si esta declaración fuese falsa o errónea.                             
                            </div>  
                            <hr>
                            <div class="col-md-12" style="margin-bottom:15px;">
                                En caso de que se inicien investigaciones sobre mi persona, relacionadas con las actividades antes señaladas o de producirse transacciones inusuales o injustificadas, Seguros Sucre S.A., podrá proporcionar a las autoridades competentes toda la información que tenga sobre las mismas o que le sea requerida. En tal sentido renuncio a presentar en contra de Seguros Sucre S.A., sus funcionarios o empleados, cualquier reclamo o acción legal, judicial, extrajudicial, administrativa, civil penal o arbitral en la eventualidad de producirse tales hechos.
                            </div>
                            <hr>
                            <div class="col-md-12" style="margin-bottom:15px;">
                                Declaración sobre la condición de Persona Expuesta Políticamente PEP (Persona que desempeña o ha desempeñado funciones públicas en el país o en el exterior). Informo que he leído la Lista Mínima de Cargos Públicos a ser considerados "Personas Expuestas Políticamente" y declaro bajo juramento que <label class="radio-inline" style="padding-left:5px;padding-right: 5px;">Si <input type="radio" name="optradio3" value="yes" @if($person_exposed == 'yes') checked @endif style="margin-left:5px;margin-top: 0px;"></label> <label class="radio-inline" style="padding-left:5px; padding-right:15px;">No <input type="radio" name="optradio3" value="no" @if($person_exposed == 'no') checked @endif style="margin-left:5px;margin-top: 0px;"></label><br> me encuentro ejerciendo uno de los cargos incluidos en la lista o lo ejercí hace un año atrás. En el caso de que la respuesta sea positiva, indicar: Cargo/Función/Jerarquía:  <input type="text2" id="pep_client" name="pep_client" class="form-control registerForm" required tabindex="2" placeholder="Cargo/Función/Jerarquía" onchange="removeInputRedFocus(this.id)" value="" {{$disable_status}}>
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
                    <input type="hidden" id="exposedPersonInput" name="exposedPersonInput" value="{{$person_exposed}}">
                </div>
                <div class="col-md-12" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                    <div class="row" style="float:left">
                        <!--<a class="btn btn-default registerForm" align="right" href="{{asset('/sales')}}" style="margin-left: -30px;"> Cancelar </a>-->
                    </div>
                    <div class="row" style="float:right">
                        <a id="thirdStepBtnBack" class="btn btn-back registerForm" align="right" href="#"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                        <a id="thirdStepBtnNext" class="btn btn-info registerForm" align="right" href="#"> Siguiente <span class="glyphicon glyphicon-step-forward"></span></a>
                    </div>
                </div>
            </form>
        </div>
        <div id="fourthStep" class="col-md-12 hidden" style="margin-top:20px">
            <!--<form id="fourthStepForm" name="fourthtepForm" method="POST" action="{{asset('/user')}}" id="salesForm">-->
            {{ csrf_field() }}
            <input type="hidden" id="documentId" name="documentId" value="{{$customer->id}}">
            <input type="hidden" id="saleId" name="saleId" value="{{$sales_id}}">
            <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;margin-bottom: 5px">
                <div class="wizard_activo registerForm titleDiv" onclick="fadeToggle('picturesDivpicturesDiv')">
                    <a href="#" class="titleLink">Documentos Requeridos - Persona Juridica</a>
                    <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                </div>
                <div id="picturesDiv" class="col-md-12">
                    <div class="col-md-12">
                        <div class="col-md-6 form-group">
                            <form method="post" id="upload_formDocumentApplicant" name="upload_formDocumentApplicant" enctype="multipart/form-data" onsubmit="uploadPictureForm('upload_formDocumentApplicant'">
                                {{ csrf_field() }}                                
                                <input type="hidden" id="documentId" name="documentId" value="{{$customer->id}}">
                                <input type="hidden" id="saleId" name="saleId" value="{{$sales_id}}">
                                <input type="hidden" id="uploadType" name="uploadType" value="DocumentApplicant">
                                <input type="hidden" id="uploadedFileDocumentApplicant" name="uploadedFileDocumentApplicant" value="{{$picture_document_applicant}}">
                                <center>
                                    <label>Copia del registro único de contribuyentes (RUC)</label>
                                    <div class="alert" id="messageDocumentApplicant" style="display: none"></div>
                                    <div style="width:100px !important;padding: 0" class="inside" id="fileNameDocumentApplicant"></div>
                                    <div class="inputWrapper"><span id="uploaded_imageDocumentApplicant">{!! $picture_document_applicant !!}</span>
                                        <center>
                                            <img src="{{asset('images/mas.png')}}" alt="Girl in a jacket" style="width:20px;height:20px;margin-bottom: -100px;">
                                        </center>
                                        <input class="fileInput" type="file" name="select_fileDocumentApplicant" onchange="fileNameFunction('DocumentApplicant')" id="select_fileDocumentApplicant">
                                    </div>
                                </center>
                                <center>
                                    <button type="submit" name="upload" id="uploadDocumentApplicant" class="btn btn-primary @if($picture_document_applicant == null) visible @else hidden @endif" onclick="uploadPictureForm('DocumentApplicant')">
                                        <span class="glyphicon glyphicon-upload"></span> Subir Foto
                                    </button>
                                    <a class="@if($picture_document_applicant == null) hidden @else visible @endif" id="deletePictureDocumentApplicant" href="#" onclick="deletePictureForm('DocumentApplicant','{{$customer->id}}','{{$sales_id}}')">
                                        <img src="{{asset('/images/menos.png')}}" style="width:20px;height:20px">
                                    </a>  
                                </center>
                            </form>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Fecha de Caducidad</label>
                            <input type="date" class="form-control" style="line-height:14px;">
                            <br>
                            <form method="post" id="upload_formDocumentSpouse" name="upload_formDocumentSpouse" enctype="multipart/form-data" onsubmit="uploadPictureForm('upload_formDocumentSpouse'">
                                {{ csrf_field() }}                                
                                <input type="hidden" id="documentId" name="documentId" value="{{$customer->id}}">
                                <input type="hidden" id="saleId" name="saleId" value="{{$sales_id}}">
                                <input type="hidden" id="uploadType" name="uploadType" value="DocumentSpouse">
                                <input type="hidden" id="uploadedFileDocumentSpouse" name="uploadedFileDocumentSpouse" value="{{$picture_document_spouse}}">
                                <center>
                                    <label>Copia del documento de identificación del representante legal o apoderado</label>
                                    <div class="alert" id="messageDocumentSpouse" style="display: none"></div>
                                    <div style="width:100px !important;padding: 0" class="inside" id="fileNameDocumentSpouse"></div>
                                    <div class="inputWrapper"><span id="uploaded_imageDocumentSpouse">{!! $picture_document_spouse !!}</span>
                                        <center>
                                            <img src="{{asset('images/mas.png')}}" alt="Girl in a jacket" style="width:20px;height:20px;margin-bottom: -100px;">
                                        </center>
                                        <input class="fileInput" type="file" name="select_fileDocumentSpouse" onchange="fileNameFunction('DocumentSpouse')" id="select_fileDocumentSpouse">
                                    </div>
                                </center>
                                <center>
                                    <button type="submit" name="upload" id="uploadDocumentSpouse" class="btn btn-primary @if($picture_document_spouse == null) visible @else hidden @endif" onclick="uploadPictureForm('DocumentSpouse')">
                                        <span class="glyphicon glyphicon-upload"></span> Subir Foto
                                    </button>
                                    <a class="@if($picture_document_spouse == null) hidden @else visible @endif" id="deletePictureDocumentSpouse" href="#" onclick="deletePictureForm('DocumentSpouse','{{$customer->id}}','{{$sales_id}}')">
                                        <img src="{{asset('/images/menos.png')}}" style="width:20px;height:20px">
                                    </a>  
                                </center>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-6 form-group">
                            <label>Fecha de Caducidad</label>
                            <input type="date" class="form-control" style="line-height:14px;">
                            <br>
                            <form method="post" id="upload_formService" name="upload_formService" enctype="multipart/form-data" onsubmit="uploadPictureForm('upload_formService'">
                                {{ csrf_field() }}                                
                                <input type="hidden" id="documentId" name="documentId" value="{{$customer->id}}">
                                <input type="hidden" id="saleId" name="saleId" value="{{$sales_id}}">
                                <input type="hidden" id="uploadType" name="uploadType" value="Service">
                                <input type="hidden" id="uploadedFilePictureService" name="uploadedFilePictureService" value="{{$picture_service}}">
                                <center>
                                    <label>Copia del documento de identificación del cónyuge o conviviente del representante legal o apoderado, si aplica</label>
                                    <div class="alert" id="messageService" style="display: none"></div>
                                    <div style="width:100px !important;padding: 0" class="inside" id="fileNameService"></div>
                                    <div class="inputWrapper"><span id="uploaded_imageService">{!! $picture_service !!}</span>
                                        <center>
                                            <img src="{{asset('images/mas.png')}}" alt="Girl in a jacket" style="width:20px;height:20px;margin-bottom: -100px;">
                                        </center>
                                        <input class="fileInput" type="file" name="select_fileService" onchange="fileNameFunction('Service')" id="select_fileService">
                                    </div>
                                </center>
                                <center>
                                    <button type="submit" name="upload" id="uploadService" class="btn btn-primary @if($picture_service == null) visible @else hidden @endif" onclick="uploadPictureForm('Service')">
                                        <span class="glyphicon glyphicon-upload"></span> Subir Foto
                                    </button>
                                    <a class="@if($picture_service == null) hidden @else visible @endif" id="deletePictureService" href="#" onclick="deletePictureForm('Service','{{$customer->id}}','{{$sales_id}}')">
                                        <img src="{{asset('/images/menos.png')}}" style="width:20px;height:20px">
                                    </a>  
                                </center>
                            </form>
                        </div>
                        <div class="col-md-6 form-group">
                            <form method="post" id="upload_formService" name="upload_formService" enctype="multipart/form-data" onsubmit="uploadPictureForm('upload_formService'">
                                {{ csrf_field() }}                                
                                <input type="hidden" id="documentId" name="documentId" value="{{$customer->id}}">
                                <input type="hidden" id="saleId" name="saleId" value="{{$sales_id}}">
                                <input type="hidden" id="uploadType" name="uploadType" value="Service">
                                <input type="hidden" id="uploadedFilePictureService" name="uploadedFilePictureService" value="{{$picture_service}}">
                                <center>
                                    <label>Copia de una planilla de servicios básicos.</label>
                                    <div class="alert" id="messageService" style="display: none"></div>
                                    <div style="width:100px !important;padding: 0" class="inside" id="fileNameService"></div>
                                    <div class="inputWrapper"><span id="uploaded_imageService">{!! $picture_service !!}</span>
                                        <center>
                                            <img src="{{asset('images/mas.png')}}" alt="Girl in a jacket" style="width:20px;height:20px;margin-bottom: -100px;">
                                        </center>
                                        <input class="fileInput" type="file" name="select_fileService" onchange="fileNameFunction('Service')" id="select_fileService">
                                    </div>
                                </center>
                                <center>
                                    <button type="submit" name="upload" id="uploadService" class="btn btn-primary @if($picture_service == null) visible @else hidden @endif" onclick="uploadPictureForm('Service')">
                                        <span class="glyphicon glyphicon-upload"></span> Subir Foto
                                    </button>
                                    <a class="@if($picture_service == null) hidden @else visible @endif" id="deletePictureService" href="#" onclick="deletePictureForm('Service','{{$customer->id}}','{{$sales_id}}')">
                                        <img src="{{asset('/images/menos.png')}}" style="width:20px;height:20px">
                                    </a>  
                                </center>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-6 form-group">
                            <form method="post" id="upload_formService" name="upload_formService" enctype="multipart/form-data" onsubmit="uploadPictureForm('upload_formService'">
                                {{ csrf_field() }}                                
                                <input type="hidden" id="documentId" name="documentId" value="{{$customer->id}}">
                                <input type="hidden" id="saleId" name="saleId" value="{{$sales_id}}">
                                <input type="hidden" id="uploadType" name="uploadType" value="Service">
                                <input type="hidden" id="uploadedFilePictureService" name="uploadedFilePictureService" value="{{$picture_service}}">
                                <center>
                                    <label>Copia de la escritura de constitución y de sus reformas, de existirlas.</label>
                                    <div class="alert" id="messageService" style="display: none"></div>
                                    <div style="width:100px !important;padding: 0" class="inside" id="fileNameService"></div>
                                    <div class="inputWrapper"><span id="uploaded_imageService">{!! $picture_service !!}</span>
                                        <center>
                                            <img src="{{asset('images/mas.png')}}" alt="Girl in a jacket" style="width:20px;height:20px;margin-bottom: -100px;">
                                        </center>
                                        <input class="fileInput" type="file" name="select_fileService" onchange="fileNameFunction('Service')" id="select_fileService">
                                    </div>
                                </center>
                                <center>
                                    <button type="submit" name="upload" id="uploadService" class="btn btn-primary @if($picture_service == null) visible @else hidden @endif" onclick="uploadPictureForm('Service')">
                                        <span class="glyphicon glyphicon-upload"></span> Subir Foto
                                    </button>
                                    <a class="@if($picture_service == null) hidden @else visible @endif" id="deletePictureService" href="#" onclick="deletePictureForm('Service','{{$customer->id}}','{{$sales_id}}')">
                                        <img src="{{asset('/images/menos.png')}}" style="width:20px;height:20px">
                                    </a>  
                                </center>
                            </form>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Fecha de Caducidad</label>
                            <input type="date" class="form-control" style="line-height:14px;">
                            <br>
                            <form method="post" id="upload_formService" name="upload_formService" enctype="multipart/form-data" onsubmit="uploadPictureForm('upload_formService'">
                                {{ csrf_field() }}                                
                                <input type="hidden" id="documentId" name="documentId" value="{{$customer->id}}">
                                <input type="hidden" id="saleId" name="saleId" value="{{$sales_id}}">
                                <input type="hidden" id="uploadType" name="uploadType" value="Service">
                                <input type="hidden" id="uploadedFilePictureService" name="uploadedFilePictureService" value="{{$picture_service}}">
                                <center>
                                    <label>Copia certificada del nombramiento del representante legal o apoderado.</label>
                                    <div class="alert" id="messageService" style="display: none"></div>
                                    <div style="width:100px !important;padding: 0" class="inside" id="fileNameService"></div>
                                    <div class="inputWrapper"><span id="uploaded_imageService">{!! $picture_service !!}</span>
                                        <center>
                                            <img src="{{asset('images/mas.png')}}" alt="Girl in a jacket" style="width:20px;height:20px;margin-bottom: -100px;">
                                        </center>
                                        <input class="fileInput" type="file" name="select_fileService" onchange="fileNameFunction('Service')" id="select_fileService">
                                    </div>
                                </center>
                                <center>
                                    <button type="submit" name="upload" id="uploadService" class="btn btn-primary @if($picture_service == null) visible @else hidden @endif" onclick="uploadPictureForm('Service')">
                                        <span class="glyphicon glyphicon-upload"></span> Subir Foto
                                    </button>
                                    <a class="@if($picture_service == null) hidden @else visible @endif" id="deletePictureService" href="#" onclick="deletePictureForm('Service','{{$customer->id}}','{{$sales_id}}')">
                                        <img src="{{asset('/images/menos.png')}}" style="width:20px;height:20px">
                                    </a>  
                                </center>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-6 form-group">
                            <form method="post" id="upload_formService" name="upload_formService" enctype="multipart/form-data" onsubmit="uploadPictureForm('upload_formService'">
                                {{ csrf_field() }}                                
                                <input type="hidden" id="documentId" name="documentId" value="{{$customer->id}}">
                                <input type="hidden" id="saleId" name="saleId" value="{{$sales_id}}">
                                <input type="hidden" id="uploadType" name="uploadType" value="Service">
                                <input type="hidden" id="uploadedFilePictureService" name="uploadedFilePictureService" value="{{$picture_service}}">
                                <center>
                                    <label>Nómina actualizada de accionistas o socios, en la que consten los montos de acciones o participaciones obtenida por el cliente en el órgano de control competente o registro competente que lo regule.</label>
                                    <div class="alert" id="messageService" style="display: none"></div>
                                    <div style="width:100px !important;padding: 0" class="inside" id="fileNameService"></div>
                                    <div class="inputWrapper"><span id="uploaded_imageService">{!! $picture_service !!}</span>
                                        <center>
                                            <img src="{{asset('images/mas.png')}}" alt="Girl in a jacket" style="width:20px;height:20px;margin-bottom: -100px;">
                                        </center>
                                        <input class="fileInput" type="file" name="select_fileService" onchange="fileNameFunction('Service')" id="select_fileService">
                                    </div>
                                </center>
                                <center>
                                    <button type="submit" name="upload" id="uploadService" class="btn btn-primary @if($picture_service == null) visible @else hidden @endif" onclick="uploadPictureForm('Service')">
                                        <span class="glyphicon glyphicon-upload"></span> Subir Foto
                                    </button>
                                    <a class="@if($picture_service == null) hidden @else visible @endif" id="deletePictureService" href="#" onclick="deletePictureForm('Service','{{$customer->id}}','{{$sales_id}}')">
                                        <img src="{{asset('/images/menos.png')}}" style="width:20px;height:20px">
                                    </a>  
                                </center>
                            </form>
                        </div>
                        <div class="col-md-6 form-group">
                            <form method="post" id="upload_formService" name="upload_formService" enctype="multipart/form-data" onsubmit="uploadPictureForm('upload_formService'">
                                {{ csrf_field() }}                                
                                <input type="hidden" id="documentId" name="documentId" value="{{$customer->id}}">
                                <input type="hidden" id="saleId" name="saleId" value="{{$sales_id}}">
                                <input type="hidden" id="uploadType" name="uploadType" value="Service">
                                <input type="hidden" id="uploadedFilePictureService" name="uploadedFilePictureService" value="{{$picture_service}}">
                                <center>
                                    <label>Certificado de cumplimiento de obligaciones otorgado por el órgano de control competente.</label>
                                    <div class="alert" id="messageService" style="display: none"></div>
                                    <div style="width:100px !important;padding: 0" class="inside" id="fileNameService"></div>
                                    <div class="inputWrapper"><span id="uploaded_imageService">{!! $picture_service !!}</span>
                                        <center>
                                            <img src="{{asset('images/mas.png')}}" alt="Girl in a jacket" style="width:20px;height:20px;margin-bottom: -100px;">
                                        </center>
                                        <input class="fileInput" type="file" name="select_fileService" onchange="fileNameFunction('Service')" id="select_fileService">
                                    </div>
                                </center>
                                <center>
                                    <button type="submit" name="upload" id="uploadService" class="btn btn-primary @if($picture_service == null) visible @else hidden @endif" onclick="uploadPictureForm('Service')">
                                        <span class="glyphicon glyphicon-upload"></span> Subir Foto
                                    </button>
                                    <a class="@if($picture_service == null) hidden @else visible @endif" id="deletePictureService" href="#" onclick="deletePictureForm('Service','{{$customer->id}}','{{$sales_id}}')">
                                        <img src="{{asset('/images/menos.png')}}" style="width:20px;height:20px">
                                    </a>  
                                </center>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-6 form-group">
                            <form method="post" id="upload_formService" name="upload_formService" enctype="multipart/form-data" onsubmit="uploadPictureForm('upload_formService'">
                                {{ csrf_field() }}                                
                                <input type="hidden" id="documentId" name="documentId" value="{{$customer->id}}">
                                <input type="hidden" id="saleId" name="saleId" value="{{$sales_id}}">
                                <input type="hidden" id="uploadType" name="uploadType" value="Service">
                                <input type="hidden" id="uploadedFilePictureService" name="uploadedFilePictureService" value="{{$picture_service}}">
                                <center>
                                    <label>Estados financieros, mínimo de un año atrás. (Si la suma asegurada supera los USD 200.000,00 se deberá presentar los estados financieros auditados).</label>
                                    <div class="alert" id="messageService" style="display: none"></div>
                                    <div style="width:100px !important;padding: 0" class="inside" id="fileNameService"></div>
                                    <div class="inputWrapper"><span id="uploaded_imageService">{!! $picture_service !!}</span>
                                        <center>
                                            <img src="{{asset('images/mas.png')}}" alt="Girl in a jacket" style="width:20px;height:20px;margin-bottom: -100px;">
                                        </center>
                                        <input class="fileInput" type="file" name="select_fileService" onchange="fileNameFunction('Service')" id="select_fileService">
                                    </div>
                                </center>
                                <center>
                                    <button type="submit" name="upload" id="uploadService" class="btn btn-primary @if($picture_service == null) visible @else hidden @endif" onclick="uploadPictureForm('Service')">
                                        <span class="glyphicon glyphicon-upload"></span> Subir Foto
                                    </button>
                                    <a class="@if($picture_service == null) hidden @else visible @endif" id="deletePictureService" href="#" onclick="deletePictureForm('Service','{{$customer->id}}','{{$sales_id}}')">
                                        <img src="{{asset('/images/menos.png')}}" style="width:20px;height:20px">
                                    </a>  
                                </center>
                            </form>
                        </div>
                        <div class="col-md-6 form-group">
                            <form method="post" id="upload_formService" name="upload_formService" enctype="multipart/form-data" onsubmit="uploadPictureForm('upload_formService'">
                                {{ csrf_field() }}                                
                                <input type="hidden" id="documentId" name="documentId" value="{{$customer->id}}">
                                <input type="hidden" id="saleId" name="saleId" value="{{$sales_id}}">
                                <input type="hidden" id="uploadType" name="uploadType" value="Service">
                                <input type="hidden" id="uploadedFilePictureService" name="uploadedFilePictureService" value="{{$picture_service}}">
                                <center>
                                    <label>Confirmación del pago del impuesto a la renta del año inmediato anterior o constancia de la información publicada por el Servicio de Rentas Internas (SRI) a través de la página web, de ser aplicable. </label>
                                    <div class="alert" id="messageService" style="display: none"></div>
                                    <div style="width:100px !important;padding: 0" class="inside" id="fileNameService"></div>
                                    <div class="inputWrapper"><span id="uploaded_imageService">{!! $picture_service !!}</span>
                                        <center>
                                            <img src="{{asset('images/mas.png')}}" alt="Girl in a jacket" style="width:20px;height:20px;margin-bottom: -100px;">
                                        </center>
                                        <input class="fileInput" type="file" name="select_fileService" onchange="fileNameFunction('Service')" id="select_fileService">
                                    </div>
                                </center>
                                <center>
                                    <button type="submit" name="upload" id="uploadService" class="btn btn-primary @if($picture_service == null) visible @else hidden @endif" onclick="uploadPictureForm('Service')">
                                        <span class="glyphicon glyphicon-upload"></span> Subir Foto
                                    </button>
                                    <a class="@if($picture_service == null) hidden @else visible @endif" id="deletePictureService" href="#" onclick="deletePictureForm('Service','{{$customer->id}}','{{$sales_id}}')">
                                        <img src="{{asset('/images/menos.png')}}" style="width:20px;height:20px">
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
                    <!--<a class="btn btn-default registerForm" align="right" href="{{asset('/sales')}}" style="margin-left: -30px;"> Cancelar </a>-->
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
                <div id="fifthStepAlert" class="alert alert-success hidden">
                    Se ha completado el Formulario de Vinculación, su asesor de venta pronto se pondra en contacto con usted.
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
                    <!--<a class="btn btn-default registerForm" align="right" href="{{asset('/sales')}}" style="margin-left: -30px;"> Cancelar </a>-->
                </div>
                <div class="row" style="float:right">
                    <a id="fifthStepBtnBack" class="btn btn-back registerForm" align="right" href="#"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                    <a id="fifthStepBtnNext" class="btn btn-info registerForm" align="right" href="#"> Validar </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
