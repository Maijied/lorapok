<div id="execution-monitor-widget" x-data="monitorWidget()" x-cloak>
    <!-- Main Larvae Button -->
    <button @click="toggleModal()" class="lorapok-btn" style="position:fixed;bottom:20px;right:20px;z-index:9999;width:80px;height:80px;border:none;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);border-radius:50%;cursor:pointer;box-shadow:0 8px 20px rgba(102,126,234,0.4);display:flex;align-items:center;justify-content:center;font-size:42px;transition:all 0.3s ease">
        🐛
        <span x-show="hasAlerts" style="position:absolute;top:-5px;right:-5px;width:28px;height:28px;background:#ef4444;border-radius:50%;color:white;font-size:16px;font-weight:bold;display:flex;align-items:center;justify-content:center;animation:pulse 1.5s infinite;border:3px solid white">!</span>
    </button>

    <!-- Main Monitor Modal -->
    <div x-show="isOpen" x-transition class="fixed inset-0 z-[10000] flex items-center justify-center" style="display:none">
        <div class="absolute inset-0 bg-gray-900 bg-opacity-75" @click="closeModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-6xl max-h-[90vh] overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-blue-600 px-6 py-4 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-white flex items-center gap-2"><span class="text-2xl">🐛</span> Lorapok Monitor</h2>
                    <p class="text-purple-100 text-sm">#MaJHiBhai</p>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Quests Button -->
                    <button title="Optimization Quests" @click.stop="activeTab='quests'" :class="activeTab==='quests' ? 'bg-white/20 ring-2 ring-white/30' : ''" class="modal-action-btn" style="width:44px;height:44px;border-radius:12px;background:rgba(255,255,255,0.08);display:flex;align-items:center;justify-content:center;transition:all 0.3s cubic-bezier(0.4, 0, 0.2, 1);">
                        <span class="text-xl larvae-animate" style="filter: drop-shadow(0 0 8px rgba(255,255,255,0.3))">🏆</span>
                    </button>

                    <button title="Developer Info" @click.stop="toggleDev()" :class="showDevInfo ? 'bg-white/20 ring-2 ring-white/30' : ''" class="modal-action-btn" style="width:44px;height:44px;border-radius:12px;background:rgba(255,255,255,0.08);display:flex;align-items:center;justify-content:center;transition:all 0.3s cubic-bezier(0.4, 0, 0.2, 1);">
                        <svg viewBox="0 0 48 48" class="larvae-wiggle" style="width:22px;height:22px">
                            <g fill="none" stroke="#fff" stroke-width="1.2">
                                <path d="M12 28c2-8 12-10 18-6 6 4 6 12 0 16-6 4-16 4-18-4" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="22" cy="18" r="3" fill="#fff" />
                            </g>
                        </svg>
                    </button>

                    <button title="Settings" @click.stop="toggleSettings()" :class="openSettings ? 'bg-white/20 ring-2 ring-white/30' : ''" class="modal-action-btn" style="width:44px;height:44px;border-radius:12px;background:rgba(255,255,255,0.08);display:flex;align-items:center;justify-content:center;transition:all 0.3s cubic-bezier(0.4, 0, 0.2, 1);">
                        <svg viewBox="0 0 24 24" class="larvae-spin" style="width:18px;height:18px">
                            <path fill="#fff" d="M12 15.5A3.5 3.5 0 1 0 12 8.5a3.5 3.5 0 0 0 0 7z"/>
                            <path fill="#fff" d="M19.4 15a7.94 7.94 0 0 0 .1-1 7.94 7.94 0 0 0-.1-1l2.1-1.6a.5.5 0 0 0 .1-.6l-2-3.5a.5.5 0 0 0-.6-.2l-2.5 1a8.1 8.1 0 0 0-1.7-1l-.4-2.7A.5.5 0 0 0 12.6 3h-4a.5.5 0 0 0-.5.4l-.4 2.7a8.1 8.1 0 0 0-1.7 1l-2.5-1a.5.5 0 0 0-.6.2l-2 3.5a.5.5 0 0 0 .1.6L4.5 12a7.94 7.94 0 0 0-.1 1c0 .3 0 .7.1 1L2.4 15.6a.5.5 0 0 0-.1.6l2 3.5a.5.5 0 0 0 .6.2l2.5-1a8.1 8.1 0 0 0 1.7 1l.4 2.7c.05.3.3.5.6.5h4c.3 0 .55-.2.6-.5l.4-2.7a8.1 8.1 0 0 0 1.7-1l2.5 1c.25.1.54 0 .6-.2l2-3.5a.5.5 0 0 0-.1-.6L19.4 15z"/>
                        </svg>
                    </button>

                    <button @click="closeModal()" class="text-white hover:text-purple-200 text-3xl ml-2" style="background:transparent;border:none;padding:0 6px">×</button>
                </div>
            </div>
            
            <div class="border-b border-gray-200 bg-gray-50">
                <nav class="flex space-x-1 px-6">
                    <button @click="activeTab='overview'" :class="activeTab==='overview'?'border-purple-500 text-purple-600':'border-transparent text-gray-500'" class="px-4 py-3 text-sm font-medium border-b-2 transition">📊 Overview</button>
                    <button @click="activeTab='timeline'" :class="activeTab==='timeline'?'border-purple-500 text-purple-600':'border-transparent text-gray-500'" class="px-4 py-3 text-sm font-medium border-b-2 transition">🐛 Timeline</button>
                    <button @click="activeTab='routes'" :class="activeTab==='routes'?'border-purple-500 text-purple-600':'border-transparent text-gray-500'" class="px-4 py-3 text-sm font-medium border-b-2 transition">🛣️ Routes</button>
                    <button @click="activeTab='queries'" :class="activeTab==='queries'?'border-purple-500 text-purple-600':'border-transparent text-gray-500'" class="px-4 py-3 text-sm font-medium border-b-2 transition">🗄️ Queries</button>
                    <button @click="activeTab='middleware'" :class="activeTab==='middleware'?'border-purple-500 text-purple-600':'border-transparent text-gray-500'" class="px-4 py-3 text-sm font-medium border-b-2 transition">🔗 Middleware</button>
                    <button @click="activeTab='logs'" :class="activeTab==='logs'?'border-purple-500 text-purple-600':'border-transparent text-gray-500'" class="px-4 py-3 text-sm font-medium border-b-2 transition">📝 Logs</button>
                </nav>
            </div>

            <div class="p-6 overflow-y-auto" style="max-height:calc(90vh - 220px)">
                <div x-show="activeTab==='overview'">@include('execution-monitor::tabs.overview')</div>
                <div x-show="activeTab==='timeline'">@include('execution-monitor::tabs.timeline')</div>
                <div x-show="activeTab==='routes'">@include('execution-monitor::tabs.routes')</div>
                <div x-show="activeTab==='queries'">@include('execution-monitor::tabs.queries')</div>
                <div x-show="activeTab==='middleware'">@include('execution-monitor::tabs.middleware')</div>
                <div x-show="activeTab==='quests'">@include('execution-monitor::tabs.achievements')</div>
                
                <!-- Logs Tab -->
                <div x-show="activeTab==='logs'" class="space-y-4">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-sm font-bold text-gray-800">Client Console & Network Logs</h3>
                            <p class="text-[10px] text-gray-400 uppercase font-bold">Real-time application activity</p>
                        </div>
                        <div class="flex gap-2">
                            <button @click="copyAllLogs()" class="px-3 py-1.5 bg-purple-600 text-white rounded-lg text-xs font-bold shadow-sm hover:bg-purple-700 transition flex items-center gap-1.5">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                Copy All
                            </button>
                            <button @click="clearLogs()" class="px-3 py-1.5 bg-white text-red-600 border border-red-100 rounded-lg text-xs font-bold shadow-sm hover:bg-red-50 transition">Clear</button>
                        </div>
                    </div>

                    <div class="space-y-2 overflow-y-auto pr-2" style="max-height:50vh;">
                        <template x-for="(log, idx) in consoleLogs" :key="idx">
                            <div class="bg-gray-50 border border-gray-100 rounded-xl overflow-hidden transition-all hover:border-purple-200" x-data="{ expanded: false }">
                                <!-- Log Header -->
                                <div @click="expanded = !expanded" class="flex items-center justify-between p-3 cursor-pointer hover:bg-white transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center justify-center w-6 h-6 rounded-lg shadow-sm" :class="{
                                            'bg-red-100 text-red-600': log.level === 'error',
                                            'bg-orange-100 text-orange-600': log.level === 'warn',
                                            'bg-blue-100 text-blue-600': log.level === 'info',
                                            'bg-gray-100 text-gray-600': log.level === 'log' || log.level === 'debug'
                                        }">
                                            <template x-if="log.level === 'error'"><span class="text-[10px]">🔴</span></template>
                                            <template x-if="log.level === 'warn'"><span class="text-[10px]">🟠</span></template>
                                            <template x-if="log.level === 'info'"><span class="text-[10px]">🔵</span></template>
                                            <template x-if="log.level === 'log' || log.level === 'debug'"><span class="text-[10px]">⚪</span></template>
                                        </div>
                                        <span class="text-[10px] font-mono text-gray-400" x-text="new Date(log.at).toLocaleTimeString()"></span>
                                        <span class="text-xs font-bold uppercase tracking-wider" :class="{
                                            'text-red-600': log.level === 'error',
                                            'text-orange-600': log.level === 'warn',
                                            'text-blue-600': log.level === 'info',
                                            'text-gray-600': log.level === 'log' || log.level === 'debug'
                                        }" x-text="log.level"></span>
                                        <span class="text-xs text-gray-700 truncate max-w-[300px] font-medium" x-text="log.msg"></span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button @click.stop="navigator.clipboard.writeText(log.msg); $el.innerHTML='✅'; setTimeout(()=>$el.innerHTML='📋', 1000)" class="p-1 hover:bg-gray-100 rounded transition text-gray-400 hover:text-purple-600" title="Copy Log">
                                            📋
                                        </button>
                                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>
                                <!-- Expanded Content -->
                                <div x-show="expanded" x-collapse x-cloak class="border-t border-gray-100 bg-white p-4">
                                    <div class="relative group">
                                        <div class="bg-gray-900 rounded-lg p-4 font-mono text-[11px] leading-relaxed overflow-x-auto text-emerald-400 shadow-inner" x-html="formatMsgHtml(log.msg)"></div>
                                        <button @click="navigator.clipboard.writeText(log.msg)" class="absolute top-2 right-2 p-2 bg-white/10 hover:bg-white/20 rounded-md text-white opacity-0 group-hover:opacity-100 transition-opacity">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <div x-show="consoleLogs.length===0" class="text-center py-12 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                            <span class="text-4xl block mb-2 opacity-20">📝</span>
                            <p class="text-sm text-gray-400 font-bold uppercase tracking-widest">No logs captured</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Professional Footer -->
            <div class="bg-gray-50 px-6 py-3 border-t border-gray-200 flex items-center justify-between">
                <div class="flex items-center gap-4 text-[10px] uppercase font-bold text-gray-400">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                        <span>System Live</span>
                    </div>
                    <div class="h-4 w-px bg-gray-300"></div>
                    <div class="flex items-center gap-1.5">
                        <span class="text-purple-600">v1.2.7-Advanced</span>
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
    <div x-show="showDevInfo" x-transition @click.away="showDevInfo = false" class="fixed inset-0 z-[11000] flex items-center justify-center" style="display:none">
        <div class="absolute inset-0 bg-gray-900 bg-opacity-75"></div>
        <div class="relative bg-gradient-to-br from-purple-600 via-blue-600 to-purple-700 rounded-3xl shadow-2xl p-8 max-w-md mx-4">
            <button @click="showDevInfo = false" class="absolute top-4 right-4 text-white text-2xl bg-white bg-opacity-10 rounded-full w-8 h-8 flex items-center justify-center">×</button>
            <div class="text-center text-white space-y-4">
                <div class="text-6xl mb-4 larvae-wiggle">🐛</div>
                <h2 class="text-2xl font-bold mb-2">Lorapok Monitor</h2>
                <p class="text-sm text-purple-100 mb-4">Laravel Performance Monitoring Package</p>
                <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-2xl p-6 space-y-3 text-left">
                    <div class="flex justify-center mb-4">
                        <img src="https://github.com/Maijied.png" alt="Mohammad Maizied Hasan Majumder" class="w-24 h-24 rounded-full border-4 border-white shadow-lg object-cover" />
                    </div>
                    <div class="flex items-center gap-2"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg><p class="font-semibold">Mohammad Maizied Hasan Majumder</p></div>
                    <div class="flex items-center gap-2"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/></svg><a href="mailto:mdshuvo40@gmail.com" class="hover:text-purple-200 text-sm">mdshuvo40@gmail.com</a></div>
                    <div class="flex items-center gap-2"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg><a href="https://github.com/Maijied" target="_blank" class="hover:text-purple-200 text-sm">github.com/Maijied</a></div>
                </div>
                <p class="text-xs text-purple-200 mt-4">#MaJHiBhai - Making Laravel Fast! ⚡</p>
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
                        <button @click="settingsTab='advanced'" :class="settingsTab==='advanced'?'bg-white text-purple-700 shadow':'text-purple-200 hover:bg-white hover:bg-opacity-10'" class="flex-1 py-2 text-xs font-bold uppercase tracking-wide rounded-md transition flex items-center justify-center gap-2">Advanced</button>
                    </div>

                    <div x-show="settingsTab==='discord'">
                        <label class="block text-xs text-purple-200 mb-1 font-semibold uppercase tracking-wider">Discord Webhook URL</label>
                        <input x-model="discordWebhook" class="w-full text-sm p-3 rounded-lg bg-white bg-opacity-10 text-white border-none focus:ring-2 focus:ring-purple-400 transition" />
                        <label class="inline-flex items-center mt-3 text-white text-sm cursor-pointer transition bg-white bg-opacity-5 rounded-lg p-2 w-full">
                            <input type="checkbox" x-model="discordEnabled" class="mr-3 rounded text-purple-600"/> Enable Discord Alerts
                        </label>
                    </div>

                    <div x-show="settingsTab==='slack'" style="display:none">
                        <label class="block text-xs text-purple-200 mb-1 font-semibold uppercase tracking-wider">Slack Webhook URL</label>
                        <input x-model="slackWebhook" class="w-full text-sm p-3 rounded-lg bg-white bg-opacity-10 text-white border-none focus:ring-2 focus:ring-purple-400 transition" />
                        <label class="inline-flex items-center mt-3 text-white text-sm cursor-pointer transition bg-white bg-opacity-5 rounded-lg p-2 w-full">
                            <input type="checkbox" x-model="slackEnabled" class="mr-3 rounded text-purple-600"/> Enable Slack Alerts
                        </label>
                    </div>

                    <div x-show="settingsTab==='email'" style="display:none" class="space-y-4">
                        <div>
                            <label class="block text-xs text-purple-200 mb-1 font-semibold uppercase tracking-wider">Alert Email (TO)</label>
                            <input x-model="mailTo" class="w-full text-sm p-3 rounded-lg bg-white bg-opacity-10 text-white border-none focus:ring-2 focus:ring-purple-400 transition" />
                            <label class="inline-flex items-center mt-3 text-white text-sm cursor-pointer transition bg-white bg-opacity-5 rounded-lg p-2 w-full">
                                <input type="checkbox" x-model="mailEnabled" class="mr-3 rounded text-purple-600"/> Enable Email Alerts
                            </label>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div><label class="block text-[10px] text-purple-200 uppercase">Host</label><input x-model="mailHost" class="w-full text-xs p-2 rounded bg-white/10 border-none" /></div>
                            <div><label class="block text-[10px] text-purple-200 uppercase">Port</label><input x-model="mailPort" class="w-full text-xs p-2 rounded bg-white/10 border-none" /></div>
                        </div>
                    </div>

                    <div x-show="settingsTab==='advanced'" style="display:none">
                        <label class="block text-xs text-purple-200 mb-1 font-semibold uppercase tracking-wider">Rate Limit (Minutes)</label>
                        <input x-model.number="rateLimitMinutes" type="number" class="w-full text-sm p-3 rounded-lg bg-white bg-opacity-10 text-white border-none focus:ring-2 focus:ring-purple-400 transition" />
                    </div>

                    <div class="flex gap-3 pt-6">
                        <button @click="saveSettings()" class="flex-1 py-3 bg-white text-purple-700 font-bold rounded-xl hover:bg-purple-50 transition shadow-lg">Save Changes</button>
                        <button @click="showSuccessModal = false" class="px-4 py-3 bg-black bg-opacity-20 text-white font-semibold rounded-xl hover:bg-opacity-30 transition">Cancel</button>
                    </div>
                </div>
            </div>

            <!-- Success/Error Toast -->
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
        var url = typeof arguments[0] === 'string' ? arguments[0] : arguments[0].url;
        var method = (arguments[1] && arguments[1].method) || 'GET';
        return origFetch.apply(this, arguments).then(function(response) {
            if (!url.includes('execution-monitor/api/data')) {
                pushLog('info', ['📡 ' + method + ' ' + url + ' [' + response.status + ']'], 'network');
            }
            return response;
        }).catch(function(err) {
            pushLog('error', ['❌ ' + method + ' ' + url + ' failed: ' + err.message], 'network');
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
        openSettings: false,
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
        activeTab: 'overview',
        data: { routes: {}, queries: [], alerts: [], total_queries: 0, total_query_time: 0 },
        consoleLogs: [],
        hasAlerts: false,
        clipboardHistory: [],
        selectedQueryIndex: null,
        copiedIndex: null,
        init() {
            console.log('🐛 Lorapok Widget Initialized');
            window.__lorapok_console_logs = window.__lorapok_console_logs || [];
            this.consoleLogs = (window.__lorapok_console_logs || []).slice().reverse();
            this.fetchData();
            setInterval(() => this.fetchData(), 5000);
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
        },
        toggleModal() { this.isOpen = !this.isOpen; if (this.isOpen) this.fetchData(); },
        closeModal() { this.isOpen = false; },
        toggleSettings() { this.openSettings = !this.openSettings; if(this.openSettings) this.showDevInfo = false; },
        toggleDev() { this.showDevInfo = !this.showDevInfo; if(this.showDevInfo) this.openSettings = false; },
        copyAllLogs() {
            try {
                var txt = this.consoleLogs.map(l => `[${l.at}] [${l.level}] ${l.msg}`).join('\n');
                navigator.clipboard.writeText(txt);
            } catch (e) {}
        },
        clearLogs() {
            try { this.consoleLogs = []; window.__lorapok_console_logs = []; } catch (e) {}
        },
        async fetchData() {
            try {
                const r = await fetch('/execution-monitor/api/data');
                this.data = await r.json();
                console.log('📊 Lorapok Data:', this.data);
                this.hasAlerts = this.data.alerts && this.data.alerts.length > 0;
                this.lastException = this.data.last_exception || null;
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
                }
            } catch (e) { console.error('❌ Lorapok Error:', e); }
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
                else if (this.settingsTab === 'advanced') payload = { rate_limit_minutes: this.rateLimitMinutes };
                
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
            return String(str).replace(/[&<>"]/g, function(s) { return m[s]; });
        }
    };
};

if (window.Alpine) { Alpine.data('monitorWidget', window.monitorWidget); } 
else { document.addEventListener('alpine:init', () => { Alpine.data('monitorWidget', window.monitorWidget); }); }
</script>
<script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script src="{{ asset('vendor/lorapok/monitor-listener.js') }}" defer></script>