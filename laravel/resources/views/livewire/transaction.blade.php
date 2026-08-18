<?php

use Livewire\Volt\Component;
use App\Models\Asset;
use App\Models\Request;
use App\Models\RequestAsset;
use App\Models\Transaction;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Enums\ReviewStatus;
use App\Enums\RequestStatus;

new class extends Component {
    use WithPagination;
    public string $title = "Transaksi";
    public ?Request $request = null;
    public function mount(?Request $request = null):void
    {
        if(!auth()->user()->canAny(['create:transactions','view:transactions','view-any:transactions']))
        {
            abort(403,"Tiada kebenaran untuk mengakses halaman ini");
        }
        if($request && $request->exists)
        {
            $this->request = $request;
        }
    }
    #[Computed]
    public function requests()
    {
        $user = auth()->user();
        if ($this->request && $this->request->exists) {
            return $this->request;
        }
        $query = Request::query();
        if($user->can('create:transactions'))
        {
            $query->whereIn('T30_status',[RequestStatus::PICKUP,RequestStatus::ACTIVE])
                ->where('T30_support_status',ReviewStatus::ACCEPTED)
                ->where('T30_approve_status',ReviewStatus::ACCEPTED);
        }
        else
        {
            $query->where('T30T10_user_id', $user->id);
        }
        return $query->paginate(10);
    }
    #[Computed]
    public function requestassets()
    {
        if (! $this->request || ! $this->request->exists) {
            return collect();
        }
        return RequestAsset::where('T31T30_request_id', $this->request->T30_id)
            ->get();
    }
    #[Computed]
    public function transactions()
    {
        if (! $this->request || ! $this->request->exists) {
            return collect();
        }
        return Transaction::where('T40T30_request_id', $this->request->T30_id)
            ->latest()
            ->get();
    }
    #[On('refresh-transaction')]
    public function refreshTransaction()
    {

    }
}; ?>

<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $this->title ?? __('Transaksi') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <x-ui.module-nav-group>
                    @can('create:transactions')
                    <x-ui.module-nav-link wire:click=""></x-ui.module-nav-button>
                    @endcan
                </x-ui.module-nav-group>
                @canany(['create:transactions','view:transactions','view-any:transactions'])
                    <livewire:transaction.modal />
                    @if($this->request && $this->request->exists)
                        <x-ui.request-details :request="$request" />
                        <x-ui.title>Perihal Kuntiti Aset</x-ui.title>
                        <x-ui.table>
                            <x-slot name="header">
                                <tr>
                                    <x-ui.th>Jenis Aset</x-ui.th>
                                    <x-ui.th></x-ui.th>
                                    <x-ui.th>Tindakan</x-ui.th>
                                </tr>
                            </x-slot>
                            @foreach($this->requestassets as $requestasset)
                                <tr wire:key="requestasset-row-{{$requestasset->T31_id}}">
                                    <x-ui.td>{{ $requestasset->category->T21_name }} x {{ $requestasset->T31_quantity }}</x-ui.td>
                                    <x-ui.td></x-ui.td>
                                    <x-ui.td>
                                        @if(!$requestasset->isRequestAssetFulfilled($requestasset->category->T21_id))
                                        <x-ui.button
                                            wire:click="dispatch('loadtransactionout',{
                                                request_id:'{{ $request->T30_id}}',
                                                asset_category_id:'{{$requestasset->category->T21_id}}'
                                                })"
                                            color="orange"
                                        >
                                            Transaksi Keluar
                                        </x-ui.button>
                                        @else
                                        Transaksi Selesai
                                        @endif
                                    </x-ui.td>
                                </tr>
                            @endforeach
                        </x-ui.table>
                        @if($this->transactions->where('T40_action','in'))
                            <x-ui.title>Pergerakan Aset</x-ui.title>
                            <x-ui.table>
                                <x-slot name="header">
                                    <tr>
                                        <th>Jenis Aset</th>
                                        <th>Pergerakan Keluar</th>
                                        <th>Pergerakan Masuk</th>
                                        <th>Tindakan</th>
                                    </tr>
                                </x-slot>
                                @foreach($request->transactionsOut as $transaction)
                                    <tr>
                                        <td>{{ $transaction->asset->category->T21_name }}</td>
                                        <td>{{ $transaction->T40_created_at }}</td>
                                        <td>
                                            @if( $transaction->transactionIn )
                                                {{ $transaction->transactionIn->T40_created_at }}
                                            @else
                                            <x-ui.button
                                                wire:click="dispatch('loadtransactionin',
                                                {transaction_id:'{{ $transaction->T40_id}}'})"
                                                color="green"
                                            >
                                                Transaksi
                                            </x-ui.button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </x-ui.table>
                        @endif
                    @else
                        <x-ui.table>
                            <x-slot name="header">
                                <tr>
                                    <x-ui.th>Nama Pemohon</x-ui.th>
                                    <x-ui.th>Tujuan</x-ui.th>
                                    <x-ui.th>Status</x-ui-th>
                                    <x-ui.th>Tindakan</x-ui.th>
                                </tr>
                            </x-slot>
                            @foreach($this->requests as $request)
                                <tr wire:key="request-row-{{$request->T30_id}}">
                                    <x-ui.td>
                                        <x-ui.user-list-item :user="$request->user"></x-ui.user-list-item>
                                    </x-ui.td>
                                    <x-ui.td>
                                        {{$request->T30_reason}} di {{$request->T30_location}}
                                    </x-ui.td>
                                    <x-ui.td><x-ui.status-pill :status="$request->T30_status"></x-ui.status-pill></x-ui.td>
                                    <x-ui.td>
                                        <x-ui.link color="blue"
                                            href="{{ route('transactions.request', $request) }}"
                                            wire:navigate
                                        >
                                            Urus Transaksi
                                        </x-ui.link>
                                        @if($request->canUpdate())
                                        {{--Can Transaction--}}
                                        @endif
                                    </x-ui.td>
                                </tr>
                            @endforeach
                        </x-ui.table>
                        {{ $this->requests->links() }}
                    @endif
                @endcanany
            </div>
        </div>
    </div>
</div>
