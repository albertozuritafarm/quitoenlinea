/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){
    
    
});

function validateNumber(){
//    event.preventDefault();
    var number = document.getElementById("number").value;
    if(number === ''){
        document.getElementById("number").focus();
        alert('Debe ingresar un numero');
        return false;
    }else{
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var data = {number};
        var url = '/payments/validateNumber';
        $.ajax({
            type: "POST",
            data: {_token: CSRF_TOKEN, data},
            url: url,
            async: false,
            success: function (data) {
                if(data.success === 'false'){
                    alert('El numero ya se encuentra registado');
                    event.preventDefault();
                    return false;
                }else{
                    var response = true;
                    return true;
                }
            },
            complete: function(response){
                return response;
            }
        });
    }
}

function cashDivClick(){
    //Activate Cash Div
    var cash = document.getElementById("cash");
    $(cash).removeClass('wizard_inactivo');
    $(cash).addClass('wizard_activo');
    var cashDiv = document.getElementById("cashDiv");
    $(cashDiv).removeClass('hidden');
    $("#cashRadioBtn").prop("checked", true);
    
    //Inactivate datafast Div
    var datafast = document.getElementById("datafast");
    $(datafast).removeClass('wizard_activo');
    $(datafast).addClass('wizard_inactivo');
    var datafastDiv = document.getElementById("datafastDiv");
    $(datafastDiv).addClass('hidden');
    $("#datafastRadioBtn").prop("checked", false);
};
function datafastDivClick(){
    //Inactivate Cash Div
    var cash = document.getElementById("cash");
    $(cash).removeClass('wizard_activo');
    $(cash).addClass('wizard_inactivo');
    var cashDiv = document.getElementById("cashDiv");
    $(cashDiv).addClass('hidden');
    $("#cashRadioBtn").prop("checked", false);
    
    //Activate datafast Div
    var datafast = document.getElementById("datafast");
    $(datafast).removeClass('wizard_inactivo');
    $(datafast).addClass('wizard_activo');
    var datafastDiv = document.getElementById("datafastDiv");
    $(datafastDiv).removeClass('hidden');
    $("#datafastRadioBtn").prop("checked", true);
};

