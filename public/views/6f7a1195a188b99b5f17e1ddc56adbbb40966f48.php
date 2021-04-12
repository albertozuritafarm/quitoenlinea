<div class="col-md-12 border" style="margin-top:10px">
    <div id="tableDiv" class="" >
        <table id="newPaginatedTable" class="table table-striped row-border table-responsive hover stripe borderTable">
            <thead>
                <tr>
                    <th align="center">ID Servicio</th>
                    <th align="center">ID Agendamiento</th>
                    <th align="center">Tipo de Golpe</th>
                    <th align="center">Fecha Inicial</th>
                    <th align="center">Fecha Fin</th>
                    <th align="center">Archivo</th>
                    <th align="center">Estado</th>
                    <th align="center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $newSchedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sche): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td align="center"><a id="schedulingModalResume<?php echo e($sche -> detaId); ?>" href="#" title="Realizar un Pago" onclick="schedulingModalResume(<?php echo e($sche -> detaId); ?>)"><?php echo e($sche -> detaId); ?></a></td>
                    <td align="center"><?php echo e($sche -> scheId); ?></td>
                    <td align="center"><?php echo e($sche -> damage); ?></td>
                    <td align="center"><?php echo e($sche -> beginDate); ?></td>
                    <td align="center"><?php echo e($sche -> endDate); ?></td>
                    <?php if($sche->file == null): ?>
                    <td align="center"></td>
                    <?php else: ?>
                    <td align="center"><a href="<?php echo e($sche -> file); ?>"><span class="glyphicon glyphicon-download-alt" style="color:black;font-size:18px"></span></a></td>
                    <?php endif; ?>
                    <td align="center"><?php echo e($sche -> statusName); ?></td>
                    <td align="center">
                        <?php if($sche->status == 3 && $edit): ?>
                        <a href="#" title="Reagendar" onclick="reschedule(<?php echo e($sche->detaId); ?>, <?php echo e($sche->time); ?>)"><span class="glyphicon glyphicon-screenshot" style="color:black;font-size:19px;margin-left:5px"></span></a>
                        <?php else: ?>
                        <a href="#"  title="Reagendar" class="no-drop"><span class="glyphicon glyphicon-screenshot" style="color:black;font-size:19px;margin-left:5px"></span></a>
                        <?php endif; ?>
                        <?php if($sche->status == 3 && $edit): ?>
                        <a id="confirmBtn<?php echo e($sche->detaId); ?>" href="#" data-toggle="tooltip" title="Confirmar" onclick="confirmAction(<?php echo e($sche->detaId); ?>)"><span class="glyphicon glyphicon-ok" style="color:green;font-size:19px;margin-left:5px"></span></a>
                        <?php else: ?>
                        <a href="#" data-toggle="tooltip" title="Confirmar" class="no-drop" disabled="disabled"><span class="glyphicon glyphicon-ok" style="color:green;font-size:19px;margin-left:5px"></span></a>
                        <?php endif; ?>
                        <?php if($sche->status == 3 && $cancel): ?>
                        <a href="#" data-toggle="tooltip" title="Cancelar" onclick="cancel(<?php echo e($sche->detaId); ?>)"><span class="glyphicon glyphicon-remove" style="color:red;font-size:19px;margin-left:5px"></span></a>
                        <?php else: ?>
                        <a href="#" data-toggle="tooltip" title="Cancelar" class="no-drop"><span class="glyphicon glyphicon-remove" style="color:red;font-size:19px;margin-left:5px"></span></a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <div id="tableUsers_paginate" class="dataTables_paginate paging_simple_numbers paginationsUsersMargin" style="display:inline">
            <p>Mostrando <?php echo e(count($newSchedules)); ?> resultados de <?php echo e($newSchedules->total()); ?> totales</p>
            <span style="float:right;margin-top:-45px; padding:0">
                <?php echo e($newSchedules->links('pagination::bootstrap-4')); ?>                        
            </span>
        </div>
    </div>
</div><?php /**PATH C:\wamp64\www\magnussucre\resources\views\pagination\scheduling.blade.php ENDPATH**/ ?>