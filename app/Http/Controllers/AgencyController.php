<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agency;

class AgencyController extends Controller
{
    public function checkAgency(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
                                                'agency_url' => 'required|string',
                                            ]);

        // Get the full agency URL from the request
        $fullAgencyUrl = $validatedData['agency_url'];

        // Extract the fragment from the full URL
        $agencyUrlFragment = $this->extractAgencyUrlFragment($fullAgencyUrl);

        // Check if the agency exists based on the extracted fragment
        $agency = Agency::where('agency_url', $agencyUrlFragment)->first();

        if ($agency) {
            // Agency exists, return data
            return response()->json([
                                        'exists' => true,
                                        'data' => [
                                            'agency_id' => $agency->id,
                                            'agency_address' => $agency->full_address,
                                            'agency_url_fragment' => $agency->agency_url,
                                        ],
                                    ], 200);
        } else {
            // Agency does not exist
            return response()->json([
                                        'exists' => false,
                                    ], 200);
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
