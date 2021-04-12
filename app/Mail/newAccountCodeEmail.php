<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use DB;
use Barryvdh\DomPDF\Facade as PDF;
use Dompdf\Adapter\PDFLib;

class newAccountCodeEmail extends Mailable {

    use Queueable,
        SerializesModels;

    public $customerId;
    public $saleId;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $code) {
        $this->name = $name;
        $this->code = $code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this->view('emails.newAccountCodeEmail')
                        ->from('demo@magnusmas.com', 'Info')
                        ->subject('Apertura de Cuenta')
                        ->replyTo('demo@magnusmas.com', 'Info')
                        ->with([
                            'name' => $this->name,
                            'code' => $this->code
        ]);
    }

}
