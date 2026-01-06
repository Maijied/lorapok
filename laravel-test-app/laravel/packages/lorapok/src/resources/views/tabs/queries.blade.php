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
			<div class="bg-white rounded-lg p-3 border shadow-sm" :class="selectedQueryIndex===idx? 'ring-2 ring-purple-300': ''" @click="selectedQueryIndex = idx">
				<div class="flex justify-between items-start gap-4">
					<div class="flex-1 font-mono text-sm text-gray-800 break-words" x-text="q.sql || q"></div>
					<div class="flex items-center gap-3">
						<div class="text-sm text-gray-500" x-text="(q.time || 0) + ' ms'"></div>
						<button type="button" class="ml-2 px-3 py-1 border rounded bg-gray-100 text-sm text-gray-700 hover:bg-gray-200" @click.prevent.stop="copyQuery(q.sql || q, idx)">
							<span x-show="copiedIndex !== idx">Copy</span>
							<span x-show="copiedIndex === idx">Copied!</span>
						</button>
					</div>
				</div>
			</div>
		</template>
	</div>
</div>
