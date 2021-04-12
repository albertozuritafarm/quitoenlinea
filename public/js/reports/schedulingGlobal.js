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
        document.getElementById("sType").value = "";
        document.getElementById("location").value = "";
        document.getElementById("paint").value = "";
        document.getElementById("damage").value = "";
        document.getElementById("salId").value = "";
    });
});

function endDateChange() {
    var beginDate = document.getElementById("beginDate").value;
    var endDate = document.getElementById("endDate").value;
    console.log("VALIDA FECHA");
    console.log(endDate.diff(beginDate));
    if (beginDate === '') {
        alert('Por favor introduza una fecha de Inicio');
        document.getElementById("endDate").value = '';
    } else if (endDate < beginDate) {
        alert('La fecha Fin no puede ser menor a la fecha de Inicio');
        document.getElementById("endDate").value = '';
    } else if (endDate.diff(beginDate) > 30){
        alert('El rango de fecha no debe de superar los 31 días');
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