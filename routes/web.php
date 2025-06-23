<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoastController;
use App\Http\Controllers\LeadController; 
use Illuminate\Support\Facades\Storage; // Add this line

Route::get('/', function () {
    return view('roast');
});

// The real-time monitor view for the conference screen
Route::get('/monitor', function () {
    return view('monitor');
});

// The endpoint that handles the resume submission
Route::post('/roast', [RoastController::class, 'roast']);

// The new endpoint to handle lead capture form submission
Route::post('/leads', [LeadController::class, 'store']);

// TEMPORARY ROUTE FOR FILE WRITING TEST - REMOVE THIS AFTER DEBUGGING
Route::get('/test-write', function () {
    try {
        $testPath = 'exports/test_write.txt';
        Storage::disk('local')->put($testPath, 'This is a test message from Laravel!');
        return "Test file written successfully to storage/app/{$testPath}";
    } catch (\Exception $e) {
        return "Failed to write test file: " . $e->getMessage();
    }
});