<x-admin-layout
    title="Editar cita"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Citas', 'href' => route('admin.appointments.index')],
        ['name' => 'Editar'],
    ]"
>
    <div class="space-y-6">
        <x-wire-card class="p-6">
            <h2 class="text-3xl font-semibold text-gray-800 mb-2">Buscar disponibilidad</h2>
            <p class="text-gray-600 mb-1">Ajusta fecha y horario para recalcular doctores disponibles.</p>
            <p class="text-sm text-gray-500 mb-4">Horario de atenci&oacute;n: 08:00 &ndash; 20:00 &nbsp;&middot;&nbsp; Duraci&oacute;n por cita: 30 minutos.</p>

            <form method="GET" action="{{ route('admin.appointments.edit', $appointment) }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                    <input type="date" id="search_date" name="appointment_date" value="{{ $filters['appointment_date'] }}" min="{{ $today }}" class="w-full rounded-lg border-gray-300" required>
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
        </x-wire-card>

        <form action="{{ route('admin.appointments.update', $appointment) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <x-wire-card class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Paciente</label>
                        <select name="patient_id" class="w-full rounded-lg border-gray-300" required>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" @selected(old('patient_id', $appointment->patient_id) == $patient->id)>
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
                            @foreach($availableDoctors as $doctor)
                                <option value="{{ $doctor->id }}" @selected(old('doctor_id', $appointment->doctor_id) == $doctor->id)>
                                    {{ $doctor->user?->name }} @if($doctor->speciality) - {{ $doctor->speciality->name }} @endif
                                </option>
                            @endforeach
                        </select>
                        @error('doctor_id')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                        <input type="date" id="book_date" name="appointment_date" value="{{ old('appointment_date', $filters['appointment_date']) }}" min="{{ $today }}" class="w-full rounded-lg border-gray-300" required>
                        @error('appointment_date')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hora de la cita</label>
                        <select id="book_start_time" name="start_time" class="w-full rounded-lg border-gray-300" required>
                            <option value="">Selecciona una hora</option>
                            @foreach($timeSlots as $slot)
                                <option value="{{ $slot }}" @selected(old('start_time', $filters['start_time']) === $slot)>{{ $slot }}</option>
                            @endforeach
                        </select>
                        @php $previewEnd = old('start_time', $filters['start_time']) ? \Carbon\Carbon::createFromFormat('H:i', old('start_time', $filters['start_time']))->addMinutes(30)->format('H:i') : '--:--'; @endphp
                        <p class="text-xs text-gray-500 mt-1">Termina a las <span id="book_end_preview">{{ $previewEnd }}</span>.</p>
                        @error('start_time')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                        <select name="status" class="w-full rounded-lg border-gray-300" required>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" @selected(old('status', $appointment->status) === $status)>{{ $status }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <input type="hidden" name="speciality_id" value="{{ old('speciality_id', $filters['speciality_id']) }}">

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notas</label>
                        <textarea name="notes" rows="4" class="w-full rounded-lg border-gray-300">{{ old('notes', $appointment->notes) }}</textarea>
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
                    Guardar cambios
                </x-wire-button>
            </div>
        </form>
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
                    ['book_date',   'book_start_time'],
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

                const bookSelect = document.getElementById('book_start_time');
                const endPreview = document.getElementById('book_end_preview');
                if (bookSelect && endPreview) {
                    bookSelect.addEventListener('change', function () {
                        if (!this.value) { endPreview.textContent = '--:--'; return; }
                        const [h, m] = this.value.split(':').map(Number);
                        const total  = h * 60 + m + 30;
                        endPreview.textContent = String(Math.floor(total / 60)).padStart(2, '0') + ':' + String(total % 60).padStart(2, '0');
                    });
                }
            });
        })();
        </script>
    @endpush
</x-admin-layout>
