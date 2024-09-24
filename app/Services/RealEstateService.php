<?php

namespace App\Services;

use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Models\Region;
use Illuminate\Support\Facades\Log;

class RealEstateService
{
    protected $client;
    protected $baseUrl = 'https://suggest.realestate.com.au/consumer-suggest/suggestions';

    public function __construct()
    {
        $this->client = new Client([
                                       'base_uri' => $this->baseUrl,
                                       'timeout'  => 10.0,
                                   ]);
    }

    /**
     * Poziva API sa datim parametrima i procesira odgovor
     */
    public function fetchAndStoreRegions($query, $max = 200)
    {
        try {
            $response = $this->client->request('GET', '', [
                'query' => [
                    'max'   => $max,
                    'type'  => 'suburb,region,precinct',
                    'src'   => 'customer-profile-home-mfe',
                    'query' => $query,
                ],
            ]);

            Log::info('Query: ' . $query);

            if ($response->getStatusCode() == 200) {
                $data = json_decode($response->getBody(), true);

                if (isset($data['_embedded']['suggestions'])) {
                    foreach ($data['_embedded']['suggestions'] as $suggestion) {
                        $source = $suggestion['source'];

                        // check if region already exists in database, if not insert
                        $exists = Region::where('atlasId', $source['atlasId'])->first();

                        if (!$exists) {
                            Region::insertOrIgnore([
                                                       'atlasId'    => $source['atlasId'],
                                                       'type'       => $suggestion['type'],
                                                       'text'       => $suggestion['display']['text'],
                                                       'name'       => $source['name'],
                                                       'state'      => $source['state'],
                                                       'postcode'   => $source['postcode'] ?? null,
                                                       'created_at' => Carbon::now(),
                                                       'updated_at' => Carbon::now(),
                                                   ]);

                            Log::info($suggestion['type'] . ' inserted: ' . $suggestion['display']['text']);
                        } else {
                            Log::info($suggestion['type'] . ' already exists: ' . $suggestion['display']['text']);
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Error fetching regions: ' . $e->getMessage());
        }
    }
}
