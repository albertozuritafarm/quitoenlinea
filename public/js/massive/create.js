/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {
    
//    $('#value').on('keyup', function(){
//        if( /[^a-zA-Z0-9 %]/.test( this.value ) ) {
//            alert('No puede ingresar Caracteres Especiales');
//            var str= this.value;
//            var newStr = str.substring(0, str.length - 1);
//            this.value=newStr;
//            this.focus();
//        }
////        this.value = this.value.toLocaleUpperCase();
//
//    });

});

function validateValue(){
    var valueInput = document.getElementById("value").value;
    console.log(valueInput);
    if( /[^0-9.]/.test( valueInput ) ) {
        alert('No puede ingresar Caracteres Especiales');
        var str= valueInput;
        var newStr = str.substring(0, str.length - 1);
        document.getElementById("value").value=newStr;
        this.focus();
    }
}

function validateUploadExcel() {
    var form = document.getElementById('uploadForm');
    event.preventDefault();
    var url2 = ROUTE + "/massive/validate/upload/excel";
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: url2,
        type: "POST",
        data: new FormData(form),
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function () {
            // Show Loader
            $("#loaderGif").addClass('loaderGif');
//                $("#loaderBody").addClass('loaderBody');
        },
        success: function (data)
        {
            if(data.success === 'false'){
                txt = '<span style="color:red">Existe un Error con el Archivo cargado</span>\n\
                        <a href="' + ROUTE + '/massive/download/error/file/'+data.name+'" target="blank" title="Descargar Archivo de Error">\n\
                            <i class="far fa-file-excel fa-2x" style="color:red">\n\
                            </i>\n\
                        </a>';
                var validateErrorMessage = document.getElementById('validateErrorMessage');
                validateErrorMessage.innerHTML = txt;
                var validateErrorDiv = document.getElementById('validateErrorDiv');
                $(validateErrorDiv).removeClass('hidden');
            }else{
                var validateErrorDiv = document.getElementById('validateErrorDiv');
                $(validateErrorDiv).addClass('hidden');
                document.getElementById("uploadForm").onsubmit = null;
                var submitFormBtn = document.getElementById('submitFormBtn');
                $(submitFormBtn).removeClass('hidden');
                var submitFormBtn2 = document.getElementById('submitFormBtn2');
                $(submitFormBtn2).removeClass('hidden');
                document.getElementById('product').setAttribute("required", "");
                document.getElementById('value').setAttribute("required", "");
                document.getElementById('channel').setAttribute("required", "");
                document.getElementById('agency').setAttribute("required", "");
            }
        },
         complete: function () {
            //Hide Loader
            var loaderGif = document.getElementById("loaderGif");
            loaderGif.classList.remove("loaderGif");
//                var loaderBody = document.getElementById("loaderBody");
//                loaderBody.classList.remove("loaderBody");
        }
    })
};