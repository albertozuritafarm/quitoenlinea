

<?php $__env->startSection('content'); ?>
<!--<div class="se-pre-con"></div>-->
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
<!--<script src="<?php echo e(asset('js/payments/createRemote.js')); ?>"></script>-->
<!--<script src="<?php echo e(asset('js/registerCustom.js')); ?>"></script>-->
<script src="<?php echo e(assets('js/remote/vehi_pictures.js')); ?>"></script>
<link href="<?php echo e(assets('css/sales/index.css')); ?>" rel="stylesheet" type="text/css"/>
<style>
    .scrollme {
    overflow-x: auto;
}
::-webkit-scrollbar {
    -webkit-appearance: none;
}

::-webkit-scrollbar:vertical {
    width: 12px;
}

::-webkit-scrollbar:horizontal {
    height: 12px;
}

::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, .5);
    border-radius: 10px;
    border: 2px solid #ffffff;
}

::-webkit-scrollbar-track {
    border-radius: 10px;  
    background-color: #ffffff; 
}
.table-responsive-stack tr {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: horizontal;
  -webkit-box-direction: normal;
      -ms-flex-direction: row;
          flex-direction: row;
}


.table-responsive-stack td,
.table-responsive-stack th {
   display:block;
/*      
   flex-grow | flex-shrink | flex-basis   */
   -ms-flex: 1 1 auto;
    flex: 1 1 auto;
}

.table-responsive-stack .table-responsive-stack-thead {
   font-weight: bold;
}

@media  screen and (max-width: 768px) {
   .table-responsive-stack tr {
      -webkit-box-orient: vertical;
      -webkit-box-direction: normal;
          -ms-flex-direction: column;
              flex-direction: column;
      border-bottom: 3px solid #ccc;
      display:block;
      
   }
   /*  IE9 FIX   */
   .table-responsive-stack td {
      float: left\9;
      width:100%;
   }
}
</style>
<script>
    $(document).ready(function () {
         $("#plate").change(function () {
             var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var plate = document.getElementById("plate").value;
            var salId = document.getElementById("salId").value;
            var url = '/remote/loadVehiPictures';
            $.ajax({
                url: url,
                type: "POST",
                /* send the csrf-token and the input to the controller */
                data: {_token: CSRF_TOKEN, plate: plate, salId:salId},
                dataType: 'JSON',
                beforeSend: function () {
                    // Show Loader
                    $("#loaderGif").addClass('loaderGif');
                },
                success: function (result) {
                    var vehiPictures = document.getElementById('vehiPictures');
                    vehiPictures.innerHTML = result.returnData;
                },
                 complete: function () {
                    //Hide Loader
                    var loaderGif = document.getElementById("loaderGif");
                    loaderGif.classList.remove("loaderGif");
                }
            });
        }); 
    });
</script>
<div class="container" style="width: auto;padding: 20px;margin: 0px">
    <center>
        <div class="row" style="background-color:white">
            <div class="col-sm-8 col-sm-offset-4" style="margin-top:10px">
                <div class="row">
                    <div class="col-xs-12 registerForm" style="margin:0px;">
                        <center>
                            <h4 style="font-weight:bold">Suba las fotos</h4>
                        </center>
                    </div>
                </div>
            </div>
            
            <?php echo $vehiDropDown; ?>

            <div id="vehiPictures" class="col-md-10 col-md-offset-1" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                <?php echo $returnData; ?>

            </div>
        </div>
    </center>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.remote_app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views\remote\vehi_pictures.blade.php ENDPATH**/ ?>