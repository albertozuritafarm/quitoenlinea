<html>
    <head>
        <title>Tu Póliza en Línea</title>
        <style>
            /** 
                Set the margins of the page to 0, so the footer and the header
                can be of the full height and width !
             **/
            @page {
                margin: 0cm;
            }

            /** Define now the real margins of every page in the PDF **/
            body {
                font-family: 'Montserrat-SemiBold';
                text-align: justify;
                font-size:10px;
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
                line-height:1%;
            }
            ol{
                list-style-type: none;
                line-height:1%;
            }
            .bold{
                font-weight: bold;
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
                line-height: 2;
                font-size: 10px;
            }

            td, th {
                text-align: left;
                padding: 2px 2px 2px 10px;
            }

            tr:nth-child(even) {
                /*background-color: #dddddd;*/
            }
            .divData{
                background-color:#e5e5e5;
                padding:5px;
            }

            table, th, td {
                border: 1px solid  #183c6b;
                font-size: 10px;
                color: #183c6b;

            }
        </style>
    </head>
    <body>
        <!-- Define header and footer blocks before your content -->
        <header>
            <img src="{{public_path('images/aseguramiento.png')}}" alt="" style="width: 100%;height:100%;"/>
            <center><strong class="bold" style="color:#183c6b;font-size: 14px;">SEGURO DE VIDA INDIVIDUAL</strong></center>
            <center><strong class="bold" style="color:#183c6b;font-size: 14px;">SOLICITUD DE SEGURO</strong></center>
        </header>

        <footer>
            <img src="{{public_path('PDF_quotation/footer.png')}}" alt="" style="width: 100%;height:100%;"/>
        </footer>

        <!-- Wrap the content of your PDF inside a main tag -->
        <main>
            <!-- FIRST SET OF DATA -->
            <div style="width:100%;">
                <div>
                <div style="width:100%;background-color: white;color:#183c6b;font-size:12px;font-weight: bold;line-height: 20px; height:25px;">
                    <strong class="bold" style="color:#183c6b;font-size: 12px;">SOLICITANTE</strong> {{$customer->first_name}} {{$customer->second_name}} {{$customer->last_name}} {{$customer->second_last_name}}
                </div>
                    <table class="divData" >
                        <tr>
                             <th style="width:15%"><span class="bold">Nombre:</span></th>
                             <td colspan="3"> {{$customer->first_name}} {{$customer->second_name}} {{$customer->last_name}} {{$customer->second_last_name}}</td>
                        </tr>
                        <tr>
                            <th style="width:15%"><span class="bold">C.I./R.U.C.</span></th>
                             <td colspan="3">{{$customer->document}}</td>
                        </tr>
                        <tr>
                             <th style="width:15%"><span class="bold">Dirección:</span></th>
                             <td colspan="3">{{$customer->address}}</td>
                        </tr>
                        <tr>
                             <th style="width:15%"><span class="bold">Ciudad:</span></th>
                             <td>{{$city->name}}</td>
                             <th style="width:15%"><span class="bold">Teléfono:</span></th>
                             <td>{{$customer->phone}}</td>
                        </tr>
                        <tr>
                             <th style="width:15%"><span class="bold">Fecha de Nacimiento:</span></th>
                             <td>{{$customer->birthdate}}</td>
                             <th style="width:15%"><span class="bold">Lugar de Nacimiento:</span></th>
                             <td></td>
                        </tr>
                        <tr>
                             <th style="width:15%"><span class="bold">Peso (libras):</span></th>
                             <td>{{$app->weight}}</td>
                             <th style="width:15%"><span class="bold">Estatura (cm):</span></th>
                             <td>{{$app->stature}}</td>
                        </tr>
                        <tr>
                             <th style="width:15%"><span class="bold">Email:</span></th>
                             <td colspan="3">{{$customer->email}}</td>
                        </tr>
                        <tr>
                             <th style="width:15%"><span class="bold">A.P.S.:</span></th>
                             <td colspan="3">{{$agentSS->agentedes}}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <hr>
            <!--SET OF DATA -->
            <div  style="width:100%;">
                <div style="width:100%;background-color: white;color:#183c6b;font-size:12px;font-weight: bold;line-height: 20px; height:25px;">
                        <strong class="bold" style="color:#183c6b;font-size: 12px;">VIGENCIA DEL SEGURO A SOLICITAR</strong></center>
                </div>
                <div class="divData">
                    <table>
                        <tr>
                            <th style="width:15%"><span>Desde:</span></th>
                            <td style="width:35%">{{date('d-m-Y', strtotime($sale->begin_date))}}</td>
                            <th style="width:15%"><span>Hasta:</span></th>
                            <td style="width:35%">{{date('d-m-Y', strtotime($sale->end_date))}}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <hr>
             <!-- THIRD SET OF DATA -->
             <div style="width:100%;">
                <div style="width:100%;background-color: white;color:#183c6b;font-size:12px;font-weight: bold;line-height: 20px; height:25px;">
                        <strong class="bold" style="color:#183c6b;font-size: 12px;">LIMITES DE COBERTURA</strong></center>
                </div>
                <div class="divData">
                    <table>
                        <tr>
                            <th style="width:50%; text-align:center;">COBERTURAS</th>
                            <th style="width:50%; text-align:center;" colspan="2">MONTOS REQUERIDOS</th>
                        </tr>
                        <tr>
                            <td style="width:50%;" class="bold">Muerte por cualquier causa</td>
                            <td style="width:5%;">USD</td>
                            <td style="width:45%;">{{$muerte}}</td>
                        </tr>
                            <td style="width:50%;" class="bold">Enfermedades Graves</td>
                            <td style="width:5%;">USD</td>
                            <td style="width:45%;">{{$enfer}}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <hr>
            <!-- THIRD SET OF DATA -->
            <div style="width:100%;">
                <div style="width:100%;background-color: white;color:#183c6b;font-size:12px;font-weight: bold;line-height: 20px; height:25px;">
                    <strong class="bold" style="color:#183c6b;font-size: 12px;">BENEFICIARIOS</strong>
                </div>
                <div class="divData" >
                    <table>
                        <thead>
                            <tr>
                                <th style="width:50%; text-align:center;">NOMBRES</th>
                                <th style="width:20%; text-align:center;">PORCENTAJE</th>
                                <th style="width:30%; text-align:center;">PARENTESCO</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($beneficiarys as $bene)
                                <tr style="text-align: center">
                                    <td style="text-align: center">{{$bene->cusName}}</td>
                                    <td style="text-align: center">{{$bene->benPor}}</td>
                                    <td style="text-align: center">{{$bene->benRela}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <hr>
            <!--SET OF DATA -->
            <div style="width:100%; padding-bottom:20px;">
                <div style="width:100%;background-color: white;color:#183c6b;font-size:12px;font-weight: bold;line-height: 20px; height:25px;">
                    <strong class="bold" style="color:#183c6b;font-size: 12px;">ANTECEDENTES DE ASEGURABILIDAD</strong>
                </div>
                <div style="width:100%;background-color: white;color:#183c6b;font-size:12px;font-weight: bold;line-height: 20px; height:25px;">
                    <table class="divData" style="border-style: none;">
                        <tbody>
                            <tr style="border-style: hidden;">
                                <td style="width:80%; border-style: hidden;">En los casos de respuestas afirmativas, favor completar el detalle en la parte inferior del cuestionario</td>
                                <td style="width:10%; text-align:center; padding-left:0px; border-style: hidden;">SI</td>
                                <td style="width:10%; text-align:center; padding-left:0px; border-style: hidden;">NO</td>
                            </tr>
                            <tr style="border: 1px solid black;">
                                <td style="width:80%;">1.	¿Alguna vez ha sido cancelada, negada o recargada su solicitud de seguro?</td>
                                <td style="width:10%;">@if($answers->insuranceRecord1 == 'yes') X @endif</td>
                                <td style="width:10%;">@if($answers->insuranceRecord1 == 'no') X @endif</td>
                            </tr>
                            <tr>
                                <td style="width:80%;">2.	¿Tiene seguros de vida en otra compañía, en caso de ser afirmativa su respuesta especificar nombre y monto del seguro? </td>
                                <td style="width:10%;">@if($answers->insuranceRecord2 == 'yes') X @endif</td>
                                <td style="width:10%;">@if($answers->insuranceRecord2 == 'no') X @endif</td>
                            </tr>
                            <tr>
                                <td style="width:80%;">3.	¿Ha participado o piensa participar en actividades de deportes riesgosos tales como: boxeo, inmersión submarina, montañismo, alas delta, paracaidismo; carreras de caballos, automóviles, motocicleta, lanchas u otros?</td>
                                <td style="width:10%;">@if($answers->insuranceRecord3 == 'yes') X @endif</td>
                                <td style="width:10%;">@if($answers->insuranceRecord3 == 'no') X @endif</td>
                            </tr>
                            <tr>
                                <td style="width:80%;">4.	¿Consume bebidas alcohólicas? En caso afirmativo indique cantidad y frecuencia</td>
                                <td style="width:10%;">@if($answers->insuranceRecord4 == 'yes') X @endif</td>
                                <td style="width:10%;">@if($answers->insuranceRecord4 == 'no') X @endif</td>
                            </tr>
                            <tr>
                                <td style="width:80%;">5.	¿Ha consumido cualquier derivado de tabaco en los últimos 12 meses? En caso afirmativo indique tipo, cantidad y frecuencia</td>
                                <td style="width:10%;">@if($answers->insuranceRecord5 == 'yes') X @endif</td>
                                <td style="width:10%;">@if($answers->insuranceRecord5 == 'no') X @endif</td>
                            </tr>
                            <tr>
                                <td style="width:80%;">6.	¿Durante los últimos diez años ha consumido cocaína, marihuana, meta-anfetaminas, barbitúricos o cualquier otra sustancia controlada?</td>
                                <td style="width:10%;">@if($answers->insuranceRecord6 == 'yes') X @endif</td>
                                <td style="width:10%;">@if($answers->insuranceRecord6 == 'no') X @endif</td>
                            </tr>
                            <tr>
                                <td style="width:80%;">7.	¿Alguna vez ha recibido beneficios por incapacidad?</td>
                                <td style="width:10%;">@if($answers->insuranceRecord7 == 'yes') X @endif</td>
                                <td style="width:10%;">@if($answers->insuranceRecord7 == 'no') X @endif</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <hr>
            <!--SET OF DATA -->
            <hr>
            <hr>
            <hr>
            <div style="width:100%;">
                <div>
                <div style="width:100%;background-color: white;color:#183c6b;font-size:12px;font-weight: bold;line-height: 20px; height:25px;">
                    <strong  style="color:#183c6b;font-size: 12px;">Detalle de las preguntas</strong>
                </div>
                    <table class="divData">
                        <tr>
                            <th style="width:10%;">Número de la pregunta</th>
                            <th style="width:90%; text-align:center; padding-left:0px;">Detalle</th>
                        </tr>
                        @if($answers->insuranceRecord1 == 'yes')
                            <tr>
                                <td style="width:10%;">1</td>
                                <td style="width:90%;">{{$answers->insuranceRecord1_detail}}</td>
                            </tr>
                        @endif
                        @if($answers->insuranceRecord2 == 'yes')
                            <tr>
                                <td style="width:10%;">2</td>
                                <td style="width:90%;">{{$answers->insuranceRecord2_detail}}</td>
                            </tr>
                        @endif
                        @if($answers->insuranceRecord3 == 'yes')
                            <tr>
                                <td style="width:10%;">3</td>
                                <td style="width:90%;">{{$answers->insuranceRecord3_detail}}</td>
                            </tr>
                        @endif
                        @if($answers->insuranceRecord4 == 'yes')
                            <tr>
                                <td style="width:10%;">4</td>
                                <td style="width:90%;">{{$answers->insuranceRecord4_detail}}</td>
                            </tr>
                        @endif
                        @if($answers->insuranceRecord5 == 'yes')
                            <tr>
                                <td style="width:10%;">5</td>
                                <td style="width:90%;">{{$answers->insuranceRecord5_detail}}</td>
                            </tr>
                        @endif
                        @if($answers->insuranceRecord6 == 'yes')
                            <tr>
                                <td style="width:10%;">6</td>
                                <td style="width:90%;">{{$answers->insuranceRecord6_detail}}</td>
                            </tr>
                        @endif
                        @if($answers->insuranceRecord7 == 'yes')
                            <tr>
                                <td style="width:10%;">7</td>
                                <td style="width:90%;">{{$answers->insuranceRecord7_detail}}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
            <hr>
            <div style="width:100%;">
                <div>
                    <div style="width:100%;background-color: white;color:#183c6b;font-size:12px;font-weight: bold;line-height: 20px; height:25px;">
                        <strong class="bold" style="color:#183c6b;font-size: 12px;">HISTORIAL CLÍNICO</strong>
                    </div>
                    <table class="divData" style="border-style: none;">
                        <tr style="border-style: hidden;">
                            <td style="width:80%; border-style: hidden;">En los casos de respuestas afirmativas, favor completar el detalle en la parte inferior del cuestionario</td>
                            <td style="width:10%; text-align:center; padding-left:0px; border-style: hidden;">SI</td>
                            <td style="width:10%; text-align:center; padding-left:0px; border-style: hidden;">NO</td>
                        </tr>
                        <tr style="border: 1px solid black;">
                            <td style="width:80%;">1.	¿Tiene médico personal?  En caso de ser afirmativa su respuesta cita el nombre del médico</td>
                            <td style="width:10%;">@if($answers->medicalHistory1 == 'yes') X @endif</td>
                            <td style="width:10%;">@if($answers->medicalHistory1 == 'no') X @endif</td>
                        </tr>
                        <tr>
                            <td style="width:80%;">2.	¿Está actualmente tomando algún medicamento, en observación o tratamiento médico?</td>
                            <td style="width:10%;">@if($answers->medicalHistory2 == 'yes') X @endif</td>
                            <td style="width:10%;">@if($answers->medicalHistory2 == 'no') X @endif</td>
                        </tr>
                        <tr>
                            <td style="width:80%;">3.	¿Se ha realizado algún examen, consulta, chequeo u operación en los últimos tres (3) años?</td>
                            <td style="width:10%;">@if($answers->medicalHistory3 == 'yes') X @endif</td>
                            <td style="width:10%;">@if($answers->medicalHistory3 == 'no') X @endif</td>
                        </tr>
                        <tr>
                            <td style="width:80%;">4.	¿Ha sido hospitalizado o internado en alguna institución o sanatorio en los últimos tres (3) años?</td>
                            <td style="width:10%;">@if($answers->medicalHistory4 == 'yes') X @endif</td>
                            <td style="width:10%;">@if($answers->medicalHistory4 == 'no') X @endif</td>
                        </tr>
                        <tr>
                            <td style="width:80%;">5.	¿Tiene que ser hospitalizado o internado en alguna institución o sanatorio?</td>
                            <td style="width:10%;">@if($answers->medicalHistory5 == 'yes') X @endif</td>
                            <td style="width:10%;">@if($answers->medicalHistory5 == 'no') X @endif</td>
                        </tr>
                        <tr>
                            <td style="width:80%;">6.	¿Ha sufrido alguna enfermedad física o mental no mencionada anteriormente?</td>
                            <td style="width:10%;">@if($answers->medicalHistory6 == 'yes') X @endif</td>
                            <td style="width:10%;">@if($answers->medicalHistory6 == 'no') X @endif</td>
                        </tr>
                        <tr>
                            <td style="width:80%;">7.	¿Han padecido sus padres o hermanos diabetes, cáncer hipertensión arterial, enfermedad cardiaca, renal y/o mental?</td>
                            <td style="width:10%;">@if($answers->medicalHistory7 == 'yes') X @endif</td>
                            <td style="width:10%;">@if($answers->medicalHistory7 == 'no') X @endif</td>
                        </tr>
                        <tr>
                            <td style="width:80%;">8.	Solo para mujeres, ¿Está embarazada? Indique cuantas semanas o meses</td>
                            <td style="width:10%;">@if($answers->medicalHistory8 == 'yes') X @endif</td>
                            <td style="width:10%;">@if($answers->medicalHistory8 == 'no') X @endif</td>
                        </tr>
                    </table>
                </div>
            </div>
            <hr>
            <div class="divData" style="width:100%;">
                <div>
                    <table>
                        <tr>
                             <th style="width:10%; text-align:center;"><span class="bold">Número de la pregunta</span></th>
                             <th style="width:25%; text-align:center;"><span class="bold">Diagnóstico y tratamiento</span></th>
                             <th style="width:15%; text-align:center;"><span class="bold">Fecha de tratamiento Mes/Año</span></th>
                             <th style="width:25%; text-align:center;"><span class="bold">Duración:</span></th>    
                             <th style="width:25%; text-align:center;"><span class="bold">Nombre del Médico  - Clínica – Hospital</span></th>                        
                        </tr>
                        @if($answers->medicalHistory1 == 'yes')
                            <tr>
                                <td>1</td>
                                <td>{{$answers->diagnosis1}}</td>
                                <td>{{date('d-m-Y', strtotime($answers->treatmentDate1))}}</td>
                                <td>{{$answers->duration1}}</td>
                                <td>{{$answers->hospital1}}</td>
                            </tr>
                        @endif
                        @if($answers->medicalHistory2 == 'yes')
                            <tr>
                                <td>2</td>
                                <td>{{$answers->diagnosis2}}</td>
                                <td>{{date('d-m-Y', strtotime($answers->treatmentDate2))}}</td>
                                <td>{{$answers->duration2}}</td>
                                <td>{{$answers->hospital2}}</td>
                            </tr>
                        @endif
                        @if($answers->medicalHistory3 == 'yes')
                            <tr>
                                <td>3</td>
                                <td>{{$answers->diagnosis3}}</td>
                                <td>{{date('d-m-Y', strtotime($answers->treatmentDate3))}}</td>
                                <td>{{$answers->duration3}}</td>
                                <td>{{$answers->hospital3}}</td>
                            </tr>
                        @endif
                        @if($answers->medicalHistory4 == 'yes')
                            <tr>
                                <td>4</td>
                                <td>{{$answers->diagnosis4}}</td>
                                <td>{{date('d-m-Y', strtotime($answers->treatmentDate4))}}</td>
                                <td>{{$answers->duration4}}</td>
                                <td>{{$answers->hospital4}}</td>
                            </tr>
                        @endif
                        @if($answers->medicalHistory5 == 'yes')
                            <tr>
                                <td>5</td>
                                <td>{{$answers->diagnosis5}}</td>
                                <td>{{date('d-m-Y', strtotime($answers->treatmentDate5))}}</td>
                                <td>{{$answers->duration5}}</td>
                                <td>{{$answers->hospital5}}</td>
                            </tr>
                        @endif
                        @if($answers->medicalHistory6 == 'yes')
                            <tr>
                                <td>6</td>
                                <td>{{$answers->diagnosis6}}</td>
                                <td>{{date('d-m-Y', strtotime($answers->treatmentDate6))}}</td>
                                <td>{{$answers->duration6}}</td>
                                <td>{{$answers->hospital6}}</td>
                            </tr>
                        @endif
                        @if($answers->medicalHistory7 == 'yes')
                            <tr>
                                <td>7</td>
                                <td>{{$answers->diagnosis7}}</td>
                                <td>{{date('d-m-Y', strtotime($answers->treatmentDate7))}}</td>
                                <td>{{$answers->duration7}}</td>
                                <td>{{$answers->hospital7}}</td>
                            </tr>
                        @endif
                        @if($answers->medicalHistory8 == 'yes')
                            <tr>
                                <td>8</td>
                                <td>{{$answers->diagnosis8}}</td>
                                <td>{{date('d-m-Y', strtotime($answers->treatmentDate8))}}</td>
                                <td>{{$answers->duration8}}</td>
                                <td>{{$answers->hospital8}}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
            <hr>
            <div style="width:100%;">
                <div style="color:#183c6b;font-size:12px;">
                <p>Autorizo a cualquier médico, hospital, clínica o cualquier otros establecimiento de servicios médico o relacionados o compañía de seguros, que posea datos referentes al diagnóstico, tratamiento o prognosis de alguna enfermedad y/o tratamiento físico o mental o que posea información que no sea médica sobre mi persona que suministre a SEGUROS SUCRE S.A., o a su representación legal, toda la información que le sea solicitada. Autorizo a SEGUROS SUCRE S.A. a que realice todo tipo de examen médico incluido sin limitarse todos aquellos que sirvan para diagnóstico de enfermedades de notificación obligatoria de acuerdo a la normativa vigente de salud, Esta autorización será válida por dos años y medio a partir de la fecha que se indica a continuación.</p>
                <p>Declaro bajo juramento que toda la información contenida en este formulario es de verídica y absoluta responsabilidad de quien lo suscribe. Autorizo a SEGUROS SUCRE S.A. a verificar la información de este formulario. Declaro bajo juramento que los fondos para el pago de primas, gastos e impuestos en razón o consecuencia de la emisión de pólizas contratadas con SEGUROS SUCRE S.A. tienen origen lícito. Eximo a SEGUROS SUCRE S.A. de toda responsabilidad, inclusive frente a terceros si esta declaración fuese falsa o errónea.</p>
                    <p>Lugar y Fecha: Quito, {{date('d-m-Y', strtotime($sale->begin_date))}}</p>
                </div>
            </div>
            <hr>
            <div style="width:100%;">
                <div style="text-align:center;">
                    <table style="width:30%; border-style: hidden; margin: 0 auto;">
                        <tr>
                            <td>FIRMADO ELECTRONICAMANTE</td>              
                        </tr>
                        <tr>
                            <td style="text-align:center;">Firma del Solicitante</td>
                        </tr>
                    </table>
                </div>
            </div>
            <hr>
            <div style="width:100%;">
                <div style="color:#183c6b;font-size:12px;">
                    <p>El Asegurado podrá solicitar a la Superintendencia de Compañías, Valores y Seguros la verificación de este texto.</p>
                    <p>Nota: La Superintendencia de Compañías, Valores y Seguros para efectos de control asignó a la presente solicitud el número de registro 42629, el 14 de Octubre de 2016.</p>
                </div>
            </div>
            <hr>
           <hr>
        </main>
    </body>
</html>