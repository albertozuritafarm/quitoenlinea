<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\VinculationSendLinkEmail;
use Illuminate\Support\Facades\Mail;

class VinculationLegalPersonSendLinkEmailJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $saleId;
    public $legalRepresentativeEmail;
    public $legalRepresentativeDocument;
    public $companyDocument;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($saleId, $legalRepresentativeEmail, $legalRepresentativeDocument,$companyDocument)
    {
        $this->saleId = $saleId;
        $this->legalRepresentativeEmail = $legalRepresentativeEmail;
        $this->legalRepresentativeDocument = $legalRepresentativeDocument;
        $this->companyDocument =$companyDocument;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new \App\Mail\VinculationLegalPersonSendLinkEmail($this->saleId, $this->legalRepresentativeDocument,$this->companyDocument);
        Mail::to($this->legalRepresentativeEmail)->send($email);
    }
}