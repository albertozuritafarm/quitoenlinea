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
            <a type="button" href="#" class="border btnTable" type="button" data-toggle="modal" data-target="#addIndividual"  onclick="clearAddInvididual()"><img id="filterImg" src="{{asset('/images/mas.png')}}" width="24" height="24" alt=""></a> 
            <a type="button" href="#" class="border btnTable" type="button" data-toggle="modal" data-target="#addExcel" onclick="clearAddExcel()"><img id="filterImg" src="{{asset('/images/xls.png')}}" width="24" height="24" alt=""></a> 
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
                    <div class="col-md-10 col-md-offset-1 border" style="margin-top:15px">
                        {{ csrf_field() }}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label style="list-style-type:disc;" for="address">Dirección:</label>
                                <input type="text" class="form-control" name="address" id="address" placeholder="Dirección" value="" tabindex="1" required>
                                <p id="addressError" style="color:red;font-weight: bold"></p>
                            </div>
                            <div class="form-group">
                                <label for="province">Provincia:</label>
                                <select class="form-control" id="province" name="province" tabindex="3" required>
                                    <option value="">-- Escoja Una --</option>
                                    @foreach($provinces as $prov)
                                    <option value="{{$prov->id}}">{{$prov->name}}</option>
                                    @endforeach
                                </select>
                                <p id="provinceError" style="color:red;font-weight: bold"></p>
                            </div> 
                            <div class="form-group">
                                <label style="list-style-type:disc;" for="phone">Teléfono Fijo:</label>
                                <input type="text" class="form-control" name="phone" id="phone" placeholder="Teléfono Fijo" value="" tabindex="5" required>
                                <p id="phoneError" style="color:red;font-weight: bold"></p>
                            </div>
                            <div class="form-group">
                                <label style="list-style-type:disc;" for="zip">Codigo Postal:</label>
                                <input type="text" class="form-control" name="zip" id="zip" placeholder="Codigo Postal" value="" tabindex="7" required>
                                <p id="zipError" style="color:red;font-weight: bold"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label style="list-style-type:disc;" for="name">Nombre:</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Nombre" value=""  maxlength="13" tabindex="2" required>
                                <p id="nameError" style="color:red;font-weight: bold"></p>
                            </div> 
                            <div class="form-group">
                                <label for="city">Ciudad:</label>
                                <select class="form-control" id="city" name="city" tabindex="4" required>
                                    <option value="">-- Escoja Una --</option>
                                </select>
                                <p id="cityError" style="color:red;font-weight: bold"></p>
                            </div> 
                            <div class="form-group">
                                <label style="list-style-type:disc;" for="contact">Contacto:</label>
                                <input type="text" class="form-control" name="contact" id="contact" placeholder="Contacto" value="" tabindex="6" required>
                                <p id="contactError" style="color:red;font-weight: bold"></p>
                            </div>
                            <div class="form-group">
                                <label style="list-style-type:disc;" for="mobile_phone">Teléfono Celular:</label>
                                <input type="text" class="form-control" name="mobile_phone" id="mobile_phone" placeholder="Teléfono Celular" value="" tabindex="8" required>
                                <p id="mobilePhoneError" style="color:red;font-weight: bold"></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-10 col-md-offset-1" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                        <div class="col-md-1">
                            <a class="btn btn-default registerForm" align="right" href="#" data-dismiss="modal" style="margin-left: -30px;"> Cancelar </a>
                        </div>
                        <div class="col-md-1 col-md-offset-10">
                            <button id="btnAddInvididual" type="button" class="btn btn-info registerForm" align="right" style="float:right;margin-right: -30px;padding: 5px;width:80px" onclick="addIndividual()"> Guardar </button>
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
                <h4 class="modal-title">Modal Header</h4>
            </div>
            <div class="modal-body">
                <div id="alertExcel" class="alert alert-danger hidden">
                </div>
                <form id="uploadForm" action="#"  method="POST" enctype="multipart/form-data" id="uploadForm"  onsubmit="validateUploadExcel()">
                    {{ csrf_field() }}
                    <div class="col-md-12 border">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="file-upload" for="file">Archivo:</label>
                                <input id="file" type="file" name="file">
                                <input type="hidden" id="channelId" name="channelId" value="{{$channelId}}">
                                <br>
                                Descargue <a href="{{asset('/agency/download')}}" target="_blank">aqui</a> el formato.
                            </div> 
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-top:15px;">
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
<script>
    document.getElementById('pagination').onchange = function () {
        document.getElementById('items').value = this.value;
        document.getElementById('btnFilterForm').click();
    };
</script>
@endsection
