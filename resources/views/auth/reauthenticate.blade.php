<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Identity - Mytime</title>
    <link rel="icon" type="image/png" href="/pictures/logo.png">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            width: 100%;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: url('/pictures/FIjian-currency.jpg') center/cover no-repeat fixed;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.45) 0%, rgba(0, 0, 0, 0.35) 100%);
            backdrop-filter: blur(4px);
            z-index: 0;
        }

        .reauthenticate-wrapper {
            position: relative;
            z-index: 1;
        }

        .reauthenticate-container {
            background-color: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15), 0 0 1px rgba(255, 255, 255, 0.3) inset;
            width: 100%;
            max-width: 420px;
            padding: 48px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            animation: fadeIn 0.6s ease;
            margin: 20px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .reauthenticate-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .security-icon {
            font-size: 64px;
            margin-bottom: 16px;
            display: inline-block;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        .reauthenticate-title {
            color: rgba(255, 255, 255, 0.95);
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .reauthenticate-subtitle {
            color: rgba(255, 255, 255, 0.75);
            font-size: 14px;
            font-weight: 500;
            letter-spacing: 0.5px;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.95);
            font-size: 13px;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
            letter-spacing: 0.3px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 10px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s ease;
            background-color: rgba(255, 255, 255, 0.12);
            color: white;
            backdrop-filter: blur(8px);
        }

        .form-group input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .form-group input:focus {
            outline: none;
            border-color: rgba(59, 130, 246, 0.6);
            background-color: rgba(255, 255, 255, 0.15);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15), inset 0 1px 2px rgba(255, 255, 255, 0.1);
        }

        .form-errors {
            background-color: rgba(248, 215, 218, 0.95);
            border: 1px solid rgba(220, 53, 69, 0.3);
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 20px;
            color: #721c24;
            font-size: 13px;
            backdrop-filter: blur(8px);
        }

        .form-errors ul {
            list-style: none;
            margin: 0;
        }

        .form-errors li {
            margin-bottom: 5px;
        }

        .form-errors li:last-child {
            margin-bottom: 0;
        }

        .btn-verify {
            width: 100%;
            padding: 12px 16px;
            background: linear-gradient(135deg, #3b82f6 0%, #0ea5e9 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
            letter-spacing: 0.3px;
            margin-top: 8px;
        }

        .btn-verify:hover {
            filter: brightness(0.95);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        }

        .btn-verify:active {
            transform: translateY(0);
        }

        .security-info {
            background-color: rgba(59, 130, 246, 0.15);
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 20px;
            color: rgba(255, 255, 255, 0.85);
            font-size: 12px;
            line-height: 1.5;
            backdrop-filter: blur(8px);
        }

        .security-info strong {
            color: rgba(255, 255, 255, 0.95);
        }

        .reauthenticate-footer {
            text-align: center;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .reauthenticate-footer a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 12px;
            transition: color 0.3s ease;
        }

        .reauthenticate-footer a:hover {
            color: rgba(255, 255, 255, 0.95);
        }

        .user-info {
            background-color: rgba(255, 255, 255, 0.08);
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 20px;
            color: rgba(255, 255, 255, 0.85);
            font-size: 13px;
            text-align: center;
            backdrop-filter: blur(8px);
        }

        .user-info strong {
            color: rgba(255, 255, 255, 0.95);
            display: block;
            margin-bottom: 4px;
        }

        @media (max-width: 480px) {
            .reauthenticate-container {
                max-width: 90%;
                padding: 28px 20px;
                margin: 16px;
            }

            .reauthenticate-title {
                font-size: 20px;
            }

            .security-icon {
                font-size: 48px;
            }
        }
    </style>
</head>
<body>
    <div class="reauthenticate-wrapper">
        <div class="reauthenticate-container">
            <div class="reauthenticate-header">
                <div class="security-icon">🔐</div>
                <h1 class="reauthenticate-title">Verify Your Identity</h1>
                <p class="reauthenticate-subtitle">For security purposes, please enter your password to access the Financial section</p>
            </div>

            <div class="user-info">
                <strong>{{ auth()->user()->name }}</strong>
                {{ auth()->user()->email }}
            </div>

            <div class="security-info">
                <strong>🛡️ Security Notice:</strong> This is a sensitive area. We require password verification to protect your financial data.
            </div>

            @if($errors->any())
                <div class="form-errors">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="/reauthenticate">
                @csrf

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required autofocus>
                </div>

                <button type="submit" class="btn-verify">🔓 Verify & Continue</button>
            </form>

            <div class="reauthenticate-footer">
                <a href="/admin/dashboard">← Back to Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>
