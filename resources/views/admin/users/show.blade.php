@php
    $title = 'Ver Usuario';
@endphp

@component('layouts.admin', ['title' => $title, 'breadcrumbs' => $breadcrumbs ?? []])
    <div class="bg-white p-4 rounded shadow">
        <h2 class="text-lg font-semibold mb-4">Usuario #{{ $user->id }}</h2>

        <div class="space-y-2">
            <div><strong>Nombre:</strong> {{ $user->name }}</div>
            <div><strong>Email:</strong> {{ $user->email }}</div>
            <div><strong>Roles:</strong> {{ $user->roles->pluck('name')->join(', ') }}</div>
        </div>

        <div class="mt-4">
            <a href="{{ route('admin.users.edit', $user) }}" class="px-3 py-1 bg-yellow-500 text-white rounded">Editar</a>
            <a href="{{ route('admin.users.index') }}" class="px-3 py-1 border rounded">Volver</a>
        </div>
    </div>
@endcomponent
