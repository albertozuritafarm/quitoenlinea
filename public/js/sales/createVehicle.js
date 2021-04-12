/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {
    
    $('#plateForm').keypress(function(e){
      if(e.keyCode==13){
          e.preventDefault();
          $('#plateBtn').click();
      }
    });
    
    function setInputFilter(textbox, inputFilter) {
        ["input"].forEach(function(event) {
            textbox.addEventListener(event, function() {
            if (inputFilter(this.value)) {
                this.oldValue = this.value;
                this.oldSelectionStart = this.selectionStart;
                this.oldSelectionEnd = this.selectionEnd;
            } else if (this.hasOwnProperty("oldValue")) {
                this.value = this.oldValue;
                this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
            } else {
                this.value = "";
            }
            });
        });
    }
    
    setInputFilter(document.getElementById("vehiValue"), function(value) {
        return /^-?\d*[.]?\d{0,2}$/.test(value); 
    });

    var div = document.getElementById('vehicleForm');
    $(div).fadeOut();

    //Btn Add Vehicles
    document.getElementById("btnVehicles").onclick = function () {
        event.preventDefault();
        var table = document.getElementById("vehiclesTable");
        var rowCountTable = table.rows.length;
        if (rowCountTable > 5) {
            $('#btnVehicles').attr('disabled', 'disabled');
            $('#btnVehicles').prop('disabled', true);
        } else {
            thirdStepValidate();
        }
    };

    //Vehicle Year Change
    $("#year").change(function () {
        var inputYear = $(this).val();
        var _thisYear = new Date().getFullYear() + 1;
        if (parseInt($(this).val()) > _thisYear) {
            var txt = 'Por favor ingrese un año valido, ' + inputYear + ' no es un año valido.';
//            document.getElementById("yearVehicleError").innerHTML = txt;
            document.getElementById("year").value = "";
        } else if (parseInt($(this).val()) < (new Date().getFullYear() - 8)) {
            var txt = 'El Vehiculo no puede tener una antiguedad mayor a 8 años (' + inputYear + ')';
//            document.getElementById("yearVehicleError").innerHTML = txt;
            document.getElementById("year").value = "";
        } else {
//            document.getElementById("yearVehicleError").innerHTML = "";
        }
    });
//    $("#vehiValue").change(function () {
//        var vehiValue = $(this).val();
//        var numbers = /^[0-9]+$/;
//        if (!vehiValue.match(numbers)) {
//            $(this).addClass('inputRedFocus');
//        }
//    });
//Vehicle Plate Change
    document.getElementById("plateBtn").onclick = function () {
        var newVehicle = document.getElementById('newVehicle');
        clearVehicleForm();
        var TableData = new Array();

        $('#vehiclesTable tr').each(function (row, tr) {
            TableData += [$(tr).find('td:eq(1)').text()] + ',0,';
        });
        
        var table = document.getElementById("vehiclesTable");
        var newVehicle = document.getElementById("newVehicle");
        if(newVehicle.value === ''){
            $(newVehicle).addClass('inputRedFocus');
            return false;
        }else{
            $(newVehicle).removeClass('inputRedFocus');
        }
        var tbodyRowCount = table.tBodies[0].rows.length;
        if(tbodyRowCount == 0){
            var resultNewVehicle = 'ok';
        }else{
            var TableDataNewVehicle = new Array();

            $('#vehiclesTable tr').each(function (row, tr) {
                TableDataNewVehicle += [$(tr).find('td:eq(11)').text()] + ',0,';
            });
            var resultNewVehicleSearch = TableDataNewVehicle.includes(newVehicle.value);
            if(resultNewVehicleSearch == true){
                var resultNewVehicle = 'ok';
            }else{
                var resultNewVehicle = 'not ok';
            }
        }
        var result = TableData.includes(document.getElementById('plateForm').value);

        if (result == false) {
//            if (newVehicle.value == 'Usado' || newVehicle.value == 'Nuevo' && resultNewVehicle == false) {
            if (resultNewVehicle == 'ok') {
                $(newVehicle).removeClass('inputRedFocus');
                var plateAlert4 = document.getElementById("plateAlert4");
                $(plateAlert4).addClass('hidden');
                var plate = document.getElementById("plateForm");
//                var myString = plate.value.replace(/\D/g, '');
//                if (myString.length != 4) {
//                    $(plate).addClass('inputRedFocus');
//                    var plateAlert3 = document.getElementById("plateAlert3");
//                    $(plateAlert3).removeClass('hidden');
//                } else {
                    $(plate).removeClass('inputRedFocus');
                    var plateAlert3 = document.getElementById("plateAlert3");
                    $(plateAlert3).addClass('hidden');
                    var thirdStepAlert = document.getElementById("thirdStepAlert");
                    $(thirdStepAlert).addClass('hidden');
                    plateBtn();
//                }
            } else {
                $(newVehicle).addClass('inputRedFocus');
            }
        } else {
            var plateAlert4 = document.getElementById("plateAlert4");
            $(plateAlert4).removeClass('hidden');
            var plateAlert4 = document.getElementById("plateForm");
            $(plateAlert4).addClass('inputRedFocus');
            $('#vehiclesTable tr').each(function (row, tr) {
                if (document.getElementById('plate').value === $(tr).find('td:eq(0)').text()) {
                    $(tr).find('td:eq(1)').addClass('borderRedFocus');
                } else {
                    $(tr).find('td:eq(1)').removeClass('borderRedFocus');
                }
            });
        }

    };
    //Price Btn
    document.getElementById("priceBtn").onclick = function () {
       var vehiPrice = document.getElementById("vehiPrice"); 
       if(vehiPrice.value == ''){
//           $(vehiPrice).addClass('inputRedFocus');
       }else{
            var validate = false;
            var model = document.getElementById("model"); 
            var brand = document.getElementById("brand"); 
            var year = document.getElementById("year"); 
            
            //Validate data
            if(model.value == ''){ $(model).addClass('inputRedFocus'); validate = true; }else{ $(model).removeClass('inputRedFocus'); }
            if(brand.value == '0'){ $(brand).addClass('inputRedFocus'); validate = true; }else{ $(brand).removeClass('inputRedFocus'); }
            if(year.value == ''){ $(year).addClass('inputRedFocus'); validate = true; }else{ $(year).removeClass('inputRedFocus'); }
            
            //Validate Variable
            if(validate == false){
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                var url = ROUTE + '/vehicles/vehiPrice/modal';
                $.ajax({
                    url: url,
                    type: "POST",
                    /* send the csrf-token and the input to the controller */
                    data: {_token: CSRF_TOKEN, model: model.value, brand:brand.value, year:year.value},
                    success: function (result) {
                        document.getElementById("priceModalSelect").innerHTML = result;
                        document.getElementById("priceModalBtn").click(); 
                    }
                });
            }
       }
    };

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
}

function btnAccVehicles() {
    event.preventDefault();
    //Validate Inputs
    var value = document.getElementById("value");
    var description = document.getElementById("description");
    var validate = true;

    if (value.value === '') { $(value).addClass('inputRedFocus'); validate = false; } else { if (isNaN(value.value.replace(',',''))) { $(value).addClass('inputRedFocus'); validate = false; } else { $(value).removeClass('inputRedFocus'); } }
    if (description.value === '') { $(description).addClass('inputRedFocus'); validate = false; } else { $(description).removeClass('inputRedFocus'); }
    var checked = '-1'; if('.chekVehiclePlate:checked'){ $('.chekVehiclePlate:checked').each(function(i,v){ checked = $(v).val(); }); } if(checked == '-1'){ validate = false; }
    
    if (validate == true) {
        var res = checked.split(",");
        //Obtain Porcentage from Step 3
        var porcentage;
        porcentage = ((res[2] * 20) / 100);
        
        //Obtain Accesories Value
        var accValue = 0;
        $('#vehiclesAccBodyTable tr').each(function (row, tr) {
            if($(tr).find('td:eq(1)').text() == res[1]){
                accValue += Number($(tr).find('td:eq(3)').text().replace(',',''));
            }else{
                accValue += 0;
            }
        });

        //Add Acc Values
        var totalAccValues = Number(accValue) + Number(value.value.replace(',',''));
        
        if (totalAccValues > porcentage) {
            alert('El valor de los accesorios no pueden ser mayor al 20% del valor asegurado del vehículo');
        } else {
            addAccRow(res[0],res[1]);
        }
    }
}

// Vehicles Table
function addRow() {

    var plateAlert2 = document.getElementById("plateAlert2");
    $(plateAlert2).addClass('hidden');

    var plate = document.getElementById("plate");
    var ramv = document.getElementById("ramv");
    var brand = document.getElementById("brand");
    var model = document.getElementById("model");
    var year = document.getElementById("year");
    var vehiClass = document.getElementById("vehiClass");
    var vehiType = document.getElementById("vehiType");
    var vehiValue = document.getElementById("vehiValue");
    var vehiPrice = document.getElementById("vehiPrice");
    var newVehicle = document.getElementById("newVehicle");
    var registration = document.getElementById("registration");
    var chassis = document.getElementById("chassis");
    var bodyTable = document.getElementById("vehiclesBodyTable");
    var rowCount = bodyTable.rows.length;
    var row = bodyTable.insertRow(rowCount);


//    row.insertCell(0).innerHTML = rowCount;
    row.insertCell(0).innerHTML = ramv.value;
    row.insertCell(1).innerHTML = plate.value;
    row.insertCell(2).innerHTML = model.value;
    row.insertCell(3).innerHTML = brand.value;
    row.insertCell(4).innerHTML = vehiClass.value;
    row.insertCell(5).innerHTML = year.value;
    row.insertCell(6).innerHTML = registration.value;
    row.insertCell(7).innerHTML = chassis.value;
    row.insertCell(8).innerHTML = vehiType.value;
    row.insertCell(9).innerHTML = vehiPrice.value;
    row.insertCell(10).innerHTML = vehiValue.value;
    row.insertCell(11).innerHTML = newVehicle.value;
    row.insertCell(12).innerHTML = '<button type="submit" class="btn btn-link" onClick="Javascript:editRow(\'' + plate.value + '\',\'' + brand.value + '\', \'' + model.value + '\', \'' + year.value + '\', \'' + vehiClass.value + '\', \'' + vehiType.value + '\', \'' + vehiValue.value + '\', \'' + vehiPrice.value + '\', \'' + newVehicle.value + '\', \'' + chassis.value + '\', \'' + registration.value + '\',this)"><span class="glyphicon glyphicon-pencil" style="color:green;font-size:18px"></span></button><br> <button type="submit" class="btn btn-link" onClick="Javacsript:deleteRow(this)"><span class="glyphicon glyphicon-remove" style="color:red;font-size:18px"></span></button>';

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

    if (rowCountTable > 5) {
        $('#btnVehicles').attr('disabled', 'disabled');
        $('#btnVehicles').prop('disabled', true);
        $('#plateBtn').attr('disabled', 'disabled');
        $('#plateBtn').prop('disabled', true);
    }

    //Return Inputs no Null
    plate.value = '';
    brand.value = '0';
    model.value = '';
    year.value = '0';
    vehiClass.value = '';
    vehiType.value = '0';
    vehiValue.value = '';
    vehiPrice.value = '';
    newVehicle.value = '';
    registration.value = '';
    chassis.value = '';

    //Disable Inputs
    $('#brand').prop('disabled', true);
    $('#model').prop('disabled', true);
    $('#year').prop('disabled', true);

}

function editRow(plate, brand, model, year, vehiClass, vehiType, vehiValue, vehiPrice, newVehicle, chassis, registration, obj) {
    event.preventDefault();
    document.getElementById("plate").value = plate;
    document.getElementById("brand").value = brand;
    document.getElementById("model").value = model;
    document.getElementById("year").value = year;
    document.getElementById("vehiClass").value = vehiClass;
    document.getElementById("vehiType").value = vehiType;
    document.getElementById("vehiValue").value = vehiValue;
    document.getElementById("vehiPrice").value = vehiPrice;
    document.getElementById("newVehicle").value = newVehicle;
    document.getElementById("chassis").value = chassis;
    document.getElementById("registration").value = registration;

    var index = obj.parentNode.parentNode.rowIndex;
    var table = document.getElementById("vehiclesTable");
    table.deleteRow(index);

    var table = document.getElementById("vehiclesTable");

    var rowCountTable = table.rows.length;

    if (rowCountTable < 6) {
        $('#btnVehicles').removeAttr('disabled');
        $('#btnVehicles').prop('disabled', false);
        $('#plateBtn').removeAttr('disabled');
        $('#plateBtn').prop('disabled', false);
    }
}
// Vehicles Table
function addAccRow(ramv,plate) {
    var description = document.getElementById("description");
    var value = document.getElementById("value");
    var bodyTable = document.getElementById("vehiclesAccBodyTable");
    var rowCount = bodyTable.rows.length;
    var row = bodyTable.insertRow(rowCount);

    row.insertCell(0).innerHTML = ramv;
    row.insertCell(1).innerHTML = plate;
    row.insertCell(2).innerHTML = description.value;
    row.insertCell(3).innerHTML = value.value;
    row.insertCell(4).innerHTML = '<button type="submit" class="btn btn-link" onClick="Javacsript:deleteAccRow(this)"><span class="glyphicon glyphicon-remove" style="color:red;font-size:18px"></span></button>';

    //Return Inputs no Null
    description.value = '';
    value.value = '';
}

function deleteRow(obj) {
    var index = obj.parentNode.parentNode.rowIndex;
    var table = document.getElementById("vehiclesTable");
    table.deleteRow(index);

//    var addButton = document.getElementById("btnVehicles");
//    var table = document.getElementById("vehiclesTable");
    var rowCount = table.rows.length;

//    console.log(rowCount);
    if (rowCount < 6) {
        $('#btnVehicles').removeAttr('disabled');
        $('#btnVehicles').prop('disabled', false);
        $('#plateBtn').removeAttr('disabled');
        $('#plateBtn').prop('disabled', false);
    }
}
function deleteAccRow(obj) {

    var index = obj.parentNode.parentNode.rowIndex;
    var table = document.getElementById("vehiclesAccTable");
    table.deleteRow(index);
}

// Third Step Validation
function thirdStepValidate() {
    var plate = document.getElementById("plate");
    var ramv = document.getElementById("ramv");
    var brand = document.getElementById("brand");
    var model = document.getElementById("model");
    var vehiClass = document.getElementById("vehiClass");
    var year = document.getElementById("year");
    var vehiType = document.getElementById("vehiType");
    var vehiValue = document.getElementById("vehiValue");
    var vehiValueString = vehiValue.value.replace(/,/g, ''); 
    var newVehicle = document.getElementById("newVehicle");
    var registration = document.getElementById("registration");
    var chassis = document.getElementById("chassis");
    var vehiPrice = document.getElementById("vehiPrice");
    var vehiPriceString = vehiPrice.value.replace(/,/g, ''); 
    var vehiPriceString = vehiPrice.value;
    vehiPriceString = vehiPriceString.replace('.','');
    var vehiPriceFinal = vehiPriceString * 0.01;
    var vehiPrice10 = vehiPriceFinal * 0.1;
    var vehiPriceMin = vehiPriceFinal - vehiPrice10;
    var vehiPriceMax = vehiPriceFinal + vehiPrice10;
    var validate = false;

    if(plate.value === ""){ $(plate).addClass('inputRedFocus'); validate = true; }else{ $(plate).removeClass('inputRedFocus'); }
    if (brand.value === "0") { $(brand).addClass('inputRedFocus'); validate = true; } else { $(brand).removeClass('inputRedFocus'); }
    if (model.value === "") { $(model).addClass('inputRedFocus'); validate = true; } else { $(model).removeClass('inputRedFocus'); }
    if (vehiClass.value === "") { $(vehiClass).addClass('inputRedFocus'); validate = true; } else { $(vehiClass).removeClass('inputRedFocus'); }
//    var numbers = /^[0-9]+$/;
    var numbers = /^-?\d*[.]?\d{0,2}$/;
    if (year.value === "0" || !year.value.match(numbers) || year.value === "") { $(year).addClass('inputRedFocus'); validate = true; } else { $(year).removeClass('inputRedFocus'); }
    if (vehiType.value === "") { $(vehiType).addClass('inputRedFocus'); validate = true; } else { $(vehiType).removeClass('inputRedFocus'); }
    if (vehiValue.value === "0" || !vehiValueString.match(numbers) || vehiValue.value === "") { $(vehiValue).addClass('inputRedFocus'); validate = true; } else { $(vehiValue).removeClass('inputRedFocus'); }
    if (newVehicle.value === "") { $(newVehicle).addClass('inputRedFocus'); validate = true; } else { $(newVehicle).removeClass('inputRedFocus'); }
    if (registration.value === "") { validate = true; $(registration).addClass("inputRedFocus"); } else { $(registration).removeClass("inputRedFocus"); }
    if (chassis.value === "") { validate = true; $(chassis).addClass("inputRedFocus"); } else { $(chassis).removeClass("inputRedFocus"); }
    if(vehiPrice.value === '0'){ $(vehiPrice).addClass('inputRedFocus'); validate = true; }else{ $(vehiPrice).removeClass('inputRedFocus'); }
        
    if(vehiValue.value === ''){
        $(vehiValue).addClass('inputRedFocus'); validate = true;
    }else{
        if (vehiValueString !== "0" || vehiValueString.match(numbers) || vehiValueString !== "") {
            if(Number(vehiValueString) < Number(vehiPriceMin) || Number(vehiValueString) > Number(vehiPriceMax)){
                $(vehiValue).addClass('inputRedFocus'); validate = true;
                var vehiPriceAlert = document.getElementById("vehiPriceAlert");
                $(vehiPriceAlert).removeClass('hidden');
            }else{
                $(vehiValue).removeClass('inputRedFocus');
                var vehiPriceAlert = document.getElementById("vehiPriceAlert");
                $(vehiPriceAlert).addClass('hidden');
            }
        }else{
        }
    }

    if (validate === false) { addRow(); }
}

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
}

function plateBtn() {
    $('#brand').prop('disabled', true);
    $('#model').prop('disabled', true);
    $('#year').prop('disabled', true);
    var brand = document.getElementById("brand");
    $(brand).removeClass('inputRedFocus');
    var model = document.getElementById("model");
    $(model).removeClass('inputRedFocus');
    var year = document.getElementById("year");
    $(year).removeClass('inputRedFocus');
    var plate = document.getElementById('plateForm').value;
    var plateAlert = document.getElementById('plateAlert');
    var plateAlert2 = document.getElementById('plateAlert2');
    var newVehicle = document.getElementById('newVehicle');
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var data = {plate: plate, newVehicle: newVehicle.value};
    var url = ROUTE + '/vehicules/check/plate';
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, data},
        url: url,
        beforeSend: function () {
            var loaderGif = document.getElementById("loaderGif");
            loaderGif.className += "loaderGif";
        },
        success: function (data) {
            if (data['validate'] == 'true') {
                $(plateAlert).removeClass('hidden');
                $(plateAlert2).addClass('hidden');
//                        document.getElementById("plate").value = '';
            } else if (data['validate'] == 'error') {
                $(plateAlert).addClass('hidden');
                $(plateAlert2).removeClass('hidden');
                plateAlert2.innerHTML = data['message'];
            } else if (data['validate'] == 'priceError'){
                $(plateAlert).addClass('hidden');
                $(plateAlert2).removeClass('hidden');
                plateAlert2.innerHTML = data['message'];
            } else if (data['validate'] == 'antDown') {
//                $(plateAlert).addClass('hidden');
//                $(plateAlert2).removeClass('hidden');
//                plateAlert2.innerHTML = data['message'];
                $('#brand').prop('disabled', false);
                $('#model').prop('disabled', false);
                $('#year').prop('disabled', false);
                $('#ramv').prop('disabled', false);
                $('#plate').prop('disabled', false);
                $('#registration').prop('disabled', false);
                $('#chassis').prop('disabled', false);
                document.getElementById("brand").value = data['brand'];
                document.getElementById("model").value = data['model'];
                document.getElementById("year").value = data['year'];
                document.getElementById("vehiType").value = data['type'];
                document.getElementById("vehiPrice").value = data['price'];
                document.getElementById("vehiClass").value = data['class'];
            } else {
                $(plateAlert).addClass('hidden');
                $(plateAlert2).addClass('hidden');
                document.getElementById("plate").value = data['plate'];
                document.getElementById("brand").value = data['brand'];
                document.getElementById("model").value = data['model'];
                document.getElementById("year").value = data['year'];
                document.getElementById("vehiType").value = data['type'];
                document.getElementById("vehiPrice").value = data['price'];
                document.getElementById("vehiClass").value = data['class'];
                document.getElementById("ramv").value = data['ramv'];
                document.getElementById("chassis").value = data['chasis'];
                document.getElementById("registration").value = data['motor'];
            }
        },
        complete: function () {
            var loaderGif = document.getElementById("loaderGif");
            loaderGif.classList.remove("loaderGif");
        }
    });
}

function fourthStepBtnNext(canalPlanId, today, oneYear, agenciaSS, ramo, value, proId) {
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

    //Obtain Checked Producto from 
    var selectedProduct = document.getElementById("productCheckBox").value;
    var selectedProductName = document.getElementById("productNameCheckBox").value;
    var selectedProductValue = document.getElementById("productValueCheckBox").value;

    //Obtain Vehicles Data from Step 3
    var TableData = new Array();

    $('#vehiclesBodyTable tr').each(function (row, tr) {
        TableData[row] = {
            "ramv": $(tr).find('td:eq(0)').text()
            , "plate": $(tr).find('td:eq(1)').text()
            , "model": $(tr).find('td:eq(2)').text()
            , "brand": $(tr).find('td:eq(3)').text()
            , "type": $(tr).find('td:eq(4)').text()
            , "year": $(tr).find('td:eq(5)').text()
            , "matricula": $(tr).find('td:eq(6)').text()
            , "chassis": $(tr).find('td:eq(7)').text()
            , "vehiType": $(tr).find('td:eq(8)').text()
            , "vehiPrice": $(tr).find('td:eq(9)').text()
            , "vehiValue": $(tr).find('td:eq(10)').text()
            , "newVehicle": $(tr).find('td:eq(11)').text()
        };
    });
    
    //Obtain Accesores Data from Step 3
    var TableDataAcc = new Array();

    $('#vehiclesAccBodyTable tr').each(function (row, tr) {
        TableDataAcc[row] = {
            "ramv": $(tr).find('td:eq(0)').text()
            , "plate": $(tr).find('td:eq(1)').text()
            , "description": $(tr).find('td:eq(2)').text()
            , "value": $(tr).find('td:eq(3)').text()
        };
    });
    
    
    document.getElementById("productCheckBox").value = canalPlanId;

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var data = {canalPlanId: canalPlanId, today: today, oneYear: oneYear, agenciaSS: agenciaSS, ramo: ramo, value: value, proId: proId, vehicles: TableData, TableDataAcc: TableDataAcc};
    var url = ROUTE + '/sales/resume/new/ss';
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, data},
        url: url,
        beforeSend: function () {
            var loaderGif = document.getElementById("loaderGif");
            loaderGif.className += "loaderGif";
        },
        success: function (data) {
            var div = document.getElementById('vehiclesTableBodyResume');

            div.innerHTML = '';
            div.innerHTML += data[0];

            var div = document.getElementById('taxTableBodyResume');
            div.innerHTML = '';
            div.innerHTML += data[1];
            nextStep('fourthStep', 'fifthStep');
        },
        complete: function () {
            var loaderGif = document.getElementById("loaderGif");
            loaderGif.classList.remove("loaderGif");
        }
    });
}

function executeSale() {
    // Obtatin Customer Data
    var customerDocument = document.getElementById("document").value;
    var customerDocumentId = document.getElementById("document_id").value;
    var customerFirstName = document.getElementById("first_name").value;
    var customerSecondName = document.getElementById("second_name").value;
    var customerLastName = document.getElementById("last_name").value;
    var customerSecondLastName = document.getElementById("second_last_name").value;
    var customerPhone = document.getElementById("phone").value;
    var customerMobilePhone = document.getElementById("mobile_phone").value;
    var customerAddress = document.getElementById("address").value;
    var customerEmail = document.getElementById("email").value;
    var customerCountry = document.getElementById("country").value;
    var customerProvince = document.getElementById("province").value;
    var customerCity = document.getElementById("city").value;
    var customerBirthDate = document.getElementById("birthdate").value;

    var customerData = new Array();
    customerData = {
        'document': customerDocument,
        'documentId': customerDocumentId,
        'firstName': customerFirstName,
        'secondName': customerSecondName,
        'lastName': customerLastName,
        'secondLastName': customerSecondLastName,
        'phone': customerPhone,
        'mobilePhone': customerMobilePhone,
        'address': customerAddress,
        'email': customerEmail,
        'country': customerCountry,
        'province': customerProvince,
        'city': customerCity,
        'birthdate' : customerBirthDate
    };

    //Obtain prices table
    var pricesTable = new Array();
    $('#taxTableBodyResume tr').each(function (row, tr) {
        pricesTable[row] = {
            "sBancos": $(tr).find('td:eq(0)').text(),
            "sCampes": $(tr).find('td:eq(1)').text(),
            "dEmisio": $(tr).find('td:eq(2)').text(),
            "subTotal": $(tr).find('td:eq(3)').text(),
            "tax": $(tr).find('td:eq(4)').text(),
            "total": $(tr).find('td:eq(5)').text()
        };
    });

    //Obtain Checked Producto from
    var selectedProduct = document.getElementById("productCheckBox").value;

    //Obtain Vehicles Data from Step 3
    var TableData = new Array();

    $('#vehiclesBodyTable tr').each(function (row, tr) {
        TableData[row] = {
            "ramv": $(tr).find('td:eq(0)').text()
            , "plate": $(tr).find('td:eq(1)').text()
            , "model": $(tr).find('td:eq(2)').text()
            , "brand": $(tr).find('td:eq(3)').text()
            , "type": $(tr).find('td:eq(4)').text()
            , "year": $(tr).find('td:eq(5)').text()
            , "matricula": $(tr).find('td:eq(6)').text()
            , "chassis": $(tr).find('td:eq(7)').text()
            , "vehiType": $(tr).find('td:eq(8)').text()
            , "vehiPrice": $(tr).find('td:eq(9)').text()
            , "vehiValue": $(tr).find('td:eq(10)').text()
            , "newVehicle": $(tr).find('td:eq(11)').text()
        };
    });

    //Obtain Accesores Data from Step 3
    var TableDataAcc = new Array();

    $('#vehiclesAccBodyTable tr').each(function (row, tr) {
        TableDataAcc[row] = {
            "ramv": $(tr).find('td:eq(0)').text()
            , "plate": $(tr).find('td:eq(1)').text()
            , "description": $(tr).find('td:eq(2)').text()
            , "value": $(tr).find('td:eq(3)').text()
        };
    });

    var insuredValue = document.getElementById("vehiValue").value;

    //Obtain Checked Producto from
    var saleMovement = document.getElementById("sale_movement").value;
    var saleId = document.getElementById("sale_id").value;

    //Obtain Check Send Quotation
    var sendQuotation = document.querySelector('.chkBoxSendQuotation').checked;
    
    //Obtain Sale Value
    var sCompania = document.getElementById("sCompania").value;
    var sCampesino = document.getElementById("sCampesino").value;
    var dEmision = document.getElementById("dEmision").value;
    var subtotal12 = document.getElementById("subtotal12").value;
    var subtotal0 = document.getElementById("subtotal0").value;
    var tax = document.getElementById("tax").value;
    var total = document.getElementById("total").value;
    var rate = document.getElementById("rate").value;
    

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var data = {rate:rate, total:total, tax:tax, subtotal12:subtotal12, subtotal0:subtotal0, dEmision:dEmision, sCampesino:sCampesino, sCompania:sCompania, product: selectedProduct, vehicles: TableData, vehiAcc: TableDataAcc, customer: customerData, pricesTable: pricesTable, saleMovement: saleMovement, saleId: saleId, insuredValue: insuredValue, sendQuotation: sendQuotation};
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
            window.location.href = ROUTE + "/sales";
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

function secondStepBtnNext() {
    event.preventDefault();
  

    //Validate Vehicles Table
    var tbl = document.getElementById('vehiclesBodyTable');
    if (tbl.rows.length === 0) {
        var thirdStepAlert = document.getElementById('thirdStepAlert');
        var plateAlert = document.getElementById('plateAlert');
        var plateAlert2 = document.getElementById('plateAlert2');
        var plateAlert3 = document.getElementById('plateAlert3');
        $(thirdStepAlert).removeClass('hidden');
        $(plateAlert).addClass('hidden');
        $(plateAlert2).addClass('hidden');
        $(plateAlert3).addClass('hidden');
        return false;
    } else {
        fillVehicleTableAcc();
        nextStep('secondStep', 'thirdStep');
    }
}

function thirdStepBtnNext(){
    //Obtain Vehicles Data from Step 3
    var TableData = new Array();
    $('#vehiclesBodyTable tr').each(function (row, tr) {
        TableData[row] = {
            "ramv": $(tr).find('td:eq(0)').text()
            , "plate": $(tr).find('td:eq(1)').text()
            , "model": $(tr).find('td:eq(2)').text()
            , "brand": $(tr).find('td:eq(3)').text()
            , "type": $(tr).find('td:eq(4)').text()
            , "year": $(tr).find('td:eq(5)').text()
            , "matricula": $(tr).find('td:eq(6)').text()
            , "chassis": $(tr).find('td:eq(7)').text()
            , "vehiType": $(tr).find('td:eq(8)').text()
            , "vehiPrice": $(tr).find('td:eq(9)').text()
            , "vehiValue": $(tr).find('td:eq(10)').text()
            , "newVehicle": $(tr).find('td:eq(11)').text()
        };
    });

    //Obtain Accesores Data from Step 3
    var TableDataAcc = new Array();

    $('#vehiclesAccBodyTable tr').each(function (row, tr) {
        TableDataAcc[row] = {
            "ramv": $(tr).find('td:eq(0)').text()
            , "plate": $(tr).find('td:eq(1)').text()
            , "description": $(tr).find('td:eq(2)').text()
            , "value": $(tr).find('td:eq(3)').text()
        };
    });
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = ROUTE + '/sales/vehicles/check/price';
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, vehicleData: TableData, vehiAccData: TableDataAcc},
        url: url,
        beforeSend: function () {
            // Show Loader
            $("#loaderGif").addClass('loaderGif');
//                $("#loaderBody").addClass('loaderBody');
        },
        success: function (data) {
            document.getElementById("productsDiv").innerHTML = data;
            nextStep('thirdStep','fourthStep');
        },
        complete: function () {
            //Hide Loader
            var loaderGif = document.getElementById("loaderGif");
            loaderGif.classList.remove("loaderGif");
//                var loaderBody = document.getElementById("loaderBody");
//                loaderBody.classList.remove("loaderBody");
        }
    });
//    nextStep('thirdStep','fourthStep');
}

function fillVehicleTableAcc(){
    document.getElementById("vehiclesBodyTableAcc").innerHTML = "";
    var plates = [];
    var i = 0;
    $('#vehiclesBodyTable tr').each(function (row, tr) {
    
            var bodyTable = document.getElementById("vehiclesBodyTableAcc");
            var rowCount = bodyTable.rows.length;
            var row = bodyTable.insertRow(rowCount);
            var ramv = $(tr).find('td:eq(0)').text();
            var plate = $(tr).find('td:eq(1)').text();
            var value = $(tr).find('td:eq(10)').text();
            var formatValue = value.replace(/,/g, ''); 
            
            row.insertCell(0).innerHTML = '<input class="chekVehiclePlate" name="checkVehiclePlate123" type="checkbox" value="'+ ramv +','+plate+','+ formatValue +'" onclick="chkVehiTableAcc(this)">';
            row.insertCell(1).innerHTML = $(tr).find('td:eq(0)').text();
            row.insertCell(2).innerHTML = $(tr).find('td:eq(1)').text();
            row.insertCell(3).innerHTML = $(tr).find('td:eq(2)').text();
            row.insertCell(4).innerHTML = $(tr).find('td:eq(3)').text();
            row.insertCell(5).innerHTML = $(tr).find('td:eq(4)').text();
            row.insertCell(6).innerHTML = $(tr).find('td:eq(5)').text();
            row.insertCell(7).innerHTML = $(tr).find('td:eq(6)').text();
            row.insertCell(8).innerHTML = $(tr).find('td:eq(7)').text();
            row.insertCell(9).innerHTML = $(tr).find('td:eq(8)').text();
            row.insertCell(10).innerHTML = $(tr).find('td:eq(9)').text();
            row.insertCell(11).innerHTML = $(tr).find('td:eq(10)').text();
            row.insertCell(12).innerHTML = $(tr).find('td:eq(11)').text();
            
            //Plates Array
            plates[i] = $(tr).find('td:eq(1)').text();
            
            i++;
    });
    $('#vehiclesAccBodyTable tr').each(function (row, tr) {
        var result = plates.includes($(tr).find('td:eq(1)').text());
        if(result == false){
            document.getElementById("vehiclesAccBodyTable").deleteRow(row);
        }
    });
}

function include(arr,obj) {
    return (arr.indexOf(obj) != -1);
}

function vehicleStatus(value) {
    var vehicleDocument = document.getElementById('vehicleDocument');
    if (value === 'Usado') {
        vehicleDocument.innerHTML = 'Placa'; 
    }
    if (value === 'Nuevo') {
        vehicleDocument.innerHTML = 'RAMV';
    }
    var div = document.getElementById('vehicleForm');
    $(div).fadeIn();
}

function hideVehicleForm() {
    var newVehicle = document.getElementById('newVehicle');
    $(newVehicle).value = '';
    var thirdStepAlert = document.getElementById('thirdStepAlert');
    $(thirdStepAlert).addClass('hidden');
    var vehicleForm = document.getElementById('vehicleForm');
    $(vehicleForm).fadeOut();
}

function newVehicleChange(value) {
    var vehicleDocument = document.getElementById('vehicleDocument');
    if (value === 'Usado') {
        vehicleDocument.innerHTML = 'Placa';
        document.getElementById("plateForm").placeholder = "Placa";
    }
    if (value === 'Nuevo') {
        vehicleDocument.innerHTML = 'RAMV';
        document.getElementById("plateForm").placeholder = "RAMV";
    }
    clearVehicleForm();
}

function removeInputRedFocusVehiclesAcc() {
    var value = document.getElementById("value");
    $(value).removeClass('inputRedFocus');
    var description = document.getElementById("description");
    $(description).removeClass('inputRedFocus');
}

function clearVehicleForm() {
    var thirdStepAlert = document.getElementById("thirdStepAlert"); $(thirdStepAlert).addClass('hidden');
    var plateAlert4 = document.getElementById("plateAlert4"); $(plateAlert4).addClass('hidden');
    var plateAlert3 = document.getElementById("plateAlert3"); $(plateAlert3).addClass('hidden');
    var plateAlert2 = document.getElementById("plateAlert2"); $(plateAlert2).addClass('hidden');
    var vehiPriceAlert = document.getElementById("vehiPriceAlert"); $(vehiPriceAlert).addClass('hidden');
    var plate = document.getElementById("plate"); $(plate).removeClass('inputRedFocus');
//    var yearVehicleError = document.getElementById("yearVehicleError");
//    yearVehicleError.innerHTML = '';

    $('#vehiclesTable tr').each(function (row, tr) {
        $(tr).find('td:eq(0)').removeClass('borderRedFocus');
    });

    $("#suggesstion-box").hide();
    $('#ramv').prop('disabled', true);
    $('#plate').prop('disabled', true);
    $('#brand').prop('disabled', true);
    $('#model').prop('disabled', true);
    $('#year').prop('disabled', true);
    $('#color').prop('disabled', true);
    $('#registration').prop('disabled', false);
    $('#chassis').prop('disabled', false);
    document.getElementById("brand").value = "0";
    document.getElementById("plate").value = "";
    document.getElementById("ramv").value = "";
    document.getElementById("model").value = "";
    document.getElementById("year").value = "0";
    document.getElementById("vehiType").value = "0";
    document.getElementById("vehiClass").value = "";
    document.getElementById("vehiValue").value = "";
    document.getElementById("registration").value = "";
    document.getElementById("chassis").value = "";
    document.getElementById("vehiPrice").value = "";
    var ramv = document.getElementById("ramv"); $(ramv).removeClass('inputRedFocus');
    var plate = document.getElementById("plate"); $(plate).removeClass('inputRedFocus');
    var brand = document.getElementById("brand"); $(brand).removeClass('inputRedFocus');
    var model = document.getElementById("model"); $(model).removeClass('inputRedFocus');
    var year = document.getElementById("year"); $(year).removeClass('inputRedFocus');
    var vehiType = document.getElementById("vehiType"); $(vehiType).removeClass('inputRedFocus');
    var vehiClass = document.getElementById("vehiClass"); $(vehiClass).removeClass('inputRedFocus');
    var vehiValue = document.getElementById("vehiValue"); $(vehiValue).removeClass('inputRedFocus');
    var vehiPrice = document.getElementById("vehiPrice"); $(vehiPrice).removeClass('inputRedFocus');
    var newVehicle = document.getElementById("newVehicle"); $(newVehicle).removeClass('inputRedFocus');
    var registration = document.getElementById("registration"); $(registration).removeClass('inputRedFocus');
    var chassis = document.getElementById("chassis");  $(chassis).removeClass('inputRedFocus');
}

function vehicleAccesoriesTable(){
    //Obtain Vehicles Data from Step 3
    var TableData = new Array();

    $('#vehiclesBodyTable tr').each(function (row, tr) {
        TableData[row] = {
            "ramv": $(tr).find('td:eq(0)').text()
            , "plate": $(tr).find('td:eq(1)').text()
            , "model": $(tr).find('td:eq(2)').text()
            , "brand": $(tr).find('td:eq(3)').text()
            , "color": $(tr).find('td:eq(4)').text()
            , "year": $(tr).find('td:eq(5)').text()
            , "matricula": $(tr).find('td:eq(6)').text()
            , "chassis": $(tr).find('td:eq(7)').text()
            , "vehiType": $(tr).find('td:eq(8)').text()
            , "vehiValue": $(tr).find('td:eq(9)').text()
            , "newVehicle": $(tr).find('td:eq(10)').text()
        };
    });
}

function priceModalSelect(value){
    document.getElementById("vehiPrice").value = value;
    document.getElementById("priceModalBtnClose").click();
}

function chkVehiTableAcc(obj){
    $('.chekVehiclePlate').not(obj).prop('checked', false);
}

function openProductModal(id, prima){
    event.preventDefault();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = ROUTE + '/sales/vehicles/check/conditions';
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, productId: id, prima:prima},
        url: url,
        beforeSend: function () {
            // Show Loader
//            $("#loaderGif").addClass('loaderGif');
//                $("#loaderBody").addClass('loaderBody');
        },
        success: function (data) {
            document.getElementById("modalProductContent").innerHTML = data; 
            document.getElementById("productModalBtn").click();
        },
        complete: function () {
            //Hide Loader
//            var loaderGif = document.getElementById("loaderGif");
//            loaderGif.classList.remove("loaderGif");
//                var loaderBody = document.getElementById("loaderBody");
//                loaderBody.classList.remove("loaderBody");
        }
    });
}