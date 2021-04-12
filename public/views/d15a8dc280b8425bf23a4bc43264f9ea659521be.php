<style>
    body {font-family: Arial;}

    /* Style the tab */
    .tab {
        overflow: hidden;
        border: 1px solid #ccc;
        background-color: #f1f1f1;
    }

    /* Style the buttons inside the tab */
    .tab button {
        background-color: inherit;
        float: left;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 14px 16px;
        transition: 0.3s;
        font-size: 17px;
    }

    /* Change background color of buttons on hover */
    .tab button:hover {
        background-color: #ddd;
    }

    /* Create an active/current tablink class */
    .tab button.active {
        background-color: #ccc;
    }

    /* Style the tab content */
    .tabcontent {
        display: none;
        padding: 6px 12px;
        border-top: none;
    }
</style>
<div class="container" style="width: 100%">

    <div class="col-md-12" style="margin-left: -15px">
        <h4>Listado de Masivos </h4>
        <?php echo $__env->make('pagination.items', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
        <div class="alert alert-success">
            <img src="<?php echo e(asset('images/iconos/ok.png')); ?>" alt="Girl in a jacket" style="width:40px;height:40px"><?php echo e(session('Inactive')); ?>

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
    </div>
    <div id="tableData">
        <?php echo $__env->make('pagination.massives', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</div><?php /**PATH C:\wamp64\www\magnussucre\resources\views\massive\carga.blade.php ENDPATH**/ ?>