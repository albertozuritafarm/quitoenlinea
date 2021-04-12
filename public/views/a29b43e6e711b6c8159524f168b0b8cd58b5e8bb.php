<form id="paymentForm" action="<?php echo e(asset('/payments/store')); ?>" method="POST">
    <input type="hidden" id="redirect" name="redirect" value="sales">
    <input type="hidden" id="option" name="option" value="creditCard">
    <div id="cashDiv" class="row">
        <?php echo e(csrf_field()); ?>

        <input type="hidden" id="salId" name="salId" value="<?php echo e($sale->id); ?>">
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
                <span class="titleLink">Pago</span>
                <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
            </div>
            <div class="col-md-12" style="padding-top:25px;">
                <div class="col-md-6">
                    <div class="form-group">
                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Número de tarjeta</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                        <input type="text" class="form-control" id="number" name="number" placeholder="Ingrese el número de tarjeta">
                    </div>
                    <div class="form-group">
                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Mes de expiración</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                        <select id="month" name="month" class="form-control">
                            <option value="">-- Selecione --</option>
                            <option value="1">01 - Enero</option>
                            <option value="2">02 - Febrero</option>
                            <option value="3">03 - Marzo</option>
                            <option value="4">04 - Abril</option>
                            <option value="5">05 - Mayo</option>
                            <option value="6">06 - Junio</option>
                            <option value="7">07 - Julio</option>
                            <option value="8">08 - Agosto</option>
                            <option value="9">09 - Septiembre</option>
                            <option value="10">10 - Octubre</option>
                            <option value="11">11 - Noviembre</option>
                            <option value="12">12 - Diciembre</option>
                        </select> 
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="cvc">CVC </label>&nbsp;<label class="own"><span class="glyphicon glyphicon-info-sign" style="color:#0099ff"><span class="own1" style="float:left"><span style="color:#0099ff;font-weight: bold">Visa/MasterCard</span> <br> Los 3 últimos dígitos al reverso de la tarjeta. <br> <br> <span style="color:#0099ff;font-weight: bold">American Express</span> <br> Los 4 dígitos en la parte delantera.</span></span></label>
                        <input type="text" class="form-control" id="cvc" name="cvc" placeholder="Ingrese el CVC">
                    </div>
                    <div class="form-group">
                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Año de expiración</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                        <select id="year" name="year" class="form-control">
                            <option value="">-- Seleccione --</option>
                            <option value="2020">2020</option>
                            <option value="2021">2021</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                            <option value="2026">2026</option>
                            <option value="2027">2027</option>
                            <option value="2028">2028</option>
                            <option value="2029">2029</option>
                            <option value="2030">2030</option>
                        </select> 
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="chargeId" id="chargeId" value="<?php echo e($charge->id); ?>">
</form><?php /**PATH C:\wamp64\www\magnussucre\resources\views\payments\pay.blade.php ENDPATH**/ ?>