<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcessedUrl extends Model
{
    use HasFactory;

    protected $fillable = [
        'order',
        'region_id',
        'url',
        'page'
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }
}
