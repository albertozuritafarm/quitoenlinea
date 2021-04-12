/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {
    //Style Table
    var tableBorder2 = document.getElementById("tableUsers_length");
    $(tableBorder2).addClass('hidden');
    var tableBorder2 = document.getElementById("tableUsers_info");
    $(tableBorder2).addClass('hidden');
    var tableBorder2 = document.getElementById("tableUsers_paginate");
    $(tableBorder2).addClass('hidden');

    //Cancel Button
//    document.getElementById("cancelBtn").onclick = function () {
//        //Obain Selected vehicles Array
//        var vehicles = [];
//        $.each($("input[name='vehicleId']:checked"), function () {
//            vehicles.push($(this).val());
//        });
//        
//        //Obtain saleId
//        var saleId = document.getElementById("saleId").value;
//        
//        //Send array to cancel
//        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
//            var data = {vehicles: vehicles, sale:saleId};
//            var url = '/sales/vehicles/cancel';
//            $.ajax({
//                type: "POST",
//                data: {_token: CSRF_TOKEN, data},
//                url: url,
//                success: function (data) {
//                    //Hide Checkbox
//                    console.log(data['sales']['0']['id']);
//                    var vehicles = data['vehicles'];
//                    data['vehicles'].forEach(function(data, index) {
//                        //hide Checkbox
//                        var id = 'vehicle'+data.id;
//                        console.log(id);
//                        var div = document.getElementById(id);
//
//                        div.innerHTML = '';
//                        
//                        
//                    });
//                    //Hide Submit Button
//                    if(data['sales']['0']['id'] == 1){
//                        var submitButton = document.getElementById("cancelBtn");
//                        $(submitButton).addClass('hidden');
//                    }
//
//                    //Show Success Message
//                    var cancelDiv = document.getElementById("cancelDiv");
//                    $(cancelDiv).removeClass('hidden');
//                }
//            });
//   };
});