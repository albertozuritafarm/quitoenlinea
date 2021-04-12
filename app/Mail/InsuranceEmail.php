<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use DB;
use Barryvdh\DomPDF\Facade as PDF;
use Dompdf\Adapter\PDFLib;

class InsuranceEmail extends Mailable {

    use Queueable,
        SerializesModels;

    public $insuranceId;
    public $code;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($insuranceId, $code) {
        $this->insuranceId = $insuranceId;
        $this->code = $code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        //Data
        $insurance = \App\insurance::find($this->insuranceId);
        $customer = \App\customers::find($insurance->customer_id);
        
        return $this->view('emails.insurance')
                        ->from('demo@magnusmas.com', 'Info')
                        ->subject('Codigo de Validacion')
                        ->replyTo('demo@magnusmas.com', 'Info')
                        ->with([
                            'insuranceId' => $this->insuranceId,
                            'code' => $this->code,
                            'customer' => $customer->first_name. ' '.$customer->last_name
                        ]);
    }

}
