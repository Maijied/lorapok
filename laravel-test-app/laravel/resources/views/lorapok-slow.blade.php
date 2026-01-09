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
    <div class="text-center">
        <h1 class="text-6xl font-bold text-red-600 mb-4">🐌 Slow Page Test</h1>
        <p class="text-2xl text-gray-700 mb-8">#MaJHiBhai - This page took 2+ seconds!</p>
        <p class="text-xl text-gray-600">Execution time: ~2 seconds (triggers threshold alert)</p>
        <p class="text-lg text-red-500 mt-4">Widget should show this in RED! 👉</p>
    </div>
    
</body>
</html>
