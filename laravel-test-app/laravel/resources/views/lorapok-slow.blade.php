<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Slow Page - Lorapok</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-red-50 min-h-screen flex items-center justify-center">
    <!-- Floating Home Button -->
    <a href="/" class="fixed top-6 left-6 z-[1000] flex items-center gap-2 bg-white bg-opacity-80 backdrop-blur-md px-4 py-2 rounded-2xl shadow-lg border border-white hover:scale-105 transition-all group">
        <span class="text-xl group-hover:rotate-12 transition-transform">🏠</span>
        <span class="text-sm font-bold text-gray-700">Back to Lab</span>
    </a>

    <div class="text-center">
        <h1 class="text-6xl font-bold text-red-600 mb-4">🐌 Slow Page Test</h1>
        <p class="text-2xl text-gray-700 mb-8">#MaJHiBhai - This page took 2+ seconds!</p>
        <p class="text-xl text-gray-600">Execution time: ~2 seconds (triggers threshold alert)</p>
        <p class="text-lg text-red-500 mt-4">Widget should show this in RED! 👉</p>
    </div>
    
</body>
</html>
