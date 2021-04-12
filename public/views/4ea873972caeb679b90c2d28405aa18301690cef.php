

<?php $__env->startSection('content'); ?>
<!--<div class="se-pre-con"></div>-->
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
<link href="<?php echo e(assets('FullCalendar/packages/core/main.css')); ?>" rel='stylesheet' />
<link href="<?php echo e(assets('FullCalendar/packages/daygrid/main.css')); ?>" rel='stylesheet' />
<link href="<?php echo e(assets('FullCalendar/packages/timegrid/main.css')); ?>" rel='stylesheet' />
<link href="<?php echo e(assets('FullCalendar/packages/list/main.css')); ?>" rel='stylesheet' />
<script src="<?php echo e(assets('FullCalendar/packages/core/main.js')); ?>"></script>
<script src="<?php echo e(assets('FullCalendar/packages/interaction/main.js')); ?>"></script>
<script src="<?php echo e(assets('FullCalendar/packages/daygrid/main.js')); ?>"></script>
<script src="<?php echo e(assets('FullCalendar/packages/timegrid/main.js')); ?>"></script>
<script src="<?php echo e(assets('FullCalendar/packages/list/main.js')); ?>"></script>
<script src="<?php echo e(assets('js/scheduling/index.js')); ?>"></script>
<!--<link href="<?php echo e(asset('css/payments/index.css')); ?>" rel="stylesheet" type="text/css"/>-->
<style>
    /* input [type = file]
----------------------------------------------- */

    input[type=file] {
        display: block !important;
        right: 1px;
        top: 1px;
        height: 34px;
        opacity: 0;
        width: 100%;
        background: none;
        position: absolute;
        overflow: hidden;
        z-index: 2;
    }

    .control-fileupload {
        display: block;
        border: 1px solid #d6d7d6;
        background: #FFF;
        border-radius: 4px;
        width: 100%;
        height: 36px;
        line-height: 36px;
        padding: 0px 10px 2px 10px;
        overflow: hidden;
        position: relative;

        &:before, input, label {
            cursor: pointer !important;
        }
        /* File upload button */
        &:before {
            /* inherit from boostrap btn styles */
            padding: 4px 12px;
            margin-bottom: 0;
            font-size: 14px;
            line-height: 20px;
            color: #333333;
            text-align: center;
            text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75);
            vertical-align: middle;
            cursor: pointer;
            background-color: #f5f5f5;
            background-image: linear-gradient(to bottom, #ffffff, #e6e6e6);
            background-repeat: repeat-x;
            border: 1px solid #cccccc;
            border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
            border-bottom-color: #b3b3b3;
            border-radius: 4px;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
            transition: color 0.2s ease;

            /* add more custom styles*/
            content: 'Browse';
            display: block;
            position: absolute;
            z-index: 1;
            top: 2px;
            right: 2px;
            line-height: 20px;
            text-align: center;
        }
        &:hover, &:focus {
            &:before {
                color: #333333;
                background-color: #e6e6e6;
                color: #333333;
                text-decoration: none;
                background-position: 0 -15px;
                transition: background-position 0.2s ease-out;
            }
        }

        label {
            line-height: 24px;
            color: #999999;
            font-size: 14px;
            font-weight: normal;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
            position: relative;
            z-index: 1;
            margin-right: 90px;
            margin-bottom: 0px;
            cursor: text;
        }
        #calendar {
            max-width: 900px;
            margin: 0 auto;
        }

        .fc-view-container {
            width: auto;
        }

        .fc-view-container .fc-view {
            overflow-x: scroll;
        }

        .fc-view-container .fc-view > table {
            width: 2500px;
        }
    }
</style>
<div class="container" style="width: 100%">
    <div>
        <div class="col-md-12 hidden border" id="filter" style="margin-top:10px;margin-left:0;margin-right:15px;">
            <form method="POST" action="<?php echo e(asset('/scheduling')); ?>">
                <?php echo e(csrf_field()); ?>

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="plate">Placa</label>
                            <input type="text" class="form-control" name="plate" id="plate" placeholder="Placa" style="line-height:14px" value="<?php echo e(session('schedulingPlate')); ?>">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="beginDate">Fecha Inicio</label>
                            <input type="date" class="form-control" name="beginDate" id="beginDate" placeholder="fecha" style="line-height:14px" value="<?php echo e(session('schedulingBeginDate')); ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="endDate">Fecha Fin</label>
                            <input type="date" class="form-control" name="endDate" id="endDate" placeholder="fecha" style="line-height:14px" onchange="endDateChange()" value="<?php echo e(session('schedulingEndDate')); ?>">
                        </div>
                    </div><div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="first_name">Nombre</label>
                            <input type="text" class="form-control" name="first_name" id="first_name" placeholder="Nombre" style="line-height:14px" value="<?php echo e(session('schedulingFirstName')); ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="last_name">Apellido</label>
                            <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Apellido" style="line-height:14px" value="<?php echo e(session('schedulingLastName')); ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="document">Documento</label>
                            <input type="text" class="form-control" name="document" id="document" placeholder="Documento" style="line-height:14px" value="<?php echo e(session('schedulingDocument')); ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="status">Estado:</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">--Seleccione Uno--</option>
                                <?php $__currentLoopData = $status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($sta->id == session('schedulingStatus')): ?>
                                        <option selected="true" value="<?php echo e($sta->id); ?>"><?php echo e($sta->name); ?></option>
                                    <?php else: ?>
                                        <option value="<?php echo e($sta->id); ?>"><?php echo e($sta->name); ?></option>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div> 
                    </div>
                    <input type="hidden" name="items" id="items" value="<?php echo e($items); ?>">
                </div>

                <input type="button" id="btnCancel" class="btn btn-default" value="Cancelar">
                <input type="button" id="btnClearScheduling" class="btn btn-default" value="Limpiar">
                <input id="btnFilterForm" type="submit" class="btn btn-primary" value="Aplicar" onclick="return val()">
            </form>
        </div>
        <div class="col-md-12" style="margin-left: -15px">
            <h4>Listado de Agendamiento </h4>
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
            <?php if(Session::has('storeMessage')): ?>
            <div class="alert alert-success" style="margin-right:-20px">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <center>
                    <span class="glyphicon glyphicon-ok" id="annulmentMsgSuccess"  style="font-weight: bold;"><?php echo e(session('storeMessage')); ?></span>
                </center>
            </div>
            <?php endif; ?>
            <?php if(Session::has('rescheduleSuccess')): ?>
            <div class="alert alert-success" style="margin-right:-20px">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <center>
                    <span class="glyphicon glyphicon-ok" id="annulmentMsgSuccess"  style="font-weight: bold;"> <?php echo e(session('rescheduleSuccess')); ?></span>
                </center>
            </div>
            <?php endif; ?>
            <?php if(Session::has('confirmSuccess')): ?>
            <div class="alert alert-success" style="margin-right:-20px">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <center>
                    <span class="glyphicon glyphicon-ok" style="font-weight: bold;"> <?php echo e(session('confirmSuccess')); ?></span>
                </center>
            </div>
            <?php endif; ?>
            <div id="confirmMessage" class="alert alert-success hidden" style="margin-right:-20px">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <center>
                    <span class="glyphicon glyphicon-ok"  style="font-weight: bold;"> El agendamiento fue confirmado Correctamente</span>
                </center>
            </div>
            <div id="cancelMessage" class="alert alert-success hidden" style="margin-right:-20px">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <center>
                    <span class="glyphicon glyphicon-ok"  style="font-weight: bold;"> El agendamiento fue cancelado Correctamente</span>
                </center>
            </div>
            <button class="border btnTable" type="button" id="filterButton"><img id="filterImg" src="<?php echo e(asset('images/filter.png')); ?>" width="24" height="24" alt=""></button> 
            <?php if($create): ?>
            <a type="button" class="border btnTable" href="<?php echo e(asset('/scheduling/create')); ?>" data-toggle="tooltip" title="Nuevo"><img src="<?php echo e(asset('images/mas.png')); ?>" width="24" height="24" alt=""></a>
            <?php else: ?>
            <?php endif; ?>
            <?php echo $__env->make('pagination.items', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div id="tableData">
            <?php echo $__env->make('pagination.scheduling', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>
</div>
<!-- RESUME MODAL-->
<!-- Trigger the modal with a button -->
<button id="modalResumeBtn" type="button" class="btn btn-info btn-lg hidden" data-toggle="modal" data-target="#myModalResume">Open Modal</button>
<!-- Modal -->
<div id="myModalResume" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Agendamiento</h4>
            </div>
            <div id="modalBody" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>

    </div>
</div>
<!-- CANCEL MODAL-->
<!-- Trigger the modal with a button -->
<button id="modalCancelBtn" type="button" class="btn btn-info btn-lg hidden" data-toggle="modal" data-target="#myModalCancel">Open Modal</button>
<!-- Modal -->
<div id="myModalCancel" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Cancelación</h4>
            </div>
            <div id="cancelModalError" class="alert alert-danger hidden">
                <center>
                    Debe seleccionar un motivo de cancelación
                </center>
            </div>
            <div id="modalBodyCancel" class="modal-body">

            </div>
            <div class="modal-footer">
                <button id="modalCancelCloseBtn" type="button" class="btn btn-default" data-dismiss="modal" style="float:left">Cerrar</button>
                <button type="button" class="btn btn-info" onclick="cancelBtn()">Cancelar</button>
            </div>
        </div>

    </div>
</div>
<!-- RESCHEDULING MODAL-->
<!-- Trigger the modal with a button -->
<button id="modalRescheduleBtn" type="button" class="btn btn-info btn-lg hidden" data-toggle="modal" data-target="#myModalReschedule">Open Modal</button>
<!-- Modal -->
<div id="myModalReschedule" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Reagendamiento</h4>
            </div>
            <div id="cancelModalError" class="alert alert-danger hidden">
                <center>
                    Debe seleccionar un motivo de cancelación
                </center>
            </div>
            <div id="rescheduleError" class="alert alert-danger hidden">
                <center>
                    Debe seleccionar otra fecha.
                </center>    
            </div>
            <div id="modalBodyCalendar" class="modal-body">
                <div id='loading'>Cargando...</div>
                <div id='calendar'></div>
            </div>
            <div class="modal-footer">
                <button id="modalCancelCloseBtn" type="button" class="btn btn-default" data-dismiss="modal" style="float:left">Cerrar</button>
            </div>
        </div>

    </div>
</div>
<!-- CONFIRMATION MODAL-->
<!-- Trigger the modal with a button -->
<button id="modalConfirmBtn" type="button" class="btn btn-info btn-lg hidden" data-toggle="modal" data-target="#myModalConfirm">Open Modal</button>
<!-- Modal -->
<div id="myModalConfirm" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Confirmar Agendamiento</h4>
            </div>
            <br>
            <div id="confirmModalError" class="alert alert-danger hidden">
                <center>
                    Solo puede subir un archivo ZIP o RAR y debe pesar menos de 10mb.
                </center>
            </div>
            <div class="modal-body">
                <!--file input example -->
                <form method="POST" id="formConfirm">
                    <?php echo e(csrf_field()); ?>

                    <span class="control-fileupload">
                        <label for="file">Seleccione un archivo :</label>
                        <input type="file" id="fileConfirm" name="fileConfirm" required onchange="Filevalidation()"> 
                    </span>
                    <input id="confirmId" name="confirmId" type="hidden" value="">
                </form>
                <!--./file input example -->
            </div>
            <div class="modal-footer">
                <button id="modalCancelCloseBtn" type="button" class="btn btn-default" data-dismiss="modal" style="float:left">Cerrar</button>
                <button type="submit" class="btn btn-info" style="float:right" onclick="modalConfirmBtn()">Confirmar</button>
            </div>
        </div>

    </div>
</div>

<script>
// Add the following code if you want the name of the file appear on select
    $(function () {
    $('input[type=file]').change(function () {
    var t = $(this).val();
    var labelText = 'Archivo : ' + t.substr(12, t.length);
    $(this).prev('label').text(labelText);
    })
    });
    document.getElementById('pagination').onchange = function () {
//        window.location = "<?php echo e($newSchedules->url(1)); ?>&items=" + this.value;
        document.getElementById('items').value = this.value;
        document.getElementById('btnFilterForm').click();
    };
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views\scheduling\index.blade.php ENDPATH**/ ?>