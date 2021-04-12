<div class="col-md-12 border" style="margin-top:10px">
    <div id="tableDiv" class="" >
        <table id="newPaginatedTable" class="table table-striped row-border table-responsive hover stripe borderTable">
            <thead>
                <tr>
                    <th align="center">N°</th>
                    <th align="center">Placa</th>
                    <th align="center">Beneficio</th>
                    <th align="center">Fecha</th>
                    <th align="center">Cliente</th>
                    <th align="center">Teléfono</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $benefits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $benefit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td align="center"><?php echo e($benefit->id); ?></td>
                    <td align="center"><?php echo e($benefit->plate); ?></td>
                    <td align="center"><?php echo e($benefit->name); ?></td>
                    <td align="center"><?php echo e($benefit->date); ?></td>
                    <td align="center"><?php echo e($benefit->customer); ?></td>
                    <td align="center"><?php echo e($benefit->phone); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <div id="tableUsers_paginate" class="dataTables_paginate paging_simple_numbers paginationsUsersMargin" style="display:inline">
            <p>Mostrando <?php echo e(count($benefits)); ?> resultados de <?php echo e($benefits->total()); ?> totales</p>
            <span style="float:right;margin-top:-45px; padding:0">
                <?php echo e($benefits->links('pagination::bootstrap-4')); ?>                        
            </span>
        </div>
    </div>
</div>
<?php /**PATH C:\wamp64\www\magnussucre\resources\views\pagination\benefits_secondary.blade.php ENDPATH**/ ?>