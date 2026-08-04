@props([
    'label' => '',
    'value' => '',
    'id' => ''
])

<div {{ $attributes->merge(['class' => 'flex flex-col']) }}>
    @if($label)
        <label 
            for="{{ $id }}" 
            class="block mb-1 text-sm font-semibold text-gray-700 dark:text-gray-300"
        >
            {{ $label }}
        </label>
    @endif
    <div 
        id="{{ $id }}"
        class="text-gray-900 bg-gray-200 dark:text-gray-100 font-normal leading-relaxed py-2"
    >
        {{ $value ?? '-' }}
    </div>
</div>   