<x-admin-layout
    title="Ver rol | LOLA "
    :breadcrumbs="[
        [
            'name' => 'Dashboard',
            'href' => url('admin')
        ],
        [
            'name' => 'Roles',
            'href' => url('admin/roles')
        ],
        [
            'name' => 'Ver',
            'href' => request()->url()
        ],
    ]"
>
    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-lg font-bold mb-4">Detalles del rol</h2>
        <!-- Aquí se mostrarán los datos del rol -->
        <div class="mb-2">
            <span class="font-semibold">ID:</span> {{ $role->id ?? 'N/A' }}
        </div>
        <div class="mb-2">
            <span class="font-semibold">Nombre:</span> {{ $role->name ?? 'N/A' }}
        </div>
        <div class="mb-2">
            <span class="font-semibold">Guard:</span> {{ $role->guard_name ?? 'N/A' }}
        </div>
        <div class="mb-2">
            <span class="font-semibold">Creado:</span> {{ $role->created_at ?? 'N/A' }}
        </div>
    </div>
</x-admin-layout>
