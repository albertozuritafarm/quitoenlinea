

<?php $__env->startSection('content'); ?>
<script src="<?php echo e(assets('js/registerCustom.js')); ?>"></script>
<script src="<?php echo e(assets('js/user/create.js')); ?>"></script>
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
<div class="container-fluid" style="margin-top:15px; font-size:14px !important">
    <!--<div class="row justify-content-center border" style="margin-left:20%;">-->
    <div class="col-md-8 col-md-offset-2 border" style="padding: 15px">
        <div class="row">
            <div class="col-xs-12 registerForm" style="margin:12px;">
                <center>
                    <h4 style="font-weight:bold">Registro de Nuevo Usuario</h4>
                    <!--<h5>Datos del Cliente.</h5>-->
                </center>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-4 wizard_inicial" style="padding-left:0px !important"><div class="wizard_inactivo"></div></div>
            <div class="col-xs-12 col-sm-4 wizard_medio"><div class="wizard_activo registerForm">Usuario</div></div>
            <div class="col-xs-12 col-sm-4 wizard_final" style="padding-right: 0px !important"><div class="wizard_inactivo"></div></div>
        </div>
        <br>
        <form method="POST" action="<?php echo e(asset('/user')); ?>">
            <div class="col-md-12">
                <a class="btn btn-default registerForm" align="left" href="<?php echo e(asset('/user')); ?>" style="margin-left: -15px"> Cancelar </a>
                <input type="submit" style="float:right;margin-right: -15px;padding: 5px" class="btn btn-info registerForm" align="right" value="Guardar" onclick="submitForm()">
            </div>
            <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;">
                <div class="wizard_activo registerForm titleDivBorderTop">
                    <span class="titleLink">Datos del Usuario</span>
                    <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                </div>
                <center>
                    <div id="resultMessage" class="alert alert-danger hidden registerForm titleDivBorderTop" style="margin-top:5px; border-radius: 0px !important">
                        <strong>Danger!</strong> Indicates a dangerous or potentially negative action.
                    </div>
                </center>
                <?php if(session('Error')): ?>
                <div class="alert alert-warning registerForm titleDivBorderTop" style="margin-top:5px; border-radius: 0px !important">
                    <create>
                        <img src="<?php echo e(asset('images/iconos/warning.png')); ?>" alt="Girl in a jacket" style="width:40px;height:40px"> <?php echo e(session('Error')); ?>

                    </create>
                </div>
                <?php endif; ?>
                <?php if(session('documentError')): ?>
                <div class="alert alert-warning registerForm titleDivBorderTop" style="margin-top:5px; border-radius: 0px !important">
                    <center>
                        <img src="<?php echo e(asset('images/iconos/warning.png')); ?>" alt="Girl in a jacket" style="width:40px;height:40px"> <?php echo e(session('documentError')); ?>

                    </center>
                </div>
                <?php endif; ?>
                <?php echo e(csrf_field()); ?>

                <div class="col-md-6">
                    <div class="form-group">
                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="first_name"> Nombre(s)</label>
                        <input type="text" class="form-control registerForm" name="first_name" id="first_name" placeholder="Nombre"  value="<?php echo e(old('first_name')); ?>" required="required" tabindex="1">
                    </div>
                    <div class="form-group">
                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document_id"> Tipo Documento</label>
                        <select id="document_id" name="document_id" class="form-control registerForm" value="<?php echo e(old('document_id')); ?>" required  tabindex="3">
                            <option selected="true" disabled="disabled" value="">--Escoja Una---</option>
                            <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($document->id); ?>"><?php echo e($document->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="correo"> Correo</label>
                        <input type="email" class="form-control registerForm" name="email" id="email" placeholder="Correo" value="<?php echo e(old('email')); ?>" required tabindex="8">
                        <p id="emailError" style="color:red;font-weight: bold"></p>                        
<!--<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>-->
                        <?php if($errors->any()): ?>
                        <span style="color:red;font-weight:bold"><?php echo e($errors->first()); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="password"> Contraseña </label> <label class="own"><span class="glyphicon glyphicon-info-sign" style="color:#0099ff"><span class="own1" style="float:left">La contraseña debe tener: <br> 1) Un Numero <br> 2) Una Letra <br> 3) Un caracter Especial <br> 4) Debe tener al menos 7 caracteres</p></span></span></label>
                        <input type="password" id="password" class="form-control registerForm" name="password" placeholder="Contraseña" required tabindex="11">
                    </div>

                    <div class="form-group">
                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="agency"> Tipo de Usuario:</label>
                        <select name="typeSucre" id="typeSucre" class="form-control registerForm" required tabindex="15" onchange="typeSucreChange(this.value)">
                            <option selected="true" disabled="disabled" value="">--Escoja Una---</option>
                            <?php $__currentLoopData = $typeSucre; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sucre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($sucre->id); ?>"><?php echo e($sucre->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="agency"> Canal</label>
                        <select name="channel" id="channel" class="form-control registerForm" required tabindex="13">
                            <option selected="true" disabled="disabled" value="">--Escoja Una---</option>
<!--                            <?php $__currentLoopData = $channels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $channel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($channel->id); ?>"><?php echo e($channel->canalnegodes); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>-->
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="last_name"> Apellido(s)</label>
                        <input type="text" class="form-control registerForm" name="last_name" id="last_name" placeholder="Apellido" value="<?php echo e(old('last_name')); ?>" required tabindex="2">
                    </div>
                    <div class="form-group">
                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Cédula</label>
                        <input type="text" class="form-control registerForm" name="document" id="document" placeholder="Cédula"  disabled="disabled" value="<?php echo e(old('document')); ?>" required tabindex="4">
                        <p id="documentError" style="color:red;font-weight: bold"></p>
                    </div>
                    <div class="form-group">
                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="tipo"> Tipo</label>
                        <select name="tipo" id="tipo" class="form-control registerForm" required tabindex="10">
                            <option selected="true" disabled="disabled" value="">--Escoja Una---</option>
                            <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($type->id); ?>"><?php echo e($type->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="password"> Confirmar Contraseña</label> <label class="own"><span class="glyphicon glyphicon-info-sign" style="color:#0099ff"><span class="own1" style="float:left">La contraseña debe tener: <br> 1) Un Numero <br> 2) Una Letra <br> 3) Un caracter Especial <br> 4) Debe tener al menos 7 caracteres</p></span></span></label>
                        <input type="password" id="passwordCheck" class="form-control registerForm" name="passwordCheck" placeholder="Contraseña" required tabindex="12">
                    </div>
                    <div class="form-group">
                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="rol"> Rol</label>
                        <select name="rol" id="rol" class="form-control registerForm" value="<?php echo e(old('rol')); ?>" required tabindex="9">
                            <option selected="true" disabled="disabled" value="">--Escoja Una---</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="agency"> Agencia de Canal</label>
                        <select name="agency" id="agency" class="form-control registerForm" required tabindex="14">
                            <option selected="true" disabled="disabled" value="">--Escoja Una---</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-12" style="padding-bottom:15px">
                <a class="btn btn-default registerForm" align="left" href="<?php echo e(asset('/user')); ?>" style="margin-left: -15px"> Cancelar </a>
                <input type="submit" style="float:right;margin-right: -15px;padding: 5px" class="btn btn-info registerForm" align="right" value="Guardar" onclick="submitForm()">
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
    
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views/user/create.blade.php ENDPATH**/ ?>