<div>
    <div class="mb-4">
        <x-wire-input
            wire:model.live.debounce.300ms="search"
            placeholder="Buscar por paciente, doctor o fecha"
        />
    </div>

    <div class="overflow-x-auto mt-4">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr>
                    <th class="px-4 py-2 border">ID</th>
                    <th class="px-4 py-2 border">Paciente</th>
                    <th class="px-4 py-2 border">Doctor</th>
                    <th class="px-4 py-2 border">Fecha</th>
                    <th class="px-4 py-2 border">Hora</th>
                    <th class="px-4 py-2 border">Hora fin</th>
                    <th class="px-4 py-2 border">Estado</th>
                    <th class="px-4 py-2 border">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($appointments as $appointment)
                    <tr>
                        <td class="px-4 py-2 border">{{ $appointment->id }}</td>
                        <td class="px-4 py-2 border">{{ $appointment->patient?->name ?? 'N/A' }}</td>
                        <td class="px-4 py-2 border">{{ $appointment->doctor?->user?->name ?? 'N/A' }}</td>
                        <td class="px-4 py-2 border">{{ $appointment->date?->format('d/m/Y') }}</td>
                        <td class="px-4 py-2 border">{{ substr((string) $appointment->start_time, 0, 5) }}</td>
                        <td class="px-4 py-2 border">{{ substr((string) $appointment->end_time, 0, 5) }}</td>
                        <td class="px-4 py-2 border">
                            @php
                                $statusClass = match ($appointment->status) {
                                    \App\Models\Appointment::STATUS_CANCELLED => 'bg-red-100 text-red-700',
                                    \App\Models\Appointment::STATUS_COMPLETED => 'bg-green-100 text-green-700',
                                    default => 'bg-blue-100 text-blue-700',
                                };
                            @endphp
                            <span class="px-2 py-1 text-xs rounded-full {{ $statusClass }}">
                                {{ $appointment->statusLabel() }}
                            </span>
                        </td>
                        <td class="px-4 py-2 border">
                            @include('admin.appointments.actions', ['appointment' => $appointment])
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-2 border text-center">No hay citas registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-2">
            {{ $appointments->links() }}
        </div>
    </div>
</div>
