<x-admin-layout
    title="Editar cita"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Citas', 'href' => route('admin.appointments.index')],
        ['name' => 'Editar'],
    ]"
>
    <form action="{{ route('admin.appointments.update', $appointment) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <x-wire-card class="p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Editar cita medica</h2>

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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Doctor</label>
                    <select name="doctor_id" class="w-full rounded-lg border-gray-300" required>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" @selected(old('doctor_id', $appointment->doctor_id) == $doctor->id)>
                                {{ $doctor->user?->name ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                    @error('doctor_id')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                    <input
                        type="date"
                        name="date"
                        value="{{ old('date', $appointment->date?->format('Y-m-d')) }}"
                        min="{{ $today }}"
                        class="w-full rounded-lg border-gray-300"
                        required
                    >
                    @error('date')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select name="status" class="w-full rounded-lg border-gray-300" required>
                        @foreach($statusOptions as $value => $label)
                            <option value="{{ $value }}" @selected((int) old('status', $appointment->status) === $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hora de inicio</label>
                    <input type="time" name="start_time" value="{{ old('start_time', substr((string) $appointment->start_time, 0, 5)) }}" class="w-full rounded-lg border-gray-300" required>
                    @error('start_time')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hora de fin</label>
                    <input type="time" name="end_time" value="{{ old('end_time', substr((string) $appointment->end_time, 0, 5)) }}" class="w-full rounded-lg border-gray-300" required>
                    @error('end_time')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Motivo</label>
                    <textarea name="reason" rows="4" class="w-full rounded-lg border-gray-300">{{ old('reason', $appointment->reason) }}</textarea>
                    @error('reason')
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
</x-admin-layout>
