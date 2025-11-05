<x-admin-layout 
    title="Edit Role: {{ $role->name }}"
    :breadcrumbs="[
        [
            'name' => 'Dashboard',
            'href'  => route('admin.dashboard')
        ],
        [
            'name' => 'Roles',
            'href'  => route('admin.roles.index')
        ],
        [
            'name' => $role->name
        ]
    ]">

    {{-- Formulario para editar el rol --}}
    <div class="p-4 bg-white rounded-lg shadow-md">
        <form action="{{ route('admin.roles.update', $role) }}" method="POST">
            @csrf {{-- Directiva de seguridad --}}
            @method('PUT') {{-- Le dice a Laravel que es una actualización --}}
            
            <div class="mb-4">
                <label for="name" class="block mb-2 text-sm font-bold text-gray-700">Role Name</label>
                <input type="text" id="name" name="name" value="{{ $role->name }}" class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline">
            </div>

            <button type="submit" class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">
                Update Role
            </button>
        </form>
    </div>

</x-admin-layout>