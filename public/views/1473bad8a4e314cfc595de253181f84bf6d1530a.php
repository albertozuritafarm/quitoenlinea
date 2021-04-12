

<?php $__env->startSection('content'); ?>

<!--<form id="FLogin" name="FLogin" method="POST" action="<?php echo e(route('login')); ?>">-->
<form method="POST" action="<?php echo e(asset('/login/recover')); ?>">
    <?php echo e(csrf_field()); ?>


    <div align="center" style="margin-bottom:60px">
        <img src="<?php echo e(assets('images/logo_seguros_sucre.jpg')); ?>" class="img-responsive" width="100%">
    </div>
    <div class="content">
        <div class="content">
            <div>
                <?php if(session('Recover')): ?>
                <div class="alert alert-success">
                    <?php echo e(session('Recover')); ?>

                </div>
                <?php endif; ?>
                <div class="form-group" align="left">
                    <label class="control-label">Correo</label>
                    <input id="email" type="email" class="form-control<?php echo e($errors->has('email') ? ' is-invalid' : ''); ?>" name="email" value="<?php echo e(old('email')); ?>" placeholder="Ingrese el Correo" required autofocus>
                    <?php if($errors->has('email')): ?>
                    <span class="invalid-feedback" role="alert">
                        <strong><?php echo e($errors->first('email')); ?></strong>
                    </span>
                    <?php endif; ?>
                </div>
                <div class="MensajeError" style="display:none" id="DIVErrorTextLogin"><label id="LErrorTextLogin"></label></div>
                <div class="MensajeError" style="display:none; margin-bottom:30px" id="DIVErrorTextPassword"><label id="LErrorTextPassword"></label></div>
            </div>
        </div>

        <button type="submit" class="btn btn-info" style="float:right">Recuperar</button>
        <a href="<?php echo e(asset('login')); ?>" class="btn btn-default" type="button" style="float:left">Cancelar</a>
        <!--<button type="button" class="btn btn-default" style="float:left">Cancelar</button>-->
    </div>
    <div>
    </div>
</form>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app_login', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views\auth\recover.blade.php ENDPATH**/ ?>