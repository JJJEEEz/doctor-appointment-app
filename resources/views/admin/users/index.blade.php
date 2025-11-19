@php
    $title = 'Usuarios';
    $breadcrumbs = [
        ['name' => 'Inicio', 'href' => route('dashboard')],
        ['name' => 'Usuarios', 'href' => route('admin.users.index')],
    ];
@endphp

@component('layouts.admin', ['title' => $title, 'breadcrumbs' => $breadcrumbs])
    <div class="bg-white p-4 rounded shadow">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Usuarios</h2>
            <a href="{{ route('admin.users.create') }}" class="inline-block px-3 py-1 bg-blue-600 text-white rounded">Crear usuario</a>
        </div>

        <livewire:admin.tables.user-table />
    </div>
@endcomponent
