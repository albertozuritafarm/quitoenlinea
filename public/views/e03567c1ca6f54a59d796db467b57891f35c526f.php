

<?php $__env->startSection('content'); ?>
<tr class="ui-droppable">
    <td style="padding: 0px">
        <table border="0" cellpadding="0" cellspacing="0" class="container" id="3" style="width:700px">
            <tbody>
                <tr>
                    <td class="container-column ui-droppable" style="width:100%;max-width:600px;vertical-align:top">
                        <table border="0" cellpadding="0" cellspacing="0" class="component title-component" i18n="MODULES-TXT_HEAD_TITLE" id="4" style="width:100%">
                            <tbody style="display: block; border: 0px none rgb(96, 96, 96); border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px; background-color: rgba(0, 0, 0, 0);">
                                <tr bgcolor="Transparent" style="display: inherit;">
                                    <td style="padding: 5px 0px 5px 14px; margin:60px 0px 20px 0px; font-size: 1.5em; font-weight: bold; display: inherit; color: rgb(96, 96, 96); text-align: center;"> <img src="<?php echo e($message->embed(public_path('images/Email/email_header.png'))); ?>" width="100%" alt="Girl in a jacket"> </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </td>
</tr>
<tr class="ui-droppable">
    <td style="padding: 0px">
        <table border="0" cellpadding="0" cellspacing="0" class="container" id="6" style="width:600px">
            <tbody>
                <tr>
                    <td class="container-column ui-droppable" style="width:100%;max-width:600px;vertical-align:top">
                        <table border="0" cellpadding="0" cellspacing="0" class="component text-component" i18n="MODULES-TXT_BEGIN_AND_CUSTUMIZE" id="7" style="width:100%">
                            <tbody style="display: block; border: 0px none rgb(96, 96, 96); border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px; background-color: rgba(0, 0, 0, 0);">
                                <tr bgcolor="Transparent" style="display: inherit;">
                                    <td style="padding: 5px 0px 5px 14px; margin:60px 0px 20px 0px; font-size: 1.5em; font-weight: bold; display: inherit; color: rgb(96, 96, 96); text-align: left;"> <img src=" <?php echo e($message->embed(public_path('images/Email/logo_vehiculo.png'))); ?>" width="20%" alt="Girl in a jacket"> </td>
                                    <td style="padding: 5px 0px 5px 14px; margin:60px 0px 20px 0px; font-size: 1.5em; font-weight: bold; display: inherit; color: rgb(96, 96, 96); text-align: left;"> <img src=" " width="20%" alt="Girl in a jacket"> </td>
                                    
                                    <td style="padding: 5px 10px 80px 14px; display: inherit; color: rgb(96, 96, 96); text-align: left;">
                                        <p style="margin: 0px;">Estimado <span style="font-weight: bold"><?php echo e($customer[0]->Cliente); ?></span></p>
                                        
                                        <p style="margin: 0px;">En el siguiente correo ponemos en tu conocimiento la cotización para tu póliza de <span style="font-weight: bold"><?php echo e($customer[0]->ramo); ?></span> generada el día <span style="font-weight: bold"><?php echo e($customer[0]->date); ?></span> con Seguros Sucre</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="col-md-8 col-md-offset-2" style="text-align:center;">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="background-color:#b3b0b0">Producto</th>
                        <th style="background-color:#b3b0b0">Prima</th>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $vehiTable; ?>

                        </td>
                    </tr>
                </thead>
                <tbody>
                    <td>
                        <?php echo $taxTable; ?>

                    </td>
                </tbody>
            </table>
        </div>
        <div class="col-md-12" style="text-align:center;">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="background-color:#b3b0b0">S. de Bancos (3.5%)</th>
                        <th style="background-color:#b3b0b0">S. Campesino (0.5%)</th>
                        <th style="background-color:#b3b0b0">D. de Emisión</th>
                        <th style="background-color:#b3b0b0">Subtotal 12%</th>
                        <th style="background-color:#b3b0b0">Subtotal 0%</th>
                        <th style="background-color:#b3b0b0">Iva</th>
                        <th style="background-color:#b3b0b0">Total</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
        <br><hr>
    </td>
</tr>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.email', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\magnussucre\resources\views\emails\quotation.blade.php ENDPATH**/ ?>