<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoastController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// The main page where users submit their resumes
Route::get('/', function () {
    return view('roast');
});

// The real-time monitor view for the conference screen
Route::get('/monitor', function () {
    return view('monitor');
});

// The endpoint that handles the resume submission
Route::post('/roast', [RoastController::class, 'roast']);

// The endpoint to fetch the leaderboard data
Route::get('/leaderboard', [RoastController::class, 'leaderboard']);