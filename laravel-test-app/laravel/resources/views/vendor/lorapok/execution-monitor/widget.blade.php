<div id="execution-monitor-widget" x-data="monitorWidget()" x-cloak>
    <!-- Main Larvae Button -->
    <button @click="toggleModal()" class="lorapok-btn" style="position:fixed;bottom:20px;right:20px;z-index:9999;width:80px;height:80px;border:none;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);border-radius:50%;cursor:pointer;box-shadow:0 8px 20px rgba(102,126,234,0.4);display:flex;align-items:center;justify-content:center;font-size:42px;transition:all 0.3s ease">
        🐛
        <span x-show="hasAlerts" style="position:absolute;top:-5px;right:-5px;width:28px;height:28px;background:#ef4444;border-radius:50%;color:white;font-size:16px;font-weight:bold;display:flex;align-items:center;justify-content:center;animation:pulse 1.5s infinite;border:3px solid white">!</span>
    </button>

    <!-- Floating actions removed: buttons moved into the main modal header for a professional layout -->

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
                    <!-- Author Photo -->
                    <div class="flex justify-center mb-4">
                        <img src="{{ asset('vendor/lorapok/images/author.jpg') }}" alt="Mohammad Maizied Hasan Majumder" class="w-24 h-24 rounded-full border-4 border-white shadow-lg object-cover" />
                    </div>
                    <div class="flex items-center gap-2"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg><p class="font-semibold">Mohammad Maizied Hasan Majumder</p></div>

                    <div class="flex items-center gap-2"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/></svg><a href="mailto:mdshuvo40@gmail.com" class="hover:text-purple-200 text-sm">mdshuvo40@gmail.com</a></div>
                    <div class="flex items-center gap-2"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg><a href="https://www.linkedin.com/in/maizied/" target="_blank" class="hover:text-purple-200 text-sm">linkedin.com/in/maizied</a></div>
                </div>
                <p class="text-xs text-purple-200 mt-4">#MaJHiBhai - Making Laravel Fast! ⚡</p>
            </div>
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
                    <!-- Action buttons moved into modal header: meaningful larvae icons -->
                    <button title="Developer Info" @click.stop="toggleDev()" class="modal-action-btn" style="width:44px;height:44px;border-radius:8px;background:rgba(255,255,255,0.08);display:flex;align-items:center;justify-content:center;">
                        <!-- Larvae info icon (wiggle) -->
                        <svg viewBox="0 0 48 48" class="larvae-wiggle" style="width:22px;height:22px">
                            <g fill="none" stroke="#fff" stroke-width="1.2">
                                <path d="M12 28c2-8 12-10 18-6 6 4 6 12 0 16-6 4-16 4-18-4" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="22" cy="18" r="3" fill="#fff" />
                            </g>
                        </svg>
                    </button>

                    <button title="Settings" @click.stop="toggleSettings()" class="modal-action-btn" style="width:44px;height:44px;border-radius:8px;background:rgba(255,255,255,0.08);display:flex;align-items:center;justify-content:center;">
                        <!-- Larvae gear icon (spin) -->
                        <svg viewBox="0 0 24 24" class="larvae-spin" style="width:18px;height:18px;transform:scale(1);">
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
                    <button @click="activeTab='routes'" :class="activeTab==='routes'?'border-purple-500 text-purple-600':'border-transparent text-gray-500'" class="px-4 py-3 text-sm font-medium border-b-2 transition">🛣️ Routes</button>
                    <button @click="activeTab='queries'" :class="activeTab==='queries'?'border-purple-500 text-purple-600':'border-transparent text-gray-500'" class="px-4 py-3 text-sm font-medium border-b-2 transition">🗄️ Queries</button>
                    <button @click="activeTab='logs'" :class="activeTab==='logs'?'border-purple-500 text-purple-600':'border-transparent text-gray-500'" class="px-4 py-3 text-sm font-medium border-b-2 transition">📝 Logs</button>
                </nav>
            </div>
            <div class="p-6 overflow-y-auto" style="max-height:calc(90vh - 180px)">
                <div x-show="activeTab==='overview'">@include('execution-monitor::tabs.overview')</div>
                <div x-show="activeTab==='routes'">@include('execution-monitor::tabs.routes')</div>
                <div x-show="activeTab==='queries'">@include('execution-monitor::tabs.queries')</div>
                <div x-show="activeTab==='logs'" class="space-y-3">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold">Client Console Logs</h3>
                        <div class="flex gap-2">
                            <button @click="copyAllLogs()" class="px-3 py-1 bg-purple-600 text-white rounded text-sm">Copy All</button>
                            <button @click="downloadLogs()" class="px-3 py-1 bg-white text-purple-700 rounded text-sm">Download</button>
                            <button @click="clearLogs()" class="px-3 py-1 bg-red-600 text-white rounded text-sm">Clear</button>
                        </div>
                    </div>
                    <div class="bg-gray-100 rounded p-3" style="height:48vh;overflow-y:auto;font-family:monospace;font-size:12px;line-height:1.4;">
                        <template x-for="(log, idx) in consoleLogs" :key="idx">
                            <div class="mb-3 pb-2 border-b border-gray-300">
                                <div class="text-xs text-gray-500" x-text="log.at"></div>
                                <div class="mt-1">
                                    <span class="font-bold" :class="{'text-red-600': log.level==='error', 'text-orange-600': log.level==='warn', 'text-blue-600': log.level==='info'}" x-text="'['+log.level+'] '"></span>
                                    <span style="white-space:pre-wrap;word-break:break-word;" x-html="formatMsgHtml(log.msg)"></span>
                                </div>
                            </div>
                        </template>
                        <div x-show="consoleLogs.length===0" class="text-sm text-gray-500">No client logs captured yet.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Modal (Redesigned) -->
    <div x-show="openSettings" x-transition class="fixed inset-0 z-[10500] flex items-center justify-center p-4" style="display:none;pointer-events:auto">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-gray-900 bg-opacity-75" @click="openSettings = false"></div>
        
        <!-- Modal Content -->
        <div class="relative bg-gradient-to-br from-purple-600 via-blue-600 to-purple-700 text-white rounded-3xl shadow-2xl p-8 w-full max-w-md mx-4">
            <!-- Close Button -->
            <button @click="openSettings = false" class="absolute top-4 right-4 text-white text-2xl bg-white bg-opacity-10 rounded-full w-8 h-8 flex items-center justify-center hover:bg-opacity-20 transition">×</button>
            
            <div class="text-center space-y-4">
                <!-- Larvae Icon -->
                <div class="text-6xl mb-2 larvae-wiggle">🐛</div>
                <h2 class="text-2xl font-bold">Lorapok Settings</h2>
                <p class="text-sm text-purple-100 mb-4">Configure your notifications</p>
                
                <!-- Settings Form -->
                <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-2xl p-6 space-y-4 text-left">
                    
                    <div>
                        <label class="block text-xs text-purple-200 mb-1 font-semibold uppercase tracking-wider">Discord Webhook</label>
                        <input x-model="discordWebhook" class="w-full text-sm p-3 rounded-lg bg-white bg-opacity-10 text-white border-none placeholder-purple-300 focus:ring-2 focus:ring-purple-400 focus:bg-opacity-20 transition" placeholder="https://discordapp.com/api/webhooks/..." />
                        <label class="inline-flex items-center mt-2 text-white text-sm cursor-pointer hover:text-purple-200 transition">
                            <input type="checkbox" x-model="discordEnabled" class="mr-2 rounded text-purple-600 focus:ring-purple-500 w-4 h-4 bg-white bg-opacity-20 border-transparent"/> 
                            Enable Discord Alerts
                        </label>
                    </div>

                    <div>
                        <label class="block text-xs text-purple-200 mb-1 font-semibold uppercase tracking-wider">Slack Webhook</label>
                        <input x-model="slackWebhook" class="w-full text-sm p-3 rounded-lg bg-white bg-opacity-10 text-white border-none placeholder-purple-300 focus:ring-2 focus:ring-purple-400 focus:bg-opacity-20 transition" placeholder="https://hooks.slack.com/services/..." />
                        <label class="inline-flex items-center mt-2 text-white text-sm cursor-pointer hover:text-purple-200 transition">
                            <input type="checkbox" x-model="slackEnabled" class="mr-2 rounded text-purple-600 focus:ring-purple-500 w-4 h-4 bg-white bg-opacity-20 border-transparent"/> 
                            Enable Slack Alerts
                        </label>
                    </div>

                    <div>
                        <label class="block text-xs text-purple-200 mb-1 font-semibold uppercase tracking-wider">Alert Email (TO)</label>
                        <input x-model="mailTo" class="w-full text-sm p-3 rounded-lg bg-white bg-opacity-10 text-white border-none placeholder-purple-300 focus:ring-2 focus:ring-purple-400 focus:bg-opacity-20 transition" placeholder="admin@example.com" />
                        <label class="inline-flex items-center mt-2 text-white text-sm cursor-pointer hover:text-purple-200 transition">
                            <input type="checkbox" x-model="mailEnabled" class="mr-2 rounded text-purple-600 focus:ring-purple-500 w-4 h-4 bg-white bg-opacity-20 border-transparent"/> 
                            Enable Email Alerts
                        </label>
                    </div>

                    <div class="flex gap-3 pt-3">
                        <button @click="saveSettings()" class="flex-1 py-3 bg-white text-purple-700 font-bold rounded-xl hover:bg-purple-50 transition shadow-lg transform hover:-translate-y-0.5 active:translate-y-0">Save Changes</button>
                        <button @click="copyEnvSnippet()" class="px-4 py-3 bg-black bg-opacity-20 text-white font-semibold rounded-xl hover:bg-opacity-30 transition">Copy .env</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
                        <button @click="saveSettings()" class="flex-1 py-3 bg-white text-purple-700 font-bold rounded-xl hover:bg-purple-50 transition shadow-lg transform hover:-translate-y-0.5 active:translate-y-0">Save Changes</button>

.lorapok-btn:hover{transform:scale(1.1) rotate(5deg);animation:wiggle 0.5s ease-in-out infinite}


</div>
<style>
@keyframes pulse{0%,100%{transform:scale(1);opacity:1}50%{transform:scale(1.15);opacity:0.8}}
@keyframes wiggle{0%,100%{transform:rotate(-5deg)}50%{transform:rotate(5deg)}}
.lorapok-btn:hover ~ .dev-larvae-container .dev-larvae,
.dev-larvae-container:hover .dev-larvae{
    opacity:1!important;
    transform:scale(1)!important;
}

.dev-larvae:hover{
    transform:scale(1.15) rotate(-5deg)!important;
    animation:wiggle 0.5s ease-in-out infinite;
}

/* Floating action buttons styling moved here from inline markup to avoid breaking layout */
@keyframes larvae-bob { 0% { transform: translateY(0); } 50% { transform: translateY(-6px); } 100% { transform: translateY(0); } }
.larvae-icon, .larvae-gear { transition: transform 0.18s ease; }
.larvae-wiggle{ display:inline-block; animation: larvae-bob 1.2s ease-in-out infinite; }
.floating-actions .float-btn { background: linear-gradient(135deg,#f3f4f6, #e6e6ff); box-shadow: 0 6px 18px rgba(0,0,0,0.08); display:flex; align-items:center; justify-content:center }
.floating-actions .float-btn:hover { transform: translateY(-8px) scale(1.05); }
.floating-actions { transition: transform 0.2s ease; }

/* Modal header action buttons */
.modal-action-btn { border: none; cursor: pointer; }
.modal-action-btn:hover { background: rgba(255,255,255,0.12); transform: translateY(-2px); }
.larvae-wiggle { display:inline-block; animation: larvae-bob 1.2s ease-in-out infinite; }
@keyframes larvae-spin { 0% { transform: rotate(0deg) } 100% { transform: rotate(360deg) } }
.larvae-spin { animation: larvae-spin 6s linear infinite; }
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

    function pushLog(level, args){
        try{
            var parts = Array.prototype.slice.call(args).map(function(a){
                if(typeof a === 'string') return a;
                try{ return safeStringify(a); }catch(e){ return String(a); }
            });
            var msg = parts.join(' ');
            var entry = { level: level, msg: msg, at: (new Date()).toISOString() };
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
    window.addEventListener('error', function(ev){ pushLog('error', [ev.message + ' at ' + (ev.filename||'') + ':' + (ev.lineno||'')]); });
    window.addEventListener('unhandledrejection', function(ev){ pushLog('error', ['Unhandled Rejection', ev.reason]); });
})();
</script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
document.addEventListener('alpine:init',()=>{
    Alpine.data('monitorWidget',()=>({
        isOpen:false,
        showDevInfo:false,
        openSettings:false,
        // notification settings stored locally for developer convenience
        discordWebhook: null,
        discordEnabled: false,
        slackWebhook: null,
        slackEnabled: false,
        mailTo: null,
        mailEnabled: false,
        activeTab:'overview',
        data: { routes: {}, queries: [], alerts: [], total_queries: 0, total_query_time: 0 },
        consoleLogs: [],
        hasAlerts:false,
        // Clipboard & selection
        clipboardHistory: [],
        selectedQueryIndex: null,
        copiedIndex: null,
        init(){
            console.log('🐛 Lorapok Widget Initialized');
            this.fetchData();
            setInterval(()=>this.fetchData(),5000);
            // load clipboard history from localStorage
            try{
                var h = localStorage.getItem('lorapok_clipboard_history');
                if(h) this.clipboardHistory = JSON.parse(h);
            }catch(e){console.warn('Lorapok: load history failed',e)}

            // load local settings
            try{
                var s = localStorage.getItem('lorapok_settings');
                if(s){
                    var cfg = JSON.parse(s);
                    this.discordWebhook = cfg.discordWebhook || null;
                    this.discordEnabled = !!cfg.discordEnabled;
                    this.slackWebhook = cfg.slackWebhook || null;
                    this.slackEnabled = !!cfg.slackEnabled;
                    this.mailTo = cfg.mailTo || null;
                    this.mailEnabled = !!cfg.mailEnabled;
                }
            }catch(e){console.warn('Lorapok: load settings failed', e)}

            // keyboard shortcut: Ctrl+Shift+C to copy selected (or first) query
            window.addEventListener('keydown', (e)=>{
                if((e.ctrlKey||e.metaKey) && e.shiftKey && (e.code === 'KeyC' || e.key === 'C')){
                    e.preventDefault();
                    try{
                        var idx = this.selectedQueryIndex;
                        var q = (this.data && this.data.queries && this.data.queries[idx]) ? this.data.queries[idx] : (this.data && this.data.queries && this.data.queries[0]) || null;
                        if(q){
                            var sql = q.sql || q;
                            this.copyQuery(sql, idx||0);
                        }
                    }catch(err){console.warn('Lorapok: shortcut copy failed',err)}
                }
            });

            // populate initial logs from global buffer if present
            try{
                window.__lorapok_console_logs = window.__lorapok_console_logs || [];
                // copy existing into Alpine state (most recent first)
                this.consoleLogs = (window.__lorapok_console_logs||[]).slice().reverse();
            }catch(e){console.warn('Lorapok: init logs failed',e)}

            // listen for new logs dispatched from the console wrapper
            window.addEventListener('lorapok:console-log', (ev)=>{
                try{ this.consoleLogs.unshift(ev.detail); if(this.consoleLogs.length>200) this.consoleLogs.length=200 }catch(e){}
            });
        },
        toggleSettings(){ console.log("Lorapok: Toggle Settings", this.openSettings);
            // open settings; ensure dev info closed
            if(!this.openSettings){ this.showDevInfo = false; this.openSettings = true; } else { this.openSettings = false; }
        },
        toggleDev(){
            // open dev info; ensure settings closed
            if(!this.showDevInfo){ this.openSettings = false; this.showDevInfo = true; } else { this.showDevInfo = false; }
        },
        copyAllLogs(){
            try{
                var txt = this.consoleLogs.map(l=>`[${l.at}] [${l.level}] ${l.msg}`).join('\n');
                navigator.clipboard.writeText(txt);
                alert('Copied '+this.consoleLogs.length+' log lines to clipboard');
            }catch(e){console.warn('Lorapok: copy logs failed',e)}
        },
        downloadLogs(){
            try{
                var txt = this.consoleLogs.map(l=>`[${l.at}] [${l.level}] ${l.msg}`).join('\n');
                var b = new Blob([txt], { type: 'text/plain' });
                var url = URL.createObjectURL(b);
                var a = document.createElement('a'); a.href = url; a.download = 'lorapok-client-logs.txt'; document.body.appendChild(a); a.click(); a.remove(); URL.revokeObjectURL(url);
            }catch(e){console.warn('Lorapok: download logs failed',e)}
        },
        clearLogs(){
            try{ this.consoleLogs = []; window.__lorapok_console_logs = []; }catch(e){}
        },
        saveSettings(){
            try{
                localStorage.setItem('lorapok_settings', JSON.stringify({
                    discordWebhook: this.discordWebhook,
                    discordEnabled: this.discordEnabled,
                    slackWebhook: this.slackWebhook,
                    slackEnabled: this.slackEnabled,
                    mailTo: this.mailTo,
                    mailEnabled: this.mailEnabled
                }));
                alert('Settings saved to localStorage for this browser. For production, add them to your .env (MONITOR_DISCORD_WEBHOOK, MONITOR_SLACK_WEBHOOK, MONITOR_MAIL_TO)');
                this.openSettings = false;
            }catch(e){ console.warn('Lorapok: save settings failed', e) }
        },
        copyEnvSnippet(){
            try{
                var snippet = '\n# Lorapok monitor settings\nMONITOR_DISCORD_WEBHOOK=' + (this.discordWebhook || '') + "\nMONITOR_DISCORD_ENABLED=" + (this.discordEnabled ? 'true' : 'false') + '\n';
                snippet += 'MONITOR_SLACK_WEBHOOK=' + (this.slackWebhook || '') + "\nMONITOR_SLACK_ENABLED=" + (this.slackEnabled ? 'true' : 'false') + '\n';
                snippet += 'MONITOR_MAIL_TO=' + (this.mailTo || '') + "\nMONITOR_MAIL_ENABLED=" + (this.mailEnabled ? 'true' : 'false') + '\n';
                navigator.clipboard.writeText(snippet);
                alert('.env snippet copied to clipboard');
            }catch(e){ console.warn('Lorapok: copy env failed', e) }
        },
        async fetchData(){
            try{
                const r=await fetch('/execution-monitor/api/data');
                this.data=await r.json();
                this.hasAlerts=this.data.alerts&&this.data.alerts.length>0;
                console.log('📊 Lorapok Data:',this.data);
            }catch(e){
                console.error('❌ Lorapok Error:',e);
            }
        },
        // copy query string to clipboard and record history
        async copyQuery(sql, idx){
            if(!sql) return;
            try{
                await navigator.clipboard.writeText(sql);
                this.copiedIndex = idx;
                setTimeout(()=> this.copiedIndex = null, 1500);
                // add to history (unique recent)
                try{
                    var entry = { sql: sql, at: new Date().toISOString() };
                    // remove duplicate
                    this.clipboardHistory = this.clipboardHistory.filter(h=>h.sql !== sql);
                    this.clipboardHistory.unshift(entry);
                    if(this.clipboardHistory.length>20) this.clipboardHistory.length=20;
                    localStorage.setItem('lorapok_clipboard_history', JSON.stringify(this.clipboardHistory));
                }catch(err){console.warn('Lorapok: history save failed',err)}
            }catch(e){ console.warn('❌ Lorapok copy failed:',e); }
        },
        toggleModal(){
            console.log('🔄 Toggle Modal - Current:',this.isOpen);
            this.isOpen=!this.isOpen;
            if(this.isOpen)this.fetchData();
        },
        closeModal(){
            console.log('✖ Close Modal');
            this.isOpen=false;
        }
        ,formatMsg(msg){
            try{
                if(!msg) return '';
                if(typeof msg !== 'string') return String(msg);
                try{
                    var parsed = JSON.parse(msg);
                    return JSON.stringify(parsed, null, 2);
                }catch(e){}
                return msg;
            }catch(e){ return msg; }
        },
        formatMsgHtml(msg){
            try{
                if(!msg) return '';
                if(typeof msg !== 'string') return this.escapeHtml(msg);
                try{
                    var parsed = JSON.parse(msg);
                    var pretty = JSON.stringify(parsed, null, 2);
                    return '<pre style="margin:0;white-space:pre-wrap;word-break:break-word;background:#f5f5f5;padding:6px;border-radius:4px;border-left:3px solid #667eea;">' + this.escapeHtml(pretty) + '</pre>';
                }catch(e){}
                return this.escapeHtml(msg);
            }catch(e){ return this.escapeHtml(String(msg)); }
        },
        escapeHtml(str){
            var m = {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'};
            return String(str).replace(/[&<>"']/g, function(s){ return m[s]; });
        }
    }))
});
</script>

<!-- Load local published listener (fallback to CDN handled inside the published asset) -->
<!-- If app is configured to use Pusher, instantiate Echo so the published listener can attach to it -->
<script>
    (function(){
        try {
            var pusherKey = "{{ config('broadcasting.connections.pusher.key') ?? '' }}";
            var pusherCluster = "{{ config('broadcasting.connections.pusher.options.cluster') ?? config('broadcasting.connections.pusher.cluster') ?? '' }}";
            var broadcastDriver = "{{ config('broadcasting.default') ?? env('BROADCAST_DRIVER') ?? 'log' }}";
            if (broadcastDriver === 'pusher' && pusherKey) {
                if (typeof Echo === 'undefined') {
                    // attempt to create Echo AFTER the library loads (monitor-listener will load it),
                    // but create a placeholder init function to run once Echo is available
                    window.__lorapok_init_echo = function() {
                        if (typeof Echo !== 'undefined' && !window.Echo) {
                            try {
                                window.Echo = new Echo({ broadcaster: 'pusher', key: pusherKey, cluster: pusherCluster, forceTLS: true });
                                console.log('Lorapok: Echo instantiated');
                            } catch(e) { console.warn('Lorapok: Echo init failed', e); }
                        }
                    };
                }
            }
        } catch(e) { console.warn('Lorapok: broadcast init error', e); }
    })();
</script>
<script src="{{ asset('vendor/lorapok/monitor-listener.js') }}" defer></script>
