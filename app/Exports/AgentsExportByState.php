<?php

namespace App\Exports;

use App\Models\Agent;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AgentsExportByState implements WithMultipleSheets
{
    use Exportable;

    public function sheets(): array
    {
        try {
            // Fetch distinct states, excluding empty values
            $states = Agent::select('agencies.state')
                ->join('agencies', 'agents.agency_id', '=', 'agencies.id')
                ->distinct()
                ->pluck('state')
                ->filter(function ($state) {
                    return !empty($state);
                });

            Log::info('Filtered states: ' . implode(', ', $states->toArray()));

            $sheets = [];
            foreach ($states as $state) {
                $sheets[$state] = new AgentsExportSheet($state); // Sheet name matches the state
            }

            return $sheets;
        } catch (\Exception $e) {
            Log::error('Error fetching states: ' . $e->getMessage());
            throw $e;
        }
    }

}