@props(['align' => 'left'])

@php
    $alignClasses = match($align) {
        'center' => 'text-center',
        'right' => 'text-right',
        default => 'text-left',
    };
@endphp

<th {{ $attributes->merge(['class' => "px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider $alignClasses"]) }}>
    {{ $slot }}
</th>   