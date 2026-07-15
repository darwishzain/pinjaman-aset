<?php

use function Livewire\Volt\{state,with};
use App\Models\User;

with(fn() =>[
    'users' => User::all(),
])


//

?>

<div>
    @can('update:user-roles')
    <button wire:click="$dispatch('create-user-form')">
        Add User
    </button>
    @endcan

    <table class="w-full border border-collapse">
        <tr>
            <th>Nama</th>
            <th>Peranan</th>
            <th>Kebenaran Tambahan</th>
            <th>Tindakan</th>
        </tr>
        @foreach ($users as $user)
        <tr>
            <td class="flex item-center">
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
            </td>
            <td>
                @foreach ($user->roles as $roles)
                    {{ $roles->name }}
                @endforeach
            </td>
            <td>
                @foreach ($user->permissions as $permission)
                    {{ $permission->name }}
                @endforeach
            </td>
            <td>
                @can('update:user-roles')
                <button wire:click="$dispatch('edit-user-form',{id:{{$user->id}}})">
                    <x-feathericon-settings />
                </button>
                @endcan
                <button ></button>
            </td>
        </tr>
        @endforeach
    </table>
</div>
