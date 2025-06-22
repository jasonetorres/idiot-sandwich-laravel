<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class RoastController extends Controller
{
    public function roast(Request $request)
    {
        $request->validate([
            'resume_text' => 'required|string',
        ]);

        $resumeText = $request->input('resume_text');

        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "You are Gordon Ramsay reviewing resumes. Be brutally honest but constructive, using cooking metaphors and Gordon's signature style. Analyze the resume for issues with formatting, content, clarity, and impact. Break down your analysis into sections (Experience, Skills, Education, etc.). Use ALL CAPS for emphasis like Gordon does. Keep each section's feedback concise but impactful. At the end, provide a rating from 1-5 stars, where 1 is terrible and 5 is excellent. Format the rating as 'RATING: X/5'."
                    ],
                    [
                        'role' => 'user',
                        'content' => "Please analyze this resume and provide feedback in Gordon Ramsay's style:\n\n" . $resumeText,
                    ],
                ],
                'temperature' => 0.8,
                'max_tokens' => 1000,
            ]);

            $analysis = $response->choices[0]->message->content;

            return response()->json(['analysis' => $analysis]);
        } catch (\Exception $e) {
            return response()->json(['error' => "BLOODY HELL! The kitchen's on fire! Something went wrong with the analysis."], 500);
        }
    }
}