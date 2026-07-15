<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Utama') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-4">Selamat Datang ke Papan Pemuka, {{ auth()->user()->name }} ({{ auth()->user()->roles->first()->name }})</h1>
                <livewire:user.user-form />
                <livewire:user.list />
                @hasrole('admin')
                Role: Admin
                @endhasrole
                @hasrole('manager')
                Role: Manager
                @endhasrole
                @hasrole('staff')
                Role: Staff
                @endhasrole
                @hasrole('guest')
                Role: Guest
                @endhasrole
            </div>
        </div>
    </div>

</x-app-layout>
