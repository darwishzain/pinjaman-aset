<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Daftar Pengguna') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('users.store') }}" method="post">
                        @csrf
                        <x-input-label for="name" value="Nama Pengguna" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" placeholder="Nama Pengguna" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        <x-input-label for="email" value="Emel Pengguna" class="mt-4" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" placeholder="Emel Pengguna" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        <x-input-label for="role" value="Peranan Pengguna" class="mt-4" />
                        <select name="role" id="role" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-900 rounded-md shadow-sm">
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}" @if ($role->name == 'staff') selected @endif>
                                {{ $role->name }}
                            </option>
                        @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        <x-input-label for="password" value="Kata Laluan Pengguna" class="mt-4" />
                        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" placeholder="Kata Laluan Pengguna" required />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        <x-input-label for="password_confirmation" value="Sahkan Kata Laluan Pengguna" class="mt-4" />
                        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" placeholder="Sahkan Kata Laluan Pengguna" required />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        <x-primary-button class="mt-4">
                            {{ __('Daftar Pengguna') }}
                        </x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

{{--
$user->assignRole('writer');        // Adds user to the writer role
$user->removeRole('writer');        // Removes user from the writer role
$user->syncRoles(['admin', 'editor']); // Replaces all existing roles with these two
// Direct Permissions (giving a permission straight to a user without a role)
$user->givePermissionTo('edit articles');
$user->revokePermissionTo('edit articles');
// CHECK ACCESS
// Role Checks (Good for broad architectural blocks)
if ($user->hasRole('admin')) {
    // Show entire admin panel
}

// Permission Checks (Best practice for operational features)
if ($user->hasPermissionTo('edit articles')) {
    // Show text editor
}

// In a standard controller or route closure:
if ($request->user()->can('edit articles')) {
    // The HasRoles trait evaluates this correctly automatically
}

--}}