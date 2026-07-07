<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pengurusan Peranan dan Kebenaran') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                 @foreach ( $roles as $role )
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold">{{ $role->name }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Kebenaran: {{ $role->permissions->pluck('name')->join(', ') }}</p>
                    </div>
                 @endforeach   
                </div>
            </div>
        </div>
    </div>
</x-app-layout>