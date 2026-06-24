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
                        <input type="text" name="name" id="name">
                        <input type="email" name="email" id="email">
                        <select name="role" id="role">
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{  $role->name }}</option>
                        @endforeach
                        </select>
                        <input type="submit" value="Tambah">
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>