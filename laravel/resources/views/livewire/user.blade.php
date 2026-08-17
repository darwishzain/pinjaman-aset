<?php

use Livewire\Volt\Component;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\WithPagination;

new class extends Component {
    use withPagination;
    public string $title = "Senarai Pengguna";
    public function mount()
    {
        if(!auth()->user()->canAny(['create:users','view-any:users','update:user-roles','update:user-permissions','update:role-permissions']))
        {
            abort(403,"Tiada kebenaran untuk mengakses halaman ini");
        }
    }
    #[Computed]
    public function users()
    {
        return User::paginate(10);
    }
    #[On('refresh-user')]
    public function refreshUser()
    {

    }
}; ?>

<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $title ?? __('Pengguna') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <x-ui.module-nav-group>
                    @can('create:users')
                    <x-ui.module-nav-button wire:click="$dispatch('loadcreateuserform')">Tambah Pengguna</x-ui.module-nav-button>
                    @endcan
                    @can('update:role-permissions')
                    <x-ui.module-nav-button wire:click="$dispatch('loadeditroleform')">Tetapan Peranan</x-ui.module-navvbutton>
                    @endcan
                </x-ui.module-nav-group>
                @canany(['create:users','view-any:users','update:user-roles','update:user-permissions','update:role-permissions'])
                    <livewire:user.modal/>
                    <livewire:view.modal/>
                    
                    @if($this->users->isEmpty())
                        <x-ui.title>Tiada Pengguna</x-ui.title>
                    @else
                        <x-ui.table>
                            <x-slot name="header">
                                <tr>
                                    <x-ui.th>Pengguna</x-ui.th>
                                    <x-ui.th>Peranan</x-ui.th>
                                    <x-ui.th>Permohonan</x-ui.th>
                                    <x-ui.th>Aset</x-ui.th>
                                    <x-ui.th>Tindakan</x-ui.th>
                                </tr>
                            </x-slot>
                        @foreach ($this->users as $user)
                            <tr wire:key="user-row-{{ $user->id }}">
                                <x-ui.td>
                                    <x-ui.user-list-item :user="$user"></x-ui.user-list-item>
                                </x-ui.td>
                                <x-ui.td>
                                    @foreach($user->roles as $role)
                                        {{$role->name}}
                                    @endforeach
                                </x-ui.td>
                                <x-ui.td>{{$user->requests()->count()}}</x-ui.td>
                                <x-ui.td>{{$user->transactions()->count()}}</x-ui.td>
                                <x-ui.td>
                                    @can('update:user-roles')
                                    <x-ui.button wire:click="$dispatch('loadedituserform',{id:'{{ $user->id }}'})">
                                        <x-feathericon-settings />
                                    </x-ui.button>
                                    @endcan
                                    <x-ui.button wire:click="$dispatch('viewuserprofile',{id:'{{ $user->id }}'})">
                                        <x-feathericon-info />
                                    </x-ui.button>
                                </x-ui.td>
                            </tr>
                        @endforeach
                        </x-ui.table>
                        {{ $this->users->links() }}
                    @endif
                @endcanany
            </div>
        </div>
    </div>
</div>
