<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mytime</title>
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

        .login-wrapper {
            position: relative;
            z-index: 1;
        }

        .login-container {
            background-color: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15), 0 0 1px rgba(255, 255, 255, 0.3) inset;
            width: 100%;
            max-width: 420px;
            padding: 48px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            animation: fadeIn 0.6s ease;
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

        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo {
            width: 180px;
            height: 180px;
            margin: 0 auto 24px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.2));
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .login-subtitle {
            color: rgba(255, 255, 255, 0.85);
            font-size: 14px;
            font-weight: 500;
            letter-spacing: 0.5px;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
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

        .btn-login {
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

        .btn-login:hover {
            filter: brightness(0.95);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .login-footer {
            text-align: center;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .login-footer a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 12px;
            transition: color 0.3s ease;
            margin: 0 8px;
        }

        .login-footer a:hover {
            color: rgba(255, 255, 255, 0.95);
        }

        @media (max-width: 480px) {
            .login-container {
                max-width: 90%;
                padding: 32px 24px;
            }

            .logo {
                width: 140px;
                height: 140px;
                margin: 0 auto 20px;
            }

            .login-subtitle {
                font-size: 13px;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-container">
            <div class="login-header">
                <div class="logo">
                    <img src="/pictures/logo.png" alt="Mytime Logo">
                </div>
                <p class="login-subtitle">Time • Refined • Elevated</p>
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

            <form method="POST" action="/login">
                @csrf

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="your@email.com" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>

                <button type="submit" class="btn-login">Sign In</button>
            </form>

            <div class="login-footer">
                <a href="#">Forgot password?</a>
                <span style="color: rgba(255, 255, 255, 0.3);">•</span>
                <a href="#">Contact support</a>
            </div>
        </div>
    </div>
</body>
</html>
