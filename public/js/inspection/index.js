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
            url: ROUTE + "/inspection/fetch_data?page=" + page,
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
        document.getElementById("salesId").value = "";
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
//        var form = document.getElementById('formConfirm');
        var url2 = ROUTE + "/inspection/upload";
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var form = $('#formConfirm')[0];
        data = new FormData(form);
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: url2,
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            beforeSend: function () {
                // Show Loader
                $("#loaderGif").addClass('loaderGif');
            },
            success: function (data)
            {
                if (data.Success === "true") {
                    window.location.href = ROUTE + "/inspection";
                } else {
                    alert(data.message);
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

function modalConfirmVehiBtn() {
    event.preventDefault();
    var validate = false;
    
    //Validate Data
    var registration = document.getElementById('registration');
    if (registration.value === '') {
        $(registration).addClass('inputRedFocus');
        validate = true;
    } else {
        $(registration).removeClass('inputRedFocus');
    }
    var chassis = document.getElementById('chassis');
    if (chassis.value === '') {
        $(chassis).addClass('inputRedFocus');
        validate = true;
    } else {
        $(chassis).removeClass('inputRedFocus');
    }
    var year = document.getElementById('year');
    if (year.value === '') {
        $(year).addClass('inputRedFocus');
        validate = true;
    } else {
        if (isNaN(year.value)) {
            $(year).addClass('inputRedFocus');
            validate = true;
        } else {
            $(year).removeClass('inputRedFocus');
        }
    }
    var color = document.getElementById('color');
    if (color.value === '') {
        $(color).addClass('inputRedFocus');
        validate = true;
    } else {
        $(color).removeClass('inputRedFocus');
    }
    var npassengers = document.getElementById('npassengers');
    if (npassengers.value === '') {
        $(npassengers).addClass('inputRedFocus');
        validate = true;
    } else {
        $(npassengers).removeClass('inputRedFocus');
    }
    var tonnage = document.getElementById('tonnage');
    if (tonnage.value === '') {
        $(tonnage).addClass('inputRedFocus');
        validate = true;
    } else {
        $(tonnage).removeClass('inputRedFocus');
    }
    var vehicleCylinder = document.getElementById('vehicleCylinder');
    if (vehicleCylinder.value === '') {
        $(vehicleCylinder).addClass('inputRedFocus');
        validate = true;
    } else {
        $(vehicleCylinder).removeClass('inputRedFocus');
    }
    var country = document.getElementById('country');
    if (country.value === '') {
        $(country).addClass('inputRedFocus');
        validate = true;
    } else {
        $(country).removeClass('inputRedFocus');
    }
    var vehicleSecurity = document.getElementById('vehicleSecurity');
    if (vehicleSecurity.value === '') {
        $(vehicleSecurity).addClass('inputRedFocus');
        validate = true;
    } else {
        $(vehicleSecurity).removeClass('inputRedFocus');
    }
    
    var vehiId = document.getElementById('vehiId').value;
    var vehiSalId = document.getElementById('vehiSalId').value;
    if (validate === false) {
        console.log('entro');
        var form = document.getElementById('formConfirmVehicle');
        var url2 = ROUTE + "/inspection/vehi/update";
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
                window.location.href = ROUTE + "/inspection";
//                if (data.Success === "ftrue") {
//                    window.location.href = ROUTE + "/inspection";
//                } else {
//                    var confirmModalError = document.getElementById('confirmModalVehiError');
//                    $(confirmModalError).removeClass('hidden');
//                }
            }
        });
    }else{
        console.log('no entro');
        
    }
}

function modalStatusBtn() {
    event.preventDefault();
    var validate = false;

    var today = new Date();
    today.setHours(0,0,0,0);
    date = document.getElementById('date')

    if(date.value == '')
    { 
        $(date).addClass('inputRedFocus'); 
        validate = true; 
    }

    var dateform = new Date(date.value);

    statusId = document.getElementById("statusId").value;
    var url = ROUTE + "/inspection/datecreate/" + statusId ;
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN},
        url: url,
        success: function (data)
        {
            var datecreate = new Date(data[0].created_at);
            if (today <= dateform) {
                alert("La fecha exede la fecha actual");
                $(date).addClass('inputRedFocus'); 
                validate = true; 
            } else if (datecreate > dateform) {
                alert("La fecha es inferior a la fecha de inspección");
                $(date).addClass('inputRedFocus'); 
                validate = true; 
            } else { 
                $(date).removeClass('inputRedFocus'); 
            }
        
            var statusModal = document.getElementById('statusModal'); if(statusModal.value == '' || statusModal == '0'){ $(statusModal).addClass('inputRedFocus'); validate = true; }else{ $(statusModal).removeClass('inputRedFocus'); }
            if (validate == false) {
                var url = ROUTE + "/inspection/confirm";
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    type: "POST",
                    data: {_token: CSRF_TOKEN, date:date.value, status:statusModal.value, inspectionId: statusId },
                    url: url,
                    success: function (data)
                    {
                        if(data.success === "true"){
                            window.location.href = ROUTE + "/inspection";
                        }else{
                            alert(data.msg);
                        }
                    }
                });
            }
        }
    });

}

Filevalidation = () => {
    const fi = document.getElementById('fileConfirm');
    // Check if any file is selected. 
    if (fi.files.length > 0) {
        for (var i = 0; i <= fi.files.length - 1; i++) {

            const fsize = fi.files.item(i).size;
            const file = Math.round((fsize / 1024));
            // The size of the file. 
            if (file >= 5000) {
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

function vehiForm(id){
    event.preventDefault();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = ROUTE + '/inspection/vehi/form';
    var data = {id: id};
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, data},
        url: url,
        success: function (data) {
            document.getElementById("modalVehiBody").innerHTML = data; 
            document.getElementById("modalVehiBtn").click();
        }
    });
}