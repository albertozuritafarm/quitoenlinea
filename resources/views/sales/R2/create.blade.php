@extends('layouts.app')

@section('content')
<script src="{{ assets('js/registerCustom.js') }}"></script>
<script src="{{ assets('js/sales/R2/create.js') }}"></script>

<link href="{{ assets('css/sales/create.css')}}" rel="stylesheet" type="text/css"/>
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
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
            <div class="col-xs-12 col-md-3 wizard_inicial"><div style="margin-left:-10px" id="zeroStepWizard" class="wizard_inactivo registerForm">Ramo</div></div>
            <div class="col-xs-12 col-md-3 wizard_medio"><div id="firstStepWizard" class="wizard_activo registerForm">Cliente</div></div>
            <div class="col-xs-12 col-md-3 wizard_medio"><div id="secondStepWizard" class="wizard_inactivo registerForm">Producto</div></div>
            <div class="col-xs-12 col-md-3 wizard_final"><div style="margin-right:-10px;" id="thirdStepWizard" class="wizard_inactivo registerForm">Resumen</div></div>
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
                            <a id="secondStepBtnNextTop" class="btn btn-info registerForm" align="right" href="#" style="padding: 5px" onclick="firstStepBtnNext()" > Siguiente <span class="glyphicon glyphicon-step-forward"></span></a>
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
                            <form>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Cédula</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <!--<input type="text" class="form-control registerForm" name="document" id="document" placeholder="Cédula" value="{{ old('document') }}" required="required">-->
                                        <div class="form-inline">
                                            <input autocomplete="off" type="text" class="form-control registerForm" name="document" id="document" value="{{$customer->document}}" @if($disabled) disabled="disabled" @else @endif placeholder="Cédula" required="required"tabindex="1" style="width:89%">
                                            <button type="button" class="btn btn-info" onclick="documentBtn()" @if($disabled) disabled="disabled" @else @endif style="width:10%"><span class="glyphicon glyphicon-search"></span></button>
                                            <div id="suggesstion-box"></div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document_id"> Tipo Documento</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <select id="document_id" name="document_id" class="form-control registerForm" value="{{old('document_id')}}" required tabindex="2" disabled="disabled">
                                            <option selected="true" value="0">--Escoja Una---</option>
                                            @foreach($documents as $document)
                                            <option @if($document->id == $customer->document_id) selected="true" @else @endif value="{{$document->id}}">{{$document->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="first_name"> Primer Nombre</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" class="form-control registerForm" name="first_name" id="first_name" placeholder="Primer Nombre" value="{{ $customer->first_name }}" required="required" tabindex="3" disabled="disabled" maxlength="30">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" style="list-style-type:disc;" for="second_name"> Segundo Nombre</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" class="form-control registerForm" name="second_name" id="second_name" placeholder="Segundo Nombre" value="{{ $customer->second_name }}" required="required" tabindex="4" disabled="disabled" maxlength="30"> 
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="last_name"> Primer Apellido</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" class="form-control registerForm" name="last_name" id="last_name" placeholder="Primer Apellido" value="{{ $customer->last_name }}" required tabindex="5" disabled="disabled" maxlength="30">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="second_last_name"> Segundo Apellido</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" class="form-control registerForm" name="second_last_name" id="second_last_name" placeholder="Segundo Apellido" value="{{ $customer->second_last_name }}" required tabindex="6" disabled="disabled" maxlength="30">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="birthdate"> Fecha de Nacimiento </label> <span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="date" class="form-control registerForm" name="birthdate" id="birthdate" data-date-format="dd/mm/yyyy" placeholder="Fecha de Nacimiento" value="{{ $customer->birthdate }}" required tabindex="7" style="line-height: 14px;">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="city"> Celular</label> <span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span><label class="own"><span class="glyphicon glyphicon-info-sign" style="color:#0099ff"><span class="own1" style="float:left">El celular debe tener 10 caracteres</span></span></label>
                                        <input type="text" class="form-control registerForm" name="mobile_phone" id="mobile_phone" placeholder="Celular" value="{{ $customer->mobile_phone }}" required tabindex="7">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Teléfono </label> <span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span><label class="own"><span class="glyphicon glyphicon-info-sign" style="color:#0099ff"><span class="own1" style="float:left">El telefono debe tener 9 caracteres</span></span></label>
                                        <input type="text" class="form-control registerForm" name="phone" id="phone" placeholder="Teléfono" value="{{ $customer->phone }}" required tabindex="8">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="city"> Dirección</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="string" class="form-control registerForm" name="address" id="address" placeholder="Dirección" required tabindex="9" value="{{ $customer->address }}" maxlength="200">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="correo"> Correo</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="email" class="form-control registerForm" name="email" id="email" placeholder="Correo" value="{{ $customer->email }}" required tabindex="10" maxlength="100">
                                        <p id="emailError" style="color:red;font-weight: bold; display: none;"></p>    
                                        <!--<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>-->
                                        @if($errors->any())
                                        <span style="color:red;font-weight:bold">{{$errors->first()}}</span>
                                        @endif
                                    </div>
                                    <div class="form-group col-md-6">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="country"> País</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <select name="country" id="country" class="form-control registerForm" required tabindex="11">
                                            <option selected="true" value="0">--Escoja Una---</option>
                                            @foreach($countries as $country)
                                            <option @if($country->id == $cusCountry->id) selected="true" @else @endif value="{{$country->id}}">{{$country->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="province"> Provincia</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <select name="province" class="form-control registerForm" id="province" required tabindex="12">
                                            @if($cusProvinceList)
                                            <option id="provinceSelect" selected="true" value="{{$cusProvince->id}}">{{$cusProvince->name}}</option>
                                            @else
                                            <option id="provinceSelect" selected="true" disabled="disabled" value="0">--Escoja Una---</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="city"> Canton</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <select name="city" class="form-control registerForm" id="city" required tabindex="13">
                                            @if($cusCityList)
                                            <option id="citySelect" selected="true" value="{{$cusCity->id}}">{{$cusCity->name}}</option>
                                            @else
                                            <option id="citySelect" selected="true" disabled="disabled" value="0">--Escoja Uno---</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-12" style="padding-bottom:15px">
                        <div class="row" style="float:left">
                            <a class="btn btn-default registerForm" align="right" href="{{asset('/sales')}}" tabindex="16"> Cancelar </a>
                        </div>
                        <div class="row" style="float:right">
                            <a class="btn btn-back registerForm" align="right" @if($disabled == null) href="{{asset('/sales/product/select')}}" @else href="#" disabled="disabled" @endif  tabindex="15"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                            <a class="btn btn-info registerForm" align="right" href="#" style="padding: 5px" onclick="firstStepBtnNext()" tabindex="14"> Siguiente <span class="glyphicon glyphicon-step-forward"></span></a>
                        </div>
                    </div>
                </div>
                <div id="secondStep" class="col-md-12 hidden">
                    <div class="col-md-12" style="margin-top:5px;padding-top:15px;">
                        <div class="row" style="float:left">
                            <a class="btn btn-default registerForm" align="right" href="{{asset('/sales')}}" tabindex="18"> Cancelar </a>
                        </div>
                        <div class="row" style="float:right">
                            <a class="btn btn-back registerForm" align="right"  href="#" onclick="nextStep('secondStep', 'firstStep')" tabindex="17"> <span class="glyphicon glyphicon-step-backward"></span>Anterior </a>
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
                            <a class="btn btn-default registerForm" align="right" href="{{asset('/sales')}}" tabindex="20"> Cancelar </a>
                        </div>
                        <div class="row" style="float:right">
                            <a class="btn btn-back registerForm" align="right"  href="#" onclick="nextStep('secondStep', 'firstStep')" tabindex="19"> <span class="glyphicon glyphicon-step-backward"></span>Anterior </a>
                        </div>
                    </div>
                </div>
                <div id="thirdStep" class="col-md-12 hidden">
                    <div class="col-md-12" style="margin-top:5px;padding-top:15px;">
                        <div class="row" style="float:left">
                            <a class="btn btn-default registerForm" align="right" href="{{asset('/sales')}}" tabindex="23"> Cancelar </a>
                        </div>
                        <div class="row" style="float:right">
                            <a class="btn btn-back registerForm" align="right" href="#" onclick="nextStep('thirdStep', 'secondStep')"><span class="glyphicon glyphicon-step-backward" tabindex="22"></span> Anterior </a>
                            <a class="btn btn-info registerForm" align="right" href="#" style="padding: 5px;width:95px;" onclick="executeSale()" tabindex="21"> Cotizar </a>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;">
                        <div class="wizard_activo registerForm titleDivBorderTop">
                            <span class="titleLink">Resumen</span>
                            <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                        </div>
                        <form action="" style="margin-top:25px;">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label class="registerForm" for="documentResume"> Identificación</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="documentResume" id="documentResume" placeholder="Identificación" value="{{ old('documentResume') }}" disabled="disabled">
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="registerForm" for="customerResume"> Cliente</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="customerResume" id="customerResume" placeholder="Cliente" value="{{ old('model') }}" disabled="disabled">                                
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label class="registerForm" for="mobile_phoneResume"> Teléfono Movil </label> <span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="mobile_phoneResume" id="mobile_phoneResume" placeholder="Teléfono Movil" value="{{ old('model') }}" disabled="disabled">
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="registerForm" for="emailResume"> Email</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="email" class="form-control registerForm" name="emailResume" id="emailResume" placeholder="Email" value="{{ old('year') }}" disabled="disabled" maxlength="100">                             
                                </div>
                            </div>
                            <div class="col-md-8 col-md-offset-2">
                                <table id="lifeTableResume" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="background-color:#b3b0b0;width:60%">Producto</th>
                                            <th style="background-color:#b3b0b0;width:25%">Prima Neta</th>
                                        </tr>
                                    </thead>
                                    <tbody id="R2TableBodyResume">
                                    </tbody>
                                </table>
                            </div>
                            <div id="taxTableResume" class="col-md-6 col-md-offset-4" style="margin-top:-20px">
                                <table class="table table-bordered">
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
                                        Enviar Cotización al correo del cliente. <input type="checkbox" class="chkBoxSendQuotation" name="sendQuotation" data-toggle="toggle"  data-on="Enviar" data-off="No Enviar" data-width="100px" id="sendQuotation" value="" checked="checked" tabindex="24">
                                    </label>
                                </center>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-12" style="padding-bottom:15px">
                        <div class="row" style="float:left">
                            <a class="btn btn-default registerForm" align="right" href="{{asset('/sales')}}" tabindex="27"> Cancelar </a>
                        </div>
                        <div class="row" style="float:right">
                            <a class="btn btn-back registerForm" align="right" href="#" onclick="nextStep('thirdStep', 'secondStep')" tabindex="26"><span class="glyphicon glyphicon-step-backward" ></span> Anterior </a>
                            <a class="btn btn-info registerForm" align="right" href="#" style="padding: 5px;width:95px;" onclick="executeSale()" tabindex="25"> Cotizar </a>
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-top:5px;padding-top:15px;">
                        <a class="btn btn-default registerForm hidden" align="left" href="/user" style="margin-left: -15px"> Cancelar </a>
                        <input type="submit" style="float:right;margin-right: -15px;padding: 5px" class="btn btn-info registerForm hidden" align="right" value="Guardar">

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
        <h4 class="modal-title">Precios de Vida</h4>
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
