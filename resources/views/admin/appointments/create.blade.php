<x-admin-layout
    title="Nueva cita"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Citas', 'href' => route('admin.appointments.index')],
        ['name' => 'Nuevo'],
    ]"
>
    <div class="space-y-6">
        <x-wire-card class="p-6">
            <h2 class="text-3xl font-semibold text-gray-800 mb-2">Buscar disponibilidad</h2>
            <p class="text-gray-600 mb-1">Encuentra el horario perfecto para tu cita.</p>
            <p class="text-sm text-gray-500 mb-4">Horario de atenci&oacute;n: 08:00 &ndash; 20:00 &nbsp;&middot;&nbsp; Duraci&oacute;n por cita: 30 minutos.</p>

            <form method="GET" action="{{ route('admin.appointments.create') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                    <input
                        type="date"
                        id="search_date"
                        name="appointment_date"
                        value="{{ $filters['appointment_date'] }}"
                        min="{{ $today }}"
                        class="w-full rounded-lg border-gray-300"
                        required
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hora de la cita</label>
                    @php
                        $timeSlots = [];
                        $slotCursor = \Carbon\Carbon::createFromFormat('H:i', '08:00');
                        $slotLimit  = \Carbon\Carbon::createFromFormat('H:i', '19:30');
                        while ($slotCursor->lte($slotLimit)) {
                            $timeSlots[] = $slotCursor->format('H:i');
                            $slotCursor->addMinutes(30);
                        }
                    @endphp
                    <select id="search_start_time" name="start_time" class="w-full rounded-lg border-gray-300" required>
                        <option value="">Selecciona una hora</option>
                        @foreach($timeSlots as $slot)
                            <option value="{{ $slot }}" @selected($filters['start_time'] === $slot)>{{ $slot }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Se reservan 30 minutos autom&aacute;ticamente.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Especialidad (opcional)</label>
                    <select name="speciality_id" class="w-full rounded-lg border-gray-300">
                        <option value="">Selecciona una especialidad</option>
                        @foreach($specialties as $specialty)
                            <option value="{{ $specialty->id }}" @selected((int) $filters['speciality_id'] === $specialty->id)>
                                {{ $specialty->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-3">
                    <x-wire-button type="submit">
                        Buscar disponibilidad
                    </x-wire-button>
                </div>
            </form>

            @if($searchPerformed)
                @if($availableDoctors->isNotEmpty())
                    <div class="mt-4 rounded-lg border border-green-200 bg-green-50 p-3 text-sm text-green-800">
                        Se encontraron {{ $availableDoctors->count() }} doctor(es) disponible(s) para el horario solicitado.
                    </div>
                @else
                    <div class="mt-4 rounded-lg border border-amber-200 bg-amber-50 p-3 text-sm text-amber-800 space-y-2">
                        <p>No hay disponibilidad exacta para {{ $filters['appointment_date'] }} a las {{ $filters['start_time'] }}.</p>
                        <div class="flex flex-col gap-1">
                            @if($nearbyAvailability['before'])
                                <p>
                                    Horario anterior m&aacute;s cercano: {{ $nearbyAvailability['before']['start'] }} - {{ $nearbyAvailability['before']['end'] }}
                                    ({{ $nearbyAvailability['before']['doctors_count'] }} doctor(es) disponible(s)).
                                </p>
                            @endif
                            @if($nearbyAvailability['after'])
                                <p>
                                    Horario posterior m&aacute;s cercano: {{ $nearbyAvailability['after']['start'] }} - {{ $nearbyAvailability['after']['end'] }}
                                    ({{ $nearbyAvailability['after']['doctors_count'] }} doctor(es) disponible(s)).
                                </p>
                            @endif
                            @if(!$nearbyAvailability['before'] && !$nearbyAvailability['after'])
                                <p>No se encontraron horarios cercanos disponibles para esa fecha.</p>
                            @endif
                        </div>
                    </div>
                @endif
            @endif
        </x-wire-card>

        @if($searchPerformed && $availableDoctors->isNotEmpty())
        <form action="{{ route('admin.appointments.store') }}" method="POST" class="space-y-6">
            @csrf

            <x-wire-card class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Paciente</label>
                        <select name="patient_id" class="w-full rounded-lg border-gray-300" required>
                            <option value="">Selecciona un paciente</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" @selected(old('patient_id') == $patient->id)>
                                    {{ $patient->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('patient_id')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Doctor disponible</label>
                        <select name="doctor_id" class="w-full rounded-lg border-gray-300" required>
                            <option value="">Selecciona un doctor</option>
                            @foreach($availableDoctors as $doctor)
                                <option value="{{ $doctor->id }}" @selected(old('doctor_id') == $doctor->id)>
                                    {{ $doctor->user?->name }} @if($doctor->speciality) - {{ $doctor->speciality->name }} @endif
                                </option>
                            @endforeach
                        </select>
                        @error('doctor_id')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    @php
                        $bookDate = old('appointment_date', $filters['appointment_date']);
                        $bookStart = old('start_time', $filters['start_time']);
                        $previewEnd = $bookStart
                            ? \Carbon\Carbon::createFromFormat('H:i', $bookStart)->addMinutes(30)->format('H:i')
                            : '--:--';
                    @endphp
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                        <input type="text" value="{{ $bookDate }}" class="w-full rounded-lg border-gray-300 bg-gray-50" readonly>
                        <input type="hidden" name="appointment_date" value="{{ $bookDate }}">
                        @error('appointment_date')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hora de la cita</label>
                        <input type="text" value="{{ $bookStart }}" class="w-full rounded-lg border-gray-300 bg-gray-50" readonly>
                        <input type="hidden" name="start_time" value="{{ $bookStart }}">
                        <p class="text-xs text-gray-500 mt-1">Termina a las <span id="book_end_preview">{{ $previewEnd }}</span>.</p>
                        @error('start_time')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                        <select name="status" class="w-full rounded-lg border-gray-300" required>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" @selected(old('status', 'Programado') === $status)>{{ $status }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <input type="hidden" name="speciality_id" value="{{ old('speciality_id', $filters['speciality_id']) }}">

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notas</label>
                        <textarea name="notes" rows="4" class="w-full rounded-lg border-gray-300">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </x-wire-card>

            <div class="flex justify-end gap-3">
                <x-wire-button href="{{ route('admin.appointments.index') }}" outline gray>
                    Volver
                </x-wire-button>
                <x-wire-button type="submit">
                    Guardar cita
                </x-wire-button>
            </div>
        </form>
        @elseif(!$searchPerformed)
            <x-wire-card class="p-6">
                <p class="text-sm text-gray-600">Completa y ejecuta la b&uacute;squeda de disponibilidad para habilitar el registro de la cita.</p>
            </x-wire-card>
        @endif
    </div>

    @push('modals')
        <script>
        (function () {
            const today = '{{ $today }}';
            const minTimeToday = '{{ $minTimeToday }}';

            function effectiveMin(dateValue) {
                return dateValue === today && minTimeToday > '08:00' ? minTimeToday : '08:00';
            }

            function refreshOptions(dateInput, selectEl) {
                if (!dateInput || !selectEl) return;
                const min = effectiveMin(dateInput.value);
                selectEl.querySelectorAll('option').forEach(function (opt) {
                    if (!opt.value) return;
                    const shouldDisable = opt.value < min;
                    opt.disabled = shouldDisable;
                    if (shouldDisable && opt.selected) {
                        opt.selected = false;
                        selectEl.value = '';
                    }
                });
            }

            document.addEventListener('DOMContentLoaded', function () {
                const pairs = [
                    ['search_date', 'search_start_time'],
                ];

                pairs.forEach(function ([dateId, selectId]) {
                    const dateInput = document.getElementById(dateId);
                    const selectEl  = document.getElementById(selectId);
                    if (!dateInput || !selectEl) return;

                    refreshOptions(dateInput, selectEl);
                    dateInput.addEventListener('change', function () {
                        refreshOptions(dateInput, selectEl);
                    });
                });

            });
        })();
        </script>
    @endpush
</x-admin-layout>
