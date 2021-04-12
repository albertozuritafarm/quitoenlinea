/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {





});

function selectProducts() {
        $('.select_products option:selected').appendTo('.selected_products');
}

function unSelectProducts() {
        $('.selected_products option:selected').appendTo('.select_products');
}

function channelProductStore(){
    event.preventDefault();
    var x = document.getElementById("selected_products");
    var selectData = new Array();
    var i;
    for (i = 0; i < x.length; i++) {
        selectData[i] = {
          "productId" :  x.options[i].value
        };
    }
//    console.log(selectData);
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var channelId = document.getElementById("channelId").value;
    var url = ROUTE + '/channel/product/store';
    $.ajax({
        url: url,
        type: "POST",
        /* send the csrf-token and the input to the controller */
        data: {_token: CSRF_TOKEN, selectData: selectData, channelId: channelId},
        success: function (result) {
            window.location.href = ROUTE + "/channel";
        }
    });
}