<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agency;
use Illuminate\Support\Facades\Log;

class AgencyController extends Controller
{
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
     * Helper function to extract the agency URL fragment from the full URL.
     */
    private function extractAgencyUrlFragment($fullAgencyUrl)
    {
        // Use regex to extract the part after /agency/
        if (preg_match('/agency\/([^\/]+)/', $fullAgencyUrl, $matches)) {
            return $matches[1];  // Return the fragment after /agency/
        }

        // If the fragment can't be extracted, return the full URL (or handle it as needed)
        return $fullAgencyUrl;
    }
}
