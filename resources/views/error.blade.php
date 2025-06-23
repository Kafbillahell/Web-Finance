<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - Page Not Found</title>
    <!-- Inter Font from Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom Keyframe for FadeIn animation, as Tailwind's default fade-in doesn't include translateY */
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

        /* Apply animation directly to the container */
        .animate-fade-in {
            animation: fadeIn 0.8s ease-out forwards;
        }

        /* Basic styling for body and root variables (Tailwind will mostly override, but good practice) */
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
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            /* Ensure the body takes full viewport height and centers content */
            min-height: 100vh;
        }
    </style>
</head>

<body class="flex justify-center items-center min-h-screen bg-gradient-to-r from-purple-500 via-purple-600 to-indigo-500 text-gray-800 overflow-hidden">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-11/12 p-10 text-center animate-fade-in box-border">
        <!-- Logo SVG - Reusing the one from your original code -->
        <div class="mb-8">
            <svg class="h-12 w-auto mx-auto" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" x2="12" y1="2" y2="22"></line>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
            </svg>
        </div>

        <!-- Error Code -->
        <h2 class="text-5xl font-extrabold mb-2 text-gray-800">{{ $code }}</h2>
        <!-- Error Message -->
        <p class="text-lg text-gray-600 mb-10 leading-relaxed">{{ $message }}</p>

        <!-- Call to action button -->
        <a href="/" class="block w-full py-4 px-6 bg-blue-600 text-white rounded-xl text-xl font-semibold cursor-pointer transition-all duration-300 ease-in-out mt-8 tracking-wide hover:bg-blue-700 hover:scale-105 hover:shadow-lg">
            Go Back Home
        </a>
    </div>
</body>

</html>
