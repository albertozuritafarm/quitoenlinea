

<?php $__env->startSection('content'); ?>
<!--<div class="se-pre-con"></div>-->
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
<script src="<?php echo e(assets('js/payments/createRemote.js')); ?>"></script>
<div class="container" style="width: 100%;padding: 20px;margin: 0px">
    <center>
        <div class="row">
            <div class="col-sm-8 col-sm-offset-4 border" style="margin-top:10px">
                <div class="row">
                    <div class="col-xs-12 registerForm" style="margin:0px;">
                        <center>
                            <h4 style="font-weight:bold">Por favor seleccione como desea pagar</h4>
                        </center>
                    </div>
                </div>
                <form action="<?php echo e(route('remotePaymentStore')); ?>" method="POST">
                    <div class="row">
                        <div class="col-md-12" style="padding-left:5px !important;padding-right: 5px">
                            <div class="col-md-4 wizard_inactivo registerForm">
                            </div>
                            <div id="cash" class="col-md-4 wizard_activo registerForm" onclick="cashDivClick()" style="cursor: pointer;">
                                <span><input id="cashRadioBtn" type="radio" name="option" value="cash" checked> Botón de Pagos</span>
                            </div>
                            <div class="col-md-4 wizard_inactivo registerForm">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div id="cashDiv" class="row" style="padding-left:5px !important;padding-right: 5px">
                        <?php echo e(csrf_field()); ?>

                        <input type="hidden" id="salId" name="salId" value="<?php echo e($sale->id); ?>">
                        <div class="col-sm-10">
                            <table id="saleResumeTable" class="table table-bordered">
                                <tbody>
                                    <tr style="background-color: #848484;color: white;">
                                        <th>Venta</th>
                                        <th>Fecha de Emisión</th>
                                        <th>Fecha Inicio Cobertura</th>
                                        <th>Fecha Fin Cobertura</th>
                                        <th>Estado</th>
                                    </tr>
                                    <tr>
                                        <td align="center"><?php echo e($sale->id); ?></td>
                                        <td align="center"><?php echo e($sale->emission_date); ?></td>
                                        <td align="center"><?php echo e($sale->begin_date); ?></td>
                                        <td align="center"><?php echo e($sale->end_date); ?></td>
                                        <td align="center"><?php echo e($status->name); ?></td>
                                    </tr>
                                    <tr style="background-color: #848484;color: white;">
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
                            <div class="col-md-10 col-md-offset-1">
                                <div class="form-group">
                                    <label for="number">Ingrese el numero de recibo</label>
                                    <input type="text" class="form-control" id="number" name="number" placeholder="Ingrese el numero de recibo">
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="chargeId" id="chargeId" value="<?php echo e($charge->id); ?>">
                        <div class="col-md-10 col-md-offset-1" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                            <a class="btn btn-default registerForm" align="left" href="<?php echo e(route('remote')); ?>" style="float:left"> Cancelar </a>
                            <input id="cancelBtn" type="submit" style="float:right;" class="btn btn-info registerForm" align="right" value="Pagar">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </center>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.remote_app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views\remote\payment_create.blade.php ENDPATH**/ ?>