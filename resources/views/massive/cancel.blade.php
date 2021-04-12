@extends('layouts.app')

@section('content')
<script src="{{ assets('js/registerCustom.js') }}"></script>
<script src="{{ assets('js/massive/cancel.js') }}"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

<style>
    .form-group{
        margin-top:25px !important;
        margin-bottom: 25px !important;
    }

</style>
<div class="container" style="margin-top:15px; font-size:14px !important">
    <!--<div class="row justify-content-center border" style="margin-left:20%;">-->
    <div class="col-md-8 col-md-offset-2 border" style="padding: 15px">
        <div class="row">
            <div class="col-xs-12 registerForm" style="margin:12px;">
                <center>
                    <h4 style="font-weight:bold">Cancelación de Masivos</h4>
                    <!--<h5>Datos del Cliente.</h5>-->
                </center>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-4 wizard_inicial" style="padding-left:0px !important"><div class="wizard_inactivo"></div></div>
            <div class="col-xs-12 col-sm-4 wizard_medio"><div class="wizard_activo registerForm">Masivos</div></div>
            <div class="col-xs-12 col-sm-4 wizard_final" style="padding-right: 0px !important"><div class="wizard_inactivo"></div></div>
        </div>
        @if ( Session::has('excelError') )
        <div class="alert alert-danger alert-dismissible" role="alert">
            <ul class="list-group">
                @foreach($errors as $error)
                <li>{{$error['msg']}}</li>
                @endforeach
            </ul> 
        </div>
        @endif
        @if ( Session::has('error') )
        <div class="alert alert-danger alert-dismissible" role="alert" style="margin-top:5px">
            <center>
                <strong>
                    {{Session::get('error')}}
                </strong>
            </center>
        </div>
        @endif
        @if ( Session::has('success') )
        <div class="alert alert-success alert-dismissible" role="alert"  style="margin-top:5px">
            <center>
                <strong>
                    {{Session::get('success')}} 
                </strong>
            </center>
        </div>
        @endif




        <form action="{{ route('massive/store/cancel') }}" method="POST" id="uploadForm" enctype="multipart/form-data" onsubmit="validateCancelExcel()">
            {{ csrf_field() }}
            <div class="col-md-12 border" style="margin-top:15px">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="agency">Canal:</label>
                        <select class="form-control" id="channel" name="channel" required>
                            <option selected="true" value="">--Escoga Uno--</option>
                            @foreach($channels as $channel)
                            <option value="{{$channel->id}}">{{$channel->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="file-upload" for="file">Archivo:</label>
                        <input type="file" name="file">
                        <br>
                        Click
                        <a href="/massive/download/cancel/format" target="blank" title="Descargar Archivo Cargado"> 
                            AQUÍ
                        </a>
                        para descargar un archivo de muestra
                    </div>  
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="channel">Agencia:</label>
                        <select class="form-control" id="agency" name="agency" required>
                            <option selected="true" value="">--Escoga Uno--</option>
                        </select>
                    </div>
                </div>
                <div class="hidden" id="validateErrorDiv" style="margin-top:30px">
                    <span id="validateErrorMessage">

                    </span>
                </div>
                <input type="submit" style="float:left;padding: 5px;width:100px;margin-right: -15px;margin-top: 10px" class="btn btn-info registerForm" align="left" value="Validar Excel" onsubmit="validateUploadExcel()">

            </div>
            <div class="col-md-12" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                <a class="btn btn-default registerForm" align="left" href="{{ asset(session('massiveIndex')) }}" style="margin-left:-15px"> Cancelar </a>
                <input id="submitFormBtn" type="submit" style="float:right;padding: 5px;width:75px;margin-right:-15px" class="btn btn-info registerForm hidden" align="right" value="Guardar">
            </div>
        </form>
    </div>
</div>
@endsection
