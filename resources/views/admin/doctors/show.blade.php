<x-admin-layout
    title="Ver doctor"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Doctores', 'href' => route('admin.doctors.index')],
        ['name' => 'Ver'],
    ]"
>
    <div class="max-w-2xl mx-auto">
        <x-wire-card class="p-6">
            <div class="flex items-center space-x-4 mb-6 pb-6 border-b">
                <img
                    src="{{ optional($doctor->user)->profile_photo_url }}"
                    alt="{{ optional($doctor->user)->name ?? 'Doctor' }}"
                    class="w-20 h-20 rounded-full object-cover object-center"
                />
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">
                        {{ optional($doctor->user)->name ?? 'N/A' }}
                    </h2>
                    <p class="text-sm text-gray-600">
                        {{ $doctor->speciality?->name ?? 'N/A' }}
                    </p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <span class="text-sm font-semibold text-gray-700">Nombre:</span>
                    <p class="text-gray-900">{{ optional($doctor->user)->name ?? 'N/A' }}</p>
                </div>

                <div>
                    <span class="text-sm font-semibold text-gray-700">Correo:</span>
                    <p class="text-gray-900">{{ optional($doctor->user)->email ?? 'N/A' }}</p>
                </div>

                <div>
                    <span class="text-sm font-semibold text-gray-700">Teléfono:</span>
                    <p class="text-gray-900">{{ optional($doctor->user)->phone ?? 'N/A' }}</p>
                </div>

                <div>
                    <span class="text-sm font-semibold text-gray-700">Especialidad:</span>
                    <p class="text-gray-900">{{ $doctor->speciality?->name ?? 'N/A' }}</p>
                </div>

                <div>
                    <span class="text-sm font-semibold text-gray-700">Cédula Profesional:</span>
                    <p class="text-gray-900">{{ $doctor->medical_license_number ?: 'N/A' }}</p>
                </div>

                <div>
                    <span class="text-sm font-semibold text-gray-700">Biografía:</span>
                    <p class="text-gray-900 whitespace-pre-line">{{ $doctor->biography ?: 'N/A' }}</p>
                </div>

                <div class="pt-4 border-t">
                    <span class="text-sm font-semibold text-gray-700">Creado:</span>
                    <p class="text-gray-900">{{ $doctor->created_at->format('d/m/Y H:i') }}</p>
                </div>

                <div>
                    <span class="text-sm font-semibold text-gray-700">Actualizado:</span>
                    <p class="text-gray-900">{{ $doctor->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            <div class="flex gap-3 mt-6 pt-6 border-t">
                <x-wire-button href="{{ route('admin.doctors.edit', $doctor) }}">
                    <i class="fas fa-edit mr-2"></i>
                    Editar
                </x-wire-button>
                <x-wire-button href="{{ route('admin.doctors.index') }}" outline gray>
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </x-wire-button>
            </div>
        </x-wire-card>
    </div>
</x-admin-layout>
