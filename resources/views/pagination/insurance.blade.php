<div class="col-md-12 border" style="margin-top:10px">
    <div id="tableDiv">
        <table id="newPaginatedTable" class="table table-striped row-border table-responsive hover stripe borderTable">
            <thead>
                <tr>
                    <th align="center">Id</th>
                    <th align="center">Trans</th>
                    <th align="center">Identificacion</th>
                    <th align="center">Cliente</th>
                    <th align="center">Plan</th>
                    <th align="center">Valor Mensual</th>
                    <th align="center">Fecha</th>
                    <th align="center">Estado</th>
                    <th align="center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($insurances as $ins)
                <tr>
                    <td align="center">{{$ins->id}}</td>
                    <td align="center">{{$ins->transaction_code}}</td>
                    <td align="center">{{$ins->cusDocument}}</td>
                    <td align="center">{{$ins->customer}}</td>
                    <td align="center">{{$ins->name}}</td>
                    <td align="center">${{$ins->value_month}}</td>
                    <td align="center">{{$ins->created_at}}</td>
                    <td align="center">{{$ins->status}}</td>
                    <td align="center">
                        @if($ins->status == 'Pendiente')
                            <a href="#" onclick="validateCode({{$ins->id}})"> 
                                <span class="glyphicon glyphicon-envelope" style="color:black;font-size:19px;"></span>
                            </a>
                        @else
                            <span class=" no-drop glyphicon glyphicon-envelope" style="color:black;font-size:19px;"></span>
                        @endif
                        @if($ins->status == 'Activo')
                            <a href="#" onclick="cancelInsurance({{$ins->id}})"> 
                                <span class="glyphicon glyphicon-remove" style="color:red;font-size:19px;"></span>
                            </a>
                        @else
                            <span class=" no-drop glyphicon glyphicon-remove" style="color:red;font-size:19px;"></span>
                        @endif
                    </td>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div id="tableUsers_paginate" class="dataTables_paginate paging_simple_numbers paginationsUsersMargin" style="display:inline">
            <p>Mostrando {{count($insurances)}} resultados de {{ $insurances->total() }} totales</p>
            <span style="float:right;margin-top:-45px; padding:0">
                {{ $insurances->links('pagination::bootstrap-4') }}                        
            </span>
        </div>
    </div>
</div>