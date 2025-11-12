@php
    $title = 'Usuarios';
    $breadcrumbs = [
        ['name' => 'Usuarios', 'href' => route('admin.users.index')]
    ];
@endphp

@component('layouts.admin', ['title' => $title, 'breadcrumbs' => $breadcrumbs])
    <div class="bg-white p-4 rounded shadow">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Usuarios</h2>
            <a href="{{ route('admin.users.create') }}" class="inline-block px-3 py-1 bg-blue-600 text-white rounded">Crear usuario</a>
        </div>

        @if($users->count())
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr>
                        <th class="p-2">ID</th>
                        <th class="p-2">Nombre</th>
                        <th class="p-2">Email</th>
                        <th class="p-2">Roles</th>
                        <th class="p-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr class="border-t">
                            <td class="p-2">{{ $user->id }}</td>
                            <td class="p-2">{{ $user->name }}</td>
                            <td class="p-2">{{ $user->email }}</td>
                            <td class="p-2">{{ $user->roles->pluck('name')->join(', ') }}</td>
                            <td class="p-2">
                                @include('admin.users.actions', ['user' => $user])
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $users->links() }}</div>
        @else
            <p>No hay usuarios aún.</p>
        @endif
    </div>
@endcomponent
