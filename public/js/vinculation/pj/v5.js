/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {
    fadeOut('personalDiv');
    fadeOut('legalRepresentativeDiv');
    fadeOut('spouseDiv');
    fadeOut('beneficiaryDiv');
    fadeOut('beneficiaryDataDiv');
    fadeOut('passportDiv');
    fadeOut('occupationDiv');
    fadeOut('financingDiv');
//    fadeOut('politicsDiv');
    fadeOut('exposedPersonRequired');
    fadeOut('exposedFamilyRequired');

    //First Step Validations
    $("#document_id").change(function () { var document_id = document.getElementById("document_id"); var documentForm = document.getElementById("documentForm"); documentForm.value = ''; removeInputRedFocus("documentForm"); if (document_id.value == 3) { $('#documentForm').prop('disabled', true); fadeIn('passportFullDiv'); } else { $('#documentForm').prop('disabled', false); fadeOut('passportFullDiv'); } });
    $("#documentForm").change(function () { var documentForm = document.getElementById("documentForm"); var document_id = document.getElementById("document_id"); if (document_id.value === '1') { validateDocument(documentForm.value); } if (document_id.value === '2') { validateRuc(documentForm.value); } });
    $("#birth_city").change(function () { var birth_city = document.getElementById("birth_city"); var birth_place = document.getElementById("birth_place"); birth_place.value = birth_city.value; });
//    var rad1 = document.firstStepForm.optradio1; for (var i = 0; i < rad1.length; i++) { rad1[i].addEventListener('change', function () { fadeToggle('emailSecondaryForm'); var extraEmailInput = document.getElementById("extraEmailInput"); extraEmailInput.value = rad1.value; if (rad1.value == 'yes') { document.getElementById("email_secondary").value = ''; } }); }
    $("#civilState").change(function () {
        var civilState = document.getElementById("civilState");
        if (civilState.value == 2) {
            fadeIn('spouseFullDiv');
        } else {
            fadeOut('spouseFullDiv');
        }
    });
    
    //Second Step Validations
//    var rad = document.secondStepForm.optradio2; var prev = null; for (var i = 0; i < rad.length; i++) { rad[i].addEventListener('change', function () { fadeToggle('otherIncomeDiv'); var extraIncomeDiv = document.getElementById("extraIncomeDiv"); extraIncomeDiv.value = rad.value; if (rad.value === 'no') { document.getElementById("other_monthly_income").value = ''; document.getElementById("other_monthly_income_source").value = ''; removeInputRedFocus('other_monthly_income'); removeInputRedFocus('other_monthly_income_source'); } }); }
    $("#economic_activity").change(function () {
        var economic_activity = document.getElementById("economic_activity");
        if (economic_activity.value === '6') {
            fadeIn('otherEconomicActivityDiv');
            removeInputRedFocus('other_economic_activity');
        } else {
            fadeOut('otherEconomicActivityDiv');
            var other_economic_activity = document.getElementById("other_economic_activity");
            other_economic_activity.value = '';
        }
    });
    
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
    
    //Second Step Validations
//    var rad3 = document.thirdStepForm.optradio3; var prev = null; for (var i = 0; i < rad3.length; i++) { rad3[i].addEventListener('change', function () { var exposedPersonInput = document.getElementById("exposedPersonInput"); exposedPersonInput.value = rad.value; fadeOut('exposedPersonRequired'); }); }
//    var rad4 = document.thirdStepForm.optradio4; var prev = null; for (var i = 0; i < rad4.length; i++) { rad4[i].addEventListener('change', function () { var exposedFamilyInput = document.getElementById("exposedFamilyInput"); exposedFamilyInput.value = rad.value; fadeOut('exposedFamilyRequired'); }); }

    //First Step Button Next
    document.getElementById("firstStepBtnNext").onclick = function () { validateFirstStep(); };

    //Second Step Button Back
    document.getElementById("secondStepBtnBack").onclick = function () { nextStep('secondStep', 'firstStep'); };

    //second Step btn Next
    document.getElementById("secondStepBtnNext").onclick = function () { validateSecondStep(); };
    
    //Third Step Button Back
    document.getElementById("thirdStepBtnBack").onclick = function () { nextStep('thirdStep', 'secondStep'); };
    
    //Third Step Button Next
    document.getElementById("thirdStepBtnNext").onclick = function () { validateThirdStep(); };

    //Fourth Step Button Back
    document.getElementById("fourthStepBtnBack").onclick = function () { nextStep('fourthStep', 'thirdStep'); };
    
    //Fourth Step Button Next
    document.getElementById("fourthStepBtnNext").onclick = function () { validateFourthStep(); };
    
    //Fifth Step Button Back
    document.getElementById("fifthStepBtnBack").onclick = function () { nextStep('fifthStep', 'fourthStep'); };
    
    //Fifth Step Button Next
    document.getElementById("fifthStepBtnNext").onclick = function () { validateFifthStep(); };
});


function validateFirstStep() {
    event.preventDefault();
    var errorFound = false;
    
    //Company Validation
    var constitution_dateCompany = document.getElementById("constitution_dateCompany"); if (constitution_dateCompany.value == '') { $(constitution_dateCompany).addClass('inputRedFocus'); errorFound = true; }
    var countryConstitutionCompany = document.getElementById("countryConstitutionCompany"); if (countryConstitutionCompany.value === '') { $(countryConstitutionCompany).addClass('inputRedFocus'); errorFound = true; }
    var main_roadCompany = document.getElementById("main_roadCompany"); if (main_roadCompany.value === '') { $(main_roadCompany).addClass('inputRedFocus'); errorFound = true; } var secondary_roadCompany = document.getElementById("secondary_roadCompany"); if (secondary_roadCompany.value === '') { $(secondary_roadCompany).addClass('inputRedFocus'); errorFound = true; }
    var numberCompany = document.getElementById("numberCompany"); if (numberCompany.value === '') { $(numberCompany).addClass('inputRedFocus'); errorFound = true; }
    var sectorCompany = document.getElementById("sectorCompany"); if (sectorCompany.value === '') { $(sectorCompany).addClass('inputRedFocus'); errorFound = true; }
    var countryCompany = document.getElementById("countryCompany"); if (countryCompany.value === '') { $(countryCompany).addClass('inputRedFocus'); errorFound = true; }
    var provinceCompany = document.getElementById("provinceCompany"); if (provinceCompany.value === '') { $(provinceCompany).addClass('inputRedFocus'); errorFound = true; }
    var cityCompany = document.getElementById("cityCompany"); if (cityCompany.value === '') { $(cityCompany).addClass('inputRedFocus'); errorFound = true; }
    var emailCompany = document.getElementById("emailCompany"); if (emailCompany.value === '') { $(emailCompany).addClass('inputRedFocus'); errorFound = true; } else { var emailValidate = ValidateEmail(emailCompany.value); if (emailValidate === false) { $(emailCompany).addClass('inputRedFocus'); errorFound = true; } }
    var mobile_phoneCompany = document.getElementById("mobile_phoneCompany"); if (mobile_phoneCompany.value === '') { $(mobile_phoneCompany).addClass('inputRedFocus'); errorFound = true; }
    var phoneCompany = document.getElementById("phoneCompany"); if (phoneCompany.value === '') { $(phoneCompany).addClass('inputRedFocus'); errorFound = true; }

    //Legal Representative Validation
    var documentRL = document.getElementById("document");  if(documentRL.value == ''){ $(documentRL).addClass('inputRedFocus'); errorFound = true; }
    var document_id = document.getElementById("document_id");  if(document_id.value == ''){ $(document_id).addClass('inputRedFocus'); errorFound = true; }
    var first_name = document.getElementById("first_name");  if(first_name.value == ''){ $(first_name).addClass('inputRedFocus'); errorFound = true; }
    var last_name = document.getElementById("last_name");  if(last_name.value == ''){ $(last_name).addClass('inputRedFocus'); errorFound = true; }
    var second_name = document.getElementById("second_name");  if(second_name.value == ''){ $(second_name).addClass('inputRedFocus'); errorFound = true; }
    var second_last_name = document.getElementById("second_last_name");  if(second_last_name.value == ''){ $(second_last_name).addClass('inputRedFocus'); errorFound = true; }
    var birth_date = document.getElementById("birth_date"); if (birth_date.value == '') { $(birth_date).addClass('inputRedFocus'); errorFound = true; }
    var birthCountry = document.getElementById("birthCountry"); if (birthCountry.value == '') { $(birthCountry).addClass('inputRedFocus'); errorFound = true; }
    var birthProvince = document.getElementById("birthProvince"); if (birthProvince.value == '') { $(birthProvince).addClass('inputRedFocus'); errorFound = true; }
    var birthCity = document.getElementById("birthCity"); if (birthCity.value == '') { $(birthCity).addClass('inputRedFocus'); errorFound = true; }
    var mobile_phone = document.getElementById("mobile_phone"); if(mobile_phone.value == ''){ $(mobile_phone).addClass('inputRedFocus'); errorFound = true; }else if(isNaN(mobile_phone.value) || mobile_phone.value.length != 10){ $(mobile_phone).addClass('inputRedFocus'); errorFound = true; }
    var phone = document.getElementById("phone");  if(phone.value == ''){ $(phone).addClass('inputRedFocus'); errorFound = true; }else if(isNaN(phone.value) || phone.value.length != 9){ $(phone).addClass('inputRedFocus'); errorFound = true; }
    var address = document.getElementById("address");  if(address.value == ''){ $(address).addClass('inputRedFocus'); errorFound = true; }
    var email = document.getElementById("email"); if (email.value == '') { $(email).addClass('inputRedFocus'); errorFound = true; } else {  var emailValidate = ValidateEmail(email.value); if (emailValidate === false) { $(email).addClass('inputRedFocus'); errorFound = true; } }
    var country = document.getElementById("country");  if(country.value == ''){ $(country).addClass('inputRedFocus'); errorFound = true; }
    var province = document.getElementById("province"); if (province.value == '') { $(province).addClass('inputRedFocus'); errorFound = true; }
    var city = document.getElementById("city");  if(city.value == ''){ $(city).addClass('inputRedFocus'); errorFound = true; }
    var civilState = document.getElementById("civilState"); if (civilState.value == '') { $(civilState).addClass('inputRedFocus'); errorFound = true; }
    
    //Spouse Validation
    if(civilState.value == '2'){
        var spouseDocument = document.getElementById("spouseDocument"); if (spouseDocument.value == '') { $(spouseDocument).addClass('inputRedFocus'); errorFound = true; }
        var spouse_document_id = document.getElementById("spouse_document_id"); if (spouse_document_id.value == '') { $(spouse_document_id).addClass('inputRedFocus'); errorFound = true; }
        var spouseFirstName = document.getElementById("spouseFirstName"); if (spouseFirstName.value == '') { $(spouseFirstName).addClass('inputRedFocus'); errorFound = true; }
        var spouseLastName = document.getElementById("spouseLastName"); if (spouseLastName.value == '') { $(spouseLastName).addClass('inputRedFocus'); errorFound = true; }
    }
    
    if (errorFound === false) {
        //Store Data
        var form = document.getElementById('firstStepForm');
        var url = ROUTE + "/vinculation/firstStepForm";
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: url,
            type: "POST",
            data: new FormData(form),
            contentType: false,
            cache: false,
            processData: false,
            success: function (data)
            {
//                nextStep('firstStep', 'secondStep');
            }
        });
    }
}

function validateSecondStep() {
    event.preventDefault();
    nextStep('secondStep', 'thirdStep');return true;
    var errorFound = false;
    
    //Economic Activity
    var economic_activity = document.getElementById("economic_activity"); if (economic_activity.value === '') { $(economic_activity).addClass('inputRedFocus'); errorFound = true; }
    if(economic_activity.value === '7'){ var other_economic_activity = document.getElementById("other_economic_activity"); if (other_economic_activity.value === '') { $(other_economic_activity).addClass('inputRedFocus'); errorFound = true; } }
    
    //Financing
    var monthly_income = document.getElementById("monthly_income"); if (monthly_income.value === '') { $(monthly_income).addClass('inputRedFocus'); errorFound = true; }
    var monthly_outcome = document.getElementById("monthly_outcome"); if (monthly_outcome.value === '') { $(monthly_outcome).addClass('inputRedFocus'); errorFound = true; }
    var total_actives = document.getElementById("total_actives"); if (total_actives.value === '') { $(total_actives).addClass('inputRedFocus'); errorFound = true; }
    var total_pasives = document.getElementById("total_pasives"); if (total_pasives.value === '') { $(total_pasives).addClass('inputRedFocus'); errorFound = true; }
    var extraIncomeDiv = document.getElementById("extraIncomeDiv");
    if(extraIncomeDiv.value === 'yes'){
        var other_monthly_income = document.getElementById("other_monthly_income"); if (other_monthly_income.value === '') { $(other_monthly_income).addClass('inputRedFocus'); errorFound = true; }
        var other_monthly_income_source = document.getElementById("other_monthly_income_source"); if (other_monthly_income_source.value === '') { $(other_monthly_income_source).addClass('inputRedFocus'); errorFound = true; }
    }
    if(errorFound == false){
        //Store Data
        var form = document.getElementById('secondStepForm');
        var url = ROUTE + "/vinculation/secondStepForm";
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: url,
            type: "POST",
            data: new FormData(form),
            contentType: false,
            cache: false,
            processData: false,
            success: function (data)
            {
                nextStep('secondStep', 'thirdStep');
            }
        });
    }
}

function validateThirdStep(){
    event.preventDefault();
    var errorFound = false;
    nextStep('thirdStep','fourthStep');return true;
    
    var exposedPersonInput = document.getElementById("exposedPersonInput"); if (exposedPersonInput.value === '') { fadeIn('exposedPersonRequired'); errorFound = true; }
    var exposedFamilyInput = document.getElementById("exposedFamilyInput"); if (exposedFamilyInput.value === '') { fadeIn('exposedFamilyRequired'); errorFound = true; }
    
    if(errorFound == false){
        //Store Data
        var form = document.getElementById('thirdStepForm');
        var url = ROUTE + "/vinculation/thirdStepForm";
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: url,
            type: "POST",
            data: new FormData(form),
            contentType: false,
            cache: false,
            processData: false,
            success: function (data)
            {
                nextStep('thirdStep', 'fourthStep');
            }
        });
    }
}

function validateFourthStep(){
    event.preventDefault();
    var errorFound = false;
    //Send Mail to User
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var saleId = document.getElementById("saleId").value;
        var url = ROUTE + '/vinculation/token/generate';
        $.ajax({
            type: "POST",
            data: {_token: CSRF_TOKEN, saleId: saleId},
            url: url,
            beforeSend: function () {
                // Show Loader
                $("#loaderGif").addClass('loaderGif');
            },
            success: function (data) {
                var fifthStepAlert = document.getElementById("fifthStepAlert");
                $(fifthStepAlert).addClass('hidden');
                nextStep('fourthStep','fifthStep');
            },
            complete: function () {
                //Hide Loader
                var loaderGif = document.getElementById("loaderGif");
                loaderGif.classList.remove("loaderGif");
            }
        });
        return true;
//    nextStep('fourthStep','fifthStep'); return true;
    
    //Applicant Validations
    var uploadedFileDocumentApplicant = document.getElementById("uploadedFileDocumentApplicant"); if(uploadedFileDocumentApplicant.value == ''){ var messageDocumentApplicant = document.getElementById("messageDocumentApplicant"); $(messageDocumentApplicant).css('display', 'block'); $(messageDocumentApplicant).html('Debe ingresar una imagen'); $(messageDocumentApplicant).addClass('alert alert-danger'); }
    var uploadedFileVotingBallotApplicant = document.getElementById("uploadedFileVotingBallotApplicant"); if(uploadedFileVotingBallotApplicant.value == ''){ var messageVotingBallotApplicant = document.getElementById("messageVotingBallotApplicant"); $(messageVotingBallotApplicant).css('display', 'block'); $(messageVotingBallotApplicant).html('Debe ingresar una imagen'); $(messageVotingBallotApplicant).addClass('alert alert-danger'); errorFound = true; }
    var uploadedFilePictureService = document.getElementById("uploadedFilePictureService"); if(uploadedFilePictureService.value == ''){ var messageService = document.getElementById("messageService"); $(messageService).css('display', 'block'); $(messageService).html('Debe ingresar una imagen'); $(messageService).addClass('alert alert-danger'); }
    //Spouse Validations
    var civilState = document.getElementById("civilState");
    if (civilState.value == 2) {
        var uploadedFileDocumentSpouse = document.getElementById("uploadedFileDocumentSpouse"); if(uploadedFileDocumentSpouse.value == ''){ var messageDocumentSpouse = document.getElementById("messageDocumentSpouse"); $(messageDocumentSpouse).css('display', 'block'); $(messageDocumentSpouse).html('Debe ingresar una imagen'); $(messageDocumentSpouse).addClass('alert alert-danger'); errorFound = true; }
        var uploadedFileVotingBallotSpouse = document.getElementById("uploadedFileVotingBallotSpouse"); if(uploadedFileVotingBallotSpouse.value == ''){ var messageVotingBallotSpouse = document.getElementById("messageVotingBallotSpouse"); $(messageVotingBallotSpouse).css('display', 'block'); $(messageVotingBallotSpouse).html('Debe ingresar una imagen'); $(messageVotingBallotSpouse).addClass('alert alert-danger'); }
    }
    if(errorFound == false){
        nextStep('fourthStep','fifthStep');
        //Conexion a la firma pendiente
    }
}

function validateFifthStep(){
    var tokenCode = document.getElementById("tokenCode");
    if(tokenCode.value == ''){
        $(tokenCode).addClass('inputRedFocus');
    }else{
        //Validate Code
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var saleId = document.getElementById("saleId").value;
        var tokenCode = document.getElementById("tokenCode").value;
        var url = ROUTE + '/vinculation/token/validate';
        $.ajax({
            type: "POST",
            data: {_token: CSRF_TOKEN, saleId: saleId, tokenCode:tokenCode},
            url: url,
            async: false,
            beforeSend: function () {
                // Show Loader
                $("#loaderGif").addClass('loaderGif');
            },
            success: function (data) {
                if(data == '200'){
                   //Send Mail to User
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    var saleId = document.getElementById("saleId");
                    var documentForm = document.getElementById("documentForm");
                    var data = {saleId: saleId.value, document: documentForm.value};
                    var url = ROUTE + '/vinculation/complete';
                    $.ajax({
                        type: "POST",
                        data: {_token: CSRF_TOKEN, data},
                        url: url,
                        async: false,
                        success: function (data) {
                            var fifthStepAlert = document.getElementById("fifthStepAlert");
                            $(fifthStepAlert).removeClass('hidden');
                            $(fifthStepAlert).removeClass('alert-danger');
                            $(fifthStepAlert).addClass('alert-success');
                            fifthStepAlert.innerHTML = 'El codigo es correcto';
                            var fifthStepBtnNext = document.getElementById("fifthStepBtnNext");
                            fifthStepBtnNext.disabled = true;
                        }
                    }); 
                }else{
                    var fifthStepAlert = document.getElementById("fifthStepAlert");
                    $(fifthStepAlert).removeClass('hidden');
                    $(fifthStepAlert).removeClass('alert-success');
                    $(fifthStepAlert).addClass('alert-danger');
                    fifthStepAlert.innerHTML = 'El codigo ingresado no es correcto';
                    var fifthStepBtnNext = document.getElementById("fifthStepBtnNext");
                    fifthStepBtnNext.disabled = true;
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

function emailIsValid(email) {
    return /\S+@\S+\.\S+/.test(email);
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
function removeInputRedFocus(id) {
    var input = document.getElementById(id);
    $(input).removeClass('inputRedFocus');
}
function validateDocument() {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var documentForm = document.getElementById("documentForm");
    var data = {document: documentForm.value};
    var url = ROUTE + '/sales/validateDocument';
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, data},
        url: url,
        async: false,
        success: function (data) {
            if (data === 'invalid') {
                $(documentForm).addClass('inputRedFocus');
                documentForm.value = '';
                alert('La cedula ingresada es incorrecta');
            } else {
                removeInputRedFocus("documentForm");
            }
        }
    });
}

function validateRuc(ruc) {
    var documentForm = document.getElementById("documentForm");
    if ((ruc.length !== 13) || (isNaN(ruc))) {
        $(documentForm).addClass('inputRedFocus');
        documentForm.value = '';
        alert('El RUC ingresado es incorrecto');
    } else {
        removeInputRedFocus("documentForm");
    }
}

function uploadPictureForm(id) {
    event.preventDefault();
    var form = document.getElementById("upload_form"+id);
    var url = ROUTE + "/vinculation/upload";
    $.ajax({
        url: url,
        type: "POST",
        data: new FormData(form),
        contentType: false,
        cache: false,
        processData: false,
        success: function (data)
        {
            var message = '#message' + id;   
            $(message).css('display', 'none');
            var uploaded_image = '#uploaded_image' + id;
            $(uploaded_image).html(data.uploaded_image);
            var deletePicture = 'deletePicture' + id;
            var uploadPicture = 'upload' + id;
            if (data.Success == 'true') {
                var uploadPic = document.getElementById("select_file" + id);
                $(uploadPic).addClass('hidden');
                var deletePic = document.getElementById(deletePicture);
                $(deletePic).removeClass('hidden');
                $(deletePic).addClass('visible');
                var uploadPic = document.getElementById(uploadPicture);
                $(uploadPic).removeClass('visible');
                $(uploadPic).addClass('hidden');
                var fileName = document.getElementById('fileName' + id);
                $(fileName).removeClass('visible');
                $(fileName).addClass('hidden');
                fileName.innerHTML = '';
                var uploadedFile = document.getElementById('uploadedFile' + id);
                uploadedFile.value = 'file';
            } else {
                $(message).css('display', 'block');
                $(message).html(data.message);
                $(message).addClass(data.class_name);
            }
        }
    });
}

function deletePictureForm(id, customer, sale) {
    event.preventDefault();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = ROUTE + '/vinculation/delete';
    var data = {id: id, customer: customer, sale:sale};
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, data},
        url: url,
        success: function (data) {
            var uploaded_imageFront = document.getElementById("uploaded_image" + id);
            $(uploaded_imageFront).html('');
            var uploadPic = document.getElementById("select_file"  + id);
            $(uploadPic).addClass('visible');
            $(uploadPic).removeClass('hidden');
            var deletePic = document.getElementById("deletePicture" + id);
            $(deletePic).removeClass('visible');
            $(deletePic).addClass('hidden');
            var uploadPic = document.getElementById("upload"  + id);
            $(uploadPic).removeClass('hidden');
            $(uploadPic).addClass('visible');
            var fileName = document.getElementById('fileName' + id);
            $(fileName).removeClass('hidden');
            $(fileName).addClass('visible');
            fileName.innerHTML = '';
            var uploadedFile = document.getElementById('uploadedFile' + id);
            uploadedFile.value = '';
        }
    });
}

function fileNameFunction(id) {
    var file = document.getElementById('select_file' + id).files[0];
    var uploadPic = document.getElementById("fileName" + id);
    uploadPic.innerHTML = file.name;

}

function isBeneficiaryChange(obj){
    fadeToggle('beneficiaryDataDiv');
}
