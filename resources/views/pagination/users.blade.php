
<div class="col-md-12 border" style="margin-top:10px">
    <div id="tableDiv" class="">
        <table id="newPaginatedTable" class="table table-striped row-border table-responsive hover stripe borderTable">
            <thead>
                <tr>
                    <th align="center">Nombre</th>
                    <th align="center">Apellido</th>
                    <th align="center">Correo</th>
                    <th align="center">Documento</th>
                    <th align="center">Tipo Documento</th>
                    <th align="center">Canal</th>
                    <th align="center">Estado</th>
                    <th align="center">Tipo</th>
                    <th align="center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td align="left">{{$user->first_name}}</td>
                    <td align="left">{{$user->last_name}}</td>
                    <td align="left">{{$user->email}}</td>
                    <td align="left">{{$user->document}}</td>
                    <td align="center">{{$user->documento}}</td>
                    <td align="center">{{$user->channel}}</td>
                    <td align="center">{{$user->estado}}</td>
                    <td align="center">{{$user->typUser}}</td>
                    <td align="center">
                        @if($edit)
                            <a onclick="openModal({{$user->id}})" title="Cambiar Contraseña">
                                <span class="glyphicon glyphicon-lock" style="color:black;font-size:14px">&ensp;</span>                                     
                            </a>
                            <a href="{{asset('')}}user/update/{{Crypt::encrypt($user->id)}}" data-toggle="tooltip" title="Actualizar">
                                <span class="glyphicon glyphicon-pencil" style="color:#139819;font-size:14px">&ensp;</span>
                            </a>
                        @else
                            <a onclick="#" title="Cambiar Contraseña" disabled="disabled" style="cursor: not-allowed;">
                                <span class="glyphicon glyphicon-lock" style="color:black;font-size:14px" disabled="disabled">&ensp;</span>                                     
                            </a>
                            <a href="#" data-toggle="tooltip" title="Actualizar" disabled="disabled" style="cursor: not-allowed;">
                                <span class="glyphicon glyphicon-pencil" style="color:#139819;font-size:14px">&ensp;</span>
                            </a>
                        @endif
                        @if($cancel)
                            @if($user->status_id == 1)
                            <!--<a href="/user/inactive/{{Crypt::encrypt($user->id)}}" data-toggle="tooltip" title="Inactivar Usuario" onclick="confirmInactivate()">-->
                            <a href="#"data-toggle="tooltip" title="Inactivar Usuario" onclick="confirmInactivate('/user/inactive/{{Crypt::encrypt($user->id)}} ' )">
                                <span class="glyphicon glyphicon-remove" style="color:#fc2d2d;font-size:14px"></span>                          
                            </a>
                            @else
                            <!--<a href="/user/inactive/{{Crypt::encrypt($user->id)}}" data-toggle="tooltip" title="Activar Usuario" onclick="confirmActivate()">-->
                            <a href="#" data-toggle="tooltip" title="Activar Usuario" onclick="confirmActivate('/user/inactive/{{Crypt::encrypt($user->id)}} ' )">
                                <span class="glyphicon glyphicon-ok" style="color:#fc2d2d;font-size:14px"></span>                          
                            </a>
                            @endif
                        @else
                            <a href="#"data-toggle="tooltip" title="Inactivar Usuario" disabled="disabled" style="cursor: not-allowed;">
                                <span class="glyphicon glyphicon-remove" style="color:#fc2d2d;font-size:14px"></span>                          
                            </a>
                        @endif
                    </td>
                </tr>

                @endforeach
            </tbody>
        </table>
        <div id="tableUsers_paginate" class="dataTables_paginate paging_simple_numbers paginationsUsersMargin" style="display:inline">
            <p>Mostrando {{count($users)}} resultados de {{ $users->total() }} totales</p>
            <span style="float:right;margin-top:-45px; padding:0">
                {{ $users->links('pagination::bootstrap-4') }}                        
            </span>
        </div>
    </div>
</div>