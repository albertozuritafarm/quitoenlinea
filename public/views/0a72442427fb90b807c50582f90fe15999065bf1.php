<div class="col-md-12 border" style="margin-top:10px">
    <div id="tableDiv">
        <table id="newPaginatedTableNoOrdering" class="table table-striped row-border table-responsive hover stripe borderTable">
            <thead>
                <tr style="background-color: #44444496; color: white;">
                    <th align="center">Ticket</th>
                    <th align="center">Estado</th>
                    <th align="center">Modulo</th>
                    <th align="center">Tipo Ticket</th>
                    <th align="center">Categoria</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td align="center"><?php echo e($ticket->id); ?></td>
                    <td align="center"><?php echo e($ticketStatus->name); ?></td>
                    <td align="center"><?php echo e($menu->name); ?></td>
                    <td align="center"><?php echo e($ticketType->name); ?></td>
                    <td align="center"><?php echo e($ticketTypeDetail->name); ?></td>
            </tbody>
        </table>
    </div>
</div>
<div class="col-md-12 borderTable" style="margin-top:10px;padding: 10px;">
         <?php $__currentLoopData = $ticketDetail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
         <div class="col-md-12 border" style="margin-top: 10px;">
             <div class="col-md-4">
                 <h5 style="font-weight: 700;"><?php echo e($deta->user); ?><?php if($deta->type_ticket_access == 1): ?>&nbsp;<span class="label label-primary">Soporte</span> <?php else: ?> <span class="label label-success">Usuario</span> <?php endif; ?></h5>
                 <h6 style="font-weight: 100;font-size: 11px;"><?php echo e($deta->date); ?></h6>
             </div>
             <div class="col-md-8">
                 <h6 style="font-weight: 400;"><?php echo nl2br(e($deta->description)); ?></h6>
                 <?php if($deta->picture1 != null): ?>
                 <hr>
                 <h6><a href="<?php echo e(asset('')); ?>/ticket/picture/1/<?php echo e($deta->id); ?>" target="_blank"><?php echo e($deta->picture2); ?></a></h6>
                 <?php endif; ?>
             </div>
         </div>
         <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div><?php /**PATH C:\wamp64\www\magnussucre\resources\views\pagination\tickets_detail.blade.php ENDPATH**/ ?>