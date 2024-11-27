<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Exports\AgentsExportByState;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class ExportAgentsToExcel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:agents {filePath}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Exports agents data to an Excel file, grouped by state.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('filePath');

        $this->info('Starting export process...');
        Log::info('Starting export process.');
        $this->info("File will be saved to: $filePath");
        Log::info("File will be saved to: $filePath");

        try {
            // Proveri da li postoji folder
            if (!is_dir(storage_path('app/exports'))) {
                $this->warn('Exports folder does not exist. Creating...');
                Log::warning('Exports folder does not exist. Creating...');
                mkdir(storage_path('app/exports'), 0755, true);
            }

            Log::info('Folder check complete.');

            // Start timing
            $startTime = microtime(true);

            // Perform the export
            Log::info('Starting Excel export...');
            Excel::store(new AgentsExportByState, $filePath);
            Log::info('Excel export completed.');

            // End timing
            $endTime = microtime(true);
            $elapsedTime = round($endTime - $startTime, 2);

            // Notify success
            $this->info("Export completed successfully in {$elapsedTime} seconds.");
            Log::info("Export completed successfully in {$elapsedTime} seconds. File saved to: storage/app/{$filePath}");

        } catch (\Exception $e) {
            // Notify about errors
            $this->error('Error during export: ' . $e->getMessage());
            Log::error('Error during export: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
        }

        return 0;
    }
}