@extends('layouts.app')

@section('content')
<script src="{{ assets('js/registerCustom.js') }}"></script>
<script src="{{ assets('js/insurance/create.js') }}"></script>
<link href="{{ assets('css/sales/create.css')}}" rel="stylesheet" type="text/css"/>
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
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
    /*    #vehiclesTable {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }*/
</style>
<meta name="csrf-token" content="{{ csrf_token() }}" />
<div class="container-fluid" style="font-size:14px !important;padding-bottom: 15px;">
    <!--<div class="row justify-content-center border" style="margin-left:20%;">-->

    <div class="col-md-8 col-md-offset-2 border">
        <div class="row">
            <div class="col-xs-12 registerForm" style="margin:12px;">
                <center>
                    <h4 style="font-weight:bold">Registro de Nueva Activacion de Seguro</h4>
                </center>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-3 wizard_inicial"><div style="margin-left:-10px" id="firstStepWizard" class="wizard_activo registerForm">Cliente</div></div>
            <div class="col-xs-12 col-md-3 wizard_medio"><div id="secondStepWizard" class="wizard_inactivo registerForm">Plan</div></div>
            <div class="col-xs-12 col-md-3 wizard_medio"><div id="thirdStepWizard" class="wizard_inactivo registerForm">Beneficiarios</div></div>
            <div class="col-xs-12 col-md-3 wizard_final"><div style="margin-right:-10px;" id="fourthStepWizard" class="wizard_inactivo registerForm">Validacion</div></div>
        </div>
        <div class="col-md-12">
            <form name="salesForm" method="POST" action="/user" id="salesForm">
                <input type="hidden" name="sale_movement" id="sale_movement" value="{{$sale_movement}}">
                <input type="hidden" name="sale_id" id="sale_id" value="{{$sale_id}}">
                <div id="firstStep" class="col-md-12">
                    <div class="col-md-12" style="margin-top:5px;margin-bottom: 5px;padding-top:15px;">
                        <div class="row" style="float:left">
                            <a class="btn btn-default registerForm" align="right" href="{{asset('/insurance')}}"> Cancelar </a>
                        </div>
                        <div class="row" style="float:right">
                            <a id="secondStepBtnBackTop" class="btn btn-back registerForm" align="right" @if($disabled == null) href="{{asset('/insurance')}}" @else href="#" disabled="disabled" @endif ><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                            <a id="secondStepBtnNextTop" class="btn btn-info registerForm" align="right" href="#" style="padding: 5px" onclick="firstStepBtnNext()"> Siguiente <span class="glyphicon glyphicon-step-forward"></span></a>
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
                        {{ csrf_field() }}
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
                                <!--<input type="text" class="form-control registerForm" name="document" id="document" placeholder="Cédula" value="{{ old('document') }}" required="required">-->
                                <div class="form-inline">
                                    <input autocomplete="off" type="text" class="form-control registerForm" name="document" id="document" value="{{$customer->document}}" @if($disabled) disabled="disabled" @else @endif placeholder="Cédula" required="required"tabindex="1" style="width:89%">
                                    <button type="button" class="btn btn-info" onclick="documentBtn()" @if($disabled) disabled="disabled" @else @endif style="width:10%"><span class="glyphicon glyphicon-search"></span></button>
                                    <div id="suggesstion-box"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="first_name"> Primer Nombre</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" class="form-control registerForm" name="first_name" id="first_name" placeholder="Primer Nombre" value="{{ $customer->first_name }}" required="required" tabindex="3" disabled="disabled">
                            </div>
                            <div class="form-group">
                                <label class="registerForm" style="list-style-type:disc;" for="second_name"> Segundo Nombre</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" class="form-control registerForm" name="second_name" id="second_name" placeholder="Segundo Nombre" value="{{ $customer->second_name }}" required="required" tabindex="5" disabled="disabled">
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="city"> Celular</label> <span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span><label class="own"><span class="glyphicon glyphicon-info-sign" style="color:#0099ff"><span class="own1" style="float:left">El celular debe tener 10 caracteres</span></span></label>
                                <input type="text" class="form-control registerForm" name="mobile_phone" id="mobile_phone" placeholder="Celular" value="{{ $customer->mobile_phone }}" required tabindex="7">
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="city"> Direccion</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" class="form-control registerForm" name="address" id="address" placeholder="Dirección" required tabindex="9" value="{{ $customer->address }}">
                            </div>

                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="country"> Pais</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <select name="country" id="country" class="form-control registerForm" required tabindex="11">
                                    <option selected="true" value="0">--Escoja Una---</option>
                                    @foreach($countries as $country)
                                    <option @if($country->id == $cusCountry->id) selected="true" @else @endif value="{{$country->id}}">{{$country->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="city"> Ciudad</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <select name="city" class="form-control registerForm" id="city" required tabindex="13">
                                    @if($cusCityList)
                                    <option id="citySelect" selected="true" value="{{$cusCity->id}}">{{$cusCity->name}}</option>
                                    @else
                                    <option id="citySelect" selected="true" disabled="disabled" value="0">--Escoja Una---</option>
                                    @endif
                                </select>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document_id"> Tipo Documento</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <select id="document_id" name="document_id" class="form-control registerForm" value="{{old('document_id')}}" required tabindex="2" disabled="disabled">
                                    <option selected="true" value="0">--Escoja Una---</option>
                                    @foreach($documents as $document)
                                    <option @if($document->id == $customer->document_id) selected="true" @else @endif value="{{$document->id}}">{{$document->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="last_name"> Primer Apellido</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" class="form-control registerForm" name="last_name" id="last_name" placeholder="Primer Apellido" value="{{ $customer->last_name }}" required tabindex="4" disabled="disabled">
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="second_last_name"> Segundo Apellido</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" class="form-control registerForm" name="second_last_name" id="second_last_name" placeholder="Segundo Apellido" value="{{ $customer->second_last_name }}" required tabindex="6" disabled="disabled">
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Teléfono </label> <span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span><label class="own"><span class="glyphicon glyphicon-info-sign" style="color:#0099ff"><span class="own1" style="float:left">El telefono debe tener 9 caracteres</span></span></label>
                                <input type="text" class="form-control registerForm" name="phone" id="phone" placeholder="Teléfono" value="{{ $customer->phone }}" required tabindex="8">
                            </div>

                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="correo"> Correo</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="email" class="form-control registerForm" name="email" id="email" placeholder="Correo" value="{{ $customer->email }}" required tabindex="10">
                                <p id="emailError" style="color:red;font-weight: bold"></p>    
    <!--<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>-->
                                @if($errors->any())
                                <span style="color:red;font-weight:bold">{{$errors->first()}}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="province"> Canton</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <select name="province" class="form-control registerForm" id="province" required tabindex="12">
                                    @if($cusProvinceList)
                                    <option id="provinceSelect" selected="true" value="{{$cusProvince->id}}">{{$cusProvince->name}}</option>
                                    @else
                                    <option id="provinceSelect" selected="true" disabled="disabled" value="0">--Escoja Una---</option>
                                    @endif
                                </select>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-12" style="padding-bottom:15px">
                        <div class="row" style="float:left">
                            <a class="btn btn-default registerForm" align="right" href="{{asset('/insurance')}}""> Cancelar </a>
                        </div>
                        <div class="row" style="float:right">
                            <a class="btn btn-back registerForm" align="right" @if($disabled == null) href="{{asset('/insurance')}}" @else href="#" disabled="disabled" @endif ><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                            <a class="btn btn-info registerForm" align="right" href="#" style="padding: 5px" onclick="firstStepBtnNext()"> Siguiente <span class="glyphicon glyphicon-step-forward"></span></a>
                        </div>
                    </div>
                </div>
                <div id="secondStep" class="col-md-12 hidden">
                    <div class="col-md-12" style="margin-top:5px;padding-top:15px;">
                        <div class="row" style="float:left">
                            <a class="btn btn-default registerForm" align="right" href="{{asset('/insurance')}}"> Cancelar </a>
                        </div>
                        <div class="row" style="float:right">
                            <a class="btn btn-back registerForm" align="right" href="#" onclick="nextStep('secondStep', 'firstStep'), clearVehicleForm()"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                            <a class="btn btn-info registerForm" align="right" href="#" style="padding: 5px" onclick="secondStepBtnNext()"> Siguiente <span class="glyphicon glyphicon-step-forward"></span></a>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;margin-bottom: 5px;">
                        <div class="wizard_activo registerForm titleDivBorderTop" onclick="fadeToggle('productsForm')">
                            <a href="#" class="titleLink">Planes</a>
                            <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                        </div>
                        <div class="col-md-12" id="productsForm" style="margin-top:15px;">
                            <div class="panel panel-default">
                                <table id="vehiclesTable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="background-color:#b3b0b0">Plan</th>
                                            <th style="background-color:#b3b0b0" class="col-md-5">Coberturas</th>
                                            <th style="background-color:#b3b0b0;">Suma Asegurada</th>
                                            <th style="background-color:#b3b0b0">Prima Mes</th>
                                            <th style="background-color:#b3b0b0">Prima Año</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {!! $products_table !!}
                                    </tbody>
                                </table>
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" style="padding-bottom:15px">
                        <div class="row" style="float:left">
                            <a class="btn btn-default registerForm" align="right" href="{{asset('/insurance')}}"> Cancelar </a>
                        </div>
                        <div class="row" style="float:right">
                            <a  class="btn btn-back registerForm" align="right" href="#" onclick="nextStep('secondStep', 'firstStep')"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                            <a class="btn btn-info registerForm" align="right" href="#" style="padding: 5px" onclick="secondStepBtnNext()"> Siguiente <span class="glyphicon glyphicon-step-forward"></span></a>
                        </div>
                    </div>
                </div>
                <div id="thirdStep" class="col-md-12 hidden">
                    <div class="col-md-12" style="margin-top:5px;padding-top:15px;">
                        <div class="row" style="float:left">
                            <a class="btn btn-default registerForm" align="right" href="{{asset('/insurance')}}"> Cancelar </a>
                        </div>
                        <div class="row" style="float:right">
                            <a class="btn btn-back registerForm" align="right" href="#" onclick="nextStep('thirdStep', 'secondStep')"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                            <a class="btn btn-info registerForm" align="right" href="#" style="padding: 5px" onclick="thirdStepBtnNext()"> Siguiente <span class="glyphicon glyphicon-step-forward"></span></a>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;margin-bottom: 5px;">
                        <div class="wizard_activo registerForm titleDivBorderTop" onclick="fadeToggle('beneficiaryForm')">
                            <a href="#" class="titleLink">Beneficiarios</a>
                            <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                        </div>
                        <div id="beneficiaryForm" class="col-md-12" style="padding-top: 25px;padding-bottom: 25px;">
                            <div class="col-md-6">
                                <div class="form-group form-inline">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Identificación</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <div class="form-inline">
                                        <input autocomplete="off" type="text" class="form-control registerForm" name="documentBeneficiary" id="documentBeneficiary" value="" placeholder="Identificación" required="required"tabindex="1" style="width:88%" onclick="clearForm()" onkeydown="clearForm()">
                                        <button type="button" class="btn btn-info" onclick="validateBeneficiary();" style="width:10%"><span class="glyphicon glyphicon-search"></span></button>
                                        <div id="suggesstion-box"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="first_name"> Nombre(s)</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="first_nameBeneficiary" id="first_nameBeneficiary" placeholder="Nombre" value="" required="required" tabindex="3" disabled="disabled">
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Porcentaje</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="porcentageBeneficiary" id="porcentageBeneficiary" placeholder="Porcentaje" value="" required tabindex="6" onkeypress="return onlyNumbers(event, this)">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document_id"> Tipo Documento</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select id="document_idBeneficiary" name="document_idBeneficiary" class="form-control registerForm" value="{{old('document_id')}}" required tabindex="2" disabled="disabled" onchange="documentIdChange(this.value)">
                                        <option selected="true" value="0">--Escoja Una---</option>
                                        @foreach($documents as $document)
                                        <option value="{{$document->id}}">{{$document->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="last_name"> Apellido(s)</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="last_nameBeneficiary" id="last_nameBeneficiary" placeholder="Apellido" value="" required tabindex="4" disabled="disabled">
                                </div>
                                <div class="form-group">
                                    <a onclick="addBeneficiary()" class="btn btn-success registerForm" align="right" href="#" style="float:right;margin-right: 0px;padding: 5px;margin-top: 25px;width:100px"><span class="glyphicon glyphicon-plus"></span>Agregar </a>
                                </div>
                            </div>
                            <div class="col-md-10 col-md-offset-1">
                                <table id="beneficiaryTable" class="table table-striped table-bordered" style="border-collapse: separate !important">
                                    <thead>
                                        <tr>
                                            <th style="background-color:#b3b0b0">Documento</th>
                                            <th style="background-color:#b3b0b0">Tipo</th>
                                            <th style="background-color:#b3b0b0">Nombre</th>
                                            <th style="background-color:#b3b0b0">Apellido</th>
                                            <th style="background-color:#b3b0b0">Porcentaje</th>
                                            <th style="background-color:#b3b0b0">Editar</th>
                                            <th style="background-color:#b3b0b0">Quitar</th>
                                        </tr>
                                    </thead>
                                    <tbody id="beneficiaryBodyTable" style="align-content: center;">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" style="padding-bottom:15px">
                        <div class="row" style="float:left">
                            <a class="btn btn-default registerForm" align="right" href="{{asset('/insurance')}}"> Cancelar </a>
                        </div>
                        <div class="row" style="float:right">
                            <a  class="btn btn-back registerForm" align="right" href="#" onclick="nextStep('thirdStep', 'secondStep')"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                            <a class="btn btn-info registerForm" align="right" href="#" style="padding: 5px"  onclick="thirdStepBtnNext()"> Siguiente <span class="glyphicon glyphicon-step-forward"></span></a>
                        </div>
                    </div>
                </div>
                <div id="fourthStep" class="col-md-12 hidden">
                    <div class="col-md-12" style="margin-top:5px;padding-top:15px;">
                        <div class="row" style="float:left">
                            <a class="btn btn-default registerForm" align="right" href="{{asset('/insurance')}}"> Cancelar </a>
                        </div>
                        <div class="row" style="float:right">
                            <a class="btn btn-info registerForm" align="right" href="#" style="padding: 5px"  onclick="validateCode()"> Siguiente <span class="glyphicon glyphicon-step-forward"></span></a>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;margin-bottom: 5px;">
                        <div class="wizard_activo registerForm titleDivBorderTop" onclick="fadeToggle('validationForm')">
                            <a href="#" class="titleLink">Validacion</a>
                            <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                        </div>
                        <div id="validationForm" class="col-md-12" style="padding-top: 25px;padding-bottom: 25px;">
                            <input type="hidden" name="insuranceId" id="insuranceId" value="">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="validation_code"> Codigo</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="validation_code" id="validation_code" placeholder="Codigo de Validacion" value="" required="required" tabindex="1">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <a onclick="resendCode()" class="btn btn-success registerForm" align="right" href="#" style="float:right;margin-right: 0px;padding: 5px;margin-top: 25px;width:100px"> Reenviar </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" style="padding-bottom:15px">
                        <div class="row" style="float:left">
                            <a class="btn btn-default registerForm" align="right" href="{{asset('/insurance')}}"> Cancelar </a>
                        </div>
                        <div class="row" style="float:right">
                            <a class="btn btn-info registerForm" align="right" href="#" style="padding: 5px"  onclick="validateCode()"> Validar </a>
                        </div>
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
                            <a href="{{asset('/sales')}}" type="button" class="btn btn-default" style="float:left">NO</a>
                            <form action="/payments/create" method="POST">
                                {{ csrf_field() }}
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
@endsection
