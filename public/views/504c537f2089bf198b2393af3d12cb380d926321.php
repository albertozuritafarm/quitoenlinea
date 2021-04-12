<script src="<?php echo e(assets('js/sales/emit.js')); ?>"></script>
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
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
        <div class="col-xs-12 col-md-3 wizard_medio"><div id="firstStepWizard" class="wizard_activo registerForm">Resumen</div></div>
        <div class="col-xs-12 col-md-3 wizard_medio"><div id="secondStepWizard" class="wizard_inactivo registerForm">Emisión</div></div>
        <div class="col-xs-12 col-md-3 wizard_final"><div style="margin-right:-10px" class="wizard_inactivo registerForm"></div></div>
    </div>
    <div id="firstStep" class="col-md-12">                                
        <input type="hidden" id="saleId" name="saleId" value="<?php echo e($saleId); ?>">
        <input type="hidden" id="insuranceBranch" name="insuranceBranch" value="<?php echo e($insuranceBranch); ?>">
        <div class="col-md-12">
            <div class="col-md-12" style="margin-top:5px;padding-top:15px;">
                <div class="row" style="float:left">
                    <a class="btn btn-default registerForm" align="right" href="<?php echo e(asset('/sales')); ?>"> Cancelar </a>
                </div>
                <div class="row" style="float:right">
                    <a onclick="validateFirstStep()" class="btn btn-info registerForm" align="right"  href="#" style="float:right;;padding: 5px;color:white"> Siguiente <span class="glyphicon glyphicon-step-forward"></span></a>
                </div>
            </div>
            <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;">
                <div class="wizard_activo registerForm titleDivBorderTop" onclick="fadeToggle('resumeDiv')">
                    <a href="#" class="titleLink">Resumen de Venta</a>
                    <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                </div>
                <div id="resumeDiv" class="col-md-8 col-md-offset-2" style="padding-top: 25px;">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th colspan="2" style="background-color:#b3b0b0; text-align:center; border-top: 1px solid #ddd;">Producto</th>
                                <th style="background-color:#b3b0b0; text-align:center; border-top: 1px solid #ddd;">Prima</th>
                            </tr>
                        </thead>
                        <tbody style="width:50%;">
                            <?php echo $vehiTable; ?>

                            <?php echo $taxTable; ?>    
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-12" style="padding-bottom:15px">
                <div class="row" style="float:left">
                    <a class="btn btn-default registerForm" align="right" href="<?php echo e(asset('/sales')); ?>"> Cancelar </a>
                </div>
                <div class="row" style="float:right">
                    <a onclick="validateFirstStep()" class="btn btn-info registerForm" align="right"  href="#" style="float:right;padding: 5px;color:white"> Siguiente <span class="glyphicon glyphicon-step-forward"></span></a>
                </div>
            </div>
        </div>
    </div>
    <div id="secondStep" class="col-md-12 hidden" style="margin-top:20px;">
        <div class="col-md-12">
            <div class="col-md-12" style="margin-top:5px;padding-top:15px;">
                <div class="row" style="float:left">
                    <a class="btn btn-default registerForm" align="right" href="<?php echo e(asset('/sales')); ?>"> Cancelar </a>
                </div>
                <div class="row" style="float:right">
                    <a onclick="nextStep('secondStep', 'firstStep')" class="btn btn-default registerForm" align="right" href="#" style="background-color: #444;color:white"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                    <a class="btn btn-info registerForm" align="right"  href="#" style="padding: 5px;color:white;width:90px;" onclick="validateSecondStep()"> Emitir <span class="glyphicon glyphicon-step-forward"></span></a>
                </div>
            </div>
            <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;margin-bottom: 5px;margin-top:25px;">
                <div class="wizard_activo registerForm titleDiv" onclick="fadeToggle('secondStepDiv')">
                    <a href="#" class="titleLink">Datos de la Poliza</a>
                    <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                </div>
                <div id="secondStepDiv" class="col-md-12" style="padding-top: 25px;padding-bottom: 25px;">
                    <div class="col-md-6">
                        <div class="form-group">
                            <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="beginDate"> Fecha Inicio Vigencia</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                            <input type="date" class="form-control" name="beginDate" id="beginDate" placeholder="Correo" value="" style="line-height:14px" onchange="removeInputRedFocus('beginDate')">
                        </div>
                        <div class="form-group">
                            <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="bank"> Entidad Financiera</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                            <select id="bank" name="docbankument_id" class="form-control registerForm" required tabindex="2" onchange="removeInputRedFocus(this.id)">
                                <option selected="true" value="" disabled="disabled">--Escoja Una---</option>
                            </select>
                        </div>
                        <form method="post" id="upload_form" name="upload_form" enctype="multipart/form-data" onsubmit="uploadPictureForm()">
                            <?php echo e(csrf_field()); ?>

                            <center>
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="bank_value"> Factura del Vehículo</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <div class="alert" id="message" style="display: none"></div>
                                <div style="width:100px !important;padding: 0" class="inside" id="fileName"></div>
                                <div class="inputWrapper"><span id="uploaded_image"></span>
                                    <center>
                                        <img src="<?php echo e(asset('images/mas.png')); ?>" alt="Girl in a jacket" style="width:20px;height:20px;margin-bottom: -100px;">
                                    </center>
                                    <input class="fileInput" type="file" name="select_file" onchange="fileNameFunction()" id="select_file">
                                </div>
                            </center>
                            <center>
                                <button type="submit" name="upload" id="upload" class="btn btn-primary"onclick="uploadPictureForm()">
                                    <span class="glyphicon glyphicon-upload"></span> Subir Foto
                                </button>
                                <a class="hidden" id="deletePicture" href="#" onclick="deletePictureForm()">
                                    <img src="<?php echo e(asset('/images/menos.png')); ?>" style="width:20px;height:20px">
                                </a>  
                            </center>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="endDate"> Fecha Fin Vigencia</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                            <input type="date" class="form-control" name="endDate" id="endDate" placeholder="Correo" value="" style="line-height:14px" onchange="removeInputRedFocus('endDate')">
                        </div>
                        <div class="form-group">
                            <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="bank_value"> Valor Endoso</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                            <input type="text" class="form-control" name="bank_value" id="bank_value" placeholder="Valor Endoso" value="" style="line-height:14px" onchange="removeInputRedFocus('bank_value')">
                        </div>
                        <form method="post" id="upload_form" name="upload_form" enctype="multipart/form-data" onsubmit="uploadPictureForm()">
                            <?php echo e(csrf_field()); ?>

                            <center>
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="bank_value"> Carta del Concesionario</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <div class="alert" id="message" style="display: none"></div>
                                <div style="width:100px !important;padding: 0" class="inside" id="fileName"></div>
                                <div class="inputWrapper"><span id="uploaded_image"></span>
                                    <center>
                                        <img src="<?php echo e(asset('images/mas.png')); ?>" alt="Girl in a jacket" style="width:20px;height:20px;margin-bottom: -100px;">
                                    </center>
                                    <input class="fileInput" type="file" name="select_file" onchange="fileNameFunction()" id="select_file">
                                </div>
                            </center>
                            <center>
                                <button type="submit" name="upload" id="upload" class="btn btn-primary"onclick="uploadPictureForm()">
                                    <span class="glyphicon glyphicon-upload"></span> Subir Foto
                                </button>
                                <a class="hidden" id="deletePicture" href="#" onclick="deletePictureForm()">
                                    <img src="<?php echo e(asset('/images/menos.png')); ?>" style="width:20px;height:20px">
                                </a>  
                            </center>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                <div class="row" style="float:left">
                    <a class="btn btn-default registerForm" align="right" href="<?php echo e(asset('/sales')); ?>"> Cancelar </a>
                </div>
                <div class="row" style="float:right">
                    <a onclick="nextStep('secondStep', 'firstStep')" class="btn btn-default registerForm" href="#" style="background-color: #444;color:white"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                    <a class="btn btn-info registerForm" href="#" style="padding: 5px;color:white;width:90px;" onclick="validateSecondStep()"> Emitir <span class="glyphicon glyphicon-step-forward"></span></a>
                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\wamp64\www\magnussucre\resources\views\sales\emitForm.blade.php ENDPATH**/ ?>