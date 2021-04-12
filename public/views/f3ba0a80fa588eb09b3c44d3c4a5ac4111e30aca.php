

<?php $__env->startSection('content'); ?>
<style>
    hr {
        height: 10px;
        color: #337ab7;
        border-color: #337ab7;
    }
    h4{
        color: #337AB7;
    }
    video{
        border: 1px solid #ddd;
    }
</style>
<div class="container" style="margin-top:15px; font-size:14px !important">
    <div align="center">
        <div class="row">
            <div class="col-md-10 col-md-offset-1 border" style="padding: 15px">
                <div class="row">
                    <div class="col-xs-12 registerForm" style="margin:12px;">
                        <center>
                            <h4 style="font-weight:bold">Listado de Tutoriales</h4>
                            <h6 style="font-weight:bold">* Seleccione un Módulo</h6>
                        </center>
                    </div>
                </div>
                <div class="row">
                    <div class="list-group"  style="display:inline-flex; flex-direction: row;">
                        <?php if(checkExtraPermits('19', \Auth::user()->role_id) || \Auth::user()->role_id == 53 || \Auth::user()->role_id == 54 || \Auth::user()->role_id == 55): ?>
                            <a id="r1Menu" class="list-group-item" href="#" style="font-size: 14px;" onclick="showTutorials('r1')">Vehículos</a>
                            <a id="r2Menu" class="list-group-item" href="#" style="font-size: 14px;" onclick="showTutorials('r2')">Vida</a>
                            <a id="r3Menu" class="list-group-item" href="#" style="font-size: 14px;" onclick="showTutorials('r3')">Casa Habitación</a>
                            <a id="r4Menu" class="list-group-item" href="#" style="font-size: 14px;" onclick="showTutorials('r4')">Accidentes Personales</a>
                        <?php endif; ?>
                        <?php if(validateUserMenu(\Auth::user()->role_id, 15) == true || \Auth::user()->role_id == 53 || \Auth::user()->role_id == 54 || \Auth::user()->role_id == 55): ?>
                            <a id="chargesMenu" class="list-group-item" href="#" style="font-size: 14px;" onclick="showTutorials('charges')">Cobranzas</a>
                        <?php endif; ?>
                        <?php if(validateUserMenu(\Auth::user()->role_id, 35) == true || \Auth::user()->role_id == 53 || \Auth::user()->role_id == 54 || \Auth::user()->role_id == 55): ?>
                            <a id="reportsMenu" class="list-group-item" href="#" style="font-size: 14px;" onclick="showTutorials('reports')">Reportes</a>
                        <?php endif; ?>
                        <?php if(validateUserMenu(\Auth::user()->role_id, 56) == true || \Auth::user()->role_id == 53 || \Auth::user()->role_id == 54 || \Auth::user()->role_id == 55): ?>
                            <a id="administrationMenu" class="list-group-item" href="#" style="font-size: 14px;" onclick="showTutorials('administration')">Administración</a>
                        <?php endif; ?>
                        <a id="presentationMenu" class="list-group-item" href="#" style="font-size: 14px;" onclick="showTutorials('presentation')">Presentación</a>
                    </div>
                </div> 
                <div id="tutorialsBody" class="row">

                </div>
                <div class="hidden">
                    <div id="r1Tutorials" style="margin-top:15px">
                        <div class="col-md-6"> 
                            <h4 style="font-weight: bold">Cotización</h4>
                            <video width="350" height="200" controls preload="none">
                                <source src="<?php echo e(asset('https://files-videos.s3.amazonaws.com/VEHICULOS/COTIZACION/COTIZACI%C3%93N+VH+2.mp4')); ?>" type="video/mp4">
                                Your browser does not support HTML5 video.
                            </video>
                        </div>
                        <div class="col-md-6"> 
                            <h4 style="font-weight: bold">Vinculación</h4>
                            <video width="350" height="200" controls preload="none">
                                <source src="<?php echo e(asset('https://files-videos.s3.amazonaws.com/VEHICULOS/VINCULACION/VINCULACI%C3%93N+VH.mp4')); ?>" type="video/mp4">
                                Your browser does not support HTML5 video.
                            </video>
                        </div>
                        <div class="col-md-6"> 
                            <h4 style="font-weight: bold">Solicitud de Inspección</h4>
                            <video width="350" height="200" controls preload="none">
                                <source src="<?php echo e(asset('https://files-videos.s3.amazonaws.com/VEHICULOS/INSPECCION/SOLICITUD+DE+INSPECCI%C3%93N+VH.mp4')); ?>" type="video/mp4">
                                Your browser does not support HTML5 video.
                            </video>
                        </div>
                        <div class="col-md-6"> 
                            <h4 style="font-weight: bold">Confirmar Inspección</h4>
                            <video width="350" height="200" controls preload="none">
                                <source src="<?php echo e(asset('https://files-videos.s3.amazonaws.com/VEHICULOS/INSPECCION/CONFIRMAR+INSPECCI%C3%93N+VH.mp4')); ?>" type="video/mp4">
                                Your browser does not support HTML5 video.
                            </video>
                        </div>
                        <div class="col-md-6"> 
                            <h4 style="font-weight: bold">Pago y emisión usado</h4>
                            <video width="350" height="200" controls preload="none">
                                <source src="<?php echo e(asset('https://files-videos.s3.amazonaws.com/VEHICULOS/PAGO-Y-EMISION/PAGO+Y+EMISI%C3%93N+VH+USADO.mp4')); ?>" type="video/mp4">
                                Your browser does not support HTML5 video.
                            </video>
                        </div>
                        <div class="col-md-6"> 
                            <h4 style="font-weight: bold">Pago y emisión nuevo</h4>
                            <video width="350" height="200" controls preload="none">
                                <source src="<?php echo e(asset('https://files-videos.s3.amazonaws.com/VEHICULOS/PAGO-Y-EMISION/PAGO+Y+EMISI%C3%93N+VH+NUEVO.mp4')); ?>" type="video/mp4">
                                Your browser does not support HTML5 video.
                            </video>
                        </div>
                    </div>
                    <div id="r3Tutorials" style="margin-top:15px">
                        <div class="col-md-6"> 
                            <h4 style="font-weight: bold">Cotización</h4>
                            <video width="350" height="200" controls preload="none">
                                <source src="<?php echo e(asset('https://files-videos.s3.amazonaws.com/CASA-HABITACION/COTIZACION/COTIZACI%C3%93N+INCENDIO+2.mp4')); ?>" type="video/mp4">
                                Your browser does not support HTML5 video.
                            </video>
                        </div>
                        <div class="col-md-6"> 
                            <h4 style="font-weight: bold">Vinculación</h4>
                            <video width="350" height="200" controls preload="none">
                                <source src="<?php echo e(asset('https://files-videos.s3.amazonaws.com/CASA-HABITACION/VINCULACION/VINCULACI%C3%93N+CASA+HABITACI%C3%93N.mp4')); ?>" type="video/mp4">
                                Your browser does not support HTML5 video.
                            </video>
                        </div>
                        <div class="col-md-6"> 
                            <h4 style="font-weight: bold">Solicitud de Inspección</h4>
                            <video width="350" height="200" controls preload="none">
                                <source src="<?php echo e(asset('https://files-videos.s3.amazonaws.com/CASA-HABITACION/INSPECCION/SOLICITUD+DE+INSPECCI%C3%93N+INCENDIO.mp4')); ?>" type="video/mp4">
                                Your browser does not support HTML5 video.
                            </video>
                        </div>
                        <div class="col-md-6"> 
                            <h4 style="font-weight: bold">Confirmar Inspección</h4>
                            <video width="350" height="200" controls preload="none">
                                <source src="<?php echo e(asset('https://files-videos.s3.amazonaws.com/CASA-HABITACION/INSPECCION/CONFIRMACI%C3%93N+INSPECCI%C3%93N+INCENDIO.mp4')); ?>" type="video/mp4">
                                Your browser does not support HTML5 video.
                            </video>
                        </div>
                        <div class="col-md-6"> 
                            <h4 style="font-weight: bold">Pago y emision</h4>
                            <video width="350" height="200" controls preload="none">
                                <source src="<?php echo e(asset('https://files-videos.s3.amazonaws.com/CASA-HABITACION/PAGO-Y-EMISION/PAGO+Y+EMISI%C3%93N+INCENDIO.mp4')); ?>" type="video/mp4">
                                Your browser does not support HTML5 video.
                            </video>
                        </div>
                    </div>
                    <div id="r4Tutorials" style="margin-top:15px">
                        <div class="col-md-6"> 
                            <h4 style="font-weight: bold">Cotización</h4>
                            <video width="350" height="200" controls preload="none">
                                <source src="<?php echo e(asset('https://files-videos.s3.amazonaws.com/AP/COTIZACION/COTIZACI%C3%93N+AP+3.mp4')); ?>" type="video/mp4">
                                Your browser does not support HTML5 video.
                            </video>
                        </div>
                        <div class="col-md-6"> 
                            <h4 style="font-weight: bold">Beneficiarios</h4>
                            <video width="350" height="200" controls preload="none">
                                <source src="<?php echo e(asset('https://files-videos.s3.amazonaws.com/AP/BENEFICIARIOS/BENEFICIARIOS+AP.mp4')); ?>" type="video/mp4">
                                Your browser does not support HTML5 video.
                            </video>
                        </div>
                        <div class="col-md-6"> 
                            <h4 style="font-weight: bold">Vinculación</h4>
                            <video width="350" height="200" controls preload="none">
                                <source src="<?php echo e(asset('https://files-videos.s3.amazonaws.com/AP/VINCULACION/VINCULACI%C3%93N+AP.mp4')); ?>" type="video/mp4">
                                Your browser does not support HTML5 video.
                            </video>
                        </div>
                        <div class="col-md-6"> 
                            <h4 style="font-weight: bold">Pago y emision</h4>
                            <video width="350" height="200" controls preload="none">
                                <source src="<?php echo e(asset('https://files-videos.s3.amazonaws.com/AP/PAGO-Y-EMISION/PAGO+Y+EMISI%C3%93N+AP.mp4')); ?>" type="video/mp4">
                                Your browser does not support HTML5 video.
                            </video>
                        </div>
                    </div>
                    <div id="r2Tutorials" style="margin-top:15px">
                        <div class="col-md-6"> 
                            <h4 style="font-weight: bold">Cotización</h4>
                            <video width="350" height="200" controls preload="none">
                                <source src="<?php echo e(asset('https://files-videos.s3.amazonaws.com/VIDA/COTIZACION/COTIZACI%C3%93N+VIDA+5.mp4')); ?>" type="video/mp4">
                                Your browser does not support HTML5 video.
                            </video>
                        </div>
                        <div class="col-md-6"> 
                            <h4 style="font-weight: bold">Vinculación</h4>
                            <video width="350" height="200" controls preload="none">
                                <source src="<?php echo e(asset('https://files-videos.s3.amazonaws.com/VIDA/VINCULACION/VINCULACION+VIDA.mp4')); ?>" type="video/mp4">
                                Your browser does not support HTML5 video.
                            </video>
                        </div>
                        <div class="col-md-6"> 
                            <h4 style="font-weight: bold">Solicitud de aseguramiento</h4>
                            <video width="350" height="200" controls preload="none">
                                <source src="<?php echo e(asset('https://files-videos.s3.amazonaws.com/VIDA/SOLICITUD-DE-ASEGURAMIENTO/SOLICITUD+ASEGURAMIENTO+VIDA.mp4')); ?>" type="video/mp4">
                                Your browser does not support HTML5 video.
                            </video>
                        </div>
                        <div class="col-md-6"> 
                            <h4 style="font-weight: bold">Confirmar solicitud de aseguramiento</h4>
                            <video width="350" height="200" controls preload="none">
                                <source src="<?php echo e(asset('https://files-videos.s3.amazonaws.com/VIDA/SOLICITUD-DE-ASEGURAMIENTO/CONFIRMAR+SOLICITUD+DE+ASEGURAMIENTO.mp4')); ?>" type="video/mp4">
                                Your browser does not support HTML5 video.
                            </video>
                        </div>
                        <div class="col-md-6"> 
                            <h4 style="font-weight: bold">Pago y emisión</h4>
                            <video width="350" height="200" controls preload="none">
                                <source src="<?php echo e(asset('https://files-videos.s3.amazonaws.com/VIDA/PAGO-Y-EMISION/PAGO+Y+EMISI%C3%93N+VIDA.mp4')); ?>" type="video/mp4">
                                Your browser does not support HTML5 video.
                            </video>
                        </div>
                    </div>
                    <div id="chargesTutorials" style="margin-top:15px">
                        <div class="col-md-6"> 
                            <h4 style="font-weight: bold">Cobranzas</h4>
                            <video width="350" height="200" controls preload="none">
                                <source src="<?php echo e(asset('https://files-videos.s3.amazonaws.com/COBRANZAS/COBRANZAS.mp4')); ?>" type="video/mp4">
                                Your browser does not support HTML5 video.
                            </video>
                        </div>
                    </div>
                    <div id="reportsTutorials" style="margin-top:15px">
                        <div class="col-md-6"> 
                            <h4 style="font-weight: bold">Cartera</h4>
                            <video width="350" height="200" controls preload="none">
                                <source src="<?php echo e(asset('https://files-videos.s3.amazonaws.com/REPORTES/CARTERA/REPORTE+DE+CARTERA.mp4')); ?>" type="video/mp4">
                                Your browser does not support HTML5 video.
                            </video>
                        </div>
                        <div class="col-md-6"> 
                            <h4 style="font-weight: bold">Comercial</h4>
                            <video width="350" height="200" controls preload="none">
                                <source src="<?php echo e(asset('https://files-videos.s3.amazonaws.com/REPORTES/COMERCIAL/REPORTE+COMERCIAL.mp4')); ?>" type="video/mp4">
                                Your browser does not support HTML5 video.
                            </video>
                        </div>
                        <div class="col-md-6"> 
                            <h4 style="font-weight: bold">Técnico</h4>
                            <video width="350" height="200" controls preload="none">
                                <source src="<?php echo e(asset('https://files-videos.s3.amazonaws.com/REPORTES/TECNICO/REPORTE+T%C3%89CNICO.mp4')); ?>" type="video/mp4">
                                Your browser does not support HTML5 video.
                            </video>
                        </div>
                    </div>
                    <div id="administrationTutorials" style="margin-top:15px">
                        <div class="col-md-6"> 
                            <h4 style="font-weight: bold">Usuarios</h4>
                            <video width="350" height="200" controls preload="none">
                                <source src="<?php echo e(asset('https://files-videos.s3.amazonaws.com/ADMINISTRACION/USUARIOS.mp4')); ?>" type="video/mp4">
                                Your browser does not support HTML5 video.
                            </video>
                        </div>
                        <div class="col-md-6"> 
                            <h4 style="font-weight: bold">Canales, roles y permisos</h4>
                            <video width="350" height="200" controls preload="none">
                                <source src="<?php echo e(asset('https://files-videos.s3.amazonaws.com/ADMINISTRACION/CANALES%2C+ROLES+Y+PERMISOS.mp4')); ?>" type="video/mp4">
                                Your browser does not support HTML5 video.
                            </video>
                        </div>
                    </div>
                    <div id="presentationTutorials" style="margin-top:15px">
                        <div class="col-md-6"> 
                            <h4 style="font-weight: bold">Presentación</h4>
                            <video width="350" height="200" controls preload="none">
                                <source src="<?php echo e(asset('https://files-videos.s3.amazonaws.com/PRESENTACION/PRESENTACI%C3%93N.mp4')); ?>" type="video/mp4">
                                Your browser does not support HTML5 video.
                            </video>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function showTutorials(module) {
        event.preventDefault();

        //Remove Active Class from Menu
        var elems = document.getElementsByClassName("active");
        [].forEach.call(elems, function (el) {
            el.classList.remove("active");
        });
        //Show Loader
        $("#loaderGif").addClass('loaderGif');
        //Run Function After Timeout
        setTimeout(function () {
            //Hide Loader
            $("#loaderGif").removeClass('loaderGif');
            //Show Div Videos
            var tutorialsBody = document.getElementById('tutorialsBody');
            var moduleBody = document.getElementById(module + 'Tutorials');
            tutorialsBody.innerHTML = moduleBody.innerHTML;
            //Add Active Class
            var moduleMenu = document.getElementById(module + 'Menu');
            $(moduleMenu).addClass('active');
        }, 300);
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views\tutorials\index.blade.php ENDPATH**/ ?>