<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\QuotationEmail;
use Illuminate\Support\Facades\Mail;

class IplaBloqueadosEmailJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $saleId;
    public $customerEmail;
    public $customerDocument;

    /**
     * Create a new job instance.
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
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new \App\Mail\QuotationEmail($this->saleId, $this->customerDocument);
        Mail::to($this->customerEmail)->send($email);
    }
}
