var TodosMarcados = false;

$('#DIVLista').on('click', '#LMarcar', function(){ 
	if (!TodosMarcados) { $('#DIVLista input[id^="CH_"]').parent().parent("tr").addClass("TRSeleccionado"); $('#DIVLista input[id^="CH_"]').prop("checked", true); TodosMarcados = true; }
	else { 	$('#DIVLista input[id^="CH_"]').parent().parent("tr").removeClass("TRSeleccionado"); $('#DIVLista input[id^="CH_"]').prop("checked", false); TodosMarcados = false; }
});

$('#DIVLista').on('change', 'input[id^="CH_"]', function(){ var tr = $(this).parent().parent("tr");	if ($(this).prop("checked")) { tr.addClass("TRSeleccionado"); } else { tr.removeClass("TRSeleccionado"); } });
$("#DIVLista").on("click", "table tbody tr td:not(.TD_Excluido)", function(){ $(this).parent("tr").find('input:checkbox').click(); });
$('#DIVLista').on('click', "input[id^='CH_']", function(e){ e.stopPropagation(); });

$("#BotonFiltro").click(function(){ 
	if ($("#DIVFiltro").is(":hidden")) { $("#DIVFiltro").slideDown(); $("#BotonFiltro img").attr("src", "../images/nofilter.png"); }
	else { $("#DIVFiltro").slideUp(); $("#BotonFiltro img").attr("src", "../images/filter.png"); LimpiarFiltro(); ReCargarLista($(this)); }
});

$("#BotonNuevaVenta").click(function(){
	CargarPlantilla("../seguros/psSeguros.php?opcion=mostrar_nueva_venta", "contenido", null, null, null, true);
});

$("#BotonEliminarVentas").click(function(){
	var cant_ventas = $('input[id^="CH_"]:checked').length;
	if (cant_ventas == 0) { MiAlert("Debe seleccionar al menos una venta para eliminar.", "INFO"); }
	else 
	{
		if (cant_ventas > 1) { s = 's'; } else { s = ''; }
		MiConfirm("¿Desea eliminar la" + s + " venta" + s + " seleccionada" + s + "?", "WARNING", "Eliminar", "Cancelar", function(){ CargarPlantilla('../seguros/psSeguros.php?opcion=eliminar_ventas', 'DIVLista', "0", getCadenaVentas(), null, true); }, null); 
	}
});

$('#DIVLista').on('click', "img[id^='BEliminar_']", function(e){
	e.stopPropagation();
	var id_venta = $(this).attr("id").split("_").pop();
	MiConfirm("¿Desea eliminar la venta seleccionada?", "WARNING", "Eliminar", "Cancelar", function(){ CargarPlantilla("../seguros/psSeguros.php?opcion=eliminar_ventas", "DIVLista", "0", id_venta, null, true); }, null);
});

$('#DIVLista').on('click', "img[id^='BPDF_']", function(e){
	e.stopPropagation();
	var id_venta = $(this).attr("id").split("_").pop();
	window.open("../seguros/psSeguros.php?opcion=generar_pdf_venta_seguro&id_venta=" + id_venta, "PDF Venta Seguro");
});


$('#DIVLista').on('click', "img[id^='BRestaurar_']", function(){
	CargarPlantilla("../seguros/psSeguros.php?opcion=restaurar_venta", "DIVLista", "1", $(this).attr("id").split("_").pop(), null, true); });

function getCadenaVentas() { var A = new Array(); $('input[id^="CH_"]:checked').each(function(index, element) { A[index] = $(this).attr("id").split("_").pop(); }); return JSON.stringify(A); };

function ReCargarLista(Control)
{
	var num_pag = 1;
	if (Control.attr("id") == 'BotonPaginacionSiguiente') { num_pag = parseInt($("#DIVTablaPaginacion .BotonPaginacionActivo").attr("id").split("pagina_").pop(), 10) + 1; }
	else if (Control.attr("id") == 'BotonPaginacionAnterior') { num_pag = parseInt($("#DIVTablaPaginacion .BotonPaginacionActivo").attr("id").split("pagina_").pop(), 10) - 1; }
	else if (Control.hasClass("BotonPaginacion")) { num_pag = Control.val(); }
	else { num_pag = 1; }
	EnviarFormulario("../seguros/psSeguros.php?opcion=mostrar_lista_ventas", "DIVLista", "FFiltro", num_pag, true);
}

function LimpiarFiltro()
{
	$("#FiltroNumero, #FiltroCliente").val("");
	$("#FiltroPlan option[value=TODOS]").prop("selected", true);
}

$(document).ready(function() { ReCargarLista($(this)); });
$("#FiltroNumero, #FiltroCliente").keyup(function(){ ReCargarLista($(this)); })
$("#SCantRegistros, #FiltroPlan").change(function(){ ReCargarLista($(this)); });
$('#DIVLista').on('click', '#DIVTablaPaginacion .BotonPaginacion', function(){ ReCargarLista($(this)); });