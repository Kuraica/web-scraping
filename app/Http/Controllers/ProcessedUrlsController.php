<?php

namespace App\Http\Controllers;

use App\Models\ProcessedUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProcessedUrlsController
{
    public function update(Request $request)
    {
        Log::info('Podaci iz zahteva: ', $request->all());

        $validatedData = $request->validate([
                                                'url'       => 'required|string',
                                                'page'      => 'nullable|numeric',
                                                'region_id' => 'required|exists:regions,id',
                                            ]);

        Log::info('Validirani podaci: ', $validatedData);

        $processedUrl = ProcessedUrl::create([
                                                 'url'       => $validatedData['url'],
                                                 'page'      => $validatedData['page'],
                                                 'region_id' => $validatedData['region_id'],
                                             ]);


        return response()->json([
                                    'success' => true,
                                    'data'    => $processedUrl,
                                    'message' => 'Proces uspešno ažuriran',
                                ])
            ->header('Access-Control-Allow-Origin', '*');
    }
}
