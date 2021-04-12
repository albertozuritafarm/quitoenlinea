<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\Email;
use Illuminate\Support\Facades\Mail;

class EmailJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $customerId;
    public $saleId;
    public $customerEmail;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($customerId, $saleId, $customerEmail)
    {
        $this->customerId = $customerId;
        $this->saleId = $saleId;
        $this->customerEmail = $customerEmail;
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new Email($this->customerId, $this->saleId);
        Mail::to($this->customerEmail)->send($email);
    }
}
