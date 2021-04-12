<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <title>Tu póliza en linea</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link href="<?php echo e(assets('css/bootstrap-3.3.6-dist/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo e(assets('scripts/jquery-ui-1.11.4.custom/jquery-ui.min.css')); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo e(assets('css/estilos_generales.css')); ?>" rel="stylesheet" type="text/css"/>
        <link rel="shortcut icon" href="images/favicons/favicon.ico">
    </head>

    <body class="fondo_login">
        <noscript style="color:red; text-align:center">Esta aplicación requiere tener habilitado JavaScript en su navegador.</noscript>
        <div class="container-fluid" style="text-align:center; width:100%;">
            <div class="row" style="max-width:380px; margin:0 auto;">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="background-color:#EEE; border-radius:10px; padding:30px 20px; margin-top:60px" > 
                    <?php echo $__env->yieldContent('content'); ?>
                </div> 
            </div>
        </div>


        <footer class="modal-footer">
            <a target="_blank" href="#" style="border:none; color:#FFF; font-size:12px">Magnus | <?php echo e(date('Y')); ?> </a>
        </footer>
        <script src="<?php echo e(assets('js/jquery-2.2.4.min.js')); ?>"></script>
        <script src="<?php echo e(assets('css/bootstrap-3.3.6-dist/js/bootstrap.min.js')); ?>"></script>
    </body>
</html><?php /**PATH C:\xampp\htdocs\laravel\magnussucre\resources\views/layouts/app_login.blade.php ENDPATH**/ ?>