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
            url: ROUTE + "/agency/fetch_data?page=" + page,
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
});

function addIndividual(id) {
    event.preventDefault();
    var error = false;

    //Validate Data
    var address = document.getElementById("address"+id);
    var addressError = document.getElementById("addressError"+id);
    if (address.value === '') {
        error = true;
        addressError.innerHTML = 'La dirección es obligatoria';
    } else {
        addressError.innerHTML = '';
    }

    var name = document.getElementById("name"+id);
    var nameError = document.getElementById("nameError"+id);
    if (name.value === '') {
        error = true;
        nameError.innerHTML = 'El nombre es obligatorio';
    } else {
        nameError.innerHTML = '';
    }

    var province = document.getElementById("province"+id);
    var provinceError = document.getElementById("provinceError"+id);
    if (province.value === '') {
        error = true;
        provinceError.innerHTML = 'La provincia es obligatoria';
    } else {
        provinceError.innerHTML = '';
    }

    var city = document.getElementById("city"+id);
    var cityError = document.getElementById("cityError"+id);
    if (city.value === '') {
        error = true;
        cityError.innerHTML = 'La ciudad es obligatoria';
    } else {
        cityError.innerHTML = '';
    }

    var phone = document.getElementById("phone"+id);
    var phoneError = document.getElementById("phoneError"+id);
    if (phone.value === '') {
        error = true;
        phoneError.innerHTML = 'El telefono es obligatorio';
    } else if (isNaN(phone.value)) {
        error = true;
        phoneError.innerHTML = 'El telefono debe ser solo numerico';
    } else if (phone.value.length !== 9) {
        error = true;
        phoneError.innerHTML = 'El telefono debe tener 9 caracteres';
    } else {
        phoneError.innerHTML = '';
    }

    var contact = document.getElementById("contact"+id);
    var contactError = document.getElementById("contactError"+id);
    if (contact.value === '') {
        error = true;
        contactError.innerHTML = 'El contacto es obligatorio';
    } else {
        contactError.innerHTML = '';
    }

    var zip = document.getElementById("zip"+id);
    var zipError = document.getElementById("zipError"+id);
    if (zip.value === '') {
        zipError.innerHTML = '';
    } else if (isNaN(zip.value)) {
        error = true;
        zipError.innerHTML = 'El Código Postal debe ser numerico';
    } else if (zip.value.length !== 5) {
        error = true;
        zipError.innerHTML = 'El Código Postal debe tener 5 caracteres';
    } else {
        zipError.innerHTML = '';
    }

    var mobile_phone = document.getElementById("mobile_phone"+id);
    var mobile_phoneError = document.getElementById("mobilePhoneError"+id);
    if (mobile_phone.value === '') {
        error = true;
        mobile_phoneError.innerHTML = 'El Télefono Celular es obligatorio';
    } else if (isNaN(mobile_phone.value)) {
        error = true;
        mobile_phoneError.innerHTML = 'El Télefono Celular debe ser numerico';
    } else if (mobile_phone.value.length !== 10) {
        error = true;
        mobile_phoneError.innerHTML = 'El Télefono Celular debe tener 10 caracteres';
    } else {
        mobile_phoneError.innerHTML = '';
    }
    
    if(id === 2){
        var agencyId = document.getElementById("agencyId").value;
    }else{
        var agencyId = null;
    }

    //Validate Error
    if (error === false) {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var channelId = document.getElementById("channelId").value;
        var alertIndividual = document.getElementById('alertIndividual');
        $(alertIndividual).addClass('hidden');
        $.ajax({
            type: "POST",
            data: {_token: CSRF_TOKEN, address: address.value, name: name.value, province: province.value, city: city.value, phone: phone.value, contact: contact.value, zip: zip.value, mobile_phone: mobile_phone.value, channelId: channelId, id:id, agencyId: agencyId},
            url: ROUTE + "/agency/store",
            beforeSend: function () {
                // Show Loader
                $("#loaderGif").addClass('loaderGif');
            },
            success: function (data)
            {
                if (data === 'false') {
                    $(alertIndividual).removeClass('hidden');
                    alertIndividual.innerHTML = 'No puede repetir el nombre de la Agencia';
                } else {
                    document.getElementById("btnFilterForm").click();
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

function clearAddInvididual() {
    document.getElementById("address1").value = '';
    document.getElementById("addressError1").innerHTML = '';
    document.getElementById("name1").value = '';
    document.getElementById("nameError1").innerHTML = '';
    document.getElementById("province1").value = '';
    document.getElementById("provinceError1").innerHTML = '';
    document.getElementById("city1").value = '';
    document.getElementById("cityError1").innerHTML = '';
    document.getElementById("phone1").value = '';
    document.getElementById("phoneError1").innerHTML = '';
    document.getElementById("contact1").value = '';
    document.getElementById("contactError1").innerHTML = '';
    document.getElementById("zip1").value = '';
    document.getElementById("zipError1").innerHTML = '';
    document.getElementById("mobile_phone1").value = '';
    document.getElementById("mobilePhoneError1").innerHTML = '';
}

function clearAddExcel() {
    document.getElementById("file").value = null;
    var alertExcel = document.getElementById("alertExcel");
    $(alertExcel).addClass('hidden');
}

function validateUploadExcel() {
    var form = document.getElementById('uploadForm');
    event.preventDefault();
    var url2 = ROUTE + "/agency/validate/upload/excel";
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: url2,
        type: "POST",
        data: new FormData(form),
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function () {
            // Show Loader
            $("#loaderGif").addClass('loaderGif');
//                $("#loaderBody").addClass('loaderBody');
        },
        success: function (data)
        {
            if (data.success === 'false') {
                var alertExcel = document.getElementById('alertExcel');
                $(alertExcel).removeClass('hidden');
                alertExcel.innerHTML = data.name;
            } else if (data.success === 'true') {
                document.getElementById('btnFilterForm').click();
            }
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
;

function editAgency(id) {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, id:id},
        url: ROUTE + "/agency/edit",
        beforeSend: function () {
            // Show Loader
            $("#loaderGif").addClass('loaderGif');
        },
        success: function (data)
        {
            $('#editAgencyBody').html(data);
            document.getElementById("editAgencyBtn").click();
        },
        complete: function () {
            //Hide Loader
            var loaderGif = document.getElementById("loaderGif");
            loaderGif.classList.remove("loaderGif");
        }
    });
}

function editStoreAgency(){
    event.preventDefault();
}