<?php

use App\Models\User;
use Spatie\Permission\Models\Role;
use Livewire\Volt\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;

new class extends Component{
    public $formlabel = '';
    #[Computed]
    public function users(){
        return User::paginate(20);//! need to do eager load
    }
    #[On('refresh-user')]
    public function refreshList()
    {
        unset($this->users);
    }
};
?>
<div>
@canany(['create:users','view:users','view-any:users','update:users','update:user-roles'])
    <livewire:user.form-modal />
    <livewire:user.view-modal />
    @can('create:users')
    <x-primary-button wire:click="$dispatch('loadcreateform')">Tambah Pengguna</x-primary-button>
    @endcan
    <div>
        <div class="text-center bold"> Peranan </div>
        <div class="grid grid-cols-1 md:grid-cols-5">
            @foreach (Role::all() as $role)
                <x-primary-button wire:click="$dispatch('loadeditroleform',{id:{{$role->id}}})">
                    {{ $role->name }}
                </x-primary-button>
            @endforeach
        </div>
        <table class="w-full border border-collapse">
            <tr>
                <th>Nama</th>
                <th></th>
                <th>Kebenaran Tambahan</th>
                <th>Tindakan</th>
            </tr>
            @forelse ($this->users as $user)
            <tr wire:key="user-row-{{ $user->id }}">
                <td class="flex item-center">
                    <div
                        wire:click="$dispatch('loaduserprofile',{id:'{{ $user->id }}'})"
                        class="cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-200 rounded-lg p-2 flex items-center"
                    >
                        <div class="shrink-0 h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center font-bold text-gray-600">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $user->name }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $user->email }}
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="ml-4">
                        <div class="text-sm font-semibold text-gray-900 dark:text-white">
                            {{ User::GROUPS[$user->group] ?? ' ' }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            @foreach ($user->roles as $role)
                                <span class="mr-2">
                                    {{ $role->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </td>
                <td>
                    <div class="text-xs text-black-200 dark:text-gray-400 mt-1 break-words whitespace-normal">
                        @foreach ($user->permissions as $permission)
                            {{ $permission->name }}
                        @endforeach
                    </div>
                </td>
                <td>
                    @can('update:user-roles')
                    <button wire:click="$dispatch('loadedituserform',{id:'{{$user->id}}'})">
                        <x-feathericon-settings />
                    </button>
                    @endcan
                    <button ></button>
                </td>
            </tr>
            @empty
            <tr><td></td><td></td><td></td><td></td></tr>
            @endforelse
        </table>
        <div class="mt-3">
            {{ $this->users->links() }}
        </div>
    </div>
@endcanany
</div>
