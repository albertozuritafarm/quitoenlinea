<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use DB;
use Barryvdh\DomPDF\Facade as PDF;
use Dompdf\Adapter\PDFLib;

class infoRiskUnblockadeUserEmail extends Mailable {

    use Queueable,
        SerializesModels;

    public $saleId;
    public $customerDocument;
    public $customerEmail;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($saleId, $customerEmail, $customerDocument)
    {
        $this->saleId = $saleId;
        $this->customerEmail = $customerEmail;
        $this->customerDocument = $customerDocument;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        $customerQuery = 'select concat(cus.first_name," ",cus.second_name," ",cus.last_name," ",cus.second_last_name) as "Cliente", concat(cus.first_name," ",cus.second_name) as "names", concat(cus.last_name," ",cus.second_last_name) as "lastNames", pro.ramodes as "ramo", cus.document as "cusDocument", DATE_FORMAT(sal.emission_date, "%Y") as "year", sal.id as "id", pro.name as "proName", DATE_FORMAT(sal.emission_date, "%d/%m/%Y") as "date", cus.email as "email", cus.mobile_phone as "mobile", cus.phone as "phone", doc.name as "typeDocDes", cus.birthdate as "cusBirthdate", pro.id as "idProduct", concat(us.first_name," ",us.last_name) as "user"
                            from customers cus
                            join sales sal on sal.customer_id = cus.id
                            join products_channel pbc on pbc.id = sal.pbc_id
                            join products pro on pro.id = pbc.product_id
                            join documents doc on cus.document_id = doc.id
                            join users us on us.id=sal.user_id
                            where sal.id = "' . $this->saleId . '"';
        $customer = DB::select($customerQuery);
        
        $sales = \App\sales::find($this->saleId);
        
        $productName = \App\sales::selectRaw('products.name, products.productodes, products.price, cus.document')
                                    ->join('products_channel as pbc','pbc.id','sales.pbc_id')
                                    ->join('products','products.id','=','pbc.product_id')
                                    ->join('customers as cus','cus.id','=','sales.customer_id')
                                    ->where('sales.id','=',$this->saleId)
                                    ->get();

        $email = $customer[0]->email;
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email = 'coberto@magnusmas.com';
        }
        
        $document = encrypt($this->customerDocument);
        $sale = encrypt($this->saleId);
//        $document = $this->customerDocument;
//        $sale = $this->saleId;
        
         //Validate ID Length
        if (strlen($customer[0]->id) == 3) {
            $id = '00' . $customer[0]->id;
        } else if (strlen($customer[0]->id) == 4) {
            $id = '0' . $customer[0]->id;
        } else {
            $id = $customer[0]->id;
        }

        $returnBenefits = false; 
        
        $email_SS = \App\global_vars::find(2);
        $tag_SS = \App\global_vars::find(3);
        
        return $this->view('emails.infoRiskUnblockadeUser')
                    ->from($email_SS->value, $tag_SS->value)
                    ->subject('Notificación – Desbloqueo Alerta de Riesgo Cotización  ' .$this->saleId)
                    ->replyTo($email_SS->value, $tag_SS->value)
                    ->with([
                        'id' => $this->saleId,
                        'document' => $document,
                        'sale' => $sale,
                        'customer' => $customer
        ]);
    }
}
