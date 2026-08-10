<?php

use App\Models\User;
use App\Models\Asset;
use App\Models\Request;
use Livewire\Volt\Component;
use Livewire\Attributes\On;

new class extends Component {
    public ?string $activemodal = null;
    public string $title = '';

    public ?User $user = null;
    public ?string $user_id = null;
    public ?Asset $asset = null;
    public ?string $asset_id = null;
    public ?Request $request = null;
    public ?string $request_id = null;
    public ?string $transaction_id = null;
    #[On('viewuserprofile')]
    public function loaduserprofile($id)
    {
        $this->activemodal = "user-profile";
        $this->title = 'Profil Pengguna';
        $this->user = User::findOrFail($id);
        $this->user_id = $this->user->id;
    }
    #[On('viewasset')]
    public function loadasset($id)
    {
        $this->activemodal = 'view-asset';
        $this->title = 'Perihal Aset';
        $this->asset = Asset::findOrFail($id);
    }
    #[On('viewrequest')]
    public function loadrequest($id)
    {
        $this->activemodal = 'view-request';
        $this->title = 'Perihal Permohonan';
        $this->request = Request::findOrFail($id);
    }
    #[On('viewtransaction')]

}; ?>

<div>
    @if(in_array($activemodal,['user-profile','view-asset','view-request']))
        <x-ui.content-modal title="{{ $title }}">
            @if($activemodal === 'user-profile')
                <x-ui.title>
                    <x-ui.user-list-item :user="$user"></x-ui.user-list-item>
                </x-ui.title>
                @if($user->permissions->isNotEmpty())
                    <x-ui.title> Kebenaran Tambahan</x-ui.title>
                    @foreach ($user->permissions as $permission)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium whitespace-nowrap bg-gray-200">{{ $permission->name }}</span>
                    @endforeach
                @endif
                <x-ui.display-field></x-ui.display-field>
            @elseif($activemodal === 'view-asset')
                <x-ui.title>
                    {{ $asset->T20_tag }}
                    <x-ui.status-pill :status="$asset->T20_status"></x-ui.status-pill>
                </x-ui.title>
                {{ $asset->T20_brand }}
                {{ $asset->T20_model }}
            @elseif($activemodal === 'view-request')
                <div class="grid grid-cols-1 md:grid-cols-3">
                    <div>Pemohon</div>
                    <div>Disokong oleh</div>
                    <div>Disahkan oleh</div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3">
                    <x-ui.user-list-item :user="$request->user" />
                    @if ($request->supportBy)
                        <x-ui.user-list-item :user="$request->supportBy" />
                    @else
                        <div><x-ui.status-pill :status="$request->T30_support_status"></x-ui.status-pill></div>
                    @endif
                    @if ($request->approveBy)
                        <x-ui.user-list-item :user="$request->approveBy" />
                    @else
                        <div><x-ui.status-pill :status="$request->T30_support_status"></x-ui.status-pill></div>
                    @endif
                    {{ $request->T30_created_at }}

                </div>
                <x-ui.title>
                    {{$request->user->name}}
                    <x-ui.status-pill :status="$request->T30_status"></x-ui.status-pill>
                </x-ui.title>
                @foreach($request->requestAssets as $requestasset)
                    {{ $requestasset->T31_quantity }}
                    {{ $requestasset->category->name }}
                @endforeach
            @endif
        </x-ui.content-modal>
    @endif
</div>
