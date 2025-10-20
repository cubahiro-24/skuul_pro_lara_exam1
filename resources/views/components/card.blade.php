<div {{ $attributes->merge(['class' => 'bg-gray-900/40 backdrop-blur-xl border border-cyan-500/30 rounded-2xl shadow-[0_0_30px_rgba(6,182,212,0.2)] overflow-hidden']) }}>
    @if(isset($header))
        <div class="px-6 py-4 border-b border-cyan-500/20 bg-gradient-to-r from-cyan-500/10 to-blue-500/10">
            {{ $header }}
        </div>
    @endif
    
    <div class="p-6">
        {{ $slot }}
    </div>
</div>
