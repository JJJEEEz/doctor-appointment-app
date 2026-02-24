<x-admin-layout
    title="Editar doctor"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Doctores', 'href' => route('admin.doctors.index')],
        ['name' => 'Editar'],
    ]"
>
    <form action="{{ route('admin.doctors.update', $doctor) }}" method="POST">
        @csrf
        @method('PUT')

        <x-wire-card class="p-4 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <img
                        src="{{ optional($doctor->user)->profile_photo_url }}"
                        alt="{{ optional($doctor->user)->name ?? 'Doctor' }}"
                        class="w-16 h-16 rounded-full object-cover object-center"
                    />
                    <div>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ optional($doctor->user)->name ?? 'Doctor' }}
                        </p>
                        <p class="text-sm text-gray-600">
                            Licencia: {{ $doctor->medical_license_number ?: 'N/A' }}
                        </p>
                        <p class="text-sm text-gray-600">
                            Biografia: {{ $doctor->biography ? \Illuminate\Support\Str::limit($doctor->biography, 80) : 'N/A' }}
                        </p>
                    </div>
                </div>

                <div class="flex space-x-3">
                    <x-wire-button href="{{ route('admin.doctors.index') }}" outline gray>
                        Volver
                    </x-wire-button>
                    <x-wire-button type="submit">
                        <i class="fa-solid fa-check mr-2"></i>
                        Guardar cambios
                    </x-wire-button>
                </div>
            </div>
        </x-wire-card>

        <x-wire-card class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <x-wire-native-select
                    label="Especialidad"
                    name="speciality_id"
                    id="speciality_id"
                >
                    <option value="">Selecciona una especialidad</option>
                    @foreach($specialties as $specialty)
                        <option value="{{ $specialty->id }}" @selected(old('speciality_id', $doctor->speciality_id) == $specialty->id)>
                            {{ $specialty->name }}
                        </option>
                    @endforeach
                </x-wire-native-select>

                <x-wire-input
                    label="Numero de licencia medica"
                    name="medical_license_number"
                    id="medical_license_number"
                    value="{{ old('medical_license_number', $doctor->medical_license_number) }}"
                />

                <div class="lg:col-span-2">
                    <x-wire-textarea
                        label="Biografia"
                        name="biography"
                        id="biography"
                        rows="4"
                    >{{ old('biography', $doctor->biography) }}</x-wire-textarea>
                </div>
            </div>
        </x-wire-card>
    </form>
</x-admin-layout>
