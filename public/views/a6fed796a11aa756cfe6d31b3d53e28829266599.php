<html>
    <head>
        <title>Magnus</title>

        <style>
            /** 
                Set the margins of the page to 0, so the footer and the header
                can be of the full height and width !
             **/
            @page  {
                margin: 0cm 0cm;
            }
            @font-face {
                font-family: 'Montserrat-SemiBold';
                src: url(C:/wamp64/www/MagnusHit/storage/fonts/Montserrat-SemiBold.ttf) format('truetype');
                font-weight: lighter;
                font-style: normal;
            }
            @font-face {
                font-family: 'Montserrat-Bold';
                src: url(C:/wamp64/www/MagnusHit/storage/fonts/Montserrat-ExtraBold.ttf) format('truetype');
                font-weight: normal;
                font-style: normal;
            }

            /** Define now the real margins of every page in the PDF **/
            body {
                font-family: 'Montserrat-SemiBold';
                text-align: justify;
                font-size:13px;
                margin-top: 150px;
                margin-left: 2cm;
                margin-right: 2cm;
                margin-bottom: 100px;
                line-height: 1.5;
            }

            /** Define the header rules **/
            header {
                position: fixed;
                top: 0cm;
                left: 0cm;
                right: 0cm;
                height: 2cm;
                text-align: center;
            }

            /** Define the footer rules **/
            footer {
                position: fixed; 
                bottom: 0cm; 
                left: 0cm; 
                right: 0cm;
                height: 2cm;
                text-align: center;
            }
            ul{
                list-style-type: none;
                line-height:1%;
            }
            ol{
                list-style-type: none;
                line-height:1%;
            }
            .bold{
                font-family: Montserrat-Bold !important;
                /*font-weight: bold;*/
            }

            .title{
                font-size: 15px;
            }

            hr {
                clear: both;
                visibility: hidden;
            }
            .page-break {
                page-break-after: always;
            }
            table {
                font-family: arial, sans-serif;
                border-collapse: collapse;
                width: 100%;
            }

            td, th {
                border: 1px solid #dddddd;
                text-align: center;
                padding: 2px;
            }

            tr:nth-child(even) {
                background-color: #dddddd;
            }
        </style>
    </head>
    <body>
        <!-- Define header and footer blocks before your content -->
        <header>
            <img src="<?php echo e(public_path('images/logo_seguros_sucre.jpg')); ?>" alt="" style="width: 30%;height:90px;padding-top: 30px; margin-left: 55px; float:left;"/>
            <strong class="bold" style="float:right;padding-top:70px;padding-right: 70px;">COD: <?php echo e($id); ?> - <?php echo e($year); ?></strong><br>
        </header>

        <footer>
        </footer>

        <!-- Wrap the content of your PDF inside a main tag -->
        <main>
            <div class="col-md-10">
                <center>
                    <strong class="bold title">CERTIFICADO DE COBERTURA</strong><br>
                    <strong class="bold title"><?php echo e($proName); ?></strong><br>
                </center>
                <hr>
                <center>
                    <strong class="bold title">CONDICIONES DEL SEGURO</strong>
                </center>
                <hr>
                <strong class="bold title">OBJETO DEL SEGURO:</strong>
                <br><hr>
                <span>Se  pagará  por  parte  de  la  compañía  al  asegurado  en  caso  de  fallecimiento  accidental,  incapacidad  total  y permanente o gastos médicos, la suma asegurada detallada en cada plan contratado</span>
                <br><hr>
                <span> 
                    Nombre del Asegurado: <?php echo e($customer); ?>

                    <br>
                    Documento de Identidad: <?php echo e($cusDocument); ?>

                </span>
                <br><hr>
                <center>
                    <strong class="bold">DOCUMENTACIÓN MÍNIMA EN CASO DE SINIESTRO</strong>
                </center>
                <hr>
                <span>
                    Dentro del término legal, la Compañía pagará por conducto del Contratante al Asegurado o a los beneficiarios, odirectamente  a  estos,  la  indemnización  a  que  está  obligada  por  esta  póliza  y  sus  amparos  adicionales  si  loshubiere,  al  acreditar  la  ocurrencia  del  siniestro  y  la  cuantía  del  mismo;  para  el  efecto  podrá  utilizar  todos  losmedios  probatorios  admitidos  en  la  Ley  ecuatoriana,  y  en  especial  la  documentación  mínima  relacionada  en  elcuadro de documentos mínimos requeridos en caso de siniestro
                </span>
                <br><hr>
                <span>
                    -Notificación de aviso de siniestro
                </span>
                <br>
                <span>
                    -Copia de cédula del asegurado
                </span>
                <br><hr>
                <center>
                    <strong class="bold">DETALLE DEL PLAN ASEGURADO</strong>
                </center>
                <br>
                <table>
                    <tr>
                        <th>Nombre del Producto</th>
                        <th>Coberturas</th>
                        <th>Suma Asegurada</th>
                    </tr>
                    <tr>
                        <td rowspan="4"><?php echo e($proName); ?></td>
                        <td>Perdida Total por robo</td>
                        <td>Valor Comercial</td>
                    </tr>
                    <tr>
                        <td>perdida Parcial por robo</td>
                        <td>Valor Comercial</td>
                    </tr>
                    <tr>
                        
                        <td>Perdida Total por daño</td>
                        <td>Valor Comercial</td>
                    </tr>
                    <tr>
                        
                        <td>perdida Parcial por daño</td>
                        <td>Valor Comercial</td>
                    </tr>
                </table>
                <br>
                <center>
                    <strong class="bold">AUTORIZACIÓN DE DEBITO</strong>
                </center>
                <br>
                <span>
                    Autorizo a la Compañía de Seguros Sucre a debitar de mi cuenta 123456 el valor mensual declarado en el plan, yme  comprometo  a  mantener  los  saldos  respectivos  para  los  débitos,  en  caso  de  dos  intentos  mensualesseguidos entiendo que la presente póliza / certificado quedará anulado automáticamente.
                </span>
                <br><hr>
                <span>
                    El  Contratante  y/o  Asegurado  podrá  solicitar  a  la  Superintendencia  de  Compañías,  Valores  y  Seguros  laverificación de este texto. <br>Lugar y fecha: Quito, <?php echo e($date); ?>

                </span>
                <br><hr><hr>
                <center>
                    <span class="bold title" style="border-top: 1px solid black;">
                        Seguros Sucre
                    </span>
                </center>
            </div>
        </main>
    </body>
</html>
<?php /**PATH C:\wamp64\www\magnussucre\resources\views\sales\pdf_sucre.blade.php ENDPATH**/ ?>