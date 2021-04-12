<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\infoListsEjecutivoSSEmailMassivesVinculation;
use Illuminate\Support\Facades\Mail;

class infoListsEjecutivoSSEmailMassivesVinculationJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $saleId;
    public $customerEmail;
    public $customerDocument;
    public $motive;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($saleId, $customerEmail, $customerDocument, $motive)
    {
        $this->saleId = $saleId;
        $this->customerEmail = $customerEmail;
        $this->customerDocument = $customerDocument;
        $this->motive = $motive;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new \App\Mail\infoListsEjecutivoSSEmailMassivesVinculation($this->saleId, $this->customerEmail, $this->customerDocument, $this->motive);
        Mail::to($this->customerEmail)->send($email);
    }
}