<html>
    <head>
        <title>Magnus</title>
        <style>
            /** 
                Set the margins of the page to 0, so the footer and the header
                can be of the full height and width !
             **/
            @import url('https://fonts.googleapis.com/css?family=Montserrat:600,700&display=swap');
            @page {
                margin: 0cm;
            }

            /** Define now the real margins of every page in the PDF **/
            body {
                font-family:  'Montserrat-SemiBold', sans-serif;
                text-align: justify;
                font-size:12px;
                margin-top: 150px;
                margin-left: 1cm;
                margin-right: 1cm;
                margin-bottom: 2cm;
            }

            /** Define the header rules **/
            header {
                position: fixed;
                top: 0cm;
                left: 0cm;
                right: 0cm;
                height:2cm;
                text-align: center;
            }

            /** Define the footer rules **/
            footer {
                position: fixed; 
                bottom: 0cm; 
                left: 0cm; 
                right: 0cm;
                height:2cm;
                text-align: center;
            }
            ul{
                list-style-type: none;
                line-height:5%;
            }
            ol{
                list-style-type: none;
                line-height:5%;
            }
            li{
                list-style-type: none;
                margin-top: 10px;
                line-height:5%;
            }
            .bold{
                font-weight: bold;
            }

            .title{
                font-size: 13px;
            }

            hr {
                clear: both;
                visibility: hidden;
            }
            .page-break {
                page-break-after: always;
            }
            table {
                font-family:  'Montserrat-SemiBold', sans-serif;
                border-collapse: collapse;
                width: 100%;
                line-height: 2;
            }

            td, th {
                text-align: left;
            }

            tr:nth-child(even) {
                /*background-color: #dddddd;*/
            }
            .divData{
                background-color:#e5e5e5;
            }
        </style>
    </head>
    <body>
        <!-- Define header and footer blocks before your content -->
        <header>
            <img src="{{public_path('PDF_quotation/header.png')}}" alt="" style="width: 100%;height:100%;"/>
            <center><strong class="bold" style="color:#183c6b;font-size: 14px;">N° COT-{{$customer[0]->ramo}}-{{$customer[0]->year}}-{{$id}}</strong></center>
        </header>

        <footer>
            <img src="{{public_path('PDF_quotation/footer.png')}}" alt="" style="width: 100%;height:100%;"/>
        </footer>

        <!-- Wrap the content of your PDF inside a main tag -->
        <main>
            <!-- FIRST SET OF DATA -->
            <div class="" style="width:100%;">
                <div style="padding-left: 10px;">
                    <table>
                        <tr>
                            <td style="width:25%"></td>
                            <td style="width:25%"></td>
                            <th style="width:25%" class="divData"><span class="bold" style="padding-left:5px">Fecha Cotización:</span></th>
                            <td style="width:25%" class="divData">{{$customer[0]->date}}</td>
                        </tr>
                        <tr>
                            <th style="width:50%;background-color:#003B71; text-align:left; border-top: 0px; border-bottom: 0px; border-left: 2px solid #CF3339; color:white; padding:0 5px 0 15px"><span class="bold title">DATOS DEL ASEGURADO</span></th>
                            <td style="width:20%;background-color:#003B71;"></td>
                            <td style="width:20%;background-color:#003B71;"></td>
                            <td style="width:10%;background-color:#003B71; border-right: 1px solid #2D2A26"></td>
                        </tr>
                        <tr class="divData borderLeftRight">
                            <th style="width:20%; border-left: 1px solid #2D2A26; padding-left: 5px">Tipo de Documento</th>
                            <td style="width:30%">{{$customer[0]->typeDocDes}}</td>
                            <th style="width:25%">Numero Doc</th>
                            <td style="width:25%; border-right: 1px solid #2D2A26">{{$customer[0]->cusDocument}}</td>
                        </tr>
                        <tr class='borderLeftRight'>
                            <th style="width:20%; border-left: 1px solid #2D2A26; padding-left: 5px">Nombres</th>
                            <td style="width:30%">{{$customer[0]->names}}</td>
                            <th style="width:25%">Apellidos</th>
                            <td style="width:25%; border-right: 1px solid #2D2A26">{{$customer[0]->lastNames}}</td>
                        </tr>
                        <tr class="divData borderLeftRight">
                            <th style="width:20%; border-left: 1px solid #2D2A26; padding-left: 5px">Fch. Nacimiento</th>
                            <td style="width:30%">{{$customer[0]->cusBirthdate}}</td>
                            <th style="width:25%">Ciudad</th>
                            <td style="width:25%; border-right: 1px solid #2D2A26">{{$customer[0]->city}}</td>
                        </tr>
                        <tr class='borderLeftRight'>
                            <th style="width:20%; border-left: 1px solid #2D2A26; padding-left: 5px">Tlf. Convencional</th>
                            <td style="width:30%">{{$customer[0]->phone}}</td>
                            <th style="width:25%">Tlf. Celular</th>
                            <td style="width:25%; border-right: 1px solid #2D2A26">{{$customer[0]->mobile}}</td>
                        </tr>
                        <tr class="divData borderLeftRight">
                            <th style="width:20%; border-left: 1px solid #2D2A26; padding-left: 5px">Correo Electronico</th>
                            <td style="width:30%">{{$customer[0]->email}}</td>
                            <td style="width:25%"></td>
                            <td style="width:25%; border-right: 1px solid #2D2A26"></td>
                        </tr>
                        <tr class='borderLeftRight borderBottom'>
                            <th style="width:20%; border-left: 1px solid #2D2A26; border-bottom: 1px solid #2D2A26; padding-left: 5px">Tiene Broker</th>
                            <td style="width:30%; border-bottom: 1px solid #2D2A26">Si</td>
                            <td style="width:25%; border-bottom: 1px solid #2D2A26"></td>
                            <td style="width:25%; border-right: 1px solid #2D2A26; border-bottom: 1px solid #2D2A26"></td>
                        </tr>
                    </table>
                    <hr>
                </div>
            </div>
            <!-- THIRD SET OF DATA -->
            <div class="" style="width:100%;">
                <div style="padding-left: 10px;">
                    <table style="border: 1px solid #2D2A26">
                        <tr>
                            <th style="width:50%;background-color:#003B71; text-align:left; border-top: 0px; border-bottom: 0px; border-left: 2px solid #CF3339; color:white; padding:0 0px 0 5px"><span class="bold title">VALOR DE PRIMA</span></th>
                            <td style="width:20%;background-color:#003B71;"></td>
                            <td style="width:20%;background-color:#003B71;"></td>
                            <td style="width:10%;background-color:#003B71; border-right: 1px solid #2D2A26"></td>
                        </tr>
                        <tr class="divData">
                            <th style="width:25%;padding-left:5px">Prima Neta</th>
                            <td style="width:25%">{{$sales->prima_total}}</td>
                            <th style="width:25%">Contribucion SCVS</th>
                            <td style="width:25%">{{$sales->super_bancos}}</td>
                        </tr>
                        <tr>
                            <th style="width:25%;padding-left:5px">Seguro Campesino</th>
                            <td style="width:25%">{{$sales->seguro_campesino}}</td>
                            <th style="width:25%">Derecho de Emision</th>
                            <td style="width:25%">{{$sales->derecho_emision}}</td>
                        </tr>
                        <tr class="divData">
                            <th style="width:25%;padding-left:5px">Iva 12%</th>
                            <td style="width:25%">{{$sales->tax}}</td>
                            <th style="width:25%">Prima Total</th>
                            <td style="width:25%">{{$sales->total}}</td>
                        </tr>
                    </table>
                    <hr>
                </div>
            </div>

            <div>
                <hr>
                    <div style="width:100%;background-color: white;color:#183c6b;font-size:14px;font-weight: bold;line-height: 20px; height:25px;">
                        <span><center><h1>{{$customer[0]->proName}}</h1></center></span>
                    </div>
                <hr>
            </div>
            <!-- COBERTURAS -->
            <div>
                    <div style="padding-left: 10px;">
                        
                            {!! getCoverageDetails(1, $customer[0]->idProduct) !!}
                        
                        <hr>
                    </div>
                <hr>
                <hr>
                <hr>
            </div>
            <!-- AMPAROS ADICIONALES --> 
            <!-- <div>
                <div style="width:100%;background-color: #183c6b;color:white;font-size:14px;font-weight: bold;line-height: 20px; height:25px;">
                    <span><center>AMPAROS ADICIONALES</center></span>
                </div>
                <hr>
                <table>
                    <tr>
                        <th style="width:40%">RESPONSABILIDAD CIVIL</th>
                        <td style="width:60%">USD 40,000.00 LUC (límite único combinado)<td>
                    </tr>
                    <tr>
                        <th style="width:40%">MUERTE ACCIDENTAL E INVALIDEZ TOTAL Y PERMANENTE</th>
                        <td style="width:60%">USD 8,000.00 por ocupante (en exceso del SPPAT) Cubre al número de ocupantes detallados en la matrícula del vehículo, incluído el conductor (máximo 5 ocupantes, no aplica furgonetas)<td>
                    </tr>
                    <tr>
                        <th style="width:40%">GASTOS MEDICOS POR ACCIDENTE</th>
                        <td style="width:60%">USD 5,000.00 por ocupante (en exceso del SPPAT<td>
                    </tr>
                    <tr>
                        <th style="width:40%">CANASTA FAMILIAR POR MUERTE DEL TITULAR</th>
                        <td style="width:60%">USD 2,000.00 de indemnización por muerte del titular de la póliza<td>
                    </tr>  
                </table>
            </div> -->
            <!-- CONDICIONES ESPECIALES -->
            <!-- <div>
                <hr>
                <div style="width:100%;background-color: #183c6b;color:white;font-size:14px;font-weight: bold;line-height: 20px; height:25px;">
                    <span><center>CONDICIONES ESPECIALES</center></span>
                </div>
                <hr>
                <div>
                    No depreciación en pérdidas parciales<br>
                    Se cubre Air Bag al 100% solamente por un siniestro cubierto por la póliza<br>
                    Descuento del 10% en talleres en convenio con Seguros Sucre para mantenimiento y daños por avería<br>
                    <hr>
                </div>
            </div> -->
            <!-- DISPOSITIVO -->
            <!-- <div>
                <span class="bold">DISPOSITIVO</span><br>
                <hr>
                <div>
                    Todo vehículo con valor asegurado igual o superior a USD 25,000.00, deberá mantener instalado y activo un dispositivo de rastreo satelital debidamente calificado durante la vigencia de la póliza, caso contrario se aplicará el deducible estipulado en las condiciones particulares.<br>
                    En caso de que el vehículo asegurado sufra algún choque, volcamiento o realice cualquier modificación o instalación de equipos adicionales que comprometan componentes eléctricos del vehículo, se deberá comunicar de manera inmediata al proveedor del sistema de seguridad con el objeto de que se revise el correcto funcionamiento del dispositivo.<br>
                    Además el asegurado se encuentra obligado a realizar por cada período de renovación una revisión del dispositivo en las instalaciones del proveedor.<br>
                    La certificación de operatividad del deducible será documento ineludible en el trámite del siniestro.<br>
                </div>
            </div> -->
            <!-- AUTORIZACIÓN EXPRESS -->
            <!-- <div>
                <hr>
                <span class="bold">AUTORIZACION EXPRESS</span><br>
                <hr>
                <div>
                    Siempre que el siniestro no supere USD. 1.500,00 y el vehículo ingrese a los talleres en convenio de la Compañía, se autorizará reparación inmediata.<br>
                    En caso de siniestro, que afecte uno de los amparos de pérdida parcial, en el que fuere necesaria la reposición de piezas que no existieren en el
                    mercado, la Compañía no será responsable por los perjuicios que ocasionare al Asegurado, el tiempo que demande la importación de dichas
                    piezas; y si tales piezas no existieren tampoco en la fábrica, la Compañía cumplirá su obligación pagando el importe de ellas, en efectivo, al
                    Asegurado, en un plazo de 45 días, de acuerdo con el precio promedio de venta de los importadores que los hubieren tenido disponibles durante el
                    último semestre, más el costo ajustado de su instalación basado en un presupuesto formulado por un taller de reconocida solvencia. <br>
                    Los gastos adicionales que implique la aceleración del proceso de importación, tampoco serán responsabilidad de la Compañía.<br>
                </div>
            </div> -->
            <!-- SUCRE ASISTENCIA -->
            <!-- <div>
                <hr>
                <span class="bold">SUCRE ASISTENCIA</span><br>
                <hr>
                <div>
                    Remolque o traslado del vehículo (a nivel nacional) por avería y accidentes USD 400,00<br>
                    Segundo Traslado hasta USD.100,00<br>
                    Auxilio mecánico en averías como llanta baja, llaves al interior, batería, gasolina.<br>
                    Conductor Elegido<br>
                    Transmisión de mensajes urgentes (ilimitado).<br>
                    Traslado de ambulancia en caso de accidentes.<br>
                    Orientación jurídica en el sitio en caso de heridos.<br>
                    Orientación jurídica telefónica.<br>
                    Asistencia Legal In Situ en Quito, Guayaquil, Cuenca, Ambato, Manta, Portoviejo<br>
                    Servicio de matriculación<br>
                    Desplazamiento de los ocupantes por inmovilización del vehículo asegurado<br>
                    Servicio Exequial<br>
                </div>
            </div> -->
            <!-- AUTO SUSTITUTO -->
            <!-- <div>
                <hr>
                <span class="bold">AUTO SUSTITUTO</span><br>
                <hr>
                <div>
                    Valor mínimo del siniestro para acceder al beneficio: $ 1.000.00 como costo ajustado y aceptado para la reparación.
                    Numero de días del beneficio:<br>
                    Siniestros parciales 12 días por evento<br>
                    Perdidas totales 20 días<br>
                    Tipo de vehículo a proveerse: categoría económica<br>
                    Voucher en garantía<br>
                </div>
            </div> -->
            <!-- SERVICIO MI AUTO MATRICULADO -->
            <!-- <div>
                <hr>
                <span class="bold">SERVICIO MI AUTO MATRICULADO</span><br>
                <hr>
                <div>
                    Servicio que incluye la gestión de los trámites de revisión técnica y matriculación vehicular del vehículo de propiedad del asegurado (1evento al
                    año)<br>
                    Servicios para vehículos que tengan un seguro de VH vigente.<br>
                    Servicios para trámites de renovación de matrícula.<br>
                    Los pagos necesarios deberán ser realizados por el Asegurado.<br>
                    El Asegurado deberá entregar los documentos necesarios.<br>
                    El Asegurado será responsable por el estado mecánico del vehículo que permita la aprobación por parte de la autoridad.<br>
                </div>
            </div> -->
            <!-- SERVICIO LEGAL -->
            <!-- <div>
                <hr>
                <span class="bold">SERVICIO LEGAL EN IMPUGNACIONES DE CONTRAVENCIONES DE TRÁNSITO</span><br>
                <hr>
                <div>
                    Servicio que cubre los honorarios profesionales de un abogado para el análisis e impugnación por contravenciones de tránsito en territorio
                    ecuatoriano.<br>
                    Condiciones:<br>
                    - Infracciones imputadas al vehículo propiedad del Asegurado.<br>
                    - Asegurado deberá notificar al servicio dentro de las 24 horas siguientes a la notificación (en la Actualidad la ANT notifica al titular al correo
                    electrónico
                    o celular del propietario)<br>
                    - Servicio Incluye:<br>
                    a.- Análisis de la contravención<br>
                    b.- Impugnación<br>
                    c.- Acompañamiento a la Audiencia respectiva<br>
                    (Límite: 1 evento al año)
                </div>
            </div> -->
            <!-- CLAUSULAS -->
            <!-- <hr>
            <div style="width:100%;background-color: #183c6b;color:white;font-size:14px;font-weight: bold;line-height: 20px; height:25px;">
                <span><center>CLAUSULAS</center></span>
            </div>
            <hr>
            <div>
                Cláusula de Adhesión <br>
                Cláusula de pago de primas 30 días.<br>
                Cláusula de cancelación de póliza 30 días<br>
                Cláusula de notificación del siniestro 10 días<br>
                Cláusula de Restitución automática de valor asegurado<br>
                Cláusula para radios, equipos de música y otros accesorios hasta el 10% de la suma asegurada del vehículo<br>
            </div> -->
            <!-- DEDUCIBLES -->
           <!--  <hr>
            <div style="width:100%;background-color: #183c6b;color:white;font-size:14px;font-weight: bold;line-height: 20px; height:25px;">
                <span><center>DEDUCIBLES</center></span>
            </div>
            <hr>
            <div>
                Pérdidas parciales 10.00 % del valor del siniestro, mínimo 1.00% del valor asegurado, no menor a USD 150.00 (siempre se aplicará el que sea mayor)<br>
                Pérdida total por daños: 10.00% del valor asegurado.<br>
                Pérdida total por robo: 0.00% del valor asegurado (con dispositivo de rastreo satelital operativo y vigente al momento del siniestro).<br>
                Pérdida total por robo (Sin dispositivo vehículos menores a USD. 25.000): 10.00% del valor asegurado<br>
                Pérdida total por robo (Sin dispositivo vehículos mayores a USD. 25.001): 20.00% del valor asegurado<br>
                Parabrisas, vidrios y cristales: USD 75.00; no incluye láminas de seguridad<br>
            </div> -->
            <!-- OBSERVACIONES -->
<!--            <hr>
            <div style="width:100%;background-color: #183c6b;color:white;font-size:14px;font-weight: bold;line-height: 20px; height:25px;">
                <span><center>OBSERVACIONES</center></span>
            </div>
            <hr>
            <div>
                La presente cotización tiene una validez de 30 días.<br>
                Quedamos a sus gratas órdenes para cualquier inquietud relacionada a la presente propuesta.<br>
                Atentamente,<br>
                <hr><hr><hr>
                <span class="bold titleDiv">SEGUROS SUCRE, S.A.</span>
            </div>-->
        </main>
    </body>
</html>
