<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoastController;
use App\Http\Controllers\LeadController; 

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
