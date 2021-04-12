@extends('layouts.app_login')

@section('content')

<!--<form id="FLogin" name="FLogin" method="POST" action="{{ route('login') }}">-->
<form method="POST" action="{{ asset('/login/recover') }}">
    {{ csrf_field() }}

    <div align="center" style="margin-bottom:60px">
        <img src="{{assets('images/logo_seguros_sucre.jpg')}}" class="img-responsive" width="100%">
    </div>
    <div class="content">
        <div class="content">
            <div>
                @if (session('Recover'))
                <div class="alert alert-success">
                    {{ session('Recover') }}
                </div>
                @endif
                <div class="form-group" align="left">
                    <label class="control-label">Correo</label>
                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="Ingrese el Correo" required autofocus>
                    @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                    @endif
                </div>
                <div class="MensajeError" style="display:none" id="DIVErrorTextLogin"><label id="LErrorTextLogin"></label></div>
                <div class="MensajeError" style="display:none; margin-bottom:30px" id="DIVErrorTextPassword"><label id="LErrorTextPassword"></label></div>
            </div>
        </div>

        <button type="submit" class="btn btn-info" style="float:right">Recuperar</button>
        <a href="{{ asset('login') }}" class="btn btn-default" type="button" style="float:left">Cancelar</a>
        <!--<button type="button" class="btn btn-default" style="float:left">Cancelar</button>-->
    </div>
    <div>
    </div>
</form>


@endsection
