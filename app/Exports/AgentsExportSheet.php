<?php

namespace App\Exports;

use App\Models\Agent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AgentsExportSheet implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithStyles, WithTitle
{
    protected $state;

    public function __construct($state)
    {
        $this->state = $state;
    }

    /**
     * Query to fetch data from the database.
     */
    public function query(): Builder
    {
        return Agent::select(
            'agents.agent_id',
            'agents.full_name',
            'agents.first_name',
            'agents.last_name',
            'agents.mobile',
            'agencies.agency_name',
            'agencies.agency_website',
            'agents.job_title',
            'agents.years_experience',
            'agencies.full_address',
            'agencies.address',
            'agencies.state',
            'agencies.postcode',
            'agents.median_price_overall',
            'agents.sales_count_as_lead',
            'agents.secondary_sales',
            'agents.top_suburb_sales',
            'agents.number_of_5_star_reviews',
            'agents.oldest_transaction_date',
            'agents.latest_transaction_date',
            'agencies.number_of_people',
            'agencies.properties_sold',
            'agencies.properties_leased',
            'agents.rea_link'
        )
            ->join('agencies', 'agents.agency_id', '=', 'agencies.id')
            ->where('agencies.state', $this->state)
            ->orderBy('agencies.agency_name', 'asc')
            ->orderBy('agents.first_name', 'asc');
    }

    /**
     * Map each row to a formatted array for the Excel sheet.
     */
    public function map($row): array
    {
        // Generate the email address
        $email = '';
        if (!empty($row->agency_website)) {
            $domain = str_replace(['www.', '/'], '', parse_url($row->agency_website, PHP_URL_HOST));
            $email = strtolower(str_replace(' ', '.', $row->first_name)) . '.' . strtolower(str_replace(' ', '.', $row->last_name)) . '@' . $domain;
        }

        // Convert median price overall to a numeric value
        $medianPrice = $this->convertMedianPrice($row->median_price_overall);

        return [
            $row->agent_id,
            $row->full_name,
            $row->first_name,
            $row->last_name,
            $row->mobile,
            $email,
            $row->agency_name,
            $row->agency_website,
            $row->job_title,
            $row->years_experience,
            $row->full_address,
            $row->address,
            $row->state,
            $row->postcode,
            $medianPrice,
            $row->sales_count_as_lead,
            $row->secondary_sales,
            $row->top_suburb_sales,
            $row->number_of_5_star_reviews,
            $row->oldest_transaction_date,
            $row->latest_transaction_date,
            $row->top_suburb_sales,
            $row->number_of_people,
            $row->properties_sold,
            $row->properties_leased,
            $row->rea_link,
        ];
    }

    /**
     * Convert median price overall to a numeric value.
     */
    private function convertMedianPrice(?string $price): ?int
    {
        if (empty($price)) {
            return null;
        }

        // Remove dollar sign and whitespace
        $price = str_replace(['$', ' '], '', $price);

        // Handle 'k' for thousands and 'M' for millions
        if (strpos($price, 'k') !== false) {
            return (int)(floatval(str_replace('k', '', $price)) * 1000);
        }

        if (strpos($price, 'M') !== false) {
            return (int)(floatval(str_replace('M', '', $price)) * 1000000);
        }

        // If no suffix, return as-is
        return (int)floatval($price);
    }

    /**
     * Export column headers.
     */
    public function headings(): array
    {
        return [
            'REA ID',
            'Candidate Name',
            'First Name',
            'Last Name',
            'Mobile',
            'Email',
            'Agency',
            'Agency website',
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
            'REA Link',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]]
        ];
    }

    /**
     * Set the sheet title based on the state.
     */
    public function title(): string
    {
        return $this->state;
    }
}