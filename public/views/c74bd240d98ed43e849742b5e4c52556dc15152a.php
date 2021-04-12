<div class="col-md-12 border" style="margin-top:10px">
    <div id="tableDiv">
        <table id="newPaginatedTable" class="table table-striped row-border table-responsive hover stripe borderTable">
            <thead>
                <tr>
                    <th align="center">Id</th>
                    <th align="center"># Trans (Id cart)</th>
                    <th align="center">Refer. Orden</th>
                    <th align="center">Fecha Orden</th>
                    <th align="center">ID Transacción</th>
                    <th align="center"># Lote</th>
                    <th align="center"># Ref</th>
                    <th align="center">AuthCode</th>
                    <th align="center">Cod. Adquirir.</th>
                    <th align="center">Tipo</th>
                    <th align="center">Respuesta</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $datafast; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td align="center"><?php echo e($dat->id); ?></a> </td>
                    <td align="center"><?php echo e($dat->id_cart); ?></a> </td>
                    <td align="center"><?php echo e($dat->order); ?></td>
                    <td align="center"><?php echo e($dat->order_date); ?></td>
                    <td align="center"><?php echo e($dat->id_transaction); ?></td>
                    <td align="center"><?php echo e($dat->lot); ?></td>
                    <td align="center"><?php echo e($dat->reference); ?></td>
                    <td align="center"><?php echo e($dat->auth_code); ?></td>
                    <td align="center"><?php echo e($dat->code); ?></td>
                    <td align="center"><?php echo e($dat->type); ?></td>
                    <td align="center"><?php echo e($dat->response); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <div id="tableUsers_paginate" class="dataTables_paginate paging_simple_numbers paginationsUsersMargin" style="display:inline">
            <p>Mostrando <?php echo e(count($datafast)); ?> resultados de <?php echo e($datafast->total()); ?> totales</p>
            <span style="float:right;margin-top:-45px; padding:0">
                <?php echo e($datafast->links('pagination::bootstrap-4')); ?>                        
            </span>
        </div>
    </div>
</div><?php /**PATH C:\wamp64\www\magnussucre\resources\views\pagination\datafast.blade.php ENDPATH**/ ?>