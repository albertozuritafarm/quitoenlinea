<!DOCTYPE html>
<html>
    <body>
        <div style="margin-left:50px; width:80%">
            <div style="background-color: #FFF; border-style: solid; border-color:#5CB85C; border-width:2px; min-width:400px;min-height:240px; max-width:450px; max-height: 310px;">
                <div style="background-color: #5CB85C; border-width:0px; min-width:400px; min-height:30px; text-align:center;color:#FFF; padding-top:5px; height:30px">
                    CÓDIGO DE AUTORIZACIÓN
                </div>
                <br/>
                <div style="padding-left:10px;">
                    Estimado/a <?php echo e($name); ?>

                </div>
                <br/>
                <div style="padding-left:10px;">
                    Se está realizando un proceso de solicitud de cuenta bancaria. 
                    Si está de acuerdo, use el siguiente código para autorizar el proceso; de lo contrario, 
                    comuníquese de inmediato con su Institución Financiera.
                </div>
                <br/>
                <div style="padding-left:10px;">
                    Código de Validación: <?php echo e($code); ?>

                </div>
                <br/>
                <div style="padding-left:10px;">
                    Gracias.
                </div>
                <br/>
                <div style="background-color: #5CB85C; border-width:0px; min-width:400px; min-height:30px; text-align:center; color:#FFF; padding-top:5px; height:30px">
                    Mensaje generado automáticamente por Magnus Soft
                </div>
            </div>
        </div>
    </body>
</html><?php /**PATH C:\wamp64\www\magnussucre\resources\views\emails\newAccountCodeEmail.blade.php ENDPATH**/ ?>