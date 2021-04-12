

<?php $__env->startSection('content'); ?>
<script src="<?php echo e(assets('js/registerCustom.js')); ?>"></script>
<link href="<?php echo e(assets('css/sales/index.css')); ?>" rel="stylesheet" type="text/css"/>
<script src="<?php echo e(assets('js/ticket/create.js')); ?>"></script>
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
<style>
    .tableSelect{
        background-color: #bababd;
    }
    .inputError{
        border-color: red;
    }
    .hidden{
        display:none;
        visibility:hidden;
    }
    .inputRedFocus{
        border-color: red;
    }
</style>

<div class="container" style="margin-top:15px; font-size:14px !important">
    <!--<div class="row justify-content-center border" style="margin-left:20%;">-->
    <div class="col-md-9 col-md-offset-1 border" style="padding: 15px">
        <div class="row">
            <div class="col-xs-12 registerForm" style="margin:12px;">
                <center>
                    <h4 style="font-weight:bold">Crear Ticket</h4>
                </center>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-4 wizard_inicial" style="padding-left:0px !important"><div class="wizard_inactivo"></div></div>
            <div class="col-xs-12 col-sm-4 wizard_medio"><div id="firstStepWizard" class="wizard_activo registerForm">Ticket</div></div>
            <div class="col-xs-12 col-sm-4 wizard_final" style="padding-right: 0px !important"><div class="wizard_inactivo"></div></div>
        </div>
        <?php if(session('error')): ?>
        <br>
        <div class="alert alert-warning">
            <center>
                <?php echo e(session('error')); ?>

            </center> 
        </div>
        <?php endif; ?>
        <?php if(session('errorMsg')): ?>
        <br>
        <div class="alert alert-danger">
            <center>
                <?php echo session('errorMsg'); ?>

            </center> 
        </div>
        <?php endif; ?>
        <br>
        <form id="ticketForm" action="<?php echo e(asset('/ticket/store')); ?>">
            <div id="firstStep">
                <div class="col-md-12 border" style="margin-top:15px">
                    <?php echo e(csrf_field()); ?>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="menu">Modulo:</label>
                            <select class="form-control" id="menuSelect" name="menuSelect" onchange="removeInputRedFocus(this.id)">
                                <option value="">--Escoge Una--</option>
                                <?php $__currentLoopData = $menu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $men): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($men->id); ?>"><?php echo e($men->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div> 
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="ticketType">Tipo de Ticket:</label>
                            <select class="form-control" id="ticketType" name="ticketType" onchange="ticketChange(this.value);removeInputRedFocus(this.id)">
                                <option value="">--Escoja Una--</option>
                                <?php $__currentLoopData = $ticketType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $typ): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($typ->id); ?>"><?php echo e($typ->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div> 
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="ticketTypeDetail">Categoria:</label>
                            <select class="form-control" id="ticketTypeDetail" name="ticketTypeDetail" onchange="removeInputRedFocus(this.id)">
                                <option value="">--Escoja Una--</option>
                            </select>
                        </div> 
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="description">Titulo:</label>
                            <input type="text" class="form-control" rows="5" id="title" name="title" placeholder="Por favor indique el titulo del ticket" onkeydown="removeInputRedFocus(this.id)" required="required">
                        </div> 
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="description">Descripción:</label>
                            <textarea class="form-control" rows="5" id="description" name="description" placeholder="Por favor describa en detalle el Ticket" onkeydown="removeInputRedFocus(this.id)"></textarea>
                        </div> 
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-4" style="margin-right:15px; margin-bottom: 15px; width:31% !important">
                            <div class="form-group">
                                <label class="file-upload" for="file">Archivo:</label>
                                <input type="file" id="file" name="file">
                                <br>
                                <input type="button" value="Limpiar" onclick="clearInputFile()">
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="col-md-12 " style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                    <div class="col-md-1">
                        <a class="btn btn-default registerForm" align="right" href="<?php echo e(asset('/ticket')); ?>" style="margin-left: -30px;"> Cancelar </a>
                    </div>
                    <div class="col-md-1 col-md-offset-10">
                        <button type="submit" class="btn btn-info registerForm" align="right" style="float:right;margin-right: -30px;padding: 5px;width:80px" onclick="submitTicket()"> Guardar </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <!--      <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Calendario</h4>
                  </div>-->
            <div class="modal-body">
                <div id='loading'>loading...</div>
                <div id='calendar'></div>
            </div>
            <div class="modal-footer">
                <button id="modalCalendarClose" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views\tickets\create.blade.php ENDPATH**/ ?>