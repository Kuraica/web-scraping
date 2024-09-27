<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Illuminate\Http\Request;

class AgentsController
{

    public function getAgents(Request $request)
    {
        return view('scraping');
    }

    public function checkAgent(string $agent)
    {
        $agentId = substr($agent, strrpos($agent, '-') + 1);

        $agentExists = Agent::where('agent_id', $agentId)->exists();

        if ($agentExists) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }
}
