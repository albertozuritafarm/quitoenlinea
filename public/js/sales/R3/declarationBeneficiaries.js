/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function nextStep(div1, div2) {
//    event.preventDefault();
    var div = document.getElementById(div1);
    $(div).fadeOut('slow');
    $(div).addClass('hidden');
    var div = document.getElementById(div2);
    $(div).fadeIn('slow');
    $(div).removeClass('hidden');

    var wizard = document.getElementById(div1 + "Wizard");
    $(wizard).removeClass('wizard_activo');
    $(wizard).addClass('wizard_inactivo');
    var wizard = document.getElementById(div2 + "Wizard");
    $(wizard).removeClass('wizard_inactivo');
    $(wizard).addClass('wizard_activo');
}

function fadeToggle(id) {
    event.preventDefault();
    var div = document.getElementById(id);
    $(div).fadeToggle(200);
}
function fadeOut(id) {
    var div = document.getElementById(id);
    $(div).fadeOut();
}
function fadeIn(id) {
    var div = document.getElementById(id);
    $(div).fadeIn();
}

function firstStepBtnNext() {
    event.preventDefault();
    //Validate Table
    if ($('#beneficiaryBodyTable tr').length == 0) {
    }else{
        var r = confirm("¿Seguro que desea guardar estos beneficiarios? Estos datos no podras ser modificados.");
        if (r === true) {
            var value = 0;
            var TableData = new Array();
            $('#beneficiaryBodyTable tr').each(function (row, tr) {
                TableData[row] = { "document": $(tr).find('td:eq(0)').text() , "type": $(tr).find('td:eq(1)').text() , "first_name": $(tr).find('td:eq(2)').text() , "last_name": $(tr).find('td:eq(3)').text() , "porcentage": $(tr).find('td:eq(4)').text() };
                value += Number(formatNumber($(tr).find('td:eq(4)').text()));
            });

            if (value != 100) {
                alert('Los porcentages deben sumar 100%');
                validate = true;
                return false;
            }else{
                var TableDataBene = new Array();

                $('#beneficiaryBodyTable tr').each(function (row, tr) {
                    TableDataBene[row] = {
                        "firstName": $(tr).find('td:eq(0)').text()
                        , "secondName": $(tr).find('td:eq(1)').text()
                        , "lastName": $(tr).find('td:eq(2)').text()
                        , "secondLastName": $(tr).find('td:eq(3)').text()
                        , "porcentaje": $(tr).find('td:eq(4)').text()
                        , "relationship": $(tr).find('td:eq(5)').text()
                    };
                });

                var url = ROUTE + "/insurance/application/R3/firstStepStore";
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                var salId = document.getElementById('salId').value;

                $.ajax({
                    url: url,
                    type: "POST",
                    data: {_token: CSRF_TOKEN, salId: salId, tableData: TableDataBene},
                    beforeSend: function () {
    //                 Show Loader
                        $("#loaderGif").addClass('loaderGif');
                    },
                    success: function (data)
                    {
                        var firstStepBtnNext1 = document.getElementById('firstStepBtnNext1');
                        $(firstStepBtnNext1).addClass('hidden'); 
                        var firstStepBtnNext2 = document.getElementById('firstStepBtnNext2');
                        $(firstStepBtnNext2).addClass('hidden'); 
                        var btnBeneficiary = document.getElementById('btnBeneficiary');
                        $(btnBeneficiary).addClass('hidden'); 
                        var alertDiv = document.getElementById('alertDiv');
                        $(alertDiv).removeClass('hidden'); 
                        location.reload();
                        
                    },
                    complete: function () {
                        //Hide Loader
                        $("#loaderGif").removeClass('loaderGif');
                    }
                });
            }
        }
    }

}

function removeInputRedFocus(id){
    var id = document.getElementById(id);
    $(id).removeClass('inputRedFocus');
}

function addBeneficiary() {
    event.preventDefault();

    //Variables
    var first_name = document.getElementById("beneficiary_first_name");
    var second_name = document.getElementById("beneficiary_second_name");
    var last_name = document.getElementById("beneficiary_last_name");
    var second_last_name = document.getElementById("beneficiary_second_last_name");
    var porcentage_Beneficiary = document.getElementById("porcentage_Beneficiary");
    var beneficiary_relationship = document.getElementById("beneficiary_relationship");
    var validate = false;

    //Validation
    if (first_name.value === "") { $(first_name).addClass('inputRedFocus'); validate = true; } else { $(first_name).removeClass('inputRedFocus'); }
   // if (second_name.value === "") { $(second_name).addClass('inputRedFocus'); validate = true; } else { $(second_name).removeClass('inputRedFocus'); }
    if (last_name.value === "") { $(last_name).addClass('inputRedFocus'); validate = true; } else { $(last_name).removeClass('inputRedFocus'); }
    if (second_last_name.value === "") { $(second_last_name).addClass('inputRedFocus'); validate = true; } else { $(second_last_name).removeClass('inputRedFocus'); }
    if (porcentage_Beneficiary.value === "" || porcentage_Beneficiary.value > 100 || porcentage_Beneficiary.value === "0") { $(porcentage_Beneficiary).addClass('inputRedFocus'); validate = true; } else { $(porcentage_Beneficiary).removeClass('inputRedFocus'); }
    if (beneficiary_relationship.value === "0") { $(beneficiary_relationship).addClass('inputRedFocus'); validate = true; } else { $(beneficiary_relationship).removeClass('inputRedFocus'); }

    //Beneficiary Table Data
    var value = 0;
    var TableData = new Array();
    $('#beneficiaryBodyTable tr').each(function (row, tr) {
        TableData += [$(tr).find('td:eq(4)').text()];
        value += Number(formatNumber($(tr).find('td:eq(4)').text()));
    });

    //Validate Beneficiary Table Data
    value += Number(formatNumber(porcentage_Beneficiary.value));
    if (value > 100) {
        validate = true;
        alert('El porcentaje no debe ser mayor a 100%');
    }

    //Add Row
    if (validate == false) {
        addRow(first_name, second_name, last_name, second_last_name, porcentage_Beneficiary, $("#beneficiary_relationship :selected").text());
    }
}

function addRow(first_name, second_name, last_name, second_last_name, porcentage, relationship) {
    var bodyTable = document.getElementById("beneficiaryBodyTable");

    var rowCount = bodyTable.rows.length;
    var row = bodyTable.insertRow(rowCount);

    //Validate Document id Name
    row.insertCell(0).innerHTML = first_name.value;
    row.insertCell(1).innerHTML = second_name.value;
    row.insertCell(2).innerHTML = last_name.value;
    row.insertCell(3).innerHTML = second_last_name.value;
    row.insertCell(4).innerHTML = porcentage.value;
    row.insertCell(5).innerHTML = relationship;
    row.insertCell(6).innerHTML = '<button type="submit" class="btn btn-link" onClick="Javacsript:editRow(\'' + first_name.value + '\',\'' + second_name.value + '\', \'' + last_name.value + '\', \'' + second_last_name.value + '\', \'' + porcentage.value + '\',\'' + relationship + '\',this)"><span class="glyphicon glyphicon-pencil" style="color:green;font-size:18px"></span></button><button type="submit" class="btn btn-link" onClick="Javacsript:deleteRow(this)"><span class="glyphicon glyphicon-remove" style="color:red;font-size:18px"></span></button>';

    //Return Inputs no Null
    first_name.value = '';
    second_name.value = '';
    last_name.value = '';
    second_last_name.value = '';
    porcentage.value = '';
    document.getElementById("beneficiary_relationship").value = '0';
}

function deleteRow(obj) {
    var index = obj.parentNode.parentNode.rowIndex;
    var table = document.getElementById("beneficiaryTable");
    table.deleteRow(index);
}

function editRow(first_name, second_name, last_name, second_last_name, porcentage_Beneficiary, relationship, obj) {
    //Fill Form
    document.getElementById("beneficiary_first_name").value = first_name;
    document.getElementById("beneficiary_second_name").value = second_name;
    document.getElementById("beneficiary_last_name").value = last_name;
    document.getElementById("beneficiary_second_last_name").value = second_last_name;
    document.getElementById("porcentage_Beneficiary").value = porcentage_Beneficiary;
    
    //Validate Beneficiary Relationship
    if(relationship === 'PADRE/MADRE'){ var relationshipId = 1; }
    if(relationship === 'HIJO(A)'){ var relationshipId = 2; }
    if(relationship === 'ABUELO(A)'){ var relationshipId = 3; }
    if(relationship === 'NIETO(A)'){ var relationshipId = 4; }
    if(relationship === 'HERMANO(A)'){ var relationshipId = 5; }
    if(relationship === 'SUEGRO(A)'){ var relationshipId = 6; }
    if(relationship === 'YERNO'){ var relationshipId = 7; }
    if(relationship === 'NUERA'){ var relationshipId = 8; }
    if(relationship === 'CUÑADO(A)'){ var relationshipId = 9; }
    if(relationship === 'CONYUGE'){ var relationshipId = 10; }
    if(relationship === 'OTROS'){ var relationshipId = 11; }
    if(relationship === 'TIO(A)'){ var relationshipId = 12; }
    if(relationship === 'PRIMO(A)'){ var relationshipId = 13; }
    if(relationship === 'ASEGURADO PRINCIPAL'){ var relationshipId = 15; }
    
//    $('#beneficiary_relationship').val(relationshipId);
    document.getElementById("beneficiary_relationship").value = relationshipId;
   
    deleteRow(obj);
}

function onlyNumbers(evt, ele) {
//    var charCode = (event.which) ? event.which : event.keyCode
//    if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode !== 44)
//        return false;
//
//    return true;
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  key = String.fromCharCode( key );
  var value = ele.value + key;
  var regex = /^\d+(,\d{0,2})?$/;
  if( !regex.test(value) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}

function formatNumber(str){ 
    var res = str.replace(",", ".");
    return res;
}
  function ResponsiveCellHeaders(elmID) {
  try {
    var THarray = [];
    var table = document.getElementById(elmID);
    console.log(table);
    var ths = table.getElementsByTagName("th");
    for (var i = 0; i < ths.length; i++) {
      var headingText = ths[i].innerHTML;
      THarray.push(headingText);
    }
    var styleElm = document.createElement("style"),
      styleSheet;
    document.head.appendChild(styleElm);
    styleSheet = styleElm.sheet;
    for (var i = 0; i < THarray.length; i++) {
      styleSheet.insertRule(
        "#" +
          elmID +
          " td:nth-child(" +
          (i + 1) +
          ')::before {content:"' +
          THarray[i] +
          ': ";}',
        styleSheet.cssRules.length
      );
    }
  } catch (e) {
    console.log("ResponsiveCellHeaders(): " + e);
  }
}

// https://adrianroselli.com/2018/02/tables-css-display-properties-and-aria.html
// https://adrianroselli.com/2018/05/functions-to-add-aria-to-tables-and-lists.html
function AddTableARIA() {
  try {
    var allTables = document.querySelectorAll('table');
    for (var i = 0; i < allTables.length; i++) {
      allTables[i].setAttribute('role','table');
    }
    var allRowGroups = document.querySelectorAll('thead, tbody, tfoot');
    for (var i = 0; i < allRowGroups.length; i++) {
      allRowGroups[i].setAttribute('role','rowgroup');
    }
    var allRows = document.querySelectorAll('tr');
    for (var i = 0; i < allRows.length; i++) {
      allRows[i].setAttribute('role','row');
    }
    var allCells = document.querySelectorAll('td');
    for (var i = 0; i < allCells.length; i++) {
      allCells[i].setAttribute('role','cell');
    }
    var allHeaders = document.querySelectorAll('th');
    for (var i = 0; i < allHeaders.length; i++) {
      allHeaders[i].setAttribute('role','columnheader');
    }
    // this accounts for scoped row headers
    var allRowHeaders = document.querySelectorAll('th[scope=row]');
    for (var i = 0; i < allRowHeaders.length; i++) {
      allRowHeaders[i].setAttribute('role','rowheader');
    }
    // caption role not needed as it is not a real role and
    // browsers do not dump their own role with display block
  } catch (e) {
    console.log("AddTableARIA(): " + e);
  }
}

$(document).ready(function() {
    ResponsiveCellHeaders("beneficiaryTable");
    AddTableARIA();

});