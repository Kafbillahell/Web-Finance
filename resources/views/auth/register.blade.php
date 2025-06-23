<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up for Free</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #007bff;
            /* A nice blue */
            --dark-color: #343a40;
            /* Dark grey for text */
            --light-bg: #f8f9fa;
            /* Light background for the form card */
            --white: #ffffff;
            --border-color: #ced4da;
            --danger-color: #dc3545;
            --shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            /* More pronounced shadow */
        }

        body {
            font-family: 'inter', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #6dd5ed 0%, #2193b0 100%);
            /* Modern gradient background */
            color: var(--dark-color);
            overflow: hidden;
            /* Prevent scrollbar from background effects */
        }

        .signup-container {
            background-color: var(--light-bg);
            /* Use a light background for the card */
            border-radius: 12px;
            box-shadow: var(--shadow);
            max-width: 450px;
            /* Reduced max-width as there's no image */
            width: 90%;
            padding: 40px;
            text-align: center;
            animation: fadeIn 0.8s ease-out;
            box-sizing: border-box;
            /* Include padding in width */
        }

        .signup-container .logo {
            margin-bottom: 30px;
        }

        .signup-container .logo img {
            max-width: 100px;
            /* Slightly smaller logo */
            height: auto;
        }

        .signup-container h2 {
            font-size: 2.5em;
            /* Slightly larger heading */
            margin-bottom: 10px;
            color: var(--dark-color);
            font-weight: 700;
            /* Bolder heading */
        }

        .signup-container p {
            font-size: 1.05em;
            color: #6c757d;
            margin-bottom: 35px;
            line-height: 1.6;
        }

        .form-group {
            margin-bottom: 25px;
            width: 100%;
            text-align: left;
            /* Align labels and errors to the left */
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 500;
            color: var(--dark-color);
            font-size: 0.95em;
        }

        .form-group input[type="text"],
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

        .form-group input:focus {
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

        .login-link {
            margin-top: 30px;
            font-size: 1em;
            color: #6c757d;
        }

        .login-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .login-link a:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        /* Animations */
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

        /* Responsive adjustments */
        @media (max-width: 576px) {
            .signup-container {
                padding: 30px 25px;
                border-radius: 8px;
            }

            .signup-container h2 {
                font-size: 2em;
            }

            .signup-container p {
                font-size: 0.95em;
                margin-bottom: 25px;
            }

            .form-group input {
                padding: 14px 18px;
            }

            .btn-submit {
                padding: 16px;
                font-size: 1.1em;
            }
        }
    </style>
</head>

<body>
    <div class="signup-container">
        <h2>Create Your Account</h2>
        <p>Join us today! Fill out the form below to get started.</p>

        <form method="POST" action="{{ route('register.store') }}" style="width: 100%;">
            @csrf
            <div class="form-group">
                <label for="name">Your Name</label>
                <input id="name" name="name" type="text" placeholder="Full Name" value="{{ old('name') }}" required>
                @error('name')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input id="email" name="email" type="email" placeholder="your@example.com" value="{{ old('email') }}" required>
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

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn-submit">Sign Up</button>

            <div class="login-link">
                Already have an account? <a href="{{ route('login') }}">Sign In</a>
            </div>
        </form>
    </div>
</body>

</html>