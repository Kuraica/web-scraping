<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BackupAgentsAndAgencies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:agents-agencies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create JSON backups of agents and agencies tables and create backup tables';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Get today's date in format day_month_year
        $todayDate = Carbon::now()->format('d_m_Y');

        // Step 1: Export agents data to JSON file
        $agentsData = DB::table('agents')->get();
        $agentsJsonFileName = "agents_{$todayDate}.json";
        Storage::put("{$agentsJsonFileName}", $agentsData->toJson(JSON_PRETTY_PRINT));
        $this->info("Agents data exported to storage/app/{$agentsJsonFileName}");

        // Step 2: Export agencies data to JSON file
        $agenciesData = DB::table('agencies')->get();
        $agenciesJsonFileName = "agencies_{$todayDate}.json";
        Storage::put("{$agenciesJsonFileName}", $agenciesData->toJson(JSON_PRETTY_PRINT));
        $this->info("Agencies data exported to storage/app/{$agenciesJsonFileName}");

        // Step 3: Create backup tables
        $agentsBackupTableName = "agents_backup_{$todayDate}";
        $agenciesBackupTableName = "agencies_backup_{$todayDate}";

        // Create backup table for agents
        DB::statement("CREATE TABLE {$agentsBackupTableName} AS SELECT * FROM agents");
        $this->info("Backup table created: {$agentsBackupTableName}");

        // Create backup table for agencies
        DB::statement("CREATE TABLE {$agenciesBackupTableName} AS SELECT * FROM agencies");
        $this->info("Backup table created: {$agenciesBackupTableName}");

        $this->info('Backup completed successfully.');

        return Command::SUCCESS;
    }
}