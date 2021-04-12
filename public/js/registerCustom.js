    $(document).ready(function() {
    
    //Obtain Provinces
    $('select[name="country"]').on('change', function(){
        var countryId = $(this).val();
        var url = ROUTE + '/province/get/'+countryId
        if(countryId) {
            $.ajax({
                url: url,
                type:"GET",
                dataType:"json",
                success:function(data) {
                    var sel = $("#province");
                    sel.empty();
                    sel.append('<option selected="true" disabled="true" value="">--Escoja Una--</option>');
                    var city = $("#city");
                    city.empty();
                    city.append('<option selected="true" disabled="true" value="">--Escoja Una--</option>');
                    for (var i=0; i<data.length; i++) {
                      sel.append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
                    }
                  },
                complete: function(){
//                    $('#loader').css("visibility", "hidden");
                }
            });
        } else {
            $('select[name="province"]').empty();
        }

    });
    //Obtain Provinces
    $('select[name="birthCountry"]').on('change', function(){
        var countryId = $(this).val();
        var url = ROUTE + '/province/get/'+countryId
        if(countryId) {
            $.ajax({
                url: url,
                type:"GET",
                dataType:"json",
                success:function(data) {
                    var sel = $("#birthProvince");
                    sel.empty();
                    sel.append('<option selected="true" disabled="true" value="">--Escoja Una--</option>');
                    var city = $("#city");
                    city.empty();
                    city.append('<option selected="true" disabled="true" value="">--Escoja Una--</option>');
                    for (var i=0; i<data.length; i++) {
                      sel.append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
                    }
                  },
                complete: function(){
//                    $('#loader').css("visibility", "hidden");
                }
            });
        } else {
            $('select[name="province"]').empty();
        }

    });
    //Obtain Provinces
    $('select[name="countryCompany"]').on('change', function(){
        var countryId = $(this).val();
        var url = ROUTE + '/province/get/'+countryId
        if(countryId) {
            $.ajax({
                url: url,
                type:"GET",
                dataType:"json",
                success:function(data) {
                    var sel = $("#provinceCompany");
                    sel.empty();
                    sel.append('<option selected="true" disabled="true" value="">--Escoja Una--</option>');
                    var city = $("#city");
                    city.empty();
                    city.append('<option selected="true" disabled="true" value="">--Escoja Una--</option>');
                    for (var i=0; i<data.length; i++) {
                      sel.append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
                    }
                  },
                complete: function(){
//                    $('#loader').css("visibility", "hidden");
                }
            });
        } else {
            $('select[name="province"]').empty();
        }

    });
    //Obtain Cities
    $('select[name="nationality"]').on('change', function(){
        var countryId = $(this).val();
        var url = ROUTE + '/city/get/country/'+countryId;
        if(countryId) {
            $.ajax({
                url: url,
                type:"GET",
                dataType:"json",
                success:function(data) {
                    var sel = $("#birth_city");
                    sel.empty();
                    sel.append('<option selected="true" disabled="true" value="">--Escoja Una--</option>');
                    var city = $("#birth_city");
                    city.empty();
                    city.append('<option selected="true" disabled="true" value="">--Escoja Una--</option>');
                    for (var i=0; i<data.length; i++) {
                      sel.append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
                    }
                  },
                complete: function(){
//                    $('#loader').css("visibility", "hidden");
                }
            });
        } else {
            $('select[name="province"]').empty();
        }

    });
    /*
    //Obtain Cities
    $('select[name="province"]').on('change', function(){
        var provinceId = $(this).val();
        var url = ROUTE + '/city/get/'+provinceId;
        if(provinceId) {
            $.ajax({
                url: url,
                type:"GET",
                dataType:"json",
                success:function(data) {
                    var sel = $("#city");
                    sel.empty();
                    sel.append('<option selected="true" disabled="true" value="">--Escoja Una--</option>');
                    for (var i=0; i<data.length; i++) {
                      sel.append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
                    }
                  },
                complete: function(){
//                    $('#loader').css("visibility", "hidden");
                }
            });
        } else {
            $('select[name="province"]').empty();
        }

    });
    */
       //Obtain cities 
    $('select[name="province"]').on('change', function(){
        var provinceid = $(this).val();
        var url = ROUTE + '/city/get/'+ provinceid;
        if(provinceid) {
            $.ajax({
                url: url,
                type:"GET",
                dataType:"json",
                success:function(data) {
                    var sel = $("#city");
                    sel.empty();
                    sel.append('<option selected="true" disabled="true" value="0">--Escoja Una--</option>');
                    for (var i=0; i<data.length; i++) {
                      sel.append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
                    }
                },
                complete: function(){
    //                    $('#loader').css("visibility", "hidden");
                }
            });
        } else {
            $('select[name="city"]').empty();
        }

    });
    /*
    //Obtain canton 
    $('select[name="city"]').on('change', function(){
        var provinceid = $(this).val();
        var url = ROUTE + '/canton/get/'+ provinceid;
        if(provinceid) {
            $.ajax({
                url: url,
                type:"GET",
                dataType:"json",
                success:function(data) {
                    var sel = $("#canton");
                    sel.empty();
                    sel.append('<option selected="true" disabled="true" value="0">--Escoja Uno--</option>');
                    for (var i=0; i<data.length; i++) {
                        sel.append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
                    }
                },
                complete: function(){
    //                    $('#loader').css("visibility", "hidden");
                }
            });
        } else {
            $('select[name="canton"]').empty();
        }

    });
    */
    //Obtain Cities
    $('select[name="birthProvince"]').on('change', function(){
        var provinceId = $(this).val();
        var url = ROUTE + '/city/get/'+provinceId;
        if(provinceId) {
            $.ajax({
                url: url,
                type:"GET",
                dataType:"json",
                success:function(data) {
                    var sel = $("#birthCity");
                    sel.empty();
                    sel.append('<option selected="true" disabled="true" value="">--Escoja Una--</option>');
                    for (var i=0; i<data.length; i++) {
                      sel.append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
                    }
                  },
                complete: function(){
//                    $('#loader').css("visibility", "hidden");
                }
            });
        } else {
            $('select[name="province"]').empty();
        }

    });
    //Obtain Cities
    $('select[name="provinceCompany"]').on('change', function(){
        var provinceId = $(this).val();
        var url = ROUTE + '/city/get/'+provinceId;
        if(provinceId) {
            $.ajax({
                url: url,
                type:"GET",
                dataType:"json",
                success:function(data) {
                    var sel = $("#cityCompany");
                    sel.empty();
                    sel.append('<option selected="true" disabled="true" value="">--Escoja Una--</option>');
                    for (var i=0; i<data.length; i++) {
                      sel.append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
                    }
                  },
                complete: function(){
//                    $('#loader').css("visibility", "hidden");
                }
            });
        } else {
            $('select[name="province"]').empty();
        }

    });
    //Obtain Provinces
    
    $('select[name="country_insured"]').on('change', function(){
        var countryId = $(this).val();
        var url = ROUTE + '/province/get/'+countryId
        if(countryId) {
            $.ajax({
                url: url,
                type:"GET",
                dataType:"json",
                success:function(data) {
                    var sel = $("#province_insured");
                    sel.empty();
                    sel.append('<option selected="true" disabled="true" value="">--Escoja Una--</option>');
                    var city = $("#city");
                    city.empty();
                    city.append('<option selected="true" disabled="true" value="">--Escoja Una--</option>');
                    for (var i=0; i<data.length; i++) {
                      sel.append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
                    }
                  },
                complete: function(){
//                    $('#loader').css("visibility", "hidden");
                }
            });
        } else {
            $('select[name="province_insured"]').empty();
        }

    });
    
    //Obtain Cities
    $('select[name="province_insured"]').on('change', function(){
        var provinceId = $(this).val();
        var url = ROUTE + '/city/get/'+provinceId;
        if(provinceId) {
            $.ajax({
                url: url,
                type:"GET",
                dataType:"json",
                success:function(data) {
                    var sel = $("#city_insured");
                    sel.empty();
                    sel.append('<option selected="true" disabled="true" value="">--Escoja Una--</option>');
                    for (var i=0; i<data.length; i++) {
                      sel.append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
                    }
                  },
                complete: function(){
//                    $('#loader').css("visibility", "hidden");
                }
            });
        } else {
            $('select[name="province_insured"]').empty();
        }

    });
    //Obtain Agencies
    $('select[name="channel"]').on('change', function(){
        var channelId = $(this).val();
        var url = ROUTE + '/agency/get/'+channelId;
        if(channelId) {
            $.ajax({
                url: url,
                type:"GET",
                dataType:"json",
                success:function(data) {
                    var sel = $("#agency");
                    sel.empty();
                    sel.append('<option selected="true" disabled="true" value="0">--Escoja Una--</option>');
                    for (var i=0; i<data.length; i++) {
                      sel.append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
                    }
                  },
                complete: function(){
//                    $('#loader').css("visibility", "hidden");
                }
            });
        } else {
            $('select[name="province"]').empty();
        }

    });

    // R4
    //Obtain rubros value
    /*
    $('select[name="rubros"]').on('change', function(){
        var rubro_cod = $(this).val();
        var url = ROUTE + '/sales/R4/getValueRubro/'+rubro_cod;
        if(rubro_cod) {
            $.ajax({
                url: url,
                type:"GET",
                dataType:"json",
                success:function(data) {
                    if (data > 0){
                        $('#ValueRubro').val(data).prop("disabled", true);
                    } else {
                        $('#ValueRubro').val('').prop("disabled", false);
                    }
                },
                complete: function(){
    //                    $('#loader').css("visibility", "hidden");
                }
            });
        } else {
            $('select[name="rubros"]').empty();
        }

    });
    */



    //Obtain canton 
    $('select[name="cities"]').on('change', function(){
        var provinceid = $(this).val();
        var url = ROUTE + '/canton/get/'+ provinceid;
        if(provinceid) {
            $.ajax({
                url: url,
                type:"GET",
                dataType:"json",
                success:function(data) {
                    var sel = $("#cantones");
                    sel.empty();
                    sel.append('<option selected="true" disabled="true" value="0">--Escoja Uno--</option>');
                    for (var i=0; i<data.length; i++) {
                      sel.append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
                    }
                },
                complete: function(){
    //                    $('#loader').css("visibility", "hidden");
                }
            });
        } else {
            $('select[name="cantones"]').empty();
        }

    });

    //Obtain cities 
    $('select[name="provinces"]').on('change', function(){
    var provinceid = $(this).val();
    var url = ROUTE + '/city/get/'+ provinceid;
    if(provinceid) {
        $.ajax({
            url: url,
            type:"GET",
            dataType:"json",
            success:function(data) {
                var sel = $("#cities");
                sel.empty();
                sel.append('<option selected="true" disabled="true" value="0">--Escoja Una--</option>');
                for (var i=0; i<data.length; i++) {
                    sel.append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
                }
            },
            complete: function(){
//                    $('#loader').css("visibility", "hidden");
            }
        });
    } else {
        $('select[name="cities"]').empty();
    }

});

});