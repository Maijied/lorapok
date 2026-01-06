<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lorapok Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-20">
        <h1 class="text-4xl font-bold text-purple-600 mb-4">🐛 Lorapok Monitor Test</h1>
        <p class="text-xl mb-8">#MaJHiBhai - Look for the purple button! 👉</p>
        
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-semibold mb-4">Test Info:</h2>
            <ul class="space-y-2">
                <li>✅ Laravel Version: {{ app()->version() }}</li>
                <li>✅ Environment: {{ app()->environment() }}</li>
                <li>✅ Monitor Enabled: {{ config('execution-monitor.enabled', 'not set') }}</li>
                <li>✅ Widget Feature: {{ config('execution-monitor.features.widget', 'not set') }}</li>
            </ul>
        </div>
    </div>

    {{-- FORCE INCLUDE WIDGET WITHOUT CONDITION --}}
    @include('execution-monitor::widget')
</body>
</html>
