<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $title ?? __('Pergerakan Aset') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if(isset($requests) && $requests)
                    <x-ui.table>
                        <x-slot name="header">
                            <tr>
                                <th>Pemohon</th>
                                <th>Status</th>
                                <th>Aset</th>
                                <th>Tindakan</th>
                            </tr>
                        </x-slot>
                        @foreach($requests as $request)
                            <tr>
                                <x-ui.td> <x-ui.user-list-item :user="$request->user"/></x-ui.td>
                                <x-ui.td> <x-ui.status-pill :status="$request->T30_status"/></x-ui.td>
                                <x-ui.td>
                                    @foreach($request->requestAssets as $requestasset)
                                        <div class="flex justify-between gap-4">
                                            <span>{{$requestasset->category->name}}</span>
                                            <span class="font-semibold">× {{$requestasset->T31_quantity}}</span>
                                        </div>
                                    @endforeach
                                </x-ui.td>
                                <x-ui.td>
                                    <x-ui.link :href="route('transactions.request',$request->T30_id)" color="green">Transaksi</x-ui.link>
                                </x-ui.td>
                            </tr>
                        @endforeach
                    </x-ui.table>
                @elseif(isset($request) && $request)
                    <livewire:transaction.index :request="$request" />
                @endif
            </div>
        </div>
    </div>
</x-app-layout>