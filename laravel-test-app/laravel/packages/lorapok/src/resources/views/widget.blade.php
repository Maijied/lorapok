<div id="execution-monitor-widget" x-data="monitorWidget()" x-cloak>
    <!-- Main Larvae Button -->
    <button @click="toggleModal()" class="lorapok-btn" style="position:fixed;bottom:20px;right:20px;z-index:9999;width:80px;height:80px;border:none;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);border-radius:50%;cursor:pointer;box-shadow:0 8px 20px rgba(102,126,234,0.4);display:flex;align-items:center;justify-content:center;font-size:42px;transition:all 0.3s ease">
        🐛
        <span x-show="hasAlerts" style="position:absolute;top:-5px;right:-5px;width:28px;height:28px;background:#ef4444;border-radius:50%;color:white;font-size:16px;font-weight:bold;display:flex;align-items:center;justify-content:center;animation:pulse 1.5s infinite;border:3px solid white">!</span>
    </button>

    <!-- Main Monitor Modal -->
    <div x-show="isOpen" x-transition class="fixed inset-0 z-[10000] flex items-center justify-center" style="display:none">
        <div class="absolute inset-0 bg-gray-900 bg-opacity-75" @click="closeModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-6xl max-h-[90vh] flex flex-col overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-purple-600 to-blue-600 px-6 py-4 flex items-center justify-between shrink-0">
                <div>
                    <h2 class="text-xl font-bold text-white flex items-center gap-2"><span class="text-2xl">🐛</span> Lorapok Monitor</h2>
                    <p class="text-purple-100 text-sm">#MaJHiBhai</p>
                </div>

                <div class="flex items-center gap-3">
                    <button title="Optimization Quests" @click.stop="activeTab='quests'" 
                        :class="activeTab==='quests' ? 'bg-amber-500/30 ring-2 ring-amber-400/50 scale-105' : 'bg-white/10 hover:bg-amber-500/20'" 
                        class="modal-action-btn" style="width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;transition:all 0.3s cubic-bezier(0.4, 0, 0.2, 1);">
                        <span class="text-xl larvae-animate" style="filter: drop-shadow(0 0 8px rgba(245, 158, 11, 0.4))">🏆</span>
                    </button>

                    <button title="Developer Info" @click.stop="toggleDev()" 
                        :class="showDevInfo ? 'bg-emerald-500/30 ring-2 ring-emerald-400/50 scale-105' : 'bg-white/10 hover:bg-emerald-500/20'" 
                        class="modal-action-btn" style="width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;transition:all 0.3s cubic-bezier(0.4, 0, 0.2, 1);">
                        <svg viewBox="0 0 48 48" class="larvae-wiggle" style="width:22px;height:22px; filter: drop-shadow(0 0 8px rgba(16, 185, 129, 0.4))">
                            <g fill="none" stroke="#fff" stroke-width="1.5">
                                <path d="M12 28c2-8 12-10 18-6 6 4 6 12 0 16-6 4-16 4-18-4" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="22" cy="18" r="3" fill="#fff" />
                            </g>
                        </svg>
                    </button>

                    <button title="Settings" @click.stop="toggleSettings()" 
                        :class="openSettings ? 'bg-indigo-500/30 ring-2 ring-indigo-400/50 scale-105' : 'bg-white/10 hover:bg-indigo-500/20'" 
                        class="modal-action-btn" style="width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;transition:all 0.3s cubic-bezier(0.4, 0, 0.2, 1);">
                        <svg viewBox="0 0 24 24" class="larvae-spin" style="width:18px;height:18px; filter: drop-shadow(0 0 8px rgba(99, 102, 241, 0.4))">
                            <path fill="#fff" d="M12 15.5A3.5 3.5 0 1 0 12 8.5a3.5 3.5 0 0 0 0 7z"/>
                            <path fill="#fff" d="M19.4 15a7.94 7.94 0 0 0 .1-1 7.94 7.94 0 0 0-.1-1l2.1-1.6a.5.5 0 0 0 .1-.6l-2-3.5a.5.5 0 0 0-.6-.2l-2.5 1a8.1 8.1 0 0 0-1.7-1l-.4-2.7A.5.5 0 0 0 12.6 3h-4a.5.5 0 0 0-.5.4l-.4 2.7a8.1 8.1 0 0 0-1.7 1l-2.5-1a.5.5 0 0 0-.6.2l-2 3.5a.5.5 0 0 0 .1.6L4.5 12a7.94 7.94 0 0 0-.1 1c0 .3 0 .7.1 1L2.4 15.6a.5.5 0 0 0-.1.6l2 3.5a.5.5 0 0 0 .6.2l2.5-1a8.1 8.1 0 0 0 1.7 1l.4 2.7c.05.3.3.5.6.5h4c.3 0 .55-.2.6-.5l.4-2.7a8.1 8.1 0 0 0 1.7-1l2.5 1c.25.1.54 0 .6-.2l2-3.5a.5.5 0 0 0-.1-.6L19.4 15z"/>
                        </svg>
                    </button>

                    <button title="Terminal" @click.stop="toggleTerminal()" 
                        :class="openTerminal ? 'bg-gray-700/50 ring-2 ring-gray-400/50 scale-105' : 'bg-white/10 hover:bg-gray-700/30'" 
                        class="modal-action-btn" style="width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;transition:all 0.3s cubic-bezier(0.4, 0, 0.2, 1);">
                        <span class="text-xl">💻</span>
                    </button>

                    <button title="Usage Guide" @click.stop="toggleUsage()" 
                        :class="showUsage ? 'bg-pink-500/30 ring-2 ring-pink-400/50 scale-105' : 'bg-white/10 hover:bg-pink-500/20'" 
                        class="modal-action-btn" style="width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;transition:all 0.3s cubic-bezier(0.4, 0, 0.2, 1);">
                        <span class="text-xl">📘</span>
                    </button>

                    <button @click="closeModal()" class="text-white hover:text-purple-200 text-3xl ml-2 transition-colors" style="background:transparent;border:none;padding:0 6px">×</button>
                </div>
            </div>
            
            <!-- Navigation -->
            <div class="border-b border-gray-200 bg-gray-50 flex justify-center shrink-0">
                <nav class="flex space-x-1 px-6">
                    <button @click="activeTab='overview'" :class="activeTab==='overview'?'border-purple-500 text-purple-600 shadow-[inset_0_-2px_0_rgba(168,85,247,1)]':'border-transparent text-gray-500 hover:text-gray-700'" class="px-4 py-4 text-xs font-black uppercase tracking-widest transition-all">📊 Overview</button>
                    <button @click="activeTab='activity'" :class="activeTab==='activity'?'border-purple-500 text-purple-600 shadow-[inset_0_-2px_0_rgba(168,85,247,1)]':'border-transparent text-gray-500 hover:text-gray-700'" class="px-4 py-4 text-xs font-black uppercase tracking-widest transition-all flex items-center gap-2">
                        📡 Activity
                        <span x-show="sessionHistory.length" class="bg-purple-100 text-purple-600 px-1.5 py-0.5 rounded-md text-[8px]" x-text="sessionHistory.length"></span>
                    </button>
                    <button @click="activeTab='timeline'" :class="activeTab==='timeline'?'border-purple-500 text-purple-600 shadow-[inset_0_-2px_0_rgba(168,85,247,1)]':'border-transparent text-gray-500 hover:text-gray-700'" class="px-4 py-4 text-xs font-black uppercase tracking-widest transition-all">🐛 Timeline</button>
                    <button @click="activeTab='routes'" :class="activeTab==='routes'?'border-purple-500 text-purple-600 shadow-[inset_0_-2px_0_rgba(168,85,247,1)]':'border-transparent text-gray-500 hover:text-gray-700'" class="px-4 py-4 text-xs font-black uppercase tracking-widest transition-all">🛣️ Routes</button>
                    <button @click="activeTab='queries'" :class="activeTab==='queries'?'border-purple-500 text-purple-600 shadow-[inset_0_-2px_0_rgba(168,85,247,1)]':'border-transparent text-gray-500 hover:text-gray-700'" class="px-4 py-4 text-xs font-black uppercase tracking-widest transition-all">🗄️ Queries</button>
                    <button @click="activeTab='middleware'" :class="activeTab==='middleware'?'border-purple-500 text-purple-600 shadow-[inset_0_-2px_0_rgba(168,85,247,1)]':'border-transparent text-gray-500 hover:text-gray-700'" class="px-4 py-4 text-xs font-black uppercase tracking-widest transition-all">🔗 Middleware</button>
                    <button @click="activeTab='logs'" :class="activeTab==='logs'?'border-purple-500 text-purple-600 shadow-[inset_0_-2px_0_rgba(168,85,247,1)]':'border-transparent text-gray-500 hover:text-gray-700'" class="px-4 py-4 text-xs font-black uppercase tracking-widest transition-all">📝 Logs</button>
                    <button @click="activeTab='playground'" :class="activeTab==='playground'?'border-purple-500 text-purple-600 shadow-[inset_0_-2px_0_rgba(168,85,247,1)]':'border-transparent text-gray-500 hover:text-gray-700'" class="px-4 py-4 text-xs font-black uppercase tracking-widest transition-all">🎮 Playground</button>
                </nav>
            </div>

            <!-- Content Area -->
            <div class="p-6 overflow-y-auto flex-1">
                <div x-show="activeTab==='overview'">@include('execution-monitor::tabs.overview')</div>
                
                <!-- Session Activity Tab -->
                <div x-show="activeTab==='activity'" class="space-y-6" x-data="{ activitySearch: '' }">
                    <div class="text-center">
                        <h3 class="text-lg font-black text-gray-900 uppercase tracking-[0.2em]">Session Activity</h3>
                        <div class="h-1 w-12 bg-blue-500 mx-auto mt-2 rounded-full"></div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-4">All routes executed in this session</p>
                    </div>

                    <div class="flex justify-center mb-4">
                        <div class="relative w-full max-w-md group">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">🔍</span>
                            <input x-model="activitySearch" type="text" placeholder="Filter session routes..." class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-xs focus:ring-2 focus:ring-blue-500 outline-none transition-all shadow-sm" />
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-[2rem] overflow-hidden shadow-sm">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase text-gray-400 tracking-widest">Route & Method</th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase text-gray-400 tracking-widest">Performance</th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase text-gray-400 tracking-widest">Resources</th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase text-gray-400 tracking-widest text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <template x-for="item in sessionHistory.filter(i => (i.request?.path || '').toLowerCase().includes(activitySearch.toLowerCase()))" :key="item.fingerprint">
                                    <tr class="hover:bg-blue-50/30 transition-colors group">
                                        <td class="px-6 py-4">
                                            <!-- Route Info -->
                                            <div class="flex items-center gap-3 mb-3">
                                                <div class="flex items-center gap-1.5 shrink-0">
                                                    <span class="text-[8px] font-black text-gray-400 uppercase tracking-tighter">Route</span>
                                                    <span class="text-[10px] font-black px-2 py-0.5 rounded bg-gray-900 text-white shadow-sm" x-text="item.request.method"></span>
                                                </div>
                                                <span class="text-xs font-black text-gray-900 font-mono tracking-tighter bg-white px-2 py-1 rounded-lg border border-gray-100 shadow-inner truncate max-w-[250px]" x-text="item.request.path" :title="item.request.path"></span>
                                            </div>

                                            <!-- Execution Context -->
                                            <div class="flex flex-col gap-2">
                                                <!-- Action Logic -->
                                                <div class="flex items-center gap-2" x-show="item.controller_action">
                                                    <div class="flex items-center gap-1.5 shrink-0">
                                                        <template x-if="item.controller_action.includes('@')">
                                                            <span class="text-[8px] bg-purple-600 text-white px-2 py-0.5 rounded-md font-black uppercase tracking-widest shadow-sm">Controller</span>
                                                        </template>
                                                        <template x-if="!item.controller_action.includes('@')">
                                                            <span class="text-[8px] bg-indigo-600 text-white px-2 py-0.5 rounded-md font-black uppercase tracking-widest shadow-sm">Function</span>
                                                        </template>
                                                    </div>
                                                    <code class="text-[10px] text-purple-700 font-black font-mono truncate max-w-[300px]" x-text="item.controller_action" :title="item.controller_action"></code>
                                                </div>

                                                <!-- View Logic -->
                                                <div class="flex items-center gap-2" x-show="item.view_path">
                                                    <div class="flex items-center gap-1.5 shrink-0">
                                                        <span class="text-[8px] bg-blue-600 text-white px-2 py-0.5 rounded-md font-black uppercase tracking-widest shadow-sm">Blade Path</span>
                                                    </div>
                                                    <code class="text-[10px] text-blue-700 font-black font-mono truncate max-w-[300px]" x-text="item.view_path" :title="item.view_path"></code>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <span class="w-2 h-2 rounded-full" :class="item.request.duration > 1 ? 'bg-red-500' : 'bg-green-500'"></span>
                                                <span class="text-xs font-black text-gray-700" x-text="(item.request.duration * 1000).toFixed(0) + 'ms'"></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex gap-3">
                                                <div class="flex flex-col">
                                                    <span class="text-[8px] font-black text-gray-400 uppercase">Queries</span>
                                                    <span class="text-xs font-bold text-purple-600" x-text="item.total_queries"></span>
                                                </div>
                                                <div class="flex flex-col">
                                                    <span class="text-[8px] font-black text-gray-400 uppercase">Memory</span>
                                                    <span class="text-xs font-bold text-emerald-600" x-text="item.memory_peak"></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <button @click="data = item; activeTab = 'overview'" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-[10px] font-black text-blue-600 hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all shadow-sm active:scale-95 uppercase tracking-tighter">Inspect</button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div x-show="activeTab==='timeline'">@include('execution-monitor::tabs.timeline')</div>
                <div x-show="activeTab==='routes'">@include('execution-monitor::tabs.routes')</div>
                <div x-show="activeTab==='queries'">@include('execution-monitor::tabs.queries')</div>
                <div x-show="activeTab==='middleware'">@include('execution-monitor::tabs.middleware')</div>
                <div x-show="activeTab==='quests'">@include('execution-monitor::tabs.achievements')</div>
                
                <!-- Logs Tab -->
                <div x-show="activeTab==='logs'" class="space-y-6" x-data="{ 
                    logMode: 'client',
                    logSearch: '',
                    logLevel: 'all',
                    logDate: new Date().toISOString().split('T')[0],
                    page: 1,
                    perPage: 10,
                    get filteredLogs() {
                        let logs = this.logMode === 'client' ? (this.consoleLogs || []) : (this.data?.server_logs || []);
                        const s = this.logSearch.toLowerCase();
                        const l = this.logLevel.toLowerCase();
                        
                        return logs.filter(log => {
                            const matchSearch = (log.msg || '').toLowerCase().includes(s) || (log.level || '').toLowerCase().includes(s);
                            const matchLevel = l === 'all' || (log.level || '').toLowerCase() === l;
                            return matchSearch && matchLevel;
                        });
                    },
                    get paginatedLogs() {
                        const start = (this.page - 1) * this.perPage;
                        return this.filteredLogs.slice(start, start + this.perPage);
                    },
                    get totalPages() {
                        return Math.ceil(this.filteredLogs.length / this.perPage) || 1;
                    }
                }">
                    <!-- Centered Title -->
                    <div class="text-center">
                        <h3 class="text-lg font-black text-gray-900 uppercase tracking-[0.2em]">Application Logs</h3>
                        <div class="h-1 w-12 bg-purple-500 mx-auto mt-2 rounded-full"></div>
                    </div>

                    <!-- Centered Controls -->
                    <div class="flex flex-col items-center gap-4">
                        <div class="flex flex-wrap items-center justify-center gap-3">
                            <!-- Toggle -->
                            <div class="flex bg-gray-100 p-1 rounded-2xl border border-gray-200 shadow-inner">
                                <button @click="logMode = 'client'; page = 1" :class="logMode === 'client' ? 'bg-white text-purple-600 shadow-md scale-105' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-2 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all duration-300">Client</button>
                                <button @click="logMode = 'server'; page = 1" :class="logMode === 'server' ? 'bg-white text-purple-600 shadow-md scale-105' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-2 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all duration-300 flex items-center gap-2">
                                    Server
                                    <span x-show="(data?.server_logs || []).length" class="bg-purple-100 text-purple-600 px-2 py-0.5 rounded-lg text-[9px]" x-text="(data?.server_logs || []).length"></span>
                                </button>
                            </div>

                            <!-- Level Filter -->
                            <div class="relative">
                                <select x-model="logLevel" @change="page = 1" class="appearance-none pl-4 pr-10 py-2.5 bg-white border border-gray-200 rounded-2xl text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-purple-500 outline-none cursor-pointer transition-all shadow-sm">
                                    <option value="all">All Levels</option>
                                    <option value="error">Error</option>
                                    <option value="warning">Warning</option>
                                    <option value="info">Info</option>
                                    <option value="debug">Debug</option>
                                </select>
                                <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">▼</span>
                            </div>

                            <!-- Date Filter (Server Only) -->
                            <div x-show="logMode === 'server'" class="relative">
                                <input type="date" x-model="logDate" @change="page = 1; fetchData()" class="pl-4 pr-4 py-2.5 bg-white border border-gray-200 rounded-2xl text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-purple-500 outline-none cursor-pointer transition-all shadow-sm" />
                            </div>
                        </div>

                        <div class="flex items-center gap-3 w-full max-w-2xl">
                            <!-- Search -->
                            <div class="relative flex-1 group">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">🔍</span>
                                <input x-model="logSearch" @input="page = 1" type="text" placeholder="Search entries, stack traces, timestamps..." class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs focus:ring-2 focus:ring-purple-500 outline-none transition-all shadow-sm" />
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-2">
                                <button @click="copyAllLogs(logMode)" class="px-5 py-3 bg-gray-900 text-white rounded-2xl text-xs font-bold shadow-lg hover:bg-gray-800 transition-all flex items-center gap-2 active:scale-95">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                    Copy
                                </button>
                                <button @click="logMode === 'client' ? clearLogs() : clearServerLogs()" class="px-5 py-3 bg-white text-red-600 border border-red-100 rounded-2xl text-xs font-bold shadow-sm hover:bg-red-50 transition-all active:scale-95">
                                    Clear
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Unified Logs Table -->
                    <div class="space-y-4">
                        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-4 py-3 text-[10px] font-black uppercase text-gray-400 tracking-widest w-48">Timestamp</th>
                                        <th class="px-4 py-3 text-[10px] font-black uppercase text-gray-400 tracking-widest w-24">Level</th>
                                        <th class="px-4 py-3 text-[10px] font-black uppercase text-gray-400 tracking-widest">Message</th>
                                        <th class="px-4 py-3 text-[10px] font-black uppercase text-gray-400 tracking-widest w-12 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <template x-for="(log, idx) in paginatedLogs" :key="idx">
                                        <tr @click="expanded = !expanded" class="hover:bg-gray-50/50 transition-colors group cursor-pointer" x-data="{ expanded: false }">
                                            <td class="px-4 py-3 text-[10px] font-mono text-gray-500 whitespace-nowrap" x-text="new Date(log.at).toLocaleString()"></td>
                                            <td class="px-4 py-3">
                                                <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-tighter" :class="{
                                                    'bg-red-100 text-red-600': ['error','critical','alert','emergency'].includes(log.level.toLowerCase()),
                                                    'bg-orange-100 text-orange-600': ['warning','notice','warn'].includes(log.level.toLowerCase()),
                                                    'bg-blue-100 text-blue-600': log.level.toLowerCase() === 'info',
                                                    'bg-gray-100 text-gray-600': ['debug','log'].includes(log.level.toLowerCase())
                                                }" x-text="log.level"></span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="text-xs text-gray-700 truncate max-w-[400px] font-medium" x-text="log.msg"></div>
                                                <div x-show="expanded" x-collapse x-cloak class="mt-3" @click.stop>
                                                    <div class="bg-gray-900 rounded-xl p-4 font-mono text-[10px] leading-relaxed overflow-x-auto text-emerald-400 shadow-inner max-h-64" x-html="logMode === 'client' ? formatMsgHtml(log.msg) : (log.full || log.msg)"></div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="flex items-center justify-center gap-1">
                                                    <button @click.stop="navigator.clipboard.writeText(log.full || log.msg); $el.innerHTML='✅'; setTimeout(()=>$el.innerHTML='📋', 1000)" class="p-1.5 hover:bg-gray-100 rounded-lg text-gray-400 hover:text-purple-600 transition-colors" title="Copy Log">
                                                        📋
                                                    </button>
                                                    <button class="p-1.5 hover:bg-gray-100 rounded-lg text-gray-400 transition-colors">
                                                        <svg class="w-4 h-4 transition-transform duration-200" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                            <div x-show="!filteredLogs.length" class="text-center py-12">
                                <span class="text-4xl block mb-2 opacity-20">📂</span>
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">No logs found</p>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <div x-show="totalPages > 1" class="flex items-center justify-between px-2">
                            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest" x-text="'Page ' + page + ' of ' + totalPages"></span>
                            <div class="flex gap-1">
                                <button @click="page--" :disabled="page <= 1" class="px-3 py-1 bg-white border border-gray-200 rounded-lg text-[10px] font-black disabled:opacity-50 hover:border-purple-300 transition-all">PREV</button>
                                <button @click="page++" :disabled="page >= totalPages" class="px-3 py-1 bg-white border border-gray-200 rounded-lg text-[10px] font-black disabled:opacity-50 hover:border-purple-300 transition-all">NEXT</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Playground Tab -->
                <div x-show="activeTab==='playground'" class="space-y-6" x-data="{
                    method: 'GET',
                    url: '/lorapok/test/slow-route',
                    body: '',
                    response: null,
                    loading: false,
                    duration: 0,
                    status: null,
                    async sendRequest() {
                        this.loading = true;
                        this.response = null;
                        this.status = null;
                        const start = performance.now();
                        try {
                            const options = {
                                method: this.method,
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']')?.content
                                }
                            };
                            if (['POST', 'PUT', 'PATCH'].includes(this.method) && this.body) {
                                options.body = this.body;
                            }
                            const res = await fetch(this.url, options);
                            this.status = res.status;
                            const text = await res.text();
                            try {
                                this.response = JSON.stringify(JSON.parse(text), null, 2);
                            } catch (e) {
                                this.response = text;
                            }
                        } catch (e) {
                            this.response = 'Error: ' + e.message;
                            this.status = 0;
                        } finally {
                            this.duration = Math.round(performance.now() - start);
                            this.loading = false;
                        }
                    }
                }">
                    <!-- Centered Title -->
                    <div class="text-center">
                        <h3 class="text-lg font-black text-gray-900 uppercase tracking-[0.2em]">API Playground</h3>
                        <div class="h-1 w-12 bg-purple-500 mx-auto mt-2 rounded-full"></div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-4">Test endpoints directly from the widget</p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-3xl p-6 shadow-sm">
                        <div class="flex flex-col gap-4">
                            <div class="flex gap-2">
                                <select x-model="method" class="w-24 bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs font-black uppercase tracking-wider focus:ring-2 focus:ring-purple-500 outline-none">
                                    <option>GET</option>
                                    <option>POST</option>
                                    <option>PUT</option>
                                    <option>DELETE</option>
                                </select>
                                <input x-model="url" type="text" class="flex-1 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-xs font-mono focus:ring-2 focus:ring-purple-500 outline-none" placeholder="https://..." />
                                <button @click="sendRequest()" :disabled="loading" class="px-6 bg-purple-600 text-white rounded-xl font-bold text-xs uppercase tracking-wider hover:bg-purple-700 transition-colors shadow-lg shadow-purple-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                                    <span x-show="loading" class="animate-spin">⌛</span>
                                    <span x-show="!loading">Send</span>
                                </button>
                            </div>
                            
                            <div x-show="['POST', 'PUT', 'PATCH'].includes(method)">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Request Body (JSON)</label>
                                <textarea x-model="body" class="w-full h-32 bg-gray-50 border border-gray-200 rounded-xl p-4 text-xs font-mono focus:ring-2 focus:ring-purple-500 outline-none resize-none" placeholder="{ &quot;key&quot;: &quot;value&quot; }"></textarea>
                            </div>
                        </div>
                    </div>

                    <div x-show="response !== null" class="bg-gray-900 rounded-3xl p-6 shadow-xl border border-gray-800">
                        <div class="flex justify-between items-center mb-4 border-b border-gray-800 pb-4">
                            <div class="flex items-center gap-4">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase" :class="status >= 200 && status < 300 ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400'" x-text="'Status: ' + status"></span>
                                <span class="text-xs text-gray-400 font-mono" x-text="duration + 'ms'"></span>
                            </div>
                            <button @click="navigator.clipboard.writeText(response)" class="text-gray-500 hover:text-white text-xs uppercase font-bold tracking-wider transition-colors">Copy Response</button>
                        </div>
                        <pre class="text-xs font-mono text-purple-300 overflow-x-auto max-h-[400px]" x-text="response"></pre>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-3 border-t border-gray-200 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-4 text-[10px] uppercase font-bold text-gray-400">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                        <span>System Live</span>
                    </div>
                    <div class="h-4 w-px bg-gray-300"></div>
                    <div class="flex items-center gap-1.5">
                        <span class="text-purple-600">v1.3.2-Advanced</span>
                    </div>
                    <div class="h-4 w-px bg-gray-300"></div>
                    <div class="flex items-center gap-2 overflow-x-auto no-scrollbar">
                        <span class="text-gray-500 whitespace-nowrap">📊 System Info:</span>
                        <div class="flex items-center gap-3">
                            <span class="text-gray-400 whitespace-nowrap">Laravel <span class="text-gray-600" x-text="data?.system_info?.laravel_version || 'N/A'"></span></span>
                            <span class="text-gray-400 whitespace-nowrap">PHP <span class="text-gray-600" x-text="data?.system_info?.php_version || 'N/A'"></span></span>
                            <span class="text-gray-400 whitespace-nowrap">DB: <span class="text-gray-600" x-text="data?.system_info?.database || 'N/A'"></span></span>
                            <span class="text-gray-400 whitespace-nowrap">Env: <span class="text-gray-600" x-text="data?.system_info?.environment || 'N/A'"></span></span>
                            <span class="text-gray-400 whitespace-nowrap">Monitor: <span x-text="data?.system_info?.monitor_status || 'N/A'"></span></span>
                            <span class="text-gray-400 whitespace-nowrap">Widget: <span class="text-purple-500" x-text="(data?.system_info?.widget_status || 'Loaded') + ' (' + (data?.system_info?.git_branch || 'main') + ')'"></span></span>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-[10px] text-gray-400 uppercase font-bold leading-tight">Rate Limit Window</p>
                        <p class="text-xs font-bold text-purple-600" x-text="(discordEnabled || slackEnabled || mailEnabled) ? rateLimitMinutes + ' Minutes' : 'Disabled'"></p>
                    </div>
                    <div class="larvae-wiggle text-2xl">🐛</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Developer Info Modal -->
    <div x-show="showDevInfo" x-transition class="fixed inset-0 z-[11000] flex items-center justify-center p-4" style="display:none;pointer-events:auto">
        <div class="absolute inset-0 bg-gray-900/90 backdrop-blur-sm" @click="showDevInfo = false"></div>
        
        <div class="relative bg-gray-900 border border-gray-700/50 text-white rounded-[2.5rem] shadow-2xl w-full max-w-lg overflow-hidden flex flex-col">
            <!-- Header / Banner -->
            <div class="bg-gradient-to-r from-violet-600 to-indigo-600 p-8 text-center relative overflow-hidden">
                <!-- Decorative Pattern -->
                <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 20px 20px;"></div>
                
                <button @click="showDevInfo = false" class="absolute top-4 right-4 text-white/70 hover:text-white transition text-2xl w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/10">×</button>
                
                <div class="relative inline-block group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-pink-600 to-purple-600 rounded-full opacity-75 group-hover:opacity-100 transition duration-1000 group-hover:duration-200 animate-tilt"></div>
                    <img src="{{ asset('vendor/lorapok/images/author.jpg') }}" class="relative w-24 h-24 rounded-full border-4 border-gray-900 shadow-xl mx-auto object-cover">
                </div>
                
                <h2 class="text-2xl font-black tracking-tight mt-4">Maizied Hasan</h2>
                <p class="text-indigo-200 text-xs font-bold uppercase tracking-widest mt-1">#MaJHiBhai - Your friendly Laravel performance companion 🐛</p>
                
                <div class="flex justify-center gap-3 mt-6">
                    <a href="https://github.com/Maijied" target="_blank" title="GitHub" class="p-2 bg-white/10 rounded-xl hover:bg-white/20 transition backdrop-blur-sm hover:scale-110">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                    </a>
                    <a href="https://www.linkedin.com/in/maizied/" target="_blank" title="LinkedIn" class="p-2 bg-white/10 rounded-xl hover:bg-white/20 transition backdrop-blur-sm hover:scale-110">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                    </a>
                    <a href="mailto:mdshuvo40@gmail.com" title="Email" class="p-2 bg-white/10 rounded-xl hover:bg-white/20 transition backdrop-blur-sm hover:scale-110">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </a>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8 bg-gray-900 space-y-8 flex-1 overflow-y-auto">
                
                <!-- Advertise Section -->
                <div class="grid grid-cols-2 gap-4">
                    <a href="https://github.com/Maijied/lorapok" target="_blank" class="group relative overflow-hidden bg-gray-800 border border-gray-700 p-4 rounded-2xl transition hover:border-yellow-500/50 hover:shadow-lg hover:shadow-yellow-900/20">
                        <div class="absolute inset-0 bg-gradient-to-br from-yellow-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="text-2xl mb-2 group-hover:scale-110 transition-transform origin-left">⭐</div>
                        <h4 class="font-bold text-gray-200 text-sm">Star Repository</h4>
                        <p class="text-[10px] text-gray-500 mt-1">Support the development</p>
                    </a>
                    <a href="https://github.com/Maijied/lorapok/issues/new" target="_blank" class="group relative overflow-hidden bg-gray-800 border border-gray-700 p-4 rounded-2xl transition hover:border-red-500/50 hover:shadow-lg hover:shadow-red-900/20">
                        <div class="absolute inset-0 bg-gradient-to-br from-red-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="text-2xl mb-2 group-hover:scale-110 transition-transform origin-left">🐛</div>
                        <h4 class="font-bold text-gray-200 text-sm">Report Bug</h4>
                        <p class="text-[10px] text-gray-500 mt-1">Found a larvae?</p>
                    </a>
                </div>

                <!-- Dropbox -->
                <div class="bg-gray-800/50 rounded-3xl p-1 border border-gray-700/50 relative group" x-data="{ letter: '', sending: false, sent: false }">
                    <div class="absolute -top-3 left-6 px-2 bg-gray-900 text-[10px] font-black text-indigo-400 uppercase tracking-widest border border-gray-800 rounded-md">
                        📮 The Dropbox
                    </div>
                    
                    <div class="p-5" x-show="!sent">
                        <textarea x-model="letter" class="w-full bg-transparent text-sm text-gray-300 placeholder-gray-600 border-0 focus:ring-0 resize-none h-24 p-0 leading-relaxed font-mono" placeholder="Hey MaJHiBhai, I loved the larvae animation! Also, have you thought about..."></textarea>
                        
                        <div class="flex justify-between items-center mt-4 border-t border-gray-700/50 pt-4">
                            <span class="text-[10px] text-gray-500 font-bold uppercase" x-text="letter.length + ' chars'"></span>
                            <button @click="if(letter) { sending=true; setTimeout(()=>{ window.open('mailto:mdshuvo40@gmail.com?subject=Lorapok%20Dropbox%20Message&body='+encodeURIComponent(letter)); sending=false; sent=true; }, 800); }" 
                                :disabled="!letter"
                                class="bg-indigo-600 hover:bg-indigo-500 text-white px-5 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg shadow-indigo-900/30 hover:shadow-indigo-900/50 active:scale-95">
                                <span x-show="!sending">Drop Letter</span>
                                <span x-show="sending" class="animate-spin">⏳</span>
                                <svg x-show="!sending" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            </button>
                        </div>
                    </div>

                    <div class="p-10 text-center" x-show="sent" style="display:none">
                        <div class="text-4xl mb-3 animate-bounce">📬</div>
                        <h4 class="font-bold text-white mb-1">Letter Dropped!</h4>
                        <p class="text-xs text-gray-500 mb-4">Thanks for reaching out.</p>
                        <button @click="sent=false; letter=''" class="text-xs text-indigo-400 font-bold hover:text-indigo-300 uppercase tracking-wide">Write another</button>
                    </div>
                </div>

                <div class="text-center">
                    <p class="text-[10px] text-gray-600 uppercase font-bold tracking-widest">Designed with ❤️ in 2026</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Modal -->
    <div x-show="openSettings" x-transition class="fixed inset-0 z-[10500] flex items-center justify-center p-4" style="display:none;pointer-events:auto">
        <div class="absolute inset-0 bg-gray-900 bg-opacity-75" @click="openSettings = false"></div>
        <div class="relative bg-gradient-to-br from-purple-600 via-blue-600 to-purple-700 text-white rounded-3xl shadow-2xl p-8 w-full max-w-2xl mx-4">
            <button @click="openSettings = false" class="absolute top-4 right-4 text-white text-2xl bg-white bg-opacity-10 rounded-full w-8 h-8 flex items-center justify-center hover:bg-opacity-20 transition">×</button>
            <div class="text-center space-y-4">
                <div class="text-6xl mb-2 larvae-wiggle">🐛</div>
                <h2 class="text-2xl font-bold">Lorapok Settings</h2>
                <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-2xl p-6 text-left">
                    <div class="flex space-x-1 bg-black bg-opacity-20 rounded-lg p-1 mb-6">
                        <button @click="settingsTab='discord'" :class="settingsTab==='discord'?'bg-white text-purple-700 shadow':'text-purple-200 hover:bg-white hover:bg-opacity-10'" class="flex-1 py-2 text-xs font-bold uppercase tracking-wide rounded-md transition flex items-center justify-center gap-2">Discord</button>
                        <button @click="settingsTab='slack'" :class="settingsTab==='slack'?'bg-white text-purple-700 shadow':'text-purple-200 hover:bg-white hover:bg-opacity-10'" class="flex-1 py-2 text-xs font-bold uppercase tracking-wide rounded-md transition flex items-center justify-center gap-2">Slack</button>
                        <button @click="settingsTab='email'" :class="settingsTab==='email'?'bg-white text-purple-700 shadow':'text-purple-200 hover:bg-white hover:bg-opacity-10'" class="flex-1 py-2 text-xs font-bold uppercase tracking-wide rounded-md transition flex items-center justify-center gap-2">Email</button>
                        <button @click="settingsTab='alerts'" :class="settingsTab==='alerts'?'bg-white text-purple-700 shadow':'text-purple-200 hover:bg-white hover:bg-opacity-10'" class="flex-1 py-2 text-xs font-bold uppercase tracking-wide rounded-md transition flex items-center justify-center gap-2">Alerts</button>
                        <button @click="settingsTab='advanced'" :class="settingsTab==='advanced'?'bg-white text-purple-700 shadow':'text-purple-200 hover:bg-white hover:bg-opacity-10'" class="flex-1 py-2 text-xs font-bold uppercase tracking-wide rounded-md transition flex items-center justify-center gap-2">Advanced</button>
                    </div>

                    <div x-show="settingsTab==='discord'" class="space-y-4" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                        <div class="text-center mb-4">
                             <div class="bg-indigo-500 w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-3 shadow-lg">
                                <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028 14.09 14.09 0 0 0 1.226-1.994.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03zM8.02 15.33c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.956-2.419 2.157-2.419 1.21 0 2.176 1.085 2.157 2.419 0 1.334-.956 2.419-2.157 2.419zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.955-2.419 2.157-2.419 1.21 0 2.176 1.085 2.157 2.419 0 1.334-.946 2.419-2.157 2.419z"/></svg>
                             </div>
                             <h3 class="text-lg font-black text-white">Discord Integration</h3>
                             <p class="text-indigo-200 text-[10px] font-bold uppercase tracking-wider mt-1">Direct performance alerts to your server</p>
                        </div>
                        <div>
                            <label class="block text-[10px] text-indigo-50 mb-1 font-black uppercase tracking-widest">Discord Webhook URL</label>
                            <input x-model="discordWebhook" class="w-full text-sm p-3 rounded-xl bg-black/30 text-white border border-white/10 placeholder-indigo-300/30 focus:ring-2 focus:ring-indigo-500 focus:bg-black/40 transition-all font-mono" placeholder="https://discordapp.com/api/webhooks/..." />
                            <label class="inline-flex items-center mt-4 text-white text-xs font-bold cursor-pointer hover:text-indigo-200 transition bg-white/5 rounded-xl p-3 w-full border border-white/5">
                                <input type="checkbox" x-model="discordEnabled" class="mr-3 rounded text-indigo-600 focus:ring-indigo-500 w-5 h-5 bg-black/20 border-white/10"/> 
                                Enable Discord Alerts
                            </label>
                        </div>
                    </div>

                    <!-- Panel: Slack -->
                    <div x-show="settingsTab==='slack'" class="space-y-4" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" style="display:none">
                        <div class="text-center mb-4">
                             <div class="bg-rose-500 w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-3 shadow-lg">
                                <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M5.042 15.165a2.528 2.528 0 0 1-2.52 2.523A2.528 2.528 0 0 1 0 15.165a2.527 2.527 0 0 1 2.522-2.52h2.52v2.52zM6.313 15.165a2.527 2.527 0 0 1 2.521-2.52 2.527 2.527 0 0 1 2.521 2.52v6.313A2.528 2.528 0 0 1 8.834 24a2.528 2.528 0 0 1-2.521-2.522v-6.313zM8.834 5.042a2.528 2.528 0 0 1-2.521-2.52A2.528 2.528 0 0 1 8.834 0a2.528 2.528 0 0 1 2.521 2.522v2.52H8.834zM8.834 6.313a2.528 2.528 0 0 1 2.521 2.521 2.528 2.528 0 0 1-2.521 2.521H2.522A2.528 2.528 0 0 1 0 8.834a2.528 2.528 0 0 1 2.522-2.521h6.312zM18.956 8.834a2.528 2.528 0 0 1 2.522-2.521A2.528 2.528 0 0 1 24 8.834a2.528 2.528 0 0 1-2.522 2.521h-2.522V8.834zM17.688 8.834a2.528 2.528 0 0 1-2.522 2.521 2.527 2.527 0 0 1-2.522-2.521V2.522A2.527 2.527 0 0 1 15.165 0a2.528 2.528 0 0 1 2.523 2.522v6.312zM15.165 18.956a2.528 2.528 0 0 1 2.523 2.522A2.528 2.528 0 0 1 15.165 24a2.527 2.527 0 0 1-2.522-2.522v-2.522h2.522zM15.165 17.688a2.527 2.527 0 0 1-2.522-2.522 2.527 2.527 0 0 1 2.522-2.521h6.313A2.527 2.527 0 0 1 24 15.165a2.528 2.528 0 0 1-2.522 2.522h-6.313z"/></svg>
                             </div>
                             <h3 class="text-lg font-black text-white">Slack Integration</h3>
                             <p class="text-indigo-200 text-[10px] font-bold uppercase tracking-wider mt-1">Real-time team synchronization</p>
                        </div>
                        <div>
                            <label class="block text-[10px] text-indigo-50 mb-1 font-black uppercase tracking-widest">Slack Webhook URL <span class="text-rose-400" x-show="slackEnabled">*</span></label>
                            <input x-model="slackWebhook" :class="{'border-rose-500': slackEnabled && !slackWebhook}" class="w-full text-sm p-3 rounded-xl bg-black/30 text-white border border-white/10 placeholder-indigo-300/30 focus:ring-2 focus:ring-rose-500 transition-all font-mono" placeholder="https://hooks.slack.com/services/..." />
                        </div>
                        <div>
                            <label class="inline-flex items-center mt-4 text-white text-xs font-bold cursor-pointer hover:text-indigo-200 transition bg-white/5 rounded-xl p-3 w-full border border-white/5">
                                <input type="checkbox" x-model="slackEnabled" class="mr-3 rounded text-rose-600 focus:ring-rose-500 w-5 h-5 bg-black/20 border-white/10"/> 
                                Enable Slack Alerts
                            </label>
                            <p class="text-[10px] text-rose-300 mt-2 font-bold uppercase" x-show="slackEnabled && !slackWebhook">⚠️ Missing webhook URL</p>
                        </div>
                    </div>

                    <!-- Panel: Email -->
                    <div x-show="settingsTab==='email'" class="space-y-4" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" style="display:none">
                        <div class="flex items-center gap-4 border-b border-white/10 pb-4 mb-4">
                             <div class="bg-blue-500 w-10 h-10 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                             </div>
                             <div>
                                 <h3 class="text-md font-black text-white">Email Alerts</h3>
                                 <p class="text-indigo-200 text-[10px] font-bold uppercase tracking-wider">Configure SMTP server reports</p>
                             </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-[10px] text-indigo-50 mb-1 font-black uppercase tracking-widest">Recipient Email</label>
                                <input x-model="mailTo" class="w-full text-sm p-3 rounded-xl bg-black/30 text-white border border-white/10 placeholder-indigo-300/30 focus:ring-2 focus:ring-blue-500 transition-all" placeholder="admin@example.com" />
                            </div>
                            <label class="inline-flex items-center text-white text-xs font-bold cursor-pointer hover:text-indigo-200 transition bg-white/5 rounded-xl p-3 w-full border border-white/5">
                                <input type="checkbox" x-model="mailEnabled" class="mr-3 rounded text-blue-600 focus:ring-blue-500 w-5 h-5 bg-black/20 border-white/10"/> 
                                Enable Email Alerts
                            </label>
                        </div>

                        <div class="mt-4 pt-4 border-t border-white/10">
                            <h3 class="text-[10px] font-black text-white mb-3 uppercase tracking-[0.2em]">SMTP Configuration</h3>
                            
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-[8px] text-indigo-200 mb-1 font-black uppercase tracking-widest">Host</label>
                                    <input type="text" x-model="mailHost" placeholder="smtp.mailtrap.io" class="w-full text-xs p-2.5 rounded-lg bg-black/30 text-white border border-white/10 focus:ring-1 focus:ring-blue-500 transition-all font-mono">
                                </div>
                                <div>
                                    <label class="block text-[8px] text-indigo-200 mb-1 font-black uppercase tracking-widest">Port</label>
                                    <input type="number" x-model="mailPort" placeholder="2525" class="w-full text-xs p-2.5 rounded-lg bg-black/30 text-white border border-white/10 focus:ring-1 focus:ring-blue-500 transition-all font-mono">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-[8px] text-indigo-200 mb-1 font-black uppercase tracking-widest">Username</label>
                                    <input type="text" x-model="mailUsername" class="w-full text-xs p-2.5 rounded-lg bg-black/30 text-white border border-white/10 focus:ring-1 focus:ring-blue-500 transition-all font-mono">
                                </div>
                                <div>
                                    <label class="block text-[8px] text-indigo-200 mb-1 font-black uppercase tracking-widest">Password</label>
                                    <input type="password" x-model="mailPassword" class="w-full text-xs p-2.5 rounded-lg bg-black/30 text-white border border-white/10 focus:ring-1 focus:ring-blue-500 transition-all font-mono">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[8px] text-indigo-200 mb-1 font-black uppercase tracking-widest">Encryption</label>
                                    <input type="text" x-model="mailEncryption" placeholder="tls" class="w-full text-xs p-2.5 rounded-lg bg-black/30 text-white border border-white/10 focus:ring-1 focus:ring-blue-500 transition-all font-mono">
                                </div>
                                <div>
                                    <label class="block text-[8px] text-indigo-200 mb-1 font-black uppercase tracking-widest">From Address</label>
                                    <input type="text" x-model="mailFromAddress" placeholder="monitor@lorapok.com" class="w-full text-xs p-2.5 rounded-lg bg-black/30 text-white border border-white/10 focus:ring-1 focus:ring-blue-500 transition-all font-mono">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Panel: Alerts (Thresholds) -->
                    <div x-show="settingsTab==='alerts'" class="space-y-4" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" style="display:none">
                        <div class="text-center mb-4">
                             <div class="bg-amber-500 w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-3 shadow-lg">
                                <span class="text-2xl">🚨</span>
                             </div>
                             <h3 class="text-lg font-black text-white">Alert Thresholds</h3>
                             <p class="text-indigo-200 text-[10px] font-bold uppercase tracking-wider mt-1">Define your performance budget</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] text-indigo-50 mb-1 font-black uppercase tracking-widest">Slow Route (ms)</label>
                                <input type="number" x-model.number="routeThreshold" class="w-full text-sm p-3 rounded-xl bg-black/30 text-white border border-white/10 focus:ring-2 focus:ring-amber-500 transition-all font-mono" placeholder="1000" />
                            </div>
                            <div>
                                <label class="block text-[10px] text-indigo-50 mb-1 font-black uppercase tracking-widest">Slow Query (ms)</label>
                                <input type="number" x-model.number="queryThreshold" class="w-full text-sm p-3 rounded-xl bg-black/30 text-white border border-white/10 focus:ring-2 focus:ring-amber-500 transition-all font-mono" placeholder="100" />
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] text-indigo-50 mb-1 font-black uppercase tracking-widest">Query Count Limit</label>
                                <input type="number" x-model.number="queryCountThreshold" class="w-full text-sm p-3 rounded-xl bg-black/30 text-white border border-white/10 focus:ring-2 focus:ring-amber-500 transition-all font-mono" placeholder="50" />
                            </div>
                            <div>
                                <label class="block text-[10px] text-indigo-50 mb-1 font-black uppercase tracking-widest">Memory Limit (MB)</label>
                                <input type="number" x-model.number="memoryThreshold" class="w-full text-sm p-3 rounded-xl bg-black/30 text-white border border-white/10 focus:ring-2 focus:ring-amber-500 transition-all font-mono" placeholder="128" />
                            </div>
                        </div>

                        <div class="bg-amber-900/40 rounded-2xl p-4 border border-amber-500/20 shadow-inner">
                            <p class="text-[9px] text-amber-100 leading-relaxed font-medium italic">
                                <span class="font-black uppercase tracking-widest block mb-1 not-italic text-amber-400">Architect Strategy</span>
                                Setting strict thresholds ensures early detection of regressions. Aim for <span class="text-white font-bold">100ms</span> for routes and <span class="text-white font-bold">10ms</span> for queries in high-traffic APIs.
                            </p>
                        </div>
                    </div>

                    <div x-show="settingsTab==='advanced'" class="space-y-4" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" style="display:none">
                        <div class="text-center mb-4">
                             <div class="bg-purple-500 w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-3 shadow-lg">
                                <span class="text-2xl">⚙️</span>
                             </div>
                             <h3 class="text-lg font-black text-white">Advanced Engine</h3>
                             <p class="text-indigo-200 text-[10px] font-bold uppercase tracking-wider mt-1">Fine-tune system behavior</p>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-[10px] text-indigo-50 mb-1 font-black uppercase tracking-widest">Alert Cooldown Period (min)</label>
                                <input x-model.number="rateLimitMinutes" type="number" min="1" max="1440" class="w-full text-sm p-3 rounded-xl bg-black/30 text-white border border-white/10 focus:ring-2 focus:ring-purple-500 transition-all font-mono" placeholder="30" />
                                <p class="text-[11px] text-indigo-200 mt-2 font-medium ml-1">Avoid notification spam for the same performance issue.</p>
                            </div>

                            <div>
                                <label class="block text-[10px] text-indigo-50 mb-1 font-black uppercase tracking-widest">Live Data Sync Interval (ms)</label>
                                <input x-model.number="pollingInterval" type="number" min="1000" step="1000" class="w-full text-sm p-3 rounded-xl bg-black/30 text-white border border-white/10 focus:ring-2 focus:ring-purple-500 transition-all font-mono" placeholder="5000" />
                                <p class="text-[11px] text-indigo-200 mt-2 font-medium ml-1">Frequency of automatic dashboard refreshes.</p>
                            </div>

                            <div class="bg-white/5 p-4 rounded-2xl border border-white/10 transition-all hover:bg-white/10 group">
                                <label class="flex items-center justify-between cursor-pointer">
                                    <div>
                                        <p class="text-xs font-black text-white uppercase tracking-wider group-hover:text-indigo-300 transition-colors">Mirror Logs to Server</p>
                                        <p class="text-[11px] text-indigo-200 mt-1 font-medium">Stream console logs to /storage/logs/lorapok-client.log</p>
                                    </div>
                                    <div class="relative">
                                        <input type="checkbox" x-model="clientLogWritingEnabled" class="sr-only peer">
                                        <div class="w-11 h-6 bg-black/40 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500 shadow-inner"></div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-6">
                        <button @click="saveSettings()" class="flex-1 py-3 bg-white text-purple-700 font-bold rounded-xl hover:bg-purple-50 transition shadow-lg">Save Changes</button>
                        <button @click="openSettings = false" class="px-4 py-3 bg-black bg-opacity-20 text-white font-semibold rounded-xl hover:bg-opacity-30 transition">Cancel</button>
                    </div>
                </div>
            </div>

            <!-- Toast -->
            <div x-show="showSuccessModal" x-transition class="absolute inset-0 flex items-center justify-center z-50 bg-black/60 backdrop-blur-sm rounded-3xl">
                <div :class="isError ? 'border-red-500' : 'border-green-500'" class="bg-gray-900 border-2 rounded-2xl p-8 max-w-sm w-full text-center">
                    <span class="text-5xl block mb-4" x-text="isError ? '❌' : '✅'"></span>
                    <h3 x-text="isError ? 'Error' : 'Success!'" class="text-xl font-bold mb-2"></h3>
                    <p x-text="modalMessage" class="text-gray-400 text-sm mb-6"></p>
                    <button @click="showSuccessModal = false" :class="isError ? 'bg-red-600 hover:bg-red-500 shadow-red-600/30' : 'bg-green-600 hover:bg-green-500 shadow-green-600/30'" class="w-full py-2 rounded-lg bg-white text-gray-900 font-bold">Awesome</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Command Terminal Modal -->
    <div x-show="openTerminal" @open-terminal.window="openTerminal = true; openSettings = false; showDevInfo = false" x-transition class="fixed inset-0 z-[11000] flex items-center justify-center p-4" style="display:none;pointer-events:auto">
        <div class="absolute inset-0 bg-gray-900 bg-opacity-80 backdrop-blur-sm" @click="openTerminal = false"></div>
        <div class="relative bg-gray-900 border border-gray-700 text-white rounded-xl shadow-2xl p-6 w-full max-w-3xl flex flex-col max-h-[80vh]">
            <div class="flex justify-between items-center mb-4 border-b border-gray-800 pb-4">
                <h3 class="text-lg font-mono font-bold flex items-center gap-2 text-green-400">
                    <span>>_</span> Lorapok Terminal
                </h3>
                <button @click="openTerminal = false" class="text-gray-500 hover:text-white">×</button>
            </div>
            
            <div class="flex-1 overflow-hidden flex flex-col gap-4">
                <!-- Custom Command Input -->
                <div class="flex gap-2" x-data="{ customCmd: '' }">
                    <span class="text-green-400 font-mono py-2 select-none">$</span>
                    <input type="text" x-model="customCmd" @keydown.enter="if(customCmd) { runCommand(customCmd); customCmd=''; }" placeholder="Type artisan command (e.g. migrate:status)..." class="flex-1 bg-black border border-gray-700 rounded px-3 py-2 text-xs font-mono text-white focus:border-green-500 focus:ring-1 focus:ring-green-500 outline-none transition-all placeholder-gray-600">
                    <button @click="if(customCmd) { runCommand(customCmd); customCmd=''; }" class="px-4 py-2 bg-green-600 text-white rounded text-xs font-bold hover:bg-green-500 transition-colors uppercase tracking-wider">Run</button>
                </div>

                <!-- Command List -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                    <button @click="runCommand('monitor:status')" class="px-3 py-2 bg-gray-800 hover:bg-gray-700 border border-gray-700 rounded text-xs font-mono text-left transition-colors flex items-center gap-2 group">
                        <span class="w-2 h-2 rounded-full bg-green-500 group-hover:animate-pulse"></span> status
                    </button>
                    <button @click="runCommand('monitor:audit')" class="px-3 py-2 bg-gray-800 hover:bg-gray-700 border border-gray-700 rounded text-xs font-mono text-left transition-colors flex items-center gap-2 group">
                        <span class="w-2 h-2 rounded-full bg-yellow-500 group-hover:animate-pulse"></span> audit
                    </button>
                    <button @click="runCommand('monitor:heatmap')" class="px-3 py-2 bg-gray-800 hover:bg-gray-700 border border-gray-700 rounded text-xs font-mono text-left transition-colors flex items-center gap-2 group">
                        <span class="w-2 h-2 rounded-full bg-purple-500 group-hover:animate-pulse"></span> heatmap
                    </button>
                    <button @click="runCommand('monitor:memory')" class="px-3 py-2 bg-gray-800 hover:bg-gray-700 border border-gray-700 rounded text-xs font-mono text-left transition-colors flex items-center gap-2 group">
                        <span class="w-2 h-2 rounded-full bg-pink-500 group-hover:animate-pulse"></span> memory
                    </button>
                </div>

                <!-- Service Toggles -->
                <div class="grid grid-cols-4 gap-2 border-t border-gray-800 pt-4">
                    <button @click="runCommand('monitor:enable routes')" class="px-2 py-1.5 bg-gray-800/50 hover:bg-green-900/30 border border-gray-700 rounded text-[10px] font-mono text-gray-400 hover:text-green-400 transition-colors">
                        + Routes
                    </button>
                    <button @click="runCommand('monitor:disable routes')" class="px-2 py-1.5 bg-gray-800/50 hover:bg-red-900/30 border border-gray-700 rounded text-[10px] font-mono text-gray-400 hover:text-red-400 transition-colors">
                        - Routes
                    </button>
                    <button @click="runCommand('monitor:enable queries')" class="px-2 py-1.5 bg-gray-800/50 hover:bg-green-900/30 border border-gray-700 rounded text-[10px] font-mono text-gray-400 hover:text-green-400 transition-colors">
                        + Queries
                    </button>
                    <button @click="runCommand('monitor:disable queries')" class="px-2 py-1.5 bg-gray-800/50 hover:bg-red-900/30 border border-gray-700 rounded text-[10px] font-mono text-gray-400 hover:text-red-400 transition-colors">
                        - Queries
                    </button>
                </div>

                <!-- Global Control -->
                <div class="grid grid-cols-2 gap-2 border-t border-gray-800 pt-4">
                    <button @click="runCommand('monitor:enable')" class="px-3 py-2 bg-green-600 hover:bg-green-500 text-white rounded text-xs font-bold transition-all shadow-lg shadow-green-900/20 uppercase tracking-widest flex items-center justify-center gap-2">
                        <span>▶️</span> Enable Monitor
                    </button>
                    <button @click="runCommand('monitor:disable')" class="px-3 py-2 bg-red-600 hover:bg-red-500 text-white rounded text-xs font-bold transition-all shadow-lg shadow-red-900/20 uppercase tracking-widest flex items-center justify-center gap-2">
                        <span>⏹️</span> Disable Monitor
                    </button>
                </div>

                <!-- Output Area -->
                <div class="flex-1 bg-black rounded-lg p-4 font-mono text-xs overflow-y-auto border border-gray-800 shadow-inner relative group">
                    <div x-show="terminalLoading" class="absolute inset-0 bg-black/50 flex items-center justify-center z-10">
                        <span class="text-green-400 animate-pulse">Executing...</span>
                    </div>
                    <template x-for="(line, idx) in terminalOutput" :key="idx">
                        <div class="mb-1">
                            <span class="text-gray-500 select-none mr-2">$</span>
                            <span :class="line.type === 'error' ? 'text-red-400' : 'text-gray-300'" x-text="line.text"></span>
                        </div>
                    </template>
                    <div x-show="terminalOutput.length === 0" class="text-gray-600 italic text-center mt-10">Select a command to execute</div>
                </div>
            </div>
        </div>
    </div>
    <!-- Usage Guide Modal -->
    <div x-show="showUsage" x-transition class="fixed inset-0 z-[11000] flex items-center justify-center p-4" style="display:none;pointer-events:auto">
        <div class="absolute inset-0 bg-gray-900/90 backdrop-blur-sm" @click="showUsage = false"></div>
        <div class="relative bg-gray-900 border border-gray-700 text-white rounded-xl shadow-2xl p-0 w-full max-w-4xl flex flex-col max-h-[85vh] overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-pink-600 to-purple-600 px-6 py-4 flex items-center justify-between shrink-0">
                <h3 class="text-lg font-black text-white flex items-center gap-2">
                    <span class="text-2xl">📘</span> Lorapok Usage Guide
                </h3>
                <button @click="showUsage = false" class="text-white/80 hover:text-white transition text-2xl w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/10">×</button>
            </div>

            <!-- Content -->
            <div class="flex-1 overflow-y-auto p-8 space-y-8">
                <!-- Section: Overview -->
                <div class="space-y-4">
                    <h4 class="text-xl font-bold text-pink-400 border-b border-gray-800 pb-2">🚀 Getting Started</h4>
                    <p class="text-sm text-gray-300 leading-relaxed">
                        Lorapok automatically tracks your Laravel application's performance. The floating <strong>Larvae Button</strong> 🐛 is your gateway to real-time insights.
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                        <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                            <div class="text-2xl mb-2">📊</div>
                            <h5 class="font-bold text-white text-sm">Overview</h5>
                            <p class="text-[10px] text-gray-400 mt-1">Instant dashboard of current request performance, memory usage, and alerts.</p>
                        </div>
                        <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                            <div class="text-2xl mb-2">📡</div>
                            <h5 class="font-bold text-white text-sm">Activity</h5>
                            <p class="text-[10px] text-gray-400 mt-1">Live feed of all requests in the current session. Click to inspect details.</p>
                        </div>
                        <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                            <div class="text-2xl mb-2">🐛</div>
                            <h5 class="font-bold text-white text-sm">Timeline</h5>
                            <p class="text-[10px] text-gray-400 mt-1">Waterfalls visualization of execution flow, queries, and events.</p>
                        </div>
                    </div>
                </div>

                <!-- Section: Terminal & Commands -->
                <div class="space-y-4">
                    <h4 class="text-xl font-bold text-green-400 border-b border-gray-800 pb-2">💻 Terminal & Commands</h4>
                    <p class="text-sm text-gray-300 leading-relaxed">
                        Execute Artisan commands directly from the browser using the <strong>Lorapok Terminal</strong>.
                    </p>
                    <ul class="list-disc list-inside text-sm text-gray-400 space-y-2 ml-2">
                        <li>Open Terminal via the <span class="bg-gray-700 px-1 rounded text-white text-xs">💻</span> icon in the header.</li>
                        <li>Type commands like <code>migrate:status</code> or <code>cache:clear</code> in the input field.</li>
                        <li>Use quick buttons for common tasks like <span class="text-yellow-400">audit</span> and <span class="text-purple-400">heatmap</span>.</li>
                        <li><strong>Service Toggles:</strong> Enable/Disable monitoring for Routes/Queries on the fly.</li>
                    </ul>
                </div>

                <!-- Section: Playground -->
                <div class="space-y-4">
                    <h4 class="text-xl font-bold text-blue-400 border-b border-gray-800 pb-2">🎮 API Playground</h4>
                    <p class="text-sm text-gray-300 leading-relaxed">
                        Test your API endpoints without leaving the interface.
                    </p>
                    <div class="bg-gray-800/50 p-4 rounded-xl border border-gray-700">
                        <code class="text-xs text-blue-300 font-mono block mb-2">// Example Workflow</code>
                        <ol class="list-decimal list-inside text-xs text-gray-400 space-y-1">
                            <li>Go to <strong>Playground</strong> tab.</li>
                            <li>Select Method (GET, POST, etc.) and enter URL.</li>
                            <li>Add JSON body if needed.</li>
                            <li>Click <strong>Send</strong> to view response status and payload.</li>
                        </ol>
                    </div>
                </div>

                <!-- Section: Notifications & Thresholds -->
                <div class="space-y-4">
                    <h4 class="text-xl font-bold text-red-400 border-b border-gray-800 pb-2">🔔 Notifications & Thresholds</h4>
                    <p class="text-sm text-gray-300 leading-relaxed">
                        Lorapok keeps you informed when your app deviates from performance standards.
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <h5 class="text-xs font-black uppercase text-gray-500 tracking-widest">Supported Channels</h5>
                            <div class="flex flex-wrap gap-2">
                                <span class="bg-indigo-900/50 text-indigo-300 px-2 py-1 rounded text-[10px] font-bold border border-indigo-700/50">Discord</span>
                                <span class="bg-rose-900/50 text-rose-300 px-2 py-1 rounded text-[10px] font-bold border border-rose-700/50">Slack</span>
                                <span class="bg-blue-900/50 text-blue-300 px-2 py-1 rounded text-[10px] font-bold border border-blue-700/50">Email</span>
                            </div>
                            <p class="text-[10px] text-gray-400">Configure these in the <strong>Settings</strong> modal using webhooks or SMTP.</p>
                        </div>
                        <div class="space-y-2">
                            <h5 class="text-xs font-black uppercase text-gray-500 tracking-widest">What triggers an alert?</h5>
                            <ul class="text-[10px] text-gray-400 space-y-1">
                                <li>• <span class="text-white">Slow Routes:</span> Request duration exceeds threshold.</li>
                                <li>• <span class="text-white">Slow Queries:</span> Individual SQL takes too long.</li>
                                <li>• <span class="text-white">Query Spam:</span> Single request runs too many queries.</li>
                                <li>• <span class="text-white">Memory Spike:</span> Peak usage exceeds MB limit.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Section: Shortcuts & Tips -->
                <div class="space-y-4">
                    <h4 class="text-xl font-bold text-yellow-400 border-b border-gray-800 pb-2">⚡ Pro Tips</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-start gap-3">
                            <span class="bg-gray-700 text-white px-2 py-1 rounded text-xs font-mono">Ctrl+Shift+C</span>
                            <div>
                                <p class="text-sm font-bold text-white">Copy Query</p>
                                <p class="text-[10px] text-gray-400">Hover over any SQL query and press to copy.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="bg-gray-700 text-white px-2 py-1 rounded text-xs font-mono">Filter Logs</span>
                            <div>
                                <p class="text-sm font-bold text-white">Advanced Search</p>
                                <p class="text-[10px] text-gray-400">Use the search bar in Logs tab to filter by level (error, info) or message content.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="bg-gray-700 text-white px-2 py-1 rounded text-xs font-mono">Polling</span>
                            <div>
                                <p class="text-sm font-bold text-white">Control Data Rate</p>
                                <p class="text-[10px] text-gray-400">Adjust refresh rate in <strong>Settings > Advanced</strong> to save resources.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="bg-gray-700 text-white px-2 py-1 rounded text-xs font-mono">The Dropbox</span>
                            <div>
                                <p class="text-sm font-bold text-white">Feedback</p>
                                <p class="text-[10px] text-gray-400">Click the Developer icon <span class="grayscale">👨‍💻</span> to drop a direct letter/email to the author.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes larvae-bob { 0% { transform: translateY(0); } 50% { transform: translateY(-6px); } 100% { transform: translateY(0); } }
.larvae-wiggle { display:inline-block; animation: larvae-bob 1.2s ease-in-out infinite; }
@keyframes larvae-spin { 0% { transform: rotate(0deg) } 100% { transform: rotate(360deg) } }
.larvae-spin { animation: larvae-spin 6s linear infinite; }
.modal-action-btn:hover { background: rgba(255,255,255,0.15)!important; transform: scale(1.05); }
.modal-action-btn { transition: all 0.2s ease; border: none; cursor: pointer; }
[x-cloak] { display: none !important; }
</style>

<script>
(function(){
    window.__lorapok_console_logs = window.__lorapok_console_logs || [];
    function safeStringify(v){
        try{
            var seen = new WeakSet();
            return JSON.stringify(v, function(k, val){
                if(typeof val === 'function') return '[Function]';
                if(typeof val === 'object' && val !== null){
                    if(seen.has(val)) return '[Circular]';
                    seen.add(val);
                }
                return val;
            }, 2);
        }catch(e){
            try{ return String(v); }catch(e2){ return '[Unserializable]'; }
        }
    }

    function pushLog(level, args, type = 'console'){
        try{
            var parts = Array.prototype.slice.call(args).map(function(a){
                if(typeof a === 'string') return a;
                try{ return safeStringify(a); }catch(e){ return String(a); }
            });
            var msg = parts.join(' ');
            var entry = { level: level, msg: msg, type: type, at: (new Date()).toISOString() };
            window.__lorapok_console_logs.push(entry);
            if(window.__lorapok_console_logs.length>1000) window.__lorapok_console_logs.shift();
            try{ window.dispatchEvent(new CustomEvent('lorapok:console-log', { detail: entry })); }catch(e){}

            // Auto-persist to server if enabled
            if (window.__lorapok_persist_active) {
                fetch('/execution-monitor/api/client-logs', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content },
                    body: JSON.stringify({ logs: [entry] })
                }).catch(function(e){});
            }
        }catch(e){}
    }
    ['log','info','warn','error','debug'].forEach(function(level){
        var orig = console[level] && console[level].bind(console) || function(){};
        console[level] = function(){
            try{ pushLog(level, arguments); }catch(e){}
            try{ orig.apply(console, arguments); }catch(e){}
        };
    });

    var origFetch = window.fetch;
    window.fetch = function() {
        var firstArg = arguments[0];
        var url = "";
        
        if (typeof firstArg === 'string') {
            url = firstArg;
        } else if (firstArg instanceof URL) {
            url = firstArg.href;
        } else if (firstArg && typeof firstArg === 'object' && firstArg.url) {
            url = firstArg.url;
        }

        var method = (arguments[1] && arguments[1].method) || 'GET';
        return origFetch.apply(this, arguments).then(function(response) {
            // Check if url exists and is a string before calling includes
            if (typeof url === 'string' && !url.includes('execution-monitor/api/data')) {
                pushLog('info', ['📡 ' + method + ' ' + url + ' [' + response.status + ']'], 'network');
            }
            return response;
        }).catch(function(err) {
            if (typeof url === 'string') {
                pushLog('error', ['❌ ' + method + ' ' + url + ' failed: ' + err.message], 'network');
            }
            throw err;
        });
    };

    window.addEventListener('error', function(ev){ pushLog('error', [ev.message + ' at ' + (ev.filename||'') + ':' + (ev.lineno||'')]); });
    window.addEventListener('unhandledrejection', function(ev){ pushLog('error', ['Unhandled Rejection', ev.reason]); });
})();

window.monitorWidget = function() {
    return {
        isOpen: false,
        showDevInfo: false,
        showUsage: false,
        openSettings: false,
        openTerminal: false,
        settingsTab: 'discord',
        showSuccessModal: false,
        isError: false,
        modalMessage: '',
        isSaving: false,
        discordWebhook: null,
        discordEnabled: false,
        slackWebhook: null,
        slackChannel: null,
        slackEnabled: false,
        lastException: null,
        mailTo: null,
        mailEnabled: false,
        mailHost: '',
        mailPort: '',
        mailUsername: '',
        mailPassword: '',
        mailEncryption: '',
        mailFromAddress: '',
        rateLimitMinutes: 30,
        pollingInterval: 5000,
        routeThreshold: 1000,
        queryThreshold: 100,
        queryCountThreshold: 50,
        memoryThreshold: 128,
        clientLogWritingEnabled: false,
        activeTab: 'overview',
        data: { routes: {}, queries: [], alerts: [], total_queries: 0, total_query_time: 0 },
        sessionHistory: [],
        consoleLogs: [],
        hasAlerts: false,
        clipboardHistory: [],
        selectedQueryIndex: null,
        copiedIndex: null,
        terminalOutput: [],
        terminalLoading: false,
        
        init() {
            console.log('🐛 Lorapok Widget Initialized');
            window.__lorapok_console_logs = window.__lorapok_console_logs || [];
            this.consoleLogs = (window.__lorapok_console_logs || []).slice().reverse();
            
            // Start polling loop
            this.scheduleNextPoll();

            try {
                var h = localStorage.getItem('lorapok_clipboard_history');
                if (h) this.clipboardHistory = JSON.parse(h);
            } catch (e) {}
            try {
                var s = localStorage.getItem('lorapok_settings');
                if (s) {
                    var cfg = JSON.parse(s);
                    this.discordWebhook = cfg.discordWebhook || null;
                    this.discordEnabled = !!cfg.discordEnabled;
                    this.slackWebhook = cfg.slackWebhook || null;
                    this.slackChannel = cfg.slackChannel || null;
                    this.slackEnabled = !!cfg.slackEnabled;
                    this.mailTo = cfg.mailTo || null;
                    this.mailEnabled = !!cfg.mailEnabled;
                }
            } catch (e) {}
            window.addEventListener('lorapok:console-log', (ev) => {
                try { this.consoleLogs.unshift(ev.detail); if (this.consoleLogs.length > 200) this.consoleLogs.length = 200 } catch (e) {}
            });

            this.$watch('clientLogWritingEnabled', value => {
                window.__lorapok_persist_active = !!value;
            });
            // Initial sync
            window.__lorapok_persist_active = !!this.clientLogWritingEnabled;
        },

        scheduleNextPoll() {
            let interval = parseInt(this.pollingInterval) || 5000;
            if (interval < 1000) interval = 1000;
            
            setTimeout(async () => {
                if (this.isOpen) { // Only poll when open to save resources
                    await this.fetchData();
                }
                this.scheduleNextPoll();
            }, interval);
        },

        toggleModal() { this.isOpen = !this.isOpen; if (this.isOpen) this.fetchData(); },
        closeModal() { this.isOpen = false; },
        toggleSettings() { this.openSettings = !this.openSettings; if(this.openSettings) { this.showDevInfo = false; this.openTerminal = false; this.showUsage = false; } },
        toggleDev() { this.showDevInfo = !this.showDevInfo; if(this.showDevInfo) { this.openSettings = false; this.openTerminal = false; this.showUsage = false; } },
        toggleTerminal() { this.openTerminal = !this.openTerminal; if(this.openTerminal) { this.openSettings = false; this.showDevInfo = false; this.showUsage = false; } },
        toggleUsage() { this.showUsage = !this.showUsage; if(this.showUsage) { this.openSettings = false; this.showDevInfo = false; this.openTerminal = false; } },
        
        copyAllLogs(mode = 'client') {
            try {
                let logs = mode === 'client' ? this.consoleLogs : (this.data?.server_logs || []);
                var txt = logs.map(l => `[${l.at}] [${l.level}] ${l.msg}`).join('\n');
                navigator.clipboard.writeText(txt);
            } catch (e) {}
        },
        
        clearLogs() {
            try { this.consoleLogs = []; window.__lorapok_console_logs = []; } catch (e) {}
        },

        async clearServerLogs() {
            if(!confirm('Are you sure you want to delete all server logs? This cannot be undone.')) return;
            try {
                const r = await fetch('/execution-monitor/api/logs/clear', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content }
                });
                if(r.ok) {
                    this.fetchData(); // Refresh
                } else {
                    alert('Failed to clear logs');
                }
            } catch(e) { console.error(e); }
        },

        async runCommand(cmd) {
            this.terminalLoading = true;
            this.terminalOutput.push({ text: '> ' + cmd, type: 'input' });
            try {
                const r = await fetch('/execution-monitor/api/command', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content },
                    body: JSON.stringify({ command: cmd })
                });
                const res = await r.json();
                if(res.success) {
                    this.terminalOutput.push({ text: res.output || '(No Output)', type: 'output' });
                } else {
                    this.terminalOutput.push({ text: 'Error: ' + res.message, type: 'error' });
                }
            } catch(e) {
                this.terminalOutput.push({ text: 'Request Failed: ' + e.message, type: 'error' });
            } finally {
                this.terminalLoading = false;
            }
        },

        async fetchData() {
            try {
                const url = new URL('/execution-monitor/api/data', window.location.origin);
                if (this.activeTab === 'logs' && this.logDate) {
                    url.searchParams.append('log_date', this.logDate);
                }
                const r = await fetch(url);
                if (!r.ok) {
                    throw new Error(`HTTP Error ${r.status}: ${r.statusText}`);
                }
                const responseText = await r.text();
                try {
                    const freshData = JSON.parse(responseText);
                    
                    // Update main data
                    this.data = freshData;

                    // Add to session history if fingerprint is new
                    if (freshData.fingerprint && !this.sessionHistory.some(h => h.fingerprint === freshData.fingerprint)) {
                        this.sessionHistory.unshift(freshData);
                        if (this.sessionHistory.length > 50) this.sessionHistory.length = 50;
                    }
                } catch (jsonErr) {
                    console.error('❌ Lorapok JSON Parse Error:', jsonErr, 'Response was:', responseText);
                    throw new Error('Invalid JSON response from server');
                }
                
                // console.log('📊 Lorapok Data:', this.data); // Reduce noise
                this.hasAlerts = this.data.alerts && this.data.alerts.length > 0;
                this.lastException = this.data.last_exception || null;
                
                // Sync settings only if not editing
                if (this.data.settings && !this.openSettings) {
                    this.discordWebhook = this.data.settings.discord_webhook;
                    this.discordEnabled = !!this.data.settings.discord_enabled;
                    this.slackWebhook = this.data.settings.slack_webhook;
                    this.slackChannel = this.data.settings.slack_channel;
                    this.slackEnabled = !!this.data.settings.slack_enabled;
                    this.mailTo = this.data.settings?.mail_to || '';
                    this.mailEnabled = !!this.data.settings?.mail_enabled;
                    this.mailHost = this.data.settings?.mail_host || '';
                    this.mailPort = this.data.settings?.mail_port || '';
                    this.mailUsername = this.data.settings?.mail_username || '';
                    this.mailPassword = this.data.settings?.mail_password || '';
                    this.mailEncryption = this.data.settings?.mail_encryption || '';
                    this.mailFromAddress = this.data.settings?.mail_from_address || '';
                    this.rateLimitMinutes = this.data.settings?.rate_limit_minutes || 30;
                    this.pollingInterval = this.data.settings?.polling_interval || 5000;
                    this.routeThreshold = this.data.settings?.route_threshold || 1000;
                    this.queryThreshold = this.data.settings?.query_threshold || 100;
                    this.queryCountThreshold = this.data.settings?.query_count_threshold || 50;
                    this.memoryThreshold = this.data.settings?.memory_threshold || 128;
                    this.clientLogWritingEnabled = !!this.data.settings?.client_log_writing_enabled;
                }
            } catch (e) { 
                console.error('❌ Lorapok Fetch Error:', e.message, e); 
            }
        },
        async saveSettings() {
            if (this.slackEnabled && !this.slackWebhook) {
                this.isError = true;
                this.modalMessage = 'Please enter a valid Slack webhook URL.';
                this.showSuccessModal = true;
                return;
            }
            this.isSaving = true;
            try {
                let payload = {};
                if (this.settingsTab === 'discord') payload = { discord_webhook: this.discordWebhook, discord_enabled: this.discordEnabled ? 1 : 0 };
                else if (this.settingsTab === 'slack') payload = { slack_webhook: this.slackWebhook, slack_channel: this.slackChannel, slack_enabled: this.slackEnabled ? 1 : 0 };
                else if (this.settingsTab === 'email') payload = { mail_to: this.mailTo, mail_enabled: this.mailEnabled ? 1 : 0, mail_host: this.mailHost, mail_port: this.mailPort, mail_username: this.mailUsername, mail_password: this.mailPassword, mail_encryption: this.mailEncryption, mail_from_address: this.mailFromAddress };
                else if (this.settingsTab === 'alerts') payload = { route_threshold: this.routeThreshold, query_threshold: this.queryThreshold, query_count_threshold: this.queryCountThreshold, memory_threshold: this.memoryThreshold };
                else if (this.settingsTab === 'advanced') payload = { rate_limit_minutes: this.rateLimitMinutes, client_log_writing_enabled: this.clientLogWritingEnabled ? 1 : 0, polling_interval: this.pollingInterval };
                
                const r = await fetch('/execution-monitor/api/settings', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content },
                    body: JSON.stringify(payload)
                });
                if (r.ok) {
                    localStorage.setItem('lorapok_settings', JSON.stringify(payload));
                    const test = await fetch('/execution-monitor/api/settings/test', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content },
                        body: JSON.stringify(payload)
                    });
                    const resStart = await test.json();
                    this.isError = !test.ok || !resStart.success;
                    this.modalMessage = test.ok && resStart.success ? 'Settings saved and test alert sent!' : 'Saved, but test failed: ' + (resStart.error || 'Check server');
                    this.showSuccessModal = true;
                } else { throw new Error('Server sync failed'); }
            } catch (e) {
                this.isError = true;
                this.modalMessage = e.message;
                this.showSuccessModal = true;
            } finally { this.isSaving = false; }
        },
        async copyQuery(sql, idx) {
            if (!sql) return;
            try {
                await navigator.clipboard.writeText(sql);
                this.copiedIndex = idx;
                setTimeout(() => this.copiedIndex = null, 1500);
                this.clipboardHistory = this.clipboardHistory.filter(h => h.sql !== sql);
                this.clipboardHistory.unshift({ sql: sql, at: new Date().toISOString() });
                if (this.clipboardHistory.length > 20) this.clipboardHistory.length = 20;
                localStorage.setItem('lorapok_clipboard_history', JSON.stringify(this.clipboardHistory));
            } catch (e) {}
        },
        formatMsgHtml(msg) {
            if (!msg) return '';
            if (typeof msg !== 'string') return this.escapeHtml(String(msg));
            try {
                var parsed = JSON.parse(msg);
                return '<pre class="bg-gray-50 p-2 rounded border-l-4 border-purple-500 text-gray-800">' + this.escapeHtml(JSON.stringify(parsed, null, 2)) + '</pre>';
            } catch (e) { return this.escapeHtml(msg); }
        },
        escapeHtml(str) {
            var m = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
            return String(str).replace(/[&<>"']/g, function(s) { return m[s]; });
        }
    };
};

if (window.Alpine) { Alpine.data('monitorWidget', window.monitorWidget); } 
else { document.addEventListener('alpine:init', () => { Alpine.data('monitorWidget', window.monitorWidget); }); }
</script>
<script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script src="{{ asset('vendor/lorapok/monitor-listener.js') }}" defer></script>
