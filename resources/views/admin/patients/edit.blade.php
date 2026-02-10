<x-admin-layout 
    title="Editar paciente"
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
            'name' => 'Editar'
        ]
    ]">

    <style>
        [x-cloak] { display: none !important; }
    </style>

    {{-- Formulario para editar el paciente --}}
    <form
        action="{{ route('admin.patients.update', $patient) }}"
        method="POST"
        x-data="{
            tab: 'personal',
            tabs: ['personal', 'medical', 'general', 'emergency'],
            getPanelRef(tabName) {
                return this.$refs[tabName + 'Panel'];
            },
            attemptTab(next) {
                if (this.validateTab(this.tab)) {
                    this.tab = next;
                }
            },
            validateTab(tabName) {
                const panel = this.getPanelRef(tabName);
                if (!panel) return true;
                this.clearClientErrors(panel);
                const fields = Array.from(panel.querySelectorAll('input, select, textarea'));
                fields.forEach((field) => this.applyCustomValidity(field));
                const invalidFields = fields.filter((field) => !field.checkValidity());
                if (invalidFields.length) {
                    invalidFields.forEach((field) => this.setClientError(field, panel));
                    invalidFields[0].reportValidity();
                    this.showAlert('Hay campos invalidos en esta pestana.');
                    return false;
                }
                return true;
            },
            applyCustomValidity(field) {
                const message = field.dataset.msgRequired || field.dataset.msgPattern || field.dataset.msgMaxlength || field.dataset.msgMinlength;
                if (!message) return;
                const isValid = field.checkValidity();
                field.setCustomValidity(isValid ? '' : message);
            },
            clearClientErrors(panel) {
                const errors = panel.querySelectorAll('[data-client-error]');
                errors.forEach((el) => {
                    el.textContent = '';
                });
                const fields = panel.querySelectorAll('input, select, textarea');
                fields.forEach((field) => {
                    field.classList.remove('border-red-500');
                });
            },
            clearFieldError(field) {
                if (!field) return;
                field.setCustomValidity('');
                field.classList.remove('border-red-500');
                const panel = field.closest('[x-ref]');
                if (!panel) return;
                const errorEl = panel.querySelector('[data-client-error=' + field.id + ']');
                if (errorEl) {
                    errorEl.textContent = '';
                }
            },
            setClientError(field, panel) {
                const errorEl = panel.querySelector('[data-client-error=' + field.id + ']');
                if (errorEl) {
                    errorEl.textContent = field.validationMessage;
                }
                field.classList.add('border-red-500');
            },
            validateAll() {
                for (const tabName of this.tabs) {
                    if (!this.validateTab(tabName)) {
                        this.tab = tabName;
                        return false;
                    }
                }
                return true;
            },
            submitForm() {
                if (this.validateAll()) {
                    this.$refs.form.submit();
                }
            },
            showAlert(message) {
                if (window.Swal) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de validacion',
                        text: message,
                    });
                } else {
                    alert(message);
                }
            }
        }"
        x-ref="form"
        @submit.prevent="submitForm"
        @input="clearFieldError($event.target)"
    >
        @csrf {{-- Directiva de seguridad --}}
        @method('PUT') {{-- Le dice a Laravel que es una actualización --}}

        <x-wire-card class="p-4 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-center space-x-4">
                    <img
                        src="{{ optional($patient->user)->profile_photo_url }}"
                        alt="{{ optional($patient->user)->name ?? $patient->name }}"
                        class="w-16 h-16 rounded-full object-cover object-center"
                    />
                    <div>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ optional($patient->user)->name ?? $patient->name }}
                        </p>
                    </div>
                </div>

                <div class="flex space-x-3 mt-6 mb-0 lg:mt-0">
                    <x-wire-button href="{{ route('admin.patients.index') }}" outline gray>
                        Volver
                    </x-wire-button>
                    <x-wire-button type="submit">
                        <i class="fa-solid fa-check mr-2"></i>
                        Guardar cambios
                    </x-wire-button>
                </div>
            </div>
        </x-wire-card>

        <x-wire-card class="p-0">
            <div class="border-b border-gray-200">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500">
                    <li class="me-2">
                        <button
                            type="button"
                            class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg"
                            :class="tab === 'personal' ? 'text-blue-600 border-blue-600' : 'border-transparent hover:text-blue-600 hover:border-gray-300'"
                            @click="attemptTab('personal')"
                        >
                            <i class="fa-solid fa-user me-2"></i>
                            Datos personales
                        </button>
                    </li>
                    <li class="me-2">
                        <button
                            type="button"
                            class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg"
                            :class="tab === 'medical' ? 'text-blue-600 border-blue-600' : 'border-transparent hover:text-blue-600 hover:border-gray-300'"
                            @click="attemptTab('medical')"
                        >
                            <i class="fa-solid fa-file-lines me-2"></i>
                            Antecedentes
                        </button>
                    </li>
                    <li class="me-2">
                        <button
                            type="button"
                            class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg"
                            :class="tab === 'general' ? 'text-blue-600 border-blue-600' : 'border-transparent hover:text-blue-600 hover:border-gray-300'"
                            @click="attemptTab('general')"
                        >
                            <i class="fa-solid fa-circle-info me-2"></i>
                            Informacion general
                        </button>
                    </li>
                    <li class="me-2">
                        <button
                            type="button"
                            class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg"
                            :class="tab === 'emergency' ? 'text-blue-600 border-blue-600' : 'border-transparent hover:text-blue-600 hover:border-gray-300'"
                            @click="attemptTab('emergency')"
                        >
                            <i class="fa-solid fa-heart me-2"></i>
                            Contacto de emergencia
                        </button>
                    </li>
                </ul>
            </div>

            <div class="p-6">
                <div x-show="tab === 'personal'" x-cloak x-ref="personalPanel">
                    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4 p-4 bg-blue-50 border-l-4 border-blue-500 rounded-lg shadow-sm">
                        <div class="flex items-start gap-3">
                            <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-full flex-shrink-0">
                                <i class="fa-solid fa-user-gear text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-blue-800">Edicion de cuenta de usuario</p>
                                <p class="text-sm text-blue-700 mt-1">
                                    La informacion de acceso (Nombre, Email y Contrasena) debe gestionarse desde la cuenta de usuario asociada.
                                </p>
                            </div>
                        </div>
                        @if($patient->user)
                            <x-wire-button href="{{ route('admin.users.edit', $patient->user) }}" class="whitespace-nowrap">
                                Editar Usuario
                                <i class="fa-solid fa-arrow-up-right-from-square ml-2"></i>
                            </x-wire-button>
                        @endif
                    </div>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                        <div>
                            <span class="text-gray-500">Telefono:</span>
                            <span class="ml-2 text-gray-900">{{ $patient->phone ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Email:</span>
                            <span class="ml-2 text-gray-900">{{ $patient->email ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Direccion:</span>
                            <span class="ml-2 text-gray-900">{{ $patient->address ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <div x-show="tab === 'medical'" x-cloak x-ref="medicalPanel">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label for="known_allergies" class="block mb-2 text-sm font-bold text-gray-700">Alergias conocidas</label>
                            <textarea id="known_allergies" name="known_allergies" rows="3" maxlength="255" data-msg-maxlength="Maximo 255 caracteres." class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline">{{ old('known_allergies', $patient->known_allergies ?? '') }}</textarea>
                            <p class="mt-1 text-xs italic text-red-500" data-client-error="known_allergies"></p>
                            @error('known_allergies')
                                <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="chronic_diseases" class="block mb-2 text-sm font-bold text-gray-700">Enfermedades cronicas</label>
                            <textarea id="chronic_diseases" name="chronic_diseases" rows="3" maxlength="255" data-msg-maxlength="Maximo 255 caracteres." class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline">{{ old('chronic_diseases', $patient->chronic_diseases ?? '') }}</textarea>
                            <p class="mt-1 text-xs italic text-red-500" data-client-error="chronic_diseases"></p>
                            @error('chronic_diseases')
                                <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="surgical_history" class="block mb-2 text-sm font-bold text-gray-700">Antecedentes quirurgicos</label>
                            <textarea id="surgical_history" name="surgical_history" rows="3" maxlength="255" data-msg-maxlength="Maximo 255 caracteres." class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline">{{ old('surgical_history', $patient->surgical_history ?? '') }}</textarea>
                            <p class="mt-1 text-xs italic text-red-500" data-client-error="surgical_history"></p>
                            @error('surgical_history')
                                <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="family_history" class="block mb-2 text-sm font-bold text-gray-700">Antecedentes familiares</label>
                            <textarea id="family_history" name="family_history" rows="3" maxlength="255" data-msg-maxlength="Maximo 255 caracteres." class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline">{{ old('family_history', $patient->family_history ?? '') }}</textarea>
                            <p class="mt-1 text-xs italic text-red-500" data-client-error="family_history"></p>
                            @error('family_history')
                                <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div x-show="tab === 'general'" x-cloak x-ref="generalPanel">
                    <div class="mb-6">
                        <label for="blood_type_id" class="block mb-2 text-sm font-bold text-gray-700">Tipo de sangre</label>
                        <select id="blood_type_id" name="blood_type_id" class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline">
                            <option value="">Seleccione un tipo</option>
                            @foreach($bloodTypes as $bloodType)
                                <option value="{{ $bloodType->id }}" @selected(old('blood_type_id', $patient->blood_type_id) == $bloodType->id)>
                                    {{ $bloodType->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs italic text-red-500" data-client-error="blood_type_id"></p>
                        @error('blood_type_id')
                            <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="observations" class="block mb-2 text-sm font-bold text-gray-700">Observaciones</label>
                        <textarea id="observations" name="observations" rows="3" maxlength="255" data-msg-maxlength="Maximo 255 caracteres." class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline">{{ old('observations', $patient->observations ?? '') }}</textarea>
                        <p class="mt-1 text-xs italic text-red-500" data-client-error="observations"></p>
                        @error('observations')
                            <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div x-show="tab === 'emergency'" x-cloak x-ref="emergencyPanel">
                    <div class="space-y-4">
                        <div>
                            <label for="emergency_contact_name" class="block mb-2 text-sm font-bold text-gray-700">Nombre de contacto</label>
                            <input type="text" id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name', $patient->emergency_contact_name ?? '') }}" maxlength="255" data-msg-maxlength="Maximo 255 caracteres." class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline">
                            <p class="mt-1 text-xs italic text-red-500" data-client-error="emergency_contact_name"></p>
                            @error('emergency_contact_name')
                                <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="emergency_contact_phone" class="block mb-2 text-sm font-bold text-gray-700">Telefono de contacto</label>
                            <input type="tel" id="emergency_contact_phone" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone ?? '') }}" minlength="10" maxlength="10" inputmode="numeric" pattern="[0-9]{10}" data-msg-pattern="El telefono debe tener exactamente 10 digitos." data-msg-minlength="El telefono debe tener exactamente 10 digitos." data-msg-maxlength="El telefono debe tener exactamente 10 digitos." oninput="this.value = this.value.replace(/\D/g, '').slice(0, 10)" class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline">
                            <p class="mt-1 text-xs italic text-red-500" data-client-error="emergency_contact_phone"></p>
                            @error('emergency_contact_phone')
                                <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="emergency_contact_relationship" class="block mb-2 text-sm font-bold text-gray-700">Relacion con el contacto</label>
                            <input type="text" id="emergency_contact_relationship" name="emergency_contact_relationship" value="{{ old('emergency_contact_relationship', $patient->emergency_contact_relationship ?? '') }}" maxlength="255" data-msg-maxlength="Maximo 255 caracteres." class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline">
                            <p class="mt-1 text-xs italic text-red-500" data-client-error="emergency_contact_relationship"></p>
                            @error('emergency_contact_relationship')
                                <p class="mt-1 text-xs italic text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </x-wire-card>
    </form>

</x-admin-layout>
