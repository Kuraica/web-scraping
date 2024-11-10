<?php

namespace App\Http\Controllers;

use App\Mail\AgentsReportMail;
use App\Models\Agency;
use App\Models\Agent;
use App\Models\Email;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Exports\AgentsExport;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class AgentsController
{

    public function getAgents(Request $request)
    {
        $email = Email::latest()->value('email');

        // Pass the email value to the view
        return view('scraping', ['email' => $email]);
    }

    public function getFirstAgents(Request $request)
    {
        $email = Email::latest()->value('email');

        // Pass the email value to the view
        return view('scraping-asc', ['email' => $email]);
    }

    public function getLastAgents(Request $request)
    {
        $email = Email::latest()->value('email');

        // Pass the email value to the view
        return view('scraping-desc', ['email' => $email]);
    }

    public function checkAgent(string $agentId)
    {
        Log::info('Agent id za proveru: ', [$agentId]);

        $agentExists = Agent::where('agent_id', $agentId)->exists();

        if ($agentExists || in_array($agentId, ['1653210', '54398', '3402060', '2532634'])) {
//        if ($agentExists) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    /**
     * Export agents and agency in Excel.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export()
    {
        $states = Agency::distinct()->pluck('state');

        foreach ($states as $state) {
            $fileName = 'agents_agencies_' . $state . '.xlsx';

            // Generiši Excel fajl za trenutni state
            Excel::store(new AgentsExport($state), $fileName, 'public'); // 'public' storage disk

            echo "Generated report for state: {$state}, saved as {$fileName}\n";
        }
    }

    public function sendAgentsReport()
    {
        Mail::to('v.kuraica@gmail.com')->send(new AgentsReportMail());

        return "Email sent successfully with the attached Excel report.";
    }
}
