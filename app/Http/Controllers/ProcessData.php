<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ProcessData extends Controller
{
    public function process(Request $request)
    {
        // Log the incoming data for debugging
        Log::info('Data from request:', $request->all());

        try {
            // Validate the incoming request data
            $validatedData = $this->validateData($request);

            // Start a database transaction
            DB::beginTransaction();

            // Extract the agency URL fragment from the full URL
            $agencyUrl = $validatedData['agency_url'];
            $urlFragment = $this->extractAgencyUrlFragment($agencyUrl);

            // Check if 'agency_id' is provided
            if (!empty($validatedData['agency_id'])) {
                // Agency exists, retrieve it by ID
                $agency = Agency::find($validatedData['agency_id']);
                Log::info('Existing agency found with ID: ' . $agency->id);
            } else {
                // Parse agency address
                $fullAddress = $validatedData['agency_address'] ?? '';
                $addressParts = explode(',', $fullAddress);

                $address = trim($addressParts[0] ?? '');
                $statePostcode = trim($addressParts[1] ?? '');

                // Assuming state and postcode are separated by space
                if ($statePostcode) {
                    $statePostcodeParts = explode(' ', $statePostcode);
                    $state = trim($statePostcodeParts[0] ?? '');
                    $postcode = trim($statePostcodeParts[1] ?? '');
                } else {
                    $state = null;
                    $postcode = null;
                }

                // Find or create the agency using the extracted URL fragment
                $agency = Agency::firstOrCreate(
                    ['agency_url' => $urlFragment], // Save only the URL fragment
                    [
                        'full_address' => $fullAddress,
                        'address' => $address,
                        'state' => $state,
                        'postcode' => $postcode,
                    ]
                );
                Log::info('Agency created or found: ' . $agency->id);
            }

            // Create the agent
            $agent = Agent::create([
                                       'agent_id' => $validatedData['rea_id'],
                                       'full_name' => $validatedData['candidate_name'],
                                       'first_name' => $validatedData['first_name'],
                                       'last_name' => $validatedData['last_name'],
                                       'mobile' => $validatedData['mobile'] ?? null,
                                       'email' => null, // Email not provided
                                       'position' => $validatedData['position'] ?? null,
                                       'job_title' => $validatedData['job_title'] ?? null,
                                       'median_price_overall' => $validatedData['median_price'] ?? null,
                                       'sales_count_as_lead' => $validatedData['sales_count_as_lead'] ?? null,
                                       'rea_link' => $validatedData['rea_link'],
                                       'agency_id' => $agency->id,
                                   ]);
            Log::info('Agent created successfully with ID: ' . $agent->id);

            // Commit the transaction
            DB::commit();

            // Return success response
            return response()->json(['success' => true, 'message' => 'Agent and agency saved successfully.'], 200);

        } catch (ValidationException $e) {
            // Return validation error response in JSON format
            return response()->json(['success' => false, 'errors' => $e->errors(), 'message' => 'Validation failed'], 422);

        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollback();

            // Log the error for debugging
            Log::error('Error saving agent and agency: ' . $e->getMessage());

            // Return error response
            return response()->json(['success' => false, 'message' => 'Failed to save agent and agency.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Validate the incoming request data.
     *
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    private function validateData(Request $request)
    {
        return $request->validate([
                                      'rea_id' => 'required|unique:agents,agent_id',
                                      'candidate_name' => 'required|string',
                                      'first_name' => 'required|string',
                                      'last_name' => 'required|string',
                                      'mobile' => 'nullable|string|max:20',
                                      'position' => 'nullable|string',
                                      'job_title' => 'nullable|string',
                                      'median_price' => 'nullable|string',
                                      'sales_count_as_lead' => 'nullable|string',
                                      'rea_link' => 'required|string',
                                      'agency_url' => 'required|string', // Full URL provided here
                                      'agency_address' => 'nullable|string',
                                      'agency_url_fragment' => 'nullable|string',
                                      'agency_id' => 'nullable|integer|exists:agencies,id',
                                  ]);
    }

    /**
     * Extracts the agency URL fragment from the full URL.
     */
    private function extractAgencyUrlFragment($agencyUrl)
    {
        // Use regex to extract the part after /agency/
        if (preg_match('/agency\/([^\/]+)/', $agencyUrl, $matches)) {
            return $matches[1]; // The part after /agency/
        }
        return null;
    }
}
