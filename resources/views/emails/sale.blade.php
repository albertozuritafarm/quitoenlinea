<!DOCTYPE html>
<html>

    <body>
        <div style="margin-left:50px; width:80%">
            <p>¡Felicitaciones {{ $name }}! Ya formas parte de </p>

            <center>
                <img src="{{ $message->embed('C:/xampp/htdocs/MagnusHit/public/images/logo.png') }}" height="211" width="211">
            </center>

            <p>Bienvenid@ al primer programa de embellecimiento automotriz en Ecuador, que te brindará protección contra golpes y abolladuras.</p>

            <p>Tu pago fue exitoso. </p>

            <p>Se reflejará el cargo a continuación en el próximo extracto de tu tarjeta de crédito o débito.</p>

            <p>El cargo aparecerá en tu estado de cuenta con el nombre de Hit Solution.</p>

            <p>Descarga el adjunto de este correo para conocer los beneficios de este programa.</p>

            <p>¡Invita a un amigo para que conozca de este producto!.</p>

            <p><img src="{{ $message->embed('C:/xampp/htdocs/MagnusHit/public/images/fb_logo.png') }}">/HIT SOLUTION</p>

            
        </div>



</body>

</html>