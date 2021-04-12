@extends('layouts.app')

@section('content')
<!--<div class="se-pre-con"></div>-->
<meta name="csrf-token" content="{{ csrf_token() }}" />
<script src="{{ assets('js/channels/index.js') }}"></script>
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
        <div class="col-md-12 border" id="filter" style="margin-top:10px;margin-left:0;margin-right:15px; display: none;">
            <form method="POST" action="{{asset('/channel')}}">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="document">Documento</label>
                            <input type="text" class="form-control" name="id" id="id" placeholder="Id" value="{{ session('channelsId') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="document">Nombre</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Nombre" value="{{ session('channelsName') }}">
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
            <h4>Listado de Canales </h4>
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
            @if($create)
                <a type="button" href="#" class="border btnTable" type="button" onclick="productChannelSS()"><img id="filterImg" src="{{asset('/images/mas.png')}}" width="24" height="24" alt=""></a> 
            @else
            @endif
            @include('pagination.items')
        </div>
        <div id="tableData">
            @include('pagination.channels')
        </div>
    </div>
</div>
<form method="post" action="{{asset('/agency/create')}}">
    {{ csrf_field() }}
    <input type="hidden" name="channelId" id="channelId" value="">
    <button type="submit" class="hidden" id="agencyBtn"></button> 
</form>
<form method="post" action="{{asset('/channel/edit')}}">
    {{ csrf_field() }}
    <input type="hidden" name="channelEditId" id="channelEditId" value="">
    <button type="submit" class="hidden" id="channelEditBtn"></button> 
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
        <h4 class="modal-title">Resumen de Canales y Agencias</h4>
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
