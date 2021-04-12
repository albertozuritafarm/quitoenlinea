/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {
//Clear Filters
    $('#btnClearFilter').click(function () {
        document.getElementById("country").value = "";
        document.getElementById("province").value = "";
        document.getElementById("city").value = "";
        document.getElementById("status").value = "";
        document.getElementById("beginDate").value = "";
        document.getElementById("endDate").value = "";
        document.getElementById("channel").value = "";
        document.getElementById("agency").value = "";
        document.getElementById("adviser").value = "";
        document.getElementById("salId").value = "";
        
    });
});

function endDateChange() {
    var beginDate = document.getElementById("beginDate").value;
    var endDate = document.getElementById("endDate").value;
    if (beginDate === '') {
        alert('Por favor introduza una fecha de Inicio');
        document.getElementById("endDate").value = '';
    } else if (endDate < beginDate) {
        alert('La fecha Fin no puede ser menor a la fecha de Inicio');
        document.getElementById("endDate").value = '';

    }
}

function val() {
    var beginDate = document.getElementById("beginDate").value;
    var endDate = document.getElementById("endDate").value;
    console.log(beginDate);
    if (beginDate === '' && endDate === '') {
        return true;
    } else if (beginDate === '' && endDate !== '') {
        alert('Ingrese una Fecha Inicio');
        return false;
    } else if (beginDate !== '' && endDate === '') {
        alert('Ingrese una Fecha Fin');
        return false;
    } else {
        return true;
    }
}

function updateAgency(name) {
//    console.log(name);
    var url = ROUTE + "/sales/massives/update/agency";
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var data = {name: name};
    $.ajax({
        url: url,
        type: "POST",
        data: {_token: CSRF_TOKEN, data:data},
        success: function (data)
        {
            document.getElementById("agency").innerHTML = data; 
        }
    });
}

function updateCity(name) {
//    console.log(name);
    var url = ROUTE + "/sales/massives/update/city";
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var data = {name: name};
    $.ajax({
        url: url,
        type: "POST",
        data: {_token: CSRF_TOKEN, data:data},
        success: function (data)
        {
            document.getElementById("city").innerHTML = data; 
        }
    });
}