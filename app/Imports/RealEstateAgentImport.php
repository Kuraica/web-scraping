<?php

namespace App\Imports;

use App\Models\PreviouslyProcessedAgent;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithUpserts;

class RealEstateAgentImport implements ToModel, WithHeadingRow, WithChunkReading, WithUpserts, WithProgressBar
{
    use Importable;

    /**
     * Obrada svakog reda iz Excel fajla.
     */
    public function model(array $row): PreviouslyProcessedAgent
    {

        return new PreviouslyProcessedAgent([
                                                'rea_id'         => $row['rea_id'],
                                                'candidate_name' => $row['candidate_name'] ?? 'Unknown',
                                                'first_name'     => $row['first_name'] ?? 'Unknown',
                                                'last_name'      => $row['last_name'] ?? 'Unknown',
                                                'agency'         => $row['agency'] ?? 'Unknown',
                                                'agency_suburb'  => $row['agency_suburb'] ?? 'Unknown',
                                                'state'          => $row['state'] ?? 'Unknown',
                                                'rea_link'       => $row['rea_link'],
                                                'mobile'       => $row['mobile'] ?? 'Unknown',
                                            ]);
    }

    /**
     * Broj redova za obradu po chunku.
     */
    public function chunkSize(): int
    {
        return 100;
    }

    /**
     * @return string|array
     */
    public function uniqueBy()
    {
        return 'rea_id';
    }
}
