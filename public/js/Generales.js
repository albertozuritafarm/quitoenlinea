function MostrarError(NombreControl, Mensaje, MarcarElementoF)
{
    MarcarElementoF || (MarcarElementoF = true);
    var Control = $("#" + NombreControl);
    var DIV = $("#DIVError" + NombreControl);
    var L = $("#LError" + NombreControl);
    L.text(Mensaje);
    DIV.slideDown();
    if (MarcarElementoF) {
        Control.addClass("FiloError");
    }
}

function OcultarError(NombreControl, DesMarcarElementoF)
{
    DesMarcarElementoF || (DesMarcarElementoF = true);
    var Control = $("#" + NombreControl);
    var DIV = $("#DIVError" + NombreControl);
    var L = $("#LError" + NombreControl);
    L.text("");
    DIV.slideUp();
    if (DesMarcarElementoF) {
        Control.removeClass("FiloError");
    }
}

function MiConfirm(Texto, Tipo, NombreBotonOK, NombreBotonCancel, FuncionSi, FuncionNo)
{
    Tipo || (Tipo = 'INFO');
    var Resultado = false;
    NombreBotonOK || (NombreBotonOK = 'Aceptar');
    NombreBotonCancel || (NombreBotonCancel = 'Cancelar');
    FuncionSi || (FuncionSi = function () {});
    FuncionNo || (FuncionNo = function () {});
    var ColorBarra = '';
    var Titulo = '';
    var Imagen = '';
    var Resultado = false;
    Texto = Texto.replace(/\n/g, "<br />");
    var Rand = Math.floor(Math.random() * 1000).toString();
    var Id_Div = "DIVMiConfirm_" + Rand;
    var Id_Img = "IMGMiConfirm_" + Rand;
    var fue_boton = false;
    switch (Tipo)
    {
        case 'INFO':
        {
            Imagen = 'info';
            ColorBarra = '#6ADDF4';
            Titulo = 'Información';
            break;
        }
        case 'WARNING':
        {
            Imagen = 'warning';
            ColorBarra = '#FFBC67';
            Titulo = 'Advertencia';
            break;
        }
        case 'ERROR':
        {
            Imagen = 'error';
            ColorBarra = '#FC727A';
            Titulo = 'Error';
            break;
        }
        case 'OK':
        {
            Imagen = 'ok';
            ColorBarra = '#5CB85C';
            Titulo = 'Notificación';
            break;
        }
    }
    $("div [id^='DIVMiConfirm_']").empty().remove();
    $(':root body').append('<div style="padding:10px; margin:15px 10px; display:none; border:solid 1px ' + ColorBarra + '; max-width:460px; font-size:12px; font-family:\'Roboto\',sans-serif,Helvetica Neue,Arial;" id="' + Id_Div + '"><div class="col-xs-12 col-sm-2" align="center" style="padding-bottom:12px"><img id="' + Id_Img + '" src="../images/iconos/' + Imagen + '.png" width="36px" height="36px" /></div><div class="col-xs-12 col-sm-10">' + Texto + '</div></div>');
    $("#" + Id_Img).on("error", function () {
        $(this).attr("src", "images/iconos/" + Imagen + ".png");
    });
    $("#" + Id_Div).dialog({
        title: Tipo,
        show: {effect: "drop", direction: "up", duration: 440},
        hide: {effect: "drop", direction: "up", duration: 220},
        width: 'auto',
        position: {my: "center top+40", at: "top", of: window, collision: "none none"},
        resizable: false,
        modal: true,
        fluid: true,
        closeOnEscape: false,
        buttons: [
            {text: NombreBotonOK, "class": 'BotonMiDialog', click: function () {
                    Resultado = true;
                    fue_boton = true;
                    $(this).dialog("close");
                }},
            {text: NombreBotonCancel, "class": 'BotonMiDialog', click: function () {
                    Resultado = false;
                    fue_boton = true;
                    $(this).dialog("close");
                }}],
        close: function (event, ui) {
            $(this).dialog("destroy");
            $("#" + Id_Div).empty().remove();
            if (fue_boton) {
                if (Resultado) {
                    FuncionSi();
                } else {
                    FuncionNo();
                }
            }
        }
    });
    $("#" + Id_Div).dialog("option", "title", Titulo);
    $("#" + Id_Div).parent().find(".ui-dialog-titlebar").css('background', ColorBarra).css('border', ColorBarra).css('color', '#FFF').css('font-size', '12px').css('font-family', '"Roboto",sans-serif,Helvetica Neue,Arial');
}

function MiAlert(Texto, Tipo, NombreBoton, FuncionSi, Raiz, FuncionOn)
{
    Tipo || (Tipo = 'INFO');
    NombreBoton || (NombreBoton = 'Aceptar');
    FuncionSi || (FuncionSi = function () {});
    FuncionOn || (FuncionOn = function () {});
    Raiz || (Raiz = false);
    var ColorBarra = '';
    var Titulo = '';
    var Imagen = '';
    var Resultado = false;
    Texto = Texto.replace(/\n/g, "<br />");
    var Rand = Math.floor(Math.random() * 1000).toString();
    var Id_Div = "DIVMiAlert_" + Rand;
    var Id_Img = "IMGMiAlert_" + Rand;
    switch (Tipo)
    {
        case 'INFO':
        {
            Imagen = 'info';
            ColorBarra = '#6ADDF4';
            Titulo = 'Información';
            break;
        }
        case 'WARNING':
        {
            Imagen = 'warning';
            ColorBarra = '#FFBC67';
            Titulo = 'Advertencia';
            break;
        }
        case 'ERROR':
        {
            Imagen = 'error';
            ColorBarra = '#FC727A';
            Titulo = 'Error';
            break;
        }
        case 'OK':
        {
            Imagen = 'ok';
            ColorBarra = '#5CB85C';
            Titulo = 'Notificación';
            break;
        }
    }
    $("div [id^='DIVMiAlert_']").empty().remove();
    if (Raiz) {
        var path_0 = '';
    } else {
        var path_0 = '../';
    }
    $(':root body').append('<div style="padding:10px; margin:20px 10px; display:none; border:solid 1px ' + ColorBarra + '; max-width:500px; font-size:12px; font-family:\'Roboto\',sans-serif,Helvetica Neue,Arial;" id="' + Id_Div + '"><div class="col-xs-12 col-sm-2" align="center" style="padding-bottom:12px"><img id="' + Id_Img + '" src="' + path_0 + 'images/iconos/' + Imagen + '.png" width="36px" height="36px" /></div><div class="col-xs-12 col-sm-10">' + Texto + '</div></div>');
    $("#" + Id_Img).on("error", function () {
        $(this).attr("src", "images/iconos/" + Imagen + ".png");
    });

    $("#" + Id_Div).dialog({
        title: Tipo,
        show: {effect: "drop", direction: "up", duration: 440},
        hide: {effect: "drop", direction: "up", duration: 220},
        width: 'auto',
        position: {my: "center top+40", at: "top", of: window, collision: "none none"},
        resizable: false,
        modal: true,
        fluid: true,
        closeOnEscape: false,
        buttons: [
            {text: NombreBoton, "class": 'BotonMiDialog', click: function () {
                    $(this).dialog("close");
                }}],
        close: function (event, ui) {
            $(this).dialog("destroy");
            $("#" + Id_Div).empty().remove();
            FuncionSi();
        }
    });
    $("#" + Id_Div).dialog("option", "title", Titulo);
    $("#" + Id_Div).parent().find(".ui-dialog-titlebar").css('background', ColorBarra).css('border', ColorBarra).css('color', '#FFF').css('font-size', '12px').css('font-family', '"Roboto",sans-serif,Helvetica Neue,Arial');
    FuncionOn();
}

function ShowFromLeft(div, callback) {
    callback || function () {};
    $("#" + div).effect("slide", {direction: 'left', mode: 'show'}, 300, callback);
}
function ShowFromRight(div, callback) {
    callback || function () {};
    $("#" + div).effect("slide", {direction: 'right', mode: 'show'}, 300, callback);
}
function HideToLeft(div, callback) {
    callback || function () {};
    $("#" + div).effect("slide", {direction: 'left', mode: 'hide'}, 300, callback);
}
function HideToRight(div, callback) {
    callback || function () {};
    $("#" + div).effect("slide", {direction: 'right', mode: 'hide'}, 300, callback);
}

function ApendizarRoot(div) {
    $("#" + div).draggable().modal("show");
    $(".modal-open").css("padding-right", 0);
}

function var_dump(obj) {
    var o = '';
    for (var i in obj) {
        o += i + ": " + obj[i] + "\n";
    }
    alert(o);
} // ESTA ES SOLO PARA DESARROLLO. LUEGO QUITAR.

function SoloNumeros(event)
{
    var CodCaracter = event.keyCode;
    if (event.shiftKey && (CodCaracter >= 48 && CodCaracter <= 57)) {
        return false;
    } else if ((CodCaracter >= 48 && CodCaracter <= 57) || (CodCaracter >= 35 && CodCaracter <= 40) ||
            (CodCaracter >= 96 && CodCaracter <= 105) || (CodCaracter == 8) || (CodCaracter == 9) || (CodCaracter == 45) || (CodCaracter == 46))
    {
        return true;
    } else {
        return false;
    }
}

function SoloLetras(event)
{
    var CodCaracter = event.keyCode;
    if ((CodCaracter >= 35 && CodCaracter <= 40) || (CodCaracter >= 65 && CodCaracter <= 90) || (CodCaracter == 8) || (CodCaracter == 9) || (CodCaracter == 45) || (CodCaracter == 46)) {
        return true;
    } else {
        return false;
    }
}

function SoloComaPuntoyNumeros(event)
{
    var CodCaracter = event.keyCode;
    if (event.shiftKey && (CodCaracter == 188 || CodCaracter == 190 || CodCaracter == 110)) {
        return false;
    } else if (SoloNumeros(event) || (CodCaracter == 188) || (CodCaracter == 190) || (CodCaracter == 110)) {
        return true;
    } else {
        return false;
    }
}

function SoloComaPuntoSignoyNumeros(event)
{
    var CodCaracter = event.keyCode;
    if (event.shiftKey && (CodCaracter == 109 || CodCaracter == 173)) {
        return false;
    } else if (SoloComaPuntoyNumeros(event) || (CodCaracter == 109) || (CodCaracter == 173)) {
        return true;
    } else {
        return false;
    }
}

function MostrarEspera()
{
    if (!$(":root body #DIV_Temporal_Espera").length)
    {
        $(':root body').append('<div id="DIV_Temporal_Espera" style="display:none"></div>');
        $("#DIV_Temporal_Espera").html('<div style="position:fixed; top:0px; left:0px; width:100%; height:100%; background-color:#000; filter: alpha(opacity=50); opacity: .5; z-index:10000000;"></div><div align="center" style="overflow: hidden; position: fixed; left: 50%; top: 50%; margin-left: -20px; margin-top: -20px; z-index:10000001;"><img title="Espere, por favor..." width="40" height="40" src="../images/espere.gif"></div>');
    }
    $(":root body #DIV_Temporal_Espera").show();
}

function OcultarEspera() {
    $(":root body #DIV_Temporal_Espera").hide();
}

function ObtenerValorSync(ps, Opcion, Valor, ConEspera)
{
    var respuesta = null;
    Opcion || (Opcion = "");
    Valor || (Valor = null);
    ConEspera || (ConEspera = false);
    var parametros = {"Opcion": Opcion, "Valor": Valor};
    $.ajax({
        data: parametros,
        dataType: 'html',
        url: ps,
        type: 'post',
        async: false,
        beforeSend: function () {
            if (ConEspera) {
                MostrarEspera();
            }
        },
        success: function (response) {
            respuesta = response;
            if (ConEspera) {
                OcultarEspera();
            }
        },
        error: function (jqXHR, tS, eT) {
            alert('ERROR Ajax. Status: ' + jqXHR.status + ' TextStatus: ' + tS + ' Error: ' + eT);
        }
    });
    return respuesta;
}

function ObtenerValorASync(ps, Opcion, Valor, ConEspera)
{
    var respuesta = null;
    Opcion || (Opcion = "");
    Valor || (Valor = null);
    ConEspera || (ConEspera = false);
    var parametros = {"Opcion": Opcion, "Valor": Valor};
    $.ajax({
        data: parametros,
        dataType: 'html',
        url: ps,
        type: 'post',
        async: true,
        beforeSend: function () {
            if (ConEspera) {
                MostrarEspera();
            }
        },
        success: function (response) {
            respuesta = response;
            if (ConEspera) {
                OcultarEspera();
            }
        },
        error: function (jqXHR, tS, eT) {
            alert('ERROR Ajax. Status: ' + jqXHR.status + ' TextStatus: ' + tS + ' Error: ' + eT);
        }
    });
    return respuesta;
}

function EnviarFormulario(ps, DivDestino, IdForm, Opcion, ConEspera)
{
    var Form = $("#" + IdForm).serialize();
    Opcion || (Opcion = "");
    ConEspera || (ConEspera = false);
    $.ajax({
        data: Form + '&Opcion=' + Opcion,
        dataType: 'html',
        url: ps,
        type: 'post',
        beforeSend: function () {
            $("#" + DivDestino + " > *").remove();
            if (ConEspera) {
                MostrarEspera();
            }
        },
        success: function (response) {
            $("#" + DivDestino).empty().html(response);
            if (ConEspera) {
                OcultarEspera();
            }
        },
        error: function (jqXHR, tS, eT) {
            console.log(jqXHR);
            alert('ERROR Ajax. Status: ' + jqXHR.status + ' TextStatus: ' + tS + ' Error: ' + eT);
        }
    });
}

function CargarPlantilla(ps, DivDestino, Opcion, Valor, Funcion, ConEspera)
{
    Opcion || (Opcion = null);
    Valor || (Valor = null);
    Funcion || (Funcion = null);
    ConEspera || (ConEspera = false);
    var parametros = {"Opcion": Opcion, "Valor": Valor};
    $.ajax({
        data: parametros,
        dataType: 'html',
        url: ps,
        type: 'post',
        beforeSend: function () {
            $("#" + DivDestino + " > *").remove();
            if (ConEspera) {
                MostrarEspera();
            }
        },
        success: function (response) {
            $("#" + DivDestino).empty().html(response);
            if (ConEspera) {
                OcultarEspera();
            }
            if (Funcion) {
                Funcion();
            }
        },
        error: function (jqXHR, tS, eT) {
            alert('ERROR Ajax. Status: ' + jqXHR.status + ' TextStatus: ' + tS + ' Error: ' + eT);
        }
    });
}

function ActualizarValueSinForm(ps, IdInput, Opcion, Valor, ConEspera)
{
    Opcion || (Opcion = "");
    Valor || (Valor = null);
    ConEspera || (ConEspera = false);
    var parametros = {"Opcion": Opcion, "Valor": Valor};
    $.ajax({
        data: parametros,
        dataType: 'text',
        url: ps,
        type: 'post',
        beforeSend: function () {
            if (ConEspera) {
                MostrarEspera();
            }
        },
        success: function (response) {
            $("#" + IdInput).val(response);
            if (ConEspera) {
                OcultarEspera();
            }
        },
        error: function (jqXHR, tS, eT) {
            alert('ERROR Ajax. Status: ' + jqXHR.status + ' TextStatus: ' + tS + ' Error: ' + eT);
        }
    });
}

function SimulaHabilitar(IdForm)
{
    var Vale = true;
    $("#" + IdForm + " *:disabled").each(function ()
    {
        Vale = true;
        if ($(this).attr("type") == "radio" || $(this).attr("type") == "checkbox") {
            if ($(this).prop("checked")) {
                Vale = true;
            } else {
                Vale = false;
            }
        }
        if (Vale) {
            $("#" + IdForm).append('<input type="hidden" data-borrar="borrar_simulados" id="' + $(this).attr("id") + '" name="' + $(this).attr("name") + '" value="' + $(this).val() + '">');
        }
    });
}

function Vacio(IdInput) {
    return $("#" + IdInput).val().trim() === "";
}

function EMailValido(email)
{
    var Expresion = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (!Expresion.test(email)) {
        return false;
    } else {
        return true;
    }
}

function ComoDinero(Numero) {
    return parseFloat(Redondea(Numero, 2)).toFixed(2);
}

function currencyFormat(id) {
        var input = document.getElementById(id);
        var currencyInput = input.value.replace(/,/g, ''); 
        document.getElementById(id).value = currency(currencyInput, { separator: ',' }).format();
}

function Como4Decimales(Numero) {
    return parseFloat(Redondea(Numero, 4)).toFixed(4);
}

function Redondea(Numero, CantDecimales) {
    return Number(Math.round(Numero + 'e' + CantDecimales) + 'e-' + CantDecimales);
}

function esEntero(n) {
    if (isNaN(n)) {
        return false;
    } else {
        if (n % 1 == 0) {
            return true;
        } else {
            return false;
        }
    }
}

function RealPositivoValido(Id) {
    var C = $("#" + Id).val();
    return (!isNaN(C) && !Vacio(Id) && parseFloat(C) >= 0 && C.indexOf('.') != C.length - 1);
}

function RealValido(Id) {
    var C = $("#" + Id).val();
    return (!isNaN(C) && !Vacio(Id) && C.indexOf('.') != C.length - 1);
}

function NumeroHasta2Decimales(Id)
{
    var C = $("#" + Id).val();
    var R = true;
    if (!RealPositivoValido(Id)) {
        R = false;
    } else {
        var PP = C.indexOf('.');
        var LC = C.length;
        if (PP != -1 && (PP < (LC - 3) || PP == (LC - 1))) {
            R = false;
        }
    }
    return R;
}

function NumeroHasta4Decimales(Id)
{
    var C = $("#" + Id).val();
    var R = true;
    if (!RealPositivoValido(Id)) {
        R = false;
    } else {
        var PP = C.indexOf('.');
        var LC = C.length;
        if (PP != -1 && (PP < (LC - 5) || PP == (LC - 1))) {
            R = false;
        }
    }
    return R;
}

function RealHasta4Decimales(Id)
{
    var C = $("#" + Id).val();
    var R = true;
    if (!RealValido(Id)) {
        R = false;
    } else {
        var PP = C.indexOf('.');
        var LC = C.length;
        if (PP != -1 && (PP < (LC - 5) || PP == (LC - 1))) {
            R = false;
        }
    }
    return R;
}

function RealHasta2Decimales(Id)
{
    var C = $("#" + Id).val();
    var R = true;
    if (!RealValido(Id)) {
        R = false;
    } else {
        var PP = C.indexOf('.');
        var LC = C.length;
        if (PP != -1 && (PP < (LC - 3) || PP == (LC - 1))) {
            R = false;
        }
    }
    return R;
}

function DosDigitos(x) {
    if (('' + x).length == 1) {
        return '0' + x;
    } else {
        return '' + x;
    }
}

function CedulaValida(cedula)
{
    var array = cedula.split("");
    var num = array.length;
    if (num == 9) {
        return true;
    } else {
        return false;
    }
}

function RUCValido(RUC)
{
    var Resultado = true;
    if (RUC.length != 13 || isNaN(parseInt(RUC))) {
        Resultado = false;
    } else
    {
        for (var index = 0; index < 13; index++) {
            if (isNaN(RUC.substr(index, 1))) {
                Resultado = false;
            }
        } // TODO NUMERICO
        if (parseInt(RUC.substr(0, 2)) > 24) {
            Resultado = false;
        } // PROVINCIA MENOR QUE 22
        if (RUC.substr(2, 1) == '7' || RUC.substr(2, 1) == '8') {
            Resultado = false;
        } // TERCER DIGITO INVALIDO
        if (RUC.substr(12, 1) == '0') {
            Resultado = false;
        } // ULTIMO DIGITO INVALIDO
        if (parseInt(RUC.substr(2, 1)) < 6) // PERSONA NATURAL
        {
            if (!CedulaValida(RUC.substr(0, 10))) {
                Resultado = false;
            }
        } else if (RUC.substr(2, 1) == '9') // JURIDICA Y EXTRANJERA
        {
            var Suma = 0;
            var ArrRef = [4, 3, 2, 7, 6, 5, 4, 3, 2];
            for (var index = 0; index < 9; index++) {
                Suma += ArrRef[index] * parseInt(RUC.substr(index, 1));
            }
            var DV = 11 - (Suma % 11);
            if (DV == 11) {
                DV = 0;
            }
            if (RUC.substr(9, 1) != DV) {
                Resultado = false;
            }
        } else if (RUC.substr(2, 1) == '6') // PUBLICOS
        {
            var Suma = 0;
            var ArrRef = [3, 2, 7, 6, 5, 4, 3, 2];
            for (var index = 0; index < 8; index++)
            {
                Suma += ArrRef[index] * parseInt(RUC.substr(index, 1));
            }
            var DV = 11 - (Suma % 11);
            if (DV == 11) {
                DV = 0;
            }
            if (RUC.substr(8, 1) != DV) {
                Resultado = false;
            }
        }
    }
    return Resultado;
}

function LoginValido(login)
{
    var Expresion = /^[a-z\d_\-.]{1,100}$/i;
    if (!Expresion.test(login)) {
        return false;
    } else {
        return true;
    }
}

function FicheroCorrecto(IdFile, CadExt)
{
    var resultado = false;
    var Ext = ($("#" + IdFile).val()).split(".").pop().toUpperCase();
    var ArrExt = CadExt.split('*');
    for (var index = 0; index < ArrExt.length; index++) {
        if (Ext == ArrExt[index]) {
            resultado = true;
        }
    }
    return resultado;
}

function normalize(term)
{
    var accentMap = {"á": "a", "é": "e", "í": "i", "ó": "o", "ú": "u"};
    var ret = "";
    for (var i = 0; i < term.length; i++) {
        ret += accentMap[term.charAt(i)] || term.charAt(i);
    }
    return ret;
}
;

function validateRuc(ruc) {
    if (ruc.length == 13) {
        if (isNaN(ruc)) {
            return false;
        } else {
            return true;
        }
    } else {
        return false;
    }
}

(function ($) {
    $.widget("custom.combobox", {
        _create: function () {
            this.wrapper = $("<span>").addClass("custom-combobox").insertAfter(this.element);
            this.element.hide();
            this._createAutocomplete();
            this._createShowAllButton();
        },

        _createAutocomplete: function () {
            var selected = this.element.children(":selected"),
                    value = selected.val() ? selected.text() : "";

            this.input = $("<input>")
                    .appendTo(this.wrapper).val(value).attr("title", "").addClass("custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left")
                    .autocomplete({
                        delay: 0,
                        minLength: 0,
                        source: $.proxy(this, "_source")
                    })
                    .tooltip({tooltipClass: "ui-state-highlight"});

            this._on(this.input, {
                autocompleteselect: function (event, ui) {
                    ui.item.option.selected = true;
                    this._trigger("select", event, {item: ui.item.option});
                },
                autocompletechange: "_removeIfInvalid"
            });
        },

        _createShowAllButton: function () {
            var input = this.input, wasOpen = false;
            input.autocomplete("widget").css("z-index", "2001"); // ESTA LÍNEA LA AGREGUÉ YO
            $("<a>").attr("tabIndex", -1).attr("title", "").tooltip().appendTo(this.wrapper).button({
                icons: {primary: "ui-icon-triangle-1-s"},
                text: false
            })
                    .removeClass("ui-corner-all").addClass("custom-combobox-toggle ui-corner-right")
                    .mousedown(function () {
                        wasOpen = input.autocomplete("widget").is(":visible");
                    })
                    .click(function () {
                        input.focus();
                        if (wasOpen) {
                            return;
                        } // Close if already visible
                        input.autocomplete("search", ""); // Pass empty string as value to search for, displaying all results
                    });
        },

        _source: function (request, response) {
            var matcher = new RegExp($.ui.autocomplete.escapeRegex(request.term), "i");
            response(this.element.children("option").map(function () {
                var text = $(this).text();
                var valor_select = $(this).val();
                if (this.value && (!request.term || matcher.test(text) || matcher.test(valor_select) || matcher.test(normalize(text))))
                    return {label: text, value: text, option: this}
            }));
        },

        _removeIfInvalid: function (event, ui) {
            // Selected an item, nothing to do
            if (ui.item) {
                return;
            }
            // Search for a match (case-insensitive)
            var value = this.input.val(), valueLowerCase = value.toLowerCase(), valid = false;
            this.element.children("option").each(function () {
                if ($(this).text().toLowerCase() === valueLowerCase || $(this).val().toLowerCase() === valueLowerCase)
                {
                    this.selected = valid = true;
                    return false;
                }
            });
            // Found a match, nothing to do
            if (valid) {
                return;
            }
            // Remove invalid value
            this.input.val("").attr("title", value + " no coincide con ningun valor.").tooltip("open");
            this.element.val("");
            this._delay(function () {
                this.input.tooltip("close").attr("title", "");
            }, 2500);
            this.input.autocomplete("instance").term = "";
        },

        _destroy: function () {
            this.wrapper.remove();
            this.element.show();
        }

    });
})(jQuery);