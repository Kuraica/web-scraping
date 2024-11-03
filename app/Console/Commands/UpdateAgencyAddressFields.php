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
    protected $description = 'Update address, state, and postcode fields based on full_address in agencies table';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Regex pattern to parse address, state, and postcode from full_address
        $pattern = '/^(.*?),\s*([A-Z]{2,3})\s*(\d{4})$/';

        // Fetch all agencies
        $agencies = Agency::all();

        foreach ($agencies as $agency) {
            $fullAddress = $agency->full_address;
            $address = null;
            $state = null;
            $postcode = null;

            if (preg_match($pattern, $fullAddress, $matches)) {
                $address = trim($matches[1]);
                $state = $matches[2];
                $postcode = $matches[3];
            } else {
                $this->error("Cannot parse address for agency ID {$agency->id}: {$fullAddress}");
                continue;
            }

            // Update agency fields if matched
            $agency->update([
                'address' => $address,
                'state' => $state,
                'postcode' => $postcode,
            ]);

            $this->info("Updated agency ID {$agency->id}: Address: $address, State: $state, Postcode: $postcode");
        }

        $this->info('All agencies have been updated.');

        return Command::SUCCESS;
    }
}