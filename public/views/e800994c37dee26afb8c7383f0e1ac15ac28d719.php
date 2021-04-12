<!DOCTYPE html>
<html lang="es">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <title>Tu póliza en linea test</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link href="<?php echo e(assets('css/bootstrap-3.3.6-dist/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo e(assets('js/jquery-ui-1.11.4.custom/jquery-ui.min.css')); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo e(assets('css/estilos_generales.css')); ?>" rel="stylesheet" type="text/css"/>
        <link rel="shortcut icon" href="<?php echo e(asset('images/favicons/favicon.ico')); ?>">
        <link href="<?php echo e(assets('css/menu.css')); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo e(assets('css/Simple-Line/simple-line-icons.css')); ?>" rel="stylesheet" type="text/css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css"> 
        <link href="<?php echo e(assets('css/estilos_generales.css')); ?>" rel="stylesheet" type="text/css" />
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
    <p align="center" style="position:absolute;left:30px;margin-top:10px;"><img src="<?php echo e(assets('images/logo_seguros_sucre_2.png')); ?>" width="130px"/></p>
    <div id="wrapper" class="">
        <div id="page-content-wrapper" style="background-color:#FFFFFF; border:solid 1px #E5E5E5;">
            <div id="Panel_Bienvenido" class="Panel_Bienvenido hidden-xs">
<!--                <li class="dropdown" style="display:inline">
                    <a href="#" class="dropdown-toggle"><span class="glyphicon glyphicon-question-sign"></span><span class="caret1" style="margin-top:16px;"></span></a>&emsp;
                    <ul class="dropdown-menu" role="menu" style="background-color: white">
                        <li class="" style="background-color: white;"><a href="<?php echo e(asset('/ticket')); ?>" style="cursor:pointer;background-color: white;color:grey;">Soporte</a></li>
                    </ul>
                </li>-->
                <li class="dropdown" style="display:inline">
                    <a href="#" class="dropdown-toggle"><span class="glyphicon glyphicon-question-sign"></span><span class="caret1" style="margin-top:16px;"></span></a>&emsp;
                    <ul class="dropdown-menu" role="menu" style="background-color: white;margin-top:-1px;">
                        <li class="" style="background-color: white;"><a href="<?php echo e(asset('/tutorials')); ?>" style="cursor:pointer;background-color: white;color:grey;">Tutoriales</a></li>
                    </ul>
                </li>
                <li class="dropdown" style="display:inline">
                    <a href="#" class="dropdown-toggle caret1"><span class="glyphicon glyphicon-user" style="margin-right:8px"></span><?php echo e(\Auth::user()->first_name); ?> <?php echo e(\Auth::user()->last_name); ?><span class="caret1" style="float:right; margin-top:16px;"></span></a>
                    <ul class="dropdown-menu" role="menu" style="background-color: white;margin-top:-1px;">
                        <li style="background-color: white;"><a href="<?php echo e(asset('/user/password/change')); ?>" style="cursor:pointer;color:grey;">Cambio de contraseña</a></li>
                    </ul>
                </li>
            </div>
            <div id="Panel_Salir" class="Panel_Salir hidden-xs"><span class="glyphicon glyphicon-log-out" style="margin-right:8px"></span>Salir</div>
        </div>

        <!--<div id="Contenedor_Principal">-->
        <div id="content" class="">
            <nav class="navbar navbar-inverse menuCustom" style="font-size: 14px;border-radius:0">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <div id="navbar" class="navbar-collapse collapse">
                        <ul class="nav navbar-nav">
                            <?php $__currentLoopData = loadMenuMain(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($menu->sub_menu == 1): ?>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle menuCustom" data-toggle="dropdown" style="color:white;"><span class="<?php echo e($menu->icon); ?>"></span> <span ><?php echo e($menu->name); ?></span><span class="caret"></span></a>
                                <ul class="dropdown-menu menuCustom" role="menu">
                                    <?php $__currentLoopData = loadMenuChild($menu->id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menuChild): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($menuChild->sub_menu == 1): ?>
                                        <li class="dropdown-submenu">
                                            <a href="#" class="dropdown-toggle menuCustom"><?php echo e($menuChild->name); ?></a>
                                            <ul class="dropdown-menu menuCustom" role="menu">
                                                <?php $__currentLoopData = loadMenuChild($menuChild->id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menuChild2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li><a class="menuCustom" href="<?php echo e(asset('')); ?><?php echo e($menuChild2->url); ?>"><?php echo e($menuChild2->name); ?></a></li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        </li>
                                        <?php else: ?>
                                        <?php if($menuChild->id == 10): ?>
                                            <?php if(Auth::user()->type_id == 2 || Auth::user()->type_id == 3): ?>
                                                <li><a class="menuCustom" href="<?php echo e(asset('')); ?><?php echo e($menuChild->url); ?>"><?php echo e($menuChild->name); ?></a></li>
                                            <?php else: ?>
                                            <?php endif; ?>
                                        <?php elseif($menuChild->id == 1): ?>
                                            <?php if(Auth::user()->type_id == 1 || Auth::user()->type_id == 3): ?>
                                                <li><a class="menuCustom" href="<?php echo e(asset('')); ?><?php echo e($menuChild->url); ?>"><?php echo e($menuChild->name); ?></a></li>
                                            <?php else: ?>
                                            <?php endif; ?>
                                            <?php else: ?>   
                                                <li><a class="menuCustom" href="<?php echo e(asset('')); ?><?php echo e($menuChild->url); ?>"><?php echo e($menuChild->name); ?></a></li>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </li>
                            <?php else: ?>
                            <li><a class="menuCustom" href="<?php echo e(asset('')); ?><?php echo e($menu->url); ?>" style="color:white;"><span class="<?php echo e($menu->icon); ?>"></span> <?php echo e($menu->name); ?></a></li>
                            <?php endif; ?>
                            <!--<li class="vl"></li>-->
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            </nav>
            <?php echo $__env->yieldContent('content'); ?>
        </div>

    </div>


    <footer class="modal-footer">
        <a href="#" style="float:left;border:none; color:black; font-size:12px">Magnus | 2020</a>
    </footer>
    <script src="<?php echo e(assets('js/jquery-2.2.4.min.js')); ?>"></script>
    <script src="<?php echo e(assets('js/jquery-ui-1.11.4.custom/jquery-ui.min.js')); ?>"></script>
    <script src="<?php echo e(assets('js/currency.js')); ?>"></script>
    <script src="<?php echo e(assets('js/Generales.js')); ?>"></script>
    <script src="<?php echo e(assets('js/login/login.js')); ?>"></script>
    <script src="<?php echo e(assets('css/bootstrap-3.3.6-dist/js/bootstrap.min.js')); ?>"></script>
    <!--<script src="<?php echo e(asset('js/Generales.js')); ?>"></script>-->
    <script src="<?php echo e(assets('js/principal/principal.js')); ?>"></script>
    <script src="<?php echo e(assets('js/loader.js')); ?>"></script>
    <!--<script src="https://code.jquery.com/jquery-3.3.1.js"></script>-->
    <script src="<?php echo e(assets('js/GlobalVars.js')); ?>"></script>
    <script src="<?php echo e(assets('js/jquery.formautofill.js')); ?>"></script>
    <!--<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>-->
    <!--<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>-->
    <!--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.18/b-1.5.6/b-colvis-1.5.6/datatables.min.cs        s"/>-->
    <link href="<?php echo e(assets('css/DataTables/datatables.min.css')); ?>" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="<?php echo e(assets('css/DataTables/datatables.min.js')); ?>"></script>


    <script src="<?php echo e(assets('js/jquery-ui.js')); ?>"></script>
    <script type="text/javascript"  src="<?php echo e(assets('js/datatables.min.js')); ?>"></script>
    <script src="<?php echo e(assets('js/modernizer.js')); ?>"></script>
    <script src="<?php echo e(assets('js/jquery-redirect.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(assets('js/dateFormat/jquery-dateFormat.min.js')); ?>"></script>
    <!-- Smartsupp Live Chat script -->
</body>
</html><?php /**PATH C:\wamp64\www\magnussucre\resources\views/layouts/app.blade.php ENDPATH**/ ?>