<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Agency;

class UpdateAgencyAddressFields extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agencies:update-address-fields';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update address, state, postcode, and agency_name fields based on full_address and agency_url in agencies table';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Regex pattern to parse address, state, and postcode from full_address
        $addressPattern = '/^(.*?),\s*([^,]+?),\s*([A-Z]{2,3})\s*(\d{4})$/';

        // Fetch all agencies
        $agencies = Agency::all();

        foreach ($agencies as $agency) {
            // Parse full_address
            $fullAddress = $agency->full_address;
            $address = null;
            $state = null;
            $postcode = null;

            if (preg_match($addressPattern, $fullAddress, $matches)) {
                $address = trim($matches[2]);
                $state = $matches[3];
                $postcode = $matches[4];
            } else {
                $this->error("Cannot parse address for agency ID {$agency->id}: {$fullAddress}");
                continue;
            }

            // Parse agency_name from agency_url
            $agencyUrl = $agency->agency_url;
            $agencyName = $this->extractAgencyNameFromUrl($agencyUrl);

            // Update agency fields if matched
            $agency->update([
                'address' => $address,
                'state' => $state,
                'postcode' => $postcode,
                'agency_name' => $agencyName,
            ]);

            $this->info("Updated agency ID {$agency->id}: Address: $address, State: $state, Postcode: $postcode, Agency Name: $agencyName");
        }

        $this->info('All agencies have been updated.');

        return Command::SUCCESS;
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
}