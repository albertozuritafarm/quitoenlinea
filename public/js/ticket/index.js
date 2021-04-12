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
            url: ROUTE + "/ticket/fetch_data?page=" + page,
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
    $('#btnClearTickets').click(function () {
        document.getElementById("number").value = "";
        document.getElementById("firstName").value = "";
        document.getElementById("lastName").value = "";
        document.getElementById("status").value = "";
        document.getElementById("beginDate").value = "";
        document.getElementById("endDate").value = "";
        document.getElementById("menu").value = "";
        document.getElementById("ticketType").value = "";
    });
});

function ticketDetail(id) {
    document.getElementById('ticketsId').value = id;
    document.getElementById('ticketsBtn').click();
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