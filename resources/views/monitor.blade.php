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
            <div class="initial-monitor-state">
                <h2>Waiting for the next chef to serve a resume...</h2>
                <p>Scan the QR code to get your resume roasted live!</p>
                <div class="qr-code-container-monitor">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=http://127.0.0.1:8000/" alt="Scan to Roast Resume">
                    <p class="mt-4">Go to your phone camera and scan this code to roast your resume now!</p>
                </div>
            </div>
            </div>
    </div>
    @vite('resources/js/app.js')
</body>
</html>