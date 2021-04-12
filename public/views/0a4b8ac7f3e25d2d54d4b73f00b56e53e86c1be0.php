

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-md-offset-3 center">
            <!--<h1>Bienvenido a su sistema HIT</h1>-->
            

            <!--<div class="card-body">-->


            <!--</div>-->
        </div>
        <br>
        <div class="col-md-5 col-md-offset-3">
            <?php if(session('ValidateUserRoute')): ?>
            <div class="alert alert-danger">
                <center><strong>
                    <?php echo e(session('ValidateUserRoute')); ?>


                    </strong></center>
            </div>
            <?php endif; ?>
            
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views\home.blade.php ENDPATH**/ ?>