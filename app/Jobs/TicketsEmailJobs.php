<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\TicketEmail;
use Illuminate\Support\Facades\Mail;

class TicketsEmailJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $ticketId;
    public $email;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($ticketId, $email)
    {
        $this->ticketId = $ticketId;
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new TicketEmail($this->ticketId);
        Mail::to($this->email)->send($email);
    }
}
