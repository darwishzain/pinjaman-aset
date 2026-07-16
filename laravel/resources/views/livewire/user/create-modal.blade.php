<?php

use function Livewire\Volt\{state,on,rules};
use Illuminate\Support\Facades\Hash;

state([
    'show' => false,
    'title' => '',
    'name' => '',
    'email' => '',
    'password' => '',
    'pasword_confirm' => '',
]);
$rules = fn () => [
    'name' => ['required', 'string'],
    'email' => ['required', 'email', 'unique:users,email'],
    //'password' => ['required', 'min:8'],
];
$createuser = function ()
{
    $this->validate();
    User::create([
        'name' => $this->name,
        'email' => $this->email,
        'password' => Hash::make($this->password),
    ]);
    session()->flash('success', 'Pengguna Ditambah!');
};
$loadCreateForm = function ()
{
    $this->show = true;
    $this->title = "Tambah Pengguna";
};
on([
    'create-user-form' => fn () => $this -> loadCreateForm(),
]);
?>

<div>
    @if($show)
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center">
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center p-4">
            <div class="w-full max-w-4xl rounded-xl bg-white shadow-xl">
                <!-- Header -->
                <x-modal-header wire:click="$set('show', false)">
                    {{ $title }}
                </x-modal-header>

                <!-- Scrollable Body -->
                <div class="max-h-[65vh] overflow-y-auto p-6">
                    @can('create:users')
                    <form>
                        <input type="text"
                            wire:model="username"
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                        >
                        <input type="email"
                            wire:model="email"
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                        >
                    </form>
                    @endcan
                </div>

                <!-- Footer -->
                <div class="flex justify-end gap-2 border-t p-6">
                    <button wire:click="$set('show', false)">Batal</button>
                    <button wire:click="createuser" class="">Simpan</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
