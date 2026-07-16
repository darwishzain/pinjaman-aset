<?php

use function Livewire\Volt\{state};

?>

<div>
    <livewire:user.access-modal />
    <livewire:user.view-modal />
    <livewire:user.create-modal />
    <livewire:role.access-modal />
    @canany(['create:user'])
    <button wire:click="$dispatch('create-user-form')">
        Tambah Pengguna
    </button>
    @endcan
    <livewire:user.list />
</div>
