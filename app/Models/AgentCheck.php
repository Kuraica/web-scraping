<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentCheck extends Model
{
    use HasFactory;

    protected $table = 'agentCheck';

    protected $fillable = [
        'agent_id',
        'agent_url',
    ];
}