@props([
    'disabled' => false,
    'id'=>'',
    'label'=>''
])
<div>
    <label 
        for="{{ $id }}"
        class="block mb-1"
    >
        {{ $label }}
    </label>
    <input 
        @disabled($disabled)
        id="{{ $id }}"
        name="{{ $id }}"
        {{ $attributes->merge(['class' => 'w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm']) }}
    >
</div>
