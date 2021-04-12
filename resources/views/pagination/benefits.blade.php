<div class="col-md-12 border" style="margin-top:10px">
    <div id="tableDiv" class="" >
        <table id="newPaginatedTable" class="table table-striped row-border table-responsive hover stripe borderTable">
            <thead>
                <tr>
                    <!--<th align="center">Todos</th>-->
                    <th align="center">N°</th>
                    <th align="center">Codigo</th>
                    <th align="center">Canal</th>
                    <th align="center">Beneficio</th>
                    <th align="center">Vigencia Hasta</th>
                    <th align="center">Vigencia Desde</th>
                    <th align="center">N° Usos</th>
                    <th align="center">Descuento</th>
                    <th align="center">Estado</th>
                    <th align="center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($benefits as $benefit)
                <tr>
                    <!--<td align="center"><input type="checkbox" name="vehicle1" value=""></td>-->
                    <td align="center">{{$benefit->id}}</td>
                    <td align="center">{{$benefit->code}}</td>
                    <td align="center">{{$benefit->channel}}</td>
                    <td align="center">{{$benefit->name}}</td>
                    <td align="center">{{$benefit->beginDate}}</td>
                    <td align="center">{{$benefit->endDate}}</td>
                    <td align="center">{{$benefit->uses}}</td>
                    <td align="center">{{$benefit->discount_percentage}}%</td>
                    <td align="center">{{$benefit->status}}</td>
                    <td align="center">
                        <!--                                    <a href="#" title="Editar" onclick="editModal({{$benefit->id}})">
                                                                <span class="glyphicon glyphicon-pencil" style="color:green;font-size:19px;margin-left:5px"></span>
                                                            </a>-->
                        @if($benefit->status === "Cancelada" || !$cancel)
                        <a href="#" title="cancelar" class="no-drop">
                            <span class="glyphicon glyphicon-remove" style="color:red;font-size:19px;margin-left:5px"></span>
                        </a>
                        @else
                        <a href="#" title="Cancelar" onclick="cancelModal({{$benefit->id}})">
                            <span class="glyphicon glyphicon-remove" style="color:red;font-size:19px;margin-left:5px"></span>
                        </a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div id="tableUsers_paginate" class="dataTables_paginate paging_simple_numbers paginationsUsersMargin" style="display:inline">
            <p>Mostrando {{count($benefits)}} resultados de {{ $benefits->total() }} totales</p>
            <span style="float:right;margin-top:-45px; padding:0">
                {{ $benefits->links('pagination::bootstrap-4') }}                        
            </span>
        </div>
    </div>
</div>
