@props(['href' => null,'color' => 'gray'])
@php
$colors = [
    'gray' => 'bg-gray-800 dark:bg-gray-200 text-white dark:text-gray-800 hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:ring-indigo-500 dark:focus:ring-offset-gray-800',
    'blue' => 'bg-indigo-600 text-white hover:bg-indigo-500 focus:bg-indigo-700 active:bg-indigo-900 focus:ring-indigo-500',
    'green' => 'bg-emerald-600 text-white hover:bg-emerald-500 focus:bg-emerald-700 active:bg-emerald-900 focus:ring-emerald-500',
    'red' => 'bg-red-600 text-white hover:bg-red-500 focus:bg-red-700 active:bg-red-900 focus:ring-red-500',
];
$colorClasses = $colors[$color] ?? $colors['gray'];
@endphp
<a {{ $attributes->merge(['class' => "inline-flex items-center px-4 py-2 m-2 $colorClasses"]) }}>
    {{ $slot }}
</a>