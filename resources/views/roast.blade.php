<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>It's Roast Time</title>
    @vite('resources/css/app.css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="min-h-screen bg-gradient-to-br from-yellow-500 via-orange-700 to-red-800 text-white p-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-center gap-4 mb-8">
            <h1 class="text-4xl font-bold">Gordon's Resume Roast</h1>
        </div>
        
        <div class="bg-white/10 backdrop-blur-lg rounded-lg p-6 mb-8 shadow-lg">
            <div class="mb-4">
                <label class="block text-lg mb-2">Paste Your Resume Here, YOU DONKEY!</label>
                <textarea
                    id="resumeText"
                    class="w-full h-64 p-4 bg-white/5 rounded-lg border border-red-400 text-white placeholder-red-300"
                    placeholder="Paste your resume content here... AND MAKE IT GOOD!"
                ></textarea>
            </div>
            
            <div class="flex gap-4">
                <button id="roastButton" class="flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg font-bold transition-colors">
                    ROAST IT!
                </button>
                <button id="clearButton" class="flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-bold transition-colors">
                    Clear
                </button>
            </div>
        </div>

        <div id="feedback" class="bg-white/10 backdrop-blur-lg rounded-lg p-6 hidden">
            <h2 class="text-2xl font-bold mb-4">The Dish</h2>
            <div id="feedbackContent" class="space-y-4"></div>
            <div id="rating" class="mt-8 text-center hidden">
                <div id="ratingText" class="text-xl font-bold mb-4"></div>
                <div class="max-w-sm mx-auto">
                    <img id="ratingGif" src="" alt="Gordon's Reaction" class="w-full rounded-lg shadow-lg" />
                </div>
            </div>
        </div>
        
        <footer class="mt-8 text-center text-sm text-red-300">
            <p>Disclaimer: This is a parody app. Gordon Ramsay isn't actually reviewing your resume, you donut, 🍩 its me Jason Torres 2025 &copy;</p>
        </footer>
    </div>
    @vite('resources/js/app.js')
</body>
</html>