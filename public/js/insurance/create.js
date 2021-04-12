/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {
//    checkValidInput();


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



    var tableBorder2 = document.getElementById("tableUsers_length");
    $(tableBorder2).addClass('hidden');
    var tableBorder2 = document.getElementById("tableUsers_info");
    $(tableBorder2).addClass('hidden');
    var tableBorder2 = document.getElementById("tableUsers_paginate");
    $(tableBorder2).addClass('hidden');

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
                //console.log(data);
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

});

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
                // Show Loader
//                $("#loaderGif").addClass('loaderGif');
//                $("#loaderBody").addClass('loaderBody');
            },
            success: function (data) {
//                console.log(data);
                if (data.success == 'true') {
//                    console.log(data);
                    document.getElementById("first_name").disabled = true;
                    document.getElementById("last_name").disabled = true;
                    document.getElementById("document_id").disabled = true;
                    if(data.second_name === null){ document.getElementById("second_name").disabled = false; }else{ document.getElementById("second_name").disabled = true;  }
                    if(data.second_last_name === null){ document.getElementById("second_last_name").disabled = false; }else{ document.getElementById("second_last_name").disabled = true;  }

                    //Province Select
                    var prov = document.getElementById("province");
                    prov.innerHTML = '';
                    var opt = document.createElement('option');
                    var opt2 = document.createElement('option');
                    opt.value = '0';
                    opt.text = '--Escoja Una--';
                    opt2.value = data['province_id'];
                    opt2.text = data['province_name'];
                    prov.appendChild(opt);
                    prov.appendChild(opt2);

                    //City Select
                    var city = document.getElementById("city");
                    city.innerHTML = '';
                    var cityOpt = document.createElement('option');
                    var cityOpt2 = document.createElement('option');
                    cityOpt.value = '0';
                    cityOpt.text = '--Escoja Una--';
                    city.appendChild(cityOpt);
                    cityOpt2.value = data['city_id'];
                    cityOpt2.text = data['city_name'];
                    city.appendChild(cityOpt2);
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
//                var loaderGif = document.getElementById("loaderGif");
//                loaderGif.classList.remove("loaderGif");
//                var loaderBody = document.getElementById("loaderBody");
//                loaderBody.classList.remove("loaderBody");
            }
        });
    } else {
        $('select[name="province"]').empty();
    }

}
function myButton_onclick() {
    $(".alert").addClass('hidden');
    ;
}

function documentBtn() {
    //console.log('hola');
    formAutoFill(document.getElementById("document").value);
}

function selectProduct(id, name, value) {
    document.getElementById("productCheckBox").value = id;
    document.getElementById("productNameCheckBox").value = name;
    document.getElementById("productValueCheckBox").value = value;
    thirdStepBtnNext();
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
    var documentNumber = document.getElementById("document"); if (documentNumber.value === "") { $(documentNumber).addClass('inputRedFocus'); validate = 'true'; }
    var first_name = document.getElementById("first_name"); if (first_name.value === "") { $(first_name).addClass('inputRedFocus'); validate = 'true'; }
    var document_id = document.getElementById("document_id"); if (document_id.value === "0") { $(document_id).addClass('inputRedFocus'); validate = 'true'; } else if (document_id.value === "1") { if (isNaN(documentNumber.value)) { $(documentNumber).addClass('inputRedFocus'); $(document_id).addClass('inputRedFocus'); validate = 'true'; } }
    var last_name = document.getElementById("last_name"); if (last_name.value === "") { $(last_name).addClass('inputRedFocus'); validate = 'true'; }
    var second_last_name = document.getElementById("second_last_name"); if (second_last_name.value === "") { $(second_last_name).addClass('inputRedFocus'); validate = 'true'; }
    var address = document.getElementById("address"); if (address.value === "") { $(address).addClass('inputRedFocus'); validate = 'true'; }
    var mobile_phone = document.getElementById("mobile_phone"); if (mobile_phone.value === "") { $(mobile_phone).addClass('inputRedFocus'); validate = 'true'; } else { if (isNaN(mobile_phone.value)) { $(mobile_phone).addClass('inputRedFocus'); validate = 'true'; } if (mobile_phone.value.length != 10) { $(mobile_phone).addClass('inputRedFocus'); validate = 'true'; } }
    var phone = document.getElementById("phone"); if (phone.value === "") { $(phone).addClass('inputRedFocus'); validate = 'true'; } else { if (isNaN(phone.value)) { $(phone).addClass('inputRedFocus'); validate = 'true'; } if (phone.value.length != 9) { $(phone).addClass('inputRedFocus'); validate = 'true'; } }
    var email = document.getElementById("email");
    var emailValidate = ValidateEmail(email.value); if (email.value === "") { $(email).addClass('inputRedFocus'); validate = 'true'; } else if (emailValidate === false) { $(email).addClass('inputRedFocus'); validate = 'true'; }
    var country = document.getElementById("country"); if (country.value === "0") { $(country).addClass('inputRedFocus'); validate = 'true'; }
    var province = document.getElementById("province"); if (province.value === "0" || province.value === "") { $(province).addClass('inputRedFocus'); validate = 'true'; }
    var city = document.getElementById("city"); if (city.value === "0" || city.value === "") { $(city).addClass('inputRedFocus'); validate = 'true'; }
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

function secondStepBtnNext(){
    nextStep('secondStep','thirdStep');
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

function clearFirstStepForm() {
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
    console.log(TableData[0]['model']);
//    var modalVehicleBasic = document.getElementById("modalVehicleBasic");
//    $(modalVehicleBasic).innerHtml = TableData[0]['model'];
    document.getElementById("modalVehicle" + type).innerHTML = TableData[0]['model'];
    document.getElementById("myModalBtn" + type).click();
}

function validateBeneficiary() {
    var validate = false;

    $('#beneficiaryTable tr').each(function (row, tr) {
        if (document.getElementById('documentBeneficiary').value === $(tr).find('td:eq(0)').text()) {
            validate = true;
            $(tr).find('td:eq(0)').addClass('borderRedFocus');
        }else{
            $(tr).find('td:eq(0)').removeClass('borderRedFocus');
        }
    });

    if (validate === false) { formAutoFillBeneficiary(document.getElementById('documentBeneficiary').value); } 
}

function onlyNumbers(evt, ele) {
//    var charCode = (event.which) ? event.which : event.keyCode
//    if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode !== 44)
//        return false;
//
//    return true;
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  key = String.fromCharCode( key );
  var value = ele.value + key;
  var regex = /^\d+(,\d{0,2})?$/;
  if( !regex.test(value) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}

function formatNumber(str){ 
    var res = str.replace(",", ".");
    return res;
}

function editRow(documentForm, document_id, first_name, last_name, porcentage, obj) {
    //Fill Form
    var documentFormSubmit = document.getElementById("documentBeneficiary");
    documentFormSubmit.value = documentForm;
    var document_idSubmit = document.getElementById("document_idBeneficiary");
    document_idSubmit.value = document_id;
    var first_nameSubmit = document.getElementById("first_nameBeneficiary");
    first_nameSubmit.value = first_name;
    var last_nameSubmit = document.getElementById("last_nameBeneficiary");
    last_nameSubmit.value = last_name;
    var porcentageSubmit = document.getElementById("porcentageBeneficiary");
    porcentageSubmit.value = porcentage;
    //Validate if Customer exist
    var url = ROUTE + '/customer/document/autofill/' + documentForm;
    $.ajax({
        url: url,
        type: "GET",
        dataType: "json",
        success: function (data) {
            if (data.success == 'true') {
                document.getElementById("first_name").disabled = true;
                document.getElementById("last_name").disabled = true;
                document.getElementById("document_id").disabled = true;
                
                document.getElementById("first_nameBeneficiary").value = data['first_name'];
                document.getElementById("last_nameBeneficiary").value = data['last_name'];
                document.getElementById("document_idBeneficiary").value = data['document_id'];
//                $("#beneficiaryForm").autofill(data);
            } else {
                document.getElementById("first_name").disabled = false;
                document.getElementById("last_name").disabled = false;
                document.getElementById("document_id").disabled = false;
            }
        }
    });
    deleteRow(obj);
}

function deleteRow(obj) {
    var index = obj.parentNode.parentNode.rowIndex;
    var table = document.getElementById("beneficiaryTable");
    table.deleteRow(index);
}


function addBeneficiary() {
    event.preventDefault();

    //Variables
    var documentForm = document.getElementById("documentBeneficiary");
    var document_id = document.getElementById("document_idBeneficiary");
    var first_name = document.getElementById("first_nameBeneficiary");
    var last_name = document.getElementById("last_nameBeneficiary");
    var porcentage = document.getElementById("porcentageBeneficiary");
    var validate = false;

    //Validation
    if (documentForm.value === "") { $(documentForm).addClass('inputRedFocus'); validate = true; } else { $(documentForm).removeClass('inputRedFocus'); }
    if (document_id.value === "0") { $(document_id).addClass('inputRedFocus'); validate = true; } else { $(document_id).removeClass('inputRedFocus'); }
    if (first_name.value === "") { $(first_name).addClass('inputRedFocus'); validate = true; } else { $(first_name).removeClass('inputRedFocus'); }
    if (last_name.value === "") { $(last_name).addClass('inputRedFocus'); validate = true; } else { $(last_name).removeClass('inputRedFocus'); }
    if (porcentage.value === "" || porcentage.value > 100 || porcentage.value === "0") { $(porcentage).addClass('inputRedFocus'); validate = true; } else { $(porcentage).removeClass('inputRedFocus'); }

    //Beneficiary Table Data
    var value = 0;
    var TableData = new Array();
    $('#beneficiaryTable tr').each(function (row, tr) {
        TableData += [$(tr).find('td:eq(4)').text()];
        value += Number(formatNumber($(tr).find('td:eq(4)').text()));
    });

    //Validate Beneficiary Table Data
    value += Number(formatNumber(porcentage.value));
    if (value > 100) {
        validate = true;
        alert('El porcentaje no debe ser mayor a 100%');
    }

    //Add Row
    if (validate === false) {
        addRow(documentForm, document_id, first_name, last_name, porcentage);
    }
}

function addRow(documentForm, document_id, first_name, last_name, porcentage) {
    var bodyTable = document.getElementById("beneficiaryBodyTable");

    var rowCount = bodyTable.rows.length;
    var row = bodyTable.insertRow(rowCount);

    //Validate Document id Name
    if (document_id.value == 1) {
        var documentName = 'Cedula';
    }
    if (document_id.value == 2) {
        var documentName = 'RUC';
    }
    if (document_id.value == 3) {
        var documentName = 'PASAPORTE';
    }
    if (document_id.value == 4) {
        var documentName = 'VISA 12 IV';
    }

    row.insertCell(0).innerHTML = documentForm.value;
    row.insertCell(1).innerHTML = documentName;
    row.insertCell(2).innerHTML = first_name.value;
    row.insertCell(3).innerHTML = last_name.value;
    row.insertCell(4).innerHTML = porcentage.value;
    row.insertCell(5).innerHTML = '<button type="submit" class="btn btn-link" onClick="Javacsript:editRow(\'' + documentForm.value + '\',\'' + document_id.value + '\', \'' + first_name.value + '\', \'' + last_name.value + '\', \'' + porcentage.value + '\',this)"><span class="glyphicon glyphicon-pencil" style="color:green;font-size:18px"></span></button>';
    row.insertCell(6).innerHTML = '<button type="submit" class="btn btn-link" onClick="Javacsript:deleteRow(this)"><span class="glyphicon glyphicon-remove" style="color:red;font-size:18px"></span></button>';

    //Return Inputs no Null
    documentForm.value = '';
    document_id.value = '0';
    first_name.value = '';
    last_name.value = '';
    porcentage.value = '';

    //Disable Inputs
    $('#document_id').prop('disabled', true);
    $('#first_name').prop('disabled', true);
    $('#last_name').prop('disabled', true);
}

function clearForm() {
    var documentForm = document.getElementById("documentBeneficiary");
    $(documentForm).removeClass('inputRedFocus');
    var first_name = document.getElementById("first_nameBeneficiary");
    first_name.value = '';
    $(first_name).removeClass('inputRedFocus');
    var document_id = document.getElementById("document_idBeneficiary");
    document_id.value = '0';
    $(document_id).removeClass('inputRedFocus');
    var last_name = document.getElementById("last_nameBeneficiary");
    last_name.value = '';
    $(last_name).removeClass('inputRedFocus');
    var porcentage = document.getElementById("porcentageBeneficiary");
    porcentage.value = '';
    $(porcentage).removeClass('inputRedFocus');
    document.getElementById("first_nameBeneficiary").disabled = true;
    document.getElementById("last_nameBeneficiary").disabled = true;
    document.getElementById("document_idBeneficiary").disabled = true;
    $('#beneficiaryTable tr').each(function (row, tr) {
        $(tr).find('td:eq(0)').removeClass('borderRedFocus');
    });
}

function formAutoFillBeneficiary(val) {
    var documentNumber = val;
    var url = ROUTE + '/customer/document/autofill/' + documentNumber;
    $.ajax({
        url: url,
        type: "GET",
        dataType: "json",
        success: function (data) {
            if (data.success == 'true') {
                document.getElementById("first_nameBeneficiary").disabled = true;
                document.getElementById("last_nameBeneficiary").disabled = true;
                document.getElementById("document_idBeneficiary").disabled = true;
                
//                console.log(data);
                document.getElementById("first_nameBeneficiary").value = data['first_name'];
                document.getElementById("last_nameBeneficiary").value = data['last_name'];
                document.getElementById("document_idBeneficiary").value = data['document_id'];
//                $("#beneficiaryForm").autofill(data);
            } else {
                document.getElementById("first_nameBeneficiary").disabled = false;
                document.getElementById("last_nameBeneficiary").disabled = false;
                document.getElementById("document_idBeneficiary").disabled = false;
            }
        }
    });
}

function thirdStepBtnNext(){
    event.preventDefault();
    var validate = false;

    //Beneficiary Table Data
    var value = 0;
    var TableData = new Array();
    $('#beneficiaryBodyTable tr').each(function (row, tr) {
        TableData[row] = { "document": $(tr).find('td:eq(0)').text() , "type": $(tr).find('td:eq(1)').text() , "first_name": $(tr).find('td:eq(2)').text() , "last_name": $(tr).find('td:eq(3)').text() , "porcentage": $(tr).find('td:eq(4)').text() };
        value += Number(formatNumber($(tr).find('td:eq(4)').text()));
    });

    //Validate Beneficiary Table Data
    var table = document.getElementById("beneficiaryTable");
    var rowCountTable = table.rows.length;
    if (rowCountTable > 1) { if (value != 100) { alert('Los porcentages deben sumar 100%'); validate = true; return false; } }else{ validate = true; alert('Debe agregar un beneficiario'); return false; }
    
    if(validate == false){
        //Store New Insurance
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
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
            'city': customerCity
        };
        
        //Obtain Radio Value
        var radios = document.getElementsByName('proId');

        for (var i = 0, length = radios.length; i < length; i++) {
          if (radios[i].checked) {
            // do whatever you want with the checked radio
            var radioSelected = radios[i].value;
            // only one radio can be logically checked, don't check the rest
            break;
          }
        }
        
        //Obtain Beneficiary Table Data
        var beneTable = new Array();
        $('#beneficiaryBodyTable tr').each(function (row, tr) {
            beneTable[row] = {
                "document": $(tr).find('td:eq(0)').text(),
                "type": $(tr).find('td:eq(1)').text(),
                "first_name": $(tr).find('td:eq(2)').text(),
                "last_name": $(tr).find('td:eq(3)').text(),
                "porcentage": $(tr).find('td:eq(4)').text()
            };
        });
        
        var url = ROUTE + '/insurance/store';
        $.ajax({
            type: "POST",
            data: {_token: CSRF_TOKEN, customerData: customerData, product: radioSelected, beneTable: beneTable},
            url: url,
            success: function (data) {
                document.getElementById("insuranceId").value  = data;
                nextStep('thirdStep','fourthStep');
            },
            error: function () {
                return "Hello";
            }
        });
    }
}

function validateCode(){
    event.preventDefault();
    var insuranceId = document.getElementById("insuranceId").value;
    var validationCode = document.getElementById("validation_code").value;
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = ROUTE + '/insurance/validate/code';
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, insuranceId: insuranceId, validationCode: validationCode},
        url: url,
        success: function (data) {
            if(data == 'false'){
                alert('El codigo ingresado es incorrecto');
            }else{
                window.location.href = ROUTE + '/insurance';
            }
        },
        error: function () {
            return "Hello";
        }
    });
}

function resendCode(){
    event.preventDefault();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var insuranceId = document.getElementById("insuranceId").value;
    var url = ROUTE + '/insurance/resend/code';
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, insuranceId: insuranceId},
        url: url,
        success: function (data) {
            alert('Se envio un nuevo codigo de validacion');
        },
        error: function () {
            return "Hello";
        }
    });
}
    