$(document).ready(function () {

    $('#btnClearFilter').click(function () {
        document.getElementById("agent").value = "";
        document.getElementById("channel").value = "";
        document.getElementById("agency").value = "";
        document.getElementById("ejecutivo_ss").value = "";
        document.getElementById("agentss").value = "";
        document.getElementById("beginDate").value = "";
        document.getElementById("endDate").value = "";
        document.getElementById("type_policy").value = "";
        document.getElementById("product").value = "";
        document.getElementById("state").value = "";
        document.getElementById("sale_id").value = "";
        document.getElementById("province").value = "";
        document.getElementById("city").value = "";
        document.getElementById("paymenttype").value = "";
        document.getElementById("brand").value = "";
        document.getElementById("uses").value = "";
    });
    
    $('#btnClearFilterTecVidAP').click(function () {
        document.getElementById("agent").value = "";
        document.getElementById("channel").value = "";
        document.getElementById("agency").value = "";
        document.getElementById("agency_ss").value = "";
        document.getElementById("ejecutivo_ss").value = "";
        document.getElementById("agentss").value = "";
        document.getElementById("beginDate").value = "";
        document.getElementById("endDate").value = "";
        document.getElementById("type_policy").value = "";
        document.getElementById("product").value = "";
        document.getElementById("state").value = "";
        document.getElementById("sale_id").value = "";
        document.getElementById("province").value = "";
        document.getElementById("city").value = "";
        document.getElementById("paymenttype").value = "";
    });

    // date picker configutación to 30 days only
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

    $('select[name="agent"]').on('change', function () {
        var agentId = $(this).val();
        var url = ROUTE + '/agenChannel/get/' + agentId
        if (agentId) {
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    var channel = $("#channel");
                    channel.empty();
                    channel.append('<option selected="true" disabled="true" value="">--Escoja Uno--</option>');
                    for (var i = 0; i < data.length; i++) {
                        channel.append('<option value="' + data[i].id + '">' + data[i].canalnegodes + '</option>');
                    }
                },
                complete: function () {
                    //                    $('#loader').css("visibility", "hidden");
                }
            });
        } else {
            $('select[name="channel"]').empty();
            $('select[name="channel"]').append('<option selected="true" disabled="true" value="">--Escoja Uno--</option>');
        }

    });

    $('select[name="channel"]').on('change', function () {
        var channelId = $(this).val();
        var url = ROUTE + '/agency/get/' + channelId
        if (channelId) {
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    var agency = $("#agency");
                    agency.empty();
                    agency.append('<option selected="true" disabled="true" value="">--Escoja Una--</option>');
                    for (var i = 0; i < data.length; i++) {
                        agency.append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
                    }
                },
                complete: function () {
                    //                    $('#loader').css("visibility", "hidden");
                }
            });
        } else {
            $('select[name="agency"]').empty();
            $('select[name="agency"]').append('<option selected="true" disabled="true" value="">--Escoja Una--</option>');
        }

    });

    $('select[name="agency"]').on('change', function () {
        var agencyId = $(this).val();
        var channelId = document.getElementById("channel").value;
        var url = ROUTE + '/ejecutivo_ss/get/' + agencyId + '/' + channelId;
        if (agencyId) {
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    var ejecutivo_ss = $("#ejecutivo_ss");
                    ejecutivo_ss.empty();
                    ejecutivo_ss.append('<option selected="true" disabled="true" value="">--Escoja Uno--</option>');
                    for (var i = 0; i < data.length; i++) {
                        ejecutivo_ss.append('<option value="' + data[i].ejecutivo_ss + '">' + data[i].ejecutivo_ss + '</option>');
                    }
                },
                complete: function () {
                    //                    $('#loader').css("visibility", "hidden");
                }
            });
        } else {
            $('select[name="ejecutivo_ss"]').empty();
            $('select[name="ejecutivo_ss"]').append('<option selected="true" disabled="true" value="">--Escoja Uno--</option>');
        }

    });

    $('select[name="province"]').on('change', function () {
        var provinceId = $(this).val();
        var url = ROUTE + '/city/get/' + provinceId
        if (provinceId) {
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    var city = $("#city");
                    city.empty();
                    city.append('<option selected="true" disabled="true" value="">--Escoja Uno--</option>');
                    for (var i = 0; i < data.length; i++) {
                        city.append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
                    }
                },
                complete: function () {
                    //                    $('#loader').css("visibility", "hidden");
                }
            });
        } else {
            $('select[name="city"]').empty();
            $('select[name="city"]').append('<option selected="true" disabled="true" value="">--Escoja Uno--</option>');
        }

    });

    $('select[name="ramov"]').on('change', function () {
        var ramoId = $(this).val();
        var url = ROUTE + '/products/get/' + ramoId
        if (ramoId) {
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    var product = $("#productv");
                    product.empty();
                    product.append('<option selected="true" disabled="true" value="">--Escoja Uno--</option>');
                    for (var i = 0; i < data.length; i++) {
                        product.append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
                    }
                },
                complete: function () {
                    //                    $('#loader').css("visibility", "hidden");
                }
            });
        } else {
            $('select[name="productv"]').empty();
            $('select[name="productv"]').append('<option selected="true" disabled="true" value="">--Escoja Uno--</option>');
        }

    });

});

function endDateChange() {
    var beginDate = moment(document.getElementById("beginDate").value);
    var endDate = moment(document.getElementById("endDate").value);

    if (beginDate === '') {
        alert('Por favor introduza una fecha de Inicio');
        document.getElementById("endDate").value = '';
    } else if (endDate < beginDate) {
        alert('La fecha Fin no puede ser menor a la fecha de Inicio');
        document.getElementById("endDate").value = '';
    } else if (endDate.diff(beginDate, 'days') > 31) {
        alert('El rango de fecha no debe de superar los 31 días');
        document.getElementById("endDate").value = '';
    }
}

function val() {
    var beginDate = document.getElementById("beginDate").value;
    var endDate = document.getElementById("endDate").value;
    

    if (beginDate === '' && endDate === '') {
        $("#loaderGif").addClass('loaderGif');
        alert('Ingrese una Fecha Inicio y Fin');
        $("#loaderGif").removeClass('loaderGif');
        return false;
    } else if (beginDate == '' && endDate !== '') {
        $("#loaderGif").addClass('loaderGif');
        alert('Ingrese una Fecha Inicio');
        $("#loaderGif").removeClass('loaderGif');
        return false;
    } else if (beginDate !== '' && endDate === '') {
        $("#loaderGif").addClass('loaderGif');
        alert('Ingrese una Fecha Fin');
        $("#loaderGif").removeClass('loaderGif');
        return false;
    } else {
        $("#loaderGif").addClass('loaderGif');
        setTimeout(function() {
            $("#loaderGif").removeClass('loaderGif');
        }, 1000); 
        return true;
    }
    
}