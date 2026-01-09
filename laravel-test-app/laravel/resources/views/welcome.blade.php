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

            <!-- Column 3: Tools & Verification -->
            <div class="space-y-6">
                <h3 class="text-lg font-bold flex items-center gap-2 px-2">
                    <span class="p-2 bg-pink-100 rounded-lg">⚙️</span> Lab Tools
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

                <div class="test-card block glass-card p-5 rounded-2xl shadow-sm border border-purple-200 relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 text-6xl opacity-10 group-hover:scale-110 transition-transform">🐛</div>
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-2xl">🧪</span>
                        <span class="text-[10px] bg-purple-600 text-white px-2 py-1 rounded-full font-bold">VERIFIED</span>
                    </div>
                    <h4 class="font-bold text-gray-800">Widget Live Sync</h4>
                    <p class="text-xs text-gray-500 mt-1 mb-4">The floating larvae button is active. All lab actions are recorded in real-time.</p>
                    <div class="flex gap-2">
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                        <span class="text-[10px] font-bold text-green-600 uppercase">System Ready</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Professional Footer -->
        <footer class="mt-24 pt-12 border-t border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 items-center">
                <div class="text-left">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="text-3xl">🐛</span>
                        <span class="text-xl font-black tracking-tighter text-purple-600">LORAPOK</span>
                    </div>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Zero-configuration performance monitoring for modern Laravel applications. Built for speed, designed for developers.
                    </p>
                </div>

                <div class="glass-card p-6 rounded-3xl flex items-center gap-4 border border-purple-100">
                    <img src="https://github.com/Maijied.png" alt="Maizied" class="w-16 h-16 rounded-2xl shadow-lg border-2 border-white">
                    <div>
                        <p class="text-xs font-bold text-purple-600 uppercase tracking-widest">Lead Architect</p>
                        <h4 class="text-lg font-bold text-gray-800">Maizied Hasan</h4>
                        <p class="text-[10px] text-gray-400 font-mono">#MaJHiBhai</p>
                    </div>
                </div>

                <div class="flex flex-col items-end gap-3">
                    <a href="https://github.com/Maijied/lorapok" target="_blank" class="flex items-center gap-2 bg-gray-900 text-white px-5 py-2.5 rounded-xl hover:bg-gray-800 transition shadow-xl group">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                        <span class="text-sm font-bold">Star on GitHub</span>
                    </a>
                    <div class="flex gap-4 pr-2">
                        <a href="https://linkedin.com/in/maizied/" target="_blank" class="text-gray-400 hover:text-blue-600 transition">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                        </a>
                        <a href="mailto:mdshuvo40@gmail.com" class="text-gray-400 hover:text-purple-600 transition">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="text-center mt-12">
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-black">Powered by Lorapok v1.2.7-Advanced</p>
            </div>
        </footer>
    </div>

    @include('execution-monitor::widget')
</body>
</html>
