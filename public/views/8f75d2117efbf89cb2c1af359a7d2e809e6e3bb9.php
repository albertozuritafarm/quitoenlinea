<div class="col-md-12 border" style="margin-top:10px">
    <div id="tableDiv">
        <table id="newPaginatedTable" class="table table-striped row-border table-responsive hover stripe borderTable">
            <thead>
                <tr>
                    <th align="center">Documento</th>
                    <th align="center">Tipo Documento</th>
                    <th align="center">Nombres</th>
                    <th align="center">Direccion</th>
                    <th align="center">Ciudad</th>
                    <th align="center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cus): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td align="center"> <a href="#" onclick="customerResume(<?php echo e($cus->id); ?>)"><?php echo e($cus->document); ?></a> </td>
                    <td align="center"><?php echo e($cus->docName); ?></td>
                    <td align="center"><?php echo e($cus->first_name); ?> <?php echo e($cus->last_name); ?></td>
                    <td align="center"><?php echo e($cus->address); ?></td>
                    <td align="center"><?php echo e($cus->citName); ?></td>
                    <td align="center">
                        <?php if($edit): ?>
                            <a href="#" onclick="editCustomer(<?php echo e($cus->id); ?>)" data-toggle="tooltip" title="Editar"><span class="glyphicon glyphicon-pencil" style="color:green;font-size:14px"></span></a>
                        <?php else: ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <div id="tableUsers_paginate" class="dataTables_paginate paging_simple_numbers paginationsUsersMargin" style="display:inline">
            <p>Mostrando <?php echo e(count($customers)); ?> resultados de <?php echo e($customers->total()); ?> totales</p>
            <span style="float:right;margin-top:-45px; padding:0">
                <?php echo e($customers->links('pagination::bootstrap-4')); ?>                        
            </span>
        </div>
    </div>
</div><?php /**PATH C:\wamp64\www\magnussucre\resources\views\pagination\customer.blade.php ENDPATH**/ ?>