/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {
//Delete Accounts button
    $('#deleteAccountsBtn').click(function () {
        var accounts = [];

        $.each($("input[name='accountId']:checked"), function () {
            accounts.push($(this).val());
        });
        if (Array.isArray(accounts) && accounts.length) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var data = {accounts: accounts};
            var url = ROUTE + '/account/delete';
            $.ajax({
                type: "POST",
                data: {_token: CSRF_TOKEN, data},
                url: url,
                success: function (data) {
                    window.location.replace("/account");
                }
            });
        }else{
            alert('Debe seleccionar al menos una solicitud');
        }
    });
//Clear Filters button
    $('#btnAccountsClear').click(function () {
        document.getElementById("banId").value = '';
        document.getElementById("beginDate").value = '';
        document.getElementById("endDate").value = '';
        document.getElementById("document").value = '';
        document.getElementById("status").value = '';
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
function accountDeny(id) {
    var opcion = confirm("¿Seguro que desea rechazar la solicitud?");
    if (opcion == true) {
        var formAprroveId = document.getElementById("formDenyId");
        formAprroveId.value = id;
        document.getElementById("formDenyBtn").click();
    } else {
    }
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
    var accountId = document.getElementById("accountId").value;
    var url = ROUTE + '/account/sendCode';
    var data = {accountId: accountId};
    
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
    var accountId = document.getElementById("accountId").value;
    var code = document.getElementById("code").value;
    var url = ROUTE + '/account/validateCode';
    var data = {accountId: accountId, code:code};
    
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
                window.location.replace(ROUTE + "/account");
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

