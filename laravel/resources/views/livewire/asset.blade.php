<?php

use Livewire\Volt\Component;
use App\Models\Asset;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\WithPagination;

new class extends Component {
    use withPagination;
    public string $title = "Senarai Aset";
    public function mount()
    {
        if(!auth()->user()->canAny(['create:assets','view:assets','view-any:assets','update:assets']))
        {
            abort(403,"Tiada kebenaran untuk mengakses halaman ini");
        }
    }
    #[Computed]
    public function assets()
    {
        return Asset::paginate(10);
    }
    #[On('refresh-asset')]
    public function refreshAsset()
    {

    }
}; ?>
<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $this->title ?? __('Aset') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <x-ui.module-nav-group />
                @canany(['create:assets','view:assets','view-any:assets','update:assets'])
                    <livewire:asset.modal></livewire:asset.modal>
                    <livewire:view.modal></livewire:view.modal>
                    @if($this->assets->isEmpty())
                        <x-ui.title>Tiada Aset Untuk Dipaparkan</x-ui.title>
                    @else
                        <x-ui.table>
                            <x-slot name="header">
                                <tr>
                                    <x-ui.th>{{ __('Label') }}</x-ui.th>
                                    <x-ui.th>{{ __('Status') }}</x-ui.th>
                                    <x-ui.th>{{ __('Pemilik') }}</x-ui.th>
                                    <x-ui.th>{{ __('Tindakan') }}</x-ui.th>
                                </tr>
                            </x-slot>
                            @foreach($this->assets as $asset)
                                <tr wire:key="asset-row-{{ $asset->T20_id }}">
                                    <x-ui.td>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $asset->T20_tag }}
                                                <button class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium whitespace-nowrap bg-gray-200">
                                                    {{ $asset->category?->T21_name ?? 'Tiada Kategori' }}
                                                </button>
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $asset->T20_brand }} {{ $asset->T20_model }}
                                            </div>
                                        </div>
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
                                        <button wire:click="$dispatch('viewasset',{ id: '{{ $asset->T20_id }}' })"><x-feathericon-info /></button>
                                    @endcan
                                    </x-ui.td>
                                </tr>
                            @endforeach
                        </x-ui.table>
                        {{ $this->assets->links() }}
                    @endif
                @endcanany
            </div>
        </div>
    </div>
</div>
