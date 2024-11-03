<?php

namespace App\Exports;

use App\Models\Agent;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class AgentsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Agent::select(
            'agents.agent_id',
            'agents.full_name as candidate_name',
            'agents.first_name',
            'agents.last_name',
            'agents.email',
            'agencies.agency_url as agency',
            'agents.job_title',
            'agents.years_experience',
            'agencies.full_address as agency_address',
            'agencies.address as agency_suburb',
            'agencies.state',
            'agencies.postcode',
            'agents.median_price_overall',
            'agents.sales_count_as_lead',
            'agents.secondary_sales',
            'agents.top_suburb_sales as top_suburb_reviews',
            'agents.number_of_5_star_reviews',
            'agents.oldest_transaction_date',
            'agents.latest_transaction_date',
            'agents.top_suburb_sales',
            'agencies.number_of_people',
            'agencies.properties_sold',
            'agencies.properties_leased',
            'agents.rea_link'
        )
            ->join('agencies', 'agents.agency_id', '=', 'agencies.id')
            ->get();
    }

    /**
     * Export column headers
     */
    public function headings(): array
    {
        return [
            'REA ID',
            'Candidate Name',
            'First Name',
            'Last Name',
            'Email',
            'Agency',
            'Job Title',
            'Years Experience',
            'Agency address',
            'Agency suburb',
            'State',
            'Postcode',
            'Median Sold Price Overall',
            'Sales Count As Lead',
            'Secondary sales',
            'Top suburb reviews',
            'Number of 5 Star Reviews',
            'Oldest transaction date',
            'Latest transaction date',
            'Top suburb sales',
            'Number of people',
            'Properties sold',
            'Properties leased',
            'REA Link'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // First column bold
            1 => ['font' => ['bold' => true]]
        ];
    }
}
