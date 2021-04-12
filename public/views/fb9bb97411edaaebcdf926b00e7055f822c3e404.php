<div class="col-md-12 border" style="margin-top:10px">
    <div id="tableDiv" class="col-md-12" style="padding-left:0% !important;">
        <table id="newPaginatedTable" class="table table-striped row-border table-responsive hover stripe borderTable">
            <thead>
                <tr>
                    <th align="center">Seleccione</th>
                    <th align="center">ID Venta</th>
                    <th align="center">ID Masivo</th>
                    <th align="center">Tipo</th>
                    <th align="center">Canal</th>
                    <th align="center">Documento Cliente</th>
                    <th align="center">Nombre Cliente</th>
                    <th align="center">Valor Venta</th>
                    <th align="center">Fecha</th>
                    <th align="center">Estado Venta</th>
                    <th align="center">Estado Cobro</th>
                    <!--<th align="center">Acciones</th>-->
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $massives; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $massive): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <?php if($massive->salStatus == 'Cancelada'): ?>
                    <td align="center"><input type="checkbox" name="vehicle1" value="" disabled="disabled"></td>
                    <?php else: ?>
                    <td align="center"><input type="checkbox" name="vehicle1" value="<?php echo e($massive->salId); ?>"></td>
                    <?php endif; ?>
                    <td align="center"><a href="#" onclick="salesResumeTable(<?php echo e($massive->salId); ?>)"><?php echo e($massive->salId); ?></a> </td>
                    <td align="center"><?php echo e($massive->massId); ?></td>
                    <td align="center"><?php echo e($massive->tipo); ?></td>
                    <td align="center"><?php echo e($massive->chanName); ?></td>
                    <td align="center"><?php echo e($massive->cusDocument); ?></td>
                    <td align="center"><?php echo e($massive->cusName); ?></td>
                    <?php if($massive->tipo == 'Cancelación'): ?>
                    <td align="center">NA</td>
                    <?php else: ?>
                    <td align="center"><?php echo e($massive->salTotal); ?></td>
                    <?php endif; ?>
                    <td align="center"><?php echo e($massive->salDate); ?></td>
                    <td align="center"><?php echo e($massive->salStatus); ?></td>
                    <td align="center"><?php echo e($massive->massStatus); ?></td>
                    <!--<td align="center">-->
                        <?php if($massive->count == 0 && $massive->salStatus != 'Cancelada' && $edit): ?>
                        <!--<a href="#" id="modalVehi" onclick="modalVehi(<?php echo e($massive->salId); ?>)"><span class="glyphicon glyphicon-plus" style="color:black;font-size:20px;top:3px !important">&ensp;</span></a>-->
                        <?php else: ?>
                        <?php endif; ?>
                        <!--                                <?php if($massive->upload_file == null): ?>
                                                        <?php else: ?>
                                                        <a href="/massive/download/upload/file/<?php echo e($massive->massId); ?>" target="blank" title="Descargar Archivo Cargado">
                                                            <img src="<?php echo e(asset('/images/xls.png')); ?>" width="16px" height="16px">         
                                                            <i class="far fa-file-excel fa-2x" style="color:green;margin-right: 5px"></i>                        
                                                        </a>
                                                        <?php endif; ?>-->
                        <!--<?php if($massive->massStatus == 'Pendiente Pago' && $massive->salStatus == 'Activo' && $edit): ?>-->
                            <!--<a class="hidden" href="#" data-toggle="tooltip" title="Confirmar Pago" onclick="payMassive(<?php echo e($massive->massId); ?>)">-->
                        <?php else: ?>
                            <!--<a class="hidden" href="#" data-toggle="tooltip" title="Confirmar Pago" class="no-drop">-->
                        <?php endif; ?>
                                <!--<span class="glyphicon glyphicon-usd" style="color:black;font-size:20px;top:3px !important">&ensp;</span>-->
                            <!--</a>-->
                    <!--</td>-->
                </tr>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <div id="tableUsers_paginate" class="dataTables_paginate paging_simple_numbers paginationsUsersMargin" style="display:inline">
            <p>Mostrando <?php echo e(count($massives)); ?> resultados de <?php echo e($massives->total()); ?> totales</p>
            <span style="float:right;margin-top:-45px; padding:0">
                <?php echo e($massives->links('pagination::bootstrap-4')); ?>                        
            </span>
        </div>
    </div>
</div><?php /**PATH C:\wamp64\www\magnussucre\resources\views\pagination\massivesSecondary.blade.php ENDPATH**/ ?>