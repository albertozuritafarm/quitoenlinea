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
                                    <td style="padding: 5px 0px 5px 14px; margin:60px 0px 20px 0px; font-size: 1.5em; font-weight: bold; display: inherit; color: rgb(96, 96, 96); text-align: center;"> <img src=" {{$message->embed(public_path('images/Email/Mail-Form_Vinculacion.png'))}}"  style="height:auto; max-width:600px; width:600px" alt="Formulario de Vinculación">
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
                                    <td style="padding: 30px 10px 30px 14px; display: inherit; color: rgb(96, 96, 96); text-align: justify;">
                                        <p style="margin: 0px;">Estimado <span style="font-weight: bold">{{$customer[0]->Cliente}}</span><span style="font-weight: bold">({{$company[0]->businessName}})</span></p>
                                        
                                        <p style="margin: 10px 0 0 0;">Gracias por confiar en Seguros Sucre – Tu Lugar Seguro. Para continuar con el proceso de vinculación ingresa al siguiente link: </p>
                                    </td>
                                </tr>
                                <tr bgcolor="Transparent" style="display: inherit;">
                                    <td style="padding: 0px 10px 30px 14px; display: inherit; color: rgb(96, 96, 96); text-align: center;">
                                        <p style="margin: 10px 0 0 0"><a href="https://tupolizaenlineatest.segurossucre.fin.ec/legalPersonVinculation/create?document={{$document}}&sales={{$sale}}&companys={{$companyDoc}}" target="_blank"><img src=" {{$message->embed(public_path('images/Email/btn.png'))}}" width="25%" alt="Click Aqui"></a></p>
                                    </td>
                                </tr>
                                <tr bgcolor="Transparent" style="display: inherit;">
                                    <td style="padding: 0px 10px 30px 14px; display: inherit; color: rgb(96, 96, 96); text-align: justify;">
                                        <p>Los documentos base que deberás adjuntar son los siguientes: </p>
                                            <ul>
                                                <li>Registro único de contribuyentes (RUC) o número análogo.</li>
                                                <li>Documento de identificación del representante legal o apoderado.</li>
                                                <li>Documento de identificación del cónyuge o conviviente del representante legal o apoderado, si aplica.</li>
                                                <li>Planilla de servicios básicos.</li>
                                                <li>Escritura de constitución y de sus reformas, de existirlas.</li>
                                                <li>Certificada del nombramiento del representante legal o apoderado.</li>
                                                <li>Nómina actualizada de accionistas o socios, en la que consten los montos de acciones o participaciones obtenida por el cliente en el órgano de control competente o registro competente que lo regule.</li>
                                                <li>Certificado de cumplimiento de obligaciones otorgado por el órgano de control competente.</li>
                                                <li>Estados financieros, mínimo de un año atrás. (Si la suma asegurada supera los USD 200.000,00 se deberá presentar los estados financieros auditados).</li>
                                            </ul>
                                        <p>Los documentos mencionados se deberán adjuntar en formato PDF o imagen, la información deberá ser legible.</p>
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