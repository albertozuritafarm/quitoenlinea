<div class="col-md-12 border" style="margin-top:10px">
    <div id="tableDiv" class="col-md-12" style="padding-left:0% !important;">
        <table id="newPaginatedTable" class="table table-striped row-border table-responsive hover stripe borderTable">
            <thead>
                <tr>
                    <th align="center">Seleccione</th>
                    <th align="center">ID Venta</th>
                    <th align="center">ID Masivo</th>
                    <th align="center">Tipo</th>
                    <th align="center">Canal</th>
                    <th align="center">Documento Cliente</th>
                    <th align="center">Nombre Cliente</th>
                    <th align="center">Valor Venta</th>
                    <th align="center">Fecha</th>
                    <th align="center">Estado Venta</th>
                    <th align="center">Estado Cobro</th>
                    <!--<th align="center">Acciones</th>-->
                </tr>
            </thead>
            <tbody>
                @foreach($massives as $massive)
                <tr>
                    @if($massive->salStatus == 'Cancelada')
                    <td align="center"><input type="checkbox" name="vehicle1" value="" disabled="disabled"></td>
                    @else
                    <td align="center"><input type="checkbox" name="vehicle1" value="{{$massive->salId}}"></td>
                    @endif
                    <td align="center"><a href="#" onclick="salesResumeTable({{$massive->salId}})">{{$massive->salId}}</a> </td>
                    <td align="center">{{$massive->massId}}</td>
                    <td align="center">{{$massive->tipo}}</td>
                    <td align="center">{{$massive->chanName}}</td>
                    <td align="center">{{$massive->cusDocument}}</td>
                    <td align="center">{{$massive->cusName}}</td>
                    @if($massive->tipo == 'Cancelación')
                    <td align="center">NA</td>
                    @else
                    <td align="center">{{$massive->salTotal}}</td>
                    @endif
                    <td align="center">{{$massive->salDate}}</td>
                    <td align="center">{{$massive->salStatus}}</td>
                    <td align="center">{{$massive->massStatus}}</td>
                    <!--<td align="center">-->
                        @if($massive->count == 0 && $massive->salStatus != 'Cancelada' && $edit)
                        <!--<a href="#" id="modalVehi" onclick="modalVehi({{$massive->salId}})"><span class="glyphicon glyphicon-plus" style="color:black;font-size:20px;top:3px !important">&ensp;</span></a>-->
                        @else
                        @endif
                        <!--                                @if($massive->upload_file == null)
                                                        @else
                                                        <a href="/massive/download/upload/file/{{$massive->massId}}" target="blank" title="Descargar Archivo Cargado">
                                                            <img src="{{ asset('/images/xls.png') }}" width="16px" height="16px">         
                                                            <i class="far fa-file-excel fa-2x" style="color:green;margin-right: 5px"></i>                        
                                                        </a>
                                                        @endif-->
                        <!--@if($massive->massStatus == 'Pendiente Pago' && $massive->salStatus == 'Activo' && $edit)-->
                            <!--<a class="hidden" href="#" data-toggle="tooltip" title="Confirmar Pago" onclick="payMassive({{$massive->massId}})">-->
                        @else
                            <!--<a class="hidden" href="#" data-toggle="tooltip" title="Confirmar Pago" class="no-drop">-->
                        @endif
                                <!--<span class="glyphicon glyphicon-usd" style="color:black;font-size:20px;top:3px !important">&ensp;</span>-->
                            <!--</a>-->
                    <!--</td>-->
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