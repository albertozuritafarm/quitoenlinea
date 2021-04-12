@extends('layouts.app')

@section('content')
<!--<div class="se-pre-con"></div>-->
<meta name="csrf-token" content="{{ csrf_token() }}" />
<script src="{{ assets('js/payments/index.js') }}"></script>
<link href="{{ assets('css/payments/index.css')}}" rel="stylesheet" type="text/css"/>
<div class="container" style="width: 100%">
    <div>
        <div class="col-md-12 border" id="filter" style="margin-top:10px;margin-left:0;margin-right:15px; display: none;">
            <form method="POST" action="{{asset('/payments')}}">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Identificación</label>
                            <input type="text" class="form-control" name="document" id="document" placeholder="Identificación" value="{{ session('paymentsDocument') }}">
                        </div>

                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Fecha</label>
                            <input type="date" class="form-control" name="date" id="date" placeholder="Correo" style="line-height:14px" value="{{session('paymentsDate')}}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Numero de Venta</label>
                            <input type="number" class="form-control" name="saleId" id="saleId" placeholder="Numero de Venta" value="{{session('paymentsSaleId')}}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="adviser">Forma de Pago</label>
                            <select name="payment_type" id="payment_type" class="form-control" value="">
                                <option selected="true" value="">Todos</option>
                                @foreach($paymentTypes as $pay)
                                @if(session('paymentsSalesType') == $pay->id)
                                <option selected="true" value="{{$pay->id}}">{{$pay->name}}</option>
                                @else
                                <option value="{{$pay->id}}">{{$pay->name}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="status">Tipo</label>
                            <select name="charge_type" id="charge_type" class="form-control" value="">
                                <option selected="true" value="">Todos</option>
                                @foreach($chargeTypes as $cha)
                                @if(session('paymentsChargesType') == $cha->id)
                                <option selected="true" value="{{$cha->id}}">{{$cha->name}}</option>
                                @else
                                <option value="{{$cha->id}}">{{$cha->name}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="status">Estado:</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">--Seleccione Uno--</option>
                                @foreach($status as $sta)
                                @if($sta->id == session('paymentsStatus'))
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
                <input id="btnFilterForm"  type="submit" class="btn btn-primary" value="Aplicar">
            </form>
        </div>
        <div class="col-md-12" style="margin-left: -15px">
            <h4>Listado de Cobranzas </h4>
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
            @if (session('errorNumber'))
            <div class="alert alert-success">
                <center>
                    {{ session('errorNumber') }}
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
            @include('pagination.items')
        </div>
        <div id ="tableData">
            @include('pagination.charges')
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
<!-- MODAL PAYMENT -->
<button id="modalBtnClickPayment" type="button" class="btn btn-info btn-lg hidden" data-toggle="modal" data-target="#paymentModal">Open Modal</button>
<div id="paymentModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Cobranza</h4>
            </div>
            <div id="modalBodyPayment" class="modal-body">
                <form action="/payments/store" method="POST">
                    {{ csrf_field() }}

                    <div id="formBody">
                    </div>
                </form>
            </div>
            <div class="modal-footer">

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
