<div class="col-md-12 border" style="margin-top:10px">
    <div id="tableDiv">
        <table id="newPaginatedTable" class="table table-striped row-border table-responsive hover stripe borderTable">
            <thead>
                <tr>
                    <th align="center">ID</th>
                    <th align="center">Nombre Canal</th>
                    <th align="center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $channels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cha): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td align="center"> <a href="#" onclick="channelResume(<?php echo e($cha->id); ?>)"><?php echo e($cha->canalnegoid); ?></a> </td>
                    <td align="center"><?php echo e($cha->canalnegodes); ?></td>
                    <td align="center"><a href="#" title="Ver Agencias" onclick="addAgencyIndividual(<?php echo e($cha->id); ?>)"><span class="glyphicon glyphicon-search" style="color:green"></span></a></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <div id="tableUsers_paginate" class="dataTables_paginate paging_simple_numbers paginationsUsersMargin" style="display:inline">
            <p>Mostrando <?php echo e(count($channels)); ?> resultados de <?php echo e($channels->total()); ?> totales</p>
            <span style="float:right;margin-top:-45px; padding:0">
                <?php echo e($channels->links('pagination::bootstrap-4')); ?>                        
            </span>
        </div>
    </div>
</div><?php /**PATH C:\wamp64\www\magnussucre\resources\views\pagination\channels.blade.php ENDPATH**/ ?>