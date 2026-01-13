<div class="space-y-10 py-4">
    <!-- Premium Header -->
    <div class="text-center relative">
        <div class="absolute -top-6 left-1/2 -translate-x-1/2 text-6xl opacity-10 select-none larvae-wiggle">🏆</div>
        <h3 class="text-2xl font-black text-gray-900 uppercase tracking-[0.25em] relative z-10">Optimization Quests</h3>
        <div class="h-1 w-20 bg-gradient-to-r from-purple-500 via-amber-400 to-blue-500 mx-auto mt-3 rounded-full shadow-sm"></div>
        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-4">Master your application performance to unlock legendary badges</p>
    </div>

    <!-- Quest Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 px-2">
        <template x-for="a in (data?.achievements || [])" :key="a.slug">
            <div class="group relative bg-white rounded-[2rem] p-6 border border-gray-100 transition-all duration-500 hover:shadow-2xl hover:shadow-purple-100 hover:-translate-y-1 overflow-hidden" 
                 :class="a.unlocked_at ? 'bg-gradient-to-br from-white to-amber-50/30 border-amber-100' : 'opacity-90'">
                
                <!-- Background Decorative Larvae -->
                <div class="absolute -right-4 -bottom-4 text-7xl opacity-[0.03] group-hover:opacity-[0.08] transition-opacity select-none" 
                     :class="a.unlocked_at ? 'text-amber-500' : 'text-gray-400'"
                     x-text="a.unlocked_at ? '🦋' : '🐛'"></div>

                <div class="flex items-start gap-6 relative z-10">
                    <!-- Icon Container -->
                    <div class="relative shrink-0">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-4xl shadow-inner transition-transform duration-500 group-hover:scale-110"
                             :class="a.unlocked_at ? 'bg-amber-100' : 'bg-gray-100 grayscale contrast-75'">
                            <span x-text="a.name.split(' ')[0]" :class="a.unlocked_at ? 'larvae-wiggle' : ''"></span>
                        </div>
                        <template x-if="a.unlocked_at">
                            <div class="absolute -top-2 -right-2 bg-green-500 text-white w-6 h-6 rounded-full flex items-center justify-center text-[10px] border-2 border-white shadow-lg">
                                ✓
                            </div>
                        </template>
                    </div>

                    <!-- Details -->
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-1">
                            <h4 class="text-base font-black text-gray-800 tracking-tight group-hover:text-purple-600 transition-colors" x-text="a.name.split(' ').slice(1).join(' ')"></h4>
                            <template x-if="a.unlocked_at">
                                <span class="text-[8px] bg-amber-500 text-white px-2 py-0.5 rounded-md font-black uppercase tracking-tighter shadow-sm animate-pulse">LEGENDARY</span>
                            </template>
                        </div>
                        <p class="text-xs text-gray-500 leading-relaxed font-medium mb-4" x-text="a.description"></p>
                        
                        <!-- Premium Progress Section -->
                        <div class="space-y-2">
                            <div class="flex justify-between items-end">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full" :class="a.unlocked_at ? 'bg-amber-400' : 'bg-purple-400'"></span>
                                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Progress</span>
                                </div>
                                <span class="text-xs font-black" :class="a.unlocked_at ? 'text-amber-600' : 'text-purple-600'">
                                    <span x-text="a.progress"></span> / <span x-text="a.goal"></span>
                                </span>
                            </div>
                            <div class="w-full h-2.5 bg-gray-100 rounded-full p-0.5 border border-gray-50 shadow-inner">
                                <div class="h-full rounded-full transition-all duration-1000 relative" 
                                     :style="`width: ${(a.progress / a.goal) * 100}%`"
                                     :class="a.unlocked_at ? 'bg-gradient-to-r from-amber-400 to-orange-500 shadow-sm' : 'bg-gradient-to-r from-purple-500 to-blue-500 shadow-sm'">
                                    
                                    <!-- Animated Progress Glimmer -->
                                    <div class="absolute inset-0 bg-white/20 skew-x-[-20deg] animate-[pulse_2s_infinite]"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Empty State -->
    <template x-if="(data?.achievements || []).length === 0">
        <div class="bg-gray-50/50 rounded-[3rem] p-16 text-center border-2 border-dashed border-gray-200">
            <div class="text-6xl mb-6 opacity-20 larvae-wiggle">🛠️</div>
            <h4 class="text-xl font-black text-gray-400 uppercase tracking-widest mb-2">No Quests Discovered</h4>
            <p class="text-sm text-gray-400 max-w-sm mx-auto leading-relaxed">Your journey toward peak performance starts with a single optimization. Run your first request to begin.</p>
            <div class="mt-8">
                <code class="bg-white px-4 py-2 rounded-xl border border-gray-200 text-xs text-purple-500 font-bold">php artisan monitor:audit</code>
            </div>
        </div>
    </template>

    <!-- Footer Tip -->
    <div class="bg-indigo-900 rounded-[2rem] p-6 text-white overflow-hidden relative group transition-all hover:scale-[1.01]">
        <div class="absolute top-0 right-0 p-4 text-4xl opacity-10 rotate-12 group-hover:rotate-0 transition-transform">💡</div>
        <div class="relative z-10">
            <h5 class="text-xs font-black uppercase tracking-[0.3em] text-indigo-300 mb-2">Pro Tip</h5>
            <p class="text-sm font-medium leading-relaxed opacity-90">Achievements are tracked globally across your application. Use the <span class="text-amber-400 font-bold">MonitorAudit</span> command to view your optimization stats directly from the terminal.</p>
        </div>
    </div>
</div>