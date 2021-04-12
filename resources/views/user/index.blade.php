@extends('layouts.app')

@section('content')
<!--<div class="se-pre-con"></div>-->
<script src="{{ assets('js/registerCustom.js') }}"></script>
<meta name="csrf-token" content="{{ csrf_token() }}" />
<style>
    .modal-header {
    border-bottom: 0 none;
}

.modal-footer {
    border-top: 0 none;
}
</style>
<div class="container" style="width: 100%">
    <div>
        <div class="col-md-12 border" id="filter" style="margin-top:10px;margin-left:0;margin-right:15px; display: none;">
            <form method="POST" action="{{ asset('/user') }}">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="first_name">Nombre</label>
                            <input type="text" class="form-control" name="first_name" id="first_name" placeholder="Nombre" value="{{ session('usersFirstName') }}">
                        </div>

                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Apellido</label>
                            <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Nombre" value="{{ session('usersLastName') }}">
                        </div>

                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Identificación</label>
                            <input type="text" class="form-control" name="document" id="document" placeholder="Nombre" value="{{ session('usersDocument')  }}">
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Email</label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="Correo" value="{{ session('usersEmail')  }}">
                        </div>

                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Rol</label>
                            <select name="rol" id="rol" class="form-control" value="">
                                <option selected="true" disabled="disabled" value="0">--Escoja Una---</option>
                                @foreach($roles as $rol)
                                @if(session('usersRol') == $rol->id)
                                <option selected="true" value="{{$rol->id}}">{{$rol->name}}</option>
                                @else
                                <option value="{{$rol->id}}">{{$rol->name}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Canal</label>
                            <select name="channel" id="channel" class="form-control" value="">
                                <option selected="true" disabled="disabled" value="0">--Escoja Una---</option>
                                @foreach($channels as $channel)
                                @if(session('usersChannel') == $channel->id)
                                <option selected="true" value="{{$channel->id}}">{{$channel->canalnegodes}}</option>
                                @else
                                <option value="{{$channel->id}}">{{$channel->canalnegodes}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Agencia</label>
                            <select name="agency" id="agency" class="form-control" value="{{old('channel')}}">
                                <option selected="true" disabled="disabled" value="0">--Escoja Una---</option>
                                @foreach($agencies as $agency)
                                @if(session('usersAgency') == $agency->id)
                                <option selected="true" value="{{$agency->id}}">{{$agency->name}}</option>
                                @else
                                <option value="{{$agency->id}}">{{$agency->name}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>

                <input type="hidden" name="items" id="items" value="{{$items}}">
                <input type="button" id="btnCancel" class="btn btn-default" value="Cancelar">
                <input type="button" id="btnClear" class="btn btn-default" value="Limpiar">
                <input id="btnFilterForm"  type="submit" class="btn btn-primary" value="Aplicar">
            </form>
        </div>
        <div class="col-md-12" style="margin-left: -15px">
            <h4>Listado de Usuarios </h4>
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
                <center><strong>{{ session('Inactive') }}</strong></center>
            </div>
            @endif
            @if (session('userStoreSuccess'))
            <div class="alert alert-success">
                {{ session('userStoreSuccess') }}
            </div>
            @endif
            <button class="border btnTable" type="button" id="filterButton"><img id="filterImg" src="{{ asset('/images/filter.png') }}" width="24" height="24" alt=""></button> 
            <a type="button" class="border btnTable @if(!$create) hidden @endif" href="{{ asset('/user/create') }}" data-toggle="tooltip" title="Crear Usuario"><img src="{{ asset('/images/mas.png') }}" width="24" height="24" alt=""></a>
            @include('pagination.items')
        </div>
        
        <div id="tableData">
            @include('pagination.users')
        </div>
    </div>
</div>
                <!-- Trigger the modal with a button -->
<button id="modalBtn" type="button" class="btn btn-info btn-lg hidden" data-toggle="modal" data-target="#myModal">Open Modal</button>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
<!--        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>-->
      </div>
      <div class="modal-body">
        <div id="successDiv" class="alert alert-success hidden">
                    <p id="successMsg"></p>
                </div>
                <div id="errorDiv" class="alert alert-danger hidden">
                    <p id="errorMsg"></p>
                </div>
                <input id="id" name="id" type="hidden" value="">
                <div class="form-group">
                    <label style="list-style-type:disc;" for="first_name">Contraseña</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Contraseña" value="" required>
                </div>
                <div class="form-group">
                    <label style="list-style-type:disc;" for="first_name">Confirmar Contraseña</label>
                    <input type="password" class="form-control" name="passwordCheck" id="passwordCheck" placeholder="Confirme la Contraseña" value="" required>
                </div>
                <div style="form-group">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="float:left">Cerrar</button>
                    <input type="submit" class="btn btn-info" value="Actualizar Contraseña" style="float:right" onclick="changePass()">
                </div>
      </div>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
      </div>
    </div>

  </div>
</div>
<script>
    $(document).ready(function () {
        
        
        document.getElementById('pagination').onchange = function () {
        document.getElementById('items').value = this.value;
        document.getElementById('btnFilterForm').click();
        };
        
    $('#first_name').on('keyup', function(){
        if( /[^a-zA-Z ]/.test( this.value ) ) {
            alert('No puede ingresar Caracteres Especiales');
            var str= this.value;
            var newStr = str.substring(0, str.length - 1);
            this.value=newStr;
            this.focus();
        }
        this.value = this.value.toLocaleUpperCase();

    });
    $('#last_name').on('keyup', function(){
        if( /[^a-zA-Z ]/.test( this.value ) ) {
            alert('No puede ingresar Caracteres Especiales');
            var str= this.value;
            var newStr = str.substring(0, str.length - 1);
            this.value=newStr;
            this.focus();
        }
        this.value = this.value.toLocaleUpperCase();

    });
    $('#document').on('keyup', function(){
        if( /[^a-zA-Z0-9]/.test( this.value ) ) {
            alert('No puede ingresar Caracteres Especiales');
            var str= this.value;
            var newStr = str.substring(0, str.length - 1);
            this.value=newStr;
            this.focus();
        }
        this.value = this.value.toLocaleUpperCase();

    });
    
    });
    
    function confirmInactivate(url) {
        event.preventDefault();
//        console.log(url);
      var r = confirm("¿Seguro que desea Inactivar el Usuario?");
      if (r == true) {
        window.location.href = ROUTE + url;
      } else {
        return false;
      }
    }
    function confirmActivate(url) {
        event.preventDefault();
//        console.log(url);
      var r = confirm("¿Seguro que desea Activar el Usuario?");
      if (r == true) {
        window.location.href = ROUTE + url;
      } else {
        return false;
      }
    }
    
    function openModal(id){
         document.getElementById("modalBtn").click();
         document.getElementById("id").value = id;
         var successDiv = document.getElementById("successDiv");
        $(successDiv).addClass('hidden');
        var errorDiv = document.getElementById("errorDiv");
        $(errorDiv).addClass('hidden');
    }
    function changePass() {
//    event.preventDefault();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var password = document.getElementById("password").value;
    var passwordCheck = document.getElementById("passwordCheck").value;
    var idUser = document.getElementById("id").value;
    var url = ROUTE + '/user/password/modal';
    $.ajax({
    url: url,
            type: "POST",
            /* send the csrf-token and the input to the controller */
            data: {_token: CSRF_TOKEN, password: password, passwordCheck: passwordCheck, idUser: idUser},
            dataType: 'JSON',
            success: function (data) {
            console.log(data);
            if (data.success === 'false') {
            var successDiv = document.getElementById("successDiv");
            $(successDiv).addClass('hidden');
            var errorDiv = document.getElementById("errorDiv");
            $(errorDiv).removeClass('hidden');
            errorDiv.innerHTML = data.msg;
            } else {
            var errorDiv = document.getElementById("errorDiv");
            $(errorDiv).addClass('hidden');
            var successDiv = document.getElementById("successDiv");
            $(successDiv).removeClass('hidden');
            successDiv.innerHTML = data.msg;
            }
            }
    });
    }
    
    $(document).on('click', '.pagination a', function (event) {
        event.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        fetch_data(page);
    });

    function fetch_data(page)
    {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            type: "POST",
            data: {_token: CSRF_TOKEN},
            url: ROUTE + "/users/fetch_data?page=" + page,
            beforeSend: function () {
                // Show Loader
                $("#loaderGif").addClass('loaderGif');
//                $("#loaderBody").addClass('loaderBody');
            },
            success: function (data)
            {
                var tableData = document.getElementById("tableData");
                tableData.innerHTML = data;
                var table = $('#newPaginatedTable').DataTable({
                    "searching": false,
                    "pagination": false,
                    "paging": false,
                    "ordering": true,
                    "info": false,
                    "order": [[0, "desc"]],
                    "language": {
                        "sProcessing": "Procesando...",
                        "sLengthMenu": "Mostrar   _MENU_   registros",
                        "sZeroRecords": "No se encontraron resultados",
                        "sEmptyTable": "Ningún dato disponible en esta tabla",
                        "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                        "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                        "sInfoPostFix": "",
                        "sSearch": "Buscar:",
                        "sUrl": "",
                        "sInfoThousands": ",",
                        "sLoadingRecords": "Cargando...",
                        "oPaginate": {
                            "sFirst": "Primero",
                            "sLast": "Último",
                            "sNext": "Siguiente",
                            "sPrevious": "Anterior"
                        },
                        "oAria": {
                            "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                        }
                    }
                });
            },
            complete: function () {
                //Hide Loader
                var loaderGif = document.getElementById("loaderGif");
                loaderGif.classList.remove("loaderGif");
//                var loaderBody = document.getElementById("loaderBody");
//                loaderBody.classList.remove("loaderBody");
            }
        });
    }
</script>
@endsection
