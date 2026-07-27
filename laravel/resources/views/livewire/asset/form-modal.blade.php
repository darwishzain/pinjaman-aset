<?php

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\Attributes\On;

new class extends Component {
    public bool $showassetform = false;
    public string $title = '';
    public string $activeform = '';
    public Collection $allcategories;

    public string $tag;//Device physical tagging by company
    public string $category_id = '';
    public string $brand;
    public string $asset_model;
    public string $serial_number = '';
    public array $connectors = [] ;
    public array $allconnectors = ['hdmi','vga','rj45','display_port','usb_a','usb_c'];
    public string $status;
    protected array $rules = [
        'tag' => 'required|string|max:255',
        'category_id' => 'required|exists:T21_asset_categories,T21_id',
        'serial_number' => ['nullable','string'],
        //Rule::unique('products')->whereNull('serial_number')
        'connectors.*'=>'nullable|integer|min:0',
    ];
    #[On('loadcreateassetform')]
    public function loadcreateform(){
        $this->showassetform = true;
        $this->title = "Tambah Aset";
        $this->activeform = 'create-asset';
        $this->allcategories = AssetCategory::all();
    }
    public function createasset(){
        $this->validate();
        $filteredConnectors = array_filter($this->connectors, function ($value) {
            return !is_null($value) && $value !== '' && $value > 0;
        });
        Asset::create([
            'T20_tag' => $this->tag,
            'T20T21_category_id' => $this->category_id,
            'T20_brand' => $this->brand,
            'T20_model' => $this->asset_model,
            'T20_serial_number' => $this->serial_number,
            'T20_specifications' => $filteredConnectors,
            'T20_status' => 'available',
        ]);
        $this->showassetform = false;
        $this->reset(['tag','category_id','serial_number']);
        $this->dispatch('refresh-asset');
    }
    #[On('loadeditassetform')]
    public function loadeditform($id)
    {
        $this->title = "Tambah Aset";
        $this->showassetform = true;
        $this->activeform = 'edit-asset';
        $this->asset = Asset::findOrFail($id);
        
    }
}; ?>

<div>
    @if($showassetform)
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center">
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center p-4">
            <div class="w-full max-w-4xl rounded-xl bg-white shadow-xl">
                <!-- Header -->
                <div class="border-b p-6">
                    <h2 class="text-lg font-semibold">
                        {{ $title }}

                        <button wire:click="$set('showassetform', false)" class="float-right">
                            ✕
                        </button>
                    </h2>
                </div>

                <!-- Scrollable Body -->
                <div class="max-h-[65vh] overflow-y-auto p-6">
                    @if($this->activeform === 'create-asset')
                        @can('create:assets')
                            <form wire:submit="createasset">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <x-select wire:model.live="category_id" id="category_id" label="Type">
                                        <option value="" disabled selected>{{ __('Choose Asset Type') }}</option>
                                        @foreach ($this->allcategories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </x-select>
                                    <x-input type="text" placeholder="Tag" wire:model="tag" label="Tag" id="tag"></x-input>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <x-input type="text" placeholder="Brand" wire:model="brand" label="Brand" id="brand"></x-input>
                                    <x-input type="text" placeholder="Model" wire:model="asset_model" label="Model" id="asset_model"></x-input>
                                    <x-input type="text" placeholder="Serial Number" wire:model="serial_number" label="Serial Number" id="serial_number"></x-input>
                                </div>
                                <div> Spesifikasi</div>
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                    @foreach($this->allconnectors as $connector)
                                        <x-input type="number" wire:model="connectors.{{ $connector }}_count" id="{{ $connector }}_count" label="Bilangan {{ strtoupper($connector) }}"></x-input>
                                    @endforeach
                                </div>
                                <x-submit-button>
                                    Tambah Aset
                                </x-submit-button>
                            </form>
                        @endcan
                    @elseif($this->activeform === 'edit-asset')
                    sss
                    @endif
                </div>

                <!-- Footer -->
                <div class="flex justify-end gap-2 border-t p-6">
                    <h2 class="text-lg font-semibold">
                        <button wire:click="$set('showassetform', false)" class="float-right">
                            Batal
                        </button>
                    </h2>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
