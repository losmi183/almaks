<?php

namespace App\Console\Commands;

use App\Services\NisReport;
use Illuminate\Console\Command;

class Report extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:cards';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetching Cards data from cards.nis.rs and save to DB';

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
     * @return int
     */
    public function handle()
    {
        // CREATE NEW OBJECT
        $nisReport = new NisReport();
        // LOGIN METHOD RETURN TOKEN FOR NEXT REQUEST
        $token = $nisReport->login();
        // CALL getReport with new token
        $allReports = $nisReport->getReport($token);
            
        // Insert into DB
        $nisReport->saveReports($allReports);


        $this->info('Last day reports saved to database');
    }
}
