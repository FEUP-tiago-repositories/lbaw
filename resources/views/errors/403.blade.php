<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Unauthorized access</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 font-sans antialiased">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="text-center max-w-2xl">
            <!-- Error code -->
            <h1 class="text-[10rem] font-bold text-gray-300 leading-none mb-4">403</h1>
            <!-- Error Title -->
            <h2 class="text-3xl font-semibold text-red-400 mb-4">Unauthorized access</h2>

            <!-- Error Message -->
            <p class="text-base text-gray-600 leading-relaxed mb-8 max-w-md mx-auto">
                @if(isset($exception) && $exception->getMessage())
                    {{ $exception->getMessage() }}
                @else
                    Sorry, you don't have permission to access this page.
                @endif
            </p>

            <div class="flex gap-4 justify-center flex-wrap">
                <!-- Go back Button -->
                <button onclick="history.back()"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-white text-black rounded-lg font-medium hover:bg-gray-200 transition-colors duration-200 cursor-pointer border-2 border-gray-200">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 16 16">
                        <path
                            d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm3.5 7.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
                    </svg>
                    Go Back
                </button>

                <!-- Back to home -->
                <a href="{{ route('home') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-700 text-white rounded-lg font-medium hover:bg-emerald-500 transition-colors duration-200">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 16 16">
                        <path
                            d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5ZM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5 5 5Z" />
                    </svg>
                    Back to Home Page
                </a>

            </div>
        </div>
    </div>
</body>

</html>