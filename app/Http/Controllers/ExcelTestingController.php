<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Row;

class ExcelTestingController extends Controller implements OnEachRow, WithChunkReading
{
    private $collectedRows = [];
    private $maxRows = 100; // Maksimalan broj redova koji želite dohvatiti

    /**
     * Testiranje redova iz Excel fajla.
     */
    public function testRows()
    {
        dd('test');
        ini_set('max_execution_time', 300); // 5 minuta
        ini_set('memory_limit', '512M');   // 512 MB

        $filePath = storage_path('app/public/previous_data.xlsx');

        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        // Procesuiranje fajla u chunkovima
        Excel::import($this, $filePath);

        return response()->json($this->collectedRows);
    }

    /**
     * Obrada svakog reda.
     */
    public function onRow(Row $row)
    {
        if (count($this->collectedRows) >= $this->maxRows) {
            return; // Prestanite sakupljati kada dostignete maksimum
        }

        $this->collectedRows[] = $row->toArray();
    }

    /**
     * Broj redova za obradu po chunku.
     */
    public function chunkSize(): int
    {
        return 50; // Učitaj samo 50 redova po chunku
    }
}