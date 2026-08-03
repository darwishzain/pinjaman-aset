<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Permohonan') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @canany(['create:requests','support:requests','approve:requests','view:requests','view-any:requests'])
                    <livewire:request.index :requests="$requests" />
                @endcanany
            </div>
        </div>
    </div>
</x-app-layout>
