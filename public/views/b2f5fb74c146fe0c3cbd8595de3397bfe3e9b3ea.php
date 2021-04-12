

<?php $__env->startSection('content'); ?>
<!--<div class="se-pre-con"></div>-->
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
<script src="<?php echo e(assets('js/registerCustom.js')); ?>"></script>
<script src="<?php echo e(assets('js/sales/index.js')); ?>"></script>
<link href="<?php echo e(assets('css/sales/index.css')); ?>" rel="stylesheet" type="text/css"/>
<div class="container" style="width: 100%">
    <div>
        <div class="col-md-12 border" id="filter" style="margin-top:10px;margin-left:0;margin-right:15px;display:none">
            <form  class="col-md-12 border" method="POST" action="<?php echo e(asset('/sales')); ?>">
                <?php echo e(csrf_field()); ?>

                <div class="row">
                <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Numero de Póliza</label>
                            <input type="text" class="form-control" name="policy_number" id="policy_number" placeholder="Numero de Póliza" value="<?php echo e(session('salesPolicyNumber')); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="first_name">Cliente</label>
                            <input type="text" class="form-control" name="customer" id="customer" placeholder="Cliente" value="<?php echo e(session('salesCustomer')); ?>">
                        </div>

                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Identificación</label>
                            <input type="text" class="form-control" name="document" id="document" placeholder="Identificación" value="<?php echo e(session('salesDocument')); ?>">
                        </div>

                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Placa</label>
                            <input type="text" class="form-control" name="plate" id="plate" placeholder="Placa" value="<?php echo e(session('salesPlate')); ?>">
                        </div>

                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="adviser">Movimiento</label>
                            <select name="movement" id="movement" class="form-control" value="">
                                <option value="">Todos</option>
                                <?php $__currentLoopData = $salesMovements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option <?php if($mov->id == session('salesMovements')): ?> selected="true" <?php else: ?> <?php endif; ?> value="<?php echo e($mov->id); ?>"><?php echo e($mov->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Fecha desde</label>
                            <input type="text" class="form-control date datepicker" name="dateFrom" id="dateFrom" data-date-format="DD-MM-YYYY" placeholder="Fecha desde" value="<?php echo e(session('salesDateFrom')); ?>" style="line-height:14px">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Fecha hasta</label>
                            <input type="text" class="form-control date datepicker" name="dateUntil" id="dateUntil" data-date-format="DD-MM-YYYY" placeholder="Fecha hasta" value="<?php echo e(session('salesDateUntil')); ?>" style="line-height:14px">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Numero de Venta</label>
                            <input type="text" class="form-control" name="saleId" id="saleId" placeholder="Numero de Venta" value="<?php echo e(session('salesSaleId')); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="adviser">Asesor</label>
                            <select name="adviser" id="adviser" class="form-control" value="">
                                <option selected="true" value="">Todos</option>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($usr->id == session('salesAdviser')): ?>
                                <option selected="true" value="<?php echo e($usr->id); ?>"><?php echo e($usr->last_name); ?> <?php echo e($usr->first_name); ?></option>
                                <?php else: ?>
                                <option value="<?php echo e($usr->id); ?>"><?php echo e($usr->last_name); ?> <?php echo e($usr->first_name); ?></option>
                                <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="status">Estado</label>
                            <select name="status" id="status" class="form-control" value="">
                                <option selected="true" value="">Todos</option>
                                <?php $__currentLoopData = $status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($sta->id == session('salesStatus')): ?>
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
                <input id="btnFilterForm" type="submit" class="btn btn-primary" value="Aplicar">
            </form>
        </div>
        <div class="col-md-12" style="margin-left: -15px">
            <h4>Listado de Ventas </h4>
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
            <?php if(Session::has('successMessage')): ?>
            <div class="alert alert-success" style="margin-right:-20px">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <center>
                    <span class="glyphicon glyphicon-ok" id="annulmentMsgSuccess"  style="font-weight: bold;"> <?php echo e(session('successMessage')); ?></span>
                </center>
            </div>
            <?php endif; ?>
            <?php if(Session::has('errorMessage')): ?>
            <div class="alert alert-danger" style="margin-right:-20px">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <center>
                    <span class="glyphicon glyphicon-ok" id="annulmentMsgSuccess"  style="font-weight: bold;"><?php echo e(session('errorMessage')); ?></span>
                </center>
            </div>
            <?php endif; ?>
            <?php if(Session::has('deleteMessage')): ?>
            <div class="alert alert-success" style="margin-right:-20px">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <center>
                    <span class="glyphicon glyphicon-ok" id="annulmentMsgSuccess"  style="font-weight: bold;"><?php echo e(session('deleteMessage')); ?></span>
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
            <?php if(session('inspectionCreate')): ?>
            <div class="alert alert-success">
                <center>
                    <?php echo e(session('inspectionCreate')); ?>

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
            <a type="button" class="border btnTable <?php if(!$create): ?> hidden <?php endif; ?>" href="<?php echo e(asset('/sales/product/select')); ?>" data-toggle="tooltip" title="Registrar Venta"><img src="<?php echo e(assets('/images/mas.png')); ?>" width="24" height="24" alt=""></a>
            <!--<a type="button" id="reloadTableBtn" class="border btnTable" href="#" data-toggle="tooltip" title="Actualizar Tabla"><img src="<?php echo e(assets('/images/restore.png')); ?>" width="22" height="22" alt=""></a>-->
            <a type="button" id="reloadTableBtn" class="border btnTable" href="#" data-toggle="tooltip" title="Actualizar Tabla"><img src="<?php echo e(assets('/images/refresh.png')); ?>" width="24" height="24" alt=""></a>
            <!--<a type="button" class="border btnTable <?php if(!$create): ?> hidden <?php endif; ?>" href="<?php echo e(route('salesCreateRemote')); ?>" data-toggle="tooltip" title="Registrar Venta Remota"><img src="<?php echo e(asset('/images/mas.png')); ?>" width="24" height="24" alt=""></a>-->
            <!--<a type="button" class="border btnTable <?php if(!$edit): ?> hidden <?php endif; ?>" href="#" data-toggle="tooltip" title="Renovar venta" onclick="renewBtn()"><img src="<?php echo e(asset('/images/restore.png')); ?>" width="24" height="24" alt="" style="opacity: 0.5;"></a>-->
            <?php echo $__env->make('pagination.items', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div id="tableData">
            <?php echo $__env->make('pagination.individual', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>
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
<!-- MODAL SALES RESUME -->
<button id="modalBtnClickResume" type="button" class="btn btn-info btn-lg hidden" data-toggle="modal" data-target="#saleModal">Open Modal</button>
<div id="saleModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Resumen de la Venta</h4>
            </div>
            <div id="modalBodySaleResume" class="modal-body">

            </div>
            <div class="modal-footer">
                <!--<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>-->
            </div>
        </div>

    </div>
</div>
<!-- MODAL ACTIVATE SALES-->
<button id="modalBtnClickActivate" type="button" class="btn btn-info btn-lg hidden" data-toggle="modal" data-target="#saleActivateModal">Open Modal</button>
<div id="saleActivateModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Validar el codigo SMS</h4>
            </div>
            <div id="modalBodySaleActivate" class="modal-body">
                <form id="validateCodeForm">
                    <?php echo e(csrf_field()); ?>

                    <div id="resultMessage">
                    </div>
                    <span class="col-md-12 border">
                        <div id="validationCode">
                        </div>
                        <div class="form-group">
                            <label for="code">Ingrese el codigo</label>
                            <input type="text" class="form-control" name="code" id="code" placeholder="Ingrese el codigo"><br>
                            <button id="resendCodeBtn" type="submit" class="btn btn-success" style="float:right;margin-bottom: 10px" onclick="resendCode()">Reenviar Codigo</button>
                        </div>
                    </span>
                    <div>
                        <button type="button" class="btn btn-default" data-dismiss="modal" style="float:left;margin-top: 10px">Cerrar</button>
                        <button id="validateCodeBtn" type="submit" class="btn btn-info" onclick="validateCode()" style="float:right;margin-top: 10px;">Validar</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="border-top: 0 none !important;">
            </div>
        </div>
    </div>
</div>
<!-- MODAL FORMULARIO VINCULACIÓN-->
<button id="modalBtnClickValidatePayer" type="button" class="btn btn-info btn-lg hidden" data-toggle="modal" data-target="#saleValidatePayer">Open Modal</button>
<div id="saleValidatePayer" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Validar Pagador</h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer" style="border-top: 0 none !important;">
            </div>
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
<script>
    document.getElementById('pagination').onchange = function () {
        document.getElementById('items').value = this.value;
        document.getElementById('btnFilterForm').click();
    };
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views/sales/index.blade.php ENDPATH**/ ?>