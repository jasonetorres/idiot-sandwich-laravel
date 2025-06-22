<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Resume Roast - Hall of Flame</title>
    @vite('resources/css/app.css')
</head>
<body class="monitor-page">
    <div class="container">
        <div class="header">
            <img src="https://i.postimg.cc/63bGh3fT/logosticker.png" alt="TORC Logo" class="header-logo">
            <h1>TORC'S RESUME ROAST</h1>
            <p>Live from Laracon!</p>
        </div>
        <div id="live-roasts">
            <!-- New roasts will appear here, styled as report cards -->
        </div>
    </div>
    @vite('resources/js/app.js')
</body>
</html>
