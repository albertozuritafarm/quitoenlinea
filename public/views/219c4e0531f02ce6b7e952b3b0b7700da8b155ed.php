<div class="col-md-12 border" style="margin-top:10px">
    <div id="tableDiv">
        <table id="newPaginatedTable" class="table table-striped row-border table-responsive hover stripe borderTable">
            <thead>
                <tr>
                    <th align="center">Id</th>
                    <th align="center">Fecha</th>
                    <th align="center">Venta</th>
                    <th align="center">Ejecutivo</th>
                    <th align="center">Ramo</th>
                    <th align="center">Estado</th>
                    <th align="center">Archivo</th>
                    <th align="center">Informe</th>
                    <th align="center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $inspections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ins): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td align="center"><?php echo e($ins->id); ?></td>
                    <td align="center"><?php echo e($ins->dateCreated); ?></td>
                    <td align="center"> <a href="#" onclick="salesResumeTable(<?php echo e($ins->salesId); ?>)"><?php echo e($ins->salesId); ?></a> </td>
                    <td align="center"> <?php echo e($ins->ejecutivo_ss); ?> </td>
                    <?php if($ins->proSegment == 'MULTIRIESGO'): ?>
                        <td align="center">CASA HABITACIÓN</td>
                    <?php else: ?>
                        <td align="center"><?php echo e($ins->proSegment); ?></td>
                    <?php endif; ?>
                    <td align="center"><?php echo e($ins->staName); ?></td>
                    <td align="center">
                        <?php if($ins->ramoId == 1 || $ins->ramoId == 2): ?>
                            <?php if($ins->urlViamatica != null): ?>
                                <a href="<?php echo e($ins->urlViamatica); ?>" target="blank"><img alt="PDF" src="<?php echo e(asset('images/pdf.png')); ?>"></a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                    <?php if($ins->file != null): ?>
                        <!--<td align="center"><a href="<?php echo e($ins->file); ?>" target="blank"><span class="glyphicon glyphicon-download-alt" style="color:black;font-size:19px;"></span></a></td>-->
                        <td align="center"><a href="<?php echo e($ins->file); ?>" target="blank"><img alt="PDF" src="<?php echo e(asset('images/pdf.png')); ?>"></a></td>
                    <?php else: ?>
                        <td align="center"></td>
                    <?php endif; ?>
                    <?php if($edit): ?>
                        <td align="center">
                                <?php if($ins->proSegment == 'VEHÍCULOS'): ?>
                                    <?php if($ins->inspector_updated == null): ?>
                                        <a href="#" onclick="vehiForm('<?php echo e($ins->id); ?>')" data-toggle="tooltip" title="Subir Inspección"><span class="glyphicon glyphicon-th-list" style="color:green;font-size:19px;margin-left:5px;"></span></a>
                                        <a href="#" data-toggle="tooltip" title="Subir Inspección"><span class="glyphicon glyphicon-upload" style="color:black;font-size:19px;margin-left:5px;"></span></a>
                                        <a href="#" data-toggle="tooltip" title="Confirmar"><span class="glyphicon glyphicon-ok" style="color:black;font-size:19px;margin-left:5px;"></span></a>
                                    <?php else: ?>
                                        <?php if($ins->staName == 'Pendiente Inspección'): ?>
                                            <a href="#" data-toggle="tooltip" title="Subir Inspección"><span class="glyphicon glyphicon-th-list" style="color:black;font-size:19px;margin-left:5px;"></span></a>
                                            <a href="#" onclick="fileUpload('<?php echo e($ins->id); ?>')" data-toggle="tooltip" title="Subir Inspección"><span class="glyphicon glyphicon-upload" style="color:green;font-size:19px;margin-left:5px;"></span></a>
                                            <a href="#" onclick="confirmInspection('<?php echo e($ins->id); ?>')" data-toggle="tooltip" title="Confirmar"><span class="glyphicon glyphicon-ok" style="color:green;font-size:19px;margin-left:5px;"></span></a>
                                        <?php else: ?>
                                            <a href="#" data-toggle="tooltip" title="Subir Inspección"><span class="glyphicon glyphicon-th-list" style="color:red;font-size:19px;margin-left:5px;"></span></a>
                                            <a href="#" data-toggle="tooltip" title="Subir Inspección"><span class="glyphicon glyphicon-upload" style="color:red;font-size:19px;margin-left:5px;"></span></a>
                                            <a href="#" data-toggle="tooltip" title="Confirmar"><span class="glyphicon glyphicon-ok" style="color:red;font-size:19px;margin-left:5px;"></span></a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php elseif($ins->ramoId == 4): ?>
                                <?php elseif($ins->ramoId == 1 || $ins->ramoId == 2): ?>
                                    <?php if(inspectionValidateR2($ins->id) == 'false'): ?>
                                    <?php else: ?>
                                        <?php if($ins->staName == 'Pendiente Inspección'): ?>
                                            <a href="#" onclick="fileUpload('<?php echo e($ins->id); ?>')" data-toggle="tooltip" title="Subir Inspección"><span class="glyphicon glyphicon-upload" style="color:black;font-size:19px;margin-left:5px;"></span></a>
                                            <a href="#" onclick="confirmInspection('<?php echo e($ins->id); ?>')" data-toggle="tooltip" title="Confirmar"><span class="glyphicon glyphicon-ok" style="color:black;font-size:19px;margin-left:5px;"></span></a>
                                        <?php else: ?>
                                            <a href="#" data-toggle="tooltip" title="Subir Inspección"><span class="glyphicon glyphicon-upload" style="color:red;font-size:19px;margin-left:5px;"></span></a>
                                            <a href="#" data-toggle="tooltip" title="Confirmar"><span class="glyphicon glyphicon-ok" style="color:red;font-size:19px;margin-left:5px;"></span></a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php else: ?>    
                                    <?php if($ins->staName == 'Pendiente Inspección'): ?>
                                        <a href="#" onclick="fileUpload('<?php echo e($ins->id); ?>')" data-toggle="tooltip" title="Subir Inspección"><span class="glyphicon glyphicon-upload" style="color:black;font-size:19px;margin-left:5px;"></span></a>
                                        <a href="#" onclick="confirmInspection('<?php echo e($ins->id); ?>')" data-toggle="tooltip" title="Confirmar"><span class="glyphicon glyphicon-ok" style="color:black;font-size:19px;margin-left:5px;"></span></a>
                                    <?php else: ?>
                                        <a href="#" data-toggle="tooltip" title="Subir Inspección"><span class="glyphicon glyphicon-upload" style="color:red;font-size:19px;margin-left:5px;"></span></a>
                                        <a href="#" data-toggle="tooltip" title="Confirmar"><span class="glyphicon glyphicon-ok" style="color:red;font-size:19px;margin-left:5px;"></span></a>
                                    <?php endif; ?>
                                <?php endif; ?>
                        </td>
                    <?php else: ?>
                        <td align="center"></td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <div id="tableUsers_paginate" class="dataTables_paginate paging_simple_numbers paginationsUsersMargin" style="display:inline">
            <p>Mostrando <?php echo e(count($inspections)); ?> resultados de <?php echo e($inspections->total()); ?> totales</p>
            <span style="float:right;margin-top:-45px; padding:0">
                <?php echo e($inspections->links('pagination::bootstrap-4')); ?>                        
            </span>
        </div>
    </div>
</div>
<?php /**PATH C:\wamp64\www\magnussucre\resources\views\pagination\inspection.blade.php ENDPATH**/ ?>