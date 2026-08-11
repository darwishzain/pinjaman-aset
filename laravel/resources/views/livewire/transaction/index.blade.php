<?php

use Livewire\Volt\Component;

new class extends Component {
    public $request;
    #[Computed]
    public function mount($request)
    {
        $this->request = $request;
    }
    #[On('refresh-request')]
    public function refreshList()
    {

    }
}; ?>

<div>
    <livewire:transaction.modal />
    <x-ui.request-details :request="$request" />
    <x-ui.title>Kuantiti Aset yang Dimohon</x-ui.title>
        <x-ui.table>
            <x-slot name="header">
                <tr>
                    <x-ui.th>Kategori</x-ui.th>
                    <x-ui.th>Bilangan</x-ui.th>
                    <x-ui.th>Tindakan</x-ui.th>
                </tr>
            </x-slot>
            <x-slot name="slot">
                @foreach($request->requestAssets as $requestasset)
                    <tr>
                        <x-ui.td>{{ $requestasset->category->T21_name }}</x-ui.td>
                        <x-ui.td>{{ $requestasset->T31_quantity }}</x-ui.td>
                        <x-ui.td>
                            <x-ui.button
                                wire:click="dispatch('loadtransactionout',{
                                    request_id:'{{ $request->T30_id}}',
                                    asset_category_id:'{{$requestasset->category->T21_id}}'
                                    })"
                                color="orange"
                            >
                                Transaksi
                            </x-ui.button>
                        </x-ui.td>
                    </tr>
                @endforeach
            </x-slot>
        </x-ui.table>
        <x-ui.divider></x-ui.divider>
        @if(isset($request->transactions) && $request->transactions)
            <x-ui.title>Pergerakan Aset</x-ui.title>
            <x-ui.table>
                <x-slot name="header">
                    <tr>
                        <th>Tarikh</th>
                        <th>Tindakan</th>
                    </tr>
                </x-slot>
                <x-slot name="slot">
                    @foreach($request->transactions as $transaction)
                        <tr>
                            <td>

                            </td>
                            <td>
                                <x-ui.button
                                    wire:click="dispatch('loadtransactionin',{'{{ $request->T30_id}}','{{ $transaction->T40_id}}'})"
                                    color="green"
                                >
                                    Transaksi
                                </x-ui.button>
                            </td>
                        </tr>
                    @endforeach
                </x-slot>

            </x-ui.table>
            
        @else
            Tiada Rekod Pergerakan Aset
        @endif
</div>
