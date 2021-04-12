/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
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
//                console.log(data);
                if (data.success == 'true') {
//                    console.log(data);
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
;






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
    //console.log('hola');
    formAutoFill(document.getElementById("document").value);
}



function selectProduct(id, name, value) {
    document.getElementById("productCheckBox").value = id;
    document.getElementById("productNameCheckBox").value = name;
    document.getElementById("productValueCheckBox").value = value;
//    clearSecondStepForm();
    fourthStepBtnNext();
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
    var validate = false;
    var weight = document.getElementById("weight"); if (weight.value === '') { $(weight).addClass('inputRedFocus'); validate = true; } else { if(isNaN(weight.value)){ $(weight).addClass('inputRedFocus'); validate = true; }else{ if(Number(weight.value) < 1 || Number(weight.value) > 999){ $(weight).addClass('inputRedFocus'); validate = true; }else{ $(weight).removeClass('inputRedFocus'); } } }
    var stature = document.getElementById("stature"); if (stature.value === '') { $(stature).addClass('inputRedFocus'); validate = true; } else { if(isNaN(stature.value)){ $(stature).addClass('inputRedFocus'); validate = true; }else{ if(Number(stature.value) < 1 || Number(stature.value) > 250){ $(stature).addClass('inputRedFocus'); validate = true; }else{ $(stature).removeClass('inputRedFocus'); } } }
    
    if(validate == false) {
        var url = ROUTE + "/insurance/application/firstStepStore";
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var salId = document.getElementById('salId').value;
        var customerMobilePhone = document.getElementById("mobile_phone").value;

        $.ajax({
            url: url,
            type: "POST",
            data: {_token: CSRF_TOKEN, salId: salId, weight:weight.value, stature:stature.value},
            beforeSend: function () {
//                 Show Loader
                $("#loaderGif").addClass('loaderGif');
            },
            success: function (data)
            {
                nextStep('firstStep', 'secondStep');
            },
            complete: function () {
                //Hide Loader
                $("#loaderGif").removeClass('loaderGif');
            }
        });

    }
}

function secondStepBtnNext() {
    event.preventDefault();
    //Validate Table
    if ($('#beneficiaryBodyTable tr').length == 0) {
        console.log('entro');
    }else{
        var value = 0;
        var TableData = new Array();
        $('#beneficiaryBodyTable tr').each(function (row, tr) {
            TableData[row] = { "document": $(tr).find('td:eq(0)').text() , "type": $(tr).find('td:eq(1)').text() , "first_name": $(tr).find('td:eq(2)').text() , "last_name": $(tr).find('td:eq(3)').text() , "porcentage": $(tr).find('td:eq(4)').text() };
            value += Number(formatNumber($(tr).find('td:eq(4)').text()));
        });
        
        if (value != 100) {
            alert('Los porcentages deben sumar 100%');
            validate = true;
            return false;
        }else{
            var TableDataBene = new Array();

            $('#beneficiaryBodyTable tr').each(function (row, tr) {
                TableDataBene[row] = {
                    "firstName": $(tr).find('td:eq(0)').text()
                    , "secondName": $(tr).find('td:eq(1)').text()
                    , "lastName": $(tr).find('td:eq(2)').text()
                    , "secondLastName": $(tr).find('td:eq(3)').text()
                    , "porcentaje": $(tr).find('td:eq(4)').text()
                    , "relationship": $(tr).find('td:eq(5)').text()
                };
            });
            
            var url = ROUTE + "/insurance/application/secondStepStore";
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var salId = document.getElementById('salId').value;
            var customerMobilePhone = document.getElementById("mobile_phone").value;

            $.ajax({
                url: url,
                type: "POST",
                data: {_token: CSRF_TOKEN, salId: salId, tableData: TableDataBene},
                beforeSend: function () {
//                 Show Loader
                    $("#loaderGif").addClass('loaderGif');
                },
                success: function (data)
                {
                    nextStep('secondStep', 'thirdStep');
                },
                complete: function () {
                    //Hide Loader
                    $("#loaderGif").removeClass('loaderGif');
                }
            });
        }
    }

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
function thirdStepBtnNext() {
    event.preventDefault();
    var validate = false;
    var insuranceRecord1Div = document.getElementById("insuranceRecord1Div"); if ($('input[name=insuranceRecord1]:checked').length > 0) { $(insuranceRecord1Div).removeClass('redBorder'); var insuranceRecord1 = document.querySelector('input[name="insuranceRecord1"]:checked').value;  if (insuranceRecord1 == 'yes') { var textAreaiR1 = document.getElementById("textAreaiR1"); if (textAreaiR1.value == '') { $(textAreaiR1).addClass('inputRedFocus'); validate = true; } else { $(textAreaiR1).removeClass('inputRedFocus'); } } else { document.getElementById("textAreaiR1").value = ''; } } else { $(insuranceRecord1Div).addClass('redBorder'); validate = true; }
    var insuranceRecord2Div = document.getElementById("insuranceRecord2Div"); if ($('input[name=insuranceRecord2]:checked').length > 0) { $(insuranceRecord2Div).removeClass('redBorder'); var insuranceRecord2 = document.querySelector('input[name="insuranceRecord2"]:checked').value;  if (insuranceRecord2 == 'yes') { var textAreaiR2 = document.getElementById("textAreaiR2"); if (textAreaiR2.value == '') { $(textAreaiR2).addClass('inputRedFocus'); validate = true; } else { $(textAreaiR2).removeClass('inputRedFocus'); } } else { document.getElementById("textAreaiR2").value = ''; } } else { $(insuranceRecord2Div).addClass('redBorder'); validate = true; }
    var insuranceRecord3Div = document.getElementById("insuranceRecord3Div"); if ($('input[name=insuranceRecord3]:checked').length > 0) { $(insuranceRecord3Div).removeClass('redBorder'); var insuranceRecord3 = document.querySelector('input[name="insuranceRecord3"]:checked').value;  if (insuranceRecord3 == 'yes') { var textAreaiR3 = document.getElementById("textAreaiR3"); if (textAreaiR3.value == '') { $(textAreaiR3).addClass('inputRedFocus'); validate = true; } else { $(textAreaiR3).removeClass('inputRedFocus'); } } else { document.getElementById("textAreaiR3").value = ''; } } else { $(insuranceRecord3Div).addClass('redBorder'); validate = true; }
    var insuranceRecord4Div = document.getElementById("insuranceRecord4Div"); if ($('input[name=insuranceRecord4]:checked').length > 0) { $(insuranceRecord4Div).removeClass('redBorder'); var insuranceRecord4 = document.querySelector('input[name="insuranceRecord4"]:checked').value;  if (insuranceRecord4 == 'yes') { var textAreaiR4 = document.getElementById("textAreaiR4"); if (textAreaiR4.value == '') { $(textAreaiR4).addClass('inputRedFocus'); validate = true; } else { $(textAreaiR4).removeClass('inputRedFocus'); } } else { document.getElementById("textAreaiR4").value = ''; } } else { $(insuranceRecord4Div).addClass('redBorder'); validate = true; }
    var insuranceRecord5Div = document.getElementById("insuranceRecord5Div"); if ($('input[name=insuranceRecord5]:checked').length > 0) { $(insuranceRecord5Div).removeClass('redBorder'); var insuranceRecord5 = document.querySelector('input[name="insuranceRecord5"]:checked').value;  if (insuranceRecord5 == 'yes') { var textAreaiR5 = document.getElementById("textAreaiR5"); if (textAreaiR5.value == '') { $(textAreaiR5).addClass('inputRedFocus'); validate = true; } else { $(textAreaiR5).removeClass('inputRedFocus'); } } else { document.getElementById("textAreaiR5").value = ''; } } else { $(insuranceRecord5Div).addClass('redBorder'); validate = true; }
    var insuranceRecord6Div = document.getElementById("insuranceRecord6Div"); if ($('input[name=insuranceRecord6]:checked').length > 0) { $(insuranceRecord6Div).removeClass('redBorder'); var insuranceRecord6 = document.querySelector('input[name="insuranceRecord6"]:checked').value;  if (insuranceRecord6 == 'yes') { var textAreaiR6 = document.getElementById("textAreaiR6"); if (textAreaiR6.value == '') { $(textAreaiR6).addClass('inputRedFocus'); validate = true; } else { $(textAreaiR6).removeClass('inputRedFocus'); } } else { document.getElementById("textAreaiR6").value = ''; } } else { $(insuranceRecord6Div).addClass('redBorder'); validate = true; }
    var insuranceRecord7Div = document.getElementById("insuranceRecord7Div"); if ($('input[name=insuranceRecord7]:checked').length > 0) { $(insuranceRecord7Div).removeClass('redBorder'); var insuranceRecord7 = document.querySelector('input[name="insuranceRecord7"]:checked').value;  if (insuranceRecord7 == 'yes') { var textAreaiR7 = document.getElementById("textAreaiR7"); if (textAreaiR7.value == '') { $(textAreaiR7).addClass('inputRedFocus'); validate = true; } else { $(textAreaiR7).removeClass('inputRedFocus'); } } else { document.getElementById("textAreaiR7").value = ''; } } else { $(insuranceRecord7Div).addClass('redBorder'); validate = true; }
    
    if(validate == false){
        var textAreaiR1 = document.getElementById("textAreaiR1");
        var textAreaiR2 = document.getElementById("textAreaiR2");
        var textAreaiR3 = document.getElementById("textAreaiR3");
        var textAreaiR4 = document.getElementById("textAreaiR4");
        var textAreaiR5 = document.getElementById("textAreaiR5");
        var textAreaiR6 = document.getElementById("textAreaiR6");
        var textAreaiR7 = document.getElementById("textAreaiR7");
        var insuranceData = new Array();
        insuranceData = {
            'insuranceRecord1' : document.querySelector('input[name="insuranceRecord1"]:checked').value,
            'textAreaiR1' : textAreaiR1.value,
            'insuranceRecord2' : document.querySelector('input[name="insuranceRecord2"]:checked').value,
            'textAreaiR2' : textAreaiR2.value,
            'insuranceRecord3' : document.querySelector('input[name="insuranceRecord3"]:checked').value,
            'textAreaiR3' : textAreaiR3.value,
            'insuranceRecord4' : document.querySelector('input[name="insuranceRecord4"]:checked').value,
            'textAreaiR4' : textAreaiR4.value,
            'insuranceRecord5' : document.querySelector('input[name="insuranceRecord5"]:checked').value,
            'textAreaiR5' : textAreaiR5.value,
            'insuranceRecord6' : document.querySelector('input[name="insuranceRecord6"]:checked').value,
            'textAreaiR6' : textAreaiR6.value,
            'insuranceRecord7' : document.querySelector('input[name="insuranceRecord7"]:checked').value,
            'textAreaiR7' : textAreaiR7.value
        };
        var url = ROUTE + "/insurance/application/thirdStepStore";
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var salId = document.getElementById('salId').value;        

        $.ajax({
            url: url,
            type: "POST",
            data: {_token: CSRF_TOKEN, salId: salId, insuranceData: insuranceData},
            beforeSend: function () {
//                 Show Loader
                $("#loaderGif").addClass('loaderGif');
            },
            success: function (data)
            {
                nextStep('thirdStep', 'fourthStep');
            },
            complete: function () {
                //Hide Loader
                $("#loaderGif").removeClass('loaderGif');
            }
        });
    }
//    nextStep('thirdStep', 'fourthStep');
}

function fourthStepBtnNext() {
    event.preventDefault();
    var validate = false;
    
    var medicalHistory1Div = document.getElementById("medicalHistory1Div"); if ($('input[name=medicalHistory1]:checked').length > 0) { $(medicalHistory1Div).removeClass('redBorder'); var medicalHistory1 = document.querySelector('input[name="medicalHistory1"]:checked').value; if (medicalHistory1 == 'yes') { var diagnosis1 = document.getElementById("diagnosis1"); if (diagnosis1.value == '') { $(diagnosis1).addClass('inputRedFocus'); validate = true; } else { $(diagnosis1).removeClass('inputRedFocus'); } var treatmentDate1 = document.getElementById("treatmentDate1"); if (treatmentDate1.value == '') { $(treatmentDate1).addClass('inputRedFocus'); validate = true; } else { $(treatmentDate1).removeClass('inputRedFocus'); } var duration1 = document.getElementById("duration1"); if (duration1.value == '') { $(duration1).addClass('inputRedFocus'); validate = true; } else { $(duration1).removeClass('inputRedFocus'); } var hospital1 = document.getElementById("hospital1"); if (hospital1.value == '') { $(hospital1).addClass('inputRedFocus'); validate = true; } else { $(hospital1).removeClass('inputRedFocus'); } } else { document.getElementById("diagnosis1").value = ''; document.getElementById("treatmentDate1").value = ''; document.getElementById("duration1").value = ''; document.getElementById("hospital1").value = ''; } } else { $(medicalHistory1Div).addClass('redBorder'); validate = true; }
    var medicalHistory2Div = document.getElementById("medicalHistory2Div"); if ($('input[name=medicalHistory2]:checked').length > 0) { $(medicalHistory2Div).removeClass('redBorder'); var medicalHistory2 = document.querySelector('input[name="medicalHistory2"]:checked').value; if (medicalHistory2 == 'yes') { var diagnosis2 = document.getElementById("diagnosis2"); if (diagnosis2.value == '') { $(diagnosis2).addClass('inputRedFocus'); validate = true; } else { $(diagnosis2).removeClass('inputRedFocus'); } var treatmentDate2 = document.getElementById("treatmentDate2"); if (treatmentDate2.value == '') { $(treatmentDate2).addClass('inputRedFocus'); validate = true; } else { $(treatmentDate2).removeClass('inputRedFocus'); } var duration2 = document.getElementById("duration2"); if (duration2.value == '') { $(duration2).addClass('inputRedFocus'); validate = true; } else { $(duration2).removeClass('inputRedFocus'); } var hospital2 = document.getElementById("hospital2"); if (hospital2.value == '') { $(hospital2).addClass('inputRedFocus'); validate = true; } else { $(hospital2).removeClass('inputRedFocus'); } } else { document.getElementById("diagnosis2").value = ''; document.getElementById("treatmentDate2").value = ''; document.getElementById("duration2").value = ''; document.getElementById("hospital2").value = ''; } } else { $(medicalHistory2Div).addClass('redBorder'); validate = true; }
    var medicalHistory3Div = document.getElementById("medicalHistory3Div"); if ($('input[name=medicalHistory3]:checked').length > 0) { $(medicalHistory3Div).removeClass('redBorder'); var medicalHistory3 = document.querySelector('input[name="medicalHistory3"]:checked').value; if (medicalHistory3 == 'yes') { var diagnosis3 = document.getElementById("diagnosis3"); if (diagnosis3.value == '') { $(diagnosis3).addClass('inputRedFocus'); validate = true; } else { $(diagnosis3).removeClass('inputRedFocus'); } var treatmentDate3 = document.getElementById("treatmentDate3"); if (treatmentDate3.value == '') { $(treatmentDate3).addClass('inputRedFocus'); validate = true; } else { $(treatmentDate3).removeClass('inputRedFocus'); } var duration3 = document.getElementById("duration3"); if (duration3.value == '') { $(duration3).addClass('inputRedFocus'); validate = true; } else { $(duration3).removeClass('inputRedFocus'); } var hospital3 = document.getElementById("hospital3"); if (hospital3.value == '') { $(hospital3).addClass('inputRedFocus'); validate = true; } else { $(hospital3).removeClass('inputRedFocus'); } } else { document.getElementById("diagnosis3").value = ''; document.getElementById("treatmentDate3").value = ''; document.getElementById("duration3").value = ''; document.getElementById("hospital3").value = ''; } } else { $(medicalHistory3Div).addClass('redBorder'); validate = true; }
    var medicalHistory4Div = document.getElementById("medicalHistory4Div"); if ($('input[name=medicalHistory4]:checked').length > 0) { $(medicalHistory4Div).removeClass('redBorder'); var medicalHistory4 = document.querySelector('input[name="medicalHistory4"]:checked').value; if (medicalHistory4 == 'yes') { var diagnosis4 = document.getElementById("diagnosis4"); if (diagnosis4.value == '') { $(diagnosis4).addClass('inputRedFocus'); validate = true; } else { $(diagnosis4).removeClass('inputRedFocus'); } var treatmentDate4 = document.getElementById("treatmentDate4"); if (treatmentDate4.value == '') { $(treatmentDate4).addClass('inputRedFocus'); validate = true; } else { $(treatmentDate4).removeClass('inputRedFocus'); } var duration4 = document.getElementById("duration4"); if (duration4.value == '') { $(duration4).addClass('inputRedFocus'); validate = true; } else { $(duration4).removeClass('inputRedFocus'); } var hospital4 = document.getElementById("hospital4"); if (hospital4.value == '') { $(hospital4).addClass('inputRedFocus'); validate = true; } else { $(hospital4).removeClass('inputRedFocus'); } } else { document.getElementById("diagnosis4").value = ''; document.getElementById("treatmentDate4").value = ''; document.getElementById("duration4").value = ''; document.getElementById("hospital4").value = ''; } } else { $(medicalHistory4Div).addClass('redBorder'); validate = true; }
    var medicalHistory5Div = document.getElementById("medicalHistory5Div"); if ($('input[name=medicalHistory5]:checked').length > 0) { $(medicalHistory5Div).removeClass('redBorder'); var medicalHistory5 = document.querySelector('input[name="medicalHistory5"]:checked').value; if (medicalHistory5 == 'yes') { var diagnosis5 = document.getElementById("diagnosis5"); if (diagnosis5.value == '') { $(diagnosis5).addClass('inputRedFocus'); validate = true; } else { $(diagnosis5).removeClass('inputRedFocus'); } var treatmentDate5 = document.getElementById("treatmentDate5"); if (treatmentDate5.value == '') { $(treatmentDate5).addClass('inputRedFocus'); validate = true; } else { $(treatmentDate5).removeClass('inputRedFocus'); } var duration5 = document.getElementById("duration5"); if (duration5.value == '') { $(duration5).addClass('inputRedFocus'); validate = true; } else { $(duration5).removeClass('inputRedFocus'); } var hospital5 = document.getElementById("hospital5"); if (hospital5.value == '') { $(hospital5).addClass('inputRedFocus'); validate = true; } else { $(hospital5).removeClass('inputRedFocus'); } } else { document.getElementById("diagnosis5").value = ''; document.getElementById("treatmentDate5").value = ''; document.getElementById("duration5").value = ''; document.getElementById("hospital5").value = ''; } } else { $(medicalHistory5Div).addClass('redBorder'); validate = true; }
    var medicalHistory6Div = document.getElementById("medicalHistory6Div"); if ($('input[name=medicalHistory6]:checked').length > 0) { $(medicalHistory6Div).removeClass('redBorder'); var medicalHistory6 = document.querySelector('input[name="medicalHistory6"]:checked').value; if (medicalHistory6 == 'yes') { var diagnosis6 = document.getElementById("diagnosis6"); if (diagnosis6.value == '') { $(diagnosis6).addClass('inputRedFocus'); validate = true; } else { $(diagnosis6).removeClass('inputRedFocus'); } var treatmentDate6 = document.getElementById("treatmentDate6"); if (treatmentDate6.value == '') { $(treatmentDate6).addClass('inputRedFocus'); validate = true; } else { $(treatmentDate6).removeClass('inputRedFocus'); } var duration6 = document.getElementById("duration6"); if (duration6.value == '') { $(duration6).addClass('inputRedFocus'); validate = true; } else { $(duration6).removeClass('inputRedFocus'); } var hospital6 = document.getElementById("hospital6"); if (hospital6.value == '') { $(hospital6).addClass('inputRedFocus'); validate = true; } else { $(hospital6).removeClass('inputRedFocus'); } } else { document.getElementById("diagnosis6").value = ''; document.getElementById("treatmentDate6").value = ''; document.getElementById("duration6").value = ''; document.getElementById("hospital6").value = ''; } } else { $(medicalHistory6Div).addClass('redBorder'); validate = true; }
    var medicalHistory7Div = document.getElementById("medicalHistory7Div"); if ($('input[name=medicalHistory7]:checked').length > 0) { $(medicalHistory7Div).removeClass('redBorder'); var medicalHistory7 = document.querySelector('input[name="medicalHistory7"]:checked').value; if (medicalHistory7 == 'yes') { var diagnosis7 = document.getElementById("diagnosis7"); if (diagnosis7.value == '') { $(diagnosis7).addClass('inputRedFocus'); validate = true; } else { $(diagnosis7).removeClass('inputRedFocus'); } var treatmentDate7 = document.getElementById("treatmentDate7"); if (treatmentDate7.value == '') { $(treatmentDate7).addClass('inputRedFocus'); validate = true; } else { $(treatmentDate7).removeClass('inputRedFocus'); } var duration7 = document.getElementById("duration7"); if (duration7.value == '') { $(duration7).addClass('inputRedFocus'); validate = true; } else { $(duration7).removeClass('inputRedFocus'); } var hospital7 = document.getElementById("hospital7"); if (hospital7.value == '') { $(hospital7).addClass('inputRedFocus'); validate = true; } else { $(hospital7).removeClass('inputRedFocus'); } } else { document.getElementById("diagnosis7").value = ''; document.getElementById("treatmentDate7").value = ''; document.getElementById("duration7").value = ''; document.getElementById("hospital7").value = ''; } } else { $(medicalHistory7Div).addClass('redBorder'); validate = true; }
    var medicalHistory8Div = document.getElementById("medicalHistory8Div"); if ($('input[name=medicalHistory8]:checked').length > 0) { $(medicalHistory8Div).removeClass('redBorder'); var medicalHistory8 = document.querySelector('input[name="medicalHistory8"]:checked').value; if (medicalHistory8 == 'yes') { var diagnosis8 = document.getElementById("diagnosis8"); if (diagnosis8.value == '') { $(diagnosis8).addClass('inputRedFocus'); validate = true; } else { $(diagnosis8).removeClass('inputRedFocus'); } var treatmentDate8 = document.getElementById("treatmentDate8"); if (treatmentDate8.value == '') { $(treatmentDate8).addClass('inputRedFocus'); validate = true; } else { $(treatmentDate8).removeClass('inputRedFocus'); } var duration8 = document.getElementById("duration8"); if (duration8.value == '') { $(duration8).addClass('inputRedFocus'); validate = true; } else { $(duration8).removeClass('inputRedFocus'); } var hospital8 = document.getElementById("hospital8"); if (hospital8.value == '') { $(hospital8).addClass('inputRedFocus'); validate = true; } else { $(hospital8).removeClass('inputRedFocus'); } } else { document.getElementById("diagnosis8").value = ''; document.getElementById("treatmentDate8").value = ''; document.getElementById("duration8").value = ''; document.getElementById("hospital8").value = ''; } } else { $(medicalHistory8Div).addClass('redBorder'); validate = true; }
   
    if(validate == false){
        var clinicalData = new Array();
        clinicalData = {
            'medicalHistory1' : document.querySelector('input[name="medicalHistory1"]:checked').value,
            'diagnosis1' : document.getElementById("diagnosis1").value,
            'treatmentDate1' : document.getElementById("treatmentDate1").value,
            'duration1' : document.getElementById("duration1").value,
            'hospital1' : document.getElementById("hospital1").value,
            'medicalHistory2' : document.querySelector('input[name="medicalHistory2"]:checked').value,
            'diagnosis2' : document.getElementById("diagnosis2").value,
            'treatmentDate2' : document.getElementById("treatmentDate2").value,
            'duration2' : document.getElementById("duration2").value,
            'hospital2' : document.getElementById("hospital2").value,
            'medicalHistory3' : document.querySelector('input[name="medicalHistory3"]:checked').value,
            'diagnosis3' : document.getElementById("diagnosis3").value,
            'treatmentDate3' : document.getElementById("treatmentDate3").value,
            'duration3' : document.getElementById("duration3").value,
            'hospital3' : document.getElementById("hospital3").value,
            'medicalHistory4' : document.querySelector('input[name="medicalHistory4"]:checked').value,
            'diagnosis4' : document.getElementById("diagnosis4").value,
            'treatmentDate4' : document.getElementById("treatmentDate4").value,
            'duration4' : document.getElementById("duration4").value,
            'hospital4' : document.getElementById("hospital4").value,
            'medicalHistory5' : document.querySelector('input[name="medicalHistory5"]:checked').value,
            'diagnosis5' : document.getElementById("diagnosis5").value,
            'treatmentDate5' : document.getElementById("treatmentDate5").value,
            'duration5' : document.getElementById("duration5").value,
            'hospital5' : document.getElementById("hospital5").value,
            'medicalHistory6' : document.querySelector('input[name="medicalHistory6"]:checked').value,
            'diagnosis6' : document.getElementById("diagnosis6").value,
            'treatmentDate6' : document.getElementById("treatmentDate6").value,
            'duration6' : document.getElementById("duration6").value,
            'hospital6' : document.getElementById("hospital6").value,
            'medicalHistory7' : document.querySelector('input[name="medicalHistory7"]:checked').value,
            'diagnosis7' : document.getElementById("diagnosis7").value,
            'treatmentDate7' : document.getElementById("treatmentDate7").value,
            'duration7' : document.getElementById("duration7").value,
            'hospital7' : document.getElementById("hospital7").value,
            'medicalHistory8' : document.querySelector('input[name="medicalHistory8"]:checked').value,
            'diagnosis8' : document.getElementById("diagnosis8").value,
            'treatmentDate8' : document.getElementById("treatmentDate8").value,
            'duration8' : document.getElementById("duration8").value,
            'hospital8' : document.getElementById("hospital8").value
        };
        var url = ROUTE + "/insurance/application/fourthStepStore";
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var salId = document.getElementById('salId').value;        

        $.ajax({
            url: url,
            type: "POST",
            data: {_token: CSRF_TOKEN, salId: salId, clinicalData: clinicalData},
            beforeSend: function () {
//                 Show Loader
                $("#loaderGif").addClass('loaderGif');
            },
            success: function (data)
            {
                nextStep('fourthStep','fifthStep');
            },
            complete: function () {
                //Hide Loader
                $("#loaderGif").removeClass('loaderGif');
            }
        });
    }
}

function fifthStepBtnNext(){
    var url = ROUTE + "/insurance/application/fifthStepStore";
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var salId = document.getElementById('salId').value;        

        $.ajax({
            url: url,
            type: "POST",
            data: {_token: CSRF_TOKEN, salId: salId},
            beforeSend: function () {
//                 Show Loader
                $("#loaderGif").addClass('loaderGif');
            },
            success: function (data)
            {
                window.location.href = data['url'];
            },
            complete: function () {
                //Hide Loader
                $("#loaderGif").removeClass('loaderGif');
            }
        });
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

function removeInputRedFocus(id){
    var id = document.getElementById(id);
    $(id).removeClass('inputRedFocus');
}

function addBeneficiary() {    
    event.preventDefault();

    //Variables
    var first_name = document.getElementById("beneficiary_first_name");
    var second_name = document.getElementById("beneficiary_second_name");
    var last_name = document.getElementById("beneficiary_last_name");
    var second_last_name = document.getElementById("beneficiary_second_last_name");
    var porcentage_Beneficiary = document.getElementById("porcentage_Beneficiary");
    var beneficiary_relationship = document.getElementById("beneficiary_relationship");
    var validate = false;

    //Validation
    if (first_name.value === "") { $(first_name).addClass('inputRedFocus'); validate = true; } else { $(first_name).removeClass('inputRedFocus'); }
    //if (second_name.value === "") { $(second_name).addClass('inputRedFocus'); validate = true; } else { $(second_name).removeClass('inputRedFocus'); }
    if (last_name.value === "") { $(last_name).addClass('inputRedFocus'); validate = true; } else { $(last_name).removeClass('inputRedFocus'); }
    if (second_last_name.value === "") { $(second_last_name).addClass('inputRedFocus'); validate = true; } else { $(second_last_name).removeClass('inputRedFocus'); }
    if (porcentage_Beneficiary.value === "" || porcentage_Beneficiary.value > 100 || porcentage_Beneficiary.value === "0") { $(porcentage_Beneficiary).addClass('inputRedFocus'); validate = true; } else { $(porcentage_Beneficiary).removeClass('inputRedFocus'); }
    if (beneficiary_relationship.value === "0") { $(beneficiary_relationship).addClass('inputRedFocus'); validate = true; } else { $(beneficiary_relationship).removeClass('inputRedFocus'); }

    //Beneficiary Table Data
    var value = 0;
    var TableData = new Array();
    $('#beneficiaryBodyTable tr').each(function (row, tr) {
        TableData += [$(tr).find('td:eq(4)').text()];
        value += Number(formatNumber($(tr).find('td:eq(4)').text()));
    });

    //Validate Beneficiary Table Data
    value += Number(formatNumber(porcentage_Beneficiary.value));
    if (value > 100) {
        validate = true;
        alert('El porcentaje no debe ser mayor a 100%');
    }

    //Add Row
    if (validate == false) {
        addRow(first_name, second_name, last_name, second_last_name, porcentage_Beneficiary, $("#beneficiary_relationship :selected").text());
    }
}

function addRow(first_name, second_name, last_name, second_last_name, porcentage, relationship) {
    var bodyTable = document.getElementById("beneficiaryBodyTable");

    var rowCount = bodyTable.rows.length;
    var row = bodyTable.insertRow(rowCount);

    //Validate Document id Name
    row.insertCell(0).innerHTML = first_name.value;
    row.insertCell(1).innerHTML = second_name.value;
    row.insertCell(2).innerHTML = last_name.value;
    row.insertCell(3).innerHTML = second_last_name.value;
    row.insertCell(4).innerHTML = porcentage.value;
    row.insertCell(5).innerHTML = relationship;
    row.insertCell(6).innerHTML = '<button type="submit" class="btn btn-link" onClick="Javacsript:editRow(\'' + first_name.value + '\',\'' + second_name.value + '\', \'' + last_name.value + '\', \'' + second_last_name.value + '\', \'' + porcentage.value + '\',\'' + relationship + '\',this)"><span class="glyphicon glyphicon-pencil" style="color:green;font-size:18px"></span></button><button type="submit" class="btn btn-link" onClick="Javacsript:deleteRow(this)"><span class="glyphicon glyphicon-remove" style="color:red;font-size:18px"></span></button>';

    //Return Inputs no Null
    first_name.value = '';
    second_name.value = '';
    last_name.value = '';
    second_last_name.value = '';
    porcentage.value = '';
    document.getElementById("beneficiary_relationship").value = '0';
}

function deleteRow(obj) {
    var index = obj.parentNode.parentNode.rowIndex;
    var table = document.getElementById("beneficiaryTable");
    table.deleteRow(index);
}

function editRow(first_name, second_name, last_name, second_last_name, porcentage_Beneficiary, relationship, obj) {
    //Fill Form
    document.getElementById("beneficiary_first_name").value = first_name;
    document.getElementById("beneficiary_second_name").value = second_name;
    document.getElementById("beneficiary_last_name").value = last_name;
    document.getElementById("beneficiary_second_last_name").value = second_last_name;
    document.getElementById("porcentage_Beneficiary").value = porcentage_Beneficiary;
    
    //Validate Beneficiary Relationship
    if(relationship === 'PADRE/MADRE'){ var relationshipId = 1; }
    if(relationship === 'HIJO(A)'){ var relationshipId = 2; }
    if(relationship === 'ABUELO(A)'){ var relationshipId = 3; }
    if(relationship === 'NIETO(A)'){ var relationshipId = 4; }
    if(relationship === 'HERMANO(A)'){ var relationshipId = 5; }
    if(relationship === 'SUEGRO(A)'){ var relationshipId = 6; }
    if(relationship === 'YERNO'){ var relationshipId = 7; }
    if(relationship === 'NUERA'){ var relationshipId = 8; }
    if(relationship === 'CUÑADO(A)'){ var relationshipId = 9; }
    if(relationship === 'CONYUGE'){ var relationshipId = 10; }
    if(relationship === 'OTROS'){ var relationshipId = 11; }
    if(relationship === 'TIO(A)'){ var relationshipId = 12; }
    if(relationship === 'PRIMO(A)'){ var relationshipId = 13; }
    if(relationship === 'ASEGURADO PRINCIPAL'){ var relationshipId = 15; }
    
//    $('#beneficiary_relationship').val(relationshipId);
    document.getElementById("beneficiary_relationship").value = relationshipId;
   
    deleteRow(obj);
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

$(document).ready(function () {
    ResponsiveCellHeaders("beneficiaryTable");
    AddTableARIA();

//    $('input[type="radio"]').prop('checked', false);

    $('input[name="insuranceRecord1"]').on('click', function() {
        var insuranceRecord1Div = document.getElementById("insuranceRecord1Div");
        $(insuranceRecord1Div).removeClass('redBorder');
        if ($(this).val() == 'yes') {
            $('#textArea_iR1').show();
        }
        else {
            $('#textArea_iR1').hide();
        }
    });
    $('input[name="insuranceRecord2"]').on('click', function() {
        var insuranceRecord2Div = document.getElementById("insuranceRecord2Div");
        $(insuranceRecord2Div).removeClass('redBorder');
        if ($(this).val() == 'yes') {
            $('#textArea_iR2').show();
        }
        else {
            $('#textArea_iR2').hide();
        }
    });
    $('input[name="insuranceRecord3"]').on('click', function() {
        var insuranceRecord3Div = document.getElementById("insuranceRecord3Div");
        $(insuranceRecord3Div).removeClass('redBorder');
        if ($(this).val() == 'yes') {
            $('#textArea_iR3').show();
        }
        else {
            $('#textArea_iR3').hide();
        }
    });
    $('input[name="insuranceRecord4"]').on('click', function() {
        var insuranceRecord4Div = document.getElementById("insuranceRecord4Div");
        $(insuranceRecord4Div).removeClass('redBorder');
        if ($(this).val() == 'yes') {
            $('#textArea_iR4').show();
        }
        else {
            $('#textArea_iR4').hide();
        }
    });
    $('input[name="insuranceRecord5"]').on('click', function() {
        var insuranceRecord5Div = document.getElementById("insuranceRecord5Div");
        $(insuranceRecord5Div).removeClass('redBorder');
        if ($(this).val() == 'yes') {
            $('#textArea_iR5').show();
        }
        else {
            $('#textArea_iR5').hide();
        }
    });
    $('input[name="insuranceRecord6"]').on('click', function() {
        var insuranceRecord6Div = document.getElementById("insuranceRecord6Div");
        $(insuranceRecord6Div).removeClass('redBorder');
        if ($(this).val() == 'yes') {
            $('#textArea_iR6').show();
        }
        else {
            $('#textArea_iR6').hide();
        }
    });
    $('input[name="insuranceRecord7"]').on('click', function() {
        var insuranceRecord7Div = document.getElementById("insuranceRecord7Div");
        $(insuranceRecord7Div).removeClass('redBorder');
        if ($(this).val() == 'yes') {
            $('#textArea_iR7').show();
        }
        else {
            $('#textArea_iR7').hide();
        }
    });

    $('input[name="medicalHistory1"]').on('click', function() {
        if ($(this).val() == 'yes') {
            $('#textArea_mH1').show();
        }
        else {
            $('#textArea_mH1').hide();
        }
    });
    $('input[name="medicalHistory2"]').on('click', function() {
        if ($(this).val() == 'yes') {
            $('#textArea_mH2').show();
        }
        else {
            $('#textArea_mH2').hide();
        }
    });
    $('input[name="medicalHistory3"]').on('click', function() {
        if ($(this).val() == 'yes') {
            $('#textArea_mH3').show();
        }
        else {
            $('#textArea_mH3').hide();
        }
    });
    $('input[name="medicalHistory4"]').on('click', function() {
        if ($(this).val() == 'yes') {
            $('#textArea_mH4').show();
        }
        else {
            $('#textArea_mH4').hide();
        }
    });
    $('input[name="medicalHistory5"]').on('click', function() {
        if ($(this).val() == 'yes') {
            $('#textArea_mH5').show();
        }
        else {
            $('#textArea_mH5').hide();
        }
    });
    $('input[name="medicalHistory6"]').on('click', function() {
        if ($(this).val() == 'yes') {
            $('#textArea_mH6').show();
        }
        else {
            $('#textArea_mH6').hide();
        }
    });
    $('input[name="medicalHistory7"]').on('click', function() {
        if ($(this).val() == 'yes') {
            $('#textArea_mH7').show();
        }
        else {
            $('#textArea_mH7').hide();
        }
    });
    $('input[name="medicalHistory8"]').on('click', function() {
        if ($(this).val() == 'yes') {
            $('#textArea_mH8').show();
        }
        else {
            $('#textArea_mH8').hide();
        }
    });

});

   function ResponsiveCellHeaders(elmID) {
  try {
    var THarray = [];
    var table = document.getElementById(elmID);
    console.log(table);
    var ths = table.getElementsByTagName("th");
    for (var i = 0; i < ths.length; i++) {
      var headingText = ths[i].innerHTML;
      THarray.push(headingText);
    }
    var styleElm = document.createElement("style"),
      styleSheet;
    document.head.appendChild(styleElm);
    styleSheet = styleElm.sheet;
    for (var i = 0; i < THarray.length; i++) {
      styleSheet.insertRule(
        "#" +
          elmID +
          " td:nth-child(" +
          (i + 1) +
          ')::before {content:"' +
          THarray[i] +
          ': ";}',
        styleSheet.cssRules.length
      );
    }
  } catch (e) {
    console.log("ResponsiveCellHeaders(): " + e);
  }
}

// https://adrianroselli.com/2018/02/tables-css-display-properties-and-aria.html
// https://adrianroselli.com/2018/05/functions-to-add-aria-to-tables-and-lists.html
function AddTableARIA() {
  try {
    var allTables = document.querySelectorAll('table');
    for (var i = 0; i < allTables.length; i++) {
      allTables[i].setAttribute('role','table');
    }
    var allRowGroups = document.querySelectorAll('thead, tbody, tfoot');
    for (var i = 0; i < allRowGroups.length; i++) {
      allRowGroups[i].setAttribute('role','rowgroup');
    }
    var allRows = document.querySelectorAll('tr');
    for (var i = 0; i < allRows.length; i++) {
      allRows[i].setAttribute('role','row');
    }
    var allCells = document.querySelectorAll('td');
    for (var i = 0; i < allCells.length; i++) {
      allCells[i].setAttribute('role','cell');
    }
    var allHeaders = document.querySelectorAll('th');
    for (var i = 0; i < allHeaders.length; i++) {
      allHeaders[i].setAttribute('role','columnheader');
    }
    // this accounts for scoped row headers
    var allRowHeaders = document.querySelectorAll('th[scope=row]');
    for (var i = 0; i < allRowHeaders.length; i++) {
      allRowHeaders[i].setAttribute('role','rowheader');
    }
    // caption role not needed as it is not a real role and
    // browsers do not dump their own role with display block
  } catch (e) {
    console.log("AddTableARIA(): " + e);
  }
}

function checkWidth(init) {
    if ($(window).width() < 480) {
            ResponsiveCellHeaders("beneficiaryTable");
            AddTableARIA();
//        $('#contentDiv').addClass('table-responsive');
    } else {
        if (!init) {
//            $('#contentDiv').removeClass('table-responsive');
        }
    }
}

