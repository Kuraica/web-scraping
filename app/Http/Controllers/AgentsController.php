<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AgentsController
{

    public function getAgents(Request $request)
    {
        return view('scraping');
    }

    public function checkAgent(string $agentId)
    {
        Log::info('Agent id za proveru: ', [$agentId]);

        $agentExists = Agent::where('agent_id', $agentId)->exists();

        if ($agentExists) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }
}
