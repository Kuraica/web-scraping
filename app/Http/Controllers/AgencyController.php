<?php

namespace App\Http\Controllers;

use App\Models\Email;
use App\Repositories\AgencyRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Agency;
use Illuminate\Support\Facades\Log;

class AgencyController extends Controller
{
    public function __construct(
        public readonly AgencyRepository $repository,
        public readonly Agency $agency
    )
    {
    }

    public function checkAgency(Request $request)
    {
        try {
            // Validate the incoming request
            $validatedData = $request->validate([
                'agency_url' => 'required|string',
            ]);

            Log::info('checkAgency validated data: ', [$validatedData]);

            // Get the full agency URL from the request
            $fullAgencyUrl = $validatedData['agency_url'];

            // **Ensure the agency_url is compared correctly**
            $agency = Agency::where('agency_url', $fullAgencyUrl)->first();

            if ($agency) {
                Log::info('Found agency: ', [$agency]);
                // Agency exists, return data
                return response()->json([
                    'success' => true,
                    'exists'  => true,
                    'data'    => [
                        'agency_id'         => $agency->id,
                        'agency_name'       => $agency->agency_name,
                        'agency_address'    => $agency->full_address,
                        'number_of_people'  => $agency->number_of_people,
                        'properties_sold'   => $agency->properties_sold,
                        'properties_leased' => $agency->properties_leased,
                    ],
                ], 200);
            } else {
                Log::info('Agency does not exist for: ', [$fullAgencyUrl]);
                // Agency does not exist
                return response()->json([
                    'success' => true,
                    'exists'  => false,
                ], 200);
            }
        } catch (\Exception $e) {
            Log::error('Error in checkAgency: ', [$e->getMessage()]);
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Vrati listu agencija koje treba ažurirati
     */
    public function getAgenciesToUpdate()
    {
        // Dobij do 100 agencija koje treba ažurirati
        $agencies = Agency::select('id', 'agency_url')
//            ->whereBetween('id', [401, 800])
            ->whereNull('address')
//            ->limit(100)
->get();

        return response()->json([
            'success'  => true,
            'agencies' => $agencies
        ]);
    }

    public function getAgency(Request $request)
    {
        return view('get-agency');
    }

    /**
     * Dohvati prvu agenciju za proveru
     */
    public function getNextAgency(): JsonResponse
    {
        $agency = $this->repository->getRandUnscrapedUnprocessedAgencyHightPririty() ?? $this->repository->getRandUnscrapedUnprocessedAgency();
        Log::info('Get next agency: ', [$agency]);
        if ($agency) {
            return response()->json([
                'success' => true,
                'agency'  => $agency,
                'url'     => $agency->agency_url,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No agency found',
            ]);
        }
    }

    /**
     * Ažuriraj podatke o agenciji
     */
    public function updateAgencyData(Request $request)
    {
        $request->dd();
        Log::info('updateAgencyData start process:', []);
        $validatedData = $request->validate([
            'agency_id'         => 'required|integer|exists:agencies,id',
            'agency_url'        => 'required|url',
            'agency_name'       => 'nullable|string|max:255',
            'agency_website'    => 'nullable|string|max:255',
            'agency_address'    => 'nullable|string|max:255',
            'number_of_people'  => 'nullable|integer',
            'properties_sold'   => 'nullable|integer',
            'properties_leased' => 'nullable|integer',
        ]);
        Log::info('updateAgencyData update data:', $validatedData);

        $fullAddress = $validatedData['agency_address'] ?? '';

        // Updated regex pattern to handle complex addresses
        $pattern = '/^(.*?),\s*([^,]+?),\s*([A-Z]{2,3})\s*(\d{4})$/';

        $address = null;
        $state = null;
        $postcode = null;

        if (preg_match($pattern, $fullAddress, $matches)) {
            $address = trim($matches[2]);
            $state = $matches[3];
            $postcode = $matches[4];
        } else {
            Log::warning("Cannot parse address for agency with URL: {$validatedData['agency_url']}. Full Address: {$fullAddress}");
        }

        $agency = Agency::find($validatedData['agency_id']);
        $agency->agency_url = $validatedData['agency_url'];
        $agency->agency_name = $validatedData['agency_name'];
        $agency->agency_website = $validatedData['agency_website'];
        $agency->address = $address;
        $agency->state = $state;
        $agency->postcode = $postcode;
        $agency->full_address = $validatedData['agency_address'];
        $agency->number_of_people = $validatedData['number_of_people'] ?? null;
        $agency->properties_sold = $validatedData['properties_sold'] ?? null;
        $agency->properties_leased = $validatedData['properties_leased'] ?? null;
        $agency->save();

        return response()->json([
            'success' => true
        ]);
    }
}
