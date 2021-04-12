<script src="<?php echo e(assets('js/sales/customer.js')); ?>"></script>
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
                        <td align="center"><?php echo e(\Carbon\Carbon::parse($sale->emission_date)->format('d-m-Y')); ?></td>
                        <td align="center"><?php echo e(\Carbon\Carbon::parse($sale->begin_date)->format('d-m-Y')); ?></td>
                        <td align="center"><?php echo e(\Carbon\Carbon::parse($sale->end_date)->format('d-m-Y')); ?></td>
                        <td align="center"><?php echo e($status->name); ?></td>
                    </tr>
                </tbody>
            </table>
            <div class="col-md-8 col-md-offset-4" style="margin-top: -20px;">
                <table id="vehiclesTableResume" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th style="background-color:#183c6b;color:white;width:60%">Producto</th>
                            <th style="background-color:#183c6b;color:white;width:25%">Prima Neta</th>
                        </tr>
                    </thead>
                    <tbody id="vehiclesTableBodyResume">
                        <tr align="center">
                            <td><?php echo e($product->productodes); ?></td>
                            <td>$<?php echo e($sale->prima_total); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="taxTableResume" class="col-md-6 col-md-offset-6" style="margin-top:-20px">
                <table class="table table-striped table-bordered">
                    <tbody id="taxTableBodyResume">
                        <tr align="center">
                        <th style="text-align:right;">S. de Compañías (3.5%)</th>
                        <td style="width:40%; text-align:right;">$<?php echo e($sale->super_bancos); ?></td>
                     </tr>
                     <tr align="center">
                        <th style="text-align:right;">S. Campesino (0.5%)</th>
                        <td style="width:40%; text-align:right;">$<?php echo e($sale->seguro_campesino); ?></td>
                     </tr>
                     <tr align="center">
                        <th style="text-align:right;">Derechos de Emisión</th>
                        <td style="width:40%; text-align:right;">$<?php echo e($sale->derecho_emision); ?></td>
                     </tr>
                     <tr align="center">
                        <th style="text-align:right;">Subtotal 12%</th>
                        <td style="width:40%; text-align:right;">$<?php echo e($sale->subtotal_12); ?></td>
                     </tr>
                     <tr align="center">
                        <th style="text-align:right;">Subtotal 0%</th>
                        <td style="width:40%; text-align:right;">$<?php echo e($sale->subtotal_0); ?></td>
                     </tr>
                     <tr align="center">
                        <th style="text-align:right;">IVA</th>
                        <td style="width:40%; text-align:right;">$<?php echo e($sale->tax); ?></td>
                     </tr>
                     <tr align="center">
                        <th style="text-align:right;">Total</th>
                        <td style="width:40%; text-align:right;">$<?php echo e($sale->total); ?></td>
                     </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-xs-10 col-md-10 col-md-offset-1 border" style="padding-left:0px !important;margin-top:25px;">
        <div class="wizard_activo registerForm titleDivBorderTop">
            <span class="titleLink">Datos para Factura</span>
            <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
        </div>
        <div class="col-md-12" style="padding-top: 25px;">
            <div class="col-md-6">
                <div class="form-group form-inline">
                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Cédula</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                    <!--<input type="text" class="form-control registerForm" name="document" id="document" placeholder="Cédula" value="<?php echo e(old('document')); ?>" required="required">-->
                    <div class="form-inline">
                        <input autocomplete="off" type="text" class="form-control registerForm" name="document" id="document" value="<?php echo e($customer->document); ?>"placeholder="Cédula" required="required"tabindex="1" style="width:87%" disabled="disabled" onclick="return false">
                        <button type="button" class="btn btn-info" style="width:11%"><span class="glyphicon glyphicon-search"></span></button>
                        <div id="suggesstion-box"></div>
                    </div>
                </div>
                <div class="form-group">
                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="first_name"> Nombre(s)</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                    <input type="text" class="form-control registerForm" name="first_name" id="first_name" placeholder="Nombre" value="<?php echo e($customer->first_name); ?>" required="required" tabindex="3" disabled="disabled">
                </div>
                <div class="form-group">
                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff"></span> <label class="registerForm" for="city">Celular</label> <span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido </span> <label class="own"> <span class="glyphicon glyphicon-info-sign" style="color:#0099ff"> <span class="own1" style="float:left"> El Celular debe tener 10 caracteres </span>                                     </span> </label>
                    <input type="text" class="form-control registerForm" name="mobile_phone" id="mobile_phone" placeholder="Nombre" value="<?php echo e($customer->mobile_phone); ?>" required tabindex="5" disabled="disabled">
                </div>
                <div class="form-group">
                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="city"> Dirección</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                    <input type="text" class="form-control registerForm" name="address" id="address" placeholder="Nombre" required tabindex="7" value="<?php echo e($customer->address); ?>" disabled="disabled">
                </div>
                <div class="form-group">
                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="country"> País</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                    <select name="country" id="country" class="form-control registerForm" required tabindex="9" disabled="disabled">
                        <option selected="true" value="0">--Escoja Una---</option>
                        <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option <?php if($country->id == $cusCountry->id): ?> selected="true" <?php else: ?> <?php endif; ?> value="<?php echo e($country->id); ?>"><?php echo e($country->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group">
                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="city"> Ciudad</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                    <select name="city" class="form-control registerForm" id="city" required tabindex="11" disabled="disabled">
                        <?php if($cusCityList): ?>
                        <option id="citySelect" selected="true" value="<?php echo e($cusCity->id); ?>"><?php echo e($cusCity->name); ?></option>
                        <?php else: ?>
                        <option id="citySelect" selected="true" disabled="disabled" value="0">--Escoja Una---</option>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document_id"> Tipo Documento</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                    <select id="document_id" name="document_id" class="form-control registerForm" value="<?php echo e(old('document_id')); ?>" required tabindex="2" disabled="disabled">
                        <option value="0">--Escoja Una---</option>
                        <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option <?php if($document->id == $customer->document_id): ?> selected="true" <?php else: ?> <?php endif; ?> value="<?php echo e($document->id); ?>"><?php echo e($document->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group">
                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="last_name"> Apellido(s)</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                    <input type="text" class="form-control registerForm" name="last_name" id="last_name" placeholder="Apellido" value="<?php echo e($customer->last_name); ?>" required tabindex="4" disabled="disabled">
                </div>
                <div class="form-group">
                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff"> </span> <label class="registerForm" for="document">Teléfono</label>&ensp;<span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido </span><label class="own"><span class="glyphicon glyphicon-info-sign" style="color:#0099ff"><span class="own1" style="float:left">El telefono debe tener 9 caracteres </span> </span> </label>
                    <input type="text" class="form-control registerForm" name="phone" id="phone" placeholder="Cédula" value="<?php echo e($customer->phone); ?>" required tabindex="6" disabled="disabled" disabled="disabled">
                </div>

                <div class="form-group">
                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="correo"> Correo</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                    <input type="email" class="form-control registerForm" name="email" id="email" placeholder="Correo" value="<?php echo e($customer->email); ?>" required tabindex="8" disabled="disabled">
                    <p id="emailError" style="color:red;font-weight: bold"></p>    
                    <?php if($errors->any()): ?>
                    <span style="color:red;font-weight:bold"><?php echo e($errors->first()); ?></span>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="province"> Cantón</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                    <select name="province" class="form-control registerForm" id="province" required tabindex="10" disabled="disabled">
                        <?php if($cusProvinceList): ?>
                        <option id="provinceSelect" selected="true" value="<?php echo e($cusProvince->id); ?>"><?php echo e($cusProvince->name); ?></option>
                        <?php else: ?>
                        <option id="provinceSelect" selected="true" disabled="disabled" value="0">--Escoja Una---</option>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="chargeId" id="chargeId" value="<?php echo e($sale->id); ?>"><?php /**PATH C:\wamp64\www\magnussucre\resources\views\payments\form.blade.php ENDPATH**/ ?>