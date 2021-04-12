<div class="col-md-12 border" style="margin-top:10px">
    <div id="tableDiv" class="" >
        <table id="newPaginatedTable" class="table table-striped row-border table-responsive hover stripe borderTable">
            <thead>
                <tr>
                    <th align="center">N°</th>
                    <th align="center">Placa</th>
                    <th align="center">Beneficio</th>
                    <th align="center">Fecha</th>
                    <th align="center">Cliente</th>
                    <th align="center">Teléfono</th>
                </tr>
            </thead>
            <tbody>
                @foreach($benefits as $benefit)
                <tr>
                    <td align="center">{{$benefit->id}}</td>
                    <td align="center">{{$benefit->plate}}</td>
                    <td align="center">{{$benefit->name}}</td>
                    <td align="center">{{$benefit->date}}</td>
                    <td align="center">{{$benefit->customer}}</td>
                    <td align="center">{{$benefit->phone}}</td>
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
