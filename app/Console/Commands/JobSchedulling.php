<?php

namespace App\Console\Commands;

use App\Http\Controllers\SentimentController;
use Illuminate\Console\Command;

class JobSchedulling extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:job-schedulling';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sentimentController = new SentimentController();
        $sentimentController->sentimentKegiatanPosko();
        $sentimentController->sentimentPolling();
    }
}
