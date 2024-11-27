<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProcessAgencyLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:agency-log {logFile}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processes log files line by line and sends JSON data to a specified API';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $logFile = $this->argument('logFile');

        // Check if log file exists
        if (!file_exists($logFile)) {
            $this->error("Log file not found: $logFile");
            return 1;
        }

        // Open the file for reading
        $handle = fopen($logFile, 'r');
        if (!$handle) {
            $this->error("Cannot open the log file: $logFile");
            return 1;
        }

        $pattern = '/updateAgencyData start process: (.+)/';

        // Process the file line by line
        while (($line = fgets($handle)) !== false) {
            if (preg_match($pattern, $line, $matches)) {
                $jsonData = $matches[1];

                try {
                    $decodedData = json_decode($jsonData, true);

                    if (json_last_error() !== JSON_ERROR_NONE) {
                        Log::error('Invalid JSON format: ' . $jsonData);
                        $this->warn('Skipping line due to invalid JSON.');
                        continue;
                    }

                    // Send JSON data to the API
                    $response = Http::post('https://765fc07a6200.ngrok.app/api/update-agency-data', $decodedData);

                    if ($response->successful()) {
                        $this->info("Successfully sent data for agency_id: " . $decodedData['agency_id']);
                    } else {
                        $this->error("Failed to send data for agency_id: " . $decodedData['agency_id']);
                        Log::error('API Response: ' . $response->body());
                    }
                } catch (\Exception $e) {
                    Log::error('Error processing line: ' . $line . ' - ' . $e->getMessage());
                    $this->error('An error occurred: ' . $e->getMessage());
                }
            }
        }

        // Close the file
        fclose($handle);

        $this->info('Log processing completed.');
        return 0;
    }
}