<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <title>Tu Póliza en Línea</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link href="{{ assets('css/bootstrap-3.3.6-dist/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{ assets('js/jquery-ui-1.11.4.custom/jquery-ui.min.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{ assets('css/estilos_generales.css')}}" rel="stylesheet" type="text/css"/>
        <link rel="shortcut icon" href="{{ assets('images/favicons/favicon.ico')}}">
        <link href="{{ assets('css/menu.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{ assets('css/Simple-Line/simple-line-icons.css')}}" rel="stylesheet" type="text/css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css"> 
        <link href="{{ assets('css/estilos_generales.css')}}" rel="stylesheet" type="text/css" />
        <script type="text/javascript">
            function load() {
                var loaderGif = document.getElementById("loaderGif");
                loaderGif.classList.remove("loaderGif");
                var loaderBody = document.getElementById("loaderBody");
                loaderBody.classList.remove("loaderBody");
            }
            window.onload = load;
        </script>
    </head>

    <body style="font-size: 11px">
        <div id='loaderGif' class="loaderGif"></div>
        <div id="loaderBody" class="loaderBody"></div>
        <div id="" class="toggled">
            <div id="page-content-wrapper" style="background-color:#FFFFFF; border:solid 1px #E5E5E5; height: 100px;">
                <img class="img-responsive" id="headerImgLeft" src="{{ assets('images/Web_header1.png')}}" width="auto" height="auto" style="float:left;margin-top: -45px; font-weight: bold;margin-left: 15px;max-width: 50%;"/>
                <img class="img-responsive" id="headerImgRight" src="{{ assets('images/Web_header2.png')}}" width="auto" height="auto" style="float:right;margin-top: -45px;margin-right: 15px;max-width: 40%;"/>
                <!-- <h5 style="float:left;margin-top: -25px; font-weight: bold;margin-left: 15px">Seguros Sucre</h5> -->
            </div>
            </nav>

            <!--<div id="Contenedor_Principal">-->
            <div id="content" class="" style="padding-top:20px;">
                @yield('content')
            </div>

        </div>


        <footer class="modal-footer">
            <a href="#" style="border:none; color:black; font-size:12px">MagnusMasSoft | 2020</a>
        </footer>
        <script src="{{ assets('js/jquery-2.2.4.min.js')}}"></script>
        <script src="{{ assets('js/jquery-ui-1.11.4.custom/jquery-ui.min.js')}}"></script>
    <script src="{{ assets('js/currency.js')}}"></script>
        <script src="{{ assets('js/Generales.js')}}"></script>
        <script src="{{ assets('js/login/login.js')}}"></script>
        <script src="{{ assets('css/bootstrap-3.3.6-dist/js/bootstrap.min.js')}}"></script>
        <script src="{{ assets('js/jquery-ui-1.11.4.custom/jquery-ui.min.js')}}"></script>
        <!--<script src="{{ assets('js/Generales.js')}}"></script>-->
        <script src="{{ assets('js/principal/principal.js')}}"></script>
        <script src="{{ assets('js/loader.js')}}"></script>
        <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="{{ assets('js/GlobalVars.js')}}"></script>
        <script src="{{ assets('js/jquery.formautofill.js')}}"></script>
        <!--<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>-->
        <!--<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>-->
        <!--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.18/b-1.5.6/b-colvis-1.5.6/datatables.min.cs        s"/>-->
        <link href="{{ assets('/css/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="{{ assets('css/datatables/datatables.min.js')}}"></script>


        <script src="{{ assets('js/jquery-ui.js')}}"></script>
        <script type="text/javascript"  src="{{ assets('js/datatables.min.js')}}"></script>
        <script src="{{ assets('js/modernizer.js')}}"></script>
        <script src="{{ assets('js/jquery-redirect.js')}}"></script>
        <script type="text/javascript" src="{{ assets('js/dateFormat/jquery-dateFormat.min.js')}}"></script>
    </body>
</html>