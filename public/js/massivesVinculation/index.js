/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {
    $('.checkSaleId').click(function() {
        $('.checkSaleId').not(this).prop('checked', false);
    });

    $('.datepicker').datepicker({
        default: false,
        format: 'dd/mm/yyyy'
    });
    
    $(document).on('click', '.pagination a', function (event) {
        event.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        fetch_data(page);
    });

    $('#reloadTableBtn').click(function () {
        event.preventDefault();
        var currentPage = document.getElementById("currentPage").value;
        fetch_data(currentPage);
    });

    function fetch_data(page)
    {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            type: "POST",
            data: {_token: CSRF_TOKEN},
            url: ROUTE + "/massivesVinculation/fetch_data?page=" + page,
            beforeSend: function () {
                // Show Loader
                $("#loaderGif").addClass('loaderGif');
//                $("#loaderBody").addClass('loaderBody');
            },
            success: function (data)
            {
                var tableData = document.getElementById("tableData");
                tableData.innerHTML = data;
                var table = $('#newPaginatedTable').DataTable({
                    "searching": false,
                    "pagination": false,
                    "paging": false,
                    "ordering": true,
                    "info": false,
                    "order": [[0, "desc"]],
                    "language": {
                        "sProcessing": "Procesando...",
                        "sLengthMenu": "Mostrar   _MENU_   registros",
                        "sZeroRecords": "No se encontraron resultados",
                        "sEmptyTable": "Ningún dato disponible en esta tabla",
                        "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                        "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                        "sInfoPostFix": "",
                        "sSearch": "Buscar:",
                        "sUrl": "",
                        "sInfoThousands": ",",
                        "sLoadingRecords": "Cargando...",
                        "oPaginate": {
                            "sFirst": "Primero",
                            "sLast": "Último",
                            "sNext": "Siguiente",
                            "sPrevious": "Anterior"
                        },
                        "oAria": {
                            "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                        }
                    }
                });
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

    $("#checkAll").click(function () {

        checkboxes = document.getElementsByName('saleId');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            if (checkboxes[i].checked === true) {
                checkboxes[i].setAttribute("checked", ""); // For IE
                checkboxes[i].removeAttribute("checked"); // For other browsers
                checkboxes[i].checked = false;
            } else {
//                checkboxes[i].checked = $('input:checkbox').not(this).prop('checked', this.checked);
                checkboxes[i].setAttribute("checked", "checked"); // For IE
                checkboxes[i].checked = true;
            }
        }
    });

    //Clear Filters
    $('#btnClearSales').click(function () {
        document.getElementById("customer").value = "";
        document.getElementById("document").value = "";
        document.getElementById("dateFrom").value = ""
        document.getElementById("dateUntil").value = "";
        document.getElementById("updateDateFrom").value = ""
        document.getElementById("updateDateUntil").value = "";
        document.getElementById("status").value = "";
        document.getElementById("nameBusiness").value = "";
        document.getElementById("channel").value = "";
    });
    //Anule Sales button
    $('#btnAnuleSales').click(function () {
        //Se debe esperar por Seguros Sucre
        return false;
        var sales = [];

        $.each($("input[name='saleId']:checked"), function () {

            sales.push($(this).val());

        });

        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var data = {sales: sales};
        var url = ROUTE + '/sales/annulment';
        $.ajax({
            type: "POST",
            data: {_token: CSRF_TOKEN, data},
            url: url,
            success: function (data) {
                if (data['error'].length > 0) {
                    var annulmentDivError = document.getElementById("annulmentDivError");
                    $(annulmentDivError).removeClass('hidden');
                    $(annulmentDivError).addClass('visible');
                    var annulmentDivSuccess = document.getElementById("annulmentDivSuccess");
                    $(annulmentDivSuccess).removeClass('visble');
                    $(annulmentDivSuccess).addClass('hidden');
                    var txt = '&ensp;La(s) venta(s) ' + data['error'] + ' no cumple(n) los requerimientos para ser anulada(s).';
                    document.getElementById("annulmentMsgError").innerHTML = txt;
                } else {
                    var annulmentDiv = document.getElementById("annulmentDivSuccess");
                    $(annulmentDiv).removeClass('hidden');
                    $(annulmentDiv).addClass('visible');
                    var annulmentDivError = document.getElementById("annulmentDivError");
                    $(annulmentDivError).removeClass('visible');
                    $(annulmentDivError).addClass('hidden');
                    var txt = '&ensp;La(s) venta(s) fueron anulada(s) correctamente.';
                    document.getElementById("annulmentMsgSuccess").innerHTML = txt;
                }
                data['success'].forEach(function (data, index) {
                    var id = 'statusTable' + data.id;
                    console.log(id);
                    var div = document.getElementById(id);

                    div.innerHTML = '';
                    div.innerHTML += 'Anulada';
                });
            }
        });
    });
    //Renew Sales button
    $('#btnRenewSales').click(function () {
        var sales = [];

        $.each($("input[name='saleId']:checked"), function () {

            sales.push($(this).val());

        });

        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var data = {sales: sales};
        var url = ROUTE + '/sales/renew';
        $.ajax({
            type: "POST",
            data: {_token: CSRF_TOKEN, data},
            url: url,
            success: function (data) {
//                console.log(data);
                window.location.href = "sales";
//                if (data['error'].length > 0) {
//                    var annulmentDivError = document.getElementById("annulmentDivError");
//                    $(annulmentDivError).removeClass('hidden');
//                    $(annulmentDivError).addClass('visible');
//                    var annulmentDivSuccess = document.getElementById("annulmentDivSuccess");
//                    $(annulmentDivSuccess).removeClass('visble');
//                    $(annulmentDivSuccess).addClass('hidden');
//                    var txt = '&ensp;La(s) venta(s) ' + data['error'] + ' no cumple(n) los requerimientos para ser anulada(s).';
//                    document.getElementById("annulmentMsgError").innerHTML = txt;
//                } else {
//                    var annulmentDiv = document.getElementById("annulmentDivSuccess");
//                    $(annulmentDiv).removeClass('hidden');
//                    $(annulmentDiv).addClass('visible');
//                    var annulmentDivError = document.getElementById("annulmentDivError");
//                    $(annulmentDivError).removeClass('visible');
//                    $(annulmentDivError).addClass('hidden');
//                    var txt = '&ensp;La(s) venta(s) fueron renovadas(s) correctamente.';
//                    document.getElementById("annulmentMsgSuccess").innerHTML = txt;
//                }
//                data['success'].forEach(function (data, index) {
//                    var id = 'statusTable' + data.id;
//                    var div = document.getElementById(id);
//
//                    div.innerHTML = '';
//                    div.innerHTML += 'Renovada';
//                });
            }
        });
    });

//Load Vehicles Images



//    window.setTimeout(function() {
//        $(".alert").addClass('hidden');
//    }, 4000);

//    function fileNameFunction() {
//        console.log('entro');
//    }

    $('#first_name').on('keyup', function () {
        if (/[^a-zA-Z ]/.test(this.value)) {
            alert('No puede ingresar Caracteres Especiales');
            var str = this.value;
            var newStr = str.substring(0, str.length - 1);
            this.value = newStr;
            this.focus();
        }
        this.value = this.value.toLocaleUpperCase();

    });
    $('#last_name').on('keyup', function () {
        if (/[^a-zA-Z ]/.test(this.value)) {
            alert('No puede ingresar Caracteres Especiales');
            var str = this.value;
            var newStr = str.substring(0, str.length - 1);
            this.value = newStr;
            this.focus();
        }
        this.value = this.value.toLocaleUpperCase();

    });
    $('#document').on('keyup', function () {
        if (/[^a-zA-Z0-9]/.test(this.value)) {
            alert('No puede ingresar Caracteres Especiales');
            var str = this.value;
            var newStr = str.substring(0, str.length - 1);
            this.value = newStr;
            this.focus();
        }
        this.value = this.value.toLocaleUpperCase();

    });
    $('#plate').on('keyup', function () {
        if (/[^a-zA-Z0-9]/.test(this.value)) {
            alert('No puede ingresar Caracteres Especiales');
            var str = this.value;
            var newStr = str.substring(0, str.length - 1);
            this.value = newStr;
            this.focus();
        }
        this.value = this.value.toLocaleUpperCase();

    });


});    
//$('#upload_formFront{{($vehicle->id)}}').on('submit', function (event) {
function uploadPictureForm(id, side) {
    var form = document.getElementById(id);
    event.preventDefault();
    var url2 = ROUTE + "/ajax_upload/action/" + side;
    var url = "{{ asset('" + url2 + "')}}";
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
            var message = '#message' + side + data.vSalId;
            var uploaded_image = '#uploaded_image' + side + data.vSalId;
            var messageResponse = 'message' + side + data.vSalId;
            var upload_imageResponse = 'upload_image' + side + data.vSalId;
            $(uploaded_image).html(data.uploaded_image);
            var deletePicture = 'deletePicture' + side + data.vSalId;
            var uploadPicture = 'upload' + side + data.vSalId;
            if (data.Success == 'true') {
                var uploadPic = document.getElementById("select_file" + side + data.vSalId);
                $(uploadPic).addClass('hidden');
                var deletePic = document.getElementById(deletePicture);
                $(deletePic).removeClass('hidden');
                $(deletePic).addClass('visible');
                var uploadPic = document.getElementById(uploadPicture);
                $(uploadPic).removeClass('visible');
                $(uploadPic).addClass('hidden');
                var fileName = document.getElementById('fileName' + side + data.vSalId);
                $(fileName).removeClass('visible');
                $(fileName).addClass('hidden');
                fileName.innerHTML = '';
                if (data.Active == 'YES') {
                    var statusTable = document.getElementById('statusTable' + data.salesId);
                    statusTable.innerHTML = '';
                    statusTable.innerHTML += 'Activo';
                }
            } else {
                $(message).css('display', 'block');
                $(message).html(data.message);
                $(message).addClass(data.class_name);
            }
        }
    });
}

//$('#deletePictureBack{{($vehicle->id)}}').click(function () {
function deletePictureForm(id, side) {
//    var deleteId = document.getElementById(id).value;
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = ROUTE + '/ajax_upload/delete/' + side;
    var data = {id: id};
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, data},
        url: url,
        success: function (data) {
            var uploaded_imageFront = document.getElementById("uploaded_image" + side + id);
            $(uploaded_imageFront).html('');
            var uploadPic = document.getElementById("select_file" + side + id);
            $(uploadPic).addClass('visible');
            $(uploadPic).removeClass('hidden');
            var deletePic = document.getElementById("deletePicture" + side + id);
            $(deletePic).removeClass('visible');
            $(deletePic).addClass('hidden');
            var uploadPic = document.getElementById("upload" + side + id);
            $(uploadPic).removeClass('hidden');
            $(uploadPic).addClass('visible');
            var fileName = document.getElementById('fileName' + side + id);
            $(fileName).removeClass('hidden');
            $(fileName).addClass('visible');
            fileName.innerHTML = '';
            var statusTable = document.getElementById('statusTable' + data.vSalId);
            statusTable.innerHTML = '';
            statusTable.innerHTML += 'Pendiente Fotos';
        }
    });
}
;

function loadPicturesModal(id) {
    event.preventDefault();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = ROUTE + '/vehicles/modal/pictures';
    var data = {id: id};
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, data},
        url: url,
        success: function (data) {
            var modalBody = document.getElementById('modalBody');
            modalBody.innerHTML = data;
            document.getElementById("modalBtnClickPictures").click();
        }
    });
}
;

//$('#select_fileFront{{($vehicle->id)}}').change(function (e) {
function fileNameFunction(id, side) {
    var file = document.getElementById('select_file' + side + id).files[0];
    var uploadPic = document.getElementById("fileName" + side + id);
    uploadPic.innerHTML = file.name;

}
;

function salesResumeTable(id) {
    event.preventDefault();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = ROUTE + '/sales/modal/resumeMassives';
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
function validateCodeModal(id) {
    var modalBody = document.getElementById('validationCode');
    modalBody.innerHTML = '<input type="hidden" id="salId" name="salId" value="' + id + '">';
    var uploadPic = document.getElementById("resultMessage");
    uploadPic.innerHTML = '';
    //Hide validate Button
    var validateCodeBtn = document.getElementById('validateCodeBtn');
    $(validateCodeBtn).css('display', 'block');
    //Hide Resend Code Button
    var resendCodeBtn = document.getElementById('resendCodeBtn');
    $(resendCodeBtn).css('display', 'block');
    var resendCodeBtn = document.getElementById('code');
    $(resendCodeBtn).val('');
    document.getElementById("modalBtnClickActivate").click();
}
function validateCode() {
    event.preventDefault();
    var form = document.getElementById('validateCodeForm');
    var url = ROUTE + "/sales/activate";
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
            var uploadPic = document.getElementById("resultMessage");
            uploadPic.innerHTML = data.data;
            if (data.success == 'true') {
                //Update Sales Status
                var id = 'statusTable' + data.id;
                var div = document.getElementById(id);
                div.innerHTML = '';
                div.innerHTML += 'Nueva';
                //Hide validate Button
                var validateCodeBtn = document.getElementById('validateCodeBtn');
                $(validateCodeBtn).css('display', 'none');
                //Hide Resend Code Button
                var resendCodeBtn = document.getElementById('resendCodeBtn');
                $(resendCodeBtn).css('display', 'none');
                //Disabled Btn
                var validateCodeOpenModal = document.getElementById('validateCodeOpenModal');
                $(validateCodeOpenModal).attr('disabled', 'disabled');
                $(validateCodeOpenModal).prop('disabled', true);
                $(validateCodeOpenModal).addClass('no-drop');
                $(validateCodeOpenModal).attr('onclick', '');
            }
        }
    })
}
function resendCode() {
    event.preventDefault();
    var url = ROUTE + "/sales/resend/code";
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var salId = document.getElementById('salId').value;
//    var data = {code: salId};
    $.ajax({
        url: url,
        type: "POST",
        data: {_token: CSRF_TOKEN, salId},
        success: function (data)
        {
            var uploadPic = document.getElementById("resultMessage");
            uploadPic.innerHTML = data;
        }
    });
}
;

function deleteSale(id) {
    event.preventDefault();
    var url = ROUTE + "/sales/delete";
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
//    var salId = document.getElementById('salId').value;
//    var data = {code: salId};
    $.ajax({
        url: url,
        type: "POST",
        data: {_token: CSRF_TOKEN, id},
        success: function (data)
        {
            window.location.href = ROUTE + "/sales";
        }
    });
}

function selectAll() {
    $('input:checkbox').not(this).prop('checked', this.checked);
}

function selectOnlyThis(id) {
//    var myCheckbox = document.getElementsByName("saleId");
//    Array.prototype.forEach.call(myCheckbox,function(el){
//        el.checked = false;
//    });
//    id.checked = true;
}

function renewBtn(){
    event.preventDefault();
    var sales = [];
    $.each($("input[name='saleId']:checked"), function () {
        sales.push($(this).val());
    });
    if(sales == ''){
        alert('Por favor seleccione una venta a Renovar');
    }else{
        var url = ROUTE + "/sales/encrypt";
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: url,
            type: "POST",
            data: {_token: CSRF_TOKEN, saleId: sales},
            success: function (data)
            {
                window.location.href = ROUTE + "/sales/renovate/"+data;
            }
        });
    }
}

function makePayment(id){
    document.getElementById('chargeId').value = id;
    document.getElementById("formBtn").click();
}

function inspection(id){
    event.preventDefault();
    var r = confirm("¿Seguro que desea realizar la inspección?");
    if (r == true) {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var url = ROUTE + "/inspection/create";
        $.ajax({
            url: url,
            type: "POST",
            data: {_token: CSRF_TOKEN, saleId: id},
            success: function (data)
            {
                window.location.href = ROUTE + "/sales";
            }
        });
    } 
}

function validateListaObservados(id, urlSale) {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = ROUTE + '/massivesVinculation/validate/lista';
    $.ajax({
        url: url,
        type: "POST",
        /* send the csrf-token and the input to the controller */
        data: {_token: CSRF_TOKEN, saleId: id},
        beforeSend: function () {
            // Show Loader
            $("#loaderGif").addClass('loaderGif');
        },
        success: function (result) {
            if(result == 'true'){
                window.location.href = ROUTE + "/massivesVinculation";
            }else{
                window.location.href = urlSale;
            }
        },
        complete: function () {
            //Hide Loader
            var loaderGif = document.getElementById("loaderGif");
            loaderGif.classList.remove("loaderGif");
        }
    });
}

function sendVinculationFormLink(saleId){
    event.preventDefault();
    var r = confirm("¿Seguro que desea enviar el link?");
    if (r == true) {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var url = ROUTE + '/vinculation/send/link';
        $.ajax({
            url: url,
            type: "POST",
            /* send the csrf-token and the input to the controller */
            data: {_token: CSRF_TOKEN, saleId:saleId},
            success: function (result) {
                alert('Se ha enviado un correo con el enlace al Formulario de Vinculación');
            }
        });
    }
}

function openValidatePayerModal(id){
    event.preventDefault();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = ROUTE + '/customer/obtain/data/sale';
    $.ajax({
        url: url,
        type: "POST",
        /* send the csrf-token and the input to the controller */
        data: {_token: CSRF_TOKEN, saleId:id},
        success: function (result) {
            console.log(result);
            $("#customerForm").autofill(result.customer);
            document.getElementById("modalBtnClickValidatePayer").click();
        }
    });
}

function nextStep(div1, div2) {
    event.preventDefault();
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

function sendPaymentLink(chargesId){
    event.preventDefault();
    var r = confirm("¿Seguro desea enviar el link del Botón de Pago?");
    if (r == true) {
        document.getElementById('chargeId').value = chargesId;
        document.getElementById('formBtn').click();
    } else {
    } 
}
