

<?php $__env->startSection('content'); ?>
<!--<div class="se-pre-con"></div>-->
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
<script src="<?php echo e(assets('js/reports/salesTimes.js')); ?>"></script>
<script src="<?php echo e(assets('js/registerCustom.js')); ?>"></script>
<link href="<?php echo e(assets('css/DateTimePicker/bootstrap-datetimepicker.min.css')); ?>" rel="stylesheet" media="screen">
<script type="text/javascript" src="<?php echo e(assets('js/DateTimePicker/bootstrap-datetimepicker.js')); ?>" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo e(assets('js/DateTimePicker/locales/bootstrap-datetimepicker.es.js')); ?>" charset="UTF-8"></script>
<link href="<?php echo e(assets('FullCalendar/packages/core/main.css')); ?>" rel='stylesheet' />
<link href="<?php echo e(assets('FullCalendar/packages/daygrid/main.css')); ?>" rel='stylesheet' />
<link href="<?php echo e(assets('FullCalendar/packages/timegrid/main.css')); ?>" rel='stylesheet' />
<link href="<?php echo e(assets('FullCalendar/packages/list/main.css')); ?>" rel='stylesheet' />
<!--<link href="<?php echo e(asset('css/payments/index.css')); ?>" rel="stylesheet" type="text/css"/>-->
<div class="container" style="margin-top:15px;width: 100%">
    <div>
        <div class="col-md-10 col-md-offset-1 border" style="padding: 15px">
            <div class="row">
                <div class="col-xs-12 registerForm" style="margin:12px;">
                    <center>
                        <h4 style="font-weight:bold">Reporte de Tiempos</h4>
                        <!--<h5>Datos del Cliente.</h5>-->
                    </center>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-4 wizard_inicial" style="padding-left:0px !important"><div class="wizard_inactivo"></div></div>
                <div class="col-xs-12 col-sm-4 wizard_medio"><div class="wizard_activo registerForm">Reporte</div></div>
                <div class="col-xs-12 col-sm-4 wizard_final" style="padding-right: 0px !important"><div class="wizard_inactivo"></div></div>
            </div>
            <div class="col-md-12 border" style="margin-top:10px;margin-left:0;margin-right:15px;">
                <form method="POST" action="<?php echo e(asset('/timeReports')); ?>">
                    <?php echo e(csrf_field()); ?>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="channel">Canal:</label>
                                <select class="form-control" id="channel" name="channel">
                                    <option value=''>--Escoja Una--</option>
                                    <?php $__currentLoopData = $channels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $channel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($channel->id); ?>"><?php echo e($channel->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="agency">Agencia:</label>
                                <select class="form-control" id="agency" name="agency">
                                    <option value=''>--Escoja Una--</option>
                                </select>
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label style="list-style-type:disc;" for="beginDate">Fecha Inicio</label>
                                <input type="date" class="form-control" name="beginDate" id="beginDate" placeholder="fecha" style="line-height:14px" value="">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label style="list-style-type:disc;" for="endDate">Fecha Fin</label>
                                <input type="date" class="form-control" name="endDate" id="endDate" placeholder="fecha" style="line-height:14px" onchange="endDateChange()" value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="country">Pais:</label>
                                <select class="form-control" id="country" name="country">
                                    <option value="">--Escoga Uno--</option>
                                    <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($country->id); ?>"><?php echo e($country->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="province">Provincia:</label>
                                <select class="form-control" id="province" name="province">
                                    <option value="">--Escoga Uno--</option>
                                </select>
                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="city">Ciudad:</label>
                                <select class="form-control" id="city" name="city">
                                    <option value="">--Escoga Uno--</option>
                                </select>
                            </div> 
                        </div>
                    </div>
                    


            </div>
            <div class="col-md-12" style="margin-top: 10px">
                <!--<input type="button" id="btnCancel" class="btn btn-default" value="Cancelar">-->
                <input type="button" id="btnClearFilter" class="btn btn-default registerForm" value="Limpiar" style="float:left;margin-left:-15px">
                <input type="submit" class="btn btn-info registerForm" value="Generar" onclick="return val()" style="float:right;margin-right: -15px;padding:5px">
            </div>
            </form>
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
    </script>
    <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views\reports\time.blade.php ENDPATH**/ ?>