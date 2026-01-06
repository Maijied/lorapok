<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Normal Page - Lorapok</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-yellow-50 min-h-screen flex items-center justify-center">
    <div class="text-center">
        <h1 class="text-6xl font-bold text-yellow-600 mb-4">📊 Normal Page Test</h1>
        <p class="text-2xl text-gray-700 mb-8">#MaJHiBhai - Regular speed page!</p>
        <p class="text-xl text-gray-600">Execution time: ~0.3 seconds</p>
        <p class="text-lg text-yellow-600 mt-4">Check the widget data! 👉</p>
    </div>
    
    @include('execution-monitor::widget')
</body>
</html>
