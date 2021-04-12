    <div class="col-md-12 border" style="margin-top:10px">

        <div id="tableDiv">
            <div  class="">
                <table id="newPaginatedTable" class="table table-responsive table-striped row-border hover stripe borderTable">
                    <thead>
                        <tr>
                            <!--<th align="center">Todos</th>-->
                            <th align="center">N°</th>
                            <th align="center">Poliza</th>
                            <th align="center">Documento</th>
                            <th align="center">Cliente</th>
                            <th align="center">Valor</th>
                            <th align="center">F. Cotización</th>
                            <th align="center">F. Vigencia</th>
                            <!--<th align="center">Producto</th>-->
                            <th align="center">Ramo</th>
                            <th align="center">Estado</th>
                            <th align="center">Movimiento</th>
                            <th align="center" class="col-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $sale)
                        <tr>
    <!--                        @if($sale->status_id == 21 && $sale->hasRenew == null)
                            <td align="center"><input type="checkbox" name="saleId" class="checkSaleId" value="{{$sale->salesId}}"></td>
                            @else
                            <td align="center"><input type="checkbox" name="saleId" class="checkSaleId" value="{{$sale->salesId}}"></td>
                            @endif-->
                            <!--<td align="center"> <a href="#" data-toggle="modal" data-target="#saleModal{{$sale->salesId}}">{{$sale->salesId}}</a> </td>-->
                            <td align="center"> <a href="#" onclick="salesResumeTable({{$sale->salesId}})">{{$sale->salesId}}</a> </td>
                            <td align="center">{{$sale->poliza}}</td>
                            <td align="center">{{$sale->document}}</td>
                            <td align="center">{{$sale->customer}}</td>
                            <td align="center">{{$sale->total}}</td>
                            <td align="center">{{$sale->date}}</td>
                            <td align="center">{{$sale->beginDate}} <br> {{$sale->endDate}}</td>
                            <!--<td align="center">{{$sale->proName}}</td>-->
                            @if($sale->proSegment == 'MULTIRIESGO' || $sale->proSegment == 'INCENDIO' )
                                <td align="center">CASA HABITACION</td>
                            @else
                                <td align="center">{{$sale->proSegment}}</td>
                            @endif
                            <td align="center" id="statusTable{{$sale->salesId}}">{{$sale->status}}</td>
                            <td align="center">{{$sale->movName}}</td>
                            <td align="center">
                                    <!-- PDF COTIZACION -->
                                    <a href="{{asset('')}}sales/pdf/{{Crypt::encrypt($sale->salesId)}}" target="_blank" data-toggle="tooltip" title="ver PDF">
                                        <img src="{{asset('/images/pdf.png')}}" height="20" width="20" style="margin-top: -10px;margin-left:-10px">
                                    </a>
                             <!-- VEHICULOS -->  <!-- INCENDIO -->
                            @if($edit)
                                @if(in_array($sale->proRamoid, array(7, 5, 40))) 
                                         <!--FORMULARIO DE VINCULACION-->  
                                        @if(in_array($sale->status_id, array(20)))
                                            <a onclick="validateListaObservadosyCartera('{{$sale->salesId}}','{{asset('')}}sales/form/{{Crypt::encrypt($sale->salesId)}}')" href="#" data-toggle="tooltip" title="Formulario de Vinculación">
                                                <span class="glyphicon glyphicon-list-alt" style="color:black;font-size:19px;margin-left:3px;margin-right:3px"></span>                          
                                            </a>
                                         @elseif(in_array($sale->status_id, array(23,25)))
                                            <a onclick="validateListaObservadosyCartera('{{$sale->salesId}}','{{asset('')}}sales/form/{{Crypt::encrypt($sale->salesId)}}')" href="#" data-toggle="tooltip" title="Formulario de Vinculación">
                                                <span class="glyphicon glyphicon-list-alt" style="color:green;font-size:19px;margin-left:3px;margin-right:3px"></span>                          
                                            </a>
                                         @else
                                            <a href="#" data-toggle="tooltip" title="Formulario de Vinculación">
                                                <span class="glyphicon glyphicon-list-alt" style="color:red;font-size:19px;margin-left:3px;margin-right:3px"></span>                          
                                            </a>
                                         @endif
                                        <!--INSPECCION--> 
                                        @if(in_array($sale->status_id, array(22)))
                                            <a href="#" data-toggle="tooltip" title="Realizar Inspección">
                                                @if($edit)
                                                    <span id="inspection({{$sale->salesId}})" onclick="inspection('{{$sale->salesId}}')" class="glyphicon glyphicon-search" style="color:green;font-size:19px;"></span>
                                                @else
                                                    <span onclick="" class="glyphicon glyphicon-search" style="color:red;font-size:19px;margin-left:3px;margin-right:3px"></span>
                                                @endif
                                            </a>
                                        @elseif(in_array($sale->status_id, array(29)))
                                            <a href="#" data-toggle="tooltip" title="Realizar Inspección">
                                                <span id="inspection({{$sale->salesId}})" onclick="inspection('{{$sale->salesId}}')" class="glyphicon glyphicon-search" style="color:black;font-size:19px;"></span>
                                            </a>
                                        @else
                                            <a href="#" data-toggle="tooltip" title="Realizar Inspección">
                                                <span onclick="" class="glyphicon glyphicon-search" style="color:red;font-size:19px;margin-left:3px;margin-right:3px"></span>
                                            </a>
                                        @endif
                                        @if(in_array($sale->status_id, array(27, 12)))
                                             <!--EMISIÓN--> 
                                            <a href="{{asset('')}}sales/emit/{{Crypt::encrypt($sale->salesId)}}/{{Crypt::encrypt($sale->cusId)}}/0" data-toggle="tooltip" title="Realizar Emisión">
                                                <span class="glyphicon glyphicon-list" style="color:black;font-size:19px;margin-left:3px;margin-right:3px"></span>                          
                                            </a>
                                        @else
                                             <!--EMISIÓN--> 
                                            <a data-toggle="tooltip" title="Realizar Emisión">
                                                <span class="glyphicon glyphicon-list" style="color:red;font-size:19px;margin-left:3px;margin-right:3px"></span>                          
                                            </a>
                                        @endif
                                 @endif
                                    <!-- VIDA Y AP-->
                                    @if(in_array($sale->proRamoid, array(1,2,4)))
                                        <!-- INSPECCION -->
                                        @if(in_array($sale->status_id, array(20)))
                                            <span id="inspection({{$sale->salesId}})" onclick="inspection('{{$sale->salesId}}')"  class="glyphicon glyphicon-search" style="color:black;font-size:19px;margin-left:3px;margin-right:3px"></span>                       
                                        @elseif(in_array($sale->status_id, array(22)))
                                            <span id="inspection({{$sale->salesId}})" onclick="inspection('{{$sale->salesId}}')"  class="glyphicon glyphicon-search" style="color:green;font-size:19px;margin-left:3px;margin-right:3px"></span>                       
                                        @else
                                            <span onclick="" class="glyphicon glyphicon-search" style="color:red;font-size:19px;margin-left:3px;margin-right:3px"></span>                       
                                        @endif

                                        <!-- FORMULARIO DE VINCULACION --> 

                                        @if(in_array($sale->status_id, array(27)))
                                            <a href="{{asset('')}}sales/form/{{Crypt::encrypt($sale->salesId)}}" data-toggle="tooltip" title="Formulario de Vinculación">
                                                <span class="glyphicon glyphicon-list-alt" style="color:black;font-size:19px;margin-left:3px;margin-right:3px"></span>                          
                                            </a>                         
                                        @elseif(in_array($sale->status_id, array(23,25)))
                                            <a href="{{asset('')}}sales/form/{{Crypt::encrypt($sale->salesId)}}" data-toggle="tooltip" title="Formulario de Vinculación">
                                                <span class="glyphicon glyphicon-list-alt" style="color:green;font-size:19px;margin-left:3px;margin-right:3px"></span>                          
                                            </a>                         
                                        @else
                                            <span class="glyphicon glyphicon-list-alt" style="color:red;font-size:19px;margin-left:3px;margin-right:3px"></span>     
                                        @endif
                                        <!-- EMISIÓN -->
                                        @if(in_array($sale->status_id, array( 12, 29)))
                                            <a href="{{asset('')}}sales/emit/{{Crypt::encrypt($sale->salesId)}}/{{Crypt::encrypt($sale->cusId)}}/0" data-toggle="tooltip" title="Realizar Emisión">
                                                <span class="glyphicon glyphicon-list" style="color:black;font-size:19px;margin-left:3px;margin-right:3px"></span>                          
                                            </a>
                                        @else
                                            <span class="glyphicon glyphicon-list" style="color:red;font-size:19px;margin-left:3px;margin-right:3px"></span>                          
                                        @endif
                                       @else
                                    @endif
                            @else
                            @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
            <div id="tableUsers_paginate" class="dataTables_paginate paging_simple_numbers paginationsUsersMargin" style="display:inline">
                <p>Mostrando {{count($sales)}} resultados de {{ $sales->total() }} totales</p>
                <span style="float:right;margin-top:-45px; padding:0">
                    {{ $sales->links('pagination::bootstrap-4') }}                        
                </span>
            </div>
            <input type="hidden" name="currentPage" id="currentPage" value="{{$sales->currentPage()}}">
        </div>
    </div>

