/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {

});

function previousStep(){
    event.preventDefault();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = ROUTE + '/sales/asset/' + document.getElementById("insurance_branch").value + '/'+ null;
    var next = '#insuredStep';
    loadNextPage(url,next);
}

function openModal(type){
    event.preventDefault();
    document.getElementById("myModalBtn" + type).click();
}

function selectProduct(id, name, value) {
    event.preventDefault();
    document.getElementById("productCheckBox").value = id;
    document.getElementById("productNameCheckBox").value = name;
    document.getElementById("productValueCheckBox").value = value;
    obtainResume();
}

function obtainResume(){
    event.preventDefault();
    //Obtain Checked Producto from 
    var selectedProduct = document.getElementById("productCheckBox").value;
    var selectedProductName = document.getElementById("productNameCheckBox").value;
    var selectedProductValue = document.getElementById("productValueCheckBox").value;

    //Obtain Vehicles Data from Step 3
    var TableData = new Array();


    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var data = {product: selectedProduct, name: selectedProductName, value: selectedProductValue};
    var url = ROUTE + '/sales/resume/new';
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, data},
        url: url,
        success: function (data) {
            var div = document.getElementById('vehiclesTableBodyResume');

            div.innerHTML = '';
            div.innerHTML += data[0];

            var div = document.getElementById('taxTableBodyResume');
            div.innerHTML = '';
            div.innerHTML += data[1];
            
            document.getElementById("documentResume").value = data[2]['document'];
            document.getElementById("customerResume").value = data[2]['first_name'] + ' ' +data[2]['last_name'];
            document.getElementById("mobile_phoneResume").value = data[2]['mobile_phone'];
            document.getElementById("emailResume").value = data[2]['email'];
        }
    });
    nextStep('thirdStep', 'fourthStep');
}

function executeSale() {
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

    //Obtain Checked Producto from
    var saleMovement = document.getElementById("sale_movement").value;
    var saleId = document.getElementById("sale_id").value;
    
    //Obtain Check Send Quotation
    var sendQuotation = document.querySelector('.chkBoxSendQuotation').checked;;
    
    //Obtain Insurance Branch
    var insuranceBranch = document.getElementById("insurance_branch").value;

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var data = {product: selectedProduct, pricesTable: pricesTable, saleMovement: saleMovement, saleId: saleId, sendQuotation:sendQuotation, insuranceBranch: insuranceBranch};
    var url = ROUTE + '/sales/store/new';
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

function nextStep(div1, div2) {
    event.preventDefault();
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

