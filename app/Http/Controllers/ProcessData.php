<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProcessData extends Controller
{
    public function process(Request $request)
    {
        Log::info('Podaci iz requesta:', $request->all());
    }
}
