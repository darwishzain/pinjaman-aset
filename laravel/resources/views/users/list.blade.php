<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Senarai Pengguna') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <a href="{{ route('user.add') }}" class="inline-block bg-blue-500 text-white font-bold py-2 px-4 rounded">
                    Daftar Pengguna
                </a>
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="mb-4">Senarai Pengguna & Peranan</h2>
                    <table class="table table-bordered table-striped w-full">
                        <thead class="table-dark">
                            <tr>
                                <th>Nama</th>
                                <th>E-mel</th>
                                <th>Peranan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @foreach($user->roles as $role)
                                            <span class="badge bg-primary me-1">{{ $role->name }}</span>
                                        @endforeach
                                        
                                        @if($user->roles->isEmpty())
                                            <span class="text-muted">Tiada Peranan</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Render pagination links if you have more than 15 users -->
                    <div class="mt-3">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>