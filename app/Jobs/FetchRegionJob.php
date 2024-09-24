<?php

namespace App\Jobs;

use App\Services\RealEstateService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class FetchRegionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $query;
    protected $max;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($query, $max = 200)
    {
        $this->query = $query;
        $this->max = $max;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(RealEstateService $service)
    {
        try {
            // Implementirajte nasumičnu pauzu između 6 i 10 sekundi
//            $sleepSeconds = rand(6, 10);
//            sleep($sleepSeconds);

            // Poziv API-ja
            $service->fetchAndStoreRegions($this->query, $this->max);
        } catch (\Exception $e) {
            Log::error('Error in FetchRegionJob: ' . $e->getMessage());
            // Opcionalno, možete odustati od job-a ili pokušati ponovo
            $this->fail($e);
        }
    }
}
