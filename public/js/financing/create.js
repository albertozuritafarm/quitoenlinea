/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {
    //Buttons Actions
    //firstStepBtnNext Click
    document.getElementById("firstStepBtnNext").onclick = function () {
        validateFirstStep();
    };
    //secondStepBtnBack Click
    document.getElementById("secondStepBtnBack").onclick = function () {
        nextStep("secondStep", "firstStep");
    };
    //secondStepBtnNext Click
    document.getElementById("secondStepBtnNext").onclick = function () {
        validateSecondStep();
    };
    //thirdStepBtnBack Click
    document.getElementById("thirdStepBtnBack").onclick = function () {
        nextStep("thirdStep", "secondStep");
    };
    //thirdStepBtnNext Click
    document.getElementById("thirdStepBtnNext").onclick = function () {
        validateThirdStep();
    };
    //fourthStepBtnBack Click
    document.getElementById("fourthStepBtnBack").onclick = function () {
        window.location.replace(ROUTE + "/account");
    };
    //fourthStepBtnNext Click
    document.getElementById("fourthStepBtnNext").onclick = function () {
        validateFourthStep();
    };
    //Document Change
    $("#document").change(function () {
        var resultMessage = document.getElementById("resultMessage");
        resultMessage.innerHTML = '';
        document.getElementById("resultCode").value = '';
    });

    $('#thirdStep').on('change', 'input[id^="RadioPlan_"]', function () {
        $(".TRSeleccionado").removeClass("TRSeleccionado");
        var tr = $(this).parent().parent("tr");
        if ($(this).prop("checked")) {
            tr.addClass("TRSeleccionado");
        }
    });
    $("#thirdStep").on("click", "table tbody tr", function () {
        $(this).find('input:radio').click();
    });
    $('#thirdStep').on('click', "input[id^='RadioPlan_']", function (e) {
        e.stopPropagation();
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
            //console.log(data);
            if (data === 'invalid') {
                alert('El documento ingresado es invalido');
                var documentInput = document.getElementById("document").value;
                $(documentInput).focus();
                documentFormClear();
            }
        },
        error: function () {
            return "Hello";
        }
    });
}
function validateFirstStep() {
    event.preventDefault();
    //Validate Variable
    var validate = 'false';

    //Validate Inputs
    var amount = document.getElementById("amount");
    if (amount.value === "") {
        $(amount).addClass('inputRedFocus');
        validate = 'true';
    } else if (amount.value > 1000 || amount.value < 0) {
        var amountError = document.getElementById("amountError");
        amountError.innerHTML = 'El valor no puede superar los $ 1000.00';
        validate = 'true';
    } else {
        var amountError = document.getElementById("amountError");
        amountError.innerHTML = '';
    }

    var bank = document.getElementById("bank");
    if (bank.value === "") {
        $(bank).addClass('inputRedFocus');
        validate = 'true';
    }

    var number = document.getElementById("number");
    var length = number.value.length;
    if (number.value === "") {
        $(number).addClass('inputRedFocus');
        validate = 'true';
    } else if (length != 5) {
        var numberError = document.getElementById("numberError");
        numberError.innerHTML = 'Número de factura / orden incorrecto. (Debe contener 5 dígitos)';
        validate = 'true';
    }

    if (validate === 'true') {
        var customerAlert = document.getElementById("customerAlert");
        customerAlert.classList.remove("hidden");
        return false;
    }
    var documentError = document.getElementById("documentError");
    documentError.innerHTML = '';
    var documentInput = document.getElementById("document");
    $(documentInput).removeClass('inputRedFocus');
    var resultMessage = document.getElementById("resultMessage");
    resultMessage.innerHTML = '';
    document.getElementById("resultCode").value = '';
    nextStep("firstStep", "secondStep");
}

function validateSecondStep() {
    var resultCode = document.getElementById("resultCode").value;
    if (resultCode == 3) {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var amount = document.getElementById("amount").value;
        var url = ROUTE + '/financing/productTable';
        var data = {amount: amount};

        $.ajax({
            type: "POST",
            data: {_token: CSRF_TOKEN, data},
            url: url,
            success: function (data) {
                var thirdStepProductTable = document.getElementById("thirdStepProductTable");
                thirdStepProductTable.innerHTML = data;
                nextStep("secondStep", "thirdStep");
            }
        });
    } else {
        var documentError = document.getElementById("documentError");
        documentError.innerHTML = 'El cliente debe estar comprobado y contar con crédito disponible.';
    }
}
function validateThirdStep() {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var amount = document.getElementById("amount").value;
    var bank = document.getElementById("bank").value;
    var number = document.getElementById("number").value;
    var financingCH = document.getElementById("financingCH").value;
    var documentNumber = document.getElementById("document").value;
    var RadioPlan = $("input[name='RadioPlan']:checked").val();
    var url = ROUTE + '/financing/sendCode';
    var data = {amount: amount, bank: bank, number:number, documentNumber: documentNumber, financingCH: financingCH, RadioPlan:RadioPlan};

    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, data},
        url: url,
        success: function (data) {
            document.getElementById("crId").value = data;
            nextStep("thirdStep", "fourthStep");
        }
    });
}

function validateFourthStep() {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var crId = document.getElementById("crId").value;
    var code = document.getElementById("code").value;
    var url = ROUTE + '/financing/validateCode';
    var data = {crId: crId, code: code};

    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, data},
        url: url,
        beforeSend: function () {
            // Show Loader
            $("#loaderGif").addClass('loaderGif');
        },
        success: function (data) {
            if (data.success == 'true') {
                window.location.replace(ROUTE + "/financing");
            } else {
                var resultMessageCode = document.getElementById("resultMessageCode");
                $(resultMessageCode).removeClass('alert alert-success alert-danger');
                $(resultMessageCode).addClass('alert alert-danger');
                resultMessageCode.innerHTML = data.msg;
            }
        },
        complete: function () {
            //Hide Loader
            var loaderGif = document.getElementById("loaderGif");
            loaderGif.classList.remove("loaderGif");
        }
    });
}

function ValidateEmail(mail)
{
    if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(document.getElementById("email").value))
    {
        document.getElementById("emailError").innerHTML = '';
        return (true);
    } else {
        var txt = 'Por favor ingrese un correo valido';
        document.getElementById("emailError").innerHTML = txt;
//    document.getElementById("email").value = '';
        return (false);
    }
}

function removeInputRedFocus(id) {
    //Input
    var input = document.getElementById(id);
    $(input).removeClass('inputRedFocus');
    //Input Error
    var divInputError = id + "Error";
    var inputError = document.getElementById(divInputError);
    inputError.innerHTML = '';
    //Message
    var customerAlert = document.getElementById('customerAlert');
    $(customerAlert).addClass('hidden');
}

function nextStep(div1, div2) {
    var div = document.getElementById(div1);
    $(div).addClass('hidden');
    var div = document.getElementById(div2);
    $(div).removeClass('hidden');

    var wizard = document.getElementById(div1 + "Wizard");
    $(wizard).removeClass('wizard_activo');
    $(wizard).addClass('wizard_inactivo');
    var wizard = document.getElementById(div2 + "Wizard");
    $(wizard).removeClass('wizard_inactivo');
    $(wizard).addClass('wizard_activo');
}


function fileNameFunction(side) {
    var file = document.getElementById('select_file' + side).files[0];
    var uploadPic = document.getElementById("fileName" + side);
    uploadPic.innerHTML = file.name;

}
;

function validateCredit() {
    //Document Error
    var documentError = document.getElementById("documentError");
    documentError.innerHTML = '';
    var documentNumber = document.getElementById("document").value;
    if (documentNumber == '') {
        var documentInput = document.getElementById("document");
        $(documentInput).addClass('inputRedFocus');
        documentError.innerHTML = 'Debe indicar la identificación (Cédula, RUC o pasaporte)';
    } else {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var amount = document.getElementById("amount").value;
        var bank = document.getElementById("bank").value;
        var url = ROUTE + '/financing/validateCredit';
        var data = {documentNumber: documentNumber, bank: bank, amount: amount};

        $.ajax({
            type: "POST",
            data: {_token: CSRF_TOKEN, data},
            url: url,
            beforeSend: function () {
                // Show Loader
                $("#loaderGif").addClass('loaderGif');
            },
            success: function (data) {
                document.getElementById("resultCode").value = data.code;
                if (data.code === 0) {
                    var documentNumber = document.getElementById("document").value;
                    var documentError = document.getElementById("documentError");
                    documentError.innerHTML = 'La identificación (' + documentNumber + ') no se encuentra registrada en el sistema.';
                } else {
                    var resultMessage = document.getElementById("resultMessage");
                    resultMessage.innerHTML = data.data;
                }
            },
            complete: function () {
                //Hide Loader
                var loaderGif = document.getElementById("loaderGif");
                loaderGif.classList.remove("loaderGif");
            }
        });

    }
}

function CHFinanciamientoClick() {
    var checkBox = document.getElementById("CHFinanciamiento");
    var financingCH = document.getElementById("financingCH");
    if (checkBox.checked == true) {
        financingCH.value = 1;
    } else {
        financingCH.value = 0;
    }
}
