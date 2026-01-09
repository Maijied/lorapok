<div x-data="{ copiedIndex: null }">
	<h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
		<span class="text-2xl">🗄️</span> Database Queries
		<span class="text-sm font-normal text-gray-500" x-text="(data?.queries?.length) ? '(' + data.queries.length + ' total)' : ''"></span>
	</h3>

	<template x-if="!(data?.queries && data.queries.length > 0)">
		<div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-600">No queries captured yet.</div>
	</template>

	<div class="space-y-3">
		<template x-for="(q, idx) in (data?.queries || [])" :key="idx">
			<div class="bg-white rounded-lg p-3 border shadow-sm" :class="selectedQueryIndex===idx? 'ring-2 ring-purple-300': ''" @click="selectedQueryIndex = idx" x-data="{ revealed: false }">
				<div class="flex justify-between items-start gap-4">
					<div class="flex-1 font-mono text-sm text-gray-800 break-words">
                        <div x-show="!revealed" x-text="q.sql || q"></div>
                        <div x-show="revealed" x-text="q.original_sql" class="text-purple-700 font-bold"></div>
                        <template x-if="q.is_masked">
                            <div class="mt-1">
                                <span class="text-[10px] bg-yellow-100 text-yellow-700 px-1.5 py-0.5 rounded border border-yellow-200">Masked for Privacy</span>
                                <template x-if="q.original_sql">
                                    <button @click.stop="revealed = !revealed" class="ml-2 text-[10px] text-purple-600 hover:underline">
                                        <span x-text="revealed ? 'Hide' : 'Reveal'"></span>
                                    </button>
                                </template>
                            </div>
                        </template>
                        <template x-for="(tip, tIdx) in (q.tips || [])" :key="tIdx">
                            <div class="mt-1 flex items-start gap-1 text-[10px] text-red-600 bg-red-50 p-1 rounded border border-red-100">
                                <span>⚠️</span>
                                <span x-text="tip.tip"></span>
                            </div>
                        </template>
                    </div>
					<div class="flex items-center gap-3">
						<div class="text-sm text-gray-500" x-text="(q.time || 0) + ' ms'"></div>
						<button type="button" class="ml-2 px-3 py-1 border rounded bg-gray-100 text-sm text-gray-700 hover:bg-gray-200" @click.prevent.stop="copyQuery(revealed ? q.original_sql : (q.sql || q), idx)">
							<span x-show="copiedIndex !== idx">Copy</span>
							<span x-show="copiedIndex === idx">Copied!</span>
						</button>
					</div>
				</div>
			</div>
		</template>
	</div>
</div>
