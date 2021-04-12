<div class="col-md-12 border" style="margin-top:10px">
    <div id="tableDiv">
        <table id="newPaginatedTable" class="table table-striped row-border table-responsive hover stripe borderTable">
            <thead>
                <tr>
                    <th align="center">ID</th>
                    <th align="center">Nombre Agencia</th>
                    <th align="center">Producto</th>
                    <th align="center">Agente</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $agencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td align="center"><?php echo e($agen->agenId); ?></td>
                    <td align="center"><?php echo e($agen->agenName); ?></td>
                    <td align="center"><?php echo e($agen->proName); ?></td>
                    <td align="center"><?php echo e($agen->agenteName); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <div id="tableUsers_paginate" class="dataTables_paginate paging_simple_numbers paginationsUsersMargin" style="display:inline">
            <p>Mostrando <?php echo e(count($agencies)); ?> resultados de <?php echo e($agencies->total()); ?> totales</p>
            <span style="float:right;margin-top:-45px; padding:0">
                <?php echo e($agencies->links('pagination::bootstrap-4')); ?>                        
            </span>
        </div>
    </div>
</div><?php /**PATH C:\wamp64\www\magnussucre\resources\views\pagination\agencies.blade.php ENDPATH**/ ?>