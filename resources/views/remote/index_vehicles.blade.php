@extends('layouts.app_login')

@section('content')

<!--<form id="FLogin" name="FLogin" method="POST" action="{{ route('login') }}">-->
<form method="POST" action="{{ route('remoteVehicles') }}">
    {{ csrf_field() }}

    <div align="center">
        <img src="images/logo.png" class="img-responsive" style="max-width:50%;">
    </div>
    <div class="content">
        <div>
            @if (session('errorMsg'))
            <div class="alert alert-danger">
                {!! session('errorMsg') !!}
            </div>
            @endif
            @if (session('vehiLinkMsg'))
            <div class="alert alert-success">
                {{ session('vehiLinkMsg') }}
            </div>
            @endif

            <div class="form-group" align="left">
                <label class="control-label">Codigo</label>
                <input id="code" type="text" class="form-control" name="code" value="{{old('code')}}" placeholder="Ingrese el Codigo" required autofocus>
            </div>
            <div class="form-group" align="left">
                <label class="control-label">Identificación</label>
                <input id="document" type="text" class="form-control" name="document" value="{{old('document')}}" placeholder="Ingrese su Identificación" required autofocus>
            </div>
            <div class="MensajeError" style="display:none" id="DIVErrorTextLogin"><label id="LErrorTextLogin"></label></div>
        </div>
    </div>
    <div>
        <div class="legend" style="margin-top:20px">
            <!-- Trigger the modal with a button -->
            <button type="submit" class="btn btn-info" id="BIngresar" style="width:100%; max-width:220px">Ingresar</button><br><br>
            <!--<button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal">Recuperar Contraseña</button>-->
        </div>
    </div>
</form>
<form method="POST" action="{{ route('resendCodeVehicles') }}">
    {{ csrf_field() }}
    <input id="salId" name="salId" type="hidden" value="">
    <button type="submit" class="btn btn-info hidden" id="btnResendCode" style="width:100%; max-width:220px">Ingresar</button>
</form>
<script>
    $(document).ready(function () {
    });
    function resendCode(id){
        document.getElementById("salId").value = id;
        document.getElementById("btnResendCode").click();
    }
</script>
@endsection

