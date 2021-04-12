

<?php $__env->startSection('content'); ?>
<script src="<?php echo e(assets('js/registerCustom.js')); ?>"></script>
<script src="<?php echo e(assets('js/sales/R3/declarationBeneficiaries.js')); ?>"></script>

<link href="<?php echo e(assets('css/sales/create.css')); ?>" rel="stylesheet" type="text/css"/>
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script src="<?php echo e(assets('responsive-tables-js-master/src/responsivetables.js')); ?>"></script>
<link href="<?php echo e(assets('responsive-tables-js-master/src/responsivetables.css')); ?>" rel="stylesheet" type="text/css"/>
<style>
    .frmSearch {border: 1px solid #a8d4b1;background-color: #c6f7d0;margin: 2px 0px;padding:40px;border-radius:4px;}
    #customer-list{float:left;list-style:none;margin-top:-3px;padding:0;width:290px;position: absolute;z-index:9999;}
    #customer-list li{padding: 10px; background: #f0f0f0; border-bottom: #bbb9b9 1px solid;}
    #customer-list li:hover{background:#ece3d2;cursor: pointer;}
    #search-box{padding: 10px;border: #a8d4b1 1px solid;border-radius:4px;}
    .error{border:1px solid red}
    .modal-header {
        border-bottom: 0 none;
    }

    .modal-footer {
        border-top: 0 none;
    }
    textarea { 
        resize: vertical; 
    }
/*    #vehiclesTable {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }*/

td::before {
  display: none;
}

@media  screen and (max-width: 37em), print and (max-width: 5in) {
    td::before {
        display: block;
        font-weight: bold;
    }
    #beneficiaryTable td{
        display: block ruby;
        text-align: left;
    }
}
</style>
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
<div class="container" style="font-size:14px !important;padding-bottom: 15px;">
    <!--<div class="row justify-content-center border" style="margin-left:20%;">-->

    <div class="col-xs-12 col-md-10 col-md-offset-1 border" style="padding: 15px;">
        <div class="row">
            <div class="col-xs-12 registerForm" style="margin:12px;">
                <center>
                    <h4 style="font-weight:bold">Declaración de Beneficiarios</h4>
                </center>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-5 wizard_inicial" style="padding-left:0px !important"><div class="wizard_inactivo registerForm"></div></div>
            <div class="col-xs-12 col-md-2 wizard_medio" style="padding-left:0px !important"><div id="firstStepWizard" class="wizard_activo registerForm">Beneficiarios</div></div>
            <div class="col-xs-12 col-md-5 wizard_final" style="padding-right:0px !important"><div class="wizard_inactivo registerForm"></div></div>
        </div>
        <div class="col-md-12">
            <form name="salesForm" method="POST" action="/user" id="salesForm">
                <div id="firstStep" class="col-md-12">
                    <div class="col-md-12" style="margin-top:5px;padding-top:15px;">
                        <div class="row" style="float:left">
                        </div>
                        <div class="row" style="float:right">
                            <a id="firstStepBtnNext1" class="btn btn-info registerForm <?php if($disabled == ''): ?> <?php else: ?> hidden <?php endif; ?>" align="right" href="#" style="padding: 5px"  <?php if($disabled == ''): ?> onclick="firstStepBtnNext()" <?php else: ?> <?php echo e($disabled); ?> <?php endif; ?>> Guardar <span class="glyphicon glyphicon-step-forward"></span></a>
                        </div>
                    </div>
                   <?php if(session('R3Success')): ?>
                       <div class="alert alert-success">
                           <?php echo e(session('R3Success')); ?>

                       </div>
                   <?php endif; ?>
                    <div class="col-xs-12 col-md-12 border <?php if($disabled == ''): ?> <?php else: ?>  <?php endif; ?>" style="padding-left:0px !important;">
                        <div class="wizard_activo registerForm titleDivBorderTop">
                            <span class="titleLink">Beneficiarios</span>
                            <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                        </div>
                        <div id="beneficiaryAlert" class="alert alert-danger hidden registerForm titleDivBorderTop" style="margin-top:5px;border-radius:0px !important;">
                            <center><strong>¡Alerta!</strong> xxxxx</center>
                        </div>
                        <div class="col-md-12" style="margin-top: 25px;">
                            <button id="btnModalFirstStep" type="button" class="btn btn-info btn-lg hidden" data-toggle="modal" data-target="#firstStepModal">Open Modal</button>
                            <!-- Modal Contents -->
                            <!-- Modal -->
                            <div id="firstStepModal" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Modal Header</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>Some text in the modal.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                           <form>
                               <div id="alertDiv" class="alert alert-success hidden">
                                   Los beneficiarios han sido guardados correctamente.
                               </div>
                               <input type="hidden" id="salId" name="salId" value="<?php echo e($sale->id); ?>">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="beneficiary_first_name"> Primer Nombre</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" class="form-control registerForm" name="beneficiary_first_name" id="beneficiary_first_name" placeholder="Primer Nombre" value="" required="required" tabindex="1">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="registerForm" style="list-style-type:disc;" for="beneficiary_second_name"> Segundo Nombre</label>
                                        <input type="text" class="form-control registerForm" name="beneficiary_second_name" id="beneficiary_second_name" placeholder="Segundo Nombre" value="" tabindex="2">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="beneficiary_last_name"> Primer Apellido</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" class="form-control registerForm" name="beneficiary_last_name" id="beneficiary_last_name" placeholder="Primer Apellido" value="" required tabindex="3">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="beneficiary_second_last_name"> Segundo Apellido</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" class="form-control registerForm" name="beneficiary_second_last_name" id="beneficiary_second_last_name" placeholder="Segundo Apellido" value="" required tabindex="4">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="porcentage_Beneficiary"> Porcentaje</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <input type="text" class="form-control registerForm" name="porcentage_Beneficiary" id="porcentage_Beneficiary" placeholder="Porcentaje" value="" required tabindex="5" onkeypress="return onlyNumbers(event, this)">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="beneficiary_relationship"> Parentesco</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                        <select id="beneficiary_relationship" name="relationship" class="form-control registerForm" value="" required tabindex="6" onchange="documentIdChange(this.value)">
                                            <option selected="true" value="0">--Escoja Una---</option>
                                            <option value="1">PADRE/MADRE</option>
                                            <option value="2">HIJO(A)</option>
                                            <option value="3">ABUELO(A)</option>
                                            <option value="4">NIETO(A)</option>
                                            <option value="5">HERMANO(A)</option>
                                            <option value="6">SUEGRO(A)</option>
                                            <option value="7">YERNO</option>
                                            <option value="8">NUERA</option>
                                            <option value="9">CUÑADO(A)</option>
                                            <option value="10">CONYUGE</option>
                                            <option value="11">OTROS</option>
                                            <option value="12">TIO(A)</option>
                                            <option value="13">PRIMO(A)</option>
                                            <option value="14">SOBRINO(A)</option>
                                            <option value="15">ASEGURADO PRINCIPAL</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-md-offset-6">
                                        <a <?php if($disabled == ''): ?> onclick="addBeneficiary()" <?php else: ?> <?php echo e($disabled); ?> <?php endif; ?> id="btnBeneficiary"  class="btn btn-success registerForm" align="right" href="#" style="float:right;margin-right: 0px;padding: 5px;width:100px" required tabindex="7"><span class="glyphicon glyphicon-plus"></span>Agregar </a>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12" style="margin-top: 55px;">
                                        <table id="beneficiaryTable" class="table table-striped table-bordered responsive">
                                            <thead class="tableCustomHeader">
                                                <tr>
                                                    <th>Primer Nombre</th>
                                                    <th>Segundo Nombre</th>
                                                    <th>Primer Apellido</th>
                                                    <th>Segundo Apellido</th>
                                                    <th>Porcentaje</th>
                                                    <th>Parentesco</th>
                                                    <th>Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody id="beneficiaryBodyTable" style="word-break: break-all">
                                                <?php echo $beneTable; ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>    
                            </form>
                        </div>
                    </div>
                    <div class="col-md-12" style="padding-bottom:15px">
                        <div class="row" style="float:left">
                            
                        </div>
                        <div class="row" style="float:right">
                            <a id="firstStepBtnNext2" class="btn btn-info registerForm <?php if($disabled == ''): ?> <?php else: ?> hidden <?php endif; ?>" align="right" href="#" style="padding: 5px" <?php if($disabled == ''): ?> onclick="firstStepBtnNext()" <?php else: ?> <?php echo e($disabled); ?> <?php endif; ?>> Guardar <span class="glyphicon glyphicon-step-forward"></span></a>
                        </div>
                    </div>
                </div>
            </form>
            <!-- Trigger the modal with a button -->
            <button id="confirmModal" type="button" class="btn btn-info btn-lg hidden" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#myModal">Open Modal</button>

            <!-- Modal -->
            <div id="myModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Su registro a sido completado satisfactoriamente</h4>
                        </div>
                        <div class="modal-body">
                            <center>
                                <p id="modalText" style="font-weight: bold;font-size: 16px"></p><br>
                                <p style="font-weight: bold;font-size: 16px">¿Desea ir a la pasarela de cobro?.</p><br>
                            </center>
                        </div>
                        <div class="modal-footer">
                            <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
                            <a href="<?php echo e(asset('/sales')); ?>" type="button" class="btn btn-default" style="float:left">NO</a>
                            <form action="/payments/create" method="POST">
                                <?php echo e(csrf_field()); ?>

                                <input type="hidden" id="chargeId" name="chargeId" value="">
                                <input type="submit" class="btn btn-info" style="float:right" value="SI">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Trigger the modal with a button -->
<button id="priceModalBtn" type="button" class="btn btn-info btn-lg hidden" data-toggle="modal" data-target="#priceModal">Open Modal</button>

<!-- Modal -->
<div id="priceModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Precios de Vehiculos</h4>
      </div>
      <div class="modal-body">
        <div class="col-md-12">
            <div class="form-group">
                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="priceModalSelect"> Modelos</label>
                <select id="priceModalSelect" name="priceModalSelect" class="form-control registerForm" value="" required onchange="priceModalSelect(this.value)">
                </select>
            </div>
        </div>
      </div>
      <div class="modal-footer">
          <button id="priceModalBtnClose" type="button" class="btn btn-default registerForm" data-dismiss="modal" style="float:left">Cerrar</button>
      </div>
    </div>

  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.remote_app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views\sales\R3\declarationBeneficiaries.blade.php ENDPATH**/ ?>