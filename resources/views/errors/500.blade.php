<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Server Error</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 font-sans antialiased">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="text-center max-w-2xl">
            <h1 class="text-[10rem] font-bold text-gray-300 leading-none mb-4">500</h1>
            <h2 class="text-3xl font-semibold text-red-400 mb-4">Server Error</h2>
            <p class="text-base text-gray-600 leading-relaxed mb-8 max-w-md mx-auto">
                Oops! Something went wrong on our end.<br>
                Please try again later or contact support if the problem persists.
            </p>
            <a href="{{ route('home') }}"
                class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-700 text-white rounded-lg font-medium hover:bg-emerald-500 transition-colors duration-200">
                Back to Home Page
            </a>
        </div>
    </div>
</body>

</html>