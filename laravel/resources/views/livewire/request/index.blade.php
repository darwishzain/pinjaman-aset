<?php

use App\Models\Request;
use Livewire\Volt\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;

new class extends Component {
    #[Computed]
    public function requests()
    {
        return Request::with(['user','supportedBy','approvedBy'])->get();
    }
}; ?>

<div>
    @canany(['create:requests','support:requests','approve:requests', 'view:requests', 'view-any:requests'])
        @can('create-requests')
            <x-primary-button wire:click="$dispatch('loadcreaterequest')">Permohonan Baru</x-primary-button>
        @endcan
        <livewire:request.modal></livewire:request.modal>
        @can('view-any:requests')
            <table>
                <tr>
                    <th></th>
                </tr>
            </table>
        @else
        Anda tidak memiliki kebenrana untuk mengakses
        @endcan
    @endcanany
</div>
