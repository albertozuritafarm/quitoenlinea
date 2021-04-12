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

function addIndividual() {
    event.preventDefault();
    var error = false;

    //Validate Data
    var address = document.getElementById("address");
    var addressError = document.getElementById("addressError");
    if (address.value === '') { error = true; addressError.innerHTML = 'La dirección es obligatoria'; } else { addressError.innerHTML = ''; }

    var name = document.getElementById("name");
    var nameError = document.getElementById("nameError");
    if (name.value === '') { error = true; nameError.innerHTML = 'El nombre es obligatorio'; } else { nameError.innerHTML = ''; }

    var province = document.getElementById("province");
    var provinceError = document.getElementById("provinceError");
    if (province.value === '') { error = true; provinceError.innerHTML = 'La provincia es obligatoria'; } else { provinceError.innerHTML = ''; }

    var city = document.getElementById("city");
    var cityError = document.getElementById("cityError");
    if (city.value === '') { error = true; cityError.innerHTML = 'La ciudad es obligatoria'; } else { cityError.innerHTML = ''; }

    var phone = document.getElementById("phone");
    var phoneError = document.getElementById("phoneError");
    if (phone.value === '') { error = true; phoneError.innerHTML = 'El telefono es obligatorio'; } else if (isNaN(phone.value)) { error = true; phoneError.innerHTML = 'El telefono debe ser solo numerico'; } else if (phone.value.length !== 9) { error = true; phoneError.innerHTML = 'El telefono debe tener 9 caracteres'; } else { phoneError.innerHTML = ''; }

    var contact = document.getElementById("contact");
    var contactError = document.getElementById("contactError");
    if (contact.value === '') { error = true; contactError.innerHTML = 'El contacto es obligatorio'; } else { contactError.innerHTML = ''; }

    var mobile_phone = document.getElementById("mobile_phone");
    var mobile_phoneError = document.getElementById("mobilePhoneError");
    if (mobile_phone.value === '') { error = true; mobile_phoneError.innerHTML = 'El Télefono Celular es obligatorio'; } else if (isNaN(mobile_phone.value)) { error = true; mobile_phoneError.innerHTML = 'El Télefono Celular debe ser numerico'; } else if (mobile_phone.value.length !== 10) { error = true; mobile_phoneError.innerHTML = 'El Télefono Celular debe tener 10 caracteres'; } else { mobile_phoneError.innerHTML = ''; }
    
    //Validate Error
    if(error === false){
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var providersId = document.getElementById("providersId").value;
        var alertIndividual = document.getElementById('alertIndividual');
        $(alertIndividual).addClass('hidden');
        $.ajax({
            type: "POST",
            data: {_token: CSRF_TOKEN, address: address.value, name: name.value, province: province.value, city: city.value, phone: phone.value, contact: contact.value, mobile_phone: mobile_phone.value, providersId: providersId},
            url: ROUTE + "/branch/store",
            beforeSend: function () {
                // Show Loader
                $("#loaderGif").addClass('loaderGif');
            },
            success: function (data)
            {
                if(data === 'false'){
                    $(alertIndividual).removeClass('hidden');
                    alertIndividual.innerHTML = 'No puede repetir el nombre de la Sucursal';
                }else{
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

function clearAddInvididual(){
    document.getElementById("address").value = '';
    document.getElementById("addressError").innerHTML = '';
    document.getElementById("name").value = '';
    document.getElementById("nameError").innerHTML = '';
    document.getElementById("province").value = '';
    document.getElementById("provinceError").innerHTML = '';
    document.getElementById("city").value = '';
    document.getElementById("cityError").innerHTML = '';
    document.getElementById("phone").value = '';
    document.getElementById("phoneError").innerHTML = '';
    document.getElementById("contact").value = '';
    document.getElementById("contactError").innerHTML = '';
    document.getElementById("mobile_phone").value = '';
    document.getElementById("mobilePhoneError").innerHTML = '';
    $('#alertIndividual').addClass('hidden');
}

function clearAddExcel(){
    document.getElementById("file").value = null;
    var alertExcel = document.getElementById("alertExcel");
    $(alertExcel).addClass('hidden');
}

function validateUploadExcel() {
    var form = document.getElementById('uploadForm');
    event.preventDefault();
    var url2 = ROUTE + "/branch/validate/upload/excel";
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
            if(data.success === 'false'){
                var alertExcel = document.getElementById('alertExcel');
                $(alertExcel).removeClass('hidden');
                alertExcel.innerHTML = data.name;
            }else if(data.success === 'true'){
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
};