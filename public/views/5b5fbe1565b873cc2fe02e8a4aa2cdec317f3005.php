

<?php $__env->startSection('content'); ?>
<!--<div class="se-pre-con"></div>-->
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
<script src="<?php echo e(assets('js/ticket/index.js')); ?>"></script>
<div class="container" style="width: 100%">
    <div>
        <div class="col-md-12 hidden border" id="filter" style="margin-top:10px;margin-left:0;margin-right:15px;">
            <form method="POST" action="<?php echo e(asset('/ticket')); ?>">
                <?php echo e(csrf_field()); ?>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="number">Numero</label>
                            <input type="text" class="form-control" name="number" id="number" placeholder="Numero" value="<?php echo e(session('ticketsNumber')); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="firstName">Nombre</label>
                            <input type="text" class="form-control" name="firstName" id="firstName" placeholder="Nombre Usuario" value="<?php echo e(session('ticketsFirstName')); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="lastName">Apellido</label>
                            <input type="text" class="form-control" name="lastName" id="lastName" placeholder="Apellido Usuario" value="<?php echo e(session('ticketsLastName')); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="status">Estado</label>
                            <select name="status" id="status" class="form-control" value="">
                                <option selected="true" value="">Todos</option>
                                <?php $__currentLoopData = $status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option <?php if($sta->id == session('ticketsStatus')): ?> <?php else: ?> <?php endif; ?> value="<?php echo e($sta->id); ?>"><?php echo e($sta->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="beginData">Fecha Inicio</label>
                            <input type="date" class="form-control" name="beginDate" id="beginDate" style="line-height:14px"  placeholder="Date" value="<?php echo e(session('ticketsBeginDate')); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="endDate">Fecha Fin</label>
                            <input type="date" class="form-control" name="endDate" id="endDate" style="line-height:14px"  placeholder="date" onchange="endDateChange()"  value="<?php echo e(session('ticketsEndDate')); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="menu">Modulo</label>
                            <select name="menu" id="menu" class="form-control" value="">
                                <option value="">Todos</option>
                                <?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $men): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option <?php if($men->id == session('ticketsMenu')): ?> selected="true" <?php else: ?> <?php endif; ?> value="<?php echo e($men->id); ?>"><?php echo e($men->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="status">Tipo Ticket</label>
                            <select name="ticketType" id="ticketType" class="form-control" value="">
                                <option value="">Todos</option>
                                <?php $__currentLoopData = $ticketType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $typ): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option <?php if($typ->id == session('ticketsTicketType')): ?> selected="true" <?php else: ?> <?php endif; ?> value="<?php echo e($typ->id); ?>"><?php echo e($typ->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="items" name="items" value="<?php echo e($items); ?>">
                <input type="button" id="btnCancel" class="btn btn-default" value="Cancelar">
                <input type="button" id="btnClearTickets" class="btn btn-default" value="Limpiar">
                <input id="btnFilterForm" type="submit" class="btn btn-primary" value="Aplicar" onclick="return val()">
            </form>
        </div>
        <div class="col-md-12" style="margin-left: -15px">
            <h4>Listado de Tickets</h4>
            <?php if(session('editSuccess')): ?>
            <div class="alert alert-success">
                <center>
                    <?php echo e(session('editSuccess')); ?>

                </center>
            </div>
            <?php endif; ?>
            <?php if(session('cancelSuccess')): ?>
            <div class="alert alert-success">
                <center>
                    <?php echo e(session('cancelSuccess')); ?>

                </center>
            </div>
            <?php endif; ?>
            <button class="border btnTable" type="button" id="filterButton"><img id="filterImg" src="<?php echo e(asset('/images/filter.png')); ?>" width="24" height="24" alt=""></button> 

                <a type="button" href="<?php echo e(asset('/ticket/create')); ?>" class="border btnTable" type="button"><img id="filterImg" src="<?php echo e(asset('/images/mas.png')); ?>" width="24" height="24" alt=""></a> 

            <?php echo $__env->make('pagination.items', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div id="tableData">
            <?php echo $__env->make('pagination.tickets', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>
</div>
<form method="post" action="<?php echo e(asset('/ticket/detail')); ?>">
    <?php echo e(csrf_field()); ?>

    <input type="hidden" name="ticketsId" id="ticketsId" value="">
    <button type="submit" class="hidden" id="ticketsBtn"></button> 
</form>
<!-- MODAL -->
<!-- MODAL RESUMEN-->
<!-- Trigger the modal with a button -->
<button id="modalAgencyResume" type="button" class="btn btn-info btn-lg hidden" data-toggle="modal" data-target="#myModal">Open Modal</button>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Resumen de Proveedores y Sucursales</h4>
      </div>
      <div id="modalResumeBody" class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div>
<script>
document.getElementById('pagination').onchange = function () {
        document.getElementById('items').value = this.value;
        document.getElementById('btnFilterForm').click();
        };
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views\tickets\index.blade.php ENDPATH**/ ?>