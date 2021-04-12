
<div class="col-md-12 border" style="margin-top:10px">
    <div id="tableDiv" class="">
        <table id="newPaginatedTable" class="table table-striped row-border table-responsive hover stripe borderTable">
            <thead>
                <tr>
                    <th align="center">Nombre</th>
                    <th align="center">Apellido</th>
                    <th align="center">Correo</th>
                    <th align="center">Documento</th>
                    <th align="center">Tipo Documento</th>
                    <th align="center">Canal</th>
                    <th align="center">Estado</th>
                    <th align="center">Tipo</th>
                    <th align="center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td align="left"><?php echo e($user->first_name); ?></td>
                    <td align="left"><?php echo e($user->last_name); ?></td>
                    <td align="left"><?php echo e($user->email); ?></td>
                    <td align="left"><?php echo e($user->document); ?></td>
                    <td align="center"><?php echo e($user->documento); ?></td>
                    <td align="center"><?php echo e($user->channel); ?></td>
                    <td align="center"><?php echo e($user->estado); ?></td>
                    <td align="center"><?php echo e($user->typUser); ?></td>
                    <td align="center">
                        <?php if($edit): ?>
                            <a onclick="openModal(<?php echo e($user->id); ?>)" title="Cambiar Contraseña">
                                <span class="glyphicon glyphicon-lock" style="color:black;font-size:14px">&ensp;</span>                                     
                            </a>
                            <a href="<?php echo e(asset('')); ?>user/update/<?php echo e(Crypt::encrypt($user->id)); ?>" data-toggle="tooltip" title="Actualizar">
                                <span class="glyphicon glyphicon-pencil" style="color:#139819;font-size:14px">&ensp;</span>
                            </a>
                        <?php else: ?>
                            <a onclick="#" title="Cambiar Contraseña" disabled="disabled" style="cursor: not-allowed;">
                                <span class="glyphicon glyphicon-lock" style="color:black;font-size:14px" disabled="disabled">&ensp;</span>                                     
                            </a>
                            <a href="#" data-toggle="tooltip" title="Actualizar" disabled="disabled" style="cursor: not-allowed;">
                                <span class="glyphicon glyphicon-pencil" style="color:#139819;font-size:14px">&ensp;</span>
                            </a>
                        <?php endif; ?>
                        <?php if($cancel): ?>
                            <?php if($user->status_id == 1): ?>
                            <!--<a href="/user/inactive/<?php echo e(Crypt::encrypt($user->id)); ?>" data-toggle="tooltip" title="Inactivar Usuario" onclick="confirmInactivate()">-->
                            <a href="#"data-toggle="tooltip" title="Inactivar Usuario" onclick="confirmInactivate('/user/inactive/<?php echo e(Crypt::encrypt($user->id)); ?> ' )">
                                <span class="glyphicon glyphicon-remove" style="color:#fc2d2d;font-size:14px"></span>                          
                            </a>
                            <?php else: ?>
                            <!--<a href="/user/inactive/<?php echo e(Crypt::encrypt($user->id)); ?>" data-toggle="tooltip" title="Activar Usuario" onclick="confirmActivate()">-->
                            <a href="#" data-toggle="tooltip" title="Activar Usuario" onclick="confirmActivate('/user/inactive/<?php echo e(Crypt::encrypt($user->id)); ?> ' )">
                                <span class="glyphicon glyphicon-ok" style="color:#fc2d2d;font-size:14px"></span>                          
                            </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="#"data-toggle="tooltip" title="Inactivar Usuario" disabled="disabled" style="cursor: not-allowed;">
                                <span class="glyphicon glyphicon-remove" style="color:#fc2d2d;font-size:14px"></span>                          
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <div id="tableUsers_paginate" class="dataTables_paginate paging_simple_numbers paginationsUsersMargin" style="display:inline">
            <p>Mostrando <?php echo e(count($users)); ?> resultados de <?php echo e($users->total()); ?> totales</p>
            <span style="float:right;margin-top:-45px; padding:0">
                <?php echo e($users->links('pagination::bootstrap-4')); ?>                        
            </span>
        </div>
    </div>
</div><?php /**PATH C:\wamp64\www\magnussucre\resources\views\pagination\users.blade.php ENDPATH**/ ?>