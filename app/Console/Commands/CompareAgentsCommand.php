<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CompareAgentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'compare:agents';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compare agents and previously processed agents and provide counts for unique IDs.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Fetch agent_ids from agents that do not exist in previously_processed_agents
//        $onlyInAgents = DB::table('agents')
//            ->leftJoin('previously_processed_agents', 'agents.agent_id', '=', 'previously_processed_agents.rea_id')
//            ->whereNull('previously_processed_agents.rea_id')
//            ->count();

        // Fetch first 20 rea_links from previously_processed_agents that do not exist in agents
        $onlyInProcessedAgentsLinks = DB::table('previously_processed_agents')
            ->leftJoin('agents', 'previously_processed_agents.rea_id', '=', 'agents.agent_id')
            ->whereNull('agents.agent_id')
            ->where('previously_processed_agents.state', 'VIC')
            ->select('previously_processed_agents.rea_link')
            ->limit(20)
            ->pluck('rea_link');

        // Display the results
//        $this->info("Total agent_ids only in 'agents' table: $onlyInAgents");
        $this->info("First 20 rea_links only in 'previously_processed_agents' table:");
        foreach ($onlyInProcessedAgentsLinks as $link) {
            $this->line($link);
        }

        return Command::SUCCESS;
    }
}