@props(['type' => 'button', 'variant' => 'primary'])

@php
$classes = [
    'primary' => 'bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white shadow-[0_0_20px_rgba(6,182,212,0.4)] hover:shadow-[0_0_30px_rgba(6,182,212,0.6)]',
    'secondary' => 'bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white shadow-[0_0_20px_rgba(168,85,247,0.4)] hover:shadow-[0_0_30px_rgba(168,85,247,0.6)]',
    'success' => 'bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white shadow-[0_0_20px_rgba(34,197,94,0.4)]',
    'danger' => 'bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white shadow-[0_0_20px_rgba(239,68,68,0.4)]',
    'outline' => 'bg-transparent border-2 border-cyan-500/50 text-cyan-400 hover:bg-cyan-500/10 hover:border-cyan-400',
];

$class = $classes[$variant] ?? $classes['primary'];
@endphp

<button 
    type="{{ $type }}"
    {{ $attributes->merge(['class' => "px-6 py-3 rounded-xl font-semibold transition-all duration-200 transform hover:scale-105 active:scale-95 $class"]) }}
>
    {{ $slot }}
</button>
