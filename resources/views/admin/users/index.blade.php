@php
    $title = 'Usuarios';
    $breadcrumbs = [
        ['name' => 'Inicio', 'href' => route('dashboard')],
        ['name' => 'Usuarios', 'href' => route('admin.users.index')],
    ];
@endphp

@component('layouts.admin', ['title' => $title, 'breadcrumbs' => $breadcrumbs])
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Gestión de Usuarios</h2>
            <x-button 
                href="{{ route('admin.users.create') }}" 
                primary 
                icon="plus"
                label="Crear Usuario"
            />
        </div>

        <livewire:admin.tables.user-table />
    </div>
@endcomponent
