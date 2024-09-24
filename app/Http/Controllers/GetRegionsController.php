<?php

namespace App\Http\Controllers;

use App\Services\RealEstateService;
use Illuminate\Http\Request;

class GetRegionsController extends Controller
{

    public function __construct(
        public RealEstateService $service
    )
    {
    }
    public function check($query)
    {
        $this->service->fetchAndStoreRegions($query);
    }
}
