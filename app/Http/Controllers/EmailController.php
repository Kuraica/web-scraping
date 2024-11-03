<?php

namespace App\Http\Controllers;

use App\Models\Email;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function updateEmail(Request $request)
    {
        // Direct validation with request->validate()
        $request->validate([
            'email' => 'required|email',
        ]);

        // Upsert email into the email table
        Email::updateOrCreate([], ['email' => $request->email]);

        return response()->json(['success' => true, 'message' => 'Email updated successfully.']);
    }
}
