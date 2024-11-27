<?php

namespace App\Repositories;

use App\Models\Agency;

class AgencyRepository
{
    public function returnSpecific($id)
    {
        return Agency::where('scraped', 0)
            ->where('id', $id)
            ->first();
    }

    public function getRandUnscrapedUnprocessedAgencyHightPririty()
    {
        return Agency::where('scraped', 0)
            ->whereIn('state', ['VIC', 'QLD', 'NSW'])
            ->whereNull('processed_by')
            ->where('number_of_people', '>', 1)
//            ->whereIn('id', [1171, 1788, 1903, 3617, 5447, 6201, 6361, 8222])
            ->inRandomOrder()
            ->first();
    }

    public function getRandUnscrapedUnprocessedAgency()
    {
        return Agency::where('scraped', 0)
            ->whereNull('processed_by')
            ->where('number_of_people', '>', 1)
            ->inRandomOrder()
            ->first();
    }
}