

<?php $__env->startSection('content'); ?>
<script src="<?php echo e(assets('js/registerCustom.js')); ?>"></script>
<script src="<?php echo e(assets('js/massive/create.js')); ?>"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

<style>
    .form-group{
        margin-top:25px !important;
        margin-bottom: 25px !important;
    }

</style>
<div class="container" style="margin-top:15px; font-size:14px !important">
    <!--<div class="row justify-content-center border" style="margin-left:20%;">-->
    <div class="col-md-8 col-md-offset-2 border" style="padding: 15px">
        <div class="row">
            <div class="col-xs-12 registerForm" style="margin:12px;">
                <center>
                    <h4 style="font-weight:bold">Registro de Venta Masiva</h4>
                    <!--<h5>Datos del Cliente.</h5>-->
                </center>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-4 wizard_inicial" style="padding-left:0px !important"><div class="wizard_inactivo"></div></div>
            <div class="col-xs-12 col-sm-4 wizard_medio"><div class="wizard_activo registerForm">Masivos</div></div>
            <div class="col-xs-12 col-sm-4 wizard_final" style="padding-right: 0px !important"><div class="wizard_inactivo"></div></div>
        </div>
        <?php if( Session::has('excelError') ): ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <ul class="list-group">
                <?php $__currentLoopData = $errors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error['msg']); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul> 
        </div>
        <?php endif; ?>
        <?php if( Session::has('error') ): ?>
        <div class="alert alert-danger alert-dismissible" role="alert" style="margin-top:5px">
            <center>
                <strong>
                    <?php echo e(Session::get('error')); ?>

                </strong>
            </center>
        </div>
        <?php endif; ?>
        <?php if( Session::has('storeSsuccess') ): ?>
        <div class="alert alert-success alert-dismissible" role="alert"  style="margin-top:5px">
            <center>
                <strong>
                    <?php echo e(Session::get('success')); ?> 
                </strong>
            </center>
        </div>
        <?php endif; ?>

        <form action="<?php echo e(route('massive/store')); ?>" method="POST" enctype="multipart/form-data" id="uploadForm"  onsubmit="validateUploadExcel()">
            <?php echo e(csrf_field()); ?>

            <div class="col-md-12" style="margin-top:5px;padding-top:15px;">
                <a class="btn btn-default registerForm" align="left" href="<?php echo e(asset(session('massiveIndex'))); ?>" style="margin-left:-15px"> Cancelar </a>
                <input id="submitFormBtn" type="submit" style="float:right;padding: 5px;width:75px;margin-right: -15px" class="btn btn-info registerForm hidden" align="right" value="Guardar">
            </div>
            <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;">
                <div class="wizard_activo registerForm titleDivBorderTop">
                    <span class="titleLink">Carga Masiva</span>
                    <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="product">Productos</label>
                        <select class="form-control" id="product" name="product">
                            <option selected="true" value="">--Escoga Uno--</option>
                            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($product->id); ?>"><?php echo e($product->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="agency">Canal:</label>
                        <select class="form-control" id="channel" name="channel">
                            <option selected="true" value="">--Escoga Uno--</option>
                            <?php $__currentLoopData = $channels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $channel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($channel->id); ?>"><?php echo e($channel->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="file-upload" for="file">Archivo:</label>
                        <input type="file" name="file" required>
                        <br>
                        Click
                        <a href="<?php echo e(asset('/massive/download/upload/format')); ?>" target="blank" title="Descargar Archivo Cargado"> 
                            AQUÍ
                        </a>
                        para descargar un archivo de muestra
                    </div>  
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="value">Valor:</label>
                        <input class="form-control" type="asd" name="value" id="value" placeholder="Introduza el valor sin IVA" onkeyup="validateValue()">
                    </div> 
                    <div class="form-group">
                        <label for="channel">Agencia:</label>
                        <select class="form-control" id="agency" name="agency">
                            <option selected="true" value="">--Escoga Uno--</option>
                        </select>
                    </div>
                    <div class="hidden" id="validateErrorDiv" style="margin-top:30px">
                        <span id="validateErrorMessage">

                        </span>
                    </div>
                    <input type="submit" style="float:left;padding: 5px;width:100px;margin-right: -15px;margin-top: 10px" class="btn btn-info registerForm" align="left" value="Validar Excel" onsubmit="validateUploadExcel()">
                
                </div>
            </div>
            <div class="col-md-12" style="padding-bottom:15px">
                <a class="btn btn-default registerForm" align="left" href="<?php echo e(asset(session('massiveIndex'))); ?>" style="margin-left:-15px"> Cancelar </a>
                <input id="submitFormBtn2" type="submit" style="float:right;padding: 5px;width:75px;margin-right: -15px" class="btn btn-info registerForm hidden" align="right" value="Guardar">
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views\massive\create.blade.php ENDPATH**/ ?>