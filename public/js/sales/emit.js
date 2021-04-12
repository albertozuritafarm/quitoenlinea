/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$( document ).ready(function() {
    
});

function sendVinculationFormLink(saleId){
    event.preventDefault();
    var r = confirm("¿Seguro que desea enviar el link?");
    if (r == true) {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var url = ROUTE + '/vinculation/send/link';
        $.ajax({
            url: url,
            type: "POST",
            /* send the csrf-token and the input to the controller */
            data: {_token: CSRF_TOKEN, saleId:saleId},
            success: function (result) {
                alert('Se ha enviado un correo con el enlace al Formulario de Vinculación');
            }
        });
    }
}

function validateFirstStep(){
    event.preventDefault();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = ROUTE + '/vinculation/confirm/complete';
    var saleId = document.getElementById('saleId').value;
    var insuranceBranch = document.getElementById('insuranceBranch').value;
    $.ajax({
        url: url,
        type: "POST",
        /* send the csrf-token and the input to the controller */
        data: {_token: CSRF_TOKEN, saleId:saleId},
        success: function (result) {
            if(result === 'success'){
                var url = ROUTE + '/sales/branch/emit/'+saleId+'/'+insuranceBranch;
                var next = '#emitStep';
                loadNextPage(url,next,'left','right');
            }else{
                alert(result);
            }
        }
    });
}

function nextStep(div1, div2) {
    var div = document.getElementById(div1);
    $(div).fadeOut('slow');
    $(div).addClass('hidden');
    var div = document.getElementById(div2);
    $(div).fadeIn('slow');
    $(div).removeClass('hidden');

    var wizard = document.getElementById(div1 + "Wizard");
    $(wizard).removeClass('wizard_activo');
    $(wizard).addClass('wizard_inactivo');
    var wizard = document.getElementById(div2 + "Wizard");
    $(wizard).removeClass('wizard_inactivo');
    $(wizard).addClass('wizard_activo');
}


function removeInputRedFocus(id){
    var id = document.getElementById(id);
    $(id).removeClass('inputRedFocus');
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