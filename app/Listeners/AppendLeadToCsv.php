<?php

namespace App\Listeners;

use App\Events\LeadCaptured;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log; // Ensure this is present
use Illuminate\Support\Facades\File; // Add this line - we'll use it if Storage fails to create directory

class AppendLeadToCsv implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LeadCaptured $event): void
    {
        Log::info("AppendLeadToCsv listener received event for email: {$event->lead->email}");

        $lead = $event->lead;
        $filename = 'all_leads.csv';
        // Use base_path() to get the absolute path from the project root
        $directory = base_path('storage/app/exports');
        $filePath = $directory . '/' . $filename;

        try {
            // Ensure the directory exists using raw PHP/Laravel File facade
            if (!File::isDirectory($directory)) {
                File::makeDirectory($directory, 0775, true); // Create recursively with 0775 permissions
                Log::info("Created directory: {$directory}");
            }

            $fileExists = file_exists($filePath);
            $handle = fopen($filePath, 'a'); // Open in append mode

            if (!$handle) {
                throw new \Exception("Could not open CSV file for writing: {$filePath}");
            }

            if (!$fileExists) {
                // Write CSV header if the file is new
                fputcsv($handle, ['Name', 'Email', 'Job Title', 'Created At']);
                Log::info("CSV header written to: {$filePath}");
            }

            // Append the new lead's data
            fputcsv($handle, [
                $lead->name,
                $lead->email,
                $lead->job_title,
                $lead->created_at->toDateTimeString()
            ]);

            fclose($handle);
            Log::info("Lead '{$lead->email}' appended to CSV file directly: {$filePath}");

        } catch (\Exception $e) {
            Log::error("Failed to append lead to CSV file directly: {$e->getMessage()} at file {$e->getFile()} line {$e->getLine()}");
        }
    }
}