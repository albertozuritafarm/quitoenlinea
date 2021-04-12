@extends('layouts.app')

@section('content')
<!--<div class="se-pre-con"></div>-->
<meta name="csrf-token" content="{{ csrf_token() }}" />
<script src="{{ assets('js/inspection/index.js') }}"></script>
<!--<link href="{{ asset('css/payments/index.css')}}" rel="stylesheet" type="text/css"/>-->
<style>
    .modal-footer {
        border-top: 0 none;
    }
</style>
<div class="container" style="width: 100%">
    <div>
        <div class="col-md-12 border" id="filter" style="margin-top:10px;margin-left:0;margin-right:15px; display: none;">
            <form method="POST" action="{{asset('/inspection')}}">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="salesId">Venta</label>
                            <input type="text" class="form-control" name="salesId" id="salesId" placeholder="Venta" value="{{ session('inspectionSalesId') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="status">Estado</label>
                            <select name="status" id="status" class="form-control" value="">
                                <option selected="true" value="">Todos</option>
                                @foreach($status as $sta)
                                <option @if($sta->id == session('inspectionStatus')) selected="true" @else @endif value="{{$sta->id}}">{{$sta->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="items" name="items" value="{{$items}}">
                <input type="button" id="btnCancel" class="btn btn-default" value="Cancelar">
                <input type="button" id="btnClearBenefits" class="btn btn-default" value="Limpiar">
                <input id="btnFilterForm" type="submit" class="btn btn-primary" value="Aplicar" onclick="return val()">
            </form>
        </div>
        <div class="col-md-12" style="margin-left: -15px">
            <h4>Listado de Inspeccion</h4>
            @if (session('inspectionUpload'))
            <div class="alert alert-success">
                <center>
                    {{ session('inspectionUpload') }}
                </center>
            </div>
            @endif
            <button class="border btnTable" type="button" id="filterButton"><img id="filterImg" src="{{asset('/images/filter.png')}}" width="24" height="24" alt=""></button> 
            @include('pagination.items')
        </div>
        <div id="tableData">
            @include('pagination.inspection')
        </div>
    </div>
</div>
<form method="post" action="{{asset('/customer/edit')}}">
    {{ csrf_field() }}
    <input type="hidden" name="customerId" id="customerId" value="">
    <button type="submit" class="hidden" id="customerBtn"></button> 
</form>
<!-- MODAL -->
<!-- MODAL RESUMEN-->
<!-- Trigger the modal with a button -->
<button id="modalAgencyResume" type="button" class="btn btn-info btn-lg hidden" data-toggle="modal" data-target="#myModal">Open Modal</button>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Resumen de Clientes y Ventas</h4>
      </div>
      <div id="modalResumeBody" class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div>
<!-- VEHICLE-->
<!-- Trigger the modal with a button -->
<button id="modalVehiBtn" type="button" class="btn btn-info btn-lg hidden" data-toggle="modal" data-target="#myModalVehi">Open Modal</button>
<!-- Modal -->
<div id="myModalVehi" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Vehículo</h4>
            </div>
            <br>
            <div id="confirmModalError" class="alert alert-danger hidden">
                <center>
                    Solo puede subir un archivo PDF y debe pesar menos de 2mb.
                </center>
            </div>
            <div id="modalVehiBody">
            </div>
        </div>
    </div>
</div>
<!-- CONFIRMATION MODAL-->
<!-- Trigger the modal with a button -->
<button id="modalConfirmBtn" type="button" class="btn btn-info btn-lg hidden" data-toggle="modal" data-target="#myModalConfirm">Open Modal</button>
<!-- Modal -->
<div id="myModalConfirm" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Subir Archivo de Inspección</h4>
            </div>
            <br>
            <div id="confirmModalError" class="alert alert-danger hidden">
                <center>
                    Solo puede subir un archivo PDF y debe pesar menos de 2mb.
                </center>
            </div>
            <div class="modal-body">
                <!--file input example -->
                <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;">
                    <div class="wizard_activo registerForm titleDivBorderTop">
                        <span class="titleLink">Datos del Canal</span>
                        <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                    </div>
                    <div class="col-md-12" style="padding-top:15px;padding-bottom: 15px;">
                        <form method="POST" id="formConfirm">
                            {{ csrf_field() }}
                            <span class="control-fileupload">
                                <label for="file">Seleccione un archivo :</label>
                                <input type="file" id="fileConfirm" name="fileConfirm" onchange="Filevalidation()"> 
                            </span>
                            <input id="confirmId" name="confirmId" type="hidden" value="">
                        </form>
                    </div>
                </div>
                <!--./file input example -->
            </div>
            <div class="modal-footer">
                <button id="modalCancelCloseBtn" type="button" class="btn btn-default registerForm" data-dismiss="modal" style="float:left">Cerrar</button>
                <button type="submit" class="btn btn-info registerForm" style="float:right" onclick="modalConfirmBtn()">Confirmar</button>
            </div>
        </div>

    </div>
</div>
<!-- STATUS MODAL-->
<!-- Trigger the modal with a button -->
<button id="modalStatusBtn" type="button" class="btn btn-info btn-lg hidden" data-toggle="modal" data-target="#myModalStatus">Open Modal</button>
<!-- Modal -->
<div id="myModalStatus" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Confirmar Inspección</h4>
            </div>
            <br>
            <div class="modal-body">
                <!--file input example -->
                <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;">
                    <div class="wizard_activo registerForm titleDivBorderTop">
                        <span class="titleLink">Datos del Canal</span>
                        <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                    </div>
                    <div class="col-md-12" style="padding-top:15px;padding-bottom: 15px;">
                        <form method="POST" id="formConfirmStatus">
                            {{ csrf_field() }}
                            <input id="statusId" name="statusId" type="hidden" value="">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <span class="control-fileupload">
                                        <label for="date">Fecha de Inspección:</label>
                                        <input class="form-control" type="date" id="date" name="date" style="line-height:14px"> 
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="list-style-type:disc;" for="statusModal">Estado</label>
                                    <select name="statusModal" id="statusModal" class="form-control">
                                        <option selected="true" value="">--Seleccione Uno--</option>
                                        @foreach($status as $sta)
                                        @if($sta->id != 22)
                                        <option value="{{$sta->id}}">{{$sta->name}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!--./file input example -->
            </div>
            <div class="modal-footer">
                <button id="modalCancelCloseBtn" type="button" class="btn btn-default registerForm" data-dismiss="modal" style="float:left">Cerrar</button>
                <button type="submit" class="btn btn-info registerForm" style="float:right" onclick="modalStatusBtn()">Confirmar</button>
            </div>
        </div>

    </div>
</div>

<!-- MODAL SALES RESUME -->
<button id="modalBtnClickResume" type="button" class="btn btn-info btn-lg hidden" data-toggle="modal" data-target="#saleModal">Open Modal</button>
<div id="saleModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Resumen de la Venta</h4>
            </div>
            <div id="modalBodySaleResume" class="modal-body">

            </div>
            <div class="modal-footer">
                <!--<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>-->
            </div>
        </div>

    </div>
</div>
<script>
document.getElementById('pagination').onchange = function () {
        document.getElementById('items').value = this.value;
        document.getElementById('btnFilterForm').click();
        };
</script>
@endsection
