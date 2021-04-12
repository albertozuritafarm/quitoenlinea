var comprobado = false;
$("#FVenta input, #FVenta select").focus(function(){ OcultarError($(this).attr("id"), true); });
$('#TextIdentificacion').change(function() { $("#DIVComprobacionCliente").slideUp(); comprobado = false; });

$('#DIVPlanes').on('change', 'input[id^="RadioPlan_"]', function(){ $(".TRSeleccionado").removeClass("TRSeleccionado"); var tr = $(this).parent().parent("tr");	if ($(this).prop("checked")) { tr.addClass("TRSeleccionado"); } });
$("#DIVPlanes").on("click", "table tbody tr", function(){ $(this).find('input:radio').click(); });
$('#DIVPlanes').on('click', "input[id^='RadioPlan_']", function(e){ e.stopPropagation(); });

$("#BCancelarD1, #BCancelarD2, #BCancelarD3, #BCancelarD4").click(function(){ CargarPlantilla("../seguros/psSeguros.php?opcion=mostrar_filtro_seguros", "contenido", null, null, null, true); });
$("#BAnteriorD2").click(function(){ HideToRight("D2", function(){ ShowFromLeft("D1"); }); });
$("#BAnteriorD3").click(function(){ HideToRight("D3", function(){ ShowFromLeft("D2"); }); });
$("#BAnteriorD4").click(function(){ HideToRight("D4", function(){ ShowFromLeft("D1"); }); });

$("#TextFechaNacimiento").keydown(function(){ return false; });
$("#TextFechaNacimiento").datepicker({
	dateFormat: "dd/mm/yy",
	maxDate: "0",
	minDate: "-100Y",
    buttonImageOnly: true,
	changeMonth: true,
	changeYear: true,
	yearRange: "-100:+0",
	autoSize: true
});

$("#TextIdentificacion1").on({
	"focus": function(event) {
	  $(event.target).select();
	},
	"keyup": function(event) {
		if ($("#STipoIdentificacion").val() == 1) {
			$(event.target).val(function(index, value) {
				return value.replace(/\D/g, "")
				.replace(/\B(?=(\d{4})+(?!\d)\.?)/g, "-");
			});
			document.getElementById("TextIdentificacion1").maxLength = "10";
		}
	}
});

$("#BSiguienteD1").click(function(){
	var TodoOK = true; var Mensaje = "";
	if (Vacio("TextIdentificacion")) { TodoOK = false; Mensaje = "Debe indicar la identificación (Cédula, RUC o pasaporte)"; MostrarError("TextIdentificacion", Mensaje); }
	else if (!comprobado) { TodoOK = false; Mensaje = "El cliente debe estar comprobado."; MostrarError("TextIdentificacion", Mensaje); }
	if (TodoOK) 
	{ 
		var json = getJSONVenta();
		$("#DIVPlanes").empty();
		CargarPlantilla("../seguros/psSeguros.php?opcion=mostrar_planes", "DIVPlanes", null, json, null, false);
		HideToLeft("D1", function(){ ShowFromRight("D2"); }); 
	}
});

$("#BSiguienteD2").click(function(){
	if ($('input[id^="RadioPlan_"]:checked').length != 1) { MiAlert("Debe seleccionar un plan.", "ERROR"); }
	else 
	{ 
		var json = getJSONVenta();
		ObtenerValorASync("../seguros/psSeguros.php?opcion=generar_codigo_enviar_correo", null, json, false);
		HideToLeft("D2", function(){ ShowFromRight("D3"); });
	}
});

$("#BFinalizarD3").click(function(){
	var TodoOK = true; var Mensaje = "";
	if (Vacio("TextCodigo")) { TodoOK = false; Mensaje = "Debe indicar el código de autorización recibido."; MostrarError("TextCodigo", Mensaje); }
	else
	{
		var json = getJSONVenta();
		var codigo_correcto = ObtenerValorSync("../seguros/psSeguros.php?opcion=chequear_codigo", null, json, true);
		if (codigo_correcto == "0") { TodoOK = false; Mensaje = "El código ingresado es incorrecto o expiró su validez de 5 min."; MostrarError("TextCodigo", Mensaje); }
		else { EnviarFormulario("../seguros/psSeguros.php?opcion=guardar_venta", "DIVOculto", "FVenta", null, true); }
	}
});

$("#BSiguienteD4").click(function(){
	var TodoOK = true; var Mensaje = "";
	if (Vacio("TextIdentificacion1")) { TodoOK = false; Mensaje = "Debe indicar la identificación del cliente."; MostrarError("TextIdentificacion1", Mensaje); }
	else if ($("#STipoIdentificacion").val() == 1 && !CedulaValida($("#TextIdentificacion").val())) { TodoOK = false; Mensaje = "La cédula indicada no es válida."; MostrarError("TextIdentificacion1", Mensaje); }
	else if ($("#STipoIdentificacion").val() == 2 && !RUCValido($("#TextIdentificacion").val())) { TodoOK = false; Mensaje = "El RUC indicado no es válido."; MostrarError("TextIdentificacion1", Mensaje); }
	if (Vacio("TextNombres")) { TodoOK = false; Mensaje = "Debe indicar el(los) nombre(s) del cliente."; MostrarError("TextNombres", Mensaje); }
	if (Vacio("TextApellidos")) { TodoOK = false; Mensaje = "Debe indicar los apellidos del cliente."; MostrarError("TextApellidos", Mensaje); }
	if (Vacio("TextFechaNacimiento")) { TodoOK = false; Mensaje = "Debe indicar la fecha de nacimiento."; MostrarError("TextFechaNacimiento", Mensaje); }
	if ($("#SGenero").val() == "TODOS") { TodoOK = false; Mensaje = "Debe indicar el género del cliente."; MostrarError("SGenero", Mensaje); }
	if ($("#SEstadoCivil").val() == "TODOS") { TodoOK = false; Mensaje = "Debe indicar el estado civil."; MostrarError("SEstadoCivil", Mensaje); }
	if ($("#SCiudad").val() == "TODOS") { TodoOK = false; Mensaje = "Debe indicar la ciudad."; MostrarError("SCiudad", Mensaje); }
	if (Vacio("TextDireccionDomicilio")) { TodoOK = false; Mensaje = "Debe indicar la dirección de domicilio."; MostrarError("TextDireccionDomicilio", Mensaje); }
	if (Vacio("TextTelefonoDomicilio")) { TodoOK = false; Mensaje = "Debe indicar el teléfono de domicilio."; MostrarError("TextTelefonoDomicilio", Mensaje); }
	if (Vacio("TextTelefonoMovil")) { TodoOK = false; Mensaje = "Debe indicar el teléfono de trabajo."; MostrarError("TextTelefonoMovil", Mensaje); }
	if (Vacio("TextCorreo")) { TodoOK = false; Mensaje = "Debe indicar el email del cliente."; MostrarError("TextCorreo", Mensaje); }
	else if (!EMailValido($("#TextCorreo").val())) { TodoOK = false; Mensaje = "El email indicado es incorrecto."; MostrarError("TextCorreo", Mensaje); }
	if (TodoOK) 
	{ 
		EnviarFormulario("../seguros/psSeguros.php?opcion=guardar_cliente", "DIVOculto", "FVenta", null, true); 
		var json = getJSONVenta();
		$("#DIVPlanes").empty();
		CargarPlantilla("../seguros/psSeguros.php?opcion=mostrar_planes", "DIVPlanes", null, json, null, false);
		HideToLeft("D4", function(){ ShowFromRight("D2"); }); 
	}
});

$("#BotonNuevoCliente").click(function(){
	HideToLeft("D1", function(){ ShowFromRight("D4"); });
});

$("#BComprobar").click(function(){
	var TodoOK = true; var Mensaje = "";
	$("#DIVComprobacionCliente").slideUp().empty();
	OcultarError("TextIdentificacion", true);
	if (Vacio("TextIdentificacion")) { TodoOK = false; Mensaje = "Debe indicar la identificación (Cédula, RUC o pasaporte)"; MostrarError("TextIdentificacion", Mensaje); }
	if (TodoOK) 
	{ 
		var respuesta_json = ObtenerValorSync("../seguros/psSeguros.php?opcion=mostrar_comprobacion_cliente", null, getJSONVenta(), true);
		MostrarDIVComprobacion(respuesta_json);
	}
});

$("#BReenviarCodigo").click(function(){
	var Res = ObtenerValorSync("../seguros/psSeguros.php?opcion=generar_codigo_enviar_correo", null, getJSONVenta(), true);
	if (Res < 0) { MiAlert("Ocurrió un error al generar el nuevo código y enviar el correo.", "ERROR"); } 
	else { MiAlert("El código fue enviado. Por favor, revise su email.", "OK"); }
});

function MostrarDIVComprobacion(respuesta_json)
{
	var obj = JSON.parse(respuesta_json);
	if (obj.codigo == 0) 
	{ 
		var tipo_documento = ""; var oa = "o";
		var identificacion = $("#TextIdentificacion").val().trim().toUpperCase();
		if (CedulaValida(identificacion)) { tipo_documento = "La cédula"; oa = "a";  }
		else if (RUCValido(identificacion)) { tipo_documento = "El RUC"; }
		else { tipo_documento = "El pasaporte"; }
		MostrarError("TextIdentificacion", tipo_documento + " '" + identificacion + "' no está registrad" + oa + " en el sistema."); 
		document.getElementById("DIVNuevoCliente").style.display = ""; 
	} else { 
		if (obj.codigo == 1) { comprobado = true; } 
		$("#DIVComprobacionCliente").empty().html(obj.html).slideDown(); 
		document.getElementById("DIVNuevoCliente").style.display = "none"; 
	}
}

function getJSONVenta()
{
	var obj = {identificacion:$("#TextIdentificacion").val(), codigo:$("#TextCodigo").val(), id_plan:$("input[id^='RadioPlan_']:checked").val()};
	return JSON.stringify(obj);
}