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
    @can('create:requests')
        <x-ui.button wire:click="$dispatch('loadcreaterequest')">Permohonan Baru</x-ui.button>
    @endcan
    @canany(['create:requests','support:requests','approve:requests', 'view:requests', 'view-any:requests'])
        <livewire:request.modal></livewire:request.modal>
        <livewire:user.form-modal></livewire:user.form-modal>
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
                        <x-ui.td>{{ $request->T30_status->label() }}</x-ui.td>
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
                            <x-ui.button wire:click="$dispatch('loadviewrequest', { id: '{{ $request->T30_id }}' })">PERIHAL</x-ui.button>
                        </x-ui.td>
                    </tr>
                @endforeach
            </x-slot>
        </x-ui.table>
    @else
    Anda tidak memiliki kebenrana untuk mengakses
    @endcanany
</div>
