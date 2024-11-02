<?php

namespace App\Http\Controllers;

use App\Models\ProcessedUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProcessedUrlsController
{
    /**
     * POST
     *
     * @param string url
     * @param string page
     * @param int region_id
     */
    public function update(Request $request)
    {
        Log::info('ProcessedUrlsController update data: ', $request->all());

        $validatedData = $request->validate([
            'order'     => 'required|string',
            'url'       => 'required|string',
            'page'      => 'nullable|numeric',
            'region_id' => 'required|exists:regions,id',
        ]);

        Log::info('Validated data: ', $validatedData);

        $processedUrl = ProcessedUrl::create([
            'order'     => $validatedData['order'],
            'url'       => $validatedData['url'],
            'page'      => $validatedData['page'],
            'region_id' => $validatedData['region_id'],
        ]);


        return response()->json([
            'success' => true,
            'data'    => $processedUrl,
            'message' => 'Process updated successful',
        ])
            ->header('Access-Control-Allow-Origin', '*');
    }

    /**
     * Continue data scraping by fetching the last processed URL.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function continueScraping(string $order)
    {
        $latestProcessedUrl = ProcessedUrl::latest()->where('order', $order)->first();

        if ($latestProcessedUrl) {
            return response()->json([
                'success'   => true,
                'url'       => $latestProcessedUrl->url,
                'region_id' => $latestProcessedUrl->region_id,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No processed URLs found.',
            ], 404);
        }
    }
}
