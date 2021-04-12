

<?php $__env->startSection('content'); ?>
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
<script src="<?php echo e(assets('js/sales/paymentsCreate.js')); ?>"></script>
<link href="<?php echo e(assets('css/sales/create.css')); ?>" rel="stylesheet" type="text/css"/>
<div class="container-fluid" style="width: 100%">
    <div class="col-md-8 col-md-offset-2 border" style="margin-top:10px">
        <div class="row">
            <div class="col-xs-12 registerForm" style="margin:12px;">
                <center>
                    <h4 style="font-weight:bold">Anulacion del pago</h4>
                </center>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-2 wizard_inicial"><div style="margin-left:-10px" class="wizard_inactivo registerForm"></div></div>
            <div class="col-xs-12 col-md-3 wizard_medio"><div id="thirdStepWizard" class="wizard_inactivo registerForm"></div></div>
            <div class="col-xs-12 col-md-2 wizard_medio"><div id="fourthStepWizard" class="wizard_activo registerForm">Resultado</div></div>
            <div class="col-xs-12 col-md-3 wizard_medio"><div id="fifthStepWizard" class="wizard_inactivo registerForm"></div></div>
            <div class="col-xs-12 col-md-2 wizard_final"><div style="margin-right:-10px" class="wizard_inactivo registerForm"></div></div>
        </div>
        <form id="customerForm" action="<?php echo e(asset('/sales/payments/pay')); ?>" method="POST">
            <div id="">
                <?php if($success == 'true'): ?>
                    <div class="alert alert-success">
                        La anulación fue procesada correctamente .
                    </div>
                <?php else: ?>
                <div class="alert alert-danger">
                    El pago no pudo ser procesado correctamente.<br>
                    Codigo: <?php echo e($code); ?><br>
                    Mensaje: <?php echo e($message); ?>

                </div>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views\payments\paymentsResult.blade.php ENDPATH**/ ?>