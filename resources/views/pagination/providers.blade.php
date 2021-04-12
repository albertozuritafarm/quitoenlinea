<div class="col-md-12 border" style="margin-top:10px">
    <div id="tableDiv">
        <table id="newPaginatedTable" class="table table-striped row-border table-responsive hover stripe borderTable">
            <thead>
                <tr>
                    <th align="center">Documento</th>
                    <th align="center">Nombre</th>
                    <th align="center">Ciudad</th>
                    <th align="center">Contacto</th>
                    <th align="center">Télefono</th>
                    <th align="center">Celular</th>
                    <th align="center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($providers as $pro)
                <tr>
                    <td align="center"> <a href="#" onclick="providersResume({{$pro->id}})">{{$pro->document}}</a> </td>
                    <td align="center">{{$pro->name}}</td>
                    <td align="center">{{$pro->citName}}</td>
                    <td align="center">{{$pro->contact}}</td>
                    <td align="center">{{$pro->phone}}</td>
                    <td align="center">{{$pro->mobile_phone}}</td>
                    <td align="center">
                        @if($createBranch)
                            <a href="#" onclick="addBranchIndividual({{$pro->id}})"><span class="glyphicon glyphicon-plus" style="color:green;font-size:14px"></span></a>
                        @else
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div id="tableUsers_paginate" class="dataTables_paginate paging_simple_numbers paginationsUsersMargin" style="display:inline">
            <p>Mostrando {{count($providers)}} resultados de {{ $providers->total() }} totales</p>
            <span style="float:right;margin-top:-45px; padding:0">
                {{ $providers->links('pagination::bootstrap-4') }}                        
            </span>
        </div>
    </div>
</div>