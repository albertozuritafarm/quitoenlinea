@extends('layouts.remote_app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<script src="{{ assets('js/sales/paymentsCreate.js') }}"></script>
<link href="{{ assets('css/sales/create.css')}}" rel="stylesheet" type="text/css"/>
<div class="container-fluid" style="width: 100%">
    <div class="col-md-8 col-md-offset-2 border" style="margin-top:10px">
        <div class="row">
            <div class="col-xs-12 registerForm" style="margin:12px;">
                <center>
                    <h4 style="font-weight:bold">Por favor ingrese los datos de su tarjeta</h4>
                </center>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-2 wizard_inicial"><div style="margin-left:-10px" class="wizard_inactivo registerForm"></div></div>
            <div class="col-xs-12 col-md-3 wizard_medio"><div id="thirdStepWizard" class="wizard_inactivo registerForm">Datos para Factura</div></div>
            <div class="col-xs-12 col-md-2 wizard_medio"><div id="fourthStepWizard" class="wizard_inactivo registerForm">Pago</div></div>
            <div class="col-xs-12 col-md-3 wizard_medio"><div id="fifthStepWizard" class="wizard_activo registerForm">Resultado</div></div>
            <div class="col-xs-12 col-md-2 wizard_final"><div style="margin-right:-10px" class="wizard_inactivo registerForm"></div></div>
        </div>
        <form id="customerForm" action="{{asset('/sales/payments/pay')}}" method="POST">
            <div id="">
                @if($success == 'true')
                    <div class="alert alert-success">
                        El pago fue procesado correctamente .
                    </div>
                @else
                <div class="alert alert-danger">
                    El pago no pudo ser procesado correctamente.<br>
                    Codigo: {{$code}}<br>
                    Mensaje: {{$message}}
                </div>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection
