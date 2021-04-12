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
                        <?php $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
    <!--                        <?php if($sale->status_id == 21 && $sale->hasRenew == null): ?>
                            <td align="center"><input type="checkbox" name="saleId" class="checkSaleId" value="<?php echo e($sale->salesId); ?>"></td>
                            <?php else: ?>
                            <td align="center"><input type="checkbox" name="saleId" class="checkSaleId" value="<?php echo e($sale->salesId); ?>"></td>
                            <?php endif; ?>-->
                            <!--<td align="center"> <a href="#" data-toggle="modal" data-target="#saleModal<?php echo e($sale->salesId); ?>"><?php echo e($sale->salesId); ?></a> </td>-->
                            <td align="center"> <a href="#" onclick="salesResumeTable(<?php echo e($sale->salesId); ?>)"><?php echo e($sale->salesId); ?></a> </td>
                            <td align="center"><?php echo e($sale->customer); ?></td>
                            <td align="center"><?php echo e($sale->document); ?></td>
                            <td align="center"><?php echo e($sale->canalnegodes); ?> </td>
                            <td align="center"><?php echo e($sale->beginDate); ?></td>
                            <td align="center"><?php echo e($sale->endDate); ?></td>
                            <td align="center" id="statusTable<?php echo e($sale->salesId); ?>">
                                <?php if(in_array($sale->status_id, array(22,25))): ?>
                                    Formulario Firmado
                                <?php else: ?>
                                    <?php echo e($sale->status); ?>

                                <?php endif; ?>
                            </td>
                            <td align="center"><?php echo e($sale->agentedes); ?> </td>
                            <td align="center">
                            <?php if($sale->vinculation_form != null): ?>
                              <a align="center" href="<?php echo e($sale->vinculation_form); ?>" target="blank">
                                  <img src="<?php echo e(asset('/images/pdf.png')); ?>" height="20" width="20" style="margin-top: -10px;margin-left:-10px" title="ver PDF">
                              </a>
                            <?php endif; ?>
                            
                            <?php if(!in_array($sale->status_id, array(22,25))): ?>
                               <a align="center" href="<?php echo e(asset('')); ?>massivesVinculation/form/<?php echo e(Crypt::encrypt($sale->salesId)); ?>')" data-toggle="tooltip" title="Formulario de Vinculación">
                                                <span class="glyphicon glyphicon-list-alt" style="color:green;font-size:18px;"></span>                          
                                 </a>                                 
                            <?php else: ?>
                                <a href="#" data-toggle="tooltip" title="Formulario de Vinculación">
                                     <span class="glyphicon glyphicon-list-alt" style="color:red;font-size:18px;"></span>                          
                                </a>                               
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

<?php /**PATH C:\wamp64\www\magnussucre\resources\views\pagination\massivesVinculation.blade.php ENDPATH**/ ?>