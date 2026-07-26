<?php

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Volt\Component;
use Livewire\Attributes\On;

new class extends Component {
    public bool $showassetform = false;
    public string $title = '';
    public string $activeform = '';
    public Collection $allcategories;
    public string $category_id = '';
    public string $tag;//Device physical tagging by company
    public string $type;
    public string $brand;
    public string $model;
    public string $serialnumber;
    //attribute section
    public string $status;
    #[On('loadcreateform')]
    public function loadcreateform(){
        $this->showassetform = true;
        $this->title = "Tambah Aset";
        $this->activeform = 'create-user';
        $this->allcategories = AssetCategory::all();
    }
    public function createasset(){
        Asset::create([
            'T20_tag' => $this->tag,
            //'T20_brand' => 'Dell',
            //'T20_model' => 'Latitude 7440',
            //'T20_serialnumber' => 'DL123456',
            'T20T21_category_id' => $this->category_id,
            //'T20_attributes' => [
            //    'cpu' => 'Intel i7',
            //    'ram' => '32GB',
            //    'storage' => '1TB SSD',
            //],
            'T20_status' => 'available',
        ]);

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
                    @if($this->activeform === 'create-user')
                        @can('create:assets')
                            <form wire:submit="createasset">
                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <label for="category_id" class="block mb-1">Jenis</label>
                                        <select name="category_id" id="category_id"
                                            wire:model="category_id"
                                            class="w-full rounded border-gray-300 focus:ring-indigo-500"
                                        >
                                            @foreach ($this->allcategories as $category)
                                                <option value="{{ $category->id}}">{{$category->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="tag">Tag</label>
                                        <input type="text"
                                            wire:model="tag"
                                            class="w-full rounded border-gray-300 focus:ring-indigo-500"
                                        >
                                    </div>
                                    <div>
                                        <label for="serial_number">No. Siri</label>
                                        <input type="text"
                                            wire:model="serial_number"
                                            class="w-full rounded border-gray-300 focus:ring-indigo-500"
                                        >
                                    </div>
                                </div>
                                <x-submit-button>
                                    Tambah Aset
                                </x-submit-button>
                            </form>
                        @endcan
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
