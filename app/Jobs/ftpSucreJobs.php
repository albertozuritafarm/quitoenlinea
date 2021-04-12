<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;

class ftpSucreJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $nameRemoteFile;
    private $nameLocalFile;
    private $type;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($nameRemoteFile, $nameLocalFile, $type)
    {
        $this->nameRemoteFile = $nameRemoteFile;
        $this->nameLocalFile = $nameLocalFile;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->type == 1){
            Storage::disk('sftp')->put($this->nameRemoteFile, Storage::disk('s3')->get($this->nameLocalFile));
        }else{
            Storage::disk('sftp')->put($this->nameRemoteFile, Storage::disk('public_sftp')->get($this->nameLocalFile));
        }
    }
}
