<script src="<?php echo e(assets('js/sales/R1/emit.js')); ?>"></script>
<div class="col-md-8 col-md-offset-2 border">
    <div class="row">
        <div class="col-xs-12 registerForm" style="margin:12px;">
            <center>
                <h4 style="font-weight:bold">Emitir la Venta</h4>
            </center>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-3 wizard_inicial"><div style="margin-left:-10px" class="wizard_inactivo registerForm"></div></div>
        <div class="col-xs-12 col-md-3 wizard_medio"><div id="firstStepWizard" class="wizard_inactivo registerForm">Resumen</div></div>
        <div class="col-xs-12 col-md-3 wizard_medio"><div id="secondStepWizard" class="wizard_activo registerForm">Emisión</div></div>
        <div class="col-xs-12 col-md-3 wizard_final"><div style="margin-right:-10px" class="wizard_inactivo registerForm"></div></div>
    </div>
    <div id="secondStep" class="col-md-12">
        <div class="col-md-12">
            <div class="col-md-12" style="margin-top:5px;padding-top:15px;">
                <div class="row" style="float:left">
                    <a class="btn btn-default registerForm" align="right" href="<?php echo e(asset('/sales')); ?>"> Cancelar </a>
                </div>
                <div class="row" style="float:right">
                    <a onclick="previous('<?php echo e($saleId); ?>','<?php echo e($document); ?>')" class="btn btn-back registerForm" align="right" href="#"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                    <a class="btn btn-info registerForm" align="right"  href="#" onclick="validateSecondStep()"> Emitir </a>
                </div>
            </div>
            <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;">
                <div class="wizard_activo registerForm titleDivBorderTop" onclick="fadeToggle('secondStepDiv')">
                    <a href="#" class="titleLink">Datos de la Poliza</a>
                    <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                </div>
                <div id="secondStepDiv" class="col-md-12" style="padding-top: 25px;padding-bottom: 25px;">
                    <form>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="beginDate"> Fecha Inicio Vigencia</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="date" class="form-control" name="beginDate" id="beginDate" placeholder="Correo" value="" style="line-height:14px" onchange="removeInputRedFocus('beginDate')">
                            </div>
                            <div class="form-group col-md-6">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="endDate"> Fecha Fin Vigencia</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="date" class="form-control" name="endDate" id="endDate" placeholder="Correo" value="" style="line-height:14px" onchange="removeInputRedFocus('endDate')">
                            </div>
                        </div>
                        <div class="form-group">
                            <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="bank"> Entidad Financiera</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                            <select id="bank" name="docbankument_id" class="form-control registerForm" required tabindex="2" onchange="removeInputRedFocus(this.id)">
                                <option selected="true" value="" disabled="disabled">--Escoja Una---</option>
                            </select>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> RUC</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <!--<input type="text" class="form-control registerForm" name="document" id="document" placeholder="Cédula" value="<?php echo e(old('document')); ?>" required="required">-->
                                <div class="form-inline">
                                    <input autocomplete="off" type="text" class="form-control registerForm" name="document" id="document" value="<?php echo e($customer->document); ?>" <?php if($disabled): ?> disabled="disabled" <?php else: ?> <?php endif; ?> placeholder="Cédula" required="required"tabindex="1" style="width:89%">
                                    <button type="button" class="btn btn-info" onclick="documentBtn()" <?php if($disabled): ?> disabled="disabled" <?php else: ?> <?php endif; ?> style="width:10%"><span class="glyphicon glyphicon-search"></span></button>
                                    <div id="suggesstion-box"></div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="businessName"> Razón Social</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="date" class="form-control" name="businessName" id="businessName" placeholder="" value="" style="line-height:14px" onchange="">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="tradename"> Nombre Comercial</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="date" class="form-control" name="tradename" id="tradename" placeholder="" value="" style="line-height:14px" onchange="">
                            </div>
                            <div class="form-group col-md-6">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="endorsementAmount"> Monto Endoso Beneficiario</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="date" class="form-control" name="endorsementAmount" id="endorsementAmount" placeholder="endorsementAmount" value="" style="line-height:14px" onchange="">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-12" style="padding-bottom:15px">
                <div class="row" style="float:left">
                    <a class="btn btn-default registerForm" align="right" href="<?php echo e(asset('/sales')); ?>"> Cancelar </a>
                </div>
                <div class="row" style="float:right">
                    <a onclick="previous('<?php echo e($saleId); ?>','<?php echo e($document); ?>')" class="btn btn-back registerForm" href="#"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                    <a class="btn btn-info registerForm" href="#" onclick="validateSecondStep()"> Emitir </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="hidden">
    <form action="<?php echo e(asset('/sales/payments/create')); ?>" method="POST">
        <?php echo e(csrf_field()); ?>

        <input type="hidden" id="chargeId" name="chargeId" value="">
        <input id="formBtn" type="submit" class="btn btn-info" style="float:right" value="SI">
    </form>
</div><?php /**PATH C:\wamp64\www\magnussucre\resources\views\sales\R4\emit_old.blade.php ENDPATH**/ ?>