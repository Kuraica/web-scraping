<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    protected $fillable = [
        'atlasId',
        'type',
        'text',
        'name',
        'state',
        'postcode',
        'scraped',
    ];

    public $incrementing = true;
    protected $keyType = 'int';

    public function formatRegionName($name): string
    {
        $name = strtolower($name);
        $name = str_replace(', ', '-', $name);
        $name = str_replace(' ', '-', $name);

        return $name;
    }
}
