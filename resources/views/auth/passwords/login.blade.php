@extends('layouts.app_login')

@section('content')

<!--<form id="FLogin" name="FLogin" method="POST" action="{{ route('login') }}">-->
<form method="POST" action="{{ route('login') }}">
    @csrf

    <div align="center" style="margin-bottom:60px">
        <img src="images/logo.png" class="img-responsive" style="max-width:50%;">
    </div>
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
            <div class="form-group" align="left">
                <label class="control-label">Contraseña</label>
                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="Ingrese su Contraseña" required>

                @if ($errors->has('password'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
                @endif
            </div>
            <div class="MensajeError" style="display:none; margin-bottom:30px" id="DIVErrorTextPassword"><label id="LErrorTextPassword"></label></div>
        </div>
    </div>
    <div>
        <div class="legend" style="margin-top:20px">
            <!-- Trigger the modal with a button -->
            <a href="/login/recover">¿Olvido su Contraseña?</a><br><br>
            
            <button type="submit" class="btn btn-info" id="BIngresar" style="width:100%; max-width:220px">Ingresar</button><br><br>
            <!--<button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal">Recuperar Contraseña</button>-->
        </div>
    </div>
</form>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Recuperar Contraseña</h4>
            </div>
            <div class="modal-body">
                <form method="POST" action="/login/recover">
                    @csrf
                    <div class="content">
                        <div>
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
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="float:left">Cerrar</button>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>

    </div>
</div>

<!--    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group row">
            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

            <div class="col-md-6">
                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                @if ($errors->has('email'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

            <div class="col-md-6">
                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                @if ($errors->has('password'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
                @endif
            </div>
        </div>



        <div class="form-group row mb-0">
            <div class="col-md-8 offset-md-4">
                <button type="submit" class="btn btn-primary">
                    {{ __('Login') }}
                </button>


            </div>
        </div>
    </form>-->

@endsection
