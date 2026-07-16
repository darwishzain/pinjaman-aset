<?php
use function Livewire\Volt\{state,on,with,rules};
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
state([
    'show' => false,
    'title' => '',
    'userid' => '',
    'user'=> '',
    'userroles' => [],
    'userpermissions' => []
]);
with(fn () => [
    'allpermissions' => Permission::all(),
    'allroles' => Role::all(),
]);
rules(function () {
    return [
        'userroles' => ['array'],
        'userpermissions' => ['array'],
    ];
});
$loadEmptyForm = function ()
{
    $this->show = true;
    $this->title = 'Tambah Pengguna';
    $this->user = null;
    $this->userpermissions = [];
    $this->userroles = [];
};
$loadUserForm = function ($id)
{
    $this->show = 'true';
    $this->user = User::findOrFail($id);
    $this->title = 'Kemaskini Pengguna';
    $this->userpermissions = $this->user
        ->getPermissionNames()
        ->toArray();
    $this->userroles = $this->user
        ->getRoleNames()
        ->toArray();
};

on([
    'edit-user-form' => fn ($id) => $this->loadUserForm($id),
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
                    @can('update:user-roles')
                    <form wire:submit="save">
                        @if ($user)
                        <input
                            type="text"
                            wire:model="userid"
                            value="{{ $user->id }}"
                            hidden
                        >
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <div class="text-sm text-gray-500">Nama</div>
                                <div class="font-medium">{{ $user->name }}</div>
                            </div>

                            <div>
                                <div class="text-sm text-gray-500">E-mel</div>
                                <div>{{ $user->email }}</div>
                            </div>
                        </div>
                        @endif
                        {{--Roles--}}
                        <h3>Peranan</h3>
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($allroles as $role)
                                <label class="flex items-center gap-2 rounded-lg border border-gray-200 p-3 hover:bg-gray-50 cursor-pointer">
                                    <input
                                        type="checkbox"
                                        wire:model="userroles"
                                        value="{{ $role->name }}"
                                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    >

                                    <span>{{ $role->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        {{--Permissions--}}
                        <h3>Peranan</h3>
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($allpermissions as $permission)
                                <label class="flex items-center gap-2 rounded-lg border border-gray-200 p-3 hover:bg-gray-50 cursor-pointer">
                                    <input
                                        type="checkbox"
                                        wire:model="userroles"
                                        value="{{ $permission->name }}"
                                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    >

                                    <span>{{ $permission->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </form>
                    @endcan
                </div>

                <!-- Footer -->
                <div class="flex justify-end gap-2 border-t p-6">
                    <button wire:click="$set('show', false)">Batal</button>
                    <button wire:click="save" class="">Simpan</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
