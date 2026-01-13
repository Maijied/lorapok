<div class="pl-4 border-l border-blue-100 py-1 mt-1">
    <div class="flex items-center gap-2">
        <span class="text-[10px] font-bold text-blue-400 uppercase tracking-tighter">Level {{ $level }}</span>
        <span class="text-[9px] text-gray-400 font-mono">Rendered at {{ now()->format('H:i:s.u') }}</span>
    </div>
    @if($level < ($max ?? 5))
        @include('lab.fragment', ['level' => $level + 1, 'max' => $max])
    @endif
</div>
