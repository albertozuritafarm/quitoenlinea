<div class="col-md-12 border" style="margin-top:10px">
    <div id="tableDiv">
        <table id="newPaginatedTable" class="table table-striped row-border table-responsive hover stripe borderTable">
            <thead>
                <tr>
                    <th align="center">Venta</th>
                    <th align="center">Identificación</th>
                    <th align="center">Fecha</th>
                    <th align="center">Valor</th>
                    <th align="center">Estado</th>
                    <th align="center">Forma de Pago</th>
                    <th align="center">Tipo</th>
                    <th align="center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($charges as $charge)
                <tr>
                    <td align="center"><a id="paymentsModalResume{{$charge->salId}}" href="#" title="Realizar un Pago" onclick="paymentsModalResume({{$charge->salId}})">{{$charge->salId}}</a></td>
                    <td align="center">{{$charge->document}}</td>
                    <td align="center" id="dateTable{{$charge->id}}">{{$charge->date}}</td>
                    <td align="center">{{$charge->value}}</td>
                    <td align="center" id="statusTable{{$charge->id}}">{{$charge->status}}</td>
                    <td align="center" id="typeTable{{$charge->id}}">{{$charge->type}}</td>
                    <td align="center">{{$charge->typeCharge}}</td>
                    <td align="center">
                        <input type="hidden" name="id" id="id" value="{{$charge->id}}">
                        @if($edit)
                            @if($charge->cancel == 'true' && $charge->statusId == 21)
                                <a href="{{asset('')}}payments/refund/{{Crypt::encrypt($charge->salId)}}" title="Anular un Pago">
                                    <span class="glyphicon glyphicon-minus-sign" style="color:black;font-size:19px">&ensp;</span>                                     
                                </a>
                            @else
                                <a class="no-drop" href="#" title="Anular un Pago">
                                    <span class="glyphicon glyphicon-minus-sign" style="color:red;font-size:19px">&ensp;</span>                                     
                                </a>
                            @endif
                        @else
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div id="tableUsers_paginate" class="dataTables_paginate paging_simple_numbers paginationsUsersMargin" style="display:inline">
            <p>Mostrando {{count($charges)}} resultados de {{ $charges->total() }} totales</p>
            <span style="float:right;margin-top:-45px; padding:0">
                {{ $charges->links('pagination::bootstrap-4') }}                        
            </span>
        </div>
    </div>
</div>