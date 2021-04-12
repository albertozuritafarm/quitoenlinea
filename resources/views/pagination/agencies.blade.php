<div class="col-md-12 border" style="margin-top:10px">
    <div id="tableDiv">
        <table id="newPaginatedTable" class="table table-striped row-border table-responsive hover stripe borderTable">
            <thead>
                <tr>
                    <th align="center">ID</th>
                    <th align="center">Nombre Agencia</th>
                    <th align="center">Producto</th>
                    <th align="center">Agente</th>
                </tr>
            </thead>
            <tbody>
                @foreach($agencies as $agen)
                <tr>
                    <td align="center">{{$agen->agenId}}</td>
                    <td align="center">{{$agen->agenName}}</td>
                    <td align="center">{{$agen->proName}}</td>
                    <td align="center">{{$agen->agenteName}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div id="tableUsers_paginate" class="dataTables_paginate paging_simple_numbers paginationsUsersMargin" style="display:inline">
            <p>Mostrando {{count($agencies)}} resultados de {{ $agencies->total() }} totales</p>
            <span style="float:right;margin-top:-45px; padding:0">
                {{ $agencies->links('pagination::bootstrap-4') }}                        
            </span>
        </div>
    </div>
</div>