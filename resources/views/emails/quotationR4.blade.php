@extends('layouts.email')

@section('content')
<tr class="ui-droppable">
    <td style="padding: 0px">
        <table border="0" cellpadding="0" cellspacing="0" class="container" id="3" style="width:700px">
            <tbody>
                <tr>
                    <td class="container-column ui-droppable" style="width:100%;max-width:600px;vertical-align:top">
                        <table border="0" cellpadding="0" cellspacing="0" class="component title-component" i18n="MODULES-TXT_HEAD_TITLE" id="4" style="width:100%">
                            <tbody style="display: block; border: 0px none rgb(96, 96, 96); border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px; background-color: rgba(0, 0, 0, 0);">
                                <tr bgcolor="Transparent" style="display: inherit;">
                                    <td style="padding: 5px 0px 5px 14px; margin:60px 0px 20px 0px; font-size: 1.5em; font-weight: bold; display: inherit; color: rgb(96, 96, 96); text-align: center;"> <img src="{{$message->embed(public_path('images/Email/Mail_Cotizacion.png'))}}" width="100%" alt="Cotización"> </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </td>
</tr>
<tr class="ui-droppable">
    <td style="padding: 0px">
        <table border="0" cellpadding="0" cellspacing="0" class="container" id="6" style="width:600px">
        <tbody>
            <tr>
                <td class="container-column ui-droppable" style="width:100%;max-width:600px;vertical-align:top">
                    <table border="0" cellpadding="0" cellspacing="0" class="component text-component" i18n="MODULES-TXT_BEGIN_AND_CUSTUMIZE" id="7" style="width:100%">
                        <tbody style="display: block; border: 0px none rgb(96, 96, 96); border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px; background-color: rgba(0, 0, 0, 0);">
                            <tr bgcolor="Transparent" style="display: inherit;">
                                <td style="padding: 5px 0px 5px 14px; margin:15px 0px 20px 0px; font-size: 1.5em; font-weight: bold; display: inherit; color: rgb(96, 96, 96); text-align: left;"> <img class ="logo_producto" src="{{$message->embed(public_path('images/Email/Vive_Seguro.png'))}}" width="30%" alt="Casa Habitación"> </td>
                            </tr>
                            <tr bgcolor="Transparent" style="display: inherit;">                                       
                                <td style="padding: 5px 10px 5px 14px; display: inherit; color: rgb(96, 96, 96); text-align: justify;">
                                    <p style="margin: 0px;">Estimado <span style="font-weight: bold;color:rgb(0, 59, 113)">{{$customer[0]->Cliente}}</span></p>
                                    <p style="margin: 10px 0 0 0;">En el siguiente correo ponemos en tu conocimiento la cotización para tu póliza de <span style="font-weight: bold;color:rgb(0, 59, 113)">Casa Habitación</span> generada el día <span style="font-weight: bold;color:rgb(0, 59, 113)">{{$customer[0]->date}}</span> con Seguros Sucre.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
        </table>
    </td>
</tr>
<tr class="ui-droppable">
    <td style="padding: 0px">
        <table class="" style="width:500px; margin-left:50px;">
            <thead>
                <tr>
                    <th width="70%" style="background-color:#003B71; text-align:left; border-top: 0px; border-bottom: 0px; border-left: 5px solid #CF3339; color:white; padding:0 0 0 15px">Producto</th>
                    <th width="30%" style="background-color:#003B71; text-align:center; border-top: 0px; border-bottom: 0px; border-left: 2px solid white; color:white; padding:0px">Prima</th>
                </tr>
            </thead>
            <tbody> 
                {!! $vehiTable !!}
                {!! $taxTable !!}
            </tbody>
        </table>
    </td>
</tr>
<tr class="ui-droppable">
    <td style="padding: 0px">
        <table border="0" cellpadding="0" cellspacing="0" class="container" id="3" style="width:700px">
            <tbody>
                <tr>
                    <td class="container-column ui-droppable" style="width:100%;max-width:600px;vertical-align:top">
                        <table border="0" cellpadding="0" cellspacing="0" class="component title-component" i18n="MODULES-TXT_HEAD_TITLE" id="4" style="width:100%">
                            <tbody style="display: block; border: 0px none rgb(96, 96, 96); border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px; background-color: rgba(0, 0, 0, 0);">
                                <tr bgcolor="Transparent" style="display: inherit;">
                                    <td style="padding: 5px 0px 5px 14px; margin:60px 0px 20px 0px; font-size: 1.5em; font-weight: bold; display: inherit; color: rgb(96, 96, 96); text-align: center;"> <img src="{{$message->embed(public_path('images/Email/Mail-Pie-de-pagina.png'))}}" width="100%" alt="Footer"> </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </td>
</tr>
@endsection