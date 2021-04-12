$(document).ready(function () {
    fadeOut('personalDiv');
    fadeOut('legalRepresentativeDiv');
    fadeOut('spouseDiv');
    fadeOut('beneficiaryDiv');
    fadeOut('spouseFullDiv');
    fadeOut('formDocumentSpouse');
    $("#document_id").change(function () { var document_id = document.getElementById("document_id"); var documentForm = document.getElementById("documentForm"); documentForm.value = ''; removeInputRedFocus("documentForm"); if (document_id.value == 3) { $('#documentForm').prop('disabled', true); fadeIn('passportFullDiv'); } else { $('#documentForm').prop('disabled', false); fadeOut('passportFullDiv'); } });
    $("#documentForm").change(function () { var documentForm = document.getElementById("documentForm"); var document_id = document.getElementById("document_id"); if (document_id.value === '1') { validateDocument(documentForm.value); } if (document_id.value === '2') { validateRuc(documentForm.value); } });
    $("#birth_city").change(function () { var birth_city = document.getElementById("birth_city"); var birth_place = document.getElementById("birth_place"); birth_place.value = birth_city.value; });

    $("#civilState").change(function () {
        var civilState = document.getElementById("civilState");
        if (civilState.value == 2 || civilState.value == 5) {
            fadeIn('spouseFullDiv'); fadeIn('formDocumentSpouse');
        } else {
            fadeOut('spouseFullDiv'); fadeOut('formDocumentSpouse');
        }
    });
    $("#document").change(function () {
        var document_id = document.getElementById("document");
        var documentInput = document.getElementById("document");
        if (document_id.value == 1) {
            if (documentInput.value.length == 10) {
                validateDocument();
                $(documentInput).focus();
            } else {
                document.getElementById("document").value = '0';
                alert('La cedula debe tener 10 dígitos');
                $(documentInput).focus();
            }
        }
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
    setInputFilter(document.getElementById("mobile_phone"), function(value) {
        return /^-?\d*[.]?\d*$/.test(value); 
    });
    setInputFilter(document.getElementById("phone"), function(value) {
        return /^-?\d*[.]?\d*$/.test(value); 
    });
    setInputFilter(document.getElementById("mobile_phoneCompany"), function(value) {
        return /^-?\d*[.]?\d*$/.test(value); 
    });
    setInputFilter(document.getElementById("phoneCompany"), function(value) {
        return /^-?\d*[.]?\d*$/.test(value); 
    });
    setInputFilter(document.getElementById("document"), function(value) {
        return /^-?\d*[.]?\d*$/.test(value); 
    });
    setInputFilter(document.getElementById("spouseDocument"), function(value) {
        return /^\d*\.?\d*$/.test(value); 
    });

    setInputFilter(document.getElementById("beneficiary_document"), function(value) {
        return /^\d*\.?\d*$/.test(value); 
    });

    setInputFilter(document.getElementById("annual_income"), function(value) {
//        return /^\d*\.?\d*$/.test(value); 
        return /^-?\d*[.]?\d{0,2}$/.test(value); 
    });
    setInputFilter(document.getElementById("beneficiary_phone"), function(value) {
        return /^\d*\.?\d*$/.test(value); 
    });
    $("#spouse_document_id").change(function () {
        validateDocumentSpouse();
    });

    $("#beneficiary_document_id").change(function () {
        validateDocumentBeneficiary();
    });
    document.getElementById("document").onclick = function () {
        $("#suggesstion-box").hide();
        document.getElementById("first_name").value = "";
        document.getElementById("second_name").value = "";
        document.getElementById("document_id").value = "0";
        document.getElementById("last_name").value = "";
        document.getElementById("second_last_name").value = "";
        document.getElementById("mobile_phone").value = "";
        document.getElementById("nationality").value = "0";
        document.getElementById("birthCountry").value = "0";
        document.getElementById("birthProvince").value = "0";
        document.getElementById("birthCity").value = "0";
        document.getElementById("birthdate").value = "";
        document.getElementById("civilState").value = "0";
        document.getElementById("address").value = "";
        document.getElementById("country").value = "0";
        document.getElementById("province").value = "0";
        document.getElementById("city").value = "0";
        document.getElementById("parroquia").value = "";
        document.getElementById("sector").value = "";
        document.getElementById("phone").value = "";
        document.getElementById("email").value = "";
        document.getElementById("first_name").disabled = true;
        document.getElementById("second_name").disabled = true;
        document.getElementById("last_name").disabled = true;
        document.getElementById("second_last_name").disabled = true;
        document.getElementById("document_id").disabled = true;
        validateInput();
    };
    $("#document").keyup(function () {
        $("#suggesstion-box").hide();
        
        document.getElementById("document_id").value = "0";
        document.getElementById("first_name").value = "";
        document.getElementById("second_name").value = "";
        document.getElementById("last_name").value = "";
        document.getElementById("second_last_name").value = "";
        document.getElementById("nationality").value = "0";
        document.getElementById("birthCountry").value = "0";
        document.getElementById("birthProvince").value = "0";
        document.getElementById("birthCity").value = "0";
        document.getElementById("birthdate").value = "";
        document.getElementById("civilState").value = "0";
        document.getElementById("address").value = "";
        document.getElementById("country").value = "0";
        document.getElementById("province").value = "0";
        document.getElementById("city").value = "0";
        document.getElementById("parroquia").value = "";
        document.getElementById("sector").value = "";
        document.getElementById("phone").value = "";
        document.getElementById("mobile_phone").value = "";
        document.getElementById("email").value = "";
        document.getElementById("first_name").disabled = true;
        document.getElementById("second_name").disabled = true;
        document.getElementById("last_name").disabled = true;
        document.getElementById("second_last_name").disabled = true;
        document.getElementById("document_id").disabled = true;
        validateInput();
    });
     ////// Validar Campos Datos Compañia /////
     $("#business_name").change(function () {
        $(this).removeClass('inputRedFocus');
        var business_name_validation = document.getElementById("business_name_validation");
        $(business_name_validation).addClass('hidden');
        
    });
    $("#documentCompany").change(function () {
        $(this).removeClass('inputRedFocus');
        var documentCompany_validation = document.getElementById("documentCompany_validation");
        $(documentCompany_validation).addClass('hidden');
        
    });
    $("#occupation").change(function () {
        $(this).removeClass('inputRedFocus');
        var occupation_validation = document.getElementById("occupation_validation");
        $(occupation_validation).addClass('hidden');
        
    });
    $("#constitution_dateCompany").change(function () {
        $(this).removeClass('inputRedFocus');
        var constitution_dateCompany_validation = document.getElementById("constitution_dateCompany_validation");
        $(constitution_dateCompany_validation).addClass('hidden');
        
    });
    $("#main_roadCompany").change(function () {
        $(this).removeClass('inputRedFocus');
        var main_roadCompany_validation = document.getElementById("main_roadCompany_validation");
        var main_roadCompany_validation_length=document.getElementById("main_roadCompany_validation_length");
        $(main_roadCompany_validation).addClass('hidden');
        $(main_roadCompany_validation_length).addClass('hidden');
        
    });
    $("#secondary_roadCompany").change(function () {
        $(this).removeClass('inputRedFocus');
        var secondary_roadCompany_validation = document.getElementById("secondary_roadCompany_validation");
        $(secondary_roadCompany_validation).addClass('hidden');
        
    });
    $("#numberCompany").change(function () {
        $(this).removeClass('inputRedFocus');
        var numberCompany_validation = document.getElementById("numberCompany_validation");
        $(numberCompany_validation).addClass('hidden');
        
    });
    $("#countryCompany").change(function () {
        $(this).removeClass('inputRedFocus');
        var countryCompany_validation = document.getElementById("countryCompany_validation");
        $(countryCompany_validation).addClass('hidden');
        
    });
    $("#provinceCompany").change(function () {
        $(this).removeClass('inputRedFocus');
        var provinceCompany_validation = document.getElementById("provinceCompany_validation");
        $(provinceCompany_validation).addClass('hidden');
        
    });
    $("#cityCompany").change(function () {
        $(this).removeClass('inputRedFocus');
        var cityCompany_validation = document.getElementById("cityCompany_validation");
        $(cityCompany_validation).addClass('hidden');
        
    });
    $("#parroquiaCompany").change(function () {
        $(this).removeClass('inputRedFocus');
        var parroquiaCompany_validation = document.getElementById("parroquiaCompany_validation");
        $(parroquiaCompany_validation).addClass('hidden');
        
    });
    $("#sectorCompany").change(function () {
        $(this).removeClass('inputRedFocus');
        var sectorCompany_validation = document.getElementById("sectorCompany_validation");
        $(sectorCompany_validation).addClass('hidden');
        
    });
    $("#phoneCompany").change(function () {
        $(this).removeClass('inputRedFocus');
        var phoneCompany_validation = document.getElementById("phoneCompany_validation");
        $(phoneCompany_validation).addClass('hidden');
        
    });
    $("#mobile_phoneCompany").change(function () {
        $(this).removeClass('inputRedFocus');
        var mobile_phoneCompany_validation = document.getElementById("mobile_phoneCompany_validation");
        $(mobile_phoneCompany_validation).addClass('hidden');
        
    });
    $("#emailCompany").change(function () {
        $(this).removeClass('inputRedFocus');
        var emailCompany_validation = document.getElementById("emailCompany_validation");
        var emailCompany_error = document.getElementById("emailCompany_error");
        $(emailCompany_validation).addClass('hidden');
        $(emailCompany_error).addClass('hidden');
    });
    ////// LegalPerson /////
    $("#document").change(function () {
        $(this).removeClass('inputRedFocus');
        var document_validation = document.getElementById("document_validation");
        $(document_validation).addClass('hidden');
        
    });
    $("#document_id").change(function () {
        $(this).removeClass('inputRedFocus');
        var document_id_validation= document.getElementById("document_id_validation");
        $(document_id_validation).addClass('hidden');
    
    });
    $("#first_name").change(function () {
        $(this).removeClass('inputRedFocus');
        var first_name_validation = document.getElementById("first_name_validation");
        $(first_name_validation).addClass('hidden');

    });
    $("#last_name").change(function () {
        $(this).removeClass('inputRedFocus');
        var last_name_validation = document.getElementById("last_name_validation");
        $(last_name_validation).addClass('hidden');

    });
    $("#second_name").change(function () {
        $(this).removeClass('inputRedFocus');
        var second_name_validation = document.getElementById("second_name_validation");
        $(second_name_validation).addClass('hidden');

    });
    $("#second_last_name").change(function () {
        $(this).removeClass('inputRedFocus');
        var second_last_name_validation = document.getElementById("second_last_name_validation");
        $(second_last_name_validation).addClass('hidden');

    });
    $("#nationality").change(function () {
        $(this).removeClass('inputRedFocus');
        var nationality_validation = document.getElementById("nationality_validation");
        $(nationality_validation).addClass('hidden');

    });
      
    $("#birthCountry").change(function () {
        $(this).removeClass('inputRedFocus');
        var birthCountry_validation = document.getElementById("birthCountry_validation");
        $(birthCountry_validation).addClass('hidden');

    });
    $("#birthProvince").change(function () {
        $(this).removeClass('inputRedFocus');
        var birthProvince_validation = document.getElementById("birthProvince_validation");
        $(birthProvince_validation).addClass('hidden');

    });
    $("#birthCity").change(function () {
        $(this).removeClass('inputRedFocus');
        var birthCity_validation = document.getElementById("birthCity_validation");
        $(birthCity_validation).addClass('hidden');

    });
    $("#birthdate").change(function () {
        $(this).removeClass('inputRedFocus');
        var birth_date_validation = document.getElementById("birth_date_validation");
        $(birth_date_validation).addClass('hidden');

    });
    $("#civilState").change(function () {
        $(this).removeClass('inputRedFocus');
        var civilState_validation = document.getElementById("civilState_validation");
        $(civilState_validation).addClass('hidden');

    });
    $("#address").change(function () {
        $(this).removeClass('inputRedFocus');
        var address_validation = document.getElementById("address_validation");
        var address_validation_length = document.getElementById("address_validation_length");
        $(address_validation).addClass('hidden');
        $(address_validation_length).addClass('hidden');

    });
    $("#country").change(function () {
        $(this).removeClass('inputRedFocus');
        var country_validation = document.getElementById("country_validation");
        $(country_validation).addClass('hidden');

    });
    
    $("#province").change(function () {
        $(this).removeClass('inputRedFocus');
        var province_validation = document.getElementById("province_validation");
        $(province_validation).addClass('hidden');

    });

    $("#city").change(function () {
        $(this).removeClass('inputRedFocus');
        var city_validation = document.getElementById("city_validation");
        $(city_validation).addClass('hidden');

    });

    $("#parroquia").change(function () {
        $(this).removeClass('inputRedFocus');
        var parroquia_validation = document.getElementById("parroquia_validation");
        $(parroquia_validation).addClass('hidden');

    });
    
    $("#sector").change(function () {
        $(this).removeClass('inputRedFocus');
        var sector_validation = document.getElementById("sector_validation");
        $(sector_validation).addClass('hidden');

    });
    $("#phone").change(function () {
        $(this).removeClass('inputRedFocus');
        var phone_validation = document.getElementById("phone_validation");
        $(phone_validation).addClass('hidden');
        var phone_validation_length = document.getElementById("phone_validation_length");
        $(phone_validation_length).addClass('hidden');

    });
    $("#mobile_phone").change(function () {
        $(this).removeClass('inputRedFocus');
        var mobile_phone_validation = document.getElementById("mobile_phone_validation");
        $(mobile_phone_validation).addClass('hidden');
        var mobile_phone_validation_length= document.getElementById("mobile_phone_validation_length");
        $(mobile_phone_validation_length).addClass('hidden');;
    });
    $("#email").change(function () {
        $(this).removeClass('inputRedFocus');
        var email_validation = document.getElementById("email_validation");
        var email_error = document.getElementById("email_error");
        $(email_validation).addClass('hidden');
        $(email_error).addClass('hidden');
    });
    ////////////////////////////////////////
    $("#spouseDocument").change(function () {
        var spouseDocument_validation = document.getElementById("spouseDocument_validation");
         $(spouseDocument_validation).addClass("hidden");
    });
    $("#spouse_document_id").change(function () {
        var spouse_document_id_validation= document.getElementById("spouse_document_id_validation");
         $(spouse_document_id_validation).addClass("hidden");
    });
    $("#spouseFirstName").change(function () {
        var spouseFirstName_validation= document.getElementById("spouseFirstName_validation");
         $(spouseFirstName_validation).addClass("hidden");
         var spouseFirstName_validation_length= document.getElementById("spouseFirstName_validation_length");
         $(spouseFirstName_validation_length).addClass("hidden");
    });
    $("#spouseLastName").change(function () {
        var spouseLastName_validation= document.getElementById("spouseLastName_validation");
         $(spouseLastName_validation).addClass("hidden");
         var spouseLastName_validation_length= document.getElementById("spouseLastName_validation_length");
         $(spouseLastName_validation_length).addClass("hidden");
    });
    ///////////////////////////////////////
    $("#beneficiaryName").change(function () {
        var beneficiaryName_validation= document.getElementById("beneficiaryName_validation");
         $(beneficiaryName_validation).addClass("hidden");
         var beneficiaryName_validation_length= document.getElementById("beneficiaryName_validation_length");
         $(beneficiaryName_validation_length).addClass("hidden");
    });
    $("#beneficiary_document").change(function () {
        var beneficiary_document_validation= document.getElementById("beneficiary_document_validation");
         $(beneficiary_document_validation).addClass("hidden");
    });
    $("#beneficiary_document_id").change(function () {
        var beneficiary_document_id_validation= document.getElementById("beneficiary_document_id_validation");
         $(beneficiary_document_id_validation).addClass("hidden");
    });
    $("#beneficiary_nationality").change(function () {
        var beneficiary_nationality_validation= document.getElementById("beneficiary_nationality_validation");
         $(beneficiary_nationality_validation).addClass("hidden");
    });
    $("#beneficiary_address").change(function () {
        var beneficiary_address_validation= document.getElementById("beneficiary_address_validation");
         $(beneficiary_address_validation).addClass("hidden");
         var beneficiary_address_validation_length= document.getElementById("beneficiary_address_validation_length");
         $(beneficiary_address_validation_length).addClass("hidden");
    });
    $("#beneficiary_phone").change(function () {
        var beneficiary_phone_validation= document.getElementById("beneficiary_phone_validation");
         $(beneficiary_phone_validation).addClass("hidden");
         var beneficiary_phone_validation_length= document.getElementById("beneficiary_phone_validation_length");
         $(beneficiary_phone_validation_length).addClass("hidden");
    });
    $("#beneficiary_relationship").change(function () {
        var beneficiary_relationship_validation= document.getElementById("beneficiary_relationship_validation");
         $(beneficiary_relationship_validation).addClass("hidden");
         var beneficiary_relationship_validation_length= document.getElementById("beneficiary_relationship_validation_length");
         $(beneficiary_relationship_validation_length).addClass("hidden");
    });

    $("#annual_income").change(function () {
        $(this).removeClass('inputRedFocus');
        var annual_income_validation= document.getElementById("annual_income_validation");
         $(annual_income_validation).addClass("hidden");
    });
    
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
function validateFirstStep() {
    event.preventDefault();
    //Validate Inputs
    var validate = 'false';
    
    //Company Validation
    var business_name = document.getElementById("business_name");
    var business_name_validation = document.getElementById("business_name_validation");
    if (business_name.value === "" || business_name.value.length > 60) {
        $(business_name).addClass('inputRedFocus');
        $(business_name_validation).removeClass('hidden');
        validate = 'true';
    }
    var documentCompany = document.getElementById("documentCompany");
    var documentCompany_validation=document.getElementById("documentCompany_validation");
    if (documentCompany.value === "") {
        $(documentCompany).addClass('inputRedFocus');
        $(documentCompany_validation).removeClass('hidden');
        validate = 'true';
    }
    var occupation= document.getElementById("occupation");
    var occupation_validation= document.getElementById("occupation_validation");
    if (occupation.value === "0") {
        $(occupation).addClass('inputRedFocus');
        $(occupation_validation).removeClass('hidden');
        validate = 'true';
    } 
    var constitution_dateCompany = document.getElementById("constitution_dateCompany");
    var constitution_dateCompany_validation=document.getElementById("constitution_dateCompany_validation");
    if (constitution_dateCompany.value === "") {
        $(constitution_dateCompany).addClass('inputRedFocus');
        $(constitution_dateCompany_validation).removeClass('hidden');
        validate = 'true';
    }
    var main_roadCompany = document.getElementById("main_roadCompany");
    var main_roadCompany_validation=document.getElementById("main_roadCompany_validation");
    var main_roadCompany_validation_length=document.getElementById("main_roadCompany_validation_length");
    if (main_roadCompany.value === "") {
        $(main_roadCompany).addClass('inputRedFocus');
        $(main_roadCompany_validation).removeClass('hidden');
        validate = 'true';
    }else if(main_roadCompany.value.length > 30) {
        $(main_roadCompany).addClass('inputRedFocus');
        $(main_roadCompany_validation_length).removeClass('hidden');
        validate = 'true';
    }
    var secondary_roadCompany = document.getElementById("secondary_roadCompany");
    var secondary_roadCompany_validation=document.getElementById("secondary_roadCompany_validation");
    if (secondary_roadCompany.value === "" || secondary_roadCompany.value.length > 30) {
        $(secondary_roadCompany).addClass('inputRedFocus');
        $(secondary_roadCompany_validation).removeClass('hidden');
        validate = 'true';
    }
    var numberCompany = document.getElementById("numberCompany");
    var numberCompany_validation=document.getElementById("numberCompany_validation");
    if (numberCompany.value === "" || numberCompany.value.length > 30) {
        $(numberCompany).addClass('inputRedFocus');
        $(numberCompany_validation).removeClass('hidden');
        validate = 'true';
    }
    var countryCompany= document.getElementById("countryCompany");
    var countryCompany_validation= document.getElementById("countryCompany_validation");
    if (countryCompany.value === "0") {
        $(countryCompany).addClass('inputRedFocus');
        $(countryCompany_validation).removeClass('hidden');
        validate = 'true';
    } 
    var provinceCompany= document.getElementById("provinceCompany");
    var provinceCompany_validation= document.getElementById("provinceCompany_validation");
    if (provinceCompany.value === "0") {
        $(provinceCompany).addClass('inputRedFocus');
        $(provinceCompany_validation).removeClass('hidden');
        validate = 'true';
    } 
    var cityCompany= document.getElementById("cityCompany");
    var cityCompany_validation= document.getElementById("cityCompany_validation");
    if (cityCompany.value === "0") {
        $(cityCompany).addClass('inputRedFocus');
        $(cityCompany_validation).removeClass('hidden');
        validate = 'true';
    } 
    
    var sectorCompany = document.getElementById("sectorCompany");
    var sectorCompany_validation=document.getElementById("sectorCompany_validation");
    if (sectorCompany.value === "" || sectorCompany.value.length > 30) {
        $(sectorCompany).addClass('inputRedFocus');
        $(sectorCompany_validation).removeClass('hidden');
        validate = 'true';
    }
    var phoneCompany = document.getElementById("phoneCompany");
    var phoneCompany_validation = document.getElementById("phoneCompany_validation");
    var phoneCompany_validation_length= document.getElementById("phoneCompany_validation_length");
    if (phoneCompany.value === "") {
        $(phoneCompany).addClass('inputRedFocus');
        $(phoneCompany_validation).removeClass('hidden');
        validate = 'true';
    } else {
        if (isNaN(phoneCompany.value)) {
            $(phoneCompany).addClass('inputRedFocus');
            validate = 'true';
        }
        if (phoneCompany.value.length != 9) {
            $(phoneCompany).addClass('inputRedFocus');
            $(phoneCompany_validation_length).removeClass("hidden");
            validate = 'true';
        }
    }
    var mobile_phoneCompany = document.getElementById("mobile_phoneCompany");
    var mobile_phoneCompany_validation = document.getElementById("mobile_phoneCompany_validation");
    var mobile_phoneCompany_validation_length= document.getElementById("mobile_phoneCompany_validation_length");
    if (mobile_phoneCompany.value === "") {
        $(mobile_phoneCompany).addClass('inputRedFocus');
        $(mobile_phoneCompany_validation).removeClass('hidden');
        validate = 'true';
    } else {
        if (isNaN(mobile_phoneCompany.value)) {
            $(mobile_phoneCompany).addClass('inputRedFocus');
            validate = 'true';
        }
        if (mobile_phoneCompany.value.length != 10) {
            $(mobile_phoneCompany).addClass('inputRedFocus');
            $(mobile_phoneCompany_validation_length).removeClass("hidden");
            validate = 'true';
        }
    }
    var emailCompany = document.getElementById("emailCompany");
    var emailValidate = ValidateEmail(emailCompany.value);
    var emailCompany_validation = document.getElementById("emailCompany_validation");
    var emailCompany_error = document.getElementById("emailCompany_error");
//        console.log(emailValidate);
    if (emailCompany.value === ""  || emailCompany.value.length > 100) {
        $(emailCompany).addClass('inputRedFocus');
        $(emailCompany_validation).removeClass('hidden');
        validate = 'true';
    } else if (emailValidate === false) {
        $(emailCompany).addClass('inputRedFocus');
        $(emailCompany_error).removeClass('hidden');
        validate = 'true';

    }
    //Legal Representative Validation
   
    var documentNumber = document.getElementById("document");
    var document_validation=document.getElementById("document_validation");
    if (documentNumber.value === "") {
        $(documentNumber).addClass('inputRedFocus');
        $(document_validation).removeClass('hidden');
        validate = 'true';
    }
    var first_name = document.getElementById("first_name");
    var first_name_validation = document.getElementById("first_name_validation");
    if (first_name.value === "" || first_name.value.length > 30) {
        $(first_name).addClass('inputRedFocus');
        $(first_name_validation).removeClass('hidden');
        validate = 'true';
    }
    
    var document_id = document.getElementById("document_id");
    var document_id_validation= document.getElementById("document_id_validation");
    if (document_id.value === "0") {
        $(document_id).addClass('inputRedFocus');
        $(document_id_validation).removeClass('hidden');
        validate = 'true';
    } else if (document_id.value === "1") {
        if (isNaN(documentNumber.value)) {
            $(documentNumber).addClass('inputRedFocus');
            $(document_id).addClass('inputRedFocus');
            validate = 'true';
        }
    }
    var last_name = document.getElementById("last_name");
    var last_name_validation = document.getElementById("last_name_validation");
    if (last_name.value === "" || last_name.value.length > 30) {
        $(last_name).addClass('inputRedFocus');
        $(last_name_validation).removeClass('hidden');
        validate = 'true';
    }
    var second_last_name = document.getElementById("second_last_name");
    var second_last_name_validation=document.getElementById("second_last_name_validation");
    if (second_last_name.value === "" || second_last_name.value.length > 30) {
        $(second_last_name).addClass('inputRedFocus');
        $(second_last_name_validation).removeClass("hidden");
        validate = 'true';
    }
    var mobile_phone = document.getElementById("mobile_phone");
    var mobile_phone_validation = document.getElementById("mobile_phone_validation");
    var mobile_phone_validation_length= document.getElementById("mobile_phone_validation_length");
    if (mobile_phone.value === "") {
        $(mobile_phone).addClass('inputRedFocus');
        $(mobile_phone_validation).removeClass('hidden');
        validate = 'true';
    } else {
        if (isNaN(mobile_phone.value)) {
            $(mobile_phone).addClass('inputRedFocus');
            validate = 'true';
        }
        if (mobile_phone.value.length != 10) {
            $(mobile_phone).addClass('inputRedFocus');
            $(mobile_phone_validation_length).removeClass("hidden");
            validate = 'true';
        }
    }

    var email = document.getElementById("email");
    var emailValidate = ValidateEmail(email.value);
    var email_validation = document.getElementById("email_validation");
    var email_error = document.getElementById("email_error");
    if (email.value === ""  || email.value.length > 100) {
        $(email).addClass('inputRedFocus');
        $(email_validation).removeClass('hidden');
        validate = 'true';
    } else if (emailValidate === false) {
        $(email).addClass('inputRedFocus');
        $(email_error).removeClass('hidden');
        validate = 'true';

    }
    var nationality= document.getElementById("nationality");
    var nationality_validation= document.getElementById("nationality_validation");
    if (nationality.value === "0") {
        $(nationality).addClass('inputRedFocus');
        $(nationality_validation).removeClass('hidden');
        validate = 'true';
    } 
    var birthCountry= document.getElementById("birthCountry");
    var birthCountry_validation= document.getElementById("birthCountry_validation");
    if (birthCountry.value === "0") {
        $(birthCountry).addClass('inputRedFocus');
        $(birthCountry_validation).removeClass('hidden');
        validate = 'true';
    } 
    var birthProvince= document.getElementById("birthProvince");
    var birthProvince_validation= document.getElementById("birthProvince_validation");
    if (birthProvince.value === "0") {
        $(birthProvince).addClass('inputRedFocus');
        $(birthProvince_validation).removeClass('hidden');
        validate = 'true';
    } 
    var birthCity= document.getElementById("birthCity");
    var birthCity_validation= document.getElementById("birthCity_validation");
    if (birthCity.value === "0") {
        $(birthCity).addClass('inputRedFocus');
        $(birthCity_validation).removeClass('hidden');
        validate = 'true';
    } 
    var birth_date = document.getElementById("birthdate");
    var birth_date_validation=document.getElementById("birth_date_validation");
    if (birth_date.value === "") {
        $(birth_date).addClass('inputRedFocus');
        $(birth_date_validation).removeClass('hidden');
        validate = 'true';
    }
    var civilState= document.getElementById("civilState");
    var civilState_validation= document.getElementById("civilState_validation");
    if (civilState.value === "0") {
        $(civilState).addClass('inputRedFocus');
        $(civilState_validation).removeClass('hidden');
        validate = 'true';
    } 
    var address= document.getElementById("address");
    var address_validation=document.getElementById("address_validation");
    var address_validation_length =document.getElementById("address_validation_length");
    if (address.value === "") {
        $(address).addClass('inputRedFocus');
        $(address_validation).removeClass("hidden");
        validate = 'true';
    }else if(address.value.length > 30){
        $(address).addClass('inputRedFocus');
        $(address_validation_length).removeClass("hidden");
        validate = 'true';
    }
    var country= document.getElementById("country");
    var country_validation= document.getElementById("country_validation");
    if (country.value === "0") {
        $(country).addClass('inputRedFocus');
        $(country_validation).removeClass('hidden');
        validate = 'true';
    } 
    var province= document.getElementById("province");
    var province_validation= document.getElementById("province_validation");
    if (province.value === "0") {
        $(province).addClass('inputRedFocus');
        $(province_validation).removeClass('hidden');
        validate = 'true';
    } 
    var city= document.getElementById("city");
    var city_validation= document.getElementById("city_validation");
    if (city.value === "0") {
        $(city).addClass('inputRedFocus');
        $(city_validation).removeClass('hidden');
        validate = 'true';
    } 

    var sector= document.getElementById("sector");
    var sector_validation=document.getElementById("sector_validation");
    if (sector.value === "" || sector.value.length > 30) {
        $(sector).addClass('inputRedFocus');
        $(sector_validation).removeClass("hidden");
        validate = 'true';
    }
    var phone = document.getElementById("phone");
    var phone_validation = document.getElementById("phone_validation");
    var phone_validation_length= document.getElementById("phone_validation_length");
    if (phone.value === "") {
        $(phone).addClass('inputRedFocus');
        $(phone_validation).removeClass('hidden');
        validate = 'true';
    } else {
        if (isNaN(phone.value)) {
            $(phone).addClass('inputRedFocus');
            validate = 'true';
        }
        if (phone.value.length != 9) {
            $(phone).addClass('inputRedFocus');
            $(phone_validation_length).removeClass("hidden");
            validate = 'true';
        }
    }
     //Spouse Validation
     var spouseDocument = document.getElementById("spouseDocument");
     var spouseDocument_validation = document.getElementById("spouseDocument_validation");
     var spouse_document_id = document.getElementById("spouse_document_id");
     var spouse_document_id_validation= document.getElementById("spouse_document_id_validation");
     var spouseFirstName = document.getElementById("spouseFirstName");
     var spouseFirstName_validation = document.getElementById("spouseFirstName_validation");
     var spouseFirstName_validation_length = document.getElementById("spouseFirstName_validation_length");
     var spouseLastName = document.getElementById("spouseLastName");
     var spouseLastName_validation= document.getElementById("spouseLastName_validation");
     var spouseLastName_validation_length= document.getElementById("spouseLastName_validation_length");
     if (civilState.value == '2' || civilState.value=='5') {
         if (spouseDocument.value === '') { 
             $(spouseDocument).addClass('inputRedFocus'); 
             $(spouseDocument_validation).removeClass('hidden'); 
             validate = true; }
         if (spouse_document_id.value === ''){ 
             $(spouse_document_id).addClass('inputRedFocus');
             $(spouse_document_id_validation).removeClass('hidden'); 
             validate = true; }
         if (spouseFirstName.value === '') {
              $(spouseFirstName).addClass('inputRedFocus'); 
              $(spouseFirstName_validation).removeClass('hidden'); 
              validate = true; 
            } else if (spouseFirstName.value.length > 60) {
                 $(spouseFirstName).addClass('inputRedFocus');
                 $(spouseFirstName_validation_length).removeClass('hidden'); 
                  validate = true; }
         if (spouseLastName.value === '') {
              $(spouseLastName).addClass('inputRedFocus');
              $(spouseLastName_validation).removeClass('hidden'); 
               validate = true; 
            } else if (spouseLastName.value.length > 20) { 
                $(spouseLastName).addClass('inputRedFocus'); 
                $(spouseLastName_validation_length).removeClass('hidden'); 
                validate = true; }
     } else {
         spouseDocument.value = '';
         spouse_document_id.value = '';
         spouseFirstName.value = '';
         spouseLastName.value = '';
     }
         
    //Benefitiary Validation

    var is_beneficiary = document.getElementById("is_beneficiary");
    if (is_beneficiary.checked === false) {
        var beneficiaryName = document.getElementById("beneficiaryName"); 
        var beneficiaryName_validation = document.getElementById("beneficiaryName_validation"); 
        var beneficiaryName_validation_length= document.getElementById("beneficiaryName_validation_length"); 
        if (beneficiaryName.value === '') {
         $(beneficiaryName).addClass('inputRedFocus');
         $(beneficiaryName_validation).removeClass('hidden ');
         validate = true; 
       } else if(beneficiaryName.value.length > 200){ 
           $(beneficiaryName).addClass('inputRedFocus'); 
           $(beneficiaryName_validation_length).removeClass('hidden ');
           validate = true; 
       } else{ $(beneficiaryName).removeClass('inputRedFocus'); }
        var beneficiary_document_id = document.getElementById("beneficiary_document_id");
        var beneficiary_document_id_validation = document.getElementById("beneficiary_document_id_validation");
         if(beneficiary_document_id.value === '0'){ 
             $(beneficiary_document_id).addClass('inputRedFocus'); 
             $(beneficiary_document_id_validation).removeClass('hidden'); 
             validate = true;
             }else{
                  $(beneficiary_document_id).removeClass('inputRedFocus'); }
        var beneficiary_document = document.getElementById("beneficiary_document");
        var beneficiary_document_validation = document.getElementById("beneficiary_document_validation");
         if(beneficiary_document.value === ''){
              $(beneficiary_document).addClass('inputRedFocus');
              $(beneficiary_document_validation).removeClass('hidden');
              validate = true;
            }else{ $(beneficiary_document).removeClass('inputRedFocus'); }
        var beneficiary_nationality = document.getElementById("beneficiary_nationality");
        var beneficiary_nationality_validation = document.getElementById("beneficiary_nationality_validation");
         if(beneficiary_nationality.value === ''){ 
             $(beneficiary_nationality).addClass('inputRedFocus'); 
             $(beneficiary_nationality_validation ).removeClass('hidden'); 
             validate = true;
             }else{
                  $(beneficiary_nationality).removeClass('inputRedFocus'); }
        var beneficiary_address = document.getElementById("beneficiary_address"); 
        var beneficiary_address_validation= document.getElementById("beneficiary_address_validation"); 
        var beneficiary_address_validation_length= document.getElementById("beneficiary_address_validation_length"); 
        if (beneficiary_address.value === '') {
             $(beneficiary_address).addClass('inputRedFocus');
             $(beneficiary_address_validation).removeClass('hidden');
             validate = true; 
            } else if(beneficiary_address.value.length > 200){ 
                $(beneficiary_address).addClass('inputRedFocus');
                $(beneficiary_address_validation_length).removeClass('hidden');
                 validate = true;
             }else{ $(beneficiary_address).removeClass('inputRedFocus'); }
        var beneficiary_phone = document.getElementById("beneficiary_phone");
        var beneficiary_phone_validation= document.getElementById("beneficiary_phone_validation");
        var beneficiary_phone_validation_length= document.getElementById("beneficiary_phone_validation_length"); 
        if (beneficiary_phone.value === '') {
              $(beneficiary_phone).addClass('inputRedFocus'); 
              $(beneficiary_phone_validation).removeClass('hidden');
              validate = true;
             } else if(beneficiary_phone.value.length > 10){ 
                 $(beneficiary_phone).addClass('inputRedFocus');
                 $(beneficiary_phone_validation_length).removeClass('hidden');
                 validate = true;
                 }else{ $(beneficiary_phone).removeClass('inputRedFocus'); }
        var beneficiary_relationship = document.getElementById("beneficiary_relationship");
        var beneficiary_relationship_validation = document.getElementById("beneficiary_relationship_validation");
        var beneficiary_relationship_validation_length = document.getElementById("beneficiary_relationship_validation_length");
         if (beneficiary_relationship.value === '') {
             $(beneficiary_relationship).addClass('inputRedFocus');validate = true;
             $(beneficiary_relationship_validation).removeClass('hidden');
             } else if(beneficiary_relationship.value.legth > 20){
                  $(beneficiary_relationship).addClass('inputRedFocus'); validate = true;
                  $(beneficiary_relationship_validation_length).removeClass('hidden');
                 }else{ $(beneficiary_relationship).removeClass('inputRedFocus'); }
    } else {
        var beneficiaryName = document.getElementById("beneficiaryName"); beneficiaryName.value = ""; beneficiaryName = null;
        var beneficiary_document_id = document.getElementById("beneficiary_document_id");  beneficiary_document_id.value = ""; beneficiary_document_id = null;
        var beneficiary_document = document.getElementById("beneficiary_document"); beneficiary_document.value = ""; beneficiary_document = null;
        var beneficiary_nationality = document.getElementById("beneficiary_nationality"); beneficiary_nationality.value = ""; beneficiary_nationality = null;
        var beneficiary_phone = document.getElementById("beneficiary_phone"); beneficiary_phone.value = ""; beneficiary_phone = null;
        var beneficiary_relationship = document.getElementById("beneficiary_relationship"); beneficiary_relationship.value = ""; beneficiary_relationship = null;
        var beneficiary_address = document.getElementById("beneficiary_address"); beneficiary_address.value = ""; beneficiary_address = null;
    }
    if (validate === 'false') {
        //Store Data
        var form = document.getElementById('firstStepForm');
        var url = ROUTE + "/legalPersonVinculation/firstStepForm";
        $('#occupation').prop('disabled', false);
        $('#document_id').prop('disabled', false);
        $('#first_name').prop('disabled', false);
        $('#last_name').prop('disabled', false);
        $('#second_name').prop('disabled', false);
        $('#second_last_name').prop('disabled', false);
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
                nextStep('firstStep', 'secondStep');
            },
            complete: function () {
                $('#occupation').prop('disabled', true);
                $('#document_id').prop('disabled', true);
                $('#first_name').prop('disabled', true);
                $('#last_name').prop('disabled', true);
                $('#second_name').prop('disabled', true);
                $('#second_last_name').prop('disabled', true);
            }
        });
    }
    
  };
  function validateSecondStep() {
    event.preventDefault();
    var validate='false';
    var annual_income = document.getElementById("annual_income");
    var annual_income_validation = document.getElementById("annual_income_validation");
    if (annual_income.value === ''||annual_income.value === '0.00') {
        $(annual_income).addClass('inputRedFocus');
        $(annual_income_validation).removeClass('hidden');
        validate = 'true';
    }
    if(validate=='false'){
        var form = document.getElementById('secondStepForm');
        var url = ROUTE + "/legalPersonVinculation/secondStepForm";
        $('#economic_activity').prop('disabled', false);
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
 };
 function validateThirdStep(){
    event.preventDefault();
    var errorFound = false;
    var optRadio3 = null;
    
    var radios = document.getElementsByName('optradio3'); for (var i = 0, length = radios.length; i < length; i++) { if (radios[i].checked) { var optRadio3 = radios[i].value; break; } }
    var pep_client = document.getElementById("pep_client"); if(optRadio3 == 'yes'){ if(pep_client.value == ''){ $(pep_client).addClass('inputRedFocus'); errorFound = true; } }
    
    if(errorFound == false){
        //Store Data
        var form = document.getElementById('thirdStepForm');
        var url = ROUTE + "/legalPersonVinculation/thirdStepForm";
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
    var uploadedFileDocumentApplicant = document.getElementById("uploadedFileDocumentApplicant"); 
    if (uploadedFileDocumentApplicant.value == '') 
    { 
        var messageDocumentApplicant = document.getElementById("messageDocumentApplicant"); 
        $(messageDocumentApplicant).css('display', 'block'); 
        $(messageDocumentApplicant).html('Debe ingresar una imagen'); 
        $(messageDocumentApplicant).addClass('alert alert-danger'); 
        var errorFound = true; 
    }

    var DocumentApplicantDate = document.getElementById("DocumentApplicantDate"); 
    if(DocumentApplicantDate.value == ''){ 
        $(DocumentApplicantDate).addClass('inputRedFocus'); 
        var errorFound = true; 
    }else{
        var res = DocumentApplicantDate.value.split("-");
        if(res[0].length !== 4){
            $(DocumentApplicantDate).addClass('inputRedFocus'); 
            var errorFound = true; 
        }
    }
        
    var civilState = document.getElementById("civilState");
    var DocumentSpouseDate = document.getElementById("DocumentSpouseDate"); 
    if(DocumentSpouseDate.value == '' && (civilState.value == 2 || civilState.value == 5))
    { 
        $(DocumentSpouseDate).addClass('inputRedFocus'); var errorFound = true; 
    }else{
        if(civilState.value == 2 || civilState.value == 5){
            var res = DocumentSpouseDate.value.split("-"); 
            if(res[0].length !== 4){
                $(DocumentSpouseDate).addClass('inputRedFocus'); 
                var errorFound = true; 
            }
        }
    }
    
    var uploadedFileDocumentSpouse = document.getElementById("uploadedFileDocumentSpouse"); 
    if (uploadedFileDocumentSpouse.value == '' && (civilState.value == 2 || civilState.value == 5)) 
    { 
        var messageDocumentSpouse = document.getElementById("messageDocumentSpouse"); 
        $(messageDocumentSpouse).css('display', 'block'); 
        $(messageDocumentSpouse).html('Debe ingresar una imagen'); 
        $(messageDocumentSpouse).addClass('alert alert-danger'); var errorFound = true; 
    } 

    var uploadedFileService = document.getElementById("uploadedFileService"); 
    if (uploadedFileService.value == '') { 
        var messageService = document.getElementById("messageService"); 
        $(messageService).css('display', 'block');
        $(messageService).html('Debe ingresar una imagen'); 
        $(messageService).addClass('alert alert-danger'); 
        var errorFound = true; 
    } 

    var uploadedFileService = document.getElementById("uploadedFileService"); 
    if (uploadedFileService.value == '') { 
        var messageService = document.getElementById("messageService"); 
        $(messageService).css('display', 'block');
        $(messageService).html('Debe ingresar una imagen'); 
        $(messageService).addClass('alert alert-danger'); 
        var errorFound = true; 
    } 
    var uploadedFileDocumentRuc = document.getElementById("uploadedFileDocumentRuc"); 
    if (uploadedFileDocumentRuc.value == '') { 
        var messageDocumentRuc = document.getElementById("messageDocumentRuc"); 
        $(messageDocumentRuc).css('display', 'block');
        $(messageDocumentRuc).html('Debe ingresar una imagen'); 
        $(messageDocumentRuc).addClass('alert alert-danger'); 
        var errorFound = true; 
    } 
    var uploadedFileConstitutionDeed = document.getElementById("uploadedFileConstitutionDeed"); 
    if (uploadedFileConstitutionDeed.value == '') { 
        var messageConstitutionDeed = document.getElementById("messageConstitutionDeed"); 
        $(messageConstitutionDeed).css('display', 'block');
        $(messageConstitutionDeed).html('Debe ingresar una imagen'); 
        $(messageConstitutionDeed).addClass('alert alert-danger'); 
        var errorFound = true; 
    } 
    var uploadedFileCertificateAppointment = document.getElementById("uploadedFileCertificateAppointment"); 
    if (uploadedFileCertificateAppointment.value == '') { 
        var messageCertificateAppointment = document.getElementById("messageCertificateAppointment"); 
        $(messageCertificateAppointment).css('display', 'block');
        $(messageCertificateAppointment).html('Debe ingresar una imagen'); 
        $(messageCertificateAppointment).addClass('alert alert-danger'); 
        var errorFound = true; 
    } 
    var uploadedFileShareholdersPayroll = document.getElementById("uploadedFileShareholdersPayroll"); 
    if (uploadedFileShareholdersPayroll.value == '') { 
        var messageShareholdersPayroll = document.getElementById("messageShareholdersPayroll"); 
        $(messageShareholdersPayroll).css('display', 'block');
        $(messageShareholdersPayroll).html('Debe ingresar una imagen'); 
        $(messageShareholdersPayroll).addClass('alert alert-danger'); 
        var errorFound = true; 
    } 
    var uploadedFileCertificateObligations = document.getElementById("uploadedFileCertificateObligations"); 
    if (uploadedFileCertificateObligations.value == '') { 
        var messageCertificateObligations = document.getElementById("messageCertificateObligations"); 
        $(messageCertificateObligations).css('display', 'block');
        $(messageCertificateObligations).html('Debe ingresar una imagen'); 
        $(messageCertificateObligations).addClass('alert alert-danger'); 
        var errorFound = true; 
    } 
    var uploadedFileFinancialState= document.getElementById("uploadedFileFinancialState"); 
    if (uploadedFileFinancialState.value == '') { 
        var messageFinancialState = document.getElementById("messageFinancialState"); 
        $(messageFinancialState).css('display', 'block');
        $(messageFinancialState).html('Debe ingresar una imagen'); 
        $(messageFinancialState).addClass('alert alert-danger'); 
        var errorFound = true; 
    } 
    
    var uploadedFileSri = document.getElementById("uploadedFileSri"); 
    if (uploadedFileSri.value == '' && vinFormVersion.value == 4) 
    { 
        var messageSri = document.getElementById("messageSri"); 
        $(messageSri).css('display', 'block'); 
        $(messageSri).html('Debe ingresar una imagen'); 
        $(messageSri).addClass('alert alert-danger'); var errorFound = true; 
    } 
    //Validate Terms and Conditions
    if (document.getElementById('termChk').checked) 
    {
        termChkAlert = document.getElementById('termChkAlert')
        $(termChkAlert).addClass('hidden'); 
    } else {
        termChkAlert = document.getElementById('termChkAlert')
        termChkAlert.classList.remove("hidden");
        errorFound = true;
    }
    
    if(errorFound == false){
        //Send Mail to User
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var saleId = document.getElementById("saleId").value;
        var legalRepresentativeId = document.getElementById("documentId").value;
        var companyId=document.getElementById("companyId").value;
        var url = ROUTE + '/legalPersonVinculation/token/generate';
        $.ajax({
            type: "POST",
            data: {_token: CSRF_TOKEN, saleId: saleId, legalRepresentativeId : legalRepresentativeId ,companyId:companyId, documentApplicantDate: DocumentApplicantDate.value, documentSpouseDate: DocumentSpouseDate.value},
            url: url,
            async: false,
            beforeSend: function () {
                // Show Loader
                $("#loaderGif").addClass('loaderGif');
            },
            success: function (data) {
                                
                window.location.href = data['url'];
//              nextStep('fourthStep','fifthStep');
            },
            complete: function () {
                //Hide Loader
                var loaderGif = document.getElementById("loaderGif");
                loaderGif.classList.remove("loaderGif");
            }
        });
    }
    
}
  function validateInput(){   
    var documentNumber = document.getElementById("document");
    var document_validation=document.getElementById("document_validation");
        $(documentNumber).removeClass('inputRedFocus');
        $(document_validation).addClass('hidden');

    var first_name = document.getElementById("first_name");
    var first_name_validation = document.getElementById("first_name_validation");
        $(first_name).removeClass('inputRedFocus');
        $(first_name_validation).addClass('hidden');

    var second_name_validation = document.getElementById("second_name_validation");
        $(second_name_validation).addClass('hidden');
     
    var document_id = document.getElementById("document_id");
    var document_id_validation= document.getElementById("document_id_validation");
        $(document_id).removeClass('inputRedFocus');
        $(document_id_validation).addClass('hidden');
     
    var last_name = document.getElementById("last_name");
    var last_name_validation = document.getElementById("last_name_validation");
        $(last_name).removeClass('inputRedFocus');
        $(last_name_validation).addClass('hidden');

    var second_last_name = document.getElementById("second_last_name");
    var second_last_name_validation=document.getElementById("second_last_name_validation");
        $(second_last_name).removeClass('inputRedFocus');
        $(second_last_name_validation).addClass("hidden");

    var mobile_phone = document.getElementById("mobile_phone");
    var mobile_phone_validation = document.getElementById("mobile_phone_validation");
    var mobile_phone_validation_length= document.getElementById("mobile_phone_validation_length");
        $(mobile_phone).removeClass('inputRedFocus');
        $(mobile_phone_validation).addClass('hidden');
            $(mobile_phone_validation_length).addClass("hidden");

    var email = document.getElementById("email");
    var email_validation = document.getElementById("email_validation");
    var email_error = document.getElementById("email_error");
        $(email).removeClass('inputRedFocus');
        $(email_validation).addClass('hidden');
        $(email_error).addClass('hidden')

    var nationality= document.getElementById("nationality");
    var nationality_validation= document.getElementById("nationality_validation");
        $(nationality).removeClass('inputRedFocus');
        $(nationality_validation).addClass('hidden');

    var birthCountry= document.getElementById("birthCountry");
    var birthCountry_validation= document.getElementById("birthCountry_validation");
        $(birthCountry).removeClass('inputRedFocus');
        $(birthCountry_validation).addClass('hidden');
         
    var birthProvince= document.getElementById("birthProvince");
    var birthProvince_validation= document.getElementById("birthProvince_validation");
        $(birthProvince).removeClass('inputRedFocus');
        $(birthProvince_validation).addClass('hidden');
        validate = 'true';

    var birthCity= document.getElementById("birthCity");
    var birthCity_validation= document.getElementById("birthCity_validation");
        $(birthCity).removeClass('inputRedFocus');
        $(birthCity_validation).addClass('hidden');
        
    var birth_date = document.getElementById("birthdate");
    var birth_date_validation=document.getElementById("birth_date_validation");
        $(birth_date).removeClass('inputRedFocus');
        $(birth_date_validation).addClass('hidden');

    var civilState= document.getElementById("civilState");
    var civilState_validation= document.getElementById("civilState_validation");
        $(civilState).removeClass('inputRedFocus');
        $(civilState_validation).addClass('hidden');
        
    var address= document.getElementById("address");
    var address_validation=document.getElementById("address_validation");
    var address_validation_length=document.getElementById("address_validation_length");
        $(address).removeClass('inputRedFocus');
        $(address_validation).addClass("hidden");
        $(address_validation_length).addClass("hidden");

    var country= document.getElementById("country");
    var country_validation= document.getElementById("country_validation");
        $(country).removeClass('inputRedFocus');
        $(country_validation).addClass('hidden');

    var province= document.getElementById("province");
    var province_validation= document.getElementById("province_validation");
        $(province).removeClass('inputRedFocus');
        $(province_validation).addClass('hidden');

    var city= document.getElementById("city");
    var city_validation= document.getElementById("city_validation");
        $(city).removeClass('inputRedFocus');
        $(city_validation).addClass('hidden');

    var parroquia= document.getElementById("parroquia");
    var parroquia_validation=document.getElementById("parroquia_validation");
        $(parroquia).removeClass('inputRedFocus');
        $(parroquia_validation).addClass("hidden");

    var sector= document.getElementById("sector");
    var sector_validation=document.getElementById("sector_validation");
        $(sector).removeClass('inputRedFocus');
        $(sector_validation).addClass("hidden");

    var phone = document.getElementById("phone");
    var phone_validation = document.getElementById("phone_validation");
    var phone_validation_length= document.getElementById("phone_validation_length");
        $(phone).removeClass('inputRedFocus');
        $(phone_validation).addClass('hidden');
        $(phone_validation_length).addClass("hidden");
    
};
function emailIsValid(email) {
    return /\S+@\S+\.\S+/.test(email);
}
function validateDocument() {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var data = {document:document.getElementById("document").value};
    var url = ROUTE + '/legalPersonVinculation/validateDocument';
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
function selectDocument(value) {
    $("#document").val(value);
    $("#suggesstion-box").hide();

    formAutoFill(value);
}
function formAutoFill(val) {
    var documentNumber = val;
    var url = ROUTE + '/legalPersonVinculation/document/autofill/' + documentNumber;
    if (documentNumber) {
        $.ajax({
            url: url,
            type: "GET",
            dataType: "json",
            beforeSend: function () {
//              Show Loader
                $("#loaderGif").addClass('loaderGif');
//              $("#loaderBody").addClass('loaderBody');
            },
            success: function (data) {
                if (data.success == 'true') {
                    document.getElementById("first_name").disabled = true;
                    document.getElementById("last_name").disabled = true;
                    document.getElementById("document_id").disabled = true;
                    if(data.second_name === null){ document.getElementById("second_name").disabled = false; }else{ document.getElementById("second_name").disabled = true;  }
                    if(data.second_last_name === null){ document.getElementById("second_last_name").disabled = false; }else{ document.getElementById("second_last_name").disabled = true;  }
                    $("#legalRepresentativeDiv").autofill(data);
                    //console.log(data);
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
            error: function () {
                document.getElementById("first_name").disabled = false;
                document.getElementById("last_name").disabled = false;
                document.getElementById("document_id").disabled = false;
                document.getElementById("second_name").disabled = false;
                document.getElementById("second_last_name").disabled = false;
            },
            complete: function () {
                //Hide Loader
                var loaderGif = document.getElementById("loaderGif");
                loaderGif.classList.remove("loaderGif");
            }
        });
    } else {
        $('select[name="province"]').empty();
    }
}
function documentButton() {
    validateDocument();
    validateInput();
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
function isBeneficiaryChange(obj){
    fadeToggle('beneficiaryDataDiv');
}

function uploadPictureForm(id) {
    event.preventDefault();
    var fi = document.getElementById('select_file'+id); if (fi.files.length > 0) { for (var i = 0; i <= fi.files.length - 1; i++) { var fsize = fi.files.item(i).size; var file = Math.round((fsize / 1024)); if (file >= 2096) { alert("El archivo debe pesar menos de 2mb."); return false; } } } 
    var form = document.getElementById("upload_form"+id);
    var url = ROUTE + "/legalPersonVinculation/upload";
    $.ajax({
        url: url,
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
        },
        complete: function () {
            //Hide Loader
            var loaderGif = document.getElementById("loaderGif");
            loaderGif.classList.remove("loaderGif");
        }
    });
}

function deletePictureForm(id, representativeLegal, company,sale) {
    event.preventDefault();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = ROUTE + '/legalPersonVinculation/delete';
    var data = {id: id, representativeLegal: representativeLegal,company:company, sale:sale};
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

function economicActivitySearch(){
    var url = ROUTE + '/legalPersonVinculation/validateEconomicActivity';
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var searchEconomicActivity = document.getElementById("searchEconomicActivity").value;
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, searchEconomicActivity:searchEconomicActivity},
        url: url,
        async: false,
        beforeSend: function () {
            // Show Loader
            $("#loaderGif").addClass('loaderGif');
        },
        success: function (data) {
            document.getElementById("economic_activity_search").innerHTML = data; 
        },
        complete: function () {
            //Hide Loader
            var loaderGif = document.getElementById("loaderGif");
            loaderGif.classList.remove("loaderGif");
        }
    });
}

function selectEconomicActivity(){   
    document.getElementById("occupation").value = document.getElementById("economic_activity_search").value;
    document.getElementById("closeModal").click();
    var occupation= document.getElementById("occupation");
    var occupation_validation= document.getElementById("occupation_validation");
        $(occupation).removeClass('inputRedFocus');
        $(occupation_validation).addClass('hidden');
}
function isBeneficiaryChange(obj){
    fadeToggle('beneficiaryDataDiv');
}
function validateDocumentSpouse() {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var spouseDocument = document.getElementById("spouseDocument");
    var spouse_document_id = document.getElementById("spouse_document_id");
    var data = {document: spouseDocument.value};
    if(spouse_document_id.value === '1'){ //CEDULA
        var url = ROUTE + '/legalPersonVinculation/validateDocument';
        $.ajax({
            type: "POST",
            data: {_token: CSRF_TOKEN, data},
            url: url,
            async: false,
            success: function (data) {
                if (data === 'invalid') {
                    document.getElementById("spouse_document_id").value = "";
                    alert('La cédula ingresada es invalida');
                }
            },
            error: function () {
                return "Hello";
            }
        });
    }
    if(spouse_document_id.value === '2'){ //RUC
        if(isNaN(spouseDocument.value) || spouseDocument.value.length != 13){
            document.getElementById("spouse_document_id").value = "";
            alert('El RUC ingresado es invalido');
        }
    }
}

function validateDocumentBeneficiary() {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var beneficiary_document = document.getElementById("beneficiary_document");
    var beneficiary_document_id = document.getElementById("beneficiary_document_id");
    var data = {document: beneficiary_document.value};
    if(beneficiary_document_id.value === '1'){ //CEDULA
        var url = ROUTE + '/legalPersonVinculation/validateDocument';
        $.ajax({
            type: "POST",
            data: {_token: CSRF_TOKEN, data},
            url: url,
            async: false,
            success: function (data) {
                if (data === 'invalid') {
                    document.getElementById("beneficiary_document_id").value = "";
                    alert('La cédula ingresada es invalida');
                }
            },
            error: function () {
                return "Hello";
            }
        });
    }
    if(beneficiary_document_id.value === '2'){ //RUC
        if(isNaN(beneficiary_document.value) || beneficiary_document.value.length != 13){
            document.getElementById("beneficiary_document_id").value = "";
            alert('El RUC ingresado es invalido');
        }
    }
}
