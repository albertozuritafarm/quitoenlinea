<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use DB;
use Barryvdh\DomPDF\Facade as PDF;
use Dompdf\Adapter\PDFLib;
use Illuminate\Encryption\Encrypter;

class VinculationCompleteEmail extends Mailable {

    use Queueable,
        SerializesModels;

    public $customerId;
    public $saleId;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($saleId) {
        $this->saleId = $saleId;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        $customerValidateQuery = 'select customer_id from sales where sales.id = "' . $this->saleId . '"';
        $customerValidate=DB::select($customerValidateQuery);
        if($customerValidate[0]->customer_id!=null){    
            $customerQuery = 'select concat(cus.first_name," ",IFNULL(cus.second_name, "")," ",cus.last_name," ",cus.second_last_name) as "Cliente", concat(cus.first_name," ",IFNULL(cus.second_name, "")) as "names", concat(cus.last_name," ",cus.second_last_name) as "lastNames", pro.ramodes as "ramo", cus.document as "cusDocument", DATE_FORMAT(sal.emission_date, "%Y") as "year", sal.id as "id", pro.name as "proName", DATE_FORMAT(sal.emission_date, "%d/%m/%Y") as "date", cus.email as "email", cus.mobile_phone as "mobile", cus.phone as "phone", sal.agen_id as "agen_id", CONCAT(users.first_name," ",users.last_name) as "user", ch.name as "channel_name", doc.name as "typeDocDes", cus.birthdate as "cusBirthdate"
            from customers cus
            join sales sal on sal.customer_id = cus.id
            join products_channel pbc on pbc.id = sal.pbc_id
            join products pro on pro.id = pbc.product_id
            join users on users. id = sal.user_id
            join documents doc on cus.document_id = doc.id
            join channels ch on ch.id = pbc.channel_id
            where sal.id = "' . $this->saleId . '"';
            $customer = DB::select($customerQuery);
        }else if($customerValidate[0]->customer_id==null){
            $customerQuery = 'select concat(cus.first_name," ",IFNULL(cus.second_name, "")," ",cus.last_name," ",cus.second_last_name) as "Cliente", concat(cus.first_name," ",IFNULL(cus.second_name, "")) as "names", concat(cus.last_name," ",cus.second_last_name) as "lastNames", pro.ramodes as "ramo", cus.document as "cusDocument", DATE_FORMAT(sal.emission_date, "%Y") as "year", sal.id as "id", pro.name as "proName", DATE_FORMAT(sal.emission_date, "%d/%m/%Y") as "date", cus.email as "email", cus.mobile_phone as "mobile", cus.phone as "phone", sal.agen_id as "agen_id", CONCAT(users.first_name," ",users.last_name) as "user", ch.name as "channel_name", doc.name as "typeDocDes", cus.birth_date as "cusBirthdate"
            from customer_legal_representative cus
            join sales sal on sal.customer_legal_representative_id= cus.id
            join products_channel pbc on pbc.id = sal.pbc_id
            join products pro on pro.id = pbc.product_id
            join users on users. id = sal.user_id
            join documents doc on cus.document_id = doc.id
            join channels ch on ch.id = pbc.channel_id
            where sal.id = "' . $this->saleId . '"';
            $customer = DB::select($customerQuery);  
        }    
     
        //Validate ID Length
        if (strlen($customer[0]->id) == 3) {
            $id = '00' . $customer[0]->id;
        } else if (strlen($customer[0]->id) == 4) {
            $id = '0' . $customer[0]->id;
        } else {
            $id = $customer[0]->id;
        }


        $email = $customer[0]->email;
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email = 'info@magnusmas.com';
        }

        $email_SS = \App\global_vars::find(2);
        $tag_SS = \App\global_vars::find(3);
        
        return $this->view('emails.vinculationFormComplete')
                    ->from($email_SS->value, $tag_SS->value)
                    ->subject('Formulario de Vinculación Completada')
                    ->replyTo($email_SS->value, $tag_SS->value)
                    ->with([
                        'id' => $this->saleId,
                        'year' => $customer[0]->year,
                        'name' => $customer[0]->Cliente,
                        'user' => $customer[0]->user
        ]);
        
    }

}
