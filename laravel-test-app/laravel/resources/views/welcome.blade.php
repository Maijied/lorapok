<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lorapok Lab - Performance Testing Suite</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes larvae-float {
            0%, 100% { transform: translateY(0) rotate(0); }
            50% { transform: translateY(-10px) rotate(5deg); }
        }
        .larvae-animate { display: inline-block; animation: larvae-float 3s ease-in-out infinite; }
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .test-card:hover { transform: translateY(-5px); transition: all 0.3s ease; }
    </style>
</head>
<body class="bg-gradient-to-br from-indigo-100 via-purple-50 to-blue-100 min-h-screen font-sans antialiased text-gray-900">
    
    <div class="container mx-auto px-4 py-12 max-w-6xl">
        <!-- Header Section -->
        <header class="text-center mb-16">
            <div class="mb-4">
                <span class="text-7xl larvae-animate">🐛</span>
            </div>
            <h1 class="text-5xl font-extrabold tracking-tight mb-4">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-blue-600">
                    Lorapok Performance Lab
                </span>
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                The ultimate testing grounds for Laravel execution monitoring. Identify bottlenecks, earn achievements, and optimize your application.
            </p>
        </header>

        <!-- System Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
            <div class="glass-card p-6 rounded-3xl shadow-sm border-l-4 border-blue-500">
                <p class="text-xs font-bold text-blue-600 uppercase mb-1">Laravel</p>
                <p class="text-2xl font-bold">{{ app()->version() }}</p>
            </div>
            <div class="glass-card p-6 rounded-3xl shadow-sm border-l-4 border-purple-500">
                <p class="text-xs font-bold text-purple-600 uppercase mb-1">PHP Version</p>
                <p class="text-2xl font-bold">{{ PHP_VERSION }}</p>
            </div>
            <div class="glass-card p-6 rounded-3xl shadow-sm border-l-4 border-green-500">
                <p class="text-xs font-bold text-green-600 uppercase mb-1">Environment</p>
                <p class="text-2xl font-bold">{{ app()->environment() }}</p>
            </div>
            <div class="glass-card p-6 rounded-3xl shadow-sm border-l-4 border-pink-500">
                <p class="text-xs font-bold text-pink-600 uppercase mb-1">DB Driver</p>
                <p class="text-2xl font-bold">{{ ucfirst(config('database.default')) }}</p>
            </div>
        </div>

        <!-- Testing Scenarios -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Column 1: Core Performance -->
            <div class="space-y-6">
                <h3 class="text-lg font-bold flex items-center gap-2 px-2">
                    <span class="p-2 bg-blue-100 rounded-lg">⚡</span> Core Latency
                </h3>
                
                <a href="/lorapok-fast-v2" class="test-card block glass-card p-5 rounded-2xl shadow-sm hover:shadow-md border border-green-100 group">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-2xl">🚀</span>
                        <span class="text-[10px] bg-green-100 text-green-700 px-2 py-1 rounded-full font-bold">STABLE</span>
                    </div>
                    <h4 class="font-bold text-gray-800 group-hover:text-green-600 transition-colors">Ultra Fast Route</h4>
                    <p class="text-xs text-gray-500 mt-1">Minimal execution time (< 50ms) to verify baseline monitoring overhead.</p>
                </a>

                <a href="/lorapok-slow-v2" class="test-card block glass-card p-5 rounded-2xl shadow-sm hover:shadow-md border border-red-100 group">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-2xl">🐌</span>
                        <span class="text-[10px] bg-red-100 text-red-700 px-2 py-1 rounded-full font-bold">ALERT</span>
                    </div>
                    <h4 class="font-bold text-gray-800 group-hover:text-red-600 transition-colors">Deliberate Slow Route</h4>
                    <p class="text-xs text-gray-500 mt-1">Simulates a 2-second delay to trigger performance threshold alerts.</p>
                </a>
            </div>

            <!-- Column 2: Resource Stress -->
            <div class="space-y-6">
                <h3 class="text-lg font-bold flex items-center gap-2 px-2">
                    <span class="p-2 bg-purple-100 rounded-lg">🗄️</span> Resource Stress
                </h3>

                <a href="/lorapok/test/many-queries" class="test-card block glass-card p-5 rounded-2xl shadow-sm hover:shadow-md border border-orange-100 group">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-2xl">⚔️</span>
                        <span class="text-[10px] bg-orange-100 text-orange-700 px-2 py-1 rounded-full font-bold">N+1 TEST</span>
                    </div>
                    <h4 class="font-bold text-gray-800 group-hover:text-orange-600 transition-colors">Query Slayer Quest</h4>
                    <p class="text-xs text-gray-500 mt-1">Executes 150+ queries to test batch detection and earn the slayer badge.</p>
                </a>

                <a href="/lorapok/test/high-memory" class="test-card block glass-card p-5 rounded-2xl shadow-sm hover:shadow-md border border-purple-100 group">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-2xl">🧠</span>
                        <span class="text-[10px] bg-purple-100 text-purple-700 px-2 py-1 rounded-full font-bold">MEM SPIKE</span>
                    </div>
                    <h4 class="font-bold text-gray-800 group-hover:text-purple-600 transition-colors">Memory Master Quest</h4>
                    <p class="text-xs text-gray-500 mt-1">Allocates large data structures to verify memory peak tracking.</p>
                </a>
            </div>

            <!-- Column 3: Error Handling -->
            <div class="space-y-6">
                <h3 class="text-lg font-bold flex items-center gap-2 px-2">
                    <span class="p-2 bg-pink-100 rounded-lg">🔥</span> Chaos Engineering
                </h3>

                <a href="/lorapok/test/exception" class="test-card block glass-card p-5 rounded-2xl shadow-sm hover:shadow-md border border-red-200 group">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-2xl">💥</span>
                        <span class="text-[10px] bg-red-600 text-white px-2 py-1 rounded-full font-bold">CRASH</span>
                    </div>
                    <h4 class="font-bold text-gray-800 group-hover:text-red-700 transition-colors">Exception Capture</h4>
                    <p class="text-xs text-gray-500 mt-1">Triggers a hard exception to verify stack trace and alert logging.</p>
                </a>

                <a href="/lorapok/test/middleware" class="test-card block glass-card p-5 rounded-2xl shadow-sm hover:shadow-md border border-blue-200 group">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-2xl">🔗</span>
                        <span class="text-[10px] bg-blue-100 text-blue-700 px-2 py-1 rounded-full font-bold">TRAIT</span>
                    </div>
                    <h4 class="font-bold text-gray-800 group-hover:text-blue-600 transition-colors">Middleware Tracking</h4>
                    <p class="text-xs text-gray-500 mt-1">Uses the MeasuresMiddleware trait to track execution time of custom middleware.</p>
                </a>

                <div class="glass-card p-5 rounded-2xl shadow-sm border border-gray-100 opacity-60">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-2xl">🎨</span>
                        <span class="text-[10px] bg-gray-100 text-gray-500 px-2 py-1 rounded-full font-bold">UI ONLY</span>
                    </div>
                    <h4 class="font-bold text-gray-800">Widget Verification</h4>
                    <p class="text-xs text-gray-500 mt-1">Open the floating 🐛 button to verify real-time data synchronization.</p>
                </div>
            </div>

        </div>

        <!-- Footer -->
        <footer class="mt-20 pt-8 border-t border-gray-200 text-center text-gray-500">
            <p class="text-sm flex items-center justify-center gap-2">
                Designed for the Laravel Community <span class="text-red-500">❤️</span> by #MaJHiBhai
            </p>
            <p class="text-[10px] mt-2 uppercase tracking-widest font-bold">Lorapok Lab v1.2.5</p>
        </footer>
    </div>

    @include('execution-monitor::widget')
</body>
</html>
