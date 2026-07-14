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
                <x-form-modal id="form-modal-body" title="Borang"></x-form-modal>
                @hasrole('superadmin')
                Role: Superadmin
                @isset($users)
                <table class="table-auto w-full border-collapse border border-gray-600">
                    <tr>
                        <th>Nama</th>
                        <th>Peranan</th>
                        <th>Kebenaran</th>
                        <th>Tindakan</th>
                    </tr>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>
                                @foreach ($user->roles as $role)
                                    <span class="bg-blue-500 text-white text-xs font-semibold px-2 py-1 rounded">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                <form action="post">
                                </form>
                                @foreach ($user->permissions as $permission)
                                    <span class="bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded">{{ $permission->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                <button type="button" id="{{ $user->id }}" class="edit-user-role-permission"><x-feathericon-settings /></button>
                                <button type="button" id="{{ $user->id }}" class="edit-user-role-permission"><x-feathericon-trash class="text-red-500 fill-current" /></button>
                            </td>
                        </tr>
                    @endforeach
                </table>
                <script>
                    document.querySelectorAll('.edit-user-role-permission').forEach(button => {
                        button.addEventListener('click', function() {
                            const userId = this.id;
                            $('#dashboard-dialog').find('#dialog-content').html(userId);
                            $('#dashboard-dialog').show();
                            //alert('Edit user with ID: ' + userId);
                            //window.location.href = `/users/${userId}/edit`;
                        });
                    });
                </script>
                @endisset
                
                @endhasrole
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
