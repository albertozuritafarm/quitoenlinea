var comprobado = false;

$("#TextValor").keydown(function(event){ return SoloComaPuntoyNumeros(event); });
$("#TextValor").keyup(function(event){ $(this).val($(this).val().replace(/,/g, ".")); });
$("#TextOrden").keydown(function(event){ return SoloNumeros(event); });
$("#FSolicitud input, #FSolicitud select").focus(function(){ OcultarError($(this).attr("id"), true); });

$('#TextValor, #SInstitucionFinanciera, #TextIdentificacion').change(function() { $("#DIVComprobacionCliente").slideUp(); comprobado = false; });

$('#DIVPlanes').on('change', 'input[id^="RadioPlan_"]', function(){ $(".TRSeleccionado").removeClass("TRSeleccionado"); var tr = $(this).parent().parent("tr");	if ($(this).prop("checked")) { tr.addClass("TRSeleccionado"); } });
$("#DIVPlanes").on("click", "table tbody tr", function(){ $(this).find('input:radio').click(); });
$('#DIVPlanes').on('click', "input[id^='RadioPlan_']", function(e){ e.stopPropagation(); });

$("#BCancelarD1, #BCancelarD2, #BCancelarD3, #BCancelarD4,  #BCancelarD4, #BCancelarD5").click(function(){ CargarPlantilla("../solicitudes/psSolicitudes.php?opcion=mostrar_filtro_solicitudes", "contenido", null, null, null, true); });
$("#BAnteriorD2").click(function(){ HideToRight("D2", function(){ ShowFromLeft("D1"); }); $("#DIVComprobacionCliente").hide().empty(); comprobado = false; });
$("#BAnteriorD3").click(function(){ HideToRight("D3", function(){ ShowFromLeft("D2"); }); });
$("#BAnteriorD4").click(function(){ HideToRight("D4", function(){ ShowFromLeft("D3"); }); });
$("#BAnteriorD5").click(function(){ HideToRight("D5", function(){ ShowFromLeft("D2"); }); });

$("#BSiguienteD1").click(function(){
	var TodoOK = true; var Mensaje = "";
	if (Vacio("TextValor")) { TodoOK = false; Mensaje = "Debe indicar el valor a financiar."; MostrarError("TextValor", Mensaje); }
	else if (!RealPositivoValido("TextValor")) { TodoOK = false; Mensaje = "El valor indicado es incorrecto."; MostrarError("TextValor", Mensaje); }
	else if (!NumeroHasta2Decimales("TextValor")) { TodoOK = false; Mensaje = "Solo admite números hasta 2 decimales."; MostrarError("TextValor", Mensaje); }
	else if (parseFloat($("#TextValor").val()) > 1000) { TodoOK = false; Mensaje = "El valor no puede superar los $ 1000.00"; MostrarError("TextValor", Mensaje); }
	if (Vacio("TextOrden")) { TodoOK = false; Mensaje = "Debe indicar el número de Factura / Orden."; MostrarError("TextOrden", Mensaje); }
	else if ($("#TextOrden").val().length != 5) { TodoOK = false; Mensaje = "Número de factura / orden incorrecto. (Debe contener 5 dígitos)"; MostrarError("TextOrden", Mensaje); }
	if ($("#SInstitucionFinanciera").val() == "TODOS") { TodoOK = false; Mensaje = "Debe indicar la Institución Financiera."; MostrarError("SInstitucionFinanciera", Mensaje); }
	if (TodoOK) { HideToLeft("D1", function(){ ShowFromRight("D2"); }); }
});

$("#BSiguienteD2").click(function(){
	var TodoOK = true; var Mensaje = "";
	if (Vacio("TextIdentificacion")) { TodoOK = false; Mensaje = "Debe indicar la identificación (Cédula, RUC o pasaporte)"; MostrarError("TextIdentificacion", Mensaje); }
	else if (!comprobado) { TodoOK = false; Mensaje = "El cliente debe estar comprobado y contar con crédito disponible."; MostrarError("TextIdentificacion", Mensaje); }
	if (TodoOK) 
	{ 
		var json = getJSONCompra();
		$("#DIVPlanes").empty();
		CargarPlantilla("../solicitudes/psSolicitudes.php?opcion=mostrar_planes", "DIVPlanes", null, json, null, false);
		HideToLeft("D2", function(){ ShowFromRight("D3"); }); 
	}
});

$("#BSiguienteD3").click(function(){
	if ($('input[id^="RadioPlan_"]:checked').length != 1) { MiAlert("Debe seleccionar un plan.", "ERROR"); }
	else 
	{ 
		var json = getJSONCompra();
		ObtenerValorASync("../solicitudes/psSolicitudes.php?opcion=generar_codigo_enviar_correo", null, json, false);
		HideToLeft("D3", function(){ ShowFromRight("D4"); }); 
	}
});

$("#BFinalizarD4").click(function(){
	var TodoOK = true; var Mensaje = "";
	if (Vacio("TextCodigo")) { TodoOK = false; Mensaje = "Debe indicar el código de autorización recibido."; MostrarError("TextCodigo", Mensaje); }
	else
	{
		var json = getJSONCompra();
		var codigo_correcto = ObtenerValorSync("../solicitudes/psSolicitudes.php?opcion=chequear_codigo", null, json, true);
		if (codigo_correcto == "0") { TodoOK = false; Mensaje = "El código ingresado es incorrecto o expiró su validez de 5 min."; MostrarError("TextCodigo", Mensaje); }
		else { EnviarFormulario("../solicitudes/psSolicitudes.php?opcion=guardar_solicitud", "DIVOculto", "FSolicitud", null, true); }
	}
});

$("#BComprobar").click(function(){
	var TodoOK = true; var Mensaje = "";
	$("#DIVComprobacionCliente").slideUp().empty();
	OcultarError("TextIdentificacion", true);
	if (Vacio("TextIdentificacion")) { TodoOK = false; Mensaje = "Debe indicar la identificación (Cédula, RUC o pasaporte)"; MostrarError("TextIdentificacion", Mensaje); }
	if (TodoOK) 
	{ 
		var respuesta_json = ObtenerValorSync("../solicitudes/psSolicitudes.php?opcion=mostrar_comprobacion_cliente", null, getJSONCompra(), true);
		MostrarDIVComprobacion(respuesta_json);
	}
});

$("#BReenviarCodigo").click(function(){
	var Res = ObtenerValorSync("../solicitudes/psSolicitudes.php?opcion=generar_codigo_enviar_correo", null, getJSONCompra(), true);
	if (Res < 0) { MiAlert("Ocurrió un error al generar el nuevo código y enviar el correo.", "ERROR"); } 
	else { MiAlert("El código fue enviado. Por favor, revise su email.", "OK"); }
});

function MostrarDIVComprobacion(respuesta_json)
{
	var obj = JSON.parse(respuesta_json);
	if (obj.codigo == 0) 
	{ 	
		var tipo_documento = "";
		var identificacion = $("#TextIdentificacion").val().trim().toUpperCase();
		if (CedulaValida(identificacion)) { tipo_documento = "La cédula"; }
		else if (RUCValido(identificacion)) { tipo_documento = "El RUC"; }
		else { tipo_documento = "El pasaporte"; }
		MostrarError("TextIdentificacion", tipo_documento + " '" + identificacion + "' no está registrado en el sistema."); 
		document.getElementById("DIVNuevoCliente").style.display = ""; 
	}
	else { 
		if (obj.codigo == 3) { comprobado = true; } 
		$("#DIVComprobacionCliente").empty().html(obj.html).slideDown();
		document.getElementById("DIVNuevoCliente").style.display = "none";
	}
}

function getJSONCompra()
{
	var obj = {valor:$("#TextValor").val(), orden:$("#TextOrden").val(), id_cooperativa:$("#SInstitucionFinanciera").val(), cooperativa:$("#SInstitucionFinanciera option:selected").text(), financiamiento:$("#CHFinanciamiento:checked").val(), identificacion:$("#TextIdentificacion").val(), codigo:$("#TextCodigo").val()};
	return JSON.stringify(obj);
}