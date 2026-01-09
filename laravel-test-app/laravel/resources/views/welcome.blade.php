<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lorapok - Laravel Execution Monitor</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-purple-50 to-blue-50 min-h-screen">
    <div class="container mx-auto px-4 py-20">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <span class="text-6xl">🐛</span>
                <h1 class="text-6xl font-bold text-purple-600 mb-6">
                    Lorapok Monitor
                </h1>
                <p class="text-2xl text-gray-700 mb-4 font-semibold">
                    #MaJHiBhai - Your Laravel Performance Companion
                </p>
                <p class="text-xl text-gray-600 mb-8">
                    Look for the purple floating button in the bottom-right corner! 👉
                </p>
            </div>
            
            <div class="bg-white rounded-2xl shadow-2xl p-8 mb-8">
                <h2 class="text-3xl font-semibold mb-6 text-gray-800">📊 System Info</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <p class="text-sm text-blue-600 font-medium">Laravel Version</p>
                        <p class="text-2xl font-bold text-blue-900">{{ app()->version() }}</p>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4">
                        <p class="text-sm text-green-600 font-medium">Environment</p>
                        <p class="text-2xl font-bold text-green-900">{{ app()->environment() }}</p>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-4">
                        <p class="text-sm text-purple-600 font-medium">Monitor Status</p>
                        <p class="text-2xl font-bold text-purple-900">✅ Active</p>
                    </div>
                    <div class="bg-pink-50 rounded-lg p-4">
                        <p class="text-sm text-pink-600 font-medium">Widget Status</p>
                        <p class="text-2xl font-bold text-pink-900">🟣 Loaded</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-2xl p-8">
                <h2 class="text-3xl font-semibold mb-6 text-gray-800">🧪 Test Pages</h2>
                <div class="space-y-3">
                    <a href="/lorapok-fast-v2" class="block bg-green-500 hover:bg-green-600 text-white font-bold py-4 px-6 rounded-lg text-center transition">
                        ⚡ Fast Page Test (< 200ms)
                    </a>
                    <a href="/lorapok-test-v2" class="block bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-4 px-6 rounded-lg text-center transition">
                        📊 Normal Page Test (200-1000ms)
                    </a>
                    <a href="/lorapok-slow-v2" class="block bg-red-500 hover:bg-red-600 text-white font-bold py-4 px-6 rounded-lg text-center transition">
                        🐌 Slow Page Test (> 1000ms)
                    </a>
                </div>
            </div>

            <div class="mt-12 text-center text-gray-600">
                <p class="text-lg">Made with ❤️ by the Lorapok Team</p>
                <p class="text-sm mt-2">#MaJHiBhai 🐛</p>
            </div>
        </div>
    </div>

    @include('execution-monitor::widget')
</body>
</html>
