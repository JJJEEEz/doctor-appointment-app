<x-admin-layout
    title="Create New Role"
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
            'name' => 'Create'
        ]
    ]"
>

    {{-- Aquí va el contenido del formulario --}}
    <div class="p-4 bg-white rounded-lg shadow-md">
        <form action="{{ route('admin.roles.store') }}" method="POST">
            @csrf {{-- Directiva de seguridad de Laravel --}}

            <div class="mb-4">
                <label for="name" class="block mb-2 text-sm font-bold text-gray-700">Role Name</label>
                <input type="text" id="name" name="name" class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline">
            </div>

            <button type="submit" class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">
                Save Role
            </button>
        </form>
    </div>

</x-admin-layout>