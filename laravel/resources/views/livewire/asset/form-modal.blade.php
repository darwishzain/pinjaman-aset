<?php

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\Attributes\On;

new class extends Component {
    public ?string $activemodal = null;
    public string $title = '';
    public Collection $allcategories;

    public ?string $asset_id = null;
    public ?Asset $asset = null;
    public string $tag = '';//Device physical tagging by company
    public string $category_id = '';
    public string $brand = '';
    public string $model = '';
    public ?string $serial_number = null;
    public array $connectors = [] ;
    public string $status;
    protected function rules(): array
    {
        return [
            'tag' => ['required','string','max:255'],
            'category_id' => ['required','exists:T21_asset_categories,T21_id'],
            'brand' => ['nullable','string','max:255'],
            'model' => ['nullable','string','max:255'],
            'serial_number' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('T20_assets', 'T20_serial_number')->ignore($this->asset?->id,'T20_id'),
            ],
            'connectors.*' => 'nullable|integer|min:0',
        ];
    }
    #[On('loadcreateassetform')]
    public function loadcreateasset(){
        $this->activemodal = 'create-asset';
        $this->title = "Tambah Aset";
        $this->reset(['tag','category_id','brand','model','serial_number']);
    }
    public function createasset(){
        $this->serial_number = $this->serial_number ?: null;
        $this->validate();
        /*$filteredConnectors = array_filter($this->connectors, function ($value) {
            return !is_null($value) && $value !== '' && $value > 0;
        });*/
        Asset::create([
            'T20_tag' => $this->tag,
            'T20T21_category_id' => $this->category_id,
            'T20_brand' => $this->brand,
            'T20_model' => $this->model,
            'T20_serial_number' => $this->serial_number,
            //'T20_specifications' => $filteredConnectors,
            'T20_status' => 'available',
        ]);
        $this->activemodal = null;
        $this->dispatch('refresh-asset');
        $this->reset(['tag','category_id','brand','model','serial_number']);
    }
    #[On('loadeditassetform')]
    public function loadupdateasset($id)
    {
        $this->activemodal = 'edit-asset';
        $this->title = "Kemaskini Aset";
        $this->reset(['tag','category_id','brand','model','serial_number']);
        $this->asset = Asset::findOrFail($id);
        $this->asset_id = $this->asset->id;
        $this->category_id = $this->asset->category_id;
        $this->tag = $this->asset->tag;
        $this->brand = $this->asset->brand;
        $this->serial_number = $this->asset->serial_number;
        //$this->specifications = $this->asset->specifications;
        $this->status = $this->asset->status;
    }
    public function updateasset()
    {
        $this->serial_number = $this->serial_number ?: null;
        $this->validate();
        $this->asset = Asset::findOrFail($this->asset_id);
        $this->asset->update([
            'T20_tag' => $this->tag,
            'T20_category_id' => $this->category_id,
            'T20_brand' => $this->brand,
            'T20_model' => $this->model,
            'T20_serial_number' => $this->serial_number,
            'T20_status' => $this->status,
        ]);
        $this->activemodal = null;
        $this->dispatch('refresh-asset');
        $this->reset(['tag','category_id','brand','model','serial_number']);
    }
    #[On('loadviewasset')]
    public function loadviewasset($id)
    {
        $this->activemodal = 'view-asset';
        $this->title = "Aset";
        $this->asset = Asset::findOrFail($id);
        $this->asset_id = $this->asset->id;
        $this->category_id = $this->asset->category_id;
        $this->tag = $this->asset->tag;
        $this->brand = $this->asset->brand;
        $this->serial_number = $this->asset->serial_number;
        //$this->specifications = $this->asset->specifications;
        $this->status = $this->asset->status;
    }
}; ?>

<div>
    @if(in_array($activemodal, ['create-asset', 'edit-asset', 'view-asset']))
    <x-content-modal title="{{ $title }}">
        @if($activemodal === 'create-asset' or $activemodal === 'edit-asset')
            @canany(['create:assets','update:assets'])
                <form 
                    wire:submit="
                    @if($asset?->exists)
                    updateasset
                    @else
                    createasset
                    @endif
                    "
                >
                    @if($asset?->exists)
                    <div class="grid grid-cols-1">
                        <div>{{ $asset->tag }}</div>
                        <x-ui.input type="hidden" wire:model="asset_id"></x-ui.input>
                    </div>
                    @endif
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <x-ui.select wire:model.live="category_id" id="category_id" label="Type" required>
                            <option value="" disabled selected>{{ __('Choose Asset Type') }}</option>
                            @foreach (AssetCategory::all() as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </x-ui.select>
                        <x-ui.input type="text" placeholder="Tag" wire:model="tag" label="Tag" id="tag"></x-ui.input>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <x-ui.input type="text" placeholder="Brand" wire:model="brand" label="Brand" id="brand"></x-ui.input>
                        <x-ui.input type="text" placeholder="Model" wire:model="model" label="Model" id="model"></x-ui.input>
                        <x-ui.input type="text" placeholder="Serial Number" wire:model="serial_number" label="Serial Number" id="serial_number"></x-ui.input>
                    </div>{{--
                    <div> Spesifikasi</div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach(Asset::CONNECTORS as $key => $label )
                            <x-ui.input type="number" wire:model="connectors.{{ $key }}_count" id="{{ $key }}_count" label="Bilangan {{ strtoupper($label) }}"></x-ui.input>
                        @endforeach
                    </div>--}}
                    <x-submit-button>
                        @if($asset?->exists)
                                Update Asset
                        @else
                                Tambah Aset
                        @endif
                    </x-submit-button>
                </form>
            @endcanany
        @elseif($activemodal === 'view-asset')
            {{ $asset->category->name }}
        @endif
    </x-content-modal>
    @endif
</div>
