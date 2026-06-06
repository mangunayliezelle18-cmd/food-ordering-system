@props(['active' => false])

@php
$classes = ($active)
            ? 'bg-gray-900 text-white px-3 py-2 rounded-md text-sm font-medium border-b-2 border-amber-400'
            : 'text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>