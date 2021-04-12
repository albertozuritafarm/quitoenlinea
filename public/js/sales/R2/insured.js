/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {

    $("#document_id_insured").change(function () {
        var document_id = document.getElementById("document_id_insured");
        var documentInput = document.getElementById("document_insured");
        if (document_id.value == 1) {
            if (documentInput.value.length == 10) {
                validateDocument();
                $(documentInput).focus();
            } else {
                document.getElementById("document_id_insured").value = '';
                alert('La cedula debe tener 10 digitos');
                $(documentInput).focus();
            }
        }
    });

    document.getElementById("document_insured").onclick = function () {
        clearInsuredForm();
    };
    $("#document_insured").keyup(function () {
        clearInsuredForm();
    });
    
});

function secondStepBtnNext() {
    event.preventDefault();
    validateInsuredForm();
}

function autoFillInsured() {
    insuredFormAutoFill(document.getElementById("document").value);
}
function documentBtn_insured() {
    clearInsuredForm();
    insuredFormAutoFill(document.getElementById("document_insured").value);
}

function insuredFormAutoFill(val) {
    var documentNumber = val;
    var url = ROUTE + '/insured/document/autofill/' + documentNumber;
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
                    document.getElementById("first_name_insured").disabled = true;
                    document.getElementById("last_name_insured").disabled = true;
                    document.getElementById("document_id_insured").disabled = true;

                    //Province Select
                    var prov = document.getElementById("province_insured");
                    prov.innerHTML = '';
                    var opt = document.createElement('option');
                    var opt2 = document.createElement('option');
                    opt.value = '';
                    opt.text = '--Escoja Una--';
                    opt2.value = data['province_id_insured'];
                    opt2.text = data['province_name_insured'];
                    prov.appendChild(opt);
                    prov.appendChild(opt2);

                    //City Select
                    var city = document.getElementById("city_insured");
                    city.innerHTML = '';
                    var cityOpt = document.createElement('option');
                    var cityOpt2 = document.createElement('option');
                    cityOpt.value = '';
                    cityOpt.text = '--Escoja Una--';
                    city.appendChild(cityOpt);
                    cityOpt2.value = data['city_id_insured'];
                    cityOpt2.text = data['city_name_insured'];
                    city.appendChild(cityOpt2);
                    $("#insuredForm").autofill(data);
                } else {
                    document.getElementById("first_name_insured").disabled = false;
                    document.getElementById("last_name_insured").disabled = false;
                    document.getElementById("document_id_insured").disabled = false;

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
//        secondStepFormValidate();
    } else {
        $('select[name="province"]').empty();
    }

}

function validateDocumentInsured() {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var data = {document: document.getElementById("document_insured").value};
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
                document.getElementById("first_name_insured").value = "";
                document.getElementById("document_id_insured").value = "";
                document.getElementById("last_name_insured").value = "";
                document.getElementById("mobile_phone_insured").value = "";
                document.getElementById("phone_insured").value = "";
                document.getElementById("address_insured").value = "";
                document.getElementById("email_insured").value = "";
                document.getElementById("country_insured").value = "";
                document.getElementById("province_insured").value = "";
                document.getElementById("city_insured").value = "";
                alert('El documento ingresado es invalido');
            }
        },
        error: function () {
            return "Hello";
        }
    });
}

function clearInsuredForm() {
    $("#suggesstion-box").hide();
    var first_name_insured = document.getElementById("first_name_insured");
    first_name_insured.value = ""; $(first_name_insured).removeClass('inputRedFocus');
    var document_id_insured = document.getElementById("document_id_insured");
    document_id_insured.value = ""; $(document_id_insured).removeClass('inputRedFocus');
    var last_name_insured = document.getElementById("last_name_insured");
    last_name_insured.value = ""; $(last_name_insured).removeClass('inputRedFocus');
    var mobile_phone_insured = document.getElementById("mobile_phone_insured");
    mobile_phone_insured.value = ""; $(mobile_phone_insured).removeClass('inputRedFocus');
    var phone_insured = document.getElementById("phone_insured");
    phone_insured.value = ""; $(phone_insured).removeClass('inputRedFocus');
    var address_insured = document.getElementById("address_insured");
    address_insured.value = ""; $(address_insured).removeClass('inputRedFocus');
    var email_insured = document.getElementById("email_insured");
    email_insured.value = ""; $(email_insured).removeClass('inputRedFocus');
    var country_insured = document.getElementById("country_insured");
    country_insured.value = ""; $(country_insured).removeClass('inputRedFocus');
    var province_insured = document.getElementById("province_insured");
    province_insured.value = ""; $(province_insured).removeClass('inputRedFocus');
    var city_insured = document.getElementById("city_insured");
    city_insured.value = ""; $(city_insured).removeClass('inputRedFocus');
    document.getElementById("first_name_insured").disabled = true;
    document.getElementById("last_name_insured").disabled = true;
    document.getElementById("document_id_insured").disabled = true;
    
}

function validateInsuredForm() {
    event.preventDefault();
    //Validate Variable
    var validate = 'false';

    //Validate Inputs
    var documentNumber = document.getElementById("document_insured");
    if (documentNumber.value === "") {
        $(documentNumber).addClass('inputRedFocus');
        validate = 'true';
    }
    var first_name = document.getElementById("first_name_insured");
    if (first_name.value === "") {
        $(first_name).addClass('inputRedFocus');
        validate = 'true';
    }
    var document_id = document.getElementById("document_id_insured");
    if (document_id.value === "") {
        $(document_id).addClass('inputRedFocus');
        validate = 'true';
    } else if (document_id.value === "1") {
        if (isNaN(documentNumber.value)) {
            $(documentNumber).addClass('inputRedFocus');
            $(document_id).addClass('inputRedFocus');
            validate = 'true';
        }
    }
    var last_name = document.getElementById("last_name_insured");
    if (last_name.value === "") {
        $(last_name).addClass('inputRedFocus');
        validate = 'true';
    }
    var address = document.getElementById("address_insured");
    if (address.value === "") {
        $(address).addClass('inputRedFocus');
        validate = 'true';
    }
    var mobile_phone = document.getElementById("mobile_phone_insured");
    if (mobile_phone.value === "") {
        $(mobile_phone).addClass('inputRedFocus');
        validate = 'true';
    } else {
        if (isNaN(mobile_phone.value)) {
            $(mobile_phone).addClass('inputRedFocus');
            validate = 'true';
        }
        if (mobile_phone.value.length != 10) {
            $(mobile_phone).addClass('inputRedFocus');
            validate = 'true';
        }
    }

    var phone = document.getElementById("phone_insured");
    if (phone.value === "") {
        $(phone).addClass('inputRedFocus');
        validate = 'true';
    } else {
        if (isNaN(phone.value)) {
            $(phone).addClass('inputRedFocus');
            validate = 'true';
        }
        if (phone.value.length != 9) {
            $(phone).addClass('inputRedFocus');
            validate = 'true';
        }
    }
    var email = document.getElementById("email_insured");
    var emailValidate = ValidateEmail(email.value);
//        console.log(emailValidate);
    if (email.value === "") {
        $(email).addClass('inputRedFocus');
        validate = 'true';
    } else if (emailValidate === false) {
        $(email).addClass('inputRedFocus');
        validate = 'true';

    }
    var country = document.getElementById("country_insured");
    if (country.value === "0") {
        $(country).addClass('inputRedFocus');
        validate = 'true';
    }
    var province = document.getElementById("province_insured");
    if (province.value === "0" || province.value === "") {
        $(province).addClass('inputRedFocus');
        validate = 'true';
    }
    var city = document.getElementById("city_insured");
    if (city.value === "0" || city.value === "") {
        $(city).addClass('inputRedFocus');
        validate = 'true';
    }

    if (validate === 'true') {
//        var customerAlert = document.getElementById("customerAlert_insured");
//        customerAlert.classList.remove("hidden");
//        $(customerAlert).addClass("visible");
        return false;
    }

    //Customer Store
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var form = document.getElementById('insuredForm');
    $.ajax({
        url: ROUTE + '/insured/store/data',
        type: "POST",
        data: new FormData(form),
        contentType: false,
        cache: false,
        processData: false,
        success: function (data)
        {
            var url = ROUTE + '/sales/product';
            var next = '#insuredStep';
            loadNextPage(url,next);
        }
    });
}

function previousStep(){
    document.getElementById("previousStepBtn").click();
}