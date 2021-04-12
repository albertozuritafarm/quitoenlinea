<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use DB;
use Barryvdh\DomPDF\Facade as PDF;
use Dompdf\Adapter\PDFLib;

class financingCodeEmail extends Mailable {

    use Queueable,
        SerializesModels;

    public $name;
    public $value;
    public $bank;
    public $code;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $value, $bank, $code) {
        $this->name = $name;
        $this->value = $value;
        $this->bank = $bank;
        $this->code = $code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this->view('emails.financingCodeEmail')
                        ->from('demo@magnusmas.com', 'Info')
                        ->subject('Código de Autorización (Proceso de Compra)')
                        ->replyTo('demo@magnusmas.com', 'Info')
                        ->with([
                            'name' => $this->name,
                            'value' => $this->value, 
                            'bank' => $this->bank,
                            'code' => $this->code
        ]);
    }

}
