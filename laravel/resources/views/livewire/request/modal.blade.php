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
    public string $request_id = '';
    public string $type = '';
    public string $start_date = '';
    public string $end_date = '';
    public string $reason = '';
    public string $location = '';
    public string $remark = '';
    public array $quantity = [];
    protected function rules():array
    {
        return [
            'user_id' => ['required','exists:users,id'],
            'type' => ['required','in:individual,department'],
            'start_date' => ['required','date','after_or_equal:tomorrow'],
            'end_date' => ['required','date','after_or_equal:start_date'],
            'reason' => ['required','string','max:255'],
            'location' => ['required','string','max:255'],
            'remark' => ['nullable','string','max:255'],
            'quantity' => [
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    if (collect($value)->max() <= 0) {
                        $fail('At least one quantity must be greater than 0.');
                    }
                },
            ],
            'quantity.*' => 'required|integer|min:0',
        ];
    }
    #[On('loadcreaterequest')]
    public function loadcreaterequest()
    {
        $this->activemodal = 'create-request';
        $this->title = 'Permohonan Baru';
        $this->user_id = auth()->user()->id;//Should I findOrFail this??
        $this->start_date = date('Y-m-d', strtotime('+1 day'));
        $this->end_date = date('Y-m-d', strtotime('+2 day'));
        foreach (AssetCategory::all() as $category) {
            $this->quantity[$category->T21_id] = 0;
        }
    }
    public function createrequest()
    {
        $this->user = User::findOrFail($this->user_id);
        $this->validate();
        $request = Request::create([
            'T30T10_user_id' => $this->user_id,
            'T30_type' => $this->type,
            'T30_start_date' => $this->start_date,
            'T30_end_date' => $this->end_date,
            'T30_reason' => $this->reason,
            'T30_location' => $this->location,
            'T30_remark' => $this->remark,
        ]);
        $this->request_id = $request->T30_id;
        foreach($this->quantity as $category_id => $qty){
            if($qty > 0){
                RequestAsset::create([
                    'T31T30_request_id' => $this->request_id,
                    'T31T21_asset_category_id' => $category_id,
                    'T31_quantity' => $qty,
                ]);
            }
        }
        $this->activemodal = null;
        $this->dispatch('refresh-request');
        $this->reset(['type','start_date','end_date','reason','location','remark','quantity']);
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
                <x-ui.input type="hidden" wire:model="user_id"></x-ui.input>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-ui.select wire:model="type" label="Kegunaan" id="type" required>
                    <option value="" disabled selected>Pilih Kegunaan</option>
                    @foreach(Request::TYPE as $key => $type)
                        <option value="{{ $key }}">{{ $type }}</option>
                    @endforeach
                </x-ui.select>
                <x-ui.input type="date" wire:model="start_date" label="Dari" id="start_date" required></x-ui.input>
                <x-ui.input type="date" wire:model="end_date" label="Hingga" id="end_date" required></x-ui.input>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-ui.input type="text" wire:model="reason" label="Tujuan" id="reason" required></x-ui.input>
                <x-ui.input type="text" wire:model="location" label="Tempat Penggunaan" id="location" required></x-ui.input>
                <x-ui.input type="text" wire:model="remark" label="Catatan (Jika Perlu)" id="remark"></x-ui.input>
            </div>
            @foreach(AssetCategory::all() as $category)
                <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                    <x-ui.input type="number"
                        wire:model="quantity.{{ $category->T21_id }}"
                        label="Bilangan {{ ucfirst($category->T21_name) }}"
                        id="{{ ucfirst($category->T21_name) }}_quantity"
                    >
                    </x-ui.input>
                </div>
            @endforeach
            <x-submit-button>
                Hantar
            </x-submit-button>
        </form>
    </x-content-modal>
    @endif
</div>
