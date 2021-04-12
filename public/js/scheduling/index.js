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
            url: ROUTE + "/scheduling/fetch_data?page=" + page,
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
    $('#btnClearScheduling').click(function () {
        document.getElementById("plate").value = "";
        document.getElementById("beginDate").value = "";
        document.getElementById("endDate").value = "";
        document.getElementById("first_name").value = "";
        document.getElementById("last_name").value = "";
        document.getElementById("document").value = "";
        document.getElementById("status").value = "";
    });
    $('#first_name').on('keyup', function(){
        if( /[^a-zA-Z ]/.test( this.value ) ) {
            alert('No puede ingresar Caracteres Especiales');
            var str= this.value;
            var newStr = str.substring(0, str.length - 1);
            this.value=newStr;
            this.focus();
        }
        this.value = this.value.toLocaleUpperCase();

    });
    $('#last_name').on('keyup', function(){
        if( /[^a-zA-Z ]/.test( this.value ) ) {
            alert('No puede ingresar Caracteres Especiales');
            var str= this.value;
            var newStr = str.substring(0, str.length - 1);
            this.value=newStr;
            this.focus();
        }
        this.value = this.value.toLocaleUpperCase();

    });
    $('#document').on('keyup', function(){
        if( /[^a-zA-Z0-9]/.test( this.value ) ) {
            alert('No puede ingresar Caracteres Especiales');
            var str= this.value;
            var newStr = str.substring(0, str.length - 1);
            this.value=newStr;
            this.focus();
        }
        this.value = this.value.toLocaleUpperCase();

    });
    $('#plate').on('keyup', function(){
        if( /[^a-zA-Z0-9]/.test( this.value ) ) {
            alert('No puede ingresar Caracteres Especiales');
            var str= this.value;
            var newStr = str.substring(0, str.length - 1);
            this.value=newStr;
            this.focus();
        }
        this.value = this.value.toLocaleUpperCase();

    });
});
function schedulingModalResume(id) {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = ROUTE + '/scheduling/modal/resume';
    var data = {id: id};
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, data},
        url: url,
        success: function (data) {
            var modalBody = document.getElementById('modalBody');
            modalBody.innerHTML = data;
            document.getElementById("modalResumeBtn").click();
        }
    });
}

function confirmAction(id) {
    document.getElementById("confirmId").value = id;
    document.getElementById("fileConfirm").value = '';
    var fileConfirm = document.getElementById("fileConfirm");
    var labelText = 'Seleccione un Archivo : ';
    $(fileConfirm).prev('label').text(labelText);
    var confirmModalError = document.getElementById('confirmModalError');
    $(confirmModalError).addClass('hidden');     
    document.getElementById("modalConfirmBtn").click();
//    var r = confirm("¿Seguro que desea confirmar el agendamiento?");
//    if (r === true) {
//        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
//        var url = '/scheduling/confirm';
//        $.ajax({
//            url: url,
//            type: "POST",
//            /* send the csrf-token and the input to the controller */
//            data: {_token: CSRF_TOKEN, id: id},
//            dataType: 'JSON',
//            success: function (data) {
//                var confirmMessage = document.getElementById('confirmMessage');
//                $(confirmMessage).removeClass('hidden');
//                var confirmBtn = document.getElementById('confirmBtn' + id);
//                $(confirmBtn).attr('disabled', 'disabled');
//                $(confirmBtn).prop('disabled', true);
//                $(confirmBtn).addClass('no-drop');
//                $(confirmBtn).attr('onclick', '');
//                var status = document.getElementById('status' + id);
//                status.innerHTML = 'Efectuado';
//            }
//        });
//    }
}

function cancel(id) {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = ROUTE + '/scheduling/modal/cancel';
    $.ajax({
        url: url,
        type: "POST",
        data: {_token: CSRF_TOKEN, id: id},
        dataType: 'JSON',
        success: function (data) {
            if (data.success === 'true') {
                var modalBodyCancel = document.getElementById('modalBodyCancel');
                modalBodyCancel.innerHTML = data.data;
                document.getElementById("modalCancelBtn").click();
            }
        },
        error: function (request, status, error) {
            alert(request.responseText);
        }
    });
}

function cancelBtn() {
    //VARIABLES
    var schedulingDetailId = document.getElementById('schedulingDetailId').value;
    var cancelMotive = document.getElementById('cancelMotive').value;
    if (cancelMotive === '') {
        //SHOW ERROR
        var cancelModalError = document.getElementById('cancelModalError');
        $(cancelModalError).removeClass('hidden');
    } else {
        //HIDE ERROR
        var cancelModalError = document.getElementById('cancelModalError');
        $(cancelModalError).addClass('hidden');

        //AJAX CALL
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var url = ROUTE + '/scheduling/store/cancel';
        $.ajax({
            url: url,
            type: "POST",
            /* send the csrf-token and the input to the controller */
            data: {_token: CSRF_TOKEN, schedulingDetailId: schedulingDetailId, cancelMotive: cancelMotive},
            dataType: 'JSON',
            success: function (data) {
                if (data.success === 'true') {
                    var cancelMessage = document.getElementById('cancelMessage');
                    $(cancelMessage).removeClass('hidden');
                    document.getElementById("modalCancelCloseBtn").click();
                    var status = document.getElementById('status' + schedulingDetailId);
                    status.innerHTML = 'Cancelada';
                }
            },
            error: function (request, status, error) {
                alert(request.responseText);
            }
        });
    }
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

function reschedule(id, time) {
    //CALENDAR
    var calendarEl = document.getElementById('calendar');
    var d = new Date();
    var month = d.getMonth();
    if(month < 10){
        var m = '0'+ month;
    }else{
        m = month;
    }
    var date = d.getFullYear() + '-'+ m +'-01';
    
//    
    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'es',
        events: {
            url: ROUTE + '/scheduling/calendar/reschedule/' + id,
            method: 'GET'
        },
        lazyFetching: true,
//        defaultDate: '2019-'+ d + '-01',
//        defaultDate: date,
        timeZone: 'local',
        plugins: ['interaction', 'dayGrid', 'timeGrid', 'list'],
        header: {
            left: 'prev,next today',
            right: 'timeGridWeek'
        },
        editable: false,
        selectable: true,
        defaultView: 'timeGridWeek',
        maxTime: "17:30:00",
        minTime: "08:30:00",
        slotDuration: '00:15:00',
        slotLabelInterval: '00:15:00',
        slotLabelFormat: {
            hour: 'numeric',
            minute: '2-digit',
            omitZeroMinute: false,
            meridiem: 'short'
        },
        hiddenDays: [0, 6], // hide Saturdar and Sundays
        navLinks: false, // cant click day/week names to navigate views
        eventLimit: true, // allow "more" link when too many events

        loading: function (bool) {
            document.getElementById('loading').style.display =
                    bool ? 'block' : 'none';
        },
        select: function (info) {
            alert("El agendamiento ha sido cambiado para las " + info.start);
            if (!confirm("¿Desea conservar este cambio?")) {
            } else {
                validateDates(info.startStr, info.endStr, id);
            }
        },
        eventDrop: function (info) {
            alert(info.event.title + " ha sido cambiado para las " + info.event.start);
            if (!confirm("¿Desea conservar este cambio?")) {
                info.revert();
            } else {
                let formatted_date = info.event.start.getDate() + "-" + (info.event.start.getMonth() + 1) + "-" + info.event.start.getFullYear() + " " + info.event.start.getHours() + ":" + info.event.start.getMinutes();
  
                validateDates(formatted_date, info.event.start, id);
            }
        }
    });
    calendarEl.innerHTML = '';
    calendar.setOption('height', 400);
    calendar.render();
    document.getElementById("modalRescheduleBtn").click();
    
}


function validateDates(beginDate, endDate, id) {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = ROUTE + '/scheduling/reschedule/validate';
    $.ajax({
        url: url,
        type: "POST",
        /* send the csrf-token and the input to the controller */
        data: {_token: CSRF_TOKEN, beginDate: beginDate, endDate: endDate, id: id},
        dataType: 'JSON',
        success: function (data) {
            if (data.success === 'true') {
                window.location.href = ROUTE + "scheduling";
            } else {
                var rescheduleError = document.getElementById('rescheduleError');
                $(rescheduleError).removeClass('hidden');
//                reschedule(id, '0');
            }
        },
        error: function (request, status, error) {
            alert(request.responseText);
        }
    });
}

function modalConfirmBtn() {
    event.preventDefault();
    var confirmId = document.getElementById('confirmId').value;
    var fileConfirm = document.getElementById('fileConfirm').value;
    console.log(confirmId + ' - ' + fileConfirm);
    if (fileConfirm !== '') {
        var form = document.getElementById('formConfirm');
        var url2 = ROUTE + "/scheduling/confirm";
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
                if(data.success === "true"){
                    window.location.href = ROUTE + "/scheduling";
                }else{
                    var confirmModalError = document.getElementById('confirmModalError');
                    $(confirmModalError).removeClass('hidden');                    
                }
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
                if (file >= 10000) { 
                    document.getElementById('fileConfirm').value = '';
                    alert( 
                      "File too Big, please select a file less than 4mb"); 
                } else if (file < 1) { 
                    document.getElementById('fileConfirm').value = '';
                    alert( 
                      "File too small, please select a file greater than 2mb"); 
                }
            } 
        } 
    } 
