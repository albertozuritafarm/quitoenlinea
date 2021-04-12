$(document).ready(function() {
    
    //Load Document Type Select
//    $('select[name="document_id"][value="{{$user[0]->document_id}}"]').attr("selected","true");
    $('select[name="document_id"][value="<?php echo $user[0]->document_id;?>"]').empty();

});