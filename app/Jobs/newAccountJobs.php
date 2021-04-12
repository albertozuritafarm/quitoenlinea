<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\Email;
use App\Mail\newAccount;
use Illuminate\Support\Facades\Mail;

class newAccountJobs implements ShouldQueue
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
    public function __construct($customerId, $customerEmail)
    {
        $this->customerId = $customerId;
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
        $email = new newAccount($this->customerId);
        Mail::to($this->customerEmail)->send($email);
    }
}
