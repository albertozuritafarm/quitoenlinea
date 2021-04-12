/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {



});

function chkChange(type, action, mainId, subMenu) {
    var main = document.getElementById("main_"+action+"_"+mainId);
    if (main.checked) {
        $(".child_secondary_" + action + "_" + mainId).prop("checked", true);
        $(".child_secondary_" + action + "_" + mainId).attr("checked", true);
    } else {
        $(".child_secondary_" + action + "_" + mainId).prop("checked", false);
        $(".child_secondary_" + action + "_" + mainId).attr("checked", false);
    }

    var selected = [];
    $(".child_secondary_" + action + "_" + mainId).each(function() {
        selected.push($(this).attr('id'));
    });
    selected.forEach(function(persona, index) {
        var chk = document.getElementById(persona);
        // Create a new 'change' event
        var event = new Event('change');

        // Dispatch it.
        chk.dispatchEvent(event);
    });
}

function chkSecondaryChange(type, action, secondaryId, mainId, subMenu) {
    var id = "secondary_"+action+"_"+mainId+"_"+secondaryId;
    var secondary = document.getElementById(id);
    var main = document.getElementById("main_"+action+"_"+mainId);
    if(secondary.checked == true){
        $(".child_third_" + action + "_" + secondaryId).prop("checked", true);
        $(main).prop("checked", true);
        $(".child_third_" + action + "_" + secondaryId).attr("checked", true);
        $(main).attr("checked", true);
    }else{
        $(".child_third_" + action + "_" + secondaryId).prop("checked", false);
        $(".child_third_" + action + "_" + secondaryId).attr("checked", false);
        //Validate if another secondary is selected
        var selected = [];
        var secondaryClass = "child_secondary_" + action + "_" + mainId;
        $.each($("."+secondaryClass+":checked"), function () {

            selected.push($(this).val());

        });
        if (selected.length == 0){
            $(main).prop("checked", false);
            $(main).attr("checked", false);
        }
    }
}
function chkThirdChange(type, action, thirdId, mainId, secondaryId) {
    var id = "third_"+action+"_"+mainId+"_"+thirdId;
    var third = document.getElementById(id);
    var secondary = document.getElementById("secondary_"+action+"_"+mainId+"_"+secondaryId);
    if(third.checked === true){;
        $(".child_secondary_" + action + "_" + secondaryId).prop("checked", true);
        $(secondary).prop("checked", true);
        $(".child_secondary_" + action + "_" + secondaryId).attr("checked", true);
        $(secondary).attr("checked", true);
    }else{
        //Validate if another secondary is selected
        var selected = [];
        var thirdClass = "child_third_" + action + "_" + secondaryId;
        $.each($("."+thirdClass+":checked"), function () {

            selected.push($(this).val());

        });
        if (selected.length == 0){
            $(secondary).prop("checked", false);
            $(secondary).attr("checked", false);
        }
    }
    //Validate Main Checkbox
    var main = document.getElementById("main_"+action+"_"+mainId);
    var selected = [];
    var checked = false;
    $(".child_secondary_" + action + "_" + mainId).each(function() {
        selected.push($(this).attr('id'));
    });
    selected.forEach(function(persona, index) {
        var chk = document.getElementById(persona);
        if(chk.checked === true){
            checked = true;
        }
    });
    $(main).prop("checked", checked);
    $(main).attr("checked", checked);
}

function submitForm(){
    var nameRol = document.getElementById('nameRol').value;
    var rolEntity = document.getElementById('rol_entity').value;
    var rol_type = document.getElementById('rol_type').value;
    var selected = [];
    var errorMessage = '';
    var errorMessageAlert = '';
    var newLine = "\r\n";
    $.each($(".chk:checked"), function () {

        selected.push($(this).val());

    });
    if(rolEntity === ''){
        errorMessage = errorMessage + 'Debe especificar una Entidad<br>';
        errorMessageAlert += 'Debe especificar una Entidad';
        errorMessageAlert += newLine;
    }
    if(rol_type === ''){
        errorMessage = errorMessage + 'Debe especificar un Tipo de Rol<br>';
        errorMessageAlert += 'Debe especificar un Tipo de Rol';
        errorMessageAlert += newLine;
    }
    if(nameRol === ''){
        errorMessage = errorMessage + 'Debe especificar un nombre<br>';
        errorMessageAlert += 'Debe especificar un nombre';
        errorMessageAlert += newLine;
    }
    if (selected.length == 0){
        errorMessage = errorMessage + 'Debe especificar unos permisos<br>';
        errorMessageAlert += 'Debe especificar unos permisos';
        errorMessageAlert += newLine;
    }
    if(errorMessage != ''){
        var errorMessageDiv = document.getElementById('errorMessageDiv');
        $(errorMessageDiv).removeClass('hidden');
        errorMessageDiv.innerHTML = errorMessage;
        alert(errorMessageAlert);
    }else{
        var errorMessageDiv = document.getElementById('errorMessageDiv');
        $(errorMessageDiv).addClass('hidden');
        
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var url = ROUTE + '/rol/store';
        $.ajax({
            url: url,
            type: "POST",
            data: {_token: CSRF_TOKEN, selected: selected, nameRol: nameRol, rolType: rol_type, rolEntity: rolEntity},
            success: function (result) {
                window.location.href = ROUTE + "/rol";
            }
        });
    }
}
function submitFormUpdate(){
    var nameRol = document.getElementById('nameRol').value;
    var idRol = document.getElementById('idRol').value;
    var rolEntity = document.getElementById('rol_entity').value;
    var rol_type = document.getElementById('rol_type').value;
    var selected = [];
    var errorMessage = '';
    var errorMessageAlert = '';
    var newLine = "\r\n";
    $.each($(".chk:checked"), function () {

        selected.push($(this).val());

    });
    if(rolEntity === ''){
        errorMessage = errorMessage + 'Debe especificar una Entidad<br>';
        errorMessageAlert += 'Debe especificar una Entidad';
        errorMessageAlert += newLine;
    }
    if(rol_type === ''){
        errorMessage = errorMessage + 'Debe especificar un Tipo de Rol<br>';
        errorMessageAlert += 'Debe especificar un Tipo de Rol';
        errorMessageAlert += newLine;
    }
    if(nameRol === ''){
        errorMessage = errorMessage + 'Debe especificar un nombre<br>';
        errorMessageAlert += 'Debe especificar un nombre';
        errorMessageAlert += newLine;
    }
    if (selected.length == 0){
        errorMessage = errorMessage + 'Debe especificar unos permisos<br>';
        errorMessageAlert += 'Debe especificar unos permisos';
        errorMessageAlert += newLine;
    }
    if(errorMessage != ''){
        var errorMessageDiv = document.getElementById('errorMessageDiv');
        $(errorMessageDiv).removeClass('hidden');
        errorMessageDiv.innerHTML = errorMessage;
        alert(errorMessageAlert);
    }else{
        var errorMessageDiv = document.getElementById('errorMessageDiv');
        $(errorMessageDiv).addClass('hidden');
        
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var url = ROUTE + '/rol/update';
        $.ajax({
            url: url,
            type: "POST",
            data: {_token: CSRF_TOKEN, selected: selected, nameRol: nameRol, idRol:idRol, rolType: rol_type, rolEntity: rolEntity},
            success: function (result) {
                window.location.href = ROUTE + "/rol";
            }
        });
    }
}







