<?php

use App\Models\Request;
use Livewire\Volt\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Spatie\Permission\Models\Permission;

new class extends Component {
    public $requests;
    #[Computed]
    public function mount($requests)
    {
        $this->requests = $requests;
    }
    #[On('refresh-request')]
    public function refreshList()
    {

    }
}; ?>

<div>
    <livewire:request.modal></livewire:request.modal>
    <livewire:view.modal></livewire:view.modal>
    @can('create:requests')
        <x-ui.button wire:click="$dispatch('loadcreaterequest')">Permohonan Baru</x-ui.button>
    @endcan
    @if($requests->isEmpty())
        <div class="mt-6 text-gray-500 dark:text-gray-400">
            Tiada permohonan untuk dipaparkan.
        </div>
    @else
        @canany(['create:requests','support:requests','approve:requests', 'view:requests', 'view-any:requests'])
            <x-ui.table class="text-sm whitespace-nowrap">
                <x-slot name="header">
                    <tr>
                        <x-ui.th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pemohon</x-ui.th>
                        <x-ui.th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sebab</x-ui.th>
                        <x-ui.th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tempat Penggunaan</x-ui.th>
                        <x-ui.th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</x-ui.th>
                        <x-ui.th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tindakan</x-ui.th>
                    </tr>
                </x-slot>
                <x-slot name="slot">
                    @foreach($requests as $request)
                        <tr wire:key="request-{{ $request->T30_id }}">
                            <x-ui.td>
                                <x-ui.user-list-item :user="$request->user"></x-ui.user-list-item>
                            </x-ui.td>
                            <x-ui.td>{{ $request->T30_reason }}</x-ui.td>
                            <x-ui.td>{{ $request->T30_location }}</x-ui.td>
                            <x-ui.td> <x-ui.status-pill :status="$request->T30_status"></x-ui.status-pill></x-ui.td>
                            <x-ui.td>
                                @can('support:requests')
                                    @if($request->needSupport())
                                        <x-ui.button color="blue" wire:click="$dispatch('loadsupportrequest', { id: '{{ $request->T30_id }}' })">SOKONG</x-ui.button>
                                    @endif
                                @endcan
                                @can('approve:requests')
                                    @if($request->needApproval())
                                        <x-ui.button color="blue" wire:click="$dispatch('loadapproverequest', { id: '{{ $request->T30_id }}' })">LULUSKAN</x-ui.button>
                                    @endif
                                @endcan
                                @can('create:transactions')
                                    <x-ui.button color="blue" wire:click="$dispatch('newtransaction', { id: '{{ $request->T30_id }}' })">TRANSAKSI</x-ui.button>
                                @endcan
                                <x-ui.button wire:click="$dispatch('viewrequest', { id: '{{ $request->T30_id }}' })">PERIHAL</x-ui.button>
                            </x-ui.td>
                        </tr>
                    @endforeach
                </x-slot>
            </x-ui.table>
        @else
        Anda tidak memiliki kebenrana untuk mengakses
        @endcanany
    @endif
</div>
