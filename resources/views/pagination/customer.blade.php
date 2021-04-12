<div class="col-md-12 border" style="margin-top:10px">
    <div id="tableDiv">
        <table id="newPaginatedTable" class="table table-striped row-border table-responsive hover stripe borderTable">
            <thead>
                <tr>
                    <th align="center">Documento</th>
                    <th align="center">Tipo Documento</th>
                    <th align="center">Nombres</th>
                    <th align="center">Direccion</th>
                    <th align="center">Ciudad</th>
                    <th align="center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $cus)
                <tr>
                    <td align="center"> <a href="#" onclick="customerResume({{$cus->id}})">{{$cus->document}}</a> </td>
                    <td align="center">{{$cus->docName}}</td>
                    <td align="center">{{$cus->first_name}} {{$cus->last_name}}</td>
                    <td align="center">{{$cus->address}}</td>
                    <td align="center">{{$cus->citName}}</td>
                    <td align="center">
                        @if($edit)
                            <a href="#" onclick="editCustomer({{$cus->id}})" data-toggle="tooltip" title="Editar"><span class="glyphicon glyphicon-pencil" style="color:green;font-size:14px"></span></a>
                        @else
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div id="tableUsers_paginate" class="dataTables_paginate paging_simple_numbers paginationsUsersMargin" style="display:inline">
            <p>Mostrando {{count($customers)}} resultados de {{ $customers->total() }} totales</p>
            <span style="float:right;margin-top:-45px; padding:0">
                {{ $customers->links('pagination::bootstrap-4') }}                        
            </span>
        </div>
    </div>
</div>