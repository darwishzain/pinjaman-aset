<?php

use App\Models\Asset;
use App\Models\AssetCategory;
use Livewire\Volt\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;

new class extends Component {
    public AssetCategory $categories;
    public $assets;

    #[Computed]
    public function mount($assets){
        $this->assets = $assets;
    }
    #[On('refresh-asset')]
    public function refreshList()
    {

    }
}; ?>

<div>
    @canany(['create:assets','view:assets','view-any:assets','update:assets'])
        <livewire:asset.modal></livewire:asset.modal>
        @can('create:assets')
            <x-primary-button wire:click="$dispatch('loadcreateassetform')" class="bg-blue-800 text-white py-2 px-4 rounded">Tambah Aset</x-primary-button>
        @endcan
        @if($assets->isNotEmpty())
        <x-ui.table class="w-full border border-collapse">
            <x-slot name="header">
                <x-ui.th>{{ __('Label') }}</x-ui.th>
                <x-ui.th>{{ __('Jenis Aset') }}</x-ui.th>
                <x-ui.th>{{ __('Status') }}</x-ui.th>
                <x-ui.th>{{ __('Pemilik') }}</x-ui.th>
                <x-ui.th>{{ __('Tindakan') }}</x-ui.th>
            </x-slot>
            <x-slot name="slot">
                @foreach ($assets as $asset)
                    <tr wire:key="asset-row-{{ $asset->T20_id }}">
                        <x-ui.td>
                            <div class="ml-4">
                                <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $asset->T20_tag }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $asset->T20_brand }} {{ $asset->T20_model }}
                                </div>
                            </div>
                        </x-ui.td>

                        <x-ui.td>
                            <button class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium whitespace-nowrap bg-gray-200">
                                {{ $asset->category?->name ?? 'Uncategorized' }}
                            </button>
                        </x-ui.td>

                        <x-ui.td>
                            <x-ui.status-pill :status="$asset->T20_status"></x-ui.status-pill>
                        </x-ui.td>
                        <x-ui.td></x-ui.td>
                        <x-ui.td>
                        @can('update:assets')
                            <button wire:click="$dispatch('loadeditassetform', { id: '{{ $asset->T20_id }}' })"><x-feathericon-settings /></button>
                        @endcan
                        @can('view:assets')
                            <button wire:click="$dispatch('loadviewasset',{ id: '{{ $asset->T20_id }}' })"><x-feathericon-info /></button>
                        @endcan
                        </x-ui.td>
                    </tr>
                @endforeach
            </x-slot>
        </x-ui.table>
        @else
        <div class="">
            {{ __('No asset records found' )}}
        </div>
        @endif
        @else
        <h1>Anda Tidak Memiliki Kebenaran Untuk Menguruskan Aset</h1>
    @endcanany
</div>
