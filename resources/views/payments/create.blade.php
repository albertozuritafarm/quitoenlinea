@extends('layouts.app')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}" />
<script src="{{ assets('js/payments/create.js') }}"></script>
<link href="{{ assets('css/sales/create.css')}}" rel="stylesheet" type="text/css"/>
<div class="container" style="width: 100%">
    <div class="col-md-8 col-md-offset-2 border" style="margin-top:10px">
        <div class="row">
            <div class="col-xs-12 registerForm" style="margin:12px;">
                <center>
                    <h4 style="font-weight:bold">Por favor ingrese los datos de su tarjeta</h4>
                </center>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" style="padding-left:5px !important;padding-right: 5px">
                <div class="col-md-4 wizard_inactivo registerForm">
                </div>
                <div id="cash" class="col-md-4 wizard_activo registerForm" onclick="cashDivClick()" style="cursor: pointer;">
                    <span><input id="cashRadioBtn" type="radio" name="option" value="creditCard" checked> Botón de Pagos</span>
                </div>
                <div class="col-md-4 wizard_inactivo registerForm">
                </div>
            </div>
        </div>
        <br><br>
        @include('payments.form')
        <div class="col-md-10 col-md-offset-1" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
            <a class="btn btn-default registerForm" align="left" href="{{ asset('/payments') }}" style="margin-left: -15px;"> Cancelar </a>
            <input id="cancelBtn" type="submit" style="float:right;margin-right: -15px;padding: 5px;width:85px" class="btn btn-info registerForm" align="right" value="Pagar">
        </div>
    </div>
</div>

@endsection
