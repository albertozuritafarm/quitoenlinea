@extends('layouts.app')

@section('content')
<script src="{{ assets('js/registerCustom.js') }}"></script>
<script src="{{ assets('js/benefits/create.js') }}"></script>
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
    <div class="col-md-8 col-md-offset-2 border" style="padding: 15px">
        <div class="row">
            <div class="col-xs-12 registerForm" style="margin:12px;">
                <center>
                    <h4 style="font-weight:bold">Beneficios</h4>
                    <!--<h5>Datos del Cliente.</h5>-->
                </center>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-4 wizard_inicial" style="padding-left:0px !important"><div class="wizard_inactivo"></div></div>
            <div class="col-xs-12 col-sm-4 wizard_medio"><div class="wizard_activo registerForm">Nuevo Beneficio</div></div>
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
        <br>
        <form method="POST" action="{{asset('/benefits/store')}}">
            <div class="col-md-10 col-md-offset-1 border" style="margin-top:15px">
                {{ csrf_field() }}
                <div class="col-md-6">
                    <label style="list-style-type:disc;" for="plate">Codigo:</label>
                    <div class="form-group">
                        <input type="text" class="form-control" name="code" id="code" placeholder="Codigo" value=""  maxlength="6" required>
                    </div> 
                    
                    <div class="form-group">
                        <label for="beginDate">Vigencia Desde:</label>
                        <div class="input-group date form_date" data-date="" data-date-format="dd-mm-yyyy" data-link-field="beginDate2" data-link-format="yyyy-mm-dd">
                            <input class="form-control" size="16" type="text" value="" name="beginDate" id="beginDate" end="endDate" readonly required>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                        <div class="input-group date form_date hidden" data-date="" data-date-format="dd-mm-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                            <input class="form-control" size="16" type="text" value="" name="beginDate2" id="beginDate2" end="endDate" readonly required>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                        <!--<input type="hidden" id="dtp_input2" value="" /><br/>-->
                    </div>
                    
                    <div class="form-group">
                        <label style="list-style-type:disc;" for="plate">Beneficio:</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Beneficio" value="" required>
                    </div> 
                    <div class="form-group">
                        <label style="list-style-type:disc;" for="plate">Descuento:</label><br>
                        <label class="checkbox-inline"><input type="checkbox" name="discount" class="check" value="YES">Si</label> 
                        <label class="checkbox-inline"><input type="checkbox" name="discount" class="check" value="NO" checked="checked">No</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="sel1">Canal:</label>
                        <select class="form-control" id="channel" name="channel" required>
                            <option value="">-- Escoja Una --</option>
                            <option value="0">Todos</option>
                            @foreach($channels as $channel)
                            <option value="{{$channel->id}}">{{$channel->name}}</option>
                            @endforeach
                        </select>
                    </div> 
                    <div class="form-group">
                        <label for="beginDate">Vigencia Hasta:</label>
                        <div class="input-group date form_date" data-date="" data-date-format="dd-mm-yyyy" data-link-field="endDate2" data-link-format="yyyy-mm-dd">
                            <input class="form-control" size="16" type="text" value="" name="endDate" id="endDate" end="endDate" readonly required onchange="endDateChange()">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                        <div class="input-group date form_date hidden" data-date="" data-date-format="dd-mm-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                            <input class="form-control" size="16" type="text" value="" name="endDate" id="endDate2" end="endDate" readonly required onchange="endDateChange()">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                        <!--<input type="hidden" id="dtp_input2" value="" /><br/>-->
                    </div>

                    <div class="form-group">
                        <label style="list-style-type:disc;" for="plate">N° Usos:</label>
                        <input type="number" class="form-control" name="uses" id="uses" placeholder="N° Usos" value="" required>
                    </div>
                    <div class="form-group">
                        <label style="list-style-type:disc;" for="plate">Porcentaje:</label>
                        <input type="number" class="form-control" name="percentage" id="percentage" placeholder="Porcentaje" value="" required disabled="disabled">
                    </div>
                </div>
            </div>
            <div class="col-md-10 col-md-offset-1" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                <div class="col-md-1">
                    <a class="btn btn-default registerForm" align="right" href="{{asset('/benefits')}}" style="margin-left: -30px;"> Cancelar </a>
                </div>
                <div class="col-md-1 col-md-offset-10">
                    <button type="submit" class="btn btn-info registerForm" align="right" style="float:right;margin-right: -30px;padding: 5px;width:70px" onclick="return val()">Guardar</button>
                    <!--<a type="submit" class="btn btn-info registerForm" align="right" href="#" style="float:right;margin-right: -30px;padding: 5px"> Guardar</a>-->
                </div>
            </div>
        </form>
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
