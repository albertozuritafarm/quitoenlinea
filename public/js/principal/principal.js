var FACTOR_DECIMAL = 10000;

$.datepicker.regional['es'] = {
    closeText: 'Cerrar',
    prevText: '< Ant',
    nextText: 'Sig >',
    currentText: 'Hoy',
    monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
    dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
    dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
    dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
    weekHeader: 'Sm',
    dateFormat: 'dd/mm/yy',
    firstDay: 1,
    isRTL: false,
    showMonthAfterYear: false,
    yearSuffix: ''};
$.datepicker.setDefaults($.datepicker.regional['es']);

$('.dropdown').on('show.bs.dropdown', function () {
    $(this).find('.dropdown-menu').first().stop(true, true).slideDown();
});
$('.dropdown').on('hide.bs.dropdown', function () {
    $(this).find('.dropdown-menu').first().stop(true, true).slideUp();
});

$('.hamburger, #LCerrarMenu').click(function () {
    hamburger_cross();
});
$('[data-toggle="offcanvas"]').click(function ()
{
    $('#wrapper').toggleClass('toggled');
});



$("#Panel_Salir").click(function () {
    window.location = ROUTE + "/logout";
});

//$(document).ready(function() {
$(document).ready(function () {
//	AutomaticoMenu();

    $('input[type=text]').on('keyup', function () {
        if (/[^a-zA-Z0-9 %-]/.test(this.value)) {
            alert('No puede ingresar Caracteres Especiales');
            var str = this.value;
            var newStr = str.substring(0, str.length - 1);
            this.value = newStr;
            this.focus();
        }
        this.value = this.value.toLocaleUpperCase();
    });

    var table = $('#tableUsers').DataTable({
        "searching": false,
        "pagination": false,
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
    var table = $('#ticketDetailTable').DataTable({
        "searching": false,
        "pagination": true,
        "paging": true,
        "ordering": false,
        "info": true,
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
    var table = $('#newPaginatedTableNoOrdering').DataTable({
        "searching": false,
        "pagination": false,
        "paging": false,
        "ordering": false,
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
//    $('#tableSche').DataTable();
    var tableProducts = $('#tableProducts').DataTable({
        "searching": false,
        "rowCallback": function (row, data, index) {
            if (index % 2 == 0) {
                $(row).removeClass('rowSelected');
            } else {
                $(row).addClass('rowSelected');
            }
        },
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
        }, "columnDefs": {

            className: "dt-head-center"
        }

    });

    $('#tableUsers tbody').on('click', 'tr', function () {
        if ($(this).hasClass('selectedTableProducts')) {
            $(this).removeClass('selectedTableProducts');
        } else {
            table.$('tr.selectedTableProducts').removeClass('selectedTableProducts');
            $(this).addClass('selectedTableProducts');
        }
    });
    //Hide/Show Filter Form
    var div = document.getElementById('filter');
    $(div).fadeOut();
    $('#filterButton').click(function () {
        var element = document.getElementById("filter");
        if(element.style.display === 'none'){ document.getElementById("filterImg").src = ROUTE + '/images/nofilter.png'; }else{ document.getElementById("filterImg").src = ROUTE + '/images/filter.png'; }
        $(element).fadeToggle(200);
   
//        if ($(element).hasClass('hidden')) {
//            $(element).removeClass('hidden');
//            $(element).fadeIn('slow');
//            document.getElementById("filterImg").src = ROUTE + '/images/nofilter.png';
//        } else {
//            $(element).addClass('hidden');
//            $(element).fadeOut('slow');
//            document.getElementById("filterImg").src = ROUTE + '/images/filter.png';
//        }
    });
    $('#btnCancel').click(function () {
        var element = document.getElementById("filter");
        if(element.style.display === 'none'){ document.getElementById("filterImg").src = ROUTE + '/images/nofilter.png'; }else{ document.getElementById("filterImg").src = ROUTE + '/images/filter.png'; }
        $(element).fadeToggle(200);
//        if ($(element).hasClass('hidden')) {
//            $(element).removeClass('hidden');
//            document.getElementById("filterImg").src = ROUTE + '/images/nofilter.png';
//        } else {
//            $(element).addClass('hidden');
//            document.getElementById("filterImg").src = ROUTE + '/images/filter.png';
//        }
    });
    var element = document.getElementById("tableUsers_length");
    $(element).addClass('border');
    $(element).addClass('floatRight');
    $(element).addClass('form-group');
    $(element).addClass('tableSearch');
    $(".sorting_desc").css("background-image", "");

    var tableUsers = document.getElementById("tableUsers_wrapper");
    $(tableUsers).addClass('tableUsersMargin');

    var paginationUsers = document.getElementById("tableUsers_paginate");
    $(paginationUsers).addClass('paginationsUsersMargin');

    var ticketDetailTable_length = document.getElementById("ticketDetailTable_length");
    $(ticketDetailTable_length).addClass('hidden');

    $('#btnClear').click(function () {
        document.getElementById("first_name").value = "";
        document.getElementById("last_name").value = "";
        document.getElementById("document").value = "";
        document.getElementById("email").value = "";
        document.getElementById("rol").value = "0";
        document.getElementById("channel").value = "0";
        document.getElementById("agency").value = "0";
    });

    $('select[name="tableUsers_length"]').addClass('form-control');
    $('select[name="tableUsers_length"]').addClass('selectSearch');

    var tableBorder = document.getElementById("tableUsers_wrapper");
//    $(tableBorder).removeClass('tableUsersMargin');
    var tableBorder2 = document.getElementById("tableUsers");
    $(tableBorder2).addClass('borderTable');
    var tablePagination = document.getElementById("tableUsers_previous");
    $(tablePagination).addClass('paginationFont');
    var tablePagination = document.getElementById("tableUsers_next");
    $(tablePagination).addClass('paginationFont');

//    window.setTimeout(function() {
//        $(".alert").addClass('hidden');
//    }, 4000);

    var tableShowRecords = document.getElementsByName("tableUsers_length");
    $(tableShowRecords).addClass('tableShowRecords');

//$('ul.nav li.dropdown a').click(function () {
//        event.preventDefault();
//        $('ul.nav li.dropdown a .dropdown-menu').toggleClass('menuCustom');
//      });

});

function loadNextPage(url, next, hideFrom = 'left', showFrom = 'right')
{
//    $(next).load(url,function(){}).fadeOut(1000).fadeIn(1000);
    $(next).fadeOut(500,function(){
        $(next).load(url, function(){
            $(next).fadeIn(500);
        });
    });
//    $(next).hide("slide", { direction: hideFrom }, 1000,function(){
//        $(next).load(url, function(){
//            $(next).show("slide", { direction: showFrom }, 1000);
//        });
//    });
}
function ValidateEmail(mail)
{
    if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail))
    {
        document.getElementById("emailError").innerHTML = '';
        return (true);
    } else {
        var txt = 'Por favor ingrese un correo valido';
        document.getElementById("emailError").innerHTML = txt;
        return (false);
    }
}

function roundNumber(num, scale) {
  if(!("" + num).includes("e")) {
    return +(Math.round(num + "e+" + scale)  + "e-" + scale);
  } else {
    var arr = ("" + num).split("e");
    var sig = ""
    if(+arr[1] + scale > 0) {
      sig = "+";
    }
    return +(Math.round(+arr[0] + "e" + sig + (+arr[1] + scale)) + "e-" + scale);
  }
}

