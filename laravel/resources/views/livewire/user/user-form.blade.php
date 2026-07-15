<?php

use function Livewire\Volt\{state,on,with};
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
state([
    'show' => false,
    'title' => '',
    'name' => '',
    'email' => '',
    'userroles' => [],
    'userpermissions' => []
]);
with(fn () => [
    'allpermissions' => Permission::all(),
    'allroles' => Role::all(),
]);

on([
    'create-user-form' => function () {
        $this->show = true;
    },
    'close-user-form' => function () {
        $this->show = false;
    },
    'edit-user-form' => function ($id) {
        $this->show = true;
        $this->user = User::findOrFail($id);
        $this->title = 'Kemaskini Pengguna';
        $this->name = $this->user->name;
        $this->email = $this->user->email;
    },
]);

?>

<div>
    @if ($show)
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center">
        <div class="bg-white p-6 rounded shadow-lg w-full max-w-lg">
            <h1 class="w-full">{{ $title }}</h1>
            <div>
                <div>Nama</div>
                <div>E-mel</div>
            </div>
            <div>
                <div><input wire:model="name"></div>
                <div><input wire:model="email"></div>
            </div>
            @foreach ($allpermissions as $permission)
            <label class="flex items-center gap-2">
                <input
                    type="checkbox"
                    wire:model="permissions"
                    value="{{ $permission->name }}"
                >

                {{ $permission->name }}
            </label>
            @endforeach
            @foreach ($allroles as $role)
            <label class="flex items-center gap-2">
                <input
                    type="checkbox"
                    wire:model="permissions"
                    value="{{ $role->name }}"
                >

                {{ $role->name }}
            </label>
            @endforeach
            <button wire:click="save">Simpan</button>
            <button wire:click="$set('show', false)">Batal</button>
        </div>
    </div>
    @endif
</div>
