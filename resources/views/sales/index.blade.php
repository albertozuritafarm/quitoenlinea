@extends('layouts.app')

@section('content')
<!--<div class="se-pre-con"></div>-->
<meta name="csrf-token" content="{{ csrf_token() }}" />
<script src="{{ assets('js/registerCustom.js') }}"></script>
<script src="{{ assets('js/sales/index.js') }}"></script>
<link href="{{ assets('css/sales/index.css')}}" rel="stylesheet" type="text/css"/>
<div class="container" style="width: 100%">
    <div>
        <div class="col-md-12 border" id="filter" style="margin-top:10px;margin-left:0;margin-right:15px;display:none">
            <form  class="col-md-12 border" method="POST" action="{{asset('/sales')}}">
                {{ csrf_field() }}
                <div class="row">
                <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Numero de Póliza</label>
                            <input type="text" class="form-control" name="policy_number" id="policy_number" placeholder="Numero de Póliza" value="{{ session('salesPolicyNumber')  }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="first_name">Cliente</label>
                            <input type="text" class="form-control" name="customer" id="customer" placeholder="Cliente" value="{{ session('salesCustomer') }}">
                        </div>

                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Identificación</label>
                            <input type="text" class="form-control" name="document" id="document" placeholder="Identificación" value="{{ session('salesDocument')  }}">
                        </div>

                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Placa</label>
                            <input type="text" class="form-control" name="plate" id="plate" placeholder="Placa" value="{{ session('salesPlate')  }}">
                        </div>

                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="adviser">Movimiento</label>
                            <select name="movement" id="movement" class="form-control" value="">
                                <option value="">Todos</option>
                                @foreach($salesMovements as $mov)
                                <option @if($mov->id == session('salesMovements')) selected="true" @else @endif value="{{$mov->id}}">{{$mov->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Fecha desde</label>
                            <input type="text" class="form-control date datepicker" name="dateFrom" id="dateFrom" data-date-format="DD-MM-YYYY" placeholder="Fecha desde" value="{{ session('salesDateFrom')  }}" style="line-height:14px">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Fecha hasta</label>
                            <input type="text" class="form-control date datepicker" name="dateUntil" id="dateUntil" data-date-format="DD-MM-YYYY" placeholder="Fecha hasta" value="{{ session('salesDateUntil')  }}" style="line-height:14px">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Numero de Venta</label>
                            <input type="text" class="form-control" name="saleId" id="saleId" placeholder="Numero de Venta" value="{{ session('salesSaleId')  }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="adviser">Asesor</label>
                            <select name="adviser" id="adviser" class="form-control" value="">
                                <option selected="true" value="">Todos</option>
                                @foreach($users as $usr)
                                @if($usr->id == session('salesAdviser'))
                                <option selected="true" value="{{$usr->id}}">{{$usr->last_name}} {{$usr->first_name}}</option>
                                @else
                                <option value="{{$usr->id}}">{{$usr->last_name}} {{$usr->first_name}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="status">Estado</label>
                            <select name="status" id="status" class="form-control" value="">
                                <option selected="true" value="">Todos</option>
                                @foreach($status as $sta)
                                @if($sta->id == session('salesStatus'))
                                <option selected="true" value="{{$sta->id}}">{{$sta->name}}</option>
                                @else
                                <option value="{{$sta->id}}">{{$sta->name}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>

                <input type="hidden" name="items" id="items" value="{{$items}}">
                <input type="button" id="btnCancel" class="btn btn-default" value="Cancelar">
                <input type="button" id="btnClearSales" class="btn btn-default" value="Limpiar">
                <input id="btnFilterForm" type="submit" class="btn btn-primary" value="Aplicar">
            </form>
        </div>
        <div class="col-md-12" style="margin-left: -15px">
            <h4>Listado de Ventas </h4>
            @if (session('Error'))
            <div class="alert alert-warning">
                <img src="{{ asset('images/iconos/warning.png')}}" alt="Girl in a jacket" style="width:40px;height:40px"> {{ session('Error') }}
            </div>
            @endif
            @if (session('Success'))
            <div class="alert alert-success">
                <img src="{{ asset('images/iconos/ok.png')}}" alt="Girl in a jacket" style="width:40px;height:40px">{{ session('Success') }}
            </div>
            @endif
            @if (session('Inactive'))
            <div class="alert alert-success" style="margin-right: -15px">
                <img src="{{ asset('images/iconos/ok.png')}}" alt="Girl in a jacket" style="width:40px;height:40px">{{ session('Inactive') }}
            </div>
            @endif
            @if (session('cancelMessage'))
            <div class="alert alert-success" style="margin-right:-20px">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <center>
                    <span class="glyphicon glyphicon-ok" id="annulmentMsgSuccess"  style="font-weight: bold;">{{ session('cancelMessage') }}</span>
                </center>
            </div>
            @endif
            @if (Session::has('message'))
            <div class="alert alert-success" style="margin-right:-20px">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <center>
                    <span class="glyphicon glyphicon-ok" id="annulmentMsgSuccess"  style="font-weight: bold;">{{ session('message') }}</span>
                </center>
            </div>
            @endif
            @if (Session::has('successMessage'))
            <div class="alert alert-success" style="margin-right:-20px">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <center>
                    <span class="glyphicon glyphicon-ok" id="annulmentMsgSuccess"  style="font-weight: bold;"> {{ session('successMessage') }}</span>
                </center>
            </div>
            @endif
            @if (Session::has('errorMessage'))
            <div class="alert alert-danger" style="margin-right:-20px">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <center>
                    <span class="glyphicon glyphicon-ok" id="annulmentMsgSuccess"  style="font-weight: bold;">{{ session('errorMessage') }}</span>
                </center>
            </div>
            @endif
            @if (Session::has('deleteMessage'))
            <div class="alert alert-success" style="margin-right:-20px">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <center>
                    <span class="glyphicon glyphicon-ok" id="annulmentMsgSuccess"  style="font-weight: bold;">{{ session('deleteMessage') }}</span>
                </center>
            </div>
            @endif
            @if (session('paymentsStore'))
            <div class="alert alert-success">
                <center>
                    {{ session('paymentsStore') }}
                </center>
            </div>
            @endif
            @if (session('inspectionCreate'))
            <div class="alert alert-success">
                <center>
                    {{ session('inspectionCreate') }}
                </center>
            </div>
            @endif

            <div id="annulmentDivSuccess" class="alert alert-success hidden" style="margin-right:-20px">
                <center>
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <span class="glyphicon glyphicon-ok" id="annulmentMsgSuccess"  style="font-weight: bold;"></span>
                </center>
            </div>
            <div id="annulmentDivError" class="alert alert-danger hidden" style="margin-right:-20px">
                <center>
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <span class="glyphicon glyphicon-remove" id="annulmentMsgError" style="font-weight: bold;">&ensp;&ensp;&ensp;</span>
                </center>
            </div>
            <button class="border btnTable" type="button" id="filterButton"><img id="filterImg" src="{{asset('/images/filter.png')}}" width="24" height="24" alt=""></button> 
            <a type="button" class="border btnTable @if(!$create) hidden @endif" href="{{asset('/sales/product/select')}}" data-toggle="tooltip" title="Registrar Venta"><img src="{{assets('/images/mas.png')}}" width="24" height="24" alt=""></a>
            <!--<a type="button" id="reloadTableBtn" class="border btnTable" href="#" data-toggle="tooltip" title="Actualizar Tabla"><img src="{{assets('/images/restore.png')}}" width="22" height="22" alt=""></a>-->
            <a type="button" id="reloadTableBtn" class="border btnTable" href="#" data-toggle="tooltip" title="Actualizar Tabla"><img src="{{assets('/images/refresh.png')}}" width="24" height="24" alt=""></a>
            <!--<a type="button" class="border btnTable @if(!$create) hidden @endif" href="{{ route('salesCreateRemote') }}" data-toggle="tooltip" title="Registrar Venta Remota"><img src="{{asset('/images/mas.png')}}" width="24" height="24" alt=""></a>-->
            <!--<a type="button" class="border btnTable @if(!$edit) hidden @endif" href="#" data-toggle="tooltip" title="Renovar venta" onclick="renewBtn()"><img src="{{asset('/images/restore.png')}}" width="24" height="24" alt="" style="opacity: 0.5;"></a>-->
            @include('pagination.items')
        </div>
        <div id="tableData">
            @include('pagination.individual')
        </div>
    </div>
</div>
<!-- MODAL VEHICULES PICTURE-->
<!-- Trigger the modal with a button -->
<button id="modalBtnClickPictures" type="button" class="btn btn-info btn-lg hidden" data-toggle="modal" data-target="#myModalVehiPictures">Open Modal</button>
<!-- Modal -->
<div id="myModalVehiPictures" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Vehiculos</h4>
            </div>
            <div id="modalBody" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
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
<!-- MODAL ACTIVATE SALES-->
<button id="modalBtnClickActivate" type="button" class="btn btn-info btn-lg hidden" data-toggle="modal" data-target="#saleActivateModal">Open Modal</button>
<div id="saleActivateModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Validar el codigo SMS</h4>
            </div>
            <div id="modalBodySaleActivate" class="modal-body">
                <form id="validateCodeForm">
                    {{ csrf_field() }}
                    <div id="resultMessage">
                    </div>
                    <span class="col-md-12 border">
                        <div id="validationCode">
                        </div>
                        <div class="form-group">
                            <label for="code">Ingrese el codigo</label>
                            <input type="text" class="form-control" name="code" id="code" placeholder="Ingrese el codigo"><br>
                            <button id="resendCodeBtn" type="submit" class="btn btn-success" style="float:right;margin-bottom: 10px" onclick="resendCode()">Reenviar Codigo</button>
                        </div>
                    </span>
                    <div>
                        <button type="button" class="btn btn-default" data-dismiss="modal" style="float:left;margin-top: 10px">Cerrar</button>
                        <button id="validateCodeBtn" type="submit" class="btn btn-info" onclick="validateCode()" style="float:right;margin-top: 10px;">Validar</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="border-top: 0 none !important;">
            </div>
        </div>
    </div>
</div>
<!-- MODAL FORMULARIO VINCULACIÓN-->
<button id="modalBtnClickValidatePayer" type="button" class="btn btn-info btn-lg hidden" data-toggle="modal" data-target="#saleValidatePayer">Open Modal</button>
<div id="saleValidatePayer" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Validar Pagador</h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer" style="border-top: 0 none !important;">
            </div>
        </div>
    </div>
</div>
<div class="hidden">
    <form action="{{asset('/sales/payments/create')}}" method="POST" target="_blank">
        {{ csrf_field() }}
        <input type="hidden" id="chargeId" name="chargeId" value="">
        <input id="formBtn" type="submit" class="btn btn-info" style="float:right" value="SI">
    </form>
</div>
<script>
    document.getElementById('pagination').onchange = function () {
        document.getElementById('items').value = this.value;
        document.getElementById('btnFilterForm').click();
    };
</script>
@endsection
