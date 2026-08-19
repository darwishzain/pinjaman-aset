<?php

use Livewire\Volt\Component;
use Livewire\Attributes\On;
use Illuminate\Database\Eloquent\Collection;
use App\Models\User;
use App\Models\Asset;
use App\Models\Request;
use App\Models\Transaction;
use App\Enums\AssetStatus;
use App\Enums\RequestStatus;
use Masmerise\Toaster\Toaster;

new class extends Component {
    public ?string $activemodal = null;
    public string $title = '';
    public ?Collection $users = null;
    public ?Collection $assets = null;

    public ?Request $request = null;
    public ?asset $asset = null;
    public ?string $request_id = null;
    public ?string $transaction_id = null;
    public ?string $action = null;
    public ?string $asset_id = null;
    public ?string $giver_id = null;
    public ?string $taker_id = null;
    public function resetForm()
    {
        $this->reset(['asset_id','request_id','action','giver_id','taker_id']);
        $this->resetValidation();
    }
    public function closeModal()
    {
        $this->activemodal = null;
        $this->resetForm();
        $this->dispatch('refresh-transaction');
    }
    #[On('loadtransactionin')]
    public function loadtransactionin($transaction_id)
    {
        $this->activemodal = 'transaction-in';
        $this->title = 'Pergerakan Masuk Aset';
        $this->resetForm();
        $this->action = 'in';
        $transaction = Transaction::find($transaction_id);
        $this->users = User::all();
        $this->asset = Asset::find($transaction->T40T20_asset_id);
        $this->asset_id = $this->asset->T20_id;
        $this->request_id = $transaction->request->T30_id;
        $this->giver_id = $transaction->request->T30T10_user_id;
        $this->taker_id = auth()->user()->id;
    }
    #[On('loadtransactionout')]
    public function loadtransactionout($request_id,$asset_category_id)
    {
        $this->activemodal = 'transaction-out';
        $this->title = 'Pergerakan Keluar Aset';
        $this->resetForm();
        $this->request = null;
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
            $request = Request::find($this->request_id);
            if($this->action == 'out')
            {
                $request->update(['T30_status'=>RequestStatus::ACTIVE]);
                $asset->update(['T20_status' => AssetStatus::ACTIVE,]);
                Toaster::success('Aset '.$asset->T20_tag.' berjaya diserahkan');
            }
            else if($this->action == 'in')
            {
                $updatedrequest = Request::find($this->request_id);
                if($updatedrequest->transactionCompleted())
                {
                    Toaster::warning('not pass here');
                    $updatedrequest->update(['T30_status'=>RequestStatus::COMPLETED]);
                }
                else
                {
                    Toaster::warning('Not all return');
                }
                $asset->update(['T20_status' => AssetStatus::AVAILABLE,]);
                Toaster::success('Aset '.$asset->T20_tag.' berjaya diterima');
            }
            $this->closeModal();
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
                <x-ui.button>
                    @if(isset($asset_id))
                        Terima
                    @else
                        Serah
                    @endif
                </x-ui.button>
            </x-ui.grid>
        </form>
    </x-ui.content-modal>
    @endif
</div>
