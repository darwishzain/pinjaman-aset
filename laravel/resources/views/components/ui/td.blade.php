@props(['align' => 'left'])

@php
    $alignClasses = match($align) {
        'center' => 'text-center',
        'right' => 'text-right',
        default => 'text-left',
    };
@endphp

<td {{ $attributes->merge(['class' => "px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200 $alignClasses"]) }}>
    {{ $slot }}
</td>   