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

class VinculationSendLinkEmail extends Mailable {

    use Queueable,
        SerializesModels;

    public $saleId;
    public $customerDocument;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($saleId, $customerDocument) {
        $this->saleId = $saleId;
        $this->customerDocument = $customerDocument;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
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

        $email = $customer[0]->email;
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email = 'demo@magnusmas.com';
        }
        
        $document = encrypt($this->customerDocument);
        $sale = encrypt($this->saleId);
            
        $short = generateShortLink();
        $url = 'https://tupolizaenlineatest.segurossucre.fin.ec/vinculation/create?document='.$document.'&sales='.$sale;
        
        $shortUrl = new \App\short_url();
        $shortUrl->short = $short;
        $shortUrl->url = $url;
        $shortUrl->save();
        
        //Send link Vinculation Form SMS
        $message = 'Completa tu formulario de vinculacion en el siguiente link: https://tupolizaenlineatest.segurossucre.fin.ec/su/'.$short.' Seguros Sucre Tu Lugar Seguro';
        smsEclipsoft($customer[0]->mobile, $message);
        
//        $document = $this->customerDocument;
//        $sale = $this->saleId;

        $email_SS = \App\global_vars::find(2);
        $tag_SS = \App\global_vars::find(3);
        
        return $this->view('emails.vinculationFormSendLink')
                    ->from($email_SS->value, $tag_SS->value)
                    ->subject('Te falta poco para llegar a tu LUGAR SEGURO')
                    ->replyTo($email_SS->value, $tag_SS->value)
                    ->with([
                        'id' => $this->saleId,
                        'document' => $document,
                        'sale' => $sale,
                        'customer' => $customer
        ]);
    }
}
