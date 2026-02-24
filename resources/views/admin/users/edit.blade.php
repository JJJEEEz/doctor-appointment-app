@php
    $title = 'Editar Usuario';
    $breadcrumbs = [
        ['name' => 'Inicio', 'href' => route('dashboard')],
        ['name' => 'Usuarios', 'href' => route('admin.users.index')],
        ['name' => 'Editar Usuario'],
    ];
@endphp

@component('layouts.admin', ['title' => $title, 'breadcrumbs' => $breadcrumbs])
    <div class="bg-white p-6 rounded-lg shadow" x-data="userForm()" x-init="setupWatcher()">
        <h2 class="text-2xl font-semibold mb-6 text-gray-800">Editar Usuario</h2>

        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nombre --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Completo *</label>
                    <input 
                        type="text"
                        name="name" 
                        placeholder="Ej: Juan Pérez"
                        value="{{ old('name', $user->name) }}"
                        pattern="^[a-záéíóúñ\s\-']+$"
                        maxlength="255"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                        required
                    />
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                    <input 
                        type="email"
                        name="email" 
                        placeholder="ejemplo@correo.com"
                        value="{{ old('email', $user->email) }}"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                        required
                    />
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Número de Identificación --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Número de Identificación *</label>
                    <input 
                        type="text"
                        name="id_number" 
                        placeholder="Ej: 12345678A, DNI, Pasaporte"
                        value="{{ old('id_number', $user->id_number) }}"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                        required
                    />
                    @error('id_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Teléfono --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                    <input 
                        type="tel"
                        name="phone" 
                        id="phone"
                        placeholder="Ej: 1234567890"
                        value="{{ old('phone', $user->phone) }}"
                        pattern="^[0-9]{10}$"
                        maxlength="10"
                        inputmode="numeric"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                    />
                    <p class="mt-1 text-xs text-gray-500">Exactamente 10 dígitos numéricos</p>
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <script>
                    document.getElementById('phone').addEventListener('input', function(e) {
                        // Permite solo números
                        this.value = this.value.replace(/[^0-9]/g, '');
                    });
                </script>
            </div>

            {{-- Dirección --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                <textarea 
                    name="address" 
                    placeholder="Calle, número, ciudad, código postal..."
                    rows="3"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                >{{ old('address', $user->address) }}</textarea>
                @error('address')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="border-t pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Cambiar Contraseña (Opcional)</h3>
                <p class="text-sm text-gray-600 mb-4">Deja estos campos vacíos si no deseas cambiar la contraseña</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nueva Contraseña --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nueva Contraseña</label>
                        <input 
                            type="password"
                            name="password" 
                            placeholder="Mínimo 8 caracteres"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                        />
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirmar Contraseña --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar Nueva Contraseña</label>
                        <input 
                            type="password"
                            name="password_confirmation" 
                            placeholder="Repite la contraseña"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                        />
                    </div>
                </div>
            </div>

            {{-- Rol --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rol del Usuario *</label>
                @php
                    $currentRole = $user->roles->first()?->name ?? '';
                @endphp
                <select 
                    name="role"
                    id="role"
                    x-model="role"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                    required
                >
                    <option value="">Seleccione un rol</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ old('role', $currentRole) == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
                @error('role')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tipo de sangre (solo Paciente) --}}
            <div x-show="role === 'Paciente'" class="space-y-6 transition-all duration-300">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Sangre *</label>
                    <select 
                        name="blood_type_id"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                    >
                        <option value="">Seleccione un tipo de sangre</option>
                        @foreach($bloodTypes as $bloodType)
                            <option value="{{ $bloodType->id }}" {{ old('blood_type_id', $user->patient?->blood_type_id) == $bloodType->id ? 'selected' : '' }}>{{ $bloodType->name }}</option>
                        @endforeach
                    </select>
                    @error('blood_type_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alergias conocidas</label>
                        <textarea 
                            name="known_allergies" 
                            placeholder="Ej: Penicilina, mariscos..."
                            rows="3"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                        >{{ old('known_allergies', $user->patient?->known_allergies) }}</textarea>
                        @error('known_allergies')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Enfermedades cronicas</label>
                        <textarea 
                            name="chronic_diseases" 
                            placeholder="Ej: Diabetes, hipertension..."
                            rows="3"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                        >{{ old('chronic_diseases', $user->patient?->chronic_diseases) }}</textarea>
                        @error('chronic_diseases')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Antecedentes quirurgicos</label>
                        <textarea 
                            name="surgical_history" 
                            placeholder="Describe tus cirugias previas..."
                            rows="3"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                        >{{ old('surgical_history', $user->patient?->surgical_history) }}</textarea>
                        @error('surgical_history')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Antecedentes familiares</label>
                        <textarea 
                            name="family_history" 
                            placeholder="Enfermedades en la familia..."
                            rows="3"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                        >{{ old('family_history', $user->patient?->family_history) }}</textarea>
                        @error('family_history')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones generales</label>
                    <textarea 
                        name="observations" 
                        placeholder="Otras observaciones medicas relevantes..."
                        rows="3"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                    >{{ old('observations', $user->patient?->observations) }}</textarea>
                    @error('observations')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <h3 class="text-lg font-medium text-gray-900 mt-6">Contacto de emergencia</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del contacto</label>
                        <input 
                            type="text"
                            name="emergency_contact_name" 
                            placeholder="Nombre completo"
                            value="{{ old('emergency_contact_name', $user->patient?->emergency_contact_name) }}"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                        />
                        @error('emergency_contact_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Telefono del contacto</label>
                        <input 
                            type="tel"
                            name="emergency_contact_phone" 
                            placeholder="1234567890"
                            value="{{ old('emergency_contact_phone', $user->patient?->emergency_contact_phone) }}"
                            maxlength="20"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                        />
                        @error('emergency_contact_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Relacion del contacto</label>
                    <input 
                        type="text"
                        name="emergency_contact_relationship" 
                        placeholder="Ej: Esposo/a, Padre/Madre, Hermano/a..."
                        value="{{ old('emergency_contact_relationship', $user->patient?->emergency_contact_relationship) }}"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                    />
                    @error('emergency_contact_relationship')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Especialidad, Cédula y Biografía (solo Doctor) --}}
            <div x-show="role === 'Doctor'" class="space-y-6 transition-all duration-300">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Especialidad *</label>
                        <select 
                            name="speciality_id"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="">Selecciona una especialidad</option>
                            @foreach($specialties as $specialty)
                                <option value="{{ $specialty->id }}" {{ old('speciality_id', $user->doctor?->speciality_id) == $specialty->id ? 'selected' : '' }}>
                                    {{ $specialty->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('speciality_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Número de cédula profesional *</label>
                        <input 
                            type="text"
                            name="medical_license_number" 
                            placeholder="Ej: 1234567"
                            value="{{ old('medical_license_number', $user->doctor?->medical_license_number) }}"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                        />
                        @error('medical_license_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Biografía</label>
                    <textarea 
                        name="biography" 
                        placeholder="Describe tu experiencia y formacion profesional..."
                        rows="4"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                    >{{ old('biography', $user->doctor?->biography) }}</textarea>
                    @error('biography')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Botones de acción --}}
            <div class="flex gap-3 pt-4 border-t">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                    <i class="fas fa-check mr-2"></i> Actualizar Usuario
                </button>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold rounded-lg transition">
                    <i class="fas fa-arrow-left mr-2"></i> Cancelar
                </a>
            </div>
        </form>
    </div>

    <script>
        function userForm() {
            return {
                role: '{{ old('role', $user->roles->first()?->name ?? '') }}',
                setupWatcher() {
                    this.$watch('role', (value) => {
                        console.log('Role changed to:', value);
                        
                        // Limpiar campos de Paciente
                        if (value !== 'Paciente') {
                            const bloodTypeSelect = document.querySelector('select[name=blood_type_id]');
                            if (bloodTypeSelect) bloodTypeSelect.value = '';
                        }
                        
                        // Limpiar campos de Doctor
                        if (value !== 'Doctor') {
                            const specialitySelect = document.querySelector('select[name=speciality_id]');
                            const licenseInput = document.querySelector('input[name=medical_license_number]');
                            const biographyTextarea = document.querySelector('textarea[name=biography]');
                            if (specialitySelect) specialitySelect.value = '';
                            if (licenseInput) licenseInput.value = '';
                            if (biographyTextarea) biographyTextarea.value = '';
                        }
                    });
                }
            }
        }
    </script>
@endcomponent
