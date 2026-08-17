<?php

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Livewire\Volt\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Masmerise\Toaster\Toaster;

new class extends Component {
    public ?string $activemodal = null;
    public string $title = '';
    public Collection $allroles;
    public Collection $allpermissions;

    #[Validate(
        rule:'required|string',
        message:[
            'name.required' =>'Nama wajib diisi',
            'name.string' => 'Format nama tidak sah',
        ]
    )]
    public string $name = '';
    #[Validate(
        rule: 'required|email|unique:users,email', 
        message: [
            'email.required' => 'E-mel wajib diisi.',
            'email.email'    => 'Emel perlu mengikut format e-mel yang sah.',
            'email.unique'   => 'E-mel ini telah pun didaftarkan.',
        ]
    )]
    public string $email = '';
    #[Validate(
        rule: 'required|string', 
        message: [
            'group.required' => 'Sila pilih kumpulan.',
            'group.string'   => 'Format kumpulan tidak sah.',
        ]
    )]
    public string $group = '';
    #[Validate(
        rule: ['required', 'string', 'min:8', 'confirmed'],
        message: [
            'password.required'  => 'Ruangan kata laluan wajib diisi.',
            'password.min'       => 'Kata laluan mestilah sekurang-kurangnya 8 aksara.',
            'password.confirmed' => 'Pengesahan kata laluan tidak sepadan.',
        ]
    )]
    public string $password = '';
    public string $password_confirmation = '';

    public ?User $user;
    public string $user_id = '';
    public array $userroles = [];
    public array $userpermissions = [];
    public string $selectRole = '';
    public array $rolepermissions = [];

    public ?Role $role = null;
    public string $roleid = '';

    public function resetForm()
    {
        $this->reset(['user_id','name', 'email', 'group', 'password', 'password_confirmation', 'userroles', 'userpermissions', 'rolepermissions']);
        $this->resetValidation();
    }
    public function closeModal()
    {
        $this->activemodal = null;
        $this->resetForm();
        $this->dispatch('refresh-user');
    }
    #[On('loadcreateuserform')]
    public function loadcreateuserform(){
        $this->title = 'Tambah Pengguna';
        $this->activemodal = 'create-user';
        $this->resetForm();
    }
    public function createuser(){
        $validated =$this->validate();
        try{
            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'group' => $this->group ?? '000',
            ]);
            Toaster::success('Pengguna Dtiambah');
            $this->closeModal();
        }catch(\Throwable $e)
        {
            Toaster::error('Ralat dalam menambah pengguna:'.$e);
        }
    }
    #[On('loadedituserform')]
    public function loadedituserform($id){
        $this->title = 'Kemaskini Pengguna';
        $this->activemodal = 'update-user';
        $this->resetForm();
        $this->user = User::findOrFail($id);
        $this->user_id = $this->user->id;
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
        if(!$this->user_id){
            return;
        }
        $validated = $this->validate(
            [
                'userroles'       => 'nullable|array',
                'userpermissions' => 'nullable|array',
            ],
            [
                'userroles.array'       => 'Format peranan tidak sah.',
                'userpermissions.array' => 'Format kebenaran tidak sah.',
            ]
        );
        try{
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
            $user = User::findOrFail($this->user_id);
            $user->syncRoles($roleids);
            $user->syncPermissions($permissionids);
            $this->closeModal();
            Toaster::success('Pengguna berjaya dikemaskinin');
        }catch(\Throwable $e)
        {
            Toaster::error('Ralat dalam mengeaskini pengguna:'.$e);
        }
    }
    public function deleteuser()
    {
        $user = User::where('id',$this->user_id)->firstOrFail();
        if(!$user->isDeletable())
        {
            Toaster::error('Ralat: Pengguna memiliki rekod permohonan/peminjaman');
            return;
        }
        $user->delete();
        $this->user = null;
        Toaster::success('Pengguna telah berjaya dipadam');
        $this->closeModal();
    }
    #[On('loadeditroleform')]
    public function loadeditroleform()
    {
        $this->title = "Tetapan Kebenaran Peranan";
        $this->activemodal = 'update-role';
        $this->resetForm();
    }
    public function updatedSelectRole($value)
    {
        if (empty($value)) {
            $this->rolepermissions = [];
            return;
        }
        $role = Role::find($value);
        if ($role) {
            $this->rolepermissions = $role->permissions
                ->pluck('id')
                ->mapWithKeys(fn ($id) => [$id => true])
                ->toArray();
        }
        Toaster::success('Memuatkan tetapan kebenaran bagi peranan '.$role->name);
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
    <x-ui.content-modal title="{{ $title }}">
        @if($this->activemodal === 'create-user')
            @can('create:users')
                <form wire:submit="createuser">
                    <x-ui.grid min="1" max="3">
                        <x-ui.input type="text" wire:model="name" label="Nama" id="name" required></x-ui.input>
                        <x-ui.input type="email" wire:model="email" label="E-mel" id="email" required></x-ui.input>
                        <x-ui.select wire:model="group" label="Kumpulan" id="group" required>
                            <option value="" disabled selected>{{ __('Pilih Kumpulan Pengguna') }}</option>
                            @foreach(User::GROUPS as $key => $name)
                            <option value="{{ $key }}">{{ $name }}</option>
                            @endforeach
                        </x-ui.select>
                    </x-ui.grid>
                    <x-ui.grid min="1" max="3">
                        @error('name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                        @error('email')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                        @error('group')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </x-ui.grid>
                    <x-ui.grid min="1" max="2">
                        <x-ui.input type="password" wire:model="password" label="Kata Laluan" id="password" required></x-ui.input>
                        <x-ui.input type="password" wire:model="password_confirmation" label="Pengesahan Kata Laluan" id="password_confirmation" required></x-ui.input>
                    </x-ui.grid>
                    <x-ui.grid min="1" max="2">
                        @error('password')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                        @error('password_confirmation')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </x-ui.grid>
                    <x-ui.button type="submit" color="blue">
                        Tambah Pengguna
                    </x-ui.button>
                </form>
            @endcan
        @elseif($this->activemodal === 'update-user' && $user)
            @can('update:user-roles')
                <form wire:submit="updateuser">
                        <input
                            type="hidden"
                            wire:model="user_id"
                        >
                        <x-ui.user-list-item :user="$user"/>
                        {{--Roles--}}
                        <x-ui.title>Peranan</x-ui.title>
                        <x-ui.grid min="1" max="3">
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
                        </x-ui.grid>
                        {{--Permissions--}}
                        <x-ui.title>Kebenaran</x-ui.title>
                        <x-ui.grid min="1" max="3">
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
                        </x-ui.grid>
                        <x-ui.button type="submit" color="blue">
                            Kemaskini Pengguna
                        </x-ui.button>
                        @if($user->isDeletable())
                        <x-ui.button
                            type="button" color="red"
                            wire:click="deleteuser"
                            wire:confirm="Adakah anda pasti untuk memadam pengguna?"
                        >
                            Padam Pengguna
                        </x-ui.button>
                        @endif
                </form>
            @endcan
        @elseif($this->activemodal === 'update-role')
            @can('update:role-permissions')
                <x-ui.grid min="1" max="1">
                    <x-ui.select wire:model.live="selectRole">
                        <option value="">--Sila Pilih Peranan--</option>
                        @foreach(Role::all() as $role)
                            <option value="{{$role->id}}">{{strtoupper($role->name)}}</option>
                        @endforeach
                    </x-ui.select>
                </x-ui.grid>
                <form wire:submit="updaterole">
                    <x-ui.title>Kebenaran Peranan</x-ui.title>
                    <x-ui.grid min="1" max="3">
                    @foreach (Permission::all() as $permission)
                        <label class="flex items-center gap-2 rounded-lg border border-gray-200 p-3 hover:bg-gray-50 cursor-pointer">
                            <input
                                type="checkbox"
                                wire:model="rolepermissions.{{ $permission->id }}" 
                                @disabled(empty($selectRole))
                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                            >
                            <span>{{ $permission->name }}</span>
                        </label>
                    @endforeach
                    </x-ui.grid>
                    <x-ui.button type="submit" color="blue">
                        Kemaskini Kebenaran Peranan
                    </x-ui.button>
                </form>
            @endcan
        @endif
    </x-ui.content-modal>
    @endif
</div>
