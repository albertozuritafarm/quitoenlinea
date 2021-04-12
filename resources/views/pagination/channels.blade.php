<div class="col-md-12 border" style="margin-top:10px">
    <div id="tableDiv">
        <table id="newPaginatedTable" class="table table-striped row-border table-responsive hover stripe borderTable">
            <thead>
                <tr>
                    <th align="center">ID</th>
                    <th align="center">Nombre Canal</th>
                    <th align="center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($channels as $cha)
                <tr>
                    <td align="center"> <a href="#" onclick="channelResume({{$cha->id}})">{{$cha->canalnegoid}}</a> </td>
                    <td align="center">{{$cha->canalnegodes}}</td>
                    <td align="center"><a href="#" title="Ver Agencias" onclick="addAgencyIndividual({{$cha->id}})"><span class="glyphicon glyphicon-search" style="color:green"></span></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div id="tableUsers_paginate" class="dataTables_paginate paging_simple_numbers paginationsUsersMargin" style="display:inline">
            <p>Mostrando {{count($channels)}} resultados de {{ $channels->total() }} totales</p>
            <span style="float:right;margin-top:-45px; padding:0">
                {{ $channels->links('pagination::bootstrap-4') }}                        
            </span>
        </div>
    </div>
</div>