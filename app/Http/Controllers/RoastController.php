<?php

namespace App\Http\Controllers;

use App\Events\NewRoast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;
use Spatie\PdfToText\Pdf;

class RoastController extends Controller
{
    public function roast(Request $request)
    {
        Log::info('Roast request received.');

        $request->validate([
            'resume_text' => 'nullable|string',
            'resume_file' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $resumeText = '';

        if ($request->filled('resume_text')) {
            $resumeText = $request->input('resume_text');
        } elseif ($request->hasFile('resume_file')) {
            $resumeText = (new Pdf())->setPdf($request->file('resume_file')->getRealPath())->text();
        } else {
            Log::warning('No resume provided.');
            return response()->json(['error' => 'No resume provided, you doughnut!'], 400);
        }

        Log::info('Resume text extracted.');

        try {
            Log::info('Sending request to OpenAI API...');
            $response = OpenAI::chat()->create([
                'model' => 'gpt-4o',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "You are an AI embodiment of Gordon Ramsay, but you are not just reviewing a resume, you are absolutely DESTROYING it. Your persona is savage, unforgiving, and brutally hilarious. Use a wide range of your most iconic insults (idiot sandwich, donut, donkey, etc.). The feedback must be extremely thorough and delivered with theatrical cruelty. Analyze every single detail: formatting, font choice, word choice, white space, and the impact of every bullet point. Break down your scathing review into these sections: 'First Impressions', 'Formatting & Presentation', 'Contact Info', 'Summary/Objective', 'Experience', 'Skills', and 'Education'. For each section, provide a merciless and in-depth critique. Don't hold back. Make the user question their life choices. At the end, provide a rating from 1-5 stars and a final, soul-crushing verdict. Format the rating as 'RATING: X/5'."
                    ],
                    [
                        'role' => 'user',
                        'content' => "Here's the resume. Now, give it to me raw:\n\n" . $resumeText,
                    ],
                ],
                'temperature' => 0.95, // Increased for more creative and unhinged responses
                'max_tokens' => 2000,
            ]);
            Log::info('Received response from OpenAI API.');

            $analysis = $response->choices[0]->message->content;

            Log::info('Dispatching NewRoast event...');
            NewRoast::dispatch($analysis);
            Log::info('NewRoast event dispatched successfully.');

            return response()->json(['analysis' => $analysis]);
        } catch (\Exception $e) {
            Log::error('An error occurred in RoastController: ' . $e->getMessage());
            return response()->json(['error' => "The kitchen is in chaos! Something went wrong with the API call."], 500);
        }
    }
}
