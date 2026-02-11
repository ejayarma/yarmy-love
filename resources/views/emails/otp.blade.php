<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Login Code</title>
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
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(221, 0, 0, 0.1);
            border: 2px solid rgba(221, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .lock-icon {
            font-size: 64px;
            margin-bottom: 20px;
        }

        h1 {
            color: #DD0000;
            font-size: 28px;
            font-weight: 700;
            margin: 0 0 10px 0;
        }

        .subtitle {
            color: #666;
            font-size: 16px;
            margin: 0 0 30px 0;
        }

        .otp-box {
            background: linear-gradient(135deg, #DD0000 0%, #FF1493 100%);
            color: white;
            border-radius: 12px;
            padding: 30px;
            margin: 30px 0;
            text-align: center;
        }

        .otp-label {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 15px;
            opacity: 0.9;
        }

        .otp-code {
            font-size: 48px;
            font-weight: 700;
            letter-spacing: 12px;
            font-family: 'Courier New', monospace;
            margin: 20px 0;
        }

        .otp-hint {
            font-size: 14px;
            opacity: 0.9;
            margin-top: 15px;
        }

        .info-box {
            background: #E3F2FD;
            border-left: 4px solid #2196F3;
            padding: 20px;
            margin: 30px 0;
            border-radius: 8px;
        }

        .info-box h3 {
            color: #1565C0;
            margin: 0 0 15px 0;
            font-size: 18px;
        }

        .info-box ul {
            margin: 0;
            padding-left: 20px;
        }

        .info-box li {
            margin-bottom: 8px;
            color: #555;
            line-height: 1.6;
        }

        .warning-box {
            background: #FFF9E6;
            border: 2px solid #FFC107;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }

        .warning-box strong {
            color: #F57C00;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #eee;
            color: #999;
            font-size: 14px;
        }

        .expiry {
            background: #FFEBEE;
            border: 2px solid #F44336;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            margin: 20px 0;
        }

        .expiry strong {
            color: #C62828;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <div class="lock-icon">🔐</div>
                <h1>Your Login Code</h1>
                <p class="subtitle">Use this code to sign in to Yarmy Love</p>
            </div>

            <div class="otp-box">
                <div class="otp-label">Your One-Time Code</div>
                <div class="otp-code">{{ $otpCode }}</div>
                <div class="otp-hint">Enter this 6-digit code to continue</div>
            </div>

            <div class="expiry">
                <p style="margin: 0;">
                    <strong>⏰ Expires in {{ round(abs($expiresInMinutes)) }} minutes</strong><br />
                    <span style="font-size: 14px; color: #666;">at {{ $expiresAt->format('h:i A') }}</span>
                </p>
            </div>

            <div class="info-box">
                <h3>🛡️ Security Tips</h3>
                <ul>
                    <li><strong>Don't share this code</strong> with anyone, even if they claim to be from Yarmy Love
                    </li>
                    <li><strong>Only valid for {{ round(abs($expiresInMinutes)) }} minutes</strong> - use it quickly!</li>
                    <li><strong>If you didn't request this,</strong> you can safely ignore this email</li>
                    <li><strong>Use it only once</strong> - the code becomes invalid after use</li>
                </ul>
            </div>

            <div class="warning-box">
                <strong>⚠️ Didn't request this code?</strong>
                <p style="margin: 10px 0 0 0;">
                    If you didn't try to sign in, someone might have entered your email by mistake.
                    You can safely ignore this email - the code will expire automatically.
                </p>
            </div>

            <div class="footer">
                <p>💕 Made with love by <b><a target="_blank" href="https://yarmy.tech">Yarmy Tech</a></b> 💕</p>
                <p>Secure authentication for your Valentine's Day 2026</p>
                <p style="font-size: 12px; color: #ccc; margin-top: 20px;">
                    This email was sent because someone requested a login code for this email address
                </p>
            </div>
        </div>
    </div>
</body>

</html>
