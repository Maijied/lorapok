<div class="space-y-6">
    <div class="p-6 bg-amber-50 rounded-3xl border border-amber-100">
        <div class="flex items-center gap-3 mb-4">
            <span class="p-2 bg-amber-500 text-white rounded-lg shadow-lg shadow-amber-200">⚔️</span>
            <h5 class="text-sm font-black text-amber-900 uppercase">Step 1: Concurrent Queries</h5>
        </div>
        <button @click="runStress('queries')" :disabled="loading" class="w-full py-4 bg-amber-600 text-white rounded-2xl font-bold hover:bg-amber-700 transition-all active:scale-95 shadow-lg shadow-amber-100">
            <span x-show="!states.queries">Launch 500 Queries</span>
            <span x-show="states.queries" class="text-white font-black animate-pulse">✓ Executing...</span>
        </button>
    </div>

    <div class="p-6 bg-rose-50 rounded-3xl border border-rose-100" :class="!states.queries ? 'opacity-50 grayscale' : ''">
        <div class="flex items-center gap-3 mb-4">
            <span class="p-2 bg-rose-500 text-white rounded-lg shadow-lg shadow-rose-200">💎</span>
            <h5 class="text-sm font-black text-rose-900 uppercase">Step 2: Transaction Lock</h5>
        </div>
        <button @click="runStress('lock')" :disabled="!states.queries || loading" class="w-full py-4 bg-rose-600 text-white rounded-2xl font-bold hover:bg-rose-700 transition-all active:scale-95">
            Simulate Transaction Lock
        </button>
    </div>
</div>
