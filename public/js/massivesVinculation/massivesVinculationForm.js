/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {
    fadeOut('payerFullDiv');
});

function isPayerChange(obj) {
    if (obj == 'Si') {
        fadeOut('payerFullDiv');
    }
    if (obj == 'No') {
        fadeIn('payerFullDiv');
    }
}

function fadeToggle(id) {
    event.preventDefault();
    var div = document.getElementById(id);
    $(div).fadeToggle(200);
}
function fadeOut(id) {
    var div = document.getElementById(id);
    $(div).fadeOut();
}
function fadeIn(id) {
    var div = document.getElementById(id);
    $(div).fadeIn();
}

function sendVinculationFormLink() {
    event.preventDefault();
    var customerId = document.getElementById('customerId');
    var saleId = document.getElementById('saleId');
    var mobile_phone = document.getElementById('mobile_phone');
    var email = document.getElementById('email');

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, customerId:customerId.value, mobilePhone:mobile_phone.value, email:email.value, saleId:saleId.value},
        url: ROUTE + "/vinculation/form/send",
        success: function (data)
        {
            alert('Se le ha enviado al usuario el link.');
        }
    });
}

function validateVinculationForm() {
    event.preventDefault();
    var filterDay = $('input[name=formValidate]:checked').val();
    if(filterDay == 'on'){
        var customerId = document.getElementById('customerId');
        var saleId = document.getElementById('saleId');
        var mobile_phone = document.getElementById('mobile_phone');
        var email = document.getElementById('email');

        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            type: "POST",
            data: {_token: CSRF_TOKEN, customerId:customerId.value, mobilePhone:mobile_phone.value, email:email.value, saleId:saleId.value},
            url: ROUTE + "/massivesVinculation/form/update",
            success: function (data)
            {     
                console.log(data);           
                if(data == 'true'){
                    $("#loaderGif").addClass('loaderGif');
                    window.location.href = ROUTE + "/massivesVinculation";
                }else if(data == 'result'){
                    $("#loaderGif").addClass('loaderGif');
                    window.location.href = ROUTE + "/massivesVinculation";
                }else{
                    alert('El formulario aun no ha sido firmado por el usuario');
                }
            }
        });
    }else{
        alert('Debe validar el formulario');
    }
}