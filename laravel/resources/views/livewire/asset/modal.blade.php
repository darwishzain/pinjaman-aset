<?php

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\Attributes\On;
use App\Enums\AssetStatus;
use Masmerise\Toaster\Toaster;

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
    public ?string $status = null;
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
    public function resetForm()
    {
        $this->reset(['tag', 'category_id', 'brand', 'model', 'serial_number', 'status', 'asset_id']);
        $this->resetValidation();
    }
    public function closeModal()
    {
        $this->activemodal = null;
        $this->resetForm();
        $this->dispatch('refresh-asset');
    }
    #[On('loadcreateassetform')]
    public function loadcreateasset(){
        $this->activemodal = 'create-asset';
        $this->title = "Tambah Aset";
        $this->resetForm();
        $this->asset = null;
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
            'T20_status' => $this->status,
        ]);
        Toaster::success('Aset ditambah ke inventori');
        $this->closeModal();
    }
    #[On('loadeditassetform')]
    public function loadupdateasset($id)
    {
        $this->activemodal = 'edit-asset';
        $this->title = "Kemaskini Aset";
        $this->resetForm();
        $this->asset = Asset::findOrFail($id);
        $this->asset_id = $this->asset->T20_id;
        $this->category_id = $this->asset->T20T21_category_id;
        $this->tag = $this->asset->T20_tag;
        $this->brand = $this->asset->T20_brand;
        $this->serial_number = $this->asset->T20_serial_number;
        //$this->specifications = $this->asset->T20_specifications;
        $this->status = $this->asset->T20_status->value;
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
        $this->closeModal();
    }
    public function deleteasset()
    {
        $asset = Asset::where('T20_id', $this->asset_id)->firstOrFail();
        if(!$asset->isDeletable())
        {
            Toaster::error('Aset tidak boleh dipadam');
            return;
        }
        $asset->delete();
        $this->asset = null;
        Toaster::success('Aset telah berjaya dipadam');
        $this->closeModal();
    }
}; ?>

<div>
    @if(in_array($activemodal, ['create-asset', 'edit-asset', 'view-asset']))
    <x-ui.content-modal title="{{ $title }}">
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
                    <x-ui.title>
                        Perihal Aset
                        @if($asset?->exists)
                            {{ $asset->T20_tag }}
                            <x-ui.status-pill :status="$asset->T20_status"></x-ui.status-pill>
                            <x-ui.input type="hidden" wire:model="asset_id"></x-ui.input>
                        @endif
                    </x-ui.title>
                    <x-ui.grid min="1" max="3">
                        <x-ui.select wire:model.live="category_id" id="category_id" label="Type" required>
                            <option selected>{{ __('Pilih Jenis Aset') }}</option>
                            @foreach (AssetCategory::all() as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </x-ui.select>
                        <x-ui.input type="text" placeholder="Tag" wire:model="tag" label="Tag" id="tag"></x-ui.input>
                    </x-ui.grid>
                    <x-ui.grid min="1" max="3">
                        <x-ui.input type="text" placeholder="Brand" wire:model="brand" label="Jenama" id="brand"></x-ui.input>
                        <x-ui.input type="text" placeholder="Model" wire:model="model" label="Model" id="model"></x-ui.input>
                        <x-ui.input type="text" placeholder="Serial Number" wire:model="serial_number" label="Nombor Siri" id="serial_number"></x-ui.input>
                        <x-ui.select wire:model="status" id="status" label="Status Penyelenggaraan" required>
                            <option value="" >--Pilih Status Aset--</option>
                            @foreach(AssetStatus::cases() as $case)
                                <option value="{{ $case->value }}" {{ old('status', $order->status->value ?? '') === $case->value ? 'selected' : '' }}>
                                    {{ ucfirst($case->label()) }}
                                </option>
                            @endforeach
                        </x-ui.select>
                    </x-ui.grid>
                    {{--
                    <x-ui.title> Spesifikasi</x-ui.title>
                    <x-ui.grid min="1" max="3">
                        @foreach(Asset::CONNECTORS as $key => $label )
                            <x-ui.input type="number" wire:model="connectors.{{ $key }}_count" id="{{ $key }}_count" label="Bilangan {{ strtoupper($label) }}"></x-ui.input>
                        @endforeach
                    </x-ui.grid>--}}
                    <x-ui.button type="submit" color="blue">
                        @if($asset?->exists)
                            Kemaskini Aset
                        @else
                            Tambah Aset
                        @endif
                    </x-ui.button>
                    @if($asset?->exists && $asset->isDeletable())
                        <x-ui.button
                            type="button" color="red"
                            wire:click="deleteasset"
                            wire:confirm="Adakah anda pasti untuk memadam aset ini?"
                        >
                            Padam Aset
                        </x-ui.button>
                    @endif
                </form>
            @endcanany
        @endif
    </x-ui.content-modal>
    @endif
</div>
