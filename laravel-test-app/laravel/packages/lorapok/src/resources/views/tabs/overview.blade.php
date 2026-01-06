<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Current Route Card -->
        <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-xl p-5 border-2 border-blue-200 shadow-sm">
            <div class="flex items-center gap-2 mb-2">
                <span class="text-2xl">🛣️</span>
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Current Route</span>
            </div>
            <p class="text-base font-mono font-semibold text-purple-900 break-all leading-tight"><span x-text="data?.current_route ?? 'N/A'"></span></p>
        </div>

        <!-- Total Queries Card -->
        <div class="bg-gradient-to-br from-pink-50 to-red-50 rounded-xl p-5 border-2 border-pink-200 shadow-sm">
            <div class="flex items-center gap-2 mb-2">
                <span class="text-2xl">🗄️</span>
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Queries</span>
            </div>
            <p class="text-4xl font-bold text-pink-600"><span x-text="(data?.total_queries ?? 0)"></span></p>
        </div>

        <!-- Memory Peak Card -->
        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-5 border-2 border-green-200 shadow-sm">
            <div class="flex items-center gap-2 mb-2">
                <span class="text-2xl">💾</span>
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Memory Peak</span>
            </div>
            <p class="text-4xl font-bold text-green-600"><span x-text="data?.memory_peak ?? 'N/A'"></span></p>
        </div>
    </div>

    <template x-if="(data?.alerts || []).length > 0">
        <div class="bg-red-50 border-2 border-red-300 rounded-xl p-5">
            <h3 class="text-lg font-bold text-red-800 mb-3 flex items-center gap-2">
                <span class="text-2xl">⚠️</span> Performance Alerts
            </h3>
            <div class="space-y-2">
                <template x-for="(alert, idx) in data.alerts" :key="idx">
                    <div class="bg-white rounded-lg p-3 border border-red-200">
                        <p class="text-sm font-medium text-red-700" x-text="alert"></p>
                    </div>
                </template>
            </div>
        </div>
    </template>
</div>
