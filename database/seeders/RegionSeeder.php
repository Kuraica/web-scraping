<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regionsJson = File::get(storage_path('app/regions.json'));
        $regions = json_decode($regionsJson, true);

        foreach ($regions as $region) {
            try {
                DB::table('regions_backup')->insert($region);
            } catch (\Exception $e) {
                dd($e->getMessage()); // Prikaz poruke greške
            }
        }
    }
}
