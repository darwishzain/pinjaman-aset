<?php

use App\Models\User;
use Livewire\Volt\Component;
use function Livewire\Volt\{computed,on,state,with};
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
on(['refresh-user' => function () {

}]);
new class extends Component{
    public $formlabel = '';
    #[Computed]
    public function users(){
        return User::all();
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
    @canany(['create:users'])
    <button wire:click="$dispatch('loadcreateform')">Tambah Pengguna</button>
    @endcanany
    <div>
        <table class="w-full border border-collapse">
            <tr>
                <th>Nama</th>
                <th>Peranan</th>
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
                    @foreach ($user->roles as $role)
                        <button wire:click="$dispatch('loadeditroleform',{id:{{$role->id}}})">
                            {{ $role->name }}
                        </button>
                    @endforeach
                </td>
                <td>
                    @foreach ($user->permissions as $permission)
                        {{ $permission->name }}
                    @endforeach
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
    </div>
@endcanany
</div>
