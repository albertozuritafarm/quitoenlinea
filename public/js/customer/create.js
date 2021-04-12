/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {
    document.getElementById("document").onclick = function () {
        $("#suggesstion-box").hide();
        document.getElementById("first_name").value = "";
        document.getElementById("document_id").value = "";
        document.getElementById("last_name").value = "";
        document.getElementById("mobile_phone").value = "";
        document.getElementById("phone").value = "";
        document.getElementById("address").value = "";
        document.getElementById("email").value = "";
        document.getElementById("country").value = "";
        document.getElementById("province").value = "";
        document.getElementById("city").value = "";
        document.getElementById("first_name").disabled = true;
        document.getElementById("last_name").disabled = true;
        document.getElementById("document_id").disabled = true;
    };
    $("#document").keyup(function () {
        $("#suggesstion-box").hide();
        document.getElementById("first_name").value = "";
        document.getElementById("document_id").value = "";
        document.getElementById("last_name").value = "";
        document.getElementById("mobile_phone").value = "";
        document.getElementById("phone").value = "";
        document.getElementById("address").value = "";
        document.getElementById("email").value = "";
        document.getElementById("country").value = "";
        document.getElementById("province").value = "";
        document.getElementById("city").value = "";
        document.getElementById("first_name").disabled = true;
        document.getElementById("last_name").disabled = true;
        document.getElementById("document_id").disabled = true;
    });

    document.getElementById("documentBtn").onclick = function () {
        formAutoFill(document.getElementById("document").value);
    };


    $("#document_id").change(function () {
        var document_id = document.getElementById("document_id");
        var documentInput = document.getElementById("document");
        if (document_id.value == 1) {
            if (documentInput.value.length == 10) {
                validateDocument();
                $(documentInput).focus();
            } else {
                document.getElementById("document_id").value = '';
                alert('La cedula debe tener 10 digitos');
                $(documentInput).focus();
            }
        }
    });
    
    $("#birth_country").change(function () {
        console.log('hola');
        birthCountry();
    });
});
function documentBtn() {
    formAutoFill(document.getElementById("document").value);
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
                    document.getElementById("first_name").disabled = true;
                    document.getElementById("last_name").disabled = true;
                    document.getElementById("document_id").disabled = true;

                    //Province Select
                    var prov = document.getElementById("province");
                    prov.innerHTML = '';
                    var opt = document.createElement('option');
                    var opt2 = document.createElement('option');
                    opt.value = '';
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
                    cityOpt.value = '';
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
                    cityOpt.value = '';
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
//        $('select[name="province"]').empty();
    }

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
                document.getElementById("document_id").value = "";
                document.getElementById("last_name").value = "";
                document.getElementById("mobile_phone").value = "";
                document.getElementById("phone").value = "";
                document.getElementById("address").value = "";
                document.getElementById("email").value = "";
                document.getElementById("country").value = "";
                document.getElementById("province").value = "";
                document.getElementById("city").value = "";
                alert('El documento ingresado es invalido');
            }
        },
        error: function () {
            return "Hello";
        }
    });
}

function removeErrorMsg(id){ 
    var errorMsg = document.getElementById("errorMsg"+id);
    if(errorMsg !== null){
        $(errorMsg).addClass('hidden');
    }
}

function birthCountry(id){
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var data = {document: document.getElementById("birth_country").value};
    var url = ROUTE + '/city/get';
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, data},
        url: url,
        async: false,
        success: function (data) {
            console.log(data);
        }
    });
}
