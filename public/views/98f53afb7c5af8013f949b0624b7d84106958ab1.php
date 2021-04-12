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
                        <?php $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
    <!--                        <?php if($sale->status_id == 21 && $sale->hasRenew == null): ?>
                            <td align="center"><input type="checkbox" name="saleId" class="checkSaleId" value="<?php echo e($sale->salesId); ?>"></td>
                            <?php else: ?>
                            <td align="center"><input type="checkbox" name="saleId" class="checkSaleId" value="<?php echo e($sale->salesId); ?>"></td>
                            <?php endif; ?>-->
                            <!--<td align="center"> <a href="#" data-toggle="modal" data-target="#saleModal<?php echo e($sale->salesId); ?>"><?php echo e($sale->salesId); ?></a> </td>-->
                            <td align="center"> <a href="#" onclick="salesResumeTable(<?php echo e($sale->salesId); ?>)"><?php echo e($sale->salesId); ?></a> </td>
                            <td align="center"><?php echo e($sale->poliza); ?></td>
                            <td align="center"><?php echo e($sale->document); ?></td>
                            <td align="center"><?php echo e($sale->customer); ?></td>
                            <td align="center"><?php echo e($sale->total); ?></td>
                            <td align="center"><?php echo e($sale->date); ?></td>
                            <td align="center"><?php echo e($sale->beginDate); ?> <br> <?php echo e($sale->endDate); ?></td>
                            <!--<td align="center"><?php echo e($sale->proName); ?></td>-->
                            <?php if($sale->proSegment == 'MULTIRIESGO' || $sale->proSegment == 'INCENDIO' ): ?>
                                <td align="center">CASA HABITACION</td>
                            <?php else: ?>
                                <td align="center"><?php echo e($sale->proSegment); ?></td>
                            <?php endif; ?>
                            <td align="center" id="statusTable<?php echo e($sale->salesId); ?>"><?php echo e($sale->status); ?></td>
                            <td align="center"><?php echo e($sale->movName); ?></td>
                            <td align="center">
                                    <!-- PDF COTIZACION -->
                                    <a href="<?php echo e(asset('')); ?>sales/pdf/<?php echo e(Crypt::encrypt($sale->salesId)); ?>" target="_blank" data-toggle="tooltip" title="ver PDF">
                                        <img src="<?php echo e(asset('/images/pdf.png')); ?>" height="20" width="20" style="margin-top: -10px;margin-left:-10px">
                                    </a>
                             <!-- VEHICULOS -->  <!-- INCENDIO -->
                            <?php if($edit): ?>
                                <?php if(in_array($sale->proRamoid, array(7, 5, 40))): ?> 
                                         <!--FORMULARIO DE VINCULACION-->  
                                        <?php if(in_array($sale->status_id, array(20))): ?>
                                            <a onclick="validateListaObservadosyCartera('<?php echo e($sale->salesId); ?>','<?php echo e(asset('')); ?>sales/form/<?php echo e(Crypt::encrypt($sale->salesId)); ?>')" href="#" data-toggle="tooltip" title="Formulario de Vinculación">
                                                <span class="glyphicon glyphicon-list-alt" style="color:black;font-size:19px;margin-left:3px;margin-right:3px"></span>                          
                                            </a>
                                         <?php elseif(in_array($sale->status_id, array(23,25))): ?>
                                            <a onclick="validateListaObservadosyCartera('<?php echo e($sale->salesId); ?>','<?php echo e(asset('')); ?>sales/form/<?php echo e(Crypt::encrypt($sale->salesId)); ?>')" href="#" data-toggle="tooltip" title="Formulario de Vinculación">
                                                <span class="glyphicon glyphicon-list-alt" style="color:green;font-size:19px;margin-left:3px;margin-right:3px"></span>                          
                                            </a>
                                         <?php else: ?>
                                            <a href="#" data-toggle="tooltip" title="Formulario de Vinculación">
                                                <span class="glyphicon glyphicon-list-alt" style="color:red;font-size:19px;margin-left:3px;margin-right:3px"></span>                          
                                            </a>
                                         <?php endif; ?>
                                        <!--INSPECCION--> 
                                        <?php if(in_array($sale->status_id, array(22))): ?>
                                            <a href="#" data-toggle="tooltip" title="Realizar Inspección">
                                                <?php if($edit): ?>
                                                    <span id="inspection(<?php echo e($sale->salesId); ?>)" onclick="inspection('<?php echo e($sale->salesId); ?>')" class="glyphicon glyphicon-search" style="color:green;font-size:19px;"></span>
                                                <?php else: ?>
                                                    <span onclick="" class="glyphicon glyphicon-search" style="color:red;font-size:19px;margin-left:3px;margin-right:3px"></span>
                                                <?php endif; ?>
                                            </a>
                                        <?php elseif(in_array($sale->status_id, array(29))): ?>
                                            <a href="#" data-toggle="tooltip" title="Realizar Inspección">
                                                <span id="inspection(<?php echo e($sale->salesId); ?>)" onclick="inspection('<?php echo e($sale->salesId); ?>')" class="glyphicon glyphicon-search" style="color:black;font-size:19px;"></span>
                                            </a>
                                        <?php else: ?>
                                            <a href="#" data-toggle="tooltip" title="Realizar Inspección">
                                                <span onclick="" class="glyphicon glyphicon-search" style="color:red;font-size:19px;margin-left:3px;margin-right:3px"></span>
                                            </a>
                                        <?php endif; ?>
                                        <?php if(in_array($sale->status_id, array(27, 12))): ?>
                                             <!--EMISIÓN--> 
                                            <a href="<?php echo e(asset('')); ?>sales/emit/<?php echo e(Crypt::encrypt($sale->salesId)); ?>/<?php echo e(Crypt::encrypt($sale->cusId)); ?>/0" data-toggle="tooltip" title="Realizar Emisión">
                                                <span class="glyphicon glyphicon-list" style="color:black;font-size:19px;margin-left:3px;margin-right:3px"></span>                          
                                            </a>
                                        <?php else: ?>
                                             <!--EMISIÓN--> 
                                            <a data-toggle="tooltip" title="Realizar Emisión">
                                                <span class="glyphicon glyphicon-list" style="color:red;font-size:19px;margin-left:3px;margin-right:3px"></span>                          
                                            </a>
                                        <?php endif; ?>
                                 <?php endif; ?>
                                    <!-- VIDA Y AP-->
                                    <?php if(in_array($sale->proRamoid, array(1,2,4))): ?>
                                        <!-- INSPECCION -->
                                        <?php if(in_array($sale->status_id, array(20))): ?>
                                            <span id="inspection(<?php echo e($sale->salesId); ?>)" onclick="inspection('<?php echo e($sale->salesId); ?>')"  class="glyphicon glyphicon-search" style="color:black;font-size:19px;margin-left:3px;margin-right:3px"></span>                       
                                        <?php elseif(in_array($sale->status_id, array(22))): ?>
                                            <span id="inspection(<?php echo e($sale->salesId); ?>)" onclick="inspection('<?php echo e($sale->salesId); ?>')"  class="glyphicon glyphicon-search" style="color:green;font-size:19px;margin-left:3px;margin-right:3px"></span>                       
                                        <?php else: ?>
                                            <span onclick="" class="glyphicon glyphicon-search" style="color:red;font-size:19px;margin-left:3px;margin-right:3px"></span>                       
                                        <?php endif; ?>

                                        <!-- FORMULARIO DE VINCULACION --> 

                                        <?php if(in_array($sale->status_id, array(27))): ?>
                                            <a href="<?php echo e(asset('')); ?>sales/form/<?php echo e(Crypt::encrypt($sale->salesId)); ?>" data-toggle="tooltip" title="Formulario de Vinculación">
                                                <span class="glyphicon glyphicon-list-alt" style="color:black;font-size:19px;margin-left:3px;margin-right:3px"></span>                          
                                            </a>                         
                                        <?php elseif(in_array($sale->status_id, array(23,25))): ?>
                                            <a href="<?php echo e(asset('')); ?>sales/form/<?php echo e(Crypt::encrypt($sale->salesId)); ?>" data-toggle="tooltip" title="Formulario de Vinculación">
                                                <span class="glyphicon glyphicon-list-alt" style="color:green;font-size:19px;margin-left:3px;margin-right:3px"></span>                          
                                            </a>                         
                                        <?php else: ?>
                                            <span class="glyphicon glyphicon-list-alt" style="color:red;font-size:19px;margin-left:3px;margin-right:3px"></span>     
                                        <?php endif; ?>
                                        <!-- EMISIÓN -->
                                        <?php if(in_array($sale->status_id, array( 12, 29))): ?>
                                            <a href="<?php echo e(asset('')); ?>sales/emit/<?php echo e(Crypt::encrypt($sale->salesId)); ?>/<?php echo e(Crypt::encrypt($sale->cusId)); ?>/0" data-toggle="tooltip" title="Realizar Emisión">
                                                <span class="glyphicon glyphicon-list" style="color:black;font-size:19px;margin-left:3px;margin-right:3px"></span>                          
                                            </a>
                                        <?php else: ?>
                                            <span class="glyphicon glyphicon-list" style="color:red;font-size:19px;margin-left:3px;margin-right:3px"></span>                          
                                        <?php endif; ?>
                                       <?php else: ?>
                                    <?php endif; ?>
                            <?php else: ?>
                            <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>

            </div>
            <div id="tableUsers_paginate" class="dataTables_paginate paging_simple_numbers paginationsUsersMargin" style="display:inline">
                <p>Mostrando <?php echo e(count($sales)); ?> resultados de <?php echo e($sales->total()); ?> totales</p>
                <span style="float:right;margin-top:-45px; padding:0">
                    <?php echo e($sales->links('pagination::bootstrap-4')); ?>                        
                </span>
            </div>
            <input type="hidden" name="currentPage" id="currentPage" value="<?php echo e($sales->currentPage()); ?>">
        </div>
    </div>

<?php /**PATH C:\wamp64\www\magnussucre\resources\views\pagination\individual.blade.php ENDPATH**/ ?>