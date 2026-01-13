<div class="space-y-6">
    <div class="p-6 bg-blue-50 rounded-3xl border border-blue-100">
        <div class="flex items-center gap-3 mb-4">
            <span class="p-2 bg-blue-500 text-white rounded-lg shadow-lg shadow-blue-200">🖼️</span>
            <h5 class="text-sm font-black text-blue-900 uppercase">Recursive Component Nesting</h5>
        </div>
        <p class="text-xs text-blue-700 mb-4 font-medium leading-relaxed">This will trigger a deep tree of Blade partials to test view path resolution and render performance.</p>
        <button @click="runRender()" :disabled="loading" class="w-full py-4 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 active:scale-95">
            Start Heavy Render
        </button>
    </div>
    
    <div x-show="renderResult" class="p-4 bg-white rounded-2xl border border-blue-100 shadow-inner max-h-40 overflow-y-auto" x-html="renderResult"></div>
</div>
