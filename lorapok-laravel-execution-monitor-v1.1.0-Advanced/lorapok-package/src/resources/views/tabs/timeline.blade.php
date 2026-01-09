<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
            <span class="text-2xl">🐛</span> Larvae Trail (Timeline)
        </h3>
        <span class="text-xs text-gray-500 font-mono" x-text="'Fingerprint: ' + (data?.fingerprint ?? 'N/A')"></span>
    </div>

    <!-- Timeline Bar -->
    <div class="relative w-full h-12 bg-gray-100 rounded-full flex overflow-hidden shadow-inner border border-gray-200">
        <template x-for="(event, idx) in data?.timeline" :key="idx">
            <div 
                class="h-full border-r border-white border-opacity-30 relative group cursor-help transition-all hover:brightness-110"
                :style="`width: ${data?.request?.duration ? Math.max(2, (event.duration / data.request.duration / 10)) : (100 / (data?.timeline?.length || 1))}%`"
                :class="{
                    'bg-purple-500': event.name === 'boot',
                    'bg-blue-500': event.name === 'routing',
                    'bg-indigo-400': event.name.startsWith('middleware'),
                    'bg-pink-500': event.name === 'controller',
                    'bg-red-400': event.name === 'queries',
                    'bg-green-500': event.name === 'response',
                    'bg-yellow-400': !['boot','routing','controller','queries','response'].includes(event.name) && !event.name.startsWith('middleware')
                }"
            >
                <!-- Tooltip -->
                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-max bg-gray-900 text-white text-xs rounded py-2 px-3 opacity-0 group-hover:opacity-100 transition pointer-events-none z-50">
                    <div class="font-bold text-purple-300" x-text="event.name.toUpperCase()"></div>
                    <div x-text="'Duration: ' + event.duration.toFixed(2) + 'ms'"></div>
                    <div x-text="'At: ' + event.timestamp.toFixed(2) + 'ms'"></div>
                    <div class="absolute top-full left-1/2 -translate-x-1/2 border-8 border-transparent border-t-gray-900"></div>
                </div>
            </div>
        </template>
    </div>

    <!-- Timeline Legend -->
    <div class="flex flex-wrap gap-4 text-xs">
        <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-purple-500"></span> Boot</div>
        <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-blue-500"></span> Routing</div>
        <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-indigo-400"></span> Middleware</div>
        <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-red-400"></span> Queries</div>
        <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-green-500"></span> Response</div>
    </div>
</div>
