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
                <?php $__currentLoopData = $charges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $charge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td align="center"><a id="paymentsModalResume<?php echo e($charge->salId); ?>" href="#" title="Realizar un Pago" onclick="paymentsModalResume(<?php echo e($charge->salId); ?>)"><?php echo e($charge->salId); ?></a></td>
                    <td align="center"><?php echo e($charge->document); ?></td>
                    <td align="center" id="dateTable<?php echo e($charge->id); ?>"><?php echo e($charge->date); ?></td>
                    <td align="center"><?php echo e($charge->value); ?></td>
                    <td align="center" id="statusTable<?php echo e($charge->id); ?>"><?php echo e($charge->status); ?></td>
                    <td align="center" id="typeTable<?php echo e($charge->id); ?>"><?php echo e($charge->type); ?></td>
                    <td align="center"><?php echo e($charge->typeCharge); ?></td>
                    <td align="center">
                        <input type="hidden" name="id" id="id" value="<?php echo e($charge->id); ?>">
                        <?php if($edit): ?>
                            <?php if($charge->cancel == 'true' && $charge->statusId == 21): ?>
                                <a href="<?php echo e(asset('')); ?>payments/refund/<?php echo e(Crypt::encrypt($charge->salId)); ?>" title="Anular un Pago">
                                    <span class="glyphicon glyphicon-minus-sign" style="color:black;font-size:19px">&ensp;</span>                                     
                                </a>
                            <?php else: ?>
                                <a class="no-drop" href="#" title="Anular un Pago">
                                    <span class="glyphicon glyphicon-minus-sign" style="color:red;font-size:19px">&ensp;</span>                                     
                                </a>
                            <?php endif; ?>
                        <?php else: ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <div id="tableUsers_paginate" class="dataTables_paginate paging_simple_numbers paginationsUsersMargin" style="display:inline">
            <p>Mostrando <?php echo e(count($charges)); ?> resultados de <?php echo e($charges->total()); ?> totales</p>
            <span style="float:right;margin-top:-45px; padding:0">
                <?php echo e($charges->links('pagination::bootstrap-4')); ?>                        
            </span>
        </div>
    </div>
</div><?php /**PATH C:\wamp64\www\magnussucre\resources\views\pagination\charges.blade.php ENDPATH**/ ?>