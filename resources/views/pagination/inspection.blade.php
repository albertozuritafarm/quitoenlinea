<div class="col-md-12 border" style="margin-top:10px">
    <div id="tableDiv">
        <table id="newPaginatedTable" class="table table-striped row-border table-responsive hover stripe borderTable">
            <thead>
                <tr>
                    <th align="center">Id</th>
                    <th align="center">Fecha</th>
                    <th align="center">Venta</th>
                    <th align="center">Ejecutivo</th>
                    <th align="center">Ramo</th>
                    <th align="center">Estado</th>
                    <th align="center">Archivo</th>
                    <th align="center">Informe</th>
                    <th align="center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inspections as $ins)
                <tr>
                    <td align="center">{{$ins->id}}</td>
                    <td align="center">{{$ins->dateCreated}}</td>
                    <td align="center"> <a href="#" onclick="salesResumeTable({{$ins->salesId}})">{{$ins->salesId}}</a> </td>
                    <td align="center"> {{$ins->ejecutivo_ss}} </td>
                    @if($ins->proSegment == 'MULTIRIESGO')
                        <td align="center">CASA HABITACIÓN</td>
                    @else
                        <td align="center">{{$ins->proSegment}}</td>
                    @endif
                    <td align="center">{{$ins->staName}}</td>
                    <td align="center">
                        @if($ins->ramoId == 1 || $ins->ramoId == 2)
                            @if($ins->urlViamatica != null)
                                <a href="{{$ins->urlViamatica}}" target="blank"><img alt="PDF" src="{{asset('images/pdf.png')}}"></a>
                            @endif
                        @endif
                    </td>
                    @if($ins->file != null)
                        <!--<td align="center"><a href="{{$ins->file}}" target="blank"><span class="glyphicon glyphicon-download-alt" style="color:black;font-size:19px;"></span></a></td>-->
                        <td align="center"><a href="{{$ins->file}}" target="blank"><img alt="PDF" src="{{asset('images/pdf.png')}}"></a></td>
                    @else
                        <td align="center"></td>
                    @endif
                    @if($edit)
                        <td align="center">
                                @if($ins->proSegment == 'VEHÍCULOS')
                                    @if($ins->inspector_updated == null)
                                        <a href="#" onclick="vehiForm('{{$ins->id}}')" data-toggle="tooltip" title="Subir Inspección"><span class="glyphicon glyphicon-th-list" style="color:green;font-size:19px;margin-left:5px;"></span></a>
                                        <a href="#" data-toggle="tooltip" title="Subir Inspección"><span class="glyphicon glyphicon-upload" style="color:black;font-size:19px;margin-left:5px;"></span></a>
                                        <a href="#" data-toggle="tooltip" title="Confirmar"><span class="glyphicon glyphicon-ok" style="color:black;font-size:19px;margin-left:5px;"></span></a>
                                    @else
                                        @if($ins->staName == 'Pendiente Inspección')
                                            <a href="#" data-toggle="tooltip" title="Subir Inspección"><span class="glyphicon glyphicon-th-list" style="color:black;font-size:19px;margin-left:5px;"></span></a>
                                            <a href="#" onclick="fileUpload('{{$ins->id}}')" data-toggle="tooltip" title="Subir Inspección"><span class="glyphicon glyphicon-upload" style="color:green;font-size:19px;margin-left:5px;"></span></a>
                                            <a href="#" onclick="confirmInspection('{{$ins->id}}')" data-toggle="tooltip" title="Confirmar"><span class="glyphicon glyphicon-ok" style="color:green;font-size:19px;margin-left:5px;"></span></a>
                                        @else
                                            <a href="#" data-toggle="tooltip" title="Subir Inspección"><span class="glyphicon glyphicon-th-list" style="color:red;font-size:19px;margin-left:5px;"></span></a>
                                            <a href="#" data-toggle="tooltip" title="Subir Inspección"><span class="glyphicon glyphicon-upload" style="color:red;font-size:19px;margin-left:5px;"></span></a>
                                            <a href="#" data-toggle="tooltip" title="Confirmar"><span class="glyphicon glyphicon-ok" style="color:red;font-size:19px;margin-left:5px;"></span></a>
                                        @endif
                                    @endif
                                @elseif($ins->ramoId == 4)
                                @elseif($ins->ramoId == 1 || $ins->ramoId == 2)
                                    @if(inspectionValidateR2($ins->id) == 'false')
                                    @else
                                        @if($ins->staName == 'Pendiente Inspección')
                                            <a href="#" onclick="fileUpload('{{$ins->id}}')" data-toggle="tooltip" title="Subir Inspección"><span class="glyphicon glyphicon-upload" style="color:black;font-size:19px;margin-left:5px;"></span></a>
                                            <a href="#" onclick="confirmInspection('{{$ins->id}}')" data-toggle="tooltip" title="Confirmar"><span class="glyphicon glyphicon-ok" style="color:black;font-size:19px;margin-left:5px;"></span></a>
                                        @else
                                            <a href="#" data-toggle="tooltip" title="Subir Inspección"><span class="glyphicon glyphicon-upload" style="color:red;font-size:19px;margin-left:5px;"></span></a>
                                            <a href="#" data-toggle="tooltip" title="Confirmar"><span class="glyphicon glyphicon-ok" style="color:red;font-size:19px;margin-left:5px;"></span></a>
                                        @endif
                                    @endif
                                @else    
                                    @if($ins->staName == 'Pendiente Inspección')
                                        <a href="#" onclick="fileUpload('{{$ins->id}}')" data-toggle="tooltip" title="Subir Inspección"><span class="glyphicon glyphicon-upload" style="color:black;font-size:19px;margin-left:5px;"></span></a>
                                        <a href="#" onclick="confirmInspection('{{$ins->id}}')" data-toggle="tooltip" title="Confirmar"><span class="glyphicon glyphicon-ok" style="color:black;font-size:19px;margin-left:5px;"></span></a>
                                    @else
                                        <a href="#" data-toggle="tooltip" title="Subir Inspección"><span class="glyphicon glyphicon-upload" style="color:red;font-size:19px;margin-left:5px;"></span></a>
                                        <a href="#" data-toggle="tooltip" title="Confirmar"><span class="glyphicon glyphicon-ok" style="color:red;font-size:19px;margin-left:5px;"></span></a>
                                    @endif
                                @endif
                        </td>
                    @else
                        <td align="center"></td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
        <div id="tableUsers_paginate" class="dataTables_paginate paging_simple_numbers paginationsUsersMargin" style="display:inline">
            <p>Mostrando {{count($inspections)}} resultados de {{ $inspections->total() }} totales</p>
            <span style="float:right;margin-top:-45px; padding:0">
                {{ $inspections->links('pagination::bootstrap-4') }}                        
            </span>
        </div>
    </div>
</div>
