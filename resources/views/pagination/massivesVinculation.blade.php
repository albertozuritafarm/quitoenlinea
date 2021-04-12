        <div class="col-md-12 border" style="margin-top:10px">
        <div id="tableDiv">
            <div  class="">
                <table id="newPaginatedTable" class="table table-responsive table-striped row-border hover stripe borderTable">
                    <thead>
                        <tr>
                            <!--<th align="center">Todos</th>-->
                            <th align="center">N. Form.</th>
                            <th align="center">Nombre Cliente</th>
                            <th align="center">Identificación</th>
                            <th align="center">Canal</th>
                            <th align="center">Fecha de Creación</th>
                            <th align="center">Fecha de Útima Actualización</th>
                            <th align="center">Estado</th>
                            <th align="center">Nombre Empresa</th>
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
                            @if($sale->customer == null)
                                <td align="center">{{$sale->rep}}</td>
                                <td align="center">{{$sale->documentRep}}</td>
                            @else
                                <td align="center">{{$sale->customer}}</td>
                                <td align="center">{{$sale->document}}</td>
                            @endif
                            <td align="center">{{$sale->canalnegodes}} </td>
                            <td align="center">{{$sale->beginDate}}</td>
                            <td align="center">{{$sale->updateDate}}</td>
                            <td align="center" id="statusTable{{$sale->salesId}}">{{$sale->status}}</td>
                            <td align="center">{{$sale->agentedes}} </td>
                            <td align="center">
                            @if($sale->vinculation_form != null)
                              <a align="center" href="{{$sale->vinculation_form}}" target="blank">
                                  <img src="{{asset('/images/pdf.png')}}" height="20" width="20" style="margin-top: -10px;margin-left:-10px" title="ver PDF">
                              </a>
                            @endif
                            @if($sale->customer == null)
                                @if(in_array($sale->status_id, array(37))||(in_array($sale->status_id, array(34))))
                                     <a href="#" data-toggle="tooltip" title="Formulario de Vinculación">
                                        <span class="glyphicon glyphicon-list-alt" style="color:red;font-size:18px;"></span>                          
                                    </a>  
                                @else                                
                                    <a align="center" href="{{asset('')}}massivesVinculation/legalPerson/form/{{Crypt::encrypt($sale->salesId)}}')" data-toggle="tooltip" title="Formulario de Vinculación">
                                        <span class="glyphicon glyphicon-list-alt" style="color:green;font-size:18px;"></span>                          
                                    </a>                            
                                @endif
                            @else
                                @if(in_array($sale->status_id, array(37))||(in_array($sale->status_id, array(34))))
                                     <a href="#" data-toggle="tooltip" title="Formulario de Vinculación">
                                         <span class="glyphicon glyphicon-list-alt" style="color:red;font-size:18px;"></span>                          
                                     </a>                         
                                @else
                                     <a align="center" href="{{asset('')}}massivesVinculation/form/{{Crypt::encrypt($sale->salesId)}}')" data-toggle="tooltip" title="Formulario de Vinculación">
                                        <span class="glyphicon glyphicon-list-alt" style="color:green;font-size:18px;"></span>                          
                                     </a>                           
                                @endif
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

