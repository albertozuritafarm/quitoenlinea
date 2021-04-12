@extends('layouts.app')

@section('content')
<!--<div class="se-pre-con"></div>-->
<meta name="csrf-token" content="{{ csrf_token() }}" />
<script src="{{ assets('js/registerCustom.js') }}"></script>
<script src="{{ assets('js/sales/cancel.js') }}"></script>
<div class="container" style="width: 100%">
    <div>
        <div class="col-md-10 col-md-offset-1 border" style="margin-top:10px">
            <div class="row">
                <div class="col-xs-12 registerForm" style="margin:12px;">
                    <center>
                        <h4 style="font-weight:bold">Cancelación de Vehiculos</h4>
                    </center>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="padding-left:5px !important;padding-right: 5px">
                    <div class="col-md-4 wizard_inactivo registerForm">
                    </div>
                    <div class="col-md-4 wizard_activo registerForm">
                        Vehiculos
                    </div>
                    <div class="col-md-4 wizard_inactivo registerForm">
                    </div>
                </div>
            </div>
            <br><br>
            <form method="POST" action="{{ asset('/sales/vehicles/cancel') }}">
                {{ csrf_field() }}
                <input id="saleId" name="saleId" type="hidden" class="form-control" value="{{$sale}}">
                <div id="tableDiv" class="col-md-12" >
                    <div id="cancelDiv" class="alert alert-success hidden" style="margin-right:-20px">
                        <center>
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <span class="glyphicon glyphicon-ok" style="font-weight: bold;">&ensp;Cancelación Existosa</span>
                        </center>
                    </div>
                    @if (session('errorMessage'))
                        <div class="alert alert-danger" style="margin-right: -15px">
                            <center><strong>{{ session('errorMessage') }}</strong></center>
                        </div>
                    @endif
                    <table id="tableUsers" class="table table-striped row-border table-responsive hover stripe" style="margin-left:-14px;">
                        <thead>
                            <tr>
                                <th align="center">Placa</th>
                                <th align="center">Marca</th>
                                <th align="center">Modelo</th>
                                <th align="center">Año</th>
                                <th align="center">Color</th>
                                <th align="center">Observaciones</th>
                                <th align="center">Cancelar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vehicles as $vehicle)
                            <tr>
                                <td align="center">{{$vehicle->plate}}</td>
                                <td align="center">{{$vehicle->brand}}</td>
                                <td align="center">{{$vehicle->model}}</td>
                                <td align="center">{{$vehicle->year}}</td>
                                <td align="center">{{$vehicle->color}}</td>
                                @if($vehicle->sche)
                                    <td align="center">Ya posee un Agendamiento Confirmado</td>
                                    <td align="center"><input type='checkbox' disabled="disabled"></td>
                                @else
                                    <td align="center"></td>
                                    <td align="center" id="vehicle{{$vehicle->id}}"><input type="checkbox" name="vehicleId[]" value="{{$vehicle->id}}"></td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <br>
                <br>
                <div class="col-md-6 col-md-offset-3">
                    <div class="form-group">
                        <label for="sel1">Motivo de Cancelación:</label>
                        <select class="form-control" id="cancelMotive" name="cancelMotive" required>
                            <option value="">--Escoja Una--</option>
                            @foreach($cancelMotives as $cancel)
                                <option value="{{$cancel->id}}">{{$cancel->name}}</option>
                            @endforeach
                        </select>
                    </div> 
                </div>

                <div class="col-md-12" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                    <a class="btn btn-default registerForm" align="left" href="{{ asset('/sales') }}" style="margin-left: -15px;width:75px"> Atras </a>
                    <input id="cancelBtn" type="submit" style="float:right;margin-right: -15px;padding: 5px;width:75px" class="btn btn-info registerForm" align="right" value="Cancelar">

                </div>
            </form>
        </div>
    </div>
</div>

@endsection
