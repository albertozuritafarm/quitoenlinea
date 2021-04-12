<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\TicketCommentEmail;
use Illuminate\Support\Facades\Mail;

class TicketsCommentEmailJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $ticketId;
    public $ticketDetailId;
    public $email;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($ticketDetailId, $email, $ticketId)
    {
        $this->ticketDetailId = $ticketDetailId;
        $this->email = $email;
        $this->ticketId = $ticketId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new TicketCommentEmail($this->ticketDetailId, $this->ticketId);
        Mail::to($this->email)->send($email);
    }
}
