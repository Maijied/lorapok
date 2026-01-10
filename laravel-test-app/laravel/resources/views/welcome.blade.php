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
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8" x-data="ajaxStorm()">
            
            <!-- Column 1: Core Performance -->
            <div class="space-y-6">
                <h3 class="text-lg font-bold flex items-center gap-2 px-2">
                    <span class="p-2 bg-blue-100 rounded-lg">⚡</span> Core Latency
                </h3>
                
                <button @click="triggerStorm()" class="test-card w-full text-left block glass-card p-5 rounded-3xl shadow-sm hover:shadow-xl border border-purple-200 group relative overflow-hidden transition-all">
                    <div class="absolute -right-2 -top-2 text-5xl opacity-10 group-hover:scale-110 transition-transform">🌪️</div>
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-2xl">⚡</span>
                        <span class="text-[10px] bg-purple-600 text-white px-2 py-1 rounded-full font-bold uppercase tracking-wider">Complex</span>
                    </div>
                    <h4 class="font-bold text-gray-800 group-hover:text-purple-600 transition-colors">Ajax Storm Scenario</h4>
                    <p class="text-xs text-gray-500 mt-1 leading-relaxed">Triggers 5 simultaneous AJAX calls to test multi-route tracking and view path resolution.</p>
                </button>

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
                        <span class="text-[10px] bg-blue-100 text-blue-700 px-2 py-1 rounded-full font-bold uppercase tracking-wider">Trait</span>
                    </div>
                    <h4 class="font-bold text-gray-800 group-hover:text-blue-600 transition-colors">Middleware Tracking</h4>
                    <p class="text-xs text-gray-500 mt-1 leading-relaxed">Uses the MeasuresMiddleware trait to track execution time of custom middleware.</p>
                </a>

                <div class="grid grid-cols-2 gap-4">
                    <a href="/lorapok/lab/advanced/heavy-json" class="test-card block glass-card p-4 rounded-2xl shadow-sm hover:shadow-md border border-orange-100 group">
                        <span class="text-xl">📦</span>
                        <h4 class="text-[11px] font-black text-gray-800 mt-2 uppercase">Heavy JSON</h4>
                    </a>
                    <a href="/lorapok/lab/advanced/cache-test" class="test-card block glass-card p-4 rounded-2xl shadow-sm hover:shadow-md border border-emerald-100 group">
                        <span class="text-xl">💾</span>
                        <h4 class="text-[11px] font-black text-gray-800 mt-2 uppercase">Cache Sim</h4>
                    </a>
                    <a href="/lorapok/lab/advanced/redirect-loop" class="test-card block glass-card p-4 rounded-2xl shadow-sm hover:shadow-md border border-indigo-100 group">
                        <span class="text-xl">🔄</span>
                        <h4 class="text-[11px] font-black text-gray-800 mt-2 uppercase">Redirects</h4>
                    </a>
                    <a href="/lorapok/lab/advanced/batch-db" class="test-card block glass-card p-4 rounded-2xl shadow-sm hover:shadow-md border border-pink-100 group">
                        <span class="text-xl">🗄️</span>
                        <h4 class="text-[11px] font-black text-gray-800 mt-2 uppercase">Batch DB</h4>
                    </a>
                </div>

                <!-- Redesigned Verification Card -->
                <div class="test-card block glass-card p-6 rounded-3xl shadow-sm border border-purple-200 relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 text-7xl opacity-10 group-hover:scale-110 transition-transform">🐛</div>
                    <div class="flex justify-between items-start mb-4">
                        <span class="p-3 bg-purple-100 rounded-2xl text-2xl">🧪</span>
                        <span class="text-[10px] bg-purple-600 text-white px-3 py-1 rounded-full font-black uppercase tracking-widest">Verified</span>
                    </div>
                    <h4 class="text-xl font-black text-gray-900 tracking-tight mb-2">Widget Live Sync</h4>
                    <p class="text-xs text-gray-500 mb-6 leading-relaxed">The floating larvae button is active. All performance metrics are broadcasted in real-time to the dashboard.</p>
                    <div class="flex items-center gap-3 bg-white bg-opacity-50 p-3 rounded-2xl border border-white/50">
                        <span class="flex h-3 w-3 relative">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                        </span>
                        <span class="text-[10px] font-black text-green-700 uppercase tracking-[0.2em]">System Operational</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Premium Footer Section -->
        <footer class="mt-32 pb-12">
            <div class="glass-card rounded-[3.5rem] p-12 border border-white shadow-2xl relative overflow-hidden">
                <!-- Decorative background elements -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-purple-200/20 blur-3xl rounded-full -mr-32 -mt-32"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-200/20 blur-3xl rounded-full -ml-32 -mb-32"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-16">
                    <!-- Brand Section -->
                    <div class="max-w-md text-center md:text-left">
                        <div class="flex items-center justify-center md:justify-start gap-4 mb-8">
                            <span class="text-5xl larvae-animate">🐛</span>
                            <span class="text-3xl font-black tracking-tighter text-gray-900">LORAPOK</span>
                        </div>
                        <p class="text-base text-gray-500 leading-relaxed mb-10">
                            The premium performance monitoring suite for modern Laravel developers. Zero configuration, absolute visibility, and beautiful execution stories.
                        </p>
                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-4">
                            <a href="https://github.com/Maijied/lorapok" target="_blank" class="bg-gray-900 text-white px-8 py-4 rounded-2xl hover:bg-gray-800 transition shadow-2xl flex items-center gap-3 text-sm font-black tracking-wide">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                                GitHub
                            </a>
                            <div class="flex gap-3">
                                <a href="https://linkedin.com/in/maizied/" target="_blank" class="w-14 h-14 flex items-center justify-center rounded-2xl bg-white border border-gray-100 text-gray-400 hover:text-blue-600 hover:border-blue-200 transition-all shadow-lg">
                                    <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                </a>
                                <a href="mailto:mdshuvo40@gmail.com" class="w-14 h-14 flex items-center justify-center rounded-2xl bg-white border border-gray-100 text-gray-400 hover:text-purple-600 hover:border-purple-200 transition-all shadow-lg">
                                    <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Author Profile Card -->
                    <div class="flex items-center gap-8 bg-gradient-to-br from-purple-600 to-indigo-800 p-1 rounded-[3rem] shadow-2xl shadow-purple-200 transition-transform hover:scale-[1.02] duration-500">
                        <div class="bg-white rounded-[2.75rem] p-8 flex items-center gap-6">
                            <div class="relative">
                                <img src="https://github.com/Maijied.png" alt="Maizied" class="w-24 h-24 rounded-[2rem] shadow-2xl object-cover ring-4 ring-purple-50">
                                <div class="absolute -bottom-1 -right-1 bg-green-500 border-4 border-white w-7 h-7 rounded-full shadow-lg"></div>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-purple-600 uppercase tracking-[0.3em] mb-2">Lead Architect</p>
                                <h4 class="text-2xl font-black text-gray-900 tracking-tighter">Maizied Hasan</h4>
                                <div class="flex items-center gap-3 mt-2">
                                    <span class="text-xs font-bold text-gray-400 font-mono">#MaJHiBhai</span>
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-200"></span>
                                    <span class="text-[10px] font-black text-indigo-500 uppercase tracking-widest">Available for Hire</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Bottom Metadata -->
                <div class="mt-20 pt-10 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-6">
                    <p class="text-[10px] text-gray-400 uppercase tracking-[0.2em] font-bold">© 2026 Lorapok Laboratory · Dhaka, Bangladesh</p>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-purple-400 animate-pulse"></span>
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-tighter">v1.3.4-Advanced</span>
                        </div>
                        <span class="h-4 w-px bg-gray-200"></span>
                        <span class="text-[10px] font-black text-purple-500 uppercase tracking-widest">Full Production Build</span>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    @include('execution-monitor::widget')

    <!-- Ajax Storm Modal -->
    <div x-data="ajaxStorm()" x-show="isOpen" x-cloak class="fixed inset-0 z-[12000] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-gray-900/80 backdrop-blur-sm" @click="isOpen = false"></div>
        <div class="relative bg-white rounded-[2.5rem] shadow-2xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[80vh]">
            <div class="bg-gradient-to-r from-purple-600 to-indigo-700 p-8 text-white">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-2xl font-black tracking-tight">AJAX Storm Results</h2>
                        <p class="text-purple-100 text-xs mt-1 uppercase tracking-widest font-bold">Execution Monitoring in Progress</p>
                    </div>
                    <button @click="isOpen = false" class="bg-white/10 hover:bg-white/20 w-10 h-10 rounded-full flex items-center justify-center transition-colors">×</button>
                </div>
            </div>

            <div class="p-8 overflow-y-auto flex-1">
                <div class="space-y-4">
                    <template x-for="(res, idx) in results" :key="idx">
                        <div class="bg-gray-50 border border-gray-100 p-4 rounded-2xl flex items-center justify-between group hover:border-purple-200 transition-all">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-lg">
                                    <span x-show="res.type==='Database'">🗄️</span>
                                    <span x-show="res.type==='Logging'">📝</span>
                                    <span x-show="res.type==='Processing'">⚙️</span>
                                    <span x-show="res.type==='Metadata'">🏷️</span>
                                    <span x-show="res.type==='View'">🖼️</span>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-purple-600 uppercase tracking-widest" x-text="res.type"></p>
                                    <p class="text-sm font-bold text-gray-800" x-text="res.detail || 'Success'"></p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] bg-green-100 text-green-700 px-2 py-1 rounded-lg font-black tracking-tighter">200 OK</span>
                            </div>
                        </div>
                    </template>

                    <div x-show="loading" class="text-center py-12">
                        <div class="larvae-spin inline-block text-4xl mb-4">🌀</div>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-[0.2em]">Executing Concurrent Calls...</p>
                    </div>
                </div>
            </div>

            <div class="p-8 bg-gray-50 border-t border-gray-100 flex justify-center">
                <button @click="triggerStorm()" :disabled="loading" class="bg-purple-600 text-white px-8 py-3 rounded-2xl font-bold shadow-lg shadow-purple-200 hover:bg-purple-700 disabled:opacity-50 transition-all transform active:scale-95">
                    Re-trigger Storm ⚡
                </button>
            </div>
        </div>
    </div>

    <script>
        function ajaxStorm() {
            return {
                isOpen: false,
                loading: false,
                results: [],
                async triggerStorm() {
                    this.isOpen = true;
                    this.loading = true;
                    this.results = [];
                    
                    const endpoints = [
                        '/lorapok/lab/ajax/queries',
                        '/lorapok/lab/ajax/logs',
                        '/lorapok/lab/ajax/process',
                        '/lorapok/lab/ajax/meta',
                        '/lorapok/lab/ajax/view'
                    ];

                    try {
                        const responses = await Promise.all(endpoints.map(url => fetch(url).then(r => r.json().catch(() => ({type: 'View', detail: 'Rendered welcome fragment'})))));
                        this.results = responses;
                    } catch (e) {
                        console.error('Storm Failed:', e);
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }
    </script>
</body>
</html>
