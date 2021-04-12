

<?php $__env->startSection('content'); ?>
<!--<div class="se-pre-con"></div>-->
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
<script src="<?php echo e(assets('js/benefits/indexSecondary.js')); ?>"></script>
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
        <div class="col-md-12 hidden border" id="filter" style="margin-top:10px;margin-left:0;margin-right:15px;">
            <form method="POST" action="<?php echo e(asset('/benefits/secondary')); ?>">
                <?php echo e(csrf_field()); ?>

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="plate">Placa</label>
                            <input type="text" class="form-control" name="plate" id="plate" placeholder="Placa" style="line-height:14px" value="<?php echo e(session('benefitsSecondaryPlate')); ?>">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="beginDate">Fecha Inicio</label>
                            <input type="date" class="form-control" name="beginDate" id="beginDate" placeholder="fecha" style="line-height:14px" value="<?php echo e(session('benefitsSecondaryBeginDate')); ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="endDate">Fecha Fin</label>
                            <input type="date" class="form-control" name="endDate" id="endDate" placeholder="fecha" style="line-height:14px" onchange="endDateChange()" value="<?php echo e(session('benefitsSecondaryEndDate')); ?>">
                        </div>
                    </div><div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Nombre</label>
                            <input type="text" class="form-control" name="first_name" id="first_name" placeholder="Nombre" style="line-height:14px" value="<?php echo e(session('benefitsSecondaryFirstName')); ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="last_name">Apellido</label>
                            <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Apellido" style="line-height:14px" value="<?php echo e(session('benefitsSecondaryLastName')); ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="document">Documento</label>
                            <input type="text" class="form-control" name="document" id="document" placeholder="Documento" style="line-height:14px" value="<?php echo e(session('benefitsSecondaryDocument')); ?>">
                        </div>
                    </div>

                </div>

                <input type="hidden" name="items" id="items" value="<?php echo e($items); ?>">
                <input type="button" id="btnCancel" class="btn btn-default" value="Cancelar">
                <input type="button" id="btnClearBenefits" class="btn btn-default" value="Limpiar">
                <input id="btnFilterForm" type="submit" class="btn btn-primary" value="Aplicar" onclick="return val()">
            </form>
        </div>
        <div class="col-md-12" style="margin-left: -15px">
            <h4>Listado de Beneficios </h4>
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
            <?php if(session('successScheduleStore')): ?>
            <div id="divSuccess" class="alert alert-success">
                <center>
                    El uso del beneficio fue agendado correctamente.
                </center>
            </div>
            <?php endif; ?>
            <div id="confirmMessage" class="alert alert-success hidden" style="margin-right:-20px">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <center>
                    <span class="glyphicon glyphicon-ok"  style="font-weight: bold;"> El agendamiento fue confirmado Correctamente</span>
                </center>
            </div>
            <div id="cancelMessage" class="alert alert-success hidden" style="margin-right:-20px">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <center>
                    <span class="glyphicon glyphicon-ok"  style="font-weight: bold;"> El agendamiento fue cancelado Correctamente</span>
                </center>
            </div>
            <button class="border btnTable" type="button" id="filterButton"><img id="filterImg" src="<?php echo e(asset('/images/filter.png')); ?>" width="24" height="24" alt=""></button> 
            <?php if($create): ?>
                <a type="button" class="border btnTable" href="#" data-toggle="tooltip" title="Nuevo" onclick="myModalAdd()"><img src="<?php echo e(asset('/images/mas.png')); ?>" width="24" height="24" alt=""></a>
            <?php endif; ?>
            <?php echo $__env->make('pagination.items', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div id="tableData">
            <?php echo $__env->make('pagination.benefits_secondary', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>
</div>
<!-- ADD MODAL-->
<!-- Trigger the modal with a button -->
<button id="myModalAddBtn" type="button" class="btn btn-info btn-lg hidden" data-toggle="modal" data-target="#myModalAdd">Open Modal</button>
<!-- Modal -->
<div id="myModalAdd" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Agendar Beneficio</h4>
            </div>
            <div id="errorModalCancel" class="alert alert-danger hidden">
                <center>
                    No se pudo cancelar el beneficio por favor verifique los datos ingresados.
                </center>
            </div>
            <div id="divError" class="alert alert-danger hidden">
                <center>
                    Su vehículo no cuenta con un beneficio adicional
                </center>
            </div>
            <div id="divSuccess" class="alert alert-success hidden">
                <center>
                    El uso del beneficio fue agendado correctamente.
                </center>
            </div>
            <div id="modalBody" class="col-md-12 modal-body">
                <div class="col-md-12 border">
                    <div class="col-md-6 col-md-offset-4">
                        <div class="form-group form-inline">
                            <label for="plate">Placa:</label>
                            <input type="text" class="form-control" name="plateModal" id="plateModal" placeholder="Placa" value="" required style="width:50%">
                            <button type="button" class="btn btn-info" id="plateBtn" onclick="plateBtn()"><span class="glyphicon glyphicon-search"></span></button>
                        </div> 

                    </div>
                    <div id="tableBenefits" class="col-md-12">

                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" style="float: left">Cerrar</button>
                <?php if($edit == 'true'): ?>
                <a id="modalBenefitsBtn" type="submit" href="#" class="btn btn-info hidden" style="float:right" onclick="storeBenefitSchedule()">Agendar</a>
                <?php else: ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script>
    $('.form_date').datetimepicker({
        language: 'es',
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
    });
    document.getElementById('pagination').onchange = function () {
        document.getElementById('items').value = this.value;
        document.getElementById('btnFilterForm').click();
    };
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views\benefits\index_secondary.blade.php ENDPATH**/ ?>