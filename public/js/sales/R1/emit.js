/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {
    
    
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

    setInputFilter(document.getElementById("endorsementAmount"), function(value) {
//        return /^\d*\.?\d*$/.test(value); 
        return /^-?\d*[.]?\d{0,2}$/.test(value); 
    });
    
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

function uploadPictureForm(id, type) {
    event.preventDefault();
    var form = document.getElementById(id);
    var url = ROUTE + "/sales/emit/r1/upload";
    $.ajax({
        url: url,
        type: "POST",
        data: new FormData(form),
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function () {
            var loaderGif = document.getElementById("loaderGif");
            loaderGif.className += "loaderGif";
        },
        success: function (data)
        {
            var message = '#message' + type;   
            $(message).css('display', 'none');
            var uploaded_image = '#uploaded_image' + type;
            $(uploaded_image).html(data.uploaded_image);
            var deletePicture = 'deletePicture' + type ;
            var uploadPicture = 'upload' + type;
            if (data.Success == 'true') {
                var uploadPic = document.getElementById("select_file" + type);
                $(uploadPic).addClass('hidden');
                var deletePic = document.getElementById(deletePicture);
                $(deletePic).removeClass('hidden');
                $(deletePic).addClass('visible');
                var uploadPic = document.getElementById(uploadPicture);
                $(uploadPic).removeClass('visible');
                $(uploadPic).addClass('hidden');
                var fileName = document.getElementById('fileName' + type);
                $(fileName).removeClass('visible');
                $(fileName).addClass('hidden');
                fileName.innerHTML = '';
            } else {
                $(message).css('display', 'block');
                $(message).html(data.message);
                $(message).addClass(data.class_name);
            }
        },
        complete: function () {
            var loaderGif = document.getElementById("loaderGif");
            loaderGif.classList.remove("loaderGif");
        }
    });
}

function deletePictureForm(type) {
    event.preventDefault();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = ROUTE + '/sales/emit/r1/delete';
    var saleId = document.getElementById("saleId");
    var data = {saleId: saleId.value, type:type};
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, data},
        url: url,
        beforeSend: function () {
            var loaderGif = document.getElementById("loaderGif");
            loaderGif.className += "loaderGif";
        },
        success: function (data) {
            var uploaded_imageFront = document.getElementById("uploaded_image" + type);
            $(uploaded_imageFront).html('');
            var uploadPic = document.getElementById("select_file" + type);
            $(uploadPic).addClass('visible');
            $(uploadPic).removeClass('hidden');
            var deletePic = document.getElementById("deletePicture" + type);
            $(deletePic).removeClass('visible');
            $(deletePic).addClass('hidden');
            var uploadPic = document.getElementById("upload" + type);
            $(uploadPic).removeClass('hidden');
            $(uploadPic).addClass('visible');
            var fileName = document.getElementById('fileName' + type);
            $(fileName).removeClass('hidden');
            $(fileName).addClass('visible');
            fileName.innerHTML = '';
//            var uploadedFile = document.getElementById('uploadedFile' + id);
//            uploadedFile.value = '';
        },
        complete: function () {
            var loaderGif = document.getElementById("loaderGif");
            loaderGif.classList.remove("loaderGif");
        }
    });
}

function fileNameFunction(type) {
    var file = document.getElementById('select_file' + type).files[0];
    var uploadPic = document.getElementById("fileName" + type);
    uploadPic.innerHTML = file.name;

}

function emitStore(){
    event.preventDefault();
    var validate = false;
    var beginDate = document.getElementById("beginDate");
    if(beginDate.value === ''){ validate = true; $(beginDate).addClass('inputRedFocus'); }
    var endDate = document.getElementById("endDate");
    if(endDate.value === ''){ validate = true; $(endDate).addClass('inputRedFocus'); }
    if( $('#uploaded_image').is(':empty') ) { validate = true; var message = document.getElementById("message"); $(message).css('display', 'block'); $(message).html('Debe ingresar una imagen'); $(message).addClass('alert alert-danger'); }
    
    if(validate === false){
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var saleId = document.getElementById("saleId");
        var insuranceBranch = document.getElementById("insuranceBranch");
        var url = ROUTE + '/sales/emit/store';
        $.ajax({
            url: url,
            type: "POST",
            /* send the csrf-token and the input to the controller */
            data: {_token: CSRF_TOKEN, beginDate:beginDate.value, endDate: endDate.value, saleId: saleId.value, insuranceBranch: insuranceBranch.value},
            success: function (result) {
                document.getElementById("chargeId").value = result;
                document.getElementById("formBtn").click();
            }
        });
    }
}

function removeInputRedFocus(id){
    var id = document.getElementById(id);
    $(id).removeClass('inputRedFocus');
}

function clearSecondStepForm(){
    var beginDate = document.getElementById("beginDate");
    $(beginDate).removeClass('inputRedFocus');
    var endDate = document.getElementById("endDate");
    $(endDate).removeClass('inputRedFocus');
    var message = document.getElementById("message");
    $(message).css('display', 'none'); 
    $(message).html('');
    $(message).removeClass('alert alert-danger');
}

function previous(saleId, customerId){
    event.preventDefault();
    var url = ROUTE + '/sales/emit/'+saleId+'/'+customerId+'/1';
    var next = '#emitStep';
    loadNextPage(url,next);
}

function endosoChange(value){
    if(value == 1){
        var div = document.getElementById('endosoDiv');
        $(div).fadeIn();
    }else{
        var div = document.getElementById('endosoDiv');
        $(div).fadeOut();
        document.getElementById('document').value = '';
        clearFormEndoso();
    }
}

function documentBtn(){
    clearFormEndoso();
    var documentEndoso = document.getElementById('document');
    if(documentEndoso.value.length != 13){ $(documentEndoso).addClass('inputRedFocus'); return false; }else if(isNaN(documentEndoso.value)){ $(documentEndoso).addClass('inputRedFocus'); return false; }else{ $(documentEndoso).removeClass('inputRedFocus'); }
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: ROUTE + '/sales/emit/r1/document',
        type: "POST",
        data: {_token: CSRF_TOKEN, document:documentEndoso.value},
        beforeSend: function () {
            var loaderGif = document.getElementById("loaderGif");
            loaderGif.className += "loaderGif";
        },
        success: function (data)
        {
            if(data['error'][0]['code'] === '001' || data['error'][0]['code'] === '003'){
            }else{
                document.getElementById('tradename').value = data['infocliente']['ruc'][0]['nombrefantasia'];
                document.getElementById('businessName').value = data['infocliente']['ruc'][0]['razonsocial'];
            }
        },
        complete: function () {
            var loaderGif = document.getElementById("loaderGif");
            loaderGif.classList.remove("loaderGif");
        }
    });
}

function clearFormEndoso(){
    document.getElementById('businessName').value = '';
    document.getElementById('tradename').value = '';
    document.getElementById('endorsementAmount').value = '';
}

function validateSecondStep() {
    event.preventDefault();
    var txt;
    var r = confirm('¿Seguro que desea realizar el pago y la emisión?');
    if (r === false) {
        return false;
    }
    var validate = false;
    var insuredValue = document.getElementById("insuredValue").value;
    //Validate Dates
    var beginDate = document.getElementById('beginDate'); if(beginDate.value === ''){ $(beginDate).addClass('inputRedFocus'); validate = true; }else{ $(beginDate).removeClass('inputRedFocus'); }
    var endDate = document.getElementById('endDate'); if(endDate.value === ''){ $(endDate).addClass('inputRedFocus'); validate = true; }else{ $(endDate).removeClass('inputRedFocus'); }
    var saleId = document.getElementById('saleId');
    var insuranceBranch = document.getElementById('insuranceBranch');
    
    var newVehicle = document.getElementById('newVehicle').value;
    if(newVehicle === 'true'){
        var endosoSelect = document.getElementById('endosoSelect'); if (endosoSelect.value === '') { $(endosoSelect).addClass('inputRedFocus'); validate = true; } else { $(endosoSelect).removeClass('inputRedFocus'); }
        if(endosoSelect.value === '1'){
            var documentEndoso = document.getElementById('document'); if(documentEndoso.value === ''){ $(documentEndoso).addClass('inputRedFocus'); validate = true; }else{ $(documentEndoso).removeClass('inputRedFocus'); }
            var businessName = document.getElementById('businessName'); if(businessName.value === ''){ $(businessName).addClass('inputRedFocus'); validate = true; }else{ $(businessName).removeClass('inputRedFocus'); }
            var tradename = document.getElementById('tradename');
            var endorsementAmount = document.getElementById('endorsementAmount');
            if (endorsementAmount.value === '') {
                $(endorsementAmount).addClass('inputRedFocus');
                validate = true;
            } else {
                var endorsementValue = endorsementAmount.value.replace(/,/g, ''); 
                if (Number(endorsementValue) > Number(insuredValue)) {
                    $(endorsementAmount).addClass('inputRedFocus');
                    validate = true;
                    alert('El monto no puede ser mayor al monto asegurado (' + insuredValue + ')');
                } else {
                    $(endorsementAmount).removeClass('inputRedFocus');
                }
            }
            
            var newVehicleData = {
                'endosoSelect':1,
                'documentEndoso':documentEndoso.value,
                'businessName':businessName.value,
                'tradename':tradename.value,
                'endorsementAmount':endorsementAmount.value
            };
        }else{
            var newVehicleData = {
                'endosoSelect':'0',
                'documentEndoso':'',
                'businessName':'',
                'tradename':'',
                'endorsementAmount':''
            };
        }
            if ($('#uploaded_imageFactura').is(':empty')) {
                validate = true;
                var message = document.getElementById("messageFactura");
                $(message).css('display', 'block');
                $(message).html('Debe ingresar un archivo');
                $(message).addClass('alert alert-danger');
            }else{
                var message = document.getElementById("messageFactura");
                $(message).css('display', 'none');
                $(message).html('Debe ingresar un archivo');
                $(message).addClass('alert alert-danger');

            }
            if ($('#uploaded_imageCarta').is(':empty')) {
                validate = true;
                var message = document.getElementById("messageCarta");
                $(message).css('display', 'block');
                $(message).html('Debe ingresar un archivo');
                $(message).addClass('alert alert-danger');
            }else{
                var message = document.getElementById("messageCarta");
                $(message).css('display', 'none');
                $(message).html('Debe ingresar un archivo');
                $(message).addClass('alert alert-danger');

            }
        
    }else{
         var newVehicleData = {};
    }
    if (validate == false) {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: ROUTE + '/sales/emit/r1/store',
            type: "POST",
            data: {_token: CSRF_TOKEN,newVehicle:newVehicle, newVehicleData: newVehicleData, beginDate:beginDate.value, endDate:endDate.value, saleId:saleId.value, insuranceBranch: insuranceBranch.value},
            beforeSend: function () {
                var loaderGif = document.getElementById("loaderGif");
                loaderGif.className += "loaderGif";
            },
            success: function (data)
            {
               window.location.href = ROUTE + "/sales";
            },
            complete: function () {
                var loaderGif = document.getElementById("loaderGif");
                loaderGif.classList.remove("loaderGif");
            }
        });
    }
}

function changeEndDate(value){
    var res = value.split("-");
    var newYear = Number(res[0]) + Number(1);
    document.getElementById('endDate').value = newYear+'-'+res[1]+'-'+res[2];
}

function endosoSelectChange(value){
    var endosoDiv = document.getElementById("endosoDiv");
    var adjuntosFullDiv = document.getElementById("adjuntosFullDiv");
    if(value == 1){
        $(endosoDiv).fadeIn();
    }else{
        $(endosoDiv).fadeOut();
    }
}

function vehiModal(id){
    event.preventDefault();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: ROUTE + '/vehi/emit/modal',
        type: "POST",
        data: {_token: CSRF_TOKEN, id:id},
        beforeSend: function () {
            var loaderGif = document.getElementById("loaderGif");
            loaderGif.className += "loaderGif";
        },
        success: function (data)
        {
           document.getElementById("vehiModalEditModalBody").innerHTML = data; 
           document.getElementById("vehiModalEditBtn").click();
        },
        complete: function () {
            var loaderGif = document.getElementById("loaderGif");
            loaderGif.classList.remove("loaderGif");
        }
    });
}
