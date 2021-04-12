/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {



    //Btn Add Services
    document.getElementById("btnAddService").onclick = function () {
        event.preventDefault();
        //Initiate Variables
        var error = false;

        //Validate Inputs Paint
        var paint = document.getElementById("paint");
        if (paint.value === '') {
            $(paint).addClass('inputError');
            error = true;
        } else {
            $(paint).removeClass('inputError');
        }

        //Validate Inputs Location
        var location = document.getElementById("location");
        if (location.value === '') {
            $(location).addClass('inputError');
            error = true;
        } else {
            $(location).removeClass('inputError');
        }

        //Validate Inputs Address
        var address = document.getElementById("address");
        if (address.value === '') {
            $(address).addClass('inputError');
            error = true;
        } else {
            $(address).removeClass('inputError');
        }

        //Validate Inputs Damage
        var damage = document.getElementById("damage");
        if (damage.value === '') {
            $(damage).addClass('inputError');
            error = true;
        } else {
            $(damage).removeClass('inputError');
        }

        //Validate Inputs dateTime
        var dateTime = document.getElementById("dateTime");
        if (dateTime.value === '') {
            $(dateTime).addClass('inputError');
            error = true;
        } else {
            $(dateTime).removeClass('inputError');
        }
        //Validate Error
        if (error === true) {
            return;
        } else {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var dateTime = document.getElementById("dateTime").value;
            var damage = document.getElementById("damage").value;
            var location = document.getElementById("location").value;
            var sale = document.getElementById("sale").value;
            var data = {dateTime: dateTime, damage: damage, location: location, sale:sale};
            var url = ROUTE + '/scheduling/validate/dateTime';
            $.ajax({
                type: "POST",
                data: {_token: CSRF_TOKEN, data},
                url: url,
                success: function (data) {
                    if (data.success === "true") {
                        var dateMessage = document.getElementById("dateMessage");
                        $(dateMessage).addClass('hidden');
                        addRow(data.id);
                    } else {
                        var dateMessage = document.getElementById("dateMessage");
                        $(dateMessage).removeClass('hidden');
                    }
                }
            });
        }
    };

//    $("#plate").change(function () {
    $("#plateBtn").click(function () {
        document.getElementById("color").value = '';
        document.getElementById("brand").value = '';
        document.getElementById("year").value = '';
        document.getElementById("document").value = '';
        document.getElementById("last_name").value = '';
        document.getElementById("mobile_phone").value = '';
        document.getElementById("email").value = '';
        document.getElementById("channel").value = '';
        var firstStepBtn = document.getElementById("firstStepBtn");
        $(firstStepBtn).addClass('hidden');
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var plate = document.getElementById("plate").value;
        var data = {plate: plate};
        var url = ROUTE + '/scheduling/validate/plate';
        $.ajax({
            type: "POST",
            data: {_token: CSRF_TOKEN, data},
            url: url,
            success: function (data) {
                var servicesTable = document.getElementById("servicesTable");
                $(servicesTable).removeClass('hidden');

                var servicesTable = document.getElementById("servicesTable");
                servicesTable.innerHTML = data.table;
                if (data.success === 'true') {
                    var plateMessage = document.getElementById("plateMessage");
                    $(plateMessage).addClass('hidden');
                    document.getElementById("color").value = data.color;
                    document.getElementById("year").value = data.year;
                    document.getElementById("brand").value = data.brand;
                } else {
                    var plate = document.getElementById("plate");
                    $(plate).focus();
                    var plateMessage = document.getElementById("plateMessage");
                    $(plateMessage).removeClass('hidden');
                    var servicesTable = document.getElementById("servicesTable");
                    $(servicesTable).addClass('hidden');
                    document.getElementById("sale").value = "";
                }
            }
        });
    });
    $("#paint").change(function () {
        //Paint Value
        var paint = document.getElementById("paint").value;

        //Empty Location Select
        var select = document.getElementById("location");
        select.options.length = 0;

        //Populate Location Select
        if (paint === "Yes") {
            var myobject = {
                '': '--Escoja Una--',
                Taller: 'Taller'
            };

            //Fill Address
            document.getElementById("address").value = "Mundo Motriz";
            document.getElementById("address").setAttribute("disabled", "disabled");
            document.getElementById("damage").removeAttribute("disabled");
            document.getElementById("time").value = "";
            document.getElementById("damage").value = "";
            populateDamage('Taller');
        } else {
            var myobject = {
                '': '--Escoja Una--',
                'Taller': 'Taller',
                'Domicilio': 'Domicilio'
            };
            document.getElementById("address").value = "";
            document.getElementById("time").value = "";
            document.getElementById("damage").value = "";
            document.getElementById("address").removeAttribute("disabled");
            document.getElementById("damage").setAttribute("disabled", "disabled");
        }

        var select = document.getElementById("location");
        for (index in myobject) {
            select.options[select.options.length] = new Option(myobject[index], index);
        }
    });
    $("#location").change(function () {
        //Empty Location Select
        var location = document.getElementById("location").value;
        if (location === "Taller") {
            //Fill Address
            document.getElementById("address").value = "Mundo Motriz";
            document.getElementById("address").setAttribute("disabled", "disabled");
            populateDamage('Taller');
            document.getElementById("damage").removeAttribute("disabled");
            document.getElementById("time").value = "";
        } else {
            //Fill Address
            document.getElementById("address").value = "";
            document.getElementById("address").removeAttribute("disabled");
            populateDamage('Domicilio');
            document.getElementById("damage").removeAttribute("disabled");
            document.getElementById("time").value = "";
        }
    });
    $("#damage").change(function () {
        //Empty Location Select
        var damage = document.getElementById("damage").value;
        var location = document.getElementById("location").value;
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var data = {damage: damage, location: location};
        var url = ROUTE + '/scheduling/validate/damage';
        $.ajax({
            type: "POST",
            data: {_token: CSRF_TOKEN, data},
            url: url,
            success: function (data) {
                document.getElementById("time").value = data;
            }
        });
    });

    //First Step Button Next
    document.getElementById("firstStepBtnNext").onclick = function () {
        firstStepBtnNext();
    };

    //Second Step Button Back
    document.getElementById("secondStepBtnBack").onclick = function () {
        secondStepBtnBack();
    };

    //Store Scheduling
    document.getElementById("secondStepBtnNext").onclick = function () {
        executeScheduling();
    };
    
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

function firstStepBtnNext() {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = ROUTE + '/scheduling/delete/temp';
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN},
        url: url,
        success: function (data) {
//            document.getElementById("time").value = data;
        }
    });
    var schedulingBodyTable = document.getElementById("schedulingBodyTable");
    schedulingBodyTable.innerHTML = '';

    var firstStep = document.getElementById("firstStep");
    $(firstStep).addClass('hidden');

    var secondStep = document.getElementById("secondStep");
    $(secondStep).removeClass('hidden');
}
;

function secondStepBtnBack() {
    var firstStep = document.getElementById("firstStep");
    $(firstStep).removeClass('hidden');

    var secondStep = document.getElementById("secondStep");
    $(secondStep).addClass('hidden');
}
;

function salesChange()
{
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var sales = document.getElementById("sales").value;
    var plate = document.getElementById("plate").value;
    var data = {sales: sales, plate: plate};
    var url = ROUTE + '/scheduling/create/fill';
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, data},
        url: url,
        success: function (data) {
            if (data.success == "true") {
                document.getElementById("color").value = data.data.color;
                document.getElementById("brand").value = data.data.brand;
                document.getElementById("year").value = data.data.year;
                document.getElementById("document").value = data.data.document;
                document.getElementById("last_name").value = data.data.last_name + ' ' + data.data.first_name;
                document.getElementById("mobile_phone").value = data.data.mobile_phone;
                document.getElementById("email").value = data.data.email;
                document.getElementById("channel").value = data.data.channel;
                var firstStepBtnNext = document.getElementById("firstStepBtnNext");
                $(firstStepBtnNext).removeClass('hidden');
                var servicesTable = document.getElementById("servicesTable");
                servicesTable.innerHTML = data.table;
            }
        }
    });
}
;

function populateDamage(location) {
    //Empty Damage Select
    var select = document.getElementById("damage");
    select.options.length = 0;

    if (location === 'Taller') {
        var myobject = {
            '': '--Escoja Una--',
            'A': 'A',
            'B': 'B',
            'C': 'C',
            'D': 'D'
        };
    } else {
        var myobject = {
            '': '--Escoja Una--',
            'A': 'A',
            'B': 'B'
        };
    }
    var select = document.getElementById("damage");
    for (index in myobject) {
        select.options[select.options.length] = new Option(myobject[index], index);
    }
}

function selectSales(id) {
//    event.preventDefault();

    //Highlight Table
    $('#salesTable tr').removeClass('tableSelect');
    $('#salesTable tr:contains("' + id + '")').addClass('tableSelect');

    document.getElementById("sale").value = id;

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var plate = document.getElementById("plate").value;
    var data = {sales: id, plate: plate};
    var url = ROUTE + '/scheduling/create/fill';
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, data},
        url: url,
        success: function (data) {
            if (data.success == "true") {
                document.getElementById("color").value = data.data.color;
                document.getElementById("brand").value = data.data.brand;
                document.getElementById("year").value = data.data.year;
                document.getElementById("document").value = data.data.document;
                document.getElementById("last_name").value = data.data.last_name + ' ' + data.data.first_name;
                document.getElementById("mobile_phone").value = data.data.mobile_phone;
                document.getElementById("email").value = data.data.email;
                document.getElementById("channel").value = data.data.channel;
                var firstStepBtnNext = document.getElementById("firstStepBtnNext");
                $(firstStepBtnNext).removeClass('hidden');
            }
        }
    });
}

// Services Table
function addRow(id) {
//    event.preventDefault();
    //Validate row count
    var sale = document.getElementById("sale").value;
    var obj = $('#salesTable tr:contains("' + sale + '")');

    var bodyTable = document.getElementById("schedulingBodyTable");
    var rowCount = bodyTable.rows.length;
    if (rowCount < obj[0].cells[4].innerHTML) {
        var paint = document.getElementById("paint");
        var plate = document.getElementById("plate");
        var location = document.getElementById("location");
        var address = document.getElementById("address");
        var damage = document.getElementById("damage");
        var dateTime = document.getElementById("dateTime");
        var time = document.getElementById("time");

        var row = bodyTable.insertRow(rowCount);


        row.insertCell(0).innerHTML = plate.value;
        row.insertCell(1).innerHTML = paint.value;
        row.insertCell(2).innerHTML = location.value;
        row.insertCell(3).innerHTML = address.value;
        row.insertCell(4).innerHTML = damage.value;
        row.insertCell(5).innerHTML = dateTime.value;
        row.insertCell(6).innerHTML = '<button type="submit" class="btn btn-link" onClick="Javacsript:deleteRow(this, '+id+')"><span class="glyphicon glyphicon-remove" style="color:red;font-size:18px"></span></button>';

        var rowVehicles = 0;
        $('#schedulingTable tr').each(function () {
            var vehiclesFirst = $(this).find("td:first").html();
        });


        var table = document.getElementById("schedulingTable");

        var rowCountTable = table.rows.length;

        if (rowCountTable > obj[0].cells[4].innerHTML) {
            $('#btnAddService').attr('disabled', 'disabled');
            $('#btnAddService').prop('disabled', true);
        }

        //Return Inputs no Null
        paint.value = '';
        location.value = '';
        damage.value = '';
        dateTime.value = '';
        address.value = '';
        time.value = '';

    }



}

function deleteRow(obj,id) {
    event.preventDefault();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = ROUTE + '/scheduling/calendar/table/delete';
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, id},
        url: url,
        success: function (data) {
            console.log(data);
        }
    });

    var index = obj.parentNode.parentNode.rowIndex;
    var table = document.getElementById("schedulingTable");
    table.deleteRow(index);

    var rowCount = table.rows.length;

    var sale = document.getElementById("sale").value;
    var obj = $('#salesTable tr:contains("' + sale + '")');

    if ((rowCount - 1) < obj[0].cells[4].innerHTML) {
        $('#btnAddService').removeAttr('disabled');
        $('#btnAddService').prop('disabled', false);
        ;
    }
    
    
}

function executeScheduling() {
    event.preventDefault();
    var sale = document.getElementById("sale").value;
    var plate = document.getElementById("plate").value;

    //Obtain Services Data from Step 2
    var tableData = new Array();

    $('#schedulingBodyTable tr').each(function (row, tr) {
        tableData[row] = {
            "plate": $(tr).find('td:eq(0)').text()
            , "paint": $(tr).find('td:eq(1)').text()
            , "location": $(tr).find('td:eq(2)').text()
            , "address": $(tr).find('td:eq(3)').text()
            , "damage": $(tr).find('td:eq(4)').text()
            , "dateTime": $(tr).find('td:eq(5)').text()
        };
    });

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = ROUTE + '/scheduling/store';
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, tableData, sale, plate},
        url: url,
        success: function (data) {
            window.location.href = ROUTE + "/scheduling";
        }
    });
}

function loadCalendar() {
    //OBTAIN EVENTS
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = ROUTE + '/scheduling/calendar';
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN},
        url: url,
        success: function (data) {
            var jsonEvents = data;
        }
    });

    //CALENDAR
    var calendarEl = document.getElementById('calendar');
    calendarEl.innerHTML = '';

    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'es',
        defaultDate: '2019-03-01',
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
        }
        , hiddenDays: [0, 6], // hide Saturdar and Sundays
        navLinks: false, // cant click day/week names to navigate views
        eventLimit: true, // allow "more" link when too many events

        loading: function (bool) {
            document.getElementById('loading').style.display =
                    bool ? 'block' : 'none';
        },
        events: {
            url: ROUTE + '/scheduling/calendar',
            failure: function () {
                document.getElementById('script-warning').style.display = 'block';
            }
        },
        select: function (info) {
            calendar.addEvent({
                title: 'Horario',
                start: info.startStr,
                end: info.endStr,
                allDay: false
            });
            var dateTime = document.getElementById('dateTime');
            var formatDate = $.format.date(info.startStr, "d-M-yyyy H:mm");
            dateTime.value = formatDate;
            //Close Modal
            document.getElementById("modalCalendarClose").click();
        }
    });

    calendar.render();
    var currentDate = new Date();
    calendar.fullCalendar('gotoDate', currentDate);
}

function jsonEvents() {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = ROUTE + '/scheduling/calendar';
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN},
        url: url,
        success: function (data) {
            var jsonEvents = data;
            return jsonEvents;
        }
    });
}
