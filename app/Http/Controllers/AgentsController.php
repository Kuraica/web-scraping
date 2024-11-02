<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AgentsController
{

    public function getFirstAgents(Request $request)
    {
        return view('scraping-asc');
    }

    public function getLastAgents(Request $request)
    {
        return view('scraping-desc');
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
