/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {
    //Only Check One (Discount)
    $('.check').click(function () {
        $('.check').not(this).prop('checked', false);
    });

    $(".check").on("click", function (e) {
        var checkbox = $(this);
        if (checkbox.is(":checked")) {
            validateDiscount(checkbox.val());
        } else {
            e.preventDefault();
        }
    });
    
      $('#name').on('keyup', function(){
            if( /[^a-zA-Z0-9 %]/.test( this.value ) ) {
                alert('No puede ingresar Caracteres Especialesasasd');
                var str= this.value;
                var newStr = str.substring(0, str.length - 1);
                this.value=newStr;
                this.focus();
            }
            this.value = this.value.toLocaleUpperCase();

        });

});

function validateDiscount(value) {
    var checkbox = $(this);
    if (checkbox.is(":checked")) {
        // do the confirmation thing here
        event.preventDefault();
    }
    if (value === 'YES') {
        document.getElementById("percentage").removeAttribute("disabled");
    } else {
        document.getElementById("percentage").setAttribute("disabled", "disabled");
        document.getElementById("percentage").value = '';
    }
}

function endDateChange() {
    var beginDate = document.getElementById("beginDate2").value;
    var endDate = document.getElementById("endDate2").value;
    if (beginDate === '') {
        alert('Por favor introduza una fecha de Inicio');
        document.getElementById("endDate").value = '';
        document.getElementById("endDate2").value = '';
    } else if (endDate < beginDate) {
        alert('La fecha Fin no puede ser menor a la fecha de Inicio');
        document.getElementById("endDate").value = '';
        document.getElementById("endDate2").value = '';

    }
}
function val() {
    var code = document.getElementById("code").value;
    console.log(code.length);
    if (code.length > 6) {
        var code = document.getElementById("code");
        $(code).focus();
        return false; // keep form from submitting
    } else {
        var r = confirm("¿Seguro que desea guardar el beneficio?");
        if (r == true) {
            return true;
        } else {
            return false;
        }
    }
}






