<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isCorrect ? '🎉 They Guessed Your Name!' : '🔍 New Name Guess Attempt' }}</title>
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
        .icon {
            font-size: 64px;
            margin-bottom: 20px;
        }
        .success-icon {
            animation: bounce 1s infinite;
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
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
        .attempt-box {
            background: linear-gradient(135deg, #FFE5EC 0%, #FFF0F5 100%);
            border-left: 4px solid #DD0000;
            padding: 25px;
            margin: 30px 0;
            border-radius: 8px;
        }
        .attempt-box.success {
            background: linear-gradient(135deg, #E8F5E9 0%, #F1F8E9 100%);
            border-left: 4px solid #4CAF50;
        }
        .attempt-box.failed {
            background: linear-gradient(135deg, #FFEBEE 0%, #FCE4EC 100%);
            border-left: 4px solid #F44336;
        }
        .attempt-label {
            font-size: 14px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        .attempt-name {
            font-size: 32px;
            font-weight: 700;
            margin: 10px 0;
        }
        .attempt-name.success {
            color: #4CAF50;
        }
        .attempt-name.failed {
            color: #F44336;
        }
        .stats-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin: 25px 0;
        }
        .stat-box {
            background: #f9f9f9;
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
        .stat-value.warning {
            color: #FF9800;
        }
        .stat-value.danger {
            color: #F44336;
        }
        .stat-label {
            font-size: 12px;
            color: #666;
        }
        .info-box {
            background: #E3F2FD;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .info-box h3 {
            color: #1565C0;
            margin: 0 0 15px 0;
            font-size: 18px;
        }
        .info-box p {
            margin: 0 0 10px 0;
            color: #555;
            line-height: 1.6;
        }
        .info-box ul {
            margin: 10px 0 0 0;
            padding-left: 20px;
        }
        .info-box li {
            margin-bottom: 8px;
            color: #555;
        }
        .celebration {
            background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
            color: white;
            text-align: center;
            padding: 30px;
            border-radius: 12px;
            margin: 30px 0;
        }
        .celebration h2 {
            margin: 0 0 15px 0;
            font-size: 24px;
        }
        .celebration p {
            margin: 0;
            font-size: 16px;
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
        .timeline {
            margin: 30px 0;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
        }
        .timeline-item {
            padding: 15px;
            border-left: 3px solid #ddd;
            margin-left: 20px;
            margin-bottom: 15px;
            position: relative;
        }
        .timeline-item::before {
            content: '';
            width: 12px;
            height: 12px;
            background: #ddd;
            border-radius: 50%;
            position: absolute;
            left: -7.5px;
            top: 20px;
        }
        .timeline-item.failed::before {
            background: #F44336;
        }
        .timeline-item.success::before {
            background: #4CAF50;
        }
        .timeline-time {
            font-size: 12px;
            color: #999;
        }
        .timeline-name {
            font-weight: 600;
            margin: 5px 0;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #eee;
            color: #999;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <div class="icon {{ $isCorrect ? 'success-icon' : '' }}">
                    @if($isCorrect)
                        🎉🎊✨
                    @elseif($attemptsRemaining === 0)
                        😢🔒
                    @elseif($attemptsRemaining <= 2)
                        🤔❓
                    @else
                        🔍🎯
                    @endif
                </div>
                <h1>
                    @if($isCorrect)
                        They Figured It Out! 🎉
                    @elseif($attemptsRemaining === 0)
                        All Attempts Used
                    @else
                        New Name Guess Attempt
                    @endif
                </h1>
                <p class="subtitle">{{ $recipientName }} just tried to guess who you are</p>
            </div>

            @if($isCorrect)
            <div class="celebration">
                <h2>🎊 Mystery Solved! 🎊</h2>
                <p>
                    {{ $recipientName }} successfully guessed that <strong>{{ $senderName }}</strong> is their secret admirer!
                    The mystery is revealed, and now the real romance can begin! 💕
                </p>
            </div>
            @endif

            <div class="attempt-box {{ $isCorrect ? 'success' : 'failed' }}">
                <div class="attempt-label">
                    @if($isCorrect)
                        ✅ CORRECT GUESS
                    @else
                        ❌ INCORRECT ATTEMPT
                    @endif
                </div>
                <div class="attempt-name {{ $isCorrect ? 'success' : 'failed' }}">
                    {{ $guessedName }}
                </div>
                <p style="margin: 10px 0 0 0; font-size: 14px; color: #666;">
                    Attempted at {{ $attemptTime }}
                </p>
                @if($isCorrect)
                <p style="margin: 10px 0 0 0; font-size: 14px; color: #4CAF50;">
                    <strong>They guessed your name correctly!</strong>
                </p>
                @else
                <p style="margin: 10px 0 0 0; font-size: 14px; color: #F44336;">
                    They guessed "{{ $guessedName }}" but your name is "{{ $senderName }}"
                </p>
                @endif
            </div>

            <div class="stats-container">
                <div class="stat-box">
                    <div class="stat-value {{ $attemptsRemaining <= 1 ? 'danger' : ($attemptsRemaining <= 2 ? 'warning' : '') }}">
                        {{ $attemptsRemaining }}
                    </div>
                    <div class="stat-label">Attempts Left</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value">{{ $totalAttempts }}</div>
                    <div class="stat-label">Total Guesses</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value">{{ $hintsViewed }}</div>
                    <div class="stat-label">Hints Viewed</div>
                </div>
            </div>

            @if($isCorrect)
            <div class="info-box" style="background: #E8F5E9; border: 2px solid #4CAF50;">
                <h3 style="color: #2E7D32;">🎯 What Happens Next?</h3>
                <ul>
                    <li><strong>They know it's you!</strong> {{ $recipientName }} successfully guessed that you're {{ $senderName }}</li>
                    <li><strong>Time to connect:</strong> Reach out and have that conversation you've been thinking about</li>
                    <li><strong>Be confident:</strong> They spent time guessing your name - they're clearly interested!</li>
                    <li><strong>Make plans:</strong> Suggest meeting up or doing something together</li>
                    <li><strong>Keep it light:</strong> Have fun with it and see where things go!</li>
                </ul>
            </div>
            @elseif($attemptsRemaining === 0)
            <div class="warning-box" style="background: #FFEBEE; border-color: #F44336;">
                <strong>⚠️ No Attempts Remaining:</strong> {{ $recipientName }} has used all their guesses and hasn't figured out that you're {{ $senderName }}. You might want to reveal yourself directly or give them another chance!
            </div>
            <div class="info-box">
                <h3>💡 What You Can Do</h3>
                <ul>
                    <li>Reach out to them directly and reveal the surprise</li>
                    <li>Send them additional hints through another channel</li>
                    <li>Create a new Love 2FA with easier hints about your name</li>
                    <li>Keep the mystery going a bit longer if you're enjoying it!</li>
                </ul>
            </div>
            @elseif($attemptsRemaining <= 2)
            <div class="warning-box">
                <strong>⚠️ Running Low on Attempts!</strong> {{ $recipientName }} only has {{ $attemptsRemaining }} {{ $attemptsRemaining === 1 ? 'attempt' : 'attempts' }} left to guess that you're {{ $senderName }}. They might need some extra hints soon!
            </div>
            @else
            <div class="info-box">
                <h3>🎮 The Game Continues</h3>
                <p>
                    {{ $recipientName }} is still trying to figure out that you're {{ $senderName }}. They have {{ $attemptsRemaining }} more
                    {{ $attemptsRemaining === 1 ? 'attempt' : 'attempts' }} to guess your name correctly. Keep the suspense going!
                </p>
            </div>
            @endif

            @if(!empty($previousAttempts) && count($previousAttempts) > 0)
            <div class="timeline">
                <h3 style="margin: 0 0 20px 0; color: #DD0000;">📝 Guess History</h3>
                @foreach($previousAttempts as $prevAttempt)
                <div class="timeline-item {{ $prevAttempt['is_correct'] ? 'success' : 'failed' }}">
                    <div class="timeline-time">{{ $prevAttempt['time'] }}</div>
                    <div class="timeline-name">{{ $prevAttempt['name'] }}</div>
                    <div>{{ $prevAttempt['is_correct'] ? '✅ Correct - They guessed your name!' : '❌ Incorrect guess' }}</div>
                </div>
                @endforeach
            </div>
            @endif

            @if(!$isCorrect)
            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ $love2faUrl }}" class="button">View Mystery Gift Page</a>
            </div>
            @endif

            <div class="footer">
                <p>💕 Made with love by <b><a target="_blank" href="https://yarmy.tech">Yarmy Tech</a></b> 💕</p>
                <p>Keeping the mystery alive this Valentine's Day 2026</p>
                <p style="font-size: 12px; color: #ccc; margin-top: 20px;">
                    This email was sent because someone attempted to guess your identity in your Love 2FA mystery
                </p>
            </div>
        </div>
    </div>
</body>
</html>
