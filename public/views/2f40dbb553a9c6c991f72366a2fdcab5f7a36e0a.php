

<?php $__env->startSection('content'); ?>
<!--<div class="se-pre-con"></div>-->
<script src="<?php echo e(assets('js/massive/indexSecondary.js')); ?>"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
<style>
    .modal-header {
        border-bottom: 0 none;
    }

    .modal-footer {
        border-top: 0 none;
    }
    #tableDiv {
        overflow-x: auto;
    }
    .massiveMenu:hover {
        cursor: pointer;
	background-color:#6c6c6c !important;
        border: 1px solid #6c6c6c  !important;
    }
    .massiveMenu{
	text-transform:uppercase;
	font-size:12px;
	font-weight:600;
    }
</style>
<ul class="nav nav-tabs" style="font-size:15px;background-color: #444;margin-top:-20px">
    <li style="padding-left:15px; "><a class="massiveMenu" onclick="redirectUrl('massive')" style="color:white;">Carga</a></li>
    <li style="margin-bottom: 0px !important"><a class="menuactivo massiveMenu" href="#">Listado</a></li>
</ul>
<div class="container" style="width: 100%">
    <div>
        <div class="col-md-12 border" id="filter" style="margin-top:10px;margin-left:0;margin-right:15px; display: none;">
            <form method="POST" action="<?php echo e(asset('/massive/secondary')); ?>">
                <?php echo e(csrf_field()); ?>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="channel">Canal</label>
                            <select name="channel" id="channel" class="form-control" value="">
                                <option value="">-- Todos --</option>
                                <?php $__currentLoopData = $channels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $channel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option <?php if($channel->id == session('massiveSecondaryChannel')): ?> selected="true"  <?php else: ?> <?php endif; ?> value="<?php echo e($channel->id); ?>"><?php echo e($channel->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="beginDate">Fecha Inicio</label>
                            <input type="date" class="form-control" name="beginDate" id="beginDate" placeholder="fecha" style="line-height:14px" value="<?php echo e(session('massiveSecondaryBeginDate')); ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="endDate">Fecha Fin</label>
                            <input type="date" class="form-control" name="endDate" id="endDate" placeholder="fecha" style="line-height:14px" onchange="endDateChange()" value="<?php echo e(session('massiveSecondaryEndDate')); ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="plate">Placa</label>
                            <input type="text" class="form-control" name="plateSearch" id="plateSearch" placeholder="Placa" style="line-height:14px" value="<?php echo e(session('massiveSecondaryPlateSearch')); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="type">Tipo</label>
                            <select name="type" id="type" class="form-control" value="">
                                <option selected="true" value="">-- Todos --</option>
                                <?php $__currentLoopData = $massiveTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option <?php if($type->id == session('massiveSecondaryType')): ?> selected="true" <?php else: ?> <?php endif; ?> value="<?php echo e($type->id); ?>"><?php echo e($type->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="statusMassive">Estado Venta</label>
                            <select name="statusMassive" id="statusMassive" class="form-control" value="">
                                <option selected="true" value="">-- Todos --</option>
                                <?php $__currentLoopData = $statusMassive; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $massive): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option <?php if($massive->id == session('massiveSecondaryStatus')): ?> selected="true" <?php else: ?> <?php endif; ?> value="<?php echo e($massive->id); ?>"><?php echo e($massive->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="statusPayment">Estado Cobro</label>
                            <select name="statusPayment" id="statusPayment" class="form-control" value="">
                                <option selected="true" value="">-- Todos --</option>
                                <?php $__currentLoopData = $statusCharge; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $charge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option <?php if($charge->id == session('massiveSecondaryStatusPayment')): ?> selected="true" <?php else: ?> <?php endif; ?> value="<?php echo e($charge->id); ?>"><?php echo e($charge->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="plate">Venta</label>
                            <input type="text" class="form-control" name="saleId" id="saleId" placeholder="Venta" style="line-height:14px" value="<?php echo e(session('massiveSecondarySaleId')); ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="plate">Masivo</label>
                            <input type="text" class="form-control" name="massId" id="massId" placeholder="Masivo" style="line-height:14px" value="<?php echo e(session('massiveSecondaryMassId')); ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="endDate">Nombre Cliente</label>
                            <input type="text" class="form-control" name="cusName" id="cusName" placeholder="Nombre Cliente" style="line-height:14px" value="<?php echo e(session('massiveSecondaryCusName')); ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="plate">Documento Cliente</label>
                            <input type="text" class="form-control" name="cusDoc" id="cusDoc" placeholder="Documento Cliente" style="line-height:14px" value="<?php echo e(session('massiveSecondaryCusDoc')); ?>">
                        </div>
                    </div>
                </div>
                <input type="hidden" name="items" id="items" value="<?php echo e($items); ?>">
                <input type="button" id="btnCancel" class="btn btn-default" value="Cancelar">
                <input type="button" id="btnClearMassive" class="btn btn-default" value="Limpiar">
                <input id="btnFilterForm" type="submit" class="btn btn-primary" value="Aplicar"  onclick="return val()">
            </form>
        </div>
        <div class="col-md-12" style="margin-left: -15px">
            <h4>Listado de Masivos </h4>
            <?php if(session('Error')): ?>
            <div class="alert alert-warning">
                <img src="<?php echo e(assets('images/iconos/warning.png')); ?>" alt="Girl in a jacket" style="width:40px;height:40px"> <?php echo e(session('Error')); ?>

            </div>
            <?php endif; ?>
            <?php if(session('Success')): ?>
            <div class="alert alert-success">
                <center><strong><?php echo e(session('Success')); ?></strong></center>
            </div>
            <?php endif; ?>
            <?php if(session('Inactive')): ?>
            <div class="alert alert-success">
                <img src="<?php echo e(assets('images/iconos/ok.png')); ?>" alt="Girl in a jacket" style="width:40px;height:40px"><?php echo e(session('Inactive')); ?>

            </div>
            <?php endif; ?>
            <?php if( Session::has('storeSuccess') ): ?>
            <div class="alert alert-success alert-dismissible" role="alert"  style="margin-top:5px">
                <center>
                    <strong>
                        <?php echo e(Session::get('storeSuccess')); ?> 
                    </strong>
                </center>
            </div>
            <?php endif; ?>
            <?php if( Session::has('cancelSuccess') ): ?>
            <div class="alert alert-success alert-dismissible" role="alert"  style="margin-top:5px">
                <center>
                    <strong>
                        <?php echo e(Session::get('cancelSuccess')); ?> 
                    </strong>
                </center>
            </div>
            <?php endif; ?>
            <div id="paymentSuccess" class="alert alert-success hidden">
                <center>
                    <strong>
                        El masivo fue pagado de manera exitosa
                    </strong>
                </center>
            </div>
            <div id="paymentError" class="alert alert-danger hidden">
                <center>
                    <strong>
                        Hubo un error por favor comuniquese con el administrador
                    </strong>
                </center>
            </div>
            <button class="border btnTable" type="button" id="filterButton"><img id="filterImg" src="<?php echo e(assets('/images/filter.png')); ?>" width="24" height="24" alt=""></button> 
            <a type="button" class="border btnTable <?php if(!$create): ?> hidden <?php endif; ?>" href="<?php echo e(asset('/massive/create')); ?>" data-toggle="tooltip" title="Nuevo"><img src="<?php echo e(assets('/images/mas.png')); ?>" width="24" height="24" alt=""></a>
            <a type="button" class="border btnTable <?php if(!$cancel): ?> hidden <?php endif; ?>" href="<?php echo e(asset('/massive/cancel')); ?>" data-toggle="tooltip" title="Cancelar"><img src="<?php echo e(assets('/images/menos.png')); ?>" width="24" height="24" alt=""></a>
            <a type="button" class="border btnTable <?php if(!$cancel): ?> hidden <?php endif; ?>" href="#" data-toggle="tooltip" title="Cancelar" onclick="cancelMassives()"><img src="<?php echo e(assets('/images/Rechazar.png')); ?>" width="24" height="24" alt=""></a>
            <div  id="tableUsers_length" class="dataTables_length border floatRight form-group tableSearch inline" style="margin-top:0px">
                <label style="width:auto">
                    Mostrar
                    <select id="pagination" class="form-control selectSearch"  style="width:50px; display:inline">
                        <option value="10" <?php if($items == 10): ?> selected <?php endif; ?> >10</option>
                        <option value="25" <?php if($items == 25): ?> selected <?php endif; ?> >25</option>
                        <option value="50" <?php if($items == 50): ?> selected <?php endif; ?> >50</option>
                        <option value="100" <?php if($items == 100): ?> selected <?php endif; ?> >100</option>
                    </select>
                    Registros
                </label>
            </div>
        </div>
        <div id="tableData">
            <?php echo $__env->make('pagination.massivesSecondary', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>
</div>
<button id="modalVehiBtn" type="button" class="btn btn-info btn-lg hidden" data-toggle="modal" data-target="#myModal">Open Modal</button>
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Ingrese el Vehiculo</h4>
            </div>
            <input type="hidden" id="modalSalId" name="modalSalId" value="">
            <div id="modalBody" class="modal-body">

            </div>
            <div class="modal-footer">
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
<script>
    document.getElementById('pagination').onchange = function () {
        document.getElementById('items').value = this.value;
        document.getElementById('btnFilterForm').click();
    };

    function redirectUrl(URL) {
        window.location.href = ROUTE + '/massive';
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views\massive\indexSecondary.blade.php ENDPATH**/ ?>