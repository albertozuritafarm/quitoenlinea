@extends('layouts.app')

@section('content')
<!--<div class="se-pre-con"></div>-->
<script src="{{ assets('js/massive/index.js') }}"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
<meta name="csrf-token" content="{{ csrf_token() }}" />
<style>
    .massiveMenu:hover {
        cursor: pointer;
	background-color:#6c6c6c !important;
        border: 1px solid #6c6c6c  !important;
    }
    .massiveMenu{
	text-transform:uppercase;
	font-size:12px;
	font-weight:600;
    }
</style>
<ul class="nav nav-tabs" style="font-size:15px;background-color: #444;margin-top:-20px">
    <li style="padding-left:15px; margin-bottom: 0px !important"><a class="menuactivo massiveMenu" href="#">Carga</a></li>
    <li><a class="massiveMenu" onclick="redirectUrl('/massive/secondary')" style="color:white;">Listado</a></li>
</ul>
<div class="container" style="width: 100%">
    <div class="col-md-12 border" id="filter" style="margin-top:10px;margin-left:0;margin-right:15px; display: none;">
        <form method="POST" action="{{asset('/massive')}}">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label style="list-style-type:disc;" for="channel">Canal</label>
                        <select name="channel" id="channel" class="form-control" value="">
                            <option value="">-- Todos --</option>
                            @foreach($channels as $channel)
                            <option @if($channel->id == session('massiveFirstViewChannel')) selected="true" @else @endif value="{{$channel->id}}">{{$channel->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label style="list-style-type:disc;" for="beginDate">Fecha Inicio</label>
                        <input type="date" class="form-control" name="beginDate" id="beginDate" placeholder="fecha" style="line-height:14px" value="{{session('massiveFirstViewBeginDate')}}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label style="list-style-type:disc;" for="endDate">Fecha Fin</label>
                        <input type="date" class="form-control" name="endDate" id="endDate" placeholder="fecha" style="line-height:14px" onchange="endDateChange()" value="{{session('massiveFirstViewEndDate ')}}">
                    </div>

                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label style="list-style-type:disc;" for="type">Tipo</label>
                        <select name="type" id="type" class="form-control" value="">
                            <option value="">-- Todos --</option>
                            @foreach($massiveTypes as $type)
                            <option @if($type->id == session('massiveFirstViewType')) selected="true" @else @endif value="{{$type->id}}">{{$type->name}}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label style="list-style-type:disc;" for="statusMassive">Estado Venta</label>
                        <select name="statusMassive" id="statusMassive" class="form-control" value="">
                            <option value="">-- Todos --</option>
                            @foreach($statusMassive as $massive)
                            <option @if($massive->id == session('massiveFirstViewStatus')) selected="true" @else @endif value="{{$massive->id}}">{{$massive->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label style="list-style-type:disc;" for="statusPayment">Estado Cobro</label>
                        <select name="statusPayment" id="statusPayment" class="form-control" value="">
                            <option  value="">-- Todos --</option>
                            @foreach($statusCharge as $charge)
                            <option @if($charge->id == session('massiveFirstViewStatusPayment')) selected="true" @else @endif value="{{$charge->id}}">{{$charge->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <input type="hidden" id="items" name="items" value="{{$items}}">
            <input type="button" id="btnCancel" class="btn btn-default" value="Cancelar">
            <input type="button" id="btnClearMassive" class="btn btn-default" value="Limpiar">
            <input id="btnFilterForm" type="submit" class="btn btn-primary" value="Aplicar"  onclick="return val()">
        </form>
    </div>
    <div class="col-md-12" style="margin-left: -15px">
        <h4>Listado de Masivos </h4>
        @if (session('Error'))
        <div class="alert alert-warning">
            <img src="{{ assets('images/iconos/warning.png')}}" alt="Girl in a jacket" style="width:40px;height:40px"> {{ session('Error') }}
        </div>
        @endif
        @if (session('Success'))
        <div class="alert alert-success">
            <img src="{{ assets('images/iconos/ok.png')}}" alt="Girl in a jacket" style="width:40px;height:40px">{{ session('Success') }}
        </div>
        @endif
        @if (session('Inactive'))
        <div class="alert alert-success">
            <img src="{{ assets('images/iconos/ok.png')}}" alt="Girl in a jacket" style="width:40px;height:40px">{{ session('Inactive') }}
        </div>
        @endif
        @if ( Session::has('storeSuccess') )
        <div class="alert alert-success alert-dismissible" role="alert"  style="margin-top:5px">
            <center>
                <strong>
                    {{Session::get('storeSuccess')}} 
                </strong>
            </center>
        </div>
        @endif
        @if ( Session::has('cancelSuccess') )
        <div class="alert alert-success alert-dismissible" role="alert"  style="margin-top:5px">
            <center>
                <strong>
                    {{Session::get('cancelSuccess')}} 
                </strong>
            </center>
        </div>
        @endif
        <div id="paymentSuccess" class="alert alert-success hidden">
            <center>
                <strong>
                    El masivo fue pagado de manera exitosa
                </strong>
            </center>
        </div>
        <div id="paymentError" class="alert alert-danger hidden">
            <center>
                <strong>
                    Hubo un error por favor comuniquese con el administrador
                </strong>
            </center>
        </div>
        <button class="border btnTable" type="button" id="filterButton"><img id="filterImg" src="{{ assets('/images/filter.png') }}" width="24" height="24" alt=""></button> 
        <a type="button" class="border btnTable" href="{{ asset('/massive/create') }}" data-toggle="tooltip" title="Nuevo"><img src="{{ assets('/images/mas.png') }}" width="24" height="24" alt=""></a>
        <a type="button" class="border btnTable" href="{{ asset('/massive/cancel') }}" data-toggle="tooltip" title="Cancelar"><img src="{{ assets('/images/menos.png') }}" width="24" height="24" alt=""></a>
        @include('pagination.items')
    </div>
    <div id="tableData">
        @include('pagination.massives')
    </div>

    <!--    <div id="carga" class="tabcontent2">
            <div id="loadContent">
                @include('massive.carga')
            </div>
        </div>-->
    <!--    <div id="listado" class="tabcontent">
            listado
        </div>-->
</div>
<script>
    document.getElementById('pagination').onchange = function () {
        document.getElementById('items').value = this.value;
        document.getElementById('btnFilterForm').click();
    };
    function openCity(evt, viewName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(viewName).style.display = "block";
        evt.currentTarget.className += " active";
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            type: "POST",
            data: {_token: CSRF_TOKEN},
            url: ROUTE + "/massive/loadView/" + viewName,
            beforeSend: function () {
                // Show Loader
                $("#loaderGif").addClass('loaderGif');
            },
            success: function (data)
            {
                var tableData = document.getElementById("loadContent");
                tableData.innerHTML = data;
            },
            complete: function () {
                //Hide Loader
                var loaderGif = document.getElementById("loaderGif");
                loaderGif.classList.remove("loaderGif");
            }
        });
    }

    function redirectUrl(URL) {
        window.location.href = ROUTE + URL;
    }
</script>
@endsection
