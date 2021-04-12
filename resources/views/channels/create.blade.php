@extends('layouts.app')

@section('content')
<script src="{{ assets('js/registerCustom.js') }}"></script>
<script src="{{ assets('js/channels/create.js') }}"></script>
<link href="{{assets('css/DateTimePicker/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" media="screen">
<script type="text/javascript" src="{{assets('js/DateTimePicker/bootstrap-datetimepicker.js')}}" charset="UTF-8"></script>
<script type="text/javascript" src="{{assets('js/DateTimePicker/locales/bootstrap-datetimepicker.es.js')}}" charset="UTF-8"></script>
<link href="{{ assets('FullCalendar/packages/core/main.css')}}" rel='stylesheet' />
<link href="{{ assets('FullCalendar/packages/daygrid/main.css')}}" rel='stylesheet' />
<link href="{{ assets('FullCalendar/packages/timegrid/main.css')}}" rel='stylesheet' />
<link href="{{ assets('FullCalendar/packages/list/main.css')}}" rel='stylesheet' />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<style>
    .tableSelect{
        background-color: #bababd;
    }
    .inputError{
        border-color: red;
    }
    .hidden{
        display:none;
        visibility:hidden;
    }
</style>

<div class="container" style="margin-top:15px; font-size:14px !important">
    <!--<div class="row justify-content-center border" style="margin-left:20%;">-->
    <div class="col-md-10 col-md-offset-1 border" style="padding: 15px">
        <div class="row">
            <div class="col-xs-12 registerForm" style="margin:12px;">
                <center>
                    <h4 style="font-weight:bold">Crear Canal</h4>
                    <!--<h5>Datos del Cliente.</h5>-->
                </center>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-4 wizard_inicial" style="padding-left:0px !important"><div class="wizard_inactivo"></div></div>
            <div class="col-xs-12 col-sm-4 wizard_medio"><div id="firstStepWizard" class="wizard_activo registerForm">Canal</div></div>
            <div class="col-xs-12 col-sm-4 wizard_final" style="padding-right: 0px !important"><div class="wizard_inactivo"></div></div>
        </div>
        @if (session('error'))
        <br>
        <div class="alert alert-warning">
            <center>
                {{ session('error') }}
            </center> 
        </div>
        @endif
        @if(session('errorMsg'))
            <br>
            <div class="alert alert-danger">
                <center>
                    {!!session('errorMsg')!!}
                </center> 
            </div>
        @endif
        <br>
        <div class="col-md-12">
            <div class="col-md-12">
                <form method="POST" action="{{asset('/channel/store')}}">
                    <div id="firstStep">
                        <div class="col-md-12">
                            <div class="col-md-1">
                                <a class="btn btn-default registerForm" align="right" href="{{asset('/channel')}}" style="margin-left: -30px;"> Cancelar </a>
                            </div>
                            <div class="col-md-1 col-md-offset-10">
                                <button type="submit" class="btn btn-info registerForm" align="right" style="float:right;margin-right: -30px;padding: 5px;width:80px"> Guardar </button>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;">
                            <div class="wizard_activo registerForm titleDivBorderTop">
                                <span class="titleLink">Datos del Canal</span>
                                <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                            </div>
                            {{ csrf_field() }}
                            <div class="col-md-6" style="margin-top:25px;">
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="ruc">RUC:</label>
                                    <input type="text" class="form-control" name="ruc" id="ruc" tabindex="1" placeholder="Ruc" value="{{old('ruc')}}"  maxlength="13" required>
                                    @if(session('errorRuc')) <p style="color:red;font-weight: bold">{{session('errorRuc')}}</p> @endif
                                </div> 
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="address">Dirección:</label>
                                    <input type="text" class="form-control" name="address" id="address" tabindex="3" placeholder="Dirección" value="{{old('address')}}" required>
                                    @if(session('errorAddress')) <p style="color:red;font-weight: bold">{{session('errorAddress')}}</p> @endif
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="province">Provincia:</label>
                                    <select class="form-control" id="province" name="province" tabindex="5" required>
                                        <option value="">-- Escoja Una --</option>
                                        @foreach($provinces as $prov)
                                            <option value="{{$prov->id}}" @if(old('province') == $prov->id) selected @endif>{{$prov->name}}</option>
                                        @endforeach
                                    </select>
                                    @if(session('errorProvince')) <p style="color:red;font-weight: bold">{{session('errorProvince')}}</p> @endif
                                </div> 
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="phone">Teléfono Fijo:</label>
                                    <input type="text" class="form-control" name="phone" id="phone" tabindex="7" placeholder="Teléfono Fijo" value="{{old('phone')}}" required>
                                    @if(session('errorPhone')) <p style="color:red;font-weight: bold">{{session('errorPhone')}}</p> @endif
                                </div>
                                <div class="form-group">
                                    <label class="registerForm" style="list-style-type:disc;" for="zip">Codigo Postal:</label>
                                    <input type="text" class="form-control" name="zip" id="zip" tabindex="9" placeholder="Codigo Postal" value="{{old('zip')}}">
                                    @if(session('errorZip')) <p style="color:red;font-weight: bold">{{session('errorZip')}}</p> @endif
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="province">Usuario:</label>
                                    <select class="form-control" id="sucreUser" name="sucreUser" tabindex="11" required>
                                        <option value="">-- Escoja Una --</option>
                                        @foreach($sucreUsers as $sucre)
                                            <option value="{{$sucre->id}}" @if(old('sucreUser') == $sucre->id) selected @endif>{{$sucre->first_name}} {{$sucre->last_name}}</option>
                                        @endforeach
                                    </select>
                                    @if(session('errorSucreUser')) <p style="color:red;font-weight: bold">{{session('errorSucreUser')}}</p> @endif
                                </div> 
                            </div>
                            <div class="col-md-6" style="margin-top:25px;">
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="name">Nombre:</label>
                                    <input type="text" class="form-control" name="name" id="name" tabindex="2" placeholder="Nombre" value="{{old('name')}}"  maxlength="13" required>
                                    @if(session('errorName')) <p style="color:red;font-weight: bold">{{session('errorName')}}</p> @endif
                                </div> 
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="contact">Contacto:</label>
                                    <input type="text" class="form-control" name="contact" id="contact" tabindex="4" placeholder="Contacto" value="{{old('contact')}}" required>
                                    @if(session('errorContact')) <p style="color:red;font-weight: bold">{{session('errorContact')}}</p> @endif
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="city">Ciudad:</label>
                                    <select class="form-control" id="city" name="city" tabindex="6" required>
                                        @if(old('city'))
                                            <option value="{{old('city')}}">{{session('cityName')}}</option>
                                        @else
                                            <option value="">-- Escoja Una --</option>
                                        @endif
                                    </select>
                                    @if(session('errorCity')) <p style="color:red;font-weight: bold">{{session('errorCity')}}</p> @endif
                                </div> 
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="mobile_phone">Teléfono Celular:</label>
                                    <input type="text" class="form-control" name="mobile_phone" id="mobile_phone" tabindex="8" placeholder="Teléfono Celular" value="{{old('mobile_phone')}}" required>
                                    @if(session('errorMobilePhone')) <p style="color:red;font-weight: bold">{{session('errorMobilePhone')}}</p> @endif
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="type">Tipo:</label>
                                    <select class="form-control" id="type" name="type" tabindex="10" required>
                                        <option selected="true" value="">-- Escoja Una --</option>
                                        @foreach($channelTypes as $type)
                                        <option @if($type->id == old('type')) selected="true" @endif value="{{$type->id}}">{{$type->name}}</option>
                                        @endforeach
                                    </select>
                                    @if(session('errorType')) <p style="color:red;font-weight: bold">{{session('errorType')}}</p> @endif
                                </div> 
                            </div>
                        </div>
                        <div class="col-md-12" style="padding-bottom:15px">
                            <div class="col-md-1">
                                <a class="btn btn-default registerForm" align="right" href="{{asset('/channel')}}" style="margin-left: -30px;"> Cancelar </a>
                            </div>
                            <div class="col-md-1 col-md-offset-10">
                                <button type="submit" class="btn btn-info registerForm" align="right" style="float:right;margin-right: -30px;padding: 5px;width:80px"> Guardar </button>
                            </div>
                        </div>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <!--      <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Calendario</h4>
                  </div>-->
            <div class="modal-body">
                <div id='loading'>loading...</div>
                <div id='calendar'></div>
            </div>
            <div class="modal-footer">
                <button id="modalCalendarClose" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<script>
    $('.form_date').datetimepicker({
        language: 'es',
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
    });
</script>
@endsection
