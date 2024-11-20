<?php

namespace App\Imports;

use App\Models\PreviouslyProcessedAgent;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Row;

class RealEstateAgentImport implements OnEachRow, WithChunkReading, WithMultipleSheets
{
    /**
     * Obrada svakog reda iz Excel fajla.
     */
    public function onRow(Row $row)
    {
        $data = $row->toArray();

        // Čuvanje podataka u tabelu
        PreviouslyProcessedAgent::updateOrCreate(
            ['rea_id' => $data['REA ID']],
            [
                'candidate_name' => $data['Candidate Name'],
                'first_name' => $data['First Name'],
                'last_name' => $data['Last Name'],
                'mobile' => $data['Mobile'] ?? null,
                'agency' => $data['Agency'] ?? null,
                'rea_link' => $data['REA Link'] ?? null,
                'agency_suburb' => $data['Agency Suburb'] ?? null,
                'state' => $data['State'] ?? null,
            ]
        );
    }

    /**
     * Broj redova za obradu po chunku.
     */
    public function chunkSize(): int
    {
        return 1000;
    }

    /**
     * Sheetovi za obradu.
     */
    public function sheets(): array
    {
        return [
            'ACT' => $this,
            'NSW' => $this,
            'QLD' => $this,
            'SA'  => $this,
            'VIC' => $this,
            'WA'  => $this,
        ];
    }
}