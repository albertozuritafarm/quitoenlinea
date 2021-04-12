/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {
    fadeOut('personalDiv');
    fadeOut('spouseDiv');
    fadeOut('beneficiaryDiv');
//    fadeOut('beneficiaryDataDiv');
    fadeOut('passportDiv');
    fadeOut('occupationDiv');
    fadeOut('financingDiv');
    fadeOut('financindSecondDiv');
    fadeOut('financindThirdDiv');
    fadeOut('workDataDiv');
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
        if (civilState.value == 2 || civilState.value == '5') {
            fadeIn('spouseFullDiv'); fadeIn('formDocumentSpouse');
        } else {
            fadeOut('spouseFullDiv'); fadeOut('formDocumentSpouse');
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

    setInputFilter(document.getElementById("other_annual_income"), function(value) {
//        return /^\d*\.?\d*$/.test(value); 
        return /^-?\d*[.]?\d{0,2}$/.test(value); 
    });

    setInputFilter(document.getElementById("total_assets"), function(value) {
//        return /^\d*\.?\d*$/.test(value); 
        return /^-?\d*[.]?\d{0,2}$/.test(value); 
    });

    setInputFilter(document.getElementById("total_passives"), function(value) {
//        return /^\d*\.?\d*$/.test(value); 
        return /^-?\d*[.]?\d{0,2}$/.test(value); 
    });
    
    setInputFilter(document.getElementById("commercial_reference_amount"), function(value) {
//        return /^\d*\.?\d*$/.test(value); 
        return /^-?\d*[.]?\d{0,2}$/.test(value); 
    });
    
    setInputFilter(document.getElementById("beneficiary_phone"), function(value) {
        return /^\d*\.?\d*$/.test(value); 
    });
    
    $("#annual_income, #other_annual_income").change(function () {
        var total_annual_income = document.getElementById("total_annual_income");
        var annual_income = document.getElementById("annual_income");
        var other_annual_income = document.getElementById("other_annual_income");
        var annualIncome = annual_income.value.replace(/,/g, ''); 
        var otherAnnualIncome = other_annual_income.value.replace(/,/g, ''); 
        total_annual_income.value = 0;
        if (annualIncome != '') {
            document.getElementById("annual_income").value = currency(annualIncome, { separator: ',' }).format();
            total_annual_income.value = currency(annualIncome, { separator: ',' }).format();
        }
        if (otherAnnualIncome != '') {
            document.getElementById("other_annual_income").value = currency(otherAnnualIncome, { separator: ',' }).format();
            total_annual_income.value = currency(otherAnnualIncome, { separator: ',' }).format();
        }
        if (annualIncome != '' && otherAnnualIncome != '') {
            total_annual_income.value = currency((currency(annualIncome).add(otherAnnualIncome)), { separator: ',' }).format();
        }
        $(total_annual_income ).removeClass('inputRedFocus');
    });

    $("#total_assets, #total_passives").change(function () {
        var total_assets_pasives = document.getElementById("total_assets_pasives");
        var total_assets = document.getElementById("total_assets");
        var total_passives = document.getElementById("total_passives");
        var totalAssets = total_assets.value.replace(/,/g, ''); 
        var totalPassives = total_passives.value.replace(/,/g, ''); 
        total_assets_pasives.value = 0;
        if (total_assets.value != '') { 
            document.getElementById("total_assets").value = currency(totalAssets, { separator: ',' }).format();
            total_assets_pasives.value = currency(totalAssets, { separator: ',' }).format();
        }
        if (total_passives.value != '') {
            document.getElementById("total_passives").value = currency(totalPassives, { separator: ',' }).format();
            total_assets_pasives.value = currency(totalPassives, { separator: ',' }).format();
        }
        if (total_passives.value != '' && total_assets.value != '') {
            total_assets_pasives.value = currency((currency(totalAssets).subtract(totalPassives)), { separator: ',' }).format(); } 
            $(total_assets_pasives).removeClass('inputRedFocus');
    });
    $("#commercial_reference_amount").change(function () {
        var commercial_reference_amount = document.getElementById("commercial_reference_amount").value;
        document.getElementById("commercial_reference_amount").value = currency(commercial_reference_amount, { separator: ',' }).format();
    });

    $("#spouse_document_id").change(function () {
        validateDocumentSpouse();
    });

    $("#beneficiary_document_id").change(function () {
        validateDocumentBeneficiary();
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
    
    //Validate Document Spouse
    $("#spouseDocument").keyup(function () {
        document.getElementById("spouse_document_id").value = '';
    });
});


function validateFirstStep() {
    event.preventDefault();
    var errorFound = false;

    //¿Customer Exists?
    var customerCheck = document.getElementById("customerCheck");
    
    //Main Data Validation
    var first_name = document.getElementById("first_name"); if (first_name.value === '') { $(first_name).addClass('inputRedFocus'); errorFound = true; }
    var last_name = document.getElementById("last_name"); if (last_name.value === '') { $(last_name).addClass('inputRedFocus'); errorFound = true; }
    var second_last_name = document.getElementById("second_last_name"); if (second_last_name.value === '') { $(last_name).addClass('inputRedFocus'); errorFound = true; }
    var document_id = document.getElementById("document_id"); if (document_id.value === '') { $(document_id).addClass('inputRedFocus'); errorFound = true; }
    var documentForm = document.getElementById("documentForm"); if (documentForm.value === '' && document_id.value !== 3) { $(documentForm).addClass('inputRedFocus'); errorFound = true; }
    var nationality = document.getElementById("nationality"); if (nationality.value === '') { $(nationality).addClass('inputRedFocus'); errorFound = true; }
    var birth_city = document.getElementById("birth_city"); if (birth_city.value === '') { $(birth_city).addClass('inputRedFocus'); errorFound = true; }
    var birth_date = document.getElementById("birth_date"); if (birth_date.value === '') { $(birth_date).addClass('inputRedFocus'); errorFound = true; }
    var civilState = document.getElementById("civilState"); if (civilState.value === '') { $(civilState).addClass('inputRedFocus');  errorFound = true;  }

    //Address Validation
    var country = document.getElementById("country"); if (country.value === '') { $(country).addClass('inputRedFocus'); errorFound = true; }
    var province = document.getElementById("province"); if (province.value === '') { $(province).addClass('inputRedFocus'); errorFound = true; }
    var city = document.getElementById("city"); if (city.value === '') { $(city).addClass('inputRedFocus'); errorFound = true; }
    var main_road = document.getElementById("main_road"); if (main_road.value === '') { $(main_road).addClass('inputRedFocus'); errorFound = true; } else if (main_road.value.length > 90) { $(main_road).addClass('inputRedFocus'); errorFound = true; }else{ $(main_road).removeClass('inputRedFocus'); }
    var secondary_road = document.getElementById("secondary_road"); if (secondary_road.value === '') { $(secondary_road).addClass('inputRedFocus'); errorFound = true; } else if (secondary_road.value.length > 50) { $(secondary_road).addClass('inputRedFocus'); errorFound = true; }else{ $(secondary_road).removeClass('inputRedFocus'); }
    var number = document.getElementById("number"); if (number.value === '') { $(number).addClass('inputRedFocus'); errorFound = true; } else if (number.value.length > 10) { $(number).addClass('inputRedFocus'); errorFound = true; }else{ $(number).removeClass('inputRedFocus'); }
    var sector = document.getElementById("sector"); if (sector.value === '') { $(sector).addClass('inputRedFocus'); errorFound = true; } else if (sector.value.length > 20) { $(sector).addClass('inputRedFocus'); errorFound = true; }else{ $(sector).removeClass('inputRedFocus'); }
    
    //Extra Data Validation
    var email = document.getElementById("email"); if (email.value === '') { $(email).addClass('inputRedFocus'); errorFound = true; }else{ var emailValidate = ValidateEmail(email.value); if(emailValidate === false){ $(email).addClass('inputRedFocus'); errorFound = true; } }
    var mobile_phone = document.getElementById("mobile_phone"); if (mobile_phone.value === '') { $(mobile_phone).addClass('inputRedFocus'); errorFound = true; }else if(isNaN(mobile_phone.value) || mobile_phone.value.length !== 10){ $(mobile_phone).addClass('inputRedFocus'); errorFound = true; }
    var phone = document.getElementById("phone"); if (phone.value === '') { $(phone).addClass('inputRedFocus'); errorFound = true; }else if(isNaN(phone.value) || phone.value.length !== 9){  $(phone).addClass('inputRedFocus');  errorFound = true; }
    
    //Passport Validation
    var passportNumber = document.getElementById("passportNumber");
    var passportBeginDate = document.getElementById("passportBeginDate");
    var passportEndDate = document.getElementById("passportEndDate");
    var migratoryState = document.getElementById("migratoryState");
    var passportEntryDate = document.getElementById("passportEntryDate");
    if (document_id.value === '3') {
        if (passportNumber.value === '') { $(passportNumber).addClass('inputRedFocus'); errorFound = true; }
        if (passportBeginDate.value === '') { $(passportBeginDate).addClass('inputRedFocus'); errorFound = true; }
        if (passportEndDate.value === '') { $(passportEndDate).addClass('inputRedFocus'); errorFound = true; }
        if (migratoryState.value === '') { $(migratoryState).addClass('inputRedFocus'); errorFound = true; }
        if (passportEntryDate.value === '') { $(passportEntryDate).addClass('inputRedFocus'); errorFound = true; }
    } else {
        $(passportNumber).removeClass('inputRedFocus');
        $(passportBeginDate).removeClass('inputRedFocus');
        $(passportEndDate).removeClass('inputRedFocus');
        $(migratoryState).removeClass('inputRedFocus');
        $(passportEntryDate).removeClass('inputRedFocus');
    }
    
    //Spouse Validation
    var spouseDocument = document.getElementById("spouseDocument");
    var spouse_document_id = document.getElementById("spouse_document_id");
    var spouseFirstName = document.getElementById("spouseFirstName");
    var spouseLastName = document.getElementById("spouseLastName");
    if (civilState.value == '2'||civilState.value == '5') {
        if (spouseDocument.value === '') { $(spouseDocument).addClass('inputRedFocus'); errorFound = true; }
        if (spouse_document_id.value === ''){ $(spouse_document_id).addClass('inputRedFocus'); errorFound = true; }
        if (spouseFirstName.value === '') { $(spouseFirstName).addClass('inputRedFocus'); errorFound = true; } else if (spouseFirstName.value.length > 60) { $(spouseFirstName).addClass('inputRedFocus'); errorFound = true; }
        if (spouseLastName.value === '') { $(spouseLastName).addClass('inputRedFocus'); errorFound = true; } else if (spouseLastName.value.length > 20) { $(spouseLastName).addClass('inputRedFocus'); errorFound = true; }
    } else {
        spouseDocument.value = '';
        spouse_document_id.value = '';
        spouseFirstName.value = '';
        spouseLastName.value = '';
    }
    
    //Benefitiary Validation

    var is_beneficiary = document.getElementById("is_beneficiary");
    if (is_beneficiary.checked === false) {
        var beneficiaryName = document.getElementById("beneficiaryName"); if (beneficiaryName.value === '') { $(beneficiaryName).addClass('inputRedFocus'); errorFound = true; } else if(beneficiaryName.value.length > 200){ $(beneficiaryName).addClass('inputRedFocus'); errorFound = true; } else{ $(beneficiaryName).removeClass('inputRedFocus'); }
        var beneficiary_document_id = document.getElementById("beneficiary_document_id"); if(beneficiary_document_id.value === ''){ $(beneficiary_document_id).addClass('inputRedFocus'); errorFound = true; }else{ $(beneficiary_document_id).removeClass('inputRedFocus'); }
        var beneficiary_document = document.getElementById("beneficiary_document"); if(beneficiary_document.value === ''){ $(beneficiary_document).addClass('inputRedFocus'); errorFound = true; }else{ $(beneficiary_document).removeClass('inputRedFocus'); }
        var beneficiary_nationality = document.getElementById("beneficiary_nationality"); if(beneficiary_nationality.value === ''){ $(beneficiary_nationality).addClass('inputRedFocus'); errorFound = true; }else{ $(beneficiary_nationality).removeClass('inputRedFocus'); }
        var beneficiary_address = document.getElementById("beneficiary_address"); if (beneficiary_address.value === '') { $(beneficiary_address).addClass('inputRedFocus'); errorFound = true; } else if(beneficiary_address.value.length > 200){ $(beneficiary_address).addClass('inputRedFocus'); errorFound = true; }else{ $(beneficiary_address).removeClass('inputRedFocus'); }
        var beneficiary_phone = document.getElementById("beneficiary_phone"); if (beneficiary_phone.value === '') { $(beneficiary_phone).addClass('inputRedFocus'); errorFound = true; } else if(beneficiary_phone.value.length > 10){ $(beneficiary_phone).addClass('inputRedFocus'); errorFound = true; }else{ $(beneficiary_phone).removeClass('inputRedFocus'); }
        var beneficiary_relationship = document.getElementById("beneficiary_relationship"); if (beneficiary_relationship.value === '') { $(beneficiary_relationship).addClass('inputRedFocus'); errorFound = true; } else if(beneficiary_relationship.value.legth > 20){ $(beneficiary_relationship).addClass('inputRedFocus'); errorFound = true; }else{ $(beneficiary_relationship).removeClass('inputRedFocus'); }
    } else {
        var beneficiaryName = document.getElementById("beneficiaryName"); beneficiaryName.value = ""; beneficiaryName = null;
        var beneficiary_document_id = document.getElementById("beneficiary_document_id");  beneficiary_document_id.value = ""; beneficiary_document_id = null;
        var beneficiary_document = document.getElementById("beneficiary_document"); beneficiary_document.value = ""; beneficiary_document = null;
        var beneficiary_nationality = document.getElementById("beneficiary_nationality"); beneficiary_nationality.value = ""; beneficiary_nationality = null;
        var beneficiary_phone = document.getElementById("beneficiary_phone"); beneficiary_phone.value = ""; beneficiary_phone = null;
        var beneficiary_relationship = document.getElementById("beneficiary_relationship"); beneficiary_relationship.value = ""; beneficiary_relationship = null;
        var beneficiary_address = document.getElementById("beneficiary_address"); beneficiary_address.value = ""; beneficiary_address = null;
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
                nextStep('firstStep', 'secondStep');
            }
        });
    }
}

function validateSecondStep() {
    event.preventDefault();
    var errorFound = false;
 
    //Financing
    //FALTA VALIDAR ACTIVIDAD ECONOMICA CUANDO CONFIRMEN EL EXCEL EN SUCRE
    var economic_activity = document.getElementById("economic_activity"); if (economic_activity.value === '') { $(economic_activity).addClass('inputRedFocus'); errorFound = true; }
    var occupation = document.getElementById("occupation"); if (occupation.value === '') { $(occupation).addClass('inputRedFocus'); errorFound = true; }
    var annual_income = document.getElementById("annual_income"); if (annual_income.value === ''||annual_income.value === '0.00') { $(annual_income).addClass('inputRedFocus'); errorFound = true; } else { if (Number(annual_income.value) < 0) { $(annual_income).addClass('inputRedFocus'); errorFound = true; } }
    var other_annual_income = document.getElementById("other_annual_income"); if (other_annual_income.value === ''||other_annual_income.value === '0.00') { $(other_annual_income).addClass('inputRedFocus'); errorFound = true; } else { if (Number(other_annual_income.value) < 0) { $(other_annual_income).addClass('inputRedFocus'); errorFound = true; } }
    var total_annual_income = document.getElementById("total_annual_income"); if (total_annual_income.value === ''||total_annual_income.value === '0.00') { $(total_annual_income).addClass('inputRedFocus'); errorFound = true; }
    var description_other_income = document.getElementById("description_other_income"); if (description_other_income.value === '') { $(description_other_income).addClass('inputRedFocus'); errorFound = true; }
    if (description_other_income.value.length > 61) { alert('La descripción no puede superar los 60 caracteres'); }
    var total_assets = document.getElementById("total_assets"); if (total_assets.value === ''||total_assets.value === '0.00') { $(total_assets).addClass('inputRedFocus'); errorFound = true; } else { if (Number(total_assets.value) < 0) { $(total_assets).addClass('inputRedFocus'); errorFound = true; } }
    var total_passives = document.getElementById("total_passives"); if (total_passives.value === ''||total_passives.value === '0.00') { $(total_passives).addClass('inputRedFocus'); errorFound = true; } else if (Number(total_passives.value) < 0) { $(total_passives).addClass('inputRedFocus'); errorFound = true; }else{ $(total_passives).removeClass('inputRedFocus'); }
    var total_assets_pasives = document.getElementById("total_assets_pasives"); if (total_assets_pasives.value === ''||total_assets_pasives.value === '0.00') { $(total_assets_pasives).addClass('inputRedFocus'); errorFound = true; }else{ $(total_assets_pasives).removeClass('inputRedFocus'); }
    
    var personal_reference_name = document.getElementById("personal_reference_name"); if (personal_reference_name.value == '') { $(personal_reference_name).addClass('inputRedFocus'); errorFound = true; }else if(personal_reference_name.value.length > 60){ $(personal_reference_name).addClass('inputRedFocus'); errorFound = true; }
    var personal_reference_relationship = document.getElementById("personal_reference_relationship"); if (personal_reference_relationship.value == '') { $(personal_reference_relationship).addClass('inputRedFocus'); errorFound = true; }else if(personal_reference_relationship.value.length > 20){ $(personal_reference_relationship).addClass('inputRedFocus'); errorFound = true; }
    var personal_reference_phone = document.getElementById("personal_reference_phone");  if(personal_reference_phone.value == ''){ $(personal_reference_phone).addClass('inputRedFocus'); errorFound = true; }else if(isNaN(personal_reference_phone.value)){ $(personal_reference_phone).addClass('inputRedFocus'); errorFound = true; }
    var commercial_reference_name = document.getElementById("commercial_reference_name"); if (commercial_reference_name.value == '') { $(commercial_reference_name).addClass('inputRedFocus'); errorFound = true; }else if(commercial_reference_name.value.length > 30){ $(commercial_reference_name).addClass('inputRedFocus'); errorFound = true; }
    var commercial_reference_amount = document.getElementById("commercial_reference_amount"); if (commercial_reference_amount.value == '') { $(commercial_reference_amount).addClass('inputRedFocus'); errorFound = true; } else { if (Number(commercial_reference_amount.value) < 1) { $(commercial_reference_amount).addClass('inputRedFocus'); errorFound = true; } }
    var commercial_reference_phone = document.getElementById("commercial_reference_phone");  if(commercial_reference_phone.value == ''){ $(commercial_reference_phone).addClass('inputRedFocus'); errorFound = true; }else if(isNaN(commercial_reference_phone.value)){ $(commercial_reference_phone).addClass('inputRedFocus'); errorFound = true; }
    var commercial_reference_bank_name = document.getElementById("commercial_reference_bank_name"); if (commercial_reference_bank_name.value == '') { $(commercial_reference_bank_name).addClass('inputRedFocus'); errorFound = true; }else if(commercial_reference_bank_name.value.length > 30){ $(commercial_reference_bank_name).addClass('inputRedFocus'); errorFound = true; }
    var commercial_reference_product = document.getElementById("commercial_reference_product"); if (commercial_reference_product.value == '') { $(commercial_reference_product).addClass('inputRedFocus'); errorFound = true; }else if(commercial_reference_product.value.length > 30){ $(commercial_reference_product).addClass('inputRedFocus'); errorFound = true; }
    
    //Work Data
    var work_name = document.getElementById("work_name"); if (work_name.value == '') { $(work_name).addClass('inputRedFocus'); errorFound = true; }
    var work_phone = document.getElementById("work_phone"); if (work_phone.value == '') { $(work_phone).addClass('inputRedFocus'); errorFound = true; } else if (isNaN(work_phone.value)) { $(work_phone).addClass('inputRedFocus'); errorFound = true; }else if(work_phone.value.length != 9){ $(work_phone).addClass('inputRedFocus'); errorFound = true; }
    var work_address = document.getElementById("work_address"); if (work_address.value == '') { $(work_address).addClass('inputRedFocus'); errorFound = true; }
     
    if(errorFound == false){
        //Store Data
        var form = document.getElementById('secondStepForm');
        var url = ROUTE + "/vinculation/secondStepForm";
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
}

function validateThirdStep(){
    event.preventDefault();
    var errorFound = false;
    var optRadio3 = null;
    
    var radios = document.getElementsByName('optradio3'); for (var i = 0, length = radios.length; i < length; i++) { if (radios[i].checked) { var optRadio3 = radios[i].value; break; } }
    var pep_client = document.getElementById("pep_client"); if(optRadio3 == 'yes'){ if(pep_client.value == ''){ $(pep_client).addClass('inputRedFocus'); errorFound = true; } }
    
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
//        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
//        var saleId = document.getElementById("saleId").value;
//        var url = ROUTE + '/vinculation/token/generate';
//        $.ajax({
//            type: "POST",
//            data: {_token: CSRF_TOKEN, saleId: saleId},
//            url: url,
//            beforeSend: function () {
//                // Show Loader
//                $("#loaderGif").addClass('loaderGif');
//            },
//            success: function (data) {
//                var fifthStepAlert = document.getElementById("fifthStepAlert");
//                $(fifthStepAlert).addClass('hidden');
//                nextStep('fourthStep','fifthStep');
//            },
//            complete: function () {
//                //Hide Loader
//                var loaderGif = document.getElementById("loaderGif");
//                loaderGif.classList.remove("loaderGif");
//            }
//        });
//        return true;
//    nextStep('fourthStep','fifthStep'); return true;
    
    //Applicant Validations
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
        var messageSrimessageService = document.getElementById("messageService"); 
        $(messageService).css('display', 'block');
        $(messageService).html('Debe ingresar una imagen'); 
        $(messageService).addClass('alert alert-danger'); 
        var errorFound = true; 
    } 
    
    var uploadedFileSri = document.getElementById("uploadedFileSri"); 
    if (uploadedFileSri.value == '') { 
        var messageSri = document.getElementById("messageSri"); 
        $(messageSri).css('display', 'block');
        $(messageSri).html('Debe ingresar una imagen'); 
        $(messageSri).addClass('alert alert-danger'); 
        var errorFound = true; 
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
        var customerId = document.getElementById("documentId").value;
        var url = ROUTE + '/vinculation/token/generate';
        $.ajax({
            type: "POST",
            data: {_token: CSRF_TOKEN, saleId: saleId, customerId: customerId, documentApplicantDate: DocumentApplicantDate.value, documentSpouseDate: DocumentSpouseDate.value},
            url: url,
            async: true,
            beforeSend: function () {
                // Show Loader
                $("#loaderGif").addClass('loaderGif');
            },
            success: function (data) {
                window.location.href = data['url'];
                //nextStep('fourthStep','fifthStep');
            },
            complete: function () {
                //Hide Loader
                var loaderGif = document.getElementById("loaderGif");
                loaderGif.classList.remove("loaderGif");
            }
        });
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
    var fi = document.getElementById('select_file'+id); if (fi.files.length > 0) { for (var i = 0; i <= fi.files.length - 1; i++) { var fsize = fi.files.item(i).size; var file = Math.round((fsize / 1024)); if (file >= 2096) { alert("El archivo debe pesar menos de 2mb."); return false; } } } 
    var form = document.getElementById("upload_form"+id);
    var url = ROUTE + "/vinculation/upload";
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

function validateDocumentSpouse() {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var spouseDocument = document.getElementById("spouseDocument");
        var spouse_document_id = document.getElementById("spouse_document_id");
        var data = {document: spouseDocument.value};
        if(spouse_document_id.value === '1'){ //CEDULA
            var url = ROUTE + '/vinculation/validateDocument';
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
            var url = ROUTE + '/vinculation/validateDocument';
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

function economicActivitySearch(){
    var economic_activity= document.getElementById("economic_activity");
    $(economic_activity).removeClass('inputRedFocus');
    var url = ROUTE + '/vinculation/validateEconomicActivity';
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
    document.getElementById("economic_activity").value = document.getElementById("economic_activity_search").value;
    document.getElementById("closeModal").click();
}
