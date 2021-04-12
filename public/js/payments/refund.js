/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){

$('#card_number').on('keyup', function () {
        if (/^\d+$/.test(this.value)) {
        }else{
            var str = this.value;
            var newStr = str.substring(0, str.length - 1);
            this.value = newStr;
            this.focus();
        }
});
    
});



