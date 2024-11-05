<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agency extends Model
{
    use HasFactory;

    protected $fillable = [
        'agency_name',
        'agency_url',
        'full_address',
        'address',
        'state',
        'postcode',
        'number_of_people',
        'properties_sold',
        'properties_leased',
    ];

    public function agents(): HasMany
    {
        return $this->hasMany(Agent::class);
    }
}
