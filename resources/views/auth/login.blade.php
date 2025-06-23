<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login to Your Account</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #007bff;
            --dark-color: #343a40;
            --light-bg: #f8f9fa;
            --white: #ffffff;
            --border-color: #ced4da;
            --danger-color: #dc3545;
            --shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(to right,#8971ea,#7f72ea,#7574ea,#6a75e9,#5f76e8);
            color: var(--dark-color);
            overflow: hidden;
        }

        .login-container {
            background-color: var(--light-bg);
            border-radius: 12px;
            box-shadow: var(--shadow);
            max-width: 450px;
            width: 90%;
            padding: 40px;
            text-align: center;
            animation: fadeIn 0.8s ease-out;
            box-sizing: border-box;
        }

        .login-container .logo {
            margin-bottom: 30px;
        }

        .login-container .logo svg {
            max-width: 100px;
            height: auto;
        }

        .login-container h2 {
            font-size: 2.5em;
            margin-bottom: 10px;
            color: var(--dark-color);
            font-weight: 700;
        }

        .login-container p {
            font-size: 1.05em;
            color: #6c757d;
            margin-bottom: 35px;
            line-height: 1.6;
        }

        .form-group {
            margin-bottom: 25px;
            width: 100%;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 500;
            color: var(--dark-color);
            font-size: 0.95em;
        }

        .form-group input[type="email"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 16px 20px;
            /* More padding for inputs */
            border: 1px solid var(--border-color);
            border-radius: 10px;
            /* More rounded inputs */
            font-size: 1.05em;
            transition: all 0.3s ease;
            box-sizing: border-box;
            background-color: var(--white);
            /* Ensure input background is white */
        }

        .form-group input[type="email"]:focus,
        .form-group input[type="password"]:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 4px rgba(0, 123, 255, 0.3);
            /* Stronger focus glow */
        }

        .form-group small.text-danger {
            color: var(--danger-color);
            margin-top: 8px;
            display: block;
            font-size: 0.88em;
            text-align: left;
        }

        .btn-submit {
            width: 100%;
            padding: 18px;
            /* More padding for button */
            background-color: var(--primary-color);
            color: var(--white);
            border: none;
            border-radius: 10px;
            /* More rounded button */
            font-size: 1.2em;
            /* Larger font for button */
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
            margin-top: 20px;
            letter-spacing: 0.5px;
            /* Slight letter spacing for modernity */
        }

        .btn-submit:hover {
            background-color: #0056b3;
            transform: translateY(-3px);
            /* More pronounced lift on hover */
            box-shadow: 0 6px 12px rgba(0, 123, 255, 0.2);
            /* Shadow on hover */
        }

        .signup-link {
            margin-top: 30px;
            font-size: 1em;
            color: #6c757d;
        }

        .signup-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .signup-link a:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        .alert {
            padding: 18px;
            border-radius: 10px;
            margin-top: 30px;
            font-size: 0.95em;
            animation: fadeIn 0.5s ease-out;
            width: 100%;
            box-sizing: border-box;
            line-height: 1.5;
        }

        .alert-warning {
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="logo">
            <svg class="h-12 w-12-400" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--primary-color)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" x2="12" y1="2" y2="22"></line>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
            </svg>
        </div>
        <h2>Welcome Back!</h2>
        <p>Sign in to continue your journey and access your personalized dashboard.</p>

        <form method="POST" action="{{ route('login') }}" style="width: 100%;">
            @csrf
            <div class="form-group">
                <label for="email">Email Address</label>
                <input id="email" name="email" type="email" placeholder="your@example.com" required autofocus value="{{ old('email') }}">
                @error('email')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" placeholder="••••••••" required>
                @error('password')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn-submit">Log In</button>

            <div class="signup-link">
                Don't have an account? <a href="{{ route('register') }}">Create an Account</a>
            </div>
        </form>

        @if(session('message'))
        <div class="alert alert-warning">{{ session('message') }}</div>
        @endif
    </div>
</body>

</html>