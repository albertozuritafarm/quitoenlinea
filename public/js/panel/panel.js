google.charts.load('current', {'packages':['gauge']});
google.charts.setOnLoadCallback(function(){ drawChart($("#HiddenCantVentas").val()); });

function drawChart(CantVentas) 
{
  var data = google.visualization.arrayToDataTable([
	['Label', 'Value'],
	['Ventas', 0]
  ]);

  var options = {
	width: 240, height: 240,
	min:0, max:30,
	majorTicks: ['0', '5', '10', '15', '20', '25', '30'], 
	minorTicks: 5, 
	redColor:"#F5A9A9",
	redFrom: 0, redTo: 10,
	yellowColor:"#F3E2A9",
	yellowFrom:10, yellowTo: 20,
	greenColor:"#BCF5A9",
	greenFrom:20, greenTo: 30,
};
  
  var chart = new google.visualization.Gauge(document.getElementById('Canvas_3'));
  chart.draw(data, options);
  data.setValue(0, 1, CantVentas); chart.draw(data, options);

  $("#SMes, #SAnho").change(function()
	{ 
		var CantVentas = ObtenerValorSync("../panel/psPanel.php?opcion=get_cantidad_ventas&mes=" + $("#SMes").val() + "&anho=" + $("#SAnho").val(), null, null, false);
		data.setValue(0, 1, CantVentas);
		chart.draw(data, options);
	});
}

