@props(['active'])

@php
$classes = ($active ?? false)
    ? 'inline-flex items-center px-3.5 py-1.5 rounded-lg text-sm font-semibold text-white bg-indigo-600 dark:bg-indigo-500 shadow-sm transition'
    : 'inline-flex items-center px-3.5 py-1.5 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>