

<?php $__env->startSection('content'); ?>
<script src="<?php echo e(assets('js/registerCustom.js')); ?>"></script>
<script src="<?php echo e(assets('js/channels/create.js')); ?>"></script>
<link href="<?php echo e(assets('css/DateTimePicker/bootstrap-datetimepicker.min.css')); ?>" rel="stylesheet" media="screen">
<script type="text/javascript" src="<?php echo e(assets('js/DateTimePicker/bootstrap-datetimepicker.js')); ?>" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo e(assets('js/DateTimePicker/locales/bootstrap-datetimepicker.es.js')); ?>" charset="UTF-8"></script>
<link href="<?php echo e(assets('FullCalendar/packages/core/main.css')); ?>" rel='stylesheet' />
<link href="<?php echo e(assets('FullCalendar/packages/daygrid/main.css')); ?>" rel='stylesheet' />
<link href="<?php echo e(assets('FullCalendar/packages/timegrid/main.css')); ?>" rel='stylesheet' />
<link href="<?php echo e(assets('FullCalendar/packages/list/main.css')); ?>" rel='stylesheet' />
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
<style>
    .tableSelect{
        background-color: #bababd;
    }
    .inputError{
        border-color: red;
    }
    .hidden{
        display:none;
        visibility:hidden;
    }
</style>

<div class="container" style="margin-top:15px; font-size:14px !important">
    <!--<div class="row justify-content-center border" style="margin-left:20%;">-->
    <div class="col-md-10 col-md-offset-1 border" style="padding: 15px">
        <div class="row">
            <div class="col-xs-12 registerForm" style="margin:12px;">
                <center>
                    <h4 style="font-weight:bold">Crear Canal</h4>
                    <!--<h5>Datos del Cliente.</h5>-->
                </center>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-4 wizard_inicial" style="padding-left:0px !important"><div class="wizard_inactivo"></div></div>
            <div class="col-xs-12 col-sm-4 wizard_medio"><div id="firstStepWizard" class="wizard_activo registerForm">Canal</div></div>
            <div class="col-xs-12 col-sm-4 wizard_final" style="padding-right: 0px !important"><div class="wizard_inactivo"></div></div>
        </div>
        <?php if(session('error')): ?>
        <br>
        <div class="alert alert-warning">
            <center>
                <?php echo e(session('error')); ?>

            </center> 
        </div>
        <?php endif; ?>
        <?php if(session('errorMsg')): ?>
            <br>
            <div class="alert alert-danger">
                <center>
                    <?php echo session('errorMsg'); ?>

                </center> 
            </div>
        <?php endif; ?>
        <br>
        <div class="col-md-12">
            <div class="col-md-12">
                <form method="POST" action="<?php echo e(asset('/channel/store')); ?>">
                    <div id="firstStep">
                        <div class="col-md-12">
                            <div class="col-md-1">
                                <a class="btn btn-default registerForm" align="right" href="<?php echo e(asset('/channel')); ?>" style="margin-left: -30px;"> Cancelar </a>
                            </div>
                            <div class="col-md-1 col-md-offset-10">
                                <button type="submit" class="btn btn-info registerForm" align="right" style="float:right;margin-right: -30px;padding: 5px;width:80px"> Guardar </button>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;">
                            <div class="wizard_activo registerForm titleDivBorderTop">
                                <span class="titleLink">Datos del Canal</span>
                                <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                            </div>
                            <?php echo e(csrf_field()); ?>

                            <div class="col-md-6" style="margin-top:25px;">
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="ruc">RUC:</label>
                                    <input type="text" class="form-control" name="ruc" id="ruc" tabindex="1" placeholder="Ruc" value="<?php echo e(old('ruc')); ?>"  maxlength="13" required>
                                    <?php if(session('errorRuc')): ?> <p style="color:red;font-weight: bold"><?php echo e(session('errorRuc')); ?></p> <?php endif; ?>
                                </div> 
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="address">Dirección:</label>
                                    <input type="text" class="form-control" name="address" id="address" tabindex="3" placeholder="Dirección" value="<?php echo e(old('address')); ?>" required>
                                    <?php if(session('errorAddress')): ?> <p style="color:red;font-weight: bold"><?php echo e(session('errorAddress')); ?></p> <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="province">Provincia:</label>
                                    <select class="form-control" id="province" name="province" tabindex="5" required>
                                        <option value="">-- Escoja Una --</option>
                                        <?php $__currentLoopData = $provinces; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($prov->id); ?>" <?php if(old('province') == $prov->id): ?> selected <?php endif; ?>><?php echo e($prov->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php if(session('errorProvince')): ?> <p style="color:red;font-weight: bold"><?php echo e(session('errorProvince')); ?></p> <?php endif; ?>
                                </div> 
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="phone">Teléfono Fijo:</label>
                                    <input type="text" class="form-control" name="phone" id="phone" tabindex="7" placeholder="Teléfono Fijo" value="<?php echo e(old('phone')); ?>" required>
                                    <?php if(session('errorPhone')): ?> <p style="color:red;font-weight: bold"><?php echo e(session('errorPhone')); ?></p> <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <label class="registerForm" style="list-style-type:disc;" for="zip">Codigo Postal:</label>
                                    <input type="text" class="form-control" name="zip" id="zip" tabindex="9" placeholder="Codigo Postal" value="<?php echo e(old('zip')); ?>">
                                    <?php if(session('errorZip')): ?> <p style="color:red;font-weight: bold"><?php echo e(session('errorZip')); ?></p> <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="province">Usuario:</label>
                                    <select class="form-control" id="sucreUser" name="sucreUser" tabindex="11" required>
                                        <option value="">-- Escoja Una --</option>
                                        <?php $__currentLoopData = $sucreUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sucre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($sucre->id); ?>" <?php if(old('sucreUser') == $sucre->id): ?> selected <?php endif; ?>><?php echo e($sucre->first_name); ?> <?php echo e($sucre->last_name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php if(session('errorSucreUser')): ?> <p style="color:red;font-weight: bold"><?php echo e(session('errorSucreUser')); ?></p> <?php endif; ?>
                                </div> 
                            </div>
                            <div class="col-md-6" style="margin-top:25px;">
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="name">Nombre:</label>
                                    <input type="text" class="form-control" name="name" id="name" tabindex="2" placeholder="Nombre" value="<?php echo e(old('name')); ?>"  maxlength="13" required>
                                    <?php if(session('errorName')): ?> <p style="color:red;font-weight: bold"><?php echo e(session('errorName')); ?></p> <?php endif; ?>
                                </div> 
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="contact">Contacto:</label>
                                    <input type="text" class="form-control" name="contact" id="contact" tabindex="4" placeholder="Contacto" value="<?php echo e(old('contact')); ?>" required>
                                    <?php if(session('errorContact')): ?> <p style="color:red;font-weight: bold"><?php echo e(session('errorContact')); ?></p> <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="city">Ciudad:</label>
                                    <select class="form-control" id="city" name="city" tabindex="6" required>
                                        <?php if(old('city')): ?>
                                            <option value="<?php echo e(old('city')); ?>"><?php echo e(session('cityName')); ?></option>
                                        <?php else: ?>
                                            <option value="">-- Escoja Una --</option>
                                        <?php endif; ?>
                                    </select>
                                    <?php if(session('errorCity')): ?> <p style="color:red;font-weight: bold"><?php echo e(session('errorCity')); ?></p> <?php endif; ?>
                                </div> 
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="mobile_phone">Teléfono Celular:</label>
                                    <input type="text" class="form-control" name="mobile_phone" id="mobile_phone" tabindex="8" placeholder="Teléfono Celular" value="<?php echo e(old('mobile_phone')); ?>" required>
                                    <?php if(session('errorMobilePhone')): ?> <p style="color:red;font-weight: bold"><?php echo e(session('errorMobilePhone')); ?></p> <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="type">Tipo:</label>
                                    <select class="form-control" id="type" name="type" tabindex="10" required>
                                        <option selected="true" value="">-- Escoja Una --</option>
                                        <?php $__currentLoopData = $channelTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option <?php if($type->id == old('type')): ?> selected="true" <?php endif; ?> value="<?php echo e($type->id); ?>"><?php echo e($type->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php if(session('errorType')): ?> <p style="color:red;font-weight: bold"><?php echo e(session('errorType')); ?></p> <?php endif; ?>
                                </div> 
                            </div>
                        </div>
                        <div class="col-md-12" style="padding-bottom:15px">
                            <div class="col-md-1">
                                <a class="btn btn-default registerForm" align="right" href="<?php echo e(asset('/channel')); ?>" style="margin-left: -30px;"> Cancelar </a>
                            </div>
                            <div class="col-md-1 col-md-offset-10">
                                <button type="submit" class="btn btn-info registerForm" align="right" style="float:right;margin-right: -30px;padding: 5px;width:80px"> Guardar </button>
                            </div>
                        </div>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <!--      <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Calendario</h4>
                  </div>-->
            <div class="modal-body">
                <div id='loading'>loading...</div>
                <div id='calendar'></div>
            </div>
            <div class="modal-footer">
                <button id="modalCalendarClose" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views\channels\create.blade.php ENDPATH**/ ?>