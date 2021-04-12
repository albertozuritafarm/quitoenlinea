

<?php $__env->startSection('content'); ?>
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
<script src="<?php echo e(assets('js/payments/refund.js')); ?>"></script>
<link href="<?php echo e(assets('css/sales/create.css')); ?>" rel="stylesheet" type="text/css"/>
<style>

    .dots-hidden { display: none; }
    /* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
</style>
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
            <div class="col-xs-12 col-md-3 wizard_inicial"><div style="margin-left:-10px" class="wizard_inactivo registerForm"></div></div>
            <div class="col-xs-12 col-md-3 wizard_medio"><div id="thirdStepWizard" class="wizard_activo registerForm">Datos para Anulacion</div></div>
            <div class="col-xs-12 col-md-3 wizard_medio"><div id="fourthStepWizard" class="wizard_inactivo registerForm">Resultado</div></div>
            <div class="col-xs-12 col-md-3 wizard_final"><div style="margin-right:-10px" class="wizard_inactivo registerForm"></div></div>
        </div>
        <form id="paymentForm" action="<?php echo e(asset('/payments/refund/store')); ?>" method="POST">
        <?php echo e(csrf_field()); ?>

        <input type="hidden" id="sale_id" name="sale_id" value="<?php echo e($sale->id); ?>">
            <div class="col-md-10 col-md-offset-1" style="margin-top:5px;padding-top:15px;">
                <div class="row" style="float:left">
                    <a class="btn btn-default registerForm" align="right" href="<?php echo e(asset('/payments')); ?>"> Cancelar </a>
                </div>
                <div class="row" style="float:right">
                    <input type="submit" name="submit_1" class="btn btn-info registerForm" value="Anular">
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
                                <td align="center"><?php echo e(date('d-m-Y h:i:s',strtotime($sale->emission_date))); ?></td>
                                <td align="center"><?php echo e($sale->begin_date); ?></td>
                                <td align="center"><?php echo e($sale->end_date); ?></td>
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
                <div class="col-md-12" style="margin-top:15px;">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="form-group">
                            <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="card_number"> Numero de Tarjeta</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                            <input type="number" class="form-control registerForm" name="card_number" id="card_number" placeholder="Numero de Tarjeta" value="" required="required" tabindex="1">
                        </div>
                    </div>
                    <div class="col-md-3 col-md-offset-3">
                        <div class="form-group">
                            <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="card_number"> Año</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                            <select class="form-control" id="year" name="year">
                                <option value="">-- Escoja Una --</option>
                                <option value="20">2020</option>
                                <option value="21">2021</option>
                                <option value="22">2022</option>
                                <option value="23">2023</option>
                                <option value="24">2024</option>
                                <option value="25">2025</option>
                                <option value="26">2026</option>
                                <option value="27">2027</option>
                                <option value="28">2028</option>
                                <option value="29">2029</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="card_number"> Mes</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                            <select class="form-control" id="month" name="month">
                                <option value="">-- Escoja Una --</option>
                                <option value="01">1</option>
                                <option value="02">2</option>
                                <option value="03">3</option>
                                <option value="04">4</option>
                                <option value="05">5</option>
                                <option value="06">6</option>
                                <option value="07">7</option>
                                <option value="08">8</option>
                                <option value="09">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                            </select>                        
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-10 col-md-offset-1">
                <div class="row" style="float:left">
                    <a class="btn btn-default registerForm" align="right" href="<?php echo e(asset('/payments')); ?>"> Cancelar </a>
                </div>
                <div class="row" style="float:right">
                    <input type="submit" name="submit_2" class="btn btn-info registerForm" value="Anular">
                </div>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views\payments\refund.blade.php ENDPATH**/ ?>