<?php

use App\Models\Asset;
use App\Models\AssetCategory;
use Livewire\Volt\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;

new class extends Component {
    public AssetCategory $categories;

    #[Computed]
    public function assets(){
        return Asset::with('category')->get();
    }
    #[Computed]
    public function assetcategories(){
        return AssetCategory::all();
    }
    #[On('refresh-asset')]
    public function refreshList()
    {
        //unset($this->assets);
        //$this->dispatch('$refresh');
    }
}; ?>

<div>
    @canany(['create:assets','view:assets','view-any:assets','update:assets'])
        <livewire:asset.form-modal></livewire:asset.form-modal>
        @can('create:assets')
            <x-primary-button wire:click="$dispatch('loadcreateassetform')" class="bg-blue-800 text-white py-2 px-4 rounded">Tambah Aset</x-primary-button>
        @endcan
        @if($this->assets->isNotEmpty())
        <table class="w-full border border-collapse">
            <tr>
                <th>{{ __('Label') }}</th>
                <th>{{ __('Type') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Ownership') }}</th>
                <th>{{ __('Action') }}</th>
            </tr>
            @foreach ($this->assets as $asset)
                <tr wire:key="asset-row-{{ $asset->id }}">
                    <td>
                        <div class="ml-4">
                            <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $asset->T20_tag }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $asset->T20_brand }} {{ $asset->T20_model }}
                            </div>
                        </div>
                    </td>

                    <td>
                        <button class="rounded-full bg-blue-400 text-black px-2 py-1">
                            {{ $asset->category?->name ?? 'Uncategorized' }}
                        </button>
                    </td>

                    <td>
                        <button class="rounded-full bg-blue-400 text-black px-2 py-1">
                            {{ $asset->T20_status }}
                        </button>
                    </td>
                    <td></td>
                    <td>
                    @can('update:assets')
                        <button wire:click="$dispatch('loadeditassetform', { id: '{{ $asset->id }}' })"><x-feathericon-settings /></button>
                    @endcan
                    @can('view:assets')
                        <button wire:click="$dispatch('loadviewasset',{ id: '{{ $asset->id }}' })"><x-feathericon-info /></button>
                    @endcan
                    </td>
                </tr>
            @endforeach
        </table>
        @else
        <div class="">
            {{ __('No asset records found' )}}
        </div>
        @endif
        @else
        <h1>Anda Tidak Memiliki Kebenaran Untuk Menguruskan Aset</h1>
    @endcanany
</div>
