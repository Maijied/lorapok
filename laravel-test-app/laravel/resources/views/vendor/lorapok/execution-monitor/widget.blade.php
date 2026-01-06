<div id="execution-monitor-widget" x-data="monitorWidget()" x-cloak>
    <button @click="toggleModal()" class="fixed z-50 w-14 h-14 rounded-full bg-gradient-to-r from-purple-600 to-blue-600 text-white shadow-lg bottom-6 right-6">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
        </svg>
    </button>

    <div x-show="isOpen" x-transition class="fixed inset-0 z-50 flex items-center justify-center" style="display:none;">
        <div class="absolute inset-0 bg-gray-900 bg-opacity-75" @click="closeModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-6xl max-h-[90vh] overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-blue-600 px-6 py-4 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-white">Lorapok Monitor</h2>
                    <p class="text-purple-100 text-sm">#MaJHiBhai</p>
                </div>
                <button @click="closeModal()" class="text-white"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="border-b border-gray-200 bg-gray-50">
                <nav class="flex space-x-1 px-6">
                    <button @click="activeTab='overview'" :class="activeTab==='overview'?'border-purple-500 text-purple-600':'border-transparent text-gray-500'" class="px-4 py-3 text-sm font-medium border-b-2">📊 Overview</button>
                    <button @click="activeTab='routes'" :class="activeTab==='routes'?'border-purple-500 text-purple-600':'border-transparent text-gray-500'" class="px-4 py-3 text-sm font-medium border-b-2">🛣️ Routes</button>
                    <button @click="activeTab='queries'" :class="activeTab==='queries'?'border-purple-500 text-purple-600':'border-transparent text-gray-500'" class="px-4 py-3 text-sm font-medium border-b-2">🗄️ Queries</button>
                </nav>
            </div>
            <div class="p-6 overflow-y-auto" style="max-height:calc(90vh - 180px);">
                <div x-show="activeTab==='overview'">@include('execution-monitor::tabs.overview')</div>
                <div x-show="activeTab==='routes'">@include('execution-monitor::tabs.routes')</div>
                <div x-show="activeTab==='queries'">@include('execution-monitor::tabs.queries')</div>
            </div>
        </div>
    </div>
</div>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
function monitorWidget() {
    return {
        isOpen: false,
        activeTab: 'overview',
        data: {},
        init() { this.fetchData(); },
        toggleModal() { this.isOpen = !this.isOpen; if(this.isOpen) this.fetchData(); },
        closeModal() { this.isOpen = false; },
        async fetchData() {
            try {
                const response = await fetch('/execution-monitor/api/data');
                this.data = await response.json();
            } catch(e) { console.error(e); }
        }
    }
}
</script>
<style>[x-cloak] { display: none !important; }</style>
