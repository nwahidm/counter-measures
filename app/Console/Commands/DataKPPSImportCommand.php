<?php

namespace App\Console\Commands;

use App\Imports\DataKPPSImport;
use Illuminate\Console\Command;

class DataKPPSImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importdata:kpps';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to import data kpps';

    public function __construct()
    {
        ini_set('memory_limit', '-1');
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $file = $this->ask('Please enter absolute or full path file');

        $this->output->title('Starting import data kpps ...');
        $import = new DataKPPSImport;
        $import->withOutput($this->output)->import($file);
        $this->output->success('Import data kpps successfully...');
    }
}
