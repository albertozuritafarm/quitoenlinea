/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {
//Delete Accounts button
    $('#deleteCrBtn').click(function () {
        var cr = [];

        $.each($("input[name='crId']:checked"), function () {
            cr.push($(this).val());
        });
        if (Array.isArray(cr) && cr.length) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var data = {cr: cr};
            var url = ROUTE + '/financing/delete';
            $.ajax({
                type: "POST",
                data: {_token: CSRF_TOKEN, data},
                url: url,
                beforeSend: function () {
                    // Show Loader
                    $("#loaderGif").addClass('loaderGif');
                },
                success: function (data) {
                    window.location.replace(ROUTE + "/financing");
                },
                complete: function () {
                    //Hide Loader
                    var loaderGif = document.getElementById("loaderGif");
                    loaderGif.classList.remove("loaderGif");
                }
            });
        }else{
            alert('Debe seleccionar al menos una solicitud');
        }
    });
//Clear Filters button
    $('#btnAccountsClear').click(function () {
        document.getElementById("crId").value = '';
        document.getElementById("beginDate").value = '';
        document.getElementById("endDate").value = '';
        document.getElementById("document").value = '';
        document.getElementById("bank").value = '';
    });
});

function accountApprove(id) {
    var opcion = confirm("¿Seguro que desea aprobar la solicitud?");
    if (opcion == true) {
        var formAprroveId = document.getElementById("formAprroveId");
        formAprroveId.value = id;
        document.getElementById("formAprroveBtn").click();
    } else {
    }
}
function crDelete(id) {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = ROUTE + '/financing/deleteCreditRequest';
    var data = {crId: id};
    
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, data},
        url: url,
        beforeSend: function () {
            // Show Loader
            $("#loaderGif").addClass('loaderGif');
        },
        success: function (data) {
            window.location.replace(ROUTE + "/financing");
        },
        complete: function () {
            //Hide Loader
            var loaderGif = document.getElementById("loaderGif");
            loaderGif.classList.remove("loaderGif");
        }
    });
}
function validateDate() {
    var beginDate = document.getElementById("beginDate").value;
    var endDate = document.getElementById("endDate").value;
    if (beginDate > endDate) {
//        alert('La Fecha de Inicio no puede ser mayor a la Fecha Fin');
        return false;
    }
}
function val() {
    var beginDate = document.getElementById("beginDate").value;
    var endDate = document.getElementById("endDate").value;
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
function beginDateChange(){
    document.getElementById("endDate").value = '';
}
function modalCode(id){
    event.preventDefault();
    document.getElementById("accountId").value = id;
    document.getElementById("modalCodeBtn").click();
}
function resendCode(mobile_phone){
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var crId = document.getElementById("accountId").value;
    var url = ROUTE + '/financing/resendCode';
    var data = {crId: crId};
    
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, data},
        url: url,
        beforeSend: function () {
            // Show Loader
            $("#loaderGif").addClass('loaderGif');
        },
        success: function (data) {
            var resultMessage = document.getElementById("resultMessage");
            $(resultMessage).removeClass('alert alert-success alert-danger');
            $(resultMessage).addClass('alert alert-success');
            resultMessage.innerHTML = 'Se envio un nuevo codigo de validación';
        },
        complete: function () {
            //Hide Loader
            var loaderGif = document.getElementById("loaderGif");
            loaderGif.classList.remove("loaderGif");
        }
    });
}
function validateCode(){
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var crId = document.getElementById("accountId").value;
    var code = document.getElementById("code").value;
    var url = ROUTE + '/financing/validateCode';
    var data = {crId: crId, code:code};
    
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, data},
        url: url,
        beforeSend: function () {
            // Show Loader
            $("#loaderGif").addClass('loaderGif');
        },
        success: function (data) {
            if(data.success == 'true'){
                window.location.replace(ROUTE + "/financing");
            }else{
                var resultMessage = document.getElementById("resultMessage");
                $(resultMessage).removeClass('alert alert-success alert-danger');
                $(resultMessage).addClass('alert alert-danger');
                resultMessage.innerHTML = data.msg;
            }
        },
        complete: function () {
            //Hide Loader
            var loaderGif = document.getElementById("loaderGif");
            loaderGif.classList.remove("loaderGif");
        }
    });
}

