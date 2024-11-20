<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreviouslyProcessedAgent extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'previously_processed_agents';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rea_id',
        'candidate_name',
        'first_name',
        'last_name',
        'mobile',
        'agency',
        'agency_suburb',
        'state',
        'rea_link',
    ];
}