<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class WebScrapingTestController extends Controller
{
    public function scrapeAgent()
    {
        // Pokrenite Dusk test koristeći Artisan komandu
//        Artisan::call('dusk', [
//            '--filter' => 'ScrapeAgentTest'
//        ]);

        // Pročitajte rezultate iz Redis-a
        $data = json_decode(Redis::get('scraped_data'), true);

        if ($data) {
            return response()->json($data);
        } else {
            return response()->json(['error' => 'Scraping failed or no data found.']);
        }
    }
}
