<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\InsuranceEmail;
use Illuminate\Support\Facades\Mail;

class InsuranceEmailJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $insuranceId;
    public $emailCus;
    public $code;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($insuranceId, $emailCus, $code)
    {
        $this->insuranceId = $insuranceId;
        $this->emailCus = $emailCus;
        $this->code = $code;
    }   

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new InsuranceEmail($this->insuranceId, $this->code);
        Mail::to($this->emailCus)->send($email);
    }
}
