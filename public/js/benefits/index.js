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
            url: ROUTE + "/benefits/fetch_data?page=" + page,
            beforeSend: function () {
                // Show Loader
                $("#loaderGif").addClass('loaderGif');
//                $("#loaderBody").addClass('loaderBody');
            },
            success: function (data)
            {
                $('#tableData').html(data);
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

    $("#plateModal").keyup(function () {
        var tableBenefits = document.getElementById("tableBenefits");
        tableBenefits.innerHTML = '';
        var modalBenefitsBtn = document.getElementById("modalBenefitsBtn");
        $(modalBenefitsBtn).addClass('hidden');
        var divSuccess = document.getElementById("divSuccess");
        $(divSuccess).addClass('hidden');
    });

    $('#btnClearBenefits').click(function () {
        document.getElementById("channel").value = "";
        document.getElementById("beginDate").value = "";
        document.getElementById("endDate").value = "";
        document.getElementById("status").value = "";
    });

});

function editModal(id) {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = ROUTE + '/benefits/edit/modal';
    var data = {id: id};
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, data},
        url: url,
        success: function (data) {
            document.getElementById('beginDateModal').value = data.beginDate;
            document.getElementById('endDateModal').value = data.endDate;
            document.getElementById('uses').value = data.uses;
            document.getElementById('benefitsId').value = data.benefitsId;
            var errorModalEdit = document.getElementById("errorModalCancel");
            $(errorModalEdit).addClass('hidden');
            document.getElementById("modalEditBtn").click();
        }
    });
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

function updateBenefit() {
    event.preventDefault();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = ROUTE + '/benefits/edit/store';
    var beginDate = document.getElementById("beginDateModal").value;
    var endDate = document.getElementById("endDateModal").value;
    var uses = document.getElementById("uses").value;
    var id = document.getElementById("benefitsId").value;
    var data = {beginDate: beginDate, endDate: endDate, uses: uses, id: id};
    var error = false;

    //Validate Data
    if (!beginDate) {
        error = true;
    }
    if (!endDate) {
        error = true;
    }
    if (!uses) {
        error = true;
    }

    //Validate Error
    if (error === false) {
        $.ajax({
            type: "POST",
            data: {_token: CSRF_TOKEN, data},
            url: url,
            success: function (data) {
                if (data.success === "true") {
                    window.location.href = ROUTE + "/benefits";
                } else {
                    var errorModalEdit = document.getElementById("errorModalEdit");
                    $(errorModalEdit).removeClass('hidden');
                }
            }
        });
    } else {
        var errorModalEdit = document.getElementById("errorModalEdit");
        $(errorModalEdit).removeClass('hidden');
    }
}

function cancelModal(id) {
    document.getElementById("benefitsIdCancel").value = id;
    var errorModalEdit = document.getElementById("errorModalCancel");
    $(errorModalEdit).addClass('hidden');
    document.getElementById("modalCancelBtn").click();
}

function cancelBenefit() {
    event.preventDefault();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = ROUTE + '/benefits/cancel/store';
    var cancelMotive = document.getElementById("cancelMotive").value;
    var benefitsIdCancel = document.getElementById("benefitsIdCancel").value;
    var data = {cancelMotive: cancelMotive, benefitsIdCancel: benefitsIdCancel};
    var error = false;

    //Validate Data
    if (!cancelMotive) {
        error = true;
    }

    //Validate Error
    if (error === false) {
        $.ajax({
            type: "POST",
            data: {_token: CSRF_TOKEN, data},
            url: url,
            success: function (data) {
                if (data.success === "true") {
                    window.location.href = ROUTE + "/benefits";
                } else {
                    var errorModalEdit = document.getElementById("errorModalCancel");
                    $(errorModalEdit).removeClass('hidden');
                }
            }
        });
    } else {
        var errorModalEdit = document.getElementById("errorModalCancel");
        $(errorModalEdit).removeClass('hidden');
    }
}

function plateBtn() {
    var divSuccess = document.getElementById("divSuccess");
    $(divSuccess).addClass('hidden');

    event.preventDefault();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = ROUTE + '/benefits/schedule/modal';
    var plateModal = document.getElementById("plateModal").value;
    var data = {plateModal: plateModal};
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, data},
        url: url,
        success: function (data) {
            if (data.success === "true") {
                var tableBenefits = document.getElementById("tableBenefits");
                tableBenefits.innerHTML = data.data;
                var modalBenefitsBtn = document.getElementById("modalBenefitsBtn");
                $(modalBenefitsBtn).addClass('hidden');
                var divError = document.getElementById("divError");
                $(divError).addClass('hidden');
            } else {
                var divError = document.getElementById("divError");
                $(divError).removeClass('hidden');
                var tableBenefits = document.getElementById("tableBenefits");
                tableBenefits.innerHTML = '';
            }
        }
    });
}

function checkValidate() {
    var atLeastOneIsChecked = $('.check').is(':checked');
    if (atLeastOneIsChecked) {
        var modalBenefitsBtn = document.getElementById("modalBenefitsBtn");
        $(modalBenefitsBtn).removeClass('hidden');
    } else {
        var modalBenefitsBtn = document.getElementById("modalBenefitsBtn");
        $(modalBenefitsBtn).addClass('hidden');
    }
}

function storeBenefitSchedule() {
    event.preventDefault();

    var bVsalId = [];

    $.each($("input[name='check']:checked"), function () {

        bVsalId.push($(this).val());

    });

//    console.log(benefits);
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = '/benefits/schedule/store';
    var data = {bVsalId: bVsalId};
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, data},
        url: url,
        success: function (data) {
            window.location.href = "/benefits";
        }
    });
}

function myModalAdd() {
    var tableBenefits = document.getElementById("tableBenefits");
    tableBenefits.innerHTML = '';
    document.getElementById("myModalAddBtn").click();
    document.getElementById("plateModal").value = '';
}
function val() {
    var beginDate = document.getElementById("beginDate").value;
    var endDate = document.getElementById("endDate").value;
    if (beginDate === '' && endDate === '') {
        return true;
    } else if (beginDate === '' && endDate !== '') {
        alert('Ingrese una Fecha Inicio');
        return false;
    } else if (beginDate !== '' && endDate === '') {
        alert('Ingrese una Fecha Fin');
        return false;
    } else {
        return true;
    }
}
