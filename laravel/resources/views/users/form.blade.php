<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Pengguna') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="" method="post">
                        <input type="text" name="name" id="name" placeholder="Nama Pengguna">
                        <input type="email" name="email" id="email" placeholder="Emel Pengguna">
                        <select name="role" id="role">
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{  $role->name }}</option>
                        @endforeach
                        </select>
                        <input type="password" name="password" id="password" placeholder="Kata Laluan">
                        <input type="submit" value="Tambah">
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