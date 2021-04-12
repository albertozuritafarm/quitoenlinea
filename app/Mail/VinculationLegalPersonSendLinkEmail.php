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

class VinculationLegalPersonSendLinkEmail extends Mailable {

    use Queueable,
        SerializesModels;

    public $saleId;
    public $legalRepresentativeDocument;
    public $companyDocument;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($saleId, $legalRepresentativeDocument,$companyDocument ) {
        $this->saleId = $saleId;
        $this->legalRepresentativeDocument = $legalRepresentativeDocument;
        $this->companyDocument =$companyDocument;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        $customerQuery = 'select concat(cus.first_name," ",IFNULL(cus.second_name, "")," ",cus.last_name," ",cus.second_last_name) as "Cliente", concat(cus.first_name," ",IFNULL(cus.second_name, "")) as "names", concat(cus.last_name," ",cus.second_last_name) as "lastNames", pro.ramodes as "ramo", cus.document as "cusDocument", DATE_FORMAT(sal.emission_date, "%Y") as "year", sal.id as "id", pro.name as "proName", DATE_FORMAT(sal.emission_date, "%d/%m/%Y") as "date", cus.email as "email", cus.mobile_phone as "mobile", cus.phone as "phone", sal.agen_id as "agen_id", CONCAT(users.first_name," ",users.last_name) as "user", ch.name as "channel_name", doc.name as "typeDocDes", cus.birth_date as "cusBirthdate"
                                        from customer_legal_representative cus                                        
                                        join sales sal on sal.customer_legal_representative_id = cus.id                                         
                                        join products_channel pbc on pbc.id = sal.pbc_id
                                        join products pro on pro.id = pbc.product_id
                                        join users on users. id = sal.user_id
                                        join documents doc on cus.document_id = doc.id
                                        join channels ch on ch.id = pbc.channel_id
                                        where sal.id = "' . $this->saleId . '"';        
        $customer = DB::select($customerQuery);
        $companyQuery = 'select com.business_name as "businessName"
                                        from companys com                                        
                                        join sales sal on sal.company_id = com.id  
                                        where sal.id = "' . $this->saleId . '"';
        $company = DB::select($companyQuery);
        $email = $customer[0]->email;
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email = 'demo@magnusmas.com';
        }
        
        $document = encrypt($this->legalRepresentativeDocument);
        $companyDoc=encrypt($this->companyDocument); 
        $sale = encrypt($this->saleId);
            
        $short = generateShortLink();
        $url = 'https://tupolizaenlineatest.segurossucre.fin.ec/legalPersonVinculation/create?document='.$document.'&sales='.$sale.'&companys='.$companyDoc;
        
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
        
        return $this->view('emails.vinculationLegalPersonFormSendLink')
                    ->from($email_SS->value, $tag_SS->value)
                    ->subject('Te falta poco para llegar a tu LUGAR SEGURO')
                    ->replyTo($email_SS->value, $tag_SS->value)
                    ->with([
                        'id' => $this->saleId,
                        'document' => $document,
                        'companyDoc'=>$companyDoc,
                        'sale' => $sale,
                        'company'=>$company,
                        'customer' => $customer
        ]);
    }
}