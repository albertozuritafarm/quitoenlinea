

<?php $__env->startSection('content'); ?>
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
<script src="<?php echo e(assets('js/sales/paymentsCreate.js')); ?>"></script>
<link href="<?php echo e(assets('css/sales/create.css')); ?>" rel="stylesheet" type="text/css"/>
<div class="container-fluid" style="width: 100%">
    <div class="col-md-8 col-md-offset-2 border" style="margin-top:10px">
        <div class="row">
            <div class="col-xs-12 registerForm" style="margin:12px;">
                <center>
                    <h4 style="font-weight:bold">Por favor ingrese los datos de su tarjeta</h4>
                </center>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-2 wizard_inicial"><div style="margin-left:-10px" class="wizard_inactivo registerForm"></div></div>
            <div class="col-xs-12 col-md-3 wizard_medio"><div id="thirdStepWizard" class="wizard_activo registerForm">Datos para Factura</div></div>
            <div class="col-xs-12 col-md-2 wizard_medio"><div id="fourthStepWizard" class="wizard_inactivo registerForm">Pago</div></div>
            <div class="col-xs-12 col-md-3 wizard_medio"><div id="fourthStepWizard" class="wizard_inactivo registerForm">Resultado</div></div>
            <div class="col-xs-12 col-md-2 wizard_final"><div style="margin-right:-10px" class="wizard_inactivo registerForm"></div></div>
        </div>
        <form id="customerForm" action="<?php echo e(asset('/sales/payments/pay')); ?>" method="POST">
            <div class='row'>
                <div class="col-md-10 col-md-offset-1" style="margin-top:5px;padding-top:15px;">
                    <div class="row" style="float:left">
                        <!--<a class="btn btn-default registerForm" align="right" href="<?php echo e(asset('/sales')); ?>"> Cancelar </a>-->
                    </div>
                    <div class="row" style="float:right">
                        <!--<a href="<?php echo e(asset('')); ?>sales/emit/<?php echo e(Crypt::encrypt($sale->id)); ?>/<?php echo e(Crypt::encrypt($sale->customer_id)); ?>/0" class="btn btn-default registerForm"style="background-color: #444;color:white"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>-->
                        <button class="btn btn-info registerForm" type="submit" style="padding: 5px;color:white"> Siguiente <span class="glyphicon glyphicon-step-forward"></span></button>
                    </div>
                </div>
            </div>
            <div id="paymentStep">
                <?php echo $__env->make('payments.form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
            <div class='row'>
                <div class="col-md-10 col-md-offset-1" style="padding-bottom:15px">
                    <div class="row" style="float:left">
                        <!--<a class="btn btn-default registerForm" align="right" href="<?php echo e(asset('/sales')); ?>"> Cancelar </a>-->
                    </div>
                    <div class="row" style="float:right">
                        <!--<a class="btn btn-default registerForm" href="<?php echo e(asset('')); ?>sales/emit/<?php echo e(Crypt::encrypt($sale->id)); ?>/<?php echo e(Crypt::encrypt($sale->customer_id)); ?>/0"  style="background-color: #444;color:white"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>-->
                        <button class="btn btn-info registerForm" type="submit" style="padding: 5px;color:white"> Siguiente <span class="glyphicon glyphicon-step-forward"></span></button>
                    </div>
                </div>
            </div>
        </form>
        <form class="hidden" id="paymentForm" action="<?php echo e(asset('/sales/payments/pay')); ?>" method="POST">
            <?php echo e(csrf_field()); ?>

            <input type="hidden" id="chargeId" name="chargeId" value="<?php echo e($charge->id); ?>">
            <button id="formBtn"></button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.remote_app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views\sales\paymentsCreate.blade.php ENDPATH**/ ?>