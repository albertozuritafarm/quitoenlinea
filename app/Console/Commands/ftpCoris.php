<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ftpCoris extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ftp:coris';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Procesar excel de Coris/Diners via FTP';

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
        processFtpCoris();
    }
}
