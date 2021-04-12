@extends('layouts.app')

@section('content')
<script src="{{ assets('js/registerCustom.js') }}"></script>
<script src="{{ assets('js/user/create.js') }}"></script>
<style>
    .form-group{
        margin-top:25px !important;
        margin-bottom: 25px !important;
    }

</style>
<div class="container-fluid" style="margin-top:15px; font-size:14px !important">
    <!--<div class="row justify-content-center border" style="margin-left:20%;">-->
    <div class="col-md-8 col-md-offset-2 border" style="padding: 15px">
        <div class="row">
            <div class="col-xs-12 registerForm" style="margin:12px;">
                <center>
                    <h4 style="font-weight:bold">Actualizar Datos del Usuario</h4>
                    <!--<h5>Datos del Clien</h5>-->
                </center>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-4 wizard_inicial" style="padding-left:0px !important"><div class="wizard_inactivo"></div></div>
            <div class="col-xs-12 col-sm-4 wizard_medio"><div class="wizard_activo registerForm">Usuario</div></div>
            <div class="col-xs-12 col-sm-4 wizard_final" style="padding-right: 0px !important"><div class="wizard_inactivo"></div></div>
        </div>
        <br>
        <div class="col-md-12">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
            <div class="col-md-12">
                <form method="POST" action="{{ asset('/user/update') }}">
                    <div class="col-md-12">
                        <a class="btn btn-default registerForm" align="left" href="{{ asset('/user') }}" style="margin-left: -15px"> Cancelar </a>
                        <input type="submit" style="float:right;margin-right: -15px;padding: 5px" class="btn btn-info registerForm" align="right" value="Actualizar">
                    </div>
                    <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;">
                        <div class="wizard_activo registerForm titleDivBorderTop">
                            <span class="titleLink">Datos del Usuario</span>
                            <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                        </div>
                        {{ csrf_field() }}
                        {{ method_field('PATCH') }}
                        <input name="id" type="hidden" value="{{ $user[0]->id }}">
                        <div class="col-md-6">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="first_name"> Nombre(s)</label>
                                <input type="text" class="form-control registerForm" name="first_name" id="first_name" tabindex="1" placeholder="Nombre" value="{{ $user[0]->first_name }}" required>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document_id"> Tipo Documento</label>
                                <select name="document_id" id="document_id" tabindex="3" class="form-control registerForm" value="{{old('document_id')}}" required>
                                    <option selected="true" disabled="disabled">--Escoja Una---</option>
                                    @foreach($documents as $document)
                                    @if($user[0]->document_id == $document->id)         
                                    <option selected="true" value="{{$document->id}}">{{$document->name}}</option>
                                    @else
                                    <option value="{{$document->id}}">{{$document->name}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="agency"> Tipo de Usuario:</label>
                                <select name="typeSucre" id="typeSucre" class="form-control registerForm" required tabindex="13" onchange="typeSucreChange(this.value)">
                                    <option selected="true" disabled="disabled" value="">--Escoja Una---</option>
                                    @foreach($typeSucre as $sucre)
                                    <option @if($sucre->id == $user[0]->type_user_sucre_id) selected="true" @else @endif value="{{$sucre->id}}">{{$sucre->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="rol"> Rol</label>
                                <select name="rol" id="rol" class="form-control registerForm" value="{{old('rol')}}" required tabindex="9">
                                    <option selected="true" disabled="disabled">--Escoja Una---</option>
                                    @foreach($rols as $rol)
                                    @if($user[0]->role_id == $rol->id)         
                                    <option selected="true"value="{{$rol->id}}">{{$rol->name}}</option>
                                    @else
                                    <option value="{{$rol->id}}">{{$rol->name}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="agency"> Canal</label>
                                <select name="channel" id="channel" class="form-control registerForm" required tabindex="11">
                                    <option selected="true" disabled="disabled">--Escoja Una---</option>
                                    @foreach($channels as $channel)
                                    @if($channel->id == $user[0]->channel)
                                    <option selected="true" value="{{$channel->id}}">{{$channel->canalnegodes}}</option>
                                    @else
                                    <option value="{{$channel->id}}">{{$channel->canalnegodes}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="last_name"> Apellido(s)</label>
                                <input type="text" class="form-control registerForm" name="last_name" id="last_name" tabindex="2" placeholder="Apellido" value="{{ $user[0]->last_name }}" required>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Cédula</label>
                                <input type="text" class="form-control registerForm" name="document" id="document" tabindex="4" placeholder="Cédula" value="{{ $user[0]->document }}" required>
                                <p id="documentError" style="color:red;font-weight: bold"></p>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="tipo"> Tipo</label>
                                <select name="tipo" id="tipo" class="form-control registerForm" required tabindex="10">
                                    <option selected="true" disabled="disabled">--Escoja Una---</option>
                                    @foreach($types as $type)
                                    @if($user[0]->type_id == $type->id)         
                                    <option selected="true" value="{{$type->id}}">{{$type->name}}</option>
                                    @else
                                    <option value="{{$type->id}}">{{$type->name}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="correo"> Correo</label>
                                <input type="email" class="form-control registerForm" name="email" id="email" tabindex="8" placeholder="Correo" value="{{ $user[0]->email }}" required>
                                <p id="emailError" style="color:red;font-weight: bold"></p>  
                            <!--<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>-->
                                @if($errors->any())
                                <span style="color:red;font-weight:bold">{{$errors->first()}}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="agency"> Agencia de Canal</label>
                                <select name="agency" id="agency" class="form-control registerForm" required tabindex="12">
                                    <option selected="true" disabled="disabled">--Escoja Una---</option>
                                    @foreach($agencies as $agency)
                                    @if($user[0]->agency == $agency->id)
                                    <option selected="true" value="{{$agency->id}}">{{$agency->puntodeventades}}</option>
                                    @else
                                    <option value="{{$agency->id}}">{{$agency->puntodeventades}}</option>
                                    @endif

                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" style="padding-bottom:15px">
                        <a class="btn btn-default registerForm" align="left" href="{{ asset('/user') }}" style="margin-left: -15px"> Cancelar </a>
                        <input type="submit" style="float:right;margin-right: -15px;padding: 5px" class="btn btn-info registerForm" align="right" value="Actualizar">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
