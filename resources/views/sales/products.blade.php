<script src="{{ assets('js/sales/products.js') }}"></script>
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<style>
    .form-group{
        margin-top:25px !important;
        margin-bottom: 25px !important;
    }
    .frmSearch {border: 1px solid #a8d4b1;background-color: #c6f7d0;margin: 2px 0px;padding:40px;border-radius:4px;}
    #customer-list{float:left;list-style:none;margin-top:-3px;padding:0;width:290px;position: absolute;z-index:9999;}
    #customer-list li{padding: 10px; background: #f0f0f0; border-bottom: #bbb9b9 1px solid;}
    #customer-list li:hover{background:#ece3d2;cursor: pointer;}
    #search-box{padding: 10px;border: #a8d4b1 1px solid;border-radius:4px;}
    .error{border:1px solid red}
    .modal-header {
        border-bottom: 0 none;
    }

    .modal-footer {
        border-top: 0 none;
    }
</style>
<meta name="csrf-token" content="{{ csrf_token() }}" />
<div class="container-fluid" style="font-size:14px !important;padding-bottom: 15px;">
    <!--<div class="row justify-content-center border" style="margin-left:20%;">-->

    <div class="col-md-8 col-md-offset-2 border">
        <div class="row">
            <div class="col-xs-12 registerForm" style="margin:12px;">
                <center>
                    <h4 style="font-weight:bold">Registro de Nueva Venta</h4>
                </center>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-3 wizard_inicial"><div style="margin-left:-10px" id="zeroStepWizard" class="wizard_inactivo registerForm">Ramo</div></div>
            <div class="col-xs-12 col-md-2 wizard_medio"><div id="firstStepWizard" class="wizard_inactivo registerForm">Cliente</div></div>
            <div class="col-xs-12 col-md-2 wizard_medio"><div id="secondStepWizard" class="wizard_inactivo registerForm">{{session('saleCreateAsset')}}</div></div>
            <div class="col-xs-12 col-md-2 wizard_medio"><div id="thirdStepWizard" class="wizard_activo registerForm">Producto</div></div>
            <div class="col-xs-12 col-md-3 wizard_final"><div style="margin-right:-10px;" id="fourthStepWizard" class="wizard_inactivo registerForm">Resumen</div></div>
        </div>
        <div class="col-md-12">
            <form name="salesForm" method="POST" action="/user" id="salesForm">
                <input type="hidden" name="sale_movement" id="sale_movement" value="{{$sale_movement}}">
                <input type="hidden" name="sale_id" id="sale_id" value="{{$sale_id}}">
                <input type="hidden" name="insurance_branch" id="insurance_branch" value="{{$insurance_branch}}">
                <div id="thirdStep" class="col-md-12">
                    <div class="col-md-12" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                        <div class="row" style="float:left">
                            <a class="btn btn-default registerForm" align="right" href="{{asset('/sales')}}"> Cancelar </a>
                        </div>
                        <div class="row" style="float:right">
                            <a class="btn btn-default registerForm" align="right"  href="#" style="background-color: #444;color:white" onclick="previousStep()"> <span class="glyphicon glyphicon-step-backward"></span>Anterior </a>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;">
                        <div class="wizard_activo registerForm titleDivBorderTop">
                            <span class="titleLink">Productos</span>
                            <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                        </div>
                        <div id="productAlert" class="alert alert-danger hidden registerForm titleDivBorderTop" style="margin-top:5px; border-radius: 0px !important">
                            <strong>¡Alerta!</strong> Debe seleccionar un producto
                        </div>
                        <!-- Contenedor -->
                        <div class="pricing-wrapper clearfix" style="padding: 5% 0 5% 0;">
                            <div class="pricing-table">
                                <h3 class="pricing-title">Futuro Seguro Plan A</h3>
                                <div class="price">$400<sup>/ año</sup></div>
                                <!-- Lista de Caracteristicas / Propiedades -->
                                <table>
                                    <tr>
                                        <td align="left" width="60%">Muerte por cualquier Causa</td>
                                        <td align="right" width="40%">$10000</td>
                                    </tr>
                                    <tr>
                                        <td align="left" width="60%">Prima Neta mensual</td>
                                        <td align="right" width="40%">$8</td>
                                    </tr>
                                    <tr>
                                        <td align="left" width="60%">Asistencia Médica Familiar</td>
                                        <td align="right" width="40%"><span class="glyphicon glyphicon-ok"></span></td>
                                    </tr>
                                    <tr>
                                        <td align="left" width="60%">Servicio Exequial</td>
                                        <td align="right" width="40%"><span class="glyphicon glyphicon-ok"></span></td>
                                    </tr>
                                </table>
                                <!-- Contratar / Comprar -->
                                <div class="table-buy">
                                    <!--<p>$60<sup>/ año</sup></p>-->
                                    <a href="#" onclick="openModal('Basic')">Ver Condiciones</a>
                                    <a id="myModalBtnBasic" href="#" data-toggle="modal" data-target="#myModalBasic" class="hidden"></a>
                                    <br><br>
                                    <a href="#" class="pricing-action" onclick="selectProduct(8, 'Futuro Seguro Plan A', 400)">Seleccionar</a>
                                </div>
                            </div>
                            <!-- Modal -->
                            <div id="myModalBasic" class="modal fade" role="dialog">
                                <div class="modal-dialog modal-lg">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <!--<h4 class="modal-title">Condiciones y Beneficios</h4>-->
                                            <div class="col-md-12 border" style="font-weight:bold;font-size: 40px;box-shadow: 1px 1px 1px #999;">
                                                <div id="modalVehicleBasic" class="col-md-4" style="text-align:center; font-size: 22px; height:70px; border-right: 1px solid black;">
                                                    Vehiculo
                                                </div>
                                                <div id="modalProductBasic" class="col-md-4" style="text-align:center;font-size: 22px;height:70px; border-right: 1px solid black;">
                                                    Rueda Seguro <br> Basic
                                                </div>
                                                <div id="modalPriceBasic" class="col-md-4" style="text-align:center;font-size: 22px;height:70px;">
                                                    $400 <br> anuales
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-body">
                                            <div class="col-md-6">
                                                <table>
                                                    <tr>
                                                        <th colspan="2" class="tableHeader">Coberturas</th>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Perdida Parcial por robo</td>
                                                        <td align="right" width="40%">70%</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Perdida Parcial por daño</td>
                                                        <td align="right" width="40%">70%</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Perdida total por robo</td>
                                                        <td align="right" width="40%">Valor Comercial</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Perdida total por daño</td>
                                                        <td align="right" width="40%">Valor Comercial</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Caminos Vecinales</td>
                                                        <td align="right" width="40%"><span class="glyphicon glyphicon-ok"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Desplome de Edificios</td>
                                                        <td align="right" width="40%"><span class="glyphicon glyphicon-ok"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Impacto de Proyectiles</td>
                                                        <td align="right" width="40%"><span class="glyphicon glyphicon-ok"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Tránsito por caminos de circulación a países de la CAN (excluye responsabilidad civil)</td>
                                                        <td align="right" width="40%"><span class="glyphicon glyphicon-ok"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Responsabilidad Civil (límite único combinado)</td>
                                                        <td align="right" width="40%">$40.000</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Accidentes Personales - Muerte Accidental</td>
                                                        <td align="right" width="40%">$8.000</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Accidentes Personales - Invalidez total y permanente</td>
                                                        <td align="right" width="40%">$8.000</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Accidentes Personales - Gastos médicos por accidente</td>
                                                        <td align="right" width="40%">$5.000</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Accidentes Personales - Canasta familiar por muerte del titular de la poliza</td>
                                                        <td align="right" width="40%">$2.000</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <table>
                                                    <tr>
                                                        <th colspan="2" class="tableHeader">Beneficios y Asistencias</th>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Autorización express para siniestros hasta $1.500</td>
                                                        <td align="right" width="40%"><span class="glyphicon glyphicon-ok"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Se cubre AIRBAG al 100% solamente por siniestro (1 evento al año)</td>
                                                        <td align="right" width="40%"><span class="glyphicon glyphicon-ok"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Cobertura para accesorios hasta el 20% del valor asegurado</td>
                                                        <td align="right" width="40%"><span class="glyphicon glyphicon-ok"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">No aplicación de depreciación en pérdidas parciales ni totales</td>
                                                        <td align="right" width="40%"><span class="glyphicon glyphicon-ok"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Remolque o traslado del vehículo por avería y accidentes</td>
                                                        <td align="right" width="40%">$400</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Segundo traslado</td>
                                                        <td align="right" width="40%">$100</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Auxilio mecánico en averías como: llanta baja, llaves al interior, batería, gasolina</td>
                                                        <td align="right" width="40%"><span class="glyphicon glyphicon-ok"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Conductor Elegido</td>
                                                        <td align="right" width="40%"><span class="glyphicon glyphicon-ok"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Auto sustituto - Siniestro parcial (valor mínimo $1.000)</td>
                                                        <td align="right" width="40%">12 días</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Auto sustituto - Siniestro total (valor mínimo $1.000)</td>
                                                        <td align="right" width="40%">20 días</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Mi auto matriculado (aplican condiciones)</td>
                                                        <td align="right" width="40%"><span class="glyphicon glyphicon-ok"></span></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="pricing-table recommended">
                                <h3 class="pricing-title">Futuro Seguro Plan B</h3>
                                <div class="price">$500<sup>/ año</sup></div>
                                <!-- Lista de Caracteristicas / Propiedades -->
                                <table>
                                    <tr>
                                        <td align="left" width="60%">Muerte por cualquier Causa</td>
                                        <td align="right" width="40%">$20000</td>
                                    </tr>
                                    <tr>
                                        <td align="left" width="60%">Prima Neta mensual</td>
                                        <td align="right" width="40%">$13,50</td>
                                    </tr>
                                    <tr>
                                        <td align="left" width="60%">Asistencia Médica Familiar</td>
                                        <td align="right" width="40%"><span class="glyphicon glyphicon-ok"></span></td>
                                    </tr>
                                    <tr>
                                        <td align="left" width="60%">Servicio Exequial</td>
                                        <td align="right" width="40%"><span class="glyphicon glyphicon-ok"></span></td>
                                    </tr>
                                </table>
                                <!-- Contratar / Comprar -->
                                <div class="table-buy">
                                    <!--<p>$100<sup>/ año</sup></p>-->
                                    <a href="#" onclick="openModal('Ultimate')">Ver Condiciones</a>
                                    <a id="myModalBtnUltimate" href="#" class="hidden" data-toggle="modal" data-target="#myModalUltimate">Ver Condiciones</a>
                                    <br><br>
                                    <a href="#" class="pricing-action" onclick="selectProduct(9, 'Futuro Seguro Plan B', 500)">Seleccionar</a>
                                </div>
                            </div>
                            <!-- Modal -->
                            <div id="myModalUltimate" class="modal fade" role="dialog">
                                <div class="modal-dialog modal-lg">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                            <div class="col-md-12 border" style="font-weight:bold;font-size: 40px;box-shadow: 1px 1px 1px #999;">
                                                <div id="modalVehicleUltimate" class="col-md-4" style="text-align:center; font-size: 22px; height:70px; border-right: 1px solid black;">
                                                    Vehiculo
                                                </div>
                                                <div id="modalProductUltimate" class="col-md-4" style="text-align:center;font-size: 22px;height:70px; border-right: 1px solid black;">
                                                    Rueda Seguro <br> Ultimate
                                                </div>
                                                <div id="modalPriceUltimate" class="col-md-4" style="text-align:center;font-size: 22px;height:70px;">
                                                    $500 <br> anuales
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-body">
                                            <div class="col-md-6">
                                                <table>
                                                    <tr>
                                                        <th colspan="2" class="tableHeader">Coberturas</th>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Perdida Parcial por robo</td>
                                                        <td align="right" width="40%">70%</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Perdida Parcial por daño</td>
                                                        <td align="right" width="40%">70%</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Perdida total por robo</td>
                                                        <td align="right" width="40%">Valor Comercial</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Perdida total por daño</td>
                                                        <td align="right" width="40%">Valor Comercial</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Caminos Vecinales</td>
                                                        <td align="right" width="40%"><span class="glyphicon glyphicon-ok"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Desplome de Edificios</td>
                                                        <td align="right" width="40%"><span class="glyphicon glyphicon-ok"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Impacto de Proyectiles</td>
                                                        <td align="right" width="40%"><span class="glyphicon glyphicon-ok"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Tránsito por caminos de circulación a países de la CAN (excluye responsabilidad civil)</td>
                                                        <td align="right" width="40%"><span class="glyphicon glyphicon-ok"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Responsabilidad Civil (límite único combinado)</td>
                                                        <td align="right" width="40%">$40.000</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Accidentes Personales - Muerte Accidental</td>
                                                        <td align="right" width="40%">$8.000</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Accidentes Personales - Invalidez total y permanente</td>
                                                        <td align="right" width="40%">$8.000</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Accidentes Personales - Gastos médicos por accidente</td>
                                                        <td align="right" width="40%">$5.000</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Accidentes Personales - Canasta familiar por muerte del titular de la poliza</td>
                                                        <td align="right" width="40%">$2.000</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <table>
                                                    <tr>
                                                        <th colspan="2" class="tableHeader">Beneficios y Asistencias</th>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Autorización express para siniestros hasta $1.500</td>
                                                        <td align="right" width="40%"><span class="glyphicon glyphicon-ok"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Se cubre AIRBAG al 100% solamente por siniestro (1 evento al año)</td>
                                                        <td align="right" width="40%"><span class="glyphicon glyphicon-ok"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Cobertura para accesorios hasta el 20% del valor asegurado</td>
                                                        <td align="right" width="40%"><span class="glyphicon glyphicon-ok"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">No aplicación de depreciación en pérdidas parciales ni totales</td>
                                                        <td align="right" width="40%"><span class="glyphicon glyphicon-ok"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Remolque o traslado del vehículo por avería y accidentes</td>
                                                        <td align="right" width="40%">$400</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Segundo traslado</td>
                                                        <td align="right" width="40%">$100</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Auxilio mecánico en averías como: llanta baja, llaves al interior, batería, gasolina</td>
                                                        <td align="right" width="40%"><span class="glyphicon glyphicon-ok"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Conductor Elegido</td>
                                                        <td align="right" width="40%"><span class="glyphicon glyphicon-ok"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Auto sustituto - Siniestro parcial (valor mínimo $1.000)</td>
                                                        <td align="right" width="40%">12 días</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Auto sustituto - Siniestro total (valor mínimo $1.000)</td>
                                                        <td align="right" width="40%">20 días</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Mi auto matriculado (aplican condiciones)</td>
                                                        <td align="right" width="40%"><span class="glyphicon glyphicon-ok"></span></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="pricing-table">
                                <h3 class="pricing-title">Futuro Seguro Plan C</h3>
                                <div class="price">$600<sup>/ año</sup></div>
                                <!-- Lista de Caracteristicas / Propiedades -->
                                <table>
                                    <tr>
                                        <td align="left" width="60%">Muerte por cualquier Causa</td>
                                        <td align="right" width="40%">$30000</td>
                                    </tr>
                                    <tr>
                                        <td align="left" width="60%">Prima Neta mensual</td>
                                        <td align="right" width="40%">$19</td>
                                    </tr>
                                    <tr>
                                        <td align="left" width="60%">Asistencia Médica Familiar</td>
                                        <td align="right" width="40%"><span class="glyphicon glyphicon-ok"></span></td>
                                    </tr>
                                    <tr>
                                        <td align="left" width="60%">Servicio Exequial</td>
                                        <td align="right" width="40%"><span class="glyphicon glyphicon-ok"></span></td>
                                    </tr>
                                </table>
                                <!-- Contratar / Comprar -->
                                <div class="table-buy">
                                    <a href="#" onclick="openModal('Premium')">Ver Condiciones</a>
                                    <a id="myModalBtnPremium" href="#" class="hidden" data-toggle="modal" data-target="#myModalPremium">Ver Condiciones</a>
                                    <br><br>
                                    <a href="#" class="pricing-action" onclick="selectProduct(10, 'Futuro Seguro Plan C', 600)">Seleccionar</a>
                                </div>
                            </div>
                            <!-- Modal -->
                            <div id="myModalPremium" class="modal fade" role="dialog">
                                <div class="modal-dialog modal-lg">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                            <div class="col-md-12 border" style="font-weight:bold;font-size: 40px;box-shadow: 1px 1px 1px #999;">
                                                <div id="modalVehiclePremium" class="col-md-4" style="text-align:center; font-size: 22px; height:70px; border-right: 1px solid black;">
                                                    Vehiculo
                                                </div>
                                                <div id="modalProductPremium" class="col-md-4" style="text-align:center;font-size: 22px;height:70px; border-right: 1px solid black;">
                                                    Rueda Seguro <br> Premium
                                                </div>
                                                <div id="modalPricePremium" class="col-md-4" style="text-align:center;font-size: 22px;height:70px;">
                                                    $600 <br> anuales
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-body">
                                            <div class="col-md-6">
                                                <table>
                                                    <tr>
                                                        <th colspan="2" class="tableHeader">Coberturas</th>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Perdida Parcial por robo</td>
                                                        <td align="right" width="40%">70%</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Perdida Parcial por daño</td>
                                                        <td align="right" width="40%">70%</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Perdida total por robo</td>
                                                        <td align="right" width="40%">Valor Comercial</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Perdida total por daño</td>
                                                        <td align="right" width="40%">Valor Comercial</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Caminos Vecinales</td>
                                                        <td align="right" width="40%"><span class="glyphicon glyphicon-ok"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Desplome de Edificios</td>
                                                        <td align="right" width="40%"><span class="glyphicon glyphicon-ok"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Impacto de Proyectiles</td>
                                                        <td align="right" width="40%"><span class="glyphicon glyphicon-ok"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Tránsito por caminos de circulación a países de la CAN (excluye responsabilidad civil)</td>
                                                        <td align="right" width="40%"><span class="glyphicon glyphicon-ok"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Responsabilidad Civil (límite único combinado)</td>
                                                        <td align="right" width="40%">$40.000</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Accidentes Personales - Muerte Accidental</td>
                                                        <td align="right" width="40%">$8.000</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Accidentes Personales - Invalidez total y permanente</td>
                                                        <td align="right" width="40%">$8.000</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Accidentes Personales - Gastos médicos por accidente</td>
                                                        <td align="right" width="40%">$5.000</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Accidentes Personales - Canasta familiar por muerte del titular de la poliza</td>
                                                        <td align="right" width="40%">$2.000</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <table>
                                                    <tr>
                                                        <th colspan="2" class="tableHeader">Beneficios y Asistencias</th>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Autorización express para siniestros hasta $1.500</td>
                                                        <td align="right" width="40%"><span class="glyphicon glyphicon-ok"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Se cubre AIRBAG al 100% solamente por siniestro (1 evento al año)</td>
                                                        <td align="right" width="40%"><span class="glyphicon glyphicon-ok"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Cobertura para accesorios hasta el 20% del valor asegurado</td>
                                                        <td align="right" width="40%"><span class="glyphicon glyphicon-ok"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">No aplicación de depreciación en pérdidas parciales ni totales</td>
                                                        <td align="right" width="40%"><span class="glyphicon glyphicon-ok"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Remolque o traslado del vehículo por avería y accidentes</td>
                                                        <td align="right" width="40%">$400</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Segundo traslado</td>
                                                        <td align="right" width="40%">$100</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Auxilio mecánico en averías como: llanta baja, llaves al interior, batería, gasolina</td>
                                                        <td align="right" width="40%"><span class="glyphicon glyphicon-ok"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Conductor Elegido</td>
                                                        <td align="right" width="40%"><span class="glyphicon glyphicon-ok"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Auto sustituto - Siniestro parcial (valor mínimo $1.000)</td>
                                                        <td align="right" width="40%">12 días</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Auto sustituto - Siniestro total (valor mínimo $1.000)</td>
                                                        <td align="right" width="40%">20 días</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" width="60%">Mi auto matriculado (aplican condiciones)</td>
                                                        <td align="right" width="40%"><span class="glyphicon glyphicon-ok"></span></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="productCheckBox"name="productCheckBox" value="">
                        <input type="hidden" id="productNameCheckBox"name="productNameCheckBox" value="">
                        <input type="hidden" id="productValueCheckBox"name="productValueCheckBox" value="">
                    </div> 
                    <div class="col-md-12" style="padding-bottom:15px">
                        <div class="row" style="float:left">
                            <a class="btn btn-default registerForm" align="right" href="{{asset('/sales')}}"> Cancelar </a>
                        </div>
                        <div class="row" style="float:right">
                            <a class="btn btn-default registerForm" align="right"  href="#" style="padding: 5px;background-color: #444;color:white" onclick="previousStep()"> <span class="glyphicon glyphicon-step-backward"></span>Anterior </a>
                        </div>
                    </div>
                </div>
                <div id="fourthStep" class="col-md-12 hidden">
                    <div class="col-md-12" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                        <div class="row" style="float:left">
                            <a class="btn btn-default registerForm" align="right" href="{{asset('/sales')}}"> Cancelar </a>
                        </div>
                        <div class="row" style="float:right">
                            <a class="btn btn-default registerForm" align="right" href="#" style="background-color: #444;color:white" onclick="nextStep('fourthStep', 'thirdStep')"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                            <a class="btn btn-info registerForm" align="right" href="#" style="padding: 5px" onclick="executeSale()"> Cotizar <span class="glyphicon glyphicon-step-forward"></span></a>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;">
                        <div class="wizard_activo registerForm titleDivBorderTop">
                            <span class="titleLink">Resumen</span>
                            <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="registerForm" for="documentResume"> Identificación</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" class="form-control registerForm" name="documentResume" id="documentResume" placeholder="Placa" value="{{ old('documentResume') }}" disabled="disabled">
                            </div>
                            <div class="form-group">
                                <label class="registerForm" for="mobile_phoneResume"> Teléfono Movil</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" class="form-control registerForm" name="mobile_phoneResume" id="mobile_phoneResume" placeholder="Modelo" value="{{ old('model') }}" disabled="disabled">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="registerForm" for="customerResume"> Cliente</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" class="form-control registerForm" name="customerResume" id="customerResume" placeholder="Modelo" value="{{ old('model') }}" disabled="disabled">
                            </div>
                            <div class="form-group">
                                <label class="registerForm" for="emailResume"> Email</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="email" class="form-control registerForm" name="emailResume" id="emailResume" placeholder="Año" value="{{ old('year') }}" disabled="disabled">
                            </div>                    
                        </div>
                        <div class="col-md-10 col-md-offset-1">
                            <table id="vehiclesTableResume" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th style="background-color:#b3b0b0">Item</th>
                                        <th style="background-color:#b3b0b0">Producto</th>
                                        <th style="background-color:#b3b0b0">Prima</th>
                                    </tr>
                                </thead>
                                <tbody id="vehiclesTableBodyResume">
                                </tbody>
                            </table>
                        </div>
                        <div id="taxTableResume" class="col-md-8 col-md-offset-2">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th style="background-color:#b3b0b0">S. de Bancos (3.5%)</th>
                                        <th style="background-color:#b3b0b0">S. Campesino (0.5%)</th>
                                        <th style="background-color:#b3b0b0">D. de Emisión</th>
                                        <th style="background-color:#b3b0b0">Subtotal</th>
                                        <th style="background-color:#b3b0b0">Iva</th>
                                        <th style="background-color:#b3b0b0">Total</th>
                                    </tr>
                                </thead>
                                <tbody id="taxTableBodyResume">
                                </tbody>
                            </table>
                        </div>
                        <div id="benefitsTable" class="col-md-4 col-md-offset-4 hidden">
                            <table class="table table-striped table-bordered">
                                <tbody>
                                    <tr id="benefitsTableBody">
                                        <td align="center" style="background-color:#b3b0b0;font-weight: bold">
                                            Beneficios Adicionales
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6 col-md-offset-3">
                            <center>
                                <label class="registerForm">
                                    Enviar Cotización al correo del cliente. <input type="checkbox" class="chkBoxSendQuotation" name="sendQuotation" data-toggle="toggle"  data-on="Enviar" data-off="No Enviar" data-width="100px" id="sendQuotation" value="" checked="checked">
                                </label>
                            </center>
                        </div>
                    </div>
                    <div class="col-md-12" style="padding-bottom:15px">
                        <div class="row" style="float:left">
                            <a class="btn btn-default registerForm" align="right" href="{{asset('/sales')}}"> Cancelar </a>
                        </div>
                        <div class="row" style="float:right">
                            <a class="btn btn-default registerForm" align="right" href="#" style="background-color: #444;color:white" onclick="nextStep('fourthStep', 'thirdStep')"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                            <a class="btn btn-info registerForm" align="right" href="#" style="padding: 5px" onclick="executeSale()"> Cotizar <span class="glyphicon glyphicon-step-forward"></span></a>
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-top:5px;padding-top:15px;">
                        <a class="btn btn-default registerForm hidden" align="left" href="/user" style="margin-left: -15px"> Cancelar </a>
                        <input type="submit" style="float:right;margin-right: -15px;padding: 5px" class="btn btn-info registerForm hidden" align="right" value="Guardar">

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

