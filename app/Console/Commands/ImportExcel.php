<?php

namespace App\Console\Commands;

use App\Imports\RealEstateAgentImport;
use Illuminate\Console\Command;

class ImportExcel extends Command
{
    protected $signature = 'import:excel';

    protected $description = 'Laravel Excel importer';

    public function handle()
    {
        $filePath = storage_path('app/public/previous_data.xlsx');
        $this->output->title('Starting import');
        (new RealEstateAgentImport())->withOutput($this->output)->import($filePath);
        $this->output->success('Import successful');
    }
}
