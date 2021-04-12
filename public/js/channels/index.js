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
            url: ROUTE + "/channel/fetch_data?page=" + page,
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

    $("#plateModal").keyup(function () {
        var tableBenefits = document.getElementById("tableBenefits");
        tableBenefits.innerHTML = '';
        var modalBenefitsBtn = document.getElementById("modalBenefitsBtn");
        $(modalBenefitsBtn).addClass('hidden');
        var divSuccess = document.getElementById("divSuccess");
        $(divSuccess).addClass('hidden');
    });

    $('#btnClearBenefits').click(function () {
        document.getElementById("id").value = "";
        document.getElementById("name").value = "";
    });

});

function channelResume(id) {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, id: id},
        url: ROUTE + "/channel/resume",
        success: function (data)
        {
            var modalResumeBody = document.getElementById("modalResumeBody");
            modalResumeBody.innerHTML = data;
            document.getElementById("modalAgencyResume").click();
            var table = $('#tableChannelResume2').DataTable({
                "searching": false,
                "pagination": false,
                "paging": true,
                "ordering": false,
                "info": true, "pageLength": 5,
                "bLengthChange": false,
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

function addAgencyIndividual(id){
    document.getElementById("channelId").value = id;
    document.getElementById("agencyBtn").click();
}

function editChannel(id){
    document.getElementById("channelEditId").value = id;
    document.getElementById("channelEditBtn").click();
}

function productChannelSS(){
    event.preventDefault;
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN},
        url: ROUTE + "/channel/productChannelSS",
        beforeSend: function () {
            // Show Loader
            $("#loaderGif").addClass('loaderGif');
        },
        success: function (data)
        {
            window.location.href = ROUTE + "/channel";
        },
        complete: function () {
            //Hide Loader
            var loaderGif = document.getElementById("loaderGif");
            loaderGif.classList.remove("loaderGif");
        }
    });
}