/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {
    //    checkValidInput();
   
       $("#document_id").change(function () {
           var document_id = document.getElementById("document_id");
           var documentInput = document.getElementById("document");
           if (document_id.value == 1) {
               if (documentInput.value.length == 10) {
                   validateDocument();
                   $(documentInput).focus();
               } else {
                   document.getElementById("document_id").value = '0';
                   alert('La cedula debe tener 10 digitos');
                   $(documentInput).focus();
               }
           }
       });
       function setInputFilter(textbox, inputFilter) {
           ["input"].forEach(function(event) {
               textbox.addEventListener(event, function() {
               if (inputFilter(this.value)) {
                   this.oldValue = this.value;
                   this.oldSelectionStart = this.selectionStart;
                   this.oldSelectionEnd = this.selectionEnd;
               } else if (this.hasOwnProperty("oldValue")) {
                   this.value = this.oldValue;
                   this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
               } else {
                   this.value = "";
               }
               });
           });
       }
       setInputFilter(document.getElementById("documentCompany"), function(value) {
        return /^-?\d*[.]?\d*$/.test(value); 
       });
       setInputFilter(document.getElementById("document"), function(value) {
        return /^-?\d*[.]?\d*$/.test(value); 
       });
       setInputFilter(document.getElementById("netPremium"), function(value) {
           return /^-?\d*[.]?\d{0,2}$/.test(value); 
       });
       setInputFilter(document.getElementById("insuredValue"), function(value) {
           return /^-?\d*[.]?\d{0,2}$/.test(value); 
       });
       setInputFilter(document.getElementById("mobile_phone"), function(value) {
           return /^-?\d*[.]?\d*$/.test(value); 
       });
       
       

       var tableBorder2 = document.getElementById("tableUsers_length");
       $(tableBorder2).addClass('hidden');
       var tableBorder2 = document.getElementById("tableUsers_info");
       $(tableBorder2).addClass('hidden');
       var tableBorder2 = document.getElementById("tableUsers_paginate");
       $(tableBorder2).addClass('hidden');
   
       
       function validateDocument() {
           var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
           var data = {document: document.getElementById("document").value};
           var url = ROUTE + '/massivesVinculation/validateDocument';
           $.ajax({
               type: "POST",
               data: {_token: CSRF_TOKEN, data},
               url: url,
               async: false,
               success: function (data) {
                   //console.log(data);
                   if (data === 'invalid') {
                       $("#suggesstion-box").hide();
                       document.getElementById("first_name").value = "";
                       document.getElementById("document_id").value = "0";
                       document.getElementById("last_name").value = "";
                       document.getElementById("mobile_phone").value = "";
                       document.getElementById("email").value = "";
                       document.getElementById("branch").value = "0";
                       document.getElementById("emissionType").value = "0";
                       document.getElementById("insuredValue").value = "";
                       document.getElementById("netPremium").value = "";
                       alert('El documento ingresado es invalido');
                   }
               },
               error: function () {
                   return "Hello";
               }
           });
       }
   
       function validate()
       {
   //        console.log('function');
           $('input:text', '#salesForm').removeClass('error');
           $('input:text[value=""]', '#salesForm').addClass('error');
           return false;
       }
   
       //Check Products Only one
       $('.check').click(function () {
           $('.check').not(this).prop('checked', false);
       });
       // Select Product
    $("#branch").change(function () {
        var branch= document.getElementById("branch");
        if(branch.value!='0'){
            productSearch();
        }
    });
       document.getElementById("document").onclick = function () {
           $("#suggesstion-box").hide();
           document.getElementById("first_name").value = "";
           document.getElementById("second_name").value = "";
           document.getElementById("document_id").value = "0";
           document.getElementById("last_name").value = "";
           document.getElementById("second_last_name").value = "";
           document.getElementById("mobile_phone").value = "";
           document.getElementById("email").value = "";
           document.getElementById("branch").value = "0";
           document.getElementById("emissionType").value = "0";
           document.getElementById("product").value = "0";
           document.getElementById("insuredValue").value = "";
           document.getElementById("netPremium").value = "";
           document.getElementById("first_name").disabled = true;
           document.getElementById("second_name").disabled = true;
           document.getElementById("last_name").disabled = true;
           document.getElementById("second_last_name").disabled = true;
           document.getElementById("document_id").disabled = true;
           validateInput();
       };
       $("#document").keyup(function () {
           $("#suggesstion-box").hide();
           document.getElementById("first_name").value = "";
           document.getElementById("second_name").value = "";
           document.getElementById("document_id").value = "0";
           document.getElementById("last_name").value = "";
           document.getElementById("second_last_name").value = "";
           document.getElementById("mobile_phone").value = "";
           document.getElementById("email").value = "";
           document.getElementById("branch").value = "0";
           document.getElementById("emissionType").value = "0";
           document.getElementById("product").value = "0";
           document.getElementById("insuredValue").value = "";
           document.getElementById("netPremium").value = ""; 
           document.getElementById("first_name").disabled = true;
           document.getElementById("second_name").disabled = true;
           document.getElementById("last_name").disabled = true;
           document.getElementById("second_last_name").disabled = true;
           document.getElementById("document_id").disabled = true;
           validateInput();
       });
       $(document).on('click', 'body *', function () {
           $("#suggesstion-box").hide();
       });
       $("#business_name").change(function () {
         $(this).removeClass('inputRedFocus');
         var business_name_validation = document.getElementById("business_name_validation");
         var business_name_validation_length = document.getElementById("business_name_validation_length");
         $(business_name_validation).addClass('hidden');
         $(business_name_validation_length).addClass('hidden');
         var customerAlert = document.getElementById("customerAlert");
         $(customerAlert).addClass("hidden");
       });
       $("#documentCompany").change(function () {
          $(this).removeClass('inputRedFocus');
          var documentCompany_validation = document.getElementById("documentCompany_validation");
          var documentCompany_validation_length= document.getElementById("documentCompany_validation_length");
          $(documentCompany_validation).addClass('hidden');
          $(documentCompany_validation_length).addClass('hidden');
          var customerAlert = document.getElementById("customerAlert");
           $(customerAlert).addClass("hidden");
       });
       $("#tradename").change(function () {
        $(this).removeClass('inputRedFocus');
        var tradename_validation = document.getElementById("tradename_validation");
        var tradename_validation_length= document.getElementById("tradename_validation_length");
        $(tradename_validation).addClass('hidden');
        $(tradename_validation_length).addClass('hidden');
        var customerAlert = document.getElementById("customerAlert");
         $(customerAlert).addClass("hidden");
     });
       $("#first_name").change(function () {
           $(this).removeClass('inputRedFocus');
           var first_name_validation = document.getElementById("first_name_validation");
           $(first_name_validation).addClass('hidden');
           var customerAlert = document.getElementById("customerAlert");
           $(customerAlert).addClass("hidden");
       });
       $("#document").change(function () {
           $(this).removeClass('inputRedFocus');
           var first_name_validation = document.getElementById("first_name_validation");
           $(first_name_validation).addClass('hidden');
           var customerAlert = document.getElementById("customerAlert");
            $(customerAlert).addClass("hidden");
       });
       $("#document_id").change(function () {
           $(this).removeClass('inputRedFocus');
           var document_id_validation= document.getElementById("document_id_validation");
           $(document_id_validation).addClass('hidden');
           var customerAlert = document.getElementById("customerAlert");
            $(customerAlert).addClass("hidden");
       });
       $("#last_name").change(function () {
           $(this).removeClass('inputRedFocus');
           var last_name_validation = document.getElementById("last_name_validation");
           $(last_name_validation).addClass('hidden');
           var customerAlert = document.getElementById("customerAlert");
            $(customerAlert).addClass("hidden");
       });
       $("#mobile_phone").change(function () {
           $(this).removeClass('inputRedFocus');
           var mobile_phone_validation = document.getElementById("mobile_phone_validation");
           $(mobile_phone_validation).addClass('hidden');
           var mobile_phone_validation_length= document.getElementById("mobile_phone_validation_length");
           $(mobile_phone_validation_length).addClass('hidden');
           var customerAlert = document.getElementById("customerAlert");
            $(customerAlert).addClass("hidden");
       });
       $("#email").change(function () {
           $(this).removeClass('inputRedFocus');
           var email_validation = document.getElementById("email_validation");
           $(email_validation).addClass('hidden');
           var email_error_validation = document.getElementById("email_error_validation");
           $(email_error_validation).addClass('hidden');
           var customerAlert = document.getElementById("customerAlert");
            $(customerAlert).addClass("hidden");
       });
       $("#branch").change(function () {
           $(this).removeClass('inputRedFocus');
           var branch_validation = document.getElementById("branch_validation");
           $(branch_validation).addClass('hidden'); 
           var customerAlert = document.getElementById("customerAlert");
            $(customerAlert).addClass("hidden");
       });
       $("#product").change(function () {
        $(this).removeClass('inputRedFocus');
        var product_validation = document.getElementById("product_validation");
        $(product_validation).addClass('hidden'); 
        var customerAlert = document.getElementById("customerAlert");
         $(customerAlert).addClass("hidden");
    });
       $("#emissionType").change(function () {
           $(this).removeClass('inputRedFocus');
           var emissionType_validation = document.getElementById("emissionType_validation");
           $(emissionType_validation).addClass('hidden'); 
           var customerAlert = document.getElementById("customerAlert");
            $(customerAlert).addClass("hidden");
       });
       $("#insuredValue").change(function () {
           $(this).removeClass('inputRedFocus');
           var insuredValue_validation = document.getElementById("insuredValue_validation");
           $(insuredValue_validation).addClass('hidden');
           var customerAlert = document.getElementById("customerAlert");
            $(customerAlert).addClass("hidden");
       });
       $("#netPremium").change(function () {
           $(this).removeClass('inputRedFocus');
           var netPremium_validation = document.getElementById("netPremium_validation");
           $(netPremium_validation).addClass('hidden');
           var customerAlert = document.getElementById("customerAlert");
            $(customerAlert).addClass("hidden");
       });
   
       //Email Change
       $("#email").change(function () {
   
           var email =  document.getElementById("email").value;
           ValidateEmail(email);
       });
   
       $('#first_name').on('keyup', function () {
           if (/[^a-zA-Z ]/.test(this.value)) {
               alert('No puede ingresar Caracteres Especiales');
               var str = this.value;
               var newStr = str.substring(0, str.length - 1);
               this.value = newStr;
               this.focus();
           }
           this.value = this.value.toLocaleUpperCase();
   
       });
   
       $('#last_name').on('keyup', function () {
           if (/[^a-zA-Z ]/.test(this.value)) {
               alert('No puede ingresar Caracteres Especiales');
               var str = this.value;
               var newStr = str.substring(0, str.length - 1);
               this.value = newStr;
               this.focus();
           }
           this.value = this.value.toLocaleUpperCase();
   
       });
       $('#document').on('keyup', function () {
           if (/[^a-zA-Z0-9]/.test(this.value)) {
               alert('No puede ingresar Caracteres Especiales');
               var str = this.value;
               var newStr = str.substring(0, str.length - 1);
               this.value = newStr;
               this.focus();
           }
           this.value = this.value.toLocaleUpperCase();
   
       });
       $('#mobile_phone').on('keyup', function () {
           if (/[^0-9]/.test(this.value)) {
               var str = this.value;
               var newStr = str.substring(0, str.length - 1);
               this.value = newStr;
               this.focus();
           }
           this.value = this.value.toLocaleUpperCase();
   
       });
       function setInputFilter(textbox, inputFilter) {
           ["input"].forEach(function(event) {
               textbox.addEventListener(event, function() {
               if (inputFilter(this.value)) {
                   this.oldValue = this.value;
                   this.oldSelectionStart = this.selectionStart;
                   this.oldSelectionEnd = this.selectionEnd;
               } else if (this.hasOwnProperty("oldValue")) {
                   this.value = this.oldValue;
                   this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
               } else {
                   this.value = "";
               }
               });
           });
       }
       
       setInputFilter(document.getElementById("document"), function(value) {
           return /^-?\d*$/.test(value); 
       });
   
   });
   function validateInput(){   
        var document_validation=document.getElementById("document_validation");
        $(document_validation).addClass('hidden');
   
        var first_name_validation = document.getElementById("first_name_validation");
        $(first_name_validation).addClass('hidden');
   
        var second_name_validation = document.getElementById("second_name_validation");
        $(second_name_validation).addClass('hidden');
   
        var document_id_validation= document.getElementById("document_id_validation");
        $(document_id_validation).addClass('hidden');
   
         var last_name_validation = document.getElementById("last_name_validation");
        $(last_name_validation).addClass('hidden');
   
        var second_last_name_validation=document.getElementById("second_last_name_validation");
        $(second_last_name_validation).addClass("hidden");
   
        var mobile_phone_validation = document.getElementById("mobile_phone_validation");
        $(mobile_phone_validation).addClass('hidden');
        var mobile_phone_validation_length= document.getElementById("mobile_phone_validation_length");
        $(mobile_phone_validation_length).addClass('hidden')
   
        var email_validation = document.getElementById("email_validation");
        var email_error_validation = document.getElementById("email_error_validation");
        $(email_validation).addClass('hidden');
        $(email_error_validation).addClass('hidden');
   
        var branch_validation = document.getElementById("branch_validation");
        $(branch_validation).addClass('hidden');
   
        var emissionType_validation = document.getElementById("emissionType_validation");
        $(emissionType_validation).addClass('hidden');
   
        var insuredValue_validation = document.getElementById("insuredValue_validation");
        $(insuredValue_validation).addClass('hidden');
   
        var netPremium_validation = document.getElementById("netPremium_validation");
        $(netPremium_validation).addClass('hidden');
         
        var product_validation = document.getElementById("product_validation");
        $(product_validation).addClass('hidden');
        var product=document.getElementById("product");
     $(product).removeClass('inputRedFocus');
   
        var first_name=document.getElementById("first_name");
        $(first_name).removeClass('inputRedFocus');
        var last_name=document.getElementById("last_name");
        $(last_name).removeClass('inputRedFocus');
        var second_name=document.getElementById("second_last_name");
        $(second_name).removeClass('inputRedFocus');
        var mobile_phone=document.getElementById("mobile_phone");
        $(mobile_phone).removeClass('inputRedFocus');
        var email=document.getElementById("email");
        $(email).removeClass('inputRedFocus')
        var branch=document.getElementById("branch");
        $(branch).removeClass('inputRedFocus');
        var emissionType=document.getElementById("emissionType");
        $(emissionType).removeClass('inputRedFocus');
        var netPremium=document.getElementById("netPremium");
        $(netPremium).removeClass('inputRedFocus');
        var insuredValue=document.getElementById("insuredValue");
        $(insuredValue).removeClass('inputRedFocus');
        var document_id=document.getElementById("document_id");
        $(document_id).removeClass('inputRedFocus');
        var number_document=document.getElementById("document");
        $(number_document).removeClass('inputRedFocus');
   
        var customerAlert = document.getElementById("customerAlert");
            $(customerAlert).addClass("hidden");
   
   }
   function validateDocument() {
       var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
       var data = {document: document.getElementById("document").value};
       var url = ROUTE + '/massivesVinculation/validateDocument';
       $.ajax({
           type: "POST",
           data: {_token: CSRF_TOKEN, data},
           url: url,
           async: false,
           success: function (data) {
               if (data === 'invalid') {
                   alert('El documento ingresado es invalido');
               } else {
                  formAutoFill(document.getElementById("document").value);
               }
           },
           error: function () {
               return "Hello";
           }
       });
   }
   function emailIsValid(email) {
    return /\S+@\S+\.\S+/.test(email);
}
   function selectDocument(value) {
       $("#document").val(value);
       $("#suggesstion-box").hide();
   
       formAutoFill(value);
   }
   
   function formAutoFill(val) {
       var documentNumber = val;
       var url = ROUTE + '/customer/document/autofill/' + documentNumber;
       if (documentNumber) {
           $.ajax({
               url: url,
               type: "GET",
               dataType: "json",
               beforeSend: function () {
   //              Show Loader
                   $("#loaderGif").addClass('loaderGif');
   //              $("#loaderBody").addClass('loaderBody');
               },
               success: function (data) {
                   if (data.success == 'true') {
                       document.getElementById("first_name").disabled = true;
                       document.getElementById("last_name").disabled = true;
                       document.getElementById("document_id").disabled = true;
                       if(data.second_name === null){ document.getElementById("second_name").disabled = false; }else{ document.getElementById("second_name").disabled = true;  }
                       if(data.second_last_name === null){ document.getElementById("second_last_name").disabled = false; }else{ document.getElementById("second_last_name").disabled = true;  }
                       $("#salesForm").autofill(data);
                   } else {
                       document.getElementById("first_name").disabled = false;
                       document.getElementById("last_name").disabled = false;
                       document.getElementById("document_id").disabled = false;
                       document.getElementById("second_name").disabled = false;
                       document.getElementById("second_last_name").disabled = false;
                       //City Select
                       var city = document.getElementById("city");
                       city.innerHTML = '';
                       var cityOpt = document.createElement('option');
                       cityOpt.value = '0';
                       cityOpt.text = '--Escoja Una--';
                       city.appendChild(cityOpt);
                   }
               },
               error: function () {
                   document.getElementById("first_name").disabled = false;
                   document.getElementById("last_name").disabled = false;
                   document.getElementById("document_id").disabled = false;
                   document.getElementById("second_name").disabled = false;
                   document.getElementById("second_last_name").disabled = false;
               },
               complete: function () {
                   //Hide Loader
                   var loaderGif = document.getElementById("loaderGif");
                   loaderGif.classList.remove("loaderGif");
               }
           });
       } else {
           $('select[name="province"]').empty();
       }
   }
   function myButton_onclick() {
       $(".alert").addClass('hidden');
       ;
   }
   ;
   
   function validateCode() {
       event.preventDefault();
       var url = ROUTE + "/sales/activate";
       var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
       var code = document.getElementById('code').value;
       var salId = document.getElementById('salId').value;
       var data = {code: code};
       $.ajax({
           url: url,
           type: "POST",
           data: {_token: CSRF_TOKEN, code, salId},
           success: function (data)
           {
               var uploadPic = document.getElementById("resultMessage");
               uploadPic.innerHTML = data.data;
               if (data.success == 'true') {
                   document.getElementById("confirmModal").click();
               }
           }
       });
   }
   ;
   function resendCode() {
       event.preventDefault();
       var url = ROUTE + "/sales/resend/code";
       var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
       var salId = document.getElementById('salId').value;
       var customerMobilePhone = document.getElementById("mobile_phone").value;
   //    var data = {code: salId};
       $.ajax({
           url: url,
           type: "POST",
           data: {_token: CSRF_TOKEN, salId, customerMobilePhone},
           success: function (data)
           {
               var uploadPic = document.getElementById("resultMessage");
               uploadPic.innerHTML = data;
           }
       });
   }
   ;
   
   function documentBtn() {
       validateDocument();
       validateInput();
   }
   
   function documentCompanyBtn() {
       var documentNumber = document.getElementById("documentCompany").value;
       var url = ROUTE + '/company/document/autofill/' + documentNumber;
       if (documentNumber) {
           $.ajax({
               url: url,
               type: "GET",
               dataType: "json",
               beforeSend: function () {
   //              Show Loader
                   $("#loaderGif").addClass('loaderGif');
   //              $("#loaderBody").addClass('loaderBody');
               },
               success: function (data) {                   
                   if (data.success == 'true') {
                       document.getElementById("business_name").disabled = true;
                       document.getElementById("tradename").disabled = true;
                       document.getElementById("documentCompanyid").disabled = true;
                       document.getElementById("business_name").value = data.razon_social;
                       document.getElementById("tradename").value = data.nombrefantasia;
                       document.getElementById("documentCompanyid").value = 2;
                       document.getElementById("document").value = data.cedularepresentantelegal;
                   } else {
                       document.getElementById("business_name").disabled = false;
                       document.getElementById("tradename").disabled = false;
                       document.getElementById("documentCompanyid").disabled = false;
                       document.getElementById("business_name").value = '';
                       document.getElementById("tradename").value = '';   
                       document.getElementById("documentCompanyid").value = '0';
                   }
               },
               error: function () {
                   document.getElementById("first_name").disabled = false;
                   document.getElementById("last_name").disabled = false;
                   document.getElementById("document_id").disabled = false;
                   document.getElementById("second_name").disabled = false;
                   document.getElementById("second_last_name").disabled = false;
               },
               complete: function () {
                   //Hide Loader
                   var loaderGif = document.getElementById("loaderGif");
                   loaderGif.classList.remove("loaderGif");
                   var cusDocument = document.getElementById("document").value;
                   if(cusDocument !== ''){
                       document.getElementById("documentBtnSearch").click();
                   }
               }
           });
       } else {
           $('select[name="province"]').empty();
       }
   }
   
   function documentCompanyClick(){
       document.getElementById("business_name").disabled = true;
       document.getElementById("tradename").disabled = true;
       document.getElementById("documentCompanyid").disabled = true;
       document.getElementById("business_name").value = '';
       document.getElementById("tradename").value = '';   
       document.getElementById("documentCompanyid").value = '0';
       $("#business_name_validation").addClass('hidden');
       $("#tradename_validation").addClass('hidden');
       $("#documentCompany_validation").addClass('hidden');
       $("#business_name").removeClass('inputRedFocus');
       $("#tradename").removeClass('inputRedFocus');
       $("#documentCompany").removeClass('inputRedFocus');
       $("#documentCompanyid").removeClass('inputRedFocus');
       $("#documentCompanyid_validation").addClass('hidden');
   }
   
   function BtnSave() {
        event.preventDefault();
       //Validate Variable
       var validate = 'false';
       //Validate Inputs
       var business_name = document.getElementById("business_name");
       var business_name_validation = document.getElementById("business_name_validation");
       var business_name_validation_length= document.getElementById("business_name_validation_length");
       if (business_name.value === "") {
           $(business_name).addClass('inputRedFocus');
           $(business_name_validation).removeClass('hidden');
           validate = 'true';
       }else if(business_name.value.length > 60){
           $(business_name).addClass('inputRedFocus');
           $(business_name_validation_length).removeClass('hidden');
           validate = 'true';
       }
       var documentCompany = document.getElementById("documentCompany");
       var documentCompany_validation=document.getElementById("documentCompany_validation");
       var documentCompany_validation_length =document.getElementById("documentCompany_validation_length");
       if (documentCompany.value === "") {
          $(documentCompany).addClass('inputRedFocus');
         $(documentCompany_validation).removeClass('hidden');
          validate = 'true';
       }else if(documentCompany.value.length > 13){
        $(documentCompany).addClass('inputRedFocus');
        $(documentCompany_validation_length).removeClass('hidden');
        validate = 'true';
        }
        var documentCompanyid = document.getElementById("documentCompanyid");
        var documentCompanyid_validation= document.getElementById("documentCompanyid_validation");
        if (documentCompanyid.value === "0") {
            $(documentCompanyid).addClass('inputRedFocus');
            $(documentCompanyid_validation).removeClass('hidden');
            validate = 'true';
        } 
       var tradename_validation_length = document.getElementById("tradename_validation_length");
       var tradename=document.getElementById("tradename");
       if (tradename.value.length > 60){
         $(tradename).addClass('inputRedFocus');
         $(tradename_validation_length).removeClass('hidden');
         validate = 'true';
    }
       var documentNumber = document.getElementById("document");
       var document_validation=document.getElementById("document_validation");
       if (documentNumber.value === "") {
           $(documentNumber).addClass('inputRedFocus');
           $(document_validation).removeClass('hidden');
           validate = 'true';
       }
       var first_name = document.getElementById("first_name");
       var first_name_validation = document.getElementById("first_name_validation");
       if (first_name.value === "" || first_name.value.length > 30) {
           $(first_name).addClass('inputRedFocus');
           $(first_name_validation).removeClass('hidden');
           validate = 'true';
       }
       var second_name = document.getElementById("second_name");
       var second_name_validation = document.getElementById("second_name_validation");
       if (second_name.value.length > 30) {
          $(second_name).addClass('inputRedFocus');
          $(second_name_validation).removeClass('hidden');
           validate = 'true';
       }
       var document_id = document.getElementById("document_id");
       var document_id_validation= document.getElementById("document_id_validation");
       if (document_id.value === "0") {
           $(document_id).addClass('inputRedFocus');
           $(document_id_validation).removeClass('hidden');
           validate = 'true';
       } else if (document_id.value === "1") {
           if (isNaN(documentNumber.value)) {
               $(documentNumber).addClass('inputRedFocus');
               $(document_id).addClass('inputRedFocus');
               validate = 'true';
           }
       }
       var last_name = document.getElementById("last_name");
       var last_name_validation = document.getElementById("last_name_validation");
       if (last_name.value === "" || last_name.value.length > 30) {
           $(last_name).addClass('inputRedFocus');
           $(last_name_validation).removeClass('hidden');
           validate = 'true';
       }
       var second_last_name = document.getElementById("second_last_name");
       var second_last_name_validation=document.getElementById("second_last_name_validation");
       if (second_last_name.value === "" || second_last_name.value.length > 30) {
           $(second_last_name).addClass('inputRedFocus');
           $(second_last_name_validation).removeClass("hidden");
           validate = 'true';
       }
       var mobile_phone = document.getElementById("mobile_phone");
       var mobile_phone_validation = document.getElementById("mobile_phone_validation");
       var mobile_phone_validation_length= document.getElementById("mobile_phone_validation_length");
       if (mobile_phone.value === "") {
           $(mobile_phone).addClass('inputRedFocus');
           $(mobile_phone_validation).removeClass('hidden');
           validate = 'true';
       } else {
           if (isNaN(mobile_phone.value)) {
               $(mobile_phone).addClass('inputRedFocus');
               validate = 'true';
           }
           if (mobile_phone.value.length != 10) {
               $(mobile_phone).addClass('inputRedFocus');
               $(mobile_phone_validation_length).removeClass("hidden");
               validate = 'true';
           }
       }
   
       var email = document.getElementById("email");
       var emailValidate = ValidateEmail(email.value);
       var email_validation = document.getElementById("email_validation");
       var email_error_validation = document.getElementById("email_error_validation");
   //        console.log(emailValidate);
       if (email.value === ""  || email.value.length > 100) {
           $(email).addClass('inputRedFocus');
           $(email_validation).removeClass('hidden');
           validate = 'true';
       } else if (emailValidate === false) {
           $(email).addClass('inputRedFocus');
           $(email_error_validation).removeClass('hidden');
           validate = 'true';
   
       }
       var branch = document.getElementById("branch");
       var branch_validation = document.getElementById("branch_validation");
       if (branch.value === "0") {
           $(branch).addClass('inputRedFocus');
           $(branch_validation).removeClass('hidden');
           validate = 'true';
       }
       var product = document.getElementById("product");
       var product_validation = document.getElementById("product_validation");
        if (product.value === "0") {
        $(product).addClass('inputRedFocus');
        $(product_validation).removeClass('hidden');
        validate = 'true';
      }
       var emissionType = document.getElementById("emissionType");
       var emissionType_validation = document.getElementById("emissionType_validation");
       if (emissionType.value === "0") {
           $(emissionType).addClass('inputRedFocus');
           $(emissionType_validation).removeClass('hidden');
           validate = 'true';
       }
       var insuredValue = document.getElementById("insuredValue");
       var insuredValue_validation = document.getElementById("insuredValue_validation");
       if (insuredValue.value === "") {
           $(insuredValue).addClass('inputRedFocus');
           $(insuredValue_validation).removeClass('hidden');
           validate = 'true';
       }
       var netPremium = document.getElementById("netPremium");
       var netPremium_validation = document.getElementById("netPremium_validation");
       if (netPremium.value=== "") {
           $(netPremium).addClass('inputRedFocus');
           $(netPremium_validation).removeClass('hidden');
           validate = 'true';
       }
       var customerAlert = document.getElementById("customerAlert");
       if (validate === 'true') {
           customerAlert.classList.remove("hidden");
           $(customerAlert).addClass("visible");
           return false;
       }else{
           customerAlert.classList.remove("visible");
           $(customerAlert).addClass("hidden");
       } 
     if(validate=='false'){  
          
       var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
       var documentCompanyid = document.getElementById("documentCompanyid").value;
       var documentCompany = document.getElementById("documentCompany").value;
       var business_name = document.getElementById("business_name").value;
       var tradename = document.getElementById("tradename").value;
       var documentNumber = document.getElementById("document").value;
       var first_name = document.getElementById("first_name").value;
       var second_name = document.getElementById("second_name").value;
       var document_id = document.getElementById("document_id").value;
       var last_name = document.getElementById("last_name").value;
       var second_last_name = document.getElementById("second_last_name").value;
       var mobile_phone = document.getElementById("mobile_phone").value;
       var email = document.getElementById("email").value;
       var branch = document.getElementById("branch").value;
       var product= document.getElementById("product").value;
       var emissionType = document.getElementById("emissionType").value;    
       var insuredValue = document.getElementById("insuredValue").value;
       var netPremium = document.getElementById("netPremium").value;
       var url = ROUTE + '/massivesVinculation/legalPerson/store';
       var data={documentCompanyid:documentCompanyid, documentCompany:documentCompany, business_name:business_name, tradename:tradename, documentNumber:documentNumber,first_name:first_name,second_name:second_name,document_id:document_id,last_name:last_name,second_last_name:second_last_name,mobile_phone:mobile_phone,email:email,branch:branch,emissionType:emissionType,insuredValue :insuredValue,netPremium:netPremium,product:product};
       $.ajax({
           type: "POST",
           data: {_token: CSRF_TOKEN,data},
           url: url,
           beforeSend: function () {
               // Show Loader
               $("#loaderGif").addClass('loaderGif');
   //                $("#loaderBody").addClass('loaderBody');   
           },
           success: function (data) {
               //console.log(data);
               $("#loaderGif").addClass('loaderGif');
               window.location.href=ROUTE + '/massivesVinculation';  
           },
           complete: function () {
               //Hide Loader
               var loaderGif = document.getElementById("loaderGif");
               $("#loaderGif").addClass('loaderGif');
   //                var loaderBody = document.getElementById("loaderBody");
   //                loaderBody.classList.remove("loaderBody");
           }
       });
    } 
   }
   ;
  
   function productSearch(){
    var url = ROUTE + '/massivesVinculation/selectProduct';
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var branch = document.getElementById("branch").value;
    $.ajax({
        type: "POST",
        data: {_token: CSRF_TOKEN, branch:branch},
        url: url,
        beforeSend: function () {
            // Show Loader
            $("#loaderGif").addClass('loaderGif');
        },
        success: function (data) {
            document.getElementById("product").innerHTML = data; 
        },
        complete: function () {
            //Hide Loader
            var loaderGif = document.getElementById("loaderGif");
            loaderGif.classList.remove("loaderGif");
        }
    });
}