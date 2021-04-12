<div class="col-md-12 border" style="margin-top:10px">
    <div id="tableDiv" class="" >
        <table id="newPaginatedTable" class="table table-striped row-border table-responsive hover stripe borderTable">
            <thead>
                <tr>
                    <th align="center">ID Servicio</th>
                    <th align="center">ID Agendamiento</th>
                    <th align="center">Tipo de Golpe</th>
                    <th align="center">Fecha Inicial</th>
                    <th align="center">Fecha Fin</th>
                    <th align="center">Archivo</th>
                    <th align="center">Estado</th>
                    <th align="center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($newSchedules as $sche)
                <tr>
                    <td align="center"><a id="schedulingModalResume{{$sche -> detaId}}" href="#" title="Realizar un Pago" onclick="schedulingModalResume({{$sche -> detaId}})">{{$sche -> detaId}}</a></td>
                    <td align="center">{{$sche -> scheId}}</td>
                    <td align="center">{{$sche -> damage}}</td>
                    <td align="center">{{$sche -> beginDate}}</td>
                    <td align="center">{{$sche -> endDate}}</td>
                    @if($sche->file == null)
                    <td align="center"></td>
                    @else
                    <td align="center"><a href="{{$sche -> file}}"><span class="glyphicon glyphicon-download-alt" style="color:black;font-size:18px"></span></a></td>
                    @endif
                    <td align="center">{{$sche -> statusName}}</td>
                    <td align="center">
                        @if($sche->status == 3 && $edit)
                        <a href="#" title="Reagendar" onclick="reschedule({{$sche->detaId}}, {{$sche->time}})"><span class="glyphicon glyphicon-screenshot" style="color:black;font-size:19px;margin-left:5px"></span></a>
                        @else
                        <a href="#"  title="Reagendar" class="no-drop"><span class="glyphicon glyphicon-screenshot" style="color:black;font-size:19px;margin-left:5px"></span></a>
                        @endif
                        @if($sche->status == 3 && $edit)
                        <a id="confirmBtn{{$sche->detaId}}" href="#" data-toggle="tooltip" title="Confirmar" onclick="confirmAction({{$sche->detaId}})"><span class="glyphicon glyphicon-ok" style="color:green;font-size:19px;margin-left:5px"></span></a>
                        @else
                        <a href="#" data-toggle="tooltip" title="Confirmar" class="no-drop" disabled="disabled"><span class="glyphicon glyphicon-ok" style="color:green;font-size:19px;margin-left:5px"></span></a>
                        @endif
                        @if($sche->status == 3 && $cancel)
                        <a href="#" data-toggle="tooltip" title="Cancelar" onclick="cancel({{$sche->detaId}})"><span class="glyphicon glyphicon-remove" style="color:red;font-size:19px;margin-left:5px"></span></a>
                        @else
                        <a href="#" data-toggle="tooltip" title="Cancelar" class="no-drop"><span class="glyphicon glyphicon-remove" style="color:red;font-size:19px;margin-left:5px"></span></a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div id="tableUsers_paginate" class="dataTables_paginate paging_simple_numbers paginationsUsersMargin" style="display:inline">
            <p>Mostrando {{count($newSchedules)}} resultados de {{ $newSchedules->total() }} totales</p>
            <span style="float:right;margin-top:-45px; padding:0">
                {{ $newSchedules->links('pagination::bootstrap-4') }}                        
            </span>
        </div>
    </div>
</div>