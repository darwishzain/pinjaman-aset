<div class="flex space-x-6 border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
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
    @can('create:transactions')
    <x-ui.module-nav-link :href="route('requests.transactions')" :active="request()->routeIs('requests.transactions')">
        Transaksi Aset
    </x-ui.module-nav-link>
    @endcan
    @can('view-any:transactions')
    <x-ui.module-nav-link>
        Transaksi Aset
    </x-ui.module-nav-link>
    @endcan
</div>