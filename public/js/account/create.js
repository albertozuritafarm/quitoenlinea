/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {
    fadeOut('personalDiv');
    fadeOut('representativeForm');
    fadeOut('economicInformationForm');
    fadeOut('assetsInformationForm');
    fadeOut('passivesInformationForm');

    //Customer Document Button 
    document.getElementById("documentBtn").onclick = function () {
        formAutoFill(document.getElementById("document").value);
    };
    //Customer Input Click
    document.getElementById("document").onclick = function () {
        documentFormClear();
    };
    //Customer Input Tab
    $("#document").keyup(function () {
        documentFormClear();
    });
    //Customer ID Change
    $("#document_id").change(function () {
        var document_id = document.getElementById("document_id");
        var documentInput = document.getElementById("document");
        if (document_id.value == 1) {
            if (documentInput.value.length == 10) {
                validateDocument();
            } else {
                document.getElementById("document_id").value = '0';
                alert('La cedula debe tener 10 digitos');
                $(documentInput).focus();
            }
        } else if (document_id.value == 2) {
            if (documentInput.value.length == 13) {
                if (isNaN(documentInput.value)) {
                    document.getElementById("document_id").value = '0';
                    alert('El RUC no tiene el formato completo');
                    $(documentInput).focus();
                } else {
                    return true;
                }
            } else {
                document.getElementById("document_id").value = '0';
                alert('El RUC debe tener 13 digitos');
                $(documentInput).focus();
            }
        } else if (document_id.value == 3) {
            if (documentInput.value.length > 3) {
                if (isNaN(documentInput.value)) {
                    document.getElementById("document_id").value = '0';
                    alert('El RUC no tiene el formato completo');
                    $(documentInput).focus();
                } else {
                    return true;
                }
            } else {
                document.getElementById("document_id").value = '0';
                alert('El Pasaporte debe tener minimo 4 ');
                $(documentInput).focus();
            }
        } else if (document_id.value == 4) {
            if (documentInput.value.length > 7) {
                if (isNaN(documentInput.value)) {
                    document.getElementById("document_id").value = '0';
                    alert('La VISA no tiene el formato completo');
                    $(documentInput).focus();
                } else {
                    return true;
                }
            } else {
                document.getElementById("document_id").value = '0';
                alert('La VISA debe tener minimo 8 digitos');
                $(documentInput).focus();
            }
        }
    });

    $("#birth_country").change(function () {
        var birthCountryValue = document.getElementById("birth_country").value;
        getCityByCountry(birthCountryValue);
    });

    //Representative Document Button 
    document.getElementById("documentRepresentativeBtn").onclick = function () {
        hideRepresentativeMsg();
        representativeAutoFill(document.getElementById("document_representative").value);
    };
    //RepresentativeInput Click
    document.getElementById("document_representative").onclick = function () {
        hideRepresentativeMsg();
        representativeFormClear();
    };
    //Representative Input Tab
    $("#document_representative").keyup(function () {
        hideRepresentativeMsg();
        representativeFormClear();
    });
    //Representative ID Change
    $("#document_id_representative").change(function () {
        var document_id_representative = document.getElementById("document_id_representative");
        var documentInput_representative = document.getElementById("document_representative");
        if (document_id_representative.value == 1) {
            if (documentInput_representative.value.length == 10) {
                validateDocumentRepresentative();
                $(documentInput_representative).focus();
            } else {
                document.getElementById("document_id_representative").value = '';
                alert('La cedula debe tener 10 digitos');
                $(document_id_representative).focus();
            }
        }
    });
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
        nextStep("secondStep", "thirdStep");
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
});

function formAutoFill(val) {
    var documentNumber = val;
    var url = ROUTE + '/customer/document/autofill/' + documentNumber;
    if (documentNumber) {
        $.ajax({
            url: url,
            type: "GET",
            dataType: "json",
            beforeSend: function () {
            },
            success: function (data) {
                if (data.success == 'true') {
                    if (data.second_name != null) {
                        document.getElementById("second_name").disabled = true;
                    } else {
                        document.getElementById("second_name").disabled = false;
                    }
                    if (data.second_last_name != null) {
                        document.getElementById("second_last_name").disabled = true;
                    } else {
                        document.getElementById("second_last_name").disabled = false;
                    }
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
                    document.getElementById("second_name").disabled = false;
                    document.getElementById("last_name").disabled = false;
                    document.getElementById("second_last_name").disabled = false;
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
            }
        });
//        secondStepFormValidate();
    } else {
        +
                $('select[name="province"]').empty();
    }
}
function representativeAutoFill(val) {
    var documentNumber = val;
    var url = ROUTE + '/customer/document/autofill/representative/' + documentNumber;
    if (documentNumber) {
        $.ajax({
            url: url,
            type: "GET",
            dataType: "json",
            beforeSend: function () {
            },
            success: function (data) {
                if (data.success == 'true') {
                    document.getElementById("first_name_representative").disabled = true;
                    document.getElementById("last_name_representative").disabled = true;
                    document.getElementById("document_id_representative").disabled = true;
                    $("#representativeForm").autofill(data);
                } else {
                    document.getElementById("first_name_representative").disabled = false;
                    document.getElementById("last_name_representative").disabled = false;
                    document.getElementById("document_id_representative").disabled = false;
                }
            },
            complete: function () {
            }
        });
//        secondStepFormValidate();
    } else {
        $('select[name="province"]').empty();
    }
}

function documentFormClear() {
    $("#suggesstion-box").hide();
    var customerAlert = document.getElementById("customerAlert");
    $(customerAlert).addClass('hidden');
    $('#document').removeClass('inputRedFocus');
    document.getElementById("first_name").value = "";
    $('#first_name').removeClass('inputRedFocus');
    document.getElementById("second_name").value = "";
    $('#second_name').removeClass('inputRedFocus');
    document.getElementById("document_id").value = "0";
    $('#document_id').removeClass('inputRedFocus');
    document.getElementById("last_name").value = "";
    $('#last_name').removeClass('inputRedFocus');
    document.getElementById("second_last_name").value = "";
    $('#second_last_name').removeClass('inputRedFocus');
    document.getElementById("birthdate").value = "";
    $('#birthdate').removeClass('inputRedFocus');
    document.getElementById("nacionality").value = "";
    $('#nacionality').removeClass('inputRedFocus');
    document.getElementById('gender').value = "";
    $('#gender').removeClass('inputRedFocus');
    document.getElementById('civil_state').value = "";
    $('#civil_state').removeClass('inputRedFocus');
    document.getElementById('profession').value = "";
    $('#profession').removeClass('inputRedFocus');
    document.getElementById('activity').value = "";
    $('#activity').removeClass('inputRedFocus');
    document.getElementById("mobile_phone").value = "";
    $('#mobile_phone').removeClass('inputRedFocus');
    document.getElementById("phone").value = "";
    $('#phone').removeClass('inputRedFocus');
    document.getElementById("address").value = "";
    $('#address').removeClass('inputRedFocus');
    document.getElementById("work_address").value = "";
    $('#work_address').removeClass('inputRedFocus');
    document.getElementById("email").value = "";
    $('#email').removeClass('inputRedFocus');
    var emailError = document.getElementById("emailError");
    emailError.innerHTML = '';
    document.getElementById("country").value = "0";
    $('#country').removeClass('inputRedFocus');
    document.getElementById("province").value = "0";
    $('#province').removeClass('inputRedFocus');
    document.getElementById("city").value = "0";
    $('#city').removeClass('inputRedFocus');
    document.getElementById("correspondence").value = "0";
    $('#correspondence').removeClass('inputRedFocus');
    document.getElementById("first_name").disabled = true;
    document.getElementById("second_name").disabled = true;
    document.getElementById("last_name").disabled = true;
    document.getElementById("second_last_name").disabled = true;
    document.getElementById("document_id").disabled = true;
}
function representativeFormClear() {
    document.getElementById("first_name_representative").value = "";
    $('#first_name_representative').removeClass('inputRedFocus');
    document.getElementById("document_id_representative").value = "";
    $('#document_id_representative').removeClass('inputRedFocus');
    $('#document_representative').removeClass('inputRedFocus');
    document.getElementById("last_name_representative").value = "";
    $('#last_name_representative').removeClass('inputRedFocus');
    document.getElementById("birthdate_representative").value = "";
    $('#birthdate_representative').removeClass('inputRedFocus');
    document.getElementById("nationality_representative").value = "";
    $('#nationality_representative').removeClass('inputRedFocus');
    document.getElementById("relationship_representative").value = "";
    $('#relationship_representative').removeClass('inputRedFocus');
    document.getElementById("gender_representative").value = "";
    $('#gender_representative').removeClass('inputRedFocus');
    document.getElementById("first_name_representative").disabled = true;
    document.getElementById("last_name_representative").disabled = true;
    document.getElementById("document_id_representative").disabled = true;
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
function validateRuc(documentInput) {
    if (isNaN(documentInput)) {
        return false;
    } else {
        return true;
    }
}
function validateDocumentRepresentative() {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var data = {document: document.getElementById("document_representative").value};
    var url = ROUTE + '/sales/validateDocument';
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, data},
        url: url,
        async: false,
        success: function (data) {
            //console.log(data);
            if (data === 'invalid') {
                var msg = document.getElementById("representative_error");
                msg.innerHTML = 'La cedula ingresada es incorrecta';
                $(msg).removeClass('hidden');
                representativeFormClear();
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
    var documentNumber = document.getElementById("document");
    if (documentNumber.value === "") {
        $(documentNumber).addClass('inputRedFocus');
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
    var first_name = document.getElementById("first_name");
    if (first_name.value === "") {
        $(first_name).addClass('inputRedFocus');
        validate = 'true';
    }
    var last_name = document.getElementById("last_name");
    if (last_name.value === "") {
        $(last_name).addClass('inputRedFocus');
        validate = 'true';
    }
    var birthdate = document.getElementById("birthdate");
    if (birthdate.value === "") {
        $(birthdate).addClass('inputRedFocus');
        validate = 'true';
    }
    var nacionality = document.getElementById("nacionality");
    if (nacionality.value === "") {
        $(nacionality).addClass('inputRedFocus');
        validate = 'true';
    }
    var gender = document.getElementById("gender");
    if (gender.value === "") {
        $(gender).addClass('inputRedFocus');
        validate = 'true';
    }
    var civil_state = document.getElementById("civil_state");
    if (civil_state.value === "") {
        $(civil_state).addClass('inputRedFocus');
        validate = 'true';
    }
    var profession = document.getElementById("profession");
    if (profession.value === "") {
        $(profession).addClass('inputRedFocus');
        validate = 'true';
    }
    var activity = document.getElementById("activity");
    if (activity.value === "") {
        $(activity).addClass('inputRedFocus');
        validate = 'true';
    }
    var address = document.getElementById("address");
    if (address.value === "") {
        $(address).addClass('inputRedFocus');
        validate = 'true';
    }
    var work_address = document.getElementById("work_address");
    if (work_address.value === "") {
        $(work_address).addClass('inputRedFocus');
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
    var correspondence = document.getElementById("correspondence");
    if (correspondence.value === "0" || correspondence.value === "") {
        $(correspondence).addClass('inputRedFocus');
        validate = 'true';
    }
    var document_representative = document.getElementById("document_representative");
    if (document_id.value === '2' || document_representative.value != '') {
        var document_representative = document.getElementById("document_representative");
        if (document_representative.value === "" || correspondence.value === "") {
            $(document_representative).addClass('inputRedFocus');
            validate = 'true';
        }
        var document_representative = document.getElementById("document_representative");
        if (document_representative.value === "" || correspondence.value === "") {
            $(document_representative).addClass('inputRedFocus');
            validate = 'true';
        }
        var document_id_representative = document.getElementById("document_id_representative");
        if (document_id_representative.value === "" || correspondence.value === "") {
            $(document_id_representative).addClass('inputRedFocus');
            validate = 'true';
        }
        var first_name_representative = document.getElementById("first_name_representative");
        if (first_name_representative.value === "" || correspondence.value === "") {
            $(first_name_representative).addClass('inputRedFocus');
            validate = 'true';
        }
        var last_name_representative = document.getElementById("last_name_representative");
        if (last_name_representative.value === "" || correspondence.value === "") {
            $(last_name_representative).addClass('inputRedFocus');
            validate = 'true';
        }
        var birthdate_representative = document.getElementById("birthdate_representative");
        if (birthdate_representative.value === "" || correspondence.value === "") {
            $(birthdate_representative).addClass('inputRedFocus');
            validate = 'true';
        }
        var nationality_representative = document.getElementById("nationality_representative");
        if (nationality_representative.value === "") {
            $(nationality_representative).addClass('inputRedFocus');
            validate = 'true';
        }
        var relationship_representative = document.getElementById("relationship_representative");
        if (relationship_representative.value === "" || correspondence.value === "") {
            $(relationship_representative).addClass('inputRedFocus');
            validate = 'true';
        }
        var gender_representative = document.getElementById("gender_representative");
        if (gender_representative.value === "" || correspondence.value === "") {
            $(gender_representative).addClass('inputRedFocus');
            validate = 'true';
        }
    } else {
        representativeFormClear();
    }
    
    //Assets Div
    var assetsId = document.getElementsByName("assetId");
    var assetsIdList = Array.prototype.slice.call(assetsId);
    assetsIdList.forEach(validateAssets);

    if (validate === 'true') {
        var customerAlert = document.getElementById("customerAlert");
        customerAlert.classList.remove("hidden");
        $(customerAlert).addClass("visible");
        return false;
    }
    ;
    var customerAlert = document.getElementById("customerAlert");
    $(customerAlert).addClass("hidden");
    var customerDocumentFront = document.getElementById("customerDocumentFront");
    customerDocumentFront.value = documentNumber.value;
    var customerDocumentBack = document.getElementById("customerDocumentBack");
    customerDocumentBack.value = documentNumber.value;
    var customerDocumentLocal = document.getElementById("customerDocumentLocal");
    customerDocumentLocal.value = documentNumber.value;

    nextStep("firstStep", "secondStep");

    IniciarMapa();
}

function validateThirdStep() {
    //Validate Variable
    var validate = 'false';

    var select_fileFront = document.getElementById("select_fileFront");
    if (select_fileFront.files.length == 0) {
        var messageFront = document.getElementById("messageFront");
        $(messageFront).removeClass('hidden');
        validate = 'true';
    }
    var select_fileBack = document.getElementById("select_fileBack");
    if (select_fileBack.files.length == 0) {
        var messageBack = document.getElementById("messageBack");
        $(messageBack).removeClass('hidden');
        validate = 'true';
    }
    var select_fileLocal = document.getElementById("select_fileLocal");
    if (select_fileLocal.files.length == 0) {
        var messageLocal = document.getElementById("messageLocal");
        $(messageLocal).removeClass('hidden');
        validate = 'true';
    }
    if (validate === 'true') {
        return false;
    } else {
        submitForm();
    }
}
function validateSecondStep() {
    //Hide Second Step Div
    var secondStepDiv = document.getElementById("secondStep");
    $(secondStepDiv).addClass('hidden');
    //Show Third Step Div        
    var thirdStepDiv = document.getElementById("thirdStep");
    $(thirdStepDiv).removeClass('hidden');

    //Inactive Second Step Wizard
    var firstStepWizard = document.getElementById("secondStepWizard");
    $(firstStepWizard).removeClass('wizard_activo');
    $(firstStepWizard).addClass('wizard_inactivo');
    //Active Third Step Wizard
    var secondStepWizard = document.getElementById("thirdStepWizard");
    $(secondStepWizard).removeClass('wizard_inactivo');
    $(secondStepWizard).addClass('wizard_activo');
}

function validateFourthStep() {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var accountId = document.getElementById("accountId").value;
    var code = document.getElementById("code").value;
    var url = ROUTE + '/account/validateCode';
    var data = {accountId: accountId, code: code};

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
                window.location.replace(ROUTE + "/account");
            } else {
                var resultMessage = document.getElementById("resultMessage");
                $(resultMessage).removeClass('alert alert-success alert-danger');
                $(resultMessage).addClass('alert alert-danger');
                resultMessage.innerHTML = data.msg;
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
    var input = document.getElementById(id);
    $(input).removeClass('inputRedFocus');
}
function hideRepresentativeMsg() {
    var msg = document.getElementById("representative_error");
    msg.innerHTML = '';
    $(msg).addClass('hidden');
}


//MAP
var Mapa, infoWindow;
var posicion = {lat: -0.17740400, lng: -78.48588200};
$("#GLLng").val(posicion.lng);
$("#GLLat").val(posicion.lat);

function IniciarMapa()
{
    Mapa = new google.maps.Map(document.getElementById('DIVMapa'),
            {
                center: posicion,
                zoom: 16
            });
    infoWindow = new google.maps.InfoWindow;

    if (navigator.geolocation)
    {
        navigator.geolocation.getCurrentPosition(function (position)
        {
            posicion =
                    {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
            ActualizarMapa();
        },
                function () {
                    handleLocationError(true, infoWindow, Mapa.getCenter());
                });
    } else
    {
        handleLocationError(false, infoWindow, Mapa.getCenter());
    }
}

function ActualizarMapa()
{
    Mapa.setCenter(posicion);
    marcador = new google.maps.Marker
            ({
                position: posicion,
                map: Mapa,
                draggable: true,
            });
    marcador.addListener('dragend', ActualizarCoordenadas);
    $("#GLLng").val(posicion.lng);
    $("#GLLat").val(posicion.lat);
}

function ActualizarCoordenadas(event)
{
    $("#GLLng").val(event.latLng.lng());
    $("#GLLat").val(event.latLng.lat());
}

function handleLocationError(browserHasGeolocation, infoWindow, posicion)
{
    ActualizarMapa();
    infoWindow.setPosition(posicion);
    infoWindow.setContent(browserHasGeolocation ? 'El servicio de Geolocalización no está disponible en este momento.' : 'Su navegador no soporta la geolocalización.');
    infoWindow.open(Mapa);
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

function uploadPictureForm(id, side) {
    event.preventDefault();
    var form = document.getElementById(id);
    var url2 = ROUTE + "/account/ajax_upload/action";
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: url2,
        type: "POST",
        data: new FormData(form),
        contentType: false,
        cache: false,
        processData: false,
        success: function (data)
        {
            var message = '#message' + side;
            var uploaded_image = '#uploaded_image' + side;
            var messageResponse = 'message' + side;
            var upload_imageResponse = 'upload_image' + side;
            $(uploaded_image).html(data.uploaded_image);
            var deletePicture = 'deletePicture' + side;
            var uploadPicture = 'upload' + side;
            if (data.Success == 'true') {
                var uploadPic = document.getElementById("select_file" + side);
                $(uploadPic).addClass('hidden');
                var imageMas = document.getElementById("imageMas" + side);
                $(imageMas).addClass('hidden');
                var deletePic = document.getElementById(deletePicture);
                $(deletePic).removeClass('hidden');
                $(deletePic).addClass('visible');
                var uploadPic = document.getElementById(uploadPicture);
                $(uploadPic).removeClass('visible');
                $(uploadPic).addClass('hidden');
                var fileName = document.getElementById('fileName' + side);
                $(fileName).removeClass('visible');
                $(fileName).addClass('hidden');
                fileName.innerHTML = '';
                var pictureNameLocal = document.getElementById('pictureName' + side);
                pictureNameLocal.value = data.pictureName;
                var message = document.getElementById("message" + side);
                $(message).addClass('hidden');
            } else {
                var message = document.getElementById("message" + side);
                $(message).removeClass('hidden');
                $(message).html(data.message);
                $(message).addClass(data.class_name);
            }
        }
    });
}
;

function deletePictureForm(side) {
    event.preventDefault();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = ROUTE + '/account/ajax_upload/delete';
    var customerDocument = document.getElementById("customerDocument" + side);
    var data = {side: side, document: customerDocument.value};
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, data},
        url: url,
        success: function (data) {
            var uploaded_imageFront = document.getElementById("uploaded_image" + side);
            $(uploaded_imageFront).html('');
            var imageMas = document.getElementById("imageMas" + side);
            $(imageMas).removeClass('hidden');
            var uploadPic = document.getElementById("select_file" + side);
            $(uploadPic).addClass('visible');
            $(uploadPic).removeClass('hidden');
            var deletePic = document.getElementById("deletePicture" + side);
            $(deletePic).removeClass('visible');
            $(deletePic).addClass('hidden');
            var uploadPic = document.getElementById("upload" + side);
            $(uploadPic).removeClass('hidden');
            $(uploadPic).addClass('visible');
            var fileName = document.getElementById('fileName' + side);
            $(fileName).removeClass('hidden');
            $(fileName).addClass('visible');
            fileName.innerHTML = '';
            document.getElementById("select_file" + side).value = "";
        }
    });
}
;

function fileNameFunction(side) {
    var file = document.getElementById('select_file' + side).files[0];
    var uploadPic = document.getElementById("fileName" + side);
    uploadPic.innerHTML = file.name;

}
;

function submitForm() {
    //Customer Data
    var documentNumber = document.getElementById("document").value;
    var document_id = document.getElementById("document_id").value;
    var first_name = document.getElementById("first_name").value;
    var last_name = document.getElementById("last_name").value;
    var birthdate = document.getElementById("birthdate").value;
    var nacionality = document.getElementById("nacionality").value;
    var gender = document.getElementById("gender").value;
    var civil_state = document.getElementById("civil_state").value;
    var profession = document.getElementById("profession").value;
    var activity = document.getElementById("activity").value;
    var address = document.getElementById("address").value;
    var work_address = document.getElementById("work_address").value;
    var mobile_phone = document.getElementById("mobile_phone").value;
    var phone = document.getElementById("phone").value;
    var email = document.getElementById("email").value;
    var city = document.getElementById("city").value;
    var correspondence = document.getElementById("correspondence").value;

    //Representative Data
    var document_representative = document.getElementById("document_representative").value;
    var document_id_representative = document.getElementById("document_id_representative").value;
    var first_name_representative = document.getElementById("first_name_representative").value;
    var last_name_representative = document.getElementById("last_name_representative").value;
    var birthdate_representative = document.getElementById("birthdate_representative").value;
    var nationality_representative = document.getElementById("nationality_representative").value;
    var relationship_representative = document.getElementById("relationship_representative").value;
    var gender_representative = document.getElementById("gender_representative").value;

    //Location Data
    var GLLng = document.getElementById("GLLng").value;
    var GLLat = document.getElementById("GLLat").value;

    //Pictures Data
    var pictureNameFront = document.getElementById("pictureNameFront").value;
    var pictureNameBack = document.getElementById("pictureNameBack").value;
    var pictureNameLocal = document.getElementById("pictureNameLocal").value;

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = ROUTE + '/account/store';
    var data = {document: documentNumber, document_id: document_id, first_name: first_name, last_name: last_name, birthdate: birthdate, nacionality: nacionality, gender: gender, civil_state: civil_state, profession: profession, activity: activity, address: address, work_address: work_address, mobile_phone: mobile_phone, phone: phone, city: city, email: email, correspondence: correspondence, document_representative: document_representative, document_id_representative: document_id_representative, first_name_representative: first_name_representative, last_name_representative: last_name_representative, birthdate_representative: birthdate_representative, nationality_representative: nationality_representative, relationship_representative: relationship_representative, gender_representative: gender_representative, GLLng: GLLng, GLLat: GLLat, pictureNameFront: pictureNameFront, pictureNameBack: pictureNameBack, pictureNameLocal: pictureNameLocal};

    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, data},
        url: url,
        beforeSend: function () {
            // Show Loader
            $("#loaderGif").addClass('loaderGif');
        },
        success: function (data) {
            if (data.success === 'true') {
                var accountId = document.getElementById("accountId");
                accountId.value = data.accountId;
                nextStep("thirdStep", "fourthStep");
            } else {
                alert(data.msg);
            }
        },
        complete: function () {
            //Hide Loader
            var loaderGif = document.getElementById("loaderGif");
            loaderGif.classList.remove("loaderGif");
        }
    });
}

function resendCode(mobile_phone) {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var accountId = document.getElementById("accountId").value;
    var url = ROUTE + '/account/sendCode';
    var data = {accountId: accountId};

    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, data},
        url: url,
        beforeSend: function () {
            // Show Loader
            $("#loaderGif").addClass('loaderGif');
        },
        success: function (data) {
            var resultMessage = document.getElementById("resultMessage");
            $(resultMessage).removeClass('alert alert-success alert-danger');
            $(resultMessage).addClass('alert alert-success');
            resultMessage.innerHTML = 'Se envio un nuevo codigo de validación';
        },
        complete: function () {
            //Hide Loader
            var loaderGif = document.getElementById("loaderGif");
            loaderGif.classList.remove("loaderGif");
        }
    });
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
function getCityByCountry(id) {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = ROUTE + '/city/get';
    var data = {id: id};

    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, data},
        url: url,
        success: function (data) {
            document.getElementById("birth_city").innerHTML = data;
        }
    });
}
function addAssets() {
    event.preventDefault();
    var x = document.getElementsByName("assetId");
    var count = x.length;
    if (count < 5) {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var url = ROUTE + '/assets/get';
        $.ajax({
            type: "POST",
            data: {_token: CSRF_TOKEN,count},
            url: url,
            success: function (data) {
                $('#assetIdDiv').append(data.id);
                $('#assetValueDiv').append(data.value);
            }
        });
    }
}

function removeAssets(){
    event.preventDefault();
    var x = document.getElementsByName("assetId");
    var count = x.length;
    if (count > 1) {
        var x = document.getElementById("formGroupAssetsId"+count);
        x.remove();
        var x = document.getElementById("formGroupAssetsValue"+count);
        x.remove();
    }
}
function addPassives() {
    event.preventDefault();
    var x = document.getElementsByName("passiveId");
    var count = x.length;
    if (count < 5) {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var url = ROUTE + '/passives/get';
        $.ajax({
            type: "POST",
            data: {_token: CSRF_TOKEN,count},
            url: url,
            success: function (data) {
                $('#passiveIdDiv').append(data.id);
                $('#passiveValueDiv').append(data.value);
            }
        });
    }
}

function removePassives(){
    event.preventDefault();
    var x = document.getElementsByName("passiveId");
    var count = x.length;
    if (count > 1) {
        var x = document.getElementById("formGroupPassivesId"+count);
        x.remove();
        var x = document.getElementById("formGroupPassivesValue"+count);
        x.remove();
    }
}

function validateAssets(){
    
}
