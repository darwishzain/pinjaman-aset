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
    public string $scheduled_pickup_at = '';
    public string $reason = '';
    public string $location = '';
    public string $remark = '';
    public array $quantity = [];

    public ?Request $request = null;
    public ?User $user = null;
    public array $support = ['comment' => '', 'status' => ''];
    public array $approve = ['comment' => '', 'status' => ''];
    protected function rules():array
    {
        if($this->activemodal === 'create-request')
        {
            $rules = [
                'user_id' => ['required','exists:users,id'],
                'type' => ['required','in:individual,department'],
                'scheduled_pickup_at' => ['required','date'],
                'start_date' => ['required','date','after:scheduled_pickup_at'],
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
        else if($this->activemodal === 'support-request')
        {
            $rules = [
                'request_id' => ['required','exists:T30_requests,T30_id'],
                'support.status' => ['required','in:accepted,declined'],
                'support.comment' => ['nullable','string','max:255'],
            ];
        }
        else if($this->activemodal === 'approve-request')
        {
            $rules = [
                'request_id' => ['required','exists:T30_requests,T30_id'],
                'scheduled_pickup_at' => ['required','date'],
                'approve.status' => ['required','in:accepted,declined'],
                'approve.comment' => ['nullable','string','max:255'],
            ];
        }
        return $rules ?? [];
    }
    #[On('loadcreaterequest')]
    public function loadcreaterequest()
    {
        $this->activemodal = 'create-request';
        $this->title = 'Permohonan Baru';
        $this->reset(['request','user_id','type','start_date','end_date','scheduled_pickup_at','reason','location','remark','quantity']);
        $this->user = User::findOrFail(auth()->user()->id);
        $this->user_id = $this->user->id;
        $this->scheduled_pickup_at = date('Y-m-d\TH:i', strtotime('tomorrow 08:00'));
        $this->start_date = date('Y-m-d', strtotime('+2 day'));
        $this->end_date = date('Y-m-d', strtotime('+3 day'));
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
            'T30_scheduled_pickup_at' => $this->scheduled_pickup_at,
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
        $this->reset(['type','start_date','end_date','scheduled_pickup_at','reason','location','remark','quantity']);
    }
    #[On('loadsupportrequest')]
    public function loadsupportrequest($id)
    {
        $this->activemodal = 'support-request';
        $this->title = 'Sokong Permohonan';
        $this->request = Request::with('requestAssets')->findOrFail($id);
        $this->request_id = $this->request->T30_id;
        $this->user = $this->request->user;
        $this->user_id = $this->user->id;
        $this->support = [
            'status' => '',
            'comment' => '',
        ];
    }
    public function supportrequest()
    {
        $this->validate();
        $this->request = Request::findOrFail($this->request_id);
        $this->request->T30T10_support_by_id = auth()->user()->id;
        $this->request->T30_support_comment = $this->support['comment'] ?? '';
        if($this->support['status'] === 'declined'){
            $this->request->T30_status = 'declined';
            $this->request->T30_scheduled_pickp_at = null;
        }
        $this->request->T30_support_status = $this->support['status'];
        $this->request->T30_support_at = now();
        $this->request->save();
        $this->activemodal = null;
        $this->dispatch('refresh-request');
        $this->reset(['support']);
    }
    #[On('loadapproverequest')]
    public function loadapproverequest($id)
    {
        $this->activemodal = 'approve-request';
        $this->title = 'Kelulusan Permohonan';
        $this->request = Request::with('requestAssets')->findOrFail($id);
        $this->request_id = $this->request->T30_id;
        $this->user = $this->request->user;
        $this->scheduled_pickup_at = $this->request->T30_scheduled_pickup_at;
        $this->user_id = $this->user->id;
        $this->approve = [
            'status' => '',
            'comment' => '',
        ];
    }
    public function approverequest()
    {
        $this->validate();
        $this->request = Request::findOrFail($this->request_id);
        $this->request->T30T10_support_by_id = auth()->user()->id;
        $this->request->T30_support_comment = $this->approve['comment'] ?? '';
        if($this->approve['status'] === 'declined'){
            $this->request->T30_status = 'declined';
            $this->request->T30_scheduled_pickup_at = null;
        }
        else if($this->T30_approve['status'] === 'accepted')
        {
            $this->request->T30_status = 'pickup';
            $this->request->T30_scheduled_pickup_at = $this->scheduled_pickup_at;
        }
        $this->request->T30_support_status = $this->approve['status'];
        $this->request->T30_support_at = now();
        $this->request->save();
        $this->activemodal = null;
        $this->dispatch('refresh-request');
        $this->reset(['approve']);
    }
}; ?>

<div>
    @if(in_array($activemodal,['create-request','support-request','approve-request']))
    <x-content-modal title="{{ $title }}">
        <div class="text-center">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <x-ui.user-list-item :user="$user"></x-ui.user-list-item>
            </div>
            <form wire:submit="
                @if($activemodal === 'create-request')
                    createrequest
                @elseif($activemodal === 'support-request')
                    supportrequest
                @elseif($activemodal === 'approve-request')
                    approverequest
                @endif
                ">
                <x-ui.input type="hidden" wire:model="user_id"></x-ui.input>
                @if($request?->exists)
                    <x-ui.title>Status {{ $request->T30_status->label() }}</x-utitle>

                    <x-ui.input type="hidden" wire:model="request_id"></x-ui.input>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <x-ui.display-field label="Kegunaan" value="{{ Request::TYPE[$request->T30_type] }}" id="type"></x-ui.display-field>
                        <x-ui.display-field label="Dari" value="{{ $request->T30_start_date }}" id="start_date"></x-ui.display-field>
                        <x-ui.display-field label="Hingga" value="{{ $request->T30_end_date }}" id="end_date"></x-ui.display-field>
                        <x-ui.display-field 
                            label="Masa Dipindah ke Lokasi Penggunaan"
                            value="{{ $request->T30_scheduled_pickup_at ? date('d/m/Y H:i', strtotime($request->T30_scheduled_pickup_at)) : '---' }}"
                            id="scheduled_pickup_at">
                        </x-ui.display-field>
                        <x-ui.display-field label="Tujuan" value="{{ $request->T30_reason }}" id="reason"></x-ui.display-field>
                        <x-ui.display-field label="Tempat Penggunaan" value="{{ $request->T30_location }}" id="location"></x-ui.display-field>
                        <x-ui.display-field label="Catatan" value="{{ !empty($request->T30_remark) ? $request->T30_remark : '--Tiada--' }}" id="remarks"></x-ui.display-field>
                    </div>
                    <x-ui.title>Bilangan Aset</x-ui.title>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($request->requestAssets as $requestasset)
                            <x-ui.display-field 
                                id="{{ strtolower($requestasset->category->T21_name) }}_quantity"
                                value="{{ ucfirst($requestasset->category->T21_name)}} x {{ $requestasset->T31_quantity }}"
                                >
                            </x-ui.display-field>
                        @endforeach
                    </div>
                    @if($activemodal === 'support-request')
                        <x-ui.title>Sokong Permohonan</x-ui.title>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-ui.select label="Sokongan" wire:model="support.status" required id="support_status">
                                <option value="" disabled selected>Pilih Sokongan</option>
                                <option value="accepted">Diterima</option>
                                <option value="declined">Ditolak</option>
                            </x-ui.select>
                            <x-ui.input wire:model="support.comment" label="Komen" id="comment"></x-ui.input>
                        </div>
                    @elseif($activemodal === 'approve-request')
                        <x-ui.title>Kelulusan Permohonan</x-ui.title>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-ui.select label="Kelulusan" wire:model="approve.status" required id="approve_status">
                                <option value="" disabled selected>Pilih Kelulusan</option>
                                <option value="accepted">Diterima</option>
                                <option value="declined">Ditolak</option>
                            </x-ui.select>
                            <x-ui.input type="text" wire:model="approve.comment" label="Komen" id="comment"></x-ui.input>
                            <x-ui.input type="datetime-local" wire:model="scheduled_pickup_at" label="Masa Dipindah ke Lokasi Penggunaan" id="scheduled_pickup_at"></x-ui.input>
                        </div>
                    @endif
                @else
                    <x-ui.title>Perihal Permohonan</x-ui.title>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <x-ui.select wire:model="type" label="Kegunaan" id="type" required>
                            <option value="" disabled selected>Pilih Kegunaan</option>
                            @foreach(Request::TYPE as $key => $type)
                                <option value="{{ $key }}">{{ $type }}</option>
                            @endforeach
                        </x-ui.select>
                        <x-ui.input type="date" wire:model="start_date" label="Dari" id="start_date" required></x-ui.input>
                        <x-ui.input type="date" wire:model="end_date" label="Hingga" id="end_date" required></x-ui.input>
                        <x-ui.input type="datetime-local" wire:model="scheduled_pickup_at" label="Masa Dipindah ke Lokasi Penggunaan" id="scheduled_pickup_at" required></x-ui.input>
                        <x-ui.input type="text" wire:model="reason" label="Tujuan" id="reason" required></x-ui.input>
                        <x-ui.input type="text" wire:model="location" label="Tempat Penggunaan" id="location" required></x-ui.input>
                        <x-ui.input type="text" wire:model="remark" label="Catatan (Jika Perlu)" id="remark"></x-ui.input>
                    </div>
                    <x-ui.title>Bilangan Aset</x-ui.title>
                    @foreach(AssetCategory::all() as $category)
                        <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                            <x-ui.input type="number"
                                wire:model="quantity.{{ $category->T21_id }}"
                                label="Bilangan {{ ucfirst($category->T21_name) }}"
                                id="{{ strtolower($category->T21_name) }}_quantity"
                            >
                            </x-ui.input>
                        </div>
                    @endforeach
                @endif
                <x-submit-button>
                    Hantar
                </x-submit-button>
            </form>
        </div>
    </x-content-modal>
    @endif
</div>
