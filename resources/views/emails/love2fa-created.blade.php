<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Mystery Gift is Ready!</title>
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
        .gift-icon {
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
        .mystery-box {
            background: linear-gradient(135deg, #2C003E 0%, #52006A 100%);
            color: white;
            border-radius: 12px;
            padding: 30px;
            margin: 30px 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .mystery-box::before {
            content: '?';
            position: absolute;
            font-size: 200px;
            opacity: 0.1;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .mystery-box h3 {
            margin: 0 0 15px 0;
            font-size: 22px;
            position: relative;
            z-index: 1;
        }
        .mystery-box p {
            margin: 0;
            position: relative;
            z-index: 1;
            font-size: 16px;
            line-height: 1.6;
        }
        .pin-box {
            background: #FFF9E6;
            border: 3px dashed #FFC107;
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
            text-align: center;
        }
        .pin-label {
            font-size: 14px;
            color: #F57C00;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }
        .pin {
            font-size: 40px;
            font-weight: 700;
            color: #DD0000;
            letter-spacing: 8px;
            font-family: 'Courier New', monospace;
        }
        .link-box {
            background: #FFF5F7;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .link-url {
            color: #DD0000;
            font-weight: 600;
            word-break: break-all;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #DD0000 0%, #FF1493 100%);
            color: white;
            text-decoration: none;
            padding: 16px 40px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
        }
        .info-section {
            background: #E8F5E9;
            border-left: 4px solid #4CAF50;
            padding: 20px;
            margin: 30px 0;
            border-radius: 8px;
        }
        .info-section h3 {
            color: #2E7D32;
            margin: 0 0 15px 0;
            font-size: 18px;
        }
        .info-section ul {
            margin: 0;
            padding-left: 20px;
        }
        .info-section li {
            margin-bottom: 8px;
            color: #555;
            line-height: 1.6;
        }
        .warning-box {
            background: #FFEBEE;
            border: 2px solid #F44336;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .warning-box strong {
            color: #C62828;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 25px 0;
        }
        .stat-card {
            background: #F5F5F5;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #DD0000;
            margin-bottom: 5px;
        }
        .stat-label {
            font-size: 14px;
            color: #666;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #eee;
            color: #999;
            font-size: 14px;
        }
        .highlight-box {
            background: #E3F2FD;
            border: 2px solid #2196F3;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <div class="gift-icon">🎁🔐</div>
                <h1>Your Love 2FA Mystery is Live!</h1>
                <p class="subtitle">The gift is wrapped, the mystery awaits...</p>
            </div>

            <div class="mystery-box">
                <h3>🕵️ Secret Admirer Mode: Activated</h3>
                <p>
                    Your anonymous gift has been created! The recipient will need to enter their PIN
                    to unlock the mystery, then guess YOUR NAME to reveal who sent it!
                </p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value">{{ $maxAttempts }}</div>
                    <div class="stat-label">Name Guessing Attempts</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ count($hints) }}</div>
                    <div class="stat-label">Hints Provided</div>
                </div>
            </div>

            <div class="warning-box">
                <strong>⚠️ Important - Share Both Items Below:</strong>
                <p style="margin: 10px 0 0 0;">The recipient needs BOTH the link AND the PIN code to access the mystery!</p>
            </div>

            <div class="highlight-box">
                <h3 style="color: #1565C0; margin: 0 0 15px 0;">📱 Step 1: Share the Link</h3>
                <div class="link-box" style="margin: 15px 0;">
                    <a href="{{ $love2faUrl }}" class="link-url">{{ $love2faUrl }}</a>
                </div>
                <p style="margin: 0; color: #666; font-size: 14px; text-align: center;">
                    Send this link <b>indirectly</b> to the recipient via text, email, or DM
                </p>
            </div>

            <div class="pin-box">
                <div class="pin-label">🔑 Step 2: Share This PIN Code</div>
                <div class="pin">{{ $recipientPincode }}</div>
                <p style="margin-top: 15px; color: #666; font-size: 14px;">
                    The recipient needs this 4-digit PIN to unlock the mystery!
                </p>
            </div>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $love2faUrl }}" class="button">View Mystery Gift Page</a>
            </div>

            <div class="info-section">
                <h3>🎯 How Love 2FA Works</h3>
                <ul>
                    <li><strong>Step 1 - Share:</strong> Send the recipient (<b>indirectly</b>) both the link and PIN code above</li>
                    <li><strong>Step 2 - Unlock:</strong> They enter the PIN to unlock the mystery page</li>
                    <li><strong>Step 3 - Reveal:</strong> They see your gift description and message</li>
                    <li><strong>Step 4 - The Game:</strong> They have {{ $maxAttempts }} attempts to guess YOUR NAME ({{ $senderName }})</li>
                    <li><strong>Step 5 - Hints:</strong> If stuck, they can view your helpful hints</li>
                    <li><strong>Step 6 - Success:</strong> When they guess "{{ $senderName }}" correctly, your identity is revealed!</li>
                    <li><strong>Step 7 - Notifications:</strong> You'll receive an email each time they make a guess</li>
                </ul>
            </div>

            @if(count($hints) > 0)
            <div class="info-section" style="background: #E3F2FD; border-color: #2196F3;">
                <h3 style="color: #1565C0;">💡 Your Hints</h3>
                <p style="margin-bottom: 10px; color: #555;">These clues will help The recipient guess your name if they get stuck:</p>
                <ul>
                    @foreach($hints as $hint)
                    <li>{{ $hint }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="info-section" style="background: #FCE4EC; border-color: #E91E63;">
                <h3 style="color: #C2185B;">💝 Quick Tips</h3>
                <ul>
                    <li><strong>Share wisely:</strong> Make sure the recipient gets both the link AND PIN</li>
                    <li><strong>The challenge:</strong> They're guessing "{{ $senderName }}" - your full name!</li>
                    <li><strong>Smart hints:</strong> Make clues clever but not too obvious</li>
                    <li><strong>Stay tuned:</strong> You'll get notified after each guess attempt</li>
                    <li><strong>Be ready:</strong> Prepare for their reaction when they figure it out!</li>
                </ul>
            </div>

            <div class="footer">
                <p>💕 Made with love by <b><a target="_blank" href="https://yarmy.tech">Yarmy Tech</a></b> 💕</p>
                <p>Keeping the mystery alive this Valentine's Day 2026</p>
                <p style="font-size: 12px; color: #ccc; margin-top: 20px;">
                    This email was sent because you created a Love 2FA mystery gift
                </p>
            </div>
        </div>
    </div>
</body>
</html>
