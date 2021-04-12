/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {
    checkValidInput();
    //Btn Add Vehicles
    document.getElementById("btnVehicles").onclick = function () {
        event.preventDefault();
        var table = document.getElementById("vehiclesTable");

        var rowCountTable = table.rows.length;

        if (rowCountTable > 3) {
            $('#btnVehicles').attr('disabled', 'disabled');
            $('#btnVehicles').prop('disabled', true);
        }else{
            thirdStepValidate();
        }
    };
    
    $("#document_id").change(function () {
        var document_id = document.getElementById("document_id");
        var documentInput = document.getElementById("document");
        if(document_id.value == 1){
            if(documentInput.value.length == 10){
                validateDocument();
                $(documentInput).focus();
            }else{
                document.getElementById("document_id").value = '0';
                alert('La cedula debe tener 10 digitos');
                $(documentInput).focus();
            }
        }
    });   

    function btnVehicles() {
//        console.log('entro');
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var plate = document.getElementById("plate").value;
        var model = document.getElementById("model").value;
        var document = document.getElementById("document").value;
        var data = "{'plate':'" + plate + "','document':'" + document + "' ,'model':'" + model + "'}";
        var url = ROUTE + '/vehicles/tempJson/';
        $.ajax({
            url: url,
            type: "POST",
            /* send the csrf-token and the input to the controller */
            data: {_token: CSRF_TOKEN, plate: plate, model: model},
            dataType: 'JSON',
            success: function (result) {
//                window.console.log('Successful');
            }
        });
    };

    var tableBorder2 = document.getElementById("tableUsers_length");
    $(tableBorder2).addClass('hidden');
    var tableBorder2 = document.getElementById("tableUsers_info");
    $(tableBorder2).addClass('hidden');
    var tableBorder2 = document.getElementById("tableUsers_paginate");
    $(tableBorder2).addClass('hidden');

    //First Step Button Next
    document.getElementById("firstStepBtnNext").onclick = function () {
        firstStepBtnNext();
    };

    function firstStepBtnNext() {

        if ($("input[id^='productCheckBox']:checked").length !== 1) {
            var productAlert = document.getElementById("productAlert");
            productAlert.classList.remove("hidden");
            productAlert.classList.remove("visible");

        } else {

            $(".alert").addClass('hidden');
            //Hide First Step Div
            var firstStep = document.getElementById("firstStep");
            $(firstStep).addClass('hidden');

            //Inactive First Step Wizard
            var firstStepWizard = document.getElementById("firstStepWizard");
            $(firstStepWizard).removeClass('wizard_activo');
            $(firstStepWizard).addClass('wizard_inactivo');

            //Show Second Step Div        
            var secondStep = document.getElementById("secondStep");
            $(secondStep).addClass('visible');
            $(secondStep).removeClass('hidden');

            //Active Second Step Wizard
            var secondStepWizard = document.getElementById("secondStepWizard");
            $(secondStepWizard).removeClass('wizard_inactivo');
            $(secondStepWizard).addClass('wizard_activo');
        }
    }

    //Second Step Button Back
    document.getElementById("secondStepBtnBack").onclick = function () {
        secondStepBtnBack();
    };

    function secondStepBtnBack() {
        //Hide First Step Div
        var firstStep = document.getElementById("firstStep");
        $(firstStep).removeClass('hidden');
        $(firstStep).addClass('visible');

        //Inactive First Step Wizard
        var firstStepWizard = document.getElementById("firstStepWizard");
        $(firstStepWizard).removeClass('wizard_inactivo');
        $(firstStepWizard).addClass('wizard_activo');

        //Show Second Step Div        
        var secondStep = document.getElementById("secondStep");
        $(secondStep).removeClass('visible');
        $(secondStep).addClass('hidden');

        //Active Second Step Wizard
        var secondStepWizard = document.getElementById("secondStepWizard");
        $(secondStepWizard).removeClass('wizard_activo');
        $(secondStepWizard).addClass('wizard_inactivo');
    }

    //second Step btn Next
    document.getElementById("secondStepBtnNext").onclick = function () {
        secondStepBtnNext();
    };
    function secondStepBtnNext() {
        event.preventDefault();
        //Validate Variable
        var validate = 'false';

        //Validate Inputs
        var documentNumber = document.getElementById("document");
        if (documentNumber.value === "") {
            $(documentNumber).addClass('inputRedFocus');
            validate = 'true';
        }
        var first_name = document.getElementById("first_name");
        if (first_name.value === "") {
            $(first_name).addClass('inputRedFocus');
            validate = 'true';
        }
        var document_id = document.getElementById("document_id");
        if (document_id.value === "0") {
            $(document_id).addClass('inputRedFocus');
            validate = 'true';
        }
        var last_name = document.getElementById("last_name");
        if (last_name.value === "") {
            $(last_name).addClass('inputRedFocus');
            validate = 'true';
        }
        var address = document.getElementById("address");
        if (address.value === "") {
            $(address).addClass('inputRedFocus');
            validate = 'true';
        }
        var mobile_phone = document.getElementById("mobile_phone");
        if (mobile_phone.value === "") {
            $(mobile_phone).addClass('inputRedFocus');
            validate = 'true';
        }else{
            if(isNaN(mobile_phone.value)){
                $(mobile_phone).addClass('inputRedFocus');
                validate = 'true';
            }
            if (mobile_phone.value.length != 10) {
                $(mobile_phone).addClass('inputRedFocus');
                validate = 'true';
            }
        }
        var phone = document.getElementById("phone");
        if (phone.value === "") {
            $(phone).addClass('inputRedFocus');
            validate = 'true';
        }else if(phone.value.length != 9){
            $(phone).addClass('inputRedFocus');
            validate = 'true';
        }
        var email = document.getElementById("email");
        var emailValidate = ValidateEmail(email.value);
//        console.log(emailValidate);
        if (email.value === "") {
            $(email).addClass('inputRedFocus');
            validate = 'true';
        }else if(emailValidate === false){
            $(email).addClass('inputRedFocus');
            validate = 'true';
            
        }
        var country = document.getElementById("country");
        if (country.value === "0") {
            $(country).addClass('inputRedFocus');
            validate = 'true';
        }
        var province = document.getElementById("province");
        if (province.value === "0") {
            $(province).addClass('inputRedFocus');
            validate = 'true';
        }
        var city = document.getElementById("city");
        if (city.value === "0") {
            $(city).addClass('inputRedFocus');
            validate = 'true';
        }
        
        if (validate === 'true') {
            var customerAlert = document.getElementById("customerAlert");
            customerAlert.classList.remove("hidden");
            customerAlert.classList.addClass("visible");
            return false;
        };

        //Hide First Step Div
        var firstStep = document.getElementById("secondStep");
        $(firstStep).addClass('hidden');

        //Inactive First Step Wizard
        var firstStepWizard = document.getElementById("secondStepWizard");
        $(firstStepWizard).removeClass('wizard_activo');
        $(firstStepWizard).addClass('wizard_inactivo');

        //Show Second Step Div        
        var secondStep = document.getElementById("thirdStep");
        $(secondStep).addClass('visible');
        $(secondStep).removeClass('hidden');

        //Active Second Step Wizard
        var secondStepWizard = document.getElementById("thirdStepWizard");
        $(secondStepWizard).removeClass('wizard_inactivo');
        $(secondStepWizard).addClass('wizard_activo');
    }
    //third Step btn Next
    document.getElementById("thirdStepBtnNext").onclick = function () {
        thirdStepBtnNext();
    };
    
    function validateDocument(){
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var data = {document: document.getElementById("document").value};
        var url = ROUTE + '/sales/validateDocument';
        $.ajax({
            type: "POST",
            data: {_token: CSRF_TOKEN, data},
            url: url,
            async: false,
            success: function (data) {
//                console.log(data);
                if(data === 'invalid'){
                    $("#suggesstion-box").hide();
        document.getElementById("first_name").value = "";
        document.getElementById("document_id").value = "0";
        document.getElementById("last_name").value = "";
        document.getElementById("mobile_phone").value = "";
        document.getElementById("phone").value = "";
        document.getElementById("address").value = "";
        document.getElementById("email").value = "";
        document.getElementById("country").value = "0";
        document.getElementById("province").value = "0";
        document.getElementById("city").value = "0";
                    alert('El documento ingresado es invalido');
                }
            },
            error: function() {
               return "Hello";
            }
        });
    }
    function thirdStepBtnNext() {
        event.preventDefault();
        //Validate Vehicles Table
        var tbl = document.getElementById('vehiclesBodyTable');
        if (tbl.rows.length === 0) {
            var tblAlert = document.getElementById('thirdStepAlert');
            $(tblAlert).removeClass('hidden');
            $(tblAlert).addClass('visible');
            var plateAlert = document.getElementById('plateAlert');
            var plateAlert2 = document.getElementById('plateAlert2');
            var plateAlert3 = document.getElementById('plateAlert3');
            $(plateAlert).addClass('hidden');
            $(plateAlert2).addClass('hidden');
            $(plateAlert3).addClass('hidden');
            return false;
        } else {



            //Hide First Step Div
            var firstStep = document.getElementById("thirdStep");
            $(firstStep).addClass('hidden');

            //Inactive First Step Wizard
            var firstStepWizard = document.getElementById("thirdStepWizard");
            $(firstStepWizard).removeClass('wizard_activo');
            $(firstStepWizard).addClass('wizard_inactivo');

            //Show Second Step Div        
            var secondStep = document.getElementById("fourthStep");
            $(secondStep).addClass('visible');
            $(secondStep).removeClass('hidden');

            //Active Second Step Wizard
            var secondStepWizard = document.getElementById("fourthStepWizard");
            $(secondStepWizard).removeClass('wizard_inactivo');
            $(secondStepWizard).addClass('wizard_activo');

            //Fill Data Fourth Step
            var secondStepDocument = document.getElementById("document");
            var secondStepFirstName = document.getElementById("first_name");
            var secondSteplastName = document.getElementById("last_name");
            var secondStepPhone = document.getElementById("mobile_phone");
            var secondStepEmail = document.getElementById("email");

            var documentResume = document.getElementById("documentResume");
            var customerResume = document.getElementById("customerResume");
            var mobile_phoneResume = document.getElementById("mobile_phoneResume");
            var emailResume = document.getElementById("emailResume");

            $(documentResume).val(secondStepDocument.value);
            $(customerResume).val(secondStepFirstName.value + ' ' + secondSteplastName.value);
            $(mobile_phoneResume).val(secondStepPhone.value);
            $(emailResume).val(secondStepEmail.value);

            //Obtain Checked Producto from Step 1
            var checkedProduct = '';

            $(".check:checked").each(function () {
                checkedProduct = ($(this).val());
            });
//            console.log(checkedProduct);
            //Obtain Vehicles Data from Step 3
            var TableData = new Array();

            $('#vehiclesBodyTable tr').each(function (row, tr) {
                TableData[row] = {
                    "plate": $(tr).find('td:eq(0)').text()
                    , "brand": $(tr).find('td:eq(1)').text()
                    , "model": $(tr).find('td:eq(2)').text()
                    , "year": $(tr).find('td:eq(3)').text()
                    , "color": $(tr).find('td:eq(4)').text()
                };
            });

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var data = {product: checkedProduct, vehicles: TableData};
            var url = ROUTE + '/sales/resume';
            $.ajax({
                type: "POST",
                data: {_token: CSRF_TOKEN, data},
                url: url,
                success: function (data) {
//                    console.log(data);
//                    $("vehiclesTableBodyResume").html(data);
                    var benefitsTable = document.getElementById('benefitsTable');
                    benefitsTable.innerHTML = '';
                    benefitsTable.innerHTML += data[6];

                    var div = document.getElementById('vehiclesTableBodyResume');

                    div.innerHTML = '';
                    div.innerHTML += data[0];

                    var div = document.getElementById('sub12');

                    div.innerHTML = '';
                    div.innerHTML += data[1];

                    var div = document.getElementById('sub0');

                    div.innerHTML = '';
                    div.innerHTML += data[2];

                    var div = document.getElementById('otherDiscount');

                    div.innerHTML = '';
                    div.innerHTML += data[3];

                    var div = document.getElementById('tax12');

                    div.innerHTML = '';
                    div.innerHTML += data[4];

                    var div = document.getElementById('total');

                    div.innerHTML = '';
                    div.innerHTML += data[5];
                }
            });
        }
    }

    //fourth Step btn Next
    document.getElementById("fourthStepBtnNext").onclick = function () {
        executeSale();
    };
    function fourthStepBtnNext() {
        //Hide First Step Div
        var firstStep = document.getElementById("fourthStep");
        $(firstStep).removeClass('visible');
        $(firstStep).addClass('hidden');

        //Inactive First Step Wizard
        var firstStepWizard = document.getElementById("fourthStepWizard");
        $(firstStepWizard).removeClass('wizard_activo');
        $(firstStepWizard).addClass('wizard_inactivo');

        //Show Second Step Div        
        var secondStep = document.getElementById("fifthStep");
        $(secondStep).addClass('visible');
        $(secondStep).removeClass('hidden');

        //Active Second Step Wizard
        var secondStepWizard = document.getElementById("fifthStepWizard");
        $(secondStepWizard).removeClass('wizard_inactivo');
        $(secondStepWizard).addClass('wizard_activo');
    }

    function executeSale() {
        // Obtatin Customer Data
        var customerDocument = document.getElementById("document").value;
        var customerDocumentId = document.getElementById("document_id").value;
        var customerFirstName = document.getElementById("first_name").value;
        var customerLastName = document.getElementById("last_name").value;
        var customerPhone = document.getElementById("phone").value;
        var customerMobilePhone = document.getElementById("mobile_phone").value;
        var customerAddress = document.getElementById("address").value;
        var customerEmail = document.getElementById("email").value;
        var customerCountry = document.getElementById("country").value;
        var customerProvince = document.getElementById("province").value;
        var customerCity = document.getElementById("city").value;
        var salesType =  3;

        var customerData = new Array();
        customerData = {
            'document': customerDocument,
            'documentId': customerDocumentId,
            'firstName': customerFirstName,
            'lastName': customerLastName,
            'phone': customerPhone,
            'mobilePhone': customerMobilePhone,
            'address': customerAddress,
            'email': customerEmail,
            'country': customerCountry,
            'province': customerProvince,
            'city': customerCity
        };

        //Obtain prices table
        var pricesTable = new Array();
        $('#taxTableBodyResume tr').each(function (row, tr) {
            pricesTable[row] = {
                "value": $(tr).find('td:eq(1)').text()
            };
        });

        //Obtain Checked Producto from Step 1
        var checkedProduct = '';

        $(".check:checked").each(function () {
            checkedProduct = ($(this).val());
        });

        //Obtain Vehicles Data from Step 3
        var TableData = new Array();

        $('#vehiclesBodyTable tr').each(function (row, tr) {
            TableData[row] = {
                "plate": $(tr).find('td:eq(0)').text()
                , "brand": $(tr).find('td:eq(1)').text()
                , "model": $(tr).find('td:eq(2)').text()
                , "year": $(tr).find('td:eq(3)').text()
                , "color": $(tr).find('td:eq(4)').text()
            };
        });

        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var data = {product: checkedProduct, vehicles: TableData, customer: customerData, pricesTable: pricesTable, salesType: salesType};
        var url = ROUTE + '/sales/store';
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

                var txt = 'Su Numero de Venta es el: ' + data['productId'] + '.';
                var paragraph = document.getElementById("modalText");
                var text = document.createTextNode(txt);

                paragraph.appendChild(text);
//                document.getElementById("confirmModal").click();
                document.getElementById("salId").value = data['salId'];
                document.getElementById("chargeId").value = data['chargeId'];

//                fourthStepBtnNext();
                returnIndex();
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
    
    function returnIndex(){
        window.location.href = ROUTE + "/sales";
    }


    function validate()
    {
//        console.log('function');
        $('input:text', '#salesForm').removeClass('error');
        $('input:text[value=""]', '#salesForm').addClass('error');
        return false;
    }

    //third Step Button Back
    document.getElementById("thirdStepBtnBack").onclick = function () {
        thirdStepBtnBack();
    };

    function thirdStepBtnBack() {
        //Hide First Step Div
        var firstStep = document.getElementById("secondStep");
        $(firstStep).removeClass('hidden');
        $(firstStep).addClass('visible');

        //Inactive First Step Wizard
        var firstStepWizard = document.getElementById("secondStepWizard");
        $(firstStepWizard).removeClass('wizard_inactivo');
        $(firstStepWizard).addClass('wizard_activo');

        //Show Second Step Div        
        var secondStep = document.getElementById("thirdStep");
        $(secondStep).removeClass('visible');
        $(secondStep).addClass('hidden');

        //Active Second Step Wizard
        var secondStepWizard = document.getElementById("thirdStepWizard");
        $(secondStepWizard).removeClass('wizard_activo');
        $(secondStepWizard).addClass('wizard_inactivo');
    }
    //fourth Step Button Back
    document.getElementById("fourthStepBtnBack").onclick = function () {
        fourthStepBtnBack();
    };

    function fourthStepBtnBack() {
        //Hide First Step Div
        var firstStep = document.getElementById("thirdStep");
        $(firstStep).removeClass('hidden');
        $(firstStep).addClass('visible');

        //Inactive First Step Wizard
        var firstStepWizard = document.getElementById("thirdStepWizard");
        $(firstStepWizard).removeClass('wizard_inactivo');
        $(firstStepWizard).addClass('wizard_activo');

        //Show Second Step Div        
        var secondStep = document.getElementById("fourthStep");
        $(secondStep).removeClass('visible');
        $(secondStep).addClass('hidden');

        //Active Second Step Wizard
        var secondStepWizard = document.getElementById("fourthStepWizard");
        $(secondStepWizard).removeClass('wizard_activo');
        $(secondStepWizard).addClass('wizard_inactivo');
    }
    //fifth Step Button Back
//    document.getElementById("fifthStepBtnBack").onclick = function () {
//        fifthStepBtnBack();
//    };

    function fifthStepBtnBack() {
        //Hide First Step Div
        var firstStep = document.getElementById("fourthStep");
        $(firstStep).removeClass('hidden');
        $(firstStep).addClass('visible');

        //Inactive First Step Wizard
        var firstStepWizard = document.getElementById("fourthStepWizard");
        $(firstStepWizard).removeClass('wizard_inactivo');
        $(firstStepWizard).addClass('wizard_activo');

        //Show Second Step Div        
        var secondStep = document.getElementById("fifthStep");
        $(secondStep).removeClass('visible');
        $(secondStep).addClass('hidden');

        //Active Second Step Wizard
        var secondStepWizard = document.getElementById("fifthStepWizard");
        $(secondStepWizard).removeClass('wizard_activo');
        $(secondStepWizard).addClass('wizard_inactivo');
    }

    //Check Products Only one
    $('.check').click(function () {
        $('.check').not(this).prop('checked', false);
    });
//Vehicle Year Change
    $("#year").change(function () {
        var inputYear = $(this).val();
        var _thisYear = new Date().getFullYear() + 1;
        if (parseInt($(this).val()) > _thisYear){
            var txt = 'Por favor ingrese un año valido, ' + inputYear + ' no es un año valido.';
            document.getElementById("yearVehicleError").innerHTML = txt;
            document.getElementById("year").value = "";
        }else if(parseInt($(this).val()) < (new Date().getFullYear() - 8)) {
            var txt = 'El Vehiculo no puede tener una antiguedad mayor a 8 años (' + inputYear +')';
            document.getElementById("yearVehicleError").innerHTML = txt;
            document.getElementById("year").value = "";
        } else {
            document.getElementById("yearVehicleError").innerHTML = "";
        }
    });
//Vehicle Plate Change
    document.getElementById("plateBtn").onclick = function () {
        var TableData = new Array();
        
        $('#vehiclesTable tr').each(function (row, tr) {
            TableData += [$(tr).find('td:eq(0)').text()] + ',0,';
        });
//            console.log(TableData);
//        var result = checkPlate(TableData);
        var result = TableData.includes(document.getElementById('plate').value);

        if(result == false){
            var plateAlert4 = document.getElementById("plateAlert4");
            $(plateAlert4).addClass('hidden');
            var plate = document.getElementById("plate");
            var myString = plate.value.replace(/\D/g,'');
            if(myString.length != 4){
                $(plate).addClass('inputRedFocus');
                var plateAlert3 = document.getElementById("plateAlert3");
                $(plateAlert3).removeClass('hidden');
            }else{
                $(plate).removeClass('inputRedFocus');
                var plateAlert3 = document.getElementById("plateAlert3");
                $(plateAlert3).addClass('hidden');
                var thirdStepAlert = document.getElementById("thirdStepAlert");
                $(thirdStepAlert).addClass('hidden');
                plateBtn();
            }
        }else{
            var plateAlert4 = document.getElementById("plateAlert4");
            $(plateAlert4).removeClass('hidden');
            var plateAlert4 = document.getElementById("plate");
            $(plateAlert4).addClass('inputRedFocus');
            $('#vehiclesTable tr').each(function (row, tr) {
                if(document.getElementById('plate').value === $(tr).find('td:eq(0)').text()){
                    $(tr).find('td:eq(0)').addClass('borderRedFocus');
                    console.log('add');
                }else{
                    $(tr).find('td:eq(0)').removeClass('borderRedFocus');
                    console.log('remove');
                }
            });
        }
        
    };
//      $("#year").change(function () {
//          $("#suggesstion-box").hide();
//        document.getElementById("brand").value = "0";
//        document.getElementById("model").value = "";
//        document.getElementById("year").value = "0";
//        document.getElementById("color").value = "";
//        var brand = document.getElementById("brand");
//        $(brand).removeClass('inputRedFocus');
//        var model = document.getElementById("model");
//        $(model).removeClass('inputRedFocus');
//        var color = document.getElementById("color");
//        $(color).removeClass('inputRedFocus');
//        var year = document.getElementById("year");
//        $(year).removeClass('inputRedFocus');
//        });
     
    document.getElementById("plate").onclick = function () {
        var plateAlert4 = document.getElementById("plateAlert4");
        $(plateAlert4).addClass('hidden');
        var plateAlert3 = document.getElementById("plateAlert3");
        $(plateAlert3).addClass('hidden');
        var plateAlert2 = document.getElementById("plateAlert2");
        $(plateAlert2).addClass('hidden');
        var plate = document.getElementById("plate");
        $(plate).removeClass('inputRedFocus');
        var yearVehicleError = document.getElementById("yearVehicleError");
        yearVehicleError.innerHTML = '';
        
        $('#vehiclesTable tr').each(function (row, tr) {
            $(tr).find('td:eq(0)').removeClass('borderRedFocus');
        });
        
        $("#suggesstion-box").hide();
        $('#brand').prop('disabled', true);
        $('#model').prop('disabled', true);
        $('#year').prop('disabled', true);
        $('#color').prop('disabled', true);
        document.getElementById("brand").value = "0";
        document.getElementById("model").value = "";
        document.getElementById("year").value = "0";
        document.getElementById("color").value = "";
        var brand = document.getElementById("brand");
        $(brand).removeClass('inputRedFocus');
        var model = document.getElementById("model");
        $(model).removeClass('inputRedFocus');
        var color = document.getElementById("color");
        $(color).removeClass('inputRedFocus');
        var year = document.getElementById("year");
        $(year).removeClass('inputRedFocus');
        

    };
    $("#plate").keyup(function () {
        var plateAlert4 = document.getElementById("plateAlert4");
        $(plateAlert4).addClass('hidden');
        var plateAlert3 = document.getElementById("plateAlert3");
        $(plateAlert3).addClass('hidden');
        var plateAlert2 = document.getElementById("plateAlert2");
        $(plateAlert2).addClass('hidden');
        var plate = document.getElementById("plate");
        $(plate).removeClass('inputRedFocus');
        var yearVehicleError = document.getElementById("yearVehicleError");
        yearVehicleError.innerHTML = '';
        
        $('#vehiclesTable tr').each(function (row, tr) {
            $(tr).find('td:eq(0)').removeClass('borderRedFocus');
        });
        
        $("#suggesstion-box").hide();
        $('#brand').prop('disabled', true);
        $('#model').prop('disabled', true);
        $('#year').prop('disabled', true);
        $('#color').prop('disabled', true);
        document.getElementById("brand").value = "0";
        document.getElementById("model").value = "";
        document.getElementById("year").value = "0";
        document.getElementById("color").value = "";
        var brand = document.getElementById("brand");
        $(brand).removeClass('inputRedFocus');
        var model = document.getElementById("model");
        $(model).removeClass('inputRedFocus');
        var color = document.getElementById("color");
        $(color).removeClass('inputRedFocus');
        var year = document.getElementById("year");
        $(year).removeClass('inputRedFocus');
        
    });
    document.getElementById("document").onclick = function () {
        $("#suggesstion-box").hide();
        document.getElementById("first_name").value = "";
        document.getElementById("document_id").value = "0";
        document.getElementById("last_name").value = "";
        document.getElementById("mobile_phone").value = "";
        document.getElementById("phone").value = "";
        document.getElementById("address").value = "";
        document.getElementById("email").value = "";
        document.getElementById("country").value = "0";
        document.getElementById("province").value = "0";
        document.getElementById("city").value = "0";
        document.getElementById("first_name").disabled=true;
        document.getElementById("last_name").disabled=true;
        document.getElementById("document_id").disabled=true;

    };
      $("#document").keyup(function () {
        $("#suggesstion-box").hide();
        document.getElementById("first_name").value = "";
        document.getElementById("document_id").value = "0";
        document.getElementById("last_name").value = "";
        document.getElementById("mobile_phone").value = "";
        document.getElementById("phone").value = "";
        document.getElementById("address").value = "";
        document.getElementById("email").value = "";
        document.getElementById("country").value = "0";
        document.getElementById("province").value = "0";
        document.getElementById("city").value = "0";
        document.getElementById("first_name").disabled=true;
        document.getElementById("last_name").disabled=true;
        document.getElementById("document_id").disabled=true;
    });
    document.getElementById("documentBtn").onclick = function () {
        formAutoFill(document.getElementById("document").value);
    };
    $(document).on('click', 'body *', function () {
        $("#suggesstion-box").hide();
    });
//    $("#document").on('change', function () {
//        formAutoFill(document.getElementById("document").value);
//    });

    
    $("#document").keyup(function () {
//        if (this.value.length < 4) {
//            $("#suggesstion-box").hide();
//            return;
//        } else {
//
//
//            $.ajax({
//                type: "GET",
//                data: 'keyword=' + $(this).val(),
//                url: '/customer/document/check/' + $(this).val(),
//                beforeSend: function () {
//                    $("#document").css("background", "#FFF url(LoaderIcon.gif) no-repeat 165px");
//                },
//                success: function (data) {
//                    $("#suggesstion-box").show();
//                    $("#suggesstion-box").html(data);
//                    $("#document").css("background", "#FFF");
//                }
//            });
//        }
    });
    //Validate second Step Form
    //Validate Inputs
//    document.getElementById("first_name").change = function () {
    $("#first_name").change(function () {
        $(this).removeClass('inputRedFocus');
    });
    $("#document").change(function () {
        $(this).removeClass('inputRedFocus');
    });
    $("#document_id").change(function () {
        $(this).removeClass('inputRedFocus');
    });
    $("#last_name").change(function () {
        $(this).removeClass('inputRedFocus');
    });
    $("#address").change(function () {
        $(this).removeClass('inputRedFocus');
    });
    $("#mobile_phone").change(function () {
        $(this).removeClass('inputRedFocus');
    });
    $("#phone").change(function () {
        $(this).removeClass('inputRedFocus');
    });
    $("#email").change(function () {
        $(this).removeClass('inputRedFocus');
    });
    $("#country").change(function () {
        $(this).removeClass('inputRedFocus');
    });
    $("#province").change(function () {
        $(this).removeClass('inputRedFocus');
    });
    $("#city").change(function () {
        $(this).removeClass('inputRedFocus');
    });
    
    //Email Change
    $("#email").change(function () {
//        console.log('entro');
        ValidateEmail();
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
    $('#mobile_phone').on('keyup', function(){
        if( /[^0-9]/.test( this.value ) ) {
            alert('No puede ingresar Caracteres Especiales');
            var str= this.value;
            var newStr = str.substring(0, str.length - 1);
            this.value=newStr;
            this.focus();
        }
        this.value = this.value.toLocaleUpperCase();

    });
    $('#phone').on('keyup', function(){
        if( /[^0-9]/.test( this.value ) ) {
            alert('No puede ingresar Caracteres Especiales');
            var str= this.value;
            var newStr = str.substring(0, str.length - 1);
            this.value=newStr;
            this.focus();
        }
        this.value = this.value.toLocaleUpperCase();

    });
    
});

function selectDocument(value) {
    $("#document").val(value);
    $("#suggesstion-box").hide();

    formAutoFill(value);
}

function formAutoFill(val) {
    var documentNumber = val;
    var url = ROUTE + '/customer/document/autofill/' + documentNumber;
    if (documentNumber) {
        $.ajax({
            url: url,
            type: "GET",
            dataType: "json",
            beforeSend: function () {
                // Show Loader
//                $("#loaderGif").addClass('loaderGif');
//                $("#loaderBody").addClass('loaderBody');
            },
            success: function (data) {
//                console.log(data);
                if(data.success == 'true'){
                    document.getElementById("first_name").disabled = true;
                    document.getElementById("last_name").disabled = true;
                    document.getElementById("document_id").disabled = true;
                    
                    //Province Select
                    var prov = document.getElementById("province");
                    prov.innerHTML = '';
                    var opt = document.createElement('option');
                    var opt2 = document.createElement('option');
                    opt.value = '0';
                    opt.text = '--Escoja Una--';
                    opt2.value = data['province_id'];
                    opt2.text = data['province_name'];
                    prov.appendChild(opt);
                    prov.appendChild(opt2);

                    //City Select
                    var city = document.getElementById("city");
                    city.innerHTML = '';
                    var cityOpt = document.createElement('option');
                    var cityOpt2 = document.createElement('option');
                    cityOpt.value = '0';
                    cityOpt.text = '--Escoja Una--';
                    city.appendChild(cityOpt);
                    cityOpt2.value = data['city_id'];
                    cityOpt2.text = data['city_name'];
                    city.appendChild(cityOpt2);
                    $("#salesForm").autofill(data);
                }else{
                    document.getElementById("first_name").disabled = false;
                    document.getElementById("last_name").disabled = false;
                    document.getElementById("document_id").disabled = false;
                    //City Select
                    var city = document.getElementById("city");
                    city.innerHTML = '';
                    var cityOpt = document.createElement('option');
                    cityOpt.value = '0';
                    cityOpt.text = '--Escoja Una--';
                    city.appendChild(cityOpt);
                }
            },
            complete: function () {
                //Hide Loader
//                var loaderGif = document.getElementById("loaderGif");
//                loaderGif.classList.remove("loaderGif");
//                var loaderBody = document.getElementById("loaderBody");
//                loaderBody.classList.remove("loaderBody");
            }
        });
        secondStepFormValidate();
    } else {
        $('select[name="province"]').empty();
    }

}
function myButton_onclick() {
    $(".alert").addClass('hidden');
    ;
}
;

// Vehicles Table
function addRow() {

    var plateAlert2 = document.getElementById("plateAlert2");
    $(plateAlert2).addClass('hidden');
    
    var plate = document.getElementById("plate");
    var brand = document.getElementById("brand");
    var model = document.getElementById("model");
    var year = document.getElementById("year");
    var color = document.getElementById("color");
    var bodyTable = document.getElementById("vehiclesBodyTable");

    var rowCount = bodyTable.rows.length;
    var row = bodyTable.insertRow(rowCount);


//    row.insertCell(0).innerHTML = rowCount;
    row.insertCell(0).innerHTML = plate.value;
    row.insertCell(1).innerHTML = brand.value;
    row.insertCell(2).innerHTML = model.value;
    row.insertCell(3).innerHTML = year.value;
    row.insertCell(4).innerHTML = color.value;
//    row.insertCell(5).innerHTML = '<input type="button" value = "Delete" onClick="Javacsript:deleteRow(this)">';
    row.insertCell(5).innerHTML = '<button type="submit" class="btn btn-link" onClick="Javacsript:deleteRow(this)"><span class="glyphicon glyphicon-remove" style="color:red;font-size:18px"></span></button>';

    var rowVehicles = 0;
    $('#vehiclesTable tr').each(function () {
        var vehiclesFirst = $(this).find("td:first").html();
//        var vehiclesSecond = $(this).find("td:nth-child(2)").html();    
//        var vehiclesThird = $(this).find("td:nth-child(3)").html();    
//        console.log(vehiclesFirst);
//        console.log(vehiclesSecond);
//        console.log(vehiclesThird);
    });

    var table = document.getElementById("vehiclesTable");

    var rowCountTable = table.rows.length;

    if (rowCountTable > 3) {
        $('#btnVehicles').attr('disabled', 'disabled');
        $('#btnVehicles').prop('disabled', true);
    }

    //Return Inputs no Null
    plate.value = '';
    brand.value = '0';
    model.value = '';
    year.value = '0';
    color.value = '';

    //Disable Inputs
    $('#brand').prop('disabled', true);;
    $('#model').prop('disabled', true);
    $('#year').prop('disabled', true);
    $('#color').prop('disabled', true);
    
}

function deleteRow(obj) {

    var index = obj.parentNode.parentNode.rowIndex;
    var table = document.getElementById("vehiclesTable");
    table.deleteRow(index);

//    var addButton = document.getElementById("btnVehicles");
//    var table = document.getElementById("vehiclesTable");
    var rowCount = table.rows.length;

//    console.log(rowCount);
    if (rowCount < 4) {
        $('#btnVehicles').removeAttr('disabled');
        $('#btnVehicles').prop('disabled', false);
        ;
    }
}


// Third Step Validation
function thirdStepValidate() {
    
//    console.log('validando');

    var plate = document.getElementById("plate");
    var brand = document.getElementById("brand");
    var model = document.getElementById("model");
    var color = document.getElementById("color");
    var year = document.getElementById("year");
    var validate = false;
    if (brand.value === "0") {
        $(brand).addClass('inputRedFocus');
        validate = true;
    }else{
        $(brand).removeClass('inputRedFocus');
    }
    if (model.value === "") {
        $(model).addClass('inputRedFocus');
        validate = true;
    }else{
        $(model).removeClass('inputRedFocus');
    }
    if (color.value === "") {
        $(color).addClass('inputRedFocus');
        validate = true;
    }else{
        $(color).removeClass('inputRedFocus');
    }
    var numbers = /^[0-9]+$/;
    if (year.value === "0" || !year.value.match(numbers) || year.value === "") {
        $(year).addClass('inputRedFocus');
        validate = true;
    }else{
        $(year).removeClass('inputRedFocus');        
    }
    
    if(validate === false){
        addRow();
    }
};

function secondStepFormValidate() {
    //Validate Inputs
    var documentNumber = document.getElementById("document");
//        if (documentNumber.value !== "") {
    $(documentNumber).removeClass('inputRedFocus');
//            validate = 'true';
//        }
    var first_name = document.getElementById("first_name");
//        if (first_name.value !== "") {
    $(first_name).removeClass('inputRedFocus');
//            validate = 'true';
//        }
    var document_id = document.getElementById("document_id");
//        if (document_id.value !== "0") {
    $(document_id).removeClass('inputRedFocus');
//            validate = 'true';
//        }
    var last_name = document.getElementById("last_name");
//        if (last_name.value !== "") {
    $(last_name).removeClass('inputRedFocus');
//            validate = 'true';
//        }
    var address = document.getElementById("address");
//        if (address.value !== "") {
    $(address).removeClass('inputRedFocus');
//            validate = 'true';
//        }
    var mobile_phone = document.getElementById("mobile_phone");
//        if (mobile_phone.value !== "") {
    $(mobile_phone).removeClass('inputRedFocus');
//            validate = 'true';
//        }
    var phone = document.getElementById("phone");
//        if (phone.value !== "") {
    $(phone).removeClass('inputRedFocus');
//            validate = 'true';
//        }
    var email = document.getElementById("email");
//        if (email.value !== "") {
    $(email).removeClass('inputRedFocus');
//            validate = 'true';
//        }
    var country = document.getElementById("country");
//        if (country.value !== "0") {
    $(country).removeClass('inputRedFocus');
//            validate = 'true';
//        }
    var province = document.getElementById("province");
//        if (province.value !== "0") {
    $(province).removeClass('inputRedFocus');
//            validate = 'true';
//        }
    var city = document.getElementById("city");
//        if (city.value !== "0") {
    $(city).removeClass('inputRedFocus');
//            validate = 'true';
//        }
    var customerAlert = document.getElementById("customerAlert");
    $(customerAlert).removeClass('visible');
    $(customerAlert).addClass('hidden');
}
;
function checkValidInput() {
    $(".yearVehicle").keydown(function (event) {
        // Allow only delete, backspace,left arrow,right arrow, Tab and numbers
        if (!((event.keyCode == 46 ||
                event.keyCode == 8 ||
                event.keyCode == 37 ||
                event.keyCode == 39 ||
                event.keyCode == 9) ||
                $(this).val().length < 4 &&
                ((event.keyCode >= 48 && event.keyCode <= 57) ||
                        (event.keyCode >= 96 && event.keyCode <= 105)))) {
            // Stop the event
            event.preventDefault();
            return false;
        }
    });
};
function validateCode (){
    event.preventDefault();
    var url = ROUTE + "/sales/activate";
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var code = document.getElementById('code').value;
    var salId = document.getElementById('salId').value;
    var data = {code: code};
    $.ajax({
        url: url,
        type: "POST",
        data: {_token: CSRF_TOKEN, code, salId},
        success: function (data)
        {
            var uploadPic = document.getElementById("resultMessage");
            uploadPic.innerHTML = data.data;
            if(data.success == 'true'){
                document.getElementById("confirmModal").click();
            }
        }
    });
};
function resendCode (){
    event.preventDefault();
    var url = ROUTE + "/sales/resend/code";
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var salId = document.getElementById('salId').value;
    var customerMobilePhone = document.getElementById("mobile_phone").value;
//    var data = {code: salId};
    $.ajax({
        url: url,
        type: "POST",
        data: {_token: CSRF_TOKEN,salId, customerMobilePhone},
        success: function (data)
        {
            var uploadPic = document.getElementById("resultMessage");
            uploadPic.innerHTML = data;
        }
    });
};

function ValidateEmail(mail) 
{
 if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(document.getElementById("email").value))
  {
    document.getElementById("emailError").innerHTML = '';
    return (true);
  }else{
    var txt = 'Por favor ingrese un correo valido';
    document.getElementById("emailError").innerHTML = txt;
//    document.getElementById("email").value = '';
    return (false);
  }
}
function documentBtn(){
//        console.log('hola');
        formAutoFill(document.getElementById("document").value);
    }
function plateBtn(){
        $('#brand').prop('disabled', true);
        $('#model').prop('disabled', true);
        $('#year').prop('disabled', true);
        $('#color').prop('disabled', true);
        var brand = document.getElementById("brand");
        $(brand).removeClass('inputRedFocus');
        var model = document.getElementById("model");
        $(model).removeClass('inputRedFocus');
        var color = document.getElementById("color");
        $(color).removeClass('inputRedFocus');
        var year = document.getElementById("year");
        $(year).removeClass('inputRedFocus');
        var plate = document.getElementById('plate').value;
        var plateAlert = document.getElementById('plateAlert');
        var plateAlert2 = document.getElementById('plateAlert2');
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var data = {plate: plate};
            var url = '/vehicules/check/plate';
            $.ajax({
                type: "POST",
                data: {_token: CSRF_TOKEN, data},
                url: url,
                beforeSend: function() {
                    var loaderGif = document.getElementById("loaderGif");
                    loaderGif.className += "loaderGif";
                },
                success: function (data) {
                    if(data['validate'] == 'true'){
                        $(plateAlert).removeClass('hidden');
                        $(plateAlert2).addClass('hidden');
//                        document.getElementById("plate").value = '';
                    }else if(data['validate'] == 'error'){
                        $(plateAlert).addClass('hidden');
                        $(plateAlert2).removeClass('hidden'); 
                        plateAlert2.innerHTML = data['message']; 
                    }else if(data['validate'] == 'antDown'){
                        $(plateAlert).addClass('hidden');
                        $(plateAlert2).removeClass('hidden'); 
                        plateAlert2.innerHTML = data['message']; 
                        $('#brand').prop('disabled', false);
                        $('#model').prop('disabled', false);
                        $('#year').prop('disabled', false);
                        $('#color').prop('disabled', false);
                        document.getElementById("brand").value = data['brand'];
                        document.getElementById("model").value = data['model'];
                        document.getElementById("year").value = data['year'];
                        document.getElementById("color").value = data['color'];
                    }else{
                        $(plateAlert).addClass('hidden');
                        $(plateAlert2).addClass('hidden');
                        document.getElementById("brand").value = data['brand'];
                        document.getElementById("model").value = data['model'];
                        document.getElementById("year").value = data['year'];
                        document.getElementById("color").value = data['color'];
                    }
                },
                complete: function() {
                    var loaderGif = document.getElementById("loaderGif");
                    loaderGif.classList.remove("loaderGif");
                },
            });
    }
    
    function checkPlate(plate) {
      return plate == document.getElementById('plate').value;
    }