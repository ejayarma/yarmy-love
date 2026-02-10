<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Valentine Response 💌</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
            background-color: #FFF5F7;
            color: #4A1C1C;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        .card {
            background: #ffffff;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            border: 2px solid rgba(221, 0, 0, 0.1);
            box-shadow: 0 8px 24px rgba(221, 0, 0, 0.12);
        }
        h1 {
            color: #DD0000;
            font-size: 26px;
        }
        p {
            font-size: 16px;
            line-height: 1.7;
        }
        .yes {
            color: #15803d;
            font-weight: 700;
            font-size: 20px;
        }
        .no {
            color: #b91c1c;
            font-weight: 700;
            font-size: 20px;
        }
        .footer {
            margin-top: 40px;
            font-size: 13px;
            color: #999;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div style="font-size: 52px;">
            {{ $response === 'yes' ? '💖' : '💔' }}
        </div>

        <h1>Hello {{ $recipientName }},</h1>

        @if ($response === 'yes')
            <p class="yes">
                They said YES! 🎉
            </p>
            <p>
                Your Valentine request was accepted.
                Looks like love is in the air 💕
            </p>
        @else
            <p class="no">
                They said NO.
            </p>
            <p>
                Not every story ends the same way —
                but you were brave enough to ask, and that matters.
            </p>
        @endif

        @if ($message)
            <hr style="margin: 30px 0;">
            <p><em>Message:</em></p>
            <p>{{ $message }}</p>
        @endif

        <p class="footer">
            💌 Sent via BeMyVal • Valentine's Day<br>
            Courage looks good on you.
        </p>
    </div>
</div>
</body>
</html>
