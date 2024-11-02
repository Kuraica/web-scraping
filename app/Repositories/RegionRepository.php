<?php

namespace App\Repositories;

use App\Models\Region;

class RegionRepository
{
    /**
     * Get first region where type = 'region' i scraped = 0, sorted by ID-u in ascending order
     *
     * @return Region|null
     */
    public function getFirstUnscrapedRegion()
    {
        return Region::where('type', 'region')
            ->where('scraped', 0)
            ->orderBy('id', 'asc')
            ->first();
    }

    /**
     * Get last region where type = 'region' i scraped = 0, sorted by ID-u in desc order
     *
     * @return Region|null
     */
    public function getLastUnscrapedRegion()
    {
        return Region::where('type', 'region')
            ->where('scraped', 0)
            ->orderBy('id', 'desc')
            ->first();
    }

    /**
     * Get first suburb were type = 'suburb' i scraped = 0, sorted by ID-u u ascending order
     *
     * @return Region|null
     */
    public function getFirstUnscrapedSuburb()
    {
        return Region::where('type', 'suburb')
            ->where('scraped', 0)
            ->orderBy('id', 'asc')
            ->first();
    }
}
