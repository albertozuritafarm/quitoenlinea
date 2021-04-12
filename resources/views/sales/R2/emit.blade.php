<script src="{{ assets('js/sales/R2/emit.js') }}"></script>
<div class="col-md-8 col-md-offset-2 border">
    <div class="row">
        <div class="col-xs-12 registerForm" style="margin:12px;">
            <center>
                <h4 style="font-weight:bold">Emitir la Venta</h4>
            </center>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-3 wizard_inicial"><div style="margin-left:-10px" class="wizard_inactivo registerForm"></div></div>
        <div class="col-xs-12 col-md-3 wizard_medio"><div id="firstStepWizard" class="wizard_inactivo registerForm">Resumen</div></div>
        <div class="col-xs-12 col-md-3 wizard_medio"><div id="secondStepWizard" class="wizard_activo registerForm">Emisión</div></div>
        <div class="col-xs-12 col-md-3 wizard_final"><div style="margin-right:-10px" class="wizard_inactivo registerForm"></div></div>
    </div>
    <div id="secondStep" class="col-md-12">
        <div class="col-md-12">
            <div class="col-md-12" style="margin-top:5px;padding-top:15px;">
                <div class="row" style="float:left">
                    <a class="btn btn-default registerForm" align="right" href="{{asset('/sales')}}"> Cancelar </a>
                </div>
                <div class="row" style="float:right">
                    <a onclick="previous('{{$saleId}}','{{$document}}')" class="btn btn-back registerForm" align="right" href="#"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                    <a class="btn btn-info registerForm" align="right"  href="#" onclick="validateSecondStep()"> Pagar y Emitir </a>
                </div>
            </div>
            <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;">
                <div class="wizard_activo registerForm titleDivBorderTop" onclick="fadeToggle('secondStepDiv')">
                    <a href="#" class="titleLink">Datos de la Poliza</a>
                    <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                </div>
                <div id="secondStepDiv" class="col-md-12" style="padding-top: 25px;padding-bottom: 25px;">
                    <form>
                        <input type="hidden" id="saleId" name="saleId" value="{{$saleId}}">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="beginDate"> Fecha Inicio Vigencia</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="date" class="form-control" name="beginDate" id="beginDate" placeholder="Correo" value="{{$now}}" style="line-height:14px" onchange="removeInputRedFocus('beginDate'), changeEndDate(this.value)" disabled="disabled">
                            </div>
                            <div class="form-group col-md-6">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="endDate"> Fecha Fin Vigencia</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="date" class="form-control" name="endDate" id="endDate" placeholder="Correo" value="{{$next}}" style="line-height:14px" onchange="removeInputRedFocus('endDate')" disabled="disabled">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-12" style="padding-bottom:15px">
                <div class="row" style="float:left">
                    <a class="btn btn-default registerForm" align="right" href="{{asset('/sales')}}"> Cancelar </a>
                </div>
                <div class="row" style="float:right">
                    <a onclick="previous('{{$saleId}}','{{$document}}')" class="btn btn-back registerForm" href="#"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                    <a class="btn btn-info registerForm" href="#" onclick="validateSecondStep()"> Pagar y Emitir </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="hidden">
    <form action="{{asset('/sales/payments/create')}}" method="POST">
        {{ csrf_field() }}
        <input type="hidden" id="chargeId" name="chargeId" value="">
        <input id="formBtn" type="submit" class="btn btn-info" style="float:right" value="SI">
    </form>
</div>