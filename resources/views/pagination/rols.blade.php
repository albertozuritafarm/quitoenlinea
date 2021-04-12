<div class="col-md-12 border" style="margin-top:10px">
    <div id="tableDiv" class="" >
        <table id="newPaginatedTable" class="table table-striped row-border table-responsive hover stripe borderTable">
            <thead>
                <tr>
                    <!--<th align="center">Todos</th>-->
                    <th align="center">N°</th>
                    <th align="center">Nombre</th>
                    <th align="center">Cantidad Usuarios</th>
                    <th align="center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rols as $rol)
                <tr>
                    <!--<td align="center"><input type="checkbox" name="vehicle1" value=""></td>-->
                    <td align="center">{{$rol->rolId}}</td>
                    <td align="center">{{$rol->rolName}}</td>
                    <td align="center">{{$rol->totalUsr}}</td>
                    <td align="center">
                        @if($edit)
                            <a href="#" data-toggle="tooltip" title="Actualizar" onclick="editRol({{$rol->rolId}})">
                                <span class="glyphicon glyphicon-pencil" style="color:#139819;font-size:14px">&ensp;</span>
                            </a>
                        @else
                            <a href="#" data-toggle="tooltip" title="Actualizar" disabled="disabled">
                                <span class="glyphicon glyphicon-pencil" style="color:#139819;font-size:14px">&ensp;</span>
                            </a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div id="tableUsers_paginate" class="dataTables_paginate paging_simple_numbers paginationsUsersMargin" style="display:inline">
        </div>
    </div>
</div>
