<?php

use function Livewire\Volt\{state,on,rules};
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

state([
    'show' => false,
    'title' => '',
    'name' => '',
    'email' => '',
    'password' => '',
    'password_confirmation' => '',
]);
$rules = fn () => [
    'name' => ['required', 'string'],
    'email' => ['required', 'email', 'unique:users,email'],
    'password' => ['required', 'min:8','confirmed'],
];
$createuser = function ()
{
    $this->validate();
    $user = User::create([
        'name' => $this->name,
        'email' => $this->email,
        'password' => Hash::make($this->password),
    ]);
    session()->flash('success', 'Pengguna Ditambah!');
    $this->reset(['name', 'email', 'password']);
    $this->dispatch('refreshloa-user');
    $this->show = false;
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
                <x-modal-header>
                    {{ $title }}
                </x-modal-header>

                <!-- Scrollable Body -->
                <div class="max-h-[65vh] overflow-y-auto p-6">
                    @can('create:users')
                    <form wire:submit="createuser">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block mb-1">Name</label>
                                <input
                                    type="text"
                                    wire:model="name"
                                    class="w-full rounded border-gray-300 focus:ring-indigo-500"
                                >
                            </div>
                            <div>
                                <label class="block mb-1">Email</label>
                                <input
                                    type="email"
                                    wire:model="email"
                                    class="w-full rounded border-gray-300 focus:ring-indigo-500"
                                >
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block mb-1">Kata Laluan</label>
                                <input
                                    type="password"
                                    wire:model="password"
                                    class="w-full rounded border-gray-300 focus:ring-indigo-500"
                                >
                            </div>
                            <div>
                                <label class="block mb-1">Pengesahan Kata Laluan</label>
                                <input
                                    type="password"
                                    wire:model="password_confirmation"
                                    class="w-full rounded border-gray-300 focus:ring-indigo-500"
                                >
                            </div>
                        </div>
                        <x-submit-button>
                            Tambah Pengguna
                        </x-submit-button>
                    </form>
                    @endcan
                </div>

                <!-- Footer -->
                <x-modal-footer></x-modal-footer>
            </div>
        </div>
    </div>
    @endif
</div>
