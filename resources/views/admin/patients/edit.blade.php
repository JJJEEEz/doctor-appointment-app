<x-admin-layout 
    title="Edit Patient: {{ $patient->name }}"
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
            'name' => $patient->name
        ]
    ]">

    {{-- Formulario para editar el paciente --}}
    <div class="p-4 bg-white rounded-lg shadow-md">
        <form action="{{ route('admin.patients.update', $patient) }}" method="POST">
            @csrf {{-- Directiva de seguridad --}}
            @method('PUT') {{-- Le dice a Laravel que es una actualización --}}
            
            <div class="mb-4">
                <label for="name" class="block mb-2 text-sm font-bold text-gray-700">Patient Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $patient->name) }}" class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block mb-2 text-sm font-bold text-gray-700">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $patient->email) }}" class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="phone" class="block mb-2 text-sm font-bold text-gray-700">Phone</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $patient->phone) }}" class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline @error('phone') border-red-500 @enderror">
                @error('phone')
                    <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="address" class="block mb-2 text-sm font-bold text-gray-700">Address</label>
                <textarea id="address" name="address" rows="3" class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline @error('address') border-red-500 @enderror">{{ old('address', $patient->address) }}</textarea>
                @error('address')
                    <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">
                    Update Patient
                </button>
                <a href="{{ route('admin.patients.index') }}" class="px-4 py-2 font-bold text-gray-700 bg-gray-300 rounded hover:bg-gray-400">
                    Cancel
                </a>
            </div>
        </form>
    </div>

</x-admin-layout>
