<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <title>Magnus</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link href="<?php echo e(asset('css/bootstrap-3.3.6-dist/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo e(asset('js/jquery-ui-1.11.4.custom/jquery-ui.min.css')); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo e(asset('css/estilos_generales.css')); ?>" rel="stylesheet" type="text/css"/>
        <link rel="shortcut icon" href="<?php echo e(asset('images/favicon.png')); ?>">
        <link href="<?php echo e(asset('css/menu.css')); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo e(asset('css/Simple-Line/simple-line-icons.css')); ?>" rel="stylesheet" type="text/css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css"> 
        <link href="<?php echo e(asset('css/estilos_generales.css')); ?>" rel="stylesheet" type="text/css" />
        <script type="text/javascript">
            function load() {
                var loaderGif = document.getElementById("loaderGif");
                loaderGif.classList.remove("loaderGif");
                var loaderBody = document.getElementById("loaderBody");
                loaderBody.classList.remove("loaderBody");
            }
            window.onload = load;
        </script>
    </head>

    <body style="font-size: 11px">
        <div id='loaderGif' class="loaderGif"></div>
        <div id="loaderBody" class="loaderBody"></div>
        <!--<div id="wrapper" class="toggled">-->

            <!--<div id="Contenedor_Principal">-->
            <!--<div id="content" class="">-->
            <div class="">
                <?php echo $__env->yieldContent('content'); ?>
            </div>

        <!--</div>-->


        <footer class="modal-footer">
            <a href="#" style="border:none; color:black; font-size:12px">Magnus | 2019</a>
        </footer>
        <script src="<?php echo e(asset('js/jquery-2.2.4.min.js')); ?>"></script>
        <script src="<?php echo e(asset('js/jquery-ui-1.11.4.custom/jquery-ui.min.js')); ?>"></script>
        <script src="<?php echo e(asset('js/Generales.js')); ?>"></script>
        <script src="<?php echo e(asset('js/login/login.js')); ?>"></script>
        <script src="<?php echo e(asset('css/bootstrap-3.3.6-dist/js/bootstrap.min.js')); ?>"></script>
        <script src="<?php echo e(asset('js/jquery-ui-1.11.4.custom/jquery-ui.min.js')); ?>"></script>
        <!--<script src="<?php echo e(asset('js/Generales.js')); ?>"></script>-->
        <script src="<?php echo e(asset('js/principal/principal.js')); ?>"></script>
        <script src="<?php echo e(asset('js/loader.js')); ?>"></script>
        <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
        <script src="<?php echo e(asset('js/principal/principal.js')); ?>"></script>
        <script src="<?php echo e(asset('js/jquery.formautofill.js')); ?>"></script>
        <!--<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>-->
        <!--<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>-->
        <!--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.18/b-1.5.6/b-colvis-1.5.6/datatables.min.cs        s"/>-->
        <link href="<?php echo e(asset('/css/datatables/datatables.min.css')); ?>" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="<?php echo e(asset('/css/datatables/datatables.min.js')); ?>"></script>


        <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.18/b-1.5.6/b-colvis-1.5.6/datatables.min.js"></script>
        <script type="text/javascript" src="https://cdn.rawgit.com/mgalante/jquery.redirect/master/jquery.redirect.js"></script>
        <script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
        <script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js" ></script>
    </body>
</html><?php /**PATH C:\wamp64\www\magnussucre\resources\views\layouts\pdf.blade.php ENDPATH**/ ?>