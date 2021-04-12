
<!--<div class="se-pre-con"></div>-->
<script src="{{ asset('js/massive/index.js') }}"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
<meta name="csrf-token" content="{{ csrf_token() }}" />
<style>
    body {font-family: Arial;}

    /* Style the tab */
    .tab {
        overflow: hidden;
        border: 1px solid #ccc;
        background-color: #f1f1f1;
    }

    /* Style the buttons inside the tab */
    .tab button {
        background-color: inherit;
        float: left;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 14px 16px;
        transition: 0.3s;
        font-size: 17px;
    }

    /* Change background color of buttons on hover */
    .tab button:hover {
        background-color: #ddd;
    }

    /* Create an active/current tablink class */
    .tab button.active {
        background-color: #ccc;
    }

    /* Style the tab content */
    .tabcontent {
        display: none;
        padding: 6px 12px;
        border-top: none;
    }
</style>
<div class="container" style="width: 100%">
        <div class="col-md-12 hidden border" id="filter" style="margin-top:10px;margin-left:0;margin-right:15px;">
            <form method="POST" action="{{asset('/massive')}}">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="channel">Canal</label>
                            <select name="channel" id="channel" class="form-control" value="">
                                <option value="">-- Todos --</option>
                                @foreach($channels as $channel)
                                <option @if($channel->id == session('massiveFirstViewChannel')) @else @endif value="{{$channel->id}}">{{$channel->name}}</option>
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
                <img src="{{ asset('images/iconos/warning.png')}}" alt="Girl in a jacket" style="width:40px;height:40px"> {{ session('Error') }}
            </div>
            @endif
            @if (session('Success'))
            <div class="alert alert-success">
                <img src="{{ asset('images/iconos/ok.png')}}" alt="Girl in a jacket" style="width:40px;height:40px">{{ session('Success') }}
            </div>
            @endif
            @if (session('Inactive'))
            <div class="alert alert-success">
                <img src="{{ asset('images/iconos/ok.png')}}" alt="Girl in a jacket" style="width:40px;height:40px">{{ session('Inactive') }}
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
            <button class="border btnTable" type="button" id="filterButton"><img id="filterImg" src="{{ asset('/images/filter.png') }}" width="24" height="24" alt=""></button> 
            <a type="button" class="border btnTable" href="{{ asset('/massive/create') }}" data-toggle="tooltip" title="Nuevo"><img src="{{ asset('/images/mas.png') }}" width="24" height="24" alt=""></a>
            <a type="button" class="border btnTable" href="{{ asset('/massive/cancel') }}" data-toggle="tooltip" title="Cancelar"><img src="{{ asset('/images/menos.png') }}" width="24" height="24" alt=""></a>
            @include('pagination.items')
        </div>
        <div id="tableData">
            @include('pagination.massives')
        </div>
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
    }
</script>