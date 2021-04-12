/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function emitPayment() {
    event.preventDefault();
    //Validate
    var validate = false;

//    var first_name = document.getElementById("first_name"); if (first_name.value === '') { $(first_name).addClass('inputRedFocus'); validate = true; } else { $(first_name).removeClass('inputRedFocus'); }
//    var last_name = document.getElementById("last_name"); if (last_name.value === '') { $(last_name).addClass('inputRedFocus'); validate = true; } else { $(last_name).removeClass('inputRedFocus'); }
//    var email = document.getElementById("email"); if (email.value === '') { $(email).addClass('inputRedFocus'); validate = true; } else { $(email).removeClass('inputRedFocus'); }
//    var documentForm = document.getElementById("document"); if (documentForm.value === '') { $(documentForm).addClass('inputRedFocus'); validate = true; } else { $(documentForm).removeClass('inputRedFocus'); }
    
    var number = document.getElementById("number"); if (number.value === '') { $(number).addClass('inputRedFocus'); validate = true; } else { $(number).removeClass('inputRedFocus'); }
    var cvc = document.getElementById("cvc"); if (cvc.value === '') { $(cvc).addClass('inputRedFocus'); validate = true; } else { $(cvc).removeClass('inputRedFocus'); }
    var month = document.getElementById("month"); if (month.value === '') { $(month).addClass('inputRedFocus'); validate = true; } else { $(month).removeClass('inputRedFocus'); }
    var year = document.getElementById("year"); if (year.value === '') { $(year).addClass('inputRedFocus'); validate = true; } else { $(year).removeClass('inputRedFocus'); }

    if (validate == false) {
        var form = document.getElementById("paymentForm");
        var url = ROUTE + "/payments/store";
        $.ajax({
            url: url,
            type: "POST",
            data: new FormData(form),
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                // Show Loader
                $("#loaderGif").addClass('loaderGif');
            },
            success: function (data)
            {
                window.location.href = ROUTE + "/sales";
            },
            complete: function () {
                //Hide Loader
                var loaderGif = document.getElementById("loaderGif");
                loaderGif.classList.remove("loaderGif");
            }
        });
    }
}

function nextStep(){
    event.preventDefault();
    document.getElementById("formBtn").click();
}

function previous(){
    document.getElementById("formBtn").click();
}
