@php
    $title = 'Crear Usuario';
    $breadcrumbs = [
        ['name' => 'Inicio', 'href' => route('dashboard')],
        ['name' => 'Usuarios', 'href' => route('admin.users.index')],
        ['name' => 'Crear Usuario'],
    ];
@endphp

@component('layouts.admin', ['title' => $title, 'breadcrumbs' => $breadcrumbs])
    <div class="bg-white p-4 rounded shadow">
        <h2 class="text-lg font-semibold mb-4">Crear Usuario</h2>

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf


            <div class="mb-3">
                <label class="block text-sm">Nombre</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full border p-2" required>
            </div>

            <div class="mb-3">
                <label class="block text-sm">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full border p-2" required>
            </div>

            <div class="mb-3">
                <label class="block text-sm">Número ID</label>
                <input type="text" name="id_number" value="{{ old('id_number') }}" class="w-full border p-2" required>
            </div>

            <div class="mb-3">
                <label class="block text-sm">Teléfono</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="w-full border p-2" required>
            </div>

            <div class="mb-3">
                <label class="block text-sm">Contraseña</label>
                <input type="password" name="password" class="w-full border p-2" required>
            </div>

            <div class="mb-3">
                <label class="block text-sm">Confirmar contraseña</label>
                <input type="password" name="password_confirmation" class="w-full border p-2" required>
            </div>

            <div class="mb-3">
                <label class="block text-sm">Roles</label>
                <select name="roles[]" multiple class="w-full border p-2">
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button class="px-4 py-2 bg-blue-600 text-white rounded">Guardar</button>
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 border rounded">Cancelar</a>
            </div>
        </form>
    </div>
@endcomponent