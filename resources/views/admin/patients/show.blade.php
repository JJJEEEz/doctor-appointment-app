<x-admin-layout
    title="Ver paciente"
    :breadcrumbs="[
        [
            'name' => 'Dashboard',
            'href' => route('admin.dashboard')
        ],
        [
            'name' => 'Patients',
            'href' => route('admin.patients.index')
        ],
        [
            'name' => 'Ver',
            'href' => request()->url()
        ],
    ]"
>
    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-lg font-bold mb-4">Detalles del paciente</h2>
        
        <div class="mb-2">
            <span class="font-semibold">ID:</span> {{ $patient->id ?? 'N/A' }}
        </div>
        <div class="mb-2">
            <span class="font-semibold">Nombre:</span> {{ $patient->name ?? 'N/A' }}
        </div>
        <div class="mb-2">
            <span class="font-semibold">Email:</span> {{ $patient->email ?? 'N/A' }}
        </div>
        <div class="mb-2">
            <span class="font-semibold">Teléfono:</span> {{ $patient->phone ?? 'N/A' }}
        </div>
        <div class="mb-2">
            <span class="font-semibold">Dirección:</span> {{ $patient->address ?? 'N/A' }}
        </div>
        <div class="mb-2">
            <span class="font-semibold">Creado:</span> {{ $patient->created_at ?? 'N/A' }}
        </div>
        <div class="mb-2">
            <span class="font-semibold">Actualizado:</span> {{ $patient->updated_at ?? 'N/A' }}
        </div>

        <div class="mt-6">
            <a href="{{ route('admin.patients.edit', $patient) }}" class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">
                Editar
            </a>
            <a href="{{ route('admin.patients.index') }}" class="px-4 py-2 font-bold text-gray-700 bg-gray-300 rounded hover:bg-gray-400">
                Volver
            </a>
        </div>
    </div>
</x-admin-layout>
