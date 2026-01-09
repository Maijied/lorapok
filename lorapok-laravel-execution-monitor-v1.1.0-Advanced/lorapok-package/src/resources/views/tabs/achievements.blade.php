<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
            <span class="text-2xl">🏆</span> Optimization Quests
        </h3>
        <span class="text-xs text-gray-500 uppercase tracking-wider">Unlock badges by optimizing your app</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <template x-for="a in (data?.achievements || [])" :key="a.slug">
            <div class="bg-white rounded-xl p-4 border-2 shadow-sm transition-all hover:scale-[1.02]" 
                 :class="a.unlocked_at ? 'border-purple-200 bg-gradient-to-br from-white to-purple-50' : 'border-gray-100 opacity-75'">
                <div class="flex items-start gap-4">
                    <div class="text-4xl filter" :class="a.unlocked_at ? '' : 'grayscale contrast-50'">
                        <span x-text="a.name.split(' ')[0]"></span>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <h4 class="font-bold text-gray-800" x-text="a.name.split(' ').slice(1).join(' ')"></h4>
                            <template x-if="a.unlocked_at">
                                <span class="text-[10px] bg-purple-100 text-purple-700 px-2 py-0.5 rounded-full font-bold uppercase">Unlocked!</span>
                            </template>
                        </div>
                        <p class="text-xs text-gray-500 mt-1" x-text="a.description"></p>
                        
                        <!-- Progress Bar -->
                        <div class="mt-4">
                            <div class="flex justify-between text-[10px] mb-1">
                                <span class="text-gray-400">Progress</span>
                                <span class="font-bold text-purple-600" x-text="a.progress + ' / ' + a.goal"></span>
                            </div>
                            <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-purple-500 transition-all duration-500" 
                                     :style="`width: ${(a.progress / a.goal) * 100}%`"
                                     :class="a.unlocked_at ? 'bg-purple-600' : 'bg-purple-400'"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <template x-if="(data?.achievements || []).length === 0">
        <div class="bg-gray-50 rounded-xl p-10 text-center">
            <div class="text-4xl mb-3">🛠️</div>
            <p class="text-gray-500 italic">No achievements found. Start optimizing to earn your first badge!</p>
            <p class="text-xs text-gray-400 mt-2">Note: Achievements require database migrations. Run <code>php artisan migrate</code> if you haven't yet.</p>
        </div>
    </template>
</div>
