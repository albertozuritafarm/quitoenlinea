<div class="col-md-12 border" style="margin-top:10px">
    <div id="tableDiv" class="" >
        <table id="newPaginatedTable" class="table table-striped row-border table-responsive hover stripe borderTable">
            <thead>
                <tr>
                    <!--<th align="center">Todos</th>-->
                    <th align="center">N°</th>
                    <th align="center">Codigo</th>
                    <th align="center">Canal</th>
                    <th align="center">Beneficio</th>
                    <th align="center">Vigencia Hasta</th>
                    <th align="center">Vigencia Desde</th>
                    <th align="center">N° Usos</th>
                    <th align="center">Descuento</th>
                    <th align="center">Estado</th>
                    <th align="center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $benefits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $benefit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <!--<td align="center"><input type="checkbox" name="vehicle1" value=""></td>-->
                    <td align="center"><?php echo e($benefit->id); ?></td>
                    <td align="center"><?php echo e($benefit->code); ?></td>
                    <td align="center"><?php echo e($benefit->channel); ?></td>
                    <td align="center"><?php echo e($benefit->name); ?></td>
                    <td align="center"><?php echo e($benefit->beginDate); ?></td>
                    <td align="center"><?php echo e($benefit->endDate); ?></td>
                    <td align="center"><?php echo e($benefit->uses); ?></td>
                    <td align="center"><?php echo e($benefit->discount_percentage); ?>%</td>
                    <td align="center"><?php echo e($benefit->status); ?></td>
                    <td align="center">
                        <!--                                    <a href="#" title="Editar" onclick="editModal(<?php echo e($benefit->id); ?>)">
                                                                <span class="glyphicon glyphicon-pencil" style="color:green;font-size:19px;margin-left:5px"></span>
                                                            </a>-->
                        <?php if($benefit->status === "Cancelada" || !$cancel): ?>
                        <a href="#" title="cancelar" class="no-drop">
                            <span class="glyphicon glyphicon-remove" style="color:red;font-size:19px;margin-left:5px"></span>
                        </a>
                        <?php else: ?>
                        <a href="#" title="Cancelar" onclick="cancelModal(<?php echo e($benefit->id); ?>)">
                            <span class="glyphicon glyphicon-remove" style="color:red;font-size:19px;margin-left:5px"></span>
                        </a>
                        <?php endif; ?>
                    </td>
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
<?php /**PATH C:\wamp64\www\magnussucre\resources\views\pagination\benefits.blade.php ENDPATH**/ ?>