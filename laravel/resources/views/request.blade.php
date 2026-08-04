<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $title ??__('Permohonan') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex space-x-6 border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
                    <x-nav-link :href="route('requests.index')" :active="request()->routeIs('requests.index')">
                        Senarai Permohonan
                    </x-nav-link>
                    @can('support:requests')
                    <x-nav-link :href="route('requests.support')" :active="request()->routeIs('requests.support')">
                        Sokong Permohonan
                    </x-nav-link>
                    @endcan
                    @can('approve:requests')
                    <x-nav-link :href="route('requests.approve')" :active="request()->routeIs('requests.approve')">
                        Luluskan Permohonan
                    </x-nav-link>
                    @endcan
                </div>
                @canany(['create:requests','support:requests','approve:requests','view:requests','view-any:requests'])
                    <livewire:request.index :requests="$requests" />
                @endcanany
            </div>
        </div>
    </div>
</x-app-layout>
