<x-admin-layout
    title="Consulta"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Citas', 'href' => route('admin.appointments.index')],
        ['name' => 'Consulta'],
    ]"
>
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $appointment->patient?->name ?? 'Paciente' }}</h1>
                    <p class="text-sm text-gray-600">Doctor: {{ $appointment->doctor?->user?->name ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-600">Fecha: {{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }} | {{ substr($appointment->start_time, 0, 5) }} - {{ substr($appointment->end_time, 0, 5) }}</p>
                </div>

                <div class="flex gap-2">
                    <button type="button" onclick="document.getElementById('patientHistoryModal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 font-medium rounded-lg border-2 border-current text-gray-700 hover:bg-gray-50 transition duration-150">
                        <i class="fa-solid fa-notes-medical mr-2"></i>
                        Ver Historia
                    </button>
                    <button type="button" onclick="document.getElementById('previousConsultationsModal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 font-medium rounded-lg border-2 border-current text-gray-700 hover:bg-gray-50 transition duration-150">
                        <i class="fa-solid fa-clock-rotate-left mr-2"></i>
                        Consultas Anteriores
                    </button>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.appointments.consultation.store', $appointment) }}" method="POST" class="bg-white rounded-lg shadow-md p-6 space-y-6">
            @csrf

            <!-- Tabs -->
            <div class="border-b border-gray-200 mb-4">
                <nav class="-mb-px flex space-x-6">
                    <button type="button" onclick="showTab('consultation')" class="consultation-tab pb-2 text-sm font-medium border-b-2 text-blue-600 border-blue-600 transition duration-150">
                        <i class="fa-solid fa-stethoscope mr-1"></i> Consulta
                    </button>
                    <button type="button" onclick="showTab('prescription')" class="prescription-tab pb-2 text-sm font-medium border-b-2 text-gray-500 border-transparent hover:border-gray-300 transition duration-150">
                        <i class="fa-solid fa-prescription-bottle-medical mr-1"></i> Receta
                    </button>
                </nav>
            </div>

            <!-- Consultation Tab -->
            <div id="consultation-tab" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Diagnostico</label>
                    <textarea name="diagnosis" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" placeholder="Describa el diagnostico del paciente aqui...">{{ $appointment->diagnosis }}</textarea>
                    @error('diagnosis')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tratamiento</label>
                    <textarea name="treatment" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" placeholder="Describa el tratamiento recomendado aqui...">{{ $appointment->treatment }}</textarea>
                    @error('treatment')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notas</label>
                    <textarea name="consultation_notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" placeholder="Agregue notas adicionales sobre la consulta...">{{ $appointment->consultation_notes }}</textarea>
                    @error('consultation_notes')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Prescription Tab -->
            <div id="prescription-tab" class="hidden space-y-3">
                <div id="prescriptions-container">
                    @forelse(($appointment->prescriptions ?? []) as $index => $prescription)
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end rounded-lg border border-gray-200 p-3 prescription-row">
                            <div class="md:col-span-5">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Medicamento</label>
                                <input type="text" name="prescriptions[{{ $index }}][medicine]" value="{{ $prescription['medicine'] ?? '' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Ej. Amoxicilina 500mg">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Dosis</label>
                                <input type="text" name="prescriptions[{{ $index }}][dose]" value="{{ $prescription['dose'] ?? '' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="1 cada 8h">
                            </div>
                            <div class="md:col-span-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Frecuencia / Duracion</label>
                                <input type="text" name="prescriptions[{{ $index }}][frequency]" value="{{ $prescription['frequency'] ?? '' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Cada 8 horas por 7 dias">
                            </div>
                            <div class="md:col-span-1">
                                <button type="button" onclick="removePrescription(this)" class="inline-flex items-center justify-center w-10 h-10 rounded bg-red-500 hover:bg-red-600 text-white">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end rounded-lg border border-gray-200 p-3 prescription-row">
                            <div class="md:col-span-5">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Medicamento</label>
                                <input type="text" name="prescriptions[0][medicine]" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Ej. Amoxicilina 500mg">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Dosis</label>
                                <input type="text" name="prescriptions[0][dose]" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="1 cada 8h">
                            </div>
                            <div class="md:col-span-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Frecuencia / Duracion</label>
                                <input type="text" name="prescriptions[0][frequency]" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Cada 8 horas por 7 dias">
                            </div>
                            <div class="md:col-span-1">
                                <button type="button" onclick="removePrescription(this)" class="inline-flex items-center justify-center w-10 h-10 rounded bg-red-500 hover:bg-red-600 text-white">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforelse
                </div>

                <button type="button" onclick="addPrescription()" class="inline-flex items-center px-4 py-2 font-medium rounded-lg border-2 border-current text-gray-700 hover:bg-gray-50 transition duration-150">
                    <i class="fa-solid fa-plus mr-2"></i>
                    Anadir medicamento
                </button>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 font-medium rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition duration-150">
                    <i class="fa-solid fa-floppy-disk mr-2"></i>
                    Guardar Consulta
                </button>
            </div>
        </form>
    </div>

    <!-- Patient History Modal -->
    <div id="patientHistoryModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-3xl">
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Historia medica del paciente</h3>
                <button type="button" onclick="document.getElementById('patientHistoryModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="px-6 py-4 grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Tipo de sangre</p>
                    <p class="font-semibold text-gray-900">{{ $appointment->patient?->bloodType?->name ?? 'No registrado' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Alergias</p>
                    <p class="font-semibold text-gray-900">{{ $appointment->patient?->known_allergies ?: 'No registradas' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Enfermedades cronicas</p>
                    <p class="font-semibold text-gray-900">{{ $appointment->patient?->chronic_diseases ?: 'No registradas' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Antecedentes quirurgicos</p>
                    <p class="font-semibold text-gray-900">{{ $appointment->patient?->surgical_history ?: 'No registrados' }}</p>
                </div>
            </div>

            <div class="px-6 py-4 border-t text-right">
                <a href="{{ route('admin.patients.show', $appointment->patient_id) }}" class="text-blue-600 hover:text-blue-700 font-semibold">
                    Ver / Editar Historia Medica
                </a>
            </div>
        </div>
    </div>

    <!-- Previous Consultations Modal -->
    <div id="previousConsultationsModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[85vh] overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Consultas Anteriores</h3>
                <button type="button" onclick="document.getElementById('previousConsultationsModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="p-4 space-y-3 overflow-y-auto max-h-[70vh]">
                @forelse($previousConsultations as $previous)
                    <article class="rounded-lg border border-gray-200 p-4">
                        <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                            <p class="font-semibold text-gray-900">
                                <i class="fa-regular fa-calendar mr-1 text-blue-600"></i>
                                {{ \Carbon\Carbon::parse($previous->date)->format('d/m/Y') }} a las {{ substr($previous->start_time, 0, 5) }}
                            </p>
                            <a href="{{ route('admin.appointments.show', $previous) }}" class="inline-flex items-center px-3 py-1 text-sm bg-blue-50 text-blue-700 border border-blue-200 rounded hover:bg-blue-100">
                                Consultar Detalle
                            </a>
                        </div>

                        <p class="text-sm text-gray-600 mb-2">Atendido por: {{ $previous->doctor?->user?->name ?? 'N/A' }}</p>

                        <div class="text-sm space-y-1 bg-gray-50 rounded p-3">
                            <p><span class="font-semibold text-gray-800">Diagnostico:</span> {{ $previous->diagnosis ?: 'Sin diagnostico registrado.' }}</p>
                            <p><span class="font-semibold text-gray-800">Tratamiento:</span> {{ $previous->treatment ?: 'Sin tratamiento registrado.' }}</p>
                            <p><span class="font-semibold text-gray-800">Notas:</span> {{ $previous->consultation_notes ?: 'Sin notas registradas.' }}</p>
                        </div>
                    </article>
                @empty
                    <p class="text-sm text-gray-600">No hay consultas anteriores registradas para este paciente.</p>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Hide all tabs
            document.getElementById('consultation-tab').classList.add('hidden');
            document.getElementById('prescription-tab').classList.add('hidden');

            // Remove active class from all tab buttons
            document.querySelectorAll('.consultation-tab, .prescription-tab').forEach(tab => {
                tab.classList.remove('text-blue-600', 'border-blue-600');
                tab.classList.add('text-gray-500', 'border-transparent');
            });

            // Show selected tab
            document.getElementById(tabName + '-tab').classList.remove('hidden');

            // Add active class to clicked button
            if (tabName === 'consultation') {
                document.querySelector('.consultation-tab').classList.add('text-blue-600', 'border-blue-600');
                document.querySelector('.consultation-tab').classList.remove('text-gray-500', 'border-transparent');
            } else {
                document.querySelector('.prescription-tab').classList.add('text-blue-600', 'border-blue-600');
                document.querySelector('.prescription-tab').classList.remove('text-gray-500', 'border-transparent');
            }
        }

        let prescriptionIndex = {{ count($appointment->prescriptions ?? []) }};

        function addPrescription() {
            const container = document.getElementById('prescriptions-container');
            const html = `
                <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end rounded-lg border border-gray-200 p-3 prescription-row">
                    <div class="md:col-span-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Medicamento</label>
                        <input type="text" name="prescriptions[${prescriptionIndex}][medicine]" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Ej. Amoxicilina 500mg">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dosis</label>
                        <input type="text" name="prescriptions[${prescriptionIndex}][dose]" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="1 cada 8h">
                    </div>
                    <div class="md:col-span-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Frecuencia / Duracion</label>
                        <input type="text" name="prescriptions[${prescriptionIndex}][frequency]" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Cada 8 horas por 7 dias">
                    </div>
                    <div class="md:col-span-1">
                        <button type="button" onclick="removePrescription(this)" class="inline-flex items-center justify-center w-10 h-10 rounded bg-red-500 hover:bg-red-600 text-white">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
            prescriptionIndex++;
        }

        function removePrescription(button) {
            button.closest('.prescription-row').remove();
        }
    </script>
</x-admin-layout>
