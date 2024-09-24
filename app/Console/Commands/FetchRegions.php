<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\FetchRegionJob;
use App\Helpers\StringCombinations;
use Illuminate\Support\Facades\Log;

class FetchRegions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:regions {length=3}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch regions from RealEstate API and store unique values';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $length = $this->argument('length');
        $combinations = StringCombinations::generate($length);

        $total = count($combinations);

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        foreach ($combinations as $combo) {
            // Enqueue FetchRegionJob za svaki query
            FetchRegionJob::dispatch($combo)->onQueue('regions');
            $bar->advance();
        }

        $bar->finish();
        $this->info("\nEnqueued all region fetch jobs.");

        return 0;
    }
}
