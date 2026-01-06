<div class="grid grid-cols-3 gap-4">
    <div class="bg-blue-50 rounded-lg p-6">
        <p class="text-sm text-blue-600">Current Route</p>
        <p class="text-2xl font-bold text-blue-900" x-text="data.current_route?.path || 'N/A'"></p>
    </div>
    <div class="bg-purple-50 rounded-lg p-6">
        <p class="text-sm text-purple-600">Total Queries</p>
        <p class="text-2xl font-bold text-purple-900" x-text="data.total_queries || 0"></p>
    </div>
    <div class="bg-green-50 rounded-lg p-6">
        <p class="text-sm text-green-600">Memory Peak</p>
        <p class="text-2xl font-bold text-green-900" x-text="data.memory?.peak || 'N/A'"></p>
    </div>
</div>
