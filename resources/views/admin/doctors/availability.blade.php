<x-admin-layout
    title="Disponibilidad del doctor"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Doctores', 'href' => route('admin.doctors.index')],
        ['name' => 'Disponibilidad'],
    ]"
>
    <div class="space-y-6">
        <x-wire-card class="p-6">
            <div class="flex items-center justify-between mb-2">
                <h2 class="text-3xl font-semibold text-gray-800">Gestor de horarios</h2>
                <x-wire-button href="{{ route('admin.doctors.show', $doctor) }}" outline gray>
                    Volver
                </x-wire-button>
            </div>
            <p class="text-gray-600">Doctor: <span class="font-semibold">{{ $doctor->user?->name ?? 'N/A' }}</span></p>
            <p class="text-gray-600">Marca los bloques de 15 minutos en los que atiende el doctor.</p>
        </x-wire-card>

        <form action="{{ route('admin.doctors.availability.update', $doctor) }}" method="POST">
            @csrf
            @method('PUT')

            <x-wire-card class="p-6 overflow-x-auto">
                <table class="min-w-full border border-gray-200 rounded-lg">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border">DIA/HORA</th>
                            @foreach($weekdays as $day => $dayLabel)
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border">{{ strtoupper($dayLabel) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hourGroups as $hourGroup)
                            <tr>
                                <td class="px-4 py-4 border align-top">
                                    <span class="text-2xl font-semibold text-gray-700">{{ $hourGroup['label'] }}</span>
                                </td>

                                @foreach($weekdays as $day => $dayLabel)
                                    <td class="px-4 py-3 border align-top">
                                        <label class="inline-flex items-center gap-2 mb-3 text-sm font-semibold text-gray-700">
                                            <input
                                                type="checkbox"
                                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 js-toggle-hour"
                                                data-day="{{ $day }}"
                                                data-hour="{{ $hourGroup['label'] }}"
                                            >
                                            Todos
                                        </label>

                                        <div class="space-y-2">
                                            @foreach($hourGroup['slots'] as $slot)
                                                @php
                                                    $value = $day . '|' . $slot;
                                                    $slotEnd = \Carbon\Carbon::createFromFormat('H:i', $slot)->addMinutes(15)->format('H:i');
                                                @endphp
                                                <label class="flex items-center gap-2 text-sm text-gray-700">
                                                    <input
                                                        type="checkbox"
                                                        name="slots[]"
                                                        value="{{ $value }}"
                                                        data-day="{{ $day }}"
                                                        data-hour="{{ $hourGroup['label'] }}"
                                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 js-slot"
                                                        @checked(in_array($value, old('slots', $existingSlots), true))
                                                    >
                                                    {{ $slot }} - {{ $slotEnd }}
                                                </label>
                                            @endforeach
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @error('slots')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
                @error('slots.*')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </x-wire-card>

            <div class="flex justify-end mt-4">
                <x-wire-button type="submit">
                    Guardar horario
                </x-wire-button>
            </div>
        </form>
    </div>

    @push('modals')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.js-toggle-hour').forEach(function (toggle) {
                    toggle.addEventListener('change', function () {
                        const day = this.dataset.day;
                        const hour = this.dataset.hour;
                        const checked = this.checked;

                        document.querySelectorAll('.js-slot[data-day="' + day + '"][data-hour="' + hour + '"]')
                            .forEach(function (slot) {
                                slot.checked = checked;
                            });
                    });
                });
            });
        </script>
    @endpush
</x-admin-layout>
