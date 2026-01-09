<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Current Route Card -->
        <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-xl p-5 border-2 border-blue-200 shadow-sm">
            <div class="flex items-center gap-2 mb-2">
                <span class="text-2xl">🛣️</span>
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Current Request</span>
            </div>
            <p class="text-base font-mono font-semibold text-purple-900 break-all leading-tight">
                <span class="bg-blue-100 text-blue-700 px-1 rounded mr-1" x-text="data?.request?.method ?? 'GET'"></span>
                <span x-text="data?.request?.path ?? 'N/A'"></span>
            </p>
            <div class="mt-2 flex items-center gap-3 text-xs text-gray-500">
                <span>Status: <span :class="data?.request?.status >= 400 ? 'text-red-600' : 'text-green-600'" class="font-bold" x-text="data?.request?.status"></span></span>
                <span>Size: <span x-text="(data?.request?.response_size / 1024).toFixed(2) + ' KB'"></span></span>
            </div>
            <!-- Comparison Badge -->
            <template x-if="data?.comparison">
                <div class="mt-3 flex items-center gap-1.5">
                    <span :class="data.comparison.diff_ms > 0 ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'" class="px-2 py-0.5 rounded-full text-[10px] font-bold">
                        <span x-text="data.comparison.diff_ms > 0 ? '↑ ' : '↓ '"></span>
                        <span x-text="Math.abs(data.comparison.diff_percent) + '%'"></span>
                    </span>
                    <span class="text-[10px] text-gray-400">vs historical avg</span>
                </div>
            </template>
        </div>

        <!-- Total Queries Card -->
        <div class="bg-gradient-to-br from-pink-50 to-red-50 rounded-xl p-5 border-2 border-pink-200 shadow-sm">
            <div class="flex items-center gap-2 mb-2">
                <span class="text-2xl">🗄️</span>
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Queries</span>
            </div>
            <p class="text-4xl font-bold text-pink-600"><span x-text="(data?.total_queries ?? 0)"></span></p>
            <p class="text-xs text-gray-500 mt-1">Total time: <span x-text="(data?.total_query_time ?? 0).toFixed(2) + 'ms'"></span></p>
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

    <!-- Request Narrative -->
    <template x-if="data?.narrative">
        <div class="bg-indigo-50 border-2 border-indigo-200 rounded-xl p-5 shadow-sm">
            <h3 class="text-lg font-bold text-indigo-800 mb-3 flex items-center gap-2">
                <span class="text-2xl">📝</span> Performance Story
            </h3>
            <div class="text-sm text-indigo-900 leading-relaxed" x-html="data.narrative.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')"></div>
        </div>
    </template>

    <!-- Eloquent Fix Suggestions -->
    <template x-if="(data?.suggestions || []).length > 0">
        <div class="bg-purple-50 border-2 border-purple-200 rounded-xl p-5">
            <h3 class="text-lg font-bold text-purple-800 mb-3 flex items-center gap-2">
                <span class="text-2xl">🤖</span> N+1 Fix Preview
            </h3>
            <div class="space-y-4">
                <template x-for="(sug, idx) in data.suggestions" :key="idx">
                    <div class="bg-white rounded-lg p-4 border border-purple-100">
                        <p class="font-bold text-purple-900 mb-1" x-text="sug.title"></p>
                        <p class="text-xs text-gray-500 mb-2" x-text="sug.impact"></p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                            <div>
                                <p class="text-[10px] uppercase font-bold text-red-500 mb-1">Before (Slow)</p>
                                <pre class="bg-gray-900 text-gray-300 p-2 rounded text-[10px] overflow-x-auto" x-text="sug.before"></pre>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-bold text-green-500 mb-1">After (Fast)</p>
                                <pre class="bg-gray-900 text-green-400 p-2 rounded text-[10px] overflow-x-auto" x-text="sug.after"></pre>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </template>

    <!-- Cache ROI Suggestions -->
    <template x-if="(data?.cache_roi || []).length > 0">
        <div class="bg-green-50 border-2 border-green-200 rounded-xl p-5">
            <h3 class="text-lg font-bold text-green-800 mb-3 flex items-center gap-2">
                <span class="text-2xl">💰</span> Cache ROI Opportunities
            </h3>
            <div class="space-y-3">
                <template x-for="(roi, idx) in data.cache_roi" :key="idx">
                    <div class="bg-white rounded-lg p-3 border border-green-100 flex justify-between items-center">
                        <div class="flex-1 mr-4">
                            <p class="text-xs font-mono text-gray-600 truncate" x-text="roi.query"></p>
                            <p class="text-xs text-green-700 mt-1" x-text="roi.tip"></p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-green-600" x-text="'-' + roi.potential_savings + 'ms'"></p>
                            <p class="text-[10px] text-gray-400 uppercase">Potential Saving</p>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </template>

    <!-- Recommendations Engine -->
    <template x-if="(data?.recommendations || []).length > 0">
        <div class="bg-yellow-50 border-2 border-yellow-200 rounded-xl p-5">
            <h3 class="text-lg font-bold text-yellow-800 mb-3 flex items-center gap-2">
                <span class="text-2xl">💡</span> Smart Recommendations
            </h3>
            <ul class="space-y-2">
                <template x-for="(rec, idx) in data.recommendations" :key="idx">
                    <li class="flex items-start gap-2 text-sm text-yellow-900">
                        <span class="mt-1 text-yellow-600">•</span>
                        <span x-text="rec"></span>
                    </li>
                </template>
            </ul>
        </div>
    </template>

    <!-- Active Quests -->
    <template x-if="(data?.achievements || []).filter(a => !a.unlocked_at && a.progress > 0).length > 0">
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
            <h4 class="text-xs font-bold text-gray-500 uppercase mb-3">🔥 Active Quests</h4>
            <div class="space-y-3">
                <template x-for="a in data.achievements.filter(a => !a.unlocked_at && a.progress > 0)" :key="a.slug">
                    <div>
                        <div class="flex justify-between text-[10px] mb-1">
                            <span class="text-gray-600 font-medium" x-text="a.name"></span>
                            <span class="text-gray-400" x-text="a.progress + ' / ' + a.goal"></span>
                        </div>
                        <div class="w-full h-1 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-purple-500" :style="`width: ${(a.progress / a.goal) * 100}%`"></div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </template>

    <!-- Recent Performance (History) -->
    <div class="bg-white border-2 border-gray-100 rounded-xl p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <span class="text-2xl">📈</span> Recent Performance
            </h3>
            <div class="flex gap-4 text-[10px] uppercase font-bold text-gray-400">
                <div>Avg Duration: <span class="text-purple-600" x-text="(data?.stats?.avg_duration || 0) + 'ms'"></span></div>
                <div>Avg Queries: <span class="text-purple-600" x-text="(data?.stats?.avg_queries || 0)"></span></div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="text-left text-xs text-gray-500 uppercase tracking-wider">
                        <th class="pb-2">Route</th>
                        <th class="pb-2">Duration</th>
                        <th class="pb-2">Queries</th>
                        <th class="pb-2 text-right">Time</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <template x-for="(item, idx) in (data?.history || []).slice(0, 5)" :key="idx">
                        <tr class="text-sm">
                            <td class="py-2">
                                <span class="bg-gray-100 text-gray-600 px-1 rounded text-xs" x-text="item.method"></span>
                                <span class="font-mono text-purple-700 ml-1" x-text="item.route"></span>
                            </td>
                            <td class="py-2">
                                <span :class="item.duration > 1 ? 'text-red-600 font-bold' : 'text-green-600'" x-text="(item.duration * 1000).toFixed(0) + 'ms'"></span>
                            </td>
                            <td class="py-2 text-gray-600" x-text="item.queries"></td>
                            <td class="py-2 text-right text-xs text-gray-400" x-text="new Date(item.timestamp).toLocaleTimeString()"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- About Lorapok / Mascot -->
    <div class="bg-gradient-to-r from-purple-600 to-blue-600 rounded-xl p-6 text-white relative overflow-hidden shadow-lg" style="background: linear-gradient(to right, var(--lorapok-primary), var(--lorapok-secondary))">
        <div class="relative z-10 flex flex-col md:flex-row items-center gap-6">
            <div class="text-7xl larvae-wiggle">🐛</div>
            <div>
                <h3 class="text-xl font-bold mb-2">About Lorapok</h3>
                <p class="text-purple-100 text-sm leading-relaxed">
                    Lorapok is a <strong>soldier fly larvae</strong> and the mascot of this package. 
                    Like its namesake insect that efficiently processes waste, Lorapok identifies performance bottlenecks 
                    in your Laravel application to help you make it fast! ⚡
                </p>
                <div class="mt-4 flex items-center gap-4">
                    <div class="text-xs">
                        <p class="opacity-75 uppercase font-bold">Created by</p>
                        <p class="font-semibold text-white">#MaJHi_BHai</p>
                    </div>
                    <div class="h-8 w-px bg-white bg-opacity-20"></div>
                    <div class="text-xs">
                        <p class="opacity-75 uppercase font-bold">Version</p>
                        <p class="font-semibold text-white">v1.1.0-Advanced</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Decorative background larvae -->
        <div class="absolute -right-4 -bottom-4 text-white opacity-10 text-9xl transform rotate-12">🐛</div>
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
