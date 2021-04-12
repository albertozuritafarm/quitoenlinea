@extends('layouts.app')

@section('content')
<!--<div class="se-pre-con"></div>-->
<meta name="csrf-token" content="{{ csrf_token() }}" />
<script src="{{ assets('js/registerCustom.js') }}"></script>
<link href="{{ assets('css/sales/index.css')}}" rel="stylesheet" type="text/css"/>
<script src="{{ assets('js/ticket/index.js') }}"></script>
<script src="{{ assets('js/ticket/create.js') }}"></script>
<style>
    .modal-footer {
    border-top: 0 none;
}
</style>
<div class="container" style="width: 100%">
    <div>
        <div class="col-md-12 hidden border" id="filter" style="margin-top:10px;margin-left:0;margin-right:15px;">
        </div>
        <div class="col-md-12" style="margin-left: -15px">
            <h4>Detalles del Ticket</h4>
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
            @if($ticket->status_id == 20)
                <a type="button" href="#" class="border btnTable no-drop" type="button" disabled="disabled"><img id="filterImg" src="{{asset('/images/mas.png')}}" width="24" height="24" alt=""></a> 
            @else
                <a type="button" href="#" class="border btnTable" type="button"  data-toggle="modal" data-target="#myModal" onclick="clearCommentForm()"><img id="filterImg" src="{{asset('/images/mas.png')}}" width="24" height="24" alt=""></a> 
            @endif
                <a class="btn btn-default registerForm" align="right" href="{{asset('')}}ticket" > Volver </a>
        </div>
        <div id="tableData">
            @include('pagination.tickets_detail')
        </div>
    </div>
</div>
<form method="post" action="{{asset('/ticket/detail')}}">
    {{ csrf_field() }}
    <button type="submit" class="hidden" id="ticketsBtn"></button> 
</form>
<!-- MODAL -->
<!-- MODAL RESUMEN-->
<!-- Trigger the modal with a button -->
<button id="modalAgencyResume" type="button" class="btn btn-info btn-lg hidden" data-toggle="modal" data-target="#myModal">Open Modal</button>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Agregue nuevo Comentario al Ticket</h4>
            </div>
            <div class="modal-body">
                <form id="ticketForm" action="{{asset('/ticket/store')}}">
                    {{ csrf_field() }}
                    <div class="col-md-12 border">
                        <input type="hidden" name="ticketsId" id="ticketsId" value="{{$ticketsId}}">
                        <input type="hidden" id="closeTicket" name="closeTicket" value="">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="description">Descripción:</label>
                                <textarea class="form-control" rows="5" id="description" name="description" placeholder="Agregue nuevo Comentario al Ticket" onkeydown="removeInputRedFocus(this.id)"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-4" style="margin-right:15px; margin-bottom: 15px; width:31% !important">
                                <div class="form-group">
                                    <label class="file-upload" for="file">Archivo:</label>
                                    <input type="file" id="file" name="file">
                                    <br>
                                    <input type="button" value="Limpiar" onclick="clearInputFile()">
                                </div> 
                            </div>
                        </div>
                    </div>
                </form>
                <div class="col-md-12 " style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                    <div class="col-md-1">
                        <a class="btn btn-default registerForm" align="right" href="#" style="margin-left: -30px;" data-dismiss="modal"> Cancelar </a>
                    </div>
                    <div class="col-md-1 col-md-offset-8">
                        <button type="submit" class="btn btn-danger registerForm" align="right" style="float:right;margin-right: -30px;padding: 5px;width:100px" onclick="submitTicketCloseComment()"> Cerrar Ticket </button>
                    </div>
                    <div class="col-md-1 col-md-offset-11">
                        <button type="submit" class="btn btn-info registerForm" align="right" style="float:right;padding: 5px;margin-right: -30px; margin-top: -35px;width:80px" onclick="submitTicketComment()"> Guardar </button>
                    </div>
                </div>
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
