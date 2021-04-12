/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {

});

function ticketChange(id) {
    removeInputRedFocus('ticketTypeDetail');
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, id:id},
        url: ROUTE + "/ticket/type/detail",
        success: function (data)
        {
            $('#ticketTypeDetail').html(data);
        }
    });
}

function submitTicket(){
    event.preventDefault();
    var error = false;
    //Validate
    var menu = document.getElementById("menuSelect"); if(menu.value == ''){ $(menu).addClass('inputRedFocus'); error = true; }
    var ticketType = document.getElementById("ticketType"); if(ticketType.value == ''){ $(ticketType).addClass('inputRedFocus'); error = true; }
    var ticketTypeDetail = document.getElementById("ticketTypeDetail"); if(ticketTypeDetail.value == ''){ $(ticketTypeDetail).addClass('inputRedFocus'); error = true; }
    var title = document.getElementById("title"); if (title.value == '') { $(title).addClass('inputRedFocus'); error = true; }
    var description = document.getElementById("description"); if (description.value == '') { $(description).addClass('inputRedFocus'); error = true; }

    if(error === false){
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var form = document.getElementById('ticketForm');
        var url2 = ROUTE + "/ticket/store";
        $.ajax({
            url: url2,
            type: "POST",
            data: new FormData(form),
            contentType: false,
            cache: false,
            processData: false,
            success: function (data)
            {
                window.location.href = ROUTE + "/ticket";
            }
        });
    }
}

function submitTicketComment(){
    event.preventDefault();
    var error = false;
    //Validate
    var description = document.getElementById("description"); if (description.value == '') { $(description).addClass('inputRedFocus'); error = true; }
    var pictureName1 = document.getElementById("pictureName1"); var pictureNewName1 = document.getElementById("pictureNewName1");
    var ticketsId = document.getElementById("ticketsId");
    var closeTicket = document.getElementById("closeTicket");
    
    if(error === false){
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var form = document.getElementById('ticketForm');
        var url2 = ROUTE + "/ticket/store/comment";
        $.ajax({
            url: url2,
            type: "POST",
            data: new FormData(form),
            contentType: false,
            cache: false,
            processData: false,
            success: function (data)
            {
                location.reload(); 
            }
        });
    }
}

function submitTicketCloseComment(){
    var txt;
    var r = confirm("¿Desea cerrar el ticket?");
    if (r == true) {
        document.getElementById("closeTicket").value = 'close';
        submitTicketComment();
    } else {
        document.getElementById("closeTicket").value = '';
    } 
}

function uploadPictureForm(id, side) {
    event.preventDefault();
    var form = document.getElementById(id);
    var url2 = ROUTE + "/ticket/ajax_upload/action";
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
            var message = '#message' + side;
            var uploaded_image = '#uploaded_image' + side;
            var messageResponse = 'message' + side;
            var upload_imageResponse = 'upload_image' + side;
            $(uploaded_image).html(data.uploaded_image);
            var deletePicture = 'deletePicture' + side;
            var uploadPicture = 'upload' + side;
            if (data.Success == 'true') {
                var uploadPic = document.getElementById("select_file" + side);
                $(uploadPic).addClass('hidden');
                var imageMas = document.getElementById("imageMas" + side);
                $(imageMas).addClass('hidden');
                var deletePic = document.getElementById(deletePicture);
                $(deletePic).removeClass('hidden');
                $(deletePic).addClass('visible');
                var uploadPic = document.getElementById(uploadPicture);
                $(uploadPic).removeClass('visible');
                $(uploadPic).addClass('hidden');
                var fileName = document.getElementById('fileName' + side);
                $(fileName).removeClass('visible');
                $(fileName).addClass('hidden');
                fileName.innerHTML = '';
                var pictureNameLocal = document.getElementById('pictureName' + side);
                pictureNameLocal.value=data.pictureName;
                var message = document.getElementById("message" + side);
                $(message).addClass('hidden');
                var pictureName = document.getElementById("pictureName" + side);
                pictureName.value = data.name;
                var pictureNewName = document.getElementById("pictureNewName" +side);
                pictureNewName.value = data.newName;
            } else {
                var message = document.getElementById("message" + side);
                $(message).removeClass('hidden');
                $(message).html(data.message);
                $(message).addClass(data.class_name);
            }
        }
    });
}

function deletePictureForm(side) {
    event.preventDefault();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url = ROUTE + '/ticket/ajax_upload/delete';
    var customerDocument = document.getElementById("customerDocument" + side);
    var data = {side: side, document: customerDocument.value};
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, data},
        url: url,
        success: function (data) {
            var uploaded_imageFront = document.getElementById("uploaded_image" + side);
            $(uploaded_imageFront).html('');
            var imageMas = document.getElementById("imageMas" + side);
            $(imageMas).removeClass('hidden');
            var uploadPic = document.getElementById("select_file" + side);
            $(uploadPic).addClass('visible');
            $(uploadPic).removeClass('hidden');
            var deletePic = document.getElementById("deletePicture" + side);
            $(deletePic).removeClass('visible');
            $(deletePic).addClass('hidden');
            var uploadPic = document.getElementById("upload" + side);
            $(uploadPic).removeClass('hidden');
            $(uploadPic).addClass('visible');
            var fileName = document.getElementById('fileName' + side);
            $(fileName).removeClass('hidden');
            $(fileName).addClass('visible');
            fileName.innerHTML = '';
            document.getElementById("select_file" + side).value = "";
        }
    });
}

function fileNameFunction(side) {
    var file = document.getElementById('select_file' + side).files[0];
    var uploadPic = document.getElementById("fileName" + side);
    uploadPic.innerHTML = file.name;

}

function removeInputRedFocus(id) {
    var input = document.getElementById(id);
    $(input).removeClass('inputRedFocus');
}

function clearCommentForm(){
    deletePictureForm(1);
    var closeTicket = document.getElementById('closeTicket');
    closeTicket.value = '';
    var description = document.getElementById('description');
    description.value = '';
}

function clearInputFile(){
    document.getElementById("file").value = "";
}