@extends('layouts.app')

@section('content')
<!--<div class="se-pre-con"></div>-->
<meta name="csrf-token" content="{{ csrf_token() }}" />
<script src="{{ assets('js/account/index.js') }}"></script>
<link href="{{ assets('css/payments/index.css')}}" rel="stylesheet" type="text/css"/>
<div class="container" style="width: 100%">
    <div>
        <div class="col-md-12 hidden border" id="filter" style="margin-top:10px;margin-left:0;margin-right:15px;">
            <form method="POST" action="{{asset('/account')}}">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name"># Solicitud</label>
                            <input type="text" class="form-control" name="banId" id="banId" placeholder="# Solicitud" value="{{ $data['banId'] }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Fecha Inicio</label>
                            <input type="date" class="form-control" name="beginDate" id="beginDate" placeholder="" style="line-height:14px" value="{{$data['beginDate']}}" onchange="beginDateChange()">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Fecha Fin</label>
                            <input type="date" class="form-control" name="endDate" id="endDate" placeholder="" style="line-height:14px" value="{{$data['endDate']}}" onchange="endDateChange()">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Identificacion</label>
                            <input type="text" class="form-control" name="document" id="document" placeholder="Identificación" style="line-height:14px" value="{{$data['document']}}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Estado</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">--Seleccione--</option>
                                @foreach($status as $sta)
                                @if($data['status'] == $sta->id)
                                <option selected='true' value="{{$sta->id}}">{{$sta->name}}</option>
                                @else
                                <option value="{{$sta->id}}">{{$sta->name}}</option>
                                @endif
                                @endforeach
                            </select> 
                        </div>
                    </div>
                </div>
                <input type="button" id="btnCancel" class="btn btn-default" value="Cancelar">
                <input type="button" id="btnAccountsClear" class="btn btn-default" value="Limpiar">
                <input type="submit" class="btn btn-primary" value="Aplicar" onclick="val()">
            </form>
        </div>
        <div class="col-md-12" style="margin-left: -15px">
            <h4>Solicitudes de Apertura de Cuentas</h4>
            @if (session('successAccount'))
            <div class="alert alert-success">
                <center>
                    {{ session('successAccount') }}
                </center>
            </div>
            @endif
            @if (session('warningAccount'))
            <div class="alert alert-warning">
                <center>
                    {{ session('warningAccount') }}
                </center>
            </div>
            @endif
            <button class="border btnTable" type="button" id="filterButton"><img id="filterImg" src="{{asset('/images/filter.png')}}" width="24" height="24" alt=""></button> 
            <a type="button" class="border btnTable" href="{{asset('/account/create')}}" data-toggle="tooltip" title="Nueva Solicitud"><img src="{{asset('/images/mas.png')}}" width="24" height="24" alt=""></a>
            <a type="button" class="border btnTable" href="#" data-toggle="tooltip" title="Eliminar Solicitu(es)" id="deleteAccountsBtn"><img src="{{asset('/images/menos.png')}}" width="24" height="24" alt=""></a>
        </div>
        <div class="col-md-12 border" style="margin-top:10px">
            <div id="tableDiv" class="col-md-12" >
                <table id="tableUsers" class="table table-striped row-border table-responsive hover stripe" style="margin-left:-14px;">
                    <thead>
                        <tr>
                            <th align="center"><center><span id="checkAll" style="margin-left:10px"> Todos</span></center></th>
                    <th align="center">Solicitud</th>
                    <th align="center">Identificacion</th>
                    <th align="center">Cliente</th>
                    <th align="center">Estado</th>
                    <th align="center">Fecha</th>
                    <th align="center">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($accounts as $acc)
                        <tr>
                            <td align="center"><input type="checkbox" name="accountId" value="{{$acc->id}}"></td>
                            <td align="center">{{$acc->id}}</td>
                            <td align="center">{{$acc->document_number}}({{$acc->document}})</td>
                            <td align="center">{{$acc->customer}}</td>
                            <td align="center">{{$acc->status}}</td>
                            <td align="center">{{$acc->date}}</td>
                            <td align="center">
                                @if($acc->status == 'Pendiente')
                                <a href="#" onclick="accountApprove({{$acc->id}})"><img src="{{asset('/images/Aprobar.png')}}"></img></a>
                                <a href="#" onclick="accountDeny({{$acc->id}})"><img src="{{asset('/images/Rechazar.png')}}"></img></a>
                                @else
                                @endif
                                @if($acc->status == 'Falta Activación')
                                <a id="validateCodeOpenModal" href="#" data-toggle="tooltip" title="Validar Codigo SMS" onclick="modalCode({{$acc->id}})">
                                        <span class="glyphicon glyphicon-envelope" style="color:#000;font-size:15px;margin-left: 5px">&ensp;</span>
                                    </a>
                                @else
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<form method="post" action="{{route('accountApprove')}}" id="formAprrove" name="formAprrove">
    {{ csrf_field() }}
    <input type="hidden" name="id" id="formAprroveId" value="">
    <button type="submit" id="formAprroveBtn" class="btn btn-primary hidden"></button>
</form>
<form method="post" action="{{route('accountDeny')}}" id="formAprrove" name="formAprrove">
    {{ csrf_field() }}
    <input type="hidden" name="id" id="formDenyId" value="">
    <button type="submit" id="formDenyBtn" class="btn btn-primary hidden"></button>
</form>
<!-- Trigger the modal with a button -->
<button id="modalCodeBtn" type="button" class="btn btn-info btn-lg hidden" data-toggle="modal" data-target="#myModal">Open Modal</button>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Validación de Solicitud</h4>
            </div>
            <div class="modal-body">
                <span class="">
                    <div id="resultMessage" class="">
                    </div>
                    <div id="validationCode">
                        <input type="hidden" name="accountId" id="accountId" value="">
                    </div>
                    <div class="form-group">
                        <label for="code">Ingrese el codigo</label>
                        <input type="text" class="form-control" name="code" id="code" placeholder="Ingrese el codigo"><br>
                        <button id="resendCodeBtn" type="submit" class="btn btn-success" style="float:right;margin-bottom: 10px" onclick="resendCode()">Reenviar Codigo</button>
                    </div>
                </span>
            </div>
            <div class="modal-footer">
                <input type="button" style="float:left;" class="btn btn-default registerForm" align="right" value="Cancelar" data-dismiss="modal">
                <input id="fourthStepBtnNext" type="button" style="float:right;" class="btn btn-info registerForm" align="right" value="Validar" onclick="validateCode()">
            </div>
        </div>

    </div>
</div>
@endsection