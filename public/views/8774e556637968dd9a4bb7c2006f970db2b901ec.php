

<?php $__env->startSection('content'); ?>
<script src="<?php echo e(assets('js/registerCustom.js')); ?>"></script>

<link href="<?php echo e(assets('FullCalendar/packages/core/main.css')); ?>" rel='stylesheet' />
<link href="<?php echo e(assets('FullCalendar/packages/daygrid/main.css')); ?>" rel='stylesheet' />
<link href="<?php echo e(assets('FullCalendar/packages/timegrid/main.css')); ?>" rel='stylesheet' />
<link href="<?php echo e(assets('FullCalendar/packages/list/main.css')); ?>" rel='stylesheet' />
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
</style>

<div class="container" style="margin-top:15px; font-size:14px !important">
    <!--<div class="row justify-content-center border" style="margin-left:20%;">-->
    <div class="col-md-8 col-md-offset-2 border" style="padding: 15px">
        <div class="row">
            <div class="col-xs-12 registerForm" style="margin:12px;">
                <center>
                    <h4 style="font-weight:bold">Agendamiento</h4>
                    <!--<h5>Datos del Cliente.</h5>-->
                </center>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-4 wizard_inicial" style="padding-left:0px !important"><div class="wizard_inactivo"></div></div>
            <div class="col-xs-12 col-sm-4 wizard_medio"><div class="wizard_activo registerForm">Nuevo Agendamiento</div></div>
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
        <?php if(session('success')): ?>
        <br>
        <div class="alert alert-success">
            <center>
                <?php echo e(session('success')); ?>

            </center>
        </div>
        <?php endif; ?>
        <br>
        <div id="plateMessage" class="alert alert-danger hidden">
            <center>
                <strong>La placa ingresada no posee ninguna venta activa</strong>
            </center>
        </div>
        <div id="dateMessage" class="alert alert-danger hidden">
            <center>
                <strong>La Fecha y Hora seleccionada no se encuentra disponible</strong>
            </center>
        </div>
        <form method="POST" action="#">
            <div id="firstStep">
                <div class="col-md-10 col-md-offset-1 border" style="margin-top:15px">
                    <?php echo e(csrf_field()); ?>

                    <div class="col-md-6">
                        <label style="list-style-type:disc;" for="plate">Placa:</label>
                        <div class="form-group form-inline">
                            <input type="text" class="form-control" name="plate" id="plate" placeholder="Placa" value="" required style="width:84%">
                            <button type="button" class="btn btn-info" id="plateBtn"><span class="glyphicon glyphicon-search"></span></button>
                        </div> 
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="plate">Año:</label>
                            <input type="text" class="form-control" name="year" id="year" placeholder="Año" value="" required disabled="disabled">
                        </div>
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="plate">Venta:</label>
                            <input type="text" class="form-control" name="sale" id="sale" placeholder="Venta" value="" required disabled="disabled">
                        </div> 
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="plate">Identificación:</label>
                            <input type="text" class="form-control" name="document" id="document" placeholder="Identificación" value="" required disabled="disabled">
                        </div>
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="plate">Email:</label>
                            <input type="text" class="form-control" name="email" id="email" placeholder="Email" value="" required disabled="disabled">
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="plate">Color:</label>
                            <input type="text" class="form-control" name="color" id="color" placeholder="Color" value="" required disabled="disabled">
                        </div>
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="plate">Marca/Modelo:</label>
                            <input type="text" class="form-control" name="brand" id="brand" placeholder="Marca/Modelo" value="" required disabled="disabled">
                        </div>

                        <div class="form-group">
                            <label style="list-style-type:disc;" for="plate">Nombres:</label>
                            <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Apellidos" value="" required disabled="disabled">
                        </div>
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="plate">Movil:</label>
                            <input type="text" class="form-control" name="mobile_phone" id="mobile_phone" placeholder="Movil" value="" required disabled="disabled">
                        </div>
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="plate">Canal:</label>
                            <input type="text" class="form-control" name="channel" id="channel" placeholder="Canal" value="" required disabled="disabled">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div id="servicesTable">
                        </div>
                    </div>
                </div>
                <div class="col-md-10 col-md-offset-1" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                    <div class="col-md-1">
                        <a class="btn btn-default registerForm" align="right" href="<?php echo e(asset('/scheduling')); ?>" style="margin-left: -30px;"> Cancelar </a>
                    </div>
                    <div class="col-md-1 col-md-offset-10">
                        <a id="firstStepBtnNext" class="btn btn-info registerForm hidden" align="right" href="#" style="float:right;margin-right: -30px;padding: 5px"> Siguiente <span class="glyphicon glyphicon-step-forward"></span></a>
                    </div>
                </div>
            </div>
            <div id="secondStep" class="hidden">
                <div class="col-md-10 col-md-offset-1 border" style="margin-top:15px">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="paint">Pintura:</label>
                            <select class="form-control" name="paint" id="paint">
                                <option value="">--Escoja Una--</option>
                                <option value="Yes">Si</option>
                                <option value="No">No</option>
                            </select>
                        </div> 
                        <div class="form-group">
                            <label style="list-style-type:disc;" for="address">Dirección:</label>
                            <input type="text" class="form-control" name="address" id="address" placeholder="Dirección" value="">
                        </div>

                        <div class="form-group">
                            <label style="list-style-type:disc;" for="time">Tiempo Estimado:</label>
                            <input type="text" class="form-control" name="time" id="time" placeholder="Tiempo Estimado" value="" required disabled="disabled">
                        </div>
                        <div class="form-group">
                            <center>
                                <a id="btnAddService" class="btn btn-success" href="#"><span class="glyphicon glyphicon-plus"></span> Agregar</a>
                            </center>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="location">Lugar de Servicio:</label>
                            <select class="form-control" name="location" id="location">
                                <option value="">--Escoja Una--</option>
                            </select>
                        </div> 
                        <div class="form-group">
                            <label for="damage">Tipo de Golpe:</label>
                            <select class="form-control" name="damage" id="damage" disabled="disabled">
                                <option value="">--Escoja Una--</option>
                            </select>
                        </div> 
                        <div class="input-group">
                            <label for="dateTime">Fecha/Hora:</label>
                            <input id="dateTime" type="text" class="form-control" name="dateTime" placeholder="Hora" disabled="disabled">
                            <div class="input-group-btn" style="padding-top:20px">
                                <a href="#" data-toggle="modal" data-target="#myModal" onclick="loadCalendar()"><span class="input-group-addon" style="height:35px"><span class="glyphicon glyphicon-th"></span></span></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <table id="schedulingTable" class="table table-bordered" style="text-align: center;">
                            <thead>
                                <tr>
                                    <th>Placa</th>
                                    <th>Pintura</th>
                                    <th>Lugar Servicio</th>
                                    <th>Dirección</th>
                                    <th>Tipo de  Golpe</th>
                                    <th>Fecha/hora</th>
                                    <th>Acción</th>
                                    <th class="hidden" data-visible="false">id</th>
                                </tr>
                            </thead>
                            <tbody id="schedulingBodyTable">
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12">

                    </div>

                </div>
                <div class="col-md-12" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                    <div class="col-md-1">
                        <a class="btn btn-default registerForm" align="right" href="<?php echo e(asset('/scheduling')); ?>" style="margin-left: 30px;"> Cancelar </a>
                    </div>
                    <div class="col-md-1 col-md-offset-8">
                        <a id="secondStepBtnBack" class="btn btn-default registerForm" align="right" href="#" style="margin-left:-80px;background-color: #444;color:white;width:90px"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                    </div>
                    <div class="col-md-1 col-md-offset-1">
                        <a id="secondStepBtnNext" class="btn btn-info registerForm" align="right" href="#" style="float:right;margin-right: 30px;padding: 5px;width:90px"> Guardar </a>
                    </div>
                </div>
        </form>

    </div>
</div>
<script type="text/javascript">
//$('.form_datetime').datetimepicker({
//    language: 'es',
//    "setDate": new Date(),
//    orientation: 'top-left',
//    weekStart: 0,
//    todayBtn: 1,
//    autoclose: 1,
//    todayHighlight: 1,
//    startView: 2,
//    forceParse: 0,
//    showMeridian: 1,
//    format: 'd-m-yyyy hh:ii',
//    daysOfWeekDisabled: [0, 6],
//    minuteStep: 15,
//    hoursDisabled: [0, 1, 2, 3, 4, 5, 6, 7, 8, 17, 18, 19, 20, 21, 22, 23]
//});
</script>
<script src="<?php echo e(assets('FullCalendar/packages/core/main.js')); ?>"></script>
<script src="<?php echo e(assets('FullCalendar/packages/interaction/main.js')); ?>"></script>
<script src="<?php echo e(assets('FullCalendar/packages/daygrid/main.js')); ?>"></script>
<script src="<?php echo e(assets('FullCalendar/packages/timegrid/main.js')); ?>"></script>
<script src="<?php echo e(assets('FullCalendar/packages/list/main.js')); ?>"></script>
<script src="<?php echo e(assets('js/scheduling/create.js')); ?>"></script>

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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views\scheduling\create.blade.php ENDPATH**/ ?>