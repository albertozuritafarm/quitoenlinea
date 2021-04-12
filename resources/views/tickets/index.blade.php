@extends('layouts.app')

@section('content')
<!--<div class="se-pre-con"></div>-->
<meta name="csrf-token" content="{{ csrf_token() }}" />
<script src="{{ assets('js/ticket/index.js') }}"></script>
<div class="container" style="width: 100%">
    <div>
        <div class="col-md-12 hidden border" id="filter" style="margin-top:10px;margin-left:0;margin-right:15px;">
            <form method="POST" action="{{asset('/ticket')}}">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="number">Numero</label>
                            <input type="text" class="form-control" name="number" id="number" placeholder="Numero" value="{{ session('ticketsNumber') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="firstName">Nombre</label>
                            <input type="text" class="form-control" name="firstName" id="firstName" placeholder="Nombre Usuario" value="{{ session('ticketsFirstName') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="lastName">Apellido</label>
                            <input type="text" class="form-control" name="lastName" id="lastName" placeholder="Apellido Usuario" value="{{ session('ticketsLastName') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="status">Estado</label>
                            <select name="status" id="status" class="form-control" value="">
                                <option selected="true" value="">Todos</option>
                                @foreach($status as $sta)
                                    <option @if($sta->id == session('ticketsStatus')) @else @endif value="{{$sta->id}}">{{$sta->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="beginData">Fecha Inicio</label>
                            <input type="date" class="form-control" name="beginDate" id="beginDate" style="line-height:14px"  placeholder="Date" value="{{ session('ticketsBeginDate') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="endDate">Fecha Fin</label>
                            <input type="date" class="form-control" name="endDate" id="endDate" style="line-height:14px"  placeholder="date" onchange="endDateChange()"  value="{{ session('ticketsEndDate') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="menu">Modulo</label>
                            <select name="menu" id="menu" class="form-control" value="">
                                <option value="">Todos</option>
                                @foreach($menus as $men)
                                    <option @if($men->id == session('ticketsMenu')) selected="true" @else @endif value="{{$men->id}}">{{$men->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="status">Tipo Ticket</label>
                            <select name="ticketType" id="ticketType" class="form-control" value="">
                                <option value="">Todos</option>
                                @foreach($ticketType as $typ)
                                    <option @if($typ->id == session('ticketsTicketType')) selected="true" @else @endif value="{{$typ->id}}">{{$typ->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="items" name="items" value="{{$items}}">
                <input type="button" id="btnCancel" class="btn btn-default" value="Cancelar">
                <input type="button" id="btnClearTickets" class="btn btn-default" value="Limpiar">
                <input id="btnFilterForm" type="submit" class="btn btn-primary" value="Aplicar" onclick="return val()">
            </form>
        </div>
        <div class="col-md-12" style="margin-left: -15px">
            <h4>Listado de Tickets</h4>
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
            <button class="border btnTable" type="button" id="filterButton"><img id="filterImg" src="{{asset('/images/filter.png')}}" width="24" height="24" alt=""></button> 

                <a type="button" href="{{asset('/ticket/create')}}" class="border btnTable" type="button"><img id="filterImg" src="{{asset('/images/mas.png')}}" width="24" height="24" alt=""></a> 

            @include('pagination.items')
        </div>
        <div id="tableData">
            @include('pagination.tickets')
        </div>
    </div>
</div>
<form method="post" action="{{asset('/ticket/detail')}}">
    {{ csrf_field() }}
    <input type="hidden" name="ticketsId" id="ticketsId" value="">
    <button type="submit" class="hidden" id="ticketsBtn"></button> 
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
        <h4 class="modal-title">Resumen de Proveedores y Sucursales</h4>
      </div>
      <div id="modalResumeBody" class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
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
