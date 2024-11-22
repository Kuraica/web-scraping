<?php

namespace App\Repositories;

use App\Models\Agency;

class AgencyRepository
{
    public function getRandUnscrapedUnprocessedAgencyHightPririty()
    {
        return Agency::where('scraped', 0)
            ->whereIn('state', ['VIC', 'QLD', 'NSW'])
            ->whereNull('processed_by')
            ->inRandomOrder()
            ->first();
    }

    public function getRandUnscrapedUnprocessedAgency()
    {
        return Agency::where('scraped', 0)
            ->whereNull('processed_by')
            ->inRandomOrder()
            ->first();
    }
}