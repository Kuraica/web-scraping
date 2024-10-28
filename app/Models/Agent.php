<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'full_name',
        'first_name',
        'last_name',
        'mobile',
        'email',
        'position',
        'job_title',
        'years_experience',
        'median_price_overall',
        'sales_count_as_lead',
        'secondary_sales',
        'number_of_5_star_reviews',
        'oldest_transaction_date',
        'latest_transaction_date',
        'top_suburb_sales',
        'rea_link',
        'agency_id'
    ];

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class);
    }
}
