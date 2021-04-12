

<?php $__env->startSection('content'); ?>
<script src="<?php echo e(assets('js/rol/create.js')); ?>"></script>
<style>
    .form-group{
        margin-top:25px !important;
        margin-bottom: 25px !important;
    }
    /*    .owners{
            width:500px;
            height:300px;
            border:0px solid #ff9d2a;
            margin:auto;
            float:left;
    
        }*/
    span.own1{
        background:#F8F8F8;
        border: 5px solid #DFDFDF;
        /*color: #717171;*/
        color: black;
        font-size: 12px;
        height: auto;
        width:310px;
        letter-spacing: 1px;
        line-height: 20px;
        position:absolute;
        text-align: left;
        /*text-transform: uppercase;*/
        top: auto;
        left:5px;
        display:none;
        padding:10px;
        border-radius: 10px;
        font-family: 'Roboto',sans-serif,Helvetica Neue,Arial !important;


    }

    label.own{
        margin:0px;
        /*float:left;*/
        position:relative;
        cursor:pointer;
    }

    label.own:hover span{
        display:block;
        z-index:9;
    }

    .inputRedFocus{
        border-color: red;
    }

</style>
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
<div class="container" style="margin-top:15px; font-size:14px !important">
    <!--<div class="row justify-content-center border" style="margin-left:20%;">-->
    <div class="col-md-8 col-md-offset-2 border">
        <div class="row">
            <div class="col-xs-12 registerForm" style="margin:12px;">
                <center>
                    <h4 style="font-weight:bold">Registro de Nuevo Rol</h4>
                    <!--<h5>Datos del Cliente.</h5>-->
                </center>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-sm-4 wizard_inicial"><div style="margin-left:-10px" id="" class="wizard_inactivo registerForm"></div></div>
            <div class="col-md-4 col-sm-4 wizard_medio"><div id="" class="wizard_activo registerForm">ROL</div></div>
            <div class="col-md-4 col-sm-4 wizard_final"><div style="margin-right:-10px;" id="" class="wizard_inactivo registerForm"></div></div>
        </div>
        <br><br>
        <?php if(session('Error')): ?>
        <div class="alert alert-warning">
            <create>
                <img src="<?php echo e(asset('images/iconos/warning.png')); ?>" alt="Girl in a jacket" style="width:40px;height:40px"> <?php echo e(session('Error')); ?>


            </create>
        </div>
        <?php endif; ?>
        <?php if(session('documentError')): ?>
        <div class="alert alert-warning">
            <center>
                <img src="<?php echo e(asset('images/iconos/warning.png')); ?>" alt="Girl in a jacket" style="width:40px;height:40px"> <?php echo e(session('documentError')); ?>

            </center>
        </div>
        <?php endif; ?>
        <div id="errorMessageDiv" class="alert alert-danger hidden">
            <center>
                
            </center>
        </div>
        <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;margin-bottom: 5px">
            <div class="wizard_activo registerForm titleDiv">
                <a href="#" class="titleLink">Editar ROL</a>
                <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
            </div>
            <div class="col-md-12">
                <div class="col-md-12">
                    <div class="form-group">
                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label for="nameRol" style="font-weight:bold;font-size:14px">Nombre:</label>
                        <input type="text" class="form-control" id="nameRol" name="nameRol" placeholder="Nombre del Rol" value="<?php echo e($rol->name); ?>">
                        <input type="hidden" id="idRol" name="idRol" value="<?php echo e($rol->id); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label for="rol_entity" style="font-weight:bold;font-size:14px">Entidad:</label>
                        <select class="form-control" name="rol_entity" id="rol_entity">
                            <option value="">-- Escoja Una--</option>
                            <?php $__currentLoopData = $rolEntity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option  <?php if($r->id == $rol->rol_entity_id): ?> selected="true" <?php endif; ?> value="<?php echo e($r->id); ?>"><?php echo e($r->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label for="rol_type" style="font-weight:bold;font-size:14px">Tipo de Rol:</label>
                        <select class="form-control" name="rol_type" id="rol_type">
                            <option value="">-- Escoja Una--</option>
                            <?php $__currentLoopData = $rolType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option <?php if($r->id == $rol->rol_type_id): ?> selected="true" <?php endif; ?> value="<?php echo e($r->id); ?>"><?php echo e($r->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>                    
                    </div>
                </div>
                <div class="col-md-12">
                    <label style="font-weight:bold;font-size:14px">Permisos:</label>
                    <table id="newPaginatedTableNoOrdering" class="table table-striped row-border table-responsive hover stripe borderTable">
                        <thead>
                            <tr style="background-color: #4444448c;color:white;text-shadow: 0px -1px 2px #000, 0px 1px 2px #000;">
                                <th align="center" style="width:20%">Modulo</th>
                                <th align="center">Ver</th>
                                <th align="center">Editar</th>
                                <th align="center">Cancelar</th>
                                <th align="center">Crear</th>
                            </tr>
                        </thead>
                        <tbody>
                             <?php $__currentLoopData = $menuMain; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $main): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                             <tr style="background-color: #44444496;">
                                <td align="left" style="font-weight: bold"><?php echo e($main->name); ?></td>
                                <td align="center"><input type="checkbox" id="main_view_<?php echo e($main->id); ?>" class="chk" name="main_view_<?php echo e($main->id); ?>" value="<?php echo e($main->id); ?>_view" <?php if($main->sub_menu == 0): ?> onchange="chkChange('main','view',<?php echo e($main->id); ?>,'false')" <?php else: ?> onchange="chkChange('main','view',<?php echo e($main->id); ?>,'true')" <?php endif; ?> <?php if($main->checkedView != null): ?> checked <?php endif; ?>></td>
                                <td align="center"><input type="checkbox" id="main_edit_<?php echo e($main->id); ?>" class="chk" name="main_view_<?php echo e($main->id); ?>" value="<?php echo e($main->id); ?>_edit" <?php if($main->sub_menu == 0): ?> onchange="chkChange('main','edit',<?php echo e($main->id); ?>,'false')" <?php else: ?> onchange="chkChange('main','edit',<?php echo e($main->id); ?>,'true')" <?php endif; ?>  <?php if($main->checkedEdit != null): ?> checked <?php endif; ?> <?php if((checkPermits($main->id, 'edit')) == false): ?> disabled="disabled" <?php endif; ?>></td>
                                <td align="center"><input type="checkbox" id="main_cancel_<?php echo e($main->id); ?>" class="chk" name="main_view_<?php echo e($main->id); ?>" value="<?php echo e($main->id); ?>_cancel" <?php if($main->sub_menu == 0): ?> onchange="chkChange('main','cancel',<?php echo e($main->id); ?>,'false')" <?php else: ?> onchange="chkChange('main','cancel',<?php echo e($main->id); ?>,'true')" <?php endif; ?>  <?php if($main->checkedCancel != null): ?> checked <?php endif; ?> <?php if((checkPermits($main->id, 'cancel')) == false): ?> disabled="disabled" <?php endif; ?>></td>
                                <td align="center"><input type="checkbox" id="main_create_<?php echo e($main->id); ?>" class="chk" name="main_view_<?php echo e($main->id); ?>" value="<?php echo e($main->id); ?>_create" <?php if($main->sub_menu == 0): ?> onchange="chkChange('main','create',<?php echo e($main->id); ?>,'false')" <?php else: ?> onchange="chkChange('main','create',<?php echo e($main->id); ?>,'true')" <?php endif; ?>  <?php if($main->checkedCreate != null): ?> checked <?php endif; ?> <?php if((checkPermits($main->id, 'create')) == false): ?> disabled="disabled" <?php endif; ?>></td>
                             </tr>
                                 <?php $__currentLoopData = $menuSecondary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $secondary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($secondary->parent_id == $main->id): ?>
                                        <tr style="background-color: #44444438;">
                                            <td align="left" style="font-weight: bold;padding-left: 25px"><?php echo e($secondary->name); ?></td>
                                            <td align="center"><input type="checkbox" id="secondary_view_<?php echo e($main->id); ?>_<?php echo e($secondary->id); ?>" class="chk child_secondary_view_<?php echo e($main->id); ?>" name="secondary_view_<?php echo e($main->id); ?>_<?php echo e($secondary->id); ?>[]" value="<?php echo e($secondary->id); ?>_view" <?php if($secondary->sub_menu == 0): ?> onchange="chkSecondaryChange('secondary','view',<?php echo e($secondary->id); ?>,<?php echo e($main->id); ?>,'false')" <?php else: ?> onchange="chkSecondaryChange('secondary','view',<?php echo e($secondary->id); ?>,<?php echo e($main->id); ?>,'true')" <?php endif; ?>  <?php if($secondary->checkedView != null): ?> checked <?php endif; ?>></td>
                                            <td align="center"><input type="checkbox" id="secondary_edit_<?php echo e($main->id); ?>_<?php echo e($secondary->id); ?>" class="chk child_secondary_edit_<?php echo e($main->id); ?>" name="secondary_edit_<?php echo e($main->id); ?>_<?php echo e($secondary->id); ?>[]" value="<?php echo e($secondary->id); ?>_edit" <?php if($secondary->sub_menu == 0): ?> onchange="chkSecondaryChange('secondary','edit',<?php echo e($secondary->id); ?>,<?php echo e($main->id); ?>,'false')" <?php else: ?> onchange="chkSecondaryChange('secondary','edit',<?php echo e($secondary->id); ?>,<?php echo e($main->id); ?>,'true')" <?php endif; ?>  <?php if($secondary->checkedEdit != null): ?> checked <?php endif; ?> <?php if((checkPermits($secondary->id, 'edit')) == false): ?> disabled="disabled" <?php endif; ?>></td>
                                            <td align="center"><input type="checkbox" id="secondary_cancel_<?php echo e($main->id); ?>_<?php echo e($secondary->id); ?>" class="chk child_secondary_cancel_<?php echo e($main->id); ?>" name="secondary_cancel_<?php echo e($main->id); ?>_<?php echo e($secondary->id); ?>[]" value="<?php echo e($secondary->id); ?>_cancel" <?php if($secondary->sub_menu == 0): ?> onchange="chkSecondaryChange('secondary','cancel',<?php echo e($secondary->id); ?>,<?php echo e($main->id); ?>,'false')" <?php else: ?> onchange="chkSecondaryChange('secondary','cancel',<?php echo e($secondary->id); ?>,<?php echo e($main->id); ?>,'true')" <?php endif; ?>  <?php if($secondary->checkedCancel != null): ?> checked <?php endif; ?> <?php if((checkPermits($secondary->id, 'cancel')) == false): ?> disabled="disabled" <?php endif; ?>></td>
                                            <td align="center"><input type="checkbox" id="secondary_create_<?php echo e($main->id); ?>_<?php echo e($secondary->id); ?>" class="chk child_secondary_create_<?php echo e($main->id); ?>" name="secondary_create_<?php echo e($main->id); ?>_<?php echo e($secondary->id); ?>[]" value="<?php echo e($secondary->id); ?>_create" <?php if($secondary->sub_menu == 0): ?> onchange="chkSecondaryChange('secondary','create',<?php echo e($secondary->id); ?>,<?php echo e($main->id); ?>,'false')" <?php else: ?> onchange="chkSecondaryChange('secondary','create',<?php echo e($secondary->id); ?>,<?php echo e($main->id); ?>,'true')" <?php endif; ?>  <?php if($secondary->checkedCreate != null): ?> checked <?php endif; ?> <?php if((checkPermits($secondary->id, 'create'))  == false): ?> disabled="disabled" <?php endif; ?>></td>
                                        </tr>
                                        <?php $__currentLoopData = $menuThird; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $third): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($third->parent_id == $secondary->id): ?>
                                            <tr>
                                                <td align="left" style="font-weight: bold;padding-left:50px"><?php echo e($third->name); ?></td>
                                                <td align="center"><input type="checkbox" id="third_view_<?php echo e($main->id); ?>_<?php echo e($third->id); ?>"  class="chk child_third_view_<?php echo e($secondary->id); ?>" name="third_view_<?php echo e($main->id); ?>[]" value="<?php echo e($third->id); ?>_view" onchange="chkThirdChange('secondary','view',<?php echo e($third->id); ?>,<?php echo e($main->id); ?>,<?php echo e($secondary->id); ?>)" <?php if($third->checkedView != null): ?> checked <?php endif; ?>></td>
                                                <td align="center"><input type="checkbox" id="third_edit_<?php echo e($main->id); ?>_<?php echo e($third->id); ?>"  class="chk child_third_edit_<?php echo e($secondary->id); ?>" name="third_edit_<?php echo e($main->id); ?>[]" value="<?php echo e($third->id); ?>_edit" onchange="chkThirdChange('secondary','edit',<?php echo e($third->id); ?>,<?php echo e($main->id); ?>,<?php echo e($secondary->id); ?>)" <?php if($third->checkedEdit != null): ?> checked <?php endif; ?> <?php if((checkPermits($third->id, 'edit')) == false): ?> disabled="disabled" <?php endif; ?>></td>
                                                <td align="center"><input type="checkbox" id="third_cancel_<?php echo e($main->id); ?>_<?php echo e($third->id); ?>"  class="chk child_third_cancel_<?php echo e($secondary->id); ?>" name="third_cancel_<?php echo e($main->id); ?>[]" value="<?php echo e($third->id); ?>_cancel" onchange="chkThirdChange('secondary','cancel',<?php echo e($third->id); ?>,<?php echo e($main->id); ?>,<?php echo e($secondary->id); ?>)" <?php if($third->checkedCancel != null): ?> checked <?php endif; ?> <?php if((checkPermits($third->id, 'cancel')) == false): ?> disabled="disabled" <?php endif; ?>></td>
                                                <td align="center"><input type="checkbox" id="third_create_<?php echo e($main->id); ?>_<?php echo e($third->id); ?>"  class="chk child_third_create_<?php echo e($secondary->id); ?>" name="third_create_<?php echo e($main->id); ?>[]" value="<?php echo e($third->id); ?>_create" onchange="chkThirdChange('secondary','create',<?php echo e($third->id); ?>,<?php echo e($main->id); ?>,<?php echo e($secondary->id); ?>)" <?php if($third->checkedCreate != null): ?> checked <?php endif; ?> <?php if((checkPermits($third->id, 'create')) == false): ?> disabled="disabled" <?php endif; ?>></td>
                                            </tr>
                                        <?php else: ?>
                                        <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                    <?php endif; ?>
                                 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                             <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div id="">
            <div class="col-md-12" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                <div class="col-md-1">
                    <a class="btn btn-default registerForm" align="right" href="<?php echo e(asset('/rol')); ?>" style="margin-left: -30px;"> Cancelar </a>
                </div>
                <div class="">
                    <input id="btnSubmit" type="submit" class="btn btn-primary registerForm" value="Guardar" onclick="submitFormUpdate()" style="float:right;margin-right: -15px">
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views\rols\edit.blade.php ENDPATH**/ ?>