

<?php $__env->startSection('content'); ?>
<script src="<?php echo e(assets('js/registerCustom.js')); ?>"></script>
<script src="<?php echo e(assets('js/channels/product.js')); ?>"></script>
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
    .bloc {
        display: inline-block;
        vertical-align: top;
        overflow: hidden;
    }
</style>

<div class="container" style="margin-top:15px; font-size:14px !important">
    <!--<div class="row justify-content-center border" style="margin-left:20%;">-->
    <div class="col-md-10 col-md-offset-1 border" style="padding: 15px">
        <div class="row">
            <div class="col-xs-12 registerForm" style="margin:12px;">
                <center>
                    <h4 style="font-weight:bold">Asignar Productos al Canal</h4>
                </center>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-4 wizard_inicial" style="padding-left:0px !important"><div class="wizard_inactivo"></div></div>
            <div class="col-xs-12 col-sm-4 wizard_medio"><div id="firstStepWizard" class="wizard_activo registerForm">Asignación</div></div>
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
        <br>
        <form method="POST" action="<?php echo e(asset('/channel/product/store')); ?>">
            <input type="hidden" id="channelId" name="channelId" value="<?php echo e($channel->id); ?>">
            <div class="col-md-12">
                <div class="col-md-12" style="margin-top:5px;padding-top:15px;">
                    <div class="row" style="float:left">
                        <a class="btn btn-default registerForm" align="right" href="<?php echo e(asset('/channel')); ?>" style="width:90px;"> Cancelar </a>
                    </div>
                    <div class="row" style="float:right">
                        <button onclick="channelProductStore()" type="button" class="btn btn-info registerForm" align="right" style="float:right;padding: 5px;width:90px"> Guardar </button>
                    </div>
                </div>
                <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;padding-bottom: 25px;">
                    <div class="wizard_activo registerForm titleDivBorderTop">
                        <span class="titleLink">Productos</span>
                        <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                    </div>
                    <div class="col-md-5" style="margin-top:15px;">
                        <center><h4>Productos</h4></center>
                        <select class="select_products border" id="products" name="products" multiple size="10" style="width:350px;">
                            <?php $__currentLoopData = $unSelectedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($pro->id); ?>"><?php echo e($pro->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-1" style="margin-top:8%;margin-right:40px;">
                        <button type="button" class="btn btn-success" onclick="selectProducts();" style="width:100px;">
                            <span class="glyphicon glyphicon-arrow-right"></span>
                        </button>
                        <hr>
                        <button type="button" class="btn btn-info" onclick="unSelectProducts();" style="width:100px;">
                            <span class="glyphicon glyphicon-arrow-left"></span>
                        </button>
                    </div>
                    <div class="col-md-5" style="margin-top:15px">
                        <center><h4>Productos Asignados</h4></center>
                        <select class="selected_products border" id="selected_products" name="selected_products" multiple size="10"" style="width:350px;">
                            <?php $__currentLoopData = $selectedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($pro->id); ?>"><?php echo e($pro->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-12" style="padding-bottom:15px;">
                    <div class="row" style="float:left">
                        <a class="btn btn-default registerForm" align="right" href="<?php echo e(asset('/channel')); ?>" style="width:90px;"> Cancelar </a>
                    </div>
                    <div class="row" style="float:right">
                        <button onclick="channelProductStore()" type="button" class="btn btn-info registerForm" align="right" style="float:right;padding: 5px;width:90px"> Guardar </button>
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views\channels\product.blade.php ENDPATH**/ ?>