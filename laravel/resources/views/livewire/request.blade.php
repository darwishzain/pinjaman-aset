<?php

use Livewire\Volt\Component;
use App\Models\Request;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Enums\RequestStatus;
use App\Enums\ReviewStatus;

new class extends Component {
    use withPagination;
    public string $title = "Senarai Permohonan";
    public function mount()
    {
        if(!auth()->user()->canAny(['create:requests','view:requests','view-any:requests','update:requests','support:requests','approve:requests']))
        {
            abort(403,"Tiada kebenaran untuk mengakses halaman ini");
        }
    }
    protected function getAllowedStatuses(): array
    {
        $user = Auth::user();

        return array_keys(array_filter(
            $this->statusPermissions,
            fn ($permission) => $user->can($permission)
        ));
    }
    #[Computed]
    public function requests()
    {
        $user = auth()->user();
        $query = Request::query();
        if($user->can('view-any:requests'))
        {
            $query->where('T30_status','!=',RequestStatus::COMPLETED);
        }
        else if($user->can('approve:requests'))
        {
            $query->where('T30_status','!=',RequestStatus::COMPLETED)
            ->where('T30_status',RequestStatus::PENDING)
            ->where('T30_support_status',ReviewStatus::ACCEPTED)
            ->where('T30_approve_status',ReviewStatus::PENDING);
        }
        else if($user->can('support:requests'))
        {
            $query->where('T30_status','!=',RequestStatus::COMPLETED)
            ->where('T30_status',RequestStatus::PENDING)
            ->where('T30_support_status',ReviewStatus::PENDING)
            ->where('T30_approve_status',ReviewStatus::PENDING)
            ->where('group',$user->group);
        }
        else
        {
            $query->where('T30T10_user_id',$user->id);
        }
        return $query->paginate(10);
    }
    #[On('refresh-request')]
    public function refreshRequest()
    {

    }
}; ?>

<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $this->title ?? __('Aset') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <x-ui.module-nav-group>
                    <x-ui.module-nav-button wire:click="$dispatch('loadcreaterequest')">Permohonan Baru</x-ui.module-nav-button>
                </x-ui.module-nav-group>
                @canany(['create:requests','view:requests','view-any:requests','update:requests','support:requests','approve:requests'])
                    <livewire:request.modal></livewire:.modal>
                    <livewire:view.modal></livewire:view.modal>
                    @if($this->requests->isEmpty())
                        <x-ui.title>Tiada Permohonan untuk dipaparkan</x-ui.title>
                    @else
                        <x-ui.table>
                            <x-slot name="header">
                                <tr>
                                    <x-ui.th>Nama Pemohon</x-ui.th>
                                    <x-ui.th>Tujuan</x-ui.th>
                                    <x-ui.th>Status</x-ui-th>
                                    <x-ui.th>Tindakan</x-ui.th>
                                </tr>
                            </x-slot>
                            @foreach($this->requests as $request)
                                <tr wire:key="request-row-{{$request->T30_id}}">
                                    <x-ui.td>
                                        <x-ui.user-list-item :user="$request->user"></x-ui.user-list-item>
                                    </x-ui.td>
                                    <x-ui.td>
                                        {{$request->T30_reason}} di {{$request->T30_location}}
                                    </x-ui.td>
                                    <x-ui.td><x-ui.status-pill :status="$request->T30_status"></x-ui.status-pill></x-ui.td>
                                    <x-ui.td>
                                        @if($request->canUpdate())
                                        {{--UPDATE REQUEST BUTTON HERE--}}
                                        @endif
                                        @if($request->canSupport())
                                        <x-ui.button color="blue" wire:click="$dispatch('loadsupportrequest', { id: '{{ $request->T30_id }}' })">Sokong</x-ui.button>
                                        @endif
                                        @if($request->canApprove())
                                        <x-ui.button color="blue" wire:click="$dispatch('loadapproverequest', { id: '{{ $request->T30_id }}' })">Luluskan</x-ui.button>
                                        @endif
                                    </x-ui.td>
                                </tr>
                            @endforeach
                        </x-ui.table>
                        {{ $this->requests->links() }}
                    @endif
                @endcanany
            </div>
        </div>
    </div>
</div>
