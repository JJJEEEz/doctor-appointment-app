<x-admin-layout
    title="Crear doctor"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Doctores', 'href' => route('admin.doctors.index')],
        ['name' => 'Crear'],
    ]"
>
    <form action="{{ route('admin.doctors.store') }}" method="POST">
        @csrf

        <x-wire-card class="p-4 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <p class="text-lg font-semibold text-gray-900">Nuevo doctor</p>
                    <p class="text-sm text-gray-600">Completa la informacion del usuario y los datos profesionales del doctor.</p>
                </div>

                <div class="flex space-x-3">
                    <x-wire-button href="{{ route('admin.doctors.index') }}" outline gray>
                        Volver
                    </x-wire-button>
                    <x-wire-button type="submit">
                        <i class="fa-solid fa-check mr-2"></i>
                        Guardar
                    </x-wire-button>
                </div>
            </div>
        </x-wire-card>

        <x-wire-card class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Datos del Usuario</h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <x-wire-input
                    label="Nombre completo"
                    name="name"
                    id="name"
                    placeholder="Ej: Juan Perez"
                    value="{{ old('name') }}"
                    required
                />
                @error('name')
                    <p class="text-sm text-red-600 -mt-4">{{ $message }}</p>
                @enderror

                <x-wire-input
                    label="Email"
                    name="email"
                    id="email"
                    type="email"
                    placeholder="ejemplo@correo.com"
                    value="{{ old('email') }}"
                    required
                />
                @error('email')
                    <p class="text-sm text-red-600 -mt-4">{{ $message }}</p>
                @enderror

                <x-wire-input
                    label="Numero de identificacion"
                    name="id_number"
                    id="id_number"
                    placeholder="Ej: 12345678A, DNI, Pasaporte"
                    value="{{ old('id_number') }}"
                    required
                />
                @error('id_number')
                    <p class="text-sm text-red-600 -mt-4">{{ $message }}</p>
                @enderror

                <x-wire-input
                    label="Telefono"
                    name="phone"
                    id="phone"
                    placeholder="Ej: 1234567890"
                    value="{{ old('phone') }}"
                />
                @error('phone')
                    <p class="text-sm text-red-600 -mt-4">{{ $message }}</p>
                @enderror

                <div class="lg:col-span-2">
                    <x-wire-textarea
                        label="Direccion"
                        name="address"
                        id="address"
                        placeholder="Calle, numero, ciudad, codigo postal..."
                        rows="3"
                    >{{ old('address') }}</x-wire-textarea>
                    @error('address')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <x-wire-input
                    label="Contrasena"
                    name="password"
                    id="password"
                    type="password"
                    placeholder="Minimo 8 caracteres"
                    required
                />
                @error('password')
                    <p class="text-sm text-red-600 -mt-4">{{ $message }}</p>
                @enderror

                <x-wire-input
                    label="Confirmar contrasena"
                    name="password_confirmation"
                    id="password_confirmation"
                    type="password"
                    placeholder="Repite la contrasena"
                    required
                />
            </div>

            <hr class="my-6">

            <h3 class="text-lg font-semibold text-gray-900 mb-4">Datos Profesionales</h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <x-wire-native-select
                    label="Especialidad"
                    name="speciality_id"
                    id="speciality_id"
                >
                    <option value="">Selecciona una especialidad</option>
                    @foreach($specialties as $specialty)
                        <option value="{{ $specialty->id }}" @selected(old('speciality_id') == $specialty->id)>
                            {{ $specialty->name }}
                        </option>
                    @endforeach
                </x-wire-native-select>
                @error('speciality_id')
                    <p class="text-sm text-red-600 -mt-4">{{ $message }}</p>
                @enderror

                <x-wire-input
                    label="Numero de licencia medica"
                    name="medical_license_number"
                    id="medical_license_number"
                    placeholder="Ej: 1234567"
                    value="{{ old('medical_license_number') }}"
                />
                @error('medical_license_number')
                    <p class="text-sm text-red-600 -mt-4">{{ $message }}</p>
                @enderror

                <div class="lg:col-span-2">
                    <x-wire-textarea
                        label="Biografia"
                        name="biography"
                        id="biography"
                        placeholder="Describe tu experiencia y formacion profesional..."
                        rows="4"
                    >{{ old('biography') }}</x-wire-textarea>
                    @error('biography')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </x-wire-card>
    </form>
</x-admin-layout>
