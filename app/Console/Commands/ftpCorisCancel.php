<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ftpCorisCancel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ftp:corisCancel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Procesar excel de Cancelaciones Coris/Diners via FTP';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        processFtpCorisCancel();
    }
}
