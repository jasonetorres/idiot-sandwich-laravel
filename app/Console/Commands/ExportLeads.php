<?php

namespace App\Console\Commands;

use App\Models\Lead;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ExportLeads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:leads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export all leads to a CSV file.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $leads = Lead::all();
        $filename = 'leads_' . now()->format('Ymd_His') . '.csv';
        $path = 'exports/' . $filename;

        // Ensure the 'exports' directory exists in storage
        if (!Storage::exists('exports')) {
            Storage::makeDirectory('exports');
        }

        $handle = fopen(Storage::path($path), 'w');
        // Add CSV header
        fputcsv($handle, ['Name', 'Email', 'Job Title', 'Created At']);

        // Add lead data rows
        foreach ($leads as $lead) {
            fputcsv($handle, [$lead->name, $lead->email, $lead->job_title, $lead->created_at]);
        }

        fclose($handle);

        $this->info("Leads exported successfully to: " . Storage::url($path));
        $this->info("You can find the file in your storage/app/exports directory.");
    }
}