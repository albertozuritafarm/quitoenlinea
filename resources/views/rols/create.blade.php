@extends('layouts.app')

@section('content')
<script src="{{ assets('js/rol/create.js') }}"></script>
<style>
    .form-group{
        margin-top:25px !important;
        margin-bottom: 25px !important;
    }
    /*    .owners{
            width:500px;
            height:300px;
            border:0px solid #ff9d2a;
            margin:auto;
            float:left;
    
        }*/
    span.own1{
        background:#F8F8F8;
        border: 5px solid #DFDFDF;
        /*color: #717171;*/
        color: black;
        font-size: 12px;
        height: auto;
        width:310px;
        letter-spacing: 1px;
        line-height: 20px;
        position:absolute;
        text-align: left;
        /*text-transform: uppercase;*/
        top: auto;
        left:5px;
        display:none;
        padding:10px;
        border-radius: 10px;
        font-family: 'Roboto',sans-serif,Helvetica Neue,Arial !important;


    }

    label.own{
        margin:0px;
        /*float:left;*/
        position:relative;
        cursor:pointer;
    }

    label.own:hover span{
        display:block;
        z-index:9;
    }

    .inputRedFocus{
        border-color: red;
    }

</style>
<meta name="csrf-token" content="{{ csrf_token() }}" />
<div class="container-fluid" style="margin-top:15px; font-size:14px !important">
    <!--<div class="row justify-content-center border" style="margin-left:20%;">-->
    <div class="col-md-8 col-md-offset-2 border">
        <div class="row">
            <div class="col-xs-12 registerForm" style="margin:12px;">
                <center>
                    <h4 style="font-weight:bold">Registro de Nuevo Rol</h4>
                    <!--<h5>Datos del Cliente.</h5>-->
                </center>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-sm-4 wizard_inicial"><div style="margin-left:-10px" id="" class="wizard_inactivo registerForm"></div></div>
            <div class="col-md-4 col-sm-4 wizard_medio"><div id="" class="wizard_activo registerForm">ROL</div></div>
            <div class="col-md-4 col-sm-4 wizard_final"><div style="margin-right:-10px;" id="" class="wizard_inactivo registerForm"></div></div>
        </div>
        <br><br>
        @if (session('Error'))
        <div class="alert alert-warning">
            <create>
                <img src="{{ asset('images/iconos/warning.png')}}" alt="Girl in a jacket" style="width:40px;height:40px"> {{ session('Error') }}

            </create>
        </div>
        @endif
        @if (session('documentError'))
        <div class="alert alert-warning">
            <center>
                <img src="{{ asset('images/iconos/warning.png')}}" alt="Girl in a jacket" style="width:40px;height:40px"> {{ session('documentError') }}
            </center>
        </div>
        @endif
        <div id="errorMessageDiv" class="alert alert-danger hidden">
            <center>
                
            </center>
        </div>
        <div class="col-xs-12 col-md-12 border" style="padding-left:0px !important;margin-bottom: 5px">
            <div class="wizard_activo registerForm titleDiv">
                <a href="#" class="titleLink">Nuevo ROL</a>
                <span style="float:right;margin-right:1%;margin-top: 4px;" class="glyphicon glyphicon-chevron-down"></span>
            </div>
            <div class="col-md-12">
                <div class="col-md-12">
                    <div class="form-group">
                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label for="nameRol" style="font-weight:bold;font-size:14px">Nombre:</label>
                        <input type="text" class="form-control" id="nameRol" name="nameRol" placeholder="Nombre del Rol">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label for="rol_entity" style="font-weight:bold;font-size:14px">Entidad:</label>
                        <select class="form-control" name="rol_entity" id="rol_entity">
                            <option value="">-- Escoja Una--</option>
                            @foreach($rolEntity as $rol)
                            <option value="{{$rol->id}}">{{$rol->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label for="rol_type" style="font-weight:bold;font-size:14px">Tipo de Rol:</label>
                        <select class="form-control" name="rol_type" id="rol_type">
                            <option value="">-- Escoja Una--</option>
                            @foreach($rolType as $rol)
                            <option value="{{$rol->id}}">{{$rol->name}}</option>
                            @endforeach
                        </select>                    
                    </div>
                </div>
                <div class="col-md-12">
                    <span class="glyphicon glyphicon-asterisk" style="color:#0099ff">&ensp;</span><label style="font-weight:bold;font-size:14px">Permisos:</label>
                    <table id="newPaginatedTableNoOrdering" class="table table-striped row-border table-responsive hover stripe borderTable">
                        <thead>
                            <tr>
                                <th align="center" style="width:20%">Modulo</th>
                                <th align="center">Ver</th>
                                <th align="center">Editar</th>
                                <th align="center">Cancelar</th>
                                <th align="center">Crear</th>
                            </tr>
                        </thead>
                        <tbody>
                             @foreach($menuMain as $main)
                             <tr style="background-color: #44444496;">
                                <td align="left" style="font-weight: bold">{{$main->name}}</td>
                                <td align="center"><input type="checkbox" id="main_view_{{$main->id}}" class="chk" name="main_view_{{$main->id}}" value="{{$main->id}}_view" @if($main->sub_menu == 0) onchange="chkChange('main','view',{{$main->id}},'false')" @else onchange="chkChange('main','view',{{$main->id}},'true')" @endif ></td>
                                <td align="center"><input type="checkbox" id="main_edit_{{$main->id}}" class="chk" name="main_view_{{$main->id}}" value="{{$main->id}}_edit" @if($main->sub_menu == 0) onchange="chkChange('main','edit',{{$main->id}},'false')" @else onchange="chkChange('main','edit',{{$main->id}},'true')" @endif  @if((checkPermits($main->id, 'edit')) == false) disabled="disabled" @endif></td>
                                <td align="center"><input type="checkbox" id="main_cancel_{{$main->id}}" class="chk" name="main_view_{{$main->id}}" value="{{$main->id}}_cancel" @if($main->sub_menu == 0) onchange="chkChange('main','cancel',{{$main->id}},'false')" @else onchange="chkChange('main','cancel',{{$main->id}},'true')" @endif  @if((checkPermits($main->id, 'cancel')) == false) disabled="disabled" @endif></td>
                                <td align="center"><input type="checkbox" id="main_create_{{$main->id}}" class="chk" name="main_view_{{$main->id}}" value="{{$main->id}}_create" @if($main->sub_menu == 0) onchange="chkChange('main','create',{{$main->id}},'false')" @else onchange="chkChange('main','create',{{$main->id}},'true')" @endif  @if((checkPermits($main->id, 'create')) == false) disabled="disabled" @endif></td>
                             </tr>
                                 @foreach($menuSecondary as $secondary)
                                    @if($secondary->parent_id == $main->id)
                                        <!--<tr style="background-color: #44444438;color:white;text-shadow: 0px -1px 2px #000, 0px 1px 2px #000;">-->
                                        <tr style="background-color: #44444438;">
                                            <td align="left" style="font-weight: bold;padding-left: 25px">{{$secondary->name}}</td>
                                            <td align="center"><input type="checkbox" id="secondary_view_{{$main->id}}_{{$secondary->id}}" class="chk child_secondary_view_{{$main->id}}" name="secondary_view_{{$main->id}}_{{$secondary->id}}[]" value="{{$secondary->id}}_view" @if($secondary->sub_menu == 0) onchange="chkSecondaryChange('secondary','view',{{$secondary->id}},{{$main->id}},'false')" @else onchange="chkSecondaryChange('secondary','view',{{$secondary->id}},{{$main->id}},'true')" @endif ></td>
                                            <td align="center"><input type="checkbox" id="secondary_edit_{{$main->id}}_{{$secondary->id}}" class="chk child_secondary_edit_{{$main->id}}" name="secondary_edit_{{$main->id}}_{{$secondary->id}}[]" value="{{$secondary->id}}_edit" @if($secondary->sub_menu == 0) onchange="chkSecondaryChange('secondary','edit',{{$secondary->id}},{{$main->id}},'false')" @else onchange="chkSecondaryChange('secondary','edit',{{$secondary->id}},{{$main->id}},'true')" @endif  @if((checkPermits($secondary->id, 'edit')) == false) disabled="disabled" @endif></td>
                                            <td align="center"><input type="checkbox" id="secondary_cancel_{{$main->id}}_{{$secondary->id}}" class="chk child_secondary_cancel_{{$main->id}}" name="secondary_cancel_{{$main->id}}_{{$secondary->id}}[]" value="{{$secondary->id}}_cancel" @if($secondary->sub_menu == 0) onchange="chkSecondaryChange('secondary','cancel',{{$secondary->id}},{{$main->id}},'false')" @else onchange="chkSecondaryChange('secondary','cancel',{{$secondary->id}},{{$main->id}},'true')" @endif  @if((checkPermits($secondary->id, 'cancel')) == false) disabled="disabled" @endif></td>
                                            <td align="center"><input type="checkbox" id="secondary_create_{{$main->id}}_{{$secondary->id}}" class="chk child_secondary_create_{{$main->id}}" name="secondary_create_{{$main->id}}_{{$secondary->id}}[]" value="{{$secondary->id}}_create" @if($secondary->sub_menu == 0) onchange="chkSecondaryChange('secondary','create',{{$secondary->id}},{{$main->id}},'false')" @else onchange="chkSecondaryChange('secondary','create',{{$secondary->id}},{{$main->id}},'true')" @endif  @if((checkPermits($secondary->id, 'create')) == false) disabled="disabled" @endif></td>
                                        </tr>
                                        @foreach($menuThird as $third)
                                        @if($third->parent_id == $secondary->id)
                                            <tr>
                                                <td align="left" style="font-weight: bold;padding-left:50px">{{$third->name}}</td>
                                                <td align="center"><input type="checkbox" id="third_view_{{$main->id}}_{{$third->id}}"  class="chk child_third_view_{{$secondary->id}}" name="third_view_{{$main->id}}[]" value="{{$third->id}}_view" onchange="chkThirdChange('secondary','view',{{$third->id}},{{$main->id}},{{$secondary->id}})"></td>
                                                <td align="center"><input type="checkbox" id="third_edit_{{$main->id}}_{{$third->id}}"  class="chk child_third_edit_{{$secondary->id}}" name="third_edit_{{$main->id}}[]" value="{{$third->id}}_edit" onchange="chkThirdChange('secondary','edit',{{$third->id}},{{$main->id}},{{$secondary->id}})" @if((checkPermits($third->id, 'edit')) == false) disabled="disabled" @endif></td>
                                                <td align="center"><input type="checkbox" id="third_cancel_{{$main->id}}_{{$third->id}}"  class="chk child_third_cancel_{{$secondary->id}}" name="third_cancel_{{$main->id}}[]" value="{{$third->id}}_cancel" onchange="chkThirdChange('secondary','cancel',{{$third->id}},{{$main->id}},{{$secondary->id}})"  @if((checkPermits($third->id, 'cancel')) == false) disabled="disabled" @endif></td>
                                                <td align="center"><input type="checkbox" id="third_create_{{$main->id}}_{{$third->id}}"  class="chk child_third_create_{{$secondary->id}}" name="third_create_{{$main->id}}[]" value="{{$third->id}}_create" onchange="chkThirdChange('secondary','create',{{$third->id}},{{$main->id}},{{$secondary->id}})" @if((checkPermits($third->id, 'create')) == false) disabled="disabled" @endif></td>
                                            </tr>
                                        @else
                                        @endif
                                        @endforeach
                                    @else
                                    @endif
                                 @endforeach
                             @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div id="">
            
            <div class="col-md-12" style="margin-top:5px;padding-top:15px;padding-bottom:15px">
                <div class="col-md-1">
                    <a class="btn btn-default registerForm" align="right" href="{{asset('/rol')}}" style="margin-left: -30px;"> Cancelar </a>
                </div>
                <div class="">
                    <input id="btnSubmit" type="submit" class="btn btn-primary registerForm" value="Guardar" onclick="submitForm()" style="float:right;margin-right: -15px">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
