<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ErrorController extends Controller
{
    public function logError(Request $request)
    {
        $data = $request->all();

        // Format the log message
        $logMessage = sprintf(
            "[%s] Action: %s | Region ID: %s | Error: %s",
            $data['timestamp'] ?? now(),
            $data['action'] ?? 'Unknown',
            $data['regionId'] ?? 'N/A',
            $data['error'] ?? 'No error message'
        );

        Log::error($logMessage);

        return response()->json(['success' => true]);
    }
}