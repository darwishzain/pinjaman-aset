@props([
    'disabled' => false,
    'id'=>'',
    'label'=>'',
])
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>
        <label
            for="{{ $id }}"
            class="block mb-1"
        >
            {{ $label }}
            @if($attributes->has('required'))
                <span class="text-red-500 font-bold ml-0.5">*</span>
            @endif
        </label>
        <select
            @disabled($disabled)
            id="{{ $id }}"
            name="{{ $id }}"
            {{ $attributes->merge(['class' => 'w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm']) }}
        >
            {{ $slot }}
        </select>
    </div>
</div>