<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ProcessExcelJob;
use Illuminate\Support\Facades\Storage;

class ExcelProcessingController extends Controller
{
    /**
     * Pokretanje obrade Excel fajla.
     */
    public function process()
    {
        $filePath = Storage::path('public/previous_data.xlsx');

        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        ProcessExcelJob::dispatch($filePath);

        return response()->json(['success' => true, 'message' => 'Excel processing queued.']);
    }
}