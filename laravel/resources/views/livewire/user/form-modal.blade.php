<?php

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Volt\Component;
use Livewire\Attributes\On;

new class extends Component {
    public ?string $activemodal = null;
    public string $title = '';
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
        $this->title = 'Tambah Pengguna';
        $this->activemodal = 'create-user';
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
        $this->activemodal = null;
        $this->dispatch('refresh-user');

    }
    #[On('loadedituserform')]
    public function loadedituserform($id){
        $this->title = 'Kemaskini Pengguna';
        $this->activemodal = 'update-user';
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
        $this->activemodal = null;
        $this->dispatch('refresh-user');
        $this->dispatch('refresh-role');
    }
    #[On('loadeditroleform')]
    public function loadeditroleform($id){
        $this->title = 'Kemaskini Peranan';
        $this->activemodal = 'update-role';
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
        $this->activemodal = null;
        $this->dispatch('refresh-user');
        $this->dispatch('refresh-role');
    }

}; ?>

<div>
    @if (in_array($activemodal,['create-user','update-user','update-role']))
    <x-content-modal title="{{ $title }}">
        @if($this->activemodal === 'create-user')
            @can('create:users')
                <form wire:submit="createuser">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <x-ui.input type="text" wire:model="name" label="Nama" id="name"></x-ui.input>
                        <x-ui.input type="email" wire:model="email" label="E-mel" id="email"></x-ui.input>
                        <x-ui.select wire:model="group" label="Kumpulan" id="group">
                            <option value="" disabled selected>{{ __('Pilih Kumpulan Pengguna') }}</option>
                            @foreach(User::GROUPS as $key => $name)
                            <option value="{{ $key }}">{{ $name }}</option>
                            @endforeach
                        </x-ui.select>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-ui.input type="password" wire:model="password" label="Kata Laluan" id="password"></x-ui.input>
                        <x-ui.input type="password" wire:model="password_confirmation" label="Pengesahan Kata Laluan" id="password_confirmation"></x-ui.input>
                    </div>
                    <x-submit-button>
                        Tambah Pengguna
                    </x-submit-button>
                </form>
            @endcan
        @elseif($this->activemodal === 'update-user' && $user)
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
        @elseif($this->activemodal === 'update-role' && $role)
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
        @endif
    </x-content-modal>
    @endif
</div>
