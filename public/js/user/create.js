/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {
    
    //Document Change
    $("#document").change(function () {
        var documentInput = document.getElementById("document");
        var e = document.getElementById("document_id");
        var document_id = e.options[e.selectedIndex].value;
        if (document_id == 1) {
            if (documentInput.value.length == 10) {
                console.log('igual a 10');
                if (isNaN(documentInput.value)) {
                    var txt = 'Por favor ingrese una cedula valida';
                    document.getElementById("documentError").innerHTML = txt;
                    document.getElementById("document").value = '';
                } else {
                    var txt = '';
                    document.getElementById("documentError").innerHTML = txt;
                }
            } else {
                var txt = 'Por favor ingrese una cedula valida';
                document.getElementById("documentError").innerHTML = txt;
                document.getElementById("document").value = '';
            }
        } else {
            var txt = '';
            document.getElementById("documentError").innerHTML = txt;
        }
    });
    //Document ID Change
    $("#document_id").change(function () {
        $('#document').prop('disabled', false);
        var documentInput = document.getElementById("document");
        var e = document.getElementById("document_id");
        var document_id = e.options[e.selectedIndex].value;
        if (document_id == 1) {
            if (documentInput.value.length == 10) {
                console.log('igual a 10');
                if (isNaN(documentInput.value)) {
                    var txt = 'Por favor ingrese una cedula valida';
                    document.getElementById("documentError").innerHTML = txt;
                    document.getElementById("document").value = '';
                } else {
                    var txt = '';
                    document.getElementById("documentError").innerHTML = txt;
                }
            } else {
                var txt = 'Por favor ingrese una cedula valida';
                document.getElementById("documentError").innerHTML = txt;
                document.getElementById("document").value = '';
            }
        } else {
            var txt = '';
            document.getElementById("documentError").innerHTML = txt;
        }
    });
    //Email Change
    $("#email").change(function () {
        ValidateEmail(this.value);
    });
    
    $('#first_name').on('keyup', function(){
        if( /[^a-zA-Z ]/.test( this.value ) ) {
            alert('No puede ingresar Caracteres Especiales');
            var str= this.value;
            var newStr = str.substring(0, str.length - 1);
            this.value=newStr;
            this.focus();
        }
        this.value = this.value.toLocaleUpperCase();

    });
    $('#last_name').on('keyup', function(){
        if( /[^a-zA-Z ]/.test( this.value ) ) {
            alert('No puede ingresar Caracteres Especiales');
            var str= this.value;
            var newStr = str.substring(0, str.length - 1);
            this.value=newStr;
            this.focus();
        }
        this.value = this.value.toLocaleUpperCase();

    });
    
});
function checkValidInputNumber() {
    $(".yearVehicle").keydown(function (event) {
        // Allow only delete, backspace,left arrow,right arrow, Tab and numbers
        if (!((event.keyCode == 46 ||
                event.keyCode == 8 ||
                event.keyCode == 37 ||
                event.keyCode == 39 ||
                event.keyCode == 9) ||
                $(this).val().length < 4 &&
                ((event.keyCode >= 48 && event.keyCode <= 57) ||
                        (event.keyCode >= 96 && event.keyCode <= 105)))) {
            // Stop the event
            event.preventDefault();
            return false;
        } else {
            return true;
        }
    });
}
;

function submitForm() {
    event.preventDefault();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var first_name = document.getElementById("first_name").value;
    var document_id = document.getElementById("document_id").value;
    var rol = document.getElementById("rol").value;
    var password = document.getElementById("password").value;
    var last_name = document.getElementById("last_name").value;
    var documentUser = document.getElementById("document").value;
    var email = document.getElementById("email").value;
    var tipo = document.getElementById("tipo").value;
    var passwordCheck = document.getElementById("passwordCheck").value;
    var agency = document.getElementById("agency").value;
    var typeSucre = document.getElementById("typeSucre").value;
    var url = ROUTE + '/user/store';
    //VALIDATE INPUTS
    var error = false;
    if(first_name == ''){ var first_name_input = document.getElementById("first_name"); $(first_name_input).addClass('inputRedFocus'); var error = true; }else{ var first_name_input = document.getElementById("first_name"); $(first_name_input).removeClass('inputRedFocus'); }
    if(last_name == ''){ var last_name_input = document.getElementById("last_name"); $(last_name_input).addClass('inputRedFocus'); var error = true; }else{ var last_name_input = document.getElementById("last_name"); $(last_name_input).removeClass('inputRedFocus'); }
    if(document_id === ''){ var document_id_input = document.getElementById("document_id"); $(document_id_input).addClass('inputRedFocus'); var error = true; }else{ var document_id_input = document.getElementById("document_id"); $(document_id_input).removeClass('inputRedFocus'); }
    if(documentUser == ''){ var documentUser_input = document.getElementById("document"); $(documentUser_input).addClass('inputRedFocus'); var error = true; }else{ var documentUser_input = document.getElementById("document"); $(documentUser_input).removeClass('inputRedFocus'); }
    if(email == ''){ var email_input = document.getElementById("email"); $(email_input).addClass('inputRedFocus'); var error = true; }else{ var email_input = document.getElementById("email"); $(email_input).removeClass('inputRedFocus'); }
    if(rol == ''){ var rol_input = document.getElementById("rol"); $(rol_input).addClass('inputRedFocus'); var error = true; }else{ var rol_input = document.getElementById("rol"); $(rol_input).removeClass('inputRedFocus'); }
    if(tipo == ''){ var tipo_input = document.getElementById("tipo"); $(tipo_input).addClass('inputRedFocus'); var error = true; }else{ var tipo_input = document.getElementById("tipo"); $(tipo_input).removeClass('inputRedFocus'); }
    if(password == ''){ var password_input = document.getElementById("password"); $(password_input).addClass('inputRedFocus'); var error = true; }else{ var password_input = document.getElementById("password"); $(password_input).removeClass('inputRedFocus'); }
    if(passwordCheck == ''){ var passwordCheck_input = document.getElementById("passwordCheck"); $(passwordCheck_input).addClass('inputRedFocus'); var error = true; }else{ var passwordCheck_input = document.getElementById("passwordCheck"); $(passwordCheck_input).removeClass('inputRedFocus'); }
    if(document.getElementById("channel").value == ''){ var channel_input = document.getElementById("channel"); $(channel_input).addClass('inputRedFocus'); var error = true; }else{ var channel_input = document.getElementById("channel"); $(channel_input).removeClass('inputRedFocus'); }
    if(agency == '' || agency == '0'){ var agency_input = document.getElementById("agency"); $(agency_input).addClass('inputRedFocus'); var error = true; }else{ var agency_input = document.getElementById("agency"); $(agency_input).removeClass('inputRedFocus'); }
    if(typeSucre == '' || typeSucre == '0'){ var typeSucre_input = document.getElementById("typeSucre"); $(typeSucre_input).addClass('inputRedFocus'); var error = true; }else{ var typeSucre_input = document.getElementById("typeSucre"); $(typeSucre_input).removeClass('inputRedFocus'); }
    if(error == false){
        $.ajax({
            url: url,
            type: "POST",
            /* send the csrf-token and the input to the controller */
            data: {_token: CSRF_TOKEN, first_name: first_name, document_id: document_id, rol:rol, password:password, last_name:last_name, document:documentUser, email:email, tipo:tipo, passwordCheck:passwordCheck, agency:agency, typeSucre:typeSucre},
            dataType: 'JSON',
            success: function (data) {
                if(data.success === 'false'){
                    var resultMessage = document.getElementById("resultMessage");
                    $(resultMessage).removeClass('hidden');
                    resultMessage.innerHTML = data.msg;
                    if(data.input == 'password'){
                        var password_input = document.getElementById("password");
                        $(password_input).addClass('inputRedFocus');
                        var passwordCheck_input = document.getElementById("passwordCheck");
                        $(passwordCheck_input).addClass('inputRedFocus');
                    }else{
                        var input = document.getElementById(data.input);
                        $(input).addClass('inputRedFocus');
                    }
                }else{
                    window.location.href =  ROUTE + "/user";
                }
            }
        });
    }else{
        var resultMessage = document.getElementById("resultMessage");
        $(resultMessage).removeClass('hidden');
        resultMessage.innerHTML = 'Por favor revise todos los campos';
    }
}

function typeSucreChange(id){
    event.preventDefault();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = ROUTE + '/user/type/sucre/change';
    $.ajax({
            url: url,
            type: "POST",
            /* send the csrf-token and the input to the controller */
            data: {_token: CSRF_TOKEN, id: id},
            beforeSend: function () {
                // Show Loader
                $("#loaderGif").addClass('loaderGif');
//                $("#loaderBody").addClass('loaderBody');
            },
            success: function (data) {
                document.getElementById("rol").innerHTML = data['rol']; 
                document.getElementById("channel").innerHTML = data['channel']; 
            },
            complete: function () {
                //Hide Loader
                var loaderGif = document.getElementById("loaderGif");
                loaderGif.classList.remove("loaderGif");
//                var loaderBody = document.getElementById("loaderBody");
//                loaderBody.classList.remove("loaderBody");
            }
        });
}
