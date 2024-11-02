<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Repositories\RegionRepository;
use App\Services\RealEstateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

    public function getRegion(string $order): JsonResponse
    {
        $return = $this->getNextRegion();

        if ($order === 'desc') {
            $return = $this->getLastRegion();
        }

        return $return;
    }

    public function getNextRegion(): JsonResponse
    {
        $region = $this->repository->getFirstUnscrapedRegion();
        Log::info('Get next region: ', [$region]);
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

    public function getLastRegion(): JsonResponse
    {
        $region = $this->repository->getLastUnscrapedRegion();
        Log::info('Get last region: ', [$region]);
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

    public function updateProcessedRegion(Region $region): JsonResponse
    {
        try {
            $region->scraped = true;
            $region->save();
            Log::info('Update processed region: ', [$region]);
            return response()->json(['success' => true, 'message' => 'Successfully processed region']);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 404);
        }
    }
}
