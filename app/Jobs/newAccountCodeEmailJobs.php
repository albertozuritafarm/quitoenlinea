<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\Email;
use App\Mail\newAccountCodeEmail;
use Illuminate\Support\Facades\Mail;

class newAccountCodeEmailJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $name;
    public $value;
    public $bank;
    public $code;
    public $customerEmail;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($name, $code, $customerEmail)
    {
        $this->name = $name;
        $this->code = $code;
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
        $email = new newAccountCodeEmail($this->name, $this->code);
        Mail::to($this->customerEmail)->send($email);
    }
}
