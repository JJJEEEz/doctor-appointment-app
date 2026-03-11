<x-admin-layout
    title="Ver cita"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Citas', 'href' => route('admin.appointments.index')],
        ['name' => 'Ver'],
    ]"
>
    <div class="max-w-3xl mx-auto">
        <x-wire-card class="p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Detalle de la cita #{{ $appointment->id }}</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <span class="text-sm font-semibold text-gray-700">Paciente:</span>
                    <p class="text-gray-900">{{ $appointment->patient?->name ?? 'N/A' }}</p>
                </div>

                <div>
                    <span class="text-sm font-semibold text-gray-700">Doctor:</span>
                    <p class="text-gray-900">{{ $appointment->doctor?->user?->name ?? 'N/A' }}</p>
                </div>

                <div>
                    <span class="text-sm font-semibold text-gray-700">Especialidad:</span>
                    <p class="text-gray-900">{{ $appointment->doctor?->speciality?->name ?? 'N/A' }}</p>
                </div>

                <div>
                    <span class="text-sm font-semibold text-gray-700">Estado:</span>
                    <p class="text-gray-900">{{ $appointment->statusLabel() }}</p>
                </div>

                <div>
                    <span class="text-sm font-semibold text-gray-700">Fecha:</span>
                    <p class="text-gray-900">{{ $appointment->date?->format('d/m/Y') }}</p>
                </div>

                <div>
                    <span class="text-sm font-semibold text-gray-700">Horario:</span>
                    <p class="text-gray-900">{{ substr((string) $appointment->start_time, 0, 5) }} - {{ substr((string) $appointment->end_time, 0, 5) }}</p>
                </div>

                <div class="md:col-span-2">
                    <span class="text-sm font-semibold text-gray-700">Motivo:</span>
                    <p class="text-gray-900 whitespace-pre-line">{{ $appointment->reason ?: 'Sin motivo registrado.' }}</p>
                </div>
            </div>

            <div class="flex gap-3 mt-6 pt-6 border-t">
                <x-wire-button href="{{ route('admin.appointments.edit', $appointment) }}">
                    <i class="fas fa-edit mr-2"></i>
                    Editar
                </x-wire-button>
                <x-wire-button href="{{ route('admin.appointments.index') }}" outline gray>
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </x-wire-button>
            </div>
        </x-wire-card>
    </div>
</x-admin-layout>
