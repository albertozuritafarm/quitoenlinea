/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {

    $(document).on('click', '.pagination a', function (event) {
        event.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        fetch_data(page);
    });

    function fetch_data(page)
    {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            type: "POST",
            data: {_token: CSRF_TOKEN},
            url: ROUTE + "/insurance/fetch_data?page=" + page,
            beforeSend: function () {
                // Show Loader
                $("#loaderGif").addClass('loaderGif');
//                $("#loaderBody").addClass('loaderBody');
            },
            success: function (data)
            {
                $('#tableData').html(data);

            },
            complete: function () {
                //Hide Loader
                var loaderGif = document.getElementById("loaderGif");
                loaderGif.classList.remove("loaderGif");
//                var loaderBody = document.getElementById("loaderBody");
//                loaderBody.classList.remove("loaderBody");
            }
        });
    }

    $('#btnClearBenefits').click(function () {
        document.getElementById("transId").value = "";
        document.getElementById("cusId").value = "";
        document.getElementById("first_name").value = "";
        document.getElementById("last_name").value = "";
        document.getElementById("status").value = "";
    });

    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();
    if (dd < 10) {
        dd = '0' + dd
    }
    if (mm < 10) {
        mm = '0' + mm
    }

    today = yyyy + '-' + mm + '-' + dd;
    document.getElementById("date").setAttribute("max", today);

});

function fileUpload(id) {
    document.getElementById("confirmId").value = id;
    document.getElementById("fileConfirm").value = '';
    var fileConfirm = document.getElementById("fileConfirm");
    var labelText = 'Seleccione un Archivo : ';
    $(fileConfirm).prev('label').text(labelText);
    var confirmModalError = document.getElementById('confirmModalError');
    $(confirmModalError).addClass('hidden');
    document.getElementById("modalConfirmBtn").click();
}

function confirmInspection(id) {
    document.getElementById("statusId").value = id;
    document.getElementById("statusModal").value = '';
    document.getElementById("date").value = '';
    document.getElementById("modalStatusBtn").click();
}

function beginDateChange() {
    var beginDate = document.getElementById("beginDate").value;
    var endDate = document.getElementById("endDate").value;
    if (endDate < beginDate) {
        alert('La fecha Fin no puede ser menor a la fecha de Inicio');
        document.getElementById("endDate").value = '';
    }
}

function endDateChange() {
    var beginDate = document.getElementById("beginDate").value;
    var endDate = document.getElementById("endDate").value;
    if (beginDate === '') {
        alert('Por favor introduza una fecha de Inicio');
        document.getElementById("endDate").value = '';
    } else if (endDate < beginDate) {
        alert('La fecha Fin no puede ser menor a la fecha de Inicio');
        document.getElementById("endDate").value = '';
    }
}

function editCustomer(id) {
    console.log(id);
    document.getElementById("customerId").value = id;
    document.getElementById("customerBtn").click();
}

function fileValidation() {
    console.log('hola');
}

function modalConfirmBtn() {
    event.preventDefault();
    var confirmId = document.getElementById('confirmId').value;
    var fileConfirm = document.getElementById('fileConfirm').value;
    console.log(confirmId + ' - ' + fileConfirm);
    if (fileConfirm !== '') {
        var form = document.getElementById('formConfirm');
        var url2 = ROUTE + "/inspection/upload";
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
                if (data.Success === "true") {
                    window.location.href = ROUTE + "/inspection";
                } else {
                    var confirmModalError = document.getElementById('confirmModalError');
                    $(confirmModalError).removeClass('hidden');
                }
            }
        });
    }
}

function modalStatusBtn() {
    event.preventDefault();
    var confirmId = document.getElementById('confirmId').value;
    var fileConfirm = document.getElementById('fileConfirm').value;
    console.log(confirmId + ' - ' + fileConfirm);
    if (fileConfirm !== '') {
        var form = document.getElementById('formConfirmStatus');
        var url2 = ROUTE + "/inspection/confirm";
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
//                if(data.Success === "true"){
//                    window.location.href = ROUTE + "/inspection";
//                }else{
//                    alert(data.msg);
//                }
            }
        });
    }
}

Filevalidation = () => {
    const fi = document.getElementById('fileConfirm');
    // Check if any file is selected. 
    if (fi.files.length > 0) {
        for (const i = 0; i <= fi.files.length - 1; i++) {

            const fsize = fi.files.item(i).size;
            const file = Math.round((fsize / 1024));
            // The size of the file. 
            if (file >= 2000) {
                document.getElementById('fileConfirm').value = '';
                alert("El archivo debe pesar menos de 2 mb");
            } else if (file < 1) {
                document.getElementById('fileConfirm').value = '';
                alert("File too small, please select a file greater than 2mb");
            }
        }
    }
}

function salesResumeTable(id) {
    event.preventDefault();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = ROUTE + '/sales/modal/resume';
    var data = {id: id};
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, data},
        url: url,
        success: function (data) {
            var modalBody = document.getElementById('modalBodySaleResume');
            modalBody.innerHTML = data;
            document.getElementById("modalBtnClickResume").click();
        }
    });
}

function validateCode(id){
    document.getElementById('insuranceId').value = id;
    document.getElementById("validateCodeBtn").click();
}

function validateCodeModal(){
    event.preventDefault();
    var insuranceId = document.getElementById("insuranceId").value;
    var validationCode = document.getElementById("validation_code").value;
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = ROUTE + '/insurance/validate/code';
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, insuranceId: insuranceId, validationCode: validationCode},
        url: url,
        success: function (data) {
            if(data == 'false'){
                alert('El codigo ingresado es incorrecto');
            }else{
                window.location.href = ROUTE + '/insurance';
            }
        },
        error: function () {
            return "Hello";
        }
    });
}

function resendCode(){
    event.preventDefault();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var insuranceId = document.getElementById("insuranceId").value;
    var url = ROUTE + '/insurance/resend/code';
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, insuranceId: insuranceId},
        url: url,
        success: function (data) {
            alert('Se envio un nuevo codigo de validacion');
        },
        error: function () {
            return "Hello";
        }
    });
}

function cancelInsurance(id){
    event.preventDefault();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = ROUTE + '/insurance/cancel';
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, insuranceId: id},
        url: url,
        success: function (data) {
            alert('Se realizo la cancelacion');
            window.location.href = ROUTE + '/insurance';
        },
        error: function () {
            return "Hello";
        }
    });
}