<?php

use Livewire\Volt\Component;
use Livewire\Attributes\On;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use function Livewire\Volt\{state,on,rules};

new class extends Component {
    public bool $showform = false;
    public string $title = '';
    public string $activeform = '';
    public Collection $allroles;
    public Collection $allpermissions;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public User $user;
    public string $userid = '';
    public array $userroles = [];
    public array $userpermissions = [];
    public array $rolepermissions = [];

    public Role $role;
    public string $roleid = '';

    #[On('loadcreateform')]
    public function loadcreateform(){
        $this->showform = true;
        $this->title = 'Tambah Pengguna';
        $this->activeform = 'create-user';
    }
    public function createuser(){
        $validated =$this->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8','confirmed'],
        ]);
        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);
        $this->reset('name', 'email', 'password', 'password_confirmation');
        $this->showform = false;
        $this->dispatch('refresh-user');

    }
    #[On('loadedituserform')]
    public function loadedituserform($id){
        $this->showform = true;
        $this->title = 'Kemaskini Pengguna';
        $this->activeform = 'edit-user';
        $this->user = User::findOrFail($id);
        $this->userid = $this->user->id;
        $this->allroles = Role::all();
        $this->allpermissions = Permission::all();
        $this->userroles = $this->user
            ->roles->pluck('id')
            ->mapWithKeys(fn ($id) => [$id => true])
            ->toArray();
        $this->userpermissions = $this->user
            ->permissions->pluck('id')
            ->mapWithKeys(fn ($id) => [$id => true])
            ->toArray();

    }
    public function updateuser(){
        if(!$this->userid){
            return;
        }
        $this->user = User::findOrFail($this->userid);
        $roleids = collect($this->userroles)
            ->filter(fn ($checked) => (bool) $checked)
            ->keys()
            ->map(fn ($id) => (int) $id)
            ->toArray();
        $permissionids = collect($this->userpermissions)
            ->filter(fn ($checked) => (bool) $checked)
            ->keys()
            ->map(fn ($id) => (int) $id)
            ->toArray();
        $this->user->syncRoles($roleids);
        $this->user->syncPermissions($permissionids);
        $this->reset(['userid','userroles','userpermissions']);
        $this->showform = false;
        $this->dispatch('refresh-user');
        $this->dispatch('refresh-role');
    }
    #[On('loadeditroleform')]
    public function loadeditroleform($id){
        $this->showform = true;
        $this->title = 'Kemaskini Peranan';
        $this->activeform = 'edit-role';
        $this->role = Role::findOrFail($id);
        $this->roleid = $this->role->id;
        $this->allpermissions = Permission::all();
        $this->rolepermissions = $this->role
            ->permissions->pluck('id')
            ->mapWithKeys(fn ($id) => [$id => true])
            ->toArray();
    }
    public function updaterole(){
        if (!$this->roleid) {
            return;
        }
        $this->role = Role::findOrFail($this->roleid);
        $permissionids = collect($this->rolepermissions)
            ->filter(fn ($value) => (bool) $value)
            ->keys()
            ->map(fn ($id) => (int) $id)
            ->toArray();
        $this->role->syncPermissions($permissionids);
        $this->reset(['roleid','rolepermissions']);
        $this->showform = false;
        $this->dispatch('refresh-user');
        $this->dispatch('refresh-role');
    }

}; ?>

<div>
    @if ($showform)
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center">
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center p-4">
            <div class="w-full max-w-4xl rounded-xl bg-white shadow-xl">
                <!-- Header -->
                <div class="border-b p-6">
                    <h2 class="text-lg font-semibold">
                        {{ $title }}

                        <button wire:click="$set('showform', false)" class="float-right">
                            ✕
                        </button>
                    </h2>
                </div>

                <!-- Scrollable Body -->
                <div class="max-h-[65vh] overflow-y-auto p-6">
                    @if($this->activeform === 'create-user')
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
                    @elseif($this->activeform === 'edit-user' && $user)
                        @can('update:user-roles')
                            <form wire:submit="updateuser">
                                    <input
                                        type="hidden"
                                        wire:model="userid"
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
                                    {{--Roles--}}
                                    <h3>Peranan</h3>
                                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                                        @foreach ($allroles as $role)
                                            <label class="flex items-center gap-2 rounded-lg border border-gray-200 p-3 hover:bg-gray-50 cursor-pointer">
                                                <input
                                                    type="checkbox"
                                                    wire:model="userroles.{{ $role->id }}"
                                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                >
                                                <span>{{ $role->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    {{--Permissions--}}
                                    <h3>Kebenaran</h3>
                                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                                        @foreach ($allpermissions as $permission)
                                            <label class="flex items-center gap-2 rounded-lg border border-gray-200 p-3 hover:bg-gray-50 cursor-pointer">
                                                <input
                                                    type="checkbox"
                                                    wire:model="userpermissions.{{ $permission->id }}"
                                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                >
                                                <span>{{ $permission->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    <x-submit-button>
                                        Kemaskini
                                    </x-submit-button>
                            </form>
                        @endcan
                    @elseif($this->activeform === 'edit-role' && $role)
                        @can('update:user-roles')
                            <form wire:submit="updaterole">
                                <input
                                    type="hidden"
                                    wire:model="roleid"
                                >
                                <div class="grid grid-cols-1 gap-1">
                                    <div>
                                        <div class="text-sm text-gray-500">Nama Peranan</div>
                                        <div class="font-medium">{{ $role->name }}</div>
                                    </div>
                                </div>
                                {{--Permissions--}}
                                <h3>Kebenaran</h3>
                                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                                    @foreach ($allpermissions as $permission)
                                        <label class="flex items-center gap-2 rounded-lg border border-gray-200 p-3 hover:bg-gray-50 cursor-pointer">
                                            <input
                                                type="checkbox"
                                                wire:model="rolepermissions.{{ $permission->id }}" 
                                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                            >
                                            <span>{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <x-submit-button>
                                    Kemaskini
                                </x-submit-button>
                            </form>
                        @endcan
                    @elseif($this->activeform === 'error')
                    Error
                    @endif
                </div>

                <!-- Footer -->
                <div class="flex justify-end gap-2 border-t p-6">
                    <h2 class="text-lg font-semibold">
                        <button wire:click="$set('showform', false)" class="float-right">
                            Batal
                        </button>
                    </h2>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
