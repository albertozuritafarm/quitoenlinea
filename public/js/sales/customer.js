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
    document.getElementById("document").onclick = function () {
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
        document.getElementById("first_name").disabled = true;
        document.getElementById("last_name").disabled = true;
        document.getElementById("document_id").disabled = true;
    };
    $("#document").keyup(function () {
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
        document.getElementById("first_name").disabled = true;
        document.getElementById("last_name").disabled = true;
        document.getElementById("document_id").disabled = true;
    });
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
                if (data.success == 'true') {
                    console.log(data);
                    document.getElementById("first_name").disabled = true;
                    document.getElementById("last_name").disabled = true;
                    document.getElementById("document_id").disabled = true;

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
                    $("#customerForm").autofill(data);
                } else {
                    document.getElementById("first_name").disabled = false;
                    document.getElementById("last_name").disabled = false;
                    document.getElementById("document_id").disabled = false;
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
        secondStepFormValidate();
    } else {
        $('select[name="province"]').empty();
    }

}
function documentBtn() {
    //console.log('hola');
    formAutoFill(document.getElementById("document").value);
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
    if (first_name.value === "") {
        $(first_name).addClass('inputRedFocus');
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
    if (last_name.value === "") {
        $(last_name).addClass('inputRedFocus');
        validate = 'true';
    }
    var address = document.getElementById("address");
    if (address.value === "") {
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
            $(phone).addClass('inputRedFocus');
            validate = 'true';
        }
    }
    var email = document.getElementById("email");
    var emailValidate = ValidateEmail(email.value);
//        console.log(emailValidate);
    if (email.value === "") {
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

    if (validate === 'true') {
        var customerAlert = document.getElementById("customerAlert");
        customerAlert.classList.remove("hidden");
        $(customerAlert).addClass("visible");
        return false;
    }
    
    //Customer Store
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var form = document.getElementById('customerForm');
    $.ajax({
        url: ROUTE + '/customer/store/data',
        type: "POST",
        data: new FormData(form),
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function () {
            // Show Loader
            $("#loaderGif").addClass('loaderGif');
        },
        success: function (data)
        {
            var url = ROUTE + '/sales/asset/' + document.getElementById("insurance_branch").value + '/' + data;
            var next = '#customerStep';
            loadNextPage(url,next);
        },
        complete: function () {
            //Hide Loader
            var loaderGif = document.getElementById("loaderGif");
            loaderGif.classList.remove("loaderGif");
        }
    });
}
function clearSecondStepForm() {
    //Validate Inputs
    var documentNumber = document.getElementById("document");
    $(documentNumber).removeClass('inputRedFocus');
    var first_name = document.getElementById("first_name");
    $(first_name).removeClass('inputRedFocus');
    var document_id = document.getElementById("document_id");
    $(document_id).removeClass('inputRedFocus');
    var last_name = document.getElementById("last_name");
    $(last_name).removeClass('inputRedFocus');
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