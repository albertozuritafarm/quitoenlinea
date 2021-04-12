<div class="col-md-12 border" style="margin-top:10px">
    <div id="tableDiv">
        <table id="newPaginatedTable" class="table table-striped row-border table-responsive hover stripe borderTable">
            <thead>
                <tr>
                    <th align="center">Nombre</th>
                    <th align="center">Ciudad</th>
                    <th align="center">Contacto</th>
                    <th align="center">Télefono</th>
                    <th align="center">Celular</th>
                </tr>
            </thead>
            <tbody>
                @foreach($providersBranch as $pro)
                <tr>
                    <td align="center">{{$pro->name}}</td>
                    <td align="center">{{$pro->citName}}</td>
                    <td align="center">{{$pro->contact}}</td>
                    <td align="center">{{$pro->phone}}</td>
                    <td align="center">{{$pro->mobile_phone}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div id="tableUsers_paginate" class="dataTables_paginate paging_simple_numbers paginationsUsersMargin" style="display:inline">
            <p>Mostrando {{count($providersBranch)}} resultados de {{ $providersBranch->total() }} totales</p>
            <span style="float:right;margin-top:-45px; padding:0">
                {{ $providersBranch->links('pagination::bootstrap-4') }}                        
            </span>
        </div>
    </div>
</div>