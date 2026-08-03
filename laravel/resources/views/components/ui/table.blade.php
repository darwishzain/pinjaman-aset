@props(['striped' => false, 'hoverable' => true])

<div class="overflow-x-auto">
    <table {{ $attributes->merge(['class' => 'min-w-full divide-y divide-gray-200 dark:divide-gray-700']) }}>
        <thead class="bg-gray-50 dark:bg-gray-800">
            {{ $header }}
        </thead>
        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
            {{ $slot }}
        </tbody>
        @if(isset($footer))
            <tfoot class="bg-gray-50 dark:bg-gray-800 font-semibold">
                {{ $footer }}
            </tfoot>
        @endif
    </table>
</div>
{{--

--}}