<?php

use Livewire\Volt\Component;
use Livewire\Attributes\On;
use Illuminate\Database\Eloquent\Collection;
use App\Models\User;
use App\Models\Asset;
use App\Models\Request;
use App\Models\Transaction;
use App\Enums\AssetStatus;

new class extends Component {
    public ?string $activemodal = null;
    public string $title = '';
    public ?Collection $users = null;
    public ?Collection $assets = null;

    public ?Request $request = null;
    public ?Transaction $transaction = null;
    public ?asset $asset = null;
    public ?string $request_id = null;
    public ?string $action = null;
    public ?string $asset_id = null;
    public ?string $giver_id = null;
    public ?string $taker_id = null;
    #[On('loadtransactionin')]
    public function loadtransactionin($transaction_id)
    {
        $this->activemodal = 'transaction-in';
        $this->title = 'Pergerakan Masuk Aset';
        $this->reset(['asset_id','request_id','action','giver_id','taker_id']);
        $this->action = 'in';
        $this->transaction = Transaction::find($transaction_id);
        $this->users = User::all();
        $this->asset = Asset::find($this->transaction->T40T20_asset_id)->first();
        $this->asset_id = $this->asset->T20_id;
        $this->request_id = $this->transaction->request->T30_id;
        $this->giver_id = $this->transaction->request->T30T10_user_id;
        $this->taker_id = auth()->user()->id;
    }
    #[On('loadtransactionout')]
    public function loadtransactionout($request_id,$asset_category_id)
    {
        $this->activemodal = 'transaction-out';
        $this->title = 'Pergerakan Masuk Aset';
        $this->reset(['asset_id','request_id','action','giver_id','taker_id']);
        $this->action = 'out';
        $this->users = User::all();
        $this->assets = Asset::where('T20T21_category_id',$asset_category_id)
            ->where('T20_status', AssetStatus::AVAILABLE->value)
            ->get();
        $this->request = Request::findOrFail($request_id);
        $this->request_id = $this->request->T30_id;
        $this->giver_id = auth()->user()->id;
        $this->taker_id = $this->request->T30T10_user_id;
    }
    public function createtransaction()
    {
        $transaction  = Transaction::create([
            'T40T30_request_id' => $this->request_id,
            'T40T20_asset_id' => $this->asset_id,
            'T40_action' => $this->action,
            'T40T10_giver_id' => $this->giver_id,
            'T40T10_taker_id' => $this->taker_id,
            'T40T10_handler_id' => auth()->user()->id,
        ]);
        if($transaction)
        {
            $asset = Asset::find($this->asset_id);
            if($this->action == 'out')
            {
                $asset->update(['T20_status' => AssetStatus::ACTIVE,]);
            }
            else if($this->action == 'in')
            {
                $asset->update(['T20_status' => AssetStatus::AVAILABLE,]);
            }
            $this->activemodal = null;
        }
    }
}; ?>

<div>
    @if(in_array($activemodal,['transaction-in','transaction-out']))
    <x-ui.content-modal title="{{$title}}">
        <form
            wire:submit="createtransaction"
        >
            <x-ui.input type="hidden" wire:model="request_id"></x-ui.input>
            <x-ui.input type="hidden" wire:model="action"></x-ui.input>
            @if(isset($asset))
                <x-ui.input type="hidden" wire:model="asset_id" id="asset_id"></x-ui.input>
            @endif
            <x-ui.grid min="1" max="3">
                @if(isset($asset))
                    <x-ui.display-field value="{{ $asset->T20_tag }}" label="Aset" ></x-ui.display-field>
                @else
                    <x-ui.select wire:model="asset_id" label="Aset" id="asset_id">
                        <option value="">--Pilih Aset--</option>
                        @foreach($assets as $asset)
                            <option value="{{ $asset->T20_id}}">{{$asset->T20_tag }}</option>
                        @endforeach
                    </x-ui.select>
                @endif
                <x-ui.select wire:model="giver_id" label="Pemberi" id="giver_id">
                    @foreach($users as $user)
                    <option value="{{$user->id}}">{{$user->name}}</option>
                    @endforeach
                </x-ui.select>
                <x-ui.select wire:model="taker_id" label="Penerima" id="taker_id">
                    @foreach($users as $user)
                    <option value="{{$user->id}}">{{$user->name}}</option>
                    @endforeach
                </x-ui.select>
                <x-ui.button>Simpan</x-ui.button>
            </x-ui.grid>
        </form>
    </x-ui.content-modal>
    @endif
</div>
