<div class="col-md-12 border" style="margin-top:10px">
    <div id="tableDiv">
        <table id="newPaginatedTable" class="table table-striped row-border table-responsive hover stripe borderTable">
            <thead>
                <tr>
                    <th align="center" style="width: 5%">Numero</th>
                    <th align="center">Solicitante</th>
                    <th align="center">Asignado</th>
                    <th align="center" style="width: 5%">Fecha</th>
                    <th align="center" style="width: 5%">Estado</th>
                    <th align="center" style="width: 18%">Titulo</th>
                    <th align="center">Modulo</th>
                    <th align="center">Tipo Ticket</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td align="center"> <a href="<?php echo e(asset('')); ?>ticket/detail/<?php echo e($tic->id); ?>"><?php echo e($tic->id); ?></a> </td>
                    <td align="center"><?php echo e($tic->user); ?></td>
                    <td align="center"><?php echo e($tic->user2); ?></td>
                    <td align="center"><?php echo e($tic->beginDate); ?></td>
                    <td align="center"><?php echo e($tic->status); ?></td>
                    <td align="center"><?php echo e($tic->title); ?></td>
                    <td align="center"><?php echo e($tic->menuName); ?></td>
                    <td align="center"><?php echo e($tic->typeName); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <div id="tableUsers_paginate" class="dataTables_paginate paging_simple_numbers paginationsUsersMargin" style="display:inline">
            <p>Mostrando <?php echo e(count($tickets)); ?> resultados de <?php echo e($tickets->total()); ?> totales</p>
            <span style="float:right;margin-top:-45px; padding:0">
                <?php echo e($tickets->links('pagination::bootstrap-4')); ?>                        
            </span>
        </div>
    </div>
</div><?php /**PATH C:\wamp64\www\magnussucre\resources\views\pagination\tickets.blade.php ENDPATH**/ ?>