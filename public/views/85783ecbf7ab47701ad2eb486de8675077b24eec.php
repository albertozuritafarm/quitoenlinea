

<?php $__env->startSection('content'); ?>
<!--<div class="se-pre-con"></div>-->
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
<script src="<?php echo e(assets('js/reports/moment.js')); ?>"></script>
<script src="<?php echo e(assets('js/reports/reportFilter.js')); ?>"></script>
<script src="<?php echo e(assets('js/registerCustom.js')); ?>"></script>
<link href="<?php echo e(assets('css/DateTimePicker/bootstrap-datetimepicker.min.css')); ?>" rel="stylesheet" media="screen">
<script type="text/javascript" src="<?php echo e(assets('js/DateTimePicker/bootstrap-datetimepicker.js')); ?>" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo e(assets('js/DateTimePicker/locales/bootstrap-datetimepicker.es.js')); ?>" charset="UTF-8"></script>
<link href="<?php echo e(assets('FullCalendar/packages/core/main.css')); ?>" rel='stylesheet' />
<link href="<?php echo e(assets('FullCalendar/packages/daygrid/main.css')); ?>" rel='stylesheet' />
<link href="<?php echo e(assets('FullCalendar/packages/timegrid/main.css')); ?>" rel='stylesheet' />
<link href="<?php echo e(assets('FullCalendar/packages/list/main.css')); ?>" rel='stylesheet' />
<!--<link href="<?php echo e(asset('css/payments/index.css')); ?>" rel="stylesheet" type="text/css"/>-->
<div class="container" style="margin-top:5px;width: 100%">
    <div>
        <div class="row">
            <div class="col-xs-12 col-md-3 wizard_inicial"><div class="wizard_inactivo registerForm"></div></div>
            <div class="col-xs-12 col-md-1 wizard_medio"><div class="wizard_inactivo registerForm"></div></div>
            <div class="col-xs-12 col-md-4 wizard_medio"><div class="wizard_activo registerForm">REPORTE TÉCNICO VD-AP-IN</div></div>
            <div class="col-xs-12 col-md-1 wizard_medio"><div class="wizard_inactivo registerForm"></div></div>
            <div class="col-xs-12 col-md-3 wizard_final"><div class="wizard_inactivo registerForm"></div></div>
        </div>
        <div class="col-md-12 border" style="padding: 5px; margin-top: 15px;">
            <div class="col-md-12 border" style="margin-top:10px;margin-left:0;margin-right:15px;">
                <form method="POST" action="<?php echo e(asset('/TevdapReport')); ?>" onsubmit="return val()">
                    <?php echo e(csrf_field()); ?>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <span class= "glyphicon glyphicon-asterisk" style="color:#0099ff"></span><label style="list-style-type:disc;" for="beginDate">Fecha Inicio Emisión:</label>
                                <input type="date" class="form-control" name="beginDate" id="beginDate" placeholder="fecha" style="line-height:14px" value="">
                            </div>
                        </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <span class= "glyphicon glyphicon-asterisk" style="color:#0099ff"></span><label style="list-style-type:disc;" for="endDate">Fecha Fin Emisión: </label>
                                    <input type="date" class="form-control" name="endDate" id="endDate" placeholder="fecha" style="line-height:14px" onchange="endDateChange()" value="">
                                </div>
                            </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="agent">Agente:</label>
                                <select class="form-control" id="agent" name="agent">
                                    <option value="">--Escoja Uno--</option>
                                    <?php $__currentLoopData = $agents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($agent->id); ?>"><?php echo e($agent->agentedes); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="channel">Canal:</label>
                                <select class="form-control" id="channel" name="channel">
                                    <option value="">--Escoja Uno--</option>
                                </select>
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="agency">Agencia Canal:</label>
                                <select class="form-control" id="agency" name="agency">
                                    <option value="">--Escoja Uno--</option>
                                </select>
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="ejecutivo_ss">Ejecutivo Comercial:</label>
                                <select class="form-control" id="ejecutivo_ss" name="ejecutivo_ss">
                                    <option value=''>--Escoja Uno--</option>
                                </select>
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="agent">Agencia S. Sucre:</label>
                                <select class="form-control" id="agency_ss" name="agency_ss">
                                    <option value="">--Escoja Uno--</option>
                                    <?php $__currentLoopData = $agencyss; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($agency->id); ?>"><?php echo e($agency->agenciades); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div> 
                        </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="type_policy">Tipo de Póliza:</label>
                                    <select class="form-control" id="type_policy" name="type_policy">
                                        <option value=''>--Escoja Una--</option>
                                        <option value=''>Emisión</option>
                                    </select>
                                </div> 
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="ramov">Ramo:</label>
                                    <select class="form-control" id="ramov" name="ramov">
                                        <option value="">--Escoja Uno--</option>
                                        <?php $__currentLoopData = $ramos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ramo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($ramo->id); ?>"><?php echo e($ramo->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div> 
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="productv">Producto:</label>
                                    <select class="form-control" id="productv" name="productv">
                                        <option value="">--Escoja Uno--</option>
                                    </select>
                                </div> 
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="state">Estado:</label>
                                    <select class="form-control" id="state" name="state">
                                        <option value="">--Escoja Uno--</option>
                                        <?php $__currentLoopData = $status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($st->id); ?>"><?php echo e($st->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div> 
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label style="list-style-type:disc;" for="sale_id">ID Venta:</label>
                                    <input type="text" class="form-control" name="sale_id" id="sale_id" placeholder="Id venta" style="line-height:14px" value="">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="province">Provincia:</label>
                                    <select class="form-control" id="province" name="province">
                                        <option value="">--Escoja Una--</option>
                                        <?php $__currentLoopData = $provincies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $province): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($province->id); ?>"><?php echo e($province->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                <label for="city">Canton:</label>
                                <select class="form-control" id="city" name="city">
                                    <option value=''>--Escoja Una--</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="paymenttype">Tipo de Pago:</label>
                                <select class="form-control" id="paymenttype" name="paymenttype">
                                    <option value="">--Escoja Uno--</option>
                                    <?php $__currentLoopData = $paymentsTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payments): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($payments->id); ?>"><?php echo e($payments->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-top: 10px">
                        <!--<input type="button" id="btnCancel" class="btn btn-default" value="Cancelar">-->
                        <input type="button" id="btnClearFilterTecVidAP" class="btn btn-default registerForm" value="Limpiar" style="float:left;margin-left:-15px">
                        <input type="submit" class="btn btn-info registerForm" value="Generar" style="float:right;margin-right: -15px;padding:5px">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views\reports\TevdapIndex.blade.php ENDPATH**/ ?>