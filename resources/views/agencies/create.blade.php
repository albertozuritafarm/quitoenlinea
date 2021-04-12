@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<script src="{{ assets('js/agencies/create.js') }}"></script>
<script src="{{ assets('js/registerCustom.js') }}"></script>
<style>
    .modal-footer {
        border-top: 0 none;
    }
</style>
<div class="container" style="width: 100%">
    <div>
        <div class="col-md-12" style="margin-left: -15px">
            <h4>Listado de Agencias</h4>
            @if (session('editSuccess'))
            <div class="alert alert-success">
                <center>
                    {{ session('editSuccess') }}
                </center>
            </div>
            @endif
            <a type="button" href="{{asset('/channel')}}" class="btn btn-default registerForm" >Volver</a> 
            @include('pagination.items')
        </div>
        <div id="tableData">
            @include('pagination.agencies')
        </div>
    </div>
</div>
<form method="post" action="{{asset('/agency/create')}}">
    {{ csrf_field() }}
    <input type="hidden" id="items" name="items" value="{{$items}}">
    <input type="hidden" id="channelId" name="channelId" value="{{$channelId}}">
    <button type="submit" class="hidden" id="btnFilterForm"></button>
</form>
<form method="post" action="{{asset('/agency/edit')}}">
    {{ csrf_field() }}
    <input type="hidden" name="agencyEditId" id="agencyEditId" value="">
    <button type="submit" class="hidden" id="agencyEditBtn"></button> 
</form>
<!-- Modal -->
<div id="addIndividual" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Agregar una Agencia</h4>
            </div>
            <div class="modal-body">
                <div id="alertIndividual" class="alert alert-danger hidden">
                </div>
                <div id="firstStep">
                    <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;">
                        <div class="wizard_activo registerForm titleDivBorderTop">
                            <span class="titleLink">Datos de la Agencia</span>
                            <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                        </div>
                        {{ csrf_field() }}
                        <div class="col-md-6" style="margin-top:25px;">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="name">Nombre:</label>
                                <input type="text" class="form-control" name="name1" id="name1" placeholder="Nombre" value="" tabindex="1" required>
                                <p id="nameError1" style="color:red;font-weight: bold"></p>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="province">Provincia:</label>
                                <select class="form-control" id="province1" name="province1" tabindex="3" required>
                                    <option value="">-- Escoja Una --</option>
                                    @foreach($provinces as $prov)
                                    <option value="{{$prov->id}}">{{$prov->name}}</option>
                                    @endforeach
                                </select>
                                <p id="provinceError1" style="color:red;font-weight: bold"></p>
                            </div> 
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="phone">Teléfono Fijo:</label>
                                <input type="text" class="form-control" name="phone1" id="phone1" placeholder="Teléfono Fijo" value="" tabindex="5" required>
                                <p id="phoneError1" style="color:red;font-weight: bold"></p>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="mobile_phone">Contacto:</label>
                                <input type="text" class="form-control" name="contact1" id="contact1" placeholder="Contacto" value="" tabindex="6" required>
                                <p id="contactError1" style="color:red;font-weight: bold"></p>
                            </div>
                        </div>
                        <div class="col-md-6" style="margin-top:25px;"> 
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="address">Dirección:</label>
                                <input type="text" class="form-control" name="address1" id="address1" placeholder="Dirección" value="" tabindex="2" required>
                                <p id="addressError1" style="color:red;font-weight: bold"></p>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="city">Ciudad:</label>
                                <select class="form-control" id="city1" name="city1" tabindex="4" required>
                                    <option value="">-- Escoja Una --</option>
                                </select>
                                <p id="cityError1" style="color:red;font-weight: bold"></p>
                            </div> 
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="mobile_phone">Teléfono Celular:</label>
                                <input type="text" class="form-control" name="mobile_phone1" id="mobile_phone1" placeholder="Teléfono Celular" value="" tabindex="8" required>
                                <p id="mobilePhoneError1" style="color:red;font-weight: bold"></p>
                            </div>
                            <div class="form-group">
                                <label class="registerForm" style="list-style-type:disc;" for="zip">Código Postal:</label>
                                <input type="text" class="form-control" name="zip1" id="zip1" placeholder="Código Postal" value="" tabindex="7" required>
                                <p id="zipError1" style="color:red;font-weight: bold"></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" style="padding-bottom:15px">
                        <div class="col-md-1">
                            <a class="btn btn-default registerForm" align="right" href="#" data-dismiss="modal" style="margin-left: -30px;"> Cancelar </a>
                        </div>
                        <div class="col-md-1 col-md-offset-10">
                            <button id="btnAddInvididual" type="button" class="btn btn-info registerForm" align="right" style="float:right;margin-right: -30px;padding: 5px;width:80px" onclick="addIndividual(1)"> Guardar </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div id="addExcel" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Agregar Varias Agencias</h4>
            </div>
            <div class="modal-body">
                <div id="alertExcel" class="alert alert-danger hidden">
                </div>
                <form id="uploadForm" action="#"  method="POST" enctype="multipart/form-data" id="uploadForm"  onsubmit="validateUploadExcel()">
                    {{ csrf_field() }}
                    <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;">
                        <div class="wizard_activo registerForm titleDivBorderTop">
                            <span class="titleLink">Seleccione un Archivo</span>
                            <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                        </div>
                        <div class="col-md-12" style="margin-top:25px;">
                            <div class="form-group">
                                <label class="file-upload" for="file">Archivo:</label>
                                <input id="file" type="file" name="file">
                                <input type="hidden" id="channelId" name="channelId" value="{{$channelId}}">
                                <br>
                                Descargue <a href="{{asset('/agency/download')}}" target="_blank">aqui</a> el formato.
                            </div> 
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-default registerForm" data-dismiss="modal"  onsubmit="validateUploadExcel()" style="float:left;margin-left: -15px">Cerrar</button>
                        <button type="submit" class="btn btn-info registerForm" onsubmit="validateUploadExcel()" style="float:right;margin-right: -15px">Guardar</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<!-- MODAL EDIT -->
<!-- Trigger the modal with a button -->
<button id="editAgencyBtn" type="button" class="btn btn-info btn-lg hidden" data-toggle="modal" data-target="#myModalEditAgency">Open Modal</button>

<!-- Modal -->
<div id="myModalEditAgency" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Editar Agencia</h4>
      </div>
      <div id="editAgencyBody" class="modal-body">
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
