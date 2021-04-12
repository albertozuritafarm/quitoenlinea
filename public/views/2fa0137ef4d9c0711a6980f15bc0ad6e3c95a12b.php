

<?php $__env->startSection('content'); ?>
<!--<div class="se-pre-con"></div>-->
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
<script src="<?php echo e(assets('js/registerCustom.js')); ?>"></script>
<script src="<?php echo e(assets('js/sales/index.js')); ?>"></script>
<link href="<?php echo e(assets('css/sales/index.css')); ?>" rel="stylesheet" type="text/css"/>

<div class="container" style="width: 100%">
    <div>
        <div class="col-md-12 hidden border" id="filter" style="margin-top:10px;margin-left:0;margin-right:15px;">
            <form method="POST" action="/sales">
                <?php echo e(csrf_field()); ?>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="first_name">Nombre</label>
                            <input type="text" class="form-control" name="first_name" id="first_name" placeholder="Nombre" value="<?php echo e($data['first_name']); ?>">
                        </div>

                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Apellido</label>
                            <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Apellido" value="<?php echo e($data['last_name']); ?>">
                        </div>

                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Identificación</label>
                            <input type="text" class="form-control" name="document" id="document" placeholder="Identificación" value="<?php echo e($data['document']); ?>">
                        </div>

                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Placa</label>
                            <input type="text" class="form-control" name="plate" id="plate" placeholder="Placa" value="<?php echo e($data['plate']); ?>">
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Fecha</label>
                            <input type="date" class="form-control" name="date" id="date" placeholder="Correo" value="<?php echo e($data['date']); ?>" style="line-height:14px">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Correo</label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="Correo" value="<?php echo e($data['email']); ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Numero de Venta</label>
                            <input type="number" class="form-control" name="saleId" id="saleId" placeholder="Numero de Venta" value="<?php echo e($data['saleId']); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="adviser">Asesor</label>
                            <select name="adviser" id="adviser" class="form-control" value="">
                                <option selected="true" value="0">Todos</option>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($usr->id == $data['adviser']): ?>
                                        <option selected="true" value="<?php echo e($usr->id); ?>"><?php echo e($usr->last_name); ?> <?php echo e($usr->first_name); ?></option>
                                    <?php else: ?>
                                        <option value="<?php echo e($usr->id); ?>"><?php echo e($usr->last_name); ?> <?php echo e($usr->first_name); ?></option>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="status">Estado</label>
                            <select name="status" id="status" class="form-control" value="">
                                <option selected="true" value="0">Todos</option>
                                <?php $__currentLoopData = $status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($sta->id == $data['status']): ?>
                                        <option selected="true" value="<?php echo e($sta->id); ?>"><?php echo e($sta->name); ?></option>
                                    <?php else: ?>
                                        <option value="<?php echo e($sta->id); ?>"><?php echo e($sta->name); ?></option>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>

                </div>

                <input type="button" id="btnCancel" class="btn btn-default" value="Cancelar">
                <input type="button" id="btnClearSales" class="btn btn-default" value="Limpiar">
                <input type="submit" class="btn btn-primary" value="Aplicar">
            </form>
        </div>
        <div class="col-md-12" style="margin-left: -15px">
            <h4>Listado de Ventas </h4>
            <?php if(session('Error')): ?>
            <div class="alert alert-warning">
                <img src="<?php echo e(asset('images/iconos/warning.png')); ?>" alt="Girl in a jacket" style="width:40px;height:40px"> <?php echo e(session('Error')); ?>

            </div>
            <?php endif; ?>
            <?php if(session('Success')): ?>
            <div class="alert alert-success">
                <img src="<?php echo e(asset('images/iconos/ok.png')); ?>" alt="Girl in a jacket" style="width:40px;height:40px"><?php echo e(session('Success')); ?>

            </div>
            <?php endif; ?>
            <?php if(session('Inactive')): ?>
            <div class="alert alert-success" style="margin-right: -15px">
                <img src="<?php echo e(asset('images/iconos/ok.png')); ?>" alt="Girl in a jacket" style="width:40px;height:40px"><?php echo e(session('Inactive')); ?>

            </div>
            <?php endif; ?>
            <?php if(session('cancelMessage')): ?>
            <div class="alert alert-success" style="margin-right:-20px">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <center>
                    <span class="glyphicon glyphicon-ok" id="annulmentMsgSuccess"  style="font-weight: bold;"><?php echo e(session('cancelMessage')); ?></span>
                </center>
            </div>
            <?php endif; ?>
            <?php if(Session::has('message')): ?>
                <div class="alert alert-success" style="margin-right:-20px">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <center>
                        <span class="glyphicon glyphicon-ok" id="annulmentMsgSuccess"  style="font-weight: bold;"><?php echo e(session('message')); ?></span>
                    </center>
                </div>
            <?php endif; ?>
            <?php if(Session::has('successMessage')): ?>
                <div class="alert alert-success" style="margin-right:-20px">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <center>
                        <span class="glyphicon glyphicon-ok" id="annulmentMsgSuccess"  style="font-weight: bold;"><?php echo e(session('successMessage')); ?></span>
                    </center>
                </div>
            <?php endif; ?>
            <?php if(Session::has('errorMessage')): ?>
                <div class="alert alert-danger" style="margin-right:-20px">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <center>
                        <span class="glyphicon glyphicon-ok" id="annulmentMsgSuccess"  style="font-weight: bold;"><?php echo e(session('errorMessage')); ?></span>
                    </center>
                </div>
            <?php endif; ?>
            <?php if(Session::has('deleteMessage')): ?>
                <div class="alert alert-success" style="margin-right:-20px">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <center>
                        <span class="glyphicon glyphicon-ok" id="annulmentMsgSuccess"  style="font-weight: bold;"><?php echo e(session('deleteMessage')); ?></span>
                    </center>
                </div>
            <?php endif; ?>
            
            <div id="annulmentDivSuccess" class="alert alert-success hidden" style="margin-right:-20px">
                <center>
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <span class="glyphicon glyphicon-ok" id="annulmentMsgSuccess"  style="font-weight: bold;"></span>
                </center>
            </div>
            <div id="annulmentDivError" class="alert alert-danger hidden" style="margin-right:-20px">
                <center>
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <span class="glyphicon glyphicon-remove" id="annulmentMsgError" style="font-weight: bold;">&ensp;&ensp;&ensp;</span>
                </center>
            </div>
            <button class="border btnTable" type="button" id="filterButton"><img id="filterImg" src="/images/filter.png" width="24" height="24" alt=""></button> 
            <a type="button" class="border btnTable" href="/sales/create" data-toggle="tooltip" title="Registrar Venta"><img src="/images/mas.png" width="24" height="24" alt=""></a>
            <a type="button" class="border btnTable" href="<?php echo e(route('salesCreateRemote')); ?>" data-toggle="tooltip" title="Registrar Venta Remota"><img src="/images/mas.png" width="24" height="24" alt=""></a>
            <a id="btnAnuleSales" type="button" class="border btnTable" href="#" data-toggle="tooltip" title="Anular Venta"><img src="/images/menos.png" width="24" height="24" alt=""></a>
            <a type="button" class="border btnTable" href="#" data-toggle="tooltip" title="Renovar venta" id="btnRenewSales"><img src="/images/restore.png" width="24" height="24" alt="" style="opacity: 0.5;"></a>
        </div>
        <div class="col-md-12 border" style="margin-top:10px">
            <div id="tableDiv" class="col-md-12" >
                <table id="tableUsers" class="table table-striped row-border table-responsive hover stripe datatable" style="margin-left:-14px;">
                    <thead>
                        <tr>
                            <!--<th align="center"><center><input type="checkbox" id="checkAll" style="margin-left:10px"> Todos</center></th>-->
                            <th align="center"><center><span id="checkAll" style="margin-left:10px"> Todos</span></center></th>
                            <th align="center">N°</th>
                            <th align="center">Documento</th>
                            <th align="center">Cliente</th>
                            <th align="center">Valor</th>
                            <th align="center">Fecha</th>
                            <th align="center">Estado</th>
                            <th align="center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <?php if($sale->status_id == 5 || $sale->status_id == 4): ?>
                                <td align="center"><input type="checkbox" name="saleId" value="<?php echo e($sale->salesId); ?>" disabled='disabled'></td>
                            <?php else: ?>
                                <td align="center"><input type="checkbox" name="saleId" value="<?php echo e($sale->salesId); ?>"></td>
                            <?php endif; ?>
                            <!--<td align="center"> <a href="#" data-toggle="modal" data-target="#saleModal<?php echo e($sale->salesId); ?>"><?php echo e($sale->salesId); ?></a> </td>-->
                            <td align="center"> <a href="#" onclick="salesResumeTable(<?php echo e($sale->salesId); ?>)"><?php echo e($sale->salesId); ?></a> </td>
                            <td align="center"><?php echo e($sale->document); ?></td>
                            <td align="center"><?php echo e($sale->customer); ?></td>
                            <td align="center"><?php echo e($sale->total); ?></td>
                            <td align="center"><?php echo e($sale->date); ?></td>
                            <td align="center" id="statusTable<?php echo e($sale->salesId); ?>"><?php echo e($sale->status); ?></td>
                            <td align="center">
                                <!--<a href="#" title="Subir Fotos" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#myModal<?php echo e($sale->salesId); ?>">-->
                                <?php if($sale->status_id == 10 || $sale->status_id == 11 || $sale->status_id == 5 || $sale->status_id == 4): ?>
                                    <a class="no-drop" href="#" title="Subir Fotos">
                                        <span class="glyphicon glyphicon-camera" style="color:black;font-size:19px">&ensp;</span>                                     
                                    </a>
                                <?php else: ?>
                                    <a id="loadPicturesModal<?php echo e($sale->salesId); ?>" href="#" title="Subir Fotos" onclick="loadPicturesModal(<?php echo e($sale->salesId); ?>)">
                                        <span class="glyphicon glyphicon-camera" style="color:black;font-size:19px">&ensp;</span>                                     
                                    </a>
                                <?php endif; ?>
                                <a href="/sales/pdf/<?php echo e(Crypt::encrypt($sale->salesId)); ?>" target="_blank" data-toggle="tooltip" title="ver PDF">
                                    <img src="/images/pdf.png" height="20" width="20" style="margin-top: -10px;margin-left:-5px">
                                    <!--<span class="glyphicon glyphicon-pencil" style="color:#139819;font-size:14px">&ensp;</span>-->
                                </a>
                                <?php if($sale->allow_cancel == "YES" && $sale->status_id == 1): ?>
                                    <a href="/sales/cancel/<?php echo e(Crypt::encrypt($sale->salesId)); ?>" data-toggle="tooltip" title="Cancelar Venta">
                                        <span class="glyphicon glyphicon-remove" style="color:#fc2d2d;font-size:19px;margin-left: 5px"></span>                          
                                    </a>
                                <?php else: ?>
                                    <a class="no-drop" href="#" data-toggle="tooltip" title="Cancelar Venta" disabled="disabled">
                                        <span class="glyphicon glyphicon-remove" style="color:#fc2d2d;font-size:19px;margin-left: 5px" disabled="disabled"></span>                          
                                    </a>
                                <?php endif; ?>
                                <?php if($sale->status_id == 10): ?>
                                    <a id="validateCodeOpenModal" href="#" data-toggle="tooltip" title="Validar Codigo SMS" onclick="validateCodeModal(<?php echo e($sale->salesId); ?>)">
                                        <span class="glyphicon glyphicon-envelope" style="color:#000;font-size:19px;margin-left: 5px">&ensp;</span>
                                    </a>
                                <?php else: ?>
                                    <a class="no-drop" id="validateCodeOpenModal" href="#" data-toggle="tooltip" title="Validar Codigo SMS" disabled="disabled">
                                        <span class="glyphicon glyphicon-envelope" style="color:#000;font-size:19px;margin-left: 5px">&ensp;</span>
                                    </a>
                                <?php endif; ?>
                                <?php if($sale->status_id == 10 || $sale->status_id == 12 || $sale->status_id == 3): ?>
                                    <a id="deleteSaleBtn" href="#" data-toggle="tooltip" title="Borrar Venta" onclick="deleteSale(<?php echo e($sale->salesId); ?>)">
                                        <span class="glyphicon glyphicon-trash" style="color:#000;font-size:19px;margin-left: -5px">&ensp;</span>
                                    </a>
                                <?php else: ?>
                                    <a class="no-drop" id="validateCodeOpenModal" href="#" data-toggle="tooltip" title="Validar Codigo SMS">
                                        <span class="glyphicon glyphicon-trash" style="color:#000;font-size:19px;margin-left: -5px">&ensp;</span>
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
<!-- MODAL VEHICULES PICTURE-->
<!-- Trigger the modal with a button -->
<button id="modalBtnClickPictures" type="button" class="btn btn-info btn-lg hidden" data-toggle="modal" data-target="#myModalVehiPictures">Open Modal</button>
<!-- Modal -->
<div id="myModalVehiPictures" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Vehiculos</h4>
            </div>
            <div id="modalBody" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>

    </div>
</div>
<!-- MODAL SALES RESUME -->
<button id="modalBtnClickResume" type="button" class="btn btn-info btn-lg hidden" data-toggle="modal" data-target="#saleModal">Open Modal</button>
<div id="saleModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Resumen de la Venta</h4>
            </div>
            <div id="modalBodySaleResume" class="modal-body">

            </div>
            <div class="modal-footer">
                <!--<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>-->
            </div>
        </div>

    </div>
</div>
<!-- MODAL ACTIVATE SALES-->
<button id="modalBtnClickActivate" type="button" class="btn btn-info btn-lg hidden" data-toggle="modal" data-target="#saleActivateModal">Open Modal</button>
<div id="saleActivateModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Validar el codigo SMS</h4>
            </div>
            <div id="modalBodySaleActivate" class="modal-body">
                <form id="validateCodeForm">
                     <?php echo e(csrf_field()); ?>

                    <div id="resultMessage">
                    </div>
                    <span class="col-md-12 border">
                        <div id="validationCode">
                        </div>
                        <div class="form-group">
                            <label for="code">Ingrese el codigo</label>
                            <input type="text" class="form-control" name="code" id="code" placeholder="Ingrese el codigo"><br>
                            <button id="resendCodeBtn" type="submit" class="btn btn-success" style="float:right;margin-bottom: 10px" onclick="resendCode()">Reenviar Codigo</button>
                        </div>
                    </span>
                    <div>
                        <button type="button" class="btn btn-default" data-dismiss="modal" style="float:left;margin-top: 10px">Cerrar</button>
                        <button id="validateCodeBtn" type="submit" class="btn btn-info" onclick="validateCode()" style="float:right;margin-top: 10px;">Validar</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="border-top: 0 none !important;">
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views\sales\showData.blade.php ENDPATH**/ ?>