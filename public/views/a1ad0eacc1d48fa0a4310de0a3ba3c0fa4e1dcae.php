<div class="col-md-12 border" style="margin-top:10px">
    <div id="tableDiv" class="" >
        <table id="newPaginatedTable" class="table table-striped row-border table-responsive hover stripe borderTable">
            <thead>
                <tr>
                    <th align="center">ID</th>
                    <th align="center">Canal</th>
                    <th align="center">Cantidad Vehiculos</th>
                    <th align="center">Total</th>
                    <th align="center">Fecha</th>
                    <th align="center">Tipo</th>
                    <th align="center">Estado Venta</th>
                    <th align="center">Estado Cobro</th>
                    <th align="center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $massives; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $massive): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td align="center"><?php echo e($massive->id); ?></td>
                    <td align="center"><?php echo e($massive->canal); ?></td>
                    <td align="center"><?php echo e($massive->cantidad); ?></td>
                    <?php if($massive->tipo == 'Cancelación'): ?>
                    <td align="center">NA</td>
                    <?php else: ?>
                    <td align="center"><?php echo e($massive->total); ?></td>
                    <?php endif; ?>
                    <td align="center"><?php echo e($massive->fecha); ?></td>
                    <td align="center"><?php echo e($massive->tipo); ?></td>
                    <td align="center"><?php echo e($massive->estadoMasivo); ?></td>
                    <td id="statusCharge<?php echo e($massive->id); ?>" align="center"><?php echo e($massive->estadoCobro); ?></td>
                    <td align="center">
                        <a href="<?php echo e(asset('/massive/download/upload/file/')); ?>/<?php echo e($massive->id); ?>" target="blank" title="Descargar Archivo Cargado">
                            <i class="far fa-file-excel fa-2x" style="color:green;margin-right: 5px"></i>                        
                        </a>
                        <?php if($massive->estadoCobro == 'Pendiente Pago' && $massive->estadoMasivo == 'Activo'): ?>
                        <a href="#" data-toggle="tooltip" title="Confirmar Pago" onclick="payMassive(<?php echo e($massive->id); ?>)">
                        <?php else: ?>
                        <a href="#" data-toggle="tooltip" title="Confirmar Pago" class="no-drop">
                        <?php endif; ?>
                        <span class="glyphicon glyphicon-usd" style="color:black;font-size:20px;top:3px !important">&ensp;</span>
                        </a>
                    </td>
                </tr>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <div id="tableUsers_paginate" class="dataTables_paginate paging_simple_numbers paginationsUsersMargin" style="display:inline">
            <p>Mostrando <?php echo e(count($massives)); ?> resultados de <?php echo e($massives->total()); ?> totales</p>
            <span style="float:right;margin-top:-45px; padding:0">
                <?php echo e($massives->links('pagination::bootstrap-4')); ?>                        
            </span>
        </div>
    </div>
</div><?php /**PATH C:\wamp64\www\magnussucre\resources\views\pagination\massives.blade.php ENDPATH**/ ?>