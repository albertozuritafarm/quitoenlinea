

<?php $__env->startSection('content'); ?>
<script src="<?php echo e(assets('js/registerCustom.js')); ?>"></script>

<div class="container" style="margin-top:15px; font-size:14px !important">
    <!--<div class="row justify-content-center border" style="margin-left:20%;">-->
    <div class="col-md-8 col-md-offset-2 border" style="padding: 15px">
        <div class="row">
            <div class="col-xs-12 registerForm" style="margin:12px;">
                <center>
                    <h4 style="font-weight:bold">Actualizar datos del Usuario</h4>
                    <!--<h5>Datos del Cliente.</h5>-->
                </center>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-4 wizard_inicial" style="padding-left:0px !important"><div class="wizard_inactivo"></div></div>
            <div class="col-xs-12 col-sm-4 wizard_medio"><div class="wizard_activo registerForm">Actualizar Contraseña</div></div>
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
        <?php if(session('success')): ?>
        <br>
        <div class="alert alert-success">
            <center>
                <?php echo e(session('success')); ?>

            </center>
        </div>
        <?php endif; ?>
        <form method="POST" action="<?php echo e(asset('/user/password/change')); ?>">
            <div class="col-md-8 col-md-offset-2 border" style="margin-top:15px">
                <?php echo e(csrf_field()); ?>

                <input name="id" type="hidden">
                <div class="form-group">
                    <label style="list-style-type:disc;" for="first_name">Contraseña</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Contraseña" value="" required>
                </div>
                <div class="form-group">
                    <label style="list-style-type:disc;" for="first_name">Confirmar Contraseña</label>
                    <input type="password" class="form-control" name="passwordCheck" id="passwordCheck" placeholder="Confirme la Contraseña" value="" required>
                </div>
            </div>
            <div class="col-md-8 col-md-offset-2" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                <a class="btn btn-default registerForm" align="left" href="<?php echo e(asset('/home')); ?>" style="margin-left: -15px"> Cancelar </a>
                <input type="submit" style="float:right;margin-right: -15px;padding: 5px" class="btn btn-info registerForm" align="right" value="Actualizar">

            </div>
        </form>
            
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views\user\passwordChange.blade.php ENDPATH**/ ?>