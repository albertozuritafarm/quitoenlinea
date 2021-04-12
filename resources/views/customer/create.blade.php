@extends('layouts.app')

@section('content')
<script src="{{ assets('js/registerCustom.js') }}"></script>
<script src="{{ assets('js/customer/create.js') }}"></script>
<link href="{{assets('css/DateTimePicker/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" media="screen">
<link href="{{assets('css/sales/create.css')}}" rel="stylesheet" media="screen">
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
    <div class="col-md-9 col-md-offset-1 border" style="padding: 15px">
        <div class="row">
            <div class="col-xs-12 registerForm" style="margin:12px;">
                <center>
                    <h4 style="font-weight:bold">Crear Cliente</h4>
                    <!--<h5>Datos del Cliente.</h5>-->
                </center>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-4 wizard_inicial" style="padding-left:0px !important"><div class="wizard_inactivo"></div></div>
            <div class="col-xs-12 col-sm-4 wizard_medio"><div id="firstStepWizard" class="wizard_activo registerForm">Cliente</div></div>
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
                <form>
                    <div id="firstStep">
                        <div class="col-md-12">
                            <div class="col-md-1">
                                <a class="btn btn-default registerForm" align="right" href="{{asset('/customer')}}" style="margin-left: -30px;"> Cancelar </a>
                            </div>
                            <div class="col-md-1 col-md-offset-10">
                                <button type="submit" class="btn btn-info registerForm" align="right" style="float:right;margin-right: -30px;padding: 5px;width:80px"> Guardar </button>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;">
                            <div class="wizard_activo registerForm titleDivBorderTop">
                                <span class="titleLink">Datos del Cliente</span>
                                <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                            </div>
                            <div id="customerAlert" class="alert alert-danger hidden registerForm titleDivBorderTop" style="margin-top:5px; border-radius: 0px !important">">
                                <center><strong>¡Alerta!</strong> Revise los campos </center>
                            </div>
                            {{ csrf_field() }}
                            <div class="col-md-6" style="margin-top:25px;">
                                <div class="form-group form-inline">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Cédula</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <!--<input type="text" class="form-control registerForm" name="document" id="document" placeholder="Cédula" value="{{ old('document') }}" required="required">-->
                                    <div class="form-inline">
                                        <input autocomplete="off" type="text" class="form-control registerForm" name="document" id="document" placeholder="Cédula" required="required"tabindex="1" style="width:88%" value="{{old('document')}}" onkeyup="removeErrorMsg('Document')">
                                        <button type="button" class="btn btn-info" id="documentBtn" onclick="documentBtn()"><span class="glyphicon glyphicon-search"></span></button>
                                        @if(session('errorDocument')) <p id="errorMsgDocument" style="color:red;font-weight: bold">{{session('errorDocument')}}</p> @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="first_name"> Nombre(s)</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="first_name" id="first_name" placeholder="Nombre" value="{{ old('first_name') }}" required="required" tabindex="3" onkeyup="removeErrorMsg('FirstName')">
                                    @if(session('errorFirstName')) <p id="errorMsgFirstName" style="color:red;font-weight: bold">{{session('errorFirstName')}}</p> @endif
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="city"> Celular</label> <span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span><label class="own"><span class="glyphicon glyphicon-info-sign" style="color:#0099ff"><span class="own1" style="float:left">El celular debe contener 10 caracteres</span></span></label>
                                    <input type="text" class="form-control registerForm" name="mobile_phone" id="mobile_phone" placeholder="Celular" value="{{ old('mobile_phone') }}" required tabindex="5" onkeyup="removeErrorMsg('MobilePhone')">
                                    @if(session('errorMobilePhone')) <p id="errorMsgMobilePhone" style="color:red;font-weight: bold">{{session('errorMobilePhone')}}</p> @endif
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="city"> Direccion</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="address" id="address" placeholder="Direccion" required tabindex="7" value="{{old('address')}}" onkeyup="removeErrorMsg('Address')">
                                    @if(session('errorAddress')) <p id="errorMsgAddress" style="color:red;font-weight: bold">{{session('errorAddress')}}</p> @endif
                                </div>

                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="country"> Pais</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select name="country" id="country" class="form-control registerForm" required tabindex="9">
                                        <option value="">--Escoja Una---</option>
                                        @foreach($countries as $country)
                                        <option @if($country->id == old('country')) selected="true" @else @endif value="{{$country->id}}">{{$country->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="city"> Ciudad</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select name="city" class="form-control registerForm" id="city" required tabindex="11">
                                        @if(old('city'))
                                        <option id="citySelect"  value="{{old('city')}}">{{session('cityName')}}</option>
                                        @else
                                        <option id="citySelect" selected="true" disabled="disabled" value="">--Escoja Una---</option>
                                        @endif
                                    </select>
                                    @if(session('errorCity')) <p id="errorMsgCity" style="color:red;font-weight: bold">{{session('errorCity')}}</p> @endif
                                </div>
                            </div>
                            <div class="col-md-6" style="margin-top:25px;">
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document_id"> Tipo Documento</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select id="document_id" name="document_id" class="form-control registerForm" value="{{old('document_id')}}" required tabindex="2">
                                        <option value="">--Escoja Una---</option>
                                        @foreach($documents as $document)
                                        <option @if($document->id == old('document_id')) selected="true" @else @endif value="{{$document->id}}">{{$document->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="last_name"> Apellido(s)</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="text" class="form-control registerForm" name="last_name" id="last_name" placeholder="Apellido" value="{{ old('last_name') }}" required tabindex="4" onkeyup="removeErrorMsg('LastName')">
                                    @if(session('errorLastName')) <p id="errorMsglastName" style="color:red;font-weight: bold">{{session('errorLastName')}}</p> @endif
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Teléfono</label> <span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span><label class="own"><span class="glyphicon glyphicon-info-sign" style="color:#0099ff"><span class="own1" style="float:left">El telefono debe tener 9 caracteres</span></span></label>
                                    <input type="text" class="form-control registerForm" name="phone" id="phone" placeholder="Teléfono" value="{{ old('phone') }}" required tabindex="6" onkeyup="removeErrorMsg('Phone')">
                                    @if(session('errorPhone')) <p id="errorMsgPhone" style="color:red;font-weight: bold">{{session('errorPhone')}}</p> @endif
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="correo"> Correo</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <input type="email" class="form-control registerForm" name="email" id="email" placeholder="Correo" value="{{old('email')}}" required tabindex="8" onkeyup="removeErrorMsg('Email')">
                                    <p id="emailError" style="color:red;font-weight: bold"></p>    
                                  <!--<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>-->
                                    @if($errors->any())
                                    <span style="color:red;font-weight:bold">{{$errors->first()}}</span>
                                    @endif
                                    @if(session('errorEmail')) <p id="errorMsgEmail" style="color:red;font-weight: bold">{{session('errorEmail')}}</p> @endif
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="province"> Canton</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                    <select name="province" class="form-control registerForm" id="province" required tabindex="10">

                                        @if(old('province'))
                                        <option id="provinceSelect"  value="{{old('province')}}">{{session('provinceName')}}</option>
                                        @else
                                        <option id="provinceSelect" selected="true" disabled="disabled" value="">--Escoja Una---</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" style="padding-bottom:15px">
                            <div class="col-md-1">
                                <a class="btn btn-default registerForm" align="right" href="{{asset('/customer')}}" style="margin-left: -30px;"> Cancelar </a>
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
@endsection
