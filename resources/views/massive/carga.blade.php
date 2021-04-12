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

    <div class="col-md-12" style="margin-left: -15px">
        <h4>Listado de Masivos </h4>
        @include('pagination.items')
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
    </div>
    <div id="tableData">
        @include('pagination.massives')
    </div>
</div>