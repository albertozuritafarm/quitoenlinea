<script src="{{ assets('js/sales/R2/insured.js') }}"></script>
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
<div id="insuredStep">
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
            <div class="col-xs-12 col-md-2 wizard_medio"><div id="secondStepWizard" class="wizard_activo registerForm">Asegurado</div></div>
            <div class="col-xs-12 col-md-2 wizard_medio"><div id="thirdStepWizard" class="wizard_inactivo registerForm">Producto</div></div>
            <div class="col-xs-12 col-md-3 wizard_final"><div style="margin-right:-10px;" id="fourthStepWizard" class="wizard_inactivo registerForm">Resumen</div></div>
        </div>
        <div class="col-md-12">
            <div class="col-md-12">
                <div class="col-md-12" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                    <div class="row" style="float:left">
                        <a class="btn btn-default registerForm" align="right" href="{{asset('/sales')}}"> Cancelar </a>
                    </div>
                    <div class="row" style="float:right">
                        <a class="btn btn-default registerForm" align="right" href="#" style="background-color: #444;color:white" onclick="previousStep()"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                        <a class="btn btn-info registerForm" align="right" href="#" style="padding: 5px" onclick="validateInsuredForm()"> Siguiente <span class="glyphicon glyphicon-step-forward"></span></a>
                    </div>
                </div>
                <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;">
                    <div class="wizard_activo registerForm titleDivBorderTop">
                        <span class="titleLink">Datos del Cliente</span>
                        <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
                    </div>
                    <div id="customerAlert" class="alert alert-danger hidden registerForm titleDivBorderTop" style="margin-top:5px; border-radius: 0px !important">
                        <center><strong>¡Alerta!</strong> Revise los campos </center>
                    </div>
                    <form name="insuredForm" method="POST" action="/user" id="insuredForm">
                        {{ csrf_field() }}
                        <input type="hidden" name="sale_movement" id="sale_movement" value="{{$sale_movement}}">
                        <input type="hidden" name="sale_id" id="sale_id" value="{{$sale_id}}">
                        <input type="hidden" name="insurance_branch" id="insurance_branch" value="{{$insuranceBranch}}">
                        <input type="hidden" name="customer_id" id="customer_id" value="{{$customerId}}">
                        <input type="hidden" name="insured_id" id="insured_id" value="{{$insuredId}}">
                        <div class="col-md-6">
                            <div class="form-group form-inline">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Cédula</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <div class="form-inline">
                                    <input autocomplete="off" type="text" class="form-control registerForm" name="document_insured" id="document_insured" value="{{$customer->document}}" placeholder="Cédula" required="required"tabindex="1" style="width:89%">
                                    <button type="button" class="btn btn-info" onclick="documentBtn_insured()" style="width:10%"><span class="glyphicon glyphicon-search"></span></button>
                                    <div id="suggesstion-box"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" style="list-style-type:disc;" for="first_name"> Nombre(s)</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" class="form-control registerForm" name="first_name_insured" id="first_name_insured" placeholder="Nombre" value="{{ $customer->first_name }}" required="required" tabindex="3" disabled="disabled">
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="city"> Celular</label> <span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span><label class="own"><span class="glyphicon glyphicon-info-sign" style="color:#0099ff"><span class="own1" style="float:left">La contraseña debe tener: <br> 1) Un Numero <br> 2) Una Letra <br> 3) Un caracter Especial <br> 4) Debe tener al menos 7 caracteres</p></span></span></label>
                                <input type="text" class="form-control registerForm" name="mobile_phone_insured" id="mobile_phone_insured" placeholder="Nombre" value="{{ $customer->mobile_phone }}" required tabindex="5">
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="city"> Direccion</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" class="form-control registerForm" name="address_insured" id="address_insured" placeholder="Nombre" required tabindex="7" value="{{ $customer->address }}">
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="country"> Pais</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <select name="country_insured" id="country_insured" class="form-control registerForm" required tabindex="9">
                                    <option selected="true" value="">--Escoja Una---</option>
                                    @foreach($countries as $cou)
                                    <option @if($cou->id == $cusCountry->id) selected="true" @else @endif value="{{$cou->id}}">{{$cou->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="city"> Ciudad</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <select name="city_insured" class="form-control registerForm" id="city_insured" required tabindex="11">
                                    <option selected="true" value="{{$cusCity->id}}">{{$cusCity->name}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document_id"> Tipo Documento</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <select id="document_id_insured" name="document_id_insured" class="form-control registerForm" value="{{old('document_id')}}" required tabindex="2" disabled="disabled">
                                    <option selected="true" value="">--Escoja Una---</option>
                                    @foreach($documents as $doc)
                                    <option @if($doc->id == $customer->document_id) selected="true" @else @endif value="{{$doc->id}}">{{$doc->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="last_name_insured"> Apellido(s)</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="text" class="form-control registerForm" name="last_name_insured" id="last_name_insured" placeholder="Apellido" value="{{ $customer->last_name }}" required tabindex="4" disabled="disabled">
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="document"> Teléfono</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span><label class="own"><span class="glyphicon glyphicon-info-sign" style="color:#0099ff"><span class="own1" style="float:left">La contraseña debe tener: <br> 1) Un Numero <br> 2) Una Letra <br> 3) Un caracter Especial <br> 4) Debe tener al menos 7 caracteres</p></span></span></label>
                                <input type="text" class="form-control registerForm" name="phone_insured" id="phone_insured" placeholder="Cédula" value="{{ $customer->phone }}" required tabindex="6">
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="correo"> Correo</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <input type="email" class="form-control registerForm" name="email_insured" id="email_insured" placeholder="Correo" value="{{ $customer->email }}" required tabindex="8">
                                <p id="emailError" style="color:red;font-weight: bold"></p>    
                            </div>
                            <div class="form-group">
                                <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label class="registerForm" for="province"> Canton</label><span name="spanRequired" class="hidden" style="color:red;font-weight:bold"> * Requerido</span>
                                <select name="province_insured" class="form-control registerForm" id="province_insured" required tabindex="10">
                                    <option selected="true" value="{{$cusProvince->id}}">{{$cusProvince->name}}</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-12" style="padding-bottom:15px">
                    <div class="row" style="float:left">
                        <a class="btn btn-default registerForm" align="right" href="{{asset('/sales')}}"> Cancelar </a>
                    </div>
                    <div class="row" style="float:right">
                        <a  class="btn btn-default registerForm" align="right" href="#" style="background-color: #444;color:white" onclick="previousStep()"><span class="glyphicon glyphicon-step-backward"></span> Anterior </a>
                        <a class="btn btn-info registerForm" align="right" href="#" style="padding: 5px" onclick="validateInsuredForm()"> Siguiente <span class="glyphicon glyphicon-step-forward"></span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- Previous Step Form -->
<form action="{{asset('/sales/create/life')}}" method="post" class="hidden">
    @csrf
    <input type="hidden" name="customerId" value="{{$customerId}}">
    <input type="hidden" name="insuredId" value="{{$insuredId}}">
    <input type="submit" value="Submit" id="previousStepBtn">
</form> 