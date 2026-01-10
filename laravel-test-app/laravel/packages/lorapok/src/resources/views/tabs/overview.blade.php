<div class="space-y-6">
    <!-- Exception Alert (Global) -->
    <div x-show="lastException" class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r shadow-sm">
        <div class="flex">
            <div class="flex-shrink-0">
                <span class="text-2xl">⚠️</span>
            </div>
            <div class="ml-3 w-full">
                <p class="text-sm font-bold text-red-800">
                    Last Request Failed: <span x-text="lastException?.message"></span>
                </p>
                <p class="text-xs text-red-600 mt-1">
                    File: <span x-text="lastException?.file"></span>:<span x-text="lastException?.line"></span>
                </p>
                <!-- Trace (Simple) -->
                <div class="mt-2" x-data="{ showTrace: false }">
                    <button @click="showTrace = !showTrace" class="text-xs text-red-500 hover:text-red-700 underline focus:outline-none">
                        <span x-text="showTrace ? 'Hide Trace' : 'Show Trace'"></span>
                    </button>
                    <div x-show="showTrace" class="mt-2 p-2 bg-red-100 rounded text-xs font-mono overflow-x-auto whitespace-pre-wrap" style="max-height: 150px;">
                        <template x-for="(frame, idx) in lastException?.trace || []">
                            <div class="mb-1">
                                #<span x-text="idx"></span> <span x-text="frame.file"></span>:<span x-text="frame.line"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Current Route Card -->
        <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-xl p-5 border-2 border-blue-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-2xl">🛣️</span>
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Current Route</span>
                </div>
                <p class="text-base font-mono font-semibold text-purple-900 break-all leading-tight"><span x-text="data?.current_route ?? 'N/A'"></span></p>
            </div>
            <div class="mt-4 space-y-3">
                <div class="flex flex-col gap-1" x-show="data?.controller_action">
                    <span class="text-[8px] font-black text-purple-500 uppercase tracking-widest">Calling Action</span>
                    <div class="bg-purple-50 border border-purple-100 px-3 py-2 rounded-xl">
                        <code class="text-[11px] text-purple-900 font-bold break-all" x-text="data.controller_action"></code>
                    </div>
                </div>
                <div class="flex flex-col gap-1" x-show="data?.view_path">
                    <span class="text-[8px] font-black text-blue-500 uppercase tracking-widest">Rendered Blade</span>
                    <div class="bg-blue-50 border border-blue-100 px-3 py-2 rounded-xl">
                        <code class="text-[11px] text-blue-900 font-bold break-all" x-text="data.view_path"></code>
                    </div>
                </div>
            </div>
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
                        <p class="text-sm font-medium text-red-700" x-text="typeof alert === 'object' ? alert.message : alert"></p>
                    </div>
                </template>
            </div>
        </div>
    </template>

    <!-- Cache ROI Recommendations -->
    <template x-if="(data?.cache_roi || []).length > 0">
        <div class="space-y-4">
            <div class="flex items-center gap-3 px-2">
                <div class="h-10 w-10 bg-indigo-600 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-200">
                    <span class="text-xl">💰</span>
                </div>
                <div>
                    <h3 class="text-lg font-black text-gray-900 uppercase tracking-tighter">Cache ROI Analysis</h3>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">High-Impact Performance Suggestions</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4">
                <template x-for="(roi, idx) in data.cache_roi" :key="idx">
                    <div class="group relative overflow-hidden bg-white border border-gray-100 p-6 rounded-[2rem] shadow-sm hover:shadow-xl hover:border-indigo-200 transition-all duration-500">
                        <!-- Impact Badge -->
                        <div class="absolute top-6 right-6">
                            <span :class="{
                                'bg-red-50 text-red-600 border-red-100': roi.impact === 'High',
                                'bg-amber-50 text-amber-600 border-amber-100': roi.impact === 'Medium'
                            }" class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border" x-text="roi.impact + ' Impact'"></span>
                        </div>

                        <div class="flex flex-col gap-4">
                            <div class="flex items-start gap-4">
                                <div class="p-3 bg-indigo-50 rounded-2xl text-2xl group-hover:scale-110 transition-transform">
                                    <span x-show="roi.type === 'route'">🚀</span>
                                    <span x-show="roi.type === 'query'">🗄️</span>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-lg font-black text-gray-900 tracking-tight" x-text="roi.title"></h4>
                                    <code class="text-[10px] text-indigo-600 font-bold block mt-1 break-all" x-text="roi.subject"></code>
                                </div>
                            </div>

                            <div class="bg-indigo-900 rounded-2xl p-4 text-white shadow-inner">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-xs">📈</span>
                                    <span class="text-xs font-black uppercase tracking-widest" x-text="roi.roi"></span>
                                </div>
                                <p class="text-xs text-indigo-100 leading-relaxed font-medium" x-text="roi.action"></p>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </template>
</div>
