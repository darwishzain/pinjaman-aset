<?php
use function Livewire\Volt\{state,with,on};
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
state([
    'show' => false,
    'title' => '',
    'userrole' => []
]);
$loadAccessForm = function ($id)
{
    $this->show = true;
    $this->userrole = Role::findOrFail($id);
    $this->title = 'Kemaskini Peranan';
};
on([
    'edit-role-form' => fn ($id) => $this -> loadAccessForm($id),
]);
?>
<div>
    @if ($show)
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center">
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center p-4">
            <div class="w-full max-w-4xl rounded-xl bg-white shadow-xl">
                <!-- Header -->
                <x-modal-header wire:click="$set('show', false)">
                    {{ $title }}
                </x-modal-header>

                <!-- Scrollable Body -->
                <div class="max-h-[65vh] overflow-y-auto p-6">
                    
                </div>

                <!-- Footer -->
                <div class="flex justify-end gap-2 border-t p-6">
                    <button wire:click="$set('show', false)">Batal</button>
                    <button wire:click="" class="">Simpan</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>