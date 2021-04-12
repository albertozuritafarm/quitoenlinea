@extends('layouts.app')

@section('content')
<script src="{{ assets('js/registerCustom.js') }}"></script>
<script src="{{ assets('js/financing/create.js') }}"></script>
<link href="{{ assets('css/sales/create.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{ assets('css/sales/index.css')}}" rel="stylesheet" type="text/css"/>
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
<meta name="csrf-token" content="{{ csrf_token() }}" />
<div class="container" style="margin-top:15px; font-size:14px !important">
    <!--<div class="row justify-content-center border" style="margin-left:20%; ">-->
    <div class="col-md-8 col-md-offset-2 border">
        <div class="row">
            <div class="col-xs-12 registerForm" style="margin:12px;">
                <center>
                    <h4 style="font-weight:bold">Proceso de Compra</h4>
                    <h5 style="font-weight:bold">Solicitud de Financiamiento</h5>
                </center>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-3 wizard_inicial" style="padding-left:5px"><div id="firstStepWizard" class="wizard_activo registerForm">Solicitud</div></div>
            <div class="col-md-3 col-sm-3 wizard_medio"><div id="secondStepWizard" class="wizard_inactivo registerForm">Comprobacion</div></div>
            <div class="col-md-3 col-sm-3 wizard_medio"><div id="thirdStepWizard" class="wizard_inactivo registerForm">Producto</div></div>
            <div class="col-md-3 col-sm-3 wizard_final" style="padding-right:5px"><div id="fourthStepWizard" class="wizard_inactivo registerForm">Validación</div></div>
        </div> 
        <!--<form action="{{route('accountStore')}}" method="POST" enctype="multipart/form-data" id="uploadForm">-->
        {{ csrf_field() }}
        <div id="firstStep">
            <div class="" style="margin-top:20px">
                <div id="customerForm"  class="col-md-12 border">
                    <div id="customerAlert" class="alert alert-danger hidden registerForm titleDivBorderTop">
                        <center><strong>¡Alerta!</strong> Revise los campos </center>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Valor Financiado</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                            <input type="text" class="form-control registerForm" name="amount" id="amount" placeholder="Valor Financiado" required="required"tabindex="1" onchange="removeInputRedFocus(this.id)">
                            <p id="amountError" style="color:red;font-weight: bold"></p>    
                        </div>
                        <div class="form-group">
                            <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="first_name"> Institución Financiera</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                            <select class="form-control registerForm" id="bank" name="bank" tabindex="2"  onchange="removeInputRedFocus(this.id)">
                                <option value="">--Escoja Una--</option>
                                @foreach($banks as $bank)
                                <option value="{{$bank->id}}">{{$bank->name}}</option>
                                @endforeach
                            </select>                        
                        </div>
                        <div class="form-group">
                            <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="first_name"> N° Factura / Orden</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                            <input class="form-control registerForm" type="text" name="number" id="number" style="line-height:14px" tabindex="3" placeholder="N° Factura/Orden"  onchange="removeInputRedFocus(this.id)" maxlength="5">
                            <p id="numberError" style="color:red;font-weight: bold"></p>    
                        </div>
                        <div class="form-group" align="left">
                            <label class="control-label" for="CHFinanciamiento">Financiamiento</label>
                            <div class="material-switch pull-right">
                                <input id="CHFinanciamiento" name="CHFinanciamiento" type="checkbox" onclick="CHFinanciamientoClick()">
                                <label for="CHFinanciamiento" class="label-success"></label>
                            </div>
                            <input id="financingCH" name="financingCH" type="hidden" value="0">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                <a class="btn btn-default registerForm" align="left" href="{{ asset('/financing') }}" style="margin-left:-15px" tabindex="30"> Cancelar </a>
                <input id="firstStepBtnNext" type="button" style="float:right;padding: 5px;width:75px;margin-right: -15px" class="btn btn-info registerForm" align="right" value="Siguiente" tabindex="31">
            </div>
        </div>
        <div id ="secondStep" class="hidden">
            <div class="col-md-12 border" style="margin-top:5px;padding-top:20px;padding-bottom:15px">
                <span class="">
                    <div class="form-group">
                        <label for="code">Identificación</label>
                        <input type="text" class="form-control" name="document" id="document" placeholder="Cédula/RUC/Pasaporte" onchange="removeInputRedFocus(this.id)"><br>
                        <p id="documentError" style="color:red;font-weight: bold"></p>    
                        <button id="resendCodeBtn" type="submit" class="btn btn-success" style="float:right;margin-bottom: 10px" onclick="validateCredit()">Comprobar</button>
                    </div>
                    <div id="resultMessage" class="">
                    </div>
                    <input type="hidden" id="resultCode" name="resultCode" value="">
                </span>
            </div>
            <div class="col-md-12" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                <input id="secondStepBtnBack" type="button" style="float:left;padding: 5px;width:75px;margin-left: -15px" class="btn btn-default registerForm" align="right" value="Anterior">
                <input id="secondStepBtnNext" type="button" style="float:right;padding: 5px;width:75px;margin-right: -15px" class="btn btn-info registerForm" align="right" value="Siguiente">
            </div>
        </div>
        <div id="thirdStep" class="hidden">
            <div id="thirdStepProductTable" class="col-md-12 border" style="margin-top:20px">
            </div>
            <div class="col-md-12" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                <input id="thirdStepBtnBack" type="button" style="float:left;padding: 5px;width:75px;margin-left: -15px" class="btn btn-default registerForm" align="right" value="Anterior">
                <input id="thirdStepBtnNext" type="button" style="float:right;padding: 5px;width:75px;margin-right: -15px" class="btn btn-info registerForm" align="right" value="Siguiente">
            </div>
        </div>
        <div id="fourthStep" class="hidden">
            <div class="col-md-12 border" style="margin-top:20px">
                <span class="">
                    <div id="resultMessageCode" class="">
                    </div>
                    <div id="validationCode">
                        <input type="hidden" name="crId" id="crId" value="">
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
@endsection
