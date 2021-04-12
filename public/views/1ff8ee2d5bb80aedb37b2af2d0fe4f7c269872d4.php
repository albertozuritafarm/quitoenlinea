

<?php $__env->startSection('content'); ?>
<!--<div class="se-pre-con"></div>-->
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
<script src="<?php echo e(assets('js/benefits/index.js')); ?>"></script>
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
            <form method="POST" action="<?php echo e(asset('/benefits')); ?>">
                <?php echo e(csrf_field()); ?>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sel1">Canal:</label>
                            <select class="form-control" id="channel" name="channel">
                                <option value="">--Escoga Uno--</option>
                                <?php $__currentLoopData = $channels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $channel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($channel->id == session('benefitsChannel')): ?>
                                <option value="<?php echo e($channel->id); ?>" selected=""><?php echo e($channel->name); ?></option>
                                <?php else: ?>
                                <option value="<?php echo e($channel->id); ?>"><?php echo e($channel->name); ?></option>
                                <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div> 
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="beginDate">Fecha Inicio</label>
                            <input type="date" class="form-control" name="beginDate" id="beginDate" placeholder="fecha" style="line-height:14px" value="<?php echo e(session('benefitsBeginDate')); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="endDate">Fecha Fin</label>
                            <input type="date" class="form-control" name="endDate" id="endDate" placeholder="fecha" style="line-height:14px" onchange="endDateChange()" value="<?php echo e(session('benefitsEndDate')); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sel1">Estado:</label>
                            <select class="form-control" id="status" name="status">
                                <option value=''>--Escoja Una--</option>
                                <?php $__currentLoopData = $status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option <?php if($sta->id == session('benefitsStatus')): ?> selected="true" <?php else: ?> <?php endif; ?> value='<?php echo e($sta->id); ?>'><?php echo e($sta->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
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
            <button class="border btnTable" type="button" id="filterButton"><img id="filterImg" src="<?php echo e(assets('/images/filter.png')); ?>" width="24" height="24" alt=""></button> 
            <?php if($edit): ?>
            <a type="button" class="border btnTable" href="<?php echo e(asset('/benefits/create')); ?>" data-toggle="tooltip" title="Nuevo"><img src="<?php echo e(assets('/images/mas.png')); ?>" width="24" height="24" alt=""></a>
            <?php else: ?>
            <?php endif; ?>
            <?php echo $__env->make('pagination.items', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div id="tableData">
            <?php echo $__env->make('pagination.benefits', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        </div>
    </div>
    <!-- EDIT MODAL-->
    <!-- Trigger the modal with a button -->
    <button id="modalEditBtn" type="button" class="btn btn-info btn-lg hidden" data-toggle="modal" data-target="#myModalEdit">Open Modal</button>
    <!-- Modal -->
    <div id="myModalEdit" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Editar Beneficio</h4>
                </div>
                <div id="errorModalEdit" class="alert alert-danger hidden">
                    <center>
                        No se pudo actualizar el beneficio por favor verifique los datos ingresados.
                    </center>
                </div>
                <div id="modalBody" class="modal-body">
                    <div class="form-group">
                        <label for="beginDate">Vigencia Desde:</label>
                        <div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                            <input class="form-control" size="16" type="text" value="" name="beginDateModal" id="beginDateModal" end="endDate" readonly required>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                        <!--<input type="hidden" id="dtp_input2" value="" /><br/>-->
                    </div>

                    <div class="form-group">
                        <label for="beginDate">Vigencia Hasta:</label>
                        <div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                            <input class="form-control" size="16" type="text" value="" name="endDateModal" id="endDateModal" end="endDate" readonly required onchange="endDateChange()">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                        <!--<input type="text" id="dtp_input2" value="" />-->
                    </div>
                    <div class="form-group">
                        <label style="list-style-type:disc;" for="plate">N° Usos:</label>
                        <input type="text" class="form-control" name="uses" id="uses" placeholder="N° Usos" value="" required>
                    </div>
                    <input type="hidden" id="benefitsId" name="benefitsId" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="float: left">Cerrar</button>
                    <a type="submit" href="#" class="btn btn-info" style="float:right" onclick="updateBenefit()">Actualizar</a>
                </div>
            </div>

        </div>
    </div>
    <!-- CANCEL MODAL-->
    <!-- Trigger the modal with a button -->
    <button id="modalCancelBtn" type="button" class="btn btn-info btn-lg hidden" data-toggle="modal" data-target="#myModalCancel">Open Modal</button>
    <!-- Modal -->
    <div id="myModalCancel" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Cancelar Beneficio</h4>
                </div>
                <div id="errorModalCancel" class="alert alert-danger hidden">
                    <center>
                        No se pudo cancelar el beneficio por favor verifique los datos ingresados.
                    </center>
                </div>
                <div id="modalBody" class="modal-body">
                    <div class="form-group">
                        <label for="sel1">Motivo:</label>
                        <select class="form-control" id="cancelMotive" name="cancelMotive">
                            <option value="">--Escoga Uno--</option>
                            <?php $__currentLoopData = $cancelMotives; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $motive): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($motive->id); ?>"><?php echo e($motive->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div> 
                    <input type="hidden" id="benefitsIdCancel" name="benefitsIdCancel" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="float: left">Cerrar</button>
                    <a type="submit" href="#" class="btn btn-info" style="float:right" onclick="cancelBenefit()">Cancelar</a>
                </div>
            </div>

        </div>
    </div>
    <!-- ADD MODAL-->
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
                        La placa ingresada no se encuentra en nuestro sistema.
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
                    <a id="modalBenefitsBtn" type="submit" href="#" class="btn btn-info hidden" style="float:right" onclick="storeBenefitSchedule()">Agendar</a>
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views\benefits\index.blade.php ENDPATH**/ ?>