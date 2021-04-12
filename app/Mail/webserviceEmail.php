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


class webserviceEmail extends Mailable {

    use Queueable,
        SerializesModels;

    public $serviceName;
    public $wsConsult;
    public $wsAnswer;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($serviceName, $wsConsult, $wsAnswer)
    {
        $this->serviceName = $serviceName;
        $this->wsConsult = $wsConsult;
        $this->wsAnswer = $wsAnswer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {     
        $email_SS = \App\global_vars::find(2);
        $tag_SS = \App\global_vars::find(3);
        
        return $this->view('emails.webserviceEmail')
                    ->from($email_SS->value, $tag_SS->value)
                    ->subject('Error Web Service:'. $this->serviceName)
                    ->replyTo($email_SS->value, $tag_SS->value)
                    ->with([
                        'wsConsult' => $this->wsConsult,
                        'wsAnswer' => $this->wsAnswer
        ]);
    }
}
