<?php

use App\Models\Asset;
use App\Models\AssetCategory;
use Livewire\Volt\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;

new class extends Component {
    public Asset $assets;
    public AssetCategory $categories;
    
    #[Computed]
    public function assets(){
        return Asset::all();
    }
    #[Computed]
    public function assetcategories(){
        return AssetCategory::all();
    }
}; ?>

<div>
    @canany(['create:assets','view:assets','view-any:assets','update:assets'])
        <livewire:asset.form-modal></livewire:asset.form-modal>
        @can('create:assets')
        <button wire:click="$dispatch('loadcreateform')" class="bg-blue-800 text-white py-2 px-4 rounded">Tambah Aset</button>
        @endcan
        {{-- @foreach ($this->assets as $asset)
        {
            {{ $asset->T20_tag }}
        }
        @endforeach--}}
        @foreach ($this->assetcategories as $category)
            {{ $category->T21_name }}
        @endforeach
    @else
    <h1>Anda Tidak Memiliki Kebenaran Untuk Menguruskan Aset</h1>
    @endcanany
</div>
