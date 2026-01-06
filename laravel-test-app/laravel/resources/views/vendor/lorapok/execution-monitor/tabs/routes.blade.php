<div>
    <h3 class="text-lg font-semibold mb-4">Route Execution Times</h3>
    <template x-if="Object.keys(data.routes || {}).length === 0">
        <p class="text-center text-gray-500">No route data available</p>
    </template>
    <template x-for="(route, path) in data.routes" :key="path">
        <div class="bg-white border rounded p-4 mb-2">
            <p class="font-mono text-sm" x-text="path"></p>
            <p class="text-xs text-gray-600" x-text="route.duration ? (route.duration*1000).toFixed(2) + ' ms' : 'N/A'"></p>
        </div>
    </template>
</div>
