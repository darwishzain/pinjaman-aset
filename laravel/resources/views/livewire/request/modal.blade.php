<?php
use App\Models\User;
use App\Models\Request;
use App\Models\RequestAsset;

use App\Models\AssetCategory;
use Livewire\Volt\Component;
use Livewire\Attributes\On;

new class extends Component {
    public ?string $activemodal = null;
    public string $title = '';

    public ?string $user_id = null;
    #[On('loadcreaterequest')]
    public function loadcreaterequest()
    {
        $this->activemodal = 'create-request';
        $this->title = 'Permohonan Baru';
        $this->user_id = auth()->user()->id;//Should I findOrFail this??
    }
    public function createrequest()
    {
        $this->user = User::findOrFail($this->user_id);
        $this->title = $this->user->name;
    }
    #[On('loadsupportrequest')]
    
    #[On('loadapproverequest')]

}; ?>

<div>
    @if(in_array($activemodal,['create-request','support-request','approve-request']))
    <x-content-modal title="{{ $title }}">
        <form
            wire:submit="createrequest"
            >
            <div class="ml-4 text-center">
                <div class="text-sm font-semibold text-gray-900 dark:text-white">
                    {{ auth()->user()->name }}
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400">
                    {{ User::GROUPS[auth()->user()->group] }}
                </div>
                <x-input type="hidden" wire:model="user_id"></x-input>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-select wire:model="type" label="Kegunaan" id="type">
                    <option value="individual" selected>Individu</option>
                    <option value="department">Jabatan/Bahagian</option>
                </x-select>
                <x-input type="date" wire:model="start_date" label="Dari" id="start_date"></x-input>
                <x-input type="date" wire:model="end_date" label="Hingga" id="end_date"></x-input>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-input type="text" wire:model="reason" label="Tujuan" id="reason"></x-input>
                <x-input type="text" wire:model="location" label="Tempat Penggunaan" id="location"></x-input>
                <x-input type="text" wire:model="remark" label="Catatan (Jika Perlu)" id="remark"></x-input>
            </div>
            @foreach(AssetCategory::all() as $category)
                <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                    <x-input type="number"
                        wire:model="quantity.{{ $category->T21_id }}"
                        label="Bilangan {{ ucfirst($category->T21_name) }}"
                        id="{{ ucfirst($category->T21_name) }}_quantity"
                    >
                    </x-input>
                </div>
            @endforeach
            <x-submit-button>
                Hantar
            </x-submit-button>
        </form>
    </x-content-modal>
    @endif
</div>
