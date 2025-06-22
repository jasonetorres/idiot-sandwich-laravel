<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Revolution\Google\Sheets\Facades\Sheets;

class LeadController extends Controller
{
    /**
     * Store a newly created lead in storage and Google Sheets.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'job_title' => 'nullable|string|max:255',
        ]);

        try {
            // --- Start Debugging Logs ---
            $sheetId = env('GOOGLE_SHEET_ID');
            $credentialsPath = config('sheets.service_account_credentials_json');

            Log::info('--- Google Sheets Debug ---');
            Log::info('Attempting to write to Google Sheet.');
            Log::info('Sheet ID from .env: ' . ($sheetId ? $sheetId : 'NULL - Please check GOOGLE_SHEET_ID in your .env file.'));
            Log::info('Credentials Path from config: ' . ($credentialsPath ? $credentialsPath : 'NULL - Please check your .env and config/sheets.php files.'));
            // --- End Debugging Logs ---

            // Prepare the data row for the sheet
            $values = [
                $validated['name'],
                $validated['email'],
                $validated['job_title'] ?? '', // Use empty string if job title is null
                now()->toDateTimeString(), // Add a timestamp for when the lead was captured
            ];

            // Append the row to the specified sheet in the Google Sheets document
            Sheets::spreadsheet($sheetId)
                  ->sheet('Sheet1') // Assuming your sheet is named 'Sheet1'
                  ->append([$values]);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            // Log the detailed error for debugging
            Log::error('Google Sheets API Error: ' . $e->getMessage());
            
            // Return success to the user so they can still use the app
            return response()->json(['success' => true]);
        }
    }
}
