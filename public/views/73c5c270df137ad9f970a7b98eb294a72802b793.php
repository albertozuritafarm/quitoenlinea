

<?php $__env->startSection('content'); ?>
<!--<div class="se-pre-con"></div>-->
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
<script src="<?php echo e(assets('js/financing/index.js')); ?>"></script>
<link href="<?php echo e(assets('css/payments/index.css')); ?>" rel="stylesheet" type="text/css"/>
<div class="container" style="width: 100%">
    <div>
        <div class="col-md-12 hidden border" id="filter" style="margin-top:10px;margin-left:0;margin-right:15px;">
            <form method="POST" action="<?php echo e(asset('/financing')); ?>">
                <?php echo e(csrf_field()); ?>

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name"># Solicitud</label>
                            <input type="text" class="form-control" name="crId" id="crId" placeholder="# Solicitud" value="<?php echo e($data['crId']); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Fecha Inicio</label>
                            <input type="date" class="form-control" name="beginDate" id="beginDate" placeholder="" style="line-height:14px" value="<?php echo e($data['beginDate']); ?>" onchange="beginDateChange()">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Fecha Fin</label>
                            <input type="date" class="form-control" name="endDate" id="endDate" placeholder="" style="line-height:14px" value="<?php echo e($data['endDate']); ?>" onchange="endDateChange()">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Identificacion</label>
                            <input type="text" class="form-control" name="document" id="document" placeholder="Identificación" style="line-height:14px" value="<?php echo e($data['document']); ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Institucion Financiera</label>
                            <select class="form-control" id="bank" name="bank">
                                <option value="">--Seleccione Uno--</option>
                                <?php $__currentLoopData = $banks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($bank->id == $data['bank']): ?>{
                                        <option value="<?php echo e($bank->id); ?>" selected='true'><?php echo e($bank->name); ?></option>
                                    <?php else: ?>
                                        <option value="<?php echo e($bank->id); ?>"><?php echo e($bank->name); ?></option>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>                       
                        </div>
                    </div>
                </div>
                <input type="button" id="btnCancel" class="btn btn-default" value="Cancelar">
                <input type="button" id="btnAccountsClear" class="btn btn-default" value="Limpiar">
                <input type="submit" class="btn btn-primary" value="Aplicar" onclick="val()">
            </form>
        </div>
        <div class="col-md-12" style="margin-left: -15px">
            <h4>Solicitudes de Financiamiento</h4>
            <?php if(session('successFinancing')): ?>
            <div class="alert alert-success">
                <center>
                    <?php echo e(session('successFinancing')); ?>

                </center>
            </div>
            <?php endif; ?>
            <?php if(session('warningAccount')): ?>
            <div class="alert alert-warning">
                <center>
                    <?php echo e(session('warningAccount')); ?>

                </center>
            </div>
            <?php endif; ?>
            <button class="border btnTable" type="button" id="filterButton"><img id="filterImg" src="<?php echo e(asset('/images/filter.png')); ?>" width="24" height="24" alt=""></button> 
            <a type="button" class="border btnTable" href="<?php echo e(asset('/financing/create')); ?>" data-toggle="tooltip" title="Nueva Solicitud"><img src="<?php echo e(asset('/images/mas.png')); ?>" width="24" height="24" alt=""></a>
            <a id="deleteCrBtn" type="button" class="border btnTable" href="#" data-toggle="tooltip" title="Eliminar"><img src="<?php echo e(asset('/images/menos.png')); ?>" width="24" height="24" alt=""></a>
        </div>
        <div class="col-md-12 border" style="margin-top:10px">
            <div id="tableDiv" class="col-md-12" >
                <table id="tableUsers" class="table table-striped row-border table-responsive hover stripe" style="margin-left:-14px;">
                    <thead>
                        <tr>
                            <th align="center"><center><span id="checkAll" style="margin-left:10px"> Marcar</span></center></th>
                    <th align="center">Solicitud</th>
                    <th align="center">Identificacion</th>
                    <th align="center">Cliente</th>
                    <th align="center">Institucion Financiera</th>
                    <th align="center">Factura/Orden</th>
                    <th align="center">Valor Solicitado</th>
                    <th align="center">Valor Total</th>
                    <th align="center">Fecha</th>
                    <th align="center">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td align="center"><input type="checkbox" name="crId" value="<?php echo e($acc->id); ?>"></td>
                            <td align="center"><?php echo e($acc->id); ?></td>
                            <td align="center"><?php echo e($acc->document_number); ?>(<?php echo e($acc->document); ?>)</td>
                            <td align="center"><?php echo e($acc->customer); ?></td>
                            <td align="center"><?php echo e($acc->bank); ?></td>
                            <td align="center"><?php echo e($acc->number); ?></td>
                            <td align="center"><?php echo e($acc->amount); ?></td>
                            <td align="center"><?php echo e($acc->total_amount); ?></td>
                            <td align="center"><?php echo e($acc->date); ?></td>
                            <td align="center">
                                <?php if($acc->status == '1'): ?>
                                <a href="#" onclick="crDelete(<?php echo e($acc->id); ?>)"><img src="<?php echo e(asset('/images/delete.png')); ?>"></img></a>
                                <?php else: ?>
                                <a id="validateCodeOpenModal" href="#" data-toggle="tooltip" title="Validar Codigo SMS" onclick="modalCode(<?php echo e($acc->id); ?>)">
                                    <span class="glyphicon glyphicon-envelope" style="color:#000;font-size:15px;margin-left: 5px">&ensp;</span>
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<form method="post" action="<?php echo e(route('accountApprove')); ?>" id="formAprrove" name="formAprrove">
    <?php echo e(csrf_field()); ?>

    <input type="hidden" name="id" id="formAprroveId" value="">
    <button type="submit" id="formAprroveBtn" class="btn btn-primary hidden"></button>
</form>
<form method="post" action="<?php echo e(route('accountDeny')); ?>" id="formAprrove" name="formAprrove">
    <?php echo e(csrf_field()); ?>

    <input type="hidden" name="id" id="formDenyId" value="">
    <button type="submit" id="formDenyBtn" class="btn btn-primary hidden"></button>
</form>
<!-- Trigger the modal with a button -->
<button id="modalCodeBtn" type="button" class="btn btn-info btn-lg hidden" data-toggle="modal" data-target="#myModal">Open Modal</button>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Validación de Solicitud</h4>
            </div>
            <div class="modal-body">
                <span class="">
                    <div id="resultMessage" class="">
                    </div>
                    <div id="validationCode">
                        <input type="hidden" name="accountId" id="accountId" value="">
                    </div>
                    <div class="form-group">
                        <label for="code">Ingrese el codigo</label>
                        <input type="text" class="form-control" name="code" id="code" placeholder="Ingrese el codigo"><br>
                        <button id="resendCodeBtn" type="submit" class="btn btn-success" style="float:right;margin-bottom: 10px" onclick="resendCode()">Reenviar Codigo</button>
                    </div>
                </span>
            </div>
            <div class="modal-footer">
                <input type="button" style="float:left;" class="btn btn-default registerForm" align="right" value="Cancelar" data-dismiss="modal">
                <input id="fourthStepBtnNext" type="button" style="float:right;" class="btn btn-info registerForm" align="right" value="Validar" onclick="validateCode()">
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views\financing\index.blade.php ENDPATH**/ ?>