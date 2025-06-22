<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LeadController extends Controller
{
    /**
     * Store a newly created lead in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        try {
            // Use a simple DB insert for this use case.
            // For a larger app, you'd use a Lead model: Lead::create($validated);
            DB::table('leads')->insert([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['success' => true, 'message' => 'Lead captured successfully!']);

        } catch (\Exception $e) {
            // Log the error for debugging, but return a generic success message to the user
            // to avoid indicating if an email already exists.
            Log::error('Lead capture failed: ' . $e->getMessage());
            // We can return success even if it fails to avoid letting a user know an email exists.
            // Or handle it differently based on requirements.
            return response()->json(['success' => true, 'message' => 'Thank you!']);
        }
    }
}
