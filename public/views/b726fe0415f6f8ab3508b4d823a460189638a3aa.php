

<?php $__env->startSection('content'); ?>
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
<script src="<?php echo e(assets('js/sales/paymentsCreate.js')); ?>"></script>
<link href="<?php echo e(assets('css/sales/create.css')); ?>" rel="stylesheet" type="text/css"/>
<?php if($charge->value < 400): ?>
<script>
var wpwlOptions = {
    locale: "es",
    style: "card",
    // Optional. Use SVG images, if available, for better quality.
    imageStyle: "svg",

    onReady: function () {
          
        var datafast= '<br/><br/><img src='+'"https://www.datafast.com.ec/images/verified.png" style='+'"display:block;margin:0 auto; width:100%;">'; 
        $('form.wpwl-form-card').find('.wpwl-button'). before(datafast); 
    }
};
</script>
<?php else: ?>
<script>
var wpwlOptions = {
    locale: "es",
    style: "card",
    // Optional. Use SVG images, if available, for better quality.
    imageStyle: "svg",

    onReady: function () {
          
        var numberOfInstallmentsHtml = '<div class="wpwl-label wpwl-label-custom" style="display:inline-block">Diferidos:</div>' + 
              '<div class="wpwl-wrapper wpwl-wrapper-custom" style="display:inlineblock">' + 
              '<select name="recurring.numberOfInstallments"><option value="0">0</option><option value="1">1</option>'+ 
              '<option value="2">2</option><option value="3">3</option>' + 
              '<option value="4">4</option><option value="5">5</option>' + 
              '<option value="6">6</option><option value="7">7</option>' + 
              '<option value="8">8</option><option value="9">9</option>' + 
              '<option value="10">10</option><option value="11">11</option>' + 
              '</option><option value="12">12</option></select>' + 
              '</div>';  
      $('form.wpwl-form-card').find('.wpwl-button').before(numberOfInstallmentsHtml);  
      
        var datafast= '<br/><br/><img src='+'"https://www.datafast.com.ec/images/verified.png" style='+'"display:block;margin:0 auto; width:100%;">'; 
        $('form.wpwl-form-card').find('.wpwl-button'). before(datafast);
        
      var frecuente = '<div class="wpwl-label wpwl-label-custom" style="display:none">Intereses:</div>' + 
              '<div class="wpwl-wrapper wpwl-wrapper-custom" style="display:none">' + 
              '<select name="customParameters[SHOPPER_interes]" style="display:none"><option value="0">No</option><option value="1">Si</option></select>' + 
              '</div>';  
            $('form.wpwl-form-card').find('.wpwl-button').before(frecuente); 
    }
};
</script>
<?php endif; ?>
<style>

    .dots-hidden { display: none; }
</style>
<script src="https://test.oppwa.com/v1/paymentWidgets.js?checkoutId=<?php echo e($checkoutId); ?>"></script>
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
            <div class="col-xs-12 col-md-3 wizard_medio"><div id="thirdStepWizard" class="wizard_inactivo registerForm">Datos para Factura</div></div>
            <div class="col-xs-12 col-md-2 wizard_medio"><div id="fourthStepWizard" class="wizard_activo registerForm">Pago</div></div>
            <div class="col-xs-12 col-md-3 wizard_medio"><div id="fifthStepWizard" class="wizard_inactivo registerForm">Resultado</div></div>
            <div class="col-xs-12 col-md-2 wizard_final"><div style="margin-right:-10px" class="wizard_inactivo registerForm"></div></div>
        </div>
        <div class="col-md-10 col-md-offset-1" style="margin-top:5px;padding-top:15px;">
            <div class="row" style="float:left">
                <!--<a class="btn btn-default registerForm" align="right" href="<?php echo e(asset('/sales')); ?>"> Cancelar </a>-->
            </div>
            <div class="row" style="float:right">
                <a href="<?php echo e(asset('/sales/payments/create?document='.\encrypt($customer->document).'&sales='.\encrypt($sale->id))); ?>" class="btn btn-back registerForm"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                <!--<a class="btn btn-info registerForm" onclick="emitPayment()"> Pagar </a>-->
            </div>
        </div>
        <div class="col-xs-10 col-md-10 col-md-offset-1 border" style="padding-left:0px !important;">
            <div class="wizard_activo registerForm titleDivBorderTop">
                <span class="titleLink">Resumen de la Venta</span>
                <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
            </div>
            <div class="col-md-12" style="margin-top:15px">
                <table id="saleResumeTable" class="table table-bordered">
                    <tbody>
                        <tr style="background-color: #183c6b;color: white;">
                            <th>Venta</th>
                            <th>Fecha de Emisión</th>
                            <th>Fecha Inicio Cobertura</th>
                            <th>Fecha Fin Cobertura</th>
                            <th>Estado</th>
                        </tr>
                        <tr>
                            <td align="center"><?php echo e($sale->id); ?></td>
                            <td align="center"><?php echo e(date('d-m-Y',strtotime($sale->emission_date))); ?></td>
                            <td align="center"><?php echo e(date('d-m-Y',strtotime($sale->begin_date))); ?></td>
                            <td align="center"><?php echo e(date('d-m-Y',strtotime($sale->end_date))); ?></td>
                            <td align="center"><?php echo e($status->name); ?></td>
                        </tr>
                        <tr style="background-color: #183c6b;color: white;">
                            <th>Subtotal 12%</th>
                            <th>Subtotal 0%</th>
                            <th>Impuesto</th>
                            <th colspan="2">Total</th>
                        </tr>
                        <tr>
                            <td align="center"><?php echo e($sale->subtotal_12); ?></td>
                            <td align="center"><?php echo e($sale->subtotal_0); ?></td>
                            <td align="center"><?php echo e($sale->tax); ?></td>
                            <td align="center" colspan="2"><?php echo e($sale->total); ?></td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
        <div class="col-xs-10 col-md-10 col-md-offset-1 border" style="padding-left:0px !important;margin-top:25px;">
            <div class="wizard_activo registerForm titleDivBorderTop">
                <span class="titleLink">Datos de la Tarjeta</span>
                <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
            </div>
            <div style="margin-top:15px;">
                <form action="<?php echo e(asset('/sales/payments/pay/result')); ?>" class="paymentWidgets" data-brands="VISA MASTER AMEX DINERS DISCOVER"></form>

            </div>
        </div>
        <div class="col-md-10 col-md-offset-1">
            <div class="row" style="float:left">
                <!--<a class="btn btn-default registerForm" align="right" href="<?php echo e(asset('/sales')); ?>"> Cancelar </a>-->
            </div>
            <div class="row" style="float:right">
                <a href="<?php echo e(asset('/sales/payments/create?document='.\encrypt($customer->document).'&sales='.\encrypt($sale->id))); ?>" class="btn btn-back registerForm"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                <!--<a class="btn btn-info registerForm" onclick="emitPayment()"> Pagar </a>-->
            </div>
        </div>
        <form class="hidden" id="paymentForm" action="<?php echo e(asset('/sales/payments/create')); ?>" method="POST">
            <?php echo e(csrf_field()); ?>

            <input type="hidden" id="chargeId" name="chargeId" value="<?php echo e($sale->id); ?>">
            <button id="formBtn"></button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.remote_app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views\sales\paymentsCreate2.blade.php ENDPATH**/ ?>