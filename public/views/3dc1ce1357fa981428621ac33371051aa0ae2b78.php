<div class="col-md-12 border" style="margin-top:10px">
    <div id="tableDiv">
        <table id="newPaginatedTable" class="table table-striped row-border table-responsive hover stripe borderTable">
            <thead>
                <tr>
                    <th align="center">Id</th>
                    <th align="center">Trans</th>
                    <th align="center">Identificacion</th>
                    <th align="center">Cliente</th>
                    <th align="center">Plan</th>
                    <th align="center">Valor Mensual</th>
                    <th align="center">Fecha</th>
                    <th align="center">Estado</th>
                    <th align="center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $insurances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ins): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td align="center"><?php echo e($ins->id); ?></td>
                    <td align="center"><?php echo e($ins->transaction_code); ?></td>
                    <td align="center"><?php echo e($ins->cusDocument); ?></td>
                    <td align="center"><?php echo e($ins->customer); ?></td>
                    <td align="center"><?php echo e($ins->name); ?></td>
                    <td align="center">$<?php echo e($ins->value_month); ?></td>
                    <td align="center"><?php echo e($ins->created_at); ?></td>
                    <td align="center"><?php echo e($ins->status); ?></td>
                    <td align="center">
                        <?php if($ins->status == 'Pendiente'): ?>
                            <a href="#" onclick="validateCode(<?php echo e($ins->id); ?>)"> 
                                <span class="glyphicon glyphicon-envelope" style="color:black;font-size:19px;"></span>
                            </a>
                        <?php else: ?>
                            <span class=" no-drop glyphicon glyphicon-envelope" style="color:black;font-size:19px;"></span>
                        <?php endif; ?>
                        <?php if($ins->status == 'Activo'): ?>
                            <a href="#" onclick="cancelInsurance(<?php echo e($ins->id); ?>)"> 
                                <span class="glyphicon glyphicon-remove" style="color:red;font-size:19px;"></span>
                            </a>
                        <?php else: ?>
                            <span class=" no-drop glyphicon glyphicon-remove" style="color:red;font-size:19px;"></span>
                        <?php endif; ?>
                    </td>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <div id="tableUsers_paginate" class="dataTables_paginate paging_simple_numbers paginationsUsersMargin" style="display:inline">
            <p>Mostrando <?php echo e(count($insurances)); ?> resultados de <?php echo e($insurances->total()); ?> totales</p>
            <span style="float:right;margin-top:-45px; padding:0">
                <?php echo e($insurances->links('pagination::bootstrap-4')); ?>                        
            </span>
        </div>
    </div>
</div><?php /**PATH C:\wamp64\www\magnussucre\resources\views\pagination\insurance.blade.php ENDPATH**/ ?>