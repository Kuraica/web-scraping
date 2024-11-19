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

            // Extract agency data
            $agencyUrl = $validatedData['agency_url'];
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
                Log::warning("Cannot parse address for agency with URL: {$agencyUrl}. Full Address: {$fullAddress}");
            }

            if (empty($validatedData['agency_name'])) {
                // Parse agency name from agency_url
                $agencyName = $this->extractAgencyNameFromUrl($agencyUrl);
            } else {
                $agencyName = $validatedData['agency_name'];
            }


            // Agency data
            $agencyData = [
                'agency_url'        => $agencyUrl,
                'full_address'      => $fullAddress,
                'address'           => $address,
                'state'             => $state,
                'postcode'          => $postcode,
                'agency_name'       => $agencyName,
                'number_of_people'  => $validatedData['number_of_people'] ?? null,
                'properties_sold'   => $validatedData['properties_sold'] ?? null,
                'properties_leased' => $validatedData['properties_leased'] ?? null,
            ];

            // Check if 'agency_id' is provided
            if (!empty($validatedData['agency_id'])) {
                // Agency exists, retrieve it by ID and update it
                $agency = Agency::find($validatedData['agency_id']);
                $agency->update($agencyData);
                Log::info('Existing agency updated with ID: ' . $agency->id);
            } else {
                // Find or create the agency using the agency_url
                $agency = Agency::updateOrCreate(
                    ['agency_url' => $agencyUrl], // Unique identifier
                    $agencyData
                );
                Log::info('Agency created or found with ID: ' . $agency->id);
            }

            // Prepare agent data
            $agentData = [
                'agent_id'                 => $validatedData['rea_id'],
                'full_name'                => $validatedData['candidate_name'],
                'first_name'               => $validatedData['first_name'] ?? 'Not provided',
                'last_name'                => $validatedData['last_name'] ?? 'Not provided',
                'mobile'                   => $validatedData['mobile'] ?? null,
                'email'                    => $validatedData['email'] ?? null,
                'position'                 => $validatedData['position'] ?? null,
                'job_title'                => $validatedData['job_title'] ?? null,
                'years_experience'         => $validatedData['years_experience'] ?? null,
                'median_price_overall'     => $validatedData['median_price'] ?? null,
                'sales_count_as_lead'      => $validatedData['sales_count_as_lead'] ?? null,
                'secondary_sales'          => $validatedData['secondary_sales'] ?? null,
                'number_of_5_star_reviews' => $validatedData['number_of_5_star_reviews'] ?? null,
                'oldest_transaction_date'  => $validatedData['oldest_transaction_date'] ?? null,
                'latest_transaction_date'  => $validatedData['latest_transaction_date'] ?? null,
                'top_suburb_sales'         => $validatedData['top_suburb_sales'] ?? null,
                'rea_link'                 => $validatedData['rea_link'],
                'agency_id'                => $agency->id,
            ];

            // Create the agent
            $agent = Agent::create($agentData);
            Log::info('Agent created successfully with ID: ' . $agent->id);

            // Commit the transaction
            DB::commit();

            // Return success response
            return response()->json(['success' => true, 'message' => 'Agent and agency saved successfully.'], 200);

        } catch (ValidationException $e) {
            // Rollback the transaction on validation error
            DB::rollBack();
            // Return validation error response in JSON format
            return response()->json(['success' => false, 'errors' => $e->errors(), 'message' => 'Validation failed'], 422);

        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();

            // Log the error for debugging
            Log::error('Error saving agent and agency: ' . $e->getMessage());

            // Return error response
            return response()->json(['success' => false, 'message' => 'Failed to save agent and agency.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Extracts the agency name from the agency URL.
     */
    private function extractAgencyNameFromUrl($agencyUrl)
    {
        // Match everything after "agency/" up to the last "-"
        $pattern = '/agency\/(.+?)-[^-]+$/';

        if (preg_match($pattern, $agencyUrl, $matches)) {
            // Replace remaining "-" characters with spaces
            $agencyName = str_replace('-', ' ', $matches[1]);

            // Capitalize each word
            $agencyName = ucwords(strtolower($agencyName));

            return $agencyName;
        }

        return null;
    }

    /**
     * Validate the incoming request data.
     *
     * @param Request $request
     *
     * @return array
     * @throws ValidationException
     */
    private function validateData(Request $request)
    {
        return $request->validate([
            'rea_id'                   => 'required|unique:agents,agent_id',
            'candidate_name'           => 'required|string',
            'first_name'               => 'nullable|string',
            'last_name'                => 'nullable|string',
            'mobile'                   => 'nullable|string|max:100',
            'email'                    => 'nullable|string|email',
            'position'                 => 'nullable|string',
            'job_title'                => 'nullable|string',
            'years_experience'         => 'nullable|string',
            'median_price'             => 'nullable|string',
            'sales_count_as_lead'      => 'nullable|integer',
            'secondary_sales'          => 'nullable|integer',
            'number_of_5_star_reviews' => 'nullable|integer',
            'oldest_transaction_date'  => 'nullable|date',
            'latest_transaction_date'  => 'nullable|date',
            'top_suburb_sales'         => 'nullable|string',
            'rea_link'                 => 'required|string',
            'agency_name'              => 'nullable|string',
            'agency_url'               => 'required|string', // Full URL provided here
            'agency_address'           => 'nullable|string',
            'agency_id'                => 'nullable|integer|exists:agencies,id',
            'number_of_people'         => 'nullable|integer',
            'properties_sold'          => 'nullable|integer',
            'properties_leased'        => 'nullable|integer',
        ]);
    }
}