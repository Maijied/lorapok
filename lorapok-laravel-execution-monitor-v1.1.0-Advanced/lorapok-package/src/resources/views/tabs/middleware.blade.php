<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-800">Middleware Performance</h3>
        <span class="text-xs text-gray-500 uppercase tracking-wider">Sorted by duration</span>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Middleware</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <template x-for="(mdata, name) in Object.entries(data?.middleware || {}).sort((a,b) => b[1].duration - a[1].duration)" :key="name">
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-purple-700" x-text="mdata[0]"></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <span :class="mdata[1].duration > 0.05 ? 'text-red-600 font-bold' : 'text-green-600'" x-text="(mdata[1].duration * 1000).toFixed(2) + ' ms'"></span>
                        </td>
                    </tr>
                </template>
                <template x-if="Object.keys(data?.middleware || {}).length === 0">
                    <tr>
                        <td colspan="2" class="px-6 py-10 text-center text-gray-500 italic">
                            No middleware tracked. Use the <code class="bg-gray-100 px-1 rounded">MeasuresMiddleware</code> trait in your middleware classes.
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 text-sm text-blue-800">
        <p><strong>💡 Tip:</strong> To track a middleware, add the <code>MeasuresMiddleware</code> trait to your middleware class and it will automatically appear here.</p>
    </div>
</div>
