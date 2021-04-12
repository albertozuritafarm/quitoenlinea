@extends('layouts.email')

@section('content')
<tr class="ui-droppable">
    <td style="padding: 0px">
        <table border="0" cellpadding="0" cellspacing="0" class="container" id="3" style="width:600px">
            <tbody>
                <tr>
                    <td class="container-column ui-droppable" style="width:100%;max-width:600px;vertical-align:top">
                        <table border="0" cellpadding="0" cellspacing="0" class="component title-component" i18n="MODULES-TXT_HEAD_TITLE" id="4" style="width:100%">
                            <tbody style="display: block; border: 0px none rgb(96, 96, 96); border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px; background-color: rgba(0, 0, 0, 0);">
                                <tr bgcolor="Transparent" style="display: inherit;">
                                    <td style="padding: 0px 0px 0px 14px; margin:20px 0px 0px 0px; font-size: 1.5em; font-weight: bold; display: inherit; color: rgb(96, 96, 96); text-align: center;background-color: rgb(217, 217, 217)"> <img src=" {{$message->embed(public_path('images/logo_seguros_sucre.jpg'))}}" alt="Logo"> </td>
                                    <td style="padding: 0px 0px 5px 14px; margin:0px 0px 20px 0px; font-size: 1.5em; font-weight: bold; display: inherit; color: rgb(96, 96, 96); text-align: center;background-color: rgb(217, 217, 217); border-top: 1px solid #5496c3">Nuevo Comentario:</td>
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
                                    <td style="padding: 5px 10px 80px 14px; display: inherit; color: rgb(96, 96, 96); text-align: left;">
                                        <p style="margin: 0px;">Hola {{$user}}, Tienes un nuevo respuesta sobre el ticket.</p>
                                        <hr>
                                        <p><strong>Ticket:</strong> {{$ticketId}}. </p>
                                        <p><strong>Modulo:</strong> {{$menu}}. </p>
                                        <p><strong>Tipo Ticket:</strong> {{$tipo}}. </p>
                                        <p><strong>Categoria:</strong> {{$typeDetail}}. </p>
                                        <p><strong>Titulo:</strong> {{$title}}. </p>
                                        <p><strong>Comentario:</strong> </p>
                                        {!!$description!!}
                                        <hr>
                                        <p>Ingresa <a href="{{asset('')}}ticket/detail/{{$ticketId}}">Aqui</a> para ver el ticket</p>
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
@endsection