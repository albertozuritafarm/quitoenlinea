@extends('layouts.app')

@section('content')
<!--<div class="se-pre-con"></div>-->
<meta name="csrf-token" content="{{ csrf_token() }}" />
<script src="{{ assets('js/reports/moment.js') }}"></script>
<script src="{{ assets('js/reports/reportFilter.js') }}"></script>
<link href="{{assets('css/DateTimePicker/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" media="screen">
<script type="text/javascript" src="{{assets('js/DateTimePicker/bootstrap-datetimepicker.js')}}" charset="UTF-8"></script>
<script type="text/javascript" src="{{assets('js/DateTimePicker/locales/bootstrap-datetimepicker.es.js')}}" charset="UTF-8"></script>
<link href="{{ assets('FullCalendar/packages/core/main.css')}}" rel='stylesheet' />
<link href="{{ assets('FullCalendar/packages/daygrid/main.css')}}" rel='stylesheet' />
<link href="{{ assets('FullCalendar/packages/timegrid/main.css')}}" rel='stylesheet' />
<link href="{{ assets('FullCalendar/packages/list/main.css')}}" rel='stylesheet' />
<!--<link href="{{ asset('css/payments/index.css')}}" rel="stylesheet" type="text/css"/>-->
<div class="container" style="margin-top:5px;width: 100%">
    <div>
        <div class="row">
            <div class="col-xs-12 col-md-3 wizard_inicial"><div class="wizard_inactivo registerForm"></div></div>
            <div class="col-xs-12 col-md-1 wizard_medio"><div class="wizard_inactivo registerForm"></div></div>
            <div class="col-xs-12 col-md-4 wizard_medio"><div class="wizard_activo registerForm">REPORTE TÉCNICO VEHÍCULOS</div></div>
            <div class="col-xs-12 col-md-1 wizard_medio"><div class="wizard_inactivo registerForm"></div></div>
            <div class="col-xs-12 col-md-3 wizard_final"><div class="wizard_inactivo registerForm"></div></div>
        </div>
        <div class="col-md-12 border" style="padding: 5px; margin-top: 15px;">
            <div class="col-md-12 border" style="margin-top:10px;margin-left:0;margin-right:15px;">
                <form method="POST" action="{{asset('/TevehiclesReport')}}" onsubmit="return val()">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <span class= "glyphicon glyphicon-asterisk" style="color:#0099ff"></span><label style="list-style-type:disc;" for="beginDate">Fecha Inicio Emisión:</label>
                                <input type="date" class="form-control" name="beginDate" id="beginDate" placeholder="fecha" style="line-height:14px" value="">
                            </div>
                        </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <span class= "glyphicon glyphicon-asterisk" style="color:#0099ff"></span><label style="list-style-type:disc;" for="endDate">Fecha Fin Emisión: </label>
                                    <input type="date" class="form-control" name="endDate" id="endDate" placeholder="fecha" style="line-height:14px" onchange="endDateChange()" value="">
                                </div>
                            </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="agent">Agente:</label>
                                <select class="form-control" id="agent" name="agent">
                                    <option value="">--Escoja Uno--</option>
                                    @foreach($agents as $agent)
                                    <option value="{{$agent->id}}">{{$agent->agentedes}}</option>
                                    @endforeach
                                </select>
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="channel">Canal:</label>
                                <select class="form-control" id="channel" name="channel">
                                    <option value="">--Escoja Uno--</option>
                                </select>
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="agency">Agencia Canal:</label>
                                <select class="form-control" id="agency" name="agency">
                                    <option value="">--Escoja Uno--</option>
                                </select>
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="ejecutivo_ss">Ejecutivo Comercial:</label>
                                <select class="form-control" id="ejecutivo_ss" name="ejecutivo_ss">
                                    <option value=''>--Escoja Uno--</option>
                                </select>
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="agentss">Agencia S. Sucre:</label>
                                <select class="form-control" id="agentss" name="agentss">
                                    <option value="">--Escoja Uno--</option>
                                    @foreach($agencyss as $agency)
                                    <option value="{{$agency->id}}">{{$agency->agenciades}}</option>
                                    @endforeach
                                </select>
                            </div> 
                        </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="type_policy">Tipo de Póliza:</label>
                                    <select class="form-control" id="type_policy" name="type_policy">
                                        <option value=''>--Escoja Una--</option>
                                        <option value=''>Emisión</option>
                                    </select>
                                </div> 
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="ramo">Ramo:</label>
                                    <select class="form-control" id="ramo" name="ramo" disabled='disabled'>
                                        <option value="">Vehículo</option>
                                    </select>
                                </div> 
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="product">Producto:</label>
                                    <select class="form-control" id="product" name="product">
                                        <option value="">--Escoja Uno--</option>
                                        @foreach($products as $pro)
                                        <option value="{{$pro->id}}">{{$pro->name}}</option>
                                        @endforeach
                                    </select>
                                </div> 
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="state">Estado:</label>
                                    <select class="form-control" id="state" name="state">
                                        <option value="">--Escoja Uno--</option>
                                        @foreach($status as $st)
                                        <option value="{{$st->id}}">{{$st->name}}</option>
                                        @endforeach
                                    </select>
                                </div> 
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label style="list-style-type:disc;" for="sale_id">ID Venta:</label>
                                    <input type="text" class="form-control" name="sale_id" id="sale_id" placeholder="Id venta" style="line-height:14px" value="">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="province">Provincia:</label>
                                    <select class="form-control" id="province" name="province">
                                        <option value="">--Escoja Una--</option>
                                        @foreach($provincies as $province)
                                            <option value="{{$province->id}}">{{$province->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                <label for="city">Canton:</label>
                                <select class="form-control" id="city" name="city">
                                    <option value=''>--Escoja Uno--</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="paymenttype">Tipo de Pago:</label>
                                <select class="form-control" id="paymenttype" name="paymenttype">
                                    <option value="">--Escoja Uno--</option>
                                    @foreach($paymentsTypes as $payments)
                                    <option value="{{$payments->id}}">{{$payments->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="brand">Marca</label>
                                <select class="form-control" id="brand" name="brand">
                                    <option value="">--Escoja Uno--</option>
                                    @foreach($vehicleBrands as $vehicleBrand)
                                    <option value="{{$vehicleBrand->id}}">{{$vehicleBrand->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="uses">Uso de vehículo</label>
                                <select class="form-control" id="uses" name="uses">
                                    <option value="">--Escoja Uno--</option>
                                    @foreach($uses as $use)
                                    <option value="{{$use->id}}">{{$use->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-top: 10px">
                        <!--<input type="button" id="btnCancel" class="btn btn-default" value="Cancelar">-->
                        <input type="button" id="btnClearFilter" class="btn btn-default registerForm" value="Limpiar" style="float:left;margin-left:-15px">
                        <input type="submit" class="btn btn-info registerForm" value="Generar" style="float:right;margin-right: -15px;padding:5px">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
