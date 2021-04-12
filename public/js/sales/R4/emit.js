/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


    
    
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
    


function clearForm() {
    var documentForm = document.getElementById("document");
    $(documentForm).removeClass('inputRedFocus');
    var first_name = document.getElementById("first_name");
    first_name.value = '';
    $(first_name).removeClass('inputRedFocus');
    var document_id = document.getElementById("document_id");
    document_id.value = '0';
    $(document_id).removeClass('inputRedFocus');
    var last_name = document.getElementById("last_name");
    last_name.value = '';
    $(last_name).removeClass('inputRedFocus');
    var porcentage = document.getElementById("porcentage");
    porcentage.value = '';
    $(porcentage).removeClass('inputRedFocus');
    document.getElementById("first_name").disabled = true;
    document.getElementById("last_name").disabled = true;
    document.getElementById("document_id").disabled = true;
    $('#beneficiaryTable tr').each(function (row, tr) {
        $(tr).find('td:eq(0)').removeClass('borderRedFocus');
    });
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

function uploadPictureForm() {
    event.preventDefault();
    var form = document.getElementById("upload_form");
    var url = ROUTE + "/sales/emit/upload";
    $.ajax({
        url: url,
        type: "POST",
        data: new FormData(form),
        contentType: false,
        cache: false,
        processData: false,
        success: function (data)
        {
            var message = '#message';
            $(message).css('display', 'none');
            var uploaded_image = '#uploaded_image';
            $(uploaded_image).html(data.uploaded_image);
            var deletePicture = 'deletePicture';
            var uploadPicture = 'upload';
            if (data.Success == 'true') {
                var uploadPic = document.getElementById("select_file");
                $(uploadPic).addClass('hidden');
                var deletePic = document.getElementById(deletePicture);
                $(deletePic).removeClass('hidden');
                $(deletePic).addClass('visible');
                var uploadPic = document.getElementById(uploadPicture);
                $(uploadPic).removeClass('visible');
                $(uploadPic).addClass('hidden');
                var fileName = document.getElementById('fileName');
                $(fileName).removeClass('visible');
                $(fileName).addClass('hidden');
                fileName.innerHTML = '';
            } else {
                $(message).css('display', 'block');
                $(message).html(data.message);
                $(message).addClass(data.class_name);
            }
        }
    });
}

function deletePictureForm() {
    event.preventDefault();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = ROUTE + '/sales/emit/delete';
    var saleId = document.getElementById("saleId");
    var data = {saleId: saleId.value};
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, data},
        url: url,
        success: function (data) {
            var uploaded_imageFront = document.getElementById("uploaded_image");
            $(uploaded_imageFront).html('');
            var uploadPic = document.getElementById("select_file");
            $(uploadPic).addClass('visible');
            $(uploadPic).removeClass('hidden');
            var deletePic = document.getElementById("deletePicture");
            $(deletePic).removeClass('visible');
            $(deletePic).addClass('hidden');
            var uploadPic = document.getElementById("upload");
            $(uploadPic).removeClass('hidden');
            $(uploadPic).addClass('visible');
            var fileName = document.getElementById('fileName');
            $(fileName).removeClass('hidden');
            $(fileName).addClass('visible');
            fileName.innerHTML = '';
        }
    });
}

function fileNameFunction() {
    var file = document.getElementById('select_file').files[0];
    var uploadPic = document.getElementById("fileName");
    uploadPic.innerHTML = file.name;

}

function validateSecondStep() {
    event.preventDefault();
    var txt;
    var r = confirm('¿Seguro que desea realizar el pago y la emisión?');
    if (r === false) {
        return false;
    }
    var validate = false;
    var endosoSelect = document.getElementById("endosoSelect");
    
    if(endosoSelect.value === ''){
        $(endosoSelect).addClass('inputRedFocus');
        return false;
    }else{
        $(endosoSelect).removeClass('inputRedFocus');
    }
    
    var insuredValue = document.getElementById("insuredValue").value;
    var beginDate = document.getElementById("beginDate"); if (beginDate.value === '') { validate = true; $(beginDate).addClass('inputRedFocus'); }
    var endDate = document.getElementById("endDate"); if (endDate.value === '') { validate = true; $(endDate).addClass('inputRedFocus'); }
    
    if(endosoSelect.value === '0'){ //NO
        var newVehicleData = {
                'endosoSelect':0,
                'documentEndoso':'',
                'businessName':'',
                'tradename':'',
                'endorsementAmount':''
            };
    }else{ //SI
        console.log('no entro');
        var documentEndoso = document.getElementById('document'); if(documentEndoso.value === ''){ $(documentEndoso).addClass('inputRedFocus'); validate = true; }else{ $(documentEndoso).removeClass('inputRedFocus'); }
        var businessName = document.getElementById('businessName'); if(businessName.value === ''){ $(businessName).addClass('inputRedFocus'); validate = true; }else{ $(businessName).removeClass('inputRedFocus'); }
        var tradename = document.getElementById('tradename');
        var endorsementAmount = document.getElementById('endorsementAmount');
        if (endorsementAmount.value === '') {
            $(endorsementAmount).addClass('inputRedFocus');
            validate = true;
        } else {
                var endorsementValue = endorsementAmount.value.replace(/,/g, ''); 
            if(Number(endorsementValue) > Number(insuredValue)){
                $(endorsementAmount).addClass('inputRedFocus');
                validate = true;
                alert('El monto no puede ser mayor al monto asegurado (' + insuredValue +')');
            }else{
                $(endorsementAmount).removeClass('inputRedFocus');
            }
        };
        var newVehicleData = {
                'endosoSelect':1,
                'documentEndoso':documentEndoso.value,
                'businessName':businessName.value,
                'tradename':tradename.value,
                'endorsementAmount':endorsementAmount.value
            };
    }    

    if (validate === false) {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var saleId = document.getElementById("saleId");
        var url = ROUTE + '/sales/emit/r4/store';
        $.ajax({
            url: url,
            type: "POST",
            /* send the csrf-token and the input to the controller */
            data: {_token: CSRF_TOKEN, beginDate: beginDate.value, endDate: endDate.value, saleId: saleId.value, newVehicleData: newVehicleData},
            beforeSend: function () {
                var loaderGif = document.getElementById("loaderGif");
                loaderGif.className += "loaderGif";
            },
            success: function (result) {
                window.location.href = ROUTE + "/sales";
            },
        complete: function () {
            var loaderGif = document.getElementById("loaderGif");
            loaderGif.classList.remove("loaderGif");
        }
        });
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
    var documentForm = document.getElementById('document');
    $(documentForm).removeClass('inputRedFocus');
    var businessName = document.getElementById('businessName');
    $(businessName).removeClass('inputRedFocus');
    var tradeName = document.getElementById('tradename');
    $(tradeName).removeClass('inputRedFocus');
    var endorsementAmount = document.getElementById('endorsementAmount');
    $(endorsementAmount).removeClass('inputRedFocus');
    document.getElementById('businessName').value = '';
    document.getElementById('tradename').value = '';
    document.getElementById('endorsementAmount').value = '';
}

function removeInputRedFocus(id) {
    var id = document.getElementById(id);
    $(id).removeClass('inputRedFocus');
}

function clearSecondStepForm() {
    var beginDate = document.getElementById("beginDate");
    $(beginDate).removeClass('inputRedFocus');
    var endDate = document.getElementById("endDate");
    $(endDate).removeClass('inputRedFocus');
    var message = document.getElementById("message");
    $(message).css('display', 'none');
    $(message).html('');
    $(message).removeClass('alert alert-danger');
}

function formAutoFill(val) {
    var documentNumber = val;
    var url = ROUTE + '/customer/document/autofill/' + documentNumber;
    $.ajax({
        url: url,
        type: "GET",
        dataType: "json",
        success: function (data) {
            if (data.success == 'true') {
                document.getElementById("first_name").disabled = true;
                document.getElementById("last_name").disabled = true;
                document.getElementById("document_id").disabled = true;

                $("#beneficiaryForm").autofill(data);
            } else {
                document.getElementById("first_name").disabled = false;
                document.getElementById("last_name").disabled = false;
                document.getElementById("document_id").disabled = false;
            }
        }
    });
}

function addBeneficiary() {
    event.preventDefault();

    //Variables
    var documentForm = document.getElementById("document");
    var document_id = document.getElementById("document_id");
    var first_name = document.getElementById("first_name");
    var last_name = document.getElementById("last_name");
    var porcentage = document.getElementById("porcentage");
    var validate = false;

    //Validation
    if (documentForm.value === "") { $(documentForm).addClass('inputRedFocus'); validate = true; } else { $(documentForm).removeClass('inputRedFocus'); }
    if (document_id.value === "0") { $(document_id).addClass('inputRedFocus'); validate = true; } else { $(document_id).removeClass('inputRedFocus'); }
    if (first_name.value === "") { $(first_name).addClass('inputRedFocus'); validate = true; } else { $(first_name).removeClass('inputRedFocus'); }
    if (last_name.value === "") { $(last_name).addClass('inputRedFocus'); validate = true; } else { $(last_name).removeClass('inputRedFocus'); }
    if (porcentage.value === "" || porcentage.value > 100 || porcentage.value === "0") { $(porcentage).addClass('inputRedFocus'); validate = true; } else { $(porcentage).removeClass('inputRedFocus'); }

    //Beneficiary Table Data
    var value = 0;
    var TableData = new Array();
    $('#beneficiaryTable tr').each(function (row, tr) {
        TableData += [$(tr).find('td:eq(4)').text()];
        value += Number(formatNumber($(tr).find('td:eq(4)').text()));
    });

    //Validate Beneficiary Table Data
    value += Number(formatNumber(porcentage.value));
    if (value > 100) {
        validate = true;
        alert('El porcentaje no debe ser mayor a 100%');
    }

    //Add Row
    if (validate === false) {
        addRow(documentForm, document_id, first_name, last_name, porcentage);
    }
}

function addRow(documentForm, document_id, first_name, last_name, porcentage) {
    var bodyTable = document.getElementById("beneficiaryBodyTable");

    var rowCount = bodyTable.rows.length;
    var row = bodyTable.insertRow(rowCount);

    //Validate Document id Name
    if (document_id.value == 1) {
        var documentName = 'Cedula';
    }
    if (document_id.value == 2) {
        var documentName = 'RUC';
    }
    if (document_id.value == 3) {
        var documentName = 'PASAPORTE';
    }
    if (document_id.value == 4) {
        var documentName = 'VISA 12 IV';
    }

    row.insertCell(0).innerHTML = documentForm.value;
    row.insertCell(1).innerHTML = documentName;
    row.insertCell(2).innerHTML = first_name.value;
    row.insertCell(3).innerHTML = last_name.value;
    row.insertCell(4).innerHTML = porcentage.value;
    row.insertCell(5).innerHTML = '<button type="submit" class="btn btn-link" onClick="Javacsript:editRow(\'' + documentForm.value + '\',\'' + document_id.value + '\', \'' + first_name.value + '\', \'' + last_name.value + '\', \'' + porcentage.value + '\',this)"><span class="glyphicon glyphicon-pencil" style="color:green;font-size:18px"></span></button>';
    row.insertCell(6).innerHTML = '<button type="submit" class="btn btn-link" onClick="Javacsript:deleteRow(this)"><span class="glyphicon glyphicon-remove" style="color:red;font-size:18px"></span></button>';

    //Return Inputs no Null
    documentForm.value = '';
    document_id.value = '0';
    first_name.value = '';
    last_name.value = '';
    porcentage.value = '';

    //Disable Inputs
    $('#document_id').prop('disabled', true);
    $('#first_name').prop('disabled', true);
    $('#last_name').prop('disabled', true);
}

function deleteRow(obj) {
    var index = obj.parentNode.parentNode.rowIndex;
    var table = document.getElementById("beneficiaryTable");
    table.deleteRow(index);
}

function editRow(documentForm, document_id, first_name, last_name, porcentage, obj) {
    //Fill Form
    var documentFormSubmit = document.getElementById("document");
    documentFormSubmit.value = documentForm;
    var document_idSubmit = document.getElementById("document_id");
    document_idSubmit.value = document_id;
    var first_nameSubmit = document.getElementById("first_name");
    first_nameSubmit.value = first_name;
    var last_nameSubmit = document.getElementById("last_name");
    last_nameSubmit.value = last_name;
    var porcentageSubmit = document.getElementById("porcentage");
    porcentageSubmit.value = porcentage;
    //Validate if Customer exist
    var url = ROUTE + '/customer/document/autofill/' + documentForm;
    $.ajax({
        url: url,
        type: "GET",
        dataType: "json",
        success: function (data) {
            if (data.success == 'true') {
                document.getElementById("first_name").disabled = true;
                document.getElementById("last_name").disabled = true;
                document.getElementById("document_id").disabled = true;

                $("#beneficiaryForm").autofill(data);
            } else {
                document.getElementById("first_name").disabled = false;
                document.getElementById("last_name").disabled = false;
                document.getElementById("document_id").disabled = false;
            }
        }
    });
    deleteRow(obj);
}

function documentIdChange(id) {
    if (id == 1) { //CEDULA
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var data = {document: document.getElementById("document").value};
        var url = ROUTE + '/sales/validateDocument';
        $.ajax({
            type: "POST",
            data: {_token: CSRF_TOKEN, data},
            url: url,
            async: false,
            success: function (data) {
                if (data === 'invalid') {
                    $("#suggesstion-box").hide();
                    document.getElementById("first_name").value = "";
                    document.getElementById("document_id").value = "0";
                    document.getElementById("last_name").value = "";
                    document.getElementById("porcentage").value = "";
                    alert('El documento ingresado es invalido');
                }
            }
        });
    }
    if (id == 2) { //RUC
        var documentForm = document.getElementById("document").value;
        if (isNaN(documentForm) || documentForm.length != 13) {
            $("#suggesstion-box").hide();
            document.getElementById("first_name").value = "";
            document.getElementById("document_id").value = "0";
            document.getElementById("last_name").value = "";
            document.getElementById("porcentage").value = "";
            alert('El documento ingresado es invalido');
        }
    }
}

function validateBeneficiary() {
    var validate = false;

    $('#beneficiaryTable tr').each(function (row, tr) {
        if (document.getElementById('document').value === $(tr).find('td:eq(0)').text()) {
            validate = true;
            $(tr).find('td:eq(0)').addClass('borderRedFocus');
        }else{
            $(tr).find('td:eq(0)').removeClass('borderRedFocus');
        }
    });

    if (validate === false) { documentBtn(); } 
}

function emitStore() {
    event.preventDefault();
    var validate = false;

    //Beneficiary Table Data
    var value = 0;
    var TableData = new Array();
    $('#beneficiaryBodyTable tr').each(function (row, tr) {
        TableData[row] = { "document": $(tr).find('td:eq(0)').text() , "type": $(tr).find('td:eq(1)').text() , "first_name": $(tr).find('td:eq(2)').text() , "last_name": $(tr).find('td:eq(3)').text() , "porcentage": $(tr).find('td:eq(4)').text() };
        value += Number(formatNumber($(tr).find('td:eq(4)').text()));
    });

    //Validate Beneficiary Table Data
    var table = document.getElementById("beneficiaryTable");
    var rowCountTable = table.rows.length;
    if (rowCountTable > 1) { if (value != 100) { alert('Los porcentages deben sumar 100%'); validate = true; return false; } }

    //Validate Image
//    if ($('#uploaded_image').is(':empty')) { validate = true; var message = document.getElementById("message"); $(message).css('display', 'block'); $(message).html('Debe ingresar una imagen'); $(message).addClass('alert alert-danger'); }

    //Validate Dates
    var beginDate = document.getElementById("beginDate"); if(beginDate.value === ''){ $(beginDate).addClass("inputRedFocus"); validate = true; } else { $(beginDate).removeClass("inputRedFocus"); }
    var endDate = document.getElementById("endDate"); if(endDate.value === ''){ $(endDate).addClass("inputRedFocus"); validate = true; }else { $(endDate).removeClass("inputRedFocus"); }
    var fileConfirm = document.getElementById("fileConfirm"); if(fileConfirm.value === ''){ document.getElementById("fileError").innerHTML = 'Debe seleccionar un archivo.'; validate = true; }else{ document.getElementById("fileError").innerHTML = ''; }

    //Emit Store
    if (validate === false) {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var saleId = document.getElementById("saleId");
        var insuranceBranch = document.getElementById("insuranceBranch");
        var form = document.getElementById("emitForm");
        var url = ROUTE + '/sales/emit/store';
        $.ajax({
             url: url,
            type: "POST",
            data: new FormData(form),
            contentType: false,
            cache: false,
            processData: false,
            success: function (result) {
                window.location.href = ROUTE + "/sales";
            }
        });
    }
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

function previous(saleId, customerId){
    event.preventDefault();
    var url = ROUTE + '/sales/emit/'+saleId+'/'+customerId+'/1';
    var next = '#emitStep';
    loadNextPage(url,next,'right','left');
}

function formatNumber(str){ 
    var res = str.replace(",", ".");
    return res;
}

function clearInputFile(e){
    event.preventDefault();
    document.getElementById("fileConfirm").value = "";
    document.getElementById('fileLabel').innerHTML = 'Archivo :';
}

function beginDateChange(){
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var beginDate = document.getElementById("beginDate");
    $(beginDate).removeClass('inputRedFocus');
    var url = ROUTE + '/sales/emit/endDate';
    $.ajax({
        url: url,
        type: "POST",
        /* send the csrf-token and the input to the controller */
        data: {_token: CSRF_TOKEN, beginDate: beginDate.value},
        success: function (result) {
            console.log(result);
            document.getElementById("endDate").value = result;
        }
    });
}

function uploadFile(){
    var form = document.getElementById('fileForm');
    var url = ROUTE + '/sales/emit/upload';
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
            console.log(data);
        }
    });
}

function changeEndDate(value){
    var res = value.split("-");
    var newYear = Number(res[0] + 1);
    document.getElementById('endDate').value = newYear+'-'+res[1]+'-'+res[2];
}

function endosoSelectChange(value){
    var endosoDiv = document.getElementById("endosoDiv");
    if(value == 1){
        $(endosoDiv).fadeIn();
    }else{
        $(endosoDiv).fadeOut();
    }
}