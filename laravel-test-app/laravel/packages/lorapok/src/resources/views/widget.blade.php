<div id="execution-monitor-widget" x-data="monitorWidget()" x-cloak>
    <!-- Main Larvae Button -->
    <button @click="toggleModal()" class="lorapok-btn" style="position:fixed;bottom:20px;right:20px;z-index:9999;width:80px;height:80px;border:none;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);border-radius:50%;cursor:pointer;box-shadow:0 8px 20px rgba(102,126,234,0.4);display:flex;align-items:center;justify-content:center;font-size:42px;transition:all 0.3s ease">
        🐛
        <span x-show="hasAlerts" style="position:absolute;top:-5px;right:-5px;width:28px;height:28px;background:#ef4444;border-radius:50%;color:white;font-size:16px;font-weight:bold;display:flex;align-items:center;justify-content:center;animation:pulse 1.5s infinite;border:3px solid white">!</span>
    </button>

    <!-- Developer Info Larvae (appears on hover) -->
    <div class="dev-larvae-container" style="position:fixed;bottom:20px;right:110px;z-index:9998;width:60px;height:60px">
        <button @click="showDevInfo = !showDevInfo" title="Developer Info - #MaJHiBhai" class="dev-larvae" style="width:100%;height:100%;border:none;background:linear-gradient(135deg,#a78bfa 0%,#7c3aed 100%);border-radius:50%;cursor:pointer;box-shadow:0 4px 12px rgba(167,139,250,0.4);display:flex;align-items:center;justify-content:center;font-size:32px;transition:all 0.3s ease;opacity:0;transform:scale(0.5)">
            🐛
        </button>
    </div>

    <!-- Developer Info Modal -->
    <div x-show="showDevInfo" x-transition @click.away="showDevInfo = false" class="fixed inset-0 z-[10000] flex items-center justify-center" style="display:none">
        <div class="absolute inset-0 bg-gray-900 bg-opacity-75"></div>
        <div class="relative bg-gradient-to-br from-purple-600 via-blue-600 to-purple-700 rounded-3xl shadow-2xl p-8 max-w-md mx-4">
            <button @click="showDevInfo = false" class="absolute top-4 right-4 text-white text-2xl">×</button>
            <div class="text-center text-white space-y-4">
                <div class="text-6xl mb-4">🐛</div>
                <h2 class="text-2xl font-bold mb-2">Lorapok Monitor</h2>
                <p class="text-sm text-purple-100 mb-4">Laravel Performance Monitoring Package</p>
                <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-2xl p-6 space-y-3 text-left">
                    <div class="flex items-center gap-2"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg><p class="font-semibold">Mohammad Maizied Hasan Majumder</p></div>
                    <div class="flex items-center gap-2"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/></svg><a href="mailto:mdshuvo40@gmail.com" class="hover:text-purple-200 text-sm">mdshuvo40@gmail.com</a></div>
                    <div class="flex items-center gap-2"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg><a href="https://www.linkedin.com/in/maizied/" target="_blank" class="hover:text-purple-200 text-sm">linkedin.com/in/maizied</a></div>
                </div>
                <p class="text-xs text-purple-200 mt-4">#MaJHiBhai - Making Laravel Fast! ⚡</p>
            </div>
        </div>
    </div>

    <!-- Main Monitor Modal -->
    <div x-show="isOpen" x-transition class="fixed inset-0 z-[10000] flex items-center justify-center" style="display:none">
        <div class="absolute inset-0 bg-gray-900 bg-opacity-75" @click="closeModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-6xl max-h-[90vh] overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-blue-600 px-6 py-4 flex items-center justify-between">
                <div><h2 class="text-xl font-bold text-white flex items-center gap-2"><span class="text-2xl">🐛</span> Lorapok Monitor</h2><p class="text-purple-100 text-sm">#MaJHiBhai</p></div>
                <button @click="closeModal()" class="text-white hover:text-purple-200 text-3xl">×</button>
            </div>
            <div class="border-b border-gray-200 bg-gray-50">
                <nav class="flex space-x-1 px-6">
                    <button @click="activeTab='overview'" :class="activeTab==='overview'?'border-purple-500 text-purple-600':'border-transparent text-gray-500'" class="px-4 py-3 text-sm font-medium border-b-2 transition">📊 Overview</button>
                    <button @click="activeTab='routes'" :class="activeTab==='routes'?'border-purple-500 text-purple-600':'border-transparent text-gray-500'" class="px-4 py-3 text-sm font-medium border-b-2 transition">🛣️ Routes</button>
                    <button @click="activeTab='queries'" :class="activeTab==='queries'?'border-purple-500 text-purple-600':'border-transparent text-gray-500'" class="px-4 py-3 text-sm font-medium border-b-2 transition">🗄️ Queries</button>
                </nav>
            </div>
            <div class="p-6 overflow-y-auto" style="max-height:calc(90vh - 180px)">
                <div x-show="activeTab==='overview'">@include('execution-monitor::tabs.overview')</div>
                <div x-show="activeTab==='routes'">@include('execution-monitor::tabs.routes')</div>
                <div x-show="activeTab==='queries'">@include('execution-monitor::tabs.queries')</div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes pulse{0%,100%{transform:scale(1);opacity:1}50%{transform:scale(1.15);opacity:0.8}}
@keyframes wiggle{0%,100%{transform:rotate(-5deg)}50%{transform:rotate(5deg)}}

.lorapok-btn:hover{transform:scale(1.1) rotate(5deg);animation:wiggle 0.5s ease-in-out infinite}

/* Developer larvae appears on main button hover */
.lorapok-btn:hover ~ .dev-larvae-container .dev-larvae,
.dev-larvae-container:hover .dev-larvae{
    opacity:1!important;
    transform:scale(1)!important;
}

.dev-larvae:hover{
    transform:scale(1.15) rotate(-5deg)!important;
    animation:wiggle 0.5s ease-in-out infinite;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
document.addEventListener('alpine:init',()=>{
    Alpine.data('monitorWidget',()=>({
        isOpen:false,
        showDevInfo:false,
        activeTab:'overview',
        data:null,
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
