<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>It's Roast Time</title>
    @vite('resources/css/app.css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="https://i.postimg.cc/63bGh3fT/logosticker.png" alt="TORC Logo" class="header-logo">
            <h1>TORC'S RESUME ROAST</h1>
            <p>Live from Laracon!</p>
        </div>

        <div class="form-container">
            <div class="tab-nav">
                <button id="paste-tab" class="tab-button active">Copy & Paste</button>
                <button id="upload-tab" class="tab-button">Upload PDF</button>
            </div>

            <div id="paste-content" class="tab-content">
                <label for="resumeText">Paste Your Resume Here, YOU DONKEY!</label>
                <textarea id="resumeText" placeholder="Paste your resume content here... AND MAKE IT GOOD!"></textarea>
            </div>

            <div id="upload-content" class="tab-content" style="display: none;">
                <label for="resumeFile">Upload Your Resume PDF, YOU DONKEY!</label>
                <input type="file" id="resumeFile" accept=".pdf" />
            </div>

            <div class="button-group">
                <button id="roastButton">ROAST IT!</button>
            </div>
        </div>

        <div id="loading" class="loading-indicator" style="display: none;">
            <div class="spinner"></div>
            <p>Whipping up a storm...</p>
        </div>

        <div id="feedback" class="feedback-container" style="display: none;">
             <p style="text-align: center; font-size: 1.25rem; color: #4ade80;">Your roast has been served on the main screen!</p>
        </div>

        <div class="qr-code-container">
            <p>Scan to participate!</p>
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ url('/') }}" alt="QR Code">
        </div>

    </div>

    <footer class="footer">
        <p>Built with <a href="https://laravel.com" target="_blank" rel="noopener noreferrer">Laravel</a> for <a href="https://torc.dev" target="_blank" rel="noopener noreferrer">Torc</a> by Jason Torres &copy; 2025</p>
    </footer>

    <!-- Audio files for sound effects -->
    <audio id="sound-terrible" src="/audio/terrible.mp3"></audio>
    <audio id="sound-bad" src="/audio/bad.mp3"></audio>
    <audio id="sound-average" src="/audio/average.mp3"></audio>
    <audio id="sound-good" src="/audio/good.mp3"></audio>
    <audio id="sound-excellent" src="/audio/excellent.mp3"></audio>

    @vite('resources/js/app.js')
</body>
</html>
