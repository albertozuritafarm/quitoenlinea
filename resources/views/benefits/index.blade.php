@extends('layouts.app')

@section('content')
<!--<div class="se-pre-con"></div>-->
<meta name="csrf-token" content="{{ csrf_token() }}" />
<script src="{{ assets('js/benefits/index.js') }}"></script>
<link href="{{assets('css/DateTimePicker/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" media="screen">
<script type="text/javascript" src="{{assets('js/DateTimePicker/bootstrap-datetimepicker.js')}}" charset="UTF-8"></script>
<script type="text/javascript" src="{{assets('js/DateTimePicker/locales/bootstrap-datetimepicker.es.js')}}" charset="UTF-8"></script>
<link href="{{ assets('FullCalendar/packages/core/main.css')}}" rel='stylesheet' />
<link href="{{ assets('FullCalendar/packages/daygrid/main.css')}}" rel='stylesheet' />
<link href="{{ assets('FullCalendar/packages/timegrid/main.css')}}" rel='stylesheet' />
<link href="{{ assets('FullCalendar/packages/list/main.css')}}" rel='stylesheet' />
<!--<link href="{{ asset('css/payments/index.css')}}" rel="stylesheet" type="text/css"/>-->
<div class="container" style="width: 100%">
    <div>
        <div class="col-md-12 hidden border" id="filter" style="margin-top:10px;margin-left:0;margin-right:15px;">
            <form method="POST" action="{{asset('/benefits')}}">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sel1">Canal:</label>
                            <select class="form-control" id="channel" name="channel">
                                <option value="">--Escoga Uno--</option>
                                @foreach($channels as $channel)
                                @if($channel->id == session('benefitsChannel'))
                                <option value="{{$channel->id}}" selected="">{{$channel->name}}</option>
                                @else
                                <option value="{{$channel->id}}">{{$channel->name}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div> 
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="beginDate">Fecha Inicio</label>
                            <input type="date" class="form-control" name="beginDate" id="beginDate" placeholder="fecha" style="line-height:14px" value="{{session('benefitsBeginDate')}}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="endDate">Fecha Fin</label>
                            <input type="date" class="form-control" name="endDate" id="endDate" placeholder="fecha" style="line-height:14px" onchange="endDateChange()" value="{{session('benefitsEndDate')}}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sel1">Estado:</label>
                            <select class="form-control" id="status" name="status">
                                <option value=''>--Escoja Una--</option>
                                @foreach($status as $sta)
                                <option @if($sta->id == session('benefitsStatus')) selected="true" @else @endif value='{{$sta->id}}'>{{$sta->name}}</option>
                                @endforeach
                            </select>
                        </div> 
                    </div>
                </div>

                <input type="hidden" name="items" id="items" value="{{$items}}">
                <input type="button" id="btnCancel" class="btn btn-default" value="Cancelar">
                <input type="button" id="btnClearBenefits" class="btn btn-default" value="Limpiar">
                <input id="btnFilterForm" type="submit" class="btn btn-primary" value="Aplicar" onclick="return val()">
            </form>
        </div>
        <div class="col-md-12" style="margin-left: -15px">
            <h4>Listado de Beneficios </h4>
            @if (session('editSuccess'))
            <div class="alert alert-success">
                <center>
                    {{ session('editSuccess') }}
                </center>
            </div>
            @endif
            @if (session('cancelSuccess'))
            <div class="alert alert-success">
                <center>
                    {{ session('cancelSuccess') }}
                </center>
            </div>
            @endif
            <div id="confirmMessage" class="alert alert-success hidden" style="margin-right:-20px">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <center>
                    <span class="glyphicon glyphicon-ok"  style="font-weight: bold;"> El agendamiento fue confirmado Correctamente</span>
                </center>
            </div>
            <div id="cancelMessage" class="alert alert-success hidden" style="margin-right:-20px">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <center>
                    <span class="glyphicon glyphicon-ok"  style="font-weight: bold;"> El agendamiento fue cancelado Correctamente</span>
                </center>
            </div>
            <button class="border btnTable" type="button" id="filterButton"><img id="filterImg" src="{{assets('/images/filter.png')}}" width="24" height="24" alt=""></button> 
            @if($edit)
            <a type="button" class="border btnTable" href="{{asset('/benefits/create')}}" data-toggle="tooltip" title="Nuevo"><img src="{{assets('/images/mas.png')}}" width="24" height="24" alt=""></a>
            @else
            @endif
            @include('pagination.items')
        </div>
        <div id="tableData">
            @include('pagination.benefits')
        </div>
        </div>
    </div>
    <!-- EDIT MODAL-->
    <!-- Trigger the modal with a button -->
    <button id="modalEditBtn" type="button" class="btn btn-info btn-lg hidden" data-toggle="modal" data-target="#myModalEdit">Open Modal</button>
    <!-- Modal -->
    <div id="myModalEdit" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Editar Beneficio</h4>
                </div>
                <div id="errorModalEdit" class="alert alert-danger hidden">
                    <center>
                        No se pudo actualizar el beneficio por favor verifique los datos ingresados.
                    </center>
                </div>
                <div id="modalBody" class="modal-body">
                    <div class="form-group">
                        <label for="beginDate">Vigencia Desde:</label>
                        <div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                            <input class="form-control" size="16" type="text" value="" name="beginDateModal" id="beginDateModal" end="endDate" readonly required>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                        <!--<input type="hidden" id="dtp_input2" value="" /><br/>-->
                    </div>

                    <div class="form-group">
                        <label for="beginDate">Vigencia Hasta:</label>
                        <div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                            <input class="form-control" size="16" type="text" value="" name="endDateModal" id="endDateModal" end="endDate" readonly required onchange="endDateChange()">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                        <!--<input type="text" id="dtp_input2" value="" />-->
                    </div>
                    <div class="form-group">
                        <label style="list-style-type:disc;" for="plate">N° Usos:</label>
                        <input type="text" class="form-control" name="uses" id="uses" placeholder="N° Usos" value="" required>
                    </div>
                    <input type="hidden" id="benefitsId" name="benefitsId" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="float: left">Cerrar</button>
                    <a type="submit" href="#" class="btn btn-info" style="float:right" onclick="updateBenefit()">Actualizar</a>
                </div>
            </div>

        </div>
    </div>
    <!-- CANCEL MODAL-->
    <!-- Trigger the modal with a button -->
    <button id="modalCancelBtn" type="button" class="btn btn-info btn-lg hidden" data-toggle="modal" data-target="#myModalCancel">Open Modal</button>
    <!-- Modal -->
    <div id="myModalCancel" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Cancelar Beneficio</h4>
                </div>
                <div id="errorModalCancel" class="alert alert-danger hidden">
                    <center>
                        No se pudo cancelar el beneficio por favor verifique los datos ingresados.
                    </center>
                </div>
                <div id="modalBody" class="modal-body">
                    <div class="form-group">
                        <label for="sel1">Motivo:</label>
                        <select class="form-control" id="cancelMotive" name="cancelMotive">
                            <option value="">--Escoga Uno--</option>
                            @foreach($cancelMotives as $motive)
                            <option value="{{$motive->id}}">{{$motive->name}}</option>
                            @endforeach
                        </select>
                    </div> 
                    <input type="hidden" id="benefitsIdCancel" name="benefitsIdCancel" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="float: left">Cerrar</button>
                    <a type="submit" href="#" class="btn btn-info" style="float:right" onclick="cancelBenefit()">Cancelar</a>
                </div>
            </div>

        </div>
    </div>
    <!-- ADD MODAL-->
    <!-- Modal -->
    <div id="myModalAdd" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Agendar Beneficio</h4>
                </div>
                <div id="errorModalCancel" class="alert alert-danger hidden">
                    <center>
                        No se pudo cancelar el beneficio por favor verifique los datos ingresados.
                    </center>
                </div>
                <div id="divError" class="alert alert-danger hidden">
                    <center>
                        La placa ingresada no se encuentra en nuestro sistema.
                    </center>
                </div>
                <div id="divSuccess" class="alert alert-success hidden">
                    <center>
                        El uso del beneficio fue agendado correctamente.
                    </center>
                </div>
                <div id="modalBody" class="col-md-12 modal-body">
                    <div class="col-md-12 border">
                        <div class="col-md-6 col-md-offset-4">
                            <div class="form-group form-inline">
                                <label for="plate">Placa:</label>
                                <input type="text" class="form-control" name="plateModal" id="plateModal" placeholder="Placa" value="" required style="width:50%">
                                <button type="button" class="btn btn-info" id="plateBtn" onclick="plateBtn()"><span class="glyphicon glyphicon-search"></span></button>
                            </div> 

                        </div>
                        <div id="tableBenefits" class="col-md-12">

                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="float: left">Cerrar</button>
                    <a id="modalBenefitsBtn" type="submit" href="#" class="btn btn-info hidden" style="float:right" onclick="storeBenefitSchedule()">Agendar</a>
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
        document.getElementById('pagination').onchange = function () {
        document.getElementById('items').value = this.value;
        document.getElementById('btnFilterForm').click();
        };
    </script>
    @endsection
