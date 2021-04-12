/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {

    $("#document_id").change(function () {
        var document_id = document.getElementById("document_id");
        var documentInput = document.getElementById("document");
        if (document_id.value == 1) {
            if (documentInput.value.length == 10) {
                validateDocument();
                $(documentInput).focus();
            } else {
                document.getElementById("document_id").value = '0';
                alert('La cedula debe tener 10 digitos');
                $(documentInput).focus();
            }
        }
    });

    //Btn Add Rubros
    document.getElementById("btnRubros").onclick = function () {
        event.preventDefault();
        var table = document.getElementById("rubrosTable");
        var rowCountTable = table.rows.length;
        $("#ValueRubro").removeClass('inputRedFocus'); 
        if (rowCountTable > 1) {
            $('#btnRubros').attr('disabled', 'disabled');
            $('#btnRubros').prop('disabled', true);
        } else {
            thirdStepValidate();
        }
    };
    
    //Value Rubro Change
//    $("#ValueRubro").change(function () {
//        var valueRubro = document.getElementById("ValueRubro").value;
//        document.getElementById("ValueRubro").value = currency(valueRubro, { separator: '' }).format();
//    });
    
    setInputFilter(document.getElementById("ValueRubro"), function(value) {
        return /^-?\d*[.]?\d{0,2}$/.test(value); 
    });

    // Third Step Validation
    function thirdStepValidate() {
        var rubros = document.getElementById("rubros");
        var ValueRubro = document.getElementById("ValueRubro");
        var validate = false;

        if (rubros.value === 0 || ValueRubro.value === '') {
            var customerAlert = document.getElementById("Alert");
            customerAlert.classList.remove("hidden");
            customerAlert.classList.remove("visible");
            validate = true;
            return false;
        }

        //if(rubros.value === ""){ $(rubros).addClass('inputRedFocus'); validate = true; }else{ $(rubros).removeClass('inputRedFocus'); }
       
        var rubroPriceMax = document.getElementById("max_"+rubros.value);
        var rubroPriceMin =document.getElementById("min_"+rubros.value);
        
        var rubroValueString = ValueRubro.value.replace(/,/g, ''); 
        if((rubroValueString <= parseFloat(rubroPriceMin.value)) || (rubroValueString > parseFloat(rubroPriceMax.value))){
            var customerAlert = document.getElementById("Alert");
            customerAlert.classList.remove("hidden");
            customerAlert.classList.remove("visible");
            customerAlert.innerHTML='<center> El valor asegurado para el rubro seleccionado no esta en el rango establecido en min: ' + rubroPriceMin.value + ' max: ' + rubroPriceMax.value + '</center>';
            $(ValueRubro).addClass('inputRedFocus'); 
            validate = true;
            return false
        } else {
            $("#Alert").addClass("hidden");
        }
        

        if (validate === false) { addRow(); }
    }

    // Vehicles Table
    function addRow() {

        var plateAlert = document.getElementById("plateAlert");
        $(plateAlert).addClass('hidden');

        var rubros = document.getElementById("rubros");
        var ValueRubro = document.getElementById("ValueRubro");
        var bodyTable = document.getElementById("rubrosBodyTable");
        var rowCount = bodyTable.rows.length;
        var row = bodyTable.insertRow(rowCount);


    //    row.insertCell(0).innerHTML = rowCount; 
        row.insertCell(0).innerHTML = rubros.value;
        row.insertCell(1).innerHTML = ValueRubro.value;
        row.insertCell(2).innerHTML = '<button type="submit" class="btn btn-link" onClick="editRow(\'' + rubros.value + '\',\'' + ValueRubro.value + '\',this)"><span class="glyphicon glyphicon-pencil" style="color:green;font-size:15px"></span></button><button type="submit" class="btn btn-link" onClick="deleteRow(this)"><span class="glyphicon glyphicon-remove" style="color:red;font-size:15px"></span></button>';

        var rowRubros = 0;
        $('#rubrosTable tr').each(function () {
            var RubrosFirst = $(this).find("td:first").html();
    //        var vehiclesSecond = $(this).find("td:nth-child(2)").html();    
    //        var vehiclesThird = $(this).find("td:nth-child(3)").html();    
        });

        var table = document.getElementById("rubrosTable");

        var rowCountTable = table.rows.length;

        if (rowCountTable > 1) {
            $('#btnRubros').attr('disabled', 'disabled');
            $('#btnRubros').prop('disabled', true);
        }

        //Return Inputs no Null
        rubros.value = '';
        ValueRubro.value = '';

        //Disable Inputs
        $('#rubros').prop('disabled', true);
        $('#ValueRubro').prop('disabled', true);
    }

    var tableBorder2 = document.getElementById("tableUsers_length");
    $(tableBorder2).addClass('hidden');
    var tableBorder2 = document.getElementById("tableUsers_info");
    $(tableBorder2).addClass('hidden');
    var tableBorder2 = document.getElementById("tableUsers_paginate");
    $(tableBorder2).addClass('hidden');
/*
    function btnRubros() {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var plate = document.getElementById("plate").value;
        var model = document.getElementById("model").value;
        var document = document.getElementById("document").value;
        var data = "{'plate':'" + plate + "','document':'" + document + "' ,'model':'" + model + "'}";
        var url = ROUTE + '/vehicles/tempJson/';
        $.ajax({
            url: url,
            type: "POST",
            /* send the csrf-token and the input to the controller */
 /*           data: {_token: CSRF_TOKEN, plate: plate, model: model},
            dataType: 'JSON',
            success: function (result) {
                   window.console.log('Successful');
            }
        });
    }
*/
    function firstStepBtnNextOld() {

        if ($("input[id^='productCheckBox']:checked").length !== 1) {
            var productAlert = document.getElementById("productAlert");
            productAlert.classList.remove("hidden");
            productAlert.classList.remove("visible");

        } else {

            $(".alert").addClass('hidden');
            nextStep('firstStep', 'secondStep')
        }
    }
    function emailIsValid(email) {
        return /\S+@\S+\.\S+/.test(email);
    }
    function validateDocument() {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var data = {document: document.getElementById("document").value};
        var url = ROUTE + '/sales/validateDocument';
        $.ajax({
            type: "POST",
            data: {_token: CSRF_TOKEN, data},
            url: url,
            async: false,
            success: function (data) {
                if (data === 'invalid') {
                    $("#suggesstion-box").hide();
                    document.getElementById("first_name").value = "";
                    document.getElementById("document_id").value = "0";
                    document.getElementById("last_name").value = "";
                    document.getElementById("mobile_phone").value = "";
                    document.getElementById("phone").value = "";
                    document.getElementById("address").value = "";
                    document.getElementById("email").value = "";
                    document.getElementById("country").value = "0";
                    document.getElementById("province").value = "0";
                    document.getElementById("city").value = "0";
                    alert('El documento ingresado es invalido');
                }
            },
            error: function () {
                return "Hello";
            }
        });
    }


    function validate()
    {
        $('input:text', '#salesForm').removeClass('error');
        $('input:text[value=""]', '#salesForm').addClass('error');
        return false;
    }

    function fourthStepBtnBack() {
        //Hide First Step Div
        var firstStep = document.getElementById("thirdStep");
        $(firstStep).removeClass('hidden');
        $(firstStep).addClass('visible');

        //Inactive First Step Wizard
        var firstStepWizard = document.getElementById("thirdStepWizard");
        $(firstStepWizard).removeClass('wizard_inactivo');
        $(firstStepWizard).addClass('wizard_activo');

        //Show Second Step Div        
        var secondStep = document.getElementById("fourthStep");
        $(secondStep).removeClass('visible');
        $(secondStep).addClass('hidden');

        //Active Second Step Wizard
        var secondStepWizard = document.getElementById("fourthStepWizard");
        $(secondStepWizard).removeClass('wizard_activo');
        $(secondStepWizard).addClass('wizard_inactivo');
    }
    //fifth Step Button Back
//    document.getElementById("fifthStepBtnBack").onclick = function () {
//        fifthStepBtnBack();
//    };

    function fifthStepBtnBack() {
        //Hide First Step Div
        var firstStep = document.getElementById("fourthStep");
        $(firstStep).removeClass('hidden');
        $(firstStep).addClass('visible');

        //Inactive First Step Wizard
        var firstStepWizard = document.getElementById("fourthStepWizard");
        $(firstStepWizard).removeClass('wizard_inactivo');
        $(firstStepWizard).addClass('wizard_activo');

        //Show Second Step Div        
        var secondStep = document.getElementById("fifthStep");
        $(secondStep).removeClass('visible');
        $(secondStep).addClass('hidden');

        //Active Second Step Wizard
        var secondStepWizard = document.getElementById("fifthStepWizard");
        $(secondStepWizard).removeClass('wizard_activo');
        $(secondStepWizard).addClass('wizard_inactivo');
    }

    //Check Products Only one
    $('.check').click(function () {
        $('.check').not(this).prop('checked', false);
    });

    document.getElementById("document").onclick = function () {
        $("#suggesstion-box").hide();
        document.getElementById("first_name").value = "";
        document.getElementById("second_name").value = "";
        document.getElementById("document_id").value = "0";
        document.getElementById("last_name").value = "";
        document.getElementById("second_last_name").value = "";
        document.getElementById("mobile_phone").value = "";
        document.getElementById("phone").value = "";
        document.getElementById("address").value = "";
        document.getElementById("email").value = "";
        document.getElementById("country").value = "0";
        document.getElementById("province").value = "0";
        document.getElementById("city").value = "0";
        document.getElementById("first_name").disabled = true;
        document.getElementById("second_name").disabled = true;
        document.getElementById("last_name").disabled = true;
        document.getElementById("second_last_name").disabled = true;
        document.getElementById("document_id").disabled = true;
    };
    $("#document").keyup(function () {
        $("#suggesstion-box").hide();
        document.getElementById("first_name").value = "";
        document.getElementById("second_name").value = "";
        document.getElementById("document_id").value = "0";
        document.getElementById("last_name").value = "";
        document.getElementById("second_last_name").value = "";
        document.getElementById("mobile_phone").value = "";
        document.getElementById("phone").value = "";
        document.getElementById("address").value = "";
        document.getElementById("email").value = "";
        document.getElementById("country").value = "0";
        document.getElementById("province").value = "0";
        document.getElementById("city").value = "0";
        document.getElementById("first_name").disabled = true;
        document.getElementById("second_name").disabled = true;
        document.getElementById("last_name").disabled = true;
        document.getElementById("second_last_name").disabled = true;
        document.getElementById("document_id").disabled = true;
    });
    $(document).on('click', 'body *', function () {
        $("#suggesstion-box").hide();
    });


    $("#document").keyup(function () {
//        if (this.value.length < 4) {
//            $("#suggesstion-box").hide();
//            return;
//        } else {
//
//
//            $.ajax({
//                type: "GET",
//                data: 'keyword=' + $(this).val(),
//                url: '/customer/document/check/' + $(this).val(),
//                beforeSend: function () {
//                    $("#document").css("background", "#FFF url(LoaderIcon.gif) no-repeat 165px");
//                },
//                success: function (data) {
//                    $("#suggesstion-box").show();
//                    $("#suggesstion-box").html(data);
//                    $("#document").css("background", "#FFF");
//                }
//            });
//        }
    });
    //Validate second Step Form
    //Validate Inputs
//    document.getElementById("first_name").change = function () {
    $("#first_name").change(function () {
        $(this).removeClass('inputRedFocus');
    });
    $("#document").change(function () {
        $(this).removeClass('inputRedFocus');
    });
    $("#document_id").change(function () {
        $(this).removeClass('inputRedFocus');
    });
    $("#last_name").change(function () {
        $(this).removeClass('inputRedFocus');
    });
    $("#address").change(function () {
        $(this).removeClass('inputRedFocus');
    });
    $("#mobile_phone").change(function () {
        $(this).removeClass('inputRedFocus');
    });
    $("#phone").change(function () {
        $(this).removeClass('inputRedFocus');
    });
    $("#email").change(function () {
        $(this).removeClass('inputRedFocus');
    });
    $("#country").change(function () {
        $(this).removeClass('inputRedFocus');
    });
    $("#province").change(function () {
        $(this).removeClass('inputRedFocus');
    });
    $("#city").change(function () {
        $(this).removeClass('inputRedFocus');
    });

    // secondform
    $("#principal_street").change(function () {
        $(this).removeClass('inputRedFocus');
    });
    $("#secondary_street").change(function () {
        $(this).removeClass('inputRedFocus');
    });
    $("#number").change(function () {
        $(this).removeClass('inputRedFocus');
    });
    $("#aparment").change(function () {
        $(this).removeClass('inputRedFocus');
    });
    $("#provinces").change(function () {
        $(this).removeClass('inputRedFocus');
    });
    $("#cities").change(function () {
        $(this).removeClass('inputRedFocus');
    });
    $("#cantones").change(function () {
        $(this).removeClass('inputRedFocus');
    });

    //Email Change
    $("#email").change(function () {
        var email =  document.getElementById("email").value;
        ValidateEmail(email);
    });

    $('#first_name').on('keyup', function () {
        if (/[^a-zA-Z ]/.test(this.value)) {
            alert('No puede ingresar Caracteres Especiales');
            var str = this.value;
            var newStr = str.substring(0, str.length - 1);
            this.value = newStr;
            this.focus();
        }
        this.value = this.value.toLocaleUpperCase();

    });

    $('#last_name').on('keyup', function () {
        if (/[^a-zA-Z ]/.test(this.value)) {
            alert('No puede ingresar Caracteres Especiales');
            var str = this.value;
            var newStr = str.substring(0, str.length - 1);
            this.value = newStr;
            this.focus();
        }
        this.value = this.value.toLocaleUpperCase();

    });
    $('#document').on('keyup', function () {
        if (/[^a-zA-Z0-9]/.test(this.value)) {
            alert('No puede ingresar Caracteres Especiales');
            var str = this.value;
            var newStr = str.substring(0, str.length - 1);
            this.value = newStr;
            this.focus();
        }
        this.value = this.value.toLocaleUpperCase();

    });
    $('#plate').on('keyup', function () {
        if (/[^a-zA-Z0-9]/.test(this.value)) {
            alert('No puede ingresar Caracteres Especiales');
            var str = this.value;
            var newStr = str.substring(0, str.length - 1);
            this.value = newStr;
            this.focus();
        }
        this.value = this.value.toLocaleUpperCase();

    });
    $('#mobile_phone').on('keyup', function () {
        if (/[^0-9]/.test(this.value)) {
            alert('No puede ingresar Caracteres Especiales');
            var str = this.value;
            var newStr = str.substring(0, str.length - 1);
            this.value = newStr;
            this.focus();
        }
        this.value = this.value.toLocaleUpperCase();

    });
    $('#phone').on('keyup', function () {
        if (/[^0-9]/.test(this.value)) {
            alert('No puede ingresar Caracteres Especiales');
            var str = this.value;
            var newStr = str.substring(0, str.length - 1);
            this.value = newStr;
            this.focus();
        }
        this.value = this.value.toLocaleUpperCase();

    });

    function setInputFilter(textbox, inputFilter) {
        ["input"].forEach(function(event) {
            textbox.addEventListener(event, function() {
            if (inputFilter(this.value)) {
                this.oldValue = this.value;
                this.oldSelectionStart = this.selectionStart;
                this.oldSelectionEnd = this.selectionEnd;
            } else if (this.hasOwnProperty("oldValue")) {
                this.value = this.oldValue;
                this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
            } else {
                this.value = "";
            }
            });
        });
    }
    
    setInputFilter(document.getElementById("document"), function(value) {
        return /^-?\d*$/.test(value); 
    });

});

function validateDocument() {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var data = {document: document.getElementById("document").value};
    var url = ROUTE + '/sales/validateDocument';
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, data},
        url: url,
        async: false,
        success: function (data) {
            if (data === 'invalid') {
                alert('El documento ingresado es invalido');
            } else {
               formAutoFill(document.getElementById("document").value);
            }
        },
        error: function () {
            return "Hello";
        }
    });
}

function editRow(rubros, valuerubro, obj) {
    event.preventDefault();
    document.getElementById("rubros").value = rubros;
    document.getElementById("ValueRubro").value = valuerubro;
    
    var index = obj.parentNode.parentNode.rowIndex;
    var table = document.getElementById("rubrosTable");
    table.deleteRow(index);

    var table = document.getElementById("rubrosTable");

    $('#btnRubros').removeAttr('disabled');
    $('#btnRubros').prop('disabled', false);
    $('#rubros').removeAttr('disabled');
    $('#ValueRubro').prop('disabled', false);
}

function deleteRow(obj) {
    var index = obj.parentNode.parentNode.rowIndex;
    var table = document.getElementById("rubrosTable");

    table.deleteRow(index);

    $('#btnRubros').removeAttr('disabled');
    $('#btnRubros').prop('disabled', false);
    $('#rubros').removeAttr('disabled');
    $('#ValueRubro').prop('disabled', false);
}


function selectDocument(value) {
    $("#document").val(value);
    $("#suggesstion-box").hide();

    formAutoFill(value);
}

function formAutoFill(val) {
    var documentNumber = val;
    var url = ROUTE + '/customer/document/autofill/' + documentNumber;
    if (documentNumber) {
        $.ajax({
            url: url,
            type: "GET",
            dataType: "json",
            beforeSend: function () {
//                 Show Loader
                $("#loaderGif").addClass('loaderGif');
//                $("#loaderBody").addClass('loaderBody');
            },
            success: function (data) {
                if (data.success == 'true') {
                    document.getElementById("first_name").disabled = true;
                    document.getElementById("last_name").disabled = true;
                    document.getElementById("document_id").disabled = true;
                    if(data.second_name === null){ document.getElementById("second_name").disabled = false; }else{ document.getElementById("second_name").disabled = true;  }
                    if(data.second_last_name === null){ document.getElementById("second_last_name").disabled = false; }else{ document.getElementById("second_last_name").disabled = true;  }

                    //Province Select
//                    var prov = document.getElementById("province");
//                    prov.innerHTML = '';
//                    var opt = document.createElement('option');
//                    var opt2 = document.createElement('option');
//                    opt.value = '0';
//                    opt.text = '--Escoja Una--';
//                    opt2.value = data['province_id'];
//                    opt2.text = data['province_name'];
//                    prov.appendChild(opt);
//                    prov.appendChild(opt2);

                    //City Select
//                    var city = document.getElementById("city");
//                    city.innerHTML = '';
//                    var cityOpt = document.createElement('option');
//                    var cityOpt2 = document.createElement('option');
//                    cityOpt.value = '0';
//                    cityOpt.text = '--Escoja Una--';
//                    city.appendChild(cityOpt);
//                    cityOpt2.value = data['city_id'];
//                    cityOpt2.text = data['city_name'];
//                    city.appendChild(cityOpt2);
            
                    document.getElementById("birthdate").value = data['birthdate'];
                    $("#salesForm").autofill(data);
                } else {
                    document.getElementById("first_name").disabled = false;
                    document.getElementById("last_name").disabled = false;
                    document.getElementById("document_id").disabled = false;
                    document.getElementById("second_name").disabled = false;
                    document.getElementById("second_last_name").disabled = false;
                    //City Select
                    var city = document.getElementById("city");
                    city.innerHTML = '';
                    var cityOpt = document.createElement('option');
                    cityOpt.value = '0';
                    cityOpt.text = '--Escoja Una--';
                    city.appendChild(cityOpt);
                }
            },
            complete: function () {
                //Hide Loader
                var loaderGif = document.getElementById("loaderGif");
                loaderGif.classList.remove("loaderGif");
//                var loaderBod
//                y = document.getElementById("loaderBody");
//                loaderBody.classList.remove("loaderBody");
            }
        });
        secondStepFormValidate();
    } else {
        $('select[name="province"]').empty();
    }

}
function myButton_onclick() {
    $(".alert").addClass('hidden');
    ;
}

function secondStepFormValidate() {
    //Validate Inputs
    var documentNumber = document.getElementById("document");
//        if (documentNumber.value !== "") {
    $(documentNumber).removeClass('inputRedFocus');
//            validate = 'true';
//        }
    var first_name = document.getElementById("first_name");
//        if (first_name.value !== "") {
    $(first_name).removeClass('inputRedFocus');
//            validate = 'true';
//        }
    var document_id = document.getElementById("document_id");
//        if (document_id.value !== "0") {
    $(document_id).removeClass('inputRedFocus');
//            validate = 'true';
//        }
    var last_name = document.getElementById("last_name");
//        if (last_name.value !== "") {
    $(last_name).removeClass('inputRedFocus');
//            validate = 'true';
//        }
    var second_last_name = document.getElementById("second_last_name");
//        if (last_name.value !== "") {
    $(second_last_name).removeClass('inputRedFocus');
//            validate = 'true';
//        }
    var address = document.getElementById("address");
//        if (address.value !== "") {
    $(address).removeClass('inputRedFocus');
//            validate = 'true';
//        }
    var mobile_phone = document.getElementById("mobile_phone");
//        if (mobile_phone.value !== "") {
    $(mobile_phone).removeClass('inputRedFocus');
//            validate = 'true';
//        }
    var phone = document.getElementById("phone");
//        if (phone.value !== "") {
    $(phone).removeClass('inputRedFocus');
//            validate = 'true';
//        }
    var email = document.getElementById("email");
//        if (email.value !== "") {
    $(email).removeClass('inputRedFocus');
//            validate = 'true';
//        }
    var country = document.getElementById("country");
//        if (country.value !== "0") {
    $(country).removeClass('inputRedFocus');
//            validate = 'true';
//        }
    var province = document.getElementById("province");
//        if (province.value !== "0") {
    $(province).removeClass('inputRedFocus');
//            validate = 'true';
//        }
    var city = document.getElementById("city");
//        if (city.value !== "0") {
    $(city).removeClass('inputRedFocus');
//            validate = 'true';
//        }
    var customerAlert = document.getElementById("customerAlert");
    $(customerAlert).removeClass('visible');
    $(customerAlert).addClass('hidden');
}
;

;
function validateCode() {
    event.preventDefault();
    var url = ROUTE + "/sales/activate";
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var code = document.getElementById('code').value;
    var salId = document.getElementById('salId').value;
    var data = {code: code};
    $.ajax({
        url: url,
        type: "POST",
        data: {_token: CSRF_TOKEN, code, salId},
        success: function (data)
        {
            var uploadPic = document.getElementById("resultMessage");
            uploadPic.innerHTML = data.data;
            if (data.success == 'true') {
                document.getElementById("confirmModal").click();
            }
        }
    });
}
;
function resendCode() {
    event.preventDefault();
    var url = ROUTE + "/sales/resend/code";
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var salId = document.getElementById('salId').value;
    var customerMobilePhone = document.getElementById("mobile_phone").value;
//    var data = {code: salId};
    $.ajax({
        url: url,
        type: "POST",
        data: {_token: CSRF_TOKEN, salId, customerMobilePhone},
        success: function (data)
        {
            var uploadPic = document.getElementById("resultMessage");
            uploadPic.innerHTML = data;
        }
    });
}
;

//function ValidateEmail(mail)
//{
//    if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(document.getElementById("email").value))
//    {
//        document.getElementById("emailError").innerHTML = '';
//        return (true);
//    } else {
//        var txt = 'Por favor ingrese un correo valido';
//        document.getElementById("emailError").innerHTML = txt;
//        return (false);
//    }
//}
function documentBtn() {
    validateDocument();
}

function selectProduct(id, name, value) {
    document.getElementById("productCheckBox").value = id;
    document.getElementById("productNameCheckBox").value = name;
    document.getElementById("productValueCheckBox").value = value;
//    clearSecondStepForm();
    thirdStepBtnNext();
}

function nextStep(div1, div2) {
//    event.preventDefault();
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
function firstStepBtnNext() {

    event.preventDefault();
    //Validate Variable
    var validate = 'false';

    //Validate Inputs
    var documentNumber = document.getElementById("document");
    if (documentNumber.value === "") {
        $(documentNumber).addClass('inputRedFocus');
        validate = 'true';
    }
    var first_name = document.getElementById("first_name");
    if (first_name.value === "" || first_name.value.length > 30) {
        alert('El primer nombre excede los 30 caracteres.');
        $(first_name).addClass('inputRedFocus');
        validate = 'true';
    }
    var second_name = document.getElementById("second_name");
    if (second_name.value.length > 30) {
        alert('El segundo nombre excede los 30 caracteres.');
        $(second_name).addClass('inputRedFocus');
        validate = 'true';
    }
    var document_id = document.getElementById("document_id");
    if (document_id.value === "0") {
        $(document_id).addClass('inputRedFocus');
        validate = 'true';
    } else if (document_id.value === "1") {
        if (isNaN(documentNumber.value)) {
            $(documentNumber).addClass('inputRedFocus');
            $(document_id).addClass('inputRedFocus');
            validate = 'true';
        }
    }
    var last_name = document.getElementById("last_name");
    if (last_name.value === "" || last_name.value.length > 30) {
        alert('El primer apellido excede los 30 caracteres.');
        $(last_name).addClass('inputRedFocus');
        validate = 'true';
    }
    var second_last_name = document.getElementById("second_last_name");
    if (second_last_name.value === "" || second_last_name.value.length > 30) {
        alert('El segundo apellido excede los 30 caracteres.');
        $(second_last_name).addClass('inputRedFocus');
        validate = 'true';
    }
    var address = document.getElementById("address");
    if (address.value === "" || address.value.length > 200) {
        alert('La dirección excede los 200 caracteres.');
        $(address).addClass('inputRedFocus');
        validate = 'true';
    }
    var mobile_phone = document.getElementById("mobile_phone");
    if (mobile_phone.value === "") {
        $(mobile_phone).addClass('inputRedFocus');
        validate = 'true';
    } else {
        if (isNaN(mobile_phone.value)) {
            $(mobile_phone).addClass('inputRedFocus');
            validate = 'true';
        }
        if (mobile_phone.value.length != 10) {
            alert('El número de celular debe tener 10 dígitos.');
            $(mobile_phone).addClass('inputRedFocus');
            validate = 'true';
        }
    }

    var phone = document.getElementById("phone");
    if (phone.value === "") {
        $(phone).addClass('inputRedFocus');
        validate = 'true';
    } else {
        if (isNaN(phone.value)) {
            $(phone).addClass('inputRedFocus');
            validate = 'true';
        }
        if (phone.value.length != 9) {
            alert('El número de celular debe tener 9 dígitos.');
            $(phone).addClass('inputRedFocus');
            validate = 'true';
        }
    }

    var email = document.getElementById("email");
    var emailValidate = ValidateEmail(email.value);
//        console.log(emailValidate);
    if (email.value === ""  || email.value.length > 100) {
        alert('El email excede los 100 caracteres.');
        $(email).addClass('inputRedFocus');
        validate = 'true';
    } else if (emailValidate === false) {
        $(email).addClass('inputRedFocus');
        validate = 'true';

    }
    var country = document.getElementById("country");
    if (country.value === "0") {
        $(country).addClass('inputRedFocus');
        validate = 'true';
    }
    var province = document.getElementById("province");
    if (province.value === "0" || province.value === "") {
        $(province).addClass('inputRedFocus');
        validate = 'true';
    }
    var city = document.getElementById("city");
    if (city.value === "0" || city.value === "") {
        $(city).addClass('inputRedFocus');
        validate = 'true';
    }
    var birthdate = document.getElementById("birthdate");
    if (birthdate.value === "") {
        $(birthdate).addClass('inputRedFocus');
        validate = 'true';
    }else{
        var age = checkAge(birthdate.value);
        if(age < 17){
            $(birthdate).addClass('inputRedFocus');
            validate = 'true';    
        }else{
            $(birthdate).removeClass('inputRedFocus');
        }
    }

    var customerAlert = document.getElementById("customerAlert");
    if (validate === 'true') {
        customerAlert.classList.remove("hidden");
        $(customerAlert).addClass("visible");
        return false;
    }else{
        customerAlert.classList.remove("visible");
        $(customerAlert).addClass("hidden");
    }
    
    nextStep('firstStep', 'secondStep');
}
function secondStepBtnNext() {
    event.preventDefault();
    //Validate Variable
    var validate = 'false';

    // validar datos
    var principal_street = document.getElementById("principal_street");
    if (principal_street.value === "") {
        $(principal_street).addClass('inputRedFocus');
        validate = 'true';
    }
    var secondary_street = document.getElementById("secondary_street");
    if (secondary_street.value === "") {
        $(secondary_street).addClass('inputRedFocus');
        validate = 'true';
    }
    var number = document.getElementById("number");
    if (number.value === "") {
        $(number).addClass('inputRedFocus');
        validate = 'true';
    }
    /*
    var aparment = document.getElementById("aparment");
    if (aparment.value === "") {
        $(aparment).addClass('inputRedFocus');
        validate = 'true';
    }
    */
    var province = document.getElementById("provinces");
    if (province.value === "0" || province.value === "") {
        $(province).addClass('inputRedFocus');
        validate = 'true';
    }
    var city = document.getElementById("cities");
    if (city.value === "0" || city.value === "") {
        $(city).addClass('inputRedFocus');
        validate = 'true';
    }
    
    var tbl = document.getElementById('rubrosBodyTable');
    if (tbl.rows.length === 0) {
        var Alert = document.getElementById('Alert');
        $(Alert).removeClass('hidden');
    } 

    var customerAlert = document.getElementById("customerAlert1");
    if (validate === 'true') {
        customerAlert.classList.remove("hidden");
        $(customerAlert).addClass("visible");
        return false;
    }else{
        customerAlert.classList.remove("visible");
        $(customerAlert).addClass("hidden");
    } 

    //Obtain rubros Data from Step 2
    var TableData = new Array();

    $('#rubrosBodyTable tr').each(function (row, tr) {
        TableData[row] = {
            "name_rubro": $(tr).find('td:eq(0)').text(),
            "assuredValue": $(tr).find('td:eq(1)').text()
        };
    });

    name_rubro = TableData[0].name_rubro;
    assured_value = TableData[0].assuredValue;

    //
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = ROUTE + '/sales/R4/check/price';
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, nameRubro:name_rubro, assuredValue:assured_value},
        url: url,
        beforeSend: function () {
            // Show Loader
            $("#loaderGif").addClass('loaderGif');
//                $("#loaderBody").addClass('loaderBody');
        },
        success: function (data) {
            document.getElementById("productsDiv").innerHTML = data;
            nextStep('secondStep','thirdStep');
        },
        complete: function () {
            //Hide Loader
            var loaderGif = document.getElementById("loaderGif");
            loaderGif.classList.remove("loaderGif");
//                var loaderBody = document.getElementById("loaderBody");
//                loaderBody.classList.remove("loaderBody");
        }
    });
    //nextStep('secondStep', 'thirdStep');
}
function secondStepBtnBack() {
    //Hide First Step Div
    var firstStep = document.getElementById("firstStep");
    $(firstStep).removeClass('hidden');
    $(firstStep).addClass('visible');

    //Inactive First Step Wizard
    var firstStepWizard = document.getElementById("firstStepWizard");
    $(firstStepWizard).removeClass('wizard_inactivo');
    $(firstStepWizard).addClass('wizard_activo');

    //Show Second Step Div        
    var secondStep = document.getElementById("secondStep");
    $(secondStep).removeClass('visible');
    $(secondStep).addClass('hidden');

    //Active Second Step Wizard
    var secondStepWizard = document.getElementById("secondStepWizard");
    $(secondStepWizard).removeClass('wizard_activo');
    $(secondStepWizard).addClass('wizard_inactivo');

}

function thirdStepBtnNext(canalPlanId, today, oneYear, agenciaSS, ramo, prima, contribucion, sCam, derEmision, subSinIva, subConIva, iva, total, rate, proId) {
    //Obtain Checked Producto from 

    document.getElementById("productCheckBox").value = canalPlanId;

    var secondStepDocument = document.getElementById("document");
    var secondStepFirstName = document.getElementById("first_name");
    var secondSteplastName = document.getElementById("last_name");
    var secondStepPhone = document.getElementById("mobile_phone");
    var secondStepEmail = document.getElementById("email");

    var documentResume = document.getElementById("documentResume");
    var customerResume = document.getElementById("customerResume");
    var mobile_phoneResume = document.getElementById("mobile_phoneResume");
    var emailResume = document.getElementById("emailResume");

    $(documentResume).val(secondStepDocument.value);
    $(customerResume).val(secondStepFirstName.value + ' ' + secondSteplastName.value);
    $(mobile_phoneResume).val(secondStepPhone.value);
    $(emailResume).val(secondStepEmail.value);

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var data = {canalPlanId: canalPlanId, today: today, oneYear: oneYear, agenciaSS: agenciaSS, ramo: ramo, prima:prima, contribucion:contribucion, sCam:sCam, derEmision:derEmision, subSinIva:subSinIva, subConIva:subConIva, iva:iva, total:total,rate:rate, proId: proId};
    var url = ROUTE + '/sales/R4/resume/new/ss';
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, data},
        url: url,
        beforeSend: function () {
            var loaderGif = document.getElementById("loaderGif");
            loaderGif.className += "loaderGif";
        },
        success: function (data) {
            var div = document.getElementById('R4TableBodyResume');
            div.innerHTML = '';
            div.innerHTML += data[0];

            var div = document.getElementById('taxTableBodyResume');
            div.innerHTML = '';
            div.innerHTML += data[1];

            nextStep('thirdStep', 'fourthStep');
        },
        complete: function () {
            var loaderGif = document.getElementById("loaderGif");
            loaderGif.classList.remove("loaderGif");
        }
    });
}

function thirdStepBtnBack() {
    //Hide First Step Div
    var firstStep = document.getElementById("secondStep");
    $(firstStep).removeClass('hidden');
    $(firstStep).addClass('visible');

    //Inactive First Step Wizard
    var firstStepWizard = document.getElementById("secondStepWizard");
    $(firstStepWizard).removeClass('wizard_inactivo');
    $(firstStepWizard).addClass('wizard_activo');

    //Show Second Step Div        
    var secondStep = document.getElementById("thirdStep");
    $(secondStep).removeClass('visible');
    $(secondStep).addClass('hidden');

    //Active Second Step Wizard
    var secondStepWizard = document.getElementById("thirdStepWizard");
    $(secondStepWizard).removeClass('wizard_activo');
    $(secondStepWizard).addClass('wizard_inactivo');
}

function clearSecondStepForm() {
    //Validate Inputs
    var documentNumber = document.getElementById("document");
    $(documentNumber).removeClass('inputRedFocus');
    var first_name = document.getElementById("first_name");
    $(first_name).removeClass('inputRedFocus');
    var second_name = document.getElementById("second_name");
    $(second_name).removeClass('inputRedFocus');
    var document_id = document.getElementById("document_id");
    $(document_id).removeClass('inputRedFocus');
    var last_name = document.getElementById("last_name");
    $(last_name).removeClass('inputRedFocus');
    var second_last_name = document.getElementById("second_last_name");
    $(second_last_name).removeClass('inputRedFocus');
    var address = document.getElementById("address");
    $(address).removeClass('inputRedFocus');
    var mobile_phone = document.getElementById("mobile_phone");
    $(mobile_phone).removeClass('inputRedFocus');
    var phone = document.getElementById("phone");
    $(phone).removeClass('inputRedFocus');
    var email = document.getElementById("email");
    $(email).removeClass('inputRedFocus');
    var country = document.getElementById("country");
    $(country).removeClass('inputRedFocus');
    var province = document.getElementById("province");
    $(province).removeClass('inputRedFocus');
    var city = document.getElementById("city");
    $(city).removeClass('inputRedFocus');

    var customerAlert = document.getElementById("customerAlert");
    $(customerAlert).addClass("hidden");
}


function executeSale() {
    // Obtatin Customer Data
    var customerDocument = document.getElementById("document").value;
    var customerDocumentId = document.getElementById("document_id").value;
    var customerFirstName = document.getElementById("first_name").value;
    var customerSecondName = document.getElementById("second_name").value;
    var customerLastName = document.getElementById("last_name").value;
    var customerSecondLastName = document.getElementById("second_last_name").value;
    var customerPhone = document.getElementById("phone").value;
    var customerMobilePhone = document.getElementById("mobile_phone").value;
    var customerAddress = document.getElementById("address").value;
    var customerEmail = document.getElementById("email").value;
    var customerCountry = document.getElementById("country").value;
    var customerProvince = document.getElementById("province").value;
    var customerCity = document.getElementById("city").value;
    var customerBirthDate = document.getElementById("birthdate").value;

    // Obtatin Property Data
    var propertyPrincipal_street = document.getElementById("principal_street").value;
    var propertySecondary_street = document.getElementById("secondary_street").value;
    var propertyNumber = document.getElementById("number").value;
    var propertyAparment = document.getElementById("aparment").value;
    var propertyCities = document.getElementById("cities").value;
    var propertyRate = document.getElementById("rate").value;
    var propertyPrima = document.getElementById("prima").value;

    // Obtatin Property Rubros Data
    var TableData = new Array();
    $('#rubrosBodyTable tr').each(function (row, tr) {
        TableData[row] = {
            "name_rubro": $(tr).find('td:eq(0)').text(),
            "assuredValue": $(tr).find('td:eq(1)').text()
        };
    });

    var name_rubro = TableData[0].name_rubro;
    var assured_value = TableData[0].assuredValue;

    var propertyData = new Array();
    propertyData = {
        'principal_street': propertyPrincipal_street,
        'secondary_street': propertySecondary_street,
        'number': propertyNumber,
        'aparment': propertyAparment,
        'cities': propertyCities,
        'rubroName': name_rubro,
        'rubroValue': assured_value,
        'rubroRate': propertyRate,
        'rubroPrima': propertyPrima
    };
    
    var customerData = new Array();
    customerData = {
        'document': customerDocument,
        'documentId': customerDocumentId,
        'firstName': customerFirstName,
        'secondName': customerSecondName,
        'lastName': customerLastName,
        'secondLastName': customerSecondLastName,
        'phone': customerPhone,
        'mobilePhone': customerMobilePhone,
        'address': customerAddress,
        'email': customerEmail,
        'country': customerCountry,
        'province': customerProvince,
        'city': customerCity,
        'birthdate' : customerBirthDate
    };

    //Obtain Checked Producto from
    var selectedProduct = document.getElementById("productCheckBox").value;
    
    //Obtain Checked Producto from
    var saleMovement = document.getElementById("sale_movement").value;
    var saleId = document.getElementById("sale_id").value;

    //Obtain Check Send Quotation
    var sendQuotation = document.querySelector('.chkBoxSendQuotation').checked;
    
    //Obtain Sale Value
    var sBancos = document.getElementById("sBancos").value;
    var sCampesino = document.getElementById("sCampesino").value;
    var dEmision = document.getElementById("dEmision").value;
    var subtotal12 = document.getElementById("subtotal12").value;
    var subtotal0 = document.getElementById("subtotal0").value;
    var tax = document.getElementById("tax").value;
    var total = document.getElementById("total").value;
    var rate = document.getElementById("rate").value;

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var data = {rate:rate, total:total, tax:tax, subtotal12:subtotal12, subtotal0:subtotal0, dEmision:dEmision, sCampesino:sCampesino, sBancos:sBancos, product: selectedProduct, customer: customerData, property: propertyData, saleMovement: saleMovement, saleId: saleId, sendQuotation: sendQuotation};
    var url = ROUTE + '/sales/R4/store';
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, data},
        url: url,
        beforeSend: function () {
            // Show Loader
            $("#loaderGif").addClass('loaderGif');
//                $("#loaderBody").addClass('loaderBody');
        },
        success: function (data) {
            window.location.href = ROUTE + "/sales";
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



function openModal(type) {
    event.preventDefault();
    var TableData = [];
    $('#vehiclesBodyTable tr').each(function (row, tr) {
        TableData[row] = {
            "plate": $(tr).find('td:eq(0)').text()
            , "brand": $(tr).find('td:eq(1)').text()
            , "model": $(tr).find('td:eq(2)').text()
            , "year": $(tr).find('td:eq(3)').text()
            , "color": $(tr).find('td:eq(4)').text()
        };
    });
//    var modalVehicleBasic = document.getElementById("modalVehicleBasic");
//    $(modalVehicleBasic).innerHtml = TableData[0]['model'];
    document.getElementById("modalVehicle" + type).innerHTML = TableData[0]['model'];
    document.getElementById("myModalBtn" + type).click();
}

function openProductModal(id, prima){
    event.preventDefault();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = ROUTE + '/sales/vehicles/check/conditions';
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, productId: id, prima:prima},
        url: url,
        beforeSend: function () {
            // Show Loader
//            $("#loaderGif").addClass('loaderGif');
//                $("#loaderBody").addClass('loaderBody');
        },
        success: function (data) {
            document.getElementById("modalProductContent").innerHTML = data; 
            document.getElementById("productModalBtn").click();
        },
        complete: function () {
            //Hide Loader
//            var loaderGif = document.getElementById("loaderGif");
//            loaderGif.classList.remove("loaderGif");
//                var loaderBody = document.getElementById("loaderBody");
//                loaderBody.classList.remove("loaderBody");
        }
    });
}

function checkAge(value){
    var res = value.split("-");
    var result = _calculateAge(new Date(res[0], res[1], res[2]));
    return result;
}

function _calculateAge(birthday) { // birthday is a date
    var ageDifMs = Date.now() - birthday.getTime();
    var ageDate = new Date(ageDifMs); // miliseconds from epoch
    return Math.abs(ageDate.getUTCFullYear() - 1970);
}
