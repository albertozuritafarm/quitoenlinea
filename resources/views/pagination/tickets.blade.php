<div class="col-md-12 border" style="margin-top:10px">
    <div id="tableDiv">
        <table id="newPaginatedTable" class="table table-striped row-border table-responsive hover stripe borderTable">
            <thead>
                <tr>
                    <th align="center" style="width: 5%">Numero</th>
                    <th align="center">Solicitante</th>
                    <th align="center">Asignado</th>
                    <th align="center" style="width: 5%">Fecha</th>
                    <th align="center" style="width: 5%">Estado</th>
                    <th align="center" style="width: 18%">Titulo</th>
                    <th align="center">Modulo</th>
                    <th align="center">Tipo Ticket</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tickets as $tic)
                <tr>
                    <td align="center"> <a href="{{asset('')}}ticket/detail/{{$tic->id}}">{{$tic->id}}</a> </td>
                    <td align="center">{{$tic->user}}</td>
                    <td align="center">{{$tic->user2}}</td>
                    <td align="center">{{$tic->beginDate}}</td>
                    <td align="center">{{$tic->status}}</td>
                    <td align="center">{{$tic->title}}</td>
                    <td align="center">{{$tic->menuName}}</td>
                    <td align="center">{{$tic->typeName}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div id="tableUsers_paginate" class="dataTables_paginate paging_simple_numbers paginationsUsersMargin" style="display:inline">
            <p>Mostrando {{count($tickets)}} resultados de {{ $tickets->total() }} totales</p>
            <span style="float:right;margin-top:-45px; padding:0">
                {{ $tickets->links('pagination::bootstrap-4') }}                        
            </span>
        </div>
    </div>
</div>