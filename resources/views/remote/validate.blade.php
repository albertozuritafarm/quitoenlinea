@extends('layouts.remote_app')

@section('content')
<!--<div class="se-pre-con"></div>-->
<meta name="csrf-token" content="{{ csrf_token() }}" />
<script src="{{ assets('js/payments/createRemote.js') }}"></script>
<div class="container" style="width: 100%;padding: 20px;margin: 0px">
    <center>
        <div class="row">
            <div class="col-sm-8 col-sm-offset-4 border" style="margin-top:10px">
                <div class="row">
                    <div class="col-xs-12 registerForm" style="margin:0px;">
                        <center>
                            <h4 style="font-weight:bold">¿Desea subir las fotos de su vehículo?</h4>
                        </center>
                    </div>
                </div>
                <div id="cashDiv" class="row" style="padding-left:5px !important;padding-right: 5px">
                    <input type="hidden" id="salId" name="salId" value="{{$sale}}">
                </div>
                <div class="col-md-10 col-md-offset-1" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                    <form action="{{ route('remoteSendVehiLink') }}" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" id="salId" name="salId" value="{{$sale}}">
                        <input id="cancelBtn" type="submit" style="float:left;width:75px" class="btn btn-default registerForm" align="right" value="No">
                    </form>
                    <form action="{{ route('remoteVehiPictures') }}" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" id="salId" name="salId" value="{{$sale}}">
                        <input id="cancelBtn" type="submit" style="float:right;width:75px" class="btn btn-info registerForm" align="right" value="Si">
                    </form>
                </div>
            </div>
        </div>
    </center>
</div>

@endsection
