@extends('layouts.app')

@section('content')
<script src="{{ assets('js/registerCustom.js') }}"></script>
<script src="{{ assets('js/sales/create.js') }}"></script>
<script src="{{ assets('js/sales/createVehicle.js') }}"></script>
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

    <div class="col-md-10 col-md-offset-1 border">
        <div class="row">
            <div class="col-xs-12 registerForm" style="margin:12px;">
                <center>
                    <h4 style="font-weight:bold">Registro de Nueva Venta</h4>
                </center>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-2 wizard_inicial"><div style="margin-left:-10px" id="zeroStepWizard" class="wizard_inactivo registerForm">Ramo</div></div>
            <div class="col-xs-12 col-md-2 wizard_medio"><div id="firstStepWizard" class="wizard_activo registerForm">Cliente</div></div>
            <div class="col-xs-12 col-md-2 wizard_medio"><div id="secondStepWizard" class="wizard_inactivo registerForm">Vehículos</div></div>
            <div class="col-xs-12 col-md-2 wizard_medio"><div id="thirdStepWizard" class="wizard_inactivo registerForm">Accesorios</div></div>
            <div class="col-xs-12 col-md-2 wizard_medio"><div id="fourthStepWizard" class="wizard_inactivo registerForm">Producto</div></div>
            <div class="col-xs-12 col-md-2 wizard_final"><div style="margin-right:-10px;" id="fifthStepWizard" class="wizard_inactivo registerForm">Resumen</div></div>
        </div>
        <div class="col-md-12">
            <form name="salesForm" method="POST" action="/user" id="salesForm">
                <input type="hidden" name="sale_movement" id="sale_movement" value="{{$sale_movement}}">
                <input type="hidden" name="sale_id" id="sale_id" value="{{$sale_id}}">
                <div id="firstStep" class="col-md-12">
                    <div class="col-md-12" style="margin-top:5px;margin-bottom: 5px;padding-top:15px;">
                        <div class="row" style="float:left">
                            <a class="btn btn-default registerForm" align="right" href="{{asset('/sales')}}"> Cancelar </a>
                        </div>
                        <div class="row" style="float:right">
                            <a id="secondStepBtnBackTop" class="btn btn-back registerForm" align="right" @if($disabled == null) href="{{asset('/sales/product/select')}}" @else href="#" disabled="disabled" @endif ><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
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
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="birthdate"> Fecha de Nacimiento </label> <span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="date" class="form-control registerForm" name="birthdate" id="birthdate" placeholder="Fecha de Nacimiento" value="{{ $customer->birthdate }}" required tabindex="14" style="line-height: 14px;" disabled="disabled">
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="city"> Celular</label> <span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span><label class="own"><span class="glyphicon glyphicon-info-sign" style="color:#0099ff"><span class="own1" style="float:left">El celular debe tener 10 caracteres</span></span></label>
                                <input type="text" class="form-control registerForm" name="mobile_phone" id="mobile_phone" placeholder="Celular" value="{{ $customer->mobile_phone }}" required tabindex="7">
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
                    </div>
                    <div class="col-md-12" style="padding-bottom:15px">
                        <div class="row" style="float:left">
                            <a class="btn btn-default registerForm" align="right" href="{{asset('/sales')}}""> Cancelar </a>
                        </div>
                        <div class="row" style="float:right">
                            <a class="btn btn-back registerForm" align="right" @if($disabled == null) href="{{asset('/sales/product/select')}}" @else href="#" disabled="disabled" @endif ><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                            <a class="btn btn-info registerForm" align="right" href="#" style="padding: 5px" onclick="firstStepBtnNext()"> Siguiente <span class="glyphicon glyphicon-step-forward"></span></a>
                        </div>
                    </div>
                </div>
                <div id="secondStep" class="col-md-12 hidden">
                    <div class="col-md-12" style="margin-top:5px;padding-top:15px;">
                        <div class="row" style="float:left">
                            <a class="btn btn-default registerForm" align="right" href="{{asset('/sales')}}"> Cancelar </a>
                        </div>
                        <div class="row" style="float:right">
                            <a class="btn btn-back registerForm" align="right" href="#" onclick="nextStep('secondStep', 'firstStep'),clearVehicleForm()"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                            <a class="btn btn-info registerForm" align="right" href="#" style="padding: 5px" onclick="hideVehicleForm(),vehicleAccesoriesTable(),secondStepBtnNext()"> Siguiente <span class="glyphicon glyphicon-step-forward"></span></a>
                        </div>
                    </div>
                    <!-- Vehicle Search-->
                    <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;">
                        <div class="wizard_activo registerForm titleDivBorderTop">
                            <span class="titleLink">Busqueda del Vehículo</span>
                            <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="province"> Estado del Vehiculo</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <select name="newVehicle" class="form-control registerForm" id="newVehicle" tabindex="1" required {{$disabled}} onchange="newVehicleChange(this.value)">
                                    <option selected="true" disabled="disabled" value="">--Escoja Una---</option>
                                    <option value="Nuevo">Nuevo</option>
                                    <option value="Usado">Usado</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label id="vehicleDocument" class="registerForm" for="last_name"> Placa/RAMV</label> <label class="own"><span class="glyphicon glyphicon-info-sign" style="color:#0099ff"><span class="own1" style="float:left">* La placa debe tener 7 Caracteres: <br> -PCQ1111 <br> Nota: Si la placa solo tiene 6 caracteres debe agregar un cero de la siguiente manera: <br> -Placa: PCQ0111</p></span></span></label>
                                <div class="form-inline">
                                    <input type="text" class="form-control registerForm" name="plateForm" id="plateForm" tabindex="2" placeholder="Placa/RAMV" value="{{ old('plate') }}" maxlength="7" required style="width:89%" onclick="clearVehicleForm();" onkeydown="clearVehicleForm();">
                                    <button type="button" class="btn btn-info" id="plateBtn" @if($disabled != null) disabled="disabled" @else @endif style="width:10%"><span class="glyphicon glyphicon-search"></span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Vehicle Form-->
                    <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;margin-top:15px;">
                        <div class="wizard_activo registerForm titleDivBorderTop">
                            <span class="titleLink">Datos del Vehículo</span>
                            <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                        </div>
                        <div id="thirdStepAlert" class="alert alert-danger hidden registerForm titleDivBorderTop" style="margin-top:5px;border-radius:0px !important;">
                            <center><strong>¡Alerta!</strong> Debe ingresar un vehiculo.</center>
                        </div>
                        <div id="plateAlert" class="alert alert-danger hidden registerForm titleDivBorderTop" style="margin-top:5px;border-radius:0px !important;">
                            <center><strong>¡Alerta!</strong> El vehiculo ya posee una venta individual.</center>
                        </div>
                        <div id="plateAlert2" class="alert alert-danger hidden registerForm titleDivBorderTop" style="margin-top:5px;border-radius:0px !important;">
                            <center><strong>¡Alerta!</strong> Debe ingresar una placa valida.</center>
                        </div>
                        <div id="plateAlert3" class="alert alert-danger hidden registerForm titleDivBorderTop" style="margin-top:5px;border-radius:0px !important;">
                            <center>Ingrese una placa valida.</center>
                        </div>
                        <div id="plateAlert4" class="alert alert-danger hidden registerForm titleDivBorderTop" style="margin-top:5px;border-radius:0px !important;">
                            <center><strong>Por favor ingrese una placa.</strong></center>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label id="vehicleDocument" class="registerForm" for="last_name"> RAMV</label> 
                                <input type="text" class="form-control registerForm" name="ramv" id="ramv" tabindex="3" placeholder="RAMV" value="{{ old('plate') }}" maxlength="7" required disabled="disabled">
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="last_name"> Modelo</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" class="form-control registerForm" name="model" id="model" tabindex="5" placeholder="Modelo" value="{{ old('model') }}" required disabled="disabled">
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="last_name"> Color</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" class="form-control registerForm" name="color" id="color" tabindex="7" placeholder="Color" value="{{ old('color') }}" required disabled="disabled">
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="registration"> Motor</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" class="form-control registerForm" name="registration" id="registration" tabindex="9" placeholder="Motor" value="" required disabled="disabled">
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="province"> Uso</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <select name="vehiType" class="form-control registerForm" id="vehiType" tabindex="11" required disabled="disabled">
                                    <option id="typeSelect" selected="true" disabled="disabled" value="0">--Escoja Una---</option>
                                    @foreach($vehiclesType as $type)
                                    <option id="typeSelect" value="{{$type->name}}">{{$type->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label id="vehicleDocument" class="registerForm" for="last_name"> Placa</label> 
                                <input type="text" class="form-control registerForm" name="plate" id="plate" tabindex="4" placeholder="Placa" value="{{ old('plate') }}" maxlength="7" required disabled="disabled">                                
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="province"> Marca</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <select name="brand" class="form-control registerForm" id="brand" tabindex="6" required disabled="disabled">
                                    <option id="brandSelect" selected="true" disabled="disabled" value="0">--Escoja Una---</option>
                                    @foreach($vehiclesBrands as $vehicleBrand)
                                    <option id="brandSelect" value="{{$vehicleBrand->name}}">{{$vehicleBrand->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="last_name"> Año</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" class="form-control registerForm yearVehicle" name="year" id="year" tabindex="8" placeholder="Año" value="{{ old('year') }}" max="2020" min="1" required disabled="disabled">
                                <p id="yearVehicleError" style="color:red;font-weight: bold"></p>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="chassis"> Chasis</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" class="form-control registerForm" name="chassis" id="chassis" tabindex="10" placeholder="Chasis" value="" required disabled="disabled">
                            </div>
                        </div>
                    </div>
                    <!-- Vehicle Prices Search-->
                    <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;margin-top:15px;">
                        <div class="wizard_activo registerForm titleDivBorderTop">
                            <span class="titleLink">Valor Referencial</span>
                            <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label id="vehicleDocument" class="registerForm" for="last_name"> Valor Referencial</label>
                                <div class="form-inline">
                                    <input type="text" class="form-control registerForm" name="vehiPrice" id="vehiPrice" tabindex="2" placeholder="Valor Referencial" value="" required style="width:89%" readonly="readonly">
                                    <button type="button" class="btn btn-info" id="priceBtn" @if($disabled != null) disabled="disabled" @else @endif style="width:10%"><span class="glyphicon glyphicon-search"></span></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="vehiValue"> Valor Asegurado</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span> <label class="own"><span class="glyphicon glyphicon-info-sign" style="color:#0099ff"><span class="own1" style="float:left">* El valor asegurado se calculará en base a un 10% mayor o menor del precio del mercado, según las políticas de la empresa.</span></span></label>
                                <input type="text" class="form-control registerForm" name="vehiValue" id="vehiValue" tabindex="12" placeholder="Valor Asegurado" value="" required >
                            </div>
                        </div>
                        <div class="col-md-6 col-md-offset-6">
                            <div class="form-group">
                                <a  id="btnVehicles" @if($disabled != null) disabled="disabled" @else @endif class="btn btn-success registerForm" align="right" href="#" style="float:right;margin-right: 0px;padding: 5px;width:100px"><span class="glyphicon glyphicon-plus"></span>Agregar </a>
                            </div>
                        </div>
                        <div class="col-md-12" style="margin-top: 15px;">
                            <table id="vehiclesTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th style="background-color:#b3b0b0">RAMV</th>
                                        <th style="background-color:#b3b0b0">Placa</th>
                                        <th style="background-color:#b3b0b0;">Modelo</th>
                                        <th style="background-color:#b3b0b0">Marca</th>
                                        <th style="background-color:#b3b0b0">Color</th>
                                        <th style="background-color:#b3b0b0">Año</th>
                                        <th style="background-color:#b3b0b0;">Motor</th>
                                        <th style="background-color:#b3b0b0;">Chasis</th>
                                        <th style="background-color:#b3b0b0">Uso</th>
                                        <th style="background-color:#b3b0b0">V. Ref.</th>
                                        <th style="background-color:#b3b0b0">V. Aseg.</th>
                                        <th style="background-color:#b3b0b0">Estado</th>
                                        <th style="background-color:#b3b0b0;margin-right: -15px;">Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="vehiclesBodyTable" style="word-break: break-all">
                                    {!! $vehiBodyTable !!}
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-12" style="padding-bottom:15px">
                        <div class="row" style="float:left">
                            <a class="btn btn-default registerForm" align="right" href="{{asset('/sales')}}"> Cancelar </a>
                        </div>
                        <div class="row" style="float:right">
                            <a  class="btn btn-back registerForm" align="right" href="#" onclick="nextStep('secondStep', 'firstStep'),clearVehicleForm()"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                            <a class="btn btn-info registerForm" align="right" href="#" style="padding: 5px" onclick="hideVehicleForm(),vehicleAccesoriesTable(),secondStepBtnNext()"> Siguiente <span class="glyphicon glyphicon-step-forward"></span></a>
                        </div>
                    </div>
                </div>
                <div id="thirdStep" class="col-md-12 hidden">
                    <div class="col-md-12" style="margin-top:5px;padding-top:15px;">
                        <div class="row" style="float:left">
                            <a class="btn btn-default registerForm" align="right" href="{{asset('/sales')}}"> Cancelar </a>
                        </div>
                        <div class="row" style="float:right">
                            <a class="btn btn-back registerForm" align="right" href="#" onclick="nextStep('thirdStep', 'secondStep'),removeInputRedFocusVehiclesAcc()"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                            <a class="btn btn-info registerForm" align="right" href="#" style="padding: 5px" onclick="thirdStepBtnNext(),removeInputRedFocusVehiclesAcc()"> Siguiente <span class="glyphicon glyphicon-step-forward"></span></a>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;">
                        <div class="wizard_activo registerForm titleDivBorderTop">
                            <span class="titleLink">Vehiculos</span>
                            <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                        </div>
                        <!-- Vehicles Table -->
                        
                        <div class="col-md-12" style="margin-top: 15px;">
                            <table id="vehiclesTableAcc" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th style="background-color:#b3b0b0">Todos</th>
                                        <th style="background-color:#b3b0b0">RAMV</th>
                                        <th style="background-color:#b3b0b0">Placa</th>
                                        <th style="background-color:#b3b0b0;">Modelo</th>
                                        <th style="background-color:#b3b0b0">Marca</th>
                                        <th style="background-color:#b3b0b0">Color</th>
                                        <th style="background-color:#b3b0b0">Año</th>
                                        <th style="background-color:#b3b0b0;">Motor</th>
                                        <th style="background-color:#b3b0b0;">Chasis</th>
                                        <th style="background-color:#b3b0b0">Uso</th>
                                        <th style="background-color:#b3b0b0">V. Ref.</th>
                                        <th style="background-color:#b3b0b0">V. Aseg.</th>
                                        <th style="background-color:#b3b0b0">Estado</th>
                                    </tr>
                                </thead>
                                <tbody id="vehiclesBodyTableAcc" style="word-break: break-all">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;">
                        <div class="wizard_activo registerForm titleDivBorderTop">
                            <span class="titleLink">Accesorios</span>
                            <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="last_name"> Descripción</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" class="form-control registerForm" name="description" id="description" tabindex=3 placeholder="Descripción" value="{{ old('model') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="chassis"> Valor</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" class="form-control registerForm" name="value" id="value" tabindex=10 placeholder="Valor" value="" required>
                            </div>
                            <div class="form-group">
                                <a onclick="btnAccVehicles()" class="btn btn-success registerForm" align="right" href="#" style="float:right;margin-right: 0px;padding: 5px;width:100px"><span class="glyphicon glyphicon-plus"></span>Agregar </a>
                            </div>
                        </div>
                        <div class="col-md-12" style="margin-top: 15px;">
                            <table id="vehiclesAccTable" class="table table-striped table-bordered" style="border-collapse: separate !important">
                                <thead>
                                    <tr>
                                        <!--<th>N°</th>-->
                                        <th style="background-color:#b3b0b0">RAMV</th>
                                        <th style="background-color:#b3b0b0">Placa</th>
                                        <th style="background-color:#b3b0b0">Descripción</th>
                                        <th style="background-color:#b3b0b0">Valor</th>
                                        <th style="background-color:#b3b0b0">Quitar</th>
                                    </tr>
                                </thead>
                                <tbody id="vehiclesAccBodyTable" style="text-align:center;">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-12" style="padding-bottom:15px">
                        <div class="row" style="float:left">
                            <a class="btn btn-default registerForm" align="right" href="{{asset('/sales')}}"> Cancelar </a>
                        </div>
                        <div class="row" style="float:right">
                            <a  class="btn btn-back registerForm" align="right" href="#" onclick="nextStep('thirdStep', 'secondStep'),removeInputRedFocusVehiclesAcc()"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                            <a class="btn btn-info registerForm" align="right" href="#" style="padding: 5px"  onclick="thirdStepBtnNext(),removeInputRedFocusVehiclesAcc()"> Siguiente <span class="glyphicon glyphicon-step-forward"></span></a>
                        </div>
                    </div>
                </div>
                <div id="fourthStep" class="col-md-12 hidden">
                    <div class="col-md-12" style="margin-top:5px;padding-top:15px;">
                        <div class="row" style="float:left">
                            <a class="btn btn-default registerForm" align="right" href="{{asset('/sales')}}"> Cancelar </a>
                        </div>
                        <div class="row" style="float:right">
                            <a class="btn btn-back registerForm" align="right"  href="#" onclick="nextStep('fourthStep', 'thirdStep')"> <span class="glyphicon glyphicon-step-backward"></span>Anterior </a>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;">
                        <div class="wizard_activo registerForm titleDivBorderTop">
                            <span class="titleLink">Productos</span>
                            <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                        </div>
                        <div id="productAlert" class="alert alert-danger hidden registerForm titleDivBorderTop" style="margin-top:5px;border-radius:0px !important;">
                            <center><strong>¡Alerta!</strong> Debe seleccionar un producto</center>
                        </div>
                        <!-- Contenedor -->
                        <div id="productsDiv" class="pricing-wrapper clearfix" style="padding: 5% 0 5% 0;">
                        </div>
                        <div class="pricing-wrapper clearfix" style="padding: 5% 0 5% 0;">
                            
                            <!-- Modal -->
                            <div id="productModal" class="modal fade" role="dialog">
                                <div class="modal-dialog modal-lg">
                                    <!-- Modal content-->
                                    <div id="modalProductContent" class="modal-content">
                                        
                                        
                                    </div>

                                </div>
                            </div>

                        </div>
                        <input type="hidden" id="productCheckBox"name="productCheckBox" value="">
                        <input type="hidden" id="productNameCheckBox"name="productNameCheckBox" value="">
                        <input type="hidden" id="productValueCheckBox"name="productValueCheckBox" value="">
                    </div> 
                    <div class="col-md-12" style="padding-bottom:15px">
                        <div class="row" style="float:left">
                            <a class="btn btn-default registerForm" align="right" href="{{asset('/sales')}}"> Cancelar </a>
                        </div>
                        <div class="row" style="float:right">
                            <a class="btn btn-back registerForm" align="right"  href="#" onclick="nextStep('fourthStep', 'thirdStep')"> <span class="glyphicon glyphicon-step-backward"></span>Anterior </a>
                        </div>
                    </div>
                </div>
                <div id="fifthStep" class="col-md-12 hidden">
                    <div class="col-md-12" style="margin-top:5px;padding-top:15px;">
                        <div class="row" style="float:left">
                            <a class="btn btn-default registerForm" align="right" href="{{asset('/sales')}}"> Cancelar </a>
                        </div>
                        <div class="row" style="float:right">
                            <a class="btn btn-back registerForm" align="right" href="#" onclick="nextStep('fifthStep', 'fourthStep')"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                            <a class="btn btn-info registerForm" align="right" href="#" style="padding: 5px;width:95px;" onclick="executeSale()"> Cotizar </a>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;">
                        <div class="wizard_activo registerForm titleDivBorderTop">
                            <span class="titleLink">Resumen</span>
                            <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="registerForm" for="documentResume"> Identificación</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" class="form-control registerForm" name="documentResume" id="documentResume" placeholder="Placa" value="{{ old('documentResume') }}" disabled="disabled">
                            </div>
                            <div class="form-group">
                                <label class="registerForm" for="mobile_phoneResume"> Teléfono Movil </label> <span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" class="form-control registerForm" name="mobile_phoneResume" id="mobile_phoneResume" placeholder="Modelo" value="{{ old('model') }}" disabled="disabled">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="registerForm" for="customerResume"> Cliente</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" class="form-control registerForm" name="customerResume" id="customerResume" placeholder="Modelo" value="{{ old('model') }}" disabled="disabled">
                            </div>
                            <div class="form-group">
                                <label class="registerForm" for="emailResume"> Email</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="email" class="form-control registerForm" name="emailResume" id="emailResume" placeholder="Año" value="{{ old('year') }}" disabled="disabled">
                            </div>                    
                        </div>
                        <div class="col-md-8 col-md-offset-2">
                            <table id="vehiclesTableResume" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th style="background-color:#b3b0b0;width:60%">Producto</th>
                                        <th style="background-color:#b3b0b0;width:25%">Prima Neta</th>
                                    </tr>
                                </thead>
                                <tbody id="vehiclesTableBodyResume">
                                </tbody>
                            </table>
                        </div>
                        <div id="taxTableResume" class="col-md-6 col-md-offset-4" style="margin-top:-20px">
                            <table class="table table-striped table-bordered">
                                <tbody id="taxTableBodyResume">
                                </tbody>
                            </table>
                        </div>
                        <div id="benefitsTable" class="col-md-4 col-md-offset-4 hidden">
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
                        <div class="col-md-6 col-md-offset-3">
                            <center>
                                <label class="registerForm">
                                    Enviar Cotización al correo del cliente. <input type="checkbox" class="chkBoxSendQuotation" name="sendQuotation" data-toggle="toggle"  data-on="Enviar" data-off="No Enviar" data-width="100px" id="sendQuotation" value="" checked="checked">
                                </label>
                            </center>
                        </div>
                    </div>
                    <div class="col-md-12" style="padding-bottom:15px">
                        <div class="row" style="float:left">
                            <a class="btn btn-default registerForm" align="right" href="{{asset('/sales')}}"> Cancelar </a>
                        </div>
                        <div class="row" style="float:right">
                            <a class="btn btn-back registerForm" align="right" href="#" onclick="nextStep('fifthStep', 'fourthStep')"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                            <a class="btn btn-info registerForm" align="right" href="#" style="padding: 5px;width:95px;" onclick="executeSale()"> Cotizar </a>
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-top:5px;padding-top:15px;">
                        <a class="btn btn-default registerForm hidden" align="left" href="/user" style="margin-left: -15px"> Cancelar </a>
                        <input type="submit" style="float:right;margin-right: -15px;padding: 5px" class="btn btn-info registerForm hidden" align="right" value="Guardar">

                    </div>
                </div>
                <div id="fifth12Step" class="col-md-12 hidden" style="margin-top:20px">
                    <div class="col-md-12 border">
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
                            <a class="btn btn-default registerForm" align="right" href="{{asset('/sales')}}" style="margin-left: -30px;"> Cancelar </a>
                        </div>
                        <div class="col-md-1 col-md-offset-8">
                            <!--<a id="fifthStepBtnBack" class="btn btn-default registerForm" align="right" href="#" style="margin-left: 25px;background-color: #444;color:white"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>-->
                        </div>
                        <div class="col-md-1 col-md-offset-1">
                            <a id="fifthStepBtnNext" class="btn btn-info registerForm" align="right" href="#" onclick="validateCode()" style="float:right;margin-right: -25px;padding: 5px;width:80px"> Validar <span class="glyphicon glyphicon-ok"></span></a>
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
<!-- Trigger the modal with a button -->
<button id="priceModalBtn" type="button" class="btn btn-info btn-lg hidden" data-toggle="modal" data-target="#priceModal">Open Modal</button>

<!-- Modal -->
<div id="priceModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Precios de Vehiculos</h4>
      </div>
      <div class="modal-body">
        <div class="col-md-12">
            <div class="form-group">
                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="priceModalSelect"> Modelos</label>
                <select id="priceModalSelect" name="priceModalSelect" class="form-control registerForm" value="" required onchange="priceModalSelect(this.value)">
                </select>
            </div>
        </div>
      </div>
      <div class="modal-footer">
          <button id="priceModalBtnClose" type="button" class="btn btn-default registerForm" data-dismiss="modal" style="float:left">Cerrar</button>
      </div>
    </div>

  </div>
</div>
@endsection
