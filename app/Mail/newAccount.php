<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use DB;
use Barryvdh\DomPDF\Facade as PDF;
use Dompdf\Adapter\PDFLib;

class newAccount extends Mailable {

    use Queueable,
        SerializesModels;

    public $customerId;
    public $saleId;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($customerId) {
        $this->customerId = $customerId;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        $customerQuery = 'select cus.*, doc.name from customers cus join documents doc on doc.id = cus.document_id where cus.id = "'.$this->customerId.'"';
        $customer = DB::select($customerQuery);
        
        $email = $customer[0]->email;
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email = 'info@magnusmas.com';
        }
        $name = $customer[0]->first_name . ' '. $customer[0]->last_name;
        $document = $customer[0]->document;
        $document_type = $customer[0]->name;
        return $this->view('emails.newAccount')
                        ->from('demo@magnusmas.com', 'Info')
                        ->subject('Apertura de Cuenta')
                        ->replyTo('demo@magnusmas.com', 'Info')
                        ->with([
                            'name' => $name,
                            'document' => $document, 
                            'document_type' => $document_type
        ]);
    }

}
