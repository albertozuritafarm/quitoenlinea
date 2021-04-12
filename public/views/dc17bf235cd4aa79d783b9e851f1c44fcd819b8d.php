

<?php $__env->startSection('content'); ?>
<!--<div class="se-pre-con"></div>-->
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
<script src="<?php echo e(assets('js/payments/index.js')); ?>"></script>
<link href="<?php echo e(assets('css/payments/index.css')); ?>" rel="stylesheet" type="text/css"/>
<div class="container" style="width: 100%">
    <div>
        <div class="col-md-12 border" id="filter" style="margin-top:10px;margin-left:0;margin-right:15px; display: none;">
            <form method="POST" action="<?php echo e(asset('/payments')); ?>">
                <?php echo e(csrf_field()); ?>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Identificación</label>
                            <input type="text" class="form-control" name="document" id="document" placeholder="Identificación" value="<?php echo e(session('paymentsDocument')); ?>">
                        </div>

                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Fecha</label>
                            <input type="date" class="form-control" name="date" id="date" placeholder="Correo" style="line-height:14px" value="<?php echo e(session('paymentsDate')); ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Numero de Venta</label>
                            <input type="number" class="form-control" name="saleId" id="saleId" placeholder="Numero de Venta" value="<?php echo e(session('paymentsSaleId')); ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="adviser">Forma de Pago</label>
                            <select name="payment_type" id="payment_type" class="form-control" value="">
                                <option selected="true" value="">Todos</option>
                                <?php $__currentLoopData = $paymentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pay): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(session('paymentsSalesType') == $pay->id): ?>
                                <option selected="true" value="<?php echo e($pay->id); ?>"><?php echo e($pay->name); ?></option>
                                <?php else: ?>
                                <option value="<?php echo e($pay->id); ?>"><?php echo e($pay->name); ?></option>
                                <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="status">Tipo</label>
                            <select name="charge_type" id="charge_type" class="form-control" value="">
                                <option selected="true" value="">Todos</option>
                                <?php $__currentLoopData = $chargeTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cha): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(session('paymentsChargesType') == $cha->id): ?>
                                <option selected="true" value="<?php echo e($cha->id); ?>"><?php echo e($cha->name); ?></option>
                                <?php else: ?>
                                <option value="<?php echo e($cha->id); ?>"><?php echo e($cha->name); ?></option>
                                <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="status">Estado:</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">--Seleccione Uno--</option>
                                <?php $__currentLoopData = $status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($sta->id == session('paymentsStatus')): ?>
                                <option selected="true" value="<?php echo e($sta->id); ?>"><?php echo e($sta->name); ?></option>
                                <?php else: ?>
                                <option value="<?php echo e($sta->id); ?>"><?php echo e($sta->name); ?></option>
                                <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div> 
                    </div>

                </div>

                <input type="hidden" name="items" id="items" value="<?php echo e($items); ?>">
                <input type="button" id="btnCancel" class="btn btn-default" value="Cancelar">
                <input type="button" id="btnClearSales" class="btn btn-default" value="Limpiar">
                <input id="btnFilterForm"  type="submit" class="btn btn-primary" value="Aplicar">
            </form>
        </div>
        <div class="col-md-12" style="margin-left: -15px">
            <h4>Listado de Cobranzas </h4>
            <?php if(session('Error')): ?>
            <div class="alert alert-warning">
                <img src="<?php echo e(asset('images/iconos/warning.png')); ?>" alt="Girl in a jacket" style="width:40px;height:40px"> <?php echo e(session('Error')); ?>

            </div>
            <?php endif; ?>
            <?php if(session('Success')): ?>
            <div class="alert alert-success">
                <img src="<?php echo e(asset('images/iconos/ok.png')); ?>" alt="Girl in a jacket" style="width:40px;height:40px"><?php echo e(session('Success')); ?>

            </div>
            <?php endif; ?>
            <?php if(session('Inactive')): ?>
            <div class="alert alert-success" style="margin-right: -15px">
                <img src="<?php echo e(asset('images/iconos/ok.png')); ?>" alt="Girl in a jacket" style="width:40px;height:40px"><?php echo e(session('Inactive')); ?>

            </div>
            <?php endif; ?>
            <?php if(session('cancelMessage')): ?>
            <div class="alert alert-success" style="margin-right:-20px">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <center>
                    <span class="glyphicon glyphicon-ok" id="annulmentMsgSuccess"  style="font-weight: bold;"><?php echo e(session('cancelMessage')); ?></span>
                </center>
            </div>
            <?php endif; ?>
            <?php if(Session::has('message')): ?>
            <div class="alert alert-success" style="margin-right:-20px">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <center>
                    <span class="glyphicon glyphicon-ok" id="annulmentMsgSuccess"  style="font-weight: bold;"><?php echo e(session('message')); ?></span>
                </center>
            </div>
            <?php endif; ?>
            <?php if(session('errorNumber')): ?>
            <div class="alert alert-success">
                <center>
                    <?php echo e(session('errorNumber')); ?>

                </center>
            </div>
            <?php endif; ?>
            <?php if(session('paymentsStore')): ?>
            <div class="alert alert-success">
                <center>
                    <?php echo e(session('paymentsStore')); ?>

                </center>
            </div>
            <?php endif; ?>

            <div id="annulmentDivSuccess" class="alert alert-success hidden" style="margin-right:-20px">
                <center>
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <span class="glyphicon glyphicon-ok" id="annulmentMsgSuccess"  style="font-weight: bold;"></span>
                </center>
            </div>
            <div id="annulmentDivError" class="alert alert-danger hidden" style="margin-right:-20px">
                <center>
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <span class="glyphicon glyphicon-remove" id="annulmentMsgError" style="font-weight: bold;">&ensp;&ensp;&ensp;</span>
                </center>
            </div>
            <button class="border btnTable" type="button" id="filterButton"><img id="filterImg" src="<?php echo e(asset('/images/filter.png')); ?>" width="24" height="24" alt=""></button> 
            <?php echo $__env->make('pagination.items', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div id ="tableData">
            <?php echo $__env->make('pagination.charges', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>
</div>
<div class="hidden">
    <form action="<?php echo e(asset('/sales/payments/create')); ?>" method="POST" target="_blank">
        <?php echo e(csrf_field()); ?>

        <input type="hidden" id="chargeId" name="chargeId" value="">
        <input id="formBtn" type="submit" class="btn btn-info" style="float:right" value="SI">
    </form>
</div>
<!-- MODAL VEHICULES PICTURE-->
<!-- Trigger the modal with a button -->
<button id="modalBtnClickPictures" type="button" class="btn btn-info btn-lg hidden" data-toggle="modal" data-target="#myModalVehiPictures">Open Modal</button>
<!-- Modal -->
<div id="myModalVehiPictures" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Vehiculos</h4>
            </div>
            <div id="modalBody" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>

    </div>
</div>
<!-- MODAL PAYMENT -->
<button id="modalBtnClickPayment" type="button" class="btn btn-info btn-lg hidden" data-toggle="modal" data-target="#paymentModal">Open Modal</button>
<div id="paymentModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Cobranza</h4>
            </div>
            <div id="modalBodyPayment" class="modal-body">
                <form action="/payments/store" method="POST">
                    <?php echo e(csrf_field()); ?>


                    <div id="formBody">
                    </div>
                </form>
            </div>
            <div class="modal-footer">

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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views\payments\index.blade.php ENDPATH**/ ?>