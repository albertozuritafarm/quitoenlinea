<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\webserviceEmail;
use Illuminate\Support\Facades\Mail;

class webserviceEmailJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $serviceName;
    public $wsConsult;
    public $wsAnswer;
    /**
     * Create a new job instance.
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
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new \App\Mail\webserviceEmail($this->serviceName,$this->wsConsult,$this->wsAnswer);
//        Mail::to('coberto@magnusmas.com')->cc('emeza@magnusmas.com')->cc('diego.erazo@segurossucre.fin.ec')->send($email);
        Mail::to('coberto@magnusmas.com')->send($email);
    }
}
