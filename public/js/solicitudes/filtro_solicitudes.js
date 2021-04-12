var TodosMarcados = false;

$('#DIVLista').on('click', '#LMarcar', function(){ 
	if (!TodosMarcados) { $('#DIVLista input[id^="CH_"]').parent().parent("tr").addClass("TRSeleccionado"); $('#DIVLista input[id^="CH_"]').prop("checked", true); TodosMarcados = true; }
	else { 	$('#DIVLista input[id^="CH_"]').parent().parent("tr").removeClass("TRSeleccionado"); $('#DIVLista input[id^="CH_"]').prop("checked", false); TodosMarcados = false; }
});

$("#FiltroNumero").keydown(function(event){ return SoloNumeros(event); });

$('#DIVLista').on('change', 'input[id^="CH_"]', function(){ var tr = $(this).parent().parent("tr");	if ($(this).prop("checked")) { tr.addClass("TRSeleccionado"); } else { tr.removeClass("TRSeleccionado"); } });
$("#DIVLista").on("click", "table tbody tr", function(){ $(this).find('input:checkbox').click(); });
$('#DIVLista').on('click', "input[id^='CH_']", function(e){ e.stopPropagation(); });

$("#BotonFiltro").click(function(){ 
	if ($("#DIVFiltro").is(":hidden")) { $("#DIVFiltro").slideDown(); $("#BotonFiltro img").attr("src", "../images/nofilter.png"); }
	else { $("#DIVFiltro").slideUp(); $("#BotonFiltro img").attr("src", "../images/filter.png"); LimpiarFiltro(); ReCargarLista($(this)); }
});

$("#BotonNuevaSolicitud").click(function(){
	CargarPlantilla("../solicitudes/psSolicitudes.php?opcion=mostrar_nueva_solicitud", "contenido", null, null, null, true);
});

$("#BotonEliminarSolicitudes").click(function(){
	var cant_solicitudes = $('input[id^="CH_"]:checked').length;
	if (cant_solicitudes == 0) { MiAlert("Debe seleccionar al menos una solicitud para eliminarla.", "INFO"); }
	else 
	{
		if (cant_solicitudes > 1) { s = 's'; es = 'es'; } else { s = ''; es = ''; }
		MiConfirm("¿Desea eliminar la" + s + " solicitud" + es + " seleccionada" + s + "?", "WARNING", "Eliminar", "Cancelar", function(){ CargarPlantilla('../solicitudes/psSolicitudes.php?opcion=eliminar_solicitudes', 'DIVLista', "0", getCadenaSolicitudes(), null, true); }, null); 
	}
});

$('#DIVLista').on('click', "img[id^='BEliminar_']", function(e){
	e.stopPropagation();
	var id_solicitud = $(this).attr("id").split("_").pop();
	MiConfirm("¿Desea eliminar la solicitud seleccionada?", "WARNING", "Eliminar", "Cancelar", function(){ CargarPlantilla("../solicitudes/psSolicitudes.php?opcion=eliminar_solicitudes", "DIVLista", "0", id_solicitud, null, true); }, null);
});

$('#DIVLista').on('click', "img[id^='BRestaurar_']", function(){
	CargarPlantilla("../solicitudes/psSolicitudes.php?opcion=restaurar_solicitud", "DIVLista", "1", $(this).attr("id").split("_").pop(), null, true); });

function getCadenaSolicitudes() { var A = new Array(); $('input[id^="CH_"]:checked').each(function(index, element) { A[index] = $(this).attr("id").split("_").pop(); }); return JSON.stringify(A); };

function ReCargarLista(Control)
{
	var num_pag = 1;
	if (Control.attr("id") == 'BotonPaginacionSiguiente') { num_pag = parseInt($("#DIVTablaPaginacion .BotonPaginacionActivo").attr("id").split("pagina_").pop(), 10) + 1; }
	else if (Control.attr("id") == 'BotonPaginacionAnterior') { num_pag = parseInt($("#DIVTablaPaginacion .BotonPaginacionActivo").attr("id").split("pagina_").pop(), 10) - 1; }
	else if (Control.hasClass("BotonPaginacion")) { num_pag = Control.val(); }
	else { num_pag = 1; }
	EnviarFormulario("../solicitudes/psSolicitudes.php?opcion=mostrar_lista_solicitudes", "DIVLista", "FFiltro", num_pag, true);
}

function LimpiarFiltro()
{
	$("#FiltroNumero, #FiltroCliente").val("");
	$("#FiltroCooperativa option[value=TODOS]").prop("selected", true);
}

$(document).ready(function() { ReCargarLista($(this)); });
$("#FiltroNumero, #FiltroCliente").keyup(function(){ ReCargarLista($(this)); })
$("#SCantRegistros, #FiltroCooperativa").change(function(){ ReCargarLista($(this)); });
$('#DIVLista').on('click', '#DIVTablaPaginacion .BotonPaginacion', function(){ ReCargarLista($(this)); });