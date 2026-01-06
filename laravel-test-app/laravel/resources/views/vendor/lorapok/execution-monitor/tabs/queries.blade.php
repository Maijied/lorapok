<div>
    <h3 class="text-lg font-semibold mb-4">Database Queries</h3>
    <template x-if="!data.queries || data.queries.length === 0">
        <p class="text-center text-gray-500">No queries executed</p>
    </template>
    <template x-for="(query, index) in data.queries" :key="index">
        <div class="bg-white border rounded p-4 mb-2">
            <span class="text-xs text-gray-500">#<span x-text="index+1"></span></span>
            <pre class="text-xs mt-2" x-text="query.sql"></pre>
            <span class="text-xs text-blue-600" x-text="query.time + ' ms'"></span>
        </div>
    </template>
</div>
