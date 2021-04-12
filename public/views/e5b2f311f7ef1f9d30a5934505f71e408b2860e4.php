<html>
    <head>
        <title>Magnus</title>
        <style>
            /** 
                Set the margins of the page to 0, so the footer and the header
                can be of the full height and width !
             **/
            @page  {
                margin: 0cm;
            }

            /** Define now the real margins of every page in the PDF **/
            body {
                font-family: 'Arial';
                font-size: 8px;
                margin-top: 50px;
                margin-left: 1cm;
                margin-right: 1cm;
                margin-bottom: 21px;
            }

            /** Define the header rules **/
            header {
                position: fixed;
                top: 0cm;
                left: 0cm;
                right: 0cm;
                height:1cm;
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
                color:blue;
            }
            .page-break {
                page-break-after: always;
            }
            table {
                font-family: arial, sans-serif;
                border-collapse: collapse;
                width: 100%;
                line-height: 2;
            }

            td, th {
                text-align: left;
               /*border: 1px solid;*/
                padding:0px; 
                margin: 0px;
                font-size:10px;
            }

            tr:nth-child(even) {
                /*background-color: #dddddd;*/
            }
            .divData{
                /*background-color:#e5e5e5;*/
            }
            hr {
              border: 1px solid black;
            }
            .underline{
                text-decoration: underline;
                text-align: center;
            }
            .underlineCell{
                text-align: center;
                border-bottom: 1px solid #000;
            }
            #referenceTable td{
                border: 1px solid black;
                text-align: center;
            }
            #referenceTable th{
                border: 1px solid black;
                text-align: center;
            }
            .page-break {
                page-break-after: always;
            }
        </style>
    </head>
    <body>
        <!-- Define header and footer blocks before your content -->
        <header>
            <!--<img src="<?php echo e(public_path('PDF_quotation/header.png')); ?>" alt="" style="width: 100%;height:100%;"/>-->
            <!--<center><strong class="bold" style="color:#183c6b;font-size: 14px;">N° COT-</strong></center>-->
        </header>

        <footer>
            <!--<img src="<?php echo e(public_path('PDF_quotation/footer.png')); ?>" alt="" style="width: 100%;height:100%;"/>-->
        </footer>

        <!-- Wrap the content of your PDF inside a main tag -->
        <main>
            <!-- FIRST SET OF DATA -->
            <div class="col-md-4 col-md-offset-4">
                <img src="<?php echo e(public_path('images/logo_seguros_sucre.jpg')); ?>" alt="" style="width:25%;margin-left:35%;"/>
                <center>
                    <span style="font-size:14px; font-family: 'Arial';"><b>
                        
                            FORMULARIO DE VINCULACIÓN DE BENEFICIARIOS
                            (Persona Jurídica)</b>
                            La información proporcionada en este documento será de estricta confidencialidad
                        
                    </span>
                </center>
            </div>
            <hr>
            <div class="col-md-12" style="padding-top: -5px;">
               <span style="font-size:10px; font-family: 'Arial'; ">
               </span>
            </div>
            <div class="col-md-12" style="padding-top: -10px;">
                <h3 class="title" style="float:left; font-size:10px;">DATOS PERSONA NATURAL </h3>
                <h3 class="title" style="float:right; font-size:10px;">FECHA: <?php echo e($viamaticaDate); ?></h3>
            </div>
<!--            <div class="col-md-12">
                <h2 style="float:left">Nombres Completos: <span class="underline"></span> </h2>
                <h2 style="float:right">Apellidos Completos: <span class="underline"></span></h2>
            </div>-->
            <br>
            <br>
            <div class="divData" style="width:100%;">
                <div>
                    <table>
                        <tr>
                            <td colspan="2">Nombres Completos:</td>
                            <td colspan="7" style="text-align:left;" class="underlineCell"><?php echo e($customer->first_name); ?> <?php echo e($customer->second_name); ?></td>
                            <td colspan="2" style="text-align:right;">Apellidos Completos:</td>
                            <td colspan="8" style="text-align:left;" class="underlineCell"><?php echo e($customer->last_name); ?> <?php echo e($customer->second_last_name); ?></td>
                        </tr>
                        <tr>
                            <td colspan="3">Cédula Identidad:<input type="checkbox" style="margin-top:5px;margin-left:5px;" <?php if($customer->document_id == 1): ?> checked="checked" <?php endif; ?> > </td>
                            <td colspan="2"> Pasaporte:<input type="checkbox" style="margin-top:5px;" <?php if($customer->document_id == 2): ?> checked="checked" <?php endif; ?> ></td>
                            <td colspan="2"> Ruc:<input type="checkbox"  style="margin-top:5px;"> </td>
                            <td colspan="1" style="text-align:right;">Nro:</td>
                            <td colspan="2" class="underlineCell" style="text-align:left;"><?php echo e($customer->document); ?></td>
                            <td colspan="1" style="text-align:right;">Nacionalidad:</td>
                            <td colspan="8" class="underlineCell" style="text-align:left;"><?php echo e($birthCountry->name); ?></td>
                        </tr>
                        <tr>
                            <td colspan="2">Lugar y fecha de <br>nacimiento:</td>
                            <td colspan="17" class="underlineCell" style="text-align:left;"><?php echo e($birthCity->name); ?> <?php echo e($vinculation->birth_date); ?></td>
                        </tr>
                        <tr>
                            <td colspan="2">Estado civil:</td>
                            <td colspan="3">Casado/a:<input type="checkbox" style="margin-top:5px;"  <?php if($vinculation->civil_state ==2): ?> checked="checked" <?php endif; ?> ></td>
                            <td colspan="3">Soltero/a:<input type="checkbox" style="margin-top:5px;"  <?php if($vinculation->civil_state == 1): ?> checked="checked" <?php endif; ?> ></td>
                            <td colspan="3">Unión de Hecho  <input type="checkbox" style="margin-top:5px;" <?php if($vinculation->civil_state == 5): ?> checked="checked" <?php endif; ?> ></td>
                            <td colspan="2"> Divorciado/a: <input type="checkbox" style="margin-top:5px;" <?php if($vinculation->civil_state == 3): ?> checked="checked" <?php endif; ?> ></td>
                            <td colspan="2">Viudo/a: <input type="checkbox" style="margin-top:5px;" <?php if($vinculation->civil_state == 4): ?> checked="checked" <?php endif; ?> > </td>
                            <td  colspan="7"></td>

                        </tr>
                        <tr>
                            <td colspan="2">Dirección/ Calle Principal:</td>
                            <td colspan="18" class="underlineCell" style="text-align:left;"><?php echo e($vinculation->main_road); ?> </td>
                        </tr>
                        <tr>
                            <td colspan="2">Transversal:</td>
                            <td colspan="9" class="underlineCell" style="text-align:left;"><?php echo e($vinculation->secondary_road); ?></td>
                            <td colspan="1" style="text-align:right;">No:</td>
                            <td colspan="8" class="underlineCell" style="text-align:left;"> <?php echo e($vinculation->address_number); ?></td>

                        </tr>
                        <tr>
                            <td colspan="2">País:</td>
                            <td colspan="2" class="underlineCell" style="text-align:left;"><span > <?php echo e($country->name); ?></span> </td>
                            <td colspan="2" style="text-align:right;">Provincia: </td>
                            <td colspan="2" class="underlineCell" style="text-align:left;"><?php echo e($province->name); ?></td>
                            <td colspan="1" style="text-align:right;">Cantón: </td>
                            <td colspan="2" class="underlineCell" style="text-align:left;"><?php echo e($city->name); ?></td>
                            <td colspan="1" style="text-align:right;">Parroquia: </td>
                            <td colspan="2" class="underlineCell" style="text-align:left;">N/A</td>
                            <td colspan="1" style="text-align:right;">Sector: </td>
                            <td colspan="4" class="underlineCell" style="text-align:left;"><?php echo e($vinculation->address_zone); ?></td>
                        </tr>
                        <tr>
                            <td colspan="2">Teléfono:</td>
                            <td colspan="4"  class="underlineCell" style="text-align:left;"><?php echo e($customer->phone); ?></td>
                            <td colspan="1" style="text-align:right;">Celular:</td>
                            <td colspan="3" class="underlineCell" style="text-align:left;"><?php echo e($customer->mobile_phone); ?></td>
                            <td colspan="1" style="text-align:right;">Email:</td>
                            <td colspan="8" class="underlineCell" style="text-align:left;"><?php echo e($customer->email); ?></td>
                        </tr>
                    </table>
                </div>

            </div>
            <!-- SECOND SET OF DATA -->
            <div class="col-md-12">
                <h3 class="title" style="float:left">DATOS ACTIVIDAD ECONÓMICA/OCUPACIÓN/NEGOCIO</h3>
            </div>
            <br>
            <br>
            <br>
            <div class="divData" style="width:100%;">
                <div>
                    <table style="padding:0px; margin: 5px 0px;">
                        <tr>
                            <td width="14%">Independiente:<input type="checkbox" style="margin-top:5px;"></td>
                            <td width="14%">Empleado privado:<input type="checkbox" checked style="margin-top:5px;"></td>
                            <td width="14%">Empleado público:<input type="checkbox" style="margin-top:5px;"></td>
                            <td width="14%">Jubilado:<input type="checkbox" style="margin-top:5px;"></td>
                            <td width="14%">Estudiante:<input type="checkbox" style="margin-top:5px;"></td>
                            <td width="14%" style="text-align:right;">Otro:</td>
                            <td width="14%"  class="underlineCell" ><?php echo e($vinculation->economic_activity_other); ?></td>
                        </tr>
                        <tr>
                            <td width="14%">Ocupación o Actividad <br>Económica:</td>
                            <td colspan="3" class="underlineCell" style="text-align:left;"><?php echo e($ecoActivity->name); ?></td>
                            <td width="15%" style="text-align:right;">Nombre de la Empresa:</td>
                            <td colspan="2" class="underlineCell" style="text-align:left;">N/A</td>
                        </tr>
                         <tr>
                            <td colspan="1">Cargo que desempeña:</td>
                            <td colspan="3" class="underlineCell" style="text-align:left;"><?php echo e($occupation->name); ?></td>
                            <td colspan="1"style="text-align:right;">Correo Electrónico:</td>
                            <td colspan="14" class="underlineCell" style="text-align:left;"></td>
                        </tr>
                        <tr>
                            <td colspan="1">Dirección del trabajo:</td>
                            <td colspan="3" class="underlineCell"></td>
                            <td colspan="1" style="text-align:right;">Teléfonos:</td>
                            <td colspan="14" class="underlineCell"></td>
                        </tr>
                        <tr>
                            <td colspan="1">Dirección de cobro:</td>
                            <td colspan="1">Domicilio:<input type="checkbox" checked="checked" style="margin-top:5px;"></td>
                            <td colspan="1">Lugar de trabajo:<input type="checkbox" style="margin-top:5px;"></td>
                            <td colspan="2" style="text-align:right;">Otro:</td>
                            <td colspan="11" class="underlineCell" style="text-align:left;"></td>


                        </tr>
                    </table>
                </div>
            </div>
            <!-- THIRD SET OF DATA -->
            <div class="col-md-12">
                <h3 class="title" style="float:left">DATOS DEL CÓNYUGE O CONVIVIENTE</h3>
            </div>
            <br>
            <br>
            <br>
            <div class="divData" style="width:100%;">
                <div>
                    <table>
                        <tr>
                            <td colspan="2">Nombres y apellidos:</td>
                            <td colspan="17" class="underlineCell" colspan="3" style="text-align:left;"><?php echo e($vinculation->spouse_name); ?> <?php echo e($vinculation->spouse_last_name); ?></td>
                        </tr>
                        <tr>
                            <td colspan="2">Cédula de Identidad: <input type="checkbox" style="margin-top:5px;" <?php if($vinculation->spouse_document_id == 1): ?> checked="checked" <?php endif; ?>> </td>
                            <td colspan="1">Pasaporte: <input type="checkbox" style="margin-top:5px;" <?php if($vinculation->spouse_document_id == 3): ?> checked="checked" <?php endif; ?>> </td>
                            <td colspan="1" style="text-align:right;">No:</td>
                            <td colspan="6" class="underlineCell"><?php echo e($vinculation->spouse_document); ?></td>
                            <td colspan="1" style="text-align:right;">Nacionalidad:</td>
                            <td colspan="8" class="underlineCell">N/A</td>
                        </tr>
                        <tr>
                            <td colspan="2">Lugar y fecha de nacimiento:</td>
                            <td colspan="8" class="underlineCell" style="text-align:left;">N/A</td>
                            <td colspan="2" style="text-align:right;">Profesión o Actividad Económica:</td>
                            <td colspan="7" class="underlineCell" style="text-align:left;">N/A</td>
                        </tr>
                        <tr>
                            <td colspan="2">Nombre de la Empresa:</td>
                            <td colspan="8" class="underlineCell" style="text-align:left;">N/A</td>
                            <td colspan="1" style="text-align:right;">Ingresos Anuales:</td>
                            <td colspan="8" class="underlineCell" style="text-align:left;">N/A</td>
                        </tr>
                        <tr>
                            <td colspan="2">Teléfono:</td>
                            <td colspan="8" class="underlineCell" style="text-align:left;">N/A</td>
                            <td colspan="1" style="text-align:right;">Email:</td>
                            <td colspan="7" class="underlineCell" style="text-align:left;">N/A</td>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- FOURTH SET OF DATA -->
            <div class="col-md-12">
                <h3 class="title" style="float:left">SITUACIÓN FINANCIERA</h3>
            </div>
            <br>
            <br>
            <br>
            <div class="divData" style="width:100%;">
                <div>
                    <table>
                        <tr>
                            <td style="width:15%">Ingreso Anual:</td>
                            <td style="width:15%" class="underlineCell" style="text-align:left;"><?php echo e($vinculation->annual_income); ?></td>
                            <td style="width:15%">Otros Ingresos Anuales:</td>
                            <td style="width:15%" class="underlineCell" style="text-align:left;"><?php echo e($vinculation->other_annual_income); ?></td>
                            <td style="width:15%">Total Ingresos:</td>
                            <td style="width:15%" class="underlineCell" style="text-align:left;"><?php echo e($vinculation->total_annual_income); ?></td>
                        </tr>
                        <tr>
                            <td style="width:25%" colspan="3">Descripción de otros ingresos:</td>
                            <td style="width:75%"colspan="3" class="underlineCell" style="text-align:left;"><?php echo e($vinculation->description_other_income); ?></td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                <span class="title">(Aplica a contratos cuya suma asegurada sea superior a USD 50.000)</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:15%">Total de Activos: USD:</td>
                            <td style="width:15%" class="underlineCell" style="text-align:left;"><?php echo e($vinculation->total_actives); ?></td>
                            <td style="width:15%">Total de Pasivos: USD:</td>
                            <td style="width:15%" class="underlineCell" style="text-align:left;"><?php echo e($vinculation->total_pasives); ?></td>
                            <td style="width:15%">(A-P) = Total Patrimonio:</td>
                            <td style="width:15%" class="underlineCell" style="text-align:left;"><?php echo e($vinculation->total_assets_pasives); ?></td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                <span class="title">Referencias (Aplica a contratos cuya suma asegurada sea superior a USD 200.000):</span>
                            </td>
                        </tr>
                    </table>
                    <table id="referenceTable">
                        <tr>
                            <th style="width:33%" colspan="3">Referencias Personales</th>
                            <th style="width:33%" colspan="3">Referencias Comerciales</th>
                            <th style="width:33%" colspan="3">Referencias Bancos/Tarjetas</th>
                        </tr>
                        <tr>
                            <td style="width:10%">Nombre</td>
                            <td style="width:23%" colspan="2" style="text-align:left;"><?php echo e($vinculation->personal_reference_name); ?></td>
                            <td style="width:10%">Entidad</td>
                            <td style="width:23%" colspan="2" style="text-align:left;"><?php echo e($vinculation->commercial_reference_name); ?></td>
                            <td style="width:10%" rowspan="2">Institución Financiera</td>
                            <td style="width:23%" colspan="2" rowspan="2" style="text-align:left;"><?php echo e($vinculation->commercial_reference_bank_name); ?></td>
                        </tr>
                        <tr>
                            <td style="width:10%">Parentesco</td>
                            <td style="width:23%" colspan="2"><?php echo e($vinculation->personal_reference_relationship); ?></td>
                            <td style="width:10%">Monto</td>
                            <td style="width:23%" colspan="2"><?php echo e($vinculation->commercial_reference_amount); ?></td>
                        </tr>
                        <tr>
                            <td style="width:10%">Teléfono</td>
                            <td style="width:23%" colspan="2"><?php echo e($vinculation->personal_reference_phone); ?></td>
                            <td style="width:10%">Teléfono</td>
                            <td style="width:23%" colspan="2"><?php echo e($vinculation->commercial_reference_phone); ?></td>
                            <td style="width:10%" >Producto</td>
                            <td style="width:23%" colspan="2"><?php echo e($vinculation->commercial_reference_product); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="page-break"></div>
            <!-- FIFTH SET OF DATA -->
            <div class="col-md-12">
                <h2 class="title" style="float:left">VÍNCULOS EXISTENTES ENTRE EL CONTRATANTE, BENEFICIARIO Y PAGADOR</h2>
            </div>
            <br>
            <br>
           <div class="divData" style="width:100%;">
                <div>
                    <table>
                        <tr>
                            <td style="width:100%">¿Es usted el beneficiario de la póliza? Si <input type="checkbox" style="margin-top:5px" <?php if($vinculation->benefitiary_name == null): ?> checked="checked" <?php endif; ?>> No <input type="checkbox" style="margin-top:5px" <?php if($vinculation->benefitiary_name != null): ?> checked="checked" <?php endif; ?>> Si respondió NO, indique a continuación los datos del beneficiario y su relación: </td>
                        </tr>
                        <tr>
                            <td style="width:50%">Nombres Completos o razón social:</td>
                            <td style="width:50%"><?php echo e($vinculation->benefitiary_name); ?></td>
                        </tr>
                        <tr>
                            <td style="width:50%">Cédula Identidad:<input type="checkbox" style="margin-top:5px" <?php if($vinculation->benefitiary_document_id == 1): ?> checked="checked" <?php endif; ?>> Pasaporte:<input type="checkbox" style="margin-top:5px" <?php if($vinculation->benefitiary_document_id == 3): ?> checked="checked" <?php endif; ?>> Ruc:<input type="checkbox" style="margin-top:5px" <?php if($vinculation->benefitiary_document_id == 2): ?> checked="checked" <?php endif; ?>> Nro: <span class="underline"></span></td>
                            <td style="width:50%">Nacionalidad: <span class="underline"></span></td>
                        </tr>
                        <tr>
                            <td style="width:50%">Dirección de domicilio: <span class="underline"><?php echo e($vinculation->benefitiary_address); ?></span></td>
                            <td style="width:50%">Teléfono: <span class="underline"><?php echo e($vinculation->benefitiary_phone); ?></span></td>
                        </tr>
                        <tr>
                            <td colspan="2">Relación: <span class="underline"><?php echo e($vinculation->benefitiary_relationship); ?></span></td>
                        </tr>
                        <tr>
                            <td style="width:10%" colspan="2">¿Es usted el pagador de la póliza? Sí <input type="checkbox" style="margin-top:5px" checked="checked"> No <input type="checkbox" style="margin-top:5px"> Si respondió NO, indique a continuación los datos personales del pagador de la póliza y su relación:</td>
                        </tr>
                        <tr>
                            <td style="width:10%" colspan="2">Nombres Completos o razón social: <span class="underline"></span></td>
                        </tr>
                        <tr>
                            <td style="width:50%">Cédula Identidad:<input type="checkbox" style="margin-top:5px"> Pasaporte:<input type="checkbox" style="margin-top:5px"> Ruc:<input type="checkbox" style="margin-top:5px"> Nro: <span class="underline"></span></td>
                            <td style="width:50%">Nacionalidad: <span class="underline"></span></td>
                        </tr>
                        <tr>
                            <td style="width:50%">Dirección de domicilio: <span class="underline"></span></td>
                            <td style="width:50%">Teléfono: <span class="underline"></span></td>
                        </tr>
                        <tr>
                            <td style="width:10%" colspan="2">Relación: <span class="underline"></span></td>
                        </tr>
                    </table>
                    <div>
                        <span style="text-align:justify !important">Cuando en la póliza de seguro de vida o de accidentes personales con la cobertura de muerte, los asegurados hubiesen designado como beneficiarios a sus parientes hasta el cuarto grado de consanguinidad o segundo grado de afinidad, o a su cónyuge o conviviente en unión de hecho, no se requerirá la información de tales beneficiarios. Si fuesen otras personas las designadas como beneficiarios, la documentación referente a estos deberá ser presentada, obligatoriamente, mediante formulario de vinculación de clientes. </span>
                    </div>
                </div>
            </div>
            <!-- SIXTH SET OF DATA -->
            <div class="col-md-12">
                <h2 class="title" style="float:left">DECLARACIÓN Y AUTORIZACIÓN</h2>
            </div>
            <br><br><br><br>
            <div class="divData" style="width:100%;text-align:justify !important">
                <div>
                    <span class="title">DECLARACIÓN</span>
                    <br>
                    <span style="text-align:justify !important">
                        Declaro que la información contenida en este formulario, así como toda la documentación presentada, es verdadera, completa y proporciona la información de modo confiable y actualizada. Además, declaro conocer y aceptar que es mi obligación como cliente actualizar anualmente estos datos, así como el comunicar y documentar de manera inmediata a la compañía cualquier cambio en la información que hubiere proporcionado. Durante la vigencia de la relación con Seguros Sucre S.A., me comprometo a proveer de la documentación e información que me sea solicitada.

                        El asegurado declara expresamente que el seguro aquí convenido ampara bienes de procedencia lícita, no ligados con actividades de narcotráfico, lavado de dinero o cualquier otra actividad tipificada en la Ley Orgánica de Prevención, Detección y Erradicación del Delito de Lavado de Activos y del Financiamiento de Delitos. Igualmente, la prima a pagar por este concepto tiene origen lícito y ninguna relación con las actividades mencionadas anteriormente. Eximo a Seguros Sucre S.A. de toda responsabilidad, inclusive respecto a terceros, si esta declaración fuese falsa o errónea. En caso de que se inicien investigaciones sobre mi persona, relacionadas con las actividades antes señaladas o de producirse transacciones inusuales o injustificadas, Seguros Sucre S.A., podrá proporcionar a las autoridades competentes toda la información que tenga sobre las mismas o que le sea requerida. En tal sentido renuncio a presentar en contra de Seguros Sucre S.A., sus funcionarios o empleados, cualquier reclamo o acción legal, judicial, extrajudicial, administrativa, civil penal o arbitral en la eventualidad de producirse tales hechos.

                        Declaración sobre la condición de Persona Expuesta Políticamente PEP (Persona que desempeña o ha desempeñado funciones públicas en el país o en el exterior). Informo que he leído la Lista Mínima de Cargos Públicos a ser considerados "Personas Expuestas Políticamente" y declaro bajo juramento que SI</span><?php if($vinculation->person_exposed =='yes'): ?> __<span class="underline">X</span>__ <?php else: ?> _____ <?php endif; ?> <span text-align="justifty"> NO </span><?php if($vinculation->person_exposed !='yes'): ?> __<span class="underline">X</span>__ <?php else: ?> _____ <?php endif; ?> <span text-align="justifty"> me encuentro ejerciendo uno de los cargos incluidos en la lista o lo ejercí hace un año atrás. En el caso de que la respuesta sea positiva, indicar: Cargo/Función/Jerarquía:
                    </span>
                    <span class="underline"><?php echo e($vinculation->family_exposed); ?></span>
                    <hr>
                    <span>
                        Nota: La presente declaración no constituye una autoincriminación de ninguna clase, ni conlleva ninguna responsabilidad administrativa, civil o penal.
                    </span>
                </div>
            </div>
            <br>
           <div class="divData" style="width:100%;text-align:justify !important">
                <div>
                    <span class="title">AUTORIZACIÓN</span>
                    <br>
                    <span style="text-align:justify">
                        Siendo conocedor de las disposiciones legales, autorizo expresamente en forma libre, voluntaria e irrevocable a Seguros Sucre S. A., a realizar el análisis y las verificaciones que considere necesarias para corroborar la licitud de fondos y bienes comprendidos en el contrato de seguro e informar a las autoridades competentes si fuera el caso; además autorizo expresa, voluntaria e irrevocablemente a todas las personas naturales o jurídicas de derecho público o privado a facilitar a Seguros Sucre S.A. toda la información que ésta les requiera  y revisar los buró de crédito sobre mi información de riesgos crediticios. 
                    </span>
                    <hr>
                    <span>
                        Nota: La presente declaración no constituye una autoincriminación de ninguna clase, ni conlleva ninguna responsabilidad administrativa, civil o penal.
                    </span>
                    <br>
                    <br>
                    <span style="text-decoration: underline; font-style: italic; margin-left: 40%">
                        Firmado Electronicamente
                    </span>
                    <br>
                    <span style="margin-left: 42%">
                        Firma del Cliente
                    </span>
                    <br>
                    <span style="margin-left: 42%">
                        C.I: <?php echo e($customer->document); ?>

                    </span>
                </div>
            </div>
            <!-- SEVENTH SET OF DATA AGENTE --> 
             <div class="col-md-12">
                <h2 class="title" style="float:left">DECLARACIÓN DE CORREDOR O BRÓKER (Si aplica).</h2>
            </div>
            <br><br><br><br>
            <div class="divData" style="width:100%;text-align:justify !important">
                <?php if($broker->channelId!=19): ?>
                <div>
                    <span style="text-align:justify">
                        Comunico que mi Corredor o Bróker es ________________________<span class="underline"><?php echo e($broker->agentedes); ?></span>_______________________________________ para el manejo y administración de las pólizas adquiridas en Seguros Sucre S.A., emitidas a mi nombre.
                    </span>
                    <br>
                    <br>
                    <span style="text-decoration: underline; font-style: italic; margin-left: 40%">
                        Firmado Electronicamente
                    </span>
                    <br>
                    <span style="margin-left: 42%">
                        Firma del Cliente
                    </span>
                    <br>
                    <span style="margin-left: 42%">
                        C.I: <?php echo e($customer->document); ?>

                    </span>
                </div>
                <?php else: ?>
                <div>
                    <span style="text-align:justify">
                        Comunico que mi Corredor o Bróker es _______________________________________________________________ para el manejo y administración de las pólizas adquiridas en Seguros Sucre S.A., emitidas a mi nombre.
                    </span>
                    <br>
                    <br>
                    <span style="margin-left: 42%">
                        Firma del Cliente
                    </span>
                    <br>
                    <span style="margin-left: 42%">
                        C.I:
                    </span>
                </div>
                <?php endif; ?>
            </div>
            <!-- EIGTH SET OF DATA DATOS DEL CANAL -->
            <div class="col-md-12">
                <h2 class="title" style="float:left">DATOS DEL CORREDOR O BRÓKER</h2>
            </div>
            <br><br><br><br>
            <div class="divData" style="width:100%;text-align:justify !important">
                <?php if($broker->channelId!=19): ?>
                <div>
                    <span style="text-align:justify">
                        Nombres Completos o razón social: ______________________________<span class="underline"><?php echo e($broker->agentedes); ?></span>_________________________________________________
                        Nombres Completos y cargo del ejecutivo encargado: ____________<span class="underline"><?php echo e($broker->ejecutivo_ss); ?> - <?php echo e($broker->puntodeventades); ?></span>____________
                        Declaro haber cumplido con el proceso de vinculación que estipula la política "Conozca a su Cliente" requerida por la compañía de seguros, la misma que ha sido confirmada y verificada correctamente.
                    </span>
                    <br>
                </div>
                <?php else: ?>
                <div>
                    <span style="text-align:justify">
                        Nombres Completos o razón social: _______________________________________________________________________________
                        Nombres Completos y cargo del ejecutivo encargado: ________________________
                        Declaro haber cumplido con el proceso de vinculación que estipula la política "Conozca a su Cliente" requerida por la compañía de seguros, la misma que ha sido confirmada y verificada correctamente.
                    </span>
                    <br>
                </div>
                <?php endif; ?>
            </div>
            <!-- NINTH SET OF DATA -->
            <div class="col-md-12">
                <h2 class="title" style="float:left">USO EXCLUSIVO DE SEGUROS SUCRE S.A.</h2>
            </div>
            <br><br><br><br>
            <div class="divData" style="width:100%;text-align:justify !important">
                <div>
                    <span style="text-align:justify">
                        Datos de la Relación Comercial
                        Nueva __<span class="underline">X</span>__    Renovación ____ Ramo: _____<span class="underline"><?php echo e($broker->ramodes); ?></span>_____________ Suma Asegurada: _______<span class="underline"><?php echo e($broker->insured_value); ?></span>________ Canal de Vinculación: ____<span class="underline"><?php echo e($broker->canal); ?></span>_______ 
                    </span>
                    <br>
                    <span class="title">
                        Nombre y firma del Ejecutivo que verifica la documentación e información:
                    </span>
                    <br>
                    <span>
                        Nombres Completos: ________________<span class="underline"><?php if($broker->channelId!=19): ?><?php echo e($broker->ejecutivo); ?><?php else: ?><?php echo e($broker->ejecutivo_ss); ?><?php endif; ?></span>__________________________________ Confirmo que he revisado la razonabilidad de la información proporcionada por el cliente o contratante y declaro que he verificado la documentación e información solicitada de acuerdo a lo establecido en la política "Conozca su Cliente" y he analizado la información respecto a la actividad económica e ingresos, los cuales concuerdan con los productos solicitados.
                    </span>
                    <br>
                </div>
            </div>
            <!-- TENTH SET OF DATA -->
            <div class="col-md-12">
                <h3 class="title" style="float:left">DOCUMENTOS REQUERIDOS - PERSONA NATURAL</h3>
            </div>
            <br><br>
            <div class="divData" style="width:100%">
                <div>
                    <ul>
                      <li style="padding:5px">•	Copia del documento de identidad del contratante</li>
                      <li style="padding:5px">•	Copia del documento de identidad del cónyuge o conviviente legal del contratante.</li>
                      <li style="padding:5px">•	Copia de una planilla de servicios básicos</li>
                    </ul>
                    <span class="title" style="margin-bottom: 0px;">
                        Contratos cuya suma asegurada sea mayor a USD. 50.000
                    </span>
                    <ul>
                      <li style="padding:5px">• Confirmación del pago del impuesto a la renta del año inmediato anterior o constancia de la información publicada</li>
                      <li style="padding:5px">por el Servicio de Rentas Internas (SRI) a través de la página web, de ser aplicable</li>
                    </ul>
                </div>
            </div>
        </main>
    </body>
</html>
<?php /**PATH C:\xampp\htdocs\laravel\magnussucre\resources\views/legalPersonVinculation/pdf_beneficiario.blade.php ENDPATH**/ ?>