<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ExportRegions extends Command
{
    protected $signature = 'export:regions';
    protected $description = 'Export regions data to a JSON file';

    public function handle()
    {
        $regions = DB::table('regions')->get();
        File::put(storage_path('app/regions.json'), $regions->toJson());

        $this->info('Regions exported successfully!');
    }
}
