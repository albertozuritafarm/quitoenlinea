<div class="col-md-12 border" style="margin-top:10px">
    <div id="tableDiv" class="" >
        <table id="newPaginatedTable" class="table table-striped row-border table-responsive hover stripe borderTable">
            <thead>
                <tr>
                    <th align="center">ID</th>
                    <th align="center">Canal</th>
                    <th align="center">Cantidad Vehiculos</th>
                    <th align="center">Total</th>
                    <th align="center">Fecha</th>
                    <th align="center">Tipo</th>
                    <th align="center">Estado Venta</th>
                    <th align="center">Estado Cobro</th>
                    <th align="center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($massives as $massive)
                <tr>
                    <td align="center">{{$massive->id}}</td>
                    <td align="center">{{$massive->canal}}</td>
                    <td align="center">{{$massive->cantidad}}</td>
                    @if($massive->tipo == 'Cancelación')
                    <td align="center">NA</td>
                    @else
                    <td align="center">{{$massive->total}}</td>
                    @endif
                    <td align="center">{{$massive->fecha}}</td>
                    <td align="center">{{$massive->tipo}}</td>
                    <td align="center">{{$massive->estadoMasivo}}</td>
                    <td id="statusCharge{{$massive->id}}" align="center">{{$massive->estadoCobro}}</td>
                    <td align="center">
                        <a href="{{asset('/massive/download/upload/file/')}}/{{$massive->id}}" target="blank" title="Descargar Archivo Cargado">
                            <i class="far fa-file-excel fa-2x" style="color:green;margin-right: 5px"></i>                        
                        </a>
                        @if($massive->estadoCobro == 'Pendiente Pago' && $massive->estadoMasivo == 'Activo')
                        <a href="#" data-toggle="tooltip" title="Confirmar Pago" onclick="payMassive({{$massive->id}})">
                        @else
                        <a href="#" data-toggle="tooltip" title="Confirmar Pago" class="no-drop">
                        @endif
                        <span class="glyphicon glyphicon-usd" style="color:black;font-size:20px;top:3px !important">&ensp;</span>
                        </a>
                    </td>
                </tr>

                @endforeach
            </tbody>
        </table>
        <div id="tableUsers_paginate" class="dataTables_paginate paging_simple_numbers paginationsUsersMargin" style="display:inline">
            <p>Mostrando {{count($massives)}} resultados de {{ $massives->total() }} totales</p>
            <span style="float:right;margin-top:-45px; padding:0">
                {{ $massives->links('pagination::bootstrap-4') }}                        
            </span>
        </div>
    </div>
</div>