$("#FiltroNumero").keydown(function(event){ return SoloNumeros(event); });
$("#BotonFiltro").click(function(){ 
	if ($("#DIVFiltro").is(":hidden")) { $("#DIVFiltro").slideDown(); $("#BotonFiltro img").attr("src", "../images/nofilter.png"); }
	else { $("#DIVFiltro").slideUp(); $("#BotonFiltro img").attr("src", "../images/filter.png"); LimpiarFiltro(); ReCargarLista($(this)); }
});

$("#FiltroDesde, #FiltroHasta").keydown(function(){ return false; });
$("#FiltroDesde, #FiltroHasta").datepicker({
	dateFormat: "dd/mm/yy",
	maxDate: "0",
	minDate: "-5Y",
    buttonImageOnly: true,
	changeMonth: true,
	changeYear: true,
	autoSize: true
});

$('#FiltroDesde').datepicker("option", "onSelect", function(sd) { $("#FiltroHasta").datepicker("option", "minDate", sd); ReCargarLista($(this)); });
$('#FiltroHasta').datepicker("option", "onSelect", function(sd) { $("#FiltroDesde").datepicker("option", "maxDate", sd); ReCargarLista($(this)); });

$("#BotonGenerarExcel").click(function(){
	window.open("../reportes/psReportes.php?opcion=generar_xls_reporte_1&FiltroNumero=" + $("#FiltroNumero").val() + "&FiltroCliente=" + $("#FiltroCliente").val() + 
				"&FiltroCooperativa=" + $("#FiltroCooperativa").val() + "&FiltroDesde=" + $("#FiltroDesde").val() + "&FiltroHasta=" + $("#FiltroHasta").val(), "Excel Reporte 1");
});

$("#BotonGenerarPDF").click(function(){
	window.open("../reportes/psReportes.php?opcion=generar_pdf_reporte_1&FiltroNumero=" + $("#FiltroNumero").val() + "&FiltroCliente=" + $("#FiltroCliente").val() + 
				"&FiltroCooperativa=" + $("#FiltroCooperativa").val() + "&FiltroDesde=" + $("#FiltroDesde").val() + "&FiltroHasta=" + $("#FiltroHasta").val(), "PDF Reporte 1");
});

function ReCargarLista(Control)
{
	var num_pag = 1;
	if (Control.attr("id") == 'BotonPaginacionSiguiente') { num_pag = parseInt($("#DIVTablaPaginacion .BotonPaginacionActivo").attr("id").split("pagina_").pop(), 10) + 1; }
	else if (Control.attr("id") == 'BotonPaginacionAnterior') { num_pag = parseInt($("#DIVTablaPaginacion .BotonPaginacionActivo").attr("id").split("pagina_").pop(), 10) - 1; }
	else if (Control.hasClass("BotonPaginacion")) { num_pag = Control.val(); }
	else { num_pag = 1; }
	EnviarFormulario("../reportes/psReportes.php?opcion=mostrar_lista_solicitudes_1", "DIVLista", "FFiltro", num_pag, true);
}

function LimpiarFiltro()
{
	$("#FiltroNumero, #FiltroCliente, #FiltroDesde, #FiltroHasta").val("");
	$("#FiltroCooperativa option[value=TODOS]").prop("selected", true);
}

$(document).ready(function() { ReCargarLista($(this)); });
$("#FiltroNumero, #FiltroCliente").keyup(function(){ ReCargarLista($(this)); })
$("#SCantRegistros, #FiltroCooperativa").change(function(){ ReCargarLista($(this)); });
$('#DIVLista').on('click', '#DIVTablaPaginacion .BotonPaginacion', function(){ ReCargarLista($(this)); });