@extends('layouts.app')

@section('content')
<script src="{{ assets('js/registerCustom.js') }}"></script>
<style>
    /*    .owners{
        width:500px;
        height:300px;
        border:0px solid #ff9d2a;
        margin:auto;
        float:left;

    }*/
    span.own1{
        background:#F8F8F8;
        border: 5px solid #DFDFDF;
        color: #717171;
        font-size: 12px;
        height: 250px;
        width:310px;
        letter-spacing: 1px;
        line-height: 20px;
        position:absolute;
        text-align: left;
        text-transform: uppercase;
        top: auto;
        left:5px;
        display:none;
        padding:10px;


    }

    label.own{
        margin:0px;
        /*float:left;*/
        position:relative;
        cursor:pointer;
    }

    label.own:hover span{
        display:block;
        z-index:9;
    }
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="card">
            <div class="col-md-4 col-md-offset-2">
                <form method="POST" action="{{ asset('/user/update/pass') }}">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <input name="id" type="hidden" value="{{ $id }}">
                    <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Contraseña</label> <label class="own"><span class="glyphicon glyphicon-info-sign" style="color:#0099ff"><span class="own1" style="float:left">La contraseña debe tener: <br> 1) Un Numero <br> 2) Una Letra <br> 3) Un caracter Especial <br> 4) Debe tener al menos 7 caracteres</p></span></span></label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="Contraseña" value="" required>
                    </div>
                    <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Confirmar Contraseña</label> <label class="own"><span class="glyphicon glyphicon-info-sign" style="color:#0099ff"><span class="own1" style="float:left">La contraseña debe tener: <br> 1) Un Numero <br> 2) Una Letra <br> 3) Un caracter Especial <br> 4) Debe tener al menos 7 caracteres</p></span></span></label>
                            <input type="password" class="form-control" name="passwordCheck" id="passwordCheck" placeholder="Confirme la Contraseña" value="" required>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Actualizar Contraseña">
                    <a class="btn btn-danger" href="{{ asset('/user') }}"> Cancelar </a>
                </form>
            </div>
        </div>
    </div>
    @endsection
