

<?php $__env->startSection('content'); ?>
<!--<div class="se-pre-con"></div>-->
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
<script src="<?php echo e(assets('js/channels/index.js')); ?>"></script>
<link href="<?php echo e(assets('css/DateTimePicker/bootstrap-datetimepicker.min.css')); ?>" rel="stylesheet" media="screen">
<script type="text/javascript" src="<?php echo e(assets('js/DateTimePicker/bootstrap-datetimepicker.js')); ?>" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo e(assets('js/DateTimePicker/locales/bootstrap-datetimepicker.es.js')); ?>" charset="UTF-8"></script>
<link href="<?php echo e(assets('FullCalendar/packages/core/main.css')); ?>" rel='stylesheet' />
<link href="<?php echo e(assets('FullCalendar/packages/daygrid/main.css')); ?>" rel='stylesheet' />
<link href="<?php echo e(assets('FullCalendar/packages/timegrid/main.css')); ?>" rel='stylesheet' />
<link href="<?php echo e(assets('FullCalendar/packages/list/main.css')); ?>" rel='stylesheet' />
<!--<link href="<?php echo e(asset('css/payments/index.css')); ?>" rel="stylesheet" type="text/css"/>-->
<div class="container" style="width: 100%">
    <div>
        <div class="col-md-12 border" id="filter" style="margin-top:10px;margin-left:0;margin-right:15px; display: none;">
            <form method="POST" action="<?php echo e(asset('/channel')); ?>">
                <?php echo e(csrf_field()); ?>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="document">Documento</label>
                            <input type="text" class="form-control" name="id" id="id" placeholder="Id" value="<?php echo e(session('channelsId')); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="document">Nombre</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Nombre" value="<?php echo e(session('channelsName')); ?>">
                        </div>
                    </div>
                </div>
                <input type="hidden" id="items" name="items" value="<?php echo e($items); ?>"> 
                <input type="button" id="btnCancel" class="btn btn-default" value="Cancelar">
                <input type="button" id="btnClearBenefits" class="btn btn-default" value="Limpiar">
                <input id="btnFilterForm" type="submit" class="btn btn-primary" value="Aplicar" onclick="return val()">
            </form>
        </div>
        <div class="col-md-12" style="margin-left: -15px">
            <h4>Listado de Canales </h4>
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
            <?php if($create): ?>
                <a type="button" href="#" class="border btnTable" type="button" onclick="productChannelSS()"><img id="filterImg" src="<?php echo e(asset('/images/mas.png')); ?>" width="24" height="24" alt=""></a> 
            <?php else: ?>
            <?php endif; ?>
            <?php echo $__env->make('pagination.items', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div id="tableData">
            <?php echo $__env->make('pagination.channels', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>
</div>
<form method="post" action="<?php echo e(asset('/agency/create')); ?>">
    <?php echo e(csrf_field()); ?>

    <input type="hidden" name="channelId" id="channelId" value="">
    <button type="submit" class="hidden" id="agencyBtn"></button> 
</form>
<form method="post" action="<?php echo e(asset('/channel/edit')); ?>">
    <?php echo e(csrf_field()); ?>

    <input type="hidden" name="channelEditId" id="channelEditId" value="">
    <button type="submit" class="hidden" id="channelEditBtn"></button> 
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
        <h4 class="modal-title">Resumen de Canales y Agencias</h4>
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views\channels\index.blade.php ENDPATH**/ ?>