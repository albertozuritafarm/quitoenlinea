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
            url: ROUTE + "/charges/fetch_data?page=" + page,
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
    
    
    $('#btnClearSales').click(function () {
        document.getElementById("document").value = "";
        document.getElementById("date").value = "";
        document.getElementById("saleId").value = "";
        document.getElementById("payment_type").value = "";
        document.getElementById("charge_type").value = "";
        document.getElementById("status").value = "";
    });

    //Anule Sales button
    $('#btnAnuleSales').click(function () {
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

    // the selector will match all input controls of type :checkbox
// and attach a click event handler 
    $("input:checkbox").on('click', function () {
        // in the handler, 'this' refers to the box clicked on
        var $box = $(this);
        if ($box.is(":checked")) {
            // the name of the box is retrieved using the .attr() method
            // as it is assumed and expected to be immutable
            var group = "input:checkbox[name='" + $box.attr("name") + "']";
            // the checked state of the group/box on the other hand will change
            // and the current value is retrieved using .prop() method
            $(group).prop("checked", false);
            $box.prop("checked", true);
        } else {
            $box.prop("checked", false);
        }
    });
});

function paymentsModal(id) {

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var data = {id};
    var url = ROUTE + '/payments/modal';
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, data},
        url: url,
        success: function (data) {
//            console.log(data);
            var modalBodyPayment = document.getElementById("formBody");
            modalBodyPayment.innerHTML = data;
            document.getElementById("modalBtnClickPayment").click();
        }
    });
}
function submitPaymentForm() {
    event.preventDefault();
//    var salId = document.getElementById("salId").value;
    var number = document.getElementById("number").value;
    var chargeId = document.getElementById("chargeId").value;
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var radioValue = $("input[name='option']:checked").val();
    var data = {chargeId: chargeId, number: number, option: radioValue};
    var url = ROUTE + '/payments/modal/store';
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, data},
        url: url,
        beforeSend: function () {
            // Show Loader
            $("#loaderGif").addClass('loaderGif');
//                $("#loaderBody").addClass('loaderBody');
        },
        success: function (data) {
            console.log(data.date);
            var modalBodyPayment = document.getElementById("resultMessage");
            modalBodyPayment.innerHTML = data.message;
            if (data.success == 'true') {
                console.log(data.date);
                var date = new Date(data.date);
//                console.log((date.getMonth() + 1) + '/' + date.getDate() + '/' +  date.getFullYear());
                var dateFormat = ((date.getFullYear()) + '-0' + (date.getMonth() + 1) + '-0' + date.getDate());
                //Disable Submit Btn
                var cashBtn = document.getElementById("cashBtn");
                $(cashBtn).addClass('hidden');

                //Disabled DataTable Btn
                var Btn = 'paymentsBtn' + data.id;
                var paymentsBtn = document.getElementById(Btn);
                $(paymentsBtn).attr('disabled', 'disabled');
                $(paymentsBtn).prop('disabled', true);
                $(paymentsBtn).addClass('no-drop');
                $(paymentsBtn).attr('onclick', '');

                //Update Status
                var status = 'statusTable' + data.id;
                var statusTable = document.getElementById(status);
                statusTable.innerHTML = '';
                statusTable.innerHTML += 'Pagada';

                //Update date
                var date = 'dateTable' + data.id;
                var dateTable = document.getElementById(date);
                dateTable.innerHTML = '';
                dateTable.innerHTML += dateFormat;

                //Update Payment Type
                var typeTable = 'typeTable' + data.id;
                var typeTable = document.getElementById(typeTable);
                typeTable.innerHTML = '';
                typeTable.innerHTML += data.paymentType;

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

function validateModalForm() {
    var r = confirm("¿Seguro que desea realizar el pago de esta Nota de credito?.");
    if (r == true) {
        submitPaymentForm();
    } else {
    }
}
function cashDivClick() {
    //Activate Cash Div
    var cash = document.getElementById("cash");
    $(cash).removeClass('wizard_inactivo');
    $(cash).addClass('wizard_activo');
    var cashDiv = document.getElementById("cashDiv");
    $(cashDiv).removeClass('hidden');
    $("#cashRadioBtn").prop("checked", true);

    //Inactivate datafast Div
    var datafast = document.getElementById("datafast");
    $(datafast).removeClass('wizard_activo');
    $(datafast).addClass('wizard_inactivo');
    var datafastDiv = document.getElementById("datafastDiv");
    $(datafastDiv).addClass('hidden');
    $("#datafastRadioBtn").prop("checked", false);
}
;
function datafastDivClick() {
    //Inactivate Cash Div
    var cash = document.getElementById("cash");
    $(cash).removeClass('wizard_activo');
    $(cash).addClass('wizard_inactivo');
    var cashDiv = document.getElementById("cashDiv");
    $(cashDiv).addClass('hidden');
    $("#cashRadioBtn").prop("checked", false);

    //Activate datafast Div
    var datafast = document.getElementById("datafast");
    $(datafast).removeClass('wizard_inactivo');
    $(datafast).addClass('wizard_activo');
    var datafastDiv = document.getElementById("datafastDiv");
    $(datafastDiv).removeClass('hidden');
    $("#datafastRadioBtn").prop("checked", true);
}
;

function paymentsModalResume(id) {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var data = {id:id};
    var url = ROUTE + '/sales/modal/resume';
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, data},
        url: url,
        success: function (data) {
            console.log(data);
            var modalBodyPayment = document.getElementById("formBody");
            modalBodyPayment.innerHTML = data;
            document.getElementById("modalBtnClickPayment").click();
        }
    });
};

function payment(id) {
    event.preventDefault();
    var storeSalId = document.getElementById("chargeId");
    storeSalId.value = id;
    document.getElementById("formBtn").click();
}

function sendPaymentLink(chargesId) {
    event.preventDefault();
    var r = confirm("¿Seguro desea enviar el link del Botón de Pago?");
    if (r == true) {
        document.getElementById('chargeId').value = chargesId;
        document.getElementById('formBtn').click();
    } else {
    }
}