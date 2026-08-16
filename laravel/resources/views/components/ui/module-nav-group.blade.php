<div class="flex space-x-6 border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
    @if(request()->is('users*'))
        <x-ui.module-nav-button wire:click="$dispatch('loadcreateuserform')">
            Tambah Pengguna
        </x-ui.module-nav-button>
    @elseif(request()->routeIs('assets.*'))
        <x-ui.module-nav-button wire:click="$dispatch('loadcreateassetform')">
            Tambah Aset
        </x-ui.module-nav-button>
    @elseif(request()->routeIs('requests.*'))
        <x-ui.module-nav-button wire:click="$dispatch('loadcreaterequestform')">
            Permohonan Baru
        </x-ui.module-nav-button>
        <x-ui.module-nav-link :href="route('requests.index')" :active="request()->routeIs('requests.index')">
            Permohonan Saya
        </x-ui.module-nav-link>
        @can('support:requests')
        <x-ui.module-nav-link :href="route('requests.support')" :active="request()->routeIs('requests.support')">
            Sokong Permohonan
        </x-ui.module-nav-link>
        @endcan
        @can('approve:requests')
        <x-ui.module-nav-link :href="route('requests.approve')" :active="request()->routeIs('requests.approve')">
            Luluskan Permohonan
        </x-ui.module-nav-link>
        @endcan
    @elseif(request()->routeIs('transactions.*'))
        @can('create:transactions')
        <x-ui.module-nav-link :href="route('transactions.index')" :active="request()->routeIs('transactions.index')">
            Transaksi Aset
        </x-ui.module-nav-link>
        @endcan
        @can('view-any:transactions')
        <x-ui.module-nav-link>
            Senarai Transaksi Aset
        </x-ui.module-nav-link>
        @endcan
    @endif
</div>