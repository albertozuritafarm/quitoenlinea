<div class="col-md-12 border" style="margin-top:10px">
    <div id="tableDiv">
        <table id="newPaginatedTable" class="table table-striped row-border table-responsive hover stripe borderTable">
            <thead>
                <tr>
                    <th align="center">Id</th>
                    <th align="center"># Trans (Id cart)</th>
                    <th align="center">Refer. Orden</th>
                    <th align="center">Fecha Orden</th>
                    <th align="center">ID Transacción</th>
                    <th align="center"># Lote</th>
                    <th align="center"># Ref</th>
                    <th align="center">AuthCode</th>
                    <th align="center">Cod. Adquirir.</th>
                    <th align="center">Tipo</th>
                    <th align="center">Respuesta</th>
                </tr>
            </thead>
            <tbody>
                @foreach($datafast as $dat)
                <tr>
                    <td align="center">{{$dat->id}}</a> </td>
                    <td align="center">{{$dat->id_cart}}</a> </td>
                    <td align="center">{{$dat->order}}</td>
                    <td align="center">{{$dat->order_date}}</td>
                    <td align="center">{{$dat->id_transaction}}</td>
                    <td align="center">{{$dat->lot}}</td>
                    <td align="center">{{$dat->reference}}</td>
                    <td align="center">{{$dat->auth_code}}</td>
                    <td align="center">{{$dat->code}}</td>
                    <td align="center">{{$dat->type}}</td>
                    <td align="center">{{$dat->response}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div id="tableUsers_paginate" class="dataTables_paginate paging_simple_numbers paginationsUsersMargin" style="display:inline">
            <p>Mostrando {{count($datafast)}} resultados de {{ $datafast->total() }} totales</p>
            <span style="float:right;margin-top:-45px; padding:0">
                {{ $datafast->links('pagination::bootstrap-4') }}                        
            </span>
        </div>
    </div>
</div>