<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Repositories\RegionRepository;
use App\Services\RealEstateService;
use Illuminate\Http\Request;

class RegionsController extends Controller
{

    public function __construct(
        public readonly RealEstateService $service,
        public readonly RegionRepository  $repository,
        public readonly Region            $region
    )
    {
    }

    public function check($query)
    {
        $this->service->fetchAndStoreRegions($query);
    }

    public function getNextRegion()
    {
        $region = $this->repository->getFirstUnscrapedRegion();

        if ($region) {
            return response()->json([
                                        'success' => true,
                                        'data'    => $region,
                                        'url'     => $this->region->formatRegionName($region->text),
                                    ]);
        } else {
            return response()->json([
                                        'success' => false,
                                        'message' => 'No region found',
                                    ]);
        }
    }

    public function updateProcessedRegion(Region $region)
    {
        dd($region);
    }
}
