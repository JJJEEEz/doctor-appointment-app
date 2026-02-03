<x-admin-layout
    title="Create New Patient"
    :breadcrumbs="[
        [
            'name' => 'Dashboard',
            'href'  => route('admin.dashboard')
        ],
        [
            'name' => 'Patients',
            'href'  => route('admin.patients.index')
        ],
        [
            'name' => 'Create'
        ]
    ]"
>

    {{-- Aquí va el contenido del formulario --}}
    <div class="p-4 bg-white rounded-lg shadow-md">
        <form action="{{ route('admin.patients.store') }}" method="POST">
            @csrf {{-- Directiva de seguridad de Laravel --}}

            <div class="mb-4">
                <label for="name" class="block mb-2 text-sm font-bold text-gray-700">Patient Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block mb-2 text-sm font-bold text-gray-700">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="phone" class="block mb-2 text-sm font-bold text-gray-700">Phone</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline @error('phone') border-red-500 @enderror">
                @error('phone')
                    <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="address" class="block mb-2 text-sm font-bold text-gray-700">Address</label>
                <textarea id="address" name="address" rows="3" class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                @error('address')
                    <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">
                    Save Patient
                </button>
                <a href="{{ route('admin.patients.index') }}" class="px-4 py-2 font-bold text-gray-700 bg-gray-300 rounded hover:bg-gray-400">
                    Cancel
                </a>
            </div>
        </form>
    </div>

</x-admin-layout>
