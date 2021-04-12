/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {

});

function validateCancelExcel() {
    var form = document.getElementById('uploadForm');
    event.preventDefault();
    var url2 = "/massive/validate/cancel/excel";
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: url2,
        type: "POST",
        data: new FormData(form),
        contentType: false,
        cache: false,
        processData: false,
        success: function (data)
        {
            if(data.success === 'false'){
                txt = '<span style="color:red">Existe un Error con el Archivo cargado</span>\n\
                        <a href="/massive/download/error/file/'+data.name+'" target="blank" title="Descargar Archivo de Error">\n\
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
                document.getElementById('channel').setAttribute("required", "");
                document.getElementById('agency').setAttribute("required", "");
            }
        }
    })
}
;