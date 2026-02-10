<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your Valentine Link Is Ready 💝</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
            background-color: #FFF5F7;
            color: #4A1C1C;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        .card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            border: 2px solid rgba(221, 0, 0, 0.12);
            box-shadow: 0 8px 30px rgba(221, 0, 0, 0.12);
            text-align: center;
        }
        h1 {
            color: #DD0000;
            font-size: 28px;
            margin-bottom: 10px;
        }
        p {
            line-height: 1.7;
            font-size: 16px;
        }
        .button {
            display: inline-block;
            margin-top: 30px;
            padding: 16px 42px;
            background: linear-gradient(135deg, #DD0000, #FF1493);
            color: white;
            text-decoration: none;
            font-weight: 600;
            border-radius: 999px;
        }
        .footer {
            margin-top: 40px;
            font-size: 14px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div style="font-size: 56px;">💌</div>

            <h1>Your Valentine Link Is Ready!</h1>

            <p>
                Hi <strong>{{ $senderName }}</strong>,
            </p>

            <p>
                Your Valentine message has been created successfully.
                All that's left is to send the link to your special someone
                and wait for their answer 💕
            </p>

            <a href="{{ $valentineUrl }}" class="button">
                Open Valentine Link
            </a>

            <p class="footer">
                💖 Made with love • Valentine’s Day 2026<br>
                Share wisely… hearts may be broken 😉
            </p>
        </div>
    </div>
</body>
</html>
