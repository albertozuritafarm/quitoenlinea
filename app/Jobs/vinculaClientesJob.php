<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class vinculaClientesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $saleId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($saleId)
    {
        $this->saleId = $saleId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        vinculaClienteeSS($this->saleId);
    }
     /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
}
