

<?php $__env->startSection('content'); ?>
<!--<div class="se-pre-con"></div>-->
<script src="<?php echo e(assets('js/massive/index.js')); ?>"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
<style>
    .massiveMenu:hover {
        cursor: pointer;
	background-color:#6c6c6c !important;
        border: 1px solid #6c6c6c  !important;
    }
    .massiveMenu{
	text-transform:uppercase;
	font-size:12px;
	font-weight:600;
    }
</style>
<ul class="nav nav-tabs" style="font-size:15px;background-color: #444;margin-top:-20px">
    <li style="padding-left:15px; margin-bottom: 0px !important"><a class="menuactivo massiveMenu" href="#">Carga</a></li>
    <li><a class="massiveMenu" onclick="redirectUrl('/massive/secondary')" style="color:white;">Listado</a></li>
</ul>
<div class="container" style="width: 100%">
    <div class="col-md-12 border" id="filter" style="margin-top:10px;margin-left:0;margin-right:15px; display: none;">
        <form method="POST" action="<?php echo e(asset('/massive')); ?>">
            <?php echo e(csrf_field()); ?>

            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label style="list-style-type:disc;" for="channel">Canal</label>
                        <select name="channel" id="channel" class="form-control" value="">
                            <option value="">-- Todos --</option>
                            <?php $__currentLoopData = $channels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $channel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option <?php if($channel->id == session('massiveFirstViewChannel')): ?> selected="true" <?php else: ?> <?php endif; ?> value="<?php echo e($channel->id); ?>"><?php echo e($channel->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label style="list-style-type:disc;" for="beginDate">Fecha Inicio</label>
                        <input type="date" class="form-control" name="beginDate" id="beginDate" placeholder="fecha" style="line-height:14px" value="<?php echo e(session('massiveFirstViewBeginDate')); ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label style="list-style-type:disc;" for="endDate">Fecha Fin</label>
                        <input type="date" class="form-control" name="endDate" id="endDate" placeholder="fecha" style="line-height:14px" onchange="endDateChange()" value="<?php echo e(session('massiveFirstViewEndDate ')); ?>">
                    </div>

                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label style="list-style-type:disc;" for="type">Tipo</label>
                        <select name="type" id="type" class="form-control" value="">
                            <option value="">-- Todos --</option>
                            <?php $__currentLoopData = $massiveTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option <?php if($type->id == session('massiveFirstViewType')): ?> selected="true" <?php else: ?> <?php endif; ?> value="<?php echo e($type->id); ?>"><?php echo e($type->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label style="list-style-type:disc;" for="statusMassive">Estado Venta</label>
                        <select name="statusMassive" id="statusMassive" class="form-control" value="">
                            <option value="">-- Todos --</option>
                            <?php $__currentLoopData = $statusMassive; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $massive): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option <?php if($massive->id == session('massiveFirstViewStatus')): ?> selected="true" <?php else: ?> <?php endif; ?> value="<?php echo e($massive->id); ?>"><?php echo e($massive->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label style="list-style-type:disc;" for="statusPayment">Estado Cobro</label>
                        <select name="statusPayment" id="statusPayment" class="form-control" value="">
                            <option  value="">-- Todos --</option>
                            <?php $__currentLoopData = $statusCharge; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $charge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option <?php if($charge->id == session('massiveFirstViewStatusPayment')): ?> selected="true" <?php else: ?> <?php endif; ?> value="<?php echo e($charge->id); ?>"><?php echo e($charge->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
            </div>
            <input type="hidden" id="items" name="items" value="<?php echo e($items); ?>">
            <input type="button" id="btnCancel" class="btn btn-default" value="Cancelar">
            <input type="button" id="btnClearMassive" class="btn btn-default" value="Limpiar">
            <input id="btnFilterForm" type="submit" class="btn btn-primary" value="Aplicar"  onclick="return val()">
        </form>
    </div>
    <div class="col-md-12" style="margin-left: -15px">
        <h4>Listado de Masivos </h4>
        <?php if(session('Error')): ?>
        <div class="alert alert-warning">
            <img src="<?php echo e(assets('images/iconos/warning.png')); ?>" alt="Girl in a jacket" style="width:40px;height:40px"> <?php echo e(session('Error')); ?>

        </div>
        <?php endif; ?>
        <?php if(session('Success')): ?>
        <div class="alert alert-success">
            <img src="<?php echo e(assets('images/iconos/ok.png')); ?>" alt="Girl in a jacket" style="width:40px;height:40px"><?php echo e(session('Success')); ?>

        </div>
        <?php endif; ?>
        <?php if(session('Inactive')): ?>
        <div class="alert alert-success">
            <img src="<?php echo e(assets('images/iconos/ok.png')); ?>" alt="Girl in a jacket" style="width:40px;height:40px"><?php echo e(session('Inactive')); ?>

        </div>
        <?php endif; ?>
        <?php if( Session::has('storeSuccess') ): ?>
        <div class="alert alert-success alert-dismissible" role="alert"  style="margin-top:5px">
            <center>
                <strong>
                    <?php echo e(Session::get('storeSuccess')); ?> 
                </strong>
            </center>
        </div>
        <?php endif; ?>
        <?php if( Session::has('cancelSuccess') ): ?>
        <div class="alert alert-success alert-dismissible" role="alert"  style="margin-top:5px">
            <center>
                <strong>
                    <?php echo e(Session::get('cancelSuccess')); ?> 
                </strong>
            </center>
        </div>
        <?php endif; ?>
        <div id="paymentSuccess" class="alert alert-success hidden">
            <center>
                <strong>
                    El masivo fue pagado de manera exitosa
                </strong>
            </center>
        </div>
        <div id="paymentError" class="alert alert-danger hidden">
            <center>
                <strong>
                    Hubo un error por favor comuniquese con el administrador
                </strong>
            </center>
        </div>
        <button class="border btnTable" type="button" id="filterButton"><img id="filterImg" src="<?php echo e(assets('/images/filter.png')); ?>" width="24" height="24" alt=""></button> 
        <a type="button" class="border btnTable" href="<?php echo e(asset('/massive/create')); ?>" data-toggle="tooltip" title="Nuevo"><img src="<?php echo e(assets('/images/mas.png')); ?>" width="24" height="24" alt=""></a>
        <a type="button" class="border btnTable" href="<?php echo e(asset('/massive/cancel')); ?>" data-toggle="tooltip" title="Cancelar"><img src="<?php echo e(assets('/images/menos.png')); ?>" width="24" height="24" alt=""></a>
        <?php echo $__env->make('pagination.items', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
    <div id="tableData">
        <?php echo $__env->make('pagination.massives', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>

    <!--    <div id="carga" class="tabcontent2">
            <div id="loadContent">
                <?php echo $__env->make('massive.carga', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>-->
    <!--    <div id="listado" class="tabcontent">
            listado
        </div>-->
</div>
<script>
    document.getElementById('pagination').onchange = function () {
        document.getElementById('items').value = this.value;
        document.getElementById('btnFilterForm').click();
    };
    function openCity(evt, viewName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(viewName).style.display = "block";
        evt.currentTarget.className += " active";
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            type: "POST",
            data: {_token: CSRF_TOKEN},
            url: ROUTE + "/massive/loadView/" + viewName,
            beforeSend: function () {
                // Show Loader
                $("#loaderGif").addClass('loaderGif');
            },
            success: function (data)
            {
                var tableData = document.getElementById("loadContent");
                tableData.innerHTML = data;
            },
            complete: function () {
                //Hide Loader
                var loaderGif = document.getElementById("loaderGif");
                loaderGif.classList.remove("loaderGif");
            }
        });
    }

    function redirectUrl(URL) {
        window.location.href = ROUTE + URL;
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views\massive\index.blade.php ENDPATH**/ ?>