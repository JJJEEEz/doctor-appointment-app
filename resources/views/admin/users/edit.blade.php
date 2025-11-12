@php
    $title = 'Editar Usuario';
@endphp

@component('layouts.admin', ['title' => $title, 'breadcrumbs' => $breadcrumbs ?? []])
    <div class="bg-white p-4 rounded shadow">
        <h2 class="text-lg font-semibold mb-4">Editar Usuario</h2>

        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="block text-sm">Nombre</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border p-2" required>
            </div>

            <div class="mb-3">
                <label class="block text-sm">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border p-2" required>
            </div>

            <div class="mb-3">
                <label class="block text-sm">Nueva contraseña (opcional)</label>
                <input type="password" name="password" class="w-full border p-2">
            </div>

            <div class="mb-3">
                <label class="block text-sm">Confirmar contraseña</label>
                <input type="password" name="password_confirmation" class="w-full border p-2">
            </div>

            <div class="mb-3">
                <label class="block text-sm">Roles</label>
                <select name="roles[]" multiple class="w-full border p-2">
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button class="px-4 py-2 bg-blue-600 text-white rounded">Actualizar</button>
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 border rounded">Cancelar</a>
            </div>
        </form>
    </div>
@endcomponent
