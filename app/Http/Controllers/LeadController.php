<?php

namespace App\Http\Controllers;

use App\Events\LeadCaptured;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Add this line
// Removed: use Revolution\Google\Sheets\Facades\Sheets; // Removed Google Sheets Facade

class LeadController extends Controller
{
    /**
     * Store a newly created lead in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:leads,email', // Added unique validation rule
            'job_title' => 'nullable|string|max:255',
        ]);

        try {
            // Check if a lead with this email already exists and create if not
            $lead = Lead::firstOrCreate(
                ['email' => $validated['email']],
                [
                    'name' => $validated['name'],
                    'job_title' => $validated['job_title'] ?? '',
                ]
            );

            // Dispatch the event after the lead is saved to the database
            if ($lead->wasRecentlyCreated) {
                event(new LeadCaptured($lead));
                Log::info("LeadCaptured event dispatched for new email: {$lead->email}"); // Updated to use imported Log
            } else {
                Log::info("Lead with email {$lead->email} already exists. No new event dispatched."); // Updated to use imported Log
            }

            // Removed all Google Sheets API integration code
            // The functionality to append to Google Sheets has been removed.

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            // Log any errors that occur during the lead saving or event dispatching
            Log::error('Lead storage or event dispatch error: ' . $e->getMessage());
            
            // Return an error response to the frontend
            return response()->json(['error' => "Something went wrong while processing your lead. Please try again."], 500);
        }
    }
}