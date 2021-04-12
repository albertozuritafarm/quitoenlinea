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
                                <input type="date" class="form-control" name="beginDate" id="beginDate" placeholder="Correo" value="" style="line-height:14px" onchange="removeInputRedFocus('beginDate'), changeEndDate(this.value)">
                            </div>
                            <div class="form-group col-md-6">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="endDate"> Fecha Fin Vigencia</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="date" class="form-control" name="endDate" id="endDate" placeholder="Correo" value="" style="line-height:14px" onchange="removeInputRedFocus('endDate')" disabled="disabled">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;margin-bottom: 5px;margin-top:25px;">
                    <div class="wizard_activo registerForm titleDivBorderTop" onclick="fadeToggle('beneficiaryForm')">
                        <a href="#" class="titleLink">Beneficiarios (Opcional)</a>
                        <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                    </div>
                    <div id="beneficiaryForm" class="col-md-12" style="padding-top: 25px;padding-bottom: 25px;">
                        <div class="col-md-6">
                            <div class="form-group form-inline">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Identificación</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <div class="form-inline">
                                    <input autocomplete="off" type="text" class="form-control registerForm" name="document" id="document" value="" placeholder="Identificación" required="required"tabindex="1" style="width:88%" onclick="clearForm()" onkeydown="clearForm()">
                                    <button type="button" class="btn btn-info" onclick="validateBeneficiary();" style="width:10%"><span class="glyphicon glyphicon-search"></span></button>
                                    <div id="suggesstion-box"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="first_name"> Nombre(s)</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" class="form-control registerForm" name="first_name" id="first_name" placeholder="Nombre" value="" required="required" tabindex="3" disabled="disabled">
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Porcentaje</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" class="form-control registerForm" name="porcentage" id="porcentage" placeholder="Porcentaje" value="" required tabindex="6" onkeypress="return onlyNumbers(event, this)">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document_id"> Tipo Documento</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <select id="document_id" name="document_id" class="form-control registerForm" value="<?php echo e(old('document_id')); ?>" required tabindex="2" disabled="disabled" onchange="documentIdChange(this.value)">
                                    <option selected="true" value="0">--Escoja Una---</option>
                                    <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($document->id); ?>"><?php echo e($document->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="last_name"> Apellido(s)</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" class="form-control registerForm" name="last_name" id="last_name" placeholder="Apellido" value="" required tabindex="4" disabled="disabled">
                            </div>
                            <div class="form-group">
                                <a onclick="addBeneficiary()" class="btn btn-success registerForm" align="right" href="#" style="float:right;margin-right: 0px;padding: 5px;margin-top: 25px;width:100px"><span class="glyphicon glyphicon-plus"></span>Agregar </a>
                            </div>
                        </div>
                        <div class="col-md-10 col-md-offset-1">
                            <table id="beneficiaryTable" class="table table-striped table-bordered" style="border-collapse: separate !important">
                                <thead>
                                    <tr>
                                        <th style="background-color:#b3b0b0">Documento</th>
                                        <th style="background-color:#b3b0b0">Tipo</th>
                                        <th style="background-color:#b3b0b0">Nombre</th>
                                        <th style="background-color:#b3b0b0">Apellido</th>
                                        <th style="background-color:#b3b0b0">Porcentaje</th>
                                        <th style="background-color:#b3b0b0">Editar</th>
                                        <th style="background-color:#b3b0b0">Quitar</th>
                                    </tr>
                                </thead>
                                <tbody id="beneficiaryBodyTable" style="align-content: center;">
                                    <?php echo $tableData; ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
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
</div><?php /**PATH C:\wamp64\www\magnussucre\resources\views\sales\R3\emit.blade.php ENDPATH**/ ?>