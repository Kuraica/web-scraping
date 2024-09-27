<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    // Nije potrebno navoditi 'id' jer je auto-increment
    protected $fillable = [
        'atlasId',
        'type',
        'text',
        'name',
        'state',
        'postcode',
        'scraped',
    ];

    // Ako koristite UUID za atlasId, ali primarni ključ je auto-increment
    public $incrementing = true;
    protected $keyType = 'int'; // Po defaultu, ovo je 'int', može ostati

    public function formatRegionName($name): string
    {
        $name = strtolower($name);
        $name = str_replace(', ', '-', $name);
        $name = str_replace(' ', '-', $name);

        return $name;
    }
}
